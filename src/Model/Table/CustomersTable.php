<?php
/**
 * Created by PhpStorm.
 * User: NagaRaj
 * Date: 05-Jan-2018
 * Time: 11:34
 */

namespace App\Model\Table;

use App\Model\Entity\Customers;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;


class CustomersTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
       
    }
}