<?php

//Include autoload
require __DIR__.'/vendor/autoload.php';

//Read configuration and init Slim App
$settings = require __DIR__."/config/settings.php";
$app      = new \Slim\App($settings);

//Set container 
$container = $app->getContainer();
\Intraway\Service\AppContainer::set($container);

//Register the routes
$app->group('/user/{idUser:[0-9]+}', function () {
  $this->get('','\Intraway\Controller\User:get');
  $this->delete('','\Intraway\Controller\User:delete');
  $this->put('','\Intraway\Controller\User:update');
  $this->post('/image','\Intraway\Controller\User:insertImage');
});
$app->post('/user','\Intraway\Controller\User:insert');
$app->get('/swagger/json','\Intraway\Controller\Swagger:getJson');
//Init
$app->run();