<?php
/**
 * Created by PhpStorm.
 * User: roamadmin
 * Date: 19-01-2018
 * Time: 10:45
 */

namespace App\Controller\Hangryadmn;

use Cake\Event\Event;
use App\Controller\AppController;
use Cake\ORM\Table;
use Cake\Utility\Hash;
use Cake\Mailer\Email;

class OrdersController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->viewBuilder()->setLayout('backend');

        $this->loadModel('Users');
        $this->loadModel('Sitesettings');
        $this->loadModel('Timezones');
        $this->loadModel('Drivers');
        $this->loadComponent('PushNotification');
        $this->loadModel('Countries');
        $this->loadModel('Orders');
        $this->loadModel('StripeCustomers');
        $this->loadModel('WalletHistories');
        $this->loadModel('Restaurants');
        $this->loadComponent('Googlemap');
        $this->loadComponent('FcmNotification');
        $this->loadComponent('IosNotification');
        $this->loadComponent('Twilio');
    }
//-------------------------------------------------------------------------------
    public function index() {


    }
//-------------------------------------------------------------------------------
    public function orderview($id = null) {

    }
//-------------------------------------------------------------------------------

    public function report() {

    }
//-------------------------------------------------------------------------------

    /*Get Order Details For Server Side Table*/
    public function getOrderDetails() {

        $orderBy = '';
        $limit = $this->request->getData('length');
        $page  = ($this->request->getData('start') == 0) ? 1 :
                ($this->request->getData('start')/$limit)+1;

        if(isset($this->request->getData('search')['value']) && !empty($this->request->getData('search')['value'])) {
            $conditions = [
                "OR" => [
                    "Orders.order_number LIKE" => "%".$this->request->getData('search')['value']."%",
                    "Orders.customer_name LIKE" => "%".$this->request->getData('search')['value']."%",
                    "Restaurants.restaurant_name LIKE" => "%".$this->request->getData('search')['value']."%"
                ],
                'Orders.status' => 'Pending',
                'Orders.order_type' => 'delivery',
            ];
        }else {
            $conditions = [
                'Orders.id IS NOT NULL',
                'Orders.status' => 'Pending',
                'Orders.order_type' => 'delivery',
            ];
        }

        if(isset($this->request->getData('order')[0]['column'])
            && !empty($this->request->getData('order')[0]['column'])) {

            $fieldName = (($this->request->getData('order')[0]['column'] == '1') ? 'Orders.order_number' : (($this->request->getData('order')[0]['column'] == '2') ? 'Orders.restaurant_id' : (($this->request->getData('order')[0]['column'] == '3') ? 'Orders.delivery_date' : (($this->request->getData('order')[0]['column'] == '4') ? 'Orders.created' : '' ))));

            if($fieldName != '') {
                $orderBy = [
                    $fieldName => $this->request->getData('order')[0]['dir']
                ];
            }

        }
        if($orderBy == '') {
            $orderBy = [
                'Orders.id' => 'DESC'
            ];
        }
        //echo "<pre>";print_r($conditions);die();
        $orderDetails = $this->Orders->find('all', [
            'conditions' => $conditions,
            'contain' => [
                'Restaurants'
            ],
            'order' => $orderBy,
            'limit' => $limit,
            'page' => $page
        ])->hydrate(false)->toArray();
        //pr($orderDetails);die();

        $orderDetailsCount = $this->Orders->find('all', [
            'conditions' => $conditions,
            'contain' => [
                'Restaurants'
            ]
        ])->hydrate(false)->toArray();

        $Response['draw']            = $this->request->getData('draw');
        $Response['recordsTotal']    = count($orderDetailsCount);
        $Response['recordsFiltered'] = count($orderDetailsCount);

        $url = 'orders/ajaxaction';
        $action = 'custstatuschange';
        $field = 'status';

        if(!empty($orderDetails)) {
            foreach($orderDetails as $key => $value) {
                /*if($value['assoonas'] == '') {
                    $deliveryTime = explode(' ',$value['delivery_time']);
                    $deliveryTime = $deliveryTime[1].' '.$deliveryTime[2];
                }else {
                    $deliveryTime = $value['delivery_time'];
                }*/

                $deliveryTime = $value['delivery_time'];

                $activestatusChange = $value['id'].',"0"'.',"'.$field.'"'.',"'.$url.'"'.',"'.$action.'"';
                $deActiveStatusChange = $value['id'].',"1"'.',"'.$field.'"'.',"'.$url.'"'.',"'.$action.'"';
                $field = 'status';
                $Response['data'][$key]['Id']                = $key+1;
                $Response['data'][$key]['Order ID']              = "<a href='".ADMIN_BASE_URL."orders/view/".$value['id']."' >".$value['order_number']."</a>";
                $Response['data'][$key]['Customer Name']         = $value['customer_name'];
                $Response['data'][$key]['Restaurant Name']      = $value['restaurant']['restaurant_name'];
                $Response['data'][$key]['Delivery Date']      = date('Y-m-d',strtotime($value['delivery_date'])).' '.$deliveryTime;
                //$Response['data'][$key]['Order Date']      = $value['created'];
                if($value['status'] == 'Pending') {
                    $Response['data'][$key]['Status']            = "<select id='currentStatus_".$value['id']."' onchange='changeOrderStatus(".$value['id'].");'><option value='pending'>Pending</option><option value='Accepted'>Accept</option><option value='Failed'>Reject</option><option value='Delivered'>Delivered</option></select> ";
                }else if($value['status'] == 'Accept') {
                    $Response['data'][$key]['Status']            = "<select id='currentStatus_".$value['id']."' onchange='changeOrderStatus(".$value['id'].");'><option value='Accept'>Accepted</option><option value='completed'>Delivered</option><option value='Failed'>Failed</option></select> ";
                }else {
                    $Response['data'][$key]['Status'] = $value['status'];
                }
                $Response['data'][$key]['Order Date']        = date('Y-m-d H:i', strtotime($value['created']));
            }
        }else {
            $Response['data'] = '';
        }
        echo json_encode($Response);die();
    }
//-------------------------------------------------------------------------------
    public function collectionorder() {

    }
//-------------------------------------------------------------------------------


    /*Get Pickup Orders*/

    public function getPickupOrderDetails() {

        $orderBy = '';
        $limit = $this->request->getData('length');
        $page  = ($this->request->getData('start') == 0) ? 1 :
            ($this->request->getData('start')/$limit)+1;

        if(isset($this->request->getData('search')['value']) &&
            !empty($this->request->getData('search')['value'])) {
            $conditions = [
                "OR" => [
                    "Orders.order_number LIKE" => "%".$this->request->getData('search')['value']."%",
                    "Orders.customer_name LIKE" => "%".$this->request->getData('search')['value']."%",
                    "Restaurants.restaurant_name LIKE" => "%".$this->request->getData('search')['value']."%"
                ],
                'AND' =>[
                    ['Orders.status !=' => 'Delivered'],
                    ['Orders.status !=' => 'Failed'],
                ],
                'OR' =>[
                    ['Orders.order_type' => 'pickup'],
                    ['Orders.order_type' => 'collection'],
                ],
                //'Orders.order_type' => 'pickup'
            ];
        }else {
            $conditions = [
                'Orders.id IS NOT NULL',
                'AND' =>[
                    ['Orders.status !=' => 'Delivered'],
                    ['Orders.status !=' => 'Failed'],
                ],
                'OR' =>[
                    ['Orders.order_type' => 'pickup'],
                    ['Orders.order_type' => 'collection'],
                ],
            ];
        }

        if(isset($this->request->getData('order')[0]['column']) &&
            !empty($this->request->getData('order')[0]['column'])) {

            $fieldName = (($this->request->getData('order')[0]['column'] == '1') ? 'Orders.order_number' : (($this->request->getData('order')[0]['column'] == '2') ? 'Orders.restaurant_id' : (($this->request->getData('order')[0]['column'] == '3') ? 'Orders.delivery_date' : (($this->request->getData('order')[0]['column'] == '4') ? 'Orders.created' : '' ))));

            if($fieldName != '') {
                $orderBy = [
                    $fieldName => $this->request->getData('order')[0]['dir']
                ];
            }

        }
        if($orderBy == '') {
            $orderBy = [
                'Orders.id' => 'DESC'
            ];
        }
        //echo "<pre>";print_r($conditions);die();
        $orderDetails = $this->Orders->find('all', [
            'conditions' => $conditions,
            'contain' => [
                'Restaurants'
            ],
            'order' => $orderBy,
            'limit' => $limit,
            'page' => $page
        ])->hydrate(false)->toArray();

        $orderDetailsCount = $this->Orders->find('all', [
            'conditions' => $conditions,
            'contain' => [
                'Restaurants'
            ]
        ])->hydrate(false)->toArray();

        $Response['draw']            = $this->request->getData('draw');
        $Response['recordsTotal']    = count($orderDetailsCount);
        $Response['recordsFiltered'] = count($orderDetailsCount);

        $url = 'orders/ajaxaction';
        $action = 'custstatuschange';
        $field = 'status';

        if(!empty($orderDetails)) {
            foreach($orderDetails as $key => $value) {
                /*if($value['assoonas'] == '') {
                    $deliveryTime = explode(' ',$value['delivery_time']);
                    $deliveryTime = $deliveryTime[1].' '.$deliveryTime[2];
                }else {
                    $deliveryTime = $value['delivery_time'];
                }*/


                $deliveryTime = $value['delivery_time'];

                $activestatusChange = $value['id'].',"0"'.',"'.$field.'"'.',"'.$url.'"'.',"'.$action.'"';
                $deActiveStatusChange = $value['id'].',"1"'.',"'.$field.'"'.',"'.$url.'"'.',"'.$action.'"';
                $field = 'status';
                $Response['data'][$key]['Id']                = $key+1;
                $Response['data'][$key]['Order ID']              = "<a href='".ADMIN_BASE_URL."orders/view/".$value['id']."' >".$value['order_number']."</a>";
                $Response['data'][$key]['Customer Name']         = $value['customer_name'];
                $Response['data'][$key]['Restaurant Name']      = $value['restaurant']['restaurant_name'];
                $Response['data'][$key]['Delivery Date']      = date('Y-m-d',strtotime($value['delivery_date'])).' '.$deliveryTime;
                //$Response['data'][$key]['Order Date']      = $value['created'];
                if($value['status'] == 'Pending') {
                    $Response['data'][$key]['Status']            = "<select id='currentStatus_".$value['id']."' onchange='changeOrderStatus(".$value['id'].");'><option value='pending'>Pending</option><option value='Accepted'>Accept</option><option value='Failed'>Reject</option><option value='Delivered'>Delivered</option></select> ";
                }else if($value['status'] == 'Accepted') {
                    $Response['data'][$key]['Status']            = "<select id='currentStatus_".$value['id']."' onchange='changeOrderStatus(".$value['id'].");'><option value='Accepted'>Accepted</option><option value='Delivered'>Delivered</option><option value='Failed'>Failed</option></select> ";
                }else {
                    $Response['data'][$key]['Status'] = $value['status'];
                }

                $Response['data'][$key]['Order Date']        = date('Y-m-d h:i A',strtotime($value['created']));
            }
        }else {
            $Response['data'] = '';
        }
        echo json_encode($Response);die();
    }
//----------------------------------------------------------------------------------------------------------

    /*Order Status Change*/
    public function changeStatus() {

        //echo "<pre>"; print_r($this->request->getData()); die();

        if($this->request->getData('id') != '' && $this->request->getData('status') != '') {

            $orderId = $this->request->getData('id');
            $orderDetails = $this->Orders->find('all',[
                'conditions' => [
                    'Orders.id' => $this->request->getData('id')
                ],
                'contain' => [
                    'Users',
                    'Drivers',
                    'Restaurants'
                ]
            ])->hydrate(false)->first();

            $orderEntity = $this->Orders->newEntity();
            $orderPatch  = $this->Orders->patchEntity($orderEntity,$this->request->getData());

            if($this->request->getData('status') == 'Accepted') {

                if($this->siteSettings['sms_option'] == 'Yes') {

                    $deliveryDate = date('Y-m-d',strtotime($orderDetails['delivery_date']));

                    $customerMessage = 'Congratulations! Your order '.
                        $orderDetails['order_number'].' succesfully accepted by '.
                        $orderDetails['restaurant']['restaurant_name'].'. Your order will be delivered by '.
                        $deliveryDate. ' at '.
                        $orderDetails['delivery_time'].'. Thanks '.
                        $this->siteSettings['site_name'];

                    $toCustomerNumber = '+'.SITE_PHONE.$orderDetails['customer_phone'];
                    $customerSms      = $this->Twilio->sendMessage($toCustomerNumber, $customerMessage);
                }
                $this->orderEmail($this->request->getData('id'));

            }

            if($this->request->getData('status') == 'Delivered') {

                $orderPatch->completed_time = date('Y-m-d, h:i A');
                $orderPatch->payment_status = 'P';

                //Reward Points

                $this->loadModel('Rewards');
                $this->loadModel('CustomerPoints');

                $rewardPoints = $this->Rewards->find('all', [
                    'conditions' => [
                        'id' => '1'
                    ]
                ])->hydrate(false)->first();

                $getRestaurantOption = $this->Restaurants->find('all', [
                    'fields' => [
                        'reward_option'
                    ],
                    'conditions' => [
                        'id' => $orderDetails['restaurant']['id'],
                    ]
                ])->hydrate(false)->first();

                if(!empty($rewardPoints) && $getRestaurantOption['reward_option'] == 'Yes') {
                    $grandTotal = $orderDetails['order_sub_total'];
                    $rewardAmount = $rewardPoints['reward_amount'];
                    $rewardPoints = $rewardPoints['reward_point'];

                    $getRewardFromTotal = $grandTotal/$rewardAmount;

                    $orderPoint = $getRewardFromTotal * $rewardPoints;

                    $customerPoint['order_id'] = $orderDetails['id'];
                    $customerPoint['restaurant_name'] = $orderDetails['restaurant']['restaurant_name'];
                    $customerPoint['customer_id'] = $orderDetails['customer_id'];
                    $customerPoint['total'] = $grandTotal;
                    $customerPoint['points'] = $orderPoint;
                    $customerPoint['type'] = 'Earned';

                    $customerPointEntity = $this->CustomerPoints->newEntity();
                    $customerPointPatch = $this->CustomerPoints->patchEntity($customerPointEntity,$customerPoint);
                    $customerPointSave = $this->CustomerPoints->save($customerPointPatch);
                }

            }

            $orderSave   = $this->Orders->save($orderPatch);
            if($orderSave) {

                $this->PushNotification->trackingNotification();

                //Customer Notification
                if ($this->request->getData('status') == 'Failed') {

                    //Amount revert to Customer
                    if($orderDetails['payment_method'] == 'Wallet' || $orderDetails['payment_wallet'] == 'Yes') {
                        if($orderDetails['paid_full'] == 'Yes') {
                            $addedAmount = $orderDetails['order_grand_total'];
                        }else {
                            $addedAmount = $orderDetails['wallet_amount'];
                        }

                        $walletAmount = $orderDetails['user']['wallet_amount'] + $addedAmount;

                        //Update Wallet Amount
                        $custEntity = $this->Users->newEntity();
                        $amount['wallet_amount'] = $walletAmount;
                        $custPatch = $this->Users->patchEntity($custEntity,$amount);
                        $custPatch->id = $orderDetails['user']['id'];
                        $saveCust = $this->Users->save($custPatch);


                        //Add Wallet History
                        $walletEntity = $this->WalletHistories->newEntity();
                        $history['customer_id'] = $orderDetails['user']['id'];
                        $history['purpose'] = "Amount Refund for order ".$orderDetails['order_number'];
                        $history['transaction_type'] = 'Credited';
                        $history['transaction_details'] = $orderDetails['order_number'];
                        $history['amount'] = $addedAmount;
                        $walletPatch = $this->WalletHistories->patchEntity($walletEntity,$history);
                        $saveWallet = $this->WalletHistories->save($walletPatch);

                    }


                    $customerMessage = 'Your order ' . $orderDetails['order_number'].' was failed due to '.$this->request->getData('failed_reason').'.';

                } elseif ($this->request->getData('status') == 'Delivered') {
                    $customerMessage = 'Your order delivered ' . $orderDetails['order_number'];
                } else {
                    $customerMessage = 'Your order accepted ' . $orderDetails['order_number'];
                }

                if(!empty($orderDetails) && $orderDetails['user']['device_id'] != '') {

                    $notificationdata['data']['title']          = "orderstatus";
                    $notificationdata['data']['message']        = $customerMessage;
                    $notificationdata['data']['is_background']  = false;
                    $notificationdata['data']['payload']        = array('OrderDetails' => "",'type'    => "orderstatus");
                    $notificationdata['data']['timestamp']      = date('Y-m-d G:i:s');
                    $gcm = (trim($orderDetails['user']['device_type']) == 'Android') ?
                        $this->FcmNotification->sendNotification($notificationdata, $orderDetails['user']['device_id']) :
                        $this->IosNotification->notificationIOS($customerMessage, $orderDetails['user']['device_id'], 'Customer');
                }

                $this->PushNotification->customerNotification($orderDetails['customer_id'],$orderDetails['restaurant']['user_id'],$customerMessage);

                //SMS FOR Failed and Delivered to Customer
                if($this->request->getData('status') == 'Failed' || $this->request->getData('status') == 'Delivered') {

                    if($this->request->getData('status') == 'Failed') {
                        //SMS TO customer
                        $customerMessage = 'Your order ' . $orderDetails['order_number'].' was failed due to '.$this->request->getData('failed_reason').'.';
                    }else {
                        //SMS TO customer
                        $customerMessage = 'Your order ' . $orderDetails['order_number'].' was delivered successful';
                    }

                    if($this->siteSettings['sms_option'] == 'Yes') {

                        $deliveryDate = date('Y-m-d',strtotime($orderDetails['delivery_date']));
                        $customerMessage = 'Congratulations! Your order '.
                            $orderDetails['order_number'].' succesfully accepted by '.
                            $orderDetails['restaurant']['restaurant_name'].'. Your order will be delivered by '.
                            $deliveryDate. ' at '.
                            $orderDetails['delivery_time'].'. Thanks '.
                            $this->siteSettings['site_name'];

                        $toCustomerNumber = '+'.SITE_PHONE.$orderDetails['customer_phone'];
                        $customerSms      = $this->Twilio->sendMessage($toCustomerNumber, $customerMessage);
                    }
                }

                //Driver Notification
                if(!empty($orderDetails) && $orderDetails['driver']['device_id'] != '') {

                    if ($this->request->getData('status') == 'Failed') {

                        $driverMessage = 'Disclaim Order '.$orderDetails['order_number'];
                        $notificationdata['data']['title']          = "disclaimorder";
                        $ack       = array('message' => $driverMessage,'OrderId' => $orderId,'OrderDetails' => '');
                        $notificationdata['data']['payload']        = array('OrderDetails' => $ack,'type'    => "disclaimorder");
                    } elseif ($this->request->getData('status') == 'Delivered') {

                        $driverMessage = 'Delivered Order '.$orderDetails['order_number'];
                        $notificationdata['data']['title']          = "deliverdorder";
                        $ack       = array('message' => $driverMessage,'OrderId' => $orderId,'OrderDetails' => '');
                        $notificationdata['data']['payload']        = array('OrderDetails' => $ack,'type'    => "deliverdorder");
                    } else {

                        $driverMessage = 'Disclaim Order '.$orderDetails['order_number'];
                        $notificationdata['data']['title']          = "deliverdorder";
                        $ack       = array('message' => $driverMessage,'OrderId' => $orderId,'OrderDetails' => '');
                        $notificationdata['data']['payload']        = array('OrderDetails' => $ack,'type'    => "acceptedorder");
                    }

                    $notificationdata['data']['message']        = $driverMessage;
                    $notificationdata['data']['is_background']  = false;
                    $notificationdata['data']['timestamp']      = date('Y-m-d G:i:s');
                    $gcm = (trim($orderDetails['driver']['device_name']) == 'ANDROID') ?
                        $this->FcmNotification->sendNotification($notificationdata, $orderDetails['driver']['device_id']) :
                        $this->IosNotification->notificationIOS($driverMessage, $orderDetails['driver']['device_id'], '');
                }

                if($this->request->getData('status') == 'Delivered') {
                    //Driver Basic Details
                    if($orderDetails['driver_id'] != 0) {

                        $driverDetails = $this->Drivers->find('all', [
                            'conditions' => [
                                'id' => $orderDetails['driver_id']
                            ]
                        ])->hydrate(false)->first();

                        $sourcelatitude = $orderDetails['source_latitude'];
                        $sourcelongitude = $orderDetails['source_longitude'];
                        $latitudeTo = $orderDetails['destination_latitude'];
                        $longitudeTo = $orderDetails['destination_longitude'];

                        $unit='K';
                        $distance = $this->Common->getDistanceValue($sourcelatitude,$sourcelongitude,$latitudeTo,$longitudeTo,
                            $unit);

                        $distance = str_replace(',','',$distance);

                        $orderProof['payout_type'] = $driverDetails['payout'];
                        $orderProof['payout_amount'] = $driverDetails['payout_amount'];
                        $orderProof['distance'] = $distance;

                        $orderEntity = $this->Orders->newEntity();
                        $orderEntity = $this->Orders->patchEntity($orderEntity, $orderProof);
                        $orderEntity->id = $orderDetails['id'];
                        $this->Orders->save($orderEntity);
                    }
                }
                echo '1';die();
            }

        }else {
            echo '0';die();
        }
    }
//----------------------------------------------------------------------------------------------------------

    /*Order Change Status Functionality*/
    public function orderStatus() {

        if($this->request->getData('id') != '' && $this->request->getData('status') != '') {

            $orderId = $this->request->getData('id');
            $orderDetails = $this->Orders->find('all',[
                'conditions' => [
                    'Orders.id' => $this->request->getData('id')
                ],
                'contain' => [
                    'Users',
                    'Drivers',
                    'Restaurants'
                ]
            ])->hydrate(false)->first();

            $orderEntity = $this->Orders->newEntity();
            $orderPatch  = $this->Orders->patchEntity($orderEntity,$this->request->getData());
            $orderPatch->driver_id  = '';
            $orderSave   = $this->Orders->save($orderPatch);
            if($orderSave) {
                //Driver Notification
                if(!empty($orderDetails) && $orderDetails['driver']['device_id'] != '') {

                    $driverMessage = 'Disclaim Order '.$orderDetails['order_number'];
                    $notificationdata['data']['title']          = "deliverdorder";
                    $ack       = array('message' => $driverMessage,'OrderId' => $orderId,'OrderDetails' => '');
                    $notificationdata['data']['payload']        = array('OrderDetails' => $ack,'type'    => "acceptedorder");

                    $notificationdata['data']['message']        = $driverMessage;
                    $notificationdata['data']['is_background']  = false;
                    $notificationdata['data']['timestamp']      = date('Y-m-d G:i:s');
                    $gcm = (trim($orderDetails['driver']['device_name']) == 'ANDROID') ?
                        $this->FcmNotification->sendNotification($notificationdata, $orderDetails['driver']['device_id']) :
                        $this->IosNotification->notificationIOS($driverMessage, $orderDetails['driver']['device_id'], '');
                }
                $this->PushNotification->rejectNotification($orderDetails['order_number']);
                echo '1';die();
            }
        }else {
            echo '0';die();
        }
    }
//----------------------------------------------------------------------------------------------------------
    /*Ajaxaction Functionality*/
    public function ajaxaction() {
        if($this->request->getData('action') == 'getDriverLists') {

            if($this->request->getData('id') != '') {

                $orderId = $this->request->getData('id');

                $availableDrivers = [];
                $orderDetails = $this->Orders->find('all', [
                    'conditions' => [
                        'id' => $this->request->getData('id')
                    ],
                ])->hydrate(false)->first();

                $restUserId = $this->Restaurants->find('all', [
                    'conditions' => [
                        'id' => $orderDetails['restaurant_id']
                    ]
                ])->hydrate(false)->first();


                if($orderDetails['driver_id'] == 0) {
                    $driverLists = $this->Drivers->find('all', [
                        'conditions' => [
                            'Drivers.is_logged' => '1',
                            'Drivers.driver_status' => 'Available',
                            'OR' => [
                                ['Drivers.created_id' => '1'],
                                ['Drivers.created_id' => $restUserId['user_id']],
                            ]
                        ],
                        'contain' => [
                            'DriverTrackings'
                        ]
                    ])->hydrate(false)->toArray();

                    if(!empty($driverLists)) {

                        foreach ($driverLists as $dKey => $dValue) {
                            if($dValue['driver_tracking']['driver_latitude'] != '' && $dValue['driver_tracking']['driver_longitude'] != '') {

                                $distance = $this->Googlemap->getDrivingDistance($dValue['driver_tracking']['driver_latitude'],  
                                $dValue['driver_tracking']['driver_longitude'],$orderDetails['source_latitude'],$orderDetails['source_longitude']);
                                $dValue['distance'] = (!empty($distance)) ? $distance['distanceText'] : 'Out of range';
                                $dValue['reachtime'] = (!empty($distance)) ? $distance['durationText'] : 'Out of range';
                                $availableDrivers[$dKey] = $dValue;
                            }
                        }

                        $distance = array();
                        if(!empty($availableDrivers)) {
                            foreach ($availableDrivers as $key => $row) {
                                $distance[$key] = $row['distance'];
                            }
                            array_multisort($distance, SORT_ASC, $availableDrivers);
                            $this->set(compact('orderId', 'availableDrivers'));
                        }
                    }
                }
                $action = $this->request->getData('action');
                $this->set(compact('orderId', 'availableDrivers','action'));
            }
        }

        if($this->request->getData('action') == 'InitialTracking') {
            $action = $this->request->getData('action');
            $this->set(compact('action'));
        }

        if($this->request->getData('action') == 'LoadTrackingMap') {

            $action = $this->request->getData('action');

            $trackDetails = $this->Orders->find('all', [
                'conditions' => [
                    'Orders.id' => $this->request->getData('id')
                ],
                'contain' => [
                    'Restaurants'
                ]
            ])->hydrate(false)->first();

            $driverDetails = $this->Drivers->find('all', [
                'conditions' => [
                    'Drivers.id' => $trackDetails['driver_id']
                ],
                'contain' => [
                    'DriverTrackings'
                ]
            ])->hydrate(false)->first();


            if (strtolower($trackDetails['status']) == 'accepted') {

                if ($this->Auth->User('role_id') == 1) {

                    $drivers = $this->Drivers->find('all', [
                        'conditions' => [
                            'driver_status' => 'Available',
                            'OR' => [
                                ['created_id' => '1'],
                                ['created_id' => $trackDetails['restaurant_id']],
                            ]
                        ],
                        'contain' => [
                            'DriverTrackings'
                        ]
                    ])->hydrate(false)->toArray();
                }
            }

            if ($trackDetails['status'] != 'Accepted' && $trackDetails['status'] != 'Delivered') {

                if ($trackDetails['status'] == 'Driver Accepted') {

                    $sourceLat = $driverDetails['driver_tracking']['driver_latitude'];
                    $sourceLong = $driverDetails['driver_tracking']['driver_longitude'];
                    $destinationLat = $trackDetails['destination_latitude'];
                    $destinationLong = $trackDetails['destination_longitude'];
                    $trackDetails['distance'] = $this->Googlemap->getDrivingDistance(
                        $sourceLat, $sourceLong,
                        $destinationLat, $destinationLong
                    );
                } else {
                    $sourceLat = $trackDetails['source_latitude'];
                    $sourceLong = $trackDetails['source_longitude'];
                    $destinationLat = $trackDetails['destination_latitude'];
                    $destinationLong = $trackDetails['destination_longitude'];
                    $trackDetails['distance'] = $this->Googlemap->getDrivingDistance(
                        $sourceLat, $sourceLong,
                        $destinationLat, $destinationLong
                    );
                }

                $this->set(compact('driverDetails','action'));
            }


            //pr($trackDetails);die();
            $action = $this->request->getData('action');
            $this->set('action', $action);
            $this->set('orders', $trackDetails);
            $this->set('driverDetails', $driverDetails);
            if (isset($drivers)) {
                $this->set(compact('drivers','action'));
            }
        }

        if($this->request->getData('action') == 'LoadDispatchMap') {

            $action = $this->request->getData('action');

            $trackDetails = $this->Orders->find('all', [
                'conditions' => [
                    'Orders.id' => $this->request->getData('id')
                ],
                'contain' => [
                    'Restaurants'
                ]
            ])->hydrate(false)->first();

            $driverDetails = $this->Drivers->find('all', [
                'conditions' => [
                    'Drivers.id' => $trackDetails['driver_id']
                ],
                'contain' => [
                    'DriverTrackings'
                ]
            ])->hydrate(false)->first();


            if (strtolower($trackDetails['status']) == 'accepted') {

                if ($this->Auth->User('role_id') == 1) {

                    $drivers = $this->Drivers->find('all', [
                        'conditions' => [
                            'driver_status' => 'Available',
                            'OR' => [
                                ['created_id' => '1'],
                                ['created_id' => $trackDetails['restaurant_id']],
                            ]
                        ],
                        'contain' => [
                            'DriverTrackings'
                        ]
                    ])->hydrate(false)->toArray();
                }
            }

            if ($trackDetails['status'] != 'Accepted' && $trackDetails['status'] != 'Delivered') {


                if ($trackDetails['status'] == 'Driver Accepted') {

                    $sourceLat = $driverDetails['driver_tracking']['driver_latitude'];
                    $sourceLong = $driverDetails['driver_tracking']['driver_longitude'];
                    $destinationLat = $trackDetails['destination_latitude'];
                    $destinationLong = $trackDetails['destination_longitude'];
                    $trackDetails['distance'] = $this->Googlemap->getDrivingDistance(
                        $sourceLat, $sourceLong,
                        $destinationLat, $destinationLong
                    );
                } else {
                    $sourceLat = $trackDetails['source_latitude'];
                    $sourceLong = $trackDetails['source_longitude'];
                    $destinationLat = $trackDetails['destination_latitude'];
                    $destinationLong = $trackDetails['destination_longitude'];
                    $trackDetails['distance'] = $this->Googlemap->getDrivingDistance(
                        $sourceLat, $sourceLong,
                        $destinationLat, $destinationLong
                    );
                }

                $this->set(compact('driverDetails','action'));
            }


            //pr($trackDetails);die();
            $action = $this->request->getData('action');
            $this->set('action', $action);
            $this->set('orders', $trackDetails);
            $this->set('driverDetails', $driverDetails);
            if (isset($drivers)) {
                $this->set(compact('drivers','action'));
            }
        }

        if($this->request->getData('action') == 'LoadAllMap') {

            $currentDate = date('Y-m-d');

            $type = $this->request->getData('type');

            $ordersList = array();
            if($type == 'Unassign') {


                $ordersList = $this->Orders->find('all', [
                    'conditions' => [
                        'Orders.order_type' => 'delivery',
                        'Orders.created LIKE' => $currentDate.'%',
                        'OR' => [
                            'Orders.status' => 'Accepted'
                        ]
                    ],
                    'contain' => [
                        'Restaurants',
                        'Drivers'
                    ],
                    'order' => [
                        'Orders.id' => 'DESC'
                    ]
                ])->hydrate(false)->toArray();


            } elseif($type == 'Waiting') {

                $ordersList = $this->Orders->find('all', [
                    'conditions' => [
                        'Orders.order_type' => 'delivery',
                        'Orders.created LIKE' => $currentDate.'%',
                        'OR' => [
                            'Orders.status' => 'Waiting'
                        ]
                    ],
                    'contain' => [
                        'Restaurants',
                        'Drivers'
                    ],
                    'order' => [
                        'Orders.id' => 'DESC'
                    ]
                ])->hydrate(false)->toArray();

                $Drivers = $this->Drivers->find('all', [
                    'conditions' => [
                        'driver_status !=' => 'offline'
                    ],
                    'contain' => [
                        'DriverTrackings',
                        'Orders' => [
                            'conditions' => [
                                'Orders.order_type' => 'delivery',
                                'Orders.created LIKE' => $currentDate.'%',
                                'OR' => [
                                    'Orders.status' => 'Waiting'
                                ]
                            ]
                        ]
                    ]
                ])->hydrate(false)->toArray();

            } elseif ($type == 'Assign') {


                $ordersList = $this->Orders->find('all', [
                    'conditions' => [
                        'Orders.order_type' => 'delivery',
                        'Orders.created LIKE' => $currentDate.'%',
                        'OR' => [
                            ['Orders.status' => 'Driver Accepted'],
                            ['Orders.status' => 'Collected']
                        ]
                    ],
                    'contain' => [
                        'Restaurants',
                        'Drivers'
                    ],
                    'order' => [
                        'Orders.id' => 'DESC'
                    ]
                ])->hydrate(false)->toArray();

                $Drivers = $this->Drivers->find('all', [
                    'conditions' => [
                        'Drivers.driver_status !=' => 'offline'
                    ],
                    'contain' => [
                        'DriverTrackings',
                        'Orders' => [
                            'conditions' => [
                                'Orders.order_type' => 'delivery',
                                'Orders.created LIKE' => $currentDate.'%',
                                'OR' => [
                                    ['Orders.status' => 'Driver Accepted'],
                                    ['Orders.status' => 'Collected']
                                ]
                            ]
                        ]
                    ]
                ])->hydrate(false)->toArray();

            }

            //echo "<pre>";print_r($Drivers);echo "</pre>";
            $action = $this->request->getData('action');
            $this->set('action', $action);
            $this->set('orders', $ordersList);
            if(isset($Drivers)) {
                $this->set('Drivers', $Drivers);
            }
        }

        if($this->request->getData('action') == 'dispatchUpdate') {

            $currentDate = date('Y-m-d');

            //Accepted Orders
            $acceptedOrder = $this->Orders->find('all', [
                'conditions' => [
                    'Orders.status' => 'Accepted',
                    "Orders.created LIKE" => "%".$currentDate."%",
                ],
                'contain' => [
                    'Restaurants' => [
                        'fields' => [
                            'restaurant_name'
                        ]
                    ]
                ],
                'order' => [
                    'Orders.id' => 'DESC'
                ]
            ])->hydrate(false)->toArray();


            //Waiting Orders
            $waitingOrders = $this->Orders->find('all', [
                'conditions' => [
                    'OR' => [
                        'Orders.status' => 'Waiting',
                    ],
                    'Orders.order_type' => 'delivery',
                    "Orders.created LIKE" => "%".$currentDate."%",
                ],
                'contain' => [
                    'Restaurants' => [
                        'fields' => [
                            'Restaurants.restaurant_name',
                        ]
                    ],
                    'Drivers' => [
                        'fields' => [
                            'driver_name'
                        ]
                    ]
                ],
                'order' => [
                    'Orders.id' => 'DESC'
                ]
            ])->hydrate(false)->toArray();

            //Driver Accepted Order Lists
            $assignedOrders = $this->Orders->find('all', [
                'conditions' => [
                    'OR' => [
                        ['Orders.status' => 'Driver Accepted'],
                        ['Orders.status' => 'Collected'],
                    ],
                    'Orders.order_type' => 'delivery',
                    "Orders.created LIKE" => "%".$currentDate."%",
                ],
                'contain' => [
                    'Restaurants' => [
                        'fields' => [
                            'Restaurants.restaurant_name',
                        ]
                    ],
                    'Drivers' => [
                        'fields' => [
                            'driver_name'
                        ]
                    ]
                ],
                'order' => [
                    'Orders.id' => 'DESC'
                ]
            ])->hydrate(false)->toArray();

            //Completed Order List
            $completedOrders = $this->Orders->find('all', [
                'conditions' => [
                    'Orders.status' => 'Delivered',
                    'Orders.order_type' => 'delivery',
                    "Orders.created LIKE" => "%".$currentDate."%",
                ],
                'contain' => [
                    'Restaurants' => [
                        'fields' => [
                            'Restaurants.restaurant_name',
                        ]
                    ],
                    'Drivers' => [
                        'fields' => [
                            'driver_name'
                        ]
                    ]
                ],
                'order' => [
                    'Orders.id' => 'DESC'
                ]
            ])->hydrate(false)->toArray();

            $action = $this->request->getData('action');
            $this->set(compact('acceptedOrder','action','waitingOrders','assignedOrders','completedOrders'));
        }

        if($this->request->getData('action') == 'dispatchDrivers') {

            $currentDate = date('Y-m-d');

            $onlineDrivers = $this->Drivers->find('all', [
                'fields' => [
                    'Drivers.id',
                    'Drivers.driver_name',
                    'Drivers.driver_status',
                    'Drivers.phone_number',
                    'Drivers.username',
                    //'Drivers.image',

                ],
                'conditions' => [
                    'NOT' => [
                        ['Drivers.driver_status' => 'offline'],
                    ]
                ],
                'contain' => [
                    'DriverTrackings' => [
                        'fields' => [
                            'DriverTrackings.driver_id',
                            'DriverTrackings.driver_latitude',
                            'DriverTrackings.driver_longitude',
                        ]
                    ],
                    'Orders' => [
                        'conditions' => [
                            'Orders.created LIKE' => $currentDate.'%',
                            'NOT' => [
                                ['Orders.status' => 'Failed'],
                                ['Orders.status' => 'Deleted'],
                            ]
                        ],
                        'fields' => [
                            'Orders.id',
                            'Orders.order_number',
                            'Orders.status',
                            'Orders.customer_name',
                            'Orders.address',
                            'Orders.driver_id',
                        ],
                        'sort' => ['Orders.id DESC']
                    ],
                ],
                // 'order' => 'Driver.id ASC'
            ])->hydrate(false)->toArray();

            //pr($onlineDrivers);die();
            foreach ($onlineDrivers as $key => $value) {
                $onlineDrivers[$key]['orders']['order_count'] = count($value['orders']);

                $currOrders = array_filter($value['orders'], function($onDrivers) {
                    if($onDrivers['status'] != 'Delivered') {
                        return true;
                    }
                });
                $onlineDrivers[$key]['orders']['current_order_count'] = count($currOrders);
            }



            $onlineDrivers = Hash::sort($onlineDrivers,'{n}.Order.current_order_count','DESC');

            $siteSettings = $this->Sitesettings->find('all', [
                'conditions' => [
                    'id' => '1'
                ]
            ])->hydrate(false)->first();

            $countries = $this->Countries->find('all', [
                'conditions' => [
                    'id' => $siteSettings['site_country']
                ]
            ])->hydrate(false)->first();

            //pr($onlineDrivers);die();
            $action = $this->request->getData('action');
            $this->set(compact('onlineDrivers','countries','action'));
        }

        if($this->request->getData('action') == 'viewOrder') {
            $orderDetails = $this->Orders->find('all',[
                'conditions' => [
                    'Orders.id' => $this->request->getData('id')
                ],
                'contain' => [
                    'Restaurants',
                    'Users',
                    'Carts'
                ]
            ])->hydrate(false)->first();

            $action = $this->request->getData('action');
            $this->set(compact('orderDetails','action'));
        }
    }
//----------------------------------------------------------------------------------------------------------
    // Order assign to driver
    public function assignOrder() {

        $orderId = $this->request->getData('id');
        $driverId = $this->request->getData('driver');

        $ordersDetails = $this->Orders->find('all', [
            'conditions' => [
                'Orders.id' => $orderId
            ],
            'contain' => [
                'Restaurants'
            ]
        ])->hydrate(false)->first();

        //pr($ordersDetails);die();

        if (!$ordersDetails['driver_id']) {

            $driverDetails = $this->Drivers->find('all', [
                'conditions' => [
                    'id' => $driverId,
                    'status' => '1'
                ]
            ])->hydrate(false)->first();


            if (SEARCHBY != 'Google') {

                $stateName = $this->States->find('all', [
                    'fields' => [
                        'state_name'
                    ],
                    'conditions' => [
                        'id' => $ordersDetails['restaurant']['state_id']
                    ]
                ])->hydrate(false)->first();

                $cityName = $this->Cities->find('all', [
                    'fields' => [
                        'city_name'
                    ],
                    'conditions' => [
                        'id' => $ordersDetails['restaurant']['location_id']
                    ]
                ])->hydrate(false)->first();

                $locationName = $this->Locations->find('all', [
                    'fields' => [
                        'area_name',
                        'zip_code'
                    ],
                    'conditions' => [
                        'id' => $ordersDetails['restaurant']['city_id']
                    ]
                ])->hydrate(false)->first();


                $restAddress = $ordersDetails['restaurant']['address'].','.$locationName['area_name'].','.$cityName['city_name'].','.$locationName['zip_code'].','.$stateName['state_name'];
            } else {
                $restAddress = $ordersDetails['restaurant']['contact_address'];
            }


            $orderDetails['paymentType']            = $ordersDetails['payment_method'];
            $orderDetails['CustomerName']           = $ordersDetails['customer_name'];

            $orderDetails['StoreName']              = stripslashes($ordersDetails['restaurant']['restaurant_name']);
            $orderDetails['SourceAddress']          = $restAddress;
            $orderDetails['SourceLatitude']         = $ordersDetails['source_latitude'];
            $orderDetails['SourceLongitude']        = $ordersDetails['source_longitude'];
            $orderDetails['DestinationAddress']     = $ordersDetails['flat_no'].','.$ordersDetails['address'];

            $orderDetails['DestinationLatitude']    = $ordersDetails['destination_latitude'];
            $orderDetails['DestinationLongitude']   = $ordersDetails['destination_longitude'];
            $orderDetails['OrderDate']              = $ordersDetails['delivery_date'];
            $orderDetails['OrderTime']              =
                ($ordersDetails['assoonas'] != 'now') ? $ordersDetails['delivery_time'] : 'ASAP';
            $orderDetails['OrderPrice']             = $ordersDetails['order_grand_total'];
            $orderDetails['OrderId']                = $ordersDetails['id'];
            $orderDetails['OrderGenerateId']        = $ordersDetails['order_number'];

            $distance = $this->Googlemap->getDrivingDistance(
                $orderDetails['SourceLatitude'],
                $orderDetails['SourceLongitude'],
                $orderDetails['DestinationLatitude'],
                $orderDetails['DestinationLongitude']);

            $orderDetails['Distance'] = (!empty($distance['distanceText'])) ? $distance['distanceText'] : '';


            $count = $this->Orders->find('all', [
                'conditions' => [
                    'driver_id' => $driverId,
                    'status' => 'Waiting'
                ]
            ])->count();

            $orderDetails['waitingCount'] = $count + 1;

            if (!empty($driverDetails)) {

                $deviceId                                   = $driverDetails['device_id'];
                $message        = 'New order came - '.$ordersDetails['order_number'];
                $notificationdata['data']['title']          = "assignorder";
                $notificationdata['data']['message']        = 'New order came - '.$ordersDetails['order_number'];
                $notificationdata['data']['is_background']  = false;
                $notificationdata['data']['payload']        = array('OrderDetails' => $orderDetails,'type'    => "assignorder");
                $notificationdata['data']['timestamp']      = date('Y-m-d G:i:s');

                //$gcm = $this->FcmNotification->sendNotification($notificationdata,$deviceId);

                $gcm    = (trim($driverDetails['device_name']) == 'ANDROID') ?
                    $this->FcmNotification->sendNotification($notificationdata, $deviceId) :
                    $this->IosNotification->notificationIOS($message, $deviceId, 'Driver', 'Driver', $orderDetails);

                //$this->Notifications->pushNotification('Driver assigned for order'.$ordersDetails['order_number'], 'FoodOrderAdmin');

                $orderStatus['id']          = $orderId;
                $orderStatus['status']      = 'Waiting';
                $orderStatus['driver_id']   = $driverId;

                $orderEntity = $this->Orders->newEntity();
                $orderPatch = $this->Orders->patchEntity($orderEntity,$orderStatus);
                $orderSave = $this->Orders->save($orderPatch);

                $this->PushNotification->trackingNotification();

                // echo $gcm['success'];
                echo '1';
            } else {
                die('404');
            }
        }

        die();
    }
//----------------------------------------------------------------------------------------------------------

    /*Order View*/
    public function view($orderId = null) {

        $orderDetails = $this->Orders->find('all', [
            'conditions' => [
                'Orders.id' => $orderId
            ],
            'contain' => [
                'Restaurants' => [
                    'fields' => [
                        'Restaurants.id',
                        'Restaurants.restaurant_name',
                        'Restaurants.contact_address',
                    ]
                ],
                'Carts' => [
                    'fields' => [
                        'Carts.id',
                        'Carts.order_id',
                        'Carts.menu_name',
                        'Carts.subaddons_name',
                        'Carts.menu_price',
                        'Carts.total_price',
                        'Carts.quantity',
                        'Carts.menu_description',
                        'Carts.menu_image',
                    ]
                ]
            ]
        ])->hydrate(false)->first();
        
        $this->set(compact('orderDetails'));
    }

    /*Order Email Functionality*/

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
        $orderDescription = '';

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

        if($orderDetails['order_description'] != "") {
            $orderDescription = '<tr>
                     <td align="center" height="100%" valign="top" width="100%" style="padding:20px;background-color:#fff">
                        <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="width:670px;background:#eee;padding:10px;">
                           <tr>
                              <td align="left" width="50%" valign="top" style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px;">
                                 <span style="font-weight: 700; display:block;">Order Instruction : '.$orderDetails['order_description'].'</span>
                                 
                              </td>                              
                           </tr>
                        </table>
                     </td>
                  </tr>';
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
                  
                  '.$orderDescription.'
                  <tr>
                     <td align="center" valign="top" style="font-size:0; padding: 10px;" bgcolor="#FF6300"><div style="color:#fff;font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px;">Copyrights '.$this->siteSettings["site_name"].' @ 2018</div></td>
                  </tr>
               </table>
            </td>
         </tr>';

        $mailContent  	 = $html;
        $customerSubject = 'Order Accepted - '.$orderDetails['order_number'];;
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


    }
//----------------------------------------------------------------------------------------------------------
public function menu_edit() {


    }
}