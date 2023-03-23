<?php
/**
 * Created by PhpStorm.
 * User: Sundar
 * Date: 31-01-2018
 * Time: 22:37
 */
namespace App\Model\Table;

use App\Model\Entity\Drivers;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;


class InvoicesTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->primaryKey('id');
        $this->addBehavior('Timestamp');

         $this->belongsTo('Restaurants',[
             'className'  => 'Restaurants',
             'foreignKey' => 'restaurant_id'
         ]);

    }
}