<?php
namespace App\Controller\Restaurantadmin;

use Cake\Event\Event;
use Cake\I18n\Time;
use App\Controller\AppController;

class DriversController extends AppController{

    public function initialize(){
        parent::initialize();
        $this->viewBuilder()->setLayout('restaurant');
        $this->loadComponent('Flash');
        $this->loadComponent('FcmNotification');
        $this->loadComponent('IosNotification');
        $this->loadComponent('Common');
        $this->loadModel('Users');
        $this->loadModel('Restaurants');
        $this->loadModel('Drivers');
        $this->loadModel('DriverTrackings');
        $this->loadModel('DriverInvoices');
        $this->loadModel('Orders');
    }
  
  #------------------------------------------------------------------------------------------
  /*Drivers Management*/
    public function index($process = null){

        $userId = $this->Auth->User();          
        
        $driverList = $this->Drivers->find('all', [
            'fields' => [
                'Drivers.id',
                'Drivers.driver_name',
                'Drivers.vechile_name',
                'Drivers.phone_number',
                'Drivers.username',
                'Drivers.payout',
                'Drivers.status',
                'Drivers.is_logged',
                'Drivers.created'                
            ],
            'conditions' => [
                'Drivers.id IS NOT NULL',  
                'Drivers.delete_status' => 'N',
                'Drivers.created_id'  => $userId['id']
            ]
        ])->hydrate(false)->toArray();
        $this->set(compact('driverList'));        
        if($process == 'Driver' ){
            $value = array($driverList);
            return $value;
        }
    }       
#------------------------------------------------------------------------------------------
    /*Drivers Add*/
   public function add(){  

    $userId = $this->Auth->User();        

     if($this->request->is(['post','put'])) {

            $postData = $this->request->getData();
            
            if (!empty($postData['driver_name']) && !empty($postData['phone_number']) 
                && !empty($postData['password']) && !empty($postData['username'])
                 && !empty($postData['vechile_name'])) {
                
                $usersCount = $this->Users->find('all', [
                    'conditions' => [                      
                       'username' => trim($postData['phone_number'])
                    ]
                ])->count();

                if (empty($usersCount)) { 
                    $userData = [];  
                    $userData['role_id'] = '5';
                    $userData['username'] = $postData['phone_number'];
                    $userData['password'] = $postData['password'];
                    $userEntity  = $this->Users->newEntity($userData);  
                    $userSave    = $this->Users->save($userEntity);

                    $postData['created_id'] =  $this->Auth->user('id');
                    $postData['user_id'] =  $userSave->id;
                    $postData['status'] = '1';
                    $driverEntity = $this->Drivers->newEntity($postData);
                    if ($this->Drivers->save($driverEntity)) {
                        $this->Flash->success(__("Drivers details inserted successfully"));
                       return $this->redirect(REST_BASE_URL.'drivers/index'); 
                    }
                } else {
                     $this->Flash->error(__("Drivers Phone number already exist"));
                }

            } else {
                $this->Flash->error(__("Please enter all details"));
            }
        }        
   }   
  #------------------------------------------------------------------------------------------
   /*Driver Edit*/
   public function edit($id = null){ 
        
        $driverList = [];
        if(!empty($id)) {
            $driverList = $this->Drivers->get($id);
        }        

        if($this->request->is(['post','put'])) {
            
            $postData = $this->request->getData(); 

            if (!empty($postData['driver_name']) && !empty($postData['phone_number']) 
                 && !empty($postData['username']) && !empty($postData['vechile_name'])) {
           
                if (!empty($driverList)) {

                    $userId= $this->Drivers->find('all', [
                        'fields' => [
                            'user_id'
                        ],
                        'conditions' => [
                            'id' => $this->request->getData('editid')
                        ]
                   ])->hydrate(false)->first();
                    

                    $usersCount = $this->Users->find('all', [
                        'conditions' => [
                            'id !=' => trim($userId['user_id']),
                           'username' => trim($postData['phone_number'])
                        ]
                    ])->first();   

                    if (empty($usersCount)) { 

                        $userData = [];  
                        $userData['role_id'] = '5';
                        $userData['username'] = $postData['phone_number']; 
                        $userData['id'] = $userId['user_id'];                         

                        $userEntity  = $this->Users->newEntity($userData); 
                        $userSave    = $this->Users->save($userEntity);

                        if(!empty($postData['editid'])) {
                            $postData['id'] = $postData['editid'];                           
                        }
                        
                        $driverEntity = $this->Drivers->patchEntity($driverList, $postData);
                        if ($this->Drivers->save($driverEntity)) {
                            $this->Flash->success(__("Drivers details updated successfully"));
                            return $this->redirect(REST_BASE_URL.'drivers/index');                           
                        }
                    } else {
                        $this->Flash->error(__("Drivers Phone number already exist"));
                    }
                
                } 
            } else {
                $this->Flash->error(__("Please enter all details"));
            }
        }       
        $this->set(compact("driverList",'id'));       
   } 
  #------------------------------------------------------------------------------------------
   /*Checking Phone Number already exist*/    
    public function driverCheck() {        
              
        if($this->request->getData('phone_number') != '') {            

            if($this->request->getData('id') != '') {

                $userId= $this->Drivers->find('all', [
                    'fields' => [
                        'user_id'
                    ],
                    'conditions' => [
                        'id' => $this->request->getData('id')
                    ]
                ])->hydrate(false)->first();  

                $conditions = [
                    'id !=' => $userId['user_id'],
                    'username' => $this->request->getData('phone_number')              
                ];
            }else {
                $conditions = [
                    'username' => $this->request->getData('phone_number')                  
                ];
            }

            $usersCount = $this->Users->find('all', [
                'conditions' => $conditions
            ])->count(); 
         
            if($usersCount == 0) {
                echo '0';
            }else {
                echo '1';
            }
            die();
        }
    }
  #-------------------------------------------------------------------------------------------
   /*Drivers Status Change*/
    public function ajaxaction() {

        if($this->request->is('ajax')){
            if($this->request->getData('action') == 'driverStatusChange'){
                $driver         = $this->Drivers->newEntity();
                $driver         = $this->Drivers->patchEntity($driver,$this->request->getData());
                $driver->id     = $this->request->getData('id');
                $driver->status = $this->request->getData('changestaus');
                $this->Drivers->save($driver);

                $this->set('id', $this->request->getData('id'));
                $this->set('action', 'driverStatusChange');
                $this->set('field', $this->request->getData('field'));
                $this->set('status', (($this->request->getData('changestaus') == 0) ? 'deactive' : 'active'));
            }

            if($this->request->getData('action') == 'driverLoginStatus'){
                $login         = $this->Drivers->newEntity();
                $login         = $this->Drivers->patchEntity($login,$this->request->getData());
                $login->id     = $this->request->getData('id');
                $login->is_logged = $this->request->getData('changestaus');
                //$this->Drivers->save($login);

                $driverDetails = $this->Drivers->find('all', [
                    'conditions' => [
                        'Drivers.id' => $this->request->getData('id')
                    ],
                    'contain' => [
                        'DriverTrackings'
                    ]
                ])->hydrate(false)->first();


                //pr($driverDetails);

                if($driverDetails['is_logged'] == 1) {
                    $notificationdata['data']['title']          = "logout";
                    $notificationdata['data']['message']        = 'logout';
                    $notificationdata['data']['is_background']  = false;
                    $notificationdata['data']['payload']        = array('OrderDetails' => "",'type'    => "logout");
                    $notificationdata['data']['timestamp']      = date('Y-m-d G:i:s');
                    $gcm                                = (trim($driverDetails['device_name']) == 'ANDROID') ? $this->FcmNotification->sendNotification($notificationdata,$driverDetails['device_id']) : $this->IosNotification->notificationIOS('logout', $driverDetails['device_id']);


                    $drivers['id'] = $driverDetails['id'];
                    $drivers['is_logged'] = '0';
                    $drivers['driver_status']  = 'Offline';
                    $drivers['device_id']      = '';
                    $drivers['login_time']      = '';

                    $this->DriverTrackings->deleteAll([
                        'id' => $driverDetails['driver_tracking']['id']
                    ]);

                    $driverEntity = $this->Drivers->newEntity($drivers);
                    $driverLogout = $this->Drivers->save($driverEntity);

                    $message = $driverDetails['driver_name']." loggedout";
                    //$this->Notifications->pushNotification($message, 'FoodOrderAdmin');
                    //$this->Notifications->pushNotification($message, 'Restaurant_'.$driverDetails['store_id']);
                }

                $this->set('id', $this->request->getData('id'));
                $this->set('action', 'driverLoginStatus');
                $this->set('field', $this->request->getData('field'));
                $this->set('is_logged', (($this->request->getData('changestaus') == 0) ? 'deactive' : 'active'));
            }
        }
    }
  #-------------------------------------------------------------------------------------------
     /*Driver Delete*/
    public function deleteDriver($id = null){

        if($this->request->is('ajax')){
            if($this->request->getData('action') == 'Driver' 
                 && $this->request->getData('id') != ''){
                
                $driver       = $this->Drivers->newEntity();
                $driver       = $this->Drivers->patchEntity($driver,$this->request->getData());
                $driver->id   = $this->request->getData('id');
                $driver->delete_status = 'Y';
                $this->Drivers->save($driver);

                list($driverList) = $this->index('Driver');
                if($this->request->is('ajax')) {
                    $action    = 'Driver';
                    $this->set(compact('action', 'driverList'));
                    $this->render('ajaxaction');
                }
            }
        }
    }
#-------------------------------------------------------------------------------------------
    /*Driver's Billing Management*/
    public function billing($id = null,$process = null) {

        $invoiceDetails = $this->DriverInvoices->find('all', [
            'conditions' => [
                'driver_id' => $id
            ]
        ])->hydrate(false)->toArray();

        $this->set(compact('invoiceDetails'));

        if($process == 'Driver'){
            $value = array($invoiceDetails);
            return $value;
        }

    }


    /*Driver's Billing Details*/
    public function billingDetails($id = null) {

        $invoiceDetails = $this->DriverInvoices->find('all', [
            'conditions' => [
                'id' => $id
            ]
        ])->hydrate(false)->first();
        //pr($invoiceDetails);
        $orderDetails = $this->Orders->find('all', [
            'conditions' => [
                'driver_invoice_number' => $invoiceDetails['invoice_number']
            ]

        ])->hydrate(false)->toArray();

        if(!empty($orderDetails)) {
            foreach ($orderDetails as $oKey => $oValue) {
                if($oValue['payout_type'] == 'distance') {
                    $orderDetails[$oKey]['distance_amount'] = $oValue['payout_amount'] * $oValue['distance'];
                }else {
                    $orderDetails[$oKey]['distance_amount'] = $oValue['payout_amount'];
                }
            }
        }
        //pr($orderDetails);die();
        $this->set(compact('invoiceDetails','orderDetails'));

    }

    /*Driver's Change Invoice Status*/
    public function invoiceStatus() {

        $driver       = $this->DriverInvoices->newEntity();
        $driver       = $this->DriverInvoices->patchEntity($driver,$this->request->getData());
        $driver->id   = $this->request->getData('id');
        $this->DriverInvoices->save($driver);

        list($invoiceDetails) = $this->billing($this->request->getData('id'),'Driver');
        //pr($invoiceDetails);die();
        if($this->request->is('ajax')) {
            $action    = 'Billing';
            $this->set(compact('action', 'invoiceDetails'));
            $this->render('ajaxaction');
        }



    }
 #-------------------------------------------------------------------------------------------
}#class end...