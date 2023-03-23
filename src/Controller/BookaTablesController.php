<?php
namespace App\Controller;

use Cake\Controller\Controller;
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
        $this->viewBuilder()->setLayout('frontend');
        $this->loadModel('Bookings');
    }

    public function beforeFilter(Event $event)
    {
        // Before Login , these are the function we can access
        $this->Auth->allow([
            'bookaTable'
        ]);
    }
    //-----------------------------------------Book a Table functionality-----------------------------------------------
    //Booking a table in Restaurant
    public function bookaTable() {
        $data = $this->Common->parseSerialize($this->request->getdata('formData'));
        //pr($data);die;

        if (!empty($data)) {
            $bookEntity = $this->Bookings->newEntity();
            $bookPatch  = $this->Bookings->patchEntity($bookEntity,$data);
            $bookPatch['restaurant_id'] = $data['resId'];
            $bookPatch['customer_id'] = $this->Auth->user('id');
            $bookPatch['booking_time']  =  $data['booking_time'];
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
        
}