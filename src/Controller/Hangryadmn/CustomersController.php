<?php
namespace App\Controller\Hangryadmn;

use Cake\Event\Event;
use Cake\I18n\Time;
use App\Controller\AppController;

class CustomersController extends AppController{

    public function initialize(){
        parent::initialize();
        $this->viewBuilder()->setLayout('backend'); 
        $this->loadComponent('Flash');
        $this->loadComponent('Googlemap');
        $this->loadComponent('Common');

        $this->loadModel('Users');              
        $this->loadModel('AddressBooks');
        $this->loadModel('WalletHistories');
        $this->loadModel('States');
        $this->loadModel('Cities');              
        $this->loadModel('Locations');              
        $this->loadModel('SiteSettings');              
    }
  
  #------------------------------------------------------------------------------------------
  /*Customer Management*/
    public function index($process = null){

        $customerList = $this->Users->find('all', [
            'fields' => [
                'Users.id',
                'Users.first_name',
                'Users.last_name',
                'Users.phone_number',
                'Users.username',
                'Users.wallet_amount',
                'Users.status',
                'Users.created',
                'Users.referred_by',
            ],
            'conditions' => [
                'Users.id IS NOT NULL',  
                'Users.role_id' => 3,              
                'Users.deleted_status' => 'N'
            ],
            'order' => [
                'Users.id' => 'DESC'
            ]
        ])->hydrate(false)->toArray();

        if(!empty($customerList)) {
            foreach ($customerList as $cKey => $cValue) {
                if($cValue['referred_by'] != '') {
                    $referredDetails = $this->Users->find('all', [
                        'fields' => [
                            'first_name',
                            'referral_code'
                        ],
                        'conditions' => [
                            'id' => $cValue['referred_by']
                        ]
                    ])->hydrate(false)->first();

                    if(!empty($referredDetails)) {
                        $customerList[$cKey]['referred_by'] = $referredDetails['first_name'].'('.$referredDetails['referral_code'].')';
                    }else {
                        $customerList[$cKey]['referred_by'] = '-';
                    }
                }else {
                    $customerList[$cKey]['referred_by'] = '-';
                }
            }
        }

        $this->set(compact('customerList'));

        if($process == 'Customer' ){
            $value = array($customerList);
            return $value;
        }
    }  
  #------------------------------------------------------------------------------------------

    /*Customer Management*/
    public function customerIndex($id = null){

        $addressbookList = $this->AddressBooks->find('all', [
            'conditions' => [
                'AddressBooks.user_id' => $id,
                'AddressBooks.delete_status' => 'N'
            ]
        ])->hydrate(false)->toArray();
        $this->set(compact('addressbookList'));

        /*if($process == 'CustomerAddress' ){
           $value = array($addressbookList);
            return $value;
        }*/
    }
#------------------------------------------------------------------------------------------
    /*Customer Add*/
   public function add(){ 
    #echo "<pre>"; print_r($this->request->getData()); die();

     if($this->request->is(['post','put'])) {

            $postData = $this->request->getData();
            
            if (!empty($postData['first_name']) && !empty($postData['last_name']) 
                && !empty($postData['phone_number']) && !empty($postData['username'])
                 && !empty($postData['password'])) {
                
                $customerCount = $this->Users->find('all', [
                    'conditions' => [
                       'username' => trim($postData['username'])
                    ]
                ])->count();

                if (empty($customerCount)) {
                    $postData['role_id'] = 3;
                    $customerEntity = $this->Users->newEntity($postData);
                    if ($this->Users->save($customerEntity)) {
                        $this->Flash->success(__("Customer details inserted successfully"));
                       return $this->redirect(ADMIN_BASE_URL.'customers/index'); 
                    }
                } else {
                     $this->Flash->error(__("Customer email already exist"));
                }

            } else {
                $this->Flash->error(__("Please enter all details"));
            }
        }        
   }   
  #------------------------------------------------------------------------------------------
   /*Customer Edit*/
   public function edit($id = null){ 

    $customerList = [];
        if(!empty($id)) {
            $customerList = $this->Users->get($id);
        }

        if($this->request->is(['post','put'])) {
            
            $postData = $this->request->getData();
            
            if (!empty($postData['first_name']) && !empty($postData['last_name']) 
                && !empty($postData['phone_number']) && !empty($postData['username'])
                 && !empty($postData['editid'])) {

                if (!empty($customerList)) {
                    
                    $customerCount = $this->Users->find('all', [
                        'conditions' => [
                            'id !=' => $id,
                            'username' => trim($postData['username'])
                        ]
                    ])->first();

                    if (empty($customerCount)) {
                        
                        $customerEntity = $this->Users->patchEntity($customerList, $postData);
                        if ($this->Users->save($customerEntity)) {
                            $this->Flash->success(__("Customer details updated successfully"));
                            return $this->redirect(ADMIN_BASE_URL.'customers/index');                           
                        }
                    } else {
                        $this->Flash->error(__("Customer email already exist"));
                    }
                
                } 
            } else {
                $this->Flash->error(__("Please enter all details"));
            }
        }       
        $this->set(compact("customerList",'id'));
       
   }  
  #------------------------------------------------------------------------------------------
   /*Customer Address Book*/
   public function customerAddressbook($id = null){ 

    $addressbookList = [];
        if(!empty($id)) {
            $addressbookList = $this->AddressBooks->get($id);
        }

        if($this->request->is(['post','put'])) {
            
            $postData = $this->request->getData();
            
            if (!empty($postData['title']) && !empty($postData['flat_no']) 
                && !empty($postData['address'])  && !empty($postData['editid'])) {
                if (!empty($addressbookList)) {
                    
                    $customerAddressCount = $this->AddressBooks->find('all', [
                        'conditions' => [
                            'id !=' => $id,
                            'title' => trim($postData['title'])
                        ]
                    ])->first();

                    if (empty($customerAddressCount)) {
                        
                         //Get Latitude and Longitude
                        $prepAddr = str_replace(' ','+',$this->request->getData('address'));

                        $url = "https://maps.google.com/maps/api/geocode/json?address=$prepAddr&key=AIzaSyA_PDTRdxnfHvK3V6-pApjZQgY8F8E5zOM&sensor=false&region=India";
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                        $response = curl_exec($ch);
                        curl_close($ch);
                        $response_a = json_decode($response);
                        $sourcelatitude = $response_a->results[0]->geometry->location->lat;
                        $sourcelongitude = $response_a->results[0]->geometry->location->lng;

                        //$customerAddressEntity->latitude = $sourcelatitude;
                        //$customerAddressEntity->longitude = $sourcelongitude;
                        $customerAddressEntity = $this->AddressBooks->patchEntity($addressbookList, $postData);
                        if ($this->AddressBooks->save($customerAddressEntity)) {
                            $this->Flash->success(__("Customer Address Book details updated successfully"));
                            return $this->redirect(ADMIN_BASE_URL.'customers/customerIndex');                           
                        }
                    } else {
                        $this->Flash->error(__("Customer Address Book Title already exist"));
                    }
                
                } 
            } else {
                $this->Flash->error(__("Please enter all details"));
            }
        }
        $stateList = $this->States->find('list', [
                        'keyField' => 'id',
                        'valueField' => 'state_name',
                        'conditions' => [
                            'States.country_id !=' => $this->siteSettings['site_country']
                        ]
                    ])->hydrate(false)->toArray();

        $cityList = $this->Cities->find('list', [
                        'keyField' => 'id',
                        'valueField' => 'city_name',
                        'conditions' => [
                            'Cities.state_id !=' => $addressbookList['CustomerAddressBook']['state_id']
                        ]
                    ])->hydrate(false)->toArray();
        
        if (SEARCHBY == 'zip') {
            $locationList = $this->Locations->find('list', [
                        'keyField' => 'id',
                        'valueField' => 'zip_code',
                        'conditions' => [
                            'Locations.city_id !=' => $addressbookList['CustomerAddressBook']['city_id']
                        ]
                    ])->hydrate(false)->toArray();
        } else {
            $locationList = $this->Locations->find('list', [
                        'keyField' => 'id',
                        'valueField' => 'area_name',
                        'conditions' => [
                            'Locations.city_id !=' => $addressbookList['CustomerAddressBook']['city_id']
                        ]
                    ])->hydrate(false)->toArray();
        }       
        $this->set(compact("addressbookList",'id', 'locationList', 'cityList', 'stateList'));
   }  
  #------------------------------------------------------------------------------------------
   /*Checking Name already exist*/    
    public function customerCheck() { 
              
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
    #------------------------------------------------------------------------------------------
    /*Checking Name already exist*/
    public function customerAddressCheck() {

        if($this->request->getData('title') != '') {
            if($this->request->getData('id') != '') {
                $conditions = [
                    'id !=' => $this->request->getData('id'),
                    'title' => $this->request->getData('title'),
                ];
            }else {
                $conditions = [
                    'title' => $this->request->getData('title'),
                ];
            }
            $customerCount = $this->AddressBooks->find('all', [
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
  #-------------------------------------------------------------------------------------------
   /*Customer Status Change*/
    public function ajaxaction() {

        if($this->request->is('ajax')){
            if($this->request->getData('action') == 'customerStatusChange'){

                $customer         = $this->Users->newEntity();
                $customer         = $this->Users->patchEntity($customer,$this->request->getData());
                $customer->id     = $this->request->getData('id');
                $customer->status = $this->request->getData('changestaus');
                $this->Users->save($customer);

                $this->set('id', $this->request->getData('id'));
                $this->set('action', 'customerStatusChange');
                $this->set('field', $this->request->getData('field'));
                $this->set('status', (($this->request->getData('changestaus') == 0) ? 'deactive' : 'active'));
            } elseif($this->request->getData('action') == 'customerAddressStatusChange'){

                $customer         = $this->Users->newEntity();
                $customer         = $this->Users->patchEntity($customer,$this->request->getData());
                $customer->id     = $this->request->getData('id');
                $customer->status = $this->request->getData('changestaus');
                $this->AddressBooks->save($customer);

                $this->set('id', $this->request->getData('id'));
                $this->set('action', 'customerAddressStatusChange');
                $this->set('field', $this->request->getData('field'));
                $this->set('status', (($this->request->getData('changestaus') == 0) ? 'deactive' : 'active'));
            }
        }
    }
  #-------------------------------------------------------------------------------------------
     /*Customer Delete*/
    public function deleteCustomer($id = null){

        if($this->request->is('ajax')){
            if($this->request->getData('action') == 'Customer' 
                 && $this->request->getData('id') != ''){

                $cust         = $this->Users->newEntity();
                $cust         = $this->Users->patchEntity($cust,$this->request->getData());
                $cust->id     = $this->request->getData('id');
                $cust->deleted_status = 'Y';
                $this->Users->save($cust);

                list($customerList) = $this->index('Customer');
                if($this->request->is('ajax')) {
                    $action    = 'Customer';
                    $this->set(compact('action', 'customerList'));
                    $this->render('ajaxaction');
                }
            } elseif($this->request->getData('action') == 'CustomerAddress'
                && $this->request->getData('id') != ''){

                $custAdd         = $this->AddressBooks->newEntity();
                $custAdd         = $this->AddressBooks->patchEntity($custAdd,$this->request->getData());
                $custAdd->id     = $this->request->getData('id');
                $custAdd->delete_status = 'Y';
                $this->AddressBooks->save($custAdd);
                echo 'success'; die();
                //$id = $this->request->getData('id');
                /*list($addressbookList) = $this->customerIndex($id,'CustomerAddress');
                if($this->request->is('ajax')) {
                    $action    = 'CustomerAddress';
                    $this->set(compact('action', 'addressbookList'));
                    $this->render('ajaxaction');
                }*/
            }
        }
    }  
    public function cityFillter() {
        $id         = $this->request->data['id'];
        $cityList = $this->Cities->find('list', [
                            'keyField' => 'id',
                            'valueField' => 'city_name',
                            'conditions' => [
                                'City.state_id !=' => $id
                            ]
                        ])->hydrate(false)->toArray();
    }
    public function locationFillter() {
        $id         = $this->request->data['id'];
        $cityList = $this->Cities->find('list', [
                            'keyField' => 'id',
                            'valueField' => 'city_name',
                            'conditions' => [
                                'City.state_id !=' => $id
                            ]
                        ])->hydrate(false)->toArray();
    }
 #-------------------------------------------------------------------------------------------
    /*Add Money Section*/
    public function addMoney() {

        $customerDetails = $this->Users->find('all', [
            'conditions' => [
                'id' => $this->request->getData('id')
            ]
        ])->hydrate(false)->first();

        if(!empty($customerDetails)) {
            $customerWallet['id'] = $this->request->getData('id');
            $customerWallet['wallet_amount'] = $customerDetails['wallet_amount'] + $this->request->getData('amount');

            $customerWalletPatch = $this->Users->newEntity($customerWallet);
            $this->Users->save($customerWalletPatch);

            // Wallet History
            $walletHistory['amount']              = $this->request->getData('amount');
            $walletHistory['purpose']             = $this->request->getData('purpose');
            $walletHistory['customer_id']         = $this->request->getData('id');
            $walletHistory['transaction_type']    = 'Credited';
            $walletHistory['transaction_details'] = rand();

            $walletHistoryPatch = $this->WalletHistories->newEntity($walletHistory);
            $this->WalletHistories->save($walletHistoryPatch);
            echo '1';die();
        }else{
            echo '0';die();
        }
    }
 //----------------------------------------------------------------------------------------------------
}#class end...