<?php
/**
 * Created by PhpStorm.
 * User: NagaRaj
 * Date: 06-Jan-2018
 * Time: 15:55
 */

namespace App\Model\Table;

use App\Model\Entity\Subaddons;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;


class SubaddonsTable extends Table
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

        $this->belongsTo('Mainaddons',[
            'className' => 'Mainaddons',
            'foreignKey' => 'mainaddons_id'
        ]);

        $this->belongsTo('MenuAddons',[
            'className' => 'MenuAddons',
            'foreignKey' => 'subaddons_id'
        ]);
    }
}