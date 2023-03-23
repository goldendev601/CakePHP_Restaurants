<?php
/**
 * Created by PhpStorm.
 * User: NagaRaj
 * Date: 10-Jan-2018
 * Time: 14:18
 */

namespace App\Model\Table;

use App\Model\Entity\DeliveryLocations;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;


class DeliveryLocationsTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->primaryKey('id');
        $this->addBehavior('Timestamp');

        $this->belongsTo('Restaurants',[
            'className' => 'Restaurants',
            'foreignKey' => 'restaurant_id'
        ]); 

        $this->belongsTo('Cities',[
            'className' => 'Cities',
            'foreignKey' => 'city_id'
        ]);

        $this->belongsTo('Locations',[
            'className' => 'Locations',
            'foreignKey' => 'location_id'
        ]);    
    }
}