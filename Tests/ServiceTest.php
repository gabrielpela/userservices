<?php
namespace Intraway\Tests;

class ServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * idUser for testing
     * @var int
     */
    protected static $idUser = null;

    /**
     * Testing insert user
     */
    public function testInsert(){   

        $url            = $this->getHost().'/user';
        $query['name']  = "John Anderson";
        $query['email'] = "johnanderson@gmail.com";

        $client   = new \GuzzleHttp\Client();
        $response = $client->request('POST', $url , ['query' => http_build_query($query)]);

        //Check Status 200
        $this->assertEquals(200, $response->getStatusCode());

        //Check userId, name and email; 
        $body = (string) $response->getBody();
        $user = json_decode($body);
        $this->assertInternalType("int", $user->idUser);
        $this->assertEquals($query['name'], $user->name);
        $this->assertEquals($query['email'], $user->email);

        static::$idUser = $user->idUser;
    }

    /**
     * Testing update user
     */
    public function testUpdate(){
        $url            = $this->getHost().'/user/'.static::$idUser;
        $query['name']  = "John F. Anderson";
        $query['email'] = "johnfanderson@gmail.com";

        $client   = new \GuzzleHttp\Client();
        $response = $client->request('PUT', $url , ['query' => http_build_query($query)]);

        //Check Status 200
        $this->assertEquals(200, $response->getStatusCode());

        //Check userId, name and email; 
        $body = (string) $response->getBody();
        $user = json_decode($body);
        $this->assertEquals(static::$idUser, $user->idUser);
        $this->assertEquals($query['name'], $user->name);
        $this->assertEquals($query['email'], $user->email);

    }

    /**
     * Testing get and delete user
     */
    public function testGetDeleteGet(){
        //Get user
        $url      = $this->getHost().'/user/'.static::$idUser;
        $client   = new \GuzzleHttp\Client();
        $response = $client->request('GET', $url );
        //Check status 200
        $this->assertEquals(200, $response->getStatusCode());
        //Check userId 
        $body = (string) $response->getBody();
        $user = json_decode($body);
        $this->assertEquals(static::$idUser, $user->idUser);


        //Delete User
        $url      = $this->getHost().'/user/'.static::$idUser;
        $client   = new \GuzzleHttp\Client();
        $response = $client->request('DELETE', $url );
        //Check status 200
        $this->assertEquals(200, $response->getStatusCode());


        //Validate user not exist
        $url     = $this->getHost().'/user/'.static::$idUser;
        $client  = new \GuzzleHttp\Client();
        try {
            $response = $client->request('GET', $url );
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            //Check status 404
            $this->assertEquals(404, $e->getResponse()->getStatusCode());
        }
    }

    /**
     * Returns the host where requests are made
     * @return string 
     */
    public function getHost(){
        global $argv, $argc;
        return isset($argv[2]) ? $argv[2] : "http://127.0.0.1"; 
    }
}