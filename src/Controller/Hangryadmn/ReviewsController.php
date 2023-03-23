<?php
namespace App\Controller\Hangryadmn;

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
    }  
  #-------------------------------------------------------------------------------------------
  /*Reviews Management*/
    public function index($process = null){

        $allReviews = $this->Reviews->find('all')->hydrate(false)->toArray();       
       //echo "<pre>"; print_r($allReviews); die();

       foreach($allReviews as $key => $val){

           $RestDet = $this->Restaurants->find('all', [
              'conditions' => [
                  'id' => $val['restaurant_id']
              ]
          ])->hydrate(false)->first();
          
           $allReviews[$key]['restaurant_name'] = $RestDet['restaurant_name'];
        }
         //echo "<pre>"; print_r($allReviews); die();

      $this->set(compact('allReviews'));

        if($process == 'reviews' ){
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
                $this->set('action', 'reviewStatusChange');
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

                 $this->Reviews->deleteAll([
                  'id' => $this->request->getData('id')
                ]);

                list($allReviews) = $this->index('reviews');
                if($this->request->is('ajax')) {
                    $action    = 'reviews';
                    $this->set(compact('action', 'allReviews','id'));
                    $this->render('ajaxaction');
                }
            }
        }
    }
 #------------------------------------------------------------------------------------------
}#class end...