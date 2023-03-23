<?php
/**
 * Created by PhpStorm.
 * User: NagaRaj
 * Date: 04-Jan-2018
 * Time: 12:45
 */

namespace App\Model\Table;

use App\Model\Entity\States;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;


class StatesTable extends Table
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

        $this->hasMany('Cities',[
            'className' => 'Cities',
            'foreignKey' => 'state_id',
            'dependent' => true,
            'cascadeCallbacks' => true,
        ]);

        $this->hasMany('Locations',[
            'className' => 'Locations',
            'foreignKey' => 'state_id',            
        ]);


        $this->hasMany('AddressBooks',[
            'className' => 'AddressBooks',
            'foreignKey' => 'state_id'
        ]);
    }
}