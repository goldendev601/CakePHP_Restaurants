<?php
/**
 * Created by PhpStorm.
 * User: Sundar
 * Date: 31-01-2018
 * Time: 23:40
 */
namespace App\Controller\Hangryadmn;

use Cake\Event\Event;
use App\Controller\AppController;
use Cake\ORM\Table;
use Cake\Utility\Hash;

class InvoicesController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->viewBuilder()->setLayout('backend');

        $this->loadModel('Users');
        $this->loadModel('Sitesettings');
        $this->loadModel('Timezones');
        $this->loadModel('Drivers');
        $this->loadModel('Countries');
        $this->loadModel('Orders');
        $this->loadModel('Restaurants');
        $this->loadModel('Invoices');
        $this->loadComponent('Googlemap');
        $this->loadComponent('PushNotification');

    }

    /*Get Invoices For All Restaurant*/
    public function index() {

        $invoiceDetails = $this->Invoices->find('all', [
            'conditions' => [
                'Invoices.id IS NOT NULL'
            ],
            'contain' => [
                'Restaurants'
            ],
            'order' => [
                'Invoices.id' => 'DESC'
            ]
        ])->hydrate(false)->toArray();

        $this->set(compact('invoiceDetails'));

        //pr($invoiceDetails);die();
    }

    /*Invoice View section*/
    public function view($id = null) {

        $invoiceDetails = $this->Invoices->find('all', [
            'conditions' => [
                'Invoices.id' => $id
            ],
            'contain' => [
                'Restaurants'
            ],
            'order' => [
                'Invoices.id' => 'DESC'
            ]
        ])->hydrate(false)->first();

        $this->set(compact('invoiceDetails'));

    }
}