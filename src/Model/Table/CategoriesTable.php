<?php
/**
 * Created by PhpStorm.
 * User: NagaRaj
 * Date: 02-Jan-2018
 * Time: 17:15
 */

namespace App\Model\Table;

use App\Model\Entity\Categories;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;


class CategoriesTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->primaryKey('id');
        $this->addBehavior('Timestamp');

        $this->hasMany('Mainaddons',[
            'className' => 'Mainaddons',
            'foreignKey' => 'category_id'
        ]);

        $this->hasMany('Subaddons',[
            'className' => 'Subaddons',
            'foreignKey' => 'category_id'
        ]);
    }
}