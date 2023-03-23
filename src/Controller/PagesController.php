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

use Cake\Core\Configure;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController
{
    public function initialize(){
        parent::initialize();
        $this->viewBuilder()->setLayout('frontend');
        $this->loadComponent('Auth');
        $this->Auth->allow([
            'languagechange',
            'pdf',
            'getPages'
        ]);
    }
    /**
     * Displays a view
     *
     * @param string ...$path Path segments.
     * @return void|\Cake\Network\Response
     * @throws \Cake\Network\Exception\ForbiddenException When a directory traversal attempt.
     * @throws \Cake\Network\Exception\NotFoundException When the view file could not
     *   be found or \Cake\View\Exception\MissingTemplateException in debug mode.
     */

    public function display(...$path)
    {
        $count = count($path);
        if (!$count) {
            return $this->redirect('/');
        }
        if (in_array('..', $path, true) || in_array('.', $path, true)) {
            throw new ForbiddenException();
        }
        $page = $subpage = null;

        if (!empty($path[0])) {
            $page = $path[0];
        }
        if (!empty($path[1])) {
            $subpage = $path[1];
        }
        $this->set(compact('page', 'subpage'));

        try {
            $this->render(implode('/', $path));
        } catch (MissingTemplateException $e) {
            if (Configure::read('debug')) {
                throw $e;
            }
            throw new NotFoundException();
        }
    }
#-----------------------------------------------------------------------------------
    //Language Change
    public function languagechange(){
        if($this->request->is('ajax')){
            if($this->request->getData('languageCode') != '') {
                $languageCode = $this->request->getData('languageCode');
                $this->request->session()->write('languageCode',$languageCode);
                echo 'success';
            } else {
                echo 'failed';
            }
        }
        die();
    }# change language function end...
#----------------------------------------------------------------------------------

    public function pdf($id = null) {

        //$orderDetails = $this->
    }

    public function aboutus() {

    }

    //-----------------------------------Get CMS Pages------------------------------------------------------------------
    public function getPages($type = null) {

        $staticpage_list = $this->Staticpages->find('all',[
            'conditions' => [
                'Staticpages.seo_url'=> $type,
                'Staticpages.status'=>1,
                'Staticpages.delete_status'=>'N'
            ]
        ])->hydrate(false)->first();
        //echo '<pre>'; print_r($staticpage_list); exit();
        $this->set(compact('staticpage_list',$staticpage_list));
    }
}
