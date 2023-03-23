<?php
/**
 * Created by PhpStorm.
 * User: Sundaramoorthy S
 * Date: 03-01-2018
 * Time: 12:24
 */
namespace App\Model\Table;

use App\Model\Entity\User;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;


class DeliverySettingsTable extends Table
{
    public function initialize(array $config)
    {
        $this->table('delivery_settings');
        $this->addBehavior('Timestamp');

        $this->belongsTo('Restaurants',[
            'className' => 'Restaurants',
            'foreignKey' => 'restaurant_id'
        ]);
    }
}