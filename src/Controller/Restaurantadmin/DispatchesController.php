<?php
/**
 * Created by PhpStorm.
 * User: roamadmin
 * Date: 19-01-2018
 * Time: 10:45
 */

namespace App\Controller\Restaurantadmin;

use Cake\Event\Event;
use App\Controller\AppController;
use Cake\ORM\Table;

class DispatchesController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->viewBuilder()->setLayout('restaurant');

        $this->loadModel('Users');
        $this->loadModel('Sitesettings');
        $this->loadModel('Timezones');
        $this->loadModel('Orders');
        $this->loadModel('Restaurants');
    }

    public function index() {

    }

    public function orderview($id = null) {

    }

    public function ajaxaction() {

    }

    public function report() {

    }

    /*Get Orderdeails For Server side Table*/
    public function getOrderDetails() {

        $restDetails = $this->Restaurants->find('all', [
            'fields' => [
                'id'
            ],
            'conditions' => [
                'user_id' => $this->Auth->user('id')
            ]
        ])->hydrate(false)->first();

        $orderBy = '';
        if(isset($this->request->getData('search')['value']) && !empty($this->request->getData('search')['value'])) {
            $conditions = [
                "OR" => [
                    "Orders.order_number LIKE" => "%".$this->request->getData('search')['value']."%",
                    "Orders.customer_name LIKE" => "%".$this->request->getData('search')['value']."%",
                    "Orders.status LIKE" => "%".$this->request->getData('search')['value']."%",
                    "Drivers.driver_name LIKE" => "%".$this->request->getData('search')['value']."%",
                    "Drivers.phone_number LIKE" => "%".$this->request->getData('search')['value']."%",
                ],
                'AND' => [
                    ['Orders.status !=' => 'Pending'],
                    ['Orders.status !=' => 'Failed'],
                    ['Orders.status !=' => 'Delivered'],
                    ['Orders.status !=' => 'Deleted'],
                ],
                'Orders.restaurant_id' => $restDetails['id'],
                'Orders.order_type' => 'delivery'
            ];
        }else {
            $conditions = [
                'Orders.id IS NOT NULL',
                'Orders.restaurant_id' => $restDetails['id'],
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
            'order' => $orderBy
        ])->hydrate(false)->toArray();
       //pr($orderDetails);die();

        $Response['draw']            = $this->request->getData('draw');
        $Response['recordsTotal']    = count($orderDetails);
        $Response['recordsFiltered'] = count($orderDetails);

        $url = 'orders/ajaxaction';
        $action = 'custstatuschange';
        $field = 'status';

        if(!empty($orderDetails)) {
            foreach($orderDetails as $key => $value) {
                $deliveryTime = $value['delivery_time'];

                $activestatusChange = $value['id'].',"0"'.',"'.$field.'"'.',"'.$url.'"'.',"'.$action.'"';
                $deActiveStatusChange = $value['id'].',"1"'.',"'.$field.'"'.',"'.$url.'"'.',"'.$action.'"';
                $field = 'status';
                $Response['data'][$key]['Id']                   = $key+1;
                $Response['data'][$key]['Date & Time']          = date('Y-m-d',strtotime($value['created']));
                $Response['data'][$key]['Order Information']    = "<a style='color:#F26822;font-weight:bold' href='".REST_BASE_URL."orders/view/".$value['id']."' >".$value['order_number']."</a>".'<br>'.$value['address'].'<br><br>'.$value['restaurant']['contact_address'];
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

    /*Order Change Status Functionality*/
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

    /*Order Tracking Functionality*/

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

        $this->set(compact('acceptedOrder'));
    }

}