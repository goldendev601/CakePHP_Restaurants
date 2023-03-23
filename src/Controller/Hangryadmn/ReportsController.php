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

class ReportsController extends AppController
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
        $this->loadComponent('Googlemap');
    }

    public function index() {

    }

    public function orderview($id = null) {

    }


    public function report() {

    }

    /*Get Order Details*/
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
                    "Restaurants.restaurant_name LIKE" => "%".$this->request->getData('search')['value']."%",
                ],
                [
                    'OR' => [
                        ['Orders.status' => 'Delivered'],
                        ['Orders.status' => 'Failed']
                    ]
                ]


            ];
        }else {
            $conditions = [
                'Orders.id IS NOT NULL',
                'OR' => [
                    ['Orders.status' => 'Delivered'],
                    ['Orders.status' => 'Failed'],
                ]
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
            ],
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


                $Response['data'][$key]['Order Date']        = date('Y-m-d h:i A', strtotime($value['created']));
            }
        }else {
            $Response['data'] = '';
        }
        echo json_encode($Response);die();
    }





}