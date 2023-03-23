<?php
/**
 * Created by PhpStorm.
 * User: NagaRaj
 * Date: 06-Jan-2018
 * Time: 15:53
 */

namespace App\Model\Table;

use App\Model\Entity\Mainaddons;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;


class MainaddonsTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->primaryKey('id');
        $this->addBehavior('Timestamp');

        $this->belongsTo('Categories',[
            'className' => 'Categories',
            'foreignKey' => 'category_id'
        ]);

        $this->hasMany('Subaddons',[
            'className' => 'Subaddons',
            'foreignKey' => 'mainaddons_id'
        ]);

        $this->hasMany('MenuAddons',[
            'className' => 'MenuAddons',
            'foreignKey' => 'mainaddons_id'
        ]);
    }
}