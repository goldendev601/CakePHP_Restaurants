<?php
/**
 * Created by PhpStorm.
 * User: roamadmin
 * Date: 20-02-2018
 * Time: 10:22
 */
namespace App\Controller\Hangryadmn;

use Cake\Datasource\ConnectionManager;
use Cake\Event\Event;
use Cake\I18n\Time;
use App\Controller\AppController;

class RewardsController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->viewBuilder()->setLayout('backend');
        $this->loadModel('Rewards');
    }
    /*Reward List page*/

    public function index() {

        if($this->request->is('post')) {
            $rewardsList = $this->Rewards->find('all', [
                'conditions' => [
                    'id IS NOT NULL'
                ]
            ])->hydrate(false)->first();

            $rewardEntity = $this->Rewards->newEntity();
            $rewardPatch = $this->Rewards->patchEntity($rewardEntity,$this->request->getData());
            if(!empty($rewardsList)) {
                $rewardPatch->id = '1';
            }
            $saveReward = $this->Rewards->save($rewardPatch);
            if($saveReward) {
                $this->Flash->success('Rewards update successful');
                return $this->redirect(ADMIN_BASE_URL.'rewards');
            }
        }

        $rewardsList = $this->Rewards->find('all', [
            'conditions' => [
                'id IS NOT NULL'
            ]
        ])->hydrate(false)->first();

        $this->set(compact('rewardsList'));
    }
}