<?php
namespace App\Controller\Restaurantadmin;

use Cake\Event\Event;
use Cake\I18n\Time;
use App\Controller\AppController;

class VouchersController extends AppController{

    public function initialize(){
        parent::initialize();
        $this->viewBuilder()->setLayout('restaurant');  
        $this->loadModel('Vouchers');   
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
  /*Vouchers Management*/
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

        $vouchersList = $this->Vouchers->find('all', [
            'fields' => [
                'Vouchers.id',              
                'Vouchers.voucher_code',
                'Vouchers.type_offer',
                'Vouchers.offer_mode',
                'Vouchers.offer_value',
                'Vouchers.voucher_from',                
                'Vouchers.voucher_to',                
                'Vouchers.status',
                'Vouchers.created'                              
            ],
            'conditions' => [
                'Vouchers.id IS NOT NULL',
                'Vouchers.resid' => $resId
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
        $this->set(compact('vouchersList'));

        if($process == 'Vouchers' ){
            $value = array($vouchersList);
            return $value;
        }
    }  
  #------------------------------------------------------------------------------------------
    /*Voucher Add Section*/
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
            $voucherExist = $this->Vouchers->find('all', [
                'conditions' => [
                    'Vouchers.resid' => $resId,
                    'Vouchers.voucher_code' => trim($this->request->getData('voucher_code'))
                ]
            ])->first();
            
            if (empty($voucherExist)) {

                $postData['resid'] = $this->request->getData('resid');
                $postData['voucher_code'] = $this->request->getData('voucher_code');
                $postData['type_offer'] = $this->request->getData('type_offer');
                $postData['offer_mode'] = $this->request->getData('offer_mode');
                $postData['offer_value'] = $this->request->getData('offer_value');
                $postData['voucher_from'] = $this->request->getData('voucher_from');
                $postData['voucher_to'] = $this->request->getData('voucher_to');
                $voucher  = $this->Vouchers->newEntity($postData);
                //pr($voucher); die();
                if ($this->Vouchers->save($voucher)) {
                    $this->Flash->success(_('Voucher details Added successfully'));
                    return $this->redirect(REST_BASE_URL.'vouchers/index');
                }
            } else {
                $this->Flash->error(__("voucher Code Already Exist"));
            }
        }
   }   
   #-----------------------------------------------------------------------------------------
    /*Voucher Edit Section*/
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
            $voucherExist = $this->Vouchers->find('all', [
                'conditions' => [
                    'id !=' => $id,
                    'resid' => trim($this->request->getData('resid')),
                    'voucher_code' => trim($this->request->getData('voucher_code'))
                ]
            ])->first();
            if (empty($voucherExist)) {
                $voucher  = $this->Vouchers->newEntity();
                $voucher  = $this->Vouchers->patchEntity($voucher,$this->request->getData());          

                if(!empty($this->request->getData('editid'))) {
                    $voucher->id  = $this->request->getData('editid');
                }        
                
                if ($this->Vouchers->save($voucher)) {
                    $this->Flash->success(_('Voucher details updated successfully'));
                    return $this->redirect(REST_BASE_URL.'vouchers/index');
                }
            } else {
                $this->Flash->error(__("Voucher Code Already Exist"));
            }
        }else {

            $vouchersList  =  [];
            if(!empty($id)){
                $vouchersList = $this->Vouchers->find('all', [
                    'conditions' => [
                        'Vouchers.id' => $id,
                        'Vouchers.resid' => $resId
                    ]
                ])->first(); 
                if(!empty($vouchersList)) {
                    $this->set(compact('vouchersList','id'));
                } else {
                    return $this->redirect(REST_BASE_URL.'vouchers/index');
                }
            } else {
                $this->set(compact('vouchersList','id'));
            }            
        }
   }   
  #------------------------------------------------------------------------------------------
    /*Offer Status Change*/
    public function ajaxaction() {

        if($this->request->is('ajax')){
            if($this->request->getData('action') == 'voucherStatuschange'){
                $voucher         = $this->Vouchers->newEntity();
                $voucher         = $this->Vouchers->patchEntity($voucher,$this->request->getData());
                $voucher->id     = $this->request->getData('id');
                $voucher->status = $this->request->getData('changestaus');
                $this->Vouchers->save($voucher);

                $this->set('id', $this->request->getData('id'));
                $this->set('action', 'voucherStatuschange');
                $this->set('field', $this->request->getData('field'));
                $this->set('status', (($this->request->getData('changestaus') == 0) ? 'deactive' : 'active'));
            }
        }
    }
  #-----------------------------------------------------------------------------------------
    /*Delete Voucher*/
    public function deleteVoucher($id = null){

        if($this->request->is('ajax')){
            if($this->request->getData('action') == 'Vouchers' 
                && $this->request->getData('id') != ''){
                $voucher         = $this->Vouchers->newEntity();
                $voucher         = $this->Vouchers->patchEntity($voucher,$this->request->getData());
                $voucher->id     = $this->request->getData('id');
                $this->Vouchers->save($voucher);
               
                list($vouchersList) = $this->index('Vouchers');
                if($this->request->is('ajax')) {
                    $action    = 'Vouchers';
                    $this->set(compact('action', 'vouchersList'));
                    $this->render('ajaxaction');
                }
            }
        }

        
    }
 #----------------------------------------------------------------------------------- 
}#class end...