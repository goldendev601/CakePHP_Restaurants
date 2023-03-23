<?php
/**
 * Created by PhpStorm.
 * User: roamadmin
 * Date: 20-02-2018
 * Time: 16:11
 */
namespace App\Model\Table;

use App\Model\Entity\Customers;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;


class CustomerPointsTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->primaryKey('id');
        $this->addBehavior('Timestamp');

        $this->belongsTo('Orders',[
            'className'  => 'Orders',
            'foreignKey' => 'order_id'
        ]);

    }
}