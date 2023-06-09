<?php
/**
 * Created by NagaRaj.V 
 * Date: 20/Jan/18
 * Time: 19:00 PM
 */
namespace V1\Controller;  

use RestApi\Controller\ApiController;

class DriversController extends ApiController{

    public function initialize(){
     	parent::initialize(); // TODO: Change the autogenerated stub
        $http = (isset($_SERVER["HTTPS"])) ? 'https://' : 'http://';
        $this->BaseUrl   = $http.$_SERVER['HTTP_HOST'].'/foodlove/';
        $this->loadComponent('Auth');
        $this->loadComponent('Common');
        $this->loadModel('Users');
        $this->loadModel('Drivers');
        $this->loadModel('DriverTrackings');
        $this->loadModel('Orders');
        $this->loadModel('Sitesettings');
        $this->loadComponent('IosNotification');
        $this->loadComponent('FcmNotification');
        // Before Login , these are the function we can access
        $this->Auth->allow([
            'login',
            'signup',
            'imageUpload',
            'details',
            'update',
            'location',
            'status',
            'changePassword',
            'logout',
            'authorized',
            'token',
            'orderDisclaim',
            'orderDetail',
        ]);
    }

    public function index(){
    	$this->httpStatusCode = 200;
        $this->apiResponse['you_response'] = 'Invalid option';        
    }
//---------------Driver Login-------------------------------------------------------
    public function login()
    {

       	if($this->request->is(['post','put'])) {
			
	       	$username = $this->request->getData('username');
	        $password = $this->request->getData('password');
	        $deviceType = $this->request->getData('device_type');
	        $deviceId = $this->request->getData('device_id');
			
            if(!empty($username) && !empty($password)) {
                $user = $this->Auth->identify();
                $driver = $this->Drivers->find('all',[
                    'conditions' => [
                        'Drivers.user_id' => $user['id']
                    ]
                ])->hydrate(false)->first();
                //echo "<pre>"; print_r($user); die();
                if(!empty($user) && ($user['role_id'] == 5) && ($driver['delete_status'] == 'N'))
                {
                	
                    $this->Auth->setUser($user);
                    if(!empty($deviceType) && $deviceId) {
                        $userDet = $this->Users->get($user['id']);
                        $data['device_type'] = $deviceType;
                        $data['device_id'] = $deviceId;
                        $userPatch = $this->Users->patchEntity($userDet,$data);
                        $this->Users->save($userPatch);
                    }
                    $response['success'] = 1;
                    $response['user']['id'] = $user['id'];
                    $response['user']['name'] = $user['first_name'].' '.$user['last_name'];
                    $response['user']['first_name'] = $user['first_name'];
                    $response['user']['last_name'] = $user['last_name'];
                    $response['user']['username'] = $user['username'];
                    $response['user']['phone_number'] = $user['phone_number'];
                    $response['message'] = 'Login successfully';

                    $this->apiResponse = $response;
                }else{
                	$response['success'] = 0;
                    $response['message'] = 'Invalid login details';
                }
            }else{
                $response['success'] = 0;
                $response['message'] = 'username / password should not be empty';

                $this->apiResponse = $response;
            }
            
        }else {
            $this->apiResponse = 'access permission denied';
        }
    }
//---------------Driver Signup-------------------------------------------------------
    public function signup(){

    	if($this->request->is(['post','put'])) {  

    		$postData = $this->request->getData();
	        $email    = $postData['username'];
	        $firstName = $postData['first_name'];
	        $lastName = $postData['last_name'];
	        $password = $postData['password'];
	        $phone_number = $postData['phone_number'];
	        $deviceType = $postData['device_type'];
	        $deviceId = $postData['device_id'];
			
	        if(!empty($email) && !empty($firstName) && !empty($lastName) 
	        	&& !empty($password)) 
	        {
                $users = $this->Users->find('all',[
                   'fields'=> [
                       'id'
                   ],
                    'conditions' => [
                        'Users.username' => $phone_number
                    ]
                ])->hydrate(false)->toArray();

                if(count($users) > 0) {
                    $response['success'] = 0;
                    $response['message'] = 'Email already exist';
                }else {
                    $postData['username'] = $phone_number;
                    $postData['role_id']  = 5;
                    $userEntity = $this->Users->newEntity($postData);
                    $result = $this->Users->save($userEntity);
                    
                    if(!empty($result)) {
                    	$data['user_id'] = $result['id'];
                    	$data['phone_number'] = $phone_number;
                    	$data['username'] = $email;
                    	$driverEntity = $this->Drivers->newEntity($data);
                    	$this->Drivers->save($driverEntity);
                        $response['success'] = 1;
                        $response['message'] = 'Users registered successfully';
                    } else {
                        $response['success'] = 0;
                        $response['message'] = 'Required fields are missing';
                    }
                }
            } else {
                $response['success'] = 0;
                $response['message'] = 'Required fields are missing';
            }
        }else {
            $this->apiResponse = 'access permission denied';
        }
    }
//---------------Driver ImageUpload-------------------------------------------------------
    /*public function imageUpload(){
    	if($this->request->is(['post','put'])) {  

    		$postData = $this->request->getData();
	        $driverid    = $postData['driverid'];
	        $image = $postData['image'];
	        $deviceType = $postData['device_type'];

	        
	        if(!empty($image)) 
	        {
	        	// Get image string posted from Android App
                $base = $image;
                if($driver['User']['device_type'] != 'ANDROID') {
                            $imageSrc = str_replace(" ","+",$base);
                } else {
                    $base = explode('\n', $base);
                    $imageSrc = '';
                    foreach ($base as $key => $value) {
                        $imageSrc .= stripslashes($value);
                    }
                }

                // Get file name posted from Android App
	            $fileId = $driver['Driver']['id'].time().'.png';
	            $filename = APP.'webroot/driversImage/'.$fileId;
	            // Decode Image
	            $binary = base64_decode(trim($imageSrc));
	            header('Content-Type: bitmap; charset=utf-8');
	            
	            $file = fopen($filename, 'wb+');
	            // Create File
	            fwrite($file, $binary);
	            fclose($file);
	            #Save Driver Image
	            
	            $driverImage['Driver']['id']    = $driver['Driver']['id'];
	            $driverImage['Driver']['image'] = $fileId;
	            $this->Driver->save($driverImage);
	            
	            $response['success'] = 1;
	            $response['message'] = 'Image uploaded successfully!';
	            $response['driverImage'] = $this->siteUrl.'/driversImage/'.$fileId;
	        } else {
	            $response['success'] = 0;
	            $response['message'] = 'Image not upload!';
	        }
        }
    }*/
//---------------Driver Details-------------------------------------------------------
    public function details(){

    	if($this->request->is(['post','put'])) {  
    		$driverId = $this->request->getData('driverid');
    		$driver = $this->Drivers->find('all',[
                    'conditions' => [
                        'Drivers.id' => $driverId
                    ]
                ])->hydrate(false)->first();
    		$userId = $driver['user_id'];
    		$user = $this->Users->find('all',[
                    'conditions' => [
                        'Users.id' => $userId
                    ]
                ])->hydrate(false)->first();
    		
            if (!empty($driver['image']) && $user['role_id'] == 5) {

                $driverImage = (!empty($driver['image'])) 
                                    ? $this->siteUrl.'/driversImage/'.$driver['image'] 
                                    : $this->siteUrl.'/driversImage/no-photo.png';

                $response['success']        = 1;
                $response['DriverName']     = $driver['driver_name'];
                $response['DriverMail']     = $driver['username'];
                $response['DriverMobile']   = $driver['phone_number'];
                $response['driverImage']   = $driverImage;

            } else {
                $response['success']        = 0;
                $response['message']        = 'Unknown driver';
            }
        }else {
            $this->apiResponse = 'access permission denied';
        }
    }
//---------------Driver Update-------------------------------------------------------
    public function update(){

    	if($this->request->is(['post','put'])) {  
    		$driver['id']             = $this->request->getData('driverid');
            $driver['driver_name']    = $this->request->getData('driverName');
            $driver['username']   = $this->request->getData('driverMail');
            $driver['phone_number']   = $this->request->getData('driverMobile');
            $driverExist  = $this->Drivers->get($driver['id']);
            if (!empty($driverExist)) {
            	$drivers = $this->Drivers->newEntity($driver);
            	$result = $this->Drivers->save($drivers);
                if (!empty($result)) {
                    $response['success'] = 1;
                    $response['message'] = 'Updated successfully!';
                } else {
                    $response['success'] = 0;
                    $response['message'] = 'Details not updated!';
                }
            } else {
                $response['success'] = 0;
                $response['message'] = 'Unknown driver!';
            }
        }else {
            $this->apiResponse = 'access permission denied';
        }
    }
//---------------Driver Location-------------------------------------------------------
    public function location(){

    	if($this->request->is(['post','put'])) {  
    		$latitude  = $this->request->getData('latitude');
            $longitude = $this->request->getData('longitude');
            $status    = ['Pending','Deleted','Delivered'];
            $driverId  = $this->request->getData('driverid');
            //Send notification for customer
            $orders = $this->Orders->find('all', [
                            'fields' => ['Orders.id','Orders.status'],
                            'conditions' => ['Orders.driver_id' => $driverId,
                                                 'NOT' => [
                                                    'Orders.status' => $status
                                                    ]
                                            ]
                            ])->hydrate(false)->first();
            if (!empty($orders)) {
                $driverLocation['latitude']  = $latitude;
                $driverLocation['longitude'] = $longitude;
                $driverLocation['angle']     = '';
                

                foreach ($orders as $key => $value) {
                    $orderId = $value['Order']['id'];
                    $driverLocation['order_id']  = $orderId;  
                    $driverLocation['status']    = $value['Order']['status'];                         
                    $this->Notifications->trackNotification($driverLocation, 'track_'.$orderId);
                }
            }                    
            
            if ($this->request->data['driverid']) {

                $driverTrack = $this->DriverTracking->findByDriverId($driverId);

                $tracking['id']        = ($driverTrack['DriverTracking']['id'] != '') ? $driverTrack['DriverTracking']['id'] : '';
                $tracking['driver_id'] = $driverId;
                $tracking['driver_latitude']  = $latitude;
                $tracking['driver_longitude'] = $longitude;
                
                $trackResult = $this->DriverTracking->save($tracking);

                $response['success'] = ($trackResult['DriverTracking']['id'] != '') ? 1 : 0;
            } else {
                $response['success'] = 0;
            }
        }else {
            $this->apiResponse = 'access permission denied';
        }
    }
//---------------Driver Status-------------------------------------------------------
    public function status(){

    	if($this->request->is(['post','put'])) {  
    		$driverId  = $this->request->getData('driverid');
            $status    = $this->request->getData('status');
            $driver = $this->Drivers->find('all',[
                    'conditions' => [
                        'Drivers.id' => $driverId
                    ]
                ])->hydrate(false)->first();
            
            if (!empty($driver)) {
            	
                $driver['driver_status'] = $status;
                $this->Driver->save($driver);

                $response['success'] = 1;
                $response['message'] = 'Status changed';

                if (strtolower($status) == 'end of shift') {
                    $message = $status.' for '.$driver['Driver']['driver_name'];
                } elseif (strtolower($status) == 'on break') {
                    $message = $driver['Driver']['driver_name']." is On a break";
                } else {
                    $message = $driver['Driver']['driver_name']." is ".$status;
                }

                $this->Notifications->pushNotification($message, 'Foodadmin');
                $this->Notifications->pushNotification($message, 'Restaurantadmin_'.$driver['user_id']);
                
            } else {
                $response['success'] = 0;
                $response['message'] = 'Status is not change';
            }
        }else {
            $this->apiResponse = 'access permission denied';
        }
    }
//---------------Driver Waiting Order Count-----------------------------------------------
    public function waitingOrders(){

    	if($this->request->is(['post','put'])) {  
    		$status   = 'Waiting';
            $currentDate = date('Y-m-d');

            $driverId = $this->request->getData('driverid');
            $order    = $this->Order->find('all', [
                                    'conditions' => [
                                                'Order.driver_id' => $driverId,
                                                'Order.created LIKE' => $currentDate.'%',
                                                'Order.status' => $status],
                                    'order' => 'Order.id Desc']);

            if (!empty($order)) {
                $orderDetails = [];
                foreach ($order as $key => $value) {

                    $datetime1 = new DateTime(date('Y-m-d G:i:s'));
                    $datetime2 = new DateTime($value['Order']['updated']);
                    $interval  = $datetime1->diff($datetime2);
                    $hour      = $interval->format('%H');
                    $min       = $interval->format('%I');
                    $sec       = $interval->format('%S');
                    $day       = $interval->format('%D');

                    if ($this->siteSetting['Sitesetting']['address_mode'] != 'Google') {
                        if ($this->siteSetting['Sitesetting']['search_by'] == 'zip') {

                            $storeAddress = $value['Store']['street_address'] . ', ' .
                                $this->storeCity[$value['Store']['store_city']] . ', ' .
                                $this->storeState[$value['Store']['store_state']] . ' ' .
                                $this->storeLocation[$value['Store']['store_zip']] . ', ' .
                                $this->siteSetting['Country']['country_name'];


                        } else {
                            $storeAddress = $value['Store']['street_address'] . ', ' .
                                $this->storeLocation[$value['Store']['store_zip']] . ', ' .
                                $this->storeCity[$value['Store']['store_city']] . ', ' .
                                $this->storeState[$value['Store']['store_state']] . ', ' .
                                $this->siteSetting['Country']['country_name'];
                        }
                    } else {
                        $storeAddress = $value['Store']['address'];
                    }


                    $orderDetails[$key]['StoreName']              = stripslashes($value['Store']['store_name']);
                    $orderDetails[$key]['SourceAddress']          = $storeAddress;
                    $orderDetails[$key]['SourceLatitude']         = $value['Order']['source_latitude'];
                    $orderDetails[$key]['SourceLongitude']        = $value['Order']['source_longitude'];
                    $orderDetails[$key]['DestinationAddress']     =
                        ($this->siteSetting['Sitesetting']['address_mode'] != 'Google') ?
                        $value['Order']['address'].', '.
                        $value['Order']['location_name'].', '.
                        $value['Order']['city_name'].', '.
                        $value['Order']['state_name'] : $value['Order']['google_address'];
                    $orderDetails[$key]['LandMark']               = $value['Order']['landmark'];
                    $orderDetails[$key]['DestinationLatitude']    = $value['Order']['destination_latitude'];
                    $orderDetails[$key]['DestinationLongitude']   = $value['Order']['destination_longitude'];
                    $orderDetails[$key]['Created']                = $value['Order']['created'];
                    $orderDetails[$key]['OrderDate']              = $value['Order']['delivery_date'];
                    $orderDetails[$key]['OrderTime']              =
                        ($value['Order']['assoonas'] == 'Later') ?
                            $value['Order']['delivery_time'] : 'ASAP';
                    $orderDetails[$key]['OrderPrice']             = $value['Order']['order_grand_total'];
                    $orderDetails[$key]['OrderId']                = $value['Order']['id'];
                    $orderDetails[$key]['OrderGenerateId']        = $value['Order']['ref_number'];
                    $orderDetails[$key]['OrderStatus']            = $value['Order']['status'];
                    $orderDetails[$key]['CustomerName']           = $value['Order']['customer_name'];
                    $orderDetails[$key]['PaymentType']            = $value['Order']['payment_type'];
                    $orderDetails[$key]['Day']                    = $day;
                    $orderDetails[$key]['Hour']                   = $hour;
                    $orderDetails[$key]['Min']                    = $min;
                    $orderDetails[$key]['Sec']                    = $sec;
                }
                $response['success'] = 1;
                $response['orders']  = $orderDetails;
            } else {
                $response['success'] = 0;
                $response['message'] = 'No record(s) found';
            }
        }else {
            $this->apiResponse = 'access permission denied';
        }
    }
//---------------Driver Waiting Orders---------------------------------------------------
    public function waitingOrdesrs(){

    	if($this->request->is(['post','put'])) {  
    		$status   = 'Waiting';
            $currentDate = date('Y-m-d');

            $driverId = $this->request->getData('driverid');
            $order    = $this->Order->find('all', [
                                    'conditions' => [
                                                'Order.driver_id' => $driverId,
                                                'Order.created LIKE' => $currentDate.'%',
                                                'Order.status' => $status],
                                    'order' => 'Order.id Desc']);

            if (!empty($order)) {
                $orderDetails = [];
                foreach ($order as $key => $value) {

                    $datetime1 = new DateTime(date('Y-m-d G:i:s'));
                    $datetime2 = new DateTime($value['Order']['updated']);
                    $interval  = $datetime1->diff($datetime2);
                    $hour      = $interval->format('%H');
                    $min       = $interval->format('%I');
                    $sec       = $interval->format('%S');
                    $day       = $interval->format('%D');

                    if ($this->siteSetting['Sitesetting']['address_mode'] != 'Google') {
                        if ($this->siteSetting['Sitesetting']['search_by'] == 'zip') {

                            $storeAddress = $value['Store']['street_address'] . ', ' .
                                $this->storeCity[$value['Store']['store_city']] . ', ' .
                                $this->storeState[$value['Store']['store_state']] . ' ' .
                                $this->storeLocation[$value['Store']['store_zip']] . ', ' .
                                $this->siteSetting['Country']['country_name'];


                        } else {
                            $storeAddress = $value['Store']['street_address'] . ', ' .
                                $this->storeLocation[$value['Store']['store_zip']] . ', ' .
                                $this->storeCity[$value['Store']['store_city']] . ', ' .
                                $this->storeState[$value['Store']['store_state']] . ', ' .
                                $this->siteSetting['Country']['country_name'];
                        }
                    } else {
                        $storeAddress = $value['Store']['address'];
                    }


                    $orderDetails[$key]['StoreName']              = stripslashes($value['Store']['store_name']);
                    $orderDetails[$key]['SourceAddress']          = $storeAddress;
                    $orderDetails[$key]['SourceLatitude']         = $value['Order']['source_latitude'];
                    $orderDetails[$key]['SourceLongitude']        = $value['Order']['source_longitude'];
                    $orderDetails[$key]['DestinationAddress']     =
                        ($this->siteSetting['Sitesetting']['address_mode'] != 'Google') ?
                        $value['Order']['address'].', '.
                        $value['Order']['location_name'].', '.
                        $value['Order']['city_name'].', '.
                        $value['Order']['state_name'] : $value['Order']['google_address'];
                    $orderDetails[$key]['LandMark']               = $value['Order']['landmark'];
                    $orderDetails[$key]['DestinationLatitude']    = $value['Order']['destination_latitude'];
                    $orderDetails[$key]['DestinationLongitude']   = $value['Order']['destination_longitude'];
                    $orderDetails[$key]['Created']                = $value['Order']['created'];
                    $orderDetails[$key]['OrderDate']              = $value['Order']['delivery_date'];
                    $orderDetails[$key]['OrderTime']              =
                        ($value['Order']['assoonas'] == 'Later') ?
                            $value['Order']['delivery_time'] : 'ASAP';
                    $orderDetails[$key]['OrderPrice']             = $value['Order']['order_grand_total'];
                    $orderDetails[$key]['OrderId']                = $value['Order']['id'];
                    $orderDetails[$key]['OrderGenerateId']        = $value['Order']['ref_number'];
                    $orderDetails[$key]['OrderStatus']            = $value['Order']['status'];
                    $orderDetails[$key]['CustomerName']           = $value['Order']['customer_name'];
                    $orderDetails[$key]['PaymentType']            = $value['Order']['payment_type'];
                    $orderDetails[$key]['Day']                    = $day;
                    $orderDetails[$key]['Hour']                   = $hour;
                    $orderDetails[$key]['Min']                    = $min;
                    $orderDetails[$key]['Sec']                    = $sec;
                }
                $response['success'] = 1;
                $response['orders']  = $orderDetails;
            } else {
                $response['success'] = 0;
                $response['message'] = 'No record(s) found';
            }
        }else {
            $this->apiResponse = 'access permission denied';
        }
    }
//---------------Driver Order Detail-------------------------------------------------------
    public function orderDetail(){

    	if($this->request->is(['post','put'])) {  
    		$orderId = $this->request->getData('orderid');
            if ($orderId != '') {
                $orderDetails = $this->Orders->get($orderId);
                echo $this->siteSettings; die();
                $orderDet['success'] = '1';

                if ($this->siteSettings['Sitesetting']['address_mode'] != 'Google') {
                    if ($this->siteSettings['Sitesetting']['search_by'] == 'zip') {
                        $storeAddress = $orderDetails['Store']['street_address'] . ', ' .
                            $this->storeCity[$orderDetails['Store']['store_city']] . ', ' .
                            $this->storeState[$orderDetails['Store']['store_state']] . ' ' .
                            $this->storeLocation[$orderDetails['Store']['store_zip']] . ', ' .
                            $this->siteSetting['Country']['country_name'];

                    } else {
                        $storeAddress = $orderDetails['Store']['street_address'] . ', ' .
                            $this->storeLocation[$orderDetails['Store']['store_zip']] . ', ' .
                            $this->storeCity[$orderDetails['Store']['store_city']] . ', ' .
                            $this->storeState[$orderDetails['Store']['store_state']] . ', ' .
                            $this->siteSettings['Country']['country_name'];
                    }
                } else {
                    $storeAddress = $orderDetails['Store']['address'];
                }


                $orderDet['orderId']                = $orderDetails['Order']['ref_number'];
                $orderDet['customerName']           = stripslashes($orderDetails['Order']['customer_name']);
                $orderDet['customerAddress']        =
                    ($this->siteSettings['Sitesetting']['address_mode'] != 'Google') ?
                        $orderDetails['Order']['address'].', '.
                        $orderDetails['Order']['location_name'].', '.
                        $orderDetails['Order']['city_name'].', '.
                        $orderDetails['Order']['state_name'] : $orderDetails['Order']['google_address'];
                $orderDet['customerEmail']          = $orderDetails['Order']['customer_email'];
                $orderDet['customerPhone']          = $orderDetails['Order']['customer_phone'];
                $orderDet['StoreName']              = stripslashes($orderDetails['Store']['store_name']);
                $orderDet['SourceAddress']          = $storeAddress;
                $orderDet['SourceLatitude']         = $orderDetails['Order']['source_latitude'];
                $orderDet['SourceLongitude']        = $orderDetails['Order']['source_longitude'];
                $orderDet['LandMark']               = $orderDetails['Order']['landmark'];
                $orderDet['DestinationLatitude']    = $orderDetails['Order']['destination_latitude'];
                $orderDet['DestinationLongitude']   = $orderDetails['Order']['destination_longitude'];
                $orderDet['OrderDate']              = $orderDetails['Order']['delivery_date'];
                $orderDet['OrderTime']              = $orderDetails['Order']['delivery_time'];
                $orderDet['OrderPrice']             = $orderDetails['Order']['order_grand_total'];
                $orderDet['OrderId']                = $orderDetails['Order']['id'];
                $orderDet['order_description']      = $orderDetails['Order']['order_description'];
                $orderDet['OrderGenerateId']        = $orderDetails['Order']['ref_number'];
                $orderDet['CustomerName']           = $orderDetails['Order']['customer_name'];
                $orderDet['PaymentType']            = $orderDetails['Order']['payment_type'];
                $orderDet['offer']                  = $orderDetails['Order']['offer_amount'];
                $orderDet['offerPercentage']        = $orderDetails['Order']['offer_percentage'];
                $orderDet['tax']                    = $orderDetails['Order']['tax_amount'];
                $orderDet['taxPercentage']          = $orderDetails['Order']['tax_percentage'];
                $orderDet['tipAmount']              = $orderDetails['Order']['tip_amount'];
                $orderDet['deliveryCharge']         = $orderDetails['Order']['delivery_charge'];
                $orderDet['voucherPercentage']      = $orderDetails['Order']['voucher_percentage'];
                $orderDet['voucherAmount']          = $orderDetails['Order']['voucher_amount'];
                $orderDet['subTotal']               = $orderDetails['Order']['order_sub_total'];
                $orderDet['total']                  = $orderDetails['Order']['order_grand_total'];
                $orderDet['status']                 = $orderDetails['Order']['status'];
                $orderDet['orderMenu']              = stripslashes_deep($orderDetails['ShoppingCart']);
                
                $response = $orderDet;
            } else {
                $response['success'] = '0';
                $response['message'] = 'There is no order(s)!';
            }
        }else {
            $this->apiResponse = 'access permission denied';
        }
    }
//---------------Driver Order Disclaim-------------------------------------------------------
    public function orderDisclaim(){

    	if($this->request->is(['post','put'])) {  
    		$orderId    = $this->request->getData('orderid');
            $driverId   = $this->request->getData('driverid');
            $latitude   = $this->request->getData('latitude');
            $longitude  = $this->request->getData('longitude');
            $reason     = $this->request->getData('reason');

            if(strtolower($reason) != 'traffic') {

                $orderDetails = $this->Orders->get($orderId);
                $order['id']        = $orderId;
                $order['status']    = 'Accepted';
                $order['driver_id'] = 0;
                
                $this->Orders->save($order);
                
                $orderStatus['id']         = '';
                $orderStatus['order_id']   = $orderId;
                $orderStatus['driver_id']  = $driverId;
                $orderStatus['status']     = 'Accepted';
                $orderStatus['driver_latitude']   = $latitude;
                $orderStatus['driver_longitude']  = $longitude;

                $this->Orderstatus->save($orderStatus);

                //Push Notification
                $message = $orderDetails['ref_number'].' is rejected by '.$orderDetails['driver_name'];
                $this->Notifications->pushNotification($message, 'FoodOrderAdmin');
                $this->Notifications->pushNotification($message, 'Restaurantadmin_'.$orderDetails['store_id']);
            }
            $response['success'] = '1';
        }else {
            $this->apiResponse = 'access permission denied';
        }
    }
//---------------Driver Logout-------------------------------------------------------
    public function logout(){

    	if($this->request->is(['post','put'])) {  
    		$driverId = $this->request->getData('driverid');
            if ($driverId != '') {
                $driver = $this->Drivers->get($driverId);
                $userId = $driver['user_id'];
                $notificationdata['data']['title']          = "logout";
                $notificationdata['data']['message']        = "logout";
                $notificationdata['data']['is_background']  = false;                
                $notificationdata['data']['payload']        = ['OrderDetails' => "",'type'    => "logout"];
                $notificationdata['data']['timestamp']      = date('Y-m-d G:i:s');
                $driver = $this->Users->get($userId);
                if ($this->request->getData('from') == 'site') {
                    $gcm    = (trim($driver['device_type']) == 'Android') ?
                                    $this->FcmNotification->sendNotification($notificationdata, $driver['device_id']) : 
                                    $this->IosNotification->notificationIOS('logout', $driver['device_id']);
                }

                $drivers['id'] = $driver['id'];
                //$drivers['is_logged'] = '0';
                //$drivers['driver_status']  = 'Offline';
                //$drivers['device_id']      = '';
                //$drivers['login_time']      = '';
                $this->DriverTrackings->deleteAll(['DriverTrackings.driver_id'=>$driver['id']]);
                $driverLogout = $this->Drivers->save($drivers);
                
                $response['success'] = '1';
                $response['message'] = 'Successfully logout ';
                
                $message = $driver['driver_name']." loggedout";
                $this->Notifications->pushNotification($message, 'Foodadmin');
                $this->Notifications->pushNotification($message, 'Restaurantadmin_'.$driver['user_id']);
            } else {
                $response['success'] = '0';
                $response['message'] = 'Try Again..!';
            }
        }else {
            $this->apiResponse = 'access permission denied';
        }
    }
//---------------Driver Token-------------------------------------------------------
    public function token(){

    	if($this->request->is(['post','put'])) {  
    		$driverId  = $this->request->getData('driverId');
            $deviceId    = $this->request->getData('deviceId');
            $driver    = $this->Drivers->get($driverId);
            if (!empty($driver)) {
            	$userId = $driver['user_id'];
            	$userEntity = $this->Users->get($userId);
                $user['device_id'] = $deviceId;
                $userPatch = $this->Users->patchEntity($userEntity,$user);
                $this->Users->save($userPatch);
                $response['success'] = 1;
                $response['message'] = 'device token updated';
                
            } else {
                $response['success'] = 0;
                $response['message'] = 'device token is not change';
            }
        }else {
            $this->apiResponse = 'access permission denied';
        }
    }
//---------------Driver Authorized-------------------------------------------------------
    public function authorized(){

    	if($this->request->is(['post','put'])) {  
    		$driverId   = $this->request->getData('driverId');
            $driver = $this->Drivers->get($driverId);
            if (!empty($driver)) {
                /*if ($driver['is_logged'] == 1) {
                    $response['success'] = 1;
                    $response['message'] = 'Authorized Person';
                } else {
                    $response['success'] = 0;
                    $response['message'] = 'Unauthorized Person';
                }*/
            } else {
                $response['success'] = 0;
                $response['message'] = 'Missing driver id';
            }
        }else {
            $this->apiResponse = 'access permission denied';
        }
    }
//---------------Driver ChangePassword-------------------------------------------------------
    public function changePassword(){

    	if($this->request->is(['post','put'])) {  
    		$driverId     = $this->request->getData('driver_id');
            $user['password'] = $this->request->getData('password');
            if ($driverId != '' && $user['password'] != '') {
                $driver = $this->Drivers->get($driverId);
                $userId = $driver['user_id'];
                $userExist = $this->Users->get($userId);
                $userPatch = $this->Users->patchEntity($userExist,$user);
                if ($this->Users->save($userPatch)) {
                    $response['success'] = '1';
                    $response['message'] = 'Changed password successfully';
                }
            } else {
                $response['success'] = '0';
                $response['message'] = 'Try Again..!';
            }
        }else {
            $this->apiResponse = 'access permission denied';
        }
    }
}
