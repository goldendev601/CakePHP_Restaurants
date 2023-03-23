<?php
/**
 * Created by NagaRaj.V 
 * Date: 20/Jan/18
 * Time: 19:15 PM
 */
namespace V1\Controller;  

use RestApi\Controller\ApiController;

class RestaurantsController extends ApiController{

    public function initialize(){
     	parent::initialize(); // TODO: Change the autogenerated stub
        $http = (isset($_SERVER["HTTPS"])) ? 'https://' : 'http://';
        $this->BaseUrl   = $http.$_SERVER['HTTP_HOST'].'/foodlove/';
    }

    public function index(){
    	$this->httpStatusCode = 200;
        $this->apiResponse['you_response'] = 'Invalid option';        
    }
}
