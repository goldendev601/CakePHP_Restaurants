<?php
namespace App\Controller\Component;

use Cake\Filesystem\Folder;
use App\Controller\AppController;
use cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\Mailer\Email;

class CommonComponent extends Component
{
    //generating random string
    public function generateRandomString($length = 10)
    {
        $characters 		= '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength  = strlen($characters);
        $randomString 		= '';

        for ($i = 0; $i < $length; $i++)
        {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
//------------------------------------------------------------------------
   //common File upload
    public function uploadFile($fDetail,$path) {
        $getTimeStamp = "";
        if($fDetail['name'] != '') {
            $fName = $fDetail['name'];
            $fSize = $fDetail['size'];
            $tmpName = $fDetail['tmp_name'];
            $getTimeStamp = $this->getTimeStampNumber();
            $aFnameDetail = $this->seperateFnameAndExt($fName);

            $refName = $this->concat($getTimeStamp, $aFnameDetail);

            move_uploaded_file($tmpName, $path.DS.$refName);

            $data['refName'] = $refName;
            $data['fName'] = $fName;
            $data['fSize'] = $fSize;
            $data['fExt'] = $aFnameDetail['ext'];
 
            return $data;
        } else {
            //$this->Session->setFlash('Error Uploading');
        }
    }
    //------------------------------------------------------------------------
    public function seperateFnameAndExt($fName) {
        $extention =  substr($fName, strrpos($fName,'.'));
        $extLenght = strlen($extention);
        $fnameWithOutExt = substr($fName, 0, -$extLenght);
        return array('fNameWOExt' => $fnameWithOutExt, 'ext' => strtolower($extention));
    }
//------------------------------------------------------------------------
    public function getTimeStampNumber() {
        return $timeStamp = rand().mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
    }
//------------------------------------------------------------------------
    public function concat($fileName, $aFnameData) {
        return trim($fileName.$aFnameData['ext']);
    }
//------------------------------------------------------------------------
    // Unlink  a file
    public function unlinkFile($filename, $path) {

        if(isset($filename) && !empty($filename)) {
            unlink($path."/".$filename);
        } else {
           // $this->Flash->error(_('Error Removing File'));
        }
    }
//------------------------------------------------------------------------
    //Removing last commas
    function removeFromString($str, $item) {
        $parts = explode(',', $str);

        while(($i = array_search($item, $parts)) !== false) {
            unset($parts[$i]);
        }
        return implode(',', $parts);
    }
//------------------------------------------------------------------------
    //getting seourl
    function seoUrl($string)
    {
        $text = preg_replace('~[^\\pL\d]+~u', '-', $string);

        // trim
        $text = trim($text, '-');

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        if(strlen($text) > 70) {
            $text = substr($text, 0, 70);
        }

        if (empty($text))
        {
            //return 'n-a';
            return time();
        }

        return $text;
    }
//------------------------------------------------------------------------
    //getting lat,long
    public function latlang($address)
    {
        $geo = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address).'&sensor=false');

        $geo = json_decode($geo, true);

        if($geo['status'] = 'OK')
        {
            $latitude = (empty($geo['results'][0]['geometry']['location']['lat'])) ? '0.00' : $geo['results'][0]['geometry']['location']['lat'];
            $longitude = (empty($geo['results'][0]['geometry']['location']['lng'])) ? '0.00' : $geo['results'][0]['geometry']['location']['lng'];
            $latlang[0]  = $latitude;
            $latlang[1]  = $longitude;
            return $latlang;
        }
    }
//------------------------------------------------------------------------
    //getting km,mile based distance
    public function distanceCal($value, $unit){
        // one miles equal to 1609.344 meters
        // one Km equal to 1000.000 meters$outletCount
        if($unit == 'M')
            return $value * 1609.344;
        else if($unit == 'Km')
            return $value * 1000.000;
    }
//------------------------------------------------------------------------
    function generateId($refid)
    {
        if (!empty($refid) && ($refid > 0))
        {
            if ($refid < 10)
            {
                $generate = '000' . $refid;
            } elseif (($refid >= 10) && ($refid < 100))
            {
                $generate = '00' . $refid;
            } elseif (($refid >= 100) && ($refid < 1000))
            {
                $generate = '0' . $refid;
            } else
            {
                $generate = $refid;
            }
        }

        return $generate;
    }
//------------------------------------------------------------------------
    function isAuthorized($role_id) {

        if(!empty($this->request->params['prefix'])) {
            $prefix = $this->request->params['prefix'];
        }else {
            $this->request->params['prefix'] = '';
        }

        //CHECK AUTH
        if($this->request->params['prefix'] == 'dryadmin' && $role_id == '2'){
            echo "<a href='".ADMIN_BASE_URL."users/logout'>Please Logout Front end</a>";exit();
        }
        elseif($this->request->params['prefix'] == '' && $role_id == '1'){
            echo "<a href='".BASE_URL."users/logout/'>Please Logout Admin Panel</a>";exit();
        }
    }
  //-------------------------------------------------------------------------------------------------------
    //Temporary Password
    public function passwordGenerator($length = '') {
        $input      =   "1234567890abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $password   =   substr(str_shuffle($input), 0, $length);
        return $password;
    }
  //---------------------------------------------------------------------------------------------------------
    //Get Mail Content
    public function getMailContent($title) {
        $mailTable      = TableRegistry::get('notifications');
        $mailContent    = $mailTable->find('all', [
            'conditions'    => [
                'title'     => $title
            ]
        ]);
        $content = !empty($mailContent) ? $mailContent->toArray() : '';
        return $content;
    }
    //-------------------------------------------------------------------------------------------------------
    //Common send mail content
    public function sendMail($title,$content)
    {
        switch($title) {

            case 'Register':
                $message        = "Your Successfully Registered with ".SITE_NAME.".Please Login your account.";
                $emailContent   = $this->getMailContent('Register');
                $mailContent    = $emailContent[0]['content'];
                $mailSubject    = $emailContent[0]['subject'];
                $mailContent    = str_replace("{firstname}", $content['firstname'], $mailContent);
                $mailContent    = str_replace("{username}", $content['username'], $mailContent);
                $mailContent    = str_replace("{password}", $content['password'], $mailContent);
                $mailContent    = str_replace("{message}", $message, $mailContent);
                $toMailId       = $content['user'];
                $fromMailId     = $content['admin'];
                break;

            case "Register To Admin":
                $message        = "New User Register with us.";
                $emailContent   = $this->getMailContent('Register To Admin');
                $mailContent    = $emailContent[0]['content'];
                $mailSubject    = $emailContent[0]['subject'];
                $mailContent    = str_replace("{firstname}", $content['firstname'], $mailContent);
                $mailContent    = str_replace("{message}", $message, $mailContent);
                $mailContent    = str_replace("{name}", $content['name'], $mailContent);
                $mailContent    = str_replace("{email}", $content['email'], $mailContent);
                $mailContent    = str_replace("{mobile}", $content['mobile'], $mailContent);
                $toMailId       = $content['admin'];
                $fromMailId     = $content['user'];
                break;

            case "Contact To Admin":
                $message        = "User wants to contact with us.";
                $emailContent   = $this->getMailContent('Contact To Admin');
                $mailContent    = $emailContent[0]['content'];
                $mailSubject    = $emailContent[0]['subject'];
                $mailContent    = str_replace("{firstname}", $content['firstname'], $mailContent);
                $mailContent    = str_replace("{message}", $message, $mailContent);
                $mailContent    = str_replace("{email}", $content['email'], $mailContent);
                $toMailId       = $content['toMailId'];
                $fromMailId     = $content['fromMailId'];
                break;

            case "Forgot Password":
                $message        = "Please Login your account, to change permanent password";
                $emailContent   = $this->getMailContent('Forgot Password');
                $mailContent    = $emailContent[0]['content'];
                $mailSubject    = $emailContent[0]['subject'];
                $mailContent    = str_replace("{firstname}", $content['firstname'], $mailContent);
                $mailContent    = str_replace("{message}", $message, $mailContent);
                $mailContent    = str_replace("{email}", $content['email'], $mailContent);
                $mailContent    = str_replace("{password}", $content['password'], $mailContent);
                $toMailId       = $content['toMailId'];
                $fromMailId     = $content['fromMailId'];
                break;

             case 'Facebook Signup':

                $emailContent   = $this->getMailContent('Facebook Signup');
                $mailContent    = $emailContent[0]['content'];
                $mailSubject    = $emailContent[0]['subject'];
                $mailContent    = str_replace("{Date}", $content['date'], $mailContent);
                $mailContent    = str_replace("{Name}", $content['name'], $mailContent);
                $mailContent    = str_replace("{Email}", $content['email'], $mailContent);
                $mailContent    = str_replace("{Password}", $content['password'], $mailContent);               
                $toMailId       = $content['toMailId'];
                $fromMailId     = $content['fromMailId'];
                break;       

            default:
                $mailContent    = '';
                $mailSubject    = '';
                $toMailId       = '';
                $fromMailId     = '';
                break;
        }

        $email = new CakeEmail;
        $email->setFrom([$fromMailId])
            ->setTo($toMailId)
            ->setSubject($mailSubject)
            ->setTemplate('default')
            ->setEmailFormat('html')
            ->setViewVars([
                'content' => $mailContent
            ]);

        return $email->send();
    }

 //------------------------------------------------------------------------------------------------
    //Calculate distance from latitude and longitude
    function getDistanceValue($latitudeFrom,$longitudeFrom,$latitudeTo,$longitudeTo,$unit){
        $theta = $longitudeFrom - $longitudeTo;
        $dist = sin(deg2rad((double)$latitudeFrom)) * sin(deg2rad((double)$latitudeTo)) +  cos(deg2rad((double)$latitudeFrom)) * cos(deg2rad((double)$latitudeTo)) * cos(deg2rad((double)$theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);
        if ($unit == "K") {
            return number_format(($miles * 1.609344),2);
        } else if ($unit == "N") {
            return ($miles * 0.8684).' nm';
        } else {
            return $miles.' mi';
        }
    }
//--------------------------------------------------------------------------------------------------------
    //getting location
    public function getAddress($latitude, $longitude)
    {
        $geolocation = $latitude . ',' . $longitude;
        $request = 'http://maps.googleapis.com/maps/api/geocode/json?latlng=' . $geolocation . '&sensor=false';
        $file_contents = file_get_contents($request);
        $json_decode = json_decode($file_contents);
        return $json_decode->results[0]->formatted_address;
    }
 //--------------------------------------------------------------------------------------------------------
    public function parseSerialize($serializeData) {
        $data = array();
        parse_str($serializeData, $data);
        return $data;
    }


    //-----------Temp Password--------------//
    public function createTempPassword($len) {
        $pass = '';
        $lchar = 0;
        $char = 0;
        for($i = 0; $i < $len; $i++) {
            while($char == $lchar) {
                $char = rand(48, 109);
                if($char > 57) $char += 7;
                if($char > 90) $char += 6;
            }
            $pass .= chr($char);
            $lchar = $char;
        }
        return $pass;
    }

    //-----------------------make directory---------------------//
    public function mkdir($targetdir) {
        if(!is_dir($targetdir)) {

            $dir = new Folder($targetdir, true, 0777);
            if(!$dir) {
                return false;
            }
        }
        return true;
    }
    
}#class end...