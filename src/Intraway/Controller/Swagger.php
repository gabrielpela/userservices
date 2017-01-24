<?php 
namespace Intraway\Controller;

/**
 * @SWG\Swagger(
 *     schemes={"http"},
 *     host="localhost",
 *     basePath="/",
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="Intraway User Profile Service",
 *         description="This is a user profile service application [https://github.com/gabrielpela/userservices] ",
 *         @SWG\Contact(
 *             email="gabrielpela@gmail.com"
 *         ),
 *     )
 * )
 */

class Swagger
{
    public function getJson(\Slim\Http\Request $request , \Slim\Http\Response $response , array $args){
        $json = \Swagger\scan($_SERVER["DOCUMENT_ROOT"]."/src/");
        $json->host = $_SERVER["HTTP_HOST"];
        return $response ->withJson($json);
    }
}