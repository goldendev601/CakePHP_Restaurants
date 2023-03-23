<?php if($action == 'getDriverLists') { ?>
    <?php if (!empty($availableDrivers) && !empty($orderId)) {
        foreach ($availableDrivers as $key => $value) { ?>

            <tr class="odd gradeX">
                <td><?php echo $key + 1; ?></td>
                <td> <?php echo $value['driver_name'] . ' - ' . $value['phone_number']; ?> </td>
                <td> <?php echo $value['vechile_name']; ?> </td>
                <td><a class="buttonStatus" href="javascript:void(0);">
                        <i class="fa fa-check"></i></a></td>
                <td> <?php echo (!empty($value['distance'])) ?
                        $value['distance'] : 'Out of Range'; ?> </td>
                <td> <?php echo $value['reachtime']; ?> </td>
                <td align="center">

                    <a class="buttonAssign" href="javascript:void(0);"
                       id="assign<?php echo $value['id']; ?>"
                       onclick="return assignOrder(<?php echo $orderId . ',' . $value['id']; ?>);"><i
                            class="fa fa-car"></i> Assign Order</a>

                    <a class="btn btn-info" id="waiting<?php echo $value['id']; ?>"
                       style="display: none;">Waiting for Acceptance</a>

                </td>
            </tr> <?php
        }
    } else { ?>
        <tr><?php echo "No Records Found" ?>
        </tr>
        <?php
    }
    die();
} ?>

<?php if($action == 'InitialTracking') {
    echo $this->GoogleMap->map();
    die();
    }
?>


<?php if($action == 'LoadTrackingMap') {


    $customerLatitude = (isset($orders['destination_latitude'])) ? $orders['destination_latitude'] : '';
    $customerLongitude = (isset($orders['destination_longitude'])) ? $orders['destination_longitude'] : '';

    $storeLatitude = (isset($orders['source_latitude'])) ? $orders['source_latitude'] : '';
    $storeLongitude = (isset($orders['source_longitude'])) ? $orders['source_longitude'] : '';

    #Customer
    echo $this->GoogleMap->addMarker("map_canvas", 1, array('latitude' => $customerLatitude, 'longitude' => $customerLongitude), array(
        'markerIcon' => BASE_URL . '/images/customer.png',
        'windowText' => $orders['customer_name'] . '</br>' . $orders['order_number'] . '</br>' . $orders['delivery_date'] . ' ' . $orders['created'] . '</br>' . $siteSettings['site_currency'] .' '. number_format($orders['order_grand_total'],2)
    ));

    #store

    #if ($orders['Statuses']['status'] != 'Picked up' && $orders['Statuses']['status'] != 'On the way') {
    echo $this->GoogleMap->addMarker("map_canvas", 1, array('latitude' => $storeLatitude, 'longitude' => $storeLongitude), array(
        'markerIcon' => BASE_URL . '/images/store.png',
        'windowText' => stripslashes($orders['restaurant']['restaurant_name'])
    ));
    #}


    if (isset($drivers)) {
        #Drivers
        foreach ($drivers as $k => $v) {
            echo $this->GoogleMap->addMarker("map_canvas", 1, array('latitude' => $drivers[$k]['driver_tracking']['driver_latitude'], 'longitude' => $drivers[$k]['driver_tracking']['driver_longitude']), array(
                'markerIcon' => BASE_URL . '/images/' . str_replace(' ', '', strtolower($orders['status'])) . '.png',
                'windowText' => $v['driver_name'] . '</br>' . $orders['order_number']
            ));
        }
        echo '||@@||';
    } else {
        echo $this->GoogleMap->addMarker("map_canvas", 1, array('latitude' => $driverDetails['driver_tracking']['driver_latitude'], 'longitude' => $driverDetails['driver_tracking']['driver_longitude']), array(
            'markerIcon' => BASE_URL . '/images/' . str_replace(' ', '', strtolower($orders['status'])) . '.png',
            'windowText' => $driverDetails['driver_name'] . '</br>' . $orders['order_number'] . '</br>' . date('Y-m-d',strtotime($orders['delivery_date'])) . ' ' . date('Y-m-d h:i A',strtotime($orders['created']))
        ));

        #Driver Direction
        if ($orders['status'] == 'Driver Accepted') {
            echo $this->GoogleMap->getDirections("map_canvas", "directions1", array(
                "from" => array("latitude" => $driverDetails['driver_tracking']['driver_latitude'], "longitude" => $driverDetails['driver_tracking']['driver_longitude']),
                "to" => array("latitude" => $storeLatitude, "longitude" => $storeLongitude)
            ));
            echo "<input type='hidden' id='avail' name='direction' value='available'>";
            echo '||@@||';
            ?>
            <span>Aproximate Distance To Restaurant : <?php echo $orders['distance']['distanceText']; ?></span>
            <span>Aproximate Time To Restaurant : <?php echo $orders['distance']['durationText'] ?></span>
            <?php
        } elseif ($orders['status'] == 'Collected') {
            echo $this->GoogleMap->getDirections("map_canvas", "directions1", array(
                "from" => array("latitude" => $driverDetails['driver_tracking']['driver_latitude'], "longitude" => $driverDetails['driver_tracking']['driver_longitude']),
                "to" => array("latitude" => $customerLatitude, "longitude" => $customerLongitude)
            ));
            echo "<input type='hidden' name='direction' value='available'>";
            echo '||@@||';
            ?>
            <span>Aproximate Distance To Customer : <?php echo $orders['distance']['distanceText']; ?></span>
            <span>Aproximate Time To Customer : <?php echo $orders['distance']['durationText'] ?></span>
            <?php
        } elseif ($orders['status'] == 'Delivered') {
            echo '||@@||';
            echo 'This order was completed by ' . $driverDetails['driver_name'];
        }
    }
    die();
    }
?>

<?php

    if($action == 'LoadDispatchMap') {

        $customerLatitude = (isset($orders['destination_latitude'])) ? $orders['destination_latitude'] : '';
        $customerLongitude = (isset($orders['destination_longitude'])) ? $orders['destination_longitude'] : '';

        $storeLatitude = (isset($orders['source_latitude'])) ? $orders['source_latitude'] : '';
        $storeLongitude = (isset($orders['source_longitude'])) ? $orders['source_longitude'] : '';

        #Customer
        echo $this->GoogleMap->addMarker("map_canvas", 1, array('latitude' => $customerLatitude, 'longitude' => $customerLongitude), array(
            'markerIcon' => BASE_URL . '/images/customer.png',
            'windowText' => $orders['customer_name'] . '</br>' . $orders['order_number'] . '</br>' . $orders['delivery_date'] . ' ' . $orders['created'] . '</br>' . $siteSettings['site_currency'] .' '. number_format($orders['order_grand_total'],2)
        ));

        #store

        #if ($orders['Statuses']['status'] != 'Picked up' && $orders['Statuses']['status'] != 'On the way') {
        echo $this->GoogleMap->addMarker("map_canvas", 1, array('latitude' => $storeLatitude, 'longitude' => $storeLongitude), array(
            'markerIcon' => BASE_URL . '/images/store.png',
            'windowText' => stripslashes($orders['restaurant']['restaurant_name'])
        ));



    $icon = strtolower($orders['status']) != 'waiting' ? strtolower($orders['status']) : 'driveraccepted';

    echo $this->GoogleMap->addMarker("map_canvas", 'driver_'.$driverDetails['driver_tracking']['driver_id'], array('latitude' => $driverDetails['driver_tracking']['driver_latitude'], 'longitude' => $driverDetails['driver_tracking']['driver_longitude']), array(
        'markerIcon' => BASE_URL . '/images/' . str_replace(' ', '', $icon) . '.png',
        'windowText' => $driverDetails['driver_name'] . '</br>' . $orders['order_number'] . ' - '.$orders['status'].'</br>' . $orders['delivery_time']
    ));


    //Zoom visible markers
    $latlong = '[';
    if(!empty($customerLatitude) && !empty($customerLongitude)) {
        $latlong  .=  ' {
                                    latlng: new google.maps.LatLng('.$customerLatitude.','. $customerLongitude.')
                                },
                                ';
    }

    if(!empty($storeLatitude) && !empty($storeLongitude)) {
        $latlong  .=  ' {
                                    latlng: new google.maps.LatLng('.$storeLatitude.','. $storeLongitude.')
                                },
                                ';
    }


    if(!empty($driverDetails['driver_tracking']['driver_latitude']) && !empty($driverDetails['driver_tracking']['driver_longitude'])) {
        $latlong  .=  '{
                                    latlng: new google.maps.LatLng('.$driverDetails['driver_tracking']['driver_latitude'].','. $driverDetails['driver_tracking']['driver_longitude'].')
                                },';
    }

    $latlong .= ']';




    #Driver Direction
    if ($orders['Order']['status'] == 'Driver Accepted') {
        echo $this->GoogleMap->getDirections("map_canvas", "directions1", array(
            "from" => array("latitude" => $driverDetails['driver_tracking']['driver_latitude'], "longitude" => $driverDetails['driver_tracking']['driver_longitude']),
            "to" => array("latitude" => $storeLatitude, "longitude" => $storeLongitude)
        ));
        echo "<input type='hidden' name='direction' value='available'>";
        echo '||@@||';
        ?>
        <span>Aproximate Distance To Restaurant : <?php echo $orders['distance']['distanceText']; ?></span>
        <span>Aproximate Time To Restaurant : <?php echo $orders['distance']['durationText'] ?></span>
        <?php
    } elseif ($orders['Order']['status'] == 'Collected') {
        echo $this->GoogleMap->getDirections("map_canvas", "directions1", array(
            "from" => array("latitude" => $driverDetails['driver_tracking']['driver_latitude'], "longitude" => $driverDetails['driver_tracking']['driver_longitude']),
            "to" => array("latitude" => $customerLatitude, "longitude" => $customerLongitude)
        ));
        echo "<input type='hidden' name='direction' value='available'>";
        echo '||@@||';
        ?>
        <span>Aproximate Distance To Customer : <?php echo $orders['distance']['distanceText']; ?></span>
        <span>Aproximate Time To Customer : <?php echo $orders['distance']['durationText'] ?></span>
        <?php
    } elseif ($orders['Order']['status'] == 'Delivered') {
        echo '||@@||';
        echo 'This order was completed by ' . $driverDetails['driver_name'];
    }
    ?>


    <script>
        var latlong = <?php echo $latlong;?>;
        if(latlong != '') {
            zoomMarkers(latlong);
        }
    </script>
<?php
    die();
}
?>

<?php if($action == 'LoadAllMap') {

    echo $map = $this->GoogleMap->map();
    $latlong    =   '[';
    foreach ($orders as $key => $value) {


        $customerLatitude = (isset($value['destination_latitude'])) ? $value['destination_latitude'] : '';
        $customerLongitude = (isset($value['destination_longitude'])) ? $value['destination_longitude'] : '';

        $storeLatitude = (isset($value['source_latitude'])) ? $value['source_latitude'] : '';
        $storeLongitude = (isset($value['source_longitude'])) ? $value['source_longitude'] : '';

        #Customer
        echo $this->GoogleMap->addMarker("map_canvas", 1, array('latitude' => $customerLatitude, 'longitude' => $customerLongitude), array(
            'markerIcon' => BASE_URL . '/images/customer.png',
            'windowText' => $value['customer_name'] . '</br>' . $value['order_number'] . '</br>' . $value['delivery_date'] . ' ' . $value['created'] . '</br>' . $siteSettings['site_currency'] .' '. number_format($value['order_grand_total'],2)
        ));

        #store

        #if ($orders['Statuses']['status'] != 'Picked up' && $orders['Statuses']['status'] != 'On the way') {
        echo $this->GoogleMap->addMarker("map_canvas", 1, array('latitude' => $storeLatitude, 'longitude' => $storeLongitude), array(
            'markerIcon' => BASE_URL . '/images/store.png',
            'windowText' => stripslashes($value['restaurant']['restaurant_name'])
        ));

        if(!empty($customerLatitude) && !empty($customerLongitude)) {
            $latlong  .=  ' {
                                        latlng: new google.maps.LatLng('.$customerLatitude.','. $customerLongitude.')
                                    },
                                    ';
        }

        if(!empty($storeLatitude) && !empty($storeLongitude)) {
            $latlong  .=  ' {
                                        latlng: new google.maps.LatLng('.$storeLatitude.','. $storeLongitude.')
                                    },
                                    ';
        }
    }



    if(!empty($Drivers)) {
        foreach ($Drivers as $key => $value) {

            $status = '';
            if(!empty($value['Order'])){
                foreach ($value['Order'] as $key => $orderstatus) {
                    $status .= $orderstatus['order_number']." - ".$orderstatus['status']."<br>"." - ".$orderstatus['delivery_time']."<br>";
                }

                echo $this->GoogleMap->addMarker(
                    "map_canvas",
                    1,
                    [
                        'latitude' => $value['DriverTracking']['driver_latitude'],
                        'longitude' => $value['DriverTracking']['driver_longitude']
                    ],
                    [
                        'markerIcon' => BASE_URL . '/images/driveraccepted.png',
                        'windowText' => $value['Driver']['driver_name']."<br>".$status
                    ]
                );

                if(!empty($value['DriverTracking']['driver_latitude']) && !empty($value['DriverTracking']['driver_longitude'])) {
                    $latlong  .=  '{
                                                latlng: new google.maps.LatLng('.$value['DriverTracking']['driver_latitude'].','. $value['DriverTracking']['driver_longitude'].')
                                            },';
                }
            }



        }
    }

    $latlong    .=   ']';


    ?>

    <script>

        var latlong = <?php echo $latlong;?>;
        if(latlong != '') {
            zoomMarkers(latlong);
        }

    </script>
<?php
    die();
}
?>

<?php if($action == 'dispatchUpdate') { ?>

    <div id="Unassign" class="fos-common">
        <div class="fos-new-input">
            <input type="text" onkeyup="fossearchorder(this)" class="form-control fos-input"><i class="fa fa-search"></i>
        </div>
        <?php if(!empty($acceptedOrder)) {

            foreach ($acceptedOrder as $aKey => $aValue) { ?>
                <div class="fos-order-part fos-order-part_<?= $aValue['restaurant_id'] ?>">
                    <div onclick="return trackingMap(<?= $aValue['id'] ?>,<?= $aValue['restaurant_id'] ?>);" class="col-xs-12 no-padding">
                        <div class="fos-rest-name-details">
                            <i class="fa fa-building-o"></i><?= $aValue['restaurant']['restaurant_name'] ?>
                            <div class="fos-ord-status pull-right"><?= $aValue['status'] ?></div>
                        </div>
                        <div class="fos-ord-id">
                            <i class="fa fa-tasks"></i><?= $aValue['order_number'] ?>
                            <div class="col-sm-5 fos-ord-payment no-padding-right pull-right text-right"><?= strtoupper($aValue['payment_method']) ?></div>
                        </div>
                        <div class="fos-cust-name">
                            <i class="fa fa-user"></i><?= $aValue['customer_name'] ?>
                            <div class="col-sm-5 fos-timeanddate no-padding pull-right text-right"><i class="fa fa-clock-o"></i><?= ($aValue['assoonas'] == 'now') ? 'ASAP': $aValue['delivery_time'] ?></div>
                        </div>
                        <div class="fos-cust-address"><i class="fa fa-map-marker"></i><?= $aValue['address'] ?></div>
                    </div>
                    <div class="fos-assign-status">View</div>
                    <div class="fos-assign-btn">Assign</div>
                </div>
                <?php

            }
        } ?>

    </div>
    <div style="display:none;" id="Waiting" class="fos-common">
        <div class="fos-no-orders">No Orders</div>
    </div>
    <div style="display:none;" id="Assign" class="fos-common">
        <div class="fos-no-orders">No Orders</div>
    </div>
    <div style="display:none;" id="Complete" class="fos-common">
        <div class="fos-new-input">
            <input type="text" onkeyup="fossearchorder(this)" class="form-control fos-input"><i class="fa fa-search"></i>
        </div>
        <div class="fos-order-part">
            <div class="fos-rest-name-details">
                <i class="fa fa-building-o"></i>Mohan Restaurant
                <div class="fos-ord-status pull-right">Delivered</div>
            </div>
            <div class="fos-ord-id">
                <i class="fa fa-tasks"></i>ORD003588
                <div class="col-sm-5 fos-ord-payment no-padding-right pull-right text-right">COD</div>
            </div>
            <div class="fos-cust-name">
                <i class="fa fa-user"></i>Vss manikandan
                <div class="col-sm-5 fos-timeanddate no-padding pull-right text-right"><i class="fa fa-clock-o"></i>ASAP</div>
            </div>
            <div class="fos-cust-address"><i class="fa fa-map-marker"></i>Vadapalani, Chennai, Tamil Nadu, India</div>
            <div class="fos-assign-status">View</div>
        </div>
    </div>

    <input type="hidden" id="unassignCount" value="<?php echo count($acceptedOrder);?>"></input>
    <input type="hidden" id="assignCount" value="<?php //echo count($driverAcceptList);?>"></input>
    <input type="hidden" id="completeCount" value="<?php //echo count($completedList);?>"></input>
    <input type="hidden" id="waitCount" value="<?php //echo count($waitingList);?>"></input>

<?php die(); } ?>
