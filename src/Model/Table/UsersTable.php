<?php
/**
 * Created by PhpStorm.
 * User: Sundaramoorthy
 * Date: 29-12-2017
 * Time: 14:30
 */

namespace App\Model\Table;

use App\Model\Entity\User;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;


class UsersTable extends Table
{
    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');

        $this->hasMany('Restaurants',[
            'className'  => 'Restaurants',
            'foreignKey' => 'user_id'
        ]);

        $this->hasMany('AddressBooks',[
            'className'  => 'AddressBooks',
            'foreignKey' => 'user_id'
        ]);

        $this->hasMany('StripeCustomers',[
            'className'  => 'StripeCustomers',
            'foreignKey' => 'customer_id'
        ]);

        $this->hasMany('WalletHistories',[
            'className'  => 'WalletHistories',
            'foreignKey' => 'customer_id'
        ]);

        $this->hasMany('Orders',[
            'className'  => 'Orders',
            'foreignKey' => 'customer_id'
        ]);

        $this->hasMany('Drivers',[
            'className'  => 'Drivers',
            'foreignKey' => 'user_id'
        ]);

        $this->hasMany('Reviews',[
            'className'  => 'Reviews',
            'foreignKey' => 'customer_id'
        ]);
    }
}