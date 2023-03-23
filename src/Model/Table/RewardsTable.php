<?php
/**
 * Created by PhpStorm.
 * User: roamadmin
 * Date: 20-02-2018
 * Time: 14:46
 */
namespace App\Model\Table;

use Cake\ORM\Table;



class RewardsTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->addBehavior('Timestamp');
    }
}