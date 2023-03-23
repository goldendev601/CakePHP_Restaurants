<?php

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Mailer\Email;
use Twilio\Rest\Client;
use Cake\Controller\Component\CookieComponent;



//require_once "Twilio/autoload.php";
require_once(ROOT . DS . 'vendor' . DS . 'twilio'. DS . 'Twilio'. DS . 'autoload.php');
require_once (ROOT . DS . 'vendor'. DS . 'Pusher.php');
class UsersController extends AppController
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
        $this->viewBuilder()->setLayout('frontend');

        $this->loadComponent('Auth');
        $this->loadComponent('Common');
        $this->loadComponent('Cookie');

        $this->loadModel("Users");
        $this->loadModel('Restaurants');
        $this->loadModel('Notifications');
        $this->loadModel('Sitesettings');
        $this->loadModel('States');
        $this->loadModel('Carts');
        $this->loadModel('Orders');
        $this->loadModel('Drivers');
        $this->loadModel('Referrals');
        $this->loadModel('DriverInvoices');
        $this->loadModel('WalletHistories');
        $this->loadModel('Restaurants');
        $this->loadModel('Invoices');
    }
//-------------------------------------------------------------------------------------
    public function beforeFilter(Event $event)
    {

        if($this->Auth->user()){
            $this->set('logginUser', $this->Auth->user());
        }else {
            $this->set('logginUser', '');
        }
        // Before Login , these are the function we can access
        $this->Auth->allow([
            'index',
            'login',
            'signup',
            'getLocation',
            'search',
            'forgotPassword',
            'restaurantSignup',
            'orderView',
            'checkUsername',
            'ajaxaction',
            'changeLocation',
            'facebookLogin',
            'driverInvoice',
            'invoice',
            'faceBookAdd',
            'mailgun',
            'social',
            'socialRedirect',
            'appPage',
            'orderrequest',
            'gprsprinter'
        ]);
    }

//-------------------------------------------------------------------------------------
    public function index()
    {


        if((SEARCHBY == 'Google' && $this->request->session()->read('searchLocation') != '') || (SEARCHBY != 'Google' && $this->request->session()->read('city_id') != '' && $this->request->session()->read('location_id') != '')){
            return $this->redirect(BASE_URL.'searches');
        }

        if(SEARCHBY  != 'Google') {
            $this->loadModel('Cities');
            $citylist = $this->Cities->find('list', [
                'keyField' => 'id',
                'valueField' => 'city_name',
                'conditions' => [
                    'status' => '1'
                ],
            ])->hydrate(false)->toArray();

            $this->set(compact('citylist'));
        }

    }
//-----------------------------------Customer Login Section Start-------------------------------------------------------
    public function login(){

        if (!empty($this->Auth->user()) && $this->Auth->user('role_id') === 1) {
            //echo ADMIN_BASE_URL;die();
            $this->Flash->error('Please logout our Admin panel');

            return $this->redirect(ADMIN_BASE_URL);
        }else if (!empty($this->Auth->user()) && $this->Auth->user('role_id') === 2) {
            $this->Flash->error('Please logout restaurant panel');
            return $this->redirect(ADMIN_BASE_URL);
        }else if (!empty($this->Auth->user()) && $this->Auth->user('role_id') === 4) {
            $this->Flash->error('Please logout our Subadmin panel');
            return $this->redirect(ADMIN_BASE_URL);
        }
    
        if(!empty($this->Auth->user())){
            if($this->Auth->redirectUrl() == '/') {
                return $this->redirect(BASE_URL.'customers');
            }else {
                return $this->redirect($this->Auth->redirectUrl());
            }
        }else if($this->request->is(['post','put'])){
             
            if(!empty($this->request->getData('username')) && !empty($this->request->getData('password'))) {
                $user = $this->Auth->identify();                

                if(!empty($user) && ($user['role_id'] == 3) && ($user['deleted_status'] == 'N') && ($user['status'] == '1')){ 
                    $this->Auth->setUser($user);

                    if ($this->request->getData('rememberMe') == "1") {

                        $cookie = array();
                        $cookie['username'] = $this->request->getData('username');
                        $cookie['password'] = $this->request->getData('password');
                        $cookie['rememberMe'] = $this->request->getData('rememberMe');
                        $this->Cookie->write('rememberMe', $cookie);
                    }else {
                        $this->Cookie->write('rememberMe', '');
                    }


                    if($this->Auth->redirectUrl() == '/') {
                        return $this->redirect(BASE_URL.'customers');
                    }else {
                        return $this->redirect($this->Auth->redirectUrl());
                    }
                }else if(!empty($user) && ($user['role_id'] == 3) && ($user['deleted_status'] == 'N') && ($user['status'] == '0')){                    
                    $this->Flash->error('Your account was deactivated. Please contact admin');
                }else if(!empty($user) && ($user['role_id'] == 3) && ($user['deleted_status'] == 'Y')){                    
                    $this->Flash->error('Your account was deleted. Please contact admin');
                }else{
                    $this->Flash->error('Invalid username or password, try again');
                }
            }   
        }
        $currentpage = $this->Auth->redirectUrl();
        if($currentpage == '/checkouts') {
            $current = explode('/',$currentpage);
            $currentpage = $current[1];
        }
        $this->request->session()->write('currentpage',$currentpage);
        $rememberMe = $this->Cookie->read('rememberMe.rememberMe');
        $username = $this->Cookie->read('rememberMe.username');
        $password = $this->Cookie->read('rememberMe.password');
        $this->set(compact('currentpage','rememberMe','username','password'));


    }
//-----------------------------------Customer Login Section End---------------------------------------------------------
//
//-----------------------------------Customer Logout Section -----------------------------------------------------------
    //logout
    public function logout() {
        $this->Auth->logout();
        return $this->redirect(BASE_URL.'users/login');
    }
//-----------------------------------Customer Logout Section End--------------------------------------------------------

//-----------------------------------Customer Signup Section Start------------------------------------------------------
    public function signup($code = null)
    {
        if (!empty($this->Auth->user()) && $this->Auth->user('role_id') === 1) {
            //echo ADMIN_BASE_URL;die();
            $this->Flash->error('Please logout our Admin panel');
            return $this->redirect(ADMIN_BASE_URL);
        }else if (!empty($this->Auth->user()) && $this->Auth->user('role_id') === 2) {
            $this->Flash->error('Please logout restaurant panel');
            return $this->redirect(ADMIN_BASE_URL);
        }else if (!empty($this->Auth->user()) && $this->Auth->user('role_id') === 4) {
            $this->Flash->error('Please logout our Subadmin panel');
            return $this->redirect(ADMIN_BASE_URL);
        }else if (!empty($this->Auth->user()) && $this->Auth->user('role_id') === 3) {
            return $this->redirect(BASE_URL.'customers');
        }

        if($code != '') {
            $checkCode = $this->Users->find('all', [
                'conditions' => [
                    'referral_code' => $code
                ]
            ])->count();

            if($checkCode > 0) {
                $referralCode = $code;
            }else {
                $referralCode = '';
            }

        }else {
            $referralCode = '';
        }
        $walletAmount = 0;

        if($this->request->is(['post','put'])) {

            if($this->request->getData('referral_code') != '') {
                $checkCode = $this->Users->find('all', [
                    'conditions' => [
                        'referral_code' => $this->request->getData('referral_code')
                    ]
                ])->hydrate(false)->first();

                if(!empty($checkCode)) {
                    $referrals = $this->Referrals->find('all', [
                        'conditions' => [
                            'referral_option' => 'Yes'
                        ]
                    ])->hydrate(false)->first();

                    if(!empty($referrals)) {
                        //This wallet Amount to New Customer
                        $walletAmount = $referrals['receive_amount'];

                        //Amount update to referred Customer
                        $updateUser['wallet_amount'] = $checkCode['wallet_amount'] + $referrals['invite_amount'];
                        $userEntity = $this->Users->newEntity();
                        $customerPatch  = $this->Users->patchEntity($userEntity,$updateUser);
                        $customerPatch->id = $checkCode['id'];
                        $saveUsers = $this->Users->save($customerPatch);

                        $walletEntity = $this->WalletHistories->newEntity();
                        $history['customer_id'] = $checkCode['id'];
                        $history['purpose'] = "Referred to ".$this->request->getData('first_name');
                        $history['transaction_type'] = 'Credited';
                        $history['amount'] = $referrals['invite_amount'];
                        $history['transaction_details'] = 'Referral Amount';
                        $walletPatch = $this->WalletHistories->patchEntity($walletEntity,$history);
                        $saveWallet = $this->WalletHistories->save($walletPatch);
                    }
                }
            }

            $userEnty     = $this->Users->newEntity();
            $userPatch    = $this->Users->patchEntity($userEnty, $this->request->getData());
            $userPatch->username = $this->request->getData('email');
            $userPatch->role_id = 3 ;
            $userPatch->status = 1;
            $userPatch->referred_by = (isset($checkCode['id']) ? $checkCode['id'] : '');
            $userPatch->wallet_amount = $walletAmount;

            $firstName = strtoupper($this->request->getData('first_name'));
            $random = mt_rand(1000, 9999);

            $userPatch->referral_code = $firstName.''.$random;

            $userSave = $this->Users->save($userPatch);

            if($userSave){

                if($walletAmount > 0) {
                    $walletEntity = $this->WalletHistories->newEntity();
                    $history['customer_id'] = $userSave->id;
                    $history['purpose'] = "Referred By ".$checkCode['first_name']." (".$checkCode['referral_code'].")";
                    $history['transaction_type'] = 'Credited';
                    $history['amount'] = $walletAmount;
                    $history['transaction_details'] = 'Referral Amount';
                    $walletPatch = $this->WalletHistories->patchEntity($walletEntity,$history);
                    $saveWallet = $this->WalletHistories->save($walletPatch);
                }


                $newRegisteration = $this->Notifications->find('all',[
                    'conditions'=>[
                        'title' => 'Customer activation'
                    ]
                ])->hydrate(false)->first();
                
                if($newRegisteration){
                    $regContent = $newRegisteration['content'];
                    $regsubject = $newRegisteration['subject'];
                }

                $toemail = $this->request->getData('email');
                $title 	 = 'logo.png';
                $password = $this->request->getData('password');
                $mailContent  = $regContent;
                $customerName = $this->request->getData('first_name').' '.$this->request->getData('last_name');
                $phoneNo = $this->request->getData('phone_number');
                $site_url     = BASE_URL;
                $source       = BASE_URL.'images/logo-home.png';
                $fromMail = 'no-reply@foodorderingsystem.com';
                $mailSubject = "Customer Signup";
                $store_name   = $this->siteSettings['site_name'];

                $mailContent  = str_replace("{firstname}", $customerName, $mailContent);
                $mailContent = str_replace("{title}", $title, $mailContent);
                $mailContent  = str_replace("{siteUrl}", $site_url, $mailContent);
                $mailContent  = str_replace("{store name}",$store_name, $mailContent);

                $email = new Email();
                $email->setFrom([$fromMail => 'Comeneat2.0']);
                $email->setTo($toemail);
                $email->setSubject($mailSubject);
                $email->setTemplate('default');
                $email->setEmailFormat('html');
                $email->setViewVars([
                    'title' => $mailSubject ,
                    'password' => $password,
                    'customerName' => $customerName,
                    'emailId' => $toemail,
                    'phoneNo' => $phoneNo,
                    'siteName'    => 'Foodorderingsystem',
                    'source'    => $source,
                    'siteUrl'    => $site_url,
                    'mailContent' => $mailContent
                ]);

                $this->Flash->success(_('Your Informations Added Successfully'));
                $user = $this->Users->find('all', [
                    'conditions' => [
                        'id' => $userSave->id
                    ]
                ])->hydrate(false)->first();

                if(!empty($user)) {

                    $this->Auth->setUser($user);
                    if($this->Auth->redirectUrl() == '/') {
                        return $this->redirect(BASE_URL.'customers');
                    }else {
                        return $this->redirect($this->Auth->redirectUrl());
                    }
                }
                
                if($email->send()){
                    $this->Flash->success(_('Your Informations Added Successfully'));

                    $user = $this->Users->find('all', [
                        'conditions' => [
                            'id' => $userSave->id
                        ]
                    ])->hydrate(false)->first();

                    if(!empty($user)) {

                        $this->Auth->setUser($user);
                        if($this->Auth->redirectUrl() == '/') {
                            return $this->redirect(BASE_URL.'customers');
                        }else {
                            return $this->redirect($this->Auth->redirectUrl());
                        }
                    }
                }

                /*$this->Flash->success(_('Your Informations Added Successfully'));
                return $this->redirect(BASE_URL);*/

            }
        }

        $currentpage = $this->Auth->redirectUrl();
        if($currentpage == '/checkouts') {
            $current = explode('/',$currentpage);
            $currentpage = $current[1];
        }
        $this->request->session()->write('currentpage',$currentpage);
        $firstName = $this->request->session()->read('first_name');
        $userName = $this->request->session()->read('username');
        $this->set(compact('currentpage','referralCode', 'firstName', 'userName'));
    }
//-----------------------------------Customer Signup Section End--------------------------------------------------------

//-----------------------------------Get Order Details for Thanks Page -------------------------------------------------
    public function thanks($id = null) { 

        if($id != ''){
            $orderId = base64_decode($id); 
            $customer_id = $this->Auth->user('id'); 

            $orderDetails = $this->Orders->find('all',[
                'conditions' =>[
                    'Orders.id' => $orderId,
                    'Orders.customer_id' => $customer_id
                ],
                'contain' => [
                    'Carts'
                ]
            ])->hydrate(false)->first();

            $ordertxt_item = '';
            if(!empty($orderDetails['carts'])) {
                foreach ($orderDetails['carts'] as $key => $value) {

                    $ordertxt_item .= $value['quantity'].";".stripslashes($value['menuname'])." ".$value['subaddons_name'].";".$value['total_price'].";";
                }

            }

            if ($orderDetails['printer_sent'] == 'N' && $orderDetails['file_write'] == 'N')
            {

                //$cc_handling    = $taxamount+$tipamount-$orderDiscountPrice;
                $sms_otype      = ($orderDetails['order_type'] == 'delivery') ? 1 : 2 ;
                $sms_ctype      = 4;
                $payment_status      = ($orderDetails['payment_status'] == 'P') ? 6 : 7 ;

                #Delivery Address
                if($orderDetails['order_type'] == 'delivery'){

                    $deliveryaddress = $orderDetails['flat_no'].','.$orderDetails['address'];
                }else{
                    $deliveryaddress = 0;
                }
                #Customer Name
                $customername = stripslashes($orderDetails['customer_name']);
                #Previous order
                $prev_order_cnt = $this->Orders->find('all', [
                    'conditions' => [
                        'customer_id' => $this->Auth->user('id'),
                        'restaurant_id' => $orderDetails['restaurant_id'],
                        'status' => 'completed'
                    ]
                ])->count();
                #Delivery time
                $delivery_date_time = $orderDetails['delivery_date']." ".$orderDetails['delivery_time'];

                $payment_method_type = $orderDetails['payment_mehotd'];

                #getfood Printer
                $ordertxt = "#" . $orderDetails['restaurant_id'] . "*" . $sms_otype . "*" . $orderDetails['order_number'] .
                    "*" . $ordertxt_item . "*" . $orderDetails['delivery_charge'] .
                    "*" . $orderDetails['order_grand_total'] . ";" . $sms_ctype .
                    ";" . $customername . ";" . $deliveryaddress .";" . $orderDetails['customer_phone'] .
                    ";" . $delivery_date_time .";" . $prev_order_cnt . ";" . $payment_status . ";" . $payment_method_type .
                    ";*" . stripslashes($orderDetails['order_description']) . "#";

                //echo "ordertxt=>".$ordertxt;

                $orderfilename = PRINTER_FILE_PATH . '/' . $orderDetails['restaurant_id'] . '.txt';

                if (!file_exists($orderfilename))
                {
                    $handle = fopen($orderfilename, "a+");
                }
                $current = file_get_contents($orderfilename);
                $current .= $ordertxt . "\n";
                file_put_contents($orderfilename, $current);

                //$this->getUpdate($CFG['table']['order'], "printer_sent = 'Y' ", " orderid = '" . $orderid . "' ");
                $printerUpdate['file_write'] = 'Y';
                $orderEntity = $this->Orders->newEntity();
                $orderPatch = $this->Orders->patchEntity($orderEntity,$printerUpdate);
                $orderPatch->id = $orderDetails['id'];
                $orderUpdate = $this->Orders->save($orderPatch);

            }


            $this->set(compact('orderDetails'));
        }
    }
//-----------------------------------Get Order Details for Thanks Page End----------------------------------------------

//-----------------------------------Get Customer Current Location using IP Address-------------------------------------
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
        //echo 'IN';die();
        if(!empty($result)) {
            echo $result['country'];die();
        }else {
            echo 'IN';die();
        }
    }
//-----------------------------------Get Customer Current Location using IP Address End---------------------------------


//-----------------------------------Set Session for Customer's Search Address------------------------------------------
    public function search() {
        if(SEARCHBY == 'Google') {
            if($this->request->getData('searchLocation') != '') {
                $this->request->session()->write('searchLocation', $this->request->getData('searchLocation'));
                die();
            }
        }else {
            $this->request->session()->write('city_id', $this->request->getData('city_id'));
            $this->request->session()->write('location_id', $this->request->getData('location_id'));
            die();
        }

    }
//-----------------------------------Set Session for Customer's Search Address------------------------------------------

//-----------------------------------Customer's Forgot Password Section Start-------------------------------------------
    public function forgotPassword(){
        
        if($this->request->is(['post','put'])) { 
            if(!empty($this->request->getData('username'))){

                    $userData = $this->Users->find('all', [
                       'conditions' =>[
                            'username' => $this->request->getData('username'),
                            'status' => 1,
                            'deleted_status' => 'N',
                            'role_id' => 3
                       ]
                    ])->first();
                
                if(!empty($userData)){

                    $newRegisteration = $this->Notifications->find('all',[
                            'conditions'=>[
                                'title' => 'Reset password'
                            ]
                    ])->hydrate(false)->first();

                    if($newRegisteration){

                        $forgetpasswordContent = $newRegisteration['content'];
                        $forgetpasswordsubject = $newRegisteration['subject'];
                    }

                    $mailContent = $forgetpasswordContent;
                    $title 	 = 'logo.png';
                    $adminName  = $this->siteSettings['site_name'];


                    $toemail      = $this->request->getData('username');
                    $customerName = $userData['first_name'].' '.$userData['last_name'];
                    $site_url     = BASE_URL;
                    $source       = BASE_URL.'images/logo-home.png';
                    $adminEmail   = $this->siteSettings['admin_email'];
                    $siteName     = $this->siteSettings['site_name'];
                    $tmpPassword  = $this->Common->passwordGenerator(7);

                    $userID      = $userData['id'];
                    $siteUrl = BASE_URL.'users/login';
                    $mailContent = str_replace("{Customer name}", $customerName, $mailContent);
                    $mailContent = str_replace("{source}", $source, $mailContent);
                    $mailContent = str_replace("{title}", $title, $mailContent);
                    $mailContent = str_replace("{SITE_URL}", $siteUrl, $mailContent);
                    $mailContent = str_replace("{tmpPassword}", $tmpPassword, $mailContent);
                    $mailContent = str_replace("{Store name}", $adminName, $mailContent);



                    $datas['password'] = $tmpPassword;
                    $datas['id'] = $userData['id'];
                    $userEnty     = $this->Users->newEntity();
                    $userPatch    = $this->Users->patchEntity($userEnty,$datas);
                    if ($this->Users->save($userPatch) ){

                        $fromMail = $this->siteSettings['admin_email'];
                        $to = $toemail;
                        $mailSubject = "Forget password";
                        $email = new Email();
                        $email->setFrom([$fromMail => 'Comeneat2.0']);
                        $email->setTo($to);
                        $email->setSubject($mailSubject);
                        $temp=$email->setTemplate('default');
                        $email->setEmailFormat('html');
                        $email->setViewVars([
                            'mailContent' => $mailContent,
                            'title' => $mailSubject ,
                            'password' => $tmpPassword,
                            'customerName' => $customerName,
                            'siteName'    => $siteName,
                            'source'    => $source,
                            'siteUrl'    => $site_url
                        ]);
                        $this->Flash->success('Temporary Password sent To your Registered email id.');
                       // $email->setViewVars(array('mailContent' => $mailContent,'source'=>$source,'storename'=>$adminName));
                        //$this->Flash->success('Temporary Password sent To your Registered email id.');

                        if($email->send()){
                            $this->Flash->success('Temporary Password sent To your Registered email id.');
                            echo '1';die();
                        }
                    }
                }else{
                    $this->Flash->error('Your account was not registered with us.');
                    echo '0';die();
                }
            }
            echo '0';die();
        }
    }
    //-----------------------------------Customer's Forgot Password Section End-----------------------------------------


    //-----------------------------------Restaurant Signup Section Start------------------------------------------------
    public function restaurantSignup(){
       
        if($this->request->is(['post','put'])) {

            //pr($this->request->getData());die();

            $resArrray = $this->request->getData();
            $usersExist = $this->Users->find('all',[
                'conditions' => [
                    'username' => trim($resArrray['User']['username'])
                ]
            ])->first();

            $restaurantName = $this->Common->seoUrl($this->request->getData('restaurant')['restaurant_name']);
            $restExist = $this->Restaurants->find('all',[
                'conditions' => [
                    'seo_url' =>$restaurantName
                ]
            ])->first();
            
            if (!empty($usersExist)) {
                $this->Flash->error('User Email Already Exists');
            }else if (!empty($restExist)) {
                $this->Flash->error('Restaurant Name Already Exists');
            } else {
                $userEnty     = $this->Users->newEntity();
                $userPatch    = $this->Users->patchEntity($userEnty, $this->request->getData('User'));
                $userPatch->role_id = 2;
                $userPatch->contact_phone = $this->request->getData('restaurant.contact_phone');
       
                if($user=$this->Users->save($userPatch)){
                    $resEnty  = $this->Restaurants->newEntity();
                    $resPatch = $this->Restaurants->patchEntity($resEnty, $this->request->getData('restaurant'));
                    $resPatch['username'] = $this->request->getData('User')['username'];
                    $resPatch['user_id'] = $user['id'];
                    $resPatch['contact_email'] = $this->request->getData('User.username');
                    $resPatch['seo_url']  = $this->Common->seoUrl($this->request->getData('restaurant.restaurant_name'));

                    $newRegisteration = $this->Notifications->find('all', [
                        'conditions' => [
                            'title' => 'Store Signup'
                        ]
                    ])->hydrate(false)->first();

                    if($rest=$this->Restaurants->save($resPatch))
                    {
                        if($newRegisteration){
                            $forgetpasswordContent = $newRegisteration['content'];
                            $forgetpasswordsubject = $newRegisteration['subject'];
                        }

                        $toemail  = $user['username'];
                        $RestName = $rest['restaurant_name'];
                        $customerName = $rest['restaurant_name'];
                        //$activation   = BASE_URL. '/users/activeLink/'.$userID;
                        
                        $site_url = BASE_URL;
                        $source   = BASE_URL.'images/logo-home.png';
                        $fromMail = 'no-reply@Foodorderingsystem.com';
                        $mailSubject = "Restaurant Signup";
                        $siteName     = $this->siteSettings['site_name'];
                        $activation   = BASE_URL.'restaurantadmin';



                        $mailContent = $forgetpasswordContent;
                        $siteUrl = $this->siteUrl.'/restaurant';
                        $mailContent = str_replace("{Customer name}", $customerName, $mailContent);
                        $mailContent = str_replace("{source}", $source, $mailContent);
                        $mailContent = str_replace("{title}", $RestName, $mailContent);
                        $mailContent = str_replace("{SITE_URL}", $siteUrl, $mailContent);
                        $mailContent = str_replace("{Store name}", $RestName, $mailContent);

                        $mailContent  = str_replace("{sellar name}", $rest['restaurant_name'], $mailContent);
                        $mailContent  = str_replace("{siteUrl}", $siteUrl, $mailContent);
                        $mailContent  = str_replace("{CLICK_HERE_TO_LOGIN}", $activation, $mailContent);
                        $mailContent  = str_replace("{SERVER_NAME}",$siteName, $mailContent);

                        $email = new Email();
                        $email->setFrom([$fromMail => 'Comeneat2.0']);
                        $email->setTo($toemail);
                        $email->setSubject($mailSubject);
                        $email->setTemplate('default');
                        $email->setEmailFormat('html');
                        $email->setViewVars([
                            'mailContent' => $mailContent,
                            'title' => $mailSubject ,
                            'restaurantName' => $RestName,
                            'customerName' => $customerName,
                            'emailId' => $toemail,
                            'siteName'    => 'Foodorderingsystem',
                            'source'    => $source,
                            'siteUrl'    => $site_url
                        ]);

                       if($email->send()){

                           //Send mail to admin
                           $emailinfo='<table>
					        <tr style="display:block; width:610px; margin-bottom:15px;font:13px Arial, Helvetica, sans-serif;">
								<td style="display:inline-block; width:185px;vertical-align: top;">Hi Admin,</td>
							</tr>
							<tr style="display:block; width:610px; margin-bottom:15px;font:13px Arial, Helvetica, sans-serif;">
								<td>New restaurant has been registered and the details are below,</td>
							</tr>
							<tr style="display:block; width:610px; margin-bottom:15px;font:13px Arial, Helvetica, sans-serif;">
								<td style="display:inline-block; width:185px;vertical-align: top;">Restaurant Name</td>
								<td style="display:inline-block; width:50px; text-align:center;">:</td>
								<td style="display:inline-block; width:350px; color:#ee541e;">'.$this->request->getData('restaurant.restaurant_name').'</td>
							</tr>
					        <tr style="display:block; width:610px; margin-bottom:15px;font:13px Arial, Helvetica, sans-serif;">
								<td style="display:inline-block; width:185px;vertical-align: top;">Contact Name</td>
								<td style="display:inline-block; width:50px; text-align:center;">:</td>
								<td style="display:inline-block; width:350px; color:#ee541e;">'.$this->request->getData('restaurant.contact_name').'</td>
							</tr>
					        <tr style="display:block; width:610px; margin-bottom:15px;font:13px Arial, Helvetica, sans-serif;">
								<td style="display:inline-block; width:185px;vertical-align: top;">Email</td>
								<td style="display:inline-block; width:50px; text-align:center;">:</td>
								<td style="display:inline-block; width:350px; color:#ee541e;">'.$this->request->getData('User.username').'</td>
							</tr>
					        <tr style="display:block; width:610px; margin-bottom:15px;font:13px Arial, Helvetica, sans-serif;">
								<td style="display:inline-block; width:185px;vertical-align: top;">Contact Phone</td>
								<td style="display:inline-block; width:50px; text-align:center;">:</td>
								<td style="display:inline-block; width:350px; color:#ee541e;">'.$this->request->getData('restaurant.contact_phone').'</td>
							</tr>';

                           $emailinfo .='</table>';

                           $mailContent  	 = $emailinfo;
                           $customerSubject = 'New Restaurant Signup';
                           $siteName   	 = $this->siteSettings['site_name'];
                           $site_url = BASE_URL;
                           $source   = BASE_URL.'images/logo-home.png';
                           $fromMail = 'no-reply@Foodorderingsystem.com';


                           /*$email = new CakeEmail(array(
                                       'transport' => 'Mandrill.Mandrill'
                                     ));*/
                           $email = new Email();
                           $email->setFrom($fromMail);
                           $email->setTo($this->siteSettings['admin_email']);
                           $email->setSubject($customerSubject);
                           $email->setTemplate('register');
                           $email->setEmailFormat('html');
                           $email->setViewVars(array('mailContent' => $mailContent,
                               'source' => $source,
                               'storename' => $siteName));

                           $email->send();
                       }

                        $this->Flash->success(_('Restaurant Added Successfully'));
                        return $this->redirect(BASE_URL);
                    }
                }
            }
        }
    }
//-----------------------------------Restaurant Signup Section End------------------------------------------------------


//-----------------------------------Check Customer/Restaurant Already exists or Not------------------------------------
    public function checkUsername() {

        if($this->request->getData('username') != '') {
            if($this->Auth->user('id') != '') {
                $conditions = [
                    'id !=' => $this->Auth->user('id'),
                    'username' => $this->request->getData('username'),
                    'deleted_status' => 'N'
                ];
            }else {
                $conditions = [
                    'username' => $this->request->getData('username'),
                    'deleted_status' => 'N'
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
//-----------------------------------Check Customer/Restaurant Already exists or Not------------------------------------


//-----------------------------------Get Location Using City ID---------------------------------------------------------
    public function ajaxaction() {

        if ($this->request->getData('action') == 'getLocation') {
            $this->loadModel('Locations');
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
    }
//-----------------------------------Get Location Using City ID---------------------------------------------------------

//-----------------------------------Change Location Functionality---------------------------------------------------------
    public function changeLocation() {
        $this->request->session()->write('searchLocation','');
        $this->request->session()->write('city_id','');
        $this->request->session()->write('location_id','');
        return $this->redirect(BASE_URL);
    }
//-----------------------------------Change Location Functionality------------------------------------------------------


//-----------------------------------Restaurant Activation Functionality------------------------------------------------
    //Restauarnt Activation
    public function activeLink($id = null) {
        if ($id) {
            $CustomerDetails = $this->Users->findById($id);

            if (!empty($CustomerDetails)) {
                if($this->Customer->updateAll(['status' => 1], ['Customer.id' => $id])) {
                    $this->Flash->success(_('You account is now activated.'));
                   return  $this->redirect(REST_BASE_URL);
                } 
            }else{
                $this->Flash->success(_('You are not register Customer'));
                $this->redirect(['controller' => 'users', 'action' => 'signup']);
            }
        }
    }
//-----------------------------------Facebook Login Functionality-------------------------------------------------------
    public function facebookLogin() {
        if($this->request->getData('username') != '') {

            $customerCount = $this->Users->find('all', [
                'conditions' => [
                    'username' => $this->request->getData('username'),
                    'role_id' => '3'
                ]
            ])->hydrate(false)->first();

            $remainingCount = $this->Users->find('all', [
                'conditions' => [
                    'username' => $this->request->getData('username'),
                    'role_id !=' => '3'
                ]
            ])->count();

            if($remainingCount == 0) {
                if(!empty($customerCount)) {
                    if($customerCount['status'] == '0') {
                        echo '2';die();
                    }else if($customerCount['deleted_status'] == 'Y') {
                        echo '3';die();
                    }else {
                        $this->Auth->setUser($customerCount);
                        echo '0';die();
                    }

                }else {
                    $userEnty     = $this->Users->newEntity();
                    $userPatch    = $this->Users->patchEntity($userEnty, $this->request->getData());
                    $userPatch->role_id = 3 ;
                    $userPatch->status = 1;
                    $userPatch->password = '123123';
                    $save = $this->Users->save($userPatch);
                    if($save){
                        $this->Flash->success(_('Your Informations Added Successfully'));
                        $this->Auth->setUser($save);
                        echo '0';die();
                    }
                }
            }else {
                echo '1';die();
            }

        }else {
            echo '5';die();
        }
    }
//-----------------------------------Facebook Login Functionality-------------------------------------------------------


//-----------------------------------Generate Driver Invoice -----------------------------------------------------------

    public function driverInvoice() {

        $driverDetails = $this->Drivers->find('all', [
            'conditions' => [
                'delete_status' => 'N'
            ]
        ])->hydrate(false)->toArray();

        if(!empty($driverDetails)) {
            $start_date = date('Y-m-d 00:00:01', strtotime('-1 days'));
            $end_date = date('Y-m-d 23:59:59', strtotime('-1 days'));
            $end_date = '2018-02-08 23:59:59';

            foreach ($driverDetails as $dKey => $dValue) {

                $orderDetails = $this->Orders->find('all', [
                    'conditions' => [
                        'status' => 'Delivered',
                        'driver_id' => $dValue['id'],
                        'driver_invoice_number' => '',
                        //'driver_deliver_date >=' => $start_date,
                        'driver_deliver_date <=' => $end_date,
                        'driver_deliver_date !=' => '0000-00-00 00:00:00'
                ]
                ])->hydrate(false)->toArray();


                $codDetails = $this->Orders->find('all', [
                    'conditions' => [
                        'status' => 'Delivered',
                        'driver_id' => $dValue['id'],
                        'driver_invoice_number' => '',
                        'driver_deliver_date <=' => $end_date,
                        'driver_deliver_date !=' => '0000-00-00 00:00:00',
                        'payment_method' => 'cod'
                ]
                ])->hydrate(false)->toArray();

                //pr($orderDetails);die();

                if(!empty($orderDetails)){
                    $amount = 0;
                    $codAmount = 0;

                    $userEnty     = $this->DriverInvoices->newEntity();

                    $data['driver_id']                  = $dValue['id'];
                    $data['invoice_date']               = date('Y-m-d', strtotime('-1 days'));
                    $data['status']                     = 'unpaid';
                    $data['order_count']                = count($orderDetails);

                    $userPatch    = $this->DriverInvoices->patchEntity($userEnty, $data);

                    $driverInvoice = $this->DriverInvoices->save($userPatch);

                    $userPatch['invoice_number']  = "FOSDIN00".$driverInvoice->id;
                    $userPatch['id']              = $driverInvoice->id;
                    $this->DriverInvoices->save($userPatch);


                    foreach ($orderDetails as $oKey => $oValue) {

                        if($oValue['payment_method'] == 'cod') {
                            $codAmount += $oValue['delivery_charge'];
                        }

                        if($oValue['payout_type'] == 'distance') {
                            $amount += $oValue['distance'] * $oValue['payout_amount'];
                        }else {
                            $amount += $oValue['payout_amount'];
                        }

                        $order_update['driver_invoice_number']     = $userPatch['invoice_number'];
                        $order_update['id']                        = $oValue['id'];
                        $order_update['driver_invoice']            = "1";

                       // $orderPatch    = $this->Orders->patchEntity($orderEnty, $order_update);

                        $orderEnty     = $this->Orders->newEntity($order_update);


                        $this->Orders->save($orderEnty);
                    }

                    $userPatch['invoice_amount']  = $amount;
                    $userPatch['cod_deliveryfee'] = $codAmount;
                    $userPatch['payto_driver']    = $userPatch['invoice_amount'] - $userPatch['cod_deliveryfee'];
                    $userPatch['id']              = $driverInvoice->id;
                    $this->DriverInvoices->save($userPatch);
                    $data['id'] = "";
                }

            }
        }
    }


    //-----------------------------------Generate Restaurant Invoice----------------------------------------------------
    public function invoice() {

        $start_date       = '2018-02-01';
        $end_date        = '2018-02-28';

        $restaurantDetails = $this->Restaurants->find('all', [
            'conditions' => [
                'status' => '1',
                'delete_status' => 'N',
                'id' => '7'
            ]
        ])->hydrate(false)->toArray();

        $tax          = $this->siteSettings['vat_percent'];
        $cardfess     = $this->siteSettings['card_fee'];

       if(!empty($restaurantDetails)) {
           foreach ($restaurantDetails as $rKey => $rValue) {

               $invoiceEntity = $this->Invoices->newEntity();

               $orderDetails = $this->Orders->find('all', [
                   'conditions' => [
                       'restaurant_id' => $rValue['id'],
                       'delivery_date >=' => $start_date,
                       'delivery_date <=' => $end_date,
                       'status' => 'Delivered'
                   ]

               ])->hydrate(false)->toArray();

               $Commission = $rValue['restaurant_commission'];

               //echo "<pre>";print_r($orderDetails);

               if(!empty($orderDetails)) {

                   $checking = $this->Invoices->find('all', [
                       'conditions' => [
                           'restaurant_id'   => $rValue['id'],
                           'start_date' => $start_date,
                           'end_date'   => $end_date
                       ]
                   ])->count();


                   if ($checking == 0) {

                       $results = $this->sumOfDetail($orderDetails,$tax,$cardfess);

                       //pr($results);die();

                       /*$grossPay  = $results['card_tax'] + $results['card_price'] +
                           $results['wallet_tax'] + $results['wallet_price'] +
                           $results['deliveryCharge'] + $results['cardTips'];*/

                       $grossPay  = $results['card_tax'] + $results['gross_pay'] +
                           $results['deliveryCharge'] + $results['cardTips'];


                       $sub_total                  = $results['subGrandTotal'];
                       //$grand_total                = $sub_total - ($results['card_tax'] + $results['wallet_tax']);
                       $grand_total                = $sub_total - ($results['card_tax']);
                       $commision                  = $sub_total*($Commission/100);
                       $commisionTotal             = $commision + $results['card_cardfee'];
                       //$commisionTotal             = $commision + $results['card_cardfee'] + $results['wallet_cardfee'];

                       $invoices['tax']            = $tax;
                       $invoices['restaurant_id']  = $rValue['id'];
                       $invoices['subtotal']       = $sub_total;
                       $invoices['end_date']       = $end_date;
                       $invoices['start_date']     = $start_date;
                       $invoices['grand_total']    = $grand_total;
                       $invoices['total_order']    = $results['totalOrder'];


                       // Wallet
                       $invoices['wallet_tax']     = $results['wallet_tax'];
                       $invoices['wallet_count']   = $results['wallet_count'];
                       $invoices['wallet_price']   = $results['wallet_price'];
                       $invoices['wallet_cardfee'] = $results['wallet_cardfee'];

                       // Card
                       $invoices['card_tax']       = $results['card_tax'];
                       $invoices['card_count']     = $results['card_count'];
                       $invoices['card_price']     = $results['card_price'];
                       $invoices['card_cardfee']   = $results['card_cardfee'];

                       //$invoices['cardfee_total']  = $results['wallet_cardfee'] + $results['card_cardfee'];
                       $invoices['cardfee_total']  = $results['card_cardfee'];


                       // Cod
                       $invoices['cod_count']      = $results['cod_count'];
                       $invoices['cod_price']      = $results['cod_price'];

                       // Paypal
                       $invoices['paypal_count']      = $results['paypal_count'];
                       $invoices['paypal_price']      = $results['paypal_price'];

                       $invoices['commision']      = $commision;

                       $invoices['commision_tax']  = $commision_tax = $commisionTotal * ($tax/100);
                       $invoices['commisionGrand'] = $commision_tax + $commisionTotal;

                       $invoices['commissionTotal']    = $commisionTotal;

                       $invoices['restaurant_commission']   = $Commission;
                       $invoices['gross_sale_amount']  = $grossPay;
                       $invoices['restaurant_owned_total']  = $grossPay - ($invoices['commisionGrand']);

                       $invoices['id'] = '';

                       $invoicePatch = $this->Invoices->patchEntity($invoiceEntity,$invoices);

                       $saveInvoice = $this->Invoices->save($invoicePatch);

                       $invoicePatch['id']     = $saveInvoice->id;
                       $invoicePatch['ref_id'] = '#FOS000'. $saveInvoice->id;

                       $this->Invoices->save($invoicePatch);
                   }
               }

           }
       }
    }

    //-----------------------------------Invoice Calculation------------------------------------------------------------

    public function sumOfDetail($detail,$tax,$cardfess){

        $total = $cod = $subtotalCod = $subtotalwalletCod = 0;
        $stripe = $totalOrders = $cardTax = $deliveryCharge = $subtotalCard = 0;
        $wallet = $subtotalWallet = $walletTax = $tips = 0;
        $cardFee = $walletCardFee = 0;
        $paypal = $subtotalPaypal = 0;


        foreach ($detail as $key => $value) {

            $totalOrders++;
            $total += $value['order_sub_total'];

            if($value['payment_method'] == 'cod') {
                $cod++;

                if($value['split_payment'] == 'Yes') {
                    $subtotalCod += $value['order_sub_total'] - $value['wallet_amount'];
                }else {
                    $subtotalCod      += $value['order_sub_total'];
                }

                if($value['split_payment'] == 'Yes') {
                    $wallet++;
                    //$subtotalwalletCod += $value['wallet_amount'];
                    $subtotalWallet += $value['wallet_amount'];
                    $cardTax        += $value['tax_amount'];
                }

            } elseif($value['payment_method'] == 'stripe') {
                $stripe++;
                $tips           += $value['tip_amount'];
                $cardFee        += $value['cardfee_price'];
                //$subtotalCard   += $value['order_sub_total'];
                $cardTax        += $value['tax_amount'];
                $deliveryCharge += $value['delivery_charge'];

                if($value['split_payment'] == 'Yes') {
                    $wallet++;
                    $subtotalCard += $value['order_sub_total'] - $value['wallet_amount'];
                    $subtotalWallet += $value['wallet_amount'];

                    $deliveryCharge += $value['delivery_charge'];
                }else {
                    $subtotalCard      += $value['order_sub_total'];
                }


            }else if($value['payment_method'] == 'paypal') {
                $paypal++;
                $cardTax        += $value['tax_amount'];
                //$subtotalPaypal      += $value['order_sub_total'];

                if($value['split_payment'] == 'Yes') {
                    $wallet++;
                    $subtotalPaypal += $value['order_sub_total'] - $value['wallet_amount'];
                    $subtotalWallet += $value['wallet_amount'];
                }else {
                    $subtotalPaypal      += $value['order_sub_total'];
                }

                $deliveryCharge += $value['delivery_charge'];
            }

            if($value['payment_method'] == 'Wallet') {
                $wallet++;
                $walletCardFee  += $value['cardfee_price'];
                $subtotalWallet += $value['order_sub_total'];

                $walletTax      += $value['tax_amount'];
                $deliveryCharge += $value['delivery_charge'];

                $cardTax        += $value['tax_amount'];

                $deliveryCharge += $value['delivery_charge'];
            }
        }

        // Wallet
        $result['wallet_tax']     = $walletTax;
        $result['wallet_price']   = $subtotalWallet;
        $result['wallet_count']   = $wallet;
        $result['wallet_cardfee'] = $walletCardFee;

        // Card
        //+ $subtotalwalletCod + $subtotalPaypal + $subtotalWallet
        $result['card_tax']       = $cardTax;
        $result['card_price']     = $subtotalCard;
        $result['gross_pay']     = $subtotalCard+ $subtotalwalletCod + $subtotalPaypal + $subtotalWallet;
        $result['card_count']     = $stripe;
        $result['card_cardfee']   = $cardFee;
        $result['cardTips']       = $tips;

        // COD
        $result['cod_count']      = $cod;
        $result['cod_price']      = $subtotalCod;

        // PAYPAL
        $result['paypal_count']      = $paypal;
        $result['paypal_price']      = $subtotalPaypal;

        $result['totalOrder']     = $totalOrders;
        $result['subGrandTotal']  = $total;
        $result['deliveryCharge'] = $deliveryCharge;

        return $result;

    }

    //-----------------------------------Restaurant Facebook Add Functionality -----------------------------------------

    public function faceBookAdd($id = null) {

        if(!empty($id)) {
            if(count($this->request->getQuery('tabs_added')) > 0) {

                foreach($_REQUEST['tabs_added'] as $key => $value){
                    $page_id = $key;
                }

                $restUpdate['id'] = $id;
                $restUpdate['fb_page_id'] = $page_id;

                $restEntity = $this->Restaurants->newEntity($restUpdate);

                $saveRest = $this->Restaurants->save($restEntity);

            } else {

                $this->Flash->error('Unable to add FB Apps');
            }

            if ($this->Auth->User('role_id') == 2) {
                $this->redirect(BASE_URL.'restaurantadmin/restaurants');
            } else {
                $this->redirect(BASE_URL.'foodadmin/restaurants/edit/'.$id);
            }

        } else {
            $this->Flash->error('Unable to add FB Apps');

            if ($this->Auth->User('role_id') == 2) {
                $this->redirect(BASE_URL.'restaurantadmin/restaurants');
            } else {
                $this->redirect(BASE_URL.'foodadmin/restaurants/edit/'.$id);
            }
        }
    }
    //-----------------------------------Google Plus Login Functionality-------------------------------------------------------


    public function social($provider) {

        /* Include the Config File */
        $config  = require_once(ROOT . DS . 'vendor' . DS . 'hybridauth' . DS . 'config.php');
        require_once(ROOT . DS . 'vendor' . DS . 'hybridauth' . DS . 'Hybrid' . DS . 'Auth.php');



        try {
            /* Initiate Hybrid_Auth Function*/
            $hybridauth = new \Hybrid_Auth($config);

            $authProvider = $hybridauth->authenticate($provider);

            $user_profile = $authProvider->getUserProfile();


            /*Modify here as per you needs. This is for demo */
            if ($user_profile && isset($user_profile->identifier)) {

                //************************* CUSTOMER LOGIN SECTION **************************************

                //echo "<pre>";print_r($user_profile);die();

                if ($user_profile->email != '') {

                    $customerCount = $this->Users->find('all', [
                        'conditions' => [
                            'username' => $user_profile->email,
                            'role_id' => '3'
                        ]
                    ])->hydrate(false)->first();

                    $remainingCount = $this->Users->find('all', [
                        'conditions' => [
                            'username' => $user_profile->email,
                            'role_id !=' => '3'
                        ]
                    ])->count();

                    if($remainingCount == 0) {
                        if(!empty($customerCount)) {
                            if($customerCount['status'] == '0') {
                                $this->Flash->error('Your account in deactive');
                                return $this->redirect(BASE_URL.'users/login');
                            }else if($customerCount['deleted_status'] == 'Y') {
                                $this->Flash->error('Your account was deleted. Please contact admin');
                                return $this->redirect(BASE_URL.'users/login');
                            }else {
                                $this->Auth->setUser($customerCount);
                                $this->Flash->success('Login Successful');
                                $currentPage = $this->request->session()->read('currentpage');
                                if($currentPage != 'checkouts') {
                                    return $this->redirect(BASE_URL.'customers');
                                }else {
                                    return $this->redirect(BASE_URL.'checkouts');
                                }
                            }

                        }else {
                            $userData['first_name'] = $user_profile->firstName;
                            $userData['last_name'] = $user_profile->lastName;
                            $userData['username'] = $user_profile->email;


                            $userEnty     = $this->Users->newEntity();
                            $userPatch    = $this->Users->patchEntity($userEnty, $userData);
                            $userPatch->role_id = 3 ;
                            $userPatch->status = 1;
                            $userPatch->password = '123123';
                            $save = $this->Users->save($userPatch);
                            if($save){
                                $this->Flash->success(_('Your Informations Added Successfully'));
                                $this->Auth->setUser($save);
                                $this->Flash->success('Login Successful');
                                $currentPage = $this->request->session()->read('currentpage');
                                if($currentPage != 'checkouts') {
                                    return $this->redirect(BASE_URL.'customers');
                                }else {
                                    return $this->redirect(BASE_URL.'checkouts');
                                }
                            }
                        }
                    }else {
                        $this->Flash->error('Email already used');
                        return $this->redirect(BASE_URL.'users/login');
                    }
                } else {
                    if ($hybridauth->logoutAllProviders()) {
                        $this->Flash->error(_('Only email address'));
                        return $this->redirect(BASE_URL.'users/login');
                    }
                }
            } else {
                if ($hybridauth->logoutAllProviders()) {
                    $this->Flash->error(_('This user denied your request'));
                    return $this->redirect(BASE_URL.'users/login');
                }
                return $this->redirect(BASE_URL.'users/login');
            }
        }catch( Exception $e ) {
            //return 'FB Connection Failed: <b>got an error!</b> error message=' . $e->getMessage() . ', error code='. $e->getCode();
        }
    }

    public function socialRedirect() {
        //$this->layout = false;
        //$this->autoRender = false;
        $config = require_once(ROOT . DS . 'vendor' . DS . 'hybridauth' . DS . 'config.php');
        require_once(ROOT . DS . 'vendor' . DS . 'hybridauth' . DS . 'Hybrid' . DS . 'Auth.php');
        require_once(ROOT . DS . 'vendor' . DS . 'hybridauth' . DS . 'Hybrid' . DS . 'Endpoint.php');
        $hybridauth = new \Hybrid_Auth($config);
        \Hybrid_Endpoint::process();
    }

    //-----------------------------------Google Plus Login Functionality------------------------------------------------


    //-----------------------------------GPRS Printer Functionality-----------------------------------------------------

    public function orderrequest($resid = null) {

        if($resid != ''){

            $orderDetails = $this->Orders->find('all',[
                'conditions' =>[
                    'Orders.restaurant_id' => $resid,
                    'Orders.status' => 'Pending',
                    'Orders.printer_sent' => 'N'
                ],
                'contain' => [
                    'Carts'
                ],
                'order' => [
                    'id' => 'ASC'
                ]
            ])->hydrate(false)->first();

            $ordertxt_item = '';
            if(!empty($orderDetails)) {

                if(!empty($orderDetails['carts'])) {
                    foreach ($orderDetails['carts'] as $key => $value) {

                        $ordertxt_item .= $value['quantity'].";".stripslashes($value['menuname'])." ".$value['subaddons_name'].";".$value['total_price'].";";
                    }
                }

                if ($orderDetails['printer_sent'] == 'N' && $orderDetails['file_write'] == 'N')
                {

                    //$cc_handling    = $taxamount+$tipamount-$orderDiscountPrice;
                    $sms_otype      = ($orderDetails['order_type'] == 'delivery') ? 1 : 2 ;
                    $sms_ctype      = 4;
                    $payment_status      = ($orderDetails['payment_status'] == 'P') ? 6 : 7 ;

                    #Delivery Address
                    if($orderDetails['order_type'] == 'delivery'){

                        $deliveryaddress = $orderDetails['flat_no'].','.$orderDetails['address'];
                    }else{
                        $deliveryaddress = 0;
                    }
                    #Customer Name
                    $customername = stripslashes($orderDetails['customer_name']);
                    #Previous order
                    $prev_order_cnt = $this->Orders->find('all', [
                        'conditions' => [
                            'customer_id' => $this->Auth->user('id'),
                            'restaurant_id' => $orderDetails['restaurant_id'],
                            'status' => 'completed'
                        ]
                    ])->count();
                    #Delivery time
                    $delivery_date_time = $orderDetails['delivery_date']." ".$orderDetails['delivery_time'];

                    $payment_method_type = $orderDetails['payment_mehotd'];

                    #getfood Printer
                    $ordertxt = "#" . $orderDetails['restaurant_id'] . "*" . $sms_otype . "*" . $orderDetails['order_number'] .
                        "*" . $ordertxt_item . "*" . $orderDetails['delivery_charge'] .
                        "*" . $orderDetails['order_grand_total'] . ";" . $sms_ctype .
                        ";" . $customername . ";" . $deliveryaddress .";" . $orderDetails['customer_phone'] .
                        ";" . $delivery_date_time .";" . $prev_order_cnt . ";" . $payment_status . ";" . $payment_method_type .
                        ";*" . stripslashes($orderDetails['order_description']) . "#";

                    //echo "ordertxt=>".$ordertxt;

                    echo $ordertxt;die();
                }
            }
        }
    }

    //-----------------------------------GPRS---------------------------------------------------------------------------
    public function gprsprinter() {
        echo "<pre>";print_r($this->request->getQuery());die();
    }
}
