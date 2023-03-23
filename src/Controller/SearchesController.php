<?php
/**
 * Created by PhpStorm.
 * User: Sundar.S
 * Date: 29-12-2017
 * Time: 16:00
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Utility\Hash;


class SearchesController extends AppController
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
        $this->loadModel('DeliverySettings');
        $this->loadModel('Areamaps');
        $this->loadModel('Sitesettings');
        $this->loadModel('PromotionBanners');
        $this->loadModel('Common');
        $this->loadModel('Offers');
        $this->loadModel('Rewards');
    }

    public function beforeFilter(Event $event)
    {
        // Before Login , these are the function we can access
        $this->Auth->allow([
            'index'
        ]);
    }

    //-----------------------------------Get Restaurant List Based on Search Address------------------------------------
    public function index() {

        $this->loadComponent('Common');
        if((SEARCHBY == 'Google' && $this->request->session()->read('searchLocation') != '') || (SEARCHBY != 'Google' && $this->request->session()->read('city_id') != '' && $this->request->session()->read('location_id') != '')){

            $today      = date("Y-m-d");

            if(SEARCHBY == 'Google') {

                $prepAddr = str_replace(' ','+',$this->request->session()->read('searchLocation'));

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
                //echo "<pre>";print_r($response_a);die();

                $sourcelatitude = $response_a->results[0]->geometry->location->lat;
                $sourcelongitude = $response_a->results[0]->geometry->location->lng;

                if($sourcelatitude != '' && $sourcelongitude != '') {
                    $restaurantList = $this->Restaurants->find('all', [
                        'conditions' => [
                            'OR' => [
                                'restaurant_pickup' => 'Yes',
                                'restaurant_delivery' => 'Yes'
                            ],
                            'id IS NOT NULL',
                            'status' => '1',
                            'delete_status' => 'N'
                        ],
                        'contain' => [
                            'RestaurantMenus' => [
                                'Categories' => [
                                    'conditions' => [
                                        'Categories.status' => '1',
                                        'Categories.delete_status' => 'N',
                                    ],
                                    'sort' => [
                                        'Categories.sortorder' => 'ASC'
                                    ]
                                ]
                            ],
                            'Reviews' =>[
                                'conditions' => [
                                'status' => 1
                                ]
                            ],
                            'Offers' => [
                                'conditions' => [
                                    'status' => 1,
                                    'from_date' <= $today,
                                    'to_date' >= $today
                                ],
                                'sort' => [
                                    'Offers.id' => 'DESC'
                                ]
                            ],
                            'RestaurantPayments'
                        ]
                    ])->hydrate(false)->toArray();
                }
            }else {
                $restaurantList = $this->Restaurants->find('all', [
                    'conditions' => [
                        'OR' => [
                            'restaurant_pickup' => 'Yes',
                            'restaurant_delivery' => 'Yes'
                        ],
                        'id IS NOT NULL',
                        'status' => '1',
                        'delete_status' => 'N'
                    ],
                    'contain' => [
                        'DeliveryLocations' => [
                            'conditions' => [
                                'city_id' => $this->request->session()->read('city_id'),
                                'location_id' => $this->request->session()->read('location_id')
                            ]

                        ],
                        'RestaurantMenus' => [
                            'Categories' => [
                                'conditions' => [
                                    'Categories.status' => '1',
                                    'Categories.delete_status' => 'N',
                                ],
                                'sort' => [
                                    'Categories.sortorder' => 'ASC'
                                ]
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
                        ],
                        'RestaurantPayments'
                    ]
                ])->hydrate(false)->toArray();

            }

           //echo "<pre>"; print_r($restaurantList); die();
            //pr($restaurantList);die();

            if(!empty($restaurantList)) {

                $currentTime = strtotime(date('h:i A'));
                $currentDay = strtolower(date('l'));

                $final = [];
                $distance = [];
                $result = [];
                $allCuisinesList = [];
                $allCuisinesLists = [];
                $sideCuisines = [];
                $pickupCount = 0;
                $deliveryCount = 0;
                $offerCount = 0;
                foreach($restaurantList as $key => $value) {
                    $categoryCount = 0;
                    if(!empty($value['restaurant_menus']) && !empty($value['restaurant_payments']) ) {

                        foreach($value['restaurant_menus'] as $rmKey => $rmValue) {
                            if(!empty($rmValue['category'])) {
                                $categoryCount = 1;
                                break;
                            }
                        }
                        if($categoryCount == 1) {

                            $message = '';
                            if(SEARCHBY == 'Google') {

                                $latitudeTo = $value['sourcelatitude'];
                                $longitudeTo = $value['sourcelongitude'];
                                $unit = 'K';
                                $distance = $this->Common->getDistanceValue($sourcelatitude, $sourcelongitude, $latitudeTo, $longitudeTo,
                                    $unit);

                                $distance = str_replace(',', '', $distance);

                                list($deliveryCharge, $message) = $this->getDeliveryCharge($value['id'], $distance, $sourcelatitude, $sourcelongitude);
                                $value['to_distance'] = $distance;
                            }else if(!empty($value['delivery_locations'])) {

                                $message = 'success';
                                $deliveryCharge = $value['delivery_locations'][0]['delivery_charge'];
                                $minimumOrder = $value['delivery_locations'][0]['minimum_order'];
                                $value['to_distance'] = '0.00';
                                $value['minimum_order'] = $minimumOrder;
                            }


                            if ($message == 'success') {


                                $restaurantCuisine = explode(',',$value['restaurant_cuisine']);
                                $cuisineList = '';
                                if(!empty($restaurantCuisine)) {
                                    foreach ($restaurantCuisine as $ckey => $cvalue) {
                                        $cuisines = $this->Cuisines->find('all', [
                                            'conditions' => [
                                                'id' => $cvalue
                                            ]
                                        ])->hydrate(false)->first();
                                        if(!empty($cuisines)) {
                                            $cuisineList[] = $cuisines['cuisine_name'];
                                            if(!in_array($cvalue,$allCuisinesList)) {
                                                $allCuisinesList[] = $cvalue;
                                                if(empty($sideCuisines[$cuisines['cuisine_name']])) {
                                                    $sideCuisines[$cuisines['cuisine_name']] = 1;
                                                }else {
                                                    $sideCuisines[$cuisines['cuisine_name']]++;
                                                }

                                                $allCuisinesLists[$cvalue] = $cuisines['cuisine_name'];
                                            }else {
                                                if(empty($sideCuisines[$cuisines['cuisine_name']])) {
                                                    $sideCuisines[$cuisines['cuisine_name']] = 1;
                                                }else {
                                                    $sideCuisines[$cuisines['cuisine_name']]++;
                                                }
                                            }
                                        }
                                    }
                                }
                                $value['cuisineLists'] = implode(',',$cuisineList);
                                $value['cuisineRecord'] = implode(', ',$cuisineList);


                                $currentDate = strtotime(date('Y-m-d'));
                                if(!empty($value['offers'])) {

                                    foreach ($value['offers'] as $oKey => $oValue) {
                                        $startDate = date('Y-m-d',strtotime($oValue['offer_from']));
                                        $toDate = date('Y-m-d',strtotime($oValue['offer_to']));

                                        if($currentDate >= strtotime($startDate) && $currentDate <= strtotime($toDate)) {
                                            $offerCount++;
                                            $value['restOffers'] = $oValue;
                                            break;
                                        }else {
                                            unset($value['offers'][$oKey]);
                                        }
                                    }

                                }


                                //echo "<pre>";print_r($offersList);die;

                                //Set Delivery Charge AMount
                                $value['delivery_charge'] = $deliveryCharge;

                                $firstOpenTime = strtotime($value[$currentDay.'_first_opentime']);
                                $firstCloseTime = strtotime($value[$currentDay.'_first_closetime']);

                                $secondOpenTime = strtotime($value[$currentDay.'_second_opentime']);
                                $secondCloseTime = strtotime($value[$currentDay.'_second_closetime']);

                                //Get Pickup Count
                                if($value['restaurant_pickup'] == 'Yes') {
                                    $pickupCount++;
                                }

                                if($value['restaurant_delivery'] == 'Yes') {
                                    $deliveryCount++;
                                }

                                if(!empty($value['reviews'])) {
                                    $reviewCount = 0;
                                    $value['totalRating'] = count($value['reviews']);
                                    foreach($value['reviews'] as $rKey => $rValue) {
                                        $reviewCount  = $rValue['rating'] + $reviewCount;
                                    }

                                    $value['finalReview'] = ($reviewCount/$value['totalRating'])*20;
                                }else {
                                    $value['totalRating'] = 0;
                                    $value['finalReview'] = 0;
                                }

                                if($value[$currentDay.'_status'] != 'Close') {
                                    if ($currentTime > $firstOpenTime && $currentTime <= $firstCloseTime) {
                                        $value['currentStatus'] = 'Open';
                                        $value['openclose'] = 0;
                                        $final[] = $value;
                                    } else if ($currentTime > $secondOpenTime && $currentTime <= $secondCloseTime) {
                                        $value['currentStatus'] = 'Open';
                                        $value['openclose'] = 0;
                                        $final[] = $value;
                                    } else if ($currentTime > $firstCloseTime && $currentTime <= $secondOpenTime) {
                                        $value['currentStatus'] = 'PreOrder';
                                        $value['openclose'] = 1;
                                        $final[] = $value;
                                    } else {
                                        $value['currentStatus'] = 'Closed';
                                        $value['openclose'] = 2;
                                        $final[] = $value;
                                    }
                                }else {
                                    $value['currentStatus'] = 'Closed';
                                    $value['openclose'] = 2;
                                    $final[] = $value;
                                }
                            }
                        }
                    }
                }
                if(!empty($final)) {
                    $result = Hash::sort($final, '{n}.to_distance', 'asc');
                    $result = Hash::sort($result, '{n}.openclose', 'asc');
                }

                $rewardData = $this->Rewards->find('all', [
                    'conditions' => [
                        'id' => 1,
                        'reward_option' => 'Yes'
                    ]
                ])->hydrate(false)->first();


                //Get Banner Section:
                $bannerLists = $this->PromotionBanners->find('all', [
                    'conditions' => [
                        'banner_image !=' => ''
                    ]
                ])->hydrate(false)->toArray();

                $searchBy=SEARCHBY;

                $this->set(compact('result','allCuisinesLists','currentDay','bannerLists','sideCuisines','pickupCount','deliveryCount','searchBy','offersList','offerCount', 'rewardData'));

            }

        }else {
            return $this->redirect(BASE_URL);
        }

    }
    //-----------------------------------Get Restaurant List Based on Search Address End--------------------------------


    //-----------------------------------Get Delivery Charge using Latitude and Longitude-------------------------------

    public function getDeliveryCharge($resId,$Durations,$lat,$lng) {

        $message= '';

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

    //-----------------------------------Get Delivery Charge using Latitude and Longitude-------------------------------


}