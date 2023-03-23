<?php
namespace App\Controller\Hangryadmn;

use Cake\Event\Event;
use Cake\I18n\Time;
use App\Controller\AppController;

class BonuspointsController extends AppController{

    public function initialize(){
        parent::initialize();
        $this->viewBuilder()->setLayout('backend');     
        $this->loadModel('Bonuspoints');
        $this->loadModel('Restaurants');
    }
  #------------------------------------------------------------------------------------------------------
    /*Bonuspoints Management*/
    public function index(){

        $bonusList = $this->Bonuspoints->find('all', [
            'conditions' => [
                'Bonuspoints.id IS NOT NULL'
            ],
            'contain' => [
                'Restaurants' => [
                    'fields'=>[
                        'Restaurants.id',
                        'Restaurants.restaurant_name',
                        'Restaurants.status'
                    ],
                    'conditions' => [
                        'Restaurants.delete_status' => 'N',
                    ]
                ]
            ],
            'group' =>[
                'Restaurants.id'
            ]
        ])->hydrate(false)->toArray();
        $this->set(compact('bonusList'));
    }
  #---------------------------------------------------------------------------------------------
    /*Bonus Add Section*/
   public function add(){

        if($this->request->is(['post'])){
            $bonus = $this->request->getData('data')['Bonus'];
            if(!empty($bonus)){
                $this->Bonuspoints->deleteAll([
                    'restaurant_id' => $this->request->getData('restaurant_id')
                ]);
                foreach( $bonus as $key => $value) {
                    $distEntity                = $this->Bonuspoints->newEntity();
                    $points['no_oforder']      = $value['no_oforder'];
                    $points['no_ofpoint']      = $value['no_ofpoint'];
                    $points['restaurant_id'] = $this->request->getData('restaurant_id');
                    $distUdtPatch              = $this->Bonuspoints->patchEntity($distEntity, $points);
                    $userSave                  = $this->Bonuspoints->save($distUdtPatch);
                }
                $this->Flash->success(_('Bonus added Successfully'));
                $this->redirect(ADMIN_BASE_URL.'bonuspoints/');
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
       $this->set(compact('restaurantLists'));
   }   
  #---------------------------------------------------------------------------
    /*Bonus Edit Section*/
    public function edit($id = null){

        if($this->request->is(['post','put'])){
            $bonus = $this->request->getData('data')['Bonus'];

            if(!empty($bonus)){
                $this->Bonuspoints->deleteAll([
                    'restaurant_id' => $this->request->getData('restaurant_id')
                ]);

                foreach( $bonus as $key => $value) {
                    $distEntity                = $this->Bonuspoints->newEntity();
                    $points['no_oforder']      = $value['no_oforder'];
                    $points['no_ofpoint']      = $value['no_ofpoint'];
                    $points['restaurant_id'] = $this->request->getData('restaurant_id');
                    $distUdtPatch              = $this->Bonuspoints->patchEntity($distEntity, $points);
                    $userSave                  = $this->Bonuspoints->save($distUdtPatch);
                }
                $this->Flash->success(_('Bonus added Successfully'));
                $this->redirect(ADMIN_BASE_URL.'bonuspoints/');
            }
        }

        $bonusList = $this->Bonuspoints->find('all', [
            'conditions' => [
                'Bonuspoints.restaurant_id' => $id
            ],
            'contain' => [
                'Restaurants' => [
                    'fields'=>[
                        'Restaurants.id',
                        'Restaurants.restaurant_name',
                        'Restaurants.status'
                    ],
                    'conditions' => [
                        'Restaurants.delete_status' => 'N',
                    ]
                ]
            ]
        ])->hydrate(false)->toArray();

        $restaurantLists = $this->Restaurants->find('list', [
            'keyField' => 'id',
            'valueField' => 'restaurant_name',
            'conditions' => [
                'id IS NOT NULL',
                'status' => '1'
            ]
        ])->hydrate(false)->toArray();
        $this->set(compact('bonusList','restaurantLists'));
  }
  #---------------------------------------------------------------------------
}#class end...