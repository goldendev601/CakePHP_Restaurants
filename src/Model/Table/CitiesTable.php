<?php
/**
 * Created by PhpStorm.
 * User: NagaRaj
 * Date: 04-Jan-2018
 * Time: 16:45
 */

namespace App\Model\Table;

use App\Model\Entity\Cities;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;


class CitiesTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->primaryKey('id');
        $this->addBehavior('Timestamp');

        $this->belongsTo('Countries',[
            'className' => 'Countries',
            'foreignKey' => 'country_id'
        ]);

        $this->belongsTo('States',[
            'className' => 'States',
            'foreignKey' => 'state_id'
        ]);

        $this->hasMany('Locations',[
            'className' => 'Locations',
            'foreignKey' => 'city_id',
            'dependent' => true,
            'cascadeCallbacks' => true,
        ]);

        $this->hasMany('DeliveryLocations',[
            'className' => 'DeliveryLocations',
            'foreignKey' => 'city_id'
        ]);

        $this->hasMany('AddressBooks',[
            'className' => 'AddressBooks',
            'foreignKey' => 'city_id'
        ]);
    }
}