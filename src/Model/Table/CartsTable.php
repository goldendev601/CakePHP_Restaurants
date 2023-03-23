<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11/12/2017
 * Time: 1:44 AM
 */
namespace App\Model\Table;

use Cake\ORM\Table;



class CartsTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->addBehavior('Timestamp');

        $this->belongsTo('RestaurantMenus',[
            'className' => 'RestaurantMenus',
            'foreignKey' => 'menu_id'
        ]);

        $this->belongsTo('Orders',[
            'className' => 'Orders',
            'foreignKey' => 'order_id'
        ]);


    }
}
?>