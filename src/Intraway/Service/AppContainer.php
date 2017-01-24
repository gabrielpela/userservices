<?php 
namespace Intraway\Service;

use \Interop\Container\ContainerInterface;
use \Monolog\Logger;
use \Monolog\Handler\StreamHandler;

class AppContainer 
{

    /**
    * Instance of slim Container
    * @var \Interop\Container\ContainerInterface
    */
    private static $container;

    /**
    * Prevent direct object creation
    */
    final private function  __construct()
    {
    }

    /**
    * Prevent object cloning
    */
    final private function  __clone()
    {
    }

    /**
    * Assign the application container
    * @param \Interop\Container\ContainerInterface $container Instance of slim Container
    */
    static public function set(ContainerInterface $container){
        static::$container = $container;
        self::setLogger();
    }

    /**
    * Configures the container with the logger instance
    */
    static protected function setLogger(){
        static::$container['logger'] = function (ContainerInterface $container) {
            $settings = $container->get('settings')['logger'];
            $logger = new Logger($settings['name']);
            $logger->pushHandler(new StreamHandler($settings['path'], $settings['level']));
            return $logger;
        };
    }

   /**
    * Configures the error handler
    */
    static protected function setErrorHandler(){
        static::$container['errorHandler'] = function ($container) {
            return function ($request, $response, $exception) use ($container) {
                static::$container->get('logger')->error($exception->getMessage(),$exception->getTrace());
                throw $exception;
            };
        };

        static::$container['phpErrorHandler'] = function ($container) {
            return function ($request, $response, $error) use ($container) {
                static::$container->get('logger')->error($error,debug_backtrace());
                throw new Exception($error);
            };
        };
    }

    /**
    * Get application container
    * @return \Interop\Container\ContainerInterface $container Instance of slim Container
    */
    static public function get(){
        return static::$container ;
    }
}