<?php
/**
 * Created by PhpStorm.
 * User: Peter
 * Date: 17/04/2016
 * Time: 21:50
 */

namespace NorthEastEvents\Controllers;

use NorthEastEvents\Models\Charity;
use NorthEastEvents\Models\CharityQuery;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


class CharityController extends Controller {
    public function CharitiesList(Request $req, Response $res, $args){
        return $this->render($req, $res, "/charities/charities.html.twig", [
           'charities' => CharityQuery::create()->find()
        ]);
    }
    
    public function CreateCharityGET(Request $req, Response $res, $args){
        if($this->current_user == null || !$this->current_user->isAdmin()){
            return $this->Unauthorised("This page requires authentication.", $req, $res, $args);
        }

        return $this->render($req, $res, "/charities/create.html.twig", [

        ]);
    }

    public function CreateCharityPOST(Request $req, Response $res, $args){
        $flash = $this->ci->get("flash");
        $router = $this->ci->get("router");

        if($this->current_user == null || !$this->current_user->isAdmin()){
            return $this->Unauthorised("This page requires authentication.", $req, $res, $args);
        }

        $name = $req->getParsedBody()['name'] ?? null;
        $bio = $req->getParsedBody()['bio'] ?? null;
        $logo = $req->getParsedBody()['logo'] ?? null;

        $previousDetails = [
            "name" => $name,
            "logo" => $logo,
            "bio" => $bio,
        ];

        $failure = false;
        if($name == null || strlen($name) < 4 || strlen($name) > 50){
            $flash->addMessageNow("Error", "Bad name format|Charity names should be between 4 and 50 characters.");
            $failure = true;
        }

        if($bio == null || strlen($bio) < 50 || strlen($bio) > 1500){
            $flash->addMessageNow("Error", "Bad bio format|Charity bios should be between 50 and 1500 characters.");
            $failure = true;
        }


        if($failure){
            return $this->render($req, $res, "/charities/create.html.twig", [
                "previous_details" => $previousDetails,
            ]);
        }

        $charity = new Charity();
        $charity->setName($name)->setBio($bio);

        if($logo != null){
            $charity->setLogo($logo);
        }

        $flash->addMessageNow("Success", sprintf("New charity added|.%s can now be used as ", $charity->getName()));
        $charity->save();

        return $res->withHeader("Location", $router->pathFor("Home"));
    }
    
    public function CharityOperations(Request $req, Response $res, $args){
        $charity = CharityQuery::create()->findOneById($args["charityID"] ?? null);
        if($charity == null){
            return $this->NotFound("Could not find a charity with the provided information.", $req, $res, $args);
        }

        return $this->render($req, $res, "/charities/charity.html.twig", [
            'charity' => $charity
        ]);
    }
}