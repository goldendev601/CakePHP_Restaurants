<?php
namespace App\Controller\Hangryadmn;

use Cake\Event\Event;
use Cake\I18n\Time;
use App\Controller\AppController;

class VouchersController extends AppController{

    public function initialize(){
        parent::initialize();
        $this->viewBuilder()->setLayout('backend');  
        $this->loadModel('Vouchers');
        $this->loadComponent('Common');   
    }
  
  #-------------------------------------------------------------------------------------------
  /*Vouchers Management*/
    public function index($process = null){
        $vouchersList = $this->Vouchers->find('all', [
            'fields' => [
                'Vouchers.id',              
                'Vouchers.voucher_code',
                'Vouchers.type_offer',
                'Vouchers.offer_mode',
                'Vouchers.free_delivery',
                'Vouchers.offer_value',
                'Vouchers.voucher_from',
                'Vouchers.voucher_to',
                'Vouchers.status'                                
            ],
            'conditions' => [
                'Vouchers.id IS NOT NULL'
            ],
            'order' => [
                'Vouchers.id' => 'DESC'
            ]
        ])->hydrate(false)->toArray();
        $this->set(compact('vouchersList'));

        //echo "<pre>"; print_r($vouchersList); die();
        if($process == 'Vouchers' ){
            $value = array($vouchersList);
            return $value;
        }
    }  
  #-------------------------------------------------------------------------------------------
    /*Checking Name already exist*/    
    public function voucherCheck() { 
        if($this->request->getData('voucher_code') != '') {
            if($this->request->getData('id') != '') {
                $conditions = [
                    'id !=' => $this->request->getData('id'),
                    'voucher_code' => $this->request->getData('voucher_code')
                ];
            }else {
                $conditions = [
                    'voucher_code' => $this->request->getData('voucher_code')
                ];
            }                   
            $voucherCount = $this->Vouchers->find('all', [
                'conditions' => $conditions
            ])->count();

            if($voucherCount == 0) {
                echo '0';
            }else {
                echo '1';
            }
            die();
        }
    }
  #-------------------------------------------------------------------------------------------

    /*Voucher Add and Edit Section*/
   public function addedit($id = null){

          $vouchersList = [];
          if(!empty($id)) {           
                $vouchersList = $this->Vouchers->find('all', [            
                    'conditions' => [
                        'Vouchers.id' => $id               
                    ]
                ])->first();            
          } 
       
        if($this->request->is(['post','put'])){  

            if($this->request->getData('editid') != '') {
                $conditions = [
                    'id !=' => $this->request->getData('editid'),                    
                    'voucher_code' => $this->request->getData('voucher_code')                   
                ];
            }else {
                $conditions = [                    
                    'voucher_code' => $this->request->getData('voucher_code')                   
                ];
            }                   
            $voucherCount = $this->Vouchers->find('all', [
                'conditions' => $conditions
            ])->count();

            if($voucherCount == 0){

                $voucher  = $this->Vouchers->newEntity();
                $voucher  = $this->Vouchers->patchEntity($voucher,$this->request->getData());           
                if($this->request->getData('editid') != '') {
                    $voucher->id  = $this->request->getData('editid');
                }else {
                    $voucher->status = '1';
                }
                if ($this->Vouchers->save($voucher)) {
                    $this->Flash->success(_('Voucher details updated successfully'));
                    return $this->redirect(ADMIN_BASE_URL.'vouchers/index');
                }
            }else{
                 $this->Flash->error(_('Voucher code already exists'));
                 $this->set(compact('vouchersList','id'));   
            }              

        }else {
            
            if(!empty($id)){    
                if(!empty($vouchersList)) {
                    $this->set(compact('vouchersList','id'));
                } else {
                    return $this->redirect(ADMIN_BASE_URL.'vouchers/index');
                }
            } else {
                $this->set(compact('vouchersList','id'));
            }
        }
   }   
  #-------------------------------------------------------------------------------------------
    /*Offer Status Change*/
    public function ajaxaction() {

        if($this->request->is('ajax')){
            if($this->request->getData('action') == 'voucherStatuschange'){
                $voucher         = $this->Vouchers->newEntity();
                $voucher         = $this->Vouchers->patchEntity($voucher,$this->request->getData());
                $voucher->id     = $this->request->getData('id');
                $voucher->status = $this->request->getData('changestaus');
                //pr($voucher); die();
                $this->Vouchers->save($voucher);

                $this->set('id', $this->request->getData('id'));
                $this->set('action', 'voucherStatuschange');
                $this->set('field', $this->request->getData('field'));
                $this->set('status', (($this->request->getData('changestaus') == 0) ? 'deactive' : 'active'));
            }
        }
    }
  #-------------------------------------------------------------------------------------------
    public function deleteVoucher($id = null){

        if($this->request->is('ajax')){
            if($this->request->getData('action') == 'Vouchers' 
                && $this->request->getData('id') != ''){

                $this->Vouchers->deleteAll([
                    'id' => $this->request->getData('id')
                ]);
               
                list($vouchersList) = $this->index('Vouchers');
                if($this->request->is('ajax')) {
                    $action    = 'Vouchers';
                    $this->set(compact('action', 'vouchersList'));
                    $this->render('ajaxaction');
                }
            }
        }
    }
 #------------------------------------------------------------------------------------ 
}#class end...