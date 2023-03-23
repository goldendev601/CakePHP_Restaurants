<?php
/**
 * Created by VimalaAnbalagan.
 * User: admin6
 * Date: 04/18/16
 * Time: 07:32 PM
 */

namespace App\Controller\Component;


use Cake\Controller\Component;

class GooglemapComponent extends Component
{
    /**
     * @param $address
     * @return array
     */
    /*Get Latitude and Longitude*/
    public function getLatitudeLongitude($address) {

        $prepAddr = str_replace(' ', '+', $address);

        //$url = "https://maps.google.com/maps/api/geocode/json?address=$prepAddr&key=AIzaSyA_PDTRdxnfHvK3V6-pApjZQgY8F8E5zOM&sensor=false&region=India";

        $url = "https://maps.google.com/maps/api/geocode/json?address=$prepAddr&key=AIzaSyAql4yBAyykHUGfXRicgL5_1YH9-ZeWk3s&sensor=false&region=India";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        $response_a = json_decode($response);

        $latitude = $response_a->results[0]->geometry->location->lat;
        $longitude = $response_a->results[0]->geometry->location->lng;


        return array('lat' => $latitude, 'long' => $longitude, 'city' => '', 'state' => '', 'country' => '');
    }

    /**
     *Googlemap::getCircleColors()
     *
     * @return array
     */
    public function getCircleColors($position) {

        // echo $position;
        $color = array(
            "#800080", "#00FF00", "#FF00FF", "#FF0000", "#0000FF", "#808000", "#008000", "#00FFFF", "#C71585",
            "#B0E0E6", "#FFDAB9", "#edc50c", "#a0ed0c", "#0ceda6", "#0c66ed", "#0c17ed", "#6b0ced", "#d50ced",
            "#ed0cbb", "#ed0c51", "#f69d9d", "#f69de2", "#da9df6", "#b29df6", "#9dacf6", "#9de6f6", "#1b535f",
            "#052228", "#1e2f33", "#486166", "#0b6b7f", "#0b7f5d", "#0a5a42", "#13dca1", "#8dcebb");
        return $color = $color[$position];
    }

    /**
     * @param $from
     * @param $to
     * @return int
     */
    public function distanceCalculation($from, $to) {

        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$from."&destinations=".$to."&mode=driving&key=AIzaSyAql4yBAyykHUGfXRicgL5_1YH9-ZeWk3s";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);

        $details = json_decode($response, true);

        $time = 0;
        $distance = 0;
        //echo 'dddd------'.$details['rows'][0]['elements'][0]['distance']['value'];
        if (!empty($details['rows'][0]['elements'][0]['distance'])) {

            //echo 'come';
            //echo 'dddd------'.$details->rows[0]->elements[0]->distance->value;
            $distance = $details['rows'][0]['elements'][0]['distance']['value'];

            $distance = number_format(($distance/1000), 1);

            return str_replace(',','',$distance);
        }


    }

    /**
     * @param $lat1
     * @param $lon1
     * @param $lat2
     * @param $lon2
     * @param $unit
     * @return float
     */
    public function distance($lat1, $lon1, $lat2, $lon2, $unit) {

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper('k');

        if ($unit == "K") {
            return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }

    /**
     * @param $sourceLat
     * @param $sourceLong
     * @param $destinationLat
     * @param $destinationLong
     * @return array|int
     */
    public function getDrivingDistance($sourceLat, $sourceLong, $destinationLat, $destinationLong) {


        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=$sourceLat,$sourceLong&destinations=$destinationLat,$destinationLong&mode=driving&key=AIzaSyAql4yBAyykHUGfXRicgL5_1YH9-ZeWk3s";
        //echo $url;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);

        $details = json_decode($response, true);


        $time = 0;
        $distance = 0;
        if (!empty($details['rows'][0]['elements'][0]['distance'])) {

            $distance = $details['rows'][0]['elements'][0]['distance']['value'];

            $distance = number_format(($distance/1000), 1);

            $distance = array(
                'distanceValue' => $details['rows'][0]['elements'][0]['distance']['value'],
                'distanceText' => $details['rows'][0]['elements'][0]['distance']['text'],
                'durationValue' => $details['rows'][0]['elements'][0]['duration']['value'],
                'durationText' => $details['rows'][0]['elements'][0]['duration']['text']
            );

            return $distance;
            //return str_replace(',','',$distance);
        }
    }

    function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
    {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $lonDelta = $lonTo - $lonFrom;
        $a = pow(cos($latTo) * sin($lonDelta), 2) +
            pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
        $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

        $angle = atan2(sqrt($a), $b);
        return $angle * $earthRadius;

        /* $dist   = acos(sin(deg2rad($lat1))
            * sin(deg2rad($lat2))
            + cos(deg2rad($lat1))
            * cos(deg2rad($lat2))
            * cos(deg2rad($lon1 - $lon2)));

        $dist   = rad2deg($dist);
        $miles  = (float) $dist * 69;

        // To get kilometers, multiply miles by 1.61
        $display     = (float) $miles * 1.61;

        // This is all displaying functionality


        return $display ;*/
    }
}