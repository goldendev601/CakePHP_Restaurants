<?php
namespace App\Controller\Hangryadmn;

use Cake\Event\Event;
use Cake\I18n\Time;
use App\Controller\AppController;

class StatesController extends AppController{

    public function initialize(){
        parent::initialize();
        $this->viewBuilder()->setLayout('backend');  
        $this->loadModel('Countries');  
        $this->loadModel('States');        
    }
  
  #-------------------------------------------------------------------------------------------
  /*States Management*/
    public function index($process = null){

        if($process != '' && $process != 'State' ) {

            $countriesList = $this->Countries->find('all', [
                'conditions' => [
                    'Countries.id IS NOT NULL',
                    'Countries.iso_code' => !empty($process) ? $process : ''
                ]
            ])->hydrate(false)->first();
            $conditions = [
                'States.id IS NOT NULL',
                'States.country_id' => $countriesList['id']
            ];

        } elseif($process == '' || $process == 'State' ) {
            $conditions = [
                'States.id IS NOT NULL'
            ];
        }

        $statesList = $this->States->find('all', [
            'fields' => [
                'States.id',
                'States.country_id',
                'States.state_name',
                'States.status',
                'States.created'
            ],
            'conditions' => $conditions,
            'order' => [
                'States.id' => 'DESC'
            ]
        ])->hydrate(false)->toArray();

        $this->set(compact('statesList'));
        if($process == 'State' ){
            $value = array($statesList);
            return $value;
        }
    }
  #-------------------------------------------------------------------------------------------
    /*Checking Name already exist*/    
    public function stateCheck() { 
              
        if($this->request->getData('state_name') != '' 
          && $this->request->getData('country_id') != '') {

            if($this->request->getData('id') != '') {
                $conditions = [
                    'id !=' => $this->request->getData('id'),
                    'country_id' => $this->request->getData('country_id'),
                    'state_name' => $this->request->getData('state_name'),                    
                ];
            }else {
                $conditions = [
                    'country_id' => $this->request->getData('country_id'),
                    'state_name' => $this->request->getData('state_name'),                    
                ];
            }                   
            $stateCount = $this->States->find('all', [
                'conditions' => $conditions
            ])->count();

            if($stateCount == 0) {
                echo '0';
            }else {
                echo '1';
            }
            die();
        }
    }
  #-------------------------------------------------------------------------------------------
     /*State Add*/
   public function add(){  

       //Countries Details
        $countrylist = $this->Countries->find('list', [
            'keyField' => 'id',
            'valueField' => 'country_name',
            'conditions' => [
                'status' => '1'
            ],
        ])->hydrate(false)->toArray();

        if($this->request->is(['post','put'])) {

           // echo "<pre>"; print_r($this->request->getData()); die();

            $postData = $this->request->getData();
            
            if (!empty($postData['state_name'])) {

                $stateCount = $this->States->find('all', [
                    'conditions' => [
                        'country_id' => trim($postData['country_id']),
                        'state_name' => trim($postData['state_name'])
                    ]
                ])->first();

                if (empty($stateCount)) {
                    $stateEntity = $this->States->newEntity($postData);
                    if ($this->States->save($stateEntity)) {
                        $this->Flash->success(__("State details inserted successfully"));
                       return $this->redirect(ADMIN_BASE_URL.'states/index'); 
                    }
                } else {
                     $this->Flash->error(__("State name already exist"));
                }

            } else {
                $this->Flash->error(__("Please enter State name"));
            }
        } 
        $this->set(compact('countrylist'));          
   }   
  #-------------------------------------------------------------------------------------------
    /*State Edit*/
   public function edit($id = null){  

   //Countries Details
        $countrylist = $this->Countries->find('list', [
            'keyField' => 'id',
            'valueField' => 'country_name',
            'conditions' => [
                'status' => '1'
            ],
        ])->hydrate(false)->toArray();

        $statesList = [];

        if(!empty($id)) {
            $statesList = $this->States->get($id);
        }

        if($this->request->is(['post','put'])) {
            
            $postData = $this->request->getData();
            
            if (!empty($postData['state_name'])) {

                if (!empty($statesList)) {
                    
                    $stateCount = $this->States->find('all', [
                        'conditions' => [
                            'id !=' => $id,
                            'country_id' => trim($postData['country_id']),
                            'state_name' => trim($postData['state_name'])
                        ]
                    ])->first();

                    if (empty($stateCount)) {
                        
                        $stateEntity = $this->States->patchEntity($statesList, $postData);
                        if ($this->States->save($stateEntity)) {
                            $this->Flash->success(__("State details updated successfully"));
                            return $this->redirect(ADMIN_BASE_URL.'states/index');                           
                        }
                    } else {
                        $this->Flash->error(__("State name already exist"));
                    }                    
                } 
            } else {
                $this->Flash->error(__("Please enter state name"));
            }
        }        
        $this->set(compact('countrylist','statesList','id'));
    }      
  #-------------------------------------------------------------------------------------------
   /*States Status Change*/
    public function ajaxaction() {

        if($this->request->is('ajax')){
            if($this->request->getData('action') == 'stateStatusChange'){

                $state         = $this->States->newEntity();
                $state         = $this->States->patchEntity($state,$this->request->getData());
                $state->id     = $this->request->getData('id');
                $state->status = $this->request->getData('changestaus');
                $this->States->save($state);

                $this->set('id', $this->request->getData('id'));
                $this->set('action', 'stateStatusChange');
                $this->set('field', $this->request->getData('field'));
                $this->set('status', (($this->request->getData('changestaus') == 0) ? 'deactive' : 'active'));
            }
        }
    }
  #-------------------------------------------------------------------------------------------
    public function deleteState($id = null){

        if($this->request->is('ajax')){

            if($this->request->getData('action') == 'State' && 
                $this->request->getData('id') != ''){

                $id     = $this->request->getData('id');
                $entity = $this->States->get($id);
                $result = $this->States->delete($entity);

                list($statesList) = $this->index('State');
                if($this->request->is('ajax')) {
                    $action    = 'State';
                    $this->set(compact('action', 'statesList'));
                    $this->render('ajaxaction');
                }
            }
        }
    }
 
 #-------------------------------------------------------------------------------------------
}#class end...