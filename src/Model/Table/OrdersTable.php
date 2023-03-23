<?php
/**
 * Created by PhpStorm.
 * User: Sundar
 * Date: 18-01-2018
 * Time: 17:46
 */
namespace App\Model\Table;

use Cake\ORM\Table;



class OrdersTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->addBehavior('Timestamp');

        $this->belongsTo('Restaurants',[
            'className'  => 'Restaurants',
            'foreignKey' => 'restaurant_id'
        ]);

        $this->belongsTo('Users',[
            'className'  => 'Users',
            'foreignKey' => 'customer_id'
        ]);

        $this->belongsTo('Drivers',[
            'className'  => 'Drivers',
            'foreignKey' => 'driver_id'
        ]);

        $this->hasMany('Carts',[
            'className' => 'Carts',
            'foreignKey' => 'order_id'
        ]);


        $this->hasMany('Reviews',[
            'className'  => 'Reviews',
            'foreignKey' => 'order_id'
        ]); 


        $this->belongsTo('Drivers',[
            'className'  => 'Drivers',
            'foreignKey' => 'driver_id'
        ]);

        $this->hasMany('CustomerPoints',[
            'className'  => 'CustomerPoints',
            'foreignKey' => 'order_id'
        ]);

    }
}
?>