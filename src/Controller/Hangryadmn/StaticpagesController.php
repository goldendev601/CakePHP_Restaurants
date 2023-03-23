<?php
/**
 * Created by Ramesh.A
 * Date: 17/Aug/17
 * Time: 2:00 PM
 * Team: Sundhar.S  
 */
namespace App\Controller\Hangryadmn;

use Cake\Event\Event;
use App\Controller\AppController;


class StaticpagesController extends AppController{

    public function initialize(){
        parent::initialize();
        $this->viewBuilder()->setLayout('backend');
        $this->loadComponent('Flash');
        //$this->loadComponent('Staticpage');
        $this->loadComponent('Common');
        $this->loadModel('Staticpages');
    }

#--------------------------------------------------------------------------------
    // Staticpages Index
    public function index($process = null){

        $staticList = $this->Staticpages->find('all', [
            'fields' => [
                'Staticpages.id',
                'Staticpages.title',
                'Staticpages.seo_url',
                'Staticpages.sortorder',
                'Staticpages.status',
                'Staticpages.created'
            ],
            'conditions' => [
                'Staticpages.id IS NOT NULL',
                'Staticpages.delete_status' => 'N'
            ],
            'order' => [
                'sortorder' => 'ASC'
            ]
        ])->hydrate(false)->toArray();
        $this->set(compact('staticList'));
        
        if($process == 'Staticpage') {
            $value = array($staticList);
            return $value;
        }
    }#index function end...
#----------------------------------------------------------------------------------

#-----------------------------------------------------------------------------------
    // Staticpages AddEdit
    public function addEdit($id = null){
        //echo $id; die();

        if($this->request->is(['post','put'])){
            //echo "<pre>"; print_r($this->request->getData()); die();
            $static  = $this->Staticpages->newEntity();
            $static  = $this->Staticpages->patchEntity($static,$this->request->getData());

            if(!empty($this->request->getData('editid'))) {
                $static->id       = $this->request->getData('editid');
            }
            $title = trim($this->request->getData('title'));
            $static->title  = $title;
            $static->seo_url   = $this->Common->seoUrl($title);
            $content = trim($this->request->getData('content'));
            $static->content  = $content;
            
            if ($this->Staticpages->save($static)) {
                $this->Flash->success(_('Staticpages details updated successfully'));
                return $this->redirect(ADMIN_BASE_URL.'Staticpages/index');
            }

        }else {

            $staticList    =   [];
            if(!empty($id)){
                $staticList = $this->Staticpages->get($id);
                if(!empty($staticList)) {
                    $this->set(compact('staticList','id'));
                } else {
                    return $this->redirect(ADMIN_BASE_URL . 'Staticpages/index');
                }
            } else {
                $this->set(compact('staticList','id'));
            }
        }
    }# addedit function end...
#-----------------------------------------------------------------------------------

#-----------------------------------------------------------------------------------
    //Static Name Already Exist
    public function staticCheck() {

        if($this->request->getData('title') != '') {
            if($this->request->getData('id') != '') {
                $conditions = [
                    'id !=' => $this->request->getData('id'),
                    'title' => $this->request->getData('title'),
                    'delete_status' => 'N'
                ];
            }else {
                $conditions = [
                    'title' => $this->request->getData('title'),
                    'delete_status' => 'N'
                ];
            }

            $staticpageCount = $this->Staticpages->find('all', [
                'conditions' => $conditions
            ])->count();

            if($staticpageCount == 0) {
                echo '0';
            }else {
                echo '1';
            }
            die();
        }
    }# Static Check function end...
#-----------------------------------------------------------------------------------

#-----------------------------------------------------------------------------------
    public function sortOrder() {
        $updateRecords = explode("record", $this->request->getData('data'));
        $limit = $this->limit;

        foreach ($updateRecords as $key => $value) {
            if (!empty($value)) {

                $sort = $key;
                $staticpage = $this->Staticpages->newEntity();
                $updateSort['sortorder'] = $sort;
                $updateSort['id'] = $value;
                $staticpageSave = $this->Staticpages->patchEntity($staticpage, $updateSort);
                //pr($staticpageSave); die();
                $this->Staticpages->save($staticpageSave);

                $staticList = $this->Staticpages->find('all', [
                    'fields' => [
                        'Staticpages.id',
                        'Staticpages.title',
                        'Staticpages.seo_url',
                        'Staticpages.sortorder',
                        'Staticpages.status',
                        'Staticpages.created'
                    ],
                    'conditions' => [
                        'Staticpages.id IS NOT NULL',
                        'Staticpages.delete_status' => 'N'
                    ],
                    'order' => [
                        'sortorder' => 'ASC'
                    ]
                ])->hydrate(false)->toArray();
                $this->set(compact('staticList'));
            }
        }
        die();
    }
#-----------------------------------------------------------------------------------

#-----------------------------------------------------------------------------------
    //Static Status Change
    public function ajaxaction() {
        if($this->request->is('ajax')){
            if($this->request->getData('action') == 'staticstatuschange'){

                $static         = $this->Staticpages->newEntity();
                $static         = $this->Staticpages->patchEntity($static,$this->request->getData());
                $static->id     = $this->request->getData('id');
                $static->status = $this->request->getData('changestaus');
                $this->Staticpages->save($static);

                $this->set('id', $this->request->getData('id'));
                $this->set('action', 'staticstatuschange');
                $this->set('field', $this->request->getData('field'));
                $this->set('status', (($this->request->getData('changestaus') == 0) ? 'deactive' : 'active'));
            }
        }
    }# StatusChange function end...
#-----------------------------------------------------------------------------------

#-----------------------------------------------------------------------------------
    //Static data delete 
    public function deletestatic($id = null){

        if($this->request->is('ajax')){
            if($this->request->getData('action') == 'Staticpage' && $this->request->getData('id') != ''){

                $static         = $this->Staticpages->newEntity();
                $static         = $this->Staticpages->patchEntity($static,$this->request->getData());
                $static->id     = $this->request->getData('id');
                $static->delete_status = 'Y';
                $this->Staticpages->save($static);

                list($staticList) = $this->index('Staticpage');
                if($this->request->is('ajax')) {
                    $action         = 'Staticpage';
                    $this->set(compact('action', 'staticList'));
                    $this->render('ajaxaction');
                }
            }
        }
    }# delete static function end...
#----------------------------------------------------------------------------------
}#class end...