<?php
namespace App\Controller\RestaurantAdmin;

use Cake\Event\Event;
use Cake\I18n\Time;
use App\Controller\AppController;

class BonuspointsController extends AppController{

    public function initialize(){
        parent::initialize();
        $this->viewBuilder()->setLayout('restaurant');
        $this->loadModel('Bonuspoints');
        $this->loadModel('Restaurants');
    }
  #------------------------------------------------------------------------------------------------------
    /*Bonuspoints Management*/
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

        $bonusList = $this->Bonuspoints->find('all', [
            'conditions' => [
                'Bonuspoints.id IS NOT NULL',
                'Bonuspoints.delete_status' => 'N',
                'Bonuspoints.restaurant_id' => $resId
            ]
        ])->hydrate(false)->toArray();
        $this->set(compact('bonusList'));

        if($process == 'Bonuspoint' ){
            $value = array($bonusList);
            return $value;
        }
    }
  #---------------------------------------------------------------------------------------------
    /*Bonuspoints Add*/
   public function add(){

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

       $bonusList = $this->Bonuspoints->find('all', [
           'conditions' => [
               'Bonuspoints.id IS NOT NULL',
               'Bonuspoints.delete_status' => 'N',
               'Bonuspoints.restaurant_id' => $resId
           ]
       ])->hydrate(false)->toArray();

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
                $this->redirect(REST_BASE_URL.'bonuspoints/');
            }
        }

       $this->set(compact('resId','bonusList'));
   }   
  #----------------------------------------------------------------------------------
    /*Bonuspoints Ajaxaction*/
    public function ajaxaction(){

        if ($this->request->getData('action') == 'bonusStatus') {
                $bonus = $this->Bonuspoints->newEntity();
                $bonus = $this->Bonuspoints->patchEntity($bonus, $this->request->getData());
                $bonus->id = $this->request->getData('id');
                $bonus->status = $this->request->getData('changestaus');
                $this->Bonuspoints->save($bonus);

                $this->set('id', $this->request->getData('id'));
                $this->set('action', 'bonusStatus');
                $this->set('field', $this->request->getData('field'));
                $this->set('status', (($this->request->getData('changestaus') == 0) ? 'deactive' : 'active'));
        }
    }
  #----------------------------------------------------------------------------------
    /*Bonuspoints Delete Bonus Point*/
    public function deleteBonus($id = null){

        if($this->request->is('ajax')){
            if($this->request->getData('action') == 'Bonuspoint' && $this->request->getData('id') != ''){

                $bonus         = $this->Bonuspoints->newEntity();
                $bonus         = $this->Bonuspoints->patchEntity($bonus,$this->request->getData());
                $bonus->id     = $this->request->getData('id');
                $bonus->delete_status = 'Y';
                $this->Bonuspoints->save($bonus);

                list($bonusList) = $this->index('Bonuspoint');
                if($this->request->is('ajax')) {
                    $action    = 'Bonuspoint';
                    $this->set(compact('action', 'bonusList'));
                    $this->render('ajaxaction');
                }
            }
        }
    }
  #----------------------------------------------------------------------------------
}#class end...