<?php
/**
 * Created by PhpStorm.
 * User: Peter
 * Date: 08/04/2016
 * Time: 15:55
 */

namespace NorthEastEvents\Controllers;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

interface ResourceInterface {
    // 403
    public function Unauthorised(string $message = null, Request $req, Response $res, array $args);
    public function APIUnauthorised(string $message = null, Request $req, Response $res, array $args);

    // General error
    public function GenericError(string $page_title, string $message, Request $req, Response $res, array $args, int $code = 200);
    public function APIGenericError(string $message, Request $req, Response $res, array $args, int $code = 200);
}