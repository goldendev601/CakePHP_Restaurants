<?php
/**
 * Created by Ramesh.A
 * Date: 17/Aug/17
 * Time: 2:00 PM
 * Team: Sundhar.S  
 */
namespace App\Controller\Hangryadmn;

use Cake\Event\Event;
use App\Controller\AppController;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;


class LanguageSettingsController extends AppController{

    public function initialize(){
        parent::initialize();
        $this->viewBuilder()->setLayout('backend');
        $this->loadComponent('Flash');
        $this->loadComponent('Common');
        $this->loadModel('LanguageSettings');
        $this->loadModel('LanguageContents');
    }

#--------------------------------------------------------------------------------
    // LanguageSettings Index
    public function index($process = null){

        $languageList = $this->LanguageSettings->find('all', [
            'fields' => [
                'LanguageSettings.id',
                'LanguageSettings.language_name',
                'LanguageSettings.language_code',
                'LanguageSettings.language_default',
                'LanguageSettings.status',
                'LanguageSettings.created'                
            ],
            'conditions' => [
                'LanguageSettings.id IS NOT NULL',
                'LanguageSettings.delete_status' => 'N'
            ]
        ])->hydrate(false)->toArray();
        $this->set(compact('languageList'));
        
        if($process == 'Languagepage') {
            $value = array($languageList);
            return $value;
        }
    }#index function end...
#----------------------------------------------------------------------------------

#-----------------------------------------------------------------------------------
    // LanguageSettings AddEdit
    public function addEdit($id = null){
        
        if($this->request->is(['post','put'])){
            
            $language  = $this->LanguageSettings->newEntity();
            $language  = $this->LanguageSettings->patchEntity($language,$this->request->getData());

            if(!empty($this->request->getData('editid'))) {
                $language->id       = $this->request->getData('editid');
            }
            $language_name = trim($this->request->getData('language_name'));
            $language->language_name  = $language_name;
            $language_code = trim($this->request->getData('language_code'));
            $language->language_code  = $language_code;
            $saveLanguage = $this->LanguageSettings->save($language);
            if ($saveLanguage) {

                if($this->request->getData('editid') == '') {
                    $lang = [];
                    $languageCode = $this->request->getData('language_code');
                    $uppLangCode = strtoupper( $languageCode );

                    $transLang = strtolower($languageCode).'_'.$uppLangCode;

                    $directoryName = ROOT . DS . 'src' . DS .'/Locale/'.$transLang;
                    if(!is_dir($directoryName)){
                        //Directory does not exist, so lets create it.
                        mkdir($directoryName, 0777, true);
                        chmod($directoryName, 0777);
                    }
                    $myfile = ROOT . DS . 'src' . DS .'/Locale/'.$transLang.'/default.po';
                    $handle = fopen($myfile, 'w') or die('Cannot open file:  '.$myfile);

                    $languageList = $this->LanguageContents->find('all', [
                        'conditions' => [
                            'LanguageContents.id IS NOT NULL',
                            'LanguageContents.language_id' => '1'
                        ]
                    ])->hydrate(false)->toArray();

                    if(!empty($languageList)) {

                        foreach ($languageList as $key => $value) {
                            $lang['id'] = '';
                            $lang['language_id'] = $saveLanguage->id;
                            $lang['msgid'] = $value['msgid'];
                            $lang['msgstr'] = $value['msgstr'];
                            $language  = $this->LanguageContents->newEntity($lang);
                            $this->LanguageContents->save($language);
                            $this->LanguageContents->id = "";
                            $saveContentFile = "\n".'msgid'.' '.  '"'.trim($value['msgid']).'"'." "."\n". 'msgstr'.' '. '"'.trim($value['msgstr']).'"';
                            $datawrite = $saveContentFile;
                            fwrite($handle, $datawrite);
                        }
                    }
                }

                $this->Flash->success(_('LanguageSettings details updated successfully'));
                return $this->redirect(ADMIN_BASE_URL.'LanguageSettings/index');
            }

        }else {

            $languageList    =   [];
            if(!empty($id)){
                $languageList = $this->LanguageSettings->get($id);
                if(!empty($languageList)) {
                    $this->set(compact('languageList','id'));
                } else {
                    return $this->redirect(ADMIN_BASE_URL . 'LanguageSettings/index');
                }
            } else {
                $this->set(compact('languageList','id'));
            }
        }
    }# addedit function end...
#-----------------------------------------------------------------------------------

#-----------------------------------------------------------------------------------
    //Language Edit File
    public function editFile($langCode = null) {


        
        $languageSettingList = $this->LanguageSettings->find('all', [
            'fields' => [
                'LanguageSettings.id'
            ],
            'conditions' => [
                'LanguageSettings.id IS NOT NULL',
                'LanguageSettings.delete_status' => 'N',
                'LanguageSettings.language_code' => $langCode
            ]
        ])->hydrate(false)->first();
        $id = $languageSettingList['id'];
         if($this->request->is(['post','put'])){
            // pr($this->request->getData());die();
            $postData = $this->request->getData();
            if (!empty($postData['editid']) && 
                !empty($postData['lang']) ) {
                $lang = [];
                $languageCode = $postData['langCode'];
                if($languageCode == 'EN') {
                    $uppLangCode = 'US';
                }else {
                    $uppLangCode = strtoupper( $languageCode );
                }

                $transLang = strtolower($languageCode).'_'.$uppLangCode;

                $directoryName = ROOT . DS . 'src' . DS .'Locale/'.$transLang;
                if(!is_dir($directoryName)){
                    //Directory does not exist, so lets create it.
                    mkdir($directoryName, 0777, true);
                    chmod($directoryName, 0777);
                }
                $myfile = ROOT . DS . 'src' . DS .'Locale/'.$transLang.'/default.po';
                $handle = fopen($myfile, 'w') or die('Cannot open file:  '.$myfile);
                $this->LanguageContents->deleteAll(
                    [
                        'language_id' => $postData['editid']
                    ]
                );
                foreach ($postData['lang'] as $key => $value) {
                    $lang['id'] = '';
                    $lang['language_id'] = $postData['editid'];
                    $lang['msgid'] = $value['msgid'];
                    $lang['msgstr'] = $value['msgstr'];
                    $language  = $this->LanguageContents->newEntity($lang);
                    $this->LanguageContents->save($language);
                    $this->LanguageContents->id = "";
                    $saveContentFile = "\n".'msgid'.' '.  '"'.trim($value['msgid']).'"'." "."\n". 'msgstr'.' '. '"'.trim($value['msgstr']).'"';
                    $datawrite = $saveContentFile;
                    fwrite($handle, $datawrite);
                }
                $this->Flash->success(_('LanguageContents details updated successfully'));
                return $this->redirect(ADMIN_BASE_URL.'LanguageSettings/index');
            }
         }
        $languageList = $this->LanguageContents->find('all', [
            'conditions' => [
                'LanguageContents.id IS NOT NULL',
                'LanguageContents.language_id' => $id
            ]
        ])->toArray();
        $this->set('languageList',$languageList);
        $this->set('id',$id);
        $this->set('langCode',$langCode);

    }# Language Edit File function end...
#-----------------------------------------------------------------------------------

#-----------------------------------------------------------------------------------
    //Language Name Already Exist
    public function languageCheck() {

        if($this->request->getData('language_name') != '') {
            if($this->request->getData('id') != '') {
                $conditions = [
                    'id !=' => $this->request->getData('id'),
                    'language_name' => $this->request->getData('language_name'),
                    'delete_status' => 'N'
                ];
            }else {
                $conditions = [
                    'language_name' => $this->request->getData('language_name'),
                    'delete_status' => 'N'
                ];
            }

            $languagepageCount = $this->LanguageSettings->find('all', [
                'conditions' => $conditions
            ])->count();

            if($languagepageCount == 0) {
                echo '0';
            }else {
                echo '1';
            }
            die();
        }
    }# Language Check function end...
#-----------------------------------------------------------------------------------

#-----------------------------------------------------------------------------------
    //Language Status Change
    public function ajaxaction() {

        if($this->request->is('ajax')){

            if($this->request->getData('action') == 'languagestatuschange'){

                $language         = $this->LanguageSettings->newEntity();
                $language         = $this->LanguageSettings->patchEntity($language,$this->request->getData());
                $language->id     = $this->request->getData('id');
                $language->status = $this->request->getData('changestaus');
                $this->LanguageSettings->save($language);

                $this->set('id', $this->request->getData('id'));
                $this->set('action', 'languagestatuschange');
                $this->set('field', $this->request->getData('field'));
                $this->set('status', (($this->request->getData('changestaus') == 0) ? 'deactive' : 'active'));
            }
            if($this->request->getData('action') == 'languagedefaultchange'){
                //echo "<pre>"; print_r($this->request->getData('id')); die();
                $statusChangeAll = $this->LanguageSettings->find('all', [
                        'conditions' => [
                            'LanguageSettings.id IS NOT NULL',
                            'LanguageSettings.status !=' => 3
                        ]
                    ])->toArray();

                foreach ($statusChangeAll as $key => $value) {
                    $statusChange['id'] = $value['id'];
                    $statusChange['language_default'] = 0;
                    $stsChange = $this->LanguageSettings->newEntity($statusChange);
                    $stsChange = $this->LanguageSettings->patchEntity($stsChange,$statusChange);
                    $this->LanguageSettings->save($stsChange);
                }

                $language         = $this->LanguageSettings->newEntity();
                $language         = $this->LanguageSettings->patchEntity($language,$this->request->getData());
                $language->id     = $this->request->getData('id');
                $language->language_default = 1;
                $this->LanguageSettings->save($language);
            }
        }
    }# StatusChange function end...
#-----------------------------------------------------------------------------------

#-----------------------------------------------------------------------------------
    //Language data delete 
    public function deletelanguage($id = null){

        if($this->request->is('ajax')){
            if($this->request->getData('action') == 'Languagepage' && $this->request->getData('id') != ''){

                $language         = $this->LanguageSettings->newEntity();
                $language         = $this->LanguageSettings->patchEntity($language,$this->request->getData());
                $language->id     = $this->request->getData('id');
                $language->delete_status = 'Y';
                $this->LanguageSettings->save($language);
                $this->LanguageContents->deleteAll(
                    [
                        'language_id' => $this->request->getData('id')
                    ]
                );
                $languageSettingList = $this->LanguageSettings->find('all', [
                    'conditions' => [
                        'LanguageSettings.id' => $this->request->getData('id')
                    ]
                ])->hydrate(false)->first();
                $transLang = strtolower($languageSettingList['language_code']).'_'.strtoupper($languageSettingList['language_code']);
                $file = ROOT . DS . 'src' . DS .'Locale/'.$transLang.'/default.po';
                unlink($file);
                $path = ROOT . DS . 'src' . DS .'Locale/'.$transLang;
                rmdir($path);
                //chmod($path, 0777);
                //$folder = new File($myfile);
                //$folder->delete();
                
                list($languageList) = $this->index('Languagepage');
                if($this->request->is('ajax')) {
                    $action         = 'Languagepage';
                    $this->set(compact('action', 'languageList'));
                    $this->render('ajaxaction');
                }
            }
        }
    }# delete language function end...
#----------------------------------------------------------------------------------
}#class end...