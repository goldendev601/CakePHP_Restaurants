<?php
namespace App\Controller\Hangryadmn;

use Cake\Event\Event;
use Cake\I18n\Time;
use App\Controller\AppController;

class CouponsController extends AppController{

    public function initialize(){
        parent::initialize();
        $this->viewBuilder()->setLayout('backend');  
        $this->loadModel('Coupons');   
        $this->loadModel('Restaurants');   
        $this->loadComponent('Common');   
    }  
  #-------------------------------------------------------------------------------------------
  /*Coupons Management*/
    public function index($process = null){
        $couponsList = $this->Coupons->find('all', [
            'fields' => [
                'Coupons.id',              
                'Coupons.coupon_code',
                'Coupons.coupon_type',
                'Coupons.coupon_offer',
                'Coupons.eligible_points',                
                'Coupons.status',
                'Coupons.created'                              
            ],
            'conditions' => [
                'Coupons.id IS NOT NULL',
                'Coupons.delete_status' => 'N'
            ],
            'contain' => [
                'Restaurants' => [
                    'fields' => [
                        'Restaurants.id',
                        'Restaurants.restaurant_name'
                    ],
                    'conditions' => [
                        'Restaurants.delete_status' => 'N'
                    ]
                ]
            ]
        ])->hydrate(false)->toArray();
        $this->set(compact('couponsList'));

        if($process == 'Coupons' ){
            $value = array($couponsList);
            return $value;
        }
    }
  #------------------------------------------------------------------------------------------
    /*Coupon Add Section*/
   public function add(){  

        $resList = $this->Restaurants->find('list', [
                'keyField' => 'id',
                'valueField' => 'restaurant_name',
                'conditions' => [
                    'Restaurants.id IS NOT NULL',
                    'Restaurants.delete_status' => 'N',
                    'Restaurants.status' => 1
                ]
            ])->hydrate(false)->toArray();
        
        $this->set('resList', $resList);
        if($this->request->is(['post'])){
            $couponExist = $this->Coupons->find('all', [
                'conditions' => [
                    'Coupons.resid' => trim($this->request->getData('resid')),
                    'Coupons.coupon_code' => trim($this->request->getData('coupon_code'))
                ]
            ])->hydrate(false)->first();
            
            if (empty($couponExist)) {
                $coupon  = $this->Coupons->newEntity($this->request->getData());
                if ($this->Coupons->save($coupon)) {
                    $this->Flash->success(_('Coupon details Added successfully'));
                    return $this->redirect(ADMIN_BASE_URL.'coupons/index');
                }
            } else {
                $this->Flash->error(__("Coupon Code Already Exist"));
            }
        }
   }   
   #-----------------------------------------------------------------------------------------
    /*Coupon Edit Section*/
   public function edit($id = null){  

        $resList = $this->Restaurants->find('list', [
                'keyField' => 'id',
                'valueField' => 'restaurant_name',
                'conditions' => [
                    'Restaurants.id IS NOT NULL',
                    'Restaurants.delete_status' => 'N',
                    'Restaurants.status' => 1
                ]
            ])->hydrate(false)->toArray();
        $this->set('resList', $resList);
        if($this->request->is(['post','put'])){

            $couponExist = $this->Coupons->find('all', [
                'conditions' => [
                    'Coupons.id !=' => $id,
                    'Coupons.resid' => trim($this->request->getData('resid')),
                    'Coupons.coupon_code' => trim($this->request->getData('coupon_code'))
                ]
            ])->hydrate(false)->first();
            if (empty($couponExist)) {
                $coupon  = $this->Coupons->newEntity();
                $coupon  = $this->Coupons->patchEntity($coupon,$this->request->getData());          

                if(!empty($this->request->getData('editid'))) {
                    $coupon->id  = $this->request->getData('editid');
                }        
                
                if ($this->Coupons->save($coupon)) {
                    $this->Flash->success(_('Coupon details updated successfully'));
                    return $this->redirect(ADMIN_BASE_URL.'coupons/index');
                }
            } else {
                $this->Flash->error(__("Coupon Code Already Exist"));
            }
        }else {

            $couponsList  =  [];
            if(!empty($id)){
                $couponsList = $this->Coupons->get($id);               
                if(!empty($couponsList)) {
                    $this->set(compact('couponsList','id'));
                } else {
                    return $this->redirect(ADMIN_BASE_URL.'coupons/index');
                }
            } else {
                $this->set(compact('couponsList','id'));
            }            
        }
   }   
  #------------------------------------------------------------------------------------------
    /*Offer Status Change*/
    public function ajaxaction() {

        if($this->request->is('ajax')){
            if($this->request->getData('action') == 'couponStatuschange'){
                $coupon         = $this->Coupons->newEntity();
                $coupon         = $this->Coupons->patchEntity($coupon,$this->request->getData());
                $coupon->id     = $this->request->getData('id');
                $coupon->status = $this->request->getData('changestaus');
                $this->Coupons->save($coupon);

                $this->set('id', $this->request->getData('id'));
                $this->set('action', 'couponStatuschange');
                $this->set('field', $this->request->getData('field'));
                $this->set('status', (($this->request->getData('changestaus') == 0) ? 'deactive' : 'active'));
            }
        }
    }
  #-----------------------------------------------------------------------------------------
    /*Coupon Delete Section*/
    public function deleteCoupon($id = null){

        if($this->request->is('ajax')){
            if($this->request->getData('action') == 'Coupons' 
                && $this->request->getData('id') != ''){

                $coupon         = $this->Coupons->newEntity();
                $coupon         = $this->Coupons->patchEntity($coupon,$this->request->getData());
                $coupon->id     = $this->request->getData('id');
                $coupon->delete_status = 'Y';
                $this->Coupons->save($coupon);
                
                list($couponsList) = $this->index('Coupons');
                if($this->request->is('ajax')) {
                    $action    = 'Coupons';
                    $this->set(compact('action', 'couponsList'));
                    $this->render('ajaxaction');
                }
            }
        }
    }
 #----------------------------------------------------------------------------------- 
}#class end...