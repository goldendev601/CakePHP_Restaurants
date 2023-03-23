<?php
/**
 * Created by PhpStorm.
 * User: Nagaraj.V
 * Date: 08-04-2018
 * Time: 10:35
 */
namespace App\Controller\Restaurantadmin;

use Cake\Event\Event;
use App\Controller\AppController;
use Cake\Utility\Hash;




class RestaurantsController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->viewBuilder()->setLayout('restaurant');
        $this->loadComponent('Flash');
        $this->loadComponent('Googlemap');
        $this->loadComponent('Common');
        $this->loadComponent('Auth');

        $this->loadModel('Users');
        $this->loadModel('Areamaps');
        $this->loadModel('Restaurants');
        $this->loadModel('DeliverySettings');
        $this->loadModel('DeliveryLocations');
        $this->loadModel('Cuisines');
        $this->loadModel('States');
        $this->loadModel('Cities');
        $this->loadModel('Locations');
        $this->loadModel('Promotions');
        $this->loadModel('PaymentMethods');
        $this->loadModel('RestaurantPayments');
        $this->loadModel('Timezones');
        $this->loadModel('Meats');
        $this->loadModel('Veggies');
    }
//-----------------------------------------------------------------------------------------
    /*Ajaxaction For Get Latitude and Draw Radius*/
    public function ajaxaction() {

        if($this->request->getData('action') == 'getCircle') {

            $resid     = $this->request->getData('resid');
            $address   = $this->request->getData('address');
            $resname   = $this->request->getData('resname');
            $position  = $this->request->getData('circleCount');
            $color     = $this->Googlemap->getCircleColors($position);
            $miles     = $this->request->getData('miles') * 1000;
            $radius    = $this->request->getData('radius_count');
            $map_id    = 'map_canvas';
            $uniqueid  = 'Circle'.$position;
            $map['id'] = 1;
            $mapDetails = $this->Googlemap->getLatitudeLongitude($address);
            $latitude  = $mapDetails['lat'];
            $longitude = $mapDetails['long'];

            if ($radius != $position && !empty($resid)) {
                echo "<script>
                      {$uniqueid}Circle.setMap(null);
                 </script>";
            }
            $action = $this->request->getData('action');
            $this->set(compact('position', 'color', 'latitude', 'longitude', 'miles', 'resname','action','address', 'map'));
        }
        #--------------------------------------------------------------
        if($this->request->getData('action') == 'showMapAdd') {

            $Address = $this->request->getData('address');
            $mapDetails = $this->Googlemap->getLatitudeLongitude($Address);
            $latitude = $mapDetails['lat'];
            $longitude = $mapDetails['long'];
            $action = $this->request->getData('action');
            $this->set(compact('ResName', 'latitude', 'longitude', 'distance','action'));
        }
        #--------------------------------------------------------------
        if($this->request->getData('action') == 'showMapEdit') {
            $Address = $this->request->getData('address');
            $StoreId = $this->request->getData('resId');
            $mapDetails = $this->Googlemap->getLatitudeLongitude($Address);
            $latitude = $mapDetails['lat'];
            $longitude = $mapDetails['long'];

            $restDetails = $this->DeliverySettings->find('all', [
                'conditions' => [
                    'restaurant_id' => $this->request->getData('resId')
                ]
            ])->hydrate(false)->toArray();
            $action = $this->request->getData('action');
            $this->set(compact('ResName', 'latitude', 'longitude', 'distance','restDetails','action'));
        }
        #--------------------------------------------------------------
        if($this->request->getData('action') == 'showPolygonmap') {
            $address = $this->request->getData('address');
            $restaurant =   $this->Googlemap->getLatitudeLongitude($address);
            $latitude  = $restaurant['lat'];
            $longitude = $restaurant['long'];
            echo $latitude.'###'.$longitude;die();
        }
        #--------------------------------------------------------------
        if($this->request->getData('action') == 'removeCircle') {
            $this->autoRender = false;
            $radius  = 'Circle'.$this->request->getData('id');
            echo "<script>
                    {$radius}Circle.setMap(null);
                  </script>";
            die();
        }
        #--------------------------------------------------------------
        if($this->request->getData('action') == 'deleteStoreMap') {
            $id =   $this->request->getData('id');
            $entity = $this->Areamaps->get($id);
            if ($this->Areamaps->delete($entity)) {
                echo "success";
            }
            die();
        }
        #--------------------------------------------------------------
        if ($this->request->getData('action') == 'getCity') {
            $citylist = $this->Cities->find('list', [
                'keyField' => 'id',
                'valueField' => 'city_name',
                'conditions' => [
                    'status' => '1',
                    'state_id' => $this->request->getData('state_id')
                ],
            ])->hydrate(false)->toArray();

            $this->set('action', $this->request->getData('action'));
            $this->set('citylist', $citylist);
        }
        #--------------------------------------------------------------
        if ($this->request->getData('action') == 'getLocation') {
            if(SEARCHBY == 'area'){
                $locationlist = $this->Locations->find('list', [
                    'keyField' => 'id',
                    'valueField' => 'area_name',
                    'conditions' => [
                        'status' => '1',
                        'city_id' => $this->request->getData('city_id')
                    ],
                ])->hydrate(false)->toArray();
            }
            if(SEARCHBY == 'zip') {
                $locationlist = $this->Locations->find('list', [
                    'keyField' => 'id',
                    'valueField' => 'zip_code',
                    'conditions' => [
                        'status' => '1',
                        'city_id' => $this->request->getData('city_id')
                    ],
                ])->hydrate(false)->toArray();
            }
            $this->set('action', $this->request->getData('action'));
            $this->set('locationlist', $locationlist);
        }
    }



    

//-----------------------------------------------------------------------------------------
    /*Get Polygon list From Database*/
    public function getPolygonList() {

        $resId  =   $this->request->getData('resid');
        $this->autoRender = false;
        $this->request->getData('resid');
        $coordQuery  =   $this->Areamaps->find('all', [
            'fields' => [
                'id',
                'mapcoords',
                'colorcode'
            ],
            'conditions' => [
                'res_id' => $resId
            ],
            'order' => [
                'id' => 'asc'
            ]
        ])->hydrate(false)->toArray();
        $coords = '';
        foreach ($coordQuery as $key => $value) {
            $coords[] = $value;
        }

        echo '{"rows":'.json_encode($coords) .'}';
        exit();
    }
//-----------------------------------------------------------------------------------------
    /*Get Delivery Charge*/
    public function getCharge() {

        $id =   $this->request->getData('mapid');
        $charge    =   $this->Areamaps->find('all', [
            'conditions' => [
                'id' => $id
            ],
            'order' => [
                'id' => 'desc'
            ]
        ])->hydrate(false)->toArray();
        if(!empty($charge)) {
            echo $charge[0]['service_min_order'].'^^^'.$charge[0]['service_delivery_charge'];
        }
        exit();
    }
//-----------------------------------------------------------------------------------------
    /*Get Restaurant Details*/
    public function index() {
      
        $user = $this->Auth->user();
        $restDetails = $this->Restaurants->find('all', [
               'conditions' => [
                'user_id' => $user['id']
            ],
            'contain' => [
                'DeliverySettings',
                'Areamaps',
                'RestaurantPayments' => [
                    'PaymentMethods'
                ]
            ]
        ])->hydrate(false)->first();     

        $id  = $restDetails['id'];
         //echo "<pre>"; print_r($restDetails); die();

        $editPayMethod = [];
        if(!empty($restDetails['restaurant_payments'])){
            $editPayMethod = Hash::extract($restDetails['restaurant_payments'], '{n}.payment_id');
        }

        //PaymentMethods
        $paymentList = $this->PaymentMethods->find('all', [
            'fields' => [
                'id',
                'payment_method_name',
            ],
            'conditions' => [
                'id IS NOT NULL',
                'status' => '1'
            ]
        ])->hydrate(false)->toArray();


        //deliveryLocation
        $deliveryLocation = $this->DeliveryLocations->find('all', [
            'fields' => [
                'DeliveryLocations.id',
                'DeliveryLocations.restaurant_id',
                'DeliveryLocations.city_id',
                'DeliveryLocations.location_id',
                'DeliveryLocations.minimum_order',
                'DeliveryLocations.delivery_charge',
            ],
            'conditions' => [
                'DeliveryLocations.restaurant_id' => $id
            ],
            'contain' =>[
                'Cities' => [
                    'fields' => [
                        'Cities.id',
                        'Cities.city_name',
                    ]
                ],
                'Locations' =>[
                    'fields' => [
                        'Locations.id',
                        'Locations.area_name',
                        'Locations.zip_code',
                    ]
                ]
            ]
        ])->hydrate(false)->toArray();
        //echo "<pre>"; print_r($deliveryLocation); die();      

        //States Details
        $statelist = $this->States->find('list', [
            'keyField' => 'id',
            'valueField' => 'state_name',
            'conditions' => [
                'status' => 1
            ],
        ])->hydrate(false)->toArray();

        //City Details
        $citylist = $this->Cities->find('list', [
            'keyField' => 'id',
            'valueField' => 'city_name',
            'conditions' => [
                'status' => 1,
                'state_id' => $restDetails['state_id']
            ],
        ])->hydrate(false)->toArray();


        //Locations Details
        if(SEARCHBY == 'area'){
             $locationlist = $this->Locations->find('list', [
                'keyField' => 'id',
                'valueField' => 'area_name',
                'conditions' => [
                    'status' => 1,
                    'city_id' => $restDetails['city_id']
                ],
            ])->hydrate(false)->toArray();
        }
        if(SEARCHBY == 'zip'){
            $locationlist = $this->Locations->find('list', [
                'keyField' => 'id',
                'valueField' => 'zip_code',
                'conditions' => [
                    'status' => 1,
                    'city_id' => $restDetails['city_id']
                ],
            ])->hydrate(false)->toArray();
        }      


        if($restDetails['restaurant_cuisine'] != '') {
            $selectedCuisine = explode(',',$restDetails['restaurant_cuisine']);
        }else {
            $selectedCuisine = '';
        }

        if($restDetails['restaurant_timezone'] != '') {
            $selectedTimezone = explode(',',$restDetails['restaurant_timezone']);
        }else {
            $selectedTimezone = '';
        }

        // if($restDetails['restaurant_meats'] != '') {
        //     $selectedMeats = explode(',',$restDetails['restaurant_meats']);
        // }else {
        //     $selectedMeats = '';
        // }

        // if($restDetails['restaurant_veggies'] != '') {
        //     $selectedVeggies = explode(',',$restDetails['restaurant_veggies']);
        // }else {
        //     $selectedVeggies = '';
        // }

        $cuisinesList = $this->Cuisines->find('list',[
            'keyField' => 'id',
            'valueField' => 'cuisine_name',
            'conditions' => [
                'id IS NOT NULL',
                'status' => 1,
                'delete_status' => 'N'
            ]
        ])->hydrate(false)->toArray();

        $timezoneList = $this->Timezones->find('list',[
            'keyField' => 'id',
            'valueField' => 'timezone_name'            
        ])->hydrate(false)->toArray();

        // $meatList = $this->Meats->find('list',[
        //     'keyField' => 'id',
        //     'valueField' => 'meat_name'            
        // ])->hydrate(false)->toArray();

        // $veggiesList = $this->Veggies->find('list',[
        //     'keyField' => 'id',
        //     'valueField' => 'veggies_name'            
        // ])->hydrate(false)->toArray();

        $EditPromoImgList = $this->Promotions->find('all', [
            'conditions' => [
                'restaurant_id' => $id
            ]
        ])->toArray();

        $restDetails['fb_page_url'] = '';
        if(!empty($restDetails['fb_page_id'])) {
            $fb_page_url = $this->getPageInfo($restDetails['fb_page_id']);
            $restDetails['fb_page_url'] = $fb_page_url;
        }


        //Updating here
        if($this->request->is(['post','put'])) {
            //echo "<pre>"; print_r($this->request->getData()); die();

            $restEntity = $this->Restaurants->newEntity();
            $restEntity = $this->Restaurants->patchEntity($restEntity,$this->request->getData());

            $restEntity->monday_status = (!empty($this->request->getData('monday_status'))) 
                ? 'Close' : '';
            $restEntity->tuesday_status = (!empty($this->request->getData('tuesday_status'))) 
                ? 'Close' : '';
            $restEntity->wednesday_status = (!empty($this->request->getData('wednesday_status'))) 
                ? 'Close' : '';
            $restEntity->thursday_status = (!empty($this->request->getData('thursday_status'))) 
                ? 'Close' : '';
            $restEntity->friday_status = (!empty($this->request->getData('friday_status'))) 
                ? 'Close' : '';
            $restEntity->saturday_status = (!empty($this->request->getData('saturday_status'))) 
                ? 'Close' : '';
            $restEntity->sunday_status = (!empty($this->request->getData('sunday_status'))) 
                ? 'Close' : '';
            

            // Heartland API keys
            $restEntity->heartland_public_api_key = (!empty($this->request->getData('heartland_public_api_key')))
                ? $this->request->getData('heartland_public_api_key') : '';
            $restEntity->heartland_secret_api_key = (!empty($this->request->getData('heartland_secret_api_key')))
                ? $this->request->getData('heartland_secret_api_key') : '';

                
            $restEntity->id = $this->request->getData('resId');           
            $restEntity->minimum_pickup_time = $this->request->getData('minimum_pickup_time');


            //Get Userid From Restaurant Table
            $userDetails = $this->Restaurants->find('all', [
                'conditions' => [
                    'id' => $this->request->getData('resId')
                ]
            ])->hydrate(false)->first();

            if(isset($this->request->getData('restaurant_logo')['name']) &&
                !empty($this->request->getData('restaurant_logo')['name']) ){

                $restEntity->seo_url = $this->Common->seoUrl($this->request->getData('restaurant_name'));


                /*if(!file_exists(WWW_ROOT.'uploads'. DS.'storeImages'.DS.$this->request->getData('resId').DS.'storeLogo')) {
                    $this->Common->mkdir(WWW_ROOT.'uploads'. DS.'storeImages'.DS.$this->request->getData('resId').DS.'storeLogo');
                }*/
                $destinationPath = WWW_ROOT.'uploads'.DS.'storeLogos';

                if ($this->request->getData('restaurant_logo')['error'] == 0) {
                    $refFile = $this->Common->uploadFile($this->request->getData('restaurant_logo'),$destinationPath);
                    $restEntity->logo_name = $refFile['refName'];
                } else {
                    $restEntity->logo_name = $userDetails['logo_name'];
                }

            }else {
                $restEntity->logo_name = $userDetails['logo_name'];
            }

            $saveEntity = $this->Restaurants->save($restEntity);

            if($saveEntity) {

                //Get Latitude and Longitude
                if(!empty($this->request->getData('contact_address')) && (SEARCHBY == 'Google')){

                    $prepAddr = str_replace(' ','+',$this->request->getData('contact_address'));
                    $url = "https://maps.google.com/maps/api/geocode/json?address=$prepAddr&key=AIzaSyAql4yBAyykHUGfXRicgL5_1YH9-ZeWk3s&sensor=false&region=India";
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                    $response = curl_exec($ch);
                    curl_close($ch);
                    $response_a = json_decode($response);

                    $sourcelatitude  = $response_a->results[0]->geometry->location->lat;
                    $sourcelongitude = $response_a->results[0]->geometry->location->lng;
                }

                //Convert array to string for cuisines
                if(!empty($this->request->getData('restaurant_cuisine'))) {
                    $restaurantCuisine = implode(',',$this->request->getData('restaurant_cuisine'));
                }else {
                    $restaurantCuisine = '';
                }

                if(!empty($this->request->getData('restaurant_timezone'))) {
                    $restaurantTimezone = $this->request->getData('restaurant_timezone');
                }else {
                    $restaurantTimezone = '';
                }


                // if(!empty($this->request->getData('restaurant_meats'))) {
                //     $restaurantMeats = implode(',',$this->request->getData('restaurant_meats'));
                // }else {
                //     $restaurantMeats = '';
                // }

                // if(!empty($this->request->getData('restaurant_veggies'))) {
                //     $restaurantVeggies = implode(',',$this->request->getData('restaurant_veggies'));
                // }else {
                //     $restaurantVeggies = '';
                // }

                //Insert into User Table
                $userEntity = $this->Users->newEntity();
                $patchEntity = $this->Users->patchEntity($userEntity,$this->request->getData());
                $patchEntity->id = $userDetails['user_id'];
                $saveUser = $this->Users->save($patchEntity);
                if($saveUser) {
                    $restSeo = $this->Common->seoUrl($this->request->getData('restaurant_name'));
                    $restEntity->seo_url = $restSeo;
                    $restEntity->id = $this->request->getData('resId');
                    if(SEARCHBY == 'Google') {
                        $restEntity->sourcelatitude = $sourcelatitude;
                        $restEntity->sourcelongitude = $sourcelongitude;
                    }
                    $restEntity->restaurant_cuisine = $restaurantCuisine;
                    $restEntity->restaurant_timezone = $restaurantTimezone;                    
                    // $restEntity->restaurant_meats = $restaurantMeats;
                    // $restEntity->restaurant_veggies = $restaurantVeggies;         
                    $saveRest = $this->Restaurants->save($restEntity);
                }

                if( !empty($this->request->getData('map_mode') == 'Circle') && (SEARCHBY == 'Google')) {

                    if (!empty($this->request->getData('DeliverySettings'))) {
                        $this->DeliverySettings->deleteAll([
                            'restaurant_id' => $this->request->getData('resId')
                        ]);

                        foreach ($this->request->getData('DeliverySettings') as $key => $values) {
                            if ($values['delivery_miles'] != '' && $values['delivery_miles'] > 0 && $values['delivery_charge'] != '') {

                                $deliverySettings = $this->DeliverySettings->newEntity();
                                $settings['restaurant_id'] 		= $this->request->getData('resId');
                                $settings['delivery_miles'] 	= $values['delivery_miles'];
                                $settings['delivery_charge'] 	= $values['delivery_charge'];
                                $settings['driver_chargehint'] 	= 'A';
                                $settings['radius_color'] 		= $values['radius_color'];
                                $settings['map_type'] 			= $values['map_type'];

                                $deliverySettings = $this->DeliverySettings->patchEntity($deliverySettings,$settings);

                                $this->DeliverySettings->save($deliverySettings);
                                $this->DeliverySettings->id = '';
                            }
                        }
                    }
                } else if($this->request->getData('map_mode') == 'Polygon' && (SEARCHBY == 'Google')) {
                    $color = ["#800080","#00FF00","#FF00FF","#FF0000","#0000FF","#808000","#008000","#00FFFF","#C71585","#B0E0E6","#FFDAB9","#edc50c","#a0ed0c","#0ceda6","#0c66ed","#0c17ed","#6b0ced","#d50ced","#ed0cbb","#ed0c51","#f69d9d","#f69de2","#da9df6","#b29df6","#9dacf6","#9de6f6","#1b535f","#052228","#1e2f33","#486166","#0b6b7f","#0b7f5d","#0a5a42","#13dca1","#8dcebb"];
                    $areaMaps   = array();
                    $areamap    =   $this->request->getData('AreaMaps');
                    $coords     =   $this->request->getData('coords');
                    $records    =   $this->request->getData('record');

                    $this->Areamaps->deleteAll([
                        'res_id' => $this->request->getData('resId')
                    ]);

                    if (!empty($areamap)) {
                        foreach($areamap as $key => $value) {
                            $areaMaps[$key]['service_delivery_charge']  = $value['delivery_charge'];
                            $areaMaps[$key]['driver_chargehint']  = 'A';
                        }
                        foreach($coords as $co => $ords) {
                            $areaMaps[$co]['mappath'] = $ords;
                        }
                        foreach($records as $re => $cords) {
                            $areaMaps[$re]['mapcoords'] = $cords;
                        }

                       foreach($areaMaps as $a => $area) {
                            if($area['service_delivery_charge'] != '') {
                                $deliveryAreas = $this->Areamaps->newEntity();
                                $areamaps['res_id'] = $this->request->getData('resId');
                                $areamaps['mapcoords'] =$area['mappath'];
                                $areamaps['mappath'] = $area['mapcoords'];
                                $areamaps['service_delivery_charge'] = $area['service_delivery_charge'];
                                $areamaps['driver_chargehint'] = $area['driver_chargehint'];
                                $areamaps['colorcode'] = $color[$a];
                                $deliveryAreas = $this->DeliverySettings->patchEntity($deliveryAreas,$areamaps);

                                $this->Areamaps->save($deliveryAreas);
                                $this->Areamaps->id = '';
                            }
                        }
                    }
                }

                //Delivery Locations
                if (!empty($this->request->getData('data')['DeliveryLocation']) && (SEARCHBY != 'Google')) {
                    $this->DeliveryLocations->deleteAll(["restaurant_id" => $this->request->getData('resId')]);
                    $locations = $this->request->getData('data')['DeliveryLocation'];

                    $deliveryLocation = [] ;
                    foreach ($locations as $key => $value) {
                        if ($value != '') {
                            $cityId = $this->Cities->find('all', [
                                'fields'=> [
                                    'id'
                                ],
                                'conditions' => [
                                    'city_name' => $value['city_name']
                                ]
                            ])->hydrate(false)->first();

                            if (SEARCHBY == 'zip') {
                                $condition = [
                                    'city_id' => $cityId['id'],
                                    'zip_code' => $value['location_name']
                                ];
                            } else {
                                $condition = [
                                    'city_id' => $cityId['id'],
                                    'area_name' => $value['location_name']
                                ];
                            }

                            $locationId = $this->Locations->find('all', [
                                'fields'=> [
                                    'id'
                                ],
                                'conditions' => $condition
                            ])->hydrate(false)->first();

                            if (!empty($locationId['id'])) {
                                $deliveryLocation['restaurant_id'] = $saveEntity->id;
                                $deliveryLocation['city_id']       = $cityId['id'];
                                $deliveryLocation['location_id']   = $locationId['id'];
                                $deliveryLocation['minimum_order'] = (!empty($value['minimum_order'])) ?
                                    $value['minimum_order'] : '0.00';
                                $deliveryLocation['delivery_charge'] = (!empty($value['delivery_charge'])) ?
                                    $value['delivery_charge'] : '0.00';
                                $deliverLocSave = $this->DeliveryLocations->newEntity($deliveryLocation);
                                $this->DeliveryLocations->save($deliverLocSave);
                                $this->DeliveryLocations->id = '';
                            }
                        }
                    }
                }

                //Edit promotions image
                if(!empty($this->request->getData('data.res_promotion'))) {

                    $promotions = $this->request->getData('data.res_promotion');

                    $banner=[];
                    /*if(!file_exists(WWW_ROOT.'uploads'. DS.'storeImages'.DS.$this->request->getData('resId').DS.'storeBanners')) {
                        $this->Common->mkdir(WWW_ROOT.'uploads'. DS.'storeImages'.DS.$this->request->getData('resId').DS.'storeBanners');
                    }*/
                    $destinationPath = WWW_ROOT.'uploads'.DS.'storeBanners';

                    foreach ($promotions as $key => $value) {
                        if(!empty($value['tmp_name'])){
                            $getFileSize = getimagesize($value['tmp_name']);
                        }

                        if(!empty($getFileSize)) {
                            if ($value['error'] == 0) {
                                $banFile = $this->Common->uploadFile($value,$destinationPath);
                                $banner['promo_image'] = $banFile['refName'];
                            } else {
                                $banner['promo_image'] = $EditPromoImgList['promo_image'];
                            }
                            $banner['restaurant_id']    =  $this->request->getData('resId');

                            $bannerEnty  = $this->Promotions->newEntity();
                            $banners     = $this->Promotions->patchEntity($bannerEnty,$banner);
                            $this->Promotions->save($banners);  
                        } 
                    }
                }

                //Paymentmethods
                if(!empty($this->request->getData('payment_id'))){
                    $this->RestaurantPayments->deleteAll([
                        "restaurant_id" => $this->request->getData('resId')
                    ]);
                    $paymethod = $this->request->getData('payment_id');
                    foreach ($paymethod as $kPay => $vPay) {
                        $payMethod['payment_id']    = $kPay;
                        $payMethod['payment_status']    = $vPay;
                        $payMethod['restaurant_id'] = $this->request->getData('resId');
                        $paymethodEnty  = $this->RestaurantPayments->newEntity($payMethod);
                        $paymethodSave  = $this->RestaurantPayments->save($paymethodEnty);
                    }
                }

                $this->Flash->success('Restaurant Updated Successful');
                return $this->redirect(REST_BASE_URL.'restaurants');
            }
        }

        $this->set(compact('restDetails','id','cuisinesList','statelist','citylist','locationlist','selectedCuisine','EditPromoImgList','deliveryLocation','paymentList','editPayMethod', 'timezoneList'));
    }
  //-----------------------------------------------------------------------------------------------------------
     public function toggleSettings() {
        
        $user = $this->Auth->user(); 
        
        $restDetails = $this->Restaurants->find('all', [
               'conditions' => [
                'user_id' => $user['id']
            ],
            'contain' => [
                'DeliverySettings',
                'Areamaps',
                'RestaurantPayments' => [
                    'PaymentMethods'
                ]
            ]
        ])->hydrate(false)->first();

        $id  = $restDetails['id']; 
        
        if($this->request->is(['post'])) {

            $restEntity = $this->Restaurants->newEntity();
            $restEntity = $this->Restaurants->patchEntity($restEntity,$this->request->getData());

            $restEntity->id = $id;
            $restEntity->minimum_pickup_time = $this->request->getData('minimum_pickup_time');  
            $restEntity->ordering_switch_status = $this->request->getData('ordering_switch_status');
            $restEntity->delivery_switch_status = $this->request->getData('delivery_switch_status');  
                  
            $saveEntity = $this->Restaurants->save($restEntity); 
            if($saveEntity){                              
                $this->Flash->success('Saved Successful');
                return $this->redirect(REST_BASE_URL.'dashboard'); 
            }          
        }
    }

//-----------------------------------------------------------------------------------------------------------
    /*Check Username*/
    public function checkEmail() {      
              
        if($this->request->getData('contact_email') != '') {

            $restSeo = $this->Common->seoUrl($this->request->getData('restname'));


            if($this->request->getData('id') != '') {
                //Get UserId
                $userDetails = $this->Restaurants->find('all', [
                    'fields' => [
                        'user_id'
                    ],
                    'conditions' => [
                        'id' => $this->request->getData('id')
                    ]
                ])->hydrate(false)->first();

                $conditions = [
                    'id !=' => $userDetails['user_id'],
                    'username' => $this->request->getData('contact_email'),
                ];

                $conditions1 = [
                    'id !=' => $this->request->getData('id'),
                    'seo_url' => $restSeo
                ];
            }else {
                $conditions = [
                    'username' => $this->request->getData('contact_email'),
                ];

                $conditions1 = [
                    'seo_url' => $restSeo
                ];

            }
            $userCount = $this->Users->find('all', [
                'conditions' => $conditions
            ])->count();

            //Check Restaurant Name


            $restCount = $this->Restaurants->find('all',[
                'conditions' =>$conditions1

            ])->count();

            if($userCount == 0 && $restCount == 0) {
                die();
            }else if($userCount != 0) {
                echo 'user';exit;
            }else if($restCount != 0) {
                echo 'rest';exit;
            }
        }
        die();
    }
    /*Delete Promotion Banner Image*/
    public function deleteProcess() {
       //pr($this->request->getData('id'));die;
        $statusPromo = $this->Promotions->get($this->request->getData('id'));
        //pr($statusPromo);die;
        if (!empty($statusPromo)) {
            $this->Promotions->deleteAll([
                'id' => $this->request->getData('id')
            ]);
           echo "delete";die();
        }
    }
  //---------------------------------------------------------------------------------------------------
    /*Get City Name Based on State*/
    public function getCityName() {

        $stateId = $this->request->getData('state_id');
        $getCity = '';

        $stateCity = $this->Cities->find('list', [
            'keyField' => 'id',
            'valueField' => 'city_name',
            'conditions' => [
                'status' => '1',
                'state_id' => $stateId
            ],
        ])->hydrate(false)->toArray();

        foreach ($stateCity as $key => $val) {
            $getCity .= $val.',';
        }
        echo trim($getCity,',');
        exit();
    }

//-----------------------------------------------------------------------------------------
    /*Get Location based on city and state*/
    public function getLocationName() {

        $stateId = $this->request->getData('stateId');
        $cityName = $this->request->getData('cityName');
        $getCityId = $this->Cities->find('all', [
            'fields' => [
                'Cities.id'
            ],
            'conditions' => [
                'Cities.state_id' => $stateId,
                'Cities.city_name' => $cityName
            ]
        ])->hydrate(false)->first();

        $getLocation = '';
        $locationList = $this->Locations->find('all', [
            'fields' => [
                'Locations.zip_code',
                'Locations.area_name'
            ],
            'conditions' => [
                'Locations.state_id' => $stateId,
                'Locations.city_id' => $getCityId['id'],
                'Locations.status' => 1
            ]
        ])->hydrate(false)->toArray();

        foreach ($locationList as $key => $val) {
            $getLocation .= (SEARCHBY == 'zip') ? $val['zip_code'].',' : $val['area_name'].',';
        }

        echo trim($getLocation,',');
        exit();
    }

    /*Facebook Ordering Section*/

    public function getPageInfo($pageid) {

        $siteDetails = $this->Sitesettings->find('all', [
            'fields' => [
                'facebook_api_id',
                'facebook_secret_key',
            ]
        ])->hydrate(false)->first();

        define("FACEBOOK_APP_ID", $siteDetails['facebook_api_id']);
        define("FACEBOOK_APP_SECRET", $siteDetails['facebook_secret_key']);
        define("FACEBOOK_ACCESS_TOKEN",'');



        if(isset($pageid) && !empty($pageid)) {

            if(defined(FACEBOOK_ACCESS_TOKEN) && FACEBOOK_ACCESS_TOKEN != '')
            {
                $extended_token = FACEBOOK_ACCESS_TOKEN;
            } else{
                // $page_info = file_get_contents("https://graph.facebook.com/oauth/access_token?client_id=".urlencode(FACEBOOK_APP_ID)."&client_secret=".urlencode(FACEBOOK_APP_SECRET)."&grant_type=client_credentials");

                $url = "https://graph.facebook.com/oauth/access_token?client_id=".urlencode(FACEBOOK_APP_ID)."&client_secret=".urlencode(FACEBOOK_APP_SECRET)."&grant_type=client_credentials";
                $ch = curl_init();
                curl_setopt ($ch, CURLOPT_URL, $url);
                curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 5);
                curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
                $page_info = curl_exec($ch);
                curl_close($ch);

                parse_str($page_info,$output);

                $output1 = json_decode($page_info,true);

                $extended_token = $output1['access_token'];

            }
            //$page_info = json_decode(file_get_contents("https://graph.facebook.com/".$pageid."?access_token=".$extended_token));
            $page_url = "https://graph.facebook.com/".$pageid."?access_token=".$extended_token;



            $ch = curl_init();
            curl_setopt ($ch, CURLOPT_URL, $page_url);
            curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
            $page_info1 = curl_exec($ch);
            curl_close($ch);
            $page = json_decode($page_info1);

            //echo "===========<pre>";print_r($page);echo "</pre>";exit;


            define("FACEBOOK_PAGE_ID",$pageid);
            define("FACEBOOK_PAGE_NAME",$page->name);

            if(!empty(FACEBOOK_PAGE_ID) && !empty(FACEBOOK_PAGE_NAME) && !empty(FACEBOOK_APP_ID))
                $facebook_app_url = 'https://www.facebook.com/pages/'.FACEBOOK_PAGE_NAME.'/'.FACEBOOK_PAGE_ID.'?sk=app_'.FACEBOOK_APP_ID;

            return $facebook_app_url;

        }
    }
    //-----------------------------------------------------------------------------------------
} #classEnd...