<?php
/**
 * Created by PhpStorm.
 * User: NagaRaj
 * Date: 04-Jan-2018
 * Time: 20:15
 */

namespace App\Model\Table;

use App\Model\Entity\Locations;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;


class LocationsTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->primaryKey('id');
        $this->addBehavior('Timestamp');

        $this->belongsTo('States',[
            'className' => 'States',
            'foreignKey' => 'state_id'
        ]);

        $this->belongsTo('Cities',[
            'className' => 'Cities',
            'foreignKey' => 'city_id'
        ]);

        $this->hasMany('DeliveryLocations',[
            'className' => 'DeliveryLocations',
            'foreignKey' => 'location_id'
        ]);

         $this->hasMany('AddressBooks',[
            'className' => 'AddressBooks',
            'foreignKey' => 'location_id'
        ]);
    }
}