<?php
namespace App\Controller\Admin;
use Cake\Event\Event;
use App\Controller\AppController;

class CategoriesController extends AppController{

	public function initialize(){
		parent::initialize();
		$this->viewBuilder()->setLayout('backend');
		$this->loadModel("Categories");
        $this->loadModel("Stores");
        $this->loadComponent('Flash');
	}
 #------------------------------------------------------------------------------------
	public function index($process = null){
        $categoryList = $this->Categories->find('all', [
                'conditions' => [
                    'id IS NOT NULL',
                    'NOT' => [
                    'status' => 3
                    ]
                ],
                'order' => [
                    'id' => 'DESC'
                ]
            ])->hydrate(false)->toArray();
        //echo "<pre>"; print_r($categoryList); die();
        $this->set(compact('categoryList'));
        if($process == 'Category') {
            $value = array($categoryList);
            return $value;
        }
	}
#------------------------------------------------------------------------------------
	public function addedit($id = null){
        
        $catDetail = [];
        if(!empty($id)) {
            $catDetail = $this->Categories->get($id);
        }

        if($this->request->is(['post','put'])) {
            $postData = $this->request->getData();
            //echo "<pre>"; print_r($postData); die();

            //Edit
            if (!empty($postData['category_name'])) {

                if (!empty($catDetail)) {
                    
                    $catExist = $this->Categories->find('all', [
                            'conditions' => [
                                'id !=' => $id,
                                'category_name' => trim($postData['category_name'])
                            ]
                        ])->first();

                    if (empty($catExist)) {
                        
                        $catEntity = $this->Categories->patchEntity($catDetail, $postData);
                        if ($this->Categories->save($catEntity)) {
                            $this->Flash->success(__("Categories Details Updated"));
                            return $this->redirect(['controller' => 'Categories', 'action' => 'index']);
                        }
                    } else {
                        $this->Flash->error(__("Categories Name Already Exist"));
                    }
                //Add
                } else {

                    $catExist = $this->Categories->find('all', [
                            'conditions' => [
                                'category_name' => trim($postData['category_name'])
                            ]
                        ])->first();
                    if (empty($catExist)) {
                        $catEntity = $this->Categories->newEntity($postData);
                        if ($this->Categories->save($catEntity)) {
                            $this->Flash->success(__("Categories Details Inserted"));
                            return $this->redirect(['controller' => 'Categories', 'action' => 'index']);
                        }
                    } else {
                        $this->Flash->error(__("Categories Name Already Exist"));
                    }
                }
            } else {
                $this->Flash->error(__("Please enter Categories Name"));
            }
        }
        /*$stores = $this->Stores->find('list', array(
                                'conditions'=>array('Store.status'=>1),
                                'fields' => array('Store.id', 'Store.store_name')));*/
        $this->set(compact("catDetail",'id','stores'));
	}
#------------------------------------------------------------------------------------
	public function categoryCheck() {

        if($this->request->getData('category_name') != '') {
            if($this->request->getData('id') != '') {
                $conditions = [
                    'id !=' => $this->request->getData('id'),
                    'category_name' => $this->request->getData('category_name')
                ];
            }else {
                $conditions = [
                    'category_name' => $this->request->getData('category_name')
                ];
            }

            $categoryCount = $this->Categories->find('all', [
                'conditions' => $conditions
            ])->count();

            if($categoryCount == 0) {
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
            if($this->request->getData('action') == 'categorystatuschange'){

                $category         = $this->Categories->newEntity();
                $category         = $this->Categories->patchEntity($category,$this->request->getData());
                $category->id     = $this->request->getData('id');
                $category->status = $this->request->getData('changestaus');
                $this->Categories->save($category);

                $this->set('id', $this->request->getData('id'));
                $this->set('action', 'categorystatuschange');
                $this->set('field', $this->request->getData('field'));
                $this->set('status', (($this->request->getData('changestaus') == 0) ? 'deactive' : 'active'));
            }
        }
    }
#------------------------------------------------------------------------------------
    public function deletecategory($id = null){

        if($this->request->is('ajax')){
            if($this->request->getData('action') == 'Category' && $this->request->getData('id') != ''){

                $category         = $this->Categories->newEntity();
                $category         = $this->Categories->patchEntity($category,$this->request->getData());
                $category->id     = $this->request->getData('id');
                $category->status = 3;
                
                $cat = $this->Categories->save($category);
                list($categoryList) = $this->index('Category');
                if($this->request->is('ajax')) {
                    $action         = 'Category';
                    $this->set(compact('action', 'categoryList'));
                    $this->render('ajaxaction');
                }
            }
        }
    }
 #------------------------------------------------------------------------------------
}
?>