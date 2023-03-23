<?php
/**
 * Created by PhpStorm.
 * User: NagaRaj
 * Date: 02-Jan-2018
 * Time: 20:53
 */

namespace App\Model\Table;

use App\Model\Entity\Vouchers;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;


class VouchersTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->primaryKey('id');
        $this->addBehavior('Timestamp');

        $this->belongsTo('Restaurants',[
            'className' => 'Restaurants',
            'foreignKey' => 'resid'
        ]);
    }
}