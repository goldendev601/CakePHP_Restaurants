<?php
/**
 * Created by PhpStorm.
 * User: Sundar
 * Date: 31-01-2018
 * Time: 23:40
 */
namespace App\Controller\Restaurantadmin;

use Cake\Event\Event;
use App\Controller\AppController;
use Cake\ORM\Table;
use Cake\Utility\Hash;

class InvoicesController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->viewBuilder()->setLayout('restaurant');

        $this->loadModel('Users');
        $this->loadModel('Sitesettings');
        $this->loadModel('Timezones');
        $this->loadModel('Drivers');
        $this->loadComponent('PushNotification');
        $this->loadModel('Countries');
        $this->loadModel('Orders');
        $this->loadModel('Restaurants');
        $this->loadComponent('Googlemap');
        $this->loadModel('Invoices');
    }

    /*Get All Invoice For Restaurant's*/

    public function index() {
        $user = $this->Auth->user();
        $restDetails = $this->Restaurants->find('all', [
            'conditions' => [
                'Restaurants.user_id' => $user['id']
            ]
        ])->hydrate(false)->first();

        $invoiceDetails = $this->Invoices->find('all', [
            'conditions' => [
                'Invoices.id IS NOT NULL',
                'Invoices.restaurant_id' => $restDetails['id']
            ],
            'contain' => [
                'Restaurants'
            ],
            'order' => [
                'Invoices.id' => 'DESC'
            ]
        ])->hydrate(false)->toArray();
        //pr($invoiceDetails); die();
        $this->set(compact('invoiceDetails'));

    }

    /*Invoice View Functionality*/
    public function view($id = null) {

        $user = $this->Auth->user();
        $restDetails = $this->Restaurants->find('all', [
            'conditions' => [
                'Restaurants.user_id' => $user['id']
            ]
        ])->hydrate(false)->first();

        $invoiceDetails = $this->Invoices->find('all', [
            'conditions' => [
                'Invoices.id' => $id,
                'Invoices.restaurant_id' => $restDetails['id']
            ],
            'contain' => [
                'Restaurants'
            ],
            'order' => [
                'Invoices.id' => 'DESC'
            ]
        ])->hydrate(false)->first();

        //pr($invoiceDetails); die();
        $this->set(compact('invoiceDetails'));

    }
}