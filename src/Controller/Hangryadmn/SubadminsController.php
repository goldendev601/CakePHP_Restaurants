<?php
/**
 * Created by ArunYadhav.C
 * Date: 17/Aug/17
 * Time: 2:00 PM
 * Team: Sundhar.S  
 */
namespace App\Controller\Hangryadmn;

use Cake\Event\Event;
use App\Controller\AppController;


class SubadminsController extends AppController{

    public function initialize(){
        parent::initialize();
		$this->viewBuilder()->setLayout('backend');
        $this->loadComponent('Flash');
        $this->loadComponent('Common');
        $this->loadModel('Users');
        $this->loadModel('Subadmins');
        $this->loadModel('EModules');
    }

    public function beforeFilter(Event $event)
    {
        // Before Login , these are the function we can access
        $this->Auth->allow([
            'login'
        ]);
    }
#--------------------------------------------------------------------------------
    /*Showing Subadmins Lists*/
    public function index($process = null) {
        $subAdminList = $this->Users->find('all', [
            'conditions' => [
                'Users.id IS NOT NULL',
                'Users.role_id' => 4,
                'Users.deleted_status' => 'N'
            ],
            'order' => [
                'Users.id' => 'DESC'
            ]
        ])->hydrate(false)->toArray();
        //echo "<pre>"; print_r($subAdminList); die();
        $this->set(compact('subAdminList'));

        if($process == 'Subadmin') {
            $value = array($subAdminList);
            return $value;
        }
    }
#--------------------------------------------------------------------------------
    /*Check Username already exists or Not*/
    public function checkUsername(){

        if($this->request->getData('username') != '') {
            if($this->request->getData('id') != '') {
                $conditions = [
                    'id !=' => $this->request->getData('id'),
                    'username' => $this->request->getData('username'),
                ];
            }else {
                $conditions = [
                    'username' => $this->request->getData('username'),
                ];
            }
            $customerCount = $this->Users->find('all', [
                'conditions' => $conditions
            ])->count();

            if($customerCount == 0) {
                echo '0';
            }else {
                echo '1';
            }
            die();
        }
    }
#--------------------------------------------------------------------------------

    /*Add Permission*/
    public function addEditPermissions($id = null) {
        $subAdminDetail = [];
        $subAdminPermission = [];
        if(!empty($id)) {
            $subAdminDetail = $this->Users->find('all', [
                'conditions' => [
                    'id' => $id,
                    'role_id' => 4
                ]
            ])->first();

            $username = $subAdminDetail['username'];
            $subAdminPermission = explode(",",$subAdminDetail['permissions']);
        }
        if ($this->request->is(['post','put'])) {
            $postData = $this->request->getData();

            if (!empty($subAdminDetail)) {
                $subAdminExist = $this->Users->find('all', [
                    'conditions' => [
                        'id !=' => $id,
                        //'role_id' => 4,
                        'username' => trim($postData['username'])
                    ]
                ])->first();

                if (empty($subAdminExist)) {
                    $subAdminDet['username'] = $postData['username'];
                    $subAdminDet['first_name'] = $postData['first_name'];
                    $subAdminDet['last_name'] = $postData['last_name'];
                    $subAdminDet['phone_number'] = $postData['phone_number'];
                    $subAdminDet['address'] = $postData['address'];
                    if (!empty($postData['category'])) {
                        $subAdminDet['permissions'] = implode(",", $postData['category']);
                    } else {
                        $subAdminDet['permissions'] = '';
                    }
                    $subAdminEntity = $this->Users->patchEntity($subAdminDetail, $subAdminDet);
                    //pr($subAdminDet); die();
                    if ($this->Users->save($subAdminEntity)) {
                        $this->Flash->success(__("Subadmin Details Updated"));
                        return $this->redirect(['controller' => 'Subadmins', 'action' => 'index']);
                    }
                } else {
                    $this->Flash->error(__("username already Exist"));
                    //return $this->redirect(['controller' => 'Subadmins', 'action' => 'index']);
                }
            }else {
                $subAdminExist = $this->Users->find('all', [
                    'conditions' => [
                        //'role_id' => 4,
                        'username' => trim($postData['username'])
                    ]
                ])->first();
                if (empty($subAdminExist)) {
                    $subAdminDet['username'] = $postData['username'];
                    $subAdminDet['password'] = $postData['password'];
                    $subAdminDet['first_name'] = $postData['first_name'];
                    $subAdminDet['last_name'] = $postData['last_name'];
                    $subAdminDet['phone_number'] = $postData['phone_number'];
                    $subAdminDet['address'] = $postData['address'];
                    $subAdminDet['role_id'] = 4;
                    if (!empty($postData['category'])) {
                        $subAdminDet['permissions'] = implode(",", $postData['category']);
                    } else {
                        $subAdminDet['permissions'] = '';
                    }
                    $subAdminEntity = $this->Users->newEntity($subAdminDet);
                    //echo "<pre>"; print_r($subAdminEntity); die();
                    if ($this->Users->save($subAdminEntity)) {
                        $this->Flash->success(__("Subadmin Details Inserted"));
                        return $this->redirect(['controller' => 'Subadmins', 'action' => 'index']);
                    }
                } else {
                    $this->Flash->error(__("Subadmin Name Already Exist"));
                }
            }
        }
        $module = $this->EModules->find('all', [
            'conditions' => [
                'id IS NOT NULL',
                'parent_id' => 0
            ]
        ])->hydrate(false)->toArray();
        
        foreach ($module as $key => $val) {
            $subModule = $this->EModules->find('all', [
                'conditions' => [
                    'id IS NOT NULL',
                    'parent_id' => $val['id']
                ]
            ])->hydrate(false)->toArray();

            $module[$key]['subModules'] = $subModule;
        }



        $this->set(compact('id','subAdminDetail', 'module', 'subAdminPermission', 'username'));
    }
    #--------------------------------------------------------------------------------
    /*Ajaxaction*/
    public function ajaxaction() {
        if($this->request->is('ajax')){
            if($this->request->getData('action') == 'subadminstatuschange'){

                $category         = $this->Users->newEntity();
                $category         = $this->Users->patchEntity($category,$this->request->getData());
                $category->id     = $this->request->getData('id');
                $category->status = $this->request->getData('changestaus');
                $this->Users->save($category);

                $this->set('id', $this->request->getData('id'));
                $this->set('action', 'subadminstatuschange');
                $this->set('field', $this->request->getData('field'));
                $this->set('status', (($this->request->getData('changestaus') == 0) ? 'deactive' : 'active'));
            }
        }
    }
#--------------------------------------------------------------------------------
    public function deletesubadmin($id = null){

        if($this->request->is('ajax')){
            if($this->request->getData('action') == 'Subadmin' && $this->request->getData('id') != ''){
                $buyer         = $this->Users->newEntity();
                $buyer         = $this->Users->patchEntity($buyer,$this->request->getData());
                $buyer->id     = $this->request->getData('id');
                $buyer->deleted_status = 'Y';
                $this->Users->save($buyer);

                list($subAdminList) = $this->index('Subadmin');
                if($this->request->is('ajax')) {
                    $action         = 'Subadmin';
                    $this->set(compact('action', 'subAdminList'));
                    $this->render('ajaxaction');
                }
            }
        }
    }

    public function checkSubuser() {

        if($this->request->getData('id') != '') {
            $conditions = [
                'id !=' => $this->request->getData('id'),
                'username' => trim($this->request->getData('username'))
            ];
        }else {
            $conditions = [
                'username' => trim($this->request->getData('username'))
            ];
        }

        $subAdminExist = $this->Users->find('all', [
            'conditions' => $conditions
        ])->first();

        if($subAdminExist == 0) {
            echo '0';die();
        }else {
            echo '1';die();
        }
    }
    #--------------------------------------------------------------------------------
}#class end...