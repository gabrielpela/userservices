<?php
namespace Intraway\Model; 

use \Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(required={"idUser", "name", "email"}, type="object", @SWG\Xml(name="User"))
 * 
 * @SWG\Property(
 *      property="idUser",
 *      type="integer",
 *      example="103"
 * )
 * @SWG\Property(
 *      property="name",
 *      type="string",
 *      example="John Anderson"
 * )
 * @SWG\Property(
 *      property="email",
 *      type="string",
 *      example="johnanderson@gmail.com"
 * )
 * @SWG\Property(
 *      property="image",
 *      type="object",
 *      @SWG\Property(property="url"    , type="string",  example="http://www.site.com/resources/000/000/00000000"),
 *      @SWG\Property(property="mime"   , type="string",  example="image/jpeg"),
 *      @SWG\Property(property="width"  , type="integer", example="600"),
 *      @SWG\Property(property="height" , type="integer", example="300"),
 *      @SWG\Property(property="size"   , type="integer", example="3452")
 * )
 */

class User extends Model 
{
    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'user';

    /**
     * The primary key associated with the model
     * @var string
     */
    protected $primaryKey = 'idUser';

    /**
     * disables timestamp columns
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Create a new User model instance.
     * @param array $attributes 
     */
    public function __construct(array $attributes = []){
      parent::__construct($attributes);
      \Intraway\Service\MysqlServer::connect();
    }

    /**
     * Get the User of a given array.
     * @return array 
     */
    public function getArray(){
      $data = $this->toArray();
      $data["image"] = $this->getImageInfo();
      return $data;
    }

    /**
     * Persist the user image
     * @param  \Slim\Http\UploadedFile $image Instance of UploadedFile
     * 
     */
    public function uploadImage(\Slim\Http\UploadedFile $image){
      $fileName = $this->getImagePath();
      $dirName = dirname($fileName);
      if(!is_dir($dirName)){
        mkdir($dirName,0755,true);
      }
      
      $image->moveTo($fileName);
    }

    /**
     * Returns an array with the information of the user image
     * @return array
     */
    public function getImageInfo(){
      $file = $this->getImagePath();
      if(!is_file($file)){
        return null;
      }
      $container = \Intraway\Service\AppContainer::get();
      $basePath  = "http://".$container->get('environment')->get("HTTP_HOST").$container->get('settings')['pathUpload'];
      
      $arrInfo = getimagesize($file);
      $arrImage["url"]    = $basePath.$this->formatImageUrl();
      $arrImage["mime"]   = $arrInfo["mime"];
      $arrImage["width"]  = $arrInfo[0];
      $arrImage["height"] = $arrInfo[1];
      $arrImage["size"]   = filesize($file);
      return $arrImage;
    }

    /**
     * Returns the path where the user image is located
     * @return string
     */
    public function getImagePath(){
      $container = \Intraway\Service\AppContainer::get();
      $basePath  = $container->get('settings')['pathRoot']."/".$container->get('settings')['pathUpload'];
      return $basePath.$this->formatImageUrl();
    }

    /**
     * Generates the structure of the user image
     * @return string
     */
    public function formatImageUrl(){
      $key = sprintf("%010d",$this->idUser);
      return substr($key,1,3)."/".substr($key,4,3)."/".$key;
    }

}