<?php
/**
 * Created by ArunYadhav.C
 * Date: 17/Aug/17
 * Time: 2:00 PM
 * Team: Sundhar.S  
 */
namespace App\Controller\Hangryadmn;

use Cake\Event\Event;
use Cake\Auth\DefaultPasswordHasher;
use App\Controller\AppController;


class UsersController extends AppController{

    public function initialize(){
        parent::initialize();
		$this->viewBuilder()->setLayout('backend');
        $this->loadComponent('Flash');

        $this->loadModel('Users');
        $this->loadModel('Restaurants');
        $this->loadModel('Drivers');
        $this->loadModel('Orders');
    }
//-----------------------------------------------------------------------------------
    public function beforeFilter(Event $event)
    {
        // Before Login , these are the function we can access
        $this->Auth->allow([
            'login'
        ]);
    }
#------------------------------------------------------------------------------------
    //Foodadmin login
    public function login(){

        if (!empty($this->Auth->user()) && $this->Auth->user('role_id') === 2) {
            $this->Flash->success('Please logout restaurant panel');
            return $this->redirect(REST_BASE_URL);
        }else if (!empty($this->Auth->user()) && $this->Auth->user('role_id') === 3) {
            $this->Flash->success('Please logout frontend');
            return $this->redirect(BASE_URL);
        }

        $this->loadComponent('Auth', [
            'loginRedirect' => [
                'controller' => 'Users',
                'action' => 'login'
            ],
            'logoutRedirect' => [
                'controller' => 'Users',
                'action' => 'logout'
            ]
        ]);

        if(!empty($this->Auth->user())){
            if($this->Auth->redirectUrl() == '/') {
                return $this->redirect(ADMIN_BASE_URL.'dashboard');
            }else {
                return $this->redirect($this->Auth->redirectUrl());
            }
        }else if($this->request->is('post')){
            $user = $this->Auth->identify();
            if(!empty($user) && ($user['role_id'] == 1)){
                $this->Auth->setUser($user);
                $this->Flash->success('Login Successful');
                if($this->Auth->redirectUrl() == '/') {
                    return $this->redirect(ADMIN_BASE_URL.'dashboard');
                }else {
                    return $this->redirect($this->Auth->redirectUrl());
                }
            }else if(!empty($user) && ($user['role_id'] == 4) &&  ($user['status'] == 1)  && ($user['deleted_status'] == 'N')){
                $this->Auth->setUser($user);
                $this->Flash->success('Login Successful');
                if($this->Auth->redirectUrl() == '/') {
                    return $this->redirect(ADMIN_BASE_URL.'dashboard');
                }else {
                    return $this->redirect($this->Auth->redirectUrl());
                }
            }else if(!empty($user) && ($user['role_id'] == 4) &&  ($user['status'] == 0)  && ($user['deleted_status'] == 'N')){
                $this->Flash->error('Your account deactivated. Please contact admin');
            }else if(!empty($user) && ($user['role_id'] == 4) && ($user['deleted_status'] == 'Y')){
                $this->Flash->error('Your account deleted. Please contact admin');
            }else{
                $this->Flash->error('Invalid username or password, try again');
            }
        }  
    }#login function end...
#---------------------------------------------------------------------------------------------

    //Foodadmin dashboard 
    public function dashboard() {
        // User Details
        $totalUsers = $this->Users->find('all',[
            'conditions' => [
                'Users.id IS NOT NULL',
                'Users.role_id' => '3',
                'Users.deleted_status' => 'N'
            ]
        ])->count();
        //pr($totalUsers); die();
        $deactiveUsers = $this->Users->find('all',[
            'conditions' => [
                'Users.id IS NOT NULL',
                'Users.status' => '0',
                'Users.role_id' => '3',
                'Users.deleted_status' => 'N'
            ]
        ])->count();
        //pr($deactiveUsers); die();
        $activeUsers = $this->Users->find('all',[
            'conditions' => [
                'Users.id IS NOT NULL',
                'Users.status' => 1,
                'Users.role_id' => '3',
                'Users.deleted_status' => 'N'
            ]
        ])->count();
        //pr($activeUsers); die();

        //Restaurant Details
        $totalRestaurants = $this->Restaurants->find('all',[
            'conditions' => [
                'Restaurants.id IS NOT NULL',
                'Restaurants.delete_status' => 'N'
            ]
        ])->count();
        //pr($totalRestaurants); die();
        $deactiveRestaurants = $this->Restaurants->find('all',[
            'conditions' => [
                'Restaurants.id IS NOT NULL',
                'Restaurants.status' => 0,
                'Restaurants.delete_status' => 'N'
            ]
        ])->count();
        //pr($deactiveRestaurants); die();
        $activeRestaurants = $this->Restaurants->find('all',[
            'conditions' => [
                'Restaurants.id IS NOT NULL',
                'Restaurants.status' => 1,
                'Restaurants.delete_status' => 'N'
            ]
        ])->count();
        //pr($activeRestaurants); die();

        //Drivers Details
        $totalDrivers = $this->Drivers->find('all',[
            'conditions' => [
                'Drivers.id IS NOT NULL',
                'Drivers.delete_status' => 'N'
            ]
        ])->count();
        //pr($totalDrivers); die();
        $deactiveDrivers = $this->Drivers->find('all',[
            'conditions' => [
                'Drivers.id IS NOT NULL',
                'Drivers.status' => '0',
                'Drivers.delete_status' => 'N'
            ]
        ])->count();
        //pr($deactiveDrivers); die();
        $activeDrivers = $this->Drivers->find('all',[
            'conditions' => [
                'Drivers.id IS NOT NULL',
                'Drivers.status' => '1',
                'Drivers.delete_status' => 'N'
            ]
        ])->count();
        //pr($activeDrivers); die();

        $orderList = $this->Orders->find('all',[
            'fields' => [
                'Orders.order_grand_total'
            ],
            'conditions' => [
                'Orders.status' => 'Delivered'
            ]
        ])->hydrate(false)->toArray();
        
        $deliveredCount = count($orderList); 
        //pr($deliveredCount); die();
        $salesPrice = 0;
        foreach ($orderList as $key => $value) {
            $salesPrice = $salesPrice + $value['order_grand_total'];
        }        

        $no_orders = $this->Orders->find('all',[            
            'conditions' => [
                'id IS NOT NULL'
            ]
        ])->count(); 


        $no_customers = $this->Orders->find('all',[
            'conditions' => [
                 'Orders.id IS NOT NULL'
            ],
            'contain' => [
                'Users'
            ],
            'group' =>[
               'Users.id'
            ]
        ])->count();   


      $this->set(compact('deliveredCount', 'salesPrice', 'no_orders', 'no_customers','totalUsers','activeUsers','deactiveUsers','totalRestaurants','deactiveRestaurants','activeRestaurants','totalDrivers','deactiveDrivers','activeDrivers'));

    }#dashboard function end...
#---------------------------------------------------------------------------------------------
    public function changepassword() {

        if($this->request->is('post')) {

            if($this->request->getData('oldPassword') != '' &&
                $this->request->getData('newPassword') != '' && 
                $this->request->getData('confirmPassword') != '') {

                $oldPassword = $this->request->getData('oldPassword');
                $newPassword = $this->request->getData('newPassword');
                $confirmPassword = $this->request->getData('confirmPassword');

                $id =  $this->Auth->user('id');
                $userDet = $this->Users->get($id);
                $password = $userDet['password'];

                if($newPassword != $confirmPassword){
                    $this->Flash->error(__('New password and confirm password mismatch'));
                    return $this->redirect(ADMIN_BASE_URL.'changepassword');
                }else if((new DefaultPasswordHasher())->check($oldPassword, $password)){
                    $custEntity = $this->Users->newEntity();
                    $userDetails = [
                        'id' => $this->Auth->user('id'),
                        'password' => $newPassword
                    ];
                    $custPatch = $this->Users->patchEntity($custEntity,$userDetails);
                    $saveCust = $this->Users->save($custPatch);
                    if($saveCust) {
                        $this->Flash->success(__('Password updated successfully'));
                        $this->Auth->logout();
                        return $this->redirect(ADMIN_BASE_URL.'changepassword');
                    }                    
                }else{                   
                    $this->Flash->error(__('Current password mismatched'));
                    return $this->redirect(ADMIN_BASE_URL.'changepassword');                  
                }                

            }else {
                $this->Flash->error(__('Error occured'));
                return $this->redirect(ADMIN_BASE_URL.'changepassword');
            }
        }
    }
#---------------------------------------------------------------------------------------------
    //Foodadmin logout 
    public function logout() {
        $this->Auth->logout();
        return $this->redirect(ADMIN_BASE_URL.'');
    }#logout function end...
#---------------------------------------------------------------------------------------------
    //Foodadmin getlocation
    public function getLocation() {

        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = @$_SERVER['REMOTE_ADDR'];
        $result  = array('country'=>'', 'city'=>'');
        if(filter_var($client, FILTER_VALIDATE_IP)){
            $ip = $client;
        }elseif(filter_var($forward, FILTER_VALIDATE_IP)){
            $ip = $forward;
        }else{
            $ip = $remote;
        }

        $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));
        if($ip_data && $ip_data->geoplugin_countryName != null){
            $result['country'] = $ip_data->geoplugin_countryCode;
            $result['city'] = $ip_data->geoplugin_city;
        }
        if(!empty($result)) {
            echo $result['country'];die();
        }else {
            echo 'IND';die();
        }

    }#getlocation function end...
#--------------------------------------------------------------------------
    public function dispatch() {

    }
#--------------------------------------------------------------------------    
     public function orderView() {
    }
#---------------------------------------------------------------------------
}#class end...