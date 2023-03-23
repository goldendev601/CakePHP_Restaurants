<?php
/**
 * Created by PhpStorm.
 * User: NagaRaj
 * Date: 02-Jan-2018
 * Time: 18:31
 */

namespace App\Model\Table;

use App\Model\Entity\Cuisines;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;


class CuisinesTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
    }
}