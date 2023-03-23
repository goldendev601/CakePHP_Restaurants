<?php
/**
 * Created by ArunYadhav.C
 * Date: 17/Aug/17
 * Time: 2:00 PM
 * Team: Sundhar.S  
 */
namespace App\Controller\Admin;

use Cake\Event\Event;
use App\Controller\AppController;


class UsersController extends AppController{

    public function initialize(){
        parent::initialize();
		$this->viewBuilder()->setLayout('adminLogin');
        $this->loadComponent('Flash');
    }
public function login(){
    
}

    


    
#---------------------------------------------------------------------------
}#class end...