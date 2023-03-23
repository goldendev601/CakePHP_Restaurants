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

class DispatchesController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->viewBuilder()->setLayout('backend');

        $this->loadModel('Users');
        $this->loadModel('Sitesettings');
        $this->loadModel('Drivers');
        $this->loadModel('Timezones');
        $this->loadModel('Countries');
        $this->loadModel('Orders');
    }
//----------------------------------------------------------------------------------
    public function index() {

    }
//----------------------------------------------------------------------------------
    public function orderview($id = null) {

    }
//----------------------------------------------------------------------------------
    public function ajaxaction() {

    }
//----------------------------------------------------------------------------------
    public function report() {

    }
//----------------------------------------------------------------------------------
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
                    "Orders.status LIKE" => "%".$this->request->getData('search')['value']."%",
                    "Drivers.driver_name LIKE" => "%".$this->request->getData('search')['value']."%",
                    "Drivers.phone_number LIKE" => "%".$this->request->getData('search')['value']."%",
                    "Restaurants.restaurant_name LIKE" => "%".$this->request->getData('search')['value']."%"
                ],
                'AND' => [
                    ['Orders.status !=' => 'Pending'],
                    ['Orders.status !=' => 'Failed'],
                    ['Orders.status !=' => 'Delivered'],
                    ['Orders.status !=' => 'Deleted'],
                ],
                'Orders.order_type' => 'delivery'
            ];
        }else {
            $conditions = [
                'Orders.id IS NOT NULL',
                'AND' => [
                    ['Orders.status !=' => 'Pending'],
                    ['Orders.status !=' => 'Failed'],
                    ['Orders.status !=' => 'Delivered'],
                    ['Orders.status !=' => 'Deleted'],
                ],
                'Orders.order_type' => 'delivery'

            ];
        }

        if(isset($this->request->getData('order')[0]['column']) && !empty($this->request->getData('order')[0]['column'])) {

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
                'Restaurants',
                'Users',
                'Drivers'
            ],
            'order' => $orderBy,
            'limit' => $limit,
            'page' => $page
        ])->hydrate(false)->toArray();
        //pr($orderDetails);die();

        $orderDetailsCount = $this->Orders->find('all', [
            'conditions' => $conditions,
            'contain' => [
                'Restaurants',
                'Drivers'
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
                $Response['data'][$key]['Id']                   = $key+1;
                $Response['data'][$key]['Date & Time']          = date('Y-m-d',strtotime($value['created']));
                $Response['data'][$key]['Order Information']    = "<a style='color:#F26822;font-weight:bold' href='".ADMIN_BASE_URL."orders/view/".$value['id']."' >".$value['order_number']."</a>".'<br>'.$value['address'].'<br><br>'.$value['restaurant']['contact_address'];
                $Response['data'][$key]['Restaurant Name']      = $value['restaurant']['restaurant_name'];
                $Response['data'][$key]['Price']                = number_format($value['order_grand_total'],2);
                $Response['data'][$key]['Driver Name']          = (!empty($value['driver'])) ? $value['driver']['driver_name'].'/'.$value['driver']['phone_number'] : 'Not Yet Assigned' ;

                $Response['data'][$key]['Status']            = "<span>".$value['status']."</span><br><select id='currentStatus_".$value['id']."' onchange='changeOrderStatus(".$value['id'].");'><option value=''>Select</option><option value='Failed'>Reject</option><option value='Delivered'>Delivered</option></select> ";

                if($value['status'] == 'Accepted') {
                    $status = '<a onclick="return getDriverList('.$value['id'].')" class="drive_icon" href="javascript:void(0)"><i class="fa fa-car"></i></a>';
                }else {
                    $status = '<a onclick="return disclaimOrder('.$value['id'].')" class="drive_icon" href="javascript:void(0)"><i class="fa fa-ban"></i></a>';
                }

                $Response['data'][$key]['Action']           = $status.'<a onclick="return viewTrack('.$value['id'].')" class="drive_icon" href="javascript:void(0)"><i class="fa fa-search"></i></a>';
            }
        }else {
            $Response['data'] = '';
        }
        echo json_encode($Response);die();
    }
//----------------------------------------------------------------------------------
    public function changeStatus() {

        if($this->request->getData('id') != '' && $this->request->getData('status') != '') {
            $orderEntity = $this->Orders->newEntity();
            $orderPatch  = $this->Orders->patchEntity($orderEntity,$this->request->getData());
            $orderSave   = $this->Orders->save($orderPatch);
            if($orderSave) {
                echo '1';die();
            }
        }else {
            echo '0';die();
        }
    }
//----------------------------------------------------------------------------------

    public function tracking() {

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
                        'Orders.order_type' => 'delivery',
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





        $onlineDrivers = Hash::sort($onlineDrivers,'{n}.orders.current_order_count','DESC');

        //echo "<pre>";print_r($onlineDrivers);die();

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
        $this->set(compact('acceptedOrder','waitingOrders','assignedOrders','completedOrders','onlineDrivers','countries'));
    }
//----------------------------------------------------------------------------------
}