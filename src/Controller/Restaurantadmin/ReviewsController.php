<?php
namespace App\Controller\Restaurantadmin;

use Cake\Datasource\ConnectionManager;
use Cake\Event\Event;
use Cake\I18n\Time;
use App\Controller\AppController;

class ReviewsController extends AppController{

    public function initialize(){
        parent::initialize();
        $this->viewBuilder()->setLayout('backend');  
        $this->loadModel('Categories'); 
        $this->loadModel('Restaurants'); 
        $this->loadModel('Reviews'); 
        $this->loadModel('Users'); 
    }  
  #-------------------------------------------------------------------------------------------
  /*Reviews Management*/
    public function index($process = null){

        $resDet = $this->Restaurants->find('all', [
            'conditions' => [
                'user_id' => $this->Auth->User('id')                
            ],
        ])->hydrate(false)->first();

        $allReviews = $this->Reviews->find('all', [
            'conditions' => [
                'status' => 1,
                'restaurant_id' => $resDet['id']
            ]
            
        ])->hydrate(false)->toArray();   

         foreach($allReviews as $key => $val){

           $RestDet = $this->Users->find('all', [
              'conditions' => [
                  'id' => $val['customer_id']
              ]
          ])->hydrate(false)->first();
          
           $allReviews[$key]['customer_name'] = $RestDet['first_name'].' '.$RestDet['first_name'];
        }
        //echo "<pre>"; print_r($allReviews); die();

      $this->set(compact('allReviews'));

        if($process == 'Reviews' ){
            $value = array($allReviews);
            return $value;
        }
    }  
 #------------------------------------------------------------------------------------------
    /*Review Status Change*/
    public function ajaxaction() {

        if($this->request->is('ajax')){
            if($this->request->getData('action') == 'reviewStatusChange'){
                $review         = $this->Reviews->newEntity();
                $review         = $this->Reviews->patchEntity($review,$this->request->getData());
                $review->id     = $this->request->getData('id');
                $review->status = $this->request->getData('changestaus');
                $this->Reviews->save($review);

                $this->set('id', $this->request->getData('id'));
                $this->set('action', 'reviews');
                $this->set('field', $this->request->getData('field'));
                $this->set('status', (($this->request->getData('changestaus') == 0) ? 'deactive' : 'active'));
            }
        }
    }
 #------------------------------------------------------------------------------------------
    /*Review Delete*/
    public function deleteReview($id = null){

        if($this->request->is('ajax')){
            if($this->request->getData('action') == 'reviews'
                && $this->request->getData('id') != ''){

                 $this->Offers->deleteAll([
                  'id' => $this->request->getData('id')
                ]);

                list($allReviews) = $this->index('reviews');
                if($this->request->is('ajax')) {
                    $action    = 'reviews';
                    $this->set(compact('action', 'allReviews'));
                    $this->render('ajaxaction');
                }
            }
        }
    }
 #------------------------------------------------------------------------------------------
}#class end...