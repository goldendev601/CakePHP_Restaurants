<?php
/**
 * Created by PhpStorm.
 * User: Sundar
 * Date: 17-01-2018
 * Time: 10:23
 */
namespace App\Controller;

use Cake\Auth\DefaultPasswordHasher;
use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Utility\Hash;



class CustomersController extends AppController
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('Auth');
        $this->viewBuilder()->setLayout('frontend');

        $this->loadModel('Carts');
        $this->loadModel('AddressBooks');
        $this->loadModel('Restaurants');
        $this->loadModel('StripeCustomers');
        $this->loadModel('WalletHistories');
        $this->loadModel('Orders');
        $this->loadModel('Cities');
        $this->loadModel('Locations');
        $this->loadModel('Reviews');
        $this->loadModel('CustomerPoints');
        $this->loadModel('Referrals');
        $this->loadModel('Rewards');
        $this->loadComponent('Common');
    }
    public function beforeFilter(Event $event)
    {

        if($this->Auth->user()){
            $this->set('logginUser', $this->Auth->user());
        }else {
            $this->set('logginUser', '');
        }
        // Before Login , these are the function we can access
        $this->Auth->allow([
            'refer'
        ]);
    }
    //-----------------------------------------Get Customer's All Details-----------------------------------------------
    public function index() {
        //pr($this->Auth->user());die();

         $walletHistoryList = $this->WalletHistories->find('all', [
            'conditions' => [
                'customer_id' => $this->Auth->user('id'),
                'id IS NOT NULL'                
            ]
        ])->hydrate(false)->toArray();
         //echo "<pre>";print_r($walletHistoryList);die;

        if($this->request->is('post')) {
            //pr($this->request->getData());die();
            if($this->request->getData('action') == 'usernameUpdate') {
                //pr($this->request->getData());die();
                $cusEntity = $this->Users->newEntity();
                $cusPatch = $this->Users->patchEntity($cusEntity,$this->request->getData());
                $cusPatch->id = $this->Auth->user('id');
                $saveCust = $this->Users->save($cusPatch);
                if($saveCust) {
                    $this->Flash->success('Username Update successfull');
                    return $this->redirect(BASE_URL.'customers');
                }
            }
         //-----------------------------------------------------------------------
            if($this->request->getData('action') == 'passwordUpdate') {
                //pr($this->request->getData());die();
                //$userDetails = [];
                $id =  $this->Auth->user('id');
                $userDet = $this->Users->get($id);
                $password = $userDet['password'];
                $oldPassword = $this->request->getData('oldPassword');
                $newPassword = $this->request->getData('newPassword');
                if ((new DefaultPasswordHasher())->check($oldPassword, $password)) {
                    $custEntity = $this->Users->newEntity();
                    $userDetails = [
                        'id' => $this->Auth->user('id'),
                        'password' => $newPassword
                    ];
                    $custPatch = $this->Users->patchEntity($custEntity,$userDetails);
                    $saveCust = $this->Users->save($custPatch);
                    if($saveCust) {
                        $this->Flash->success('Password Update successfully');
                        $this->Auth->logout();
                        return $this->redirect(BASE_URL.'customers');
                    }
                } else {
                    $this->Flash->error('Old password mismatch');
                }

            }
        //-----------------------------------------------------------------------
            if($this->request->getData('action') == 'addCard') {
                $custEnty = $this->StripeCustomers->newEntity();
                $custPatch = $this->StripeCustomers->patchEntity($custEnty,$this->request->getData());
                $custPatch->customer_id = $this->Auth->user('id');
                $custPatch->customer_name = $this->Auth->user('first_name');
                $saveCust = $this->StripeCustomers->save($custPatch);
                if($saveCust) {
                    $this->Flash->success('Card added successfull');
                    return $this->redirect(BASE_URL.'customers');
                }
            }
        //-----------------------------------------------------------------------
            if($this->request->getData('action') == 'profileUpdate') {

                $userId   = $this->Auth->user('id');
                $imageOld = $this->Users->get($userId);               

                $userEntity = $this->Users->newEntity();
                $userPatch  = $this->Users->patchEntity($userEntity,$this->request->getData());
                $userPatch->id = $userId;               

               /*if(!empty($this->request->getData('image.name')))
                {                   
                   $imageArr  = $this->request->getData('image');                    
                   $valid     = getimagesize($_FILES['image']['tmp_name']);
                   
                   $filePart  = pathinfo($imageArr['name']);
                   $fileType  = ['jpg','jpeg','gif','png']; 
                  
                   if( $imageArr['error'] == 0 && $imageArr['size'] > 0 &&
                    in_array(strtolower($filePart['extension']),$fileType) && !empty($valid) ){ 
                   
                        require_once(ROOT . DS . 'vendor' . DS . 'Cloudinary' . DS . 'Cloudinary.php');
                        require_once(ROOT . DS . 'vendor' . DS . 'Cloudinary' . DS . 'Uploader.php');
                        require_once(ROOT . DS . 'vendor' . DS . 'Cloudinary' . DS . 'Api.php');

                        $logoName = 'image';

                        \Cloudinary::config(array(
                            "cloud_name" => "dntzmscli",
                            "api_key" => "213258421718748",
                            "api_secret" => "vTrAbTdKHswpiOQZcHvCv9LqZ3M"
                        ));

                        $data = \Cloudinary\Uploader::upload($_FILES["image"]["tmp_name"],
                        array(
                            "public_id" => $logoName,
                            "tags" => array("special", "for_homepage")
                        ));
                        $userPatch->image = $data['secure_url']; 

                   } else{
                        if(!empty($imageOld['image'])){
                           $userPatch->image = $imageOld['image'];  
                        }
                   }                      
                }else{
                    if(!empty($imageOld['image'])){
                       $userPatch->image = $imageOld['image'];  
                    }
                }*/

                $userDetails = $this->Users->find('all', [
                    'conditions' => [
                        'id' => $this->Auth->user('id')
                    ]
                ])->hydrate(false)->first();              
               
                $saveUsers = $this->Users->save($userPatch);   
                if($saveUsers) {
                    $this->Flash->success('profile details Updated successfull');
                    return $this->redirect(BASE_URL.'customers');
                }
            }
        //-----------------------------------------------------------------------
            if($this->request->getData('action') == 'reviewUpdate') {

                $postData = $this->request->getData('data')['reviews'];  

                if(!empty($postData['rating'])){
                      $orderInfo    = $this->Orders->get($postData['order_id']); 
                      $reviewCount  = $this->Reviews->find('all',[
                          'conditions'=>[                             
                             'Reviews.order_id'=>$postData['order_id']
                          ]
                        ])->count();

                      if($reviewCount == 0 && !empty($orderInfo)){
                             $reviews = [];  
                             $reviews['order_id'] = $orderInfo['id'];
                             $reviews['customer_id']   = $orderInfo['customer_id'];
                             $reviews['restaurant_id'] = $orderInfo['restaurant_id'];                             
                             $reviews['message']  = $postData['message'];
                             $reviews['rating']   = $postData['rating']; 
                             $reviwEntity = $this->Reviews->newEntity($reviews);
                             $saveReviews = $this->Reviews->save($reviwEntity);  
                             
                             $this->Flash->success('Thank you for your review');
                             return $this->redirect(BASE_URL.'customers');
                      }else{
                         $this->Flash->error('Review Already Exsits');
                         return $this->redirect(BASE_URL.'customers');
                      }
                }                              
            }
         //-----------------------------------------------------------------------
        }

        $userDetails = $this->Users->find('all', [
            'conditions' => [
                'id' => $this->Auth->user('id')
            ]
        ])->hydrate(false)->first();

        if($userDetails['referral_code'] == '') {
            $firstName = strtoupper($userDetails['first_name']);
            $random = mt_rand(1000, 9999);

            $updateUser['referral_code'] = $firstName.''.$random;

            $userEntity = $this->Users->newEntity();
            $userPatch  = $this->Users->patchEntity($userEntity,$updateUser);
            $userPatch->id = $userDetails['id'];
            $saveUsers = $this->Users->save($userPatch);

        }

        $customerDetails = $this->Users->find('all', [
            'conditions' => [
                'Users.id' => $this->Auth->user('id')
            ],
            'contain' => [
                'Orders' => [
                    'Restaurants' => [
                        'fields' => [
                            'Restaurants.restaurant_name',
                            'Restaurants.id',
                            'Restaurants.logo_name',
                            'Restaurants.contact_address',
                        ]
                    ],
                    'sort' => [
                        'Orders.id' => 'DESC'
                    ],
                    'Reviews',

                ],
                'AddressBooks' => [
                    'conditions' => [
                        'AddressBooks.delete_status' => 'N',
                    ],
                    'States' => [
                        'fields' => [
                            'state_name'
                        ]
                    ],
                    'Cities'=> [
                        'fields' => [
                            'city_name'
                        ]
                    ],
                    'Locations'=> [
                        'fields' => [
                            'area_name',
                            'zip_code'
                        ]
                    ]
                ],
                'StripeCustomers'
            ]
        ])->hydrate(false)->first();

        if($customerDetails['referral_code'] != '') {
            $customerDetails['referral_url'] = BASE_URL.'users/signup/'.$customerDetails['referral_code'];
        }

        //Get Referred Customer by You
        $referredList = $this->Users->find('all', [
            'fields' => [
                'first_name',
                'last_name',
                'username',
                'created'
            ],
            'conditions' => [
                'referred_by' => $this->Auth->user('id')
            ]
        ])->hydrate(false)->toArray();


        $customerPoints = $this->CustomerPoints->find('all', [
            'conditions' => [
                'CustomerPoints.customer_id' => $this->Auth->user('id')
            ],
            'contain' => [
                'Orders' => [
                    'fields' => [
                        'Orders.order_number',
                        'Orders.reward_offer',
                    ]
                ]
            ],
            'order' => [
                'CustomerPoints.id' => 'DESC'
            ]
        ])->hydrate(false)->toArray();


        $customerTotalPoints = $this->CustomerPoints->find('all', [
            'fields' => [
                'total_points' => 'SUM(points)'
            ],
            'conditions' => [
                'customer_id' => $this->Auth->user('id'),
                'status' => '1'
            ]
        ])->hydrate(false)->first();


        if($customerTotalPoints['total_points'] == '') {

            $customerTotalPoints['total_points'] = 0;
        }

        $rewardPoint = $this->Rewards->find('all', [
            'conditions' => [
                'id' => 1,
                'reward_option' => 'Yes'
            ]
        ])->hydrate(false)->first();

        $createdDate = explode(' ',$rewardPoint['created']);

        $date1=date_create($createdDate[0]);
        $date2=date_create(date('Y-m-d'));
        $diff=date_diff($date1,$date2);
        $diff = $diff->format("%R%a");

        $remainingDays = ($rewardPoint['reward_validity'] - $diff);

        if($remainingDays < 0) {
            $remainingDays = 0;
        }

        //Referral
        $referrals = $this->Referrals->find('all', [
            'conditions' => [
                'id' => 1,
                'referral_option' => 'Yes'
            ]
        ])->hydrate(false)->first();


        $this->set(compact('customerDetails','walletHistoryList','customerTotalPoints','customerPoints','remainingDays','rewardPoint','referrals','referredList'));
    }
    //-----------------------------------------Add Address Book---------------------------------------------------------
    public function addAddress() {

        if(SEARCHBY == 'Google') {

            if($this->request->getData('address') != '' && $this->request->getData('title') != '') {
                $addressCount = $this->AddressBooks->find('all', [
                    'conditions' => [
                        'title' => $this->request->getData('title'),
                        'user_id' => $this->Auth->user('id'),
                        'delete_status' => 'N'
                    ]
                ])->count();

                if($addressCount == 0) {

                    $restaurantDetails = $this->Restaurants->find('all', [
                        'conditions' => [
                            'id' => $this->request->session()->read('resid')
                        ]
                    ])->hydrate(false)->first();

                    $prepAddr = str_replace(' ','+',$this->request->getData('address'));


                    /*$geocode=file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.$prepAddr.
                        '&sensor=false');

                    $output= json_decode($geocode);*/

                    $url = "https://maps.google.com/maps/api/geocode/json?address=$prepAddr&key=AIzaSyAql4yBAyykHUGfXRicgL5_1YH9-ZeWk3s&sensor=false&region=India";
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

                    if($sourcelatitude != '' && $sourcelongitude != '') {


                        $addressEntity = $this->AddressBooks->newEntity();
                        $addressPatch = $this->AddressBooks->patchEntity($addressEntity,$this->request->getData());
                        $addressPatch->user_id = $this->Auth->user('id');
                        $addressPatch->status = '1';
                        $addressPatch->latitude = $sourcelatitude;
                        $addressPatch->longitude = $sourcelongitude;
                        $addressSave = $this->AddressBooks->save($addressPatch);
                        if($addressSave) {
                            $this->Flash->success('Address Added Successful');
                            echo '0';die();
                        }
                    }

                }else {
                    echo '2';die();
                }
            }else {
                echo '1';die();
            }
        }else {

            $addressCount = $this->AddressBooks->find('all', [
                'conditions' => [
                    'title' => $this->request->getData('title'),
                    'user_id' => $this->Auth->user('id'),
                    'delete_status' => 'N'
                ]
            ])->count();

            if($addressCount == 0) {

                $stateName = $this->States->find('all', [
                    'fields' => [
                        'state_name'
                    ],
                    'conditions' => [
                        'id' => $this->request->getData('state_id')
                    ]
                ])->hydrate(false)->first();

                $cityName = $this->Cities->find('all', [
                    'fields' => [
                        'city_name'
                    ],
                    'conditions' => [
                        'id' => $this->request->getData('city_id')
                    ]
                ])->hydrate(false)->first();

                $locationName = $this->Locations->find('all', [
                    'fields' => [
                        'area_name',
                        'zip_code'
                    ],
                    'conditions' => [
                        'id' => $this->request->getData('location_id')
                    ]
                ])->hydrate(false)->first();

                $address = $this->request->getData('street_address').','.$stateName['state_name'].','.$cityName['city_name'].','.$locationName['area_name'].','.$locationName['zip_code'];

                $prepAddr = str_replace(' ','+',$address);

                //echo $prepAddr;die();

                $url = "https://maps.google.com/maps/api/geocode/json?address=$prepAddr&key=AIzaSyAql4yBAyykHUGfXRicgL5_1YH9-ZeWk3s&sensor=false&region=India";
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

                if($sourcelatitude != '' && $sourcelongitude != '') {


                    $addressEntity = $this->AddressBooks->newEntity();
                    $addressPatch = $this->AddressBooks->patchEntity($addressEntity,$this->request->getData());
                    $addressPatch->user_id = $this->Auth->user('id');
                    $addressPatch->status = '1';
                    $addressPatch->latitude = $sourcelatitude;
                    $addressPatch->address = $this->request->getData('street_address');
                    $addressPatch->longitude = $sourcelongitude;
                    $addressSave = $this->AddressBooks->save($addressPatch);
                    if($addressSave) {
                        $this->Flash->success('Address Added Successful');
                        echo '0';die();
                    }
                }

            }else {
                echo '2';die();
            }
        }
    }
    //-----------------------------------------Edit Address Book--------------------------------------------------------
    public function editAddress() {

        if(SEARCHBY == 'Google') {
            if ($this->request->getData('address') != '' && $this->request->getData('title') != '') {
                $addressCount = $this->AddressBooks->find('all', [
                    'conditions' => [
                        'id !=' => $this->request->getData('editId'),
                        'title' => $this->request->getData('title'),
                        'user_id' => $this->Auth->user('id')
                    ]
                ])->count();

                if ($addressCount == 0) {

                    $restaurantDetails = $this->Restaurants->find('all', [
                        'conditions' => [
                            'id' => $this->request->session()->read('resid')
                        ]
                    ])->hydrate(false)->first();

                    $prepAddr = str_replace(' ', '+', $this->request->getData('address'));


                    /*$geocode=file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.$prepAddr.
                        '&sensor=false');

                    $output= json_decode($geocode);*/

                    $url = "https://maps.google.com/maps/api/geocode/json?address=$prepAddr&key=AIzaSyAql4yBAyykHUGfXRicgL5_1YH9-ZeWk3s&sensor=false&region=India";
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

                    if ($sourcelatitude != '' && $sourcelongitude != '') {


                        $addressEntity = $this->AddressBooks->newEntity();
                        $addressPatch = $this->AddressBooks->patchEntity($addressEntity, $this->request->getData());
                        $addressPatch->user_id = $this->Auth->user('id');
                        $addressPatch->status = '1';
                        $addressPatch->latitude = $sourcelatitude;
                        $addressPatch->longitude = $sourcelongitude;
                        $addressPatch->id = $this->request->getData('editId');
                        $addressSave = $this->AddressBooks->save($addressPatch);
                        if ($addressSave) {
                            $this->Flash->success('Address Update Successful');
                            echo '0';
                            die();
                        }
                    }

                } else {
                    echo '2';
                    die();
                }
            } else {
                echo '1';
                die();
            }
        }else {

            $addressCount = $this->AddressBooks->find('all', [
                'conditions' => [
                    'id !=' => $this->request->getData('editId'),
                    'title' => $this->request->getData('title'),
                    'user_id' => $this->Auth->user('id')
                ]
            ])->count();

            if($addressCount == 0) {

                $stateName = $this->States->find('all', [
                    'fields' => [
                        'state_name'
                    ],
                    'conditions' => [
                        'id' => $this->request->getData('state_id')
                    ]
                ])->hydrate(false)->first();

                $cityName = $this->Cities->find('all', [
                    'fields' => [
                        'city_name'
                    ],
                    'conditions' => [
                        'id' => $this->request->getData('location_id')
                    ]
                ])->hydrate(false)->first();

                $locationName = $this->Locations->find('all', [
                    'fields' => [
                        'area_name',
                        'zip_code'
                    ],
                    'conditions' => [
                        'id' => $this->request->getData('city_id')
                    ]
                ])->hydrate(false)->first();

                $address = $this->request->getData('street_address').','.$stateName['state_name'].','.$cityName['city_name'].','.$locationName['area_name'].','.$locationName['zip_code'];

                $prepAddr = str_replace(' ','+',$address);

                //echo $prepAddr;die();

                $url = "https://maps.google.com/maps/api/geocode/json?address=$prepAddr&key=AIzaSyAql4yBAyykHUGfXRicgL5_1YH9-ZeWk3s&sensor=false&region=India";
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

                if($sourcelatitude != '' && $sourcelongitude != '') {


                    $addressEntity = $this->AddressBooks->newEntity();
                    $addressPatch = $this->AddressBooks->patchEntity($addressEntity,$this->request->getData());
                    $addressPatch->user_id = $this->Auth->user('id');
                    $addressPatch->status = '1';
                    $addressPatch->latitude = $sourcelatitude;
                    $addressPatch->address = $this->request->getData('street_address');
                    $addressPatch->longitude = $sourcelongitude;
                    $addressPatch->id = $this->request->getData('editId');
                    $addressSave = $this->AddressBooks->save($addressPatch);
                    if($addressSave) {
                        $this->Flash->success('Address Added Successful');
                        echo '0';die();
                    }
                }

            }else {
                echo '2';die();
            }

        }
    }
    //-----------------------------------------Ajaxaction Functionality--------------------------------------------------------
    public function ajaxaction() {
        if($this->request->getData('action') == 'editAddress') {

            $addressDetails = $this->AddressBooks->find('all', [
                'conditions' => [
                    'id' => $this->request->getData('id')
                ]
            ])->hydrate(false)->first();

            if(SEARCHBY != 'Google') {
                $citylist = $this->Cities->find('list', [
                    'keyField' => 'id',
                    'valueField' => 'city_name',
                    'conditions' => [
                        'status' => '1',
                        'state_id' => $addressDetails['state_id']
                    ],
                ])->hydrate(false)->toArray();

                if(SEARCHBY == 'area'){
                    $locaionlist = $this->Locations->find('list', [
                        'keyField' => 'id',
                        'valueField' => 'area_name',
                        'conditions' => [
                            'status' => '1',
                            'city_id' => $addressDetails['city_id']
                        ],
                    ])->hydrate(false)->toArray();
                }
                if(SEARCHBY == 'zip') {
                    $locaionlist = $this->Locations->find('list', [
                        'keyField' => 'id',
                        'valueField' => 'zip_code',
                        'conditions' => [
                            'status' => '1',
                            'city_id' => $addressDetails['city_id']
                        ],
                    ])->hydrate(false)->toArray();
                }

            }
            $action = $this->request->getData('action');
            $id = $this->request->getData('id');
            $this->set(compact('action','addressDetails','id','locaionlist','citylist'));

        }

        #--------------------------------------------------------------
        if ($this->request->getData('action') == 'getCity') {
            $citylist = $this->Cities->find('list', [
                'keyField' => 'id',
                'valueField' => 'city_name',
                'conditions' => [
                    'status' => '1',
                    'state_id' => $this->request->getData('state_id')
                ],
            ])->hydrate(false)->toArray();

            $this->set('action', $this->request->getData('action'));
            $this->set('citylist', $citylist);
        }
        #--------------------------------------------------------------
        if ($this->request->getData('action') == 'getLocation') {
            if(SEARCHBY == 'area'){
                $locaionlist = $this->Locations->find('list', [
                    'keyField' => 'id',
                    'valueField' => 'area_name',
                    'conditions' => [
                        'status' => '1',
                        'city_id' => $this->request->getData('city_id')
                    ],
                ])->hydrate(false)->toArray();
            }
            if(SEARCHBY == 'zip') {
                $locaionlist = $this->Locations->find('list', [
                    'keyField' => 'id',
                    'valueField' => 'zip_code',
                    'conditions' => [
                        'status' => '1',
                        'city_id' => $this->request->getData('city_id')
                    ],
                ])->hydrate(false)->toArray();
            }
            $this->set('action', $this->request->getData('action'));
            $this->set('locaionlist', $locaionlist);
        }
        #--------------------------------------------------------------
        if ($this->request->getData('action') == 'editgetCity') {
            $citylist = $this->Cities->find('list', [
                'keyField' => 'id',
                'valueField' => 'city_name',
                'conditions' => [
                    'status' => '1',
                    'state_id' => $this->request->getData('state_id')
                ],
            ])->hydrate(false)->toArray();

            $this->set('action', $this->request->getData('action'));
            $this->set('citylist', $citylist);
        }
        #--------------------------------------------------------------
        if ($this->request->getData('action') == 'editgetLocation') {
            if(SEARCHBY == 'area'){
                $locaionlist = $this->Locations->find('list', [
                    'keyField' => 'id',
                    'valueField' => 'area_name',
                    'conditions' => [
                        'status' => '1',
                        'city_id' => $this->request->getData('city_id')
                    ],
                ])->hydrate(false)->toArray();
            }
            if(SEARCHBY == 'zip') {
                $locaionlist = $this->Locations->find('list', [
                    'keyField' => 'id',
                    'valueField' => 'zip_code',
                    'conditions' => [
                        'status' => '1',
                        'city_id' => $this->request->getData('city_id')
                    ],
                ])->hydrate(false)->toArray();
            }
            $this->set('action', $this->request->getData('action'));
            $this->set('locaionlist', $locaionlist);
        }

        if($this->request->getData('action') == 'orderStatus') {
            if($this->request->getData('id') != '') {
                $orderDetails = $this->Orders->find('all', [
                    'conditions' => [
                        'order_number' => $this->request->getData('id'),
                        'customer_id' => $this->Auth->user('id')
                    ],
                    'contain' => [
                        'Restaurants'
                    ]
                ])->hydrate(false)->first();

                $this->set('action', $this->request->getData('action'));
                $this->set('orderDetails', $orderDetails);
            }
        }
    }
    //-----------------------------------------Delete Address Book--------------------------------------------------------
    public function deleteAddress() {
        $id     = $this->request->getData('id');
        $entity = $this->AddressBooks->get($id);
        $result = $this->AddressBooks->delete($entity);
        echo '0';die();
    }
    //-----------------------------------------Delete Stripe Card--------------------------------------------------------
    public function deleteCard() {
        $id     = $this->request->getData('id');
        $entity = $this->StripeCustomers->get($id);
        $result = $this->StripeCustomers->delete($entity);
        echo '0';die();
    }
    //-----------------------------------------Add Money Functionality -------------------------------------------------

    public function addMoney() {
        if($this->request->getData('amount') != '' && $this->request->getData('cardId') != '') {

            //pr($this->Auth->user());die();
            require_once(ROOT . DS . 'vendor' . DS . 'stripe' . DS . 'init.php');

            \Stripe\Stripe::setApiKey(STRIPE_SECRET);

            $cardDetails = $this->StripeCustomers->find('all', [
                'conditions' => [
                    'id' => $this->request->getData('cardId')
                ]
            ])->hydrate(false)->first();

            $siteSettings = $this->Sitesettings->find('all', [
                'conditions' => [
                    'id' => '1'
                ]
            ])->hydrate(false)->first();


            // Token is created using Checkout or Elements!
            // Get the payment token ID submitted by the form:
            //$token = $_POST['stripeToken'];
            $payAmt = $this->request->getData('amount')*100;

            // Charge the user's card:
            if($cardDetails['stripe_customer_id'] == '') {

                // Create a Customer:
                $customer = \Stripe\Customer::create(array(
                    "email" => $this->Auth->user('username'),
                    "source" => $cardDetails['stripe_token_id'],
                ));

                // Charge the Customer instead of the card:
                $charge = \Stripe\Charge::create(array(
                    "amount" => $payAmt,
                    "currency" => "usd",
                    "customer" => $customer->id,
                    "description" => "Load Amount"
                ));
                //Save Stripe's Customer Details in Table
                $cardEntity = $this->StripeCustomers->newEntity();
                $cardInsert['stripe_customer_id'] = $customer->id;
                $cardPatch = $this->StripeCustomers->patchEntity($cardEntity,$cardInsert);
                $cardPatch->id = $this->request->getData('cardId');
                $saveCard = $this->StripeCustomers->save($cardPatch);

            }else {
                $charge = \Stripe\Charge::create(array(
                        "amount" => $payAmt, // amount in cents, again
                        "currency" => "usd",
                        "customer" => $cardDetails['stripe_customer_id'],
                        "description" => "Load Amount"
                ));
            }

            $walletEntity = $this->WalletHistories->newEntity();
            $history['customer_id'] = $this->Auth->user('id');
            $history['purpose'] = "Load Amount";
            $history['transaction_type'] = $cardDetails['card_type'];
            $history['payment_type'] = 'Stripe';
            $history['amount'] = $this->request->getData('amount');
            $history['transaction_details'] = 'Myaccount';
            $walletPatch = $this->WalletHistories->patchEntity($walletEntity,$history);
            $saveWallet = $this->WalletHistories->save($walletPatch);
            if($saveWallet) {

                $userDetails = $this->Users->find('all', [
                    'conditions' => [
                        'id' => $this->Auth->user('id')
                    ]
                ])->hydrate(false)->first();

                $totalAmount = $this->request->getData('amount') + $userDetails['wallet_amount'];
                $custEntity = $this->Users->newEntity();
                $amount['wallet_amount'] = $totalAmount ;
                $custPatch = $this->Users->patchEntity($custEntity,$amount);
                $custPatch->id = $this->Auth->user('id');
                $saveCust = $this->Users->save($custPatch);
                if($saveCust) {
                    $this->Flash->success('Amount Loaded Successful');
                    echo '0';die();
                }
            }
        }
        die();
    }

    //-----------------------------------------Add Money to Wallet Using Paypal-----------------------------------------
    public function addMoneyPaypal() {

        if($this->request->getData('amount') != '') {


            $walletEntity = $this->WalletHistories->newEntity();
            $history['customer_id'] = $this->Auth->user('id');
            $history['purpose'] = "Load Amount";
            $history['transaction_type'] = 'Credited';
            $history['transaction_id'] = $this->request->getData('payment_id');
            $history['amount'] = $this->request->getData('amount');
            $history['payment_type'] = 'Paypal';
            $history['transaction_details'] = 'Myaccount';
            $walletPatch = $this->WalletHistories->patchEntity($walletEntity,$history);
            $saveWallet = $this->WalletHistories->save($walletPatch);
            if($saveWallet) {

                $userDetails = $this->Users->find('all', [
                    'conditions' => [
                        'id' => $this->Auth->user('id')
                    ]
                ])->hydrate(false)->first();

                $totalAmount = $this->request->getData('amount') + $userDetails['wallet_amount'];
                $custEntity = $this->Users->newEntity();
                $amount['wallet_amount'] = $totalAmount ;
                $custPatch = $this->Users->patchEntity($custEntity,$amount);
                $custPatch->id = $this->Auth->user('id');
                $saveCust = $this->Users->save($custPatch);
                if($saveCust) {
                    $this->Flash->success('Amount Loaded Successful');
                    echo '0';die();
                }
            }
        }
        die();

    }
    //-----------------------------------------Get Order Details For Customer's Order view------------------------------
    public function orderView($id = null) {
            
        $this->viewBuilder()->options([
            'pdfConfig' => [
                'orientation' => 'portrait',
                'filename' => 'Order_' . $id
            ]
        ]);

        if($id != '') {
            $orderDetails = $this->Orders->find('all', [
                'conditions' => [
                    'Orders.id' => $id,
                    'Orders.customer_id' => $this->Auth->user('id')
                ],
                'contain' => [
                    'Restaurants' => [
                        'fields' => [
                            'Restaurants.restaurant_name',
                            'Restaurants.id',
                            'Restaurants.restaurant_logo',
                            'Restaurants.logo_name',
                            'Restaurants.contact_address'
                        ]
                    ],
                    'Users',
                    'Carts' => [
                        'RestaurantMenus',
                        'sort' => [
                            'Carts.menu_id' => 'ASC'
                        ]
                    ]
                ]
            ])->hydrate(false)->first();
            //echo "<pre>"; print_r($orderDetails); die();


            if($orderDetails['status'] != 'Pending' && $orderDetails['status'] != 'Delivered' && $orderDetails['status'] != 'Failed'){
                $orderDetails['status'] = 'Accepted';
            }
            #pr($orderDetails);die();
            $this->set(compact('orderDetails'));
        }
    }

    //-----------------------------------------Showing PDF--------------------------------------------------------------
    public function pdf($id = null) {

        if($id != '') {
            $orderDetails = $this->Orders->find('all', [
                'conditions' => [
                    'Orders.id' => $id
                ],
                'contain' => [
                    'Restaurants' => [
                        'fields' => [
                            'Restaurants.restaurant_name',
                            'Restaurants.id',
                            'Restaurants.restaurant_logo',
                            'Restaurants.contact_address'
                        ]
                    ],
                    'Users',
                    'Carts' => [
                        'RestaurantMenus',
                        'sort' => [
                            'Carts.menu_id' => 'ASC'
                        ]
                    ]
                ]
            ])->hydrate(false)->first();

            if($orderDetails['status'] != 'Pending' && $orderDetails['status'] != 'Delivered' && $orderDetails['status'] != 'Failed'){
                $orderDetails['status'] = 'Accepted';
            }
            //pr($orderDetails);die();
            $html = '<div style="diplay:block;width:650px;background:#fff;margin:0 auto;padding:30px;border:1px solid #ddd;">
                                <div style="display:inline-block;width:49%;text-align:left;font:bold 24px Arial;padding-bottom:20px">ORD003600</div>
                                <div style="display:inline-block;width:50%;text-align:right;font:bold 24px Arial;padding-bottom:20px">2018-01-22 11:03:29</div>
                                <table style="border:3px solid #ddd;"  cellpadding="0" cellspacing="0" width="100%">
                                    <tr>
                                        <tr>
                                            <th bgcolor="#eee" colspan="2" style="border-bottom:1px solid #eee;font:bold 14px Arial;padding:10px 20px;line-height:30px;">Customer & Order information</th>
                                        </tr>	
                                    </tr>
                                    <tr>
                                        <td width="30%" style="border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:14px;">Customer Name</td>
                                        <td width="50%" style="border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:14px;">: Vss manikandan</td>
                                    </tr>
                                    <tr>
                                        <td width="30%" style="border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:14px;">Customer Email</td>
                                        <td width="50%" style="border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:14px;">: manikandan.mn@roamsoft.in</td>
                                    </tr>
                                    <tr>
                                        <td width="30%" style="border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:14px;">Address</td>
                                        <td width="50%" style="border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:14px;">: 234,Vadapalani, Chennai, Tamil Nadu, India</td>
                                    </tr>
                                    <tr>
                                        <td width="30%" style="border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:14px;">Phone Number</td>
                                        <td width="50%" style="border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:14px;">: 9876543210</td>
                                    </tr>
                                    <tr>
                                        <td width="30%" style="border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:14px;">Delivery Date</td>
                                        <td width="50%" style="border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:14px;">: 2018-01-24 </td>
                                    </tr>
                                    <tr>
                                        <td width="30%" style="border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:14px;">Delivery Time</td>
                                        <td width="50%" style="border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:14px;">: Wed - 12:40 AM </td>
                                    </tr>
                                    <tr>
                                        <td width="30%" style="border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:14px;">Order Type</td>
                                        <td width="50%" style="border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:14px;">: Delivery</td>
                                    </tr>
                                    <tr>
                                        <td width="30%" style="border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:14px;">Payment Method</td>
                                        <td width="50%" style="border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:14px;">: Paypal</td>
                                    </tr>
                                    <tr>
                                        <td width="30%" style="border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:14px;">Payment Status</td>
                                        <td width="50%" style="border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:14px;">: paid</td>
                                    </tr>
                                    <tr>
                                        <td width="30%" style="border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:14px;">Store</td>
                                        <td width="50%" style="border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:14px;">: Masala Times</td>
                                    </tr>
                                    <tr>
                                        <td width="30%" style="border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:14px;">Order Status</td>
                                        <td width="50%" style="border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:14px;">: Pending</td>
                                    </tr>
                                </table>
                                <table style="border:3px solid #ddd; margin-top:20px"  cellpadding="0" cellspacing="0" width="100%">
                                    <tr>
                                        <th bgcolor="#eee" style="width:10%;border-bottom:1px solid #eee;font:bold 14px Arial;padding:10px 20px;line-height:30px;">S.No</th>
                                        <th bgcolor="#eee" align="left" style="width:45%;border-bottom:1px solid #eee;font:bold 14px Arial;padding:10px 20px;line-height:30px;">Item Name</th>
                                        <th bgcolor="#eee" style="width:5%;border-bottom:1px solid #eee;font:bold 14px Arial;padding:10px 20px;line-height:30px;">Qty</th>
                                        <th bgcolor="#eee" align="right" style="width:20%;border-bottom:1px solid #eee;font:bold 14px Arial;padding:10px 20px;line-height:30px;">Price</th>
                                        <th bgcolor="#eee" align="right" style="width:20%;border-bottom:1px solid #eee;font:bold 14px Arial;padding:10px 20px;line-height:30px;">Total Price</th>
                                    </tr>
                                    <tr>
                                        <td align="center" style="width:10%;border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:14px;">1</td>
                                        <td align="left" style="width:45%;border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:14px;">Cheese Pizza [Deal :: Funghi] [Small ]</td>
                                        <td style="width:5%;border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:14px;">1</td>
                                        <td align="right" style="width:20%;border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:14px;">4.50$</td>
                                        <td align="right" style="width:20%;border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:14px;">4.50$</td>
                                    </tr>
                                    <tr>
                                        <td align="center" style="width:10%;border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:14px;">2</td>
                                        <td align="left" style="width:45%;border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:14px;">Garlic Bread with Cheese[Deal :: Garlic Bread with Tomato Garlic Bread with Tomato]</td>
                                        <td style="width:5%;border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:14px;">5</td>
                                        <td align="right" style="width:20%;border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:14px;">2.50$</td>
                                        <td align="right" style="width:20%;border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:14px;">12.50$</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" align="right"  style="border-bottom:1px solid #eee;font:bold 14px Arial;padding:10px 20px;line-height:14px;">Sub Total</td>
                                        <td align="right" style="border-bottom:1px solid #eee;font:bold 14px Arial;padding:10px 20px;line-height:14px;">12.50$</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" align="right"  style="border-bottom:1px solid #eee;font:bold 14px Arial;padding:10px 20px;line-height:14px;">Tax</td>
                                        <td align="right" style="border-bottom:1px solid #eee;font:bold 14px Arial;padding:10px 20px;line-height:14px;">5.00$</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" align="right"  style="border-bottom:1px solid #eee;font:bold 14px Arial;padding:10px 20px;line-height:14px;">Delivery Fees</td>
                                        <td align="right" style="border-bottom:1px solid #eee;font:bold 14px Arial;padding:10px 20px;line-height:14px;">2.50$</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" align="right"  style="border-bottom:1px solid #eee;font:bold 16px Arial;padding:10px 20px;line-height:14px;">Total</td>
                                        <td align="right" style="border-bottom:1px solid #eee;font:bold 16px Arial;padding:10px 20px;line-height:14px;">20.50$</td>
                                    </tr>
                                </table>
                            </div>';


            $this->set(compact('html'));
        }

    }

    public function orderStatus() {

    }

    public function refer() {

    }
//----------------------------------------------------------------------
}