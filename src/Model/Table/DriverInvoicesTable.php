<?php
/**
 * Created by PhpStorm.
 * User: roamadmin
 * Date: 31-01-2018
 * Time: 18:48
 */
namespace App\Model\Table;

use App\Model\Entity\Drivers;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;


class DriverInvoicesTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->primaryKey('id');
        $this->addBehavior('Timestamp');

       /* $this->belongsTo('Users',[
            'className'  => 'Users',
            'foreignKey' => 'user_id'
        ]);

        $this->hasOne('DriverTrackings',[
            'className'  => 'DriverTrackings',
            'foreignKey' => 'driver_id'
        ]);

        $this->hasMany('Orders',[
            'className'  => 'Orders',
            'foreignKey' => 'driver_id'
        ]);*/

    }
}