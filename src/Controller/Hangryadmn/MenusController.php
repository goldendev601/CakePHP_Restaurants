<?php
/**
 * Created by PhpStorm.
 * User: Sundar
 * Date: 04-01-2018
 * Time: 16:34
 */
namespace App\Controller\Hangryadmn;

use Cake\Event\Event;
use App\Controller\AppController;


class MenusController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->viewBuilder()->setLayout('backend');
        $this->loadComponent('Common');

        $this->loadModel('Users');
        $this->loadModel('Restaurants');
        $this->loadModel('Categories');
        $this->loadModel('RestaurantMenus');
        $this->loadModel('MenuDetails');
        $this->loadModel('MenuAddons');
    }

    //----------------------------------------------------------------------------------------

    /*Get Menu Details*/
    public function index($process = null) {
        $menuDetails = $this->RestaurantMenus->find('all', [
            'conditions' => [
                'RestaurantMenus.delete_status' => 'N'
            ],
            'contain' => [
                'Restaurants',
                'Categories' => [
                    'fields' => [
                        'Categories.category_name'
                    ],
                    'conditions' => [
                        'Categories.delete_status' => 'N'
                    ],
                ]
            ],
            'order' =>[
                'RestaurantMenus.id' => 'DESC'
            ]
        ])->hydrate(false)->toArray();
        //pr($menuDetails);die();

        $this->set(compact('menuDetails'));

        if($process == 'RestaurantMenus') {
            $value = array($menuDetails);
            return $value;
        }
    }
    //----------------------------------------------------------------------------------------

    /*Menu Add Section*/
    public function add() {

        //pr($this->request->getData());die();
        if($this->request->is('post')) {

           // pr($this->request->getData());die();
            //Menu Add Section
            $menuAdd = $this->RestaurantMenus->newEntity();
            $menuPatch = $this->RestaurantMenus->patchEntity($menuAdd,$this->request->getData());
            $menuPatch['menu_addon'] = $this->request->getData('menu_addons');
            $menuPatch['price_option'] = $this->request->getData('price_option');
            $menuPatch['status'] = '1';
            $menuSave = $this->RestaurantMenus->save($menuPatch);

            if($this->request->getData('price_option') == "single") {
                $menuDetailsAdd = $this->MenuDetails->newEntity();
                $menuDetails['menu_id'] =  $menuSave->id;
                $menuDetails['sub_name'] =  ($this->request->getData('data')['MenuDetail'][0]['sub_name'] != '') ? $this->request->getData('data')['MenuDetail'][0]['sub_name'] : $this->request->getData('menu_name');
                $menuDetails['orginal_price'] = $this->request->getData('menu_price');
                $menuDetailsPatch = $this->MenuDetails->patchEntity($menuDetailsAdd,$menuDetails);
                $menuDetailSave = $this->MenuDetails->save($menuDetailsPatch);

            }else {
                $menuDetails = $this->request->getData('data')['MenuDetail'];
                foreach($menuDetails as $key => $value) {
                    $menuDetailsAdd = $this->MenuDetails->newEntity();
                    $menuDetailsPatch = $this->MenuDetails->patchEntity($menuDetailsAdd,$value);
                    $menuDetailsPatch['menu_id'] =  $menuSave->id;
                    $menuDetailSave = $this->MenuDetails->save($menuDetailsPatch);
                    $menuDetailArray[] = $menuDetailSave->id;
                    $menuDetailSave->id = '';
                }

            }

            if ($this->request->getData('menu_addons') == "Yes") {
                if(!empty($this->request->getData('data')['MenuAddon'])){
                    $menuAddons = $this->request->getData('data')['MenuAddon'];
                }else{
                    $menuAddons = '';
                }

                $category_id = $this->request->getData('category_id');
                $j = '';
                if(!empty($menuAddons)){
                    foreach ($menuAddons as $mkey => $mvalue) {
                        foreach ($mvalue['Subaddon'] as $skey => $svalue) {
                            if (!empty($svalue) && isset($svalue['subaddons_id'])) {
                                if ($this->request->getData('price_option') == "single") {
                                    $menuAddonsAdd = $this->MenuAddons->newEntity();

                                    $menuAddonDetails['menu_id'] = $menuSave->id;
                                    $menuAddonDetails['restaurant_id'] = $this->request->getData('restaurant_id');
                                    $menuAddonDetails['category_id'] = $category_id;
                                    $menuAddonDetails['mainaddons_id'] = $mvalue['mainaddons_id'];
                                    $menuAddonDetails['price_option'] = $this->request->getData('price_option');
                                    $menuAddonDetails['subaddons_id'] = $svalue['subaddons_id'];
                                    $menuAddonDetails['menudetails_id'] = $menuDetailSave->id;
                                    $menuAddonDetails['subaddons_price'] = ($svalue['subaddons_price'] != '') ? $svalue['subaddons_price'] : '';

                                    $menuAddonsPatch = $this->MenuAddons->patchEntity($menuAddonsAdd, $menuAddonDetails);
                                    $menuAddonsSave = $this->MenuAddons->save($menuAddonsPatch);
                                    //$menuAddonsSave->id = '';
                                } else {

                                    $i = ($j == 0) ? count($menuDetailArray) : $j;
                                    foreach ($svalue['subaddons_price'] as $subPriceKey => $subPriceVal) {
                                        $menuAddonsAdd = $this->MenuAddons->newEntity();
                                        $k = count($menuDetailArray) - $i;
                                        $j = $i - 1;
                                        $i = ($j == 0) ? count($menuDetailArray) : $j;

                                        $subPrice['menu_id'] = $menuSave->id;
                                        $subPrice['restaurant_id'] = $this->request->getData('restaurant_id');
                                        $subPrice['category_id'] = $category_id;
                                        $subPrice['mainaddons_id'] = $mvalue['mainaddons_id'];
                                        $subPrice['subaddons_id'] = $svalue['subaddons_id'];
                                        $subPrice['price_option'] = $this->request->getData('price_option');
                                        $subPrice['menudetails_id'] = $menuDetailArray[$k];
                                        $subPrice['subaddons_price'] = ($subPriceVal != '') ? $subPriceVal : '0.00';

                                        $menuAddonsPatch = $this->MenuAddons->patchEntity($menuAddonsAdd, $subPrice);
                                        $menuAddonsSave = $this->MenuAddons->save($menuAddonsPatch);
                                        //$menuAddonsSave->id = '';
                                    }

                                }
                            }
                        }
                    }
                }
            }

            //Menu Image Upload Section
            $invalid = '0';
            if(isset($this->request->getData('menuImage')['name']) &&
                !empty($this->request->getData('menuImage')['name'])){

                $menuAdd = $this->RestaurantMenus->newEntity();

                $valid     = getimagesize($_FILES['menuImage']['tmp_name']);
                $filePart  = pathinfo($this->request->getData('menuImage')['name']);
                $logo      = ['jpg','jpeg','gif','png'];

                if( $this->request->getData('menuImage')['error'] == 0 &&
                    ($this->request->getData('menuImage')['size'] > 0 ) &&
                    in_array(strtolower($filePart['extension']),$logo) && !empty($valid) ) {

                    //Image Upload Start
                    $file = MENU_LOGO_PATH.'/'. $menuSave->id;
                    if (!file_exists($file))
                        mkdir($file, 0777, true);

                    $img_path       = $file;
                    $image_detail   = $this->Common->UploadFile($this->request->getData('menuImage'), $img_path);
                    $menuImage['menu_image']  = $image_detail['refName'];
                    $menuImage['id']  = $menuSave->id;

                    $menuPatch = $this->RestaurantMenus->patchEntity($menuAdd,$menuImage);
                    $menuSave = $this->RestaurantMenus->save($menuPatch);
                }
            }
            $this->Flash->success(__('Menu Added successful'));
            return $this->redirect(ADMIN_BASE_URL.'menus');
        }

        $restaurantLists = $this->Restaurants->find('list', [
            'keyField' => 'id',
            'valueField' => 'restaurant_name',
            'conditions' => [
                'id IS NOT NULL',
                'status' => '1'
            ]
        ])->hydrate(false)->toArray();


        $categoryList = $this->Categories->find('list', [
            'keyField' => 'id',
            'valueField' => 'category_name',
            'conditions' => [
                'id IS NOT NULL',
                'status' => '1',
                'delete_status' => 'N'
            ]
        ])->hydrate(false)->toArray();
        $this->set(compact('restaurantLists','categoryList'));
    }
   //----------------------------------------------------------------------------------------------
    /*Menu Edit Section*/
    public function edit($id = null) {

        if($this->request->is('post')) {
            //echo "<pre>";print_r($this->request->getData());die();
            //Menu Add Section
            $menuAdd = $this->RestaurantMenus->newEntity();
            $menuPatch = $this->RestaurantMenus->patchEntity($menuAdd,$this->request->getData());
            $menuPatch['menu_addon'] = $this->request->getData('menu_addons');
            $menuPatch['id'] = $this->request->getData('editedId');
            $menuSave = $this->RestaurantMenus->save($menuPatch);

            //Delete Menu Detail
            $this->MenuDetails->deleteAll([
                'menu_id' => $this->request->getData('editedId')
            ]);
            if($this->request->getData('price_option') == "single") {

                $menuDetailsAdd = $this->MenuDetails->newEntity();
                $menuDetails['menu_id'] =  $this->request->getData('editedId');
                $menuDetails['sub_name'] =  ($this->request->getData('data')['MenuDetail'][0]['sub_name'] != '') ? $this->request->getData('data')['MenuDetail'][0]['sub_name'] : $this->request->getData('menu_name');
                $menuDetails['orginal_price'] = $this->request->getData('menu_price');
                $menuDetailsPatch = $this->MenuDetails->patchEntity($menuDetailsAdd,$menuDetails);
                $menuDetailSave = $this->MenuDetails->save($menuDetailsPatch);

            }else {
                $menuDetails = $this->request->getData('data')['MenuDetail'];
                foreach($menuDetails as $key => $value) {
                    $menuDetailsAdd = $this->MenuDetails->newEntity();
                    $menuDetailsPatch = $this->MenuDetails->patchEntity($menuDetailsAdd,$value);
                    $menuDetailsPatch['menu_id'] =  $this->request->getData('editedId');
                    $menuDetailSave = $this->MenuDetails->save($menuDetailsPatch);
                    $menuDetailArray[] = $menuDetailSave->id;
                    $menuDetailSave->id = '';
                }

            }

            if ($this->request->getData('menu_addons') == "Yes") {
                //Delete MenuAddons Detail
                $this->MenuAddons->deleteAll([
                    'menu_id' => $this->request->getData('editedId')
                ]);
                if(!empty($this->request->getData('data')['MenuAddon'])){
                    $menuAddons = $this->request->getData('data')['MenuAddon'];
                }else{
                    $menuAddons='';
                }
                
                $category_id = $this->request->getData('category_id');
                $j = '';
                if(!empty($menuAddons)){
                    foreach ($menuAddons as $mkey => $mvalue) {
                        $menuAddonDetails = '';
                        foreach ($mvalue['Subaddon'] as $skey => $svalue) {
                            if (!empty($svalue) && isset($svalue['subaddons_id'])) {
                                if ($this->request->getData('price_option') == "single") {
                                    $menuAddonsAdd = $this->MenuAddons->newEntity();

                                    $menuAddonDetails['menu_id'] = $this->request->getData('editedId');
                                    $menuAddonDetails['restaurant_id'] = $this->request->getData('restaurant_id');
                                    $menuAddonDetails['category_id'] = $category_id;
                                    $menuAddonDetails['mainaddons_id'] = $mvalue['mainaddons_id'];
                                    $menuAddonDetails['price_option'] = $this->request->getData('price_option');
                                    $menuAddonDetails['menudetails_id'] = $menuDetailSave->id;
                                    $menuAddonDetails['subaddons_id'] = $svalue['subaddons_id'];
                                    $menuAddonDetails['subaddons_price'] = ($svalue['subaddons_price'][0] != '') ? $svalue['subaddons_price'][0] : '0.00';

                                    $menuAddonsPatch = $this->MenuAddons->patchEntity($menuAddonsAdd, $menuAddonDetails);
                                    $menuAddonsSave = $this->MenuAddons->save($menuAddonsPatch);
                                    $menuAddonsSave->id = '';
                                } else {

                                    $subPrice = '';
                                    $i = ($j == 0) ? count($menuDetailArray) : $j;
                                    foreach ($svalue['subaddons_price'] as $subPriceKey => $subPriceVal) {
                                        $menuAddonsAdd = $this->MenuAddons->newEntity();
                                        $k = count($menuDetailArray) - $i;
                                        $j = $i - 1;
                                        $i = ($j == 0) ? count($menuDetailArray) : $j;

                                        $subPrice['menu_id'] = $this->request->getData('editedId');
                                        $subPrice['restaurant_id'] = $this->request->getData('restaurant_id');
                                        $subPrice['category_id'] = $category_id;
                                        $subPrice['mainaddons_id'] = $mvalue['mainaddons_id'];
                                        $subPrice['subaddons_id'] = $svalue['subaddons_id'];
                                        $subPrice['price_option'] = $this->request->getData('price_option');
                                        $subPrice['menudetails_id'] = $menuDetailArray[$k];
                                        $subPrice['subaddons_price'] = ($subPriceVal != '') ? $subPriceVal : '0.00';

                                        $menuAddonsPatch = $this->MenuAddons->patchEntity($menuAddonsAdd, $subPrice);
                                        $menuAddonsSave = $this->MenuAddons->save($menuAddonsPatch);
                                        $menuAddonsSave->id = '';
                                    }

                                }
                            }
                        }
                    }
                }
            }

            //Menu Image Upload Section
            $invalid = '0';
            if(isset($this->request->getData('menuImage')['name']) && !empty($this->request->getData('menuImage')['name'])
            ){
                $menuAdd = $this->RestaurantMenus->newEntity();


                $valid     = getimagesize($_FILES['menuImage']['tmp_name']);
                $filePart  = pathinfo($this->request->getData('menuImage')['name']);
                $logo      = ['jpg','jpeg','gif','png'];

                if( $this->request->getData('menuImage')['error'] == 0 &&
                    ($this->request->getData('menuImage')['size'] > 0 ) &&
                    in_array(strtolower($filePart['extension']),$logo) && !empty($valid) ) {

                    //Image Upload Start
                    $file = MENU_LOGO_PATH.'/'. $this->request->getData('editedId');
                    if (!file_exists($file))
                        mkdir($file, 0777, true);

                    $img_path       = $file;
                    $image_detail   = $this->Common->UploadFile($this->request->getData('menuImage'), $img_path);
                    $menuImage['menu_image']  = $image_detail['refName'];
                    $menuImage['id']  = $this->request->getData('editedId');

                    $menuPatch = $this->RestaurantMenus->patchEntity($menuAdd,$menuImage);
                    $menuSave = $this->RestaurantMenus->save($menuPatch);
                }
            }
            $this->Flash->success(__('Menu Added successful'));
            return $this->redirect(ADMIN_BASE_URL.'menus');
        }

        $menuDetails = $this->RestaurantMenus->find('all', [
            'conditions' => [
                'id' => $id
            ],
            'contain' => [
                'MenuDetails'
            ]
        ])->hydrate(false)->first();

 
        $restaurantLists = $this->Restaurants->find('list', [
            'keyField' => 'id',
            'valueField' => 'restaurant_name',
            'conditions' => [
                'id IS NOT NULL',
                'status' => '1'
            ]
        ])->hydrate(false)->toArray();

        $categoryList = $this->Categories->find('list', [
            'keyField' => 'id',
            'valueField' => 'category_name',
            'conditions' => [
                'id IS NOT NULL',
                'status' => '1',
                'delete_status' => 'N'
            ]
        ])->hydrate(false)->toArray();

        //pr($menuDetails);die();
        $this->set(compact('restaurantLists','menuDetails','id','categoryList'));
    }
//----------------------------------------------------------------------------------------

    /*Menu Ajaxaction*/
    public function ajaxaction() {

        if($this->request->getData('action') == 'getCategory') {
            $categoryList = $this->Categories->find('list', [
                'keyField' => 'id',
                'valueField' => 'category_name'
            ])->hydrate(false)->toArray();
            $action = $this->request->getData('action');
            $this->set(compact('categoryList','action'));
        }

        if($this->request->getData('action') == 'menuStatus') {
            $category         = $this->RestaurantMenus->newEntity();
            $category         = $this->RestaurantMenus->patchEntity($category,$this->request->getData());
            $category->id     = $this->request->getData('id');
            $category->status = $this->request->getData('changestaus');
            $this->RestaurantMenus->save($category);

            $this->set('id', $this->request->getData('id'));
            $this->set('action', 'menuStatus');
            $this->set('field', $this->request->getData('field'));
            $this->set('status', (($this->request->getData('changestaus') == 0) ? 'deactive' : 'active'));
        }

        if($this->request->getData('action') == 'getAddons') {
            $this->loadModel('Mainaddons');
            $this->loadModel('Subaddons');
            $this->loadModel('MenuAddons');
            $addonsList = $this->Mainaddons->find('all', [
                'conditions' => [
                    //'Mainaddons.restaurant_id' => $this->request->getData('restaurant_id'),
                    'Mainaddons.category_id' => $this->request->getData('category_id'),
                    'Mainaddons.status' => '1',
                    'Mainaddons.delete_status' => 'N'
                ],
                'contain' => [
                    'Subaddons' => [
                        'conditions' => [
                            //'Subaddons.restaurant_id' => $this->request->getData('restaurant_id')
                        ]
                    ]
                ]
            ])->hydrate(false)->toArray();
            //pr($addonsList);die();

            $selectedAddons = [];

            if(isset($addonsList[0]['subaddons'])) {
                foreach($addonsList as $key => $value) {
                    foreach ($value['subaddons'] as $subkey => $subvalue) {
                        $editAddonList = $this->MenuAddons->find('all', [
                            'conditions' => [
                                'menu_id' => $this->request->getData('menuId'),
                                //'restaurant_id' => $this->request->getData('restaurant_id'),
                                'category_id' => $this->request->getData('category_id'),
                                'subaddons_id' => $subvalue['id']
                            ]

                        ])->hydrate(false)->toArray();
                        if (!empty($editAddonList)) {
                            foreach ($editAddonList as $skey => $sval) {
                                $selectedAddons[] = $sval['subaddons_id'];
                            }
                        }
                        $addonsList[$key]['subaddons'][$subkey]['menuAddons'] = $editAddonList;
                    }
                }
            }

            //echo "<pre>";print_r($addonsList);die();

            $action = $this->request->getData('action');
            $priceOption = $this->request->getData('price_option');
            $menuID = $this->request->getData('menuId');
            $menuLength = $this->request->getData('menuLength');

            $this->set(compact('addonsList','action','selectedAddons','priceOption','menuLength','editAddonList','menuID'));

        }
    }

    //----------------------------------------------------------------------------------------
    /*Check Menu Name Already exists or not*/
    public function checkMenu() {

        if($this->request->getData('id') != '') {
            $conditions = [
                'id !=' => $this->request->getData('id'),
                'restaurant_id' => $this->request->getData('restaurant_id'),
                'category_id' => $this->request->getData('category_id'),
                'menu_name' => $this->request->getData('menu_name'),
            ];
        }else {
            $conditions = [
                'restaurant_id' => $this->request->getData('restaurant_id'),
                'category_id' => $this->request->getData('category_id'),
                'menu_name' => $this->request->getData('menu_name'),
            ];
        }

        $menuCount = $this->RestaurantMenus->find('all', [
            'conditions' => $conditions
        ])->count();
        if($menuCount == 0){
            echo '0';die();

        }else{
            echo '1';die();
        }
    }
//----------------------------------------------------------------------------------------

    /*Menu Delete Section*/
    public function deleteMenu() {

        //Delete Record
        $this->RestaurantMenus->deleteAll([
            'id' => $this->request->getData('id')
        ]);


        list($menuDetails) = $this->index('RestaurantMenus');
        if($this->request->is('ajax')) {
            $action         = 'RestaurantMenus';
            $this->set(compact('action', 'menuDetails'));
            $this->render('ajaxaction');
        }
    }
 //----------------------------------------------------------------------------------------
}