<?php
namespace Intraway\Controller;

use \Intraway\Service\AppContainer;
use \Slim\Http\Request;
use \Slim\Http\Response;
use \Intraway\Model\User as ModelUser;
use \Intraway\ApiResponse;

class User 
{
  
   /**
    * @SWG\Get(
    *     path="/user/{idUser}",
    *     summary="Find user by id",
    *     tags={"User"},
    *     produces={"application/json"},
    *     @SWG\Parameter(
    *         name="idUser",
    *         in="path",
    *         description="User id",
    *         required=true,
    *         type="integer",
    *         @SWG\Items(type="integer")
    *     ),
    *     @SWG\Response(
    *         response=200,
    *         description="successful operation",
    *         @SWG\Schema(ref="#/definitions/User")
    *     ),
    *     @SWG\Response(
    *         response="404",
    *         description="User not found",
    *     )
    *  )
    * 
    * @param  Slim\Http\Request $request   [description]
    * @param  Slim\Http\Response $response [description]
    * @param  array $args                  [description]
    * @return Slim\Http\Response           [description]
    */
    public function get(Request $request, Response $response, array $args)
    {
        $container = AppContainer::get();
        $container->get('logger')->info("Access route: ".$request->getMethod()." - ".$request->getAttribute('route')->getPattern(),$args);

        //Find user
        try{
            $userTable = new ModelUser();
            $user      = $userTable->find($args["idUser"]);
        }catch(\Exception $e){
            $container->get('logger')->critical("Error find user: ".$e->getMessage(),$e->getTrace());
            return $response->withStatus(500);
        }

        //Return 404 if user not exist
        if(empty($user)){
            return $response->withStatus(404);
        }

        return $response->withJson($user->getArray());
    }

  /**
    * @SWG\Put(
    *     path="/user/{idUser}",
    *     summary="Update an existing user",
    *     tags={"User"},
    *     produces={"application/json"},
    *     @SWG\Parameter(
    *         name="idUser",
    *         in="path",
    *         description="User id",
    *         required=true,
    *         type="integer",
    *         @SWG\Items(type="integer")
    *     ),
    *     @SWG\Parameter(
    *         name="name",
    *         in="query",
    *         description="User name",
    *         required=false,
    *         type="string",
    *         @SWG\Items(type="string")
    *     ),
    *     @SWG\Parameter(
    *         name="email",
    *         in="query",
    *         description="User Email",
    *         required=false,
    *         type="string",
    *         @SWG\Items(type="string")
    *     ),
    *     @SWG\Response(
    *         response=200,
    *         description="successful operation",
    *         @SWG\Schema(ref="#/definitions/User")
    *     ),
    *     @SWG\Response(
    *         response="404",
    *         description="User not found",
    *     )
    *  )
    * 
    * @param  Slim\Http\Request $request   [description]
    * @param  Slim\Http\Response $response [description]
    * @param  array $args                  [description]
    * @return Slim\Http\Response           [description]
    */
    public function update(Request $request, Response $response, array $args)
    {
        $container = AppContainer::get();
        $container->get('logger')->info("Access route: ".$request->getMethod()." - ".$request->getAttribute('route')->getPattern(),$args);

        //Find user
        try{
            $userTable = new ModelUser();
            $user      = $userTable->find($args["idUser"]);
        }catch(\Exception $e){
            $container->get('logger')->critical("Error find user: ".$e->getMessage(),$e->getTrace());
            return $response->withStatus(500);
        }

        //Return 404 if user not exist
        if(empty($user)){
            return $response->withStatus(404);
        }

        //Set user data from request
        $user->name  = trim($request->getQueryParam("name",""));
        $user->email = trim($request->getQueryParam("email",""));

        //Save data
        try{
            $user->save();
        }catch(\Exception $e){
            $container->get('logger')->critical("Error update user: ".$e->getMessage(),$e->getTrace());
            return $response->withStatus(500);
        }
        return $response->withJson($user->getArray());
    }

  /**
    * @SWG\Post(
    *     path="/user",
    *     summary="Add a new user",
    *     tags={"User"},
    *     produces={"application/json"},
    *     @SWG\Parameter(
    *         name="name",
    *         in="query",
    *         description="User name",
    *         required=false,
    *         type="string",
    *         @SWG\Items(type="string")
    *     ),
    *     @SWG\Parameter(
    *         name="email",
    *         in="query",
    *         description="User Email",
    *         required=false,
    *         type="string",
    *         @SWG\Items(type="string")
    *     ),
    *     @SWG\Response(
    *         response=200,
    *         description="successful operation",
    *         @SWG\Schema(ref="#/definitions/User")
    *     ),
    *  )
    * 
    * @param  Slim\Http\Request $request   [description]
    * @param  Slim\Http\Response $response [description]
    * @param  array $args                  [description]
    * @return Slim\Http\Response           [description]
    */
    public function insert(Request $request, Response $response, array $args)
    {
        $container = AppContainer::get();
        $container->get('logger')->info("Access route: ".$request->getMethod()." - ".$request->getAttribute('route')->getPattern(),$args);

        //Create user
        try{
            $user = new ModelUser();
        }catch(\Exception $e){
            $container->get('logger')->critical("Error create user instance: ".$e->getMessage(),$e->getTrace());
            return $response->withStatus(500);
        }

        //Set user data from request
        $user->name  = trim($request->getQueryParam("name",""));
        $user->email = trim($request->getQueryParam("email",""));

        //Save data
        try{
            $user->save();
        }catch(\Exception $e){
            $container->get('logger')->critical("Error on save user: ".$e->getMessage(),$e->getTrace());
            return $response->withStatus(500);
        }

        return $response->withJson($user->getArray());
    }

   /**
    * @SWG\Post(
    *     path="/user/{idUser}/image",
    *     summary="Add image to user",
    *     tags={"User"},
    *     produces={"application/json"},
    *     @SWG\Parameter(
    *         name="idUser",
    *         in="path",
    *         description="User id",
    *         required=true,
    *         type="integer",
    *         @SWG\Items(type="integer")
    *     ),
    *     @SWG\Parameter(
    *         name="image",
    *         in="formData",
    *         description="User image",
    *         required=true,
    *         type="file",
    *         @SWG\Items(type="string")
    *     ),
    *     @SWG\Response(
    *         response=200,
    *         description="successful operation",
    *         @SWG\Schema(ref="#/definitions/User")
    *     ),
    *     @SWG\Response(
    *         response="404",
    *         description="User not found",
    *     ),
    *     @SWG\Response(
    *         response="415",
    *         description="Unsupported file type",
    *     )
    *  )
    * 
    * @param  Slim\Http\Request $request   [description]
    * @param  Slim\Http\Response $response [description]
    * @param  array $args                  [description]
    * @return Slim\Http\Response           [description]
    */
    public function insertImage(Request $request, Response $response, array $args)
    {
        $container = AppContainer::get();
        $container->get('logger')->info("Access route: ".$request->getMethod()." - ".$request->getAttribute('route')->getPattern(),$args);

        //Find user
        try{
            $userTable = new ModelUser();
            $user      = $userTable->find($args["idUser"]);
        }catch(\Exception $e){
            $container->get('logger')->critical("Error find user: ".$e->getMessage(),$e->getTrace());
            return $response->withStatus(500);
        }

        //Return 404 if user not exist
        if(empty($user)){
            return $response->withStatus(404);
        }

        $files = $request->getUploadedFiles();
        if(empty($files['image'])){
            return $response->withStatus(415);
        }
        

        //check error upload
        if($files['image']->getError()){
            $container->get('logger')->critical("Error upload image: Code ".$files['image']->getError(), (array) $files['image']);
            return $response->withStatus(500);
        }

        //Check image type
        $imageInfo = getimagesize($files['image']->file);
        if(!$imageInfo){
            return $response->withStatus(415);
        }

        //Upload image
        try{
            $user->uploadImage($files['image']);
        }catch(\Exception $e){
            $container->get('logger')->critical("Error Save image: ".$e->getMessage(),$e->getTrace());
            return $response->withStatus(500);
        }
        return $response->withJson($user->getArray());
    }

   /**
    * @SWG\Delete(
    *     path="/user/{idUser}",
    *     summary="Delete an existing user",
    *     tags={"User"},
    *     produces={"application/json"},
    *     @SWG\Parameter(
    *         name="idUser",
    *         in="path",
    *         description="User id",
    *         required=true,
    *         type="integer",
    *         @SWG\Items(type="integer")
    *     ),
    *     @SWG\Response(
    *         response=200,
    *         description="successful operation",
    *         @SWG\Schema(ref="#/definitions/ApiResponse")
    *     ),
    *     @SWG\Response(
    *         response="404",
    *         description="User not found",
    *     )
    *  )
    * 
    * @param  Slim\Http\Request $request   [description]
    * @param  Slim\Http\Response $response [description]
    * @param  array $args                  [description]
    * @return Slim\Http\Response           [description]
    */
    public function delete($request, $response, $args){
        $container = AppContainer::get();
        $container->get('logger')->info("Access route: ".$request->getMethod()." - ".$request->getAttribute('route')->getPattern(),$args);

        //Find user
        try{
            $userTable = new ModelUser();
            $user      = $userTable->find($args["idUser"]);
        }catch(\Exception $e){
            $container->get('logger')->critical("Error find user: ".$e->getMessage(),$e->getTrace());
            return $response->withStatus(500);
        }

        //Return 404 if user not exist
        if(empty($user)){
            return $response->withStatus(404);
        }

        //Save data
        try{
            $user->delete();
        }catch(\Exception $e){
            $container->get('logger')->critical("Error update user: ".$e->getMessage(),$e->getTrace());
            return $response->withStatus(500);
        }

        $apiResponse = new ApiResponse();
        $apiResponse->code    = 200;
        $apiResponse->message = "removed";
        return $response->withJson($apiResponse);
    }
}