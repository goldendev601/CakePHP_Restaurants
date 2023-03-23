<?php
/**
 * Created by PhpStorm.
 * User: Sundar.S
 * Date: 29-12-2017
 * Time: 19:34
 */
namespace App\Controller\RestaurantAdmin;

use Cake\Event\Event;
use App\Controller\AppController;


class AddonsController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->viewBuilder()->setLayout('restaurant');
        $this->loadComponent('Flash');
        $this->loadModel('Categories');
        $this->loadModel('Mainaddons');
        $this->loadModel('Subaddons');
    }

    public function beforeFilter(Event $event)
    {
        // Before Login , these are the function we can access
        $this->Auth->allow([
            'login'
        ]);
    }

#--------------------------------------------------------------------------------------------
    //Restaurantadmin Addons index
    public function index() {
          
         $addonsList = $this->Mainaddons->find('all', [
            'fields' => [
                'Mainaddons.id',
                'Mainaddons.mainaddons_name',
                'Mainaddons.status',
                'Mainaddons.created'
            ],
            'conditions' => [
                'Mainaddons.delete_status' => 'N',
            ],
            'contain' => [
                'Subaddons' => [
                    'conditions' => [
                        'Subaddons.delete_status' => 'N',
                    ]
                ],
                'Categories' => [
                    'fields' => [
                        'Categories.id',
                        'Categories.category_name'
                    ],
                    'conditions' => [
                        'Categories.delete_status' => 'N',
                    ]
                ]
            ],
            'order' => [
                'Mainaddons.id' => 'DESC'
            ]
        ])->hydrate(false)->toArray();
        $this->set(compact('addonsList'));
        if($process == 'Addon' ){
            $value = array($addonsList);
            return $value;
        }

    }#index function end...

#-------------------------------------------------------------------------------------------
    /*Check Addons Name ALready exists or Not*/
    public function  addonCheck(){

        if($this->request->getData('mainaddons_name') != '' && $this->request->getData('category_id') != '') {
            if($this->request->getData('id') != '') {
                $conditions = [
                    'id !=' => $this->request->getData('id'),
                    'category_id' => $this->request->getData('category_id'),
                    'mainaddons_name' => $this->request->getData('mainaddons_name'),
                ];
            }else {
                $conditions = [
                    'category_id' => $this->request->getData('category_id'),
                    'mainaddons_name' => $this->request->getData('mainaddons_name'),
                ];
            }
            $addonCount = $this->Mainaddons->find('all', [
                'conditions' => $conditions
            ])->count();

            if($addonCount == 0) {
                echo '0';
            }else {
                echo '1';
            }
            die();
        }
    }



#-------------------------------------------------------------------------------------------
    /*Addons Add Section*/
  public function add(){

      if($this->request->is('post')) {
          $postData = $this->request->getData();
          
          if (!empty($postData['mainaddons_name'])) {

              $mainAddonCount = $this->Mainaddons->find('all', [
                  'conditions' => [
                      'mainaddons_name' => trim($postData['mainaddons_name']),
                      'category_id' => trim($postData['category_id']),
                  ]
              ])->first();

              if (empty($mainAddonCount)) {

                  $mainEntity = $this->Mainaddons->newEntity($postData);
                  $mainEntity->status = '1';
                  $mainAddon  = $this->Mainaddons->save($mainEntity);                 

                    if (!empty($mainAddon)) {
                      if (!empty($this->request->getData('Main')['subaddons'])) {
                        foreach ($this->request->getData('Main')['subaddons'] as $skey => $sval) {
                            if (!empty($sval['subaddons_name']) && !empty($sval['subaddons_price'])) {
                                $subEntity = $this->Subaddons->newEntity();
                                $sub['mainaddons_id']   = $mainAddon->id;
                                $sub['category_id']     = $postData['category_id'];
                                $sub['subaddons_name']  = $sval['subaddons_name'];
                                $sub['subaddons_price'] = $sval['subaddons_price'];
                                $subPatch = $this->Subaddons->patchEntity($subEntity, $sub);
                                $this->Subaddons->save($subPatch);
                                unset($subPatch);
                            }
                        }
                    }
                    $this->Flash->success(__("Mainaddon details inserted successfully"));
                    return $this->redirect('https://www.hangrymenu.com/restaurantadmin/addons/index'); 
                  }
              } else {
                   $this->Flash->error(__("Mainaddon name already exist"));
              }
          } else {
              $this->Flash->error(__("Please enter mainaddon name"));
          }
      }

     //Categories Details
      $categorylist = $this->Categories->find('list', [
          'keyField' => 'id',
          'valueField' => 'category_name',
          'conditions' => [
              'status' => 1,
              'delete_status' => 'N'
          ],
      ])->hydrate(false)->toArray();   
      $this->set(compact('categorylist'));    
   } 
  #-------------------------------------------------------------------------------------------

    /*Addons Edit Section*/
   public function edit($id = null){

       //Categories Details
       $categorylist = $this->Categories->find('list', [
           'keyField' => 'id',
           'valueField' => 'category_name',
           'conditions' => [
               'status' => 1,
               'delete_status' => 'N'
           ],
       ])->hydrate(false)->toArray();

       //Edit Details
       $addonsList = $this->Mainaddons->find('all', [
          'conditions' => [
              'Mainaddons.id' => $id
          ],
          'contain' => [
              'Subaddons'
          ]
        ])->first();

       //------------------------------------------
       if($this->request->is(['post','put'])) {

           $postData = $this->request->getData();

           if (!empty($postData['mainaddons_name'])) {

               $mainAddonCount = $this->Mainaddons->find('all', [
                   'conditions' => [
                       'id !=' => $id,
                       'mainaddons_name' => trim($postData['mainaddons_name']),
                       'category_id' => trim($postData['category_id']),
                   ]
               ])->first();

               if (empty($mainAddonCount)) {

                   $mainEntity = $this->Mainaddons->newEntity($postData);
                   $mainEntity->id = $postData['editid'];
                   $mainAddon  = $this->Mainaddons->save($mainEntity);

                   if (!empty($mainAddon)) {
                       if(isset($postData['editid']) && !empty($postData['editid'])) {
                           $this->Subaddons->deleteAll(
                           [
                               'Subaddons.mainaddons_id' => $postData['editid']
                           ]);
                       }

                       if (!empty($this->request->getData('Main')['subaddons'])) {
                           foreach ($this->request->getData('Main')['subaddons'] as $skey => $sval) {
                               if (!empty($sval['subaddons_name']) && !empty($sval['subaddons_price'])) {
                                   $subEntity = $this->Subaddons->newEntity();
                                   $sub['mainaddons_id']   = $mainAddon->id;
                                   $sub['category_id']     = $postData['category_id'];
                                   $sub['subaddons_name']  = $sval['subaddons_name'];
                                   $sub['subaddons_price'] = $sval['subaddons_price'];
                                   $subPatch = $this->Subaddons->patchEntity($subEntity, $sub);
                                   $this->Subaddons->save($subPatch);
                                   unset($subPatch);
                               }
                           }
                       }
                       $this->Flash->success(__("Mainaddon details inserted successfully"));
                       return $this->redirect('https://www.hangrymenu.com/restaurantadmin/addons/index');
                   }
               } else {
                   $this->set(compact('addonsList','categorylist','id'));
                   $this->Flash->error(__("Mainaddon name already exist"));
               }
           } else {
               $this->set(compact('addonsList','categorylist','id'));
               $this->Flash->error(__("Please enter mainaddon name"));
           }
       }

        $this->set(compact(['addonsList','categorylist','id']));
   }
 #------------------------------------------------------------------------------------------
    /*Addon Status Change*/
    public function ajaxaction() {

        if($this->request->is('ajax')){
            if($this->request->getData('action') == 'addonStatusChange'){
                $addon         = $this->Mainaddons->newEntity();
                $addon         = $this->Mainaddons->patchEntity($addon,$this->request->getData());
                $addon->id     = $this->request->getData('id');
                $addon->status = $this->request->getData('changestaus');
                $this->Mainaddons->save($addon);

                $this->set('id', $this->request->getData('id'));
                $this->set('action', 'addonStatusChange');
                $this->set('field', $this->request->getData('field'));
                $this->set('status', (($this->request->getData('changestaus') == 0) ? 'deactive' : 'active'));
            }
        }
    }
 #------------------------------------------------------------------------------------------
    /*Addon Delete*/
    public function deleteAddon($id = null){

        if($this->request->is('ajax')){
            if($this->request->getData('action') == 'Addon'
                && $this->request->getData('id') != ''){

                $id = $this->request->getData('id');
                $addon         = $this->Mainaddons->newEntity();
                $addon         = $this->Mainaddons->patchEntity($addon,$this->request->getData());
                $addon->id     = $this->request->getData('id');
                $addon->delete_status = 'Y';
                $this->Mainaddons->save($addon);

                $dbConn = ConnectionManager::get('default');
                $dbConn->update('subaddons', ['delete_status' => 'Y'], ['mainaddons_id' => $id]);

                list($addonsList) = $this->index('Addon');
                if($this->request->is('ajax')) {
                    $action    = 'Addon';
                    $this->set(compact('action', 'addonsList'));
                    $this->render('ajaxaction');
                }
            }
        }
    }



#---------------------------------------------------------------------------------------------
}#class end...
