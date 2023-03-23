<?php

namespace App\Controller\Restaurantadmin;

use Cake\Event\Event;
use App\Controller\AppController;


class CategoriesController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->viewBuilder()->setLayout('restaurant');
        $this->loadComponent('Flash');
        $this->loadModel('Categories');
	$this->loadComponent('Common');
    }

    public function beforeFilter(Event $event)
    {
        // Before Login , these are the function we can access
        $this->Auth->allow([
            'login'
        ]);
    }
#--------------------------------------------------------------------------------------------
    //Restaurantadmin Categories index
    public function index () {
        $catList = $this->Categories->find('all', [
            'fields' => [
                'Categories.id',
                'Categories.category_name',
                'Categories.category_seo',
                'Categories.sortorder',
                'Categories.status',
                'Categories.created'
            ],
            'conditions' => [
                'Categories.id IS NOT NULL',
                'Categories.delete_status' => 'N'
            ],
            'order' =>[
                'Categories.sortorder' => 'ASC'
            ]
        ])->hydrate(false)->toArray();
        $this->set(compact('catList'));

        if($process == 'Category' ){
            $value = array($catList);
            return $value;
        }
    }#index function end...

  #-------------------------------------------------------------------------------------------
      /*Category Add Edit Functionality*/
    public function addedit($id = null){  

        if($this->request->is(['post','put'])){

            $category  = $this->Categories->newEntity();
            $category  = $this->Categories->patchEntity($category,$this->request->getData());
            
            if(!empty($this->request->getData('editid'))) {
                $category->id       = $this->request->getData('editid');
            }else {
                $category->status = '1';
            }
            $categoryName = trim($this->request->getData('category_name'));
            $category->category_name  = $categoryName;
            $category->category_seo   = $this->Common->seoUrl($categoryName);

          
           
            if ($this->Categories->save($category)) {
                $this->Flash->success(_('Category details updated successfully'));
                return $this->redirect('https://www.hangrymenu.com/restaurantadmin/categories/index');
            }

        }else {

            $catList    =   [];
            if(!empty($id)){
                $catList = $this->Categories->get($id);
                if(!empty($catList)) {
                    $this->set(compact('catList','id'));
                } else {
                    return $this->redirect('https://www.hangrymenu.com/restaurantadmin/categories/index');
                }
            } else {
                $this->set(compact('catList','id'));
            }
        }
   }   
  #-------------------------------------------------------------------------------------------
   /*Checking Name already exist*/    
    public function categoryCheck() { 
              
        if($this->request->getData('category_name') != '') {
            if($this->request->getData('id') != '') {
                $conditions = [
                    'id !=' => $this->request->getData('id'),
                    'category_name' => $this->request->getData('category_name'),
                    'delete_status' => 'N'
                ];
            }else {
                $conditions = [
                    'category_name' => $this->request->getData('category_name'),
                    'delete_status' => 'N'
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
  #-------------------------------------------------------------------------------------------
    /*Category SortOrder*/
    public function sortOrder() {
        //$this->viewBuilder()->setLayout('');
        $updateRecords = explode("record", $this->request->getData('data'));
        $limit = $this->limit;

        foreach ($updateRecords as $key => $value) {
            if (!empty($value)) {

                $sort = $key;
                $categorypage = $this->Categories->newEntity();
                $updateSort['sortorder'] = $sort;
                $updateSort['id'] = $value;
                $categorySave = $this->Categories->patchEntity($categorypage, $updateSort);
                $this->Categories->save($categorySave);

                $catList = $this->Categories->find('all', [
                    'fields' => [
                        'Categories.id',
                        'Categories.category_name',
                        'Categories.category_seo',
                        'Categories.sortorder',
                        'Categories.status',
                        'Categories.created'
                    ],
                    'conditions' => [
                        'Categories.id IS NOT NULL',
                        'Categories.delete_status' => 'N'
                    ],
                    'order' =>[
                        'Categories.sortorder' => 'ASC'
                    ]
                ])->hydrate(false)->toArray();
                $this->set(compact('catList'));
            }
        }
        die();
    }
#-----------------------------------------------------------------------------------
   /*Category Status Change*/
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
  #-------------------------------------------------------------------------------------------
    /*Category Delete Section*/
    public function deletecategory($id = null){

        if($this->request->is('ajax')){
            if($this->request->getData('action') == 'Category' && $this->request->getData('id') != ''){

                $category         = $this->Categories->newEntity();
                $category         = $this->Categories->patchEntity($category,$this->request->getData());
                $category->id     = $this->request->getData('id');
                $category->delete_status = 'Y';
                $this->Categories->save($category);

                list($catList) = $this->index('Category');
                if($this->request->is('ajax')) {
                    $action    = 'Category';
                    $this->set(compact('action', 'catList'));
                    $this->render('ajaxaction');
                }
            }
        }
    }

}#class function end...
