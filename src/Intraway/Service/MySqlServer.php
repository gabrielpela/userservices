<?php
namespace Intraway\Service;

use \Intraway\Service\AppContainer;
use \Illuminate\Database\Capsule\Manager;

class MySqlServer 
{

    /**
    * Mysql Connection install
    * @var \Illuminate\Database\ConnectionResolver
    */
    protected static $connection = null;

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
    * Create database connection and returns new or existing \Illuminate\Database\Capsule\Manager
    * @return \Illuminate\Database\Capsule\Manager
    */
    final public static function connect(){
        if(static::$connection !== null){
            return static::$connection;
        }

        //Load settings
        $container = AppContainer::get();
        $settings  = $container->get('settings')['db'];

        //Connect to DB
        static::$connection = new Manager;
        static::$connection->addConnection($settings);
        static::$connection->setAsGlobal();
        static::$connection->bootEloquent();

        return static::$connection;
    }

}