<?php
/**
 * Created by PhpStorm.
 * User: Ramesh A
 * Date: 06-Jan-2018
 */

namespace App\Model\Table;

use App\Model\Entity\EModules;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;


class EModulesTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
    }
}