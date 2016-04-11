<?php
namespace NorthEastEvents\Controllers;

use Interop\Container\ContainerInterface;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

abstract class Controller implements ResourceInterface {
    protected $ci;
    public $page_title = "Unknown";
    public $resource_type = "Unknown";
    public $not_found_message = null;
    public $unauthorised_message = null;
    public $not_allowed_message = null;
    public $generic_error = null;

    public function __construct(ContainerInterface $ci) {
        $this->ci = $ci;
    }

    public function renderVariables(array $additionalVariables){
        return array_merge([
            "page_title" => $this->page_title,
            "current_user" => $this->ci->get("session")->getSegment('NorthEastEvents\Login')->get("user", null),
            "resource_type" => $this->resource_type,
        ], $additionalVariables);
    }

    public function NotFound(string $message = null, Request $request, Response $response, array $args){
        $this->page_title = "Not found";
        if(!($message == null || strlen($message) == 0)){
            $message = $this->not_found_message;
        }
        return $this->ci->get("view")->render($response, "/errors/404.twig.html", $this->renderVariables([
            "error_message" => $message,
            "error_code" => 404
        ]))->withStatus(404);
    }

    public function APINotFound(string $message = null, Request $request, Response $response, array $args){
        if(!($message == null || strlen($message) == 0)){
            $message = $this->not_found_message;
        }
        return $response->withJson([ "Error" => [ "Message" => $message] ], 404);
    }

    public function Unauthorised(string $message = null, Request $request, Response $response, array $args){
        $this->page_title = "Not allowed";
        if($message == null || strlen($message) == 0 || $message == false){
            $message = $this->not_found_message;
        }
        return $this->ci->get("view")->render($response, "/errors/403.twig.html", $this->renderVariables([
            "error_message" => $message,
            "error_code" => 403
        ]))->withStatus(403);
    }

    public function APIUnauthorised(string $message = null, Request $request, Response $response, array $args){
        if(!($message == null || strlen($message) == 0)){
            $message = $this->not_found_message;
        }
        return $response->withJson([ "Error" => [ "Message" => $message] ], 403);
    }

    public function NotAllowed(string $message = null, Request $request, Response $response, array $args){
        $this->page_title = "Not found";
        if(!($message == null || strlen($message) == 0)){
            $message = $this->not_allowed_message;
        }
        return $this->ci->get("view")->render($response, "/errors/405.twig.html", $this->renderVariables([
            "error_message" => $message,
            "error_code" => 405
        ]))->withStatus(405);
    }

    public function APINotAllowed(string $message = null, Request $request, Response $response, array $args){
        if(!($message == null || strlen($message) == 0)){
            $message = $this->not_allowed_message;
        }
        return $response->withJson([ "Error" => [ "Message" => $message] ], 405);
    }

    public function GenericError(string $page_title, string $message, Request $request, Response $response,
                                        array $args, int $code = 200){
        $this->page_title = $page_title;
        if(!($message == null || strlen($message) == 0)){
            $message = $this->not_allowed_message;
        }
        return $this->ci->get("view")->render($response, "/errors/error.twig.html", $this->renderVariables([
            "error_message" => $message,
            "error_code" => $code
        ]))->withStatus($code);
    }

    public function APIGenericError(string $message, Request $request, Response $response, array $args,
                                           int $code = 200){
        if(!($message == null || strlen($message) == 0)){
            $message = $this->not_allowed_message;
        }
        return $response->withJson([ "Error" => [ "Message" => $message] ], $code);
    }
}