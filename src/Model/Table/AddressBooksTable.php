<?php
/**
 * Created by PhpStorm.
 * User: NagaRaj
 * Date: 11-Jan-2018
 * Time: 11:11
 */

namespace App\Model\Table;

use App\Model\Entity\AddressBooks;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;


class AddressBooksTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->primaryKey('id');
        $this->addBehavior('Timestamp');


        $this->belongsTo('Users',[
            'className' => 'Users',
            'foreignKey' => 'user_id'
        ]);

        $this->belongsTo('States',[
            'className' => 'States',
            'foreignKey' => 'state_id'
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