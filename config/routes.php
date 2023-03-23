<?php
use Cake\Core\Plugin;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

Router::defaultRouteClass(DashedRoute::class);

Router::scope('/', function (RouteBuilder $routes) {
    
	//Home Page
    $routes->connect('/', ['controller' => 'Users', 'action' => 'index']);


    $routes->connect('/pages/*', [
        'controller' => 'pages',
        'action' => 'getPages'
    ]);
	Router::prefix('hangryadmn', function($routes) {
        $routes->connect('/', [
            'controller' => 'Users',
            'action' => 'login'
        ]);

        $routes->connect('/dashboard', [
            'controller' => 'Users',
            'action' => 'dashboard'
        ]);

        $routes->connect('/changepassword', [
            'controller' => 'Users',
            'action' => 'changepassword'
        ]);

        $routes->connect('/dispatch', [
            'controller' => 'Dispatches',
            'action' => 'index'
        ]);

        $routes->connect('/reviews', [
            'controller' => 'Reviews',
            'action' => 'index'
        ]);

        $routes->fallbacks('InflectedRoute');
    });

	Router::prefix('restaurantadmin', function($routes) {
        $routes->connect('/', [
            'controller' => 'Users',
            'action' => 'login'
        ]);

        $routes->connect('/dashboard', [
            'controller' => 'Users',
            'action' => 'dashboard'
        ]);

        $routes->connect('/changepassword', [
            'controller' => 'Users',
            'action' => 'changepassword'
        ]);
        
        $routes->connect('/reviews', [
            'controller' => 'Reviews',
            'action' => 'index'
        ]);

        $routes->connect('/dispatch', [
            'controller' => 'Dispatches',
            'action' => 'index'
        ]);

        $routes->fallbacks('InflectedRoute');
    });
   
    $routes->connect('/restaurantSignup', [
        'controller' => 'Users',
        'action' => 'restaurantSignup'
    ]);

    $routes->connect('/refer-and-earn', [
        'controller' => 'Customers',
        'action' => 'restaurantSignup'
    ]);

    $routes->connect('/thanks/*', [
        'controller' => 'Users',
        'action' => 'thanks'
    ]);

    $routes->connect('/menu/*', [
        'controller' => 'Menus',
        'action' => 'index'
    ]);
    $routes->connect('/pizzamenus/*', [
        'controller' => 'Menus',
        'action' => 'index'
    ]);

    //-----Customer side Mobile Api response -----//       

    $routes->connect('/v1/CustomerSignUp',[
        'controller' => 'Customers',
        'action' => 'signup',
        'plugin' => 'V1'
    ]);
    
    $routes->connect('/v1/CustomerLogin',[
        'controller' => 'Customers',
        'action' => 'login',
        'plugin' => 'V1'
    ]);

    $routes->connect('/v1/ForgetPassword',[
        'controller' => 'Customers',
        'action' => 'forgetPassword',
        'plugin' => 'V1'
    ]);

    $routes->connect('/v1/MyAccount',[
        'controller' => 'Customers',
        'action' => 'myAccount',
        'plugin' => 'V1'
    ]);


    $routes->connect('/v1/Searches',[
        'controller' => 'Customers',
        'action' => 'searches',
        'plugin' => 'V1'
    ]);

    $routes->connect('/v1/MenuDetails',[
        'controller' => 'Customers',
        'action' => 'menuDetails',
        'plugin' => 'V1'
    ]);

    $routes->connect('/v1/GetLocation',[
        'controller' => 'Customers',
        'action' => 'getLocation',
        'plugin' => 'V1'
    ]);

    $routes->connect('/v1/BookaTable',[
        'controller' => 'Customers',
        'action' => 'bookaTable',
        'plugin' => 'V1'
    ]);

    $routes->connect('/v1/Checkout',[
        'controller' => 'Customers',
        'action' => 'checkOut',
        'plugin' => 'V1'
    ]);

    $routes->connect('/v1/PlaceOrder',[
        'controller' => 'Customers',
        'action' => 'placeOrder',
        'plugin' => 'V1'
    ]);

    $routes->connect('/v1/RestaurantDetails',[
        'controller' => 'Customers',
        'action' => 'restaurantDetails',
        'plugin' => 'V1'
    ]);

    $routes->connect('/v1/ProductDetails',[
        'controller' => 'Customers',
        'action' => 'productDetails',
        'plugin' => 'V1'
    ]);

    $routes->connect('/v1/ProductSubAddon',[
        'controller' => 'Customers',
        'action' => 'productSubAddon',
        'plugin' => 'V1'
    ]);

    $routes->connect('/v1/siteSettings',[
        'controller' => 'Customers',
        'action' => 'siteSettings',
        'plugin' => 'V1'
    ]);

    $routes->connect('/v1/SocialLogin',[
        'controller' => 'Customers',
        'action' => 'SocialLogin',
        'plugin' => 'V1'
    ]);

    $routes->connect('/v1/getRewards',[
        'controller' => 'Customers',
        'action' => 'getRewards',
        'plugin' => 'V1'
    ]);

    $routes->connect('/v1/rewardHistory',[
        'controller' => 'Customers',
        'action' => 'rewardHistory',
        'plugin' => 'V1'
    ]);


    
    $routes->extensions(['pdf']);

    //-----Driver Api response-----//

    $routes->connect('/v1/driverlogin',[
        'controller' => 'Drivers',
        'action' => 'login',
        'plugin' => 'V1'
    ]);
    $routes->connect('/v1/driversignup',[
        'controller' => 'Drivers',
        'action' => 'signup',
        'plugin' => 'V1'
    ]);
    $routes->connect('/v1/driverimageUpload',[
        'controller' => 'Drivers',
        'action' => 'imageUpload',
        'plugin' => 'V1'
    ]);
    $routes->connect('/v1/driverdetails',[
        'controller' => 'Drivers',
        'action' => 'details',
        'plugin' => 'V1'
    ]);
    $routes->connect('/v1/driverupdate',[
        'controller' => 'Drivers',
        'action' => 'update',
        'plugin' => 'V1'
    ]);
    $routes->connect('/v1/driverlocation',[
        'controller' => 'Drivers',
        'action' => 'location',
        'plugin' => 'V1'
    ]);
    $routes->connect('/v1/driverstatus',[
        'controller' => 'Drivers',
        'action' => 'status',
        'plugin' => 'V1'
    ]);
    $routes->connect('/v1/driverchangePassword',[
        'controller' => 'Drivers',
        'action' => 'changePassword',
        'plugin' => 'V1'
    ]);
    $routes->connect('/v1/driverlogout',[
        'controller' => 'Drivers',
        'action' => 'logout',
        'plugin' => 'V1'
    ]);
    $routes->connect('/v1/driverauthorized',[
        'controller' => 'Drivers',
        'action' => 'authorized',
        'plugin' => 'V1'
    ]);
    $routes->connect('/v1/drivertoken',[
        'controller' => 'Drivers',
        'action' => 'token',
        'plugin' => 'V1'
    ]);
    $routes->connect('/v1/driverorderDisclaim',[
        'controller' => 'Drivers',
        'action' => 'orderDisclaim',
        'plugin' => 'V1'
    ]);
    $routes->connect('/v1/driverorderDetail',[
        'controller' => 'Drivers',
        'action' => 'orderDetail',
        'plugin' => 'V1'
    ]);


    $routes->connect('/v1/MobileApi/request',[
        'controller' => 'MobileApi',
        'action' => 'request',
        'plugin' => 'V1'
    ]);

    $routes->connect('/v1/StoreMobileApi/requests',[
        'controller' => 'StoreMobileApi',
        'action' => 'request',
        'plugin' => 'V1'
    ]);

    $routes->extensions(['json', 'csv','pdf']);
    $routes->fallbacks(DashedRoute::class);

});
Plugin::routes();
