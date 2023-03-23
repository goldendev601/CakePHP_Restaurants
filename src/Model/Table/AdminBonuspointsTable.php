<?php
/**
 * Created by PhpStorm.
 * User: NagaRaj
 * Date: 12-Jan-2018
 * Time: 00:32
 */

namespace App\Model\Table;

use App\Model\Entity\Bonuspoints;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;


class AdminBonuspointsTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
    }
}