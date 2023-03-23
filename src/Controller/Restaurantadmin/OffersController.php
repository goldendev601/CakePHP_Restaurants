<?php
/**
 * Created by PhpStorm.
 * User: Sundar.S
 * Date: 29-12-2017
 * Time: 19:37
 */
namespace App\Controller\Restaurantadmin;

use Cake\Event\Event;
use App\Controller\AppController;


class OffersController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->viewBuilder()->setLayout('restaurant');
        $this->loadComponent('Flash');

        $this->loadModel('Users');
        $this->loadModel('Offers');
        $this->loadModel('Restaurants');
    }

    public function beforeFilter(Event $event)
    {
        // Before Login , these are the function we can access
        $this->Auth->allow([
            'login'
        ]);
    }


    /*Offer Management*/
    public function index($process = null) {
        //echo "<pre>";print_r($this->Auth->User('id'));die;
        $resDet = $this->Restaurants->find('all', [
            'conditions' => [
                'user_id' => $this->Auth->User('id')                
            ],
        ])->hydrate(false)->first();
        
         $offerList = $this->Offers->find('all', [
            'conditions' => [
                'id IS NOT NULL',              
                'resid' => $resDet['id']
            ],
        ])->toArray();
       
        $this->set(compact('offerList'));

        //echo "<pre>"; print_r($offerList); die();
        if($process == 'Offers' ){
            $value = array($offerList);
            return $value;
        }
    }
    /*Offer Add Functionality*/
    public function add(){
      //echo "<pre>";print_r($this->request->getData());die;

        $resDet = $this->Restaurants->find('all', [
            'conditions' => [
                'user_id' => $this->Auth->User('id')                
            ],
        ])->hydrate(false)->first();

        if($this->request->is(['post','put'])){
           
            $postData = $this->request->getData(); 
            $offerdetail=[];
            $offerdetail['status'] = '1';
            $offerdetail['resid'] =  $resDet['id'];
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
            
            $offerdetail['offer_from'] =  !empty($postData['offer_from']) ? $postData['offer_from'] : '' ;
            $offerdetail['offer_to'] =  !empty($postData['offer_to']) ? $postData['offer_to'] : '' ;

            $offerEnty  = $this->Offers->newEntity($offerdetail);  
            $offerSave  = $this->Offers->save($offerEnty); 
            
            if ($offerSave) {
                $this->Flash->success(_('Offer details added successfully'));
                return $this->redirect(REST_BASE_URL.'offers/index');
            }
        }       
   }   
  #-------------------------------------------------------------------------------------------
    /*Offer Edit Functionality*/
   public function edit($id = null){  
    //pr($this->request->getData());die;
    
        $offerList = [];
        if(!empty($id)) {            
            $offerList = $this->Offers->find('all', [            
                'conditions' => [
                    'Offers.id' => $id               
                ]
            ])->first();            
        } 
        
        $resDet = $this->Restaurants->find('all', [
            'conditions' => [
                'user_id' => $this->Auth->User('id')                
            ],
        ])->hydrate(false)->first();    

        if($this->request->is(['post','put'])) {
           $postData = $this->request->getData(); 
           $first_user = $this->request->getData('first_user');
           $minDel = $this->request->getData('min_delivery');

            if (!empty($offerList)) { 
                
                $postData = $this->request->getData(); 
                $offerdetail=[];
                $offerdetail['id'] =  !empty($postData['editid']) ? $postData['editid'] : '' ;
                $offerdetail['resid'] =  $resDet['id'];
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
                
                $offerdetail['offer_from'] =  !empty($postData['offer_from']) ? $postData['offer_from'] : '' ;
                $offerdetail['offer_to'] =  !empty($postData['offer_to']) ? $postData['offer_to'] : '' ;

                $offerEnty  = $this->Offers->newEntity($offerdetail);  
                $offerSave  = $this->Offers->save($offerEnty);   

                if ($offerSave) {
                    $this->Flash->success(__("Offers details updated successfully"));
                    return $this->redirect(REST_BASE_URL.'offers/index');                           
                }                                   
            }                
        } 

        if(!empty($id)){           
            if(!empty($offerList)){              
               $this->set(compact("offerList",'id'));
            }else{
                $this->Flash->error(__("Invalid Offers details !"));
                return $this->redirect(REST_BASE_URL.'offers/index');   
            } 
        }      
   }    
 #-------------------------------------------------------------------------------
    /*Offer Status Change*/
    public function ajaxaction() {

       //echo "<pre>"; print_r($this->request->getData()); die();

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
    /*Offer Delete Section*/
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
        //die();
    }
}