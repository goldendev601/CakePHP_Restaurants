<?php
/**
 * Created by PhpStorm.
 * User: NagaRaj
 * Date: 20-Jan-2018
 * Time: 12pm
 */

namespace App\Model\Table;

use App\Model\Entity\Drivers;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;


class DriverTrackingsTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->primaryKey('id');
        $this->addBehavior('Timestamp');

        $this->hasMany('Drivers',[
            'className'  => 'Drivers',
            'foreignKey' => 'driver_id'
        ]);
       
    }
}