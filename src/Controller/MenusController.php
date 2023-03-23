<?php
/**
 * Created by PhpStorm.
 * User: Sundar.S
 * Date: 29-12-2017
 * Time: 16:05
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;



class MenusController extends AppController
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('Auth');
        $this->viewBuilder()->setLayout('frontend');
        $this->loadModel('Restaurants');
        $this->loadModel('Cuisines');
        $this->loadModel('RestaurantMenus');
        $this->loadComponent('Common');
        $this->loadComponent('PushNotification');
        $this->loadModel('MenuDetails');
        $this->loadModel('Categories');
        $this->loadModel('MenuAddons');
        $this->loadModel('Carts');
        $this->loadModel('Offers');
        $this->loadModel('Orders');
        $this->loadModel('Rewards');
        $this->loadModel('CustomerPoints');
        $this->loadModel('Timezones');
        $this->loadModel('Meats');
        $this->loadModel('Veggies');
        $this->loadModel('Amount');
    }

    public function beforeFilter(Event $event)
    {
        // Before Login , these are the function we can access
        $this->Auth->allow([
            'index',
            'ajaxaction',
            'orderType',
            'checkFacebookDetails',
            'clearCart'
        ]);
    }

    //--------------------------------------Get Menu Details Based on Restaurant Name-----------------------------------
    public function index($restName = null) {

        //pr($_SERVER);die();

        //pr($this->request->getData());die();

        $restaurantId = [];
        if($this->request->getData('signed_request') != '' || $this->request->session()->read('signed_request') != '') {

            require_once(ROOT . DS . 'vendor' . DS . 'facebook' . DS . 'php-sdk'. DS. 'src'. DS .'facebook.php');

            if($this->request->session()->read('signed_request') != '') {
                $signedData = $this->request->session()->read('signed_request');
            }else if($this->request->getData('signed_request') != '') {
                $signedData = $this->request->getData('signed_request');
            }
            //echo $signedData;

            $data = $this->parse_signed_request($signedData);

            $restaurantId = $this->Restaurants->find('all', [
                'conditions' => [
                    'fb_page_id' => $data['page']['id']
                ]
            ])->hydrate(false)->first();
            //pr($data);die();

            if(!empty($restaurantId)) {
                $restName = $restaurantId['seo_url'];
            }

            define("FACEBOOK_APP_ID", $this->siteSettings['facebook_api_id']);
            define("FACEBOOK_APP_SECRET", $this->siteSettings['facebook_secret_key']);

            //Get User Details
            $facebook = new \Facebook(array(
                'appId'  => FACEBOOK_APP_ID,
                'secret' => FACEBOOK_APP_SECRET,
                'default_graph_version' => 'v2.11',
                'cookie' => true,
            ));

            $app_url = $this->getPageInfo($data['page']['id']);

            // Get User ID
            $user = $facebook->getUser();

            if($user) {

                try {

                    $user_profile = $facebook->api('/me?fields=email,first_name,last_name');

                    if(isset($user_profile['email']) && !empty($user_profile['email'])) {
                        $userLoggIn = $this->checkFacebookDetails($user_profile);
                        //echo "<pre>";print_r($userLoggIn);die();
                        if (!$userLoggIn) {
                            echo '<h3 class="form-title"> You are not authorized to access this Page </h3>';
                            exit();
                        }
                    } else {

                        if($user) {
                            echo '<h3 class="form-title"> You are not authorized to access this Page </h3>';
                            exit();

                        }else {
                            $loginUrl = $facebook->getLoginUrl(array(
                                'scope' => 'email,user_birthday, user_location, user_work_history, user_hometown, user_photos',
                                'redirect_uri'  => $app_url
                            ));
                            print('<script> top.location.href=\'' . $loginUrl . '\'</script>');
                            exit;
                        }
                    }
                } catch (FacebookApiException $e) {
                    echo '<h3 class="form-title"> You are not authorized to access this Page </h3>';
                    exit();
                }

            }else {
                $loginUrl = $facebook->getLoginUrl(array (
                    'display' => 'popup',
                    'redirect_uri' => $app_url
                ));
                print('<script> top.location.href=\'' . $loginUrl . '\'</script>');
            }

            $this->request->session()->write('signed_request',$signedData);


        }
        //pr($restaurantId);die();

        /*if((SEARCHBY == 'Google' && $this->request->session()->read('searchLocation') != '') || (SEARCHBY != 'Google' && $this->request->session()->read('city_id') != '' && $this->request->session()->read('location_id') != '') || !empty($restaurantId) || $this->request->session()->read('signed_request') != ''){*/


        if($restName != ''){

            $this->request->session()->write('restName',$restName);
            $this->request->session()->write('currentStatus','');

            if ($restName != '') {

                $restDetails = $this->Restaurants->find('all', [
                    'conditions' => [
                        'seo_url' => $restName
                    ],
                    'contain' => [
                        'RestaurantMenus' => [
                            'conditions' => [
                                'RestaurantMenus.status' => 1
                            ],
                            'MenuDetails',
                            'sort' => [
                                'RestaurantMenus.category_id' => 'ASC'
                            ]

                        ],
                        'DeliveryLocations' => [
                            'conditions' => [
                                'city_id' => $this->request->session()->read('city_id'),
                                'location_id' => $this->request->session()->read('location_id')
                            ]

                        ],
                        'Reviews' =>[
                                'conditions' => [
                                'status' => 1
                            ]
                        ],
                        'Offers' => [
                            'conditions' => [
                                'Offers.status' => 1
                            ],
                            'sort' => [
                                'Offers.id' => 'DESC'
                            ]
                        ]
                    ]
                ])->hydrate(false)->first();                
                //echo "<pre>"; print_r($restDetails); die();

                $menuDetails = $this->RestaurantMenus->find('all', [
                    'conditions' => [
                        'RestaurantMenus.id' => $this->request->getData('menuid')
                    ],
                    'contain' => [
                        'MenuDetails'
                    ]
                ])->hydrate(false)->first();

                $offerCount = 0;
                $currentDate = strtotime(date('Y-m-d'));
                if(!empty($restDetails['offers'])) {

                    foreach ($restDetails['offers'] as $oKey => $oValue) {
                        $startDate = date('Y-m-d',strtotime($oValue['offer_from']));
                        $toDate = date('Y-m-d',strtotime($oValue['offer_to']));

                        if($currentDate >= strtotime($startDate) && $currentDate <= strtotime($toDate)) {
                            $offerCount++;
                            $restDetails['restOffers'] = $oValue;
                            break;
                        }
                    }
                }
               
                //pr($restDetails);die;
                //echo 'session-----'.$this->request->session()->read('sessionId');die();
                if ($this->request->session()->read('sessionId') != '') {
                    $sessionId = $this->request->session()->read('sessionId');
                    $this->request->session()->write('resid', $restDetails['id']);

                } else {
                    $sessionId = session_id();
                    $this->request->session()->write('sessionId', $sessionId);
                    $this->request->session()->write('resid', $restDetails['id']);
                }

                //pr($restDetails);die();
                //echo $this->request->session()->read('resid');die;

                if (!empty($restDetails)) {
                    //-----------Timings start--------------------
                    $array_of_time = array ();

                    $nowTime = date('h:i A');
                    //echo $nowTime;die;
                    //$nowTime = '12.35 PM';
                    $currentStatus ='';
                    $currentTime = strtotime($nowTime);
                    $currentDate = date('Y-m-d');

                    $currentDay = strtolower(date('l'));
                    //$currentDay = 'monday';

                    $firstStartTime = $restDetails[$currentDay.'_first_opentime'];
                    $firstEndTime = $restDetails[$currentDay.'_first_closetime'];

                    $secondStartTime = $restDetails[$currentDay.'_second_opentime'];
                    $secondEndTime = $restDetails[$currentDay.'_second_closetime'];


                    $firstOpenTime = strtotime($restDetails[$currentDay.'_first_opentime']);
                    $firstCloseTime = strtotime($restDetails[$currentDay.'_first_closetime']);

                    $secondOpenTime = strtotime($restDetails[$currentDay.'_second_opentime']);
                    $secondCloseTime = strtotime($restDetails[$currentDay.'_second_closetime']);


                    //In first Timing section
                    if($restDetails[$currentDay.'_status'] != 'Close') {

                        if($currentTime < $firstOpenTime) {

                            $restDetails['currentStatus'] = 'Open';
                            $final[] = $restDetails;

                            $nowTime = date("h:i A", strtotime('+45 minutes', $firstOpenTime));

                            $start_time = strtotime($currentDate . ' ' . $nowTime);
                            $end_time = strtotime($currentDate . ' ' . $firstEndTime);

                            $fifteen_mins = 15 * 60;

                            while ($start_time <= $end_time) {
                                $array_of_time[date("D h:i A", $start_time)] = date("D h:i A", $start_time);
                                $start_time += $fifteen_mins;
                            }

                        }


                        if ($currentTime > $firstOpenTime && $currentTime <= $firstCloseTime) {
                            $restDetails['currentStatus'] = 'Open';
                            $final[] = $restDetails;

                            $nowTime = date("h:i A", strtotime('+45 minutes', $currentTime));

                            $start_time = strtotime($currentDate . ' ' . $nowTime);
                            $end_time = strtotime($currentDate . ' ' . $firstEndTime);

                            $fifteen_mins = 15 * 60;

                            while ($start_time <= $end_time) {
                                $array_of_time[date("D h:i A", $start_time)] = date("D h:i A", $start_time);
                                $start_time += $fifteen_mins;
                            }

                            //print_r ($array_of_time);die();

                        }

                        if (empty($array_of_time)) {

                            if ($currentTime > $firstCloseTime && $currentTime <= $secondOpenTime) {
                                $restDetails['currentStatus'] = 'PreOrder';
                                $final[] = $restDetails;

                                $secondStartTime = date("h:i A", strtotime('+45 minutes', $secondOpenTime));

                                $start_time = strtotime($currentDate . ' ' . $secondStartTime);
                                $end_time = strtotime($currentDate . ' ' . $secondEndTime);

                                $fifteen_mins = 15 * 60;

                                while ($start_time <= $end_time) {
                                    $array_of_time[date("D h:i A", $start_time)] = date("D h:i A", $start_time);
                                    $start_time += $fifteen_mins;
                                }

                                //print_r ($array_of_time);
                            }
                        } else {
                            if ($currentTime < $secondOpenTime) {
                                //$secondStartTime = 45 * 60;
                                $secondStartTime = date("h:i A", strtotime('+45 minutes', $secondOpenTime));
                                $start_time = strtotime($currentDate . ' ' . $secondStartTime);
                                $end_time = strtotime($currentDate . ' ' . $secondEndTime);

                                $fifteen_mins = 15 * 60;

                                while ($start_time <= $end_time) {
                                    $array_of_time[date("D h:i A", $start_time)] = date("D h:i A", $start_time);
                                    $start_time += $fifteen_mins;
                                }
                            }
                        }

                        if ($currentTime > $secondOpenTime && $currentTime <= $secondCloseTime) {
                            $restDetails['currentStatus'] = 'Open';
                            $final[] = $restDetails;

                            $nowTime = date("h:i A", strtotime('+45 minutes', $currentTime));

                            $start_time = strtotime($currentDate . ' ' . $nowTime);
                            $end_time = strtotime($currentDate . ' ' . $secondEndTime);

                            $fifteen_mins = 15 * 60;

                            while ($start_time <= $end_time) {
                                $array_of_time[date("D h:i A", $start_time)] = date("D h:i A", $start_time);
                                $start_time += $fifteen_mins;
                            }
                        }
                        //pr($array_of_time);die;
                    }
                     $this->set(compact('array_of_time','currentDate'));
                    //----------------Timings end------------------

                    //Get Cuisines List
                    $allCuisinesList = [];

                    $restaurantCuisine = explode(',', $restDetails['restaurant_cuisine']);
                    $cuisineList = '';
                    if (!empty($restaurantCuisine)) {
                        foreach ($restaurantCuisine as $ckey => $cvalue) {
                            $cuisines = $this->Cuisines->find('all', [
                                'conditions' => [
                                    'id' => $cvalue
                                ]
                            ])->hydrate(false)->first();
                            if (!empty($cuisines)) {
                                $cuisineList[] = $cuisines['cuisine_name'];
                                if (!in_array($cvalue, $allCuisinesList)) {
                                    $allCuisinesList[] = $cvalue;
                                    if (empty($sideCuisines[$cuisines['cuisine_name']])) {
                                        $sideCuisines[$cuisines['cuisine_name']] = 1;
                                    } else {
                                        $sideCuisines[$cuisines['cuisine_name']]++;
                                    }

                                    $allCuisinesLists[$cvalue] = $cuisines['cuisine_name'];
                                } else {
                                    if (empty($sideCuisines[$cuisines['cuisine_name']])) {
                                        $sideCuisines[$cuisines['cuisine_name']] = 1;
                                    } else {
                                        $sideCuisines[$cuisines['cuisine_name']]++;
                                    }
                                }
                            }
                        }
                    }
                    $restDetails['cuisineLists'] = implode(', ', $cuisineList);

                    //Get Timezones List

                    $allTimezonesList = [];

                    $restaurantTimezone = explode(',', $restDetails['restaurant_timezone']);
                    $timezoneList = '';
                    if (!empty($restaurantTimezone)) {
                        foreach ($restaurantTimezone as $tkey => $tvalue) {
                            $timezones = $this->Timezones->find('all', [
                                'conditions' => [
                                    'id' => $tvalue
                                ]
                            ])->hydrate(false)->first();
                            if (!empty($timezones)) {
                                $timezoneList[] = $timezones['timezone_name'];
                                if (!in_array($tvalue, $allTimezonesList)) {
                                    $allTimezonesList[] = $tvalue;
                                    if (empty($sideTimezones[$timezones['timezone_name']])) {
                                        $sideTimezones[$timezones['timezone_name']] = 1;
                                    } else {
                                        $sideTimezones[$timezones['timezone_name']]++;
                                    }

                                    $allTimezonesList[$cvalue] = $timezones['timezone_name'];
                                } else {
                                    if (empty($sideTimezones[$timezones['timezone_name']])) {
                                        $sideTimezones[$timezones['timezone_name']] = 1;
                                    } else {
                                        $sideTimezones[$timezones['timezone_name']]++;
                                    }
                                }
                            }
                        }
                    }
                    $restDetails['timezoneList'] = implode(', ', $timezoneList);                   
                              

                    //offers
                    $offerCount = 0;
                    $currentDate = strtotime(date('Y-m-d'));
                    if(!empty($restDetails['offers'])) {

                        foreach ($restDetails['offers'] as $oKey => $oValue) {
                            $startDate = date('Y-m-d',strtotime($oValue['offer_from']));
                            $toDate = date('Y-m-d',strtotime($oValue['offer_to']));

                            if($currentDate >= strtotime($startDate) && $currentDate <= strtotime($toDate)) {
                                $offerCount++;
                                $restDetails['offerLists'] = $oValue;
                                unset($restDetails['offers']);
                                break;
                            }else {
                                unset($restDetails['offers'][$oKey]);
                            }
                        }

                    }
                    //echo "<pre>";print_r($offerList);die;

                    $deliveryCharge = '';
                    $message = '';

                    //---------------------------Get Delivery Charge----------------------------------------------------
                    if(SEARCHBY == 'Google') {

                        if($this->request->session()->read('searchLocation') != '') {

                            $prepAddr = str_replace(' ', '+', $this->request->session()->read('searchLocation'));

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

                            $sourcelatitude = $response_a->results[0]->geometry->location->lat;
                            $sourcelongitude = $response_a->results[0]->geometry->location->lng;

                            $latitudeTo = $restDetails['sourcelatitude'];
                            $longitudeTo = $restDetails['sourcelongitude'];
                            $unit = 'K';
                            $distance = $this->Common->getDistanceValue($sourcelatitude, $sourcelongitude, $latitudeTo, $longitudeTo,
                                $unit);

                            $distance = str_replace(',', '', $distance);

                            $restDetails['distance'] = $distance;

                            list($deliveryCharge, $message) = $this->getDeliveryCharge($restDetails['id'], $distance, $sourcelatitude, $sourcelongitude);

                        }else {
                            $message = 'success';
                            $deliveryCharge = 0.00;
                            $minimumOrder = 0.00;
                            $restDetails['distance'] = '0.00';
                            $restDetails['minimum_order'] = $restDetails['minimum_order'];
                        }

                    }else if(!empty($restDetails['delivery_locations'])) {

                        $message = 'success';
                        $deliveryCharge = $restDetails['delivery_locations'][0]['delivery_charge'];
                        $minimumOrder = $restDetails['delivery_locations'][0]['minimum_order'];
                        $restDetails['distance'] = '0.00';
                        $restDetails['minimum_order'] = $minimumOrder;
                    }else {
                        $restDetails['distance'] = '0.00';
                    }

                    if ($message == 'success') {
                        $restDetails['delivery_charge'] = $deliveryCharge;
                    } else {
                        $restDetails['delivery_charge'] = '';
                    }
                    $this->request->session()->write('delivery_charge', $restDetails['delivery_charge']);
                    //----------------------------------------------Get Delivery Charge End-------------------------------------------------

                    //----------------------------------------------Get Category List-------------------------------------------------------

                    foreach ($restDetails['restaurant_menus'] as $key => $value) {

                        $category = $this->Categories->find('all', [

                            'conditions' => [
                                'id' => $value['category_id'],
                                'status' => '1',
                                'delete_status' => 'N'
                            ],
                            'sort' => [
                                'sortorder' => 'ASC'
                            ]
                        ])->hydrate(false)->first();

                        if(!empty($category)) {
                            $categoryList[$value['category_id']] = $category['category_name'];
                        }


                    }

                    if(!empty($categoryList)) {
                        $categoryList = array_unique($categoryList);
                    }else {
                        $categoryList = array_unique($categoryList);
                    }


                    //----------------------------------------------Get Category List End---------------------------------------------------

                    $cartsDetails = $this->Carts->find('all', [
                        'sum(Carts.price) AS ctotal',
                        'conditions' => [
                            'session_id' => $sessionId,
                        ],
                        'contain' => [
                            'RestaurantMenus' => [
                                'conditions' => [
                                    'RestaurantMenus.delete_status' => 'N'
                                ]
                            ]
                        ],
                        'order' => [
                            'Carts.menu_id' => 'ASC'
                        ]
                    ])->hydrate(false)->toArray();

                    $cartCount = count($cartsDetails);
                    $subTotal = 0;
                    $taxAmount = 0;
                    $cartRestaurantId = '';
                    if (!empty($cartsDetails)) {

                        foreach ($cartsDetails as $ckey => $cvalue) {
                            $subTotal = $cvalue['total_price'] + $subTotal;
                        }
                        if ($restDetails['restaurant_tax'] > 0) {
                            $taxAmount = ($subTotal * $restDetails['restaurant_tax']) / 100;
                        }

                        $deliveryCharge = (isset($restDetails['delivery_charge'])) ? $restDetails['delivery_charge'] : '0.00';

                        $totalAmount = $subTotal + $taxAmount + $deliveryCharge;

                        $withOutDelivery = $subTotal + $taxAmount;



                        $checkOffers = $this->Offers->find('all', [
                            'conditions' => [
                                'resid' => $this->request->session()->read('resid'),
                                'status' => '1',
                            ],
                            'order' => [
                                'id' => 'DESC'
                            ]

                        ])->hydrate(false)->toArray();



                        $firstUser = 'No';
                        $normalOffer = 'No';
                        $offerAmount = '';
                        $offerPercentage = '';
                        if(!empty($checkOffers)) {

                            foreach($checkOffers as $cKey => $cValue) {

                                $startDate = date('Y-m-d',strtotime($cValue['offer_from']));
                                $toDate = date('Y-m-d',strtotime($cValue['offer_to']));

                                if($currentDate >= strtotime($startDate) && $currentDate <= strtotime($toDate)) {
                                    if($cValue['first_user'] == 'Y') {
                                        $orderCount = $this->Orders->find('all', [
                                            'conditions' => [
                                                'customer_id' => $this->Auth->user('id')
                                            ]
                                        ])->count();


                                        if($orderCount == 0) {
                                            if($subTotal >= $cValue['free_price']) {
                                                $firstUser = 'Yes';
                                                $offerAmount = $subTotal * $cValue['free_percentage']/100;
                                                $offerPercentage = $cValue['free_percentage'];

                                                $this->request->session()->write('offerAmount',$offerAmount);
                                                $this->request->session()->write('firstUser',$firstUser);
                                                $this->request->session()->write('offerPercentage',$offerPercentage);

                                                $totalAmount = $totalAmount - $offerAmount;
                                            }else {
                                                $this->request->session()->write('offerAmount',$offerAmount);
                                                $this->request->session()->write('firstUser',$firstUser);
                                                $this->request->session()->write('offerPercentage',$offerPercentage);
                                            }
                                        }else if($cValue['normal'] == 'Y') {
                                            if($subTotal >= $cValue['normal_price']) {


                                                $normalOffer = 'Yes';
                                                $offerAmount = $subTotal * $cValue['normal_percentage'] / 100;
                                                $offerPercentage = $cValue['normal_percentage'];

                                                $this->request->session()->write('offerAmount', $offerAmount);
                                                $this->request->session()->write('normalOffer', $normalOffer);
                                                $this->request->session()->write('offerPercentage', $offerPercentage);

                                                $totalAmount = $totalAmount - $offerAmount;
                                                $withOutDelivery = $withOutDelivery - $offerAmount;


                                            }else {
                                                $this->request->session()->write('offerAmount','');
                                                $this->request->session()->write('normalOffer','');
                                                $this->request->session()->write('offerPercentage','');
                                            }

                                        }else {
                                            $this->request->session()->write('offerAmount','');
                                            $this->request->session()->write('normalOffer','');
                                            $this->request->session()->write('offerPercentage','');
                                        }
                                        break;
                                    }else if($cValue['normal'] == 'Y') {

                                        if($subTotal >= $cValue['normal_price']) {

                                            $normalOffer = 'Yes';
                                            $offerAmount = $subTotal * $cValue['normal_percentage'] / 100;

                                            $offerPercentage = $cValue['normal_percentage'];

                                            $this->request->session()->write('offerAmount', $offerAmount);
                                            $this->request->session()->write('normalOffer', $normalOffer);
                                            $this->request->session()->write('offerPercentage', $offerPercentage);

                                            $totalAmount = $totalAmount - $offerAmount;


                                        }else {
                                            $this->request->session()->write('offerAmount','');
                                            $this->request->session()->write('normalOffer','');
                                            $this->request->session()->write('offerPercentage','');
                                        }

                                        break;
                                    }else {
                                        $this->request->session()->write('offerAmount','');
                                        $this->request->session()->write('normalOffer','');
                                        $this->request->session()->write('offerPercentage','');
                                    }
                                }else {
                                    $this->request->session()->write('offerAmount','');
                                    $this->request->session()->write('normalOffer','');
                                    $this->request->session()->write('offerPercentage','');
                                }
                            }

                        }else {
                            $this->request->session()->write('offerAmount','');
                            $this->request->session()->write('normalOffer','');
                            $this->request->session()->write('offerPercentage','');
                        }

                        $cartRestaurantId = $cartsDetails[0]['restaurant_id'];
                    }else {
                        $cartRestaurantId = $this->request->session()->read('resid');
                    }

                    //Reward Section:
                    //if(!empty($this->Auth->user()) && $this->Auth->user('role_id') == '3') {

                        $rewardData = $rewardPoint = $this->Rewards->find('all', [
                            'conditions' => [
                                'id' => 1,
                                'reward_option' => 'Yes'
                            ]
                        ])->hydrate(false)->first();

                        $getRestaurantOption = $this->Restaurants->find('all', [
                            'fields' => [
                                'reward_option'
                            ],
                            'conditions' => [
                                'id' => $cartRestaurantId,
                            ]
                        ])->hydrate(false)->first();

                        if(!empty($rewardPoint) && $getRestaurantOption['reward_option'] == 'Yes') {

                            if(!empty($this->Auth->user()) && $this->Auth->user('role_id') == '3') {


                                $customerPoints = $this->CustomerPoints->find('all', [
                                    'conditions' => [
                                        'customer_id' => $this->Auth->user('id'),
                                        'status' => '1'
                                    ]
                                ])->hydrate(false)->toArray();

                                $createdDate = explode(' ', $rewardPoint['created']);

                                $date1 = date_create($createdDate[0]);
                                $date2 = date_create(date('Y-m-d'));
                                $diff = date_diff($date1, $date2);
                                $diff = $diff->format("%R%a");


                                $remainingDays = ($rewardPoint['reward_validity'] - $diff);
                                $resPercent = $rewardPoint['reward_percentage'];

                                $previousCount = count($customerPoints) + 1;



                                if (($remainingDays > 0) && ($previousCount >= $rewardPoint['redeem_order'])) {

                                    $customerPoints = $this->CustomerPoints->find('all', [
                                        'fields' => [
                                            'total_points' => 'SUM(points)'
                                        ],
                                        'conditions' => [
                                            'customer_id' => $this->Auth->user('id'),
                                            'status' => '1'
                                        ]
                                    ])->hydrate(false)->toArray();

                                    $userPoints = $customerPoints[0]['total_points'];
                                    $resPoints = $rewardPoint['reward_totalpoint'];

                                    if ($userPoints >= $resPoints) {
                                        $totPercent = (abs($userPoints / $resPoints) * $resPercent);

                                        $offPercent = $subTotal * abs($totPercent / 100);
                                        $this->request->session()->write('rewardPoint', $offPercent);
                                        $this->request->session()->write('rewardPercentage', number_format($totPercent, 2));
                                        $totalAmount = $totalAmount - $offPercent;
                                    } else {
                                        $this->request->session()->write('rewardPoint', '');
                                    }
                                } else {
                                    $this->request->session()->write('rewardPoint', '');
                                }

                                $grandTotal = $subTotal;
                                $rewardAmount = $rewardPoint['reward_amount'];
                                $rewardPoints = $rewardPoint['reward_point'];

                                $getRewardFromTotal = $grandTotal/$rewardAmount;

                                $orderPoint = $getRewardFromTotal * $rewardPoints;
                                $this->request->session()->write('orderPoint',round($orderPoint));


                            }else {
                                $grandTotal = $subTotal;
                                $rewardAmount = $rewardPoint['reward_amount'];
                                $rewardPoints = $rewardPoint['reward_point'];

                                $getRewardFromTotal = $grandTotal/$rewardAmount;

                                $orderPoint = $getRewardFromTotal * $rewardPoints;
                                $this->request->session()->write('orderPoint',round($orderPoint));
                                $this->request->session()->write('rewardPoint','');
                            }

                        }else {
                            $this->request->session()->write('rewardPoint','');
                            $this->request->session()->write('orderPoint','');
                        }

                    /*}else {
                        $this->request->session()->write('rewardPoint','');
                        $this->request->session()->write('orderPoint','');
                    }*/


                    if(SEARCHBY == 'Google') {
                        if($restDetails['free_delivery'] > 0 && $restDetails['free_delivery'] <= $subTotal) {
                            $restDetails['delivery_charge'] = 0.00;
                        }
                    }

                    $minimumOrder = $restDetails['minimum_order'];

                    if ($subTotal >= $minimumOrder) {
                        $minimumOrder = '1';
                    } else {
                        $minimumOrder = '0';
                    }
                    $minimumOrderAmount = $restDetails['minimum_order'];


                    //order Type
                    $orderType = $this->request->session()->read('orderType');

                    if(empty($final)) {
                        $restDetails['currentStatus'] = 'Closed';
                    }

                    $this->request->session()->write('currentStatus',$restDetails['currentStatus']);

                    $currentStatus = $this->request->session()->read('currentStatus');

                    if(!empty($restDetails['reviews'])) {
                        $reviewCount = 0;
                        $restDetails['totalRating'] = count($restDetails['reviews']);
                        foreach($restDetails['reviews'] as $rKey => $rValue) {
                            $reviewCount  = $rValue['rating'] + $reviewCount;

                            $restDetails['reviews'][$rKey]['ratingCount'] = $rValue['rating']*20;

                            $RestDet = $this->Users->find('all', [
                                    'conditions' => [
                                    'id' => $rValue['customer_id']
                                ]
                            ])->hydrate(false)->first();

                        $restDetails['reviews'][$rKey]['customer_name'] = $RestDet['first_name'].''.$RestDet['last_name'];

                        }
                        //pr($restDetails);die;

                        $restDetails['finalReview'] = ($reviewCount/$restDetails['totalRating'])*20;
                    }else {
                        $restDetails['totalRating'] = 0;
                        $restDetails['finalReview'] = 0;
                    }

                    //pr($restDetails['reviews']);die;
                    //echo "<pre>";print_r($categoryList);die;
                    $search = SEARCHBY;

                    $restId = $this->request->session()->read('resid');


                    $this->set(compact('restDetails', 'categoryList', 'menuDetails', 'restaurantDetails', 'cuisinesList', 'timezoneList', 'cartsDetails', 'cartCount', 'taxAmount', 'subTotal', 'totalAmount', 'deliveryCharge', 'final', 'minimumOrder', 'minimumOrderAmount', 'withOutDelivery','orderType','currentStatus','offersList','search','restName','cartRestaurantId','restId', 'rewardData'));

                } else {
                    return $this->redirect(BASE_URL . 'searches');
                }

            } else {
                return $this->redirect(BASE_URL . 'searches');
            }
        } else {
            return $this->redirect(BASE_URL . 'searches');
        }
    }
    //--------------------------------------Get Menu Details Based on Restaurant Name-----------------------------------

    //--------------------------------------Get Delivery Charge --------------------------------------------------------

    public function getDeliveryCharge($resId,$Durations,$lat,$lng) {


        $restDetails = $this->Restaurants->find('all', [
            'fields' => [
                'id',
                'map_mode'
            ],
            'conditions' => [
                'id' => $resId
            ],
            'contain' => [
                'DeliverySettings' => [
                    'sort' => [
                        'DeliverySettings.delivery_miles' => 'ASC'
                    ]
                ],
                'Areamaps'
            ]

        ])->hydrate(false)->first();


        $deliveryCharge = $count = 0;
        if($restDetails['map_mode'] == 'Circle') {
            foreach ($restDetails['delivery_settings'] as $key => $value) {

                if($Durations <= $value['delivery_miles']) {
                    $deliveryCharge = $value['delivery_charge'];
                    $count        = '1';
                    break;
                }
            }
            if($count == 1){
                $message        = 'success';
            } else {
                $message = 'failed';
            }
        } elseif($restDetails['map_mode'] == 'Polygon') {
            foreach ($restDetails['areamaps'] as $key => $value) {

                $xyArray = $this->getXYAxis($value['mappath']);

                if (!empty($xyArray) && is_array($xyArray)) {
                    $objPolygon = new PolygonsController(array('x'=>$lat, 'y'=>$lng), $xyArray);

                    if ($objPolygon->check()) {
                        $deliveryCharge = $value['service_delivery_charge'];
                        $message        = 'success';
                        break;
                    }
                    else {
                        $message = 'failed';
                    }
                }
            }
        } else {
            $message = 'failed';
        }

        return array($deliveryCharge,$message);
    }

    //Get XYAxis for location
    public function getXYAxis($mapPath) {
        $xy = explode(',',$mapPath);
        $xyArray = array();

        if (!empty($xy) && is_array($xy)) {
            foreach ($xy as $x=>$y) {

                $xyAxis = explode(' ',trim($y));
                $xyArray[] = array('y'=>$xyAxis[0], 'x'=>$xyAxis[1]);

            }
        }
        return $xyArray;
    }

    //--------------------------------------Get Delivery Charge --------------------------------------------------------

    //--------------------------------------Clear Cart Functionality----------------------------------------------------

    public function clearCart() {

        session_regenerate_id();
        $this->request->session()->write('sessionId','');

        $sessionId = session_id();
        $this->request->session()->write('sessionId',$sessionId);
        echo $sessionId;
        die();

    }

    //--------------------------------------Clear Cart Functionality----------------------------------------------------


    //---------------------------Ajaxaction For Cart Update,Insert,Delete and Get Addons--------------------------------

    public function ajaxaction() {

        if($this->request->getData('action') == 'cartUpdate') {
            //echo "<pre>"; print_r($this->request->getData()); die();
            $currentStatus = $this->request->session()->read('currentStatus');

            //check Previous Cart
            $previousCart = $this->Carts->find('all', [
                'conditions' => [
                    'session_id' => $this->request->session()->read('sessionId')
                ]
            ])->hydrate(false)->first();

            if(!empty($previousCart)) {
                if($this->request->getData('resid') != $previousCart['restaurant_id']) {
                    echo '0';die();
                }
            }

            $menuDetails = $this->RestaurantMenus->find('all', [
                'conditions' => [
                    'RestaurantMenus.id' => $this->request->getData('menuid')
                ],
                'contain' => [
                    'MenuDetails'
                ]
            ])->hydrate(false)->first();

            if($menuDetails['restaurant_id'] != $this->request->session()->read('resid')) {
                session_regenerate_id();
                $this->request->session()->write('sessionId','');
            }



            if($this->request->session()->read('sessionId') != '') {
                $sessionId =  $this->request->session()->read('sessionId');
            }else {
                $sessionId = session_id();
                $this->request->session()->write('sessionId',$sessionId);
                $this->request->session()->write('resid',$menuDetails['restaurant_id']);
            }



            //Menu Details
            $detailMenus= $this->MenuDetails->find('all', [
                'conditions' => [
                    'id' => $this->request->getData('id')
                ]
            ])->hydrate(false)->first();
            //pr($detailMenus);die();

            $mainAddonsName = '';
            $subAddonsPrice = 0;
            //Get Subaddons List
            if(!empty($this->request->getData('subaddons'))) {

                $addonsList = explode(',',$this->request->getData('subaddons'));
                if(!empty($addonsList)) {
                    $subAddonsName = '';
                    $mainAddonId = '';

                    foreach ($addonsList as $key => $value) {
                        if($value != '') {
                            $menuAddonsLists = $this->MenuAddons->find('all', [
                                'conditions' => [
                                    'subaddons_id' => $value,
                                    'menudetails_id' => $this->request->getData('id'),
                                    'menu_id' => $this->request->getData('menuid'),
                                ],
                                'contain' => [
                                    'Subaddons' => [
                                        'fields' => [
                                            'Subaddons.subaddons_name'
                                        ]
                                    ],
                                    'Mainaddons' => [
                                        'fields' => [
                                            'Mainaddons.mainaddons_name'
                                        ]
                                    ]
                                ]
                            ])->hydrate(false)->first();
                            //pr($menuAddonsLists);die();

                            if(!empty($menuAddonsLists)) {
                                if($menuAddonsLists['mainaddons_id'] != $mainAddonId) {
                                    if($mainAddonId != '') {
                                        $moreItem = '<br>';
                                    }else {
                                        $moreItem = '';
                                    }

                                    $subaddons_price = ($menuAddonsLists['subaddons_price'] >0) ? number_format($menuAddonsLists['subaddons_price'],2) : 'Free';
                                    $mainAddonsName .= $moreItem.''.$menuAddonsLists['mainaddon']['mainaddons_name'].' : '.$menuAddonsLists['subaddon']['subaddons_name'].'['.$subaddons_price.']';
                                    $mainAddonId = $menuAddonsLists['mainaddons_id'];

                                }else {

                                    $subaddons_price = ($menuAddonsLists['subaddons_price'] >0) ? number_format($menuAddonsLists['subaddons_price'],2) : 'Free';
                                    $mainAddonsName .= ' + '.$menuAddonsLists['subaddon']['subaddons_name'].'['.$subaddons_price.']';
                                }

                                $subAddonsPrice += $menuAddonsLists['subaddons_price'];                               
                            }
                        }
                    }
                }
                $subaddons_name = '['.$detailMenus['sub_name'].']'.'<br>'.$mainAddonsName;
                $menuPrice = $subAddonsPrice + $detailMenus['orginal_price'];

            }else {
                if($menuDetails['price_option'] == 'multiple') {
                    $subaddons_name = $detailMenus['sub_name'];
                    $menuPrice = $detailMenus['orginal_price'];

                }else {
                    $subaddons_name = '';
                    $menuPrice = $menuDetails['menu_details'][0]['orginal_price'];
                }
            }

            //echo'sundar--------'.$sessionId;die();

            $cartDetails = $this->Carts->find('all', [
                'fields' => [
                    'id',
                    'quantity'
                ],
                'conditions' => [
                    'menu_id' => $this->request->getData('menuid'),
                    'session_id' => $sessionId,
                    'restaurant_id' => $this->request->session()->read('resid'),
                    'subaddons_name' => $subaddons_name,
                ]
            ])->hydrate(false)->first();



            if(!empty($cartDetails)) {

                if($this->request->getData('quantity') == '') {
                    if($this->request->getData('type') == 'add') {
                        $cartUpdate['quantity'] = $cartDetails['quantity'] +1;
                    }else {
                        $cartUpdate['quantity'] = $cartDetails['quantity'] -1;
                    }
                }else {

                    $cartUpdate['quantity'] = $cartDetails['quantity'] +$this->request->getData('quantity');
                }



                $cartUpdate['total_price'] = $menuPrice * $cartUpdate['quantity'];

                $cartEntity = $this->Carts->newEntity();
                $cartPatch = $this->Carts->patchEntity($cartEntity,$cartUpdate);
                $cartPatch->id = $cartDetails['id'];
                $cartSave = $this->Carts->save($cartPatch);

                if($cartUpdate['quantity'] == 0) {

                    $entity = $this->Carts->get($cartDetails['id']);
                    $result = $this->Carts->delete($entity);
                }
            }else {
                if($this->request->getData('quantity') == '') {
                    $cartUpdate['quantity'] = 1;
                }else {
                    $cartUpdate['quantity'] = $this->request->getData('quantity');
                }

                $cartUpdate['session_id'] = $sessionId;
                $cartUpdate['menu_id'] = $this->request->getData('menuid');
                $cartUpdate['menu_name'] = $menuDetails['menu_name'];
                $cartUpdate['subaddons_name'] = $subaddons_name;
                $cartUpdate['menu_price'] = $menuPrice;
                $cartUpdate['restaurant_id'] = $menuDetails['restaurant_id'];
                $cartUpdate['category_id'] = $menuDetails['category_id'];
                $cartUpdate['total_price'] = $menuPrice * $cartUpdate['quantity'];

                $cartEntity = $this->Carts->newEntity();
                $cartPatch = $this->Carts->patchEntity($cartEntity,$cartUpdate);
                $cartSave = $this->Carts->save($cartPatch);
            }

            $cartsDetails = $this->Carts->find('all', [
                'conditions' => [
                    'session_id' => $sessionId,
                ],
                'contain' => [
                    'RestaurantMenus'
                ],
                'order' => [
                    'Carts.menu_id' => 'ASC'
                ]
            ])->hydrate(false)->toArray();

            $restaurantDetails = $this->Restaurants->find('all', [
                'conditions' => [
                    'id' => $menuDetails['restaurant_id']
                ]
            ])->hydrate(false)->first();

            if(SEARCHBY != 'Google') {
                $restaurantDetails = $this->Restaurants->find('all', [
                    'conditions' => [
                        'id' => $menuDetails['restaurant_id']
                    ],
                    'contain' =>[
                        'DeliveryLocations' => [
                            'conditions' => [
                                'city_id' => $this->request->session()->read('city_id'),
                                'location_id' => $this->request->session()->read('location_id')
                            ]

                        ]
                    ]

                ])->hydrate(false)->first();
                //pr($restaurantDetails);die();

                if(isset($restaurantDetails['delivery_locations'][0]['minimum_order']) && $restaurantDetails['delivery_locations'][0]['minimum_order'] != '') {
                    $restaurantDetails['minimum_order'] = $restaurantDetails['delivery_locations'][0]['minimum_order'];
                }else {
                    $restaurantDetails['minimum_order'] = '0.00';
                }


            }else {
                $restaurantDetails = $this->Restaurants->find('all', [
                    'conditions' => [
                        'id' => $menuDetails['restaurant_id']
                    ]
                ])->hydrate(false)->first();
            }

            $subTotal = 0;
            $taxAmount = 0;
            if(!empty($cartsDetails)) {
                foreach($cartsDetails as $ckey => $cvalue) {
                    $subTotal = $cvalue['total_price'] + $subTotal;
                }
                if($restaurantDetails['restaurant_tax'] > 0) {
                    $taxAmount = ($subTotal * $restaurantDetails['restaurant_tax'])/100;
                }
            }

            if(SEARCHBY == 'Google') {
                if($restaurantDetails['free_delivery'] > 0 && $restaurantDetails['free_delivery'] <= $subTotal) {
                    $deliveryCharge = 0.00;
                }else {
                    $deliveryCharge = $this->request->session()->read('delivery_charge');
                }
            }else {
                $deliveryCharge = $this->request->session()->read('delivery_charge');
            }
            //$deliveryCharge = $restaurantDetails['delivery_charge'];


            $totalAmount = $subTotal + $taxAmount + $deliveryCharge;

            $withOutDelivery = $subTotal + $taxAmount;

            $minimumOrder = $restaurantDetails['minimum_order'];

            $currentDate = strtotime(date('Y-m-d'));

            $checkOffers = $this->Offers->find('all', [
                'conditions' => [
                    'resid' => $this->request->session()->read('resid'),
                    'status' => '1',
                ],
                'order' => [
                    'id' => 'DESC'
                ]

            ])->hydrate(false)->toArray();


            $firstUser = 'No';
            $normalOffer = 'No';
            $offerAmount = '';
            $offerPercentage = '';
            if(!empty($checkOffers)) {

                foreach($checkOffers as $cKey => $cValue) {

                    $startDate = date('Y-m-d',strtotime($cValue['offer_from']));
                    $toDate = date('Y-m-d',strtotime($cValue['offer_to']));

                    if($currentDate >= strtotime($startDate) && $currentDate <= strtotime($toDate)) {
                        if($cValue['first_user'] == 'Y') {
                            $orderCount = $this->Orders->find('all', [
                                'conditions' => [
                                    'customer_id' => $this->Auth->user('id')
                                ]
                            ])->count();


                            if($orderCount == 0) {
                                if($subTotal >= $cValue['free_price']) {
                                    $firstUser = 'Yes';
                                    $offerAmount = $subTotal * $cValue['free_percentage']/100;
                                    $offerPercentage = $cValue['free_percentage'];

                                    $this->request->session()->write('offerAmount',$offerAmount);
                                    $this->request->session()->write('firstUser',$firstUser);
                                    $this->request->session()->write('offerPercentage',$offerPercentage);

                                    $totalAmount = $totalAmount - $offerAmount;
                                }else {
                                    $this->request->session()->write('offerAmount',$offerAmount);
                                    $this->request->session()->write('firstUser',$firstUser);
                                    $this->request->session()->write('offerPercentage',$offerPercentage);
                                }
                            }else if($cValue['normal'] == 'Y') {
                                if($subTotal >= $cValue['normal_price']) {


                                    $normalOffer = 'Yes';
                                    $offerAmount = $subTotal * $cValue['normal_percentage'] / 100;
                                    $offerPercentage = $cValue['normal_percentage'];

                                    $this->request->session()->write('offerAmount', $offerAmount);
                                    $this->request->session()->write('normalOffer', $normalOffer);
                                    $this->request->session()->write('offerPercentage', $offerPercentage);

                                    $totalAmount = $totalAmount - $offerAmount;
                                    $withOutDelivery = $withOutDelivery - $offerAmount;


                                }else {
                                    $this->request->session()->write('offerAmount','');
                                    $this->request->session()->write('normalOffer','');
                                    $this->request->session()->write('offerPercentage','');
                                }

                            }else {
                                $this->request->session()->write('offerAmount','');
                                $this->request->session()->write('normalOffer','');
                                $this->request->session()->write('offerPercentage','');
                            }
                            break;
                        }else if($cValue['normal'] == 'Y') {

                            if($subTotal >= $cValue['normal_price']) {

                                $normalOffer = 'Yes';
                                $offerAmount = $subTotal * $cValue['normal_percentage'] / 100;

                                $offerPercentage = $cValue['normal_percentage'];

                                $this->request->session()->write('offerAmount', $offerAmount);
                                $this->request->session()->write('normalOffer', $normalOffer);
                                $this->request->session()->write('offerPercentage', $offerPercentage);

                                $totalAmount = $totalAmount - $offerAmount;


                            }else {
                                $this->request->session()->write('offerAmount','');
                                $this->request->session()->write('normalOffer','');
                                $this->request->session()->write('offerPercentage','');
                            }

                            break;
                        }else {
                            $this->request->session()->write('offerAmount','');
                            $this->request->session()->write('normalOffer','');
                            $this->request->session()->write('offerPercentage','');
                        }
                    }
                }

            }else {
                $this->request->session()->write('offerAmount','');
                $this->request->session()->write('normalOffer','');
                $this->request->session()->write('offerPercentage','');
            }

            //Reward Section:
            $rewardPoint = $this->Rewards->find('all', [
                'conditions' => [
                    'id' => 1,
                    'reward_option' => 'Yes'
                ]
            ])->hydrate(false)->first();

            $getRestaurantOption = $this->Restaurants->find('all', [
                'fields' => [
                    'reward_option'
                ],
                'conditions' => [
                    'id' => $this->request->session()->read('resid'),
                ]
            ])->hydrate(false)->first();

            if(!empty($rewardPoint) && $getRestaurantOption['reward_option'] == 'Yes') {

                if(!empty($this->Auth->user()) && $this->Auth->user('role_id') == '3') {

                    $customerPoints = $this->CustomerPoints->find('all', [
                        'conditions' => [
                            'customer_id' => $this->Auth->user('id'),
                            'status' => '1'
                        ]
                    ])->hydrate(false)->toArray();

                    $createdDate = explode(' ', $rewardPoint['created']);

                    $date1 = date_create($createdDate[0]);
                    $date2 = date_create(date('Y-m-d'));
                    $diff = date_diff($date1, $date2);
                    $diff = $diff->format("%R%a");


                    $remainingDays = ($rewardPoint['reward_validity'] - $diff);
                    $resPercent = $rewardPoint['reward_percentage'];

                    $previousCount = count($customerPoints) + 1;

                    if (($remainingDays > 0) && ($previousCount >= $rewardPoint['redeem_order'])) {

                        $customerPoints = $this->CustomerPoints->find('all', [
                            'fields' => [
                                'total_points' => 'SUM(points)'
                            ],
                            'conditions' => [
                                'customer_id' => $this->Auth->user('id'),
                                'status' => '1'
                            ]
                        ])->hydrate(false)->toArray();

                        $userPoints = $customerPoints[0]['total_points'];
                        $resPoints = $rewardPoint['reward_totalpoint'];

                        if ($userPoints >= $resPoints) {
                            $totPercent = (abs($userPoints / $resPoints) * $resPercent);

                            $offPercent = $subTotal * abs($totPercent / 100);
                            $this->request->session()->write('rewardPoint', $offPercent);
                            $this->request->session()->write('rewardPercentage', number_format($totPercent, 2));
                            $totalAmount = $totalAmount - $offPercent;
                        } else {
                            $this->request->session()->write('rewardPoint', '');
                        }
                    } else {
                        $this->request->session()->write('rewardPoint', '');
                    }

                    $grandTotal = $subTotal;
                    $rewardAmount = $rewardPoint['reward_amount'];
                    $rewardPoints = $rewardPoint['reward_point'];

                    $getRewardFromTotal = $grandTotal/$rewardAmount;

                    $orderPoint = $getRewardFromTotal * $rewardPoints;
                    $this->request->session()->write('orderPoint',round($orderPoint));


                }else {
                    $grandTotal = $subTotal;
                    $rewardAmount = $rewardPoint['reward_amount'];
                    $rewardPoints = $rewardPoint['reward_point'];

                    $getRewardFromTotal = $grandTotal/$rewardAmount;

                    $orderPoint = $getRewardFromTotal * $rewardPoints;
                    $this->request->session()->write('orderPoint',round($orderPoint));
                    $this->request->session()->write('rewardPoint','');
                }

            }else {
                $this->request->session()->write('rewardPoint','');
                $this->request->session()->write('orderPoint','');
            }

            if($subTotal >= $minimumOrder ) {
                $minimumOrder = '1';
            }else {
                $minimumOrder = '0';
            }

            //pr($cartsDetails);die();
            $action = $this->request->getData('action');
            $orderType = $this->request->getData('orderType');
            $this->set(compact('action','cartsDetails','taxAmount','subTotal','totalAmount','deliveryCharge','minimumOrder','restaurantDetails','withOutDelivery','orderType','currentStatus'));

        }
        if($this->request->getData('action') == 'getTiming') {


            if($this->request->getData('date') != '') {

                $restaurantDetails = $this->Restaurants->find('all', [
                    'conditions' => [
                        'id' => $this->request->session()->read('resid')
                    ]
                ])->hydrate(false)->first();

                //pr($restaurantDetails);die;
                //Timing Section
                $array_of_time = array ();

                $nowTime = date('h:i A');
                //$nowTime = '12.35 PM';

                $currentTime = strtotime($nowTime);
                $currentDate = $this->request->getData('date');

                $today = date('Y-m-d');

                $currentDay = strtolower(date('l',strtotime($currentDate)));
                //$currentDay = 'monday';

                $firstStartTime = $restaurantDetails[$currentDay.'_first_opentime'];
                $firstEndTime = $restaurantDetails[$currentDay.'_first_closetime'];

                $secondStartTime = $restaurantDetails[$currentDay.'_second_opentime'];
                $secondEndTime = $restaurantDetails[$currentDay.'_second_closetime'];


                $firstOpenTime = strtotime($restaurantDetails[$currentDay.'_first_opentime']);
                $firstCloseTime = strtotime($restaurantDetails[$currentDay.'_first_closetime']);

                $secondOpenTime = strtotime($restaurantDetails[$currentDay.'_second_opentime']);
                $secondCloseTime = strtotime($restaurantDetails[$currentDay.'_second_closetime']);


                //In first Timing section
                if($restaurantDetails[$currentDay.'_status'] != 'Close') {

                    $restaurantDetails['currentStatus'] = 'Open';
                    $final[] = $restaurantDetails;

                    if(strtotime($today) != strtotime($currentDate)) {

                        $nowTime = date("h:i A", strtotime('+45 minutes', $firstOpenTime));

                        $start_time = strtotime($currentDate . ' ' . $nowTime);
                        $end_time = strtotime($currentDate . ' ' . $firstEndTime);

                        $fifteen_mins = 15 * 60;

                        while ($start_time <= $end_time) {
                            $array_of_time[date("D h:i A", $start_time)] = date("D h:i A", $start_time);
                            $start_time += $fifteen_mins;
                        }

                        $secondStartTime = date("h:i A", strtotime('+45 minutes', $secondOpenTime));
                        $start_time = strtotime($currentDate . ' ' . $secondStartTime);
                        $end_time = strtotime($currentDate . ' ' . $secondEndTime);

                        $fifteen_mins = 15 * 60;

                        while ($start_time <= $end_time) {
                            $array_of_time[date("D h:i A", $start_time)] = date("D h:i A", $start_time);
                            $start_time += $fifteen_mins;
                        }

                    }else {

                        if($currentTime < $firstOpenTime) {

                            $firstOpenTime = date("h:i A", strtotime('+45 minutes', $firstOpenTime));

                            $start_time = strtotime($currentDate . ' ' . $firstOpenTime);
                            $end_time = strtotime($currentDate . ' ' . $firstEndTime);

                            $fifteen_mins = 15 * 60;

                            while ($start_time <= $end_time) {
                                $array_of_time[date("D h:i A", $start_time)] = date("D h:i A", $start_time);
                                $start_time += $fifteen_mins;
                            }

                            $secondStartTime = date("h:i A", strtotime('+45 minutes', $secondOpenTime));
                            $start_time = strtotime($currentDate . ' ' . $secondStartTime);
                            $end_time = strtotime($currentDate . ' ' . $secondEndTime);

                            $fifteen_mins = 15 * 60;

                            while ($start_time <= $end_time) {
                                $array_of_time[date("D h:i A", $start_time)] = date("D h:i A", $start_time);
                                $start_time += $fifteen_mins;
                            }

                        }

                        if($currentTime > $firstOpenTime && $currentTime <= $firstCloseTime) {


                            $nowTime = date("h:i A", strtotime('+45 minutes', $currentTime));

                            $start_time = strtotime($currentDate . ' ' . $nowTime);
                            $end_time = strtotime($currentDate . ' ' . $firstEndTime);

                            $fifteen_mins = 15 * 60;

                            while ($start_time <= $end_time) {
                                $array_of_time[date("D h:i A", $start_time)] = date("D h:i A", $start_time);
                                $start_time += $fifteen_mins;
                            }

                            $secondStartTime = date("h:i A", strtotime('+45 minutes', $secondOpenTime));
                            $start_time1 = strtotime($currentDate . ' ' . $secondStartTime);
                            $end_time1 = strtotime($currentDate . ' ' . $secondEndTime);

                            $fifteen_mins = 15 * 60;

                            while ($start_time1 <= $end_time1) {
                                $array_of_time[date("D h:i A", $start_time1)] = date("D h:i A", $start_time1);
                                $start_time1 += $fifteen_mins;
                            }

                        }

                        if(empty($array_of_time)) {

                            if($currentTime > $firstCloseTime && $currentTime < $secondOpenTime ) {

                                $secondStartTime = date("h:i A", strtotime('+45 minutes', $secondOpenTime));
                                $start_time = strtotime($currentDate . ' ' . $secondStartTime);
                                $end_time = strtotime($currentDate . ' ' . $secondEndTime);

                                $fifteen_mins = 15 * 60;

                                while ($start_time <= $end_time) {
                                    $array_of_time[date("D h:i A", $start_time)] = date("D h:i A", $start_time);
                                    $start_time += $fifteen_mins;
                                }

                            }
                        }

                        if($currentTime > $secondOpenTime && $currentTime <= $secondCloseTime) {


                            $currentTime = date("h:i A", strtotime('+45 minutes', $currentTime));
                            $start_time = strtotime($currentDate . ' ' . $currentTime);
                            $end_time = strtotime($currentDate . ' ' . $secondEndTime);

                            $fifteen_mins = 15 * 60;

                            while ($start_time <= $end_time) {
                                $array_of_time[date("D h:i A", $start_time)] = date("D h:i A", $start_time);
                                $start_time += $fifteen_mins;
                            }
                        }
                    }
                }
                $action = $this->request->getData('action');
                $this->set(compact('array_of_time','action'));
            }

        }

        if($this->request->getData('action') == 'cartEdit') {

            $currentStatus = $this->request->session()->read('currentStatus');

            $cartDetails = $this->Carts->find('all', [
                'fields' => [
                    'id',
                    'quantity',
                    'total_price',
                    'restaurant_id',
                    'menu_price'
                ],
                'conditions' => [
                    'id' => $this->request->getData('id'),

                ]
            ])->hydrate(false)->first();

            /*if($cartDetails['restaurant_id'] != $this->request->session()->read('resid')) {
                session_regenerate_id();
                $this->request->session()->write('sessionId','');
            }*/

            if($this->request->session()->read('sessionId') != '') {
                $sessionId =  $this->request->session()->read('sessionId');
                $this->request->session()->write('resid',$cartDetails['restaurant_id']);

            }else {
                $sessionId = session_id();
                $this->request->session()->write('sessionId',$sessionId);
                $this->request->session()->write('resid',$cartDetails['restaurant_id']);
            }

            if($this->request->getData('type') != 'delete') {
                if($this->request->getData('type') == 'add') {
                    $cartUpdate['quantity'] = $cartDetails['quantity'] +1;
                }else {
                    $cartUpdate['quantity'] = $cartDetails['quantity'] -1;
                }



                $cartUpdate['total_price'] = $cartDetails['menu_price'] * $cartUpdate['quantity'];

                $cartUpdate['restaurant_id'] = $this->request->session()->read('resid');

                $cartEntity = $this->Carts->newEntity();
                $cartPatch = $this->Carts->patchEntity($cartEntity,$cartUpdate);
                $cartPatch->id = $cartDetails['id'];
                $cartSave = $this->Carts->save($cartPatch);

                if($cartUpdate['quantity'] == 0) {

                    $entity = $this->Carts->get($cartDetails['id']);
                    $result = $this->Carts->delete($entity);
                }
            }else {
                $this->Carts->deleteAll([
                    'id' => $this->request->getData('id')
                ]);
            }

            $cartsDetails = $this->Carts->find('all', [
                'conditions' => [
                    'session_id' => $sessionId,
                ],
                'contain' => [
                    'RestaurantMenus'
                ],
                'order' => [
                    'Carts.menu_id' => 'ASC'
                ]
            ])->hydrate(false)->toArray();

            if(SEARCHBY != 'Google') {
                $restaurantDetails = $this->Restaurants->find('all', [
                    'conditions' => [
                        'id' => $this->request->session()->read('resid')
                    ],
                    'contain' =>[
                        'DeliveryLocations' => [
                            'conditions' => [
                                'city_id' => $this->request->session()->read('city_id'),
                                'location_id' => $this->request->session()->read('location_id')
                            ]

                        ]
                    ]

                ])->hydrate(false)->first();
                //pr($restaurantDetails);die();

                $restaurantDetails['minimum_order'] = $restaurantDetails['delivery_locations'][0]['minimum_order'];

            }else {
                $restaurantDetails = $this->Restaurants->find('all', [
                    'conditions' => [
                        'id' => $this->request->session()->read('resid')
                    ]
                ])->hydrate(false)->first();
            }



            $subTotal = 0;
            $taxAmount = 0;
            if(!empty($cartsDetails)) {
                foreach($cartsDetails as $ckey => $cvalue) {
                    $subTotal = $cvalue['total_price'] + $subTotal;
                }
                if($restaurantDetails['restaurant_tax'] > 0) {
                    $taxAmount = ($subTotal * $restaurantDetails['restaurant_tax'])/100;
                }
            }

            if(SEARCHBY == 'Google') {
                if($restaurantDetails['free_delivery'] > 0 && $restaurantDetails['free_delivery'] <= $subTotal) {
                    $deliveryCharge = 0.00;
                }else {
                    $deliveryCharge = $this->request->session()->read('delivery_charge');
                }
            }else {
                $deliveryCharge = $this->request->session()->read('delivery_charge');
            }
            //$deliveryCharge = $restaurantDetails['delivery_charge'];
            //$deliveryCharge = $this->request->session()->read('delivery_charge');

            $totalAmount = $subTotal + $taxAmount + $deliveryCharge;

            $withOutDelivery = $subTotal + $taxAmount;

            $minimumOrder = $restaurantDetails['minimum_order'];

            $currentDate = strtotime(date('Y-m-d'));

            $checkOffers = $this->Offers->find('all', [
                'conditions' => [
                    'resid' => $this->request->session()->read('resid'),
                    'status' => '1',
                ],
                'order' => [
                    'id' => 'DESC'
                ]

            ])->hydrate(false)->toArray();

            $firstUser = 'No';
            $normalOffer = 'No';
            $offerAmount = '';
            $offerPercentage = '';
            if(!empty($checkOffers)) {

                foreach($checkOffers as $cKey => $cValue) {

                    $startDate = date('Y-m-d',strtotime($cValue['offer_from']));
                    $toDate = date('Y-m-d',strtotime($cValue['offer_to']));

                    if($currentDate >= strtotime($startDate) && $currentDate <= strtotime($toDate)) {
                        if($cValue['first_user'] == 'Y') {
                            $orderCount = $this->Orders->find('all', [
                                'conditions' => [
                                    'customer_id' => $this->Auth->user('id')
                                ]
                            ])->count();


                            if($orderCount == 0) {
                                if($subTotal >= $cValue['free_price']) {
                                    $firstUser = 'Yes';
                                    $offerAmount = $subTotal * $cValue['free_percentage']/100;
                                    $offerPercentage = $cValue['free_percentage'];

                                    $this->request->session()->write('offerAmount',$offerAmount);
                                    $this->request->session()->write('firstUser',$firstUser);
                                    $this->request->session()->write('offerPercentage',$offerPercentage);

                                    $totalAmount = $totalAmount - $offerAmount;
                                }else {
                                    $this->request->session()->write('offerAmount',$offerAmount);
                                    $this->request->session()->write('firstUser',$firstUser);
                                    $this->request->session()->write('offerPercentage',$offerPercentage);
                                }
                            }else if($cValue['normal'] == 'Y') {
                                if($subTotal >= $cValue['normal_price']) {


                                    $normalOffer = 'Yes';
                                    $offerAmount = $subTotal * $cValue['normal_percentage'] / 100;
                                    $offerPercentage = $cValue['normal_percentage'];

                                    $this->request->session()->write('offerAmount', $offerAmount);
                                    $this->request->session()->write('normalOffer', $normalOffer);
                                    $this->request->session()->write('offerPercentage', $offerPercentage);

                                    $totalAmount = $totalAmount - $offerAmount;
                                    $withOutDelivery = $withOutDelivery - $offerAmount;


                                }else {
                                    $this->request->session()->write('offerAmount','');
                                    $this->request->session()->write('normalOffer','');
                                    $this->request->session()->write('offerPercentage','');
                                }

                            }else {
                                $this->request->session()->write('offerAmount','');
                                $this->request->session()->write('normalOffer','');
                                $this->request->session()->write('offerPercentage','');
                            }
                            break;
                        }else if($cValue['normal'] == 'Y') {

                            if($subTotal >= $cValue['normal_price']) {

                                $normalOffer = 'Yes';
                                $offerAmount = $subTotal * $cValue['normal_percentage'] / 100;

                                $offerPercentage = $cValue['normal_percentage'];

                                $this->request->session()->write('offerAmount', $offerAmount);
                                $this->request->session()->write('normalOffer', $normalOffer);
                                $this->request->session()->write('offerPercentage', $offerPercentage);

                                $totalAmount = $totalAmount - $offerAmount;


                            }else {
                                $this->request->session()->write('offerAmount','');
                                $this->request->session()->write('normalOffer','');
                                $this->request->session()->write('offerPercentage','');
                            }

                            break;
                        }else {
                            $this->request->session()->write('offerAmount','');
                            $this->request->session()->write('normalOffer','');
                            $this->request->session()->write('offerPercentage','');
                        }
                    }
                }

            }else {
                $this->request->session()->write('offerAmount','');
                $this->request->session()->write('normalOffer','');
                $this->request->session()->write('offerPercentage','');
            }

            //Reward Section:
            $rewardPoint = $this->Rewards->find('all', [
                'conditions' => [
                    'id' => 1,
                    'reward_option' => 'Yes'
                ]
            ])->hydrate(false)->first();

            $getRestaurantOption = $this->Restaurants->find('all', [
                'fields' => [
                    'reward_option'
                ],
                'conditions' => [
                    'id' => $this->request->session()->read('resid'),
                ]
            ])->hydrate(false)->first();

            if(!empty($rewardPoint) && $getRestaurantOption['reward_option'] == 'Yes') {

                if(!empty($this->Auth->user()) && $this->Auth->user('role_id') == '3') {

                    $customerPoints = $this->CustomerPoints->find('all', [
                        'conditions' => [
                            'customer_id' => $this->Auth->user('id'),
                            'status' => '1'
                        ]
                    ])->hydrate(false)->toArray();

                    $createdDate = explode(' ', $rewardPoint['created']);

                    $date1 = date_create($createdDate[0]);
                    $date2 = date_create(date('Y-m-d'));
                    $diff = date_diff($date1, $date2);
                    $diff = $diff->format("%R%a");


                    $remainingDays = ($rewardPoint['reward_validity'] - $diff);
                    $resPercent = $rewardPoint['reward_percentage'];

                    $previousCount = count($customerPoints) + 1;

                    if (($remainingDays > 0) && ($previousCount >= $rewardPoint['redeem_order'])) {

                        $customerPoints = $this->CustomerPoints->find('all', [
                            'fields' => [
                                'total_points' => 'SUM(points)'
                            ],
                            'conditions' => [
                                'customer_id' => $this->Auth->user('id'),
                                'status' => '1'
                            ]
                        ])->hydrate(false)->toArray();

                        $userPoints = $customerPoints[0]['total_points'];
                        $resPoints = $rewardPoint['reward_totalpoint'];

                        if ($userPoints >= $resPoints) {
                            $totPercent = (abs($userPoints / $resPoints) * $resPercent);

                            $offPercent = $subTotal * abs($totPercent / 100);
                            $this->request->session()->write('rewardPoint', $offPercent);
                            $this->request->session()->write('rewardPercentage', number_format($totPercent, 2));
                            $totalAmount = $totalAmount - $offPercent;
                        } else {
                            $this->request->session()->write('rewardPoint', '');
                        }
                    } else {
                        $this->request->session()->write('rewardPoint', '');
                    }

                    $grandTotal = $subTotal;
                    $rewardAmount = $rewardPoint['reward_amount'];
                    $rewardPoints = $rewardPoint['reward_point'];

                    $getRewardFromTotal = $grandTotal/$rewardAmount;

                    $orderPoint = $getRewardFromTotal * $rewardPoints;
                    $this->request->session()->write('orderPoint',round($orderPoint));


                }else {
                    $grandTotal = $subTotal;
                    $rewardAmount = $rewardPoint['reward_amount'];
                    $rewardPoints = $rewardPoint['reward_point'];

                    $getRewardFromTotal = $grandTotal/$rewardAmount;

                    $orderPoint = $getRewardFromTotal * $rewardPoints;
                    $this->request->session()->write('orderPoint',round($orderPoint));
                    $this->request->session()->write('rewardPoint','');
                }

            }else {
                $this->request->session()->write('rewardPoint','');
                $this->request->session()->write('orderPoint','');
            }

            if($subTotal >= $minimumOrder ) {
                $minimumOrder = '1';
            }else {
                $minimumOrder = '0';
            }

            //pr($cartsDetails);die();
            $action = 'cartUpdate';
            $search = SEARCHBY;
            $orderType = $this->request->getData('orderType');
            $this->set(compact('action','cartsDetails','taxAmount','subTotal','totalAmount','deliveryCharge','minimumOrder','restaurantDetails','withOutDelivery','orderType','currentStatus','search'));

        }

        if($this->request->getData('action') == 'getMenuDetails') {

            if($this->request->getData('menuid') != '') {
                $menuDetails = $this->RestaurantMenus->find('all', [
                    'conditions' => [
                        'id' => $this->request->getData('menuid')
                    ],
                    'contain' => [
                        'MenuDetails.MenuAddons.Mainaddons',
                        'MenuDetails.MenuAddons.Subaddons',

                    ]
                ])->hydrate(false)->first();


               //=============================================================================

                $details = [];
                $addons = [];
                foreach ($menuDetails['menu_details'] as $key => $value) {
                    $details[$value['id']] = [
                        'id' => $value['id'],
                        'menu_id' => $value['menu_id'],
                        'sub_name' => $value['sub_name'],
                        'orginal_price' => $value['orginal_price']
                    ];

                    foreach ($value['menu_addons'] as $mKey => $mValue) {
                        if($mValue['mainaddon']['status'] == 1) {
                            $addons[$mValue['mainaddons_id']]['mainaddons_name'] = $mValue['mainaddon']['mainaddons_name'];
                            $addons[$mValue['mainaddons_id']]['mainaddons_mini_count'] = $mValue['mainaddon']['mainaddons_mini_count'];
                            $addons[$mValue['mainaddons_id']]['mainaddons_count'] = $mValue['mainaddon']['mainaddons_count'];
                            $addons[$mValue['mainaddons_id']]['sub_addons'][$mValue['subaddons_id']]['name']
                                = $mValue['subaddon']['subaddons_name'];
                            $addons[$mValue['mainaddons_id']]['sub_addons'][$mValue['subaddons_id']]['price'][$value['id']]
                                = $mValue['subaddons_price'];
                        }

                    }
                    //$addons['MainAddon']
                    //break;

                }

                //pr($menuDetails);die();
               // pr($details);
                //pr($addons); die();
                $action = $this->request->getData('action');
                $menuId = $this->request->getData('menuid');
                $this->set(compact('details','action','addons','menuId','menuDetails'));
            }
        }

        if($this->request->getData('action') == 'getDetails') {
            $menuAddonsList = $this->MenuDetails->find('all',[
                'conditions' => [
                    'id' => $this->request->getData('id')
                ],
                'contain' => [
                    'MenuAddons.Mainaddons',
                    'MenuAddons.Subaddons',

                ]
            ])->hydrate(false)->toArray();

            $menuDetails = $this->RestaurantMenus->find('all', [
                'conditions' => [
                    'id' => $this->request->getData('menuId')
                ],
                'contain' => [
                    'MenuDetails'

                ]
            ])->hydrate(false)->first();




            //pr($menuAddonsList);die();

            $action = $this->request->getData('action');
            $id = $this->request->getData('id');
            $menuId = $this->request->getData('menuId');
            $this->set(compact('menuDetails','action','menuAddonsList','menuId','id'));



        }
    }

    //-----------------------------------------Get Order Type in Session------------------------------------------------

    public function orderType() {
        $this->request->session()->write('orderType',$this->request->getData('type'));
        die();
    }

    //-----------------------------------------Facebook Ordering Functionality------------------------------------------

    public function parse_signed_request($signed_request)
    {

        list($encoded_sig, $payload) = explode('.', $signed_request, 2);

        // decode the data
        $sig = $this->base64_url_decode($encoded_sig);
        $data = json_decode($this->base64_url_decode($payload), true);
        return $data;
    }
    public function base64_url_decode($input)
    {
        return base64_decode(strtr($input, '-_', '+/'));
    }

    public function getPageInfo($pageid) {

        $siteDetails = $this->Sitesettings->find('all', [
            'fields' => [
                'facebook_api_id',
                'facebook_secret_key',
            ]
        ])->hydrate(false)->first();

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

    // Social login check
    public function checkFacebookDetails($incomingProfile){

        $existingProfile = $this->Users->find('all', [
            'conditions' => [
                'username' => $incomingProfile['email'],
                'role_id !=' => '3'
            ]
        ])->count();
        if ($existingProfile == 0) {

            $existingProfile = $this->Users->find('all', [
                'conditions' => [
                    'username' => $incomingProfile['email'],
                    'role_id' => '3'
                ]
            ])->hydrate(false)->first();

            if(!empty($existingProfile)) {

                $this->Auth->setUser($existingProfile);

                $this->set('logginUser', $this->Auth->user());
                return true;

            }else {
                $user['role_id'] = '3';
                $user['username'] = $incomingProfile['email'];
                $user['status'] = '1';
                $user['password'] = '123123';
                $user['first_name'] = $incomingProfile['first_name'];
                $user['last_name'] = $incomingProfile['last_name'];
                $newEntity = $this->Users->newEntity($user);
                $saveEntity = $this->Users->save($newEntity);

                $existingProfile = $this->Users->find('all', [
                    'conditions' => [
                        'id' => $saveEntity->id
                    ]
                ])->hydrate(false)->first();

                $this->Auth->setUser($existingProfile);

                $this->set('logginUser', $this->Auth->user());
                return true;
            }
        }

        return 3;

        die();
    }

    //-----------------------------------------Facebook Ordering Functionality------------------------------------------


}