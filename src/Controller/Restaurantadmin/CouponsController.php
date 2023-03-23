<?php
namespace App\Controller\Restaurantadmin;

use Cake\Event\Event;
use Cake\I18n\Time;
use App\Controller\AppController;

class CouponsController extends AppController{

    public function initialize(){
        parent::initialize();
        $this->viewBuilder()->setLayout('restaurant');  
        $this->loadModel('Coupons');   
        $this->loadModel('Restaurants');   
        $this->loadComponent('Common');
        $this->loadComponent('Flash');   
    }
    public function beforeFilter(Event $event)
    {
        // Before Login , these are the function we can access
        $this->Auth->allow([
            'login'
        ]);
    }  
  #------------------------------------------------------------------------------------------
  /*Coupons Management*/
    public function index($process = null){

        $user = $this->Auth->user();
        $restDetails = $this->Restaurants->find('all', [
            'fields' => [
                'id'
            ],
            'conditions' => [
                'user_id' => $user['id']
            ]
        ])->hydrate(false)->first();
        $resId  = $restDetails['id'];

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
                'Coupons.resid' => $resId 
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
        //pr($couponsList); die();
        $this->set(compact('couponsList'));

        if($process == 'Coupons' ){
            $value = array($couponsList);
            return $value;
        }
    }  
  #------------------------------------------------------------------------------------------
   public function add(){  
        $user = $this->Auth->user();
        $resList = $this->Restaurants->find('all', [
                'fields' => [
                    'id',
                    'restaurant_name'
                ],
                'conditions' => [
                    'Restaurants.id IS NOT NULL',
                    'Restaurants.user_id' => $user['id'],
                    'Restaurants.delete_status' => 'N',
                    'Restaurants.status' => 1
                ]
            ])->hydrate(false)->first();
        $resId  = $resList['id'];
        $this->set('resList', $resList);

        if($this->request->is(['post'])){
            $couponExist = $this->Coupons->find('all', [
                'conditions' => [
                    'Coupons.resid' => $resId,
                    'Coupons.coupon_code' => trim($this->request->getData('coupon_code'))
                ]
            ])->first();
            
            if (empty($couponExist)) {
                $postData['resid'] = $this->request->getData('resid');
                $postData['coupon_code'] = $this->request->getData('coupon_code');
                $postData['coupon_type'] = $this->request->getData('coupon_type');
                $postData['coupon_offer'] = $this->request->getData('coupon_offer');
                $postData['eligible_points'] = $this->request->getData('eligible_points');
                $coupon  = $this->Coupons->newEntity($postData);
                if ($this->Coupons->save($coupon)) {
                    $this->Flash->success(_('Coupon details Added successfully'));
                    return $this->redirect(REST_BASE_URL.'coupons/index');
                }
            } else {
                $this->Flash->error(__("Coupon Code Already Exist"));
            }
        }
   }   
   #-----------------------------------------------------------------------------------------
   public function edit($id = null){  
        $user = $this->Auth->user();
        $resList = $this->Restaurants->find('all', [
                'fields' => [
                    'id',
                    'restaurant_name'
                ],
                'conditions' => [
                    'Restaurants.id IS NOT NULL',
                    'Restaurants.user_id' => $user['id'],
                    'Restaurants.delete_status' => 'N',
                    'Restaurants.status' => 1
                ]
            ])->first();
        $resId  = $resList['id'];
        $this->set('resList', $resList);
        if($this->request->is(['post','put'])){
            $couponExist = $this->Coupons->find('all', [
                'conditions' => [
                    'Coupons.id' => $id,
                    'Coupons.resid' => trim($this->request->getData('resid')),
                    'Coupons.coupon_code' => trim($this->request->getData('coupon_code'))
                ]
            ])->first();
            if (empty($couponExist)) {
                $coupon  = $this->Coupons->newEntity();
                $coupon  = $this->Coupons->patchEntity($coupon,$this->request->getData());          

                if(!empty($this->request->getData('editid'))) {
                    $coupon->id  = $this->request->getData('editid');
                }        
                
                if ($this->Coupons->save($coupon)) {
                    $this->Flash->success(_('Coupon details updated successfully'));
                    return $this->redirect(REST_BASE_URL.'coupons/index');
                }
            } else {
                $this->Flash->error(__("Coupon Code Already Exist"));
            }
        }else {

            $couponsList  =  [];
            if(!empty($id)){
                $couponsList = $this->Coupons->find('all', [
                    'conditions' => [
                        'Coupons.id' => $id,
                        'Coupons.resid' => $resId
                    ]
                ])->first(); 
                if(!empty($couponsList)) {
                    $this->set(compact('couponsList','id'));
                } else {
                    return $this->redirect(REST_BASE_URL.'coupons/index');
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
    /*Coupon Delete Coupon*/
    public function deleteCoupon($id = null){

        if($this->request->is('ajax')){
            if($this->request->getData('action') == 'Coupons' 
                && $this->request->getData('id') != ''){

                $id     = $this->request->getData('id');
                $entity = $this->Coupons->get($id);
                $result = $this->Coupons->delete($entity);
               
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