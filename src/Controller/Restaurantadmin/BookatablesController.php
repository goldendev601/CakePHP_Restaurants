<?php

namespace App\Controller\Restaurantadmin;


use Cake\Controller\Controller;
use App\Controller\AppController;
use Cake\Mailer\Email;
use Cake\Event\Event;
use Cake\Utility\Hash;


class BookaTablesController extends AppController
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
        $this->loadComponent('Common');
        $this->viewBuilder()->setLayout('restaurant');
        $this->loadModel('Restaurants');
        $this->loadModel('Cuisines');
        $this->loadModel('DeliverySettings');
        $this->loadModel('Areamaps');
        $this->loadModel('Sitesettings');
        $this->loadModel('Bookings');
    }

    public function beforeFilter(Event $event)
    {
        // Before Login , these are the function we can access
        $this->Auth->allow([
            'login',
            'index',
            'bookaTable',
            'bookatableDetails'
        ]);
    }

    // Book a table store index
    public function index() {
        //$this->layout  = 'assets';
        $userId = $this->Auth->User();

        $restDetails = $this->Restaurants->find('all', [
               'conditions' => [
                'user_id' => $userId['id']
            ],
            'contain' => [
                'DeliverySettings',
                'Areamaps'
            ]
        ])->hydrate(false)->first();
        $resId = $restDetails['id'];
        
        $bookaTables =  $this->Bookings->find('all',[
                                'conditions' => [
                                'restaurant_id' => $resId
                            ],
                                'order' => 'id ASC'
        ])->hydrate(false)->toArray();
        //pr($bookaTables);die;

        $status = ['Pending' => 'Pending', 'Approved' => 'Approved', 'Cancel' => 'Cancel'];
        $this->set(compact('bookaTables', 'status'));

    }


     //Booking a table in Restaurant
    public function bookaTable() {
        $data = $this->Common->parseSerialize($this->request->getdata('formData'));
        

        if (!empty($data)) {
            $bookEntity = $this->Bookings->newEntity();
            $bookPatch  = $this->Bookings->patchEntity($bookEntity,$data);
            $bookUpdatePatch1['restaurant_id'] = $data['resId'];
            $result = $this->Bookings->save($bookPatch);
           
           
            $bookUpdatePatch1['booking_id'] = '#Book000'.$result['id'];
            $bookUpdatePatch1['id'] = $result['id'];
            $bookEntity1 = $this->Bookings->newEntity();
            $bookupPatch  = $this->Bookings->patchEntity($bookEntity1,$bookUpdatePatch1);
            $this->Bookings->save($bookupPatch);
           
            echo 'Success';
        }
        exit();
    }
    // Book a table change status 
    public function bookStatus() {

        $bookStatus['id']            = $this->request->getData('bookId');
        $bookStatus['status']        = $this->request->getData('status');
        $bookStatus['cancel_reason'] = $this->request->getData('reason');

        $bookEntity = $this->Bookings->newEntity();
        $bookPatch  = $this->Bookings->patchEntity($bookEntity,$bookStatus);

        if ($this->Bookings->save($bookPatch)) {
            //$this->bookaTableMail($this->BookaTable->id);
            echo "Success";
        }
         exit();

    }
    /*Book a Table Details*/
    public function bookatableDetails() {

        $bookDetails = $this->Bookings->find('all', [
               'conditions' => [
                'id' => $this->request->getData('bookId')
            ]
        ])->first();
        //pr($bookDetails);die;
        $this->set(compact('bookDetails'));
    }

    // Book a table detail mail
    public function bookaTableMail($bookaTableId) {

        $bookTableDeatails = $this->BookaTable->findById($bookaTableId);

        $Customer   = $bookTableDeatails['BookaTable']['customer_name'];
        $Restaurant = $bookTableDeatails['Store']['store_name'];
        $custMail   = $bookTableDeatails['BookaTable']['booking_email'];
        $status     = $bookTableDeatails['BookaTable']['status'];
        $storeMail  = $bookTableDeatails['Store']['contact_email'];
        $custPhone  = $bookTableDeatails['BookaTable']['booking_phone'];
        $storePhone = $bookTableDeatails['Store']['contact_phone'];
        $bookingId  = $bookTableDeatails['BookaTable']['booking_id'];


        $emailinfo='<table>
                    <tr style="display:block; width:610px; margin-bottom:15px;font:13px Arial, Helvetica, sans-serif;">
                        <td style="display:inline-block; width:185px;vertical-align: top;">Restaurant Name</td>
                        <td style="display:inline-block; width:50px; text-align:center;">:</td>
                        <td style="display:inline-block; width:350px; color:#ee541e;">'.$Restaurant.'</td>
                    </tr>
                    <tr style="display:block; width:610px; margin-bottom:15px;font:13px Arial, Helvetica, sans-serif;">
                        <td style="display:inline-block; width:185px;vertical-align: top;">Book Table id</td>
                        <td style="display:inline-block; width:50px; text-align:center;">:</td>
                        <td style="display:inline-block; width:350px; color:#ee541e;">'.$bookingId.'</td>
                    </tr>
                    <tr style="display:block; width:610px; margin-bottom:15px;font:13px Arial, Helvetica, sans-serif;">
                        <td style="display:inline-block; width:185px;vertical-align: top;">Guest Count</td>
                        <td style="display:inline-block; width:50px; text-align:center;">:</td>
                        <td style="display:inline-block; width:350px; color:#ee541e;">'.$bookTableDeatails['BookaTable']['guest_count'].'</td>
                    </tr>
                    <tr style="display:block; width:610px; margin-bottom:15px;font:13px Arial, Helvetica, sans-serif;">
                        <td style="display:inline-block; width:185px;vertical-align: top;">Customer Name</td>
                        <td style="display:inline-block; width:50px; text-align:center;">:</td>
                        <td style="display:inline-block; width:350px; color:#ee541e;">'.$Customer.'</td>
                    </tr> 
                    <tr style="display:block; width:610px; margin-bottom:15px;font:13px Arial, Helvetica, sans-serif;">
                        <td style="display:inline-block; width:185px;vertical-align: top;">Customer Email</td>
                        <td style="display:inline-block; width:50px; text-align:center;">:</td>
                        <td style="display:inline-block; width:350px; color:#ee541e;">'.$custMail.'</td>
                    </tr> 
                    <tr style="display:block; width:610px; margin-bottom:15px;font:13px Arial, Helvetica, sans-serif;">
                        <td style="display:inline-block; width:185px;vertical-align: top;">Customer Phone</td>
                        <td style="display:inline-block; width:50px; text-align:center;">:</td>
                        <td style="display:inline-block; width:350px; color:#ee541e;">'.$custPhone.'</td>
                    </tr>';

        if (!empty($bookTableDeatails['BookaTable']['booking_instruction'])) {
            $emailinfo .='
                    <tr style="display:block; width:610px; margin-bottom:15px;font:13px Arial, Helvetica, sans-serif;">
                        <td style="display:inline-block; width:185px;vertical-align: top;">Instruction</td>
                        <td style="display:inline-block; width:50px; text-align:center;">:</td>
                        <td style="display:inline-block; width:350px; color:#ee541e;">'.$bookTableDeatails['BookaTable']['booking_instruction'].'</td>
                    </tr>';
        }

        $emailinfo .='
                    <tr style="display:block; width:610px; margin-bottom:15px;font:13px Arial, Helvetica, sans-serif;">
                        <td style="display:inline-block; width:185px;vertical-align: top;">Status</td>
                        <td style="display:inline-block; width:50px; text-align:center;">:</td>
                        <td style="display:inline-block; width:350px; color:#ee541e;">'.$status.'</td>
                    </tr>';


        if ($status == 'Cancel') {
            $emailinfo .='
                    <tr style="display:block; width:610px; margin-bottom:15px;font:13px Arial, Helvetica, sans-serif;">
                        <td style="display:inline-block; width:185px;vertical-align: top;">Cancel Reason</td>
                        <td style="display:inline-block; width:50px; text-align:center;">:</td>
                        <td style="display:inline-block; width:350px; color:#ee541e;">'.$bookTableDeatails['BookaTable']['cancel_reason'].'</td>
                    </tr>';
        }

        $emailinfo .='</table>';

        $mailContent     = $emailinfo;
        $customerSubject = 'Your book a table '.$bookingId.' status '.$status;
        $storeSubject    = $Customer.' book a table '.$bookingId.' details';
        $source          = $this->siteUrl.'/siteicons/logo.png';
        $siteName        = $this->siteSetting['Sitesetting']['site_name'];

        /*$email = new CakeEmail(array(
                      'transport' => 'Mandrill.Mandrill'
                    ));*/
        $email = new CakeEmail();
        $email->setFrom($storeMail);
        $email->setTo($custMail);
        $email->setSubject($customerSubject);
        $email->setTemplate('default');
        $email->setEmailFormat('html');
        $email->setViewVars(array('mailContent' => $mailContent,
                               'source' => $source,
                               'storename' => $siteName));

        //$email->send();

        if ($status == 'Pending') {

            $storeMessage   = $Customer.' booking a table. Booking id '. $bookingId;
            $tostoreNumber  = '+'.$this->siteSetting['Country']['phone_code'].$storePhone;
            if($this->siteSetting['Sitesetting']['sms_option'] == 'Yes'){  
                $storeSms       = $this->Twilio->sendSingleSms($tostoreNumber, $storeMessage);
            }

            /*$email = new CakeEmail(array(
                      'transport' => 'Mandrill.Mandrill'
                    ));*/
            $email = new CakeEmail();
            $email->setFrom($custMail);
            $email->setTo($storeMail);
            $email->setSubject($storeSubject);
            $email->setTemplate('register');
            $email->setEmailFormat('html');
            $email->setViewVars(array('mailContent' => $mailContent,
                                   'source' => $source,
                                   'storename' => $siteName));

            $email->send();
        }


        // Book a table Sms
        if ($status == 'Pending') {
            $customerMessage  = 'Your table booked successfully.';
        } else {
            $customerMessage  = 'Your booked table has been '.strtolower($status);
            $customerMessage .= ($status == 'Cancel') ? 'ed.' : '.';
        }

        if ($status == 'Cancel') {
            $customerMessage .= ' reason : '.$bookTableDeatails['BookaTable']['cancel_reason'].'.';
        }

        $customerMessage .= ' booking id '.$bookingId.'.  Regards '.$siteName.'.';
        
        $toCustomerNumber = '+'.$this->siteSetting['Country']['phone_code'].$custPhone;
        if($this->siteSetting['Sitesetting']['sms_option'] == 'Yes'){  
            $customerSms      = $this->Twilio->sendSingleSms($toCustomerNumber, $customerMessage);
        }

        return true;
    }



        
}