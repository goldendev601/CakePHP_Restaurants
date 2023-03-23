<?php
namespace App\Controller\Hangryadmn;

use Cake\Event\Event;
use Cake\I18n\Time;
use App\Controller\AppController;

class CountriesController extends AppController{

    public function initialize(){
        parent::initialize();
        $this->viewBuilder()->setLayout('backend');  
        $this->loadModel('Countries'); 
    }
  
  #-------------------------------------------------------------------------------------------
  /*Countries Management*/
    public function index($process = null){
        
        $countriesList = $this->Countries->find('all', [
            'fields' => [
                'Countries.id',
                'Countries.country_name',
                'Countries.currency_name',
                'Countries.currency_code',
                'Countries.currency_symbol',
                'Countries.phone_code',
                'Countries.iso_code',
                'Countries.status',
                'Countries.created'                
            ],
            'conditions' => [
                'Countries.id IS NOT NULL',                
            ],
            'order' => [
                'Countries.id' => 'DESC'
            ]
        ])->hydrate(false)->toArray();
        $this->set(compact('countriesList'));

        #echo "<pre>"; print_r($countriesList); die();
        if($process == 'Country' ){
            $value = array($countriesList);
            return $value;
        }
    }  
  #-------------------------------------------------------------------------------------------
    /*Checking Name already exist*/    
    public function countryCheck() { 
              
        if($this->request->getData('country_name') != '') {
            if($this->request->getData('id') != '') {
                $conditions = [
                    'id !=' => $this->request->getData('id'),
                    'country_name' => $this->request->getData('country_name'),                    
                ];
            }else {
                $conditions = [
                    'country_name' => $this->request->getData('country_name'),                   
                ];
            }                   
            $countryCount = $this->Countries->find('all', [
                'conditions' => $conditions
            ])->count();

            if($countryCount == 0) {
                echo '0';
            }else {
                echo '1';
            }
            die();
        }
    }
  #-------------------------------------------------------------------------------------------
    /*Country Add*/
   public function add(){         

        if($this->request->is(['post','put'])) {

            $postData = $this->request->getData();
            
            if (!empty($postData['country_name'])) {

                $countryCount = $this->Countries->find('all', [
                    'conditions' => [
                        'country_name' => trim($postData['country_name'])
                    ]
                ])->first();

                if (empty($countryCount)) {
                    $catEntity = $this->Countries->newEntity($postData);
                    if ($this->Countries->save($catEntity)) {
                        $this->Flash->success(__("Country details inserted successfully"));
                       return $this->redirect(ADMIN_BASE_URL.'countries/index'); 
                    }
                } else {
                     $this->Flash->error(__("Country name already exist"));
                }

            } else {
                $this->Flash->error(__("Please enter country name"));
            }
        } 
   }   
  #-------------------------------------------------------------------------------------------
   /*Country Edit*/
   public function edit($id = null){  

        $countriesList = [];
        if(!empty($id)) {
            $countriesList = $this->Countries->get($id);
        }

        if($this->request->is(['post','put'])) {
            
                $postData = $this->request->getData();
                
                if (!empty($postData['country_name'])) {

                    if (!empty($countriesList)) {
                        
                        $countryCount = $this->Countries->find('all', [
                                'conditions' => [
                                    'id !=' => $id,
                                    'country_name' => trim($postData['country_name'])
                                ]
                            ])->first();

                        if (empty($countryCount)) {
                            
                            $catEntity = $this->Countries->patchEntity($countriesList, $postData);
                            if ($this->Countries->save($catEntity)) {
                                $this->Flash->success(__("Country details updated successfully"));
                                return $this->redirect(ADMIN_BASE_URL.'countries/index');                           
                            }
                        } else {
                            $this->Flash->error(__("Country name already exist"));
                        }
                    
                    } 
                } else {
                    $this->Flash->error(__("Please enter country name"));
                }
        }       
        $this->set(compact("countriesList",'id'));
   }   
  #-------------------------------------------------------------------------------------------
   
   /*Country Status Change*/
    public function ajaxaction() {

        if($this->request->is('ajax')){
            if($this->request->getData('action') == 'countryStatusChange'){
                $country         = $this->Countries->newEntity();
                $country         = $this->Countries->patchEntity($country,$this->request->getData());
                $country->id     = $this->request->getData('id');
                $country->status = $this->request->getData('changestaus');
                $this->Countries->save($country);

                $this->set('id', $this->request->getData('id'));
                $this->set('action', 'countryStatusChange');
                $this->set('field', $this->request->getData('field'));
                $this->set('status', (($this->request->getData('changestaus') == 0) ? 'deactive' : 'active'));
            }
        }
    }
  #-------------------------------------------------------------------------------------------
     /*Country Delete*/
    public function deleteCountry($id = null){
        
        if($this->request->is('ajax')){
            if($this->request->getData('action') == 'Country'
                && $this->request->getData('id') != ''){

                $id     = $this->request->getData('id');
                $entity = $this->Countries->get($id);                
                $this->Countries->delete($entity);

                list($countriesList) = $this->index('Country');
                if($this->request->is('ajax')) {
                    $action    = 'Country';
                    $this->set(compact('action', 'countriesList'));
                    $this->render('ajaxaction');
                }
            }
        }
    }
#-------------------------------------------------------------------------------------------  
}#class end...