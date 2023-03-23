<?php
/**
 * Created by Ramesh.A
 * Date: 17/Aug/17
 * Time: 2:00 PM
 * Team: Sundhar.S  
 */
namespace App\Controller\Hangryadmn;


use Cake\Event\Event;
use App\Controller\AppController;
use Cake\Mailer\Email;


class NewslettersController extends AppController{

    public function initialize(){
        parent::initialize();
        $this->viewBuilder()->setLayout('backend');
        $this->loadComponent('Flash');
        $this->loadComponent('Common');
        $this->loadModel('Users');
    }

#--------------------------------------------------------------------------------
    // Newsletters Index
    public function index(){

        $cusList = $this->Users->find('all', [
            'conditions' => [
                'Users.id IS NOT NULL',
                'Users.status' => '1',
                'Users.role_id' => '3',
                'Users.deleted_status' => 'N',
                'Users.newsletter' => 'Y'
            ]
        ])->hydrate(false)->toArray();
        $this->set(compact('cusList'));        
    }
#----------------------------------------------------------------------------------
//Sendall Customer to send amil
    public function sendall() {

        if ($this->request->is(['post','put'])) {

            if(!empty($this->request->getData())) {             

                $allcustomers = $this->Users->find('list', [                                  
                            'keyField' => 'id',
                            'valueField' => 'username',
                           'conditions' => [
                                'newsletter'=>'Y',                                       
                                'status' => '1',
                                'role_id' => '3',
                                'deleted_status' => 'N',
                           ]
                        ])->hydrate(false)->toArray();                

                $source     = $this->siteUrl.'/siteicons/logo.png';
                $siteName   = $this->siteSettings['site_name'];
                $storeEmail = $this->siteSettings['admin_email'];

                $subject    = $this->request->getData('subject');
                $content    = $this->request->getData('content');

                /*foreach ($allcustomers as $key => $tomail) {

                    $mailContent = $content;   
                    $email = new CakeEmail();
                    $email->setFrom([$fromMail => 'Comeneat2.0']);  
                    $email->setTemplate('default');
                    $email->setEmailFormat('html');
                    $email->setFrom($storeEmail);
                    $email->setTo($tomail);                  
                    $email->setSubject($subject);
                    $email->setViewVars(['mailContent' => $mailContent,
                                           'siteName'    => 'Foodorderingsystem',
                                            'source'    => $source,
                                            'storename'  => $siteName]);    
                                               
                    //$email->send();
                }*/
            $this ->Session->setFlash('<p>'.__('Newsletter Email has been send successfully', true).'</p>', 'default', 
                                                            ['class' => 'alert alert-success']);
            return $this->redirect(['controller'=>'newsletters', 'action' => 'index']);
            }
        }
    }
#-----------------------------------------------------------------------------------
    //Select Customer to send mail
    public function sendselectcustomer() {
        if ($this->request->is(['post','put'])) {


            if (!empty($this->request->getData('email'))) {
                $emailList = implode($this->request->getData('email'), ',');
                $this->set(compact('emailList'));
            } else {

                if (!empty($this->request->getData())) {

                    $subject  = $this->request->getData('subject');
                    $tomail   = $this->request->getData('to');
                    $tomails  = explode(',', $tomail);
                    $content  = $this->request->getData('content');
                    $source   = $this->siteUrl.'/siteicons/logo.png';
                    $siteName = $this->siteSetting['Sitesetting']['site_name'];

                    $storeEmail = $this->siteSetting['Sitesetting']['admin_email'];

                    /*foreach ($tomails as $key => $tomail) {

                        $mailContent = $content;

                        $email = new CakeEmail();
                        $email->setTemplate('default');
                        $email->setEmailFormat('html');
                        $email->setFrom($storeEmail);
                        $email->setTo($tomail);
                        $email->setSubject($subject);
                        $email->setViewVars(['mailContent' => $mailContent,
                                              'source'     => $source,
                                              'storename'  => $siteName]);
                        //$email->send();

                    }*/

                $this->Flash->success('Email Send Successful');

                return $this->redirect(ADMIN_BASE_URL.'Newsletters');

                }
            }
        }
    }#sendselect customer function end...
#-----------------------------------------------------------------------------------
}#class end...