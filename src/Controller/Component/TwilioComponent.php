<?php
/**
 * Created by PhpStorm.
 * User: roamadmin
 * Date: 16-02-2018
 * Time: 17:28
 */
namespace App\Controller\Component;
use App\Controller\AppController;
use cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\Mailer\Email;

require_once(ROOT . DS . 'vendor' . DS . 'twilio'. DS . 'Twilio'. DS . 'autoload.php');

class TwilioComponent extends Component
{
    /*Send SMS Using Twilio Component*/

    public function sendMessage($to,$message) {

        $sid = SMS_SID; // Your SID from www.twilio.com/console
        $token = SMS_TOKEN; // Your Auth Token from www.twilio.com/console

        $client = new \Twilio\Rest\Client($sid, $token);
        $message = $client->messages->create(
            $to,
            array(
                'from' => SMS_FROM, // From a valid Twilio number
                'body' => $message
            )
        );
    }

}