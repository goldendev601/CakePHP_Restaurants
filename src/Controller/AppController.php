<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\I18n\I18n;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    //----------------------------Initialize Functionality Assign All Global Value in this Section----------------------
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        $this->loadComponent('Auth');
        $this->loadModel('Users');
        $this->loadModel('Sitesettings');
        $this->loadModel('LanguageSettings');
        $this->loadModel('EModules');
        $this->loadModel('States');
        $this->loadModel('Cities');
        $this->loadModel('Countries');
        $this->loadModel('Staticpages');
        $this->loadModel('Locations');
        $this->loadModel('Restaurants');

        $this->Auth->setConfig('authError',false);
        /*
         * Enable the following components for recommended CakePHP security settings.
         * see http://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        //$this->loadComponent('Security');
        //$this->loadComponent('Csrf');
        //pr($this->request->params); die();
        $this->siteSettings = $siteSettings = $this->Sitesettings->find('all', [
            'conditions' => [
                'id' => '1'
            ]
        ])->hydrate(false)->first();

        $this->prefix = (!empty($this->request->getParam('prefix')))
            ? $this->request->getParam('prefix') : '';

        if($siteSettings['offline_status'] == 'Yes' && $this->prefix == '') {
            $this->Auth->logout();
            echo $siteSettings['offline_notes'];die();
        }

        $siteCountry = $this->Countries->find('all', [
            'conditions' => [
                'id' => $siteSettings['site_country']
            ]
        ])->hydrate(false)->first();

        $countryCode = $siteCountry['iso_code'];


        //Logged User
        $controller = $this->request->getParam('controller');
        $action     = $this->request->getParam('action');

        //Logged User
        if(!empty($this->Auth->user())){
            $this->set('logginUser', $this->Auth->user());
        }
        else
            $this->set('logginUser', '');

        define('STRIPE_MODE',$siteSettings['stripe_mode']);
        define('PAYPAL_MODE',$siteSettings['paypal_mode']);
        define('FAV_ICON',$siteSettings['site_fav']);
        define('APPID',$siteSettings['facebook_api_id']);
        define('GOOGLE_SECRET',$siteSettings['google_secret_key']);
        define('GOOGLE_CLIENT',$siteSettings['google_api_id']);
        define('ZOPIM_CODE',$siteSettings['woopra_analytics']);
        define('ANALYTIC_CODE',$siteSettings['google_analytics']);


        define('SMS_TOKEN',$siteSettings['sms_token']);
        define('SMS_SID',$siteSettings['sms_id']);
        define('SMS_FROM',$siteSettings['sms_source_number']);
        define('SITE_PHONE',$siteCountry['phone_code']);


        define('PUSHER_APPID',$siteSettings['pusher_id']);
        define('PUSHER_SECRET',$siteSettings['pusher_secret']);
        define('PUSHER_AUTHKEY',$siteSettings['pusher_key']);

        if($siteSettings['stripe_mode'] == 'Live') {
            define('STRIPE_SECRET',$siteSettings['stripe_secretkey']);
            define('STRIPE_PUBLISH',$siteSettings['stripe_publishkey']);
        }else {
            define('STRIPE_SECRET',$siteSettings['stripe_secretkeyTest']);
            define('STRIPE_PUBLISH',$siteSettings['stripe_publishkeyTest']);
        }

        if($siteSettings['paypal_mode'] == 'Live') {
            define('CLIENT_ID',$siteSettings['live_clientid']);
            define('MODE','production');
        }else {
            define('CLIENT_ID',$siteSettings['test_clientid']);
            define('MODE','sandbox');
        }



      
      //---------------------  Search by ------------------------------------
       $search = ($siteSettings['address_mode'] == 'Google') ? 'Google' :
                  (($siteSettings['search_by']== 'area')? 'area' : 'zip');

        define('SEARCHBY',$search);

        $statelist = $this->States->find('list', [
            'keyField' => 'id',
            'valueField' => 'state_name',
            'conditions' => [
                'status' => '1',
                'country_id' => $this->siteSettings['site_country']
            ],
        ])->hydrate(false)->toArray();

        //Restaurant Login
        if($this->Auth->user('role_id') == '2') {
            $restaurantDetails = $this->Restaurants->find('all', [
                'fields' => [
                    'restaurant_dispatch'
                ],
                'conditions' => [
                    'user_id' => $this->Auth->user('id')
                ]
            ])->hydrate(false)->first();

        }

        date_default_timezone_set($siteSettings['site_timezone']);
        $this->set(compact('controller', 'action','siteSettings','statelist','countryCode','restaurantDetails','siteCountry'));
        $this->basicSetup();
    }

    /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return \Cake\Network\Response|null|void
     */
    public function beforeRender(Event $event)
    {        
        #------------------------------------------------------------------
        /*if (!array_key_exists('_serialize', $this->viewVars) &&
            in_array($this->response->type(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }*/
    }
    //-----------------------------------------Get Modules Data --------------------------------------------------------
    public function basicSetup() {



        $this->loggedUser = $loggedUser = $this->Auth->User();
        $this->permissions = [];
        if(!empty($loggedUser)) {
            if (!empty($loggedUser['role_id'] == 1 || $loggedUser['role_id'] == 4)) {

                $subadminDet = $this->Users->find('all', [
                    'conditions' => [
                        'id' => $loggedUser['id']
                    ]
                ])->hydrate(false)->first();
                $moduleDet = $this->EModules->find('all', [
                    'conditions' => [
                        'id IS NOT NULL'
                    ],
                    'order' => [
                        'id' => 'ASC'
                    ]
                ])->hydrate(false)->toArray();

               // pr($moduleDet);die;

                foreach ($moduleDet as $key => $val) {
                    if ($val['parent_id'] == 0) {
                        $available = explode(',',$val['page_url']);
                        //echo "<pre>";print_r($available);die();
                        $val['available'] = $available;
                        $modules[] = $val;
                    } else {
                        $getMain = $this->EModules->find('all', [
                            'conditions' => [
                                'id' => $val['parent_id']
                            ]
                        ])->hydrate(false)->first();

                        $val['main_name'] = $getMain['modulesname'];
                        $val['main_page_url'] = $getMain['page_url'];
                        //$val['controller'] = $getMain['page_url'];
                        $subModules[$val['parent_id']][] = $val;
                    }
                }
                if (!empty($subadminDet)) {
                    $this->permissions = explode(',', $subadminDet['permissions']);
                }

                //echo "<pre>";print_r($subModules);die();
               // pr($subModules);die();
            }
        }
        $permissions = $this->permissions;

        $langDefault = $this->LanguageSettings->find('all', [
            'conditions' => [
                'id IS NOT NULL',
                'language_default' => 1,
                'delete_status' => 'N',
                'status' => '1',
            ]
        ])->hydrate(false)->first();


        $langDet = $this->LanguageSettings->find('all', [
            'conditions' => [
                'id IS NOT NULL',
                'delete_status' => 'N',
                'status' => '1',
            ]
        ])->hydrate(false)->toArray();

        if($this->request->session()->read('languageCode') == '') {
            $this->request->session()->write('languageCode',$langDefault['language_code']);

            if ($langDefault == 'en') {
                I18n::locale('en_US');
            } else {
                $langUpper = strtoupper($langDefault['language_code']);
                $defLang = $langDefault['language_code'].'_'.$langUpper;
                I18n::locale($defLang);
            }


        }else {


            if ($this->request->session()->read('languageCode') == 'EN') {
                I18n::locale('en_US');
            } else {
                $langLower = strtolower($this->request->session()->read('languageCode'));
                $defLang = $langLower.'_'.$this->request->session()->read('languageCode');
                //echo $defLang;die();
                I18n::locale($defLang);
            }
        }
        $base_path = ROOT . '/tmp';
        $models      = $base_path.'/cache/models';
        $persistent  = $base_path.'/cache/persistent';
        $cache_files = array($models,$persistent);


        //To delete Cache files
        foreach ($cache_files as $filepath) {
            $filepath = $filepath.'/*';
            $files = glob($filepath); //get all file names
            foreach($files as $file){
                if(is_file($file)) {
                    unlink($file); //delete file
                }

            }
        }

        //echo __('FIND RESTAURANTS');die();

        $static_page_list = $this->Staticpages->find('all', [
            'conditions'=> [
                    'status'=>1,
                    'delete_status'=>'N'
                ]
            ]);


        $this->set(compact('permissions', 'modules', 'subModules', 'loggedUser', 'langDet', 'static_page_list'));
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event); // TODO: Change the autogenerated stub
    }
}