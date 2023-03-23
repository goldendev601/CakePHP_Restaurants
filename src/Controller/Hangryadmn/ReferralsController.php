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

class ReferralsController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->viewBuilder()->setLayout('backend');
        $this->loadModel('Referrals');
    }

    /*Get Referal List*/
    public function index() {

        if($this->request->is('post')) {

            $referralList = $this->Referrals->find('all', [
                'conditions' => [
                    'id IS NOT NULL'
                ]
            ])->hydrate(false)->first();

            $rewardEntity = $this->Referrals->newEntity();
            $rewardPatch = $this->Referrals->patchEntity($rewardEntity,$this->request->getData());
            if(!empty($referralList)) {
                $rewardPatch->id = '1';
            }
            $saveReward = $this->Referrals->save($rewardPatch);
            if($saveReward) {
                $this->Flash->success('Referral update successful');
                return $this->redirect(ADMIN_BASE_URL.'referrals');
            }
        }

        $referralList = $this->Referrals->find('all', [
            'conditions' => [
                'id IS NOT NULL'
            ]
        ])->hydrate(false)->first();

        $this->set(compact('referralList'));
    }
}