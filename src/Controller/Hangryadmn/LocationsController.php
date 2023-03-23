<?php
namespace App\Controller\Hangryadmn;

use Cake\Event\Event;
use Cake\I18n\Time;
use App\Controller\AppController;

class LocationsController extends AppController{

    public function initialize(){
        parent::initialize();
        $this->viewBuilder()->setLayout('backend'); 
        $this->loadModel('States');        
        $this->loadModel('Cities');       
        $this->loadModel('Locations');       
  }
  #-------------------------------------------------------------------------------------------
  /*Locations Management*/
    public function index($process = null){

        if($process != '' && $process != 'Location') {
            $conditions = [
                'Locations.id IS NOT NULL',
                'Locations.city_id' => $process
            ];
        } elseif($process == '' && $process == 'Location') {
            $conditions = [
                'Locations.id IS NOT NULL'
            ];
        }
        $locationList = $this->Locations->find('all', [
            'fields' => [
                'Locations.id',
                'Locations.state_id',
                'Locations.city_id',
                'Locations.zip_code',
                'Locations.area_name',
                'Locations.status',
                'Locations.created'                
            ],
            'conditions' => $conditions,
            'contain' => [
                 'Cities' =>[
                    'fields'=>[
                       'Cities.id',
                       'Cities.city_name'
                    ],
                    'conditions'=>[
                        'Cities.status' => 1,
                    ]
                ], 
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
        $this->set(compact('locationList'));

        //echo "<pre>"; print_r($locationList); die();
        if($process == 'Location' ){
            $value = array($locationList);
            return $value;
        }
    }  
  #-------------------------------------------------------------------------------------------
    /*Location Add Section*/
   public function add(){  
        
        if($this->request->is(['post','put'])) {
           //echo "<pre>"; print_r($this->request->getData()); die();
            $postData = $this->request->getData();
            
            if (!empty($postData['area_name'])) {

                $locationCount = $this->Locations->find('all', [
                    'conditions' => [
                        'city_id' => trim($postData['city_id']),
                        'area_name' => trim($postData['area_name'])
                    ]
                ])->first();
                if (empty($locationCount)) {
                    $locatEntity = $this->Locations->newEntity($postData);
                    if ($this->Locations->save($locatEntity)) {
                        $this->Flash->success(__("Area details inserted successfully"));
                       return $this->redirect(ADMIN_BASE_URL.'locations/index'); 
                    }
                } else {
                     $this->Flash->error(__("Area name already exist"));
                }

            } else {
                $this->Flash->error(__("Please enter Area name"));
            }
        } 
        //States Details
        $statelist = $this->States->find('list', [
            'keyField' => 'id',
            'valueField' => 'state_name',
            'conditions' => [
                'status' => '1'
            ],
        ])->hydrate(false)->toArray();

        //Cities Details
        $citylist = $this->Cities->find('list', [
            'keyField' => 'id',
            'valueField' => 'city_name',
            'conditions' => [
                'status' => '1'
            ],
        ])->hydrate(false)->toArray();
        $this->set(compact('statelist','citylist'));
   }   
   #-------------------------------------------------------------------------------------------
    /*Location Edit Section*/
   public function edit($id = null){  

       $locationList = [];

        if(!empty($id)) {
            $locationList = $this->Locations->get($id);
        }
        #echo "<pre>"; print_r($locationList); die();
        if($this->request->is(['post','put'])) {
            
            $postData = $this->request->getData();
            
            if (!empty($postData['area_name'])) {

                if (!empty($locationList)) {
                    
                    $locationCount = $this->Locations->find('all', [
                        'conditions' => [
                            'id !=' => $id,
                            'city_id' => trim($postData['city_id']),
                            'area_name' => trim($postData['area_name'])
                        ]
                    ])->first();                    

                    if (empty($locationCount)) {                        
                        $locatEntity = $this->Locations->patchEntity($locationList, $postData);
                        if ($this->Locations->save($locatEntity)) {
                            $this->Flash->success(__("Area details updated successfully"));
                            return $this->redirect(ADMIN_BASE_URL.'locations/index');                           
                        }
                    } else {
                        $this->Flash->error(__("Area name already exist"));
                    }                    
                } 
            } else {
                $this->Flash->error(__("Please enter Area name"));
            }
        } 
       
        //States Details
        $statelist = $this->States->find('list', [
            'keyField' => 'id',
            'valueField' => 'state_name',
            'conditions' => [
                'status' => '1'
            ],
        ])->hydrate(false)->toArray();

        //Cities Details
        $citylist = $this->Cities->find('list', [
            'keyField' => 'id',
            'valueField' => 'city_name',
            'conditions' => [
                'status' => '1'
            ],
        ])->hydrate(false)->toArray();
         $this->set(compact('statelist','citylist','locationList','id'));
   }
  #-------------------------------------------------------------------------------------------
   /*Checking Name already exist*/    
    public function locationCheck() { 
              
        if($this->request->getData('area_name') != '' 
             && $this->request->getData('city_id') != '') {

            if($this->request->getData('id') != '') {
                $conditions = [
                    'id !=' => $this->request->getData('id'),
                    'city_id' => $this->request->getData('city_id'),
                    'area_name' => $this->request->getData('area_name')                    
                ];
            }else {
                $conditions = [
                    'city_id' => $this->request->getData('city_id'),
                    'area_name' => $this->request->getData('area_name')                    
                ];
            }  

            $locationCount = $this->Locations->find('all', [
                'conditions' => $conditions
            ])->count();

            if($locationCount == 0) {
                echo '0';
            }else {
                echo '1';
            }
            die();
        }
    }
  #-------------------------------------------------------------------------------------------
   /*Locations Status Change*/
    public function ajaxaction() {

        if($this->request->is('ajax')){

            if($this->request->getData('action') == 'getCityList'){

                $citylist = $this->Cities->find('list', [
                    'keyField' => 'id',
                    'valueField' => 'city_name',
                    'conditions' => [
                        'status' => '1',
                        'state_id' => $this->request->getData('state_id')
                    ],
                ])->hydrate(false)->toArray();

                $this->set('action', $this->request->getData('action'));
                $this->set('citylist', $citylist);                
            }

            if($this->request->getData('action') == 'locationStatusChange'){
                $location         = $this->Locations->newEntity();
                $location         = $this->Locations->patchEntity($location,$this->request->getData());
                $location->id     = $this->request->getData('id');
                $location->status = $this->request->getData('changestaus');
                $this->Locations->save($location);

                $this->set('id', $this->request->getData('id'));
                $this->set('action', 'locationStatusChange');
                $this->set('field', $this->request->getData('field'));
                $this->set('status', (($this->request->getData('changestaus') == 0) ? 'deactive' : 'active'));
            }
        }
    }
  #-------------------------------------------------------------------------------------------
    /*Location Delete Section*/
    public function deleteLocation($id = null){

        if($this->request->is('ajax')){
            if($this->request->getData('action') == 'Location' 
                && $this->request->getData('id') != ''){
                
                $id     = $this->request->getData('id');
                $entity = $this->Locations->get($id);
                $result = $this->Locations->delete($entity);                                

                list($locationList) = $this->index('Location');
                if($this->request->is('ajax')) {
                    $action    = 'Location';
                    $this->set(compact('action', 'locationList'));
                    $this->render('ajaxaction');
                }
            }
        }
    }
  #-------------------------------------------------------------------------------------------
}#class end...