<?php
/**
 * Created by PhpStorm.
 * User: roamadmin
 * Date: 07-10-2017
 * Time: 23:04
 */

namespace App\Model\Table;

use App\Model\Entity\RestaurantMenus;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class RestaurantMenusTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');

        $this->hasMany('MenuDetails',[
            'className' => 'MenuDetails',
            'foreignKey' => 'menu_id',
            'dependent' => true,
            'cascadeCallbacks' => true,
        ]);

        $this->belongsTo('Categories',[
            'className' => 'Categories',
            'foreignKey' => 'category_id'
        ]);

        $this->belongsTo('Restaurants',[
            'className' => 'Restaurants',
            'foreignKey' => 'restaurant_id'
        ]);       

        $this->hasMany('Carts',[
            'className' => 'Carts',
            'foreignKey' => 'menu_id'
        ]);

        $this->hasMany('MenuAddons',[
            'className' => 'MenuAddons',
            'foreignKey' => 'menu_id'
        ]);
    }
}
?>