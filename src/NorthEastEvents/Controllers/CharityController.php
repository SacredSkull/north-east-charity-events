<?php
/**
 * Created by PhpStorm.
 * User: Peter
 * Date: 17/04/2016
 * Time: 21:50
 */

namespace NorthEastEvents\Controllers;

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
        return $this->render($req, $res, "/charities/create.html.twig", [

        ]);
    }

    public function CreateCharityPOST(Request $req, Response $res, $args){

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