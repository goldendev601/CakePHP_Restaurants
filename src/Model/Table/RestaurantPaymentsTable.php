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


class RestaurantPaymentsTable extends Table
{
    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');

        $this->belongsTo('Restaurants',[
            'className'  => 'Restaurants',
            'foreignKey' => 'restaurant_id'
        ]);

        $this->belongsTo('PaymentMethods',[
            'className'  => 'PaymentMethods',
            'foreignKey' => 'payment_id'
        ]);
    }
}