<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 12/3/2017
 * Time: 10:32 PM
 */
namespace App\Controller\Component;
use App\Controller\AppController;
use cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\Mailer\Email;

require_once (ROOT . DS . 'vendor'. DS . 'Pusher.php');
class PushNotificationComponent extends Component
{

    /*Web Push Notification*/
    public function pushNotification($message,$id = null) {


        $options = array(
            'encrypted' => true
        );
        $pusher = new \Pusher(
            PUSHER_AUTHKEY,
            PUSHER_SECRET,
            PUSHER_APPID,
            $options
        );

        $data['message'] = $message;
        $data['id'] = $id;
        $pusher->trigger('my-channel', 'my-event', $data);


    }

    /*Send Notification For Tracking Map*/
    public function trackingNotification($ordernumber= null,$status = null) {


        $options = array(
            'encrypted' => true
        );
        $pusher = new \Pusher(
            PUSHER_AUTHKEY,
            PUSHER_SECRET,
            PUSHER_APPID,
            $options
        );

        if($ordernumber != '') {
            $data['message'] = 'Order '.$ordernumber.' was '.$status;
        }else {
            $data['message'] = 'Test';
        }
        $data['status'] = $status;
        $pusher->trigger('my-channelTrack', 'my-eventTrack', $data);
    }

    /*Reject Notification for Dispatch Page*/
    public function rejectNotification($ordernumber= null) {


        $options = array(
            'encrypted' => true
        );
        $pusher = new \Pusher(
            PUSHER_AUTHKEY,
            PUSHER_SECRET,
            PUSHER_APPID,
            $options
        );

        $data['id'] = $ordernumber;
        $pusher->trigger('my-channelReject', 'my-eventReject', $data);
    }

    /*Send Order Notification For Customer*/
    public function customerNotification($customerId= null,$restaurantId = null,$message = null) {


        $options = array(
            'encrypted' => true
        );
        $pusher = new \Pusher(
            PUSHER_AUTHKEY,
            PUSHER_SECRET,
            PUSHER_APPID,
            $options
        );

        $data['customer_id'] = $customerId;
        $data['restaurant_id'] = $restaurantId;
        $data['message'] = $message;
        $pusher->trigger('my-channelCustomer', 'my-eventCustomer', $data);
    }

    /*Send Order Notification For Driver*/
    public function driverNotification($message= null,$createdId= null) {


        $options = array(
            'encrypted' => true
        );
        $pusher = new \Pusher(
            PUSHER_AUTHKEY,
            PUSHER_SECRET,
            PUSHER_APPID,
            $options
        );
        $data['message'] = $message;
        $data['createdId'] = $createdId;
        $pusher->trigger('my-channelDriver', 'my-eventDriver', $data);
    }

}