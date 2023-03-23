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


class DashboardController extends AppController{

    public function initialize(){
        parent::initialize();
		$this->viewBuilder()->setLayout('backend');
        $this->loadComponent('Flash');
    }
public function dashboard(){
    
}

    


    
#---------------------------------------------------------------------------
}#class end...