<?php
/**
 * Created by PhpStorm.
 * User: Sundaramoorthy S
 * Date: 02-01-2018
 * Time: 19:57
 */
namespace App\Model\Table;

use App\Model\Entity\User;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;


class RestaurantsTable extends Table
{
    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');

        $this->belongsTo('Users',[
            'className'  => 'Users',
            'foreignKey' => 'user_id'
        ]);

        $this->hasMany('RestaurantMenus',[
            'className'  => 'RestaurantMenus',
            'foreignKey' => 'restaurant_id'
        ]);

        $this->hasMany('Orders',[
            'className'  => 'Orders',
            'foreignKey' => 'restaurant_id'
        ]);

        $this->hasMany('DeliverySettings',[
            'className' => 'DeliverySettings',
            'foreignKey' => 'restaurant_id',
            'dependent' => true,
            'cascadeCallbacks' => true,
        ]);

        $this->hasMany('Areamaps',[
            'className' => 'Areamaps',
            'foreignKey' => 'res_id',
            'dependent' => true,
            'cascadeCallbacks' => true,
        ]);

        $this->hasMany('DeliveryLocations',[
            'className' => 'DeliveryLocations',
            'foreignKey' => 'restaurant_id'
        ]);

        $this->hasMany('Bonuspoints',[
            'className' => 'Bonuspoints',
            'foreignKey' => 'restaurant_id'
        ]);

        $this->hasMany('Coupons',[
            'className'  => 'Coupons',
            'foreignKey' => 'restaurant_id'
        ]);

        $this->hasMany('Vouchers',[
            'className'  => 'Vouchers',
            'foreignKey' => 'restaurant_id'
        ]);

        $this->hasMany('Reviews',[
            'className'  => 'Reviews',
            'foreignKey' => 'restaurant_id'
        ]);

        $this->hasMany('Offers',[
            'className'  => 'Offers',
            'foreignKey' => 'resid'
        ]);

        $this->hasMany('Promotions',[
            'className'  => 'Promotions',
            'foreignKey' => 'restaurant_id'
        ]);


        $this->hasMany('RestaurantPayments',[
            'className'  => 'RestaurantPayments',
            'foreignKey' => 'restaurant_id'
        ]);

        $this->hasMany('Invoices',[
            'className'  => 'Invoices',
            'foreignKey' => 'restaurant_id'
        ]);
    }
}