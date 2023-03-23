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


class BookingsTable extends Table
{
    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');

         $this->belongsTo('Restaurants',[
             'className'  => 'Restaurants',
             'foreignKey' => 'restaurant_id'
         ]);

        // $this->hasMany('RestaurantMenus',[
        //     'className'  => 'RestaurantMenus',
        //     'foreignKey' => 'restaurant_id'
        // ]);
    }
}