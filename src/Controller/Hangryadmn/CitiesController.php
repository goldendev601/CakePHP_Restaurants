<?php
namespace App\Controller\Hangryadmn;

use Cake\Event\Event;
use Cake\I18n\Time;
use App\Controller\AppController;

class CitiesController extends AppController{

    public function initialize(){
        parent::initialize();
        $this->viewBuilder()->setLayout('backend');  
        $this->loadModel('Countries');  
        $this->loadModel('States');        
        $this->loadModel('Cities');        
    }
  
  #-------------------------------------------------------------------------------------------
  /*Cities Management*/
    public function index($process = null){

        if($process != '' && $process != 'City') {
            $conditions = [
                'Cities.id IS NOT NULL',
                'Cities.state_id' => $process
            ];
        } elseif($process == '' && $process == 'City') {
            $conditions = [
                'Cities.id IS NOT NULL'
            ];
        }
        $citiesList = $this->Cities->find('all', [
            'fields' => [
                'Cities.id',
                'Cities.country_id',
                'Cities.state_id',
                'Cities.city_name',
                'Cities.status',
                'Cities.created'                
            ],
            'conditions' => $conditions,
            'contain' => [
                 'States' =>[
                    'fields'=>[
                       'States.id',
                       'States.state_name'
                    ],
                    'conditions'=>[
                        'States.status' => 1,
                    ]
                ]
            ]
        ])->hydrate(false)->toArray();
        $this->set(compact('citiesList'));

        //echo "<pre>"; print_r($citiesList); die();
        if($process == 'City' ){
            $value = array($citiesList);
            return $value;
        }
    }  
  #-------------------------------------------------------------------------------------------
    /*Checking Name already exist*/    
    public function cityCheck() { 
              
        if($this->request->getData('city_name') != '' 
          && $this->request->getData('state_id') != '') {

            if($this->request->getData('id') != '') {
                $conditions = [
                    'id !=' => $this->request->getData('id'),
                    'state_id' => $this->request->getData('state_id'),
                    'city_name' => $this->request->getData('city_name'),                    
                ];
            }else {
                $conditions = [
                    'state_id' => $this->request->getData('state_id'),
                    'city_name' => $this->request->getData('city_name'),                    
                ];
            }  

            $cityCount = $this->Cities->find('all', [
                'conditions' => $conditions
            ])->count();

            if($cityCount == 0) {
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

        if($this->request->is(['post','put'])) {
           //echo "<pre>"; print_r($this->request->getData()); die();
            $postData = $this->request->getData();
            
            if (!empty($postData['city_name'])) {

                $cityCount = $this->Cities->find('all', [
                    'conditions' => [
                        'state_id' => trim($postData['state_id']),
                        'city_name' => trim($postData['city_name'])
                    ]
                ])->first();
                if (empty($cityCount)) {
                    $cityEntity = $this->Cities->newEntity($postData);
                    if ($this->Cities->save($cityEntity)) {
                        $this->Flash->success(__("City details inserted successfully"));
                       return $this->redirect(ADMIN_BASE_URL.'cities/index'); 
                    }
                } else {
                     $this->Flash->error(__("City name already exist"));
                }

            } else {
                $this->Flash->error(__("Please enter city name"));
            }
        } 
        //Countries Details
        $countrylist = $this->Countries->find('list', [
            'keyField' => 'id',
            'valueField' => 'country_name',
            'conditions' => [
                'status' => 1
            ],
        ])->hydrate(false)->toArray();

       //States Details
        $statelist = $this->States->find('list', [
            'keyField' => 'id',
            'valueField' => 'state_name',
            'conditions' => [
                'status' => 1
            ],
        ])->hydrate(false)->toArray();
        $this->set(compact('countrylist','statelist'));          
   }   
  #-------------------------------------------------------------------------------------------
    /*State Edit*/
   public function edit($id = null){  

        $citiesList = [];

        if(!empty($id)) {
            $citiesList = $this->Cities->get($id);
        }

        //echo "<pre>"; print_r($citiesList); die();

        if($this->request->is(['post','put'])) {
            
            $postData = $this->request->getData();
            
            if (!empty($postData['city_name'])) {

                if (!empty($citiesList)) {
                    
                    $cityCount = $this->Cities->find('all', [
                        'conditions' => [
                            'id !=' => $id,
                            'state_id' => trim($postData['state_id']),
                            'city_name' => trim($postData['city_name'])
                        ]
                    ])->first();                    

                    if (empty($cityCount)) {                        
                        $cityEntity = $this->Cities->patchEntity($citiesList, $postData);
                        if ($this->Cities->save($cityEntity)) {
                            $this->Flash->success(__("City details updated successfully"));
                            return $this->redirect(ADMIN_BASE_URL.'cities/index');                           
                        }
                    } else {
                        $this->Flash->error(__("City name already exist"));
                    }                    
                } 
            } else {
                $this->Flash->error(__("Please enter city name"));
            }
        } 
         
        //Countries Details
        $countrylist = $this->Countries->find('list', [
            'keyField' => 'id',
            'valueField' => 'country_name',
            'conditions' => [
                'status' => 1
            ],
        ])->hydrate(false)->toArray();


        //States Details
        $statelist = $this->States->find('list', [
            'keyField' => 'id',
            'valueField' => 'state_name',
            'conditions' => [
                'status' => 1
            ],
        ])->hydrate(false)->toArray();      
        $this->set(compact('countrylist','statelist','citiesList','id'));
    }      
  #-------------------------------------------------------------------------------------------
   /*States Status Change*/
    public function ajaxaction() {

        if($this->request->is('ajax')){

            if($this->request->getData('action') == 'getStateList'){

                $statelist = $this->States->find('list', [
                    'keyField' => 'id',
                    'valueField' => 'state_name',
                    'conditions' => [
                        'status' => 1,
                        'country_id' => $this->request->getData('country_id')
                    ],
                ])->hydrate(false)->toArray();

                $this->set('action', $this->request->getData('action'));
                $this->set('statelist', $statelist);                
            }

            if($this->request->getData('action') == 'cityStatusChange'){

                $city         = $this->Cities->newEntity();
                $city         = $this->Cities->patchEntity($city,$this->request->getData());
                $city->id     = $this->request->getData('id');
                $city->status = $this->request->getData('changestaus');
                $this->Cities->save($city);

                $this->set('id', $this->request->getData('id'));
                $this->set('action', 'cityStatusChange');
                $this->set('field', $this->request->getData('field'));
                $this->set('status', (($this->request->getData('changestaus') == 0) ? 'deactive' : 'active'));
            }
        }
    }
  #-------------------------------------------------------------------------------------------
    /*Delete City */
    public function deleteCity($id = null){

        if($this->request->is('ajax')){

            if($this->request->getData('action') == 'City' && 
                $this->request->getData('id') != ''){

                $id     = $this->request->getData('id');
                $entity = $this->Cities->get($id);
                $result = $this->Cities->delete($entity);

                list($citiesList) = $this->index('City');
                if($this->request->is('ajax')) {
                    $action    = 'City';
                    $this->set(compact('action', 'citiesList'));
                    $this->render('ajaxaction');
                }
            }
        }
    } 
 #-------------------------------------------------------------------------------------------
}#class end...