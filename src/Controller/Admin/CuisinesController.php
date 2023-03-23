<?php
namespace App\Controller\Admin;
use Cake\Event\Event;
use App\Controller\AppController;

class CuisinesController extends AppController{

	public function initialize(){
		parent::initialize();
		$this->viewBuilder()->setLayout('backend');
		$this->loadModel("Cuisines");
        $this->loadComponent('Flash');
	}
 #------------------------------------------------------------------------------------
	public function index($process = null){
        $cuisineList = $this->Cuisines->find('all', [
                'conditions' => [
                    'id IS NOT NULL',
                    'NOT' => [
                    'status' => 3
                    ]
                ]
            ])->hydrate(false)->toArray();
        //echo "<pre>"; print_r($cuisineList); die();
        $this->set(compact('cuisineList'));
        if($process == 'Cuisine') {
            $value = array($cuisineList);
            return $value;
        }
	}
#------------------------------------------------------------------------------------
	public function addedit($id = null){
        
        $cuisineDetail = [];
        if(!empty($id)) {
            $cuisineDetail = $this->Cuisines->get($id);
        }

        if($this->request->is(['post','put'])) {
            $postData = $this->request->getData();
            //echo "<pre>"; print_r($this->request->getData()); die();

            //Edit
            if (!empty($postData['cuisine_name'])) {

                if (!empty($cuisineDetail)) {
                    
                    $cuisineExist = $this->Cuisines->find('all', [
                            'conditions' => [
                                'id !=' => $id,
                                'cuisine_name' => trim($postData['cuisine_name'])
                            ]
                        ])->first();

                    if (empty($cuisineExist)) {
                        
                        $cuisineEntity = $this->Cuisines->patchEntity($cuisineDetail, $postData);
                        if ($this->Cuisines->save($cuisineEntity)) {
                            $this->Flash->success(__("Cuisines Details Updated"));
                            return $this->redirect(['controller' => 'Cuisines', 'action' => 'index']);
                        }
                    } else {
                        $this->Flash->error(__("Cuisines Name Already Exist"));
                    }
                //Add
                } else {

                    $cuisineExist = $this->Cuisines->find('all', [
                            'conditions' => [
                                'cuisine_name' => trim($postData['cuisine_name'])
                            ]
                        ])->first();
                    if (empty($cuisineExist)) {
                        $cuisineEntity = $this->Cuisines->newEntity($postData);
                        if ($this->Cuisines->save($cuisineEntity)) {
                            $this->Flash->success(__("Cuisines Details Inserted"));
                            return $this->redirect(['controller' => 'Cuisines', 'action' => 'index']);
                        }
                    } else {
                        $this->Flash->error(__("Cuisines Name Already Exist"));
                    }
                }
            } else {
                $this->Flash->error(__("Please enter Cuisines Name"));
            }
        }
       
        $this->set(compact("cuisineDetail",'id',''));
	}
#------------------------------------------------------------------------------------
	public function cuisineCheck() {

        if($this->request->getData('cuisine_name') != '') {
            if($this->request->getData('id') != '') {
                $conditions = [
                    'id !=' => $this->request->getData('id'),
                    'cuisine_name' => $this->request->getData('cuisine_name')
                ];
            }else {
                $conditions = [
                    'cuisine_name' => $this->request->getData('cuisine_name')
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
#------------------------------------------------------------------------------------
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
#------------------------------------------------------------------------------------
    public function deletecuisine($id = null){

        if($this->request->is('ajax')){
            if($this->request->getData('action') == 'Cuisine' && $this->request->getData('id') != ''){

                $cuisine         = $this->Cuisines->newEntity();
                $cuisine         = $this->Cuisines->patchEntity($cuisine,$this->request->getData());
                $cuisine->id     = $this->request->getData('id');
                $cuisine->status = 3;
                
                $cat = $this->Cuisines->save($cuisine);
//echo "<pre>"; print_r($cat); die();
                list($cuisineList) = $this->index('Cuisine');
                if($this->request->is('ajax')) {
                    $action         = 'Cuisine';
                    $this->set(compact('action', 'cuisineList'));
                    $this->render('ajaxaction');
                }
            }
        }
    }
 #------------------------------------------------------------------------------------
}
?>