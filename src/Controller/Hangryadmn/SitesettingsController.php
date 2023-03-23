<?php
namespace App\Controller\Hangryadmn;

use Cake\Event\Event;
use Cake\I18n\Time;
use App\Controller\AppController;
use Cake\ORM\Table;

class SitesettingsController extends AppController{

    public function initialize(){
        parent::initialize();
        $this->viewBuilder()->setLayout('backend');
        $this->loadComponent('Auth');
        $this->loadComponent("Common");
        $this->loadModel("Sitesettings");
        $this->loadModel("Countries");
        $this->loadModel("States");
        $this->loadModel("Cities");
        $this->loadModel("Locations");
        $this->loadModel("Timezones");
        $this->loadModel("PromotionBanners");

    }
    #------------------------------------------------------------------------------------------------------
    public function beforeFilter(Event $event){
       if(!empty($this->Auth->user())) {
           
       }else {
           $this->Flash->success('Your session Timeout');
           return $this->redirect(BASE_URL);
       }
    }
    #------------------------------------------------------------------------------------------------------
    /*Get Sitesettings Details*/
    public function index(){
        
        if($this->request->is(['post','put'])) {
            //echo "<pre>"; print_r($this->request->getData()); die();

            $sitesetting  = $this->Sitesettings->newEntity();
            $sitesetting  = $this->Sitesettings->patchEntity($sitesetting, $this->request->getData());

            $sitesetting->id      = '1';
            $sitesettingEditInfo  = $this->Sitesettings->get(1);
          
            //Site Logo ------------------------------------------------------------
            $invalid = '0';
            if(!empty($this->request->getData('site_logo.name')))
            {
                $logoName = 'logo';
                $destinationPath = WWW_ROOT.'uploads'. DS.'siteImages'. DS.'siteLogo';

                if ($this->request->getData('site_logo')['error'] == 0) {
                    $refFile = $this->Common->uploadFile($this->request->getData('site_logo'),$destinationPath);
                    $sitesetting->site_logo = $refFile['refName'];
                } else {
                    $sitesetting->site_logo = $sitesettingEditInfo['site_logo'];
                }
                
            }else{
                $sitesetting->site_logo = $sitesettingEditInfo['site_logo'];
            }

            //Site FavIcon ----------------------------------------------------
            if(!empty($this->request->getData('site_fav.name'))){

                $favName = 'favicon';
                $destinationPath = WWW_ROOT.'uploads'. DS.'siteImages'. DS.'siteFav';

                if ($this->request->getData('site_fav')['error'] == 0) {
                    $refFile = $this->Common->uploadFile($this->request->getData('site_fav'),$destinationPath);
                    $sitesetting->site_fav = $refFile['refName'];
                } else {
                    $sitesetting->site_fav = $sitesettingEditInfo['site_fav'];
                }

            }else{
                $sitesetting->site_fav = $sitesettingEditInfo['site_fav'];
            }
            //Save Process---------------------------------------------------------------------------------------

            if($invalid == 0) {
                if ($this->Sitesettings->save($sitesetting)){
                    $this->Flash->success(_('Site details are updated successfully'));
                    return $this->redirect(ADMIN_BASE_URL.'sitesettings/index/');
                }
            }
        }

        //Edit Details Getting
        $EditSettingsList = $this->Sitesettings->find('all', [
                'conditions' => [
                    'id' => '1'
                ]
        ])->first();

        //Country Details
        $countryList = $this->Countries->find('list',[
            'keyField' => 'id',
            'valueField' => 'country_name',
            'conditions' => [
                'id IS NOT NULL',
                'delete_status' => 'N',
                'status' => '1'
            ]
        ])->hydrate(false)->toArray();

        //States Details
        $stateList = $this->States->find('list', [
            'keyField' => 'id',
            'valueField' => 'state_name',
            'conditions' => [
                'status' => '1',
                'country_id' => $EditSettingsList['site_country']
            ],
        ])->hydrate(false)->toArray();


        //Cities Details
        $cityList = $this->Cities->find('list', [
            'keyField' => 'id',
            'valueField' => 'city_name',
            'conditions' => [
                'status' => '1',
                'state_id' => $EditSettingsList['site_state']
            ],
        ])->hydrate(false)->toArray();


        //Locations Details
        /*if(SEARCHBY == 'area'){
            $locationList = $this->Locations->find('list',[
                'keyField' => 'id',
                'valueField' => 'area_name',
                'conditions' => [
                    'status' => 1
                ]
            ])->hydrate(false)->toArray();
        }else{*/
            $locationList = $this->Locations->find('list',[
                'keyField' => 'id',
                'valueField' => 'zip_code',
                'conditions' => [
                    'status' => '1',
                    'city_id' => $EditSettingsList['site_city']
                ]
            ])->hydrate(false)->toArray();
        //}
      
        $this->set(compact('EditSettingsList','countryList','stateList','cityList','locationList','adminBonus'));
    }
    //----------------------------------------------------------------------------------------
    #Get Currency
    function getCurrency(){

        $getcurrency = $this->Countries->find('all',[
            'fields' => [
                'currency_name',
                'currency_code',
                'currency_symbol',
                'iso_code'
            ],
            'conditions' => [
                'id' => trim($this->request->getData('SiteCountry')),
                'id IS NOT NULL'
            ]
        ])->hydrate(false)->first();

        $getTimezone = $this->Timezones->find('all',[
            'fields' => [
                'id',
                'timezone_name'
            ],
            'conditions' => [
                'iso_code' => trim($getcurrency['iso_code']),
                'id IS NOT NULL'
            ]
        ])->hydrate(false)->first();
        $getcurrency['timezone'] = $getTimezone['timezone_name'];
       
        if(!empty($getcurrency)){
            echo json_encode($getcurrency);
            die();
        }
    }
    //----------------------------------------------------------------------------------------
    #Payment settings
    function paymentSettings(){
        if($this->request->is(['post','put'])) {  
            //echo "<pre>"; print_r($this->request->getData()); die();
            $paymentSetting  = $this->Sitesettings->newEntity();
            $paymentSetting  = $this->Sitesettings->patchEntity($paymentSetting, $this->request->getData());

            $paymentSetting->id  = $this->Auth->user('role_id');
           
            if($this->Sitesettings->save($paymentSetting)){
                $this->Flash->success(_('Payment details are updated successfully'));
                return $this->redirect(ADMIN_BASE_URL.'sitesettings/paymentSettings/');
            }
        }
       
        $EditPaymentList = $this->Sitesettings->find('all', [
                    'conditions' => [
                        'id' => '1'
                    ]
        ])->first();
        $this->set(compact('EditPaymentList'));
    }
   //----------------------------------------------------------------------------------------
    #OldThirdParty settings
    function thirdpartySettings(){
        if($this->request->is(['post','put'])) {  
            $thirdpartySetting  = $this->Sitesettings->newEntity();
            $thirdpartySetting  = $this->Sitesettings->patchEntity($thirdpartySetting, $this->request->getData());

            $thirdpartySetting->id  = $this->Auth->user('role_id');
           
            if ($this->Sitesettings->save($thirdpartySetting)){
                $this->Flash->success(_('ThirdParty Settings details are updated successfully'));
                return $this->redirect(ADMIN_BASE_URL.'sitesettings/thirdpartySettings/');
            }
        }
        $EditThirdPartyList = $this->Sitesettings->find('all', [
                    'conditions' => [
                        'id' => '1'
                    ]
        ])->first();
        $this->set(compact('EditThirdPartyList'));
    }
    //----------------------------------------------------------------------------------------
    #ThirdParty settings
    function thirdparty(){
        if($this->request->is(['post','put'])) {
            $thirdparty  = $this->Sitesettings->newEntity();
            $thirdparty  = $this->Sitesettings->patchEntity($thirdparty, $this->request->getData());
            $thirdparty->id  = $this->Auth->user('role_id');
            if ($this->Sitesettings->save($thirdparty)){
                $this->Flash->success(_('ThirdParty Settings details are updated successfully'));
                return $this->redirect(ADMIN_BASE_URL.'sitesettings/thirdparty/');
            }
        }
        $EditThirdPartyList = $this->Sitesettings->find('all', [
            'conditions' => [
                'id' => '1'
            ]
        ])->first();
        $this->set(compact('EditThirdPartyList'));
    }
    //----------------------------------------------------------------------------------------
    public function promotionBanners() {
        $bannerSection = $this->PromotionBanners->find('all',[
            'conditions' => ['id IS NOT NULL']
        ])->toArray();

        if($this->request->is(['post','put'])) {

            if(!empty($this->request->getData())) {
                $promotionBanner = $this->request->getData();
                $banner=[];

                $destinationPath = WWW_ROOT.'uploads'. DS.'siteImages'. DS.'promoBanner';

                foreach ($promotionBanner['data']['PromotionBanner'] as $key => $value) {
                    if(!empty($value['banner_image']['tmp_name'])){
                        $getFileSize = getimagesize($value['banner_image']['tmp_name']);
                    }
                    
                    if(!empty($getFileSize)) {

                        if ($this->request->getData($value['banner_image']['error']) == 0) {
                            $refFile = $this->Common->uploadFile($value['banner_image'],$destinationPath);
                            $banner['banner_image'] = $refFile['refName'];
                        } else {
                            $banner['banner_image']     = $bannerSection['banner_image'];
                        }

                        $banner['banner_link']  =  $value['banner_link'];
                        if(!empty($value['pass_id'])){
                            $banner['id']          = $value['pass_id'];
                        }
                      
                        $bannerEnty  = $this->PromotionBanners->newEntity();
                        $banners     = $this->PromotionBanners->patchEntity($bannerEnty,$banner);
                        $succ=$this->PromotionBanners->save($banners);                        

                        if($succ){
                             $this->Flash->success(__("Promotion Banner Added SuccessFully"));
                            return $this->redirect(['controller' => 'sitesettings', 'action' => 'promotionBanners']);
                        }
                    } 
                }
            }
        }


        $this->set(compact('bannerSection'));
    }
#-----------------------------------------------------------------------------------------
    public function ajaxaction() {

        if ($this->request->is('ajax')) {
            if ($this->request->getData('action') == 'getStatelist') {
                $country_id = $this->request->getData('country_id');
                $stateList = $this->States->find('list', [
                    'keyField' => 'id',
                    'valueField' => 'state_name',
                    'conditions' => [
                        'country_id' => $country_id,
                        'status' => '1'
                    ],
                ])->hydrate(false)->toArray();

                $this->set('action', $this->request->getData('action'));
                $this->set(compact('stateList'));
            }

            if ($this->request->getData('action') == 'getCitylist') {
                $state_id = $this->request->getData('state_id');
                $cityList = $this->Cities->find('list', [
                    'keyField' => 'id',
                    'valueField' => 'city_name',
                    'conditions' => [
                        'state_id' => $state_id,
                        'status' => '1'
                    ],
                ])->hydrate(false)->toArray();

                $this->set('action', $this->request->getData('action'));
                $this->set(compact('cityList'));
            }

            if ($this->request->getData('action') == 'getLocationlist') {
                $city_id = $this->request->getData('city_id');
                    /*if(SEARCHBY == 'area'){
                        $locationList = $this->Locations->find('list', [
                            'keyField' => 'id',
                            'valueField' => 'area_name',
                            'conditions' => [
                                'city_id' => $city_id,
                                'status' => '1'
                            ],
                        ])->hydrate(false)->toArray();
                    }
                    if(SEARCHBY == 'zip'){*/
                        $locationList = $this->Locations->find('list', [
                            'keyField' => 'id',
                            'valueField' => 'zip_code',
                            'conditions' => [
                                'city_id' => $city_id,
                                'status' => '1'
                            ],
                        ])->hydrate(false)->toArray();
                    //}
                $this->set('action', $this->request->getData('action'));
                $this->set(compact('locationList'));
            }
        }
    }
#-----------------------------------------------------------------------------------------
}#class end...