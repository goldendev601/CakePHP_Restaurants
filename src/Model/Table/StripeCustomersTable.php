<?php
/**
 * Created by PhpStorm.
 * User: Sundaramoorthy
 * Date: 17-01-2018
 * Time: 14:51
 */
namespace App\Model\Table;

use App\Model\Entity\User;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;


class StripeCustomersTable extends Table
{
    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');

        $this->belongsTo('Users',[
            'className'  => 'Users',
            'foreignKey' => 'customer_id'
        ]);
    }
}