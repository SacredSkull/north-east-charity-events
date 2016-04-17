<?php
namespace NorthEastEvents\Controllers;

use Interop\Container\ContainerInterface;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

abstract class Controller implements ResourceInterface {
    public $page_title = "Unknown";
    public $resource_type = "Unknown";
    public $not_found_message = "Resource could not be found.";
    public $unauthorised_message = "You do not have access to this resource.";
    public $not_allowed_message = "This resource does not allow that method.";
    public $generic_error = "An error occurred when trying to process your request.";

    protected $ci;
    protected $current_user = null;

    public function __construct(ContainerInterface $ci) {
        $this->ci = $ci;
        $this->current_user = $this->ci->get("session")->getSegment('NorthEastEvents\Login')->get("user", null);
    }

    public function render(Request $req, Response $res, string $template, array $vars = []){
        return $this->ci->get("view")->render($res, $template, array_merge([
            "page_title" => $this->page_title,
            "current_user" => $this->current_user,
            "resource_type" => $this->resource_type,
            "route" => $req->getAttribute('route')->getName(),
            "flashes" => $this->ci->get("flash")->getMessages()
        ], $vars));
    }

    public function sendEmail(string $to, string $subject, string $message, $additional_headers = null, $additional_params = null){
        $headers = sprintf("From: no-reply@%s\r\n", $_SERVER['HTTP_HOST']);
        $headers .= sprintf("Reply-To: no-reply@%s\r\n\r\n", $_SERVER['HTTP_HOST']);
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
        if($additional_headers != null)
            $headers .= $additional_headers;

        $head = "<html><body style='font-family: \"Helvetica Neue\", Helvetica, Arial, sans-serif'>";
        $footer = "</body></html>";
        $message = wordwrap($head . $message . $footer, 70);
        mail($to, $subject, $message, $additional_headers, $additional_params);
    }

    public function NotFound(string $message = null, Request $request, Response $response, array $args){
        $this->page_title = "Not found";
        if(!($message == null || strlen($message) == 0)){
            $message = $this->not_found_message;
        }
        return $this->render($request, $response, "/errors/404.html.twig", [
            "error_message" => $message,
            "error_code" => 404
        ])->withStatus(404);
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

        return $this->render($request, $response, "/errors/403.html.twig", [
            "error_message" => $message,
            "error_code" => 403
        ])->withStatus(403);
    }

    public function APIUnauthorised(string $message = null, Request $request, Response $response, array $args){
        if(!($message == null || strlen($message) == 0)){
            $message = $this->not_found_message;
        }
        return $response->withJson([ "Error" => [ "Message" => $message] ], 403);
    }

    public function NotAllowed(string $message = null, Request $request, Response $response, array $args){
        $this->page_title = "Not allowed";
        if(!($message == null || strlen($message) == 0)){
            $message = $this->not_allowed_message;
        }
        return $this->ci->get("view")->render($response, "/errors/405.html.twig", $this->renderVariables([
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
        return $this->ci->get("view")->render($response, "/errors/error.html.twig", $this->renderVariables([
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