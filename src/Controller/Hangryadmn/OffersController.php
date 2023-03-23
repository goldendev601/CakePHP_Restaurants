<?php
namespace App\Controller\Hangryadmn;

use Cake\Event\Event;
use Cake\I18n\Time;
use App\Controller\AppController;

class OffersController extends AppController{

    public function initialize(){
        parent::initialize();
        $this->viewBuilder()->setLayout('backend');  
        $this->loadModel('Offers');   
        $this->loadModel('Restaurants');
        $this->loadComponent('Common');
    }
  
  #-------------------------------------------------------------------------------------------
  /*Offers Management*/
    public function index($process = null){
        $offerList = $this->Offers->find('all', [
            'conditions' => [
                'Offers.id IS NOT NULL'                
            ],
            'order' => [
                'id' => 'DESC'
            ]
        ])->hydrate(false)->toArray();

        foreach($offerList as $key => $val){

           $RestDet = $this->Restaurants->find('all', [
              'fields'=>[
                'restaurant_name'
              ],
              'conditions' => [
                  'id' => $val['resid']
              ],
          ])->hydrate(false)->first();
          
           $offerList[$key]['restName'] = $RestDet['restaurant_name'];
        }

        $this->set(compact('offerList'));

        
        if($process == 'Offers' ){
            $value = array($offerList);
            return $value;
        }
    }  
  #-------------------------------------------------------------------------------------------
    /*Offer Add Section*/
   public function add(){

        $restaurantLists = $this->Restaurants->find('list', [
           'keyField' => 'id',
           'valueField' => 'restaurant_name',
           'conditions' => [
               'id IS NOT NULL',
               'status' => '1'
           ]
       ])->hydrate(false)->toArray();
       
        if($this->request->is(['post','put'])){

            $postData = $this->request->getData();

            $offerdetail=[];
            $offerdetail['status'] = '1';
            $offerdetail['resid'] =  !empty($postData['restaurant_id']) ? $postData['restaurant_id'] : '' ;
            $offerdetail['first_user'] =  !empty($postData['first_user']) ? 'Y' : 'N' ;
            $offerdetail['normal'] =  !empty($postData['min_delivery']) ? 'Y' : 'N' ;
            if(!empty($postData['first_user'])){
              $offerdetail['free_percentage'] =  !empty($postData['free_percentage']) ? $postData['free_percentage'] : '' ;
              $offerdetail['free_price'] =  !empty($postData['free_price']) ? $postData['free_price'] : '' ;
            }
            if(!empty($postData['min_delivery'])){
              $offerdetail['normal_price'] =  !empty($postData['normal_price']) ? $postData['normal_price'] : '' ;
              $offerdetail['normal_percentage'] =  !empty($postData['normal_percentage']) ? $postData['normal_percentage'] : '' ;
            }            
            
            $offerdetail['offer_from'] =  $postData['offer_from'];
            $offerdetail['offer_to'] =  $postData['offer_to'];

            $offerEnty  = $this->Offers->newEntity($offerdetail);  
            $offerSave  = $this->Offers->save($offerEnty);   

            if ($offerSave) {
                $this->Flash->success(_('Offer details updated successfully'));
                return $this->redirect(ADMIN_BASE_URL.'offers/index');
            }
        }  
        $this->set(compact('restaurantLists'));     
   }   
  #-------------------------------------------------------------------------------------------
    /*Offer Edit Section*/
   public function edit($id = null){  
       
        $offerList = [];
        if(!empty($id)) {
            //$offerList = $this->Offers->get($id);
            $offerList = $this->Offers->find('all', [            
                'conditions' => [
                    'Offers.id' => $id               
                ]
            ])->first();            
        } 
    

        if($this->request->is(['post','put'])) {

           $postData = $this->request->getData(); 
            $offerdetail=[];
            $offerdetail['id'] =  !empty($postData['editid']) ? $postData['editid'] : '' ;
            $offerdetail['resid'] =  !empty($postData['restaurant_id']) ? $postData['restaurant_id'] : '' ;
            $offerdetail['first_user'] =  !empty($postData['first_user']) ? 'Y' : 'N' ;
            $offerdetail['normal'] =  !empty($postData['min_delivery']) ? 'Y' : 'N' ;
            if(!empty($postData['first_user'])){
              $offerdetail['free_percentage'] =  !empty($postData['free_percentage']) ? $postData['free_percentage'] : '' ;
              $offerdetail['free_price'] =  !empty($postData['free_price']) ? $postData['free_price'] : '' ;
            }
            if(!empty($postData['min_delivery'])){
              $offerdetail['normal_price'] =  !empty($postData['normal_price']) ? $postData['normal_price'] : '' ;
              $offerdetail['normal_percentage'] =  !empty($postData['normal_percentage']) ? $postData['normal_percentage'] : '' ;
            }

            $offerdetail['offer_from'] =  $postData['offer_from'];
            $offerdetail['offer_to'] =  $postData['offer_to'];

            $offerEnty  = $this->Offers->newEntity($offerdetail);  
            $offerSave  = $this->Offers->save($offerEnty);   

            if ($offerSave) {
                $this->Flash->success(_('Offer details updated successfully'));
                return $this->redirect(ADMIN_BASE_URL.'offers/index');
            }               
        } 

        if(!empty($id)){           
            if(!empty($offerList)){              
               $this->set(compact("offerList",'id'));
            }else{
                $this->Flash->error(__("Invalid Offers details !"));
                return $this->redirect(ADMIN_BASE_URL.'offers/index');   
            } 
        }

       $restaurantLists = $this->Restaurants->find('list', [
           'keyField' => 'id',
           'valueField' => 'restaurant_name',
           'conditions' => [
               'id IS NOT NULL',
               'status' => '1'
           ]
       ])->hydrate(false)->toArray();

       $this->set(compact('restaurantLists','offerList','id'));
   }    
 #-------------------------------------------------------------------------------
    /*Offer Status Change*/
    public function ajaxaction() {

       // echo "<pre>"; print_r($this->request->getData()); die();

        if($this->request->is('ajax')){
            if($this->request->getData('action') == 'offerStatuschange'){
                $offer         = $this->Offers->newEntity();
                $offer         = $this->Offers->patchEntity($offer,$this->request->getData());
                $offer->id     = $this->request->getData('id');
                $offer->status = $this->request->getData('changestaus');
                $this->Offers->save($offer);

                $this->set('id', $this->request->getData('id'));
                $this->set('action', 'offerStatuschange');
                $this->set('field', $this->request->getData('field'));
                $this->set('status', (($this->request->getData('changestaus') == 0) ? 'deactive' : 'active'));
            }
        }
    }
  #-------------------------------------------------------------------------------------------
    public function deleteOffer($id = null){

        if($this->request->is('ajax')){
            if($this->request->getData('action') == 'Offers' 
                && $this->request->getData('id') != ''){

                $this->Offers->deleteAll([
                    'id' => $this->request->getData('id')
                ]);
               
                list($offerList) = $this->index('Offers');
                if($this->request->is('ajax')) {
                    $action    = 'Offers';
                    $this->set(compact('action', 'offerList'));
                    $this->render('ajaxaction');
                }
            }
        }
    }
 #------------------------------------------------------------------------------------ 
}#class end...