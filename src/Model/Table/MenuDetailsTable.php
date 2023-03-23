<?php
/**
 * Created by PhpStorm.
 * User: roamadmin
 * Date: 23-10-2017
 * Time: 22:07
 */

namespace App\Model\Table;

use App\Model\Entity\MenuDetails;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class MenuDetailsTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');

        $this->belongsTo('RestaurantMenus',[
            'className' => 'RestaurantMenus',
            'foreignKey' => 'menu_id',
            'dependent' => true,
            'cascadeCallbacks' => true,
        ]);

        $this->hasMany('MenuAddons',[
            'className' => 'MenuAddons',
            'foreignKey' => 'menudetails_id'
        ]);
    }
}
?>