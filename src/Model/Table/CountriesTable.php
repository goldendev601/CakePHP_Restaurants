<?php
/**
 * Created by PhpStorm.
 * User: NagaRaj
 * Date: 04-Jan-2018
 * Time: 10:15
 */

namespace App\Model\Table;

use App\Model\Entity\Countries;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;


class CountriesTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->primaryKey('id');
        $this->addBehavior('Timestamp');

        $this->hasMany('States',[
            'className' => 'States',
            'foreignKey' => 'country_id',
            'dependent' => true,
            'cascadeCallbacks' => true,
        ]);

        $this->hasMany('Cities',[
            'className' => 'Cities',
            'foreignKey' => 'country_id'
        ]);

        $this->hasMany('Timezones',[
            'className'  => 'Timezones',
            'foreignKey' => 'iso_code'
        ]);
    }
}