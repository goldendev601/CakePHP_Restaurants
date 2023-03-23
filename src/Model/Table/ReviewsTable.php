<?php
/**
 * Created by PhpStorm.
 * User: NagaRaj.V
 * Date: 23-Jan-2017
 * Time: 15:30
 */

namespace App\Model\Table;
use Cake\ORM\Table;

class ReviewsTable extends Table
{
    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');

        $this->belongsTo('Restaurants',[
            'className'  => 'Restaurants',
            'foreignKey' => 'restaurant_id'
        ]);

        $this->belongsTo('Orders',[
            'className'  => 'Orders',
            'foreignKey' => 'order_id'
        ]); 
       
        $this->belongsTo('Users',[
            'className'  => 'Users',
            'foreignKey' => 'customer_id'
        ]);   
    }
}