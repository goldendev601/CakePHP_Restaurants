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


class PaymentMethodsController extends AppController{

    public function initialize(){
        parent::initialize();
        $this->viewBuilder()->setLayout('backend');
        $this->loadComponent('Flash');
        $this->loadComponent('Common');
        $this->loadModel('PaymentMethods');
    }

#--------------------------------------------------------------------------------
    // Payment Method Index
    public function index($process = null){
        $paymentList = $this->PaymentMethods->find('all',[
            'fields' => [
                'PaymentMethods.id',
                'PaymentMethods.payment_method_name',
                'PaymentMethods.payment_method_image',
                'PaymentMethods.status',
                'PaymentMethods.created'
            ],
            'conditions' => [
                'PaymentMethods.id IS NOT NULL'
            ]

        ])->hydrate(false)->toArray();
        $this->set(compact('paymentList'));
        
        if($process == 'PaymentMethod') {
            $value = array($paymentList);
            return $value;
        }
    }# index function end...
#----------------------------------------------------------------------------------

#-----------------------------------------------------------------------------------
    // Payment Method Add
    public function add(){
       
        if($this->request->is(['post'])){
            $postData = $this->request->getData();
            
            $paymentExist = $this->PaymentMethods->find('all', [
                'conditions' => [
                    'payment_method_name' => trim($postData['paymentMethodName'])
                ]
            ])->hydrate(false)->first();
            if (empty($paymentExist)) {
                //Image Upload Start
                if (!empty($postData['paymentMethodImage']) && ($postData['paymentMethodImage']['error'] == 0)) {
                    $valid = getimagesize($_FILES['paymentMethodImage']['tmp_name']);
                    if (!empty($valid)) {
                        $filePart = pathinfo($postData['paymentMethodImage']['tmp_name']);
                        $logo = ['jpeg', 'jpg', 'gif', 'png'];
                        if (!empty($logo)) {
                            $img_path = PAYMENTS_LOGO_PATH;
                            $this->Common->unlinkFile($postData['paymentMethodImage']['name'], $img_path);
                            $image_detail = $this->Common->UploadFile($postData['paymentMethodImage'], $img_path);
                            $postData['payment_method_image'] = $image_detail['refName'];
                        } else {
                            echo 'Not Image Type';
                        }
                    } else {
                        echo 'Not Valid Image';
                    }
                } else {
                    if (!empty($paymentMethodDetail))
                        $postData['payment_method_image'] = $paymentMethodDetail['payment_method_image'];
                    else
                        $postData['payment_method_image'] = '';
                }
                $postData['payment_method_name'] = $postData['paymentMethodName'];
                $paymentMethodEntity = $this->PaymentMethods->newEntity($postData);
                //echo "<pre>"; print_r($paymentMethodEntity['payment_method_name']); die();
                if ($this->PaymentMethods->save($paymentMethodEntity)) {
                    $this->Flash->success(__("PaymentMethod Details Inserted"));
                    return $this->redirect(['controller' => 'PaymentMethods', 'action' => 'index']);
                }
            } else {
                $this->Flash->error(__("PaymentMethod Name Already Exist"));
            }
        }
            
    }# add function end...
#-----------------------------------------------------------------------------------

#-----------------------------------------------------------------------------------
    // Payment Method Edit
    public function edit($id = null){
        if(!empty($id)) {
            $paymentDetail = $this->PaymentMethods->get($id);
        }
        if($this->request->is(['put'])) {
            $postData = $this->request->getData();

            $paymentExist = $this->PaymentMethods->find('all', [
                'conditions' => [
                    'id !=' => $id,
                    'payment_method_name' => trim($postData['paymentMethodName'])
                ]
            ])->first();
            if (empty($paymentExist)) {
                //Image Upload Start
                if (!empty($postData['paymentMethodImage']) && !empty($postData['paymentMethodImage']['error'] == 0)) {
                    $valid = getimagesize($_FILES['paymentMethodImage']['tmp_name']);
                    if (!empty($valid)) {
                        $filePart = pathinfo($postData['paymentMethodImage']['name']);
                        $logo = ['jpg', 'jpeg', 'gif', 'png'];
                        if (!empty($logo)) {
                            $img_path = PAYMENTS_LOGO_PATH;
                            $this->Common->unlinkFile($postData['paymentMethodImage']['name'], $img_path);
                            $image_detail = $this->Common->UploadFile($postData['paymentMethodImage'], $img_path);
                            $postData['payment_method_image'] = $image_detail['refName'];
                        }else {
                            echo 'Not Image Type';
                        }
                    } else {
                        echo 'Not Valid Image';
                    }
                } else {
                    if (!empty($paymentDetail))
                        $postData['payment_method_image'] = $paymentDetail['payment_method_image'];
                    else
                        $postData['payment_method_image'] = '';
                }
                $postData['payment_method_name'] = $postData['paymentMethodName'];
                $paymentEntity = $this->PaymentMethods->patchEntity($paymentDetail, $postData);
                if ($this->PaymentMethods->save($paymentEntity)) {
                    $this->Flash->success(__("PaymentMethod Details Updated"));
                    return $this->redirect(['controller' => 'PaymentMethods', 'action' => 'index']);
                }
            } else {
                $this->Flash->error(__("PaymentMethod Name Already Exist"));
            }
        }
        $paymentList = $this->PaymentMethods->find('all', [
            'conditions' => [
                'id IS NOT NULL'
            ]
        ])->hydrate(false)->toArray();
        $this->set(compact('id', 'paymentList', 'paymentDetail'));
    }# edit function end...
#-----------------------------------------------------------------------------------

#-----------------------------------------------------------------------------------
    //Static Status Change
    public function ajaxaction() {

        if($this->request->is('ajax')){
            if($this->request->getData('action') == 'paymentstatuschange'){

                $payment         = $this->PaymentMethods->newEntity();
                $payment         = $this->PaymentMethods->patchEntity($payment,$this->request->getData());
                $payment->id     = $this->request->getData('id');
                $payment->status = $this->request->getData('changestaus');
                $this->PaymentMethods->save($payment);

                $this->set('id', $this->request->getData('id'));
                $this->set('action', 'paymentstatuschange');
                $this->set('field', $this->request->getData('field'));
                $this->set('status', (($this->request->getData('changestaus') == 0) ? 'deactive' : 'active'));
            }
        }
    }# StatusChange function end...
#-----------------------------------------------------------------------------------

#-----------------------------------------------------------------------------------
    //Static data delete 
    public function deletepayment($id = null){

        if($this->request->is('ajax')){
            if($this->request->getData('action') == 'PaymentMethod'
                && $this->request->getData('id') != ''){

                $this->PaymentMethods->deleteAll([
                    'id' => $this->request->getData('id')
                ]);

                list($paymentList) = $this->index('PaymentMethod');
                if($this->request->is('ajax')) {
                    $action         = 'PaymentMethod';
                    $this->set(compact('action', 'paymentList'));
                    $this->render('ajaxaction');
                }
            }
        }
    }
    # delete payment function end...
#----------------------------------------------------------------------------------
}#class end...