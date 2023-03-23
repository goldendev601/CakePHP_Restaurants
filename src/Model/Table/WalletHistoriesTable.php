<?php
/**
 * Created by PhpStorm.
 * User: Sundar
 * Date: 18-01-2018
 * Time: 12:51
 */
namespace App\Model\Table;

use App\Model\Entity\User;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;


class WalletHistoriesTable extends Table
{
    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');

        $this->belongsTo('Users',[
            'className'  => 'Users',
            'foreignKey' => 'customer_id'
        ]);
    }
}