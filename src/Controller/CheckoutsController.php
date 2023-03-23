<?php
/**
 * Created by PhpStorm.
 * User: Sundar.S
 * Date: 29-12-2017
 * Time: 16:06
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Utility\Hash;
use Cake\Mailer\Email;

class CheckoutsController extends AppController
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

        $this->loadModel('Carts');
        $this->loadModel('AddressBooks');
        $this->loadModel('Restaurants');
        $this->loadModel('RestaurantPayments');
        $this->loadModel('Orders');
        $this->loadModel('WalletHistories');
        $this->loadModel('StripeCustomers');
        $this->loadModel('DeliveryLocations');
        $this->loadModel('States');
        $this->loadModel('Cities');
        $this->loadModel('Locations');
        $this->loadModel('Offers');
        $this->loadModel('Vouchers');
        $this->loadModel('PaymentMethods');
        $this->loadModel('CustomerPoints');
        $this->loadModel('Rewards');
        $this->loadComponent('Common');
        $this->loadComponent('PushNotification');
        $this->loadComponent('FcmNotification');
        $this->loadComponent('Twilio');
        $this->loadModel('Timezones');
        $this->loadModel('Users');
    }

    public function beforeFilter(Event $event)
    {
        // Before Login , these are the function we can access
        $this->Auth->allow([
            'checkLogin',
            'ajaxaction'
        ]);
    }

    //-----------------------------------------Checkout Index Functionality---------------------------------------------
    public function index() {

        if (!empty($this->Auth->user()) && $this->Auth->user('role_id') === 1) {
            //echo ADMIN_BASE_URL;die();
            $this->Flash->error('Please logout our Admin panel');

            return $this->redirect(ADMIN_BASE_URL);
        }else if (!empty($this->Auth->user()) && $this->Auth->user('role_id') === 2) {
            $this->Flash->error('Please logout restaurant panel');
            return $this->redirect(ADMIN_BASE_URL);
        }else if (!empty($this->Auth->user()) && $this->Auth->user('role_id') === 4) {
            $this->Flash->error('Please logout our Subadmin panel');
            return $this->redirect(ADMIN_BASE_URL);
        }


        //Get Restaurant Details
        $restaurantDetails = $this->Restaurants->find('all', [
            'conditions' => [
                'id' => $this->request->session()->read('resid')
            ]
        ])->hydrate(false)->first();


        if($restaurantDetails['restaurant_timezone'] != '') {
            $selectedTimezone = explode(',',$restaurantDetails['restaurant_timezone']);
        }else {
            $selectedTimezone = '';
        }


        $timezoneList = $this->Timezones->find('list',[
            'keyField' => 'id',
            'valueField' => 'timezone_name'            
        ])->hydrate(false)->toArray();


        if ($this->request->is('post')) {
            $config = new \GlobalPayments\Api\ServicesConfig();
            $config->serviceUrl = 'https://cert.api2.heartlandportico.com';
            $config->secretApiKey = $restaurantDetails['heartland_secret_api_key'];
            \GlobalPayments\Api\ServicesContainer::configure($config);
 
            try {
                $card = new \GlobalPayments\Api\PaymentMethods\CreditCardData();
                $card->token = $this->request->getData('heartland_token_id');
 
                $address = new \GlobalPayments\Api\Entities\Address();
                $address->postalCode = $this->request->getData('address_zip');
 
                $response = $card->tokenize()
                    ->withAddress($address)
                    ->execute();
 
                if ($response->responseCode === '00') {
 
                    $custEnty = $this->StripeCustomers->newEntity();
                    $custPatch = $this->StripeCustomers->patchEntity($custEnty, $this->request->getData());
                    $custPatch->stripe_token_id = $response->token;
                    $custPatch->stripe_customer_id = '';
                    $custPatch->card_id = 'customer_id';
                    $custPatch->country = 'US';
                    $custPatch->client_ip = $_SERVER['REMOTE_ADDR'];
                    $custPatch->customer_id = $this->Auth->user('id');
                    $custPatch->customer_name = $this->Auth->user('first_name');
                    $saveCust = $this->StripeCustomers->save($custPatch);
 
                    if ($saveCust) {
                        $this->Flash->success('Card added successfully');
 
                        return $this->redirect(BASE_URL.'checkouts');
                    }
 
                }
            } catch (\Exception $e) {
                error_log('exception: ' . $e->getMessage());
                // TODO: bubble up errors to user?
            }
 
            $this->Flash->error('Error saving card');
            return $this->redirect(BASE_URL . 'checkouts');
        }

        if($this->request->is(['post','put'])) {
            $restEntity = $this->Restaurants->newEntity();
            $restEntity = $this->Restaurants->patchEntity($restEntity,$this->request->getData());

            $restEntity->minimum_pickup_time = $this->request->getData('minimum_pickup_time');
            $saveEntity = $this->Restaurants->save($restEntity);

            if($saveEntity) {
                if(!empty($this->request->getData('restaurant_timezone'))) {
                    $restaurantTimezone = implode(',',$this->request->getData('restaurant_timezone'));
                }else {
                    $restaurantTimezone = '';
                }
            }
        }

        //echo $this->request->session()->read('orderType');die();
        if($this->request->session()->read('sessionId') != '') {
            $sessionId =  $this->request->session()->read('sessionId');

        }else {
            return $this->redirect(BASE_URL);
        }
       

        if (!empty($restaurantDetails)) {
            //Get Timezones List

            $allTimezonesList = [];

            $restaurantTimezone = explode(',', $restaurantDetails['restaurant_timezone']);
            $timezoneList = [];
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

                            $allTimezonesList[$tvalue] = $timezones['timezone_name'];
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
            $restaurantDetails['timezoneList'] = implode(', ', $timezoneList);
        }

        if($this->Auth->user('id') != '' && $sessionId != '') {

            //Cart Details
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

            //pr($cartsDetails);die();


            //pr($cartsDetails);die();

            if(empty($cartsDetails)) {
                return $this->redirect(BASE_URL);
            }else {
                $this->request->session()->write('resid',$cartsDetails[0]['restaurant_id']);
            }

            //Get Address Book Details:
            $customerDetails = $this->AddressBooks->find('all', [
                'conditions' => [
                    'user_id' => $this->Auth->user('id')
                ]
            ])->hydrate(false)->first();            

            $cartCount = count($cartsDetails);
            $subTotal = 0;
            $taxAmount = 0;
            $subTotalWithRedeem = 0;

            if(!empty($cartsDetails)) {
                foreach($cartsDetails as $ckey => $cvalue) {
                    $subTotal = $cvalue['total_price'] + $subTotal;
                }
                if($restaurantDetails['restaurant_tax'] > 0) {
                    $taxAmount = ($subTotal * $restaurantDetails['restaurant_tax'])/100;
                }
            }

            if($restaurantDetails['free_delivery'] > 0 && $restaurantDetails['free_delivery'] <= $subTotal ) {
                if($this->request->session()->read('offer_mode') == 'free_delivery') {
                    $this->request->session()->write('offer_mode','');
                    $this->request->session()->write('offer_value','');
                    $this->request->session()->write('voucher_code','');
                    $this->request->session()->write('voucherAmount','');
                }
            }
            //$deliveryCharge = (isset($final[0]['delivery_charge'])) ? $final[0]['delivery_charge'] : '0.00';

            $offerMode = $this->request->session()->read('offer_mode');
            $offerValue = $this->request->session()->read('offer_value');
            $voucherCode = $this->request->session()->read('voucher_code');
            $voucherAmount = $this->request->session()->read('voucherAmount');

            //'offerMode','offerValue','voucherAmount'

            if($offerMode != '' && $offerValue != '' && $voucherCode != '') {
                if($voucherAmount != '') {
                    $totalAmount = $subTotal + $taxAmount - $voucherAmount;
                }

            }else {

                $totalAmount = $subTotal + $taxAmount;
            }

            $normalTotal = $subTotal + $taxAmount;


            //Offer Section

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

                                    //$totalAmount = $totalAmount - $offerAmount;
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

                                    //$totalAmount = $totalAmount - $offerAmount;


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

            $needOrderCount = 0;

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
                $customerPoints = $this->CustomerPoints->find('all', [
                    'conditions' => [
                        'customer_id' => $this->Auth->user('id'),
                        'status' => '1'
                    ]
                ])->hydrate(false)->toArray();



                $createdDate = explode(' ',$rewardPoint['created']);

                $date1=date_create($createdDate[0]);
                $date2=date_create(date('Y-m-d'));
                $diff=date_diff($date1,$date2);
                $diff = $diff->format("%R%a");



                $remainingDays = ($rewardPoint['reward_validity'] - $diff);
                $resPercent = $rewardPoint['reward_percentage'];

                $previousOrderCount = count($customerPoints)+1;

                if (($remainingDays > 0) && ( $previousOrderCount >= $rewardPoint['redeem_order'])) {

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
                    $resPoints  = $rewardPoint['reward_totalpoint'];

                    if($userPoints >= $resPoints) {
                        $totPercent = (abs($userPoints/$resPoints) * $resPercent);

                        $offPercent = $subTotal * abs($totPercent/100);

                        if ($offPercent > $subTotal) {
                            $offPercent = $subTotal;
                            $totPercent = 100;
                        }

                        $this->request->session()->write('rewardPoint',$offPercent);
                        $this->request->session()->write('rewardPercentage',number_format($totPercent,2));
                        //$totalAmount = $totalAmount - $offPercent;
                    }else {
                        $this->request->session()->write('rewardPoint','');
                    }
                    $needOrderCount = 0;

                }else {
                    $this->request->session()->write('rewardPoint','');

                    $needOrderCount = $rewardPoint['redeem_order'] - count($customerPoints);
                }

            }



            //Addressbook Details
            $addressBooks = $this->AddressBooks->find('all', [
                'conditions' => [
                    'user_id' => $this->Auth->user('id'),
                    'AddressBooks.delete_status' => 'N',
                    'AddressBooks.status' => '1'
                ],
                'contain' =>[
                    'States' => [
                        'fields' => [
                            'state_name'
                        ]
                    ],
                    'Cities'=> [
                        'fields' => [
                            'city_name'
                        ]
                    ],
                    'Locations'=> [
                        'fields' => [
                            'area_name',
                            'zip_code'
                        ]
                    ]
                ],
                'order' => [
                    'AddressBooks.id' => 'DESC'
                ],
                //'limit' => '2'
            ])->hydrate(false)->toArray();

            //pr($addressBooks);die();
            $addressBookLists = [];
            $outOfDelivery = [];
            if(!empty($addressBooks)) {
                foreach($addressBooks as $key => $value) {

                    if(SEARCHBY == 'Google') {

                        $sourcelatitude = $value['latitude'];
                        $sourcelongitude = $value['longitude'];

                        if($sourcelatitude != '' && $sourcelongitude != '') {

                            $final = array();
                            $distance = array();
                            $result = array();

                            $latitudeTo  = $restaurantDetails['sourcelatitude'];
                            $longitudeTo = $restaurantDetails['sourcelongitude'];
                            $unit='K';
                            $distance = $this->Common->getDistanceValue(
                                $sourcelatitude,
                                $sourcelongitude,
                                $latitudeTo,
                                $longitudeTo,
                                $unit
                            );
                            $distance = str_replace(',','',$distance);
                            list($deliveryCharge,$message) = $this->getDeliveryCharge($restaurantDetails['id'],$distance,$sourcelatitude,$sourcelongitude);

                            if ($message == 'success') {
                                $value['to_distance'] = $distance;
                                if(SEARCHBY == 'Google') {

                                    if($restaurantDetails['free_delivery'] > 0 && $restaurantDetails['free_delivery'] <= $subTotal && $firstUser == 'No' ) {
                                        $deliveryCharge = 0.00;
                                    }
                                }
                                $value['deliveryCharge'] = number_format($deliveryCharge,2);
                                $value['deliveryMin'] = $restaurantDetails['minimum_order'];
                                $addressBookLists[] = $value;
                            }else {
                                $value['to_distance'] = $distance;
                                $outOfDelivery[] = $value;
                            }
                        }
                    }else {
                        $deliveryDetails = $this->DeliveryLocations->find('all', [
                            'conditions' => [
                                'restaurant_id' => $this->request->session()->read('resid'),
                                'city_id' => $value['city_id'],
                                'location_id' => $value['location_id'],
                            ]
                        ])->hydrate(false)->first();

                        if(!empty($deliveryDetails)) {
                            $value['to_distance'] = '0.00';
                            $value['deliveryCharge'] = number_format($deliveryDetails['delivery_charge'],2);
                            $value['deliveryMin'] = $deliveryDetails['minimum_order'];
                            $addressBookLists[] = $value;
                        }else {
                            $value['to_distance'] = '0.00';
                            $outOfDelivery[] = $value;
                        }
                    }
                }
            }

            //pr($addressBookLists);die();
            if(!empty($addressBookLists)) {
                $addressBookLists = Hash::sort($addressBookLists, '{n}.to_distance', 'asc');

                //$withOutDelivery = $totalAmount;

                if(SEARCHBY == 'Google') {
                    if($restaurantDetails['free_delivery'] > 0 && $restaurantDetails['free_delivery'] <= $subTotal && $firstUser == 'No') {
                        $addressBookLists[0]['deliveryCharge'] = 0.00;
                    }
                }
                //echo $addressBookLists[0]['deliveryCharge'];die();

                $deliveryCharge = $addressBookLists[0]['deliveryCharge'];


                if($this->request->session()->read('orderType') != 'pickup') {

                    $totalAmount = $totalAmount + $deliveryCharge;

                    $normalTotal = $normalTotal + $deliveryCharge;
                }

                if($offerMode != '' && $offerValue != '' && $voucherCode != '') {
                    if($voucherAmount == 'free') {
                        $totalAmount = $totalAmount - $deliveryCharge;
                        $voucherAmount = $deliveryCharge;
                    }
                }
            }
            //pr($addressBookLists);die();


            $totalAddress = $this->AddressBooks->find('all', [
                'conditions' => [
                    'user_id' => $this->Auth->user('id'),
                    'delete_status' => 'N',
                    'status' => '1'
                ],
                'order' => [
                    'id' => 'DESC'
                ]
            ])->count();

            //Timing Section
            $array_of_time = [];

            $nowTime = date('h:i A');
            //$nowTime = '12.35 PM';

            $currentTime = strtotime($nowTime);
            $currentDate = date('Y-m-d');

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

            $restaurantDetails['currentStatus'] = '';

            //In first Timing section
            if($restaurantDetails[$currentDay.'_status'] != 'Close') {

                if($currentTime < $firstOpenTime) {

                    $restaurantDetails['currentStatus'] = 'PreOrder';
                    $final[] = $restaurantDetails;

                    $nowTime = date("h:i A", strtotime('+45 minutes', $firstOpenTime));

                    $start_time = strtotime($currentDate . ' ' . $nowTime);
                    $end_time = strtotime($currentDate . ' ' . $firstEndTime);

                    $fifteen_mins = 15 * 60;

                    while ($start_time <= $end_time) {
                        $array_of_time[] = date("D h:i A", $start_time);
                        $start_time += $fifteen_mins;
                    }

                }

                if($currentTime > $firstOpenTime && $currentTime <= $firstCloseTime) {



                    if($restaurantDetails['currentStatus'] == '') {
                        $restaurantDetails['currentStatus'] = 'Open';
                    }
                    $final[] = $restaurantDetails;

                    $nowTime = date("h:i A", strtotime('+45 minutes', $currentTime));

                    $start_time = strtotime($currentDate . ' ' . $nowTime);
                    $end_time = strtotime($currentDate . ' ' . $firstEndTime);

                    $fifteen_mins = 15 * 60;

                    while ($start_time <= $end_time) {
                        $array_of_time[] = date("D h:i A", $start_time);
                        $start_time += $fifteen_mins;
                    }

                    $secondStartTime = date("h:i A", strtotime('+45 minutes', $secondOpenTime));
                    $start_time1 = strtotime($currentDate . ' ' . $secondStartTime);
                    $end_time1 = strtotime($currentDate . ' ' . $secondEndTime);

                    $fifteen_mins = 15 * 60;

                    while ($start_time1 <= $end_time1) {
                        $array_of_time[] = date("D h:i A", $start_time1);
                        $start_time1 += $fifteen_mins;
                    }

                }

                if (empty($array_of_time)) {

                    if ($currentTime > $firstCloseTime && $currentTime <= $secondOpenTime) {

                        if($restaurantDetails['currentStatus'] == '') {
                            $restaurantDetails['currentStatus'] = 'PreOrder';
                        }
                        $final[] = $restaurantDetails;

                        $secondStartTime = date("h:i A", strtotime('+45 minutes', $secondOpenTime));

                        $start_time = strtotime($currentDate . ' ' . $secondStartTime);
                        $end_time = strtotime($currentDate . ' ' . $secondEndTime);

                        $fifteen_mins = 15 * 60;

                        while ($start_time <= $end_time) {
                            $array_of_time[] = date("D h:i A", $start_time);
                            $start_time += $fifteen_mins;
                        }

                        //print_r ($array_of_time);
                    }
                } else {
                    if ($currentTime < $secondOpenTime) {
                        if($restaurantDetails['currentStatus'] == '') {
                            $restaurantDetails['currentStatus'] = 'PreOrder';
                        }
                        $final[] = $restaurantDetails;

                        //$secondStartTime = 45 * 60;
                        $secondStartTime = date("h:i A", strtotime('+45 minutes', $secondOpenTime));
                        $start_time = strtotime($currentDate . ' ' . $secondStartTime);
                        $end_time = strtotime($currentDate . ' ' . $secondEndTime);

                        $fifteen_mins = 15 * 60;

                        while ($start_time <= $end_time) {
                            $array_of_time[] = date("D h:i A", $start_time);
                            $start_time += $fifteen_mins;
                        }
                    }
                }

                if ($currentTime > $secondOpenTime && $currentTime <= $secondCloseTime) {

                    if($restaurantDetails['currentStatus'] == '') {
                        $restaurantDetails['currentStatus'] = 'Open';
                    }
                    $final[] = $restaurantDetails;

                    $nowTime = date("h:i A", strtotime('+45 minutes', $currentTime));

                    $start_time = strtotime($currentDate . ' ' . $nowTime);
                    $end_time = strtotime($currentDate . ' ' . $secondEndTime);

                    $fifteen_mins = 15 * 60;

                    while ($start_time <= $end_time) {
                        $array_of_time[] = date("D h:i A", $start_time);
                        $start_time += $fifteen_mins;
                    }

                   // print_r ($array_of_time);
                }
            }

            $orderType = $this->request->session()->read('orderType');
            //echo $orderType;die();

            $userDetails = $this->Users->find('all', [
                'conditions' => [
                    'id' => $this->Auth->user('id')
                ],
                'contain' => [
                    'StripeCustomers'
                ]

            ])->hydrate(false)->first();

           //pr($array_of_time);die();



            $paymentDetails = $this->RestaurantPayments->find('all', [
                'conditions' => [
                    'RestaurantPayments.restaurant_id' => $this->request->session()->read('resid'),
                    'RestaurantPayments.payment_status' => 'Y'
                ],
                'contain' => [
                    'PaymentMethods'
                ]

            ])->hydrate(false)->toArray();
            //echo "<pre>";print_r($paymentDetails);die();

            /*$userDetails = $this->Users->find('all', [
                'conditions' => [
                    'id' => $this->Auth->user('id')
                ]

            ])->hydrate(false)->first();*/

            $this->set(compact('restaurantDetails','cuisinesList','cartsDetails','cartCount','taxAmount','subTotal','totalAmount','deliveryCharge','final','customerDetails','addressBooks','totalAddress','outOfDelivery','addressBookLists','saveCardDetails','withOutDelivery','array_of_time','deliveryCharge','orderType','userDetails','offerMode','offerValue','voucherAmount','normalTotal','paymentDetails','needOrderCount'));

        }else {
            return $this->redirect(BASE_URL);
        }

    }

    //-----------------------------------------Checkout Ajaxaction Functionality---------------------------------------------

    public function ajaxaction() {

        if($this->request->getData('action') == 'getTiming') {

            if($this->request->getData('date') != '') {

                $restaurantDetails = $this->Restaurants->find('all', [
                    'conditions' => [
                        'id' => $this->request->session()->read('resid')
                    ]
                ])->hydrate(false)->first();

                //pr($restaurantDetails);die;
                //Timing Section
                $array_of_time = [];

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
                            $array_of_time[] = date("D h:i A", $start_time);
                            $start_time += $fifteen_mins;
                        }

                        $secondStartTime = date("h:i A", strtotime('+45 minutes', $secondOpenTime));
                        $start_time = strtotime($currentDate . ' ' . $secondStartTime);
                        $end_time = strtotime($currentDate . ' ' . $secondEndTime);

                        $fifteen_mins = 15 * 60;

                        while ($start_time <= $end_time) {
                            $array_of_time[] = date("D h:i A", $start_time);
                            $start_time += $fifteen_mins;
                        }

                    }else {

                        if($currentTime < $firstOpenTime) {

                            $firstOpenTime = date("h:i A", strtotime('+45 minutes', $firstOpenTime));

                            $start_time = strtotime($currentDate . ' ' . $firstOpenTime);
                            $end_time = strtotime($currentDate . ' ' . $firstEndTime);

                            $fifteen_mins = 15 * 60;

                            while ($start_time <= $end_time) {
                                $array_of_time[] = date("D h:i A", $start_time);
                                $start_time += $fifteen_mins;
                            }

                            $secondStartTime = date("h:i A", strtotime('+45 minutes', $secondOpenTime));
                            $start_time = strtotime($currentDate . ' ' . $secondStartTime);
                            $end_time = strtotime($currentDate . ' ' . $secondEndTime);

                            $fifteen_mins = 15 * 60;

                            while ($start_time <= $end_time) {
                                $array_of_time[] = date("D h:i A", $start_time);
                                $start_time += $fifteen_mins;
                            }

                        }

                        if($currentTime > $firstOpenTime && $currentTime <= $firstCloseTime) {


                            $nowTime = date("h:i A", strtotime('+45 minutes', $currentTime));

                            $start_time = strtotime($currentDate . ' ' . $nowTime);
                            $end_time = strtotime($currentDate . ' ' . $firstEndTime);

                            $fifteen_mins = 15 * 60;

                            while ($start_time <= $end_time) {
                                $array_of_time[] = date("D h:i A", $start_time);
                                $start_time += $fifteen_mins;
                            }

                            $secondStartTime = date("h:i A", strtotime('+45 minutes', $secondOpenTime));
                            $start_time1 = strtotime($currentDate . ' ' . $secondStartTime);
                            $end_time1 = strtotime($currentDate . ' ' . $secondEndTime);

                            $fifteen_mins = 15 * 60;

                            while ($start_time1 <= $end_time1) {
                                $array_of_time[] = date("D h:i A", $start_time1);
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
                                    $array_of_time[] = date("D h:i A", $start_time);
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
                                $array_of_time[] = date("D h:i A", $start_time);
                                $start_time += $fifteen_mins;
                            }
                        }
                    }
                }
                $action = $this->request->getData('action');
                $this->set(compact('array_of_time','action'));
            }

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
                $locaionlist = $this->Locations->find('list', [
                    'keyField' => 'id',
                    'valueField' => 'area_name',
                    'conditions' => [
                        'status' => '1',
                        'city_id' => $this->request->getData('city_id')
                    ],
                ])->hydrate(false)->toArray();
            }
            if(SEARCHBY == 'zip') {
                $locaionlist = $this->Locations->find('list', [
                    'keyField' => 'id',
                    'valueField' => 'zip_code',
                    'conditions' => [
                        'status' => '1',
                        'city_id' => $this->request->getData('city_id')
                    ],
                ])->hydrate(false)->toArray();
            }
            $this->set('action', $this->request->getData('action'));
            $this->set('locaionlist', $locaionlist);
        }
    }

    //-----------------------------------------Get Delivery Charge Functionality----------------------------------------
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

        //echo $Durations;
        //pr($restDetails);die();


        $deliveryCharge = $count = 0;
        if($restDetails['map_mode'] == 'Circle') {
            foreach ($restDetails['delivery_settings'] as $key => $value) {

                //echo $value['delivery_miles'];die();
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
                    $objPolygon = new PolygonsController(['x' => $lat, 'y' => $lng], $xyArray);

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

        return [$deliveryCharge, $message];
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

    //-----------------------------------------Get Delivery Charge Functionality----------------------------------------

    //-----------------------------------------Add Address Book in Checkout Page----------------------------------------

    public function addAddress() {

        if(SEARCHBY == 'Google') {
            if($this->request->getData('address') != '' && $this->request->getData('title') != '') {
                $addressCount = $this->AddressBooks->find('all', [
                    'conditions' => [
                        'title' => $this->request->getData('title'),
                        'user_id' => $this->Auth->user('id')
                    ]
                ])->count();

                if($addressCount == 0) {

                    $restaurantDetails = $this->Restaurants->find('all', [
                        'conditions' => [
                            'id' => $this->request->session()->read('resid')
                        ]
                    ])->hydrate(false)->first();

                    $prepAddr = str_replace(' ','+',$this->request->getData('address'));


                    /*$geocode=file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.$prepAddr.
                        '&sensor=false');

                    $output= json_decode($geocode);*/

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

                    if($sourcelatitude != '' && $sourcelongitude != '') {

                        $final = [];
                        $distance = [];
                        $result = [];

                        $latitudeTo  = $restaurantDetails['sourcelatitude'];
                        $longitudeTo = $restaurantDetails['sourcelongitude'];
                        $unit='K';

                        $distance = $this->Common->getDistanceValue(
                            $sourcelatitude,
                            $sourcelongitude,
                            $latitudeTo,
                            $longitudeTo,
                            $unit
                        );

                        $distance = str_replace(',','',$distance);


                        list($deliveryCharge,$message) = $this->getDeliveryCharge($restaurantDetails['id'],$distance,$sourcelatitude,$sourcelongitude);

                        if ($message == 'success') {

                            $addressEntity = $this->AddressBooks->newEntity();
                            $addressPatch = $this->AddressBooks->patchEntity($addressEntity,$this->request->getData());
                            $addressPatch->user_id = $this->Auth->user('id');
                            $addressPatch->status = '1';
                            $addressPatch->latitude = $sourcelatitude;
                            $addressPatch->longitude = $sourcelongitude;
                            $addressSave = $this->AddressBooks->save($addressPatch);
                            if($addressSave) {
                                $this->Flash->success('Address Added Successful');
                                echo '0';
                                die();
                            }
                        }else {
                            echo 'error';
                            die();
                        }
                    }

                }else {
                    echo '2';
                    die();
                }
            }else {
                echo '1';
                die();
            }
        }else {

            $addressCount = $this->AddressBooks->find('all', [
                'conditions' => [
                    'title' => $this->request->getData('title'),
                    'user_id' => $this->Auth->user('id')
                ]
            ])->count();

            if($addressCount == 0) {

                $stateName = $this->States->find('all', [
                    'fields' => [
                        'state_name'
                    ],
                    'conditions' => [
                        'id' => $this->request->getData('state_id')
                    ]
                ])->hydrate(false)->first();

                $cityName = $this->Cities->find('all', [
                    'fields' => [
                        'city_name'
                    ],
                    'conditions' => [
                        'id' => $this->request->getData('location_id')
                    ]
                ])->hydrate(false)->first();

                $locationName = $this->Locations->find('all', [
                    'fields' => [
                        'area_name',
                        'zip_code'
                    ],
                    'conditions' => [
                        'id' => $this->request->getData('city_id')
                    ]
                ])->hydrate(false)->first();

                $address = $this->request->getData('street_address').','.$stateName['state_name'].','.$cityName['city_name'].','.$locationName['area_name'].','.$locationName['zip_code'];

                $prepAddr = str_replace(' ','+',$address);



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

                $deliveryDetails = $this->DeliveryLocations->find('all', [
                    'conditions' => [
                        'restaurant_id' => $this->request->session()->read('resid'),
                        'city_id' => $this->request->getData('city_id'),
                        'location_id' => $this->request->getData('location_id'),
                    ]
                ])->hydrate(false)->first();

                $message = '';
                if(!empty($deliveryDetails)) {
                    $value['to_distance'] = '0.00';
                    $value['deliveryCharge'] = number_format($deliveryDetails['delivery_charge'],2);
                    $message = 'success';
                }

                if ($message == 'success') {

                    $addressEntity = $this->AddressBooks->newEntity();
                    $addressPatch = $this->AddressBooks->patchEntity($addressEntity,$this->request->getData());
                    $addressPatch->user_id = $this->Auth->user('id');
                    $addressPatch->status = '1';
                    $addressPatch->latitude = $sourcelatitude;
                    $addressPatch->longitude = $sourcelongitude;
                    $addressPatch->address = $this->request->getData('street_address');
                    $addressSave = $this->AddressBooks->save($addressPatch);
                    if($addressSave) {
                        $this->Flash->success('Address Added Successful');
                        echo '0';
                        die();
                    }
                }else {
                    echo 'error';
                    die();
                }

            }else {
                echo '2';
                die();
            }

        }

    }

    //-----------------------------------------Insert Order into Order Table--------------------------------------------

    public function placeOrder() {
        //pr($this->request->getData());die();

        if($this->request->session()->read('sessionId') != '') {
            $sessionId =  $this->request->session()->read('sessionId');

        }        

        if($sessionId != '') {

            //Cart Details
            $cartsDetails = $this->Carts->find('all', [
                'conditions' => [
                    'session_id' => $sessionId,
                ]
            ])->hydrate(false)->toArray();

            $customerDetails = $this->Users->find('all', [
                'conditions' => [
                    'id' => $this->Auth->user('id')
                ]
            ])->hydrate(false)->first();

            $restaurantDetails = $this->Restaurants->find('all', [
                'conditions' => [
                    'id' => $this->request->session()->read('resid')
                ]
            ])->hydrate(false)->first();            

            //Voucher Section
            $offerMode = $this->request->session()->read('offer_mode');
            $offerValue = $this->request->session()->read('offer_value');
            $voucherCode = $this->request->session()->read('voucher_code');
            $voucherAmount = $this->request->session()->read('voucherAmount');

            //Offer Section
            $offerPercentage = $this->request->session()->read('offerPercentage');
            $offerAmount = $this->request->session()->read('offerAmount');
            $firstUser = $this->request->session()->read('firstUser');

            //Redeem Section
            $redeemPercentage = $this->request->session()->read('rewardPercentage');
            $redeemAmount = $this->request->session()->read('rewardPoint');

            $addressDetails = $this->AddressBooks->find('all', [
                'conditions' => [
                    'id' => $this->request->getData('checkout_address')
                ]
            ])->hydrate(false)->first();            

            $deliveryCharge = 0;


            if($this->request->getData('order_type') == 'delivery') {

                if(SEARCHBY == 'Google') {

                    $sourcelatitude = $addressDetails['latitude'];
                    $sourcelongitude = $addressDetails['longitude'];


                    if($sourcelatitude != '' && $sourcelongitude != '') {

                        $final = array();
                        $distance = array();
                        $result = array();

                        $latitudeTo  = $restaurantDetails['sourcelatitude'];
                        $longitudeTo = $restaurantDetails['sourcelongitude'];
                        $unit='K';
                        $distance = $this->Common->getDistanceValue($sourcelatitude,$sourcelongitude,$latitudeTo,$longitudeTo,
                            $unit);

                        $distance = str_replace(',','',$distance);
                        list($deliveryCharge,$message) = $this->getDeliveryCharge($restaurantDetails['id'],$distance,$sourcelatitude,$sourcelongitude);

                        if ($message == 'success') {
                            $to_distance = $distance;
                            $deliveryCharge = number_format($deliveryCharge,2);
                        }else {
                            $to_distance = $distance;
                        }
                    }

                }else {

                    $stateName = $this->States->find('all', [
                        'fields' => [
                            'state_name'
                        ],
                        'conditions' => [
                            'id' => $addressDetails['state_id']
                        ]
                    ])->hydrate(false)->first();

                    $cityName = $this->Cities->find('all', [
                        'fields' => [
                            'city_name'
                        ],
                        'conditions' => [
                            'id' => $addressDetails['city_id']
                        ]
                    ])->hydrate(false)->first();

                    $locationName = $this->Locations->find('all', [
                        'fields' => [
                            'area_name',
                            'zip_code'
                        ],
                        'conditions' => [
                            'id' => $addressDetails['location_id']
                        ]
                    ])->hydrate(false)->first();

                    $deliveryDetails = $this->DeliveryLocations->find('all', [
                        'conditions' => [
                            'restaurant_id' => $this->request->session()->read('resid'),
                            'city_id' => $addressDetails['city_id'],
                            'location_id' => $addressDetails['location_id'],
                        ]
                    ])->hydrate(false)->first();

                    if(!empty($deliveryDetails)) {
                        $value['to_distance'] = '0.00';
                        $deliveryCharge = number_format($deliveryDetails['delivery_charge'],2);
                    }else {
                        $value['to_distance'] = '0.00';
                    }

                    if(SEARCHBY == 'area') {
                        $cityaddress = $locationName['area_name'].','.$cityName['city_name'].','.$stateName['state_name'];
                    }else {
                        $cityaddress = $cityName['city_name'].','.$locationName['zip_code'].','.$stateName['state_name'];
                    }

                    $address = $addressDetails['address'].','.$cityaddress;
                }

            }else {
                $address = '';
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

            if($this->request->getData('order_type') == 'delivery') {

                if(SEARCHBY == 'Google') {
                    if($restaurantDetails['free_delivery'] > 0 && $restaurantDetails['free_delivery'] <= $subTotal && ($firstUser == 'No' || $firstUser == '')) {
                        $deliveryCharge = 0.00;
                    }
                }

                //echo $totalAmount;die();

                $totalAmount = $subTotal + $taxAmount + $deliveryCharge;

                if($offerAmount != '') {
                    $totalAmount = $totalAmount - $offerAmount;
                }

                if($redeemAmount != '') {
                    $totalAmount = $totalAmount - $redeemAmount;
                }


                if($voucherAmount == 'free') {
                    $totalAmount = $totalAmount - $deliveryCharge;
                    $voucherAmount = $deliveryCharge;
                }else if ($voucherAmount) {
                    $totalAmount = $totalAmount - $voucherAmount;
                }

                $orderUpdate['delivery_charge'] = $deliveryCharge;

                if($redeemAmount >= 100) {
                    $totalAmount = $deliveryCharge;
                }

            }else {
                $totalAmount = $subTotal + $taxAmount;

                if($offerAmount != '') {
                    $totalAmount = $totalAmount - $offerAmount;
                }

                if($redeemAmount != '') {
                    $totalAmount = $totalAmount - $redeemAmount;
                }

                
            }            

            $orderEntity = $this->Orders->newEntity();

            $orderUpdate['customer_id'] = $this->Auth->user('id');
            $orderUpdate['restaurant_id'] = $this->request->session()->read('resid');
            $orderUpdate['customer_name'] = $customerDetails['first_name'];
            $orderUpdate['customer_email'] = $customerDetails['username'];
            $orderUpdate['customer_phone'] = $customerDetails['phone_number'];
            $orderUpdate['source_latitude'] = $restaurantDetails['sourcelatitude'];
            $orderUpdate['source_longitude'] = $restaurantDetails['sourcelongitude'];
            $orderUpdate['destination_latitude'] = $addressDetails['latitude'];
            $orderUpdate['destination_longitude'] = $addressDetails['longitude'];
            $orderUpdate['flat_no'] = $addressDetails['flat_no'];
            if(SEARCHBY == 'Google') {
                $orderUpdate['address'] = $addressDetails['address'];
            }else {
                $orderUpdate['address'] = $address;
            }

            $orderUpdate['assoonas'] = ($this->request->getData('deliverytime') == 'now') ? $this->request->getData('deliverytime') : '';
            $orderUpdate['order_description	'] = $this->request->getData('order_description');

            $orderUpdate['tax_percentage'] = $restaurantDetails['restaurant_tax'];
            $orderUpdate['tax_amount'] = $taxAmount;
            $orderUpdate['order_type'] = $this->request->getData('order_type');


            $orderUpdate['order_description'] = $this->request->getData('order_description');
            $orderUpdate['voucher_code'] = $this->request->getData('voucher_code');
            $orderUpdate['order_sub_total'] = $subTotal;
            $orderUpdate['order_grand_total'] = $totalAmount;
            $orderUpdate['payment_method'] = $this->request->getData('payment_method');

            //Voucher Section
            $orderUpdate['voucher_code'] = $voucherCode;
            $orderUpdate['voucher_percentage'] = $offerValue;
            $orderUpdate['voucher_amount'] = $voucherAmount;

            $orderUpdate['offer_percentage'] = $offerPercentage;
            $orderUpdate['offer_amount'] = $offerAmount;

            $orderUpdate['order_point'] = $this->request->session()->read('orderPoint');

            if($redeemAmount != '' && $redeemPercentage != '') {
                $orderUpdate['reward_used'] = 'Y';
                $orderUpdate['reward_offer'] = $redeemAmount;
                $orderUpdate['reward_offer_percentage'] = $redeemPercentage;
            }

            $this->request->session()->write('offer_mode','');
            $this->request->session()->write('offer_value','');
            $this->request->session()->write('voucher_code','');
            $this->request->session()->write('voucherAmount','');

            $this->request->session()->write('offer_percentage','');
            $this->request->session()->write('offer_amount','');


            //Reward
            $this->request->session()->write('rewardPercentage','');
            $this->request->session()->write('rewardPoint','');

            if($this->request->getData('deliverytime') != 'now') {

                if($this->request->getData('delivery_time') != '') {
                    $deliveryTime = explode(' ',$this->request->getData('delivery_time'));
                    $deliveryTime = $deliveryTime[1].' '.$deliveryTime[2];

                    $orderUpdate['delivery_date'] = $this->request->getData('delivery_date');
                    $orderUpdate['delivery_time'] = $deliveryTime;

                }else {
                    $this->Flash->error('Delivery Time Missing');
                    return $this->redirect(BASE_URL.'checkouts');
                }

            }else {
                $orderUpdate['delivery_date'] = date('Y-m-d');
                $orderUpdate['delivery_time'] = date('h:i A');
            }

            $cardFee = $this->siteSettings['card_fee'];

            $orderPatch = $this->Orders->patchEntity($orderEntity,$orderUpdate);  

            if($this->request->getData('payment_method') == 'cod' || $this->request->getData('paidFull') == 'Yes') {

                $orderSave = $this->Orders->save($orderPatch);
                if($orderSave) {

                    $ordergenid = $this->Common->generateId($orderSave->id);
                    $finalorderid = "ORD00".$ordergenid;

                    if($this->request->getData('payment_wallet') == 'Yes') {

                        $walletAmount = $customerDetails['wallet_amount'] - $totalAmount;

                        if($walletAmount < 0) {
                            $history['amount'] = $customerDetails['wallet_amount'];
                            $orderUpdate['split_payment'] = 'Yes';
                            $orderUpdate['wallet_amount'] = $customerDetails['wallet_amount'];
                            $customerDetails['wallet_amount'] = 0;
                            $orderUpdate['payment_method']  =  'cod';
                        }else {
                            $customerDetails['wallet_amount'] = $walletAmount;
                            $orderUpdate['payment_status']  =  'P';
                            $orderUpdate['payment_method']  =  'Wallet';
                            $history['amount'] = $totalAmount;
                        }
                        // $history['amount'] = $totalAmount;

                        //Update Wallet Amount
                        $custEntity = $this->Users->newEntity();
                        $amount['wallet_amount'] = $customerDetails['wallet_amount'];
                        $custPatch = $this->Users->patchEntity($custEntity,$amount);
                        $custPatch->id = $this->Auth->user('id');
                        $saveCust = $this->Users->save($custPatch);

                        //Add Wallet History
                        $walletEntity = $this->WalletHistories->newEntity();
                        $history['customer_id'] = $this->Auth->user('id');
                        $history['purpose'] = "Amount Paid";
                        $history['transaction_type'] = 'Debited';

                        $history['transaction_details'] = $finalorderid;
                        $walletPatch = $this->WalletHistories->patchEntity($walletEntity,$history);
                        $saveWallet = $this->WalletHistories->save($walletPatch);

                    }

                    //$orderUpdate['payment_method']  =  'cod';
                    $orderUpdate['order_number']  =  $finalorderid;
                    $orderUpdate['payment_wallet']  =  $this->request->getData('payment_wallet');
                    $orderUpdate['paid_full']  =  $this->request->getData('paidFull');
                    $orderUpdate['id']                =  $orderSave->id;
                    $leadsupdt = $this->Orders->patchEntity($orderEntity,$orderUpdate);
                    $leadssave = $this->Orders->save($leadsupdt);

                    //Update orderiD to Cart Table
                    foreach($cartsDetails as $key => $value) {
                        $cartEntity = $this->Carts->newEntity();
                        $cartUpdate['order_id'] = $orderSave->id;
                        $cartPatch = $this->Carts->patchEntity($cartEntity,$cartUpdate);
                        $cartPatch->id = $value['id'];
                        $cartSave = $this->Carts->save($cartPatch);
                    }

                    $this->request->session()->write('sessionId','');
                    session_regenerate_id();

                    $orderId = base64_encode($orderSave->id);


                    /*$this->Flash->set(__('Your Order Placed Successful'));
                    return $this->redirect(BASE_URL.'users/thanks/'.$orderId);*/
                }
            }else if($this->request->getData('payment_method') == 'stripe') {

                require_once(ROOT . DS . 'vendor' . DS . 'stripe' . DS . 'init.php');
                \Stripe\Stripe::setApiKey('sk_test_BQokikJOvBiI2HlWgH4olfQ2');


                $token  = $this->request->getData('res-sp-token');
                $payableAmount = $totalAmount*100;              
                

                if($this->request->getData('payment_wallet') == 'Yes') {

                    $payableAmount = $totalAmount - $customerDetails['wallet_amount'];

                    $payableAmount = $payableAmount * 100;

                }
                if($this->request->getData('credit_card_choose') == '') {

                     // Create a Customer:
                    $customer = \Stripe\Customer::create([
                        "email" => $customerDetails['username'],
                        "source" => $token,
                    ]);
 
                    // Charge the Customer instead of the card:
                    $charge = \Stripe\Charge::create([
                        "amount" => $payableAmount,
                        "currency" => "usd",
                        "customer" => $customer->id
                    ]);
                    //echo "<pre>";print_r($charge);die();
                    //Save Stripe's Customer Details in Table
                    $cardEntity = $this->StripeCustomers->newEntity();
                    $cardInsert['customer_id'] = $this->Auth->user('id');
                    $cardInsert['customer_name'] = $this->Auth->user('first_name');
                    $cardInsert['stripe_customer_id'] = $customer->id;
                    $cardInsert['stripe_token_id'] = $token;
                    $cardInsert['card_id'] = $charge['source']['id'];
                    $cardInsert['card_brand'] = $charge['source']['brand'];
                    $cardInsert['card_type'] = $charge['source']['funding'];
                    $cardInsert['card_number'] = $charge['source']['last4'];
                    $cardInsert['exp_month'] = $charge['source']['exp_month'];
                    $cardInsert['exp_year'] = $charge['source']['exp_year'];
                    $cardInsert['client_ip'] = $charge['source']['client_ip'];
                    $cardInsert['country'] = $charge['source']['country'];
                    $cardInsert['card_email'] = $charge['source']['name'];
                    $cardPatch = $this->StripeCustomers->patchEntity($cardEntity,$cardInsert);
                    $saveCard = $this->StripeCustomers->save($cardPatch);

                    $orderUpdate['stripe_customerid'] = $customer->id;


                }else {
                    $stripeDetails = $this->StripeCustomers->find('all', [
                        'conditions' => [
                            'id' => $this->request->getData('credit_card_choose')
                        ]
                    ])->hydrate(false)->first();                    

                    if(!empty($stripeDetails)) {                       
                        

                        if($stripeDetails['stripe_customer_id'] == '') {                     

                            $customer = \Stripe\Customer::create([
                                "email" => $customerDetails['username'],
                                "source" => $stripeDetails['stripe_token_id'],
                            ]);                                  

                            $stripeDetails['stripe_customer_id'] = $customer->id;

                            $cardEntity = $this->StripeCustomers->newEntity();

                            $cardInsert['stripe_customer_id'] = $customer->id;
                            $cardPatch = $this->StripeCustomers->patchEntity($cardEntity,$cardInsert);
                            $cardPatch->id = $stripeDetails['id'];
                            $saveCard = $this->StripeCustomers->save($cardPatch);
                        }
                                                

                        // YOUR CODE: Save the customer ID and other info in a database for later.

                        // YOUR CODE (LATER): When it's time to charge the customer again, retrieve the customer ID.
                        $charge = \Stripe\Charge::create([
                            "amount" => $payableAmount, // $15.00 this time
                            "currency" => "usd",
                            "customer" => $stripeDetails['stripe_customer_id']
                        ]);
                        $orderUpdate['stripe_customerid'] = $stripeDetails['stripe_customer_id'];


                    }
                }            
                

                $orderSave = $this->Orders->save($orderPatch);
                $payableAmount = $totalAmount;

                if($orderSave) {

                    $ordergenid = $this->Common->generateId($orderSave->id);
                    $finalorderid = "ORD00".$ordergenid;

                    if($this->request->getData('payment_wallet') == 'Yes') {

                        $orderUpdate['split_payment'] = 'Yes';

                        $walletAmount = $customerDetails['wallet_amount'] - $totalAmount;

                        $payableAmount = $totalAmount - $customerDetails['wallet_amount'];



                        if($walletAmount < 0) {
                            $orderUpdate['split_payment'] = 'Yes';
                            $orderUpdate['wallet_amount'] = $customerDetails['wallet_amount'];
                            $customerDetails['wallet_amount'] = 0;
                        }else {
                            $customerDetails['wallet_amount'] = $walletAmount;
                            $orderUpdate['payment_status']  =  'P';
                        }

                        //Update Wallet Amount
                        $custEntity = $this->Users->newEntity();
                        $amount['wallet_amount'] = $customerDetails['wallet_amount'];
                        $custPatch = $this->Users->patchEntity($custEntity,$amount);
                        $custPatch->id = $this->Auth->user('id');
                        $saveCust = $this->Users->save($custPatch);

                        $history['amount'] = $totalAmount;


                        //Add Wallet History
                        $walletEntity = $this->WalletHistories->newEntity();
                        $history['customer_id'] = $this->Auth->user('id');
                        $history['purpose'] = "Amount Paid";
                        $history['transaction_type'] = 'Debited';
                        $history['transaction_details'] = $finalorderid;
                        $walletPatch = $this->WalletHistories->patchEntity($walletEntity,$history);
                        $saveWallet = $this->WalletHistories->save($walletPatch);

                    }

                    $token  = $this->request->getData('res-sp-token');
                    $payAmt = number_format($payableAmount,2)*100;

                    $orderUpdate['cardfee_percentage']  = $cardFee;
                    $orderUpdate['cardfee_price']       = $totalAmount * ($cardFee/100);

                    $orderUpdate['order_number']  =  $finalorderid;
                    $orderUpdate['payment_wallet']  =  $this->request->getData('payment_wallet');
                    $orderUpdate['card_id']  =  $this->request->getData('credit_card_choose');
                    $orderUpdate['transaction_id']  =  $charge['balance_transaction'];
                    $orderUpdate['payment_status']  =  'P';

                    $orderUpdate['id']                =  $orderSave->id;
                    $leadsupdt = $this->Orders->patchEntity($orderEntity,$orderUpdate);
                    $leadssave = $this->Orders->save($leadsupdt);

                    //Update orderiD to Cart Table
                    foreach($cartsDetails as $key => $value) {
                        $cartEntity = $this->Carts->newEntity();
                        $cartUpdate['order_id'] = $orderSave->id;
                        $cartPatch = $this->Carts->patchEntity($cartEntity,$cartUpdate);
                        $cartPatch->id = $value['id'];
                        $cartSave = $this->Carts->save($cartPatch);
                    }

                    $this->request->session()->write('sessionId','');
                    session_regenerate_id();

                    $orderId = base64_encode($orderSave->id);
                    /*$this->Flash->set(__('Your Order Placed Successful'));
                    return $this->redirect(BASE_URL.'users/thanks/'.$orderId);*/
                }
            }elseif ($this->request->getData('payment_method') == 'heartland') {                
                                       
                $config = new \GlobalPayments\Api\ServicesConfig();              
                $config->serviceUrl = 'https://cert.api2.heartlandportico.com';
                $config->secretApiKey = $restaurantDetails['heartland_secret_api_key']; 

                \GlobalPayments\Api\ServicesContainer::configure($config);  

                $payableAmount = $totalAmount;

                if ($this->request->getData('payment_wallet') == 'Yes') {
                    $payableAmount = $totalAmount - $customerDetails['wallet_amount'];
                    $payableAmount = $payableAmount;
                }

                if ($this->request->getData('credit_card_choose') == '') {
                    $this->Flash->error('Please select a card');
                    return $this->redirect(BASE_URL . 'checkouts');
                }

                $heartlandDetails = $this->StripeCustomers->find('all', [
                    'conditions' => [
                        'id' => $this->request->getData('credit_card_choose')
                    ]
                ])->hydrate(false)->first();
                if (!empty($heartlandDetails)) {
                    $orderUpdate['stripe_customerid'] = $heartlandDetails['stripe_token_id'];

                    $card = new \GlobalPayments\Api\PaymentMethods\CreditCardData();
                    $card->token = $heartlandDetails['stripe_token_id'];

                    $address = new \GlobalPayments\Api\Entities\Address();
                    $address->postalCode = $this->request->getData('address_zip');

                    $charge = null;
                    try {
                        $charge = $card->charge($payableAmount)
                            ->withCurrency('USD')
                            ->withAddress($address)
                            ->withAllowDuplicates(true)
                            ->execute();
                    } catch (\Exception $e) {
                        error_log('payment exception: ' . $e->getMessage());
                        $errorMessage = $e->getMessage();
                    }
                }

                if (!isset($charge) || $charge == null) {
                    $this->Flash->error($errorMessage ?: 'Error during payment');
                    return $this->redirect(BASE_URL . 'checkouts');
                }

                // TODO: make sure data below is correct before order save?
                $orderUpdate['order_number'] = 'temp';
                $orderUpdate['driver_id'] = '0';
                $orderUpdate['ref_number'] = $charge->referenceNumber;
                $orderUpdate = $this->Orders->patchEntity($orderEntity, (array)$orderUpdate);
                $orderUpdate['google_address'] = '';
                $orderUpdate['landmark'] = '';
                $orderUpdate['state_name'] = '';
                $orderUpdate['city_name'] = '';
                $orderUpdate['location_name'] = '';
                $orderUpdate['delivery_time_slot'] = $this->request->getData('deliverytime');
                $orderUpdate['delivery_date'] = $this->request->getData('delivery_date');
                $orderUpdate['delivery_time'] = $this->request->getData('delivery_time');
                $orderUpdate['delivered_time'] = '';
                $orderUpdate['offer_percentage'] = '0';
                $orderUpdate['offer_amount'] = '0';
                $orderUpdate['voucher_percentage'] = '0';
                $orderUpdate['voucher_amount'] = '0';
                $orderUpdate['tip_percentage'] = '0';
                $orderUpdate['tip_amount'] = '0';

                $orderUpdate['cardfee_percentage']  = $cardFee;
                $orderUpdate['cardfee_price']       = $totalAmount * ($cardFee / 100);

                $orderUpdate['payment_wallet']  =  $this->request->getData('payment_wallet') ?: 'No';
                $orderUpdate['card_id']  =  $this->request->getData('credit_card_choose');
                $orderUpdate['transaction_id']  =  $charge->transactionId;
                $orderUpdate['payment_status']  =  'P';
                $orderUpdate['paid_full'] = 1;
                $orderUpdate['wallet_amount'] = 0;
                $orderUpdate['distance'] = 0;
                $orderUpdate['driver_invoice_number'] = 'temp';
                $orderUpdate['driver_deliver_date'] = $this->request->getData('delivery_date');
                $orderUpdate['driver_deliver_time'] = $this->request->getData('delivery_time');
                $orderUpdate['driver_charge'] = 0;
                $orderUpdate['failed_reason'] = '';
                $orderUpdate['order_point'] = 0;
                $orderUpdate['reward_offer'] = 0;
                $orderUpdate['reward_offer_percentage'] = 0;
                $orderUpdate['payerID'] = '';
                $orderUpdate['paymentToken'] = $orderUpdate['stripe_customerid'];
                $orderUpdate['paymentID'] = $orderUpdate['transaction_id'];
                $orderUpdate['order_proof'] = '';
                $orderUpdate['payout_type'] = 0;
                $orderUpdate['payout_amount'] = 0;
                $orderUpdate['completed_time'] = 0;
                $orderSave = $this->Orders->save($orderUpdate);
                $payableAmount = $totalAmount;

                if ($orderSave) {
                    $ordergenid = $this->Common->generateId($orderSave->id);
                    $finalorderid = "ORD00".$ordergenid;

                    if ($this->request->getData('payment_wallet') == 'Yes') {
                        $orderUpdate['split_payment'] = 'Yes';

                        $walletAmount = $customerDetails['wallet_amount'] - $totalAmount;

                        $payableAmount = $totalAmount - $customerDetails['wallet_amount'];

                        if ($walletAmount < 0) {
                            $orderUpdate['split_payment'] = 'Yes';
                            $orderUpdate['wallet_amount'] = $customerDetails['wallet_amount'];
                            $customerDetails['wallet_amount'] = 0;
                        } else {
                            $customerDetails['wallet_amount'] = $walletAmount;
                            $orderUpdate['payment_status']  =  'P';
                        }

                        //Update Wallet Amount
                        $custEntity = $this->Users->newEntity();
                        $amount['wallet_amount'] = $customerDetails['wallet_amount'];
                        $custPatch = $this->Users->patchEntity($custEntity, $amount);
                        $custPatch->id = $this->Auth->user('id');
                        $saveCust = $this->Users->save($custPatch);

                        $history['amount'] = $totalAmount;

                        //Add Wallet History
                        $walletEntity = $this->WalletHistories->newEntity();
                        $history['customer_id'] = $this->Auth->user('id');
                        $history['purpose'] = "Amount Paid";
                        $history['transaction_type'] = 'Debited';
                        $history['transaction_details'] = $finalorderid;
                        $walletPatch = $this->WalletHistories->patchEntity($walletEntity, $history);
                        $saveWallet = $this->WalletHistories->save($walletPatch);
                    }

                    $payAmt = number_format($payableAmount, 2) * 100;

                    $orderUpdate['cardfee_percentage']  = $cardFee;
                    $orderUpdate['cardfee_price']       = $totalAmount * ($cardFee / 100);

                    $orderUpdate['order_number']  =  $finalorderid;
                    $orderUpdate['payment_wallet']  =  $this->request->getData('payment_wallet') ?: 'No';
                    $orderUpdate['card_id']  =  $this->request->getData('credit_card_choose');
                    $orderUpdate['transaction_id']  =  $charge->transactionId;
                    $orderUpdate['payment_status']  =  'P';

                    $orderUpdate['id']                =  $orderSave->id;
                    $leadsupdt = $this->Orders->patchEntity($orderEntity, (array)$orderUpdate);
                    $leadssave = $this->Orders->save($leadsupdt);

                    //Update orderiD to Cart Table
                    foreach ($cartsDetails as $key => $value) {
                        $cartEntity = $this->Carts->newEntity();
                        $cartUpdate['order_id'] = $orderSave->id;
                        $cartPatch = $this->Carts->patchEntity($cartEntity, $cartUpdate);
                        $cartPatch->id = $value['id'];
                        $cartSave = $this->Carts->save($cartPatch);
                    }

                    $this->request->session()->write('sessionId', '');
                    session_regenerate_id();

                    $orderId = base64_encode($orderSave->id);
                    /*$this->Flash->set(__('Your Order Placed Successful'));
                    return $this->redirect(BASE_URL.'users/thanks/'.$orderId);*/
                }
            }else if($this->request->getData('payment_method') == 'paypal') {

                $orderSave = $this->Orders->save($orderPatch);
                if($orderSave) {

                    $ordergenid = $this->Common->generateId($orderSave->id);
                    $finalorderid = "ORD00".$ordergenid;



                    if($this->request->getData('payment_wallet') == 'Yes') {
                        $orderUpdate['split_payment'] = 'Yes';

                        $walletAmount = $customerDetails['wallet_amount'] - $totalAmount;

                        $history['amount'] = $customerDetails['wallet_amount'];

                        /*if($walletAmount < 0) {
                            $customerDetails['wallet_amount'] = 0;
                        }else {
                            $customerDetails['wallet_amount'] = $walletAmount;
                            //$orderUpdate['payment_status']  =  'P';
                        }*/

                        if($walletAmount < 0) {
                            $orderUpdate['split_payment'] = 'Yes';
                            $orderUpdate['wallet_amount'] = $customerDetails['wallet_amount'];
                            $customerDetails['wallet_amount'] = 0;
                        }else {
                            $customerDetails['wallet_amount'] = $walletAmount;
                            $orderUpdate['payment_status']  =  'P';
                        }

                        //Update Wallet Amount
                        $custEntity = $this->Users->newEntity();
                        $amount['wallet_amount'] = $customerDetails['wallet_amount'] ;
                        $custPatch = $this->Users->patchEntity($custEntity,$amount);
                        $custPatch->id = $this->Auth->user('id');
                        $saveCust = $this->Users->save($custPatch);
                    }

                    //$orderUpdate['cardfee_percentage']  = $cardFee;
                    //$orderUpdate['cardfee_price']       = $totalAmount * ($cardFee/100);
                    $orderUpdate['order_number']  =  $finalorderid;
                    $orderUpdate['payerID']  =  $this->request->getData('payerID');
                    $orderUpdate['paymentToken']  =  $this->request->getData('paymentToken');
                    $orderUpdate['paymentID']  =  $this->request->getData('paymentID');
                    $orderUpdate['payment_status']  =  'P';

                    $orderUpdate['id']                =  $orderSave->id;
                    $leadsupdt = $this->Orders->patchEntity($orderEntity,$orderUpdate);
                    $leadssave = $this->Orders->save($leadsupdt);

                    //Update orderiD to Cart Table
                    foreach($cartsDetails as $key => $value) {
                        $cartEntity = $this->Carts->newEntity();
                        $cartUpdate['order_id'] = $orderSave->id;
                        $cartPatch = $this->Carts->patchEntity($cartEntity,$cartUpdate);
                        $cartPatch->id = $value['id'];
                        $cartSave = $this->Carts->save($cartPatch);
                    }

                    $this->request->session()->write('sessionId','');
                    session_regenerate_id();

                    $orderId = base64_encode($orderSave->id);

                }
            }else {
                return $this->redirect(BASE_URL);
            }           


            if($_SERVER['HTTP_HOST'] != 'localhost') {

                $restaurantFCM = $this->Restaurants->find('all', [
                    'fields' => [
                        'device_id'
                    ],
                    'conditions' => [
                        'id' => $this->request->session()->read('resid')
                    ]
                ])->hydrate(false)->first();

                if($restaurantFCM['device_id'] != '') {

                    $message      = 'New order came - '.$finalorderid;

                    $notificationdata['data']['title']          = "Neworder";
                    $notificationdata['data']['message']        = $message;
                    $notificationdata['data']['is_background']  = false;
                    $notificationdata['data']['payload']        = array('OrderDetails' => "",'type'    => "ordercanceled");
                    $notificationdata['data']['timestamp']      = date('Y-m-d G:i:s');

                    $this->FcmNotification->sendNotification($notificationdata, $restaurantFCM['device_id']);

                }

                //$this->orderEmail($orderSave->id);
            }

            $message = 'New Order Came';
            $this->PushNotification->pushNotification($message,$restaurantDetails['user_id']);

            //Reward Points Update

            if($redeemAmount != '' && $redeemPercentage != '') {

                $customerPoints = $this->CustomerPoints->find('all', [
                    'fields' => [
                        'total_points' => 'SUM(points)'
                    ],
                    'conditions' => [
                        'customer_id' => $this->Auth->user('id'),
                        'status' => '1'
                    ]
                ])->hydrate(false)->toArray();

                $customerPoint['order_id'] = $orderSave->id;
                $customerPoint['restaurant_name'] = $restaurantDetails['restaurant_name'];
                $customerPoint['customer_id'] = $this->Auth->user('id');
                $customerPoint['total'] = $totalAmount;
                $customerPoint['points'] = $customerPoints[0]['total_points'];
                $customerPoint['type'] = 'Spent';

                $customerPointEntity = $this->CustomerPoints->newEntity();
                $customerPointPatch = $this->CustomerPoints->patchEntity($customerPointEntity,$customerPoint);
                $customerPointSave = $this->CustomerPoints->save($customerPointPatch);

                $rewardPoints = $this->CustomerPoints->find('all', [
                    'conditions' => [
                        'customer_id' => $this->Auth->user('id'),
                        'status' => '1'
                    ]
                ])->hydrate(false)->toArray();

                if(!empty($rewardPoints)) {

                    foreach ($rewardPoints as $key => $value) {
                        $reward['id'] = $value['id'];
                        $reward['status'] = '0';
                        $rewardPointEntity = $this->CustomerPoints->newEntity();
                        $rewardPointPatch = $this->CustomerPoints->patchEntity($rewardPointEntity,$reward);
                        $customerPointSave = $this->CustomerPoints->save($rewardPointPatch);
                    }

                }

            }
            $this->Flash->set(__('Your Order Placed Successful'));
            return $this->redirect(BASE_URL.'users/thanks/'.$orderId);

        }
    }

    //-----------------------------------------Voucher Apply Functionality----------------------------------------------

    public function voucherApply() {
        if($this->request->getData('vouchercode') != '') {
            $voucherDetails = $this->Vouchers->find('all', [
                'conditions' => [
                    'voucher_code' => $this->request->getData('vouchercode'),
                    'status' => '1'
                ]
            ])->hydrate(false)->first();



            $currentDate = strtotime(date('Y-m-d'));





            if(!empty($voucherDetails)) {

                $startDate = date('Y-m-d',strtotime($voucherDetails['voucher_from']));
                $toDate = date('Y-m-d',strtotime($voucherDetails['voucher_to']));

                if($currentDate >= strtotime($startDate) && $currentDate <= strtotime($toDate)) {


                    $checkCoupon = 0;
                    if($voucherDetails['type_offer'] == 'single') {

                        $checkCoupon = $this->Orders->find('all', [
                            'conditions' => [
                                'customer_id' => $this->Auth->user('id'),
                                'voucher_code' => $this->request->getData('vouchercode')
                            ]
                        ])->count();
                    }

                    if($checkCoupon == 0) {
                        $this->request->session()->write('offer_mode',$voucherDetails['offer_mode']);
                        $this->request->session()->write('offer_value',$voucherDetails['offer_value']);
                        $this->request->session()->write('voucher_code',$voucherDetails['voucher_code']);

                        if($voucherDetails['offer_mode'] == 'price') {

                            $voucherAmount =  $voucherDetails['offer_value'];
                            $this->request->session()->write('voucherAmount',$voucherAmount);
                        }else if($voucherDetails['offer_mode'] == 'percentage') {
                            $subTotal = str_replace(',','',$this->request->getData('subTotal'));
                            $voucherAmount = ( $subTotal * $voucherDetails['offer_value'])/100;
                            $this->request->session()->write('voucherAmount',$voucherAmount);
                        }else {
                            if($this->request->getData('deliveryAmt') != 'Free') {
                                if($this->request->session()->read('orderType') != 'pickup') {
                                    $voucherAmount = 'free';
                                    $this->request->session()->write('voucherAmount',$voucherAmount);
                                }else {
                                    $this->request->session()->write('offer_mode','');
                                    $this->request->session()->write('offer_value','');
                                    $this->request->session()->write('voucher_code','');
                                    $this->request->session()->write('voucherAmount','');
                                    echo 'pickup';die();
                                }

                            }else {
                                $this->request->session()->write('offer_mode','');
                                $this->request->session()->write('offer_value','');
                                $this->request->session()->write('voucher_code','');
                                echo 'Already Free Delivery Applied';die();
                            }


                        }
                        echo $voucherAmount;die();
                    }else {
                        echo 'already';die();
                    }

                }else {
                    echo 'no';die();
                }


            }else {
                echo 'no';die();
            }

        }else {
            echo 'missing';die();
        }
    }

    //-----------------------------------------Remove Voucher-----------------------------------------------------------
    public function removeVoucher() {
        $this->request->session()->write('offer_mode','');
        $this->request->session()->write('offer_value','');
        $this->request->session()->write('voucher_code','');
        $this->request->session()->write('voucherAmount','');
        die();
    }

    //-----------------------------------------Order SMS Functionality--------------------------------------------------
    public function ordersms($orderId) {

        $orderDetail = $this->Orders->find('all', [
            'conditions' => [
                'id' => $orderId
            ],
            'contain' => [
                'Restaurants'
            ]
        ])->hydrate(false)->first();


        $siteCountry = $this->Countries->find('all', [
            'conditions' => [
                'id' => $this->siteSettings['site_country']
            ]
        ])->hydrate(false)->first();

        $countryCode = $siteCountry['phone_code'];

        $customerMessage = 'Thanks for using '.$this->siteSetting['Sitesetting']['site_name'].' service. Your order '.$orderDetail['order_number'].' has been placed . Track your order at '.$this->siteUrl.'.  Regards '.$this->siteSettings['site_name'].'.';
        $toCustomerNumber = '+'.$countryCode.$this->Auth->User('Customer.customer_phone');
        if($this->siteSettings['sms_option'] == 'Yes'){
            $customerSms      = $this->Twilio->sendMessage($toCustomerNumber, $customerMessage);
        }

        if ($orderDetail['restaurant']['sms_option'] == 'Yes' && !empty($orderDetail['restaurant']['sms_phonenumber'])) {
            $restaurantMessage  = "Dear ".$orderDetail['restaurant']['restaurant_name']." you've received a ";
            //$storeMessage .= ($orderDetail['payment_method'] != 'paid') ? 'COD' : 'PAID';
            $restaurantMessage .= ' order '.$orderDetail['order_number'].' from '.$orderDetail['customer_name'];

            if ($orderDetail['order_type'] == 'Delivery') {
                $restaurantMessage .= ','.$orderDetail['flat_no'].','.$orderDetail['address'];

            }

            $restaurantMessage .= '. '.$orderDetail['order_type'].' due on '.$orderDetail['delivery_date'].' at '.$orderDetail['delivery_time'].'. Thanks '.$this->siteSettings['site_name'].'';
            $toStoreNumber = '+'.$countryCode.$orderDetail['sms_phonenumber'];
            if($this->siteSettings['sms_option'] == 'Yes'){
                $customerSms   = $this->Twilio->sendMessage($toStoreNumber, $restaurantMessage);
            }
        }
        return true;
    }

    //-----------------------------------------Order Email Functionality------------------------------------------------

    public function orderEmail($orderId) {

        $orderDetails = $this->Orders->find('all', [
            'conditions' => [
                'Orders.id' => $orderId
            ],
            'contain' => [
                'Carts',
                'Restaurants'
            ]
        ])->hydrate(false)->first();
        $offerDetails = '';
        $voucherDetails = '';
        $rewardDetails = '';

        if($orderDetails['split_payment'] == 'Yes') {
            $paymentMethod = strtoupper(($orderDetails['payment_method'] == 'stripe') ? 'Card' : $orderDetails['payment_method']).' & '.'Wallet';
        }else {
            $paymentMethod = strtoupper(($orderDetails['payment_method'] == 'stripe') ? 'Card' : $orderDetails['payment_method']);

        }

        $paymentStatus = ($orderDetails['payment_status'] == 'P') ? 'Paid' : 'Not Paid';

        if($orderDetails['order_type'] == 'delivery') {
            $deliveryAmount = ($orderDetails['delivery_charge'] > 0) ? $this->siteSettings['site_currency'].' '. number_format($orderDetails['delivery_charge'],2) : 'Free';

            $deliveryCharge = '<tr>
                              <td align="left" style="">
                                 <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                    <tr>
                                       <td width="75%" align="right" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 15px; font-weight: 600;padding: 10px;border-top: 1px solid #eeeeee;">
                                          Delivery Fee
                                       </td>
                                       <td width="25%" align="right" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 15px; font-weight: 600; line-height: 24px; padding: 10px;border-top: 1px solid #eeeeee;">
                                          '.$deliveryAmount.'
                                       </td>
                                    </tr>
                                 </table>
                              </td>
                           </tr>';

            $deliveryAddress = '<tr>
                     <td align="center" height="100%" valign="top" width="100%" style="padding:0px 20px 20px 20px;background-color:#fff">
                        <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="width:670px;padding:10px;">
                           <tr>
                              <td align="left" valign="top" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-top:15px">
                                 <span style="font-weight: 700;">Delivery Address</span>
                                 <span style="padding-top:0px;display:block">'.$orderDetails['customer_name'].'<br>'.$orderDetails['flat_no'].', '.$orderDetails['address'].'<br>'.$orderDetails['customer_phone'].'</span>
                              </td>
                              <td align="right" valign="top" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-top:15px">
                                 <span style="font-weight: 700;">Delivery Time / Date</span>
                                 <span style="padding-top:0px;display:block">'.date('Y-m-d',strtotime($orderDetails['delivery_date'])).' '.$orderDetails['delivery_time'].'</span>
                              </td>
                           </tr>
                        </table>
                     </td>
                  </tr>';
        }else {
            $deliveryCharge = '';
            $deliveryAddress = '<tr>
                     <td align="center" height="100%" valign="top" width="100%" style="padding:0px 20px 20px 20px;background-color:#fff">
                        <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="width:670px;padding:10px;">
                           <tr>
                              <td align="left" valign="top" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-top:15px">
                                 <span style="font-weight: 700;">Pickup Address</span>
                                 <span style="padding-top:0px;display:block">'.$orderDetails['restaurant']['restaurant_name'].'<br>'.$orderDetails['restaurant']['contact_address'].'<br>'.$orderDetails['restaurant']['contact_phone'].'</span>
                              </td>
                              <td align="right" valign="top" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-top:15px">
                                 <span style="font-weight: 700;">Pickup Time / Date</span>
                                 <span style="padding-top:0px;display:block">'.date('Y-m-d',strtotime($orderDetails['delivery_date'])).' '.$orderDetails['delivery_time'].'</span>
                              </td>
                           </tr>
                        </table>
                     </td>
                  </tr>';

        }

        if($orderDetails['offer_amount'] > 0) {
            $offerDetails = '<tr>
                              <td align="left" style="">
                                 <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                    <tr>
                                       <td width="75%" align="right" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 15px; font-weight: 600; line-height: 24px; padding: 10px;border-top: 1px solid #eeeeee;">
                                          Offer('.$orderDetails['offer_percentage'].') %
                                       </td>
                                       <td width="25%" align="right" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 15px; font-weight: 600; line-height: 24px; padding: 10px;border-top: 1px solid #eeeeee;">
                                          '.$this->siteSettings["site_currency"].' '.number_format($orderDetails['offer_amount'],2).'
                                       </td>
                                    </tr>
                                 </table>
                              </td>
                           </tr>';
        }

        if($orderDetails['voucher_amount'] > 0) {
            $voucherDetails = '<tr>
                              <td align="left" style="">
                                 <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                    <tr>
                                       <td width="75%" align="right" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 15px; font-weight: 600; line-height: 24px; padding: 10px;border-top: 1px solid #eeeeee;">
                                          Offer('.$orderDetails['voucher_percentage'].') %
                                       </td>
                                       <td width="25%" align="right" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 15px; font-weight: 600; line-height: 24px; padding: 10px;border-top: 1px solid #eeeeee;">
                                          '.$this->siteSettings["site_currency"].' '.number_format($orderDetails['voucher_amount'],2).'
                                       </td>
                                    </tr>
                                 </table>
                              </td>
                           </tr>';
        }

        if($orderDetails['reward_used'] == 'N' && $orderDetails['reward_offer'] > 0) {
            $rewardDetails = '<tr>
                              <td align="left" style="">
                                 <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                    <tr>
                                       <td width="75%" align="right" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 15px; font-weight: 600; line-height: 24px; padding: 10px;border-top: 1px solid #eeeeee;">
                                          Offer('.$orderDetails['reward_offer_percentage'].') %
                                       </td>
                                       <td width="25%" align="right" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 15px; font-weight: 600; line-height: 24px; padding: 10px;border-top: 1px solid #eeeeee;">
                                          '.$this->siteSettings["site_currency"].' '.number_format($orderDetails['reward_offer'],2).'
                                       </td>
                                    </tr>
                                 </table>
                              </td>
                           </tr>';
        }
        $cartDetails = '';

        if(!empty($orderDetails['carts'])) {
            foreach($orderDetails['carts'] as $cKey => $cValue) {

                $menuName = $cValue['menu_name'];

                $subAddons = ($cValue['subaddons_name'] != "") ? $cValue['subaddons_name'] : "";

                $cartDetails .= '
                                <tr>
                                   <td width="10%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 15px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;border-bottom:1px solid #eee;">'.($cKey+1).'</td>
                                   <td width="45%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 15px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;border-bottom:1px solid #eee;">'.$menuName.' '.$subAddons.'</td>
                                   <td width="10%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 15px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;border-bottom:1px solid #eee;">'.$cValue['quantity'].'</td>
                                   <td width="15%" align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 15px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;border-bottom:1px solid #eee;">
                                      '.$this->siteSettings["site_currency"].' '.number_format($cValue['menu_price'],2).'
                                   </td>
                                   <td width="20%" align="right" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 15px; font-weight: 400; line-height: 24px; padding: 15px 10px 5px 10px;border-bottom:1px solid #eee;">
                                      '.$this->siteSettings["site_currency"].' '.number_format($cValue['total_price'],2).'
                                   </td>
                                </tr>';

            }
        }

        $html = '
         <tr>
            <td align="center" style="background-color: #eeeeee;" bgcolor="#eeeeee">
               <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:767px;">
                  <tr>
                     <td align="center" valign="top" style="font-size:0; padding: 15px;" bgcolor="#FF6300">
                        <div style="display:inline-block; vertical-align:middle; text-align:center">
                           <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:300px;">
                              <tr>
                                 <td align="left" valign="top" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 34px; font-weight: 800;">
                                    <img width="170" src="'.DRIVERS_LOGO_URL.'/uploads/siteImages/siteLogo/'.$this->siteSettings['site_logo'].'">
                                 </td>
                              </tr>
                           </table>
                        </div>
                     </td>
                  </tr>
                  <tr>
                     <td align="center" style="padding: 20px; background-color: #ffffff;" bgcolor="#ffffff">
                        <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:670px;">
                           <tr>
                              <td align="center" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px;">
                                 <img src="'.BASE_URL.'images/tick.png" width="100" style="display: block; border: 0px;" />
                                 <h2 style="font-size: 28px; font-weight: 800; line-height: 36px; color: #333333; margin: 0;">
                                    Thank You For Your Order!
                                 </h2>
                              </td>
                           </tr>
                           <tr>
                              <td align="left" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-top: 10px;">
                                 <p style="font-size: 16px; font-weight: 400; line-height: 24px; color: #777777;">
                                    <span style="display:block;">Dear <strong style=" color:#000;">'.$orderDetails['customer_name'].'</strong></span>
                                    Thank you for shopping with us.Your order details below for the <span style="color:#000;font-weight:bold;">order ID '.$orderDetails['order_number'].'</span>, We gladly confirm your online order for delivery.
                                 </p>
                              </td>
                           </tr>
                           
                           <tr>
                              <td align="left" style="padding-top: 20px;">
                                 <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                    <tr>
                                   <td  width="10%" align="left" bgcolor="#eeeeee" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 15px; font-weight: 700; line-height: 24px; padding: 10px;">S.No</td>
                                   <td width="45%" align="left" bgcolor="#eeeeee" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 15px; font-weight: 700; line-height: 24px; padding: 10px;">Menu Name</td>
                                   <td width="10%" align="left" bgcolor="#eeeeee" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 700; line-height: 24px; padding: 10px;">Qty</td>
                                   <td width="15%" align="left" bgcolor="#eeeeee" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 15px; font-weight: 700; line-height: 24px; padding: 10px;">
                                      Price
                                   </td>
                                   <td width="20%" align="right" bgcolor="#eeeeee" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 15px; font-weight: 700; line-height: 24px; padding: 10px;">
                                      Total Price
                                   </td>
                                </tr>
                                    '.$cartDetails.'                                 
                                 </table>
                              </td>
                           </tr>   
                                 
                           
                           <tr>
                              <td align="left" style="">
                                 <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                    <tr>
                                       <td width="75%" align="right" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 15px; font-weight: 600; line-height: 24px; padding: 10px;border-top: 1px solid #eeeeee;">
                                          Sub Total
                                       </td>
                                       <td width="25%" align="right" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 15px; font-weight: 600; line-height: 24px; padding: 10px;border-top: 1px solid #eeeeee;">
                                          '.$this->siteSettings["site_currency"].' '.number_format($orderDetails['order_sub_total'],2).'
                                       </td>
                                    </tr>
                                 </table>
                              </td>
                           </tr>
                           <tr>
                              <td align="left" style="">
                                 <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                    <tr>
                                       <td width="75%" align="right" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 15px; font-weight: 600; line-height: 24px; padding: 10px;border-top: 1px solid #eeeeee;">
                                          Tax('.$orderDetails['tax_percentage'].') %
                                       </td>
                                       <td width="25%" align="right" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 15px; font-weight: 600; line-height: 24px; padding: 10px;border-top: 1px solid #eeeeee;">
                                          '.$this->siteSettings["site_currency"].' '.number_format($orderDetails['tax_amount'],2).'
                                       </td>
                                    </tr>
                                 </table>
                              </td>
                           </tr>
                           '.$deliveryCharge.'                           
                           '.$offerDetails.'
                           '.$voucherDetails.'
                           '.$rewardDetails.'

                           <tr>
                              <td align="left" style="">
                                 <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                    <tr>
                                       <td width="75%" align="right" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 600; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;">
                                          TOTAL
                                       </td>
                                       <td width="25%" align="right" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 600; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;">
                                          '.$this->siteSettings["site_currency"].' '.number_format($orderDetails['order_grand_total'],2).'
                                       </td>
                                    </tr>
                                 </table>
                              </td>
                           </tr>
                        </table>
                     </td>
                  </tr>
                  <tr>
                     <td align="center" height="100%" valign="top" width="100%" style="padding:20px;background-color:#fff">
                        <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="width:670px;background:#eee;padding:10px;">
                           <tr>
                              <td align="left" width="50%" valign="top" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px;">
                                 <span style="font-weight: 700; display:block;">Order Number/ID : '.$orderDetails['order_number'].'</span>
                                 <span style="font-weight: 700; display:block;">Payment method : '.$paymentMethod.'</span>
                              </td>
                              <td align="right" width="50%" valign="top" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px;">
                                 <span style="font-weight: 700; display:block;">Order type : '.ucfirst($orderDetails['order_type']).'</span>
                                 <span style="font-weight: 700; display:block;">Payment status : '.$paymentStatus.' </span>
                              </td>
                           </tr>
                        </table>
                     </td>
                  </tr>
                  '.$deliveryAddress.'
                  
                  <tr>
                     <td align="center" height="100%" valign="top" width="100%" style="padding:20px;background-color:#fff">
                        <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="width:670px;background:#eee;padding:10px;">
                           <tr>
                              <td align="left" width="50%" valign="top" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px;">
                                 <span style="font-weight: 700; display:block;">Order Instruction : '.$orderDetails['order_description'].'</span>
                                 
                              </td>                              
                           </tr>
                        </table>
                     </td>
                  </tr>
                  <tr>
                     <td align="center" valign="top" style="font-size:0; padding: 10px;" bgcolor="#FF6300"><div style="color:#fff;font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px;">Copyrights '.$this->siteSettings["site_name"].' @ 2018</div></td>
                  </tr>
               </table>
            </td>
         </tr>';

        $mailContent  	 = $html;
        $customerSubject = 'Order Placed Successful - '.$orderDetails['order_number'];;
        $siteName   	 = $this->siteSettings['site_name'];
        $site_url = BASE_URL;
        $source   = BASE_URL.'images/logo-home.png';
        $fromMail = $this->siteSettings['order_email'];
        $email = new Email();
        $email->setFrom($fromMail);
        $email->setTo($orderDetails['customer_email']);
        $email->setSubject($customerSubject);
        $email->setTemplate('order');
        $email->setEmailFormat('html');
        $email->setViewVars(array('mailContent' => $mailContent,
            'source' => $source,
            'storename' => $siteName));

        $email->send();

        //Mail to Restaurant

        $toMail = ($orderDetails['restaurant']['email_order'] == 'Yes') ? $orderDetails['restaurant']['order_email'] : $orderDetails['restaurant']['contact_email'];

        $mailContent  	 = $html;
        $customerSubject = 'New Order - '.$orderDetails['order_number'];
        $siteName   	 = $this->siteSettings['site_name'];
        $site_url = BASE_URL;
        $source   = BASE_URL.'images/logo-home.png';
        $fromMail = $orderDetails['customer_email'];
        $email = new Email();
        $email->setFrom($fromMail);
        $email->setTo($toMail);
        $email->setSubject($customerSubject);
        $email->setTemplate('order');
        $email->setEmailFormat('html');
        $email->setViewVars(array('mailContent' => $mailContent,
            'source' => $source,
            'storename' => $siteName));

        $email->send();

        //Mail to Admin
        $mailContent  	 = $html;
        $customerSubject = 'New Order - '.$orderDetails['order_number'];
        $siteName   	 = $this->siteSettings['site_name'];
        $site_url = BASE_URL;
        $source   = BASE_URL.'images/logo-home.png';
        $fromMail = $orderDetails['customer_email'];
        $email = new Email();
        $email->setFrom($fromMail);
        $email->setTo($this->siteSettings['order_email']);
        $email->setSubject($customerSubject);
        $email->setTemplate('order');
        $email->setEmailFormat('html');
        $email->setViewVars(array('mailContent' => $mailContent,
            'source' => $source,
            'storename' => $siteName));

        $email->send();
        return true;

    }

    //-----------------------------------------Check Customer's Profile-------------------------------------------------

    public function checkData() {

        $userDetails = $this->Users->find('all', [
            'conditions' => [
                'id' => $this->Auth->user('id')
            ],
            'fields' => [
                'phone_number',
                'username'
            ]
        ])->hydrate(false)->first();
        
        if($userDetails['phone_number'] == '' && $userDetails['username'] == '') {
            echo 'both';
            die();
        }else if($userDetails['phone_number'] == '') {
            echo 'phone';
            die();
        }else if($userDetails['username'] == '') {
            echo 'email';
            die();
        }else {
            echo 'success';
            die();
        }
    }

    //-----------------------------------------Update phone number for Customer-----------------------------------------

    public function updateProfile() {

        $userEntity = $this->Users->newEntity();
        $userPatch = $this->Users->patchEntity($userEntity,$this->request->getData());
        $userPatch->id = $this->Auth->user('id');
        $userSave = $this->Users->save($userPatch);
        if($userSave) {
            $user = $this->Users->find('all', [
                'conditions' => [
                    'id' => $this->Auth->user('id')
                ]
            ])->hydrate(false)->first();

            $this->Auth->setUser($user);
            echo '0';die();
        }
    }
}