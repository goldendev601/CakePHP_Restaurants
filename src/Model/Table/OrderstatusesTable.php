<?php
/**
 * Created by PhpStorm.
 * User: Sundar
 * Date: 18-01-2018
 * Time: 17:46
 */
namespace App\Model\Table;

use Cake\ORM\Table;



class OrderstatusesTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->addBehavior('Timestamp');
        
    }
}
?>