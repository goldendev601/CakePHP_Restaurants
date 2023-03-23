<?php
namespace App\Controller\Hangryadmn;

use Cake\Event\Event;
use Cake\I18n\Time;
use App\Controller\AppController;

class CuisinesController extends AppController{

    public function initialize(){
        parent::initialize();
        $this->viewBuilder()->setLayout('backend');     
        $this->loadModel('Cuisines');   
        $this->loadComponent('Common');     
    }
  #------------------------------------------------------------------------------------------------------
    /*Cuisines Management*/
    public function index($process = null){
        $cuisinesList = $this->Cuisines->find('all', [
            'fields' => [
                'Cuisines.id',
                'Cuisines.cuisine_name',
                'Cuisines.cuisine_seo',
                'Cuisines.status',
                'Cuisines.created'                
            ],
            'conditions' => [
                'Cuisines.id IS NOT NULL',
                'Cuisines.delete_status' => 'N'
            ],
            'order' => [
                'Cuisines.cuisine_name' => 'ASC'
            ]
        ])->hydrate(false)->toArray();
        $this->set(compact('cuisinesList'));

        #echo "<pre>"; print_r($cuisinesList); die();
        if($process == 'Cuisines'){
            $value = array($cuisinesList);
            return $value;
        }
    }  
  #---------------------------------------------------------------------------------------------

    /*Cuisine Add Edit Section*/
   public function addedit($id = null){

        if($this->request->is(['post','put'])){

            $cuisine  = $this->Cuisines->newEntity();
            $cuisine  = $this->Cuisines->patchEntity($cuisine,$this->request->getData());

            if(!empty($this->request->getData('editid'))) {
                $cuisine->id       = $this->request->getData('editid');
            }
            $cuisineName = trim($this->request->getData('cuisine_name'));
            $cuisine->cuisine_name  = $cuisineName;
            $cuisine->cuisine_seo   = $this->Common->seoUrl($cuisineName);

            //echo "<pre>"; print_r($cuisine); die();
            
            if ($this->Cuisines->save($cuisine)) {
                $this->Flash->success(_('Cuisine details updated successfully'));
                return $this->redirect(ADMIN_BASE_URL.'cuisines/index');
            }

        }else {

            $cuisinesList    =   [];
            if(!empty($id)){
                $cuisinesList = $this->Cuisines->get($id);
                if(!empty($cuisinesList)) {
                    $this->set(compact('cuisinesList','id'));
                } else {
                    return $this->redirect(ADMIN_BASE_URL.'cuisines/index');
                }
            } else {
                $this->set(compact('cuisinesList','id'));
            }
        }
   }   
  #-------------------------------------------------------------------------------------------
   /*Checking Name already exist*/    
    public function cuisineCheck() { 
              
        if($this->request->getData('cuisine_name') != '') {
            if($this->request->getData('id') != '') {
                $conditions = [
                    'id !=' => $this->request->getData('id'),
                    'cuisine_name' => $this->request->getData('cuisine_name'),
                    'delete_status' => 'N'
                ];
            }else {
                $conditions = [
                    'cuisine_name' => $this->request->getData('cuisine_name'),
                    'delete_status' => 'N'
                ];
            }                   
            $cuisineCount = $this->Cuisines->find('all', [
                'conditions' => $conditions
            ])->count();

            if($cuisineCount == 0) {
                echo '0';
            }else {
                echo '1';
            }
            die();
        }
    }
  #-------------------------------------------------------------------------------------------
   /*Category Status Change*/
    public function ajaxaction() {

        if($this->request->is('ajax')){
            if($this->request->getData('action') == 'cuisinestatuschange'){

                $cuisine         = $this->Cuisines->newEntity();
                $cuisine         = $this->Cuisines->patchEntity($cuisine,$this->request->getData());
                $cuisine->id     = $this->request->getData('id');
                $cuisine->status = $this->request->getData('changestaus');
                $this->Cuisines->save($cuisine);

                $this->set('id', $this->request->getData('id'));
                $this->set('action', 'cuisinestatuschange');
                $this->set('field', $this->request->getData('field'));
                $this->set('status', (($this->request->getData('changestaus') == 0) ? 'deactive' : 'active'));
            }
        }
    }
  #-------------------------------------------------------------------------------------------
    /*Delete Section For Cuisine*/
    public function deleteCuisine($id = null){

        if($this->request->is('ajax')){
            if($this->request->getData('action') == 'Cuisines' && $this->request->getData('id') != ''){

                $cuisine         = $this->Cuisines->newEntity();
                $cuisine         = $this->Cuisines->patchEntity($cuisine,$this->request->getData());
                $cuisine->id     = $this->request->getData('id');
                $cuisine->delete_status = 'Y';
                $this->Cuisines->save($cuisine);

                list($cuisinesList) = $this->index('Cuisines');
                if($this->request->is('ajax')) {
                    $action    = 'Cuisines';
                    $this->set(compact('action', 'cuisinesList'));
                    $this->render('ajaxaction');
                }
            }
        }
    }
#-------------------------------------------------------------------------------------------  
 
}#class end...