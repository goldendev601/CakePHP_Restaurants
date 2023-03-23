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

class DispatchController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->viewBuilder()->setLayout('restaurant');

        $this->loadModel('Users');
        $this->loadModel('Restaurants');
        $this->loadModel('Sitesettings');
        $this->loadModel('Timezones');
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
//----------------------------------------------------------------------------------------------------------------------
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
                'AND' =>[
                    ['Orders.status !=' => 'Pending'],
                    ['Orders.status !=' => 'Delivered'],
                    ['Orders.status !=' => 'Failed'],
                ],
                'Orders.restaurant_id' => $restDetails['id'],


            ];
        }else {
            $conditions = [
                'AND' =>[
                    ['Orders.status !=' => 'Pending'],
                    ['Orders.status !=' => 'Delivered'],
                    ['Orders.status !=' => 'Failed'],
                ],
                'Orders.restaurant_id' => $restDetails['id'],
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
                'Users'
            ],
            'order' => $orderBy,
            'limit' => $limit,
            'page' => $page
        ])->hydrate(false)->toArray();

        $orderDetailsCount = $this->Orders->find('all', [
            'conditions' => $conditions,
            'contain' => [
                'Restaurants',
                'Users'
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
                $Response['data'][$key]['Driver Name']          = "sundar";

                $Response['data'][$key]['Status']            = "<select id='currentStatus_".$value['id']."' onchange='changeOrderStatus(".$value['id'].");'><option value=''>Select</option><option value='Failed'>Reject</option><option value='Delivered'>Delivered</option></select> ";


                $Response['data'][$key]['Action']           = '<a class="drive_icon" href="javascript:void(0)"><i class="fa fa-car"></i></a> <a class="drive_icon" href="javascript:void(0)"><i class="fa fa-search"></i></a>';
            }
        }else {
            $Response['data'] = '';
        }
        echo json_encode($Response);die();
    }
//----------------------------------------------------------------------------------
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
 //----------------------------------------------------------------------------------
}