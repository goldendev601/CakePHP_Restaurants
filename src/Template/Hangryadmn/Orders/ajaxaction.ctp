<?php if($action == 'getDriverLists') { ?>
    <?php if (!empty($availableDrivers) && !empty($orderId)) {
        foreach ($availableDrivers as $key => $value) { ?>

            <tr class="odd gradeX">
                <td><?php echo $key + 1; ?></td>
                <td> <?php echo $value['driver_name'] . ' - ' . $value['phone_number']; ?> </td>

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
            if(!empty($value['orders'])){

                foreach ($value['orders'] as $key => $orderstatus) {
                    $status .= $orderstatus['order_number']." - ".$orderstatus['status']."<br>"." - ".$orderstatus['delivery_time']."<br>";
                }

                echo $this->GoogleMap->addMarker(
                    "map_canvas",
                    1,
                    [
                        'latitude' => $value['driver_tracking']['driver_latitude'],
                        'longitude' => $value['driver_tracking']['driver_longitude']
                    ],
                    [
                        'markerIcon' => BASE_URL . '/images/driveraccepted.png',
                        'windowText' => $value['driver_name']."<br>".$status
                    ]
                );

                if(!empty($value['driver_tracking']['driver_latitude']) && !empty($value['driver_tracking']['driver_longitude'])) {
                    $latlong  .=  '{
                                                latlng: new google.maps.LatLng('.$value['driver_tracking']['driver_latitude'].','. $value['driver_tracking']['driver_longitude'].')
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
                    <div class="fos-assign-status" data-toggle="modal" onclick="return viewOrder(<?= $aValue['id'] ?>)">View</div>
                    <div class="fos-assign-btn" onclick="return getDriverList(<?= $aValue['id'] ?>)">Assign</div>
                </div>
                <?php
            }
        }else { ?>
            <div class="fos-no-orders">No Orders</div>

        <?php } ?>

    </div>
    <div style="display:none;" id="Waiting" class="fos-common">
        <div class="fos-new-input">
            <input type="text" onkeyup="fossearchorder(this)" class="form-control fos-input"><i class="fa fa-search"></i>
        </div>
        <?php if(!empty($waitingOrders)) {
            foreach ($waitingOrders as $wKey => $wvalue) { ?>

                <div class="fos-order-part fos-order-part_<?= $wvalue['restaurant_id'] ?>">
                    <div onclick="return trackingMap(<?= $wvalue['id'] ?>,<?= $wvalue['restaurant_id'] ?>);" class="col-xs-12 no-padding">
                        <div class="fos-rest-name-details">
                            <i class="fa fa-building-o"></i><?= $wvalue['restaurant']['restaurant_name'] ?>
                            <div class="fos-ord-status pull-right"><?= $wvalue['status'] ?></div>
                        </div>
                        <div class="fos-ord-id">
                            <i class="fa fa-tasks"></i><?= $wvalue['order_number'] ?>
                            <div class="col-sm-5 fos-ord-payment no-padding-right pull-right text-right"><?= strtoupper($wvalue['payment_method']) ?></div>
                        </div>
                        <div class="fos-cust-name">
                            <i class="fa fa-user"></i><?= $wvalue['customer_name'] ?>
                            <div class="col-sm-5 fos-timeanddate no-padding pull-right text-right"><i class="fa fa-clock-o"></i><?= ($wvalue['assoonas'] == 'now') ? 'ASAP': $wvalue['delivery_time'] ?></div>
                        </div>
                        <div class="fos-cust-address"><i class="fa fa-map-marker"></i><?= $wvalue['address'] ?></div>
                    </div>
                    <div class="fos-assign-status" data-toggle="modal" onclick="return viewOrder(<?= $wvalue['id'] ?>)">View</div>
                    <?php
                    if(!empty($wvalue['driver']['driver_name'])) { ?>
                        <div class="fos-assign-btn"><?php echo $wvalue['driver']['driver_name'];?></div><?php
                    }
                    ?>
                    <div class="fos-assign-btn" onclick="return rejectOrder(<?= $wvalue['id'] ?>)">Rejects</div>
                </div>

                <?php
            }
        }else { ?>
            <div class="fos-no-orders">No Orders</div>
        <?php } ?>
    </div>
    <div style="display:none;" id="Assign" class="fos-common">
        <div class="fos-new-input">
            <input type="text" onkeyup="fossearchorder(this)" class="form-control fos-input"><i class="fa fa-search"></i>
        </div>

        <?php if(!empty($assignedOrders)) {
            foreach ($assignedOrders as $assignKey => $assignvalue) { ?>

                <div class="fos-order-part fos-order-part_<?= $assignvalue['restaurant_id'] ?>">
                    <div onclick="return trackingMap(<?= $assignvalue['id'] ?>,<?= $assignvalue['restaurant_id'] ?>);" class="col-xs-12 no-padding">
                        <div class="fos-rest-name-details">
                            <i class="fa fa-building-o"></i><?= $assignvalue['restaurant']['restaurant_name'] ?>
                            <div class="fos-ord-status pull-right"><?= $assignvalue['status'] ?></div>
                        </div>
                        <div class="fos-ord-id">
                            <i class="fa fa-tasks"></i><?= $assignvalue['order_number'] ?>
                            <div class="col-sm-5 fos-ord-payment no-padding-right pull-right text-right"><?= strtoupper($assignvalue['payment_method']) ?></div>
                        </div>
                        <div class="fos-cust-name">
                            <i class="fa fa-user"></i><?= $assignvalue['customer_name'] ?>
                            <div class="col-sm-5 fos-timeanddate no-padding pull-right text-right"><i class="fa fa-clock-o"></i><?= ($assignvalue['assoonas'] == 'now') ? 'ASAP': $assignvalue['delivery_time'] ?></div>
                        </div>
                        <div class="fos-cust-address"><i class="fa fa-map-marker"></i><?= $assignvalue['address'] ?></div>
                    </div>
                    <div class="fos-assign-status" data-toggle="modal" onclick="return viewOrder(<?= $assignvalue['id'] ?>)">View</div>
                    <?php
                    if(!empty($assignvalue['driver']['driver_name'])) { ?>
                        <div class="fos-assign-btn"><?php echo $assignvalue['driver']['driver_name'];?></div><?php
                    }
                    ?>
                    <div class="fos-assign-btn" onclick="return rejectOrder(<?= $assignvalue['id'] ?>)">Reject</div>
                </div>

                <?php
            }
        }else { ?>
            <div class="fos-no-orders">No Orders</div>
        <?php } ?>
    </div>
    <div style="display:none;" id="Complete" class="fos-common">
        <div class="fos-new-input">
            <input type="text" onkeyup="fossearchorder(this)" class="form-control fos-input"><i class="fa fa-search"></i>
        </div>
        <?php if(!empty($completedOrders)) {
            foreach ($completedOrders as $cKey => $cValue) { ?>

                <div class="fos-order-part fos-order-part_<?= $cValue['restaurant_id'] ?>">
                    <div onclick="return trackingMap(<?= $cValue['id'] ?>,<?= $cValue['restaurant_id'] ?>);" class="col-xs-12 no-padding">
                        <div class="fos-rest-name-details">
                            <i class="fa fa-building-o"></i><?= $cValue['restaurant']['restaurant_name'] ?>
                            <div class="fos-ord-status pull-right"><?= $cValue['status'] ?></div>
                        </div>
                        <div class="fos-ord-id">
                            <i class="fa fa-tasks"></i><?= $cValue['order_number'] ?>
                            <div class="col-sm-5 fos-ord-payment no-padding-right pull-right text-right"><?= strtoupper($cValue['payment_method']) ?></div>
                        </div>
                        <div class="fos-cust-name">
                            <i class="fa fa-user"></i><?= $cValue['customer_name'] ?>
                            <div class="col-sm-5 fos-timeanddate no-padding pull-right text-right"><i class="fa fa-clock-o"></i><?= ($cValue['assoonas'] == 'now') ? 'ASAP': $cValue['delivery_time'] ?></div>
                        </div>
                        <div class="fos-cust-address"><i class="fa fa-map-marker"></i><?= $cValue['address'] ?></div>
                    </div>
                    <div class="fos-assign-status" data-toggle="modal" onclick="return viewOrder(<?= $cValue['id'] ?>)">View</div>
                </div>

                <?php
            }
        }else { ?>
            <div class="fos-no-orders">No Orders</div>
        <?php } ?>
    </div>

    <input type="hidden" id="unassignCount" value="<?php echo count($acceptedOrder);?>"></input>
    <input type="hidden" id="assignCount" value="<?php echo count($assignedOrders);?>"></input>
    <input type="hidden" id="completeCount" value="<?php echo count($completedOrders);?>"></input>
    <input type="hidden" id="waitCount" value="<?php echo count($waitingOrders);?>"></input>

<?php die(); } ?>

<?php if($action == 'dispatchDrivers') { ?>

    <div class="fos-new-top">Online Drivers</div>
    <?php
    $main = 0;
    $delstatus = 1;
    foreach($onlineDrivers as $key => $value) {
        $nextValue = $key+1;

        $currCount = 0;
        $compCount = 0;
        $waitCount = 0;
        foreach($value['orders'] as $keys => $vals) {
            if($vals['status'] != '') {
                if($vals['status'] != 'Delivered' && $vals['status']!= 'Waiting') {
                    $currCount++;
                } elseif($vals['status'] == 'Waiting') {
                    $waitCount++;
                } elseif($vals['status'] == 'Delivered') {
                    $compCount++;
                }
            }
        }

        $main = 0;
        if(!empty($onlineDrivers[$nextValue]['orders'])) {
            foreach($onlineDrivers[$nextValue]['orders'] as $k => $v) {
                if($v['status'] != '') {
                    if($v['status'] != 'Delivered') {
                        $main++;
                    }
                }
            }
        } ?>
        <div class="accordion_head">
        <div class="fos-rt-section">
            <div class="fos-online-drivers"><?php
                if(!empty($value['driver']['image'])) { ?>
                    <img class="fos-profile-img" onerror="this.onerror=null;this.src='<?php echo BASE_URL."/images/no-image.png"; ?>'" class="img-responsive img_fields" src="<?php //echo $siteUrl. '/driversImage/'.$value['image']; ?>" alt=""><?php
                } else { ?>
                    <img class="fos-profile-img" class="img-responsive img_fields" src="<?php echo BASE_URL."/images/no-image.png"; ?>" alt=""><?php
                }?>
                <div class="fos-names">
                    <h6><?php echo $value['driver_name'];?></h6>
                    <p><?php echo '+'.$countries['phone_code'].''.$value['phone_number'];?></p>
                </div>


                <a class="fos-view-details  pull-right"><?php
                    if($waitCount != 0) {
                        echo $waitCount.' waiting';
                    } else {
                        echo 'No waiting';
                    }
                    echo "<br>";
                    echo ($currCount == 1) ? $currCount.' Order' : ($currCount == 0 ? 'No Orders' : $currCount.' Orders');?><?php if($value['orders']['order_count'] != 0) { ?><?php } ?>
                </a>



            </div>
        </div>
        </div><?php if($value['orders']['order_count'] != 0) { ?>
            <div class="accordion_body" style="display:none;">
            <div class="fos-t-back">
                <?php
                if($value['orders']['order_count'] != 0) {

                    ?>
                    <div class="fos-tab-ord">
                    <ul class="fos-ord-tab text-center">
                        <li>
                            <a class="current-order active" data-orders="Currentorder_<?php echo $value['id'];?>">
                                <div class="text-center">Current Order (<?php echo ($currCount + $waitCount);?>)</div>
                            </a>
                        </li>
                        <li>
                            <a data-orders="Completedorder_<?php echo $value['id'];?>">
                                <div class="text-center fos-ord-de-list">Completed Order (<?php echo $compCount;?>)</div>
                            </a>
                        </li>
                    </ul>
                    </div><?php
                } ?>

                <div class="fos-c-ord fos-current-order-show" id="Currentorder_<?php echo $value['id'];?>">
                    <?php

                    // if($value['Order']['order_count'] != 0) {
                    $counts = 0;
                    foreach($value['orders'] as $key => $val) {
                        if(is_array($val)) {
                            if(!empty($val['id']) && $val['status'] != 'Delivered') {
                                $counts++;

                                ?>
                                <div class="fos-task-section">
                                <div class="fos-ordered-id"><?php echo $val['order_number'];?> <div class="fos-task-processing pull-right"><?php echo $val['status'];?> </div></div>
                                <div class="fos-ordered-cs-name"><?php echo $val['customer_name'];?></div>
                                <div class="fos-ordered-address"><?php echo $val['address'];?></div>
                                </div><?php
                            }

                        }

                    }

                    if($counts == 0) { ?>
                        <div class="fos-no-orders">No Orders</div><?php
                    }
                    ?>
                </div>
                <div class="fos-c-ord fos-completed-order-show" id="Completedorder_<?php echo $value['id'];?>"  style="display:none;">
                    <?php
                    // if($value['Order']['order_count'] != 0) {
                    $count = 0;
                    foreach($value['orders'] as $key => $val) {
                        if(is_array($val)) {
                            if(!empty($val['id']) && $val['status'] == 'Delivered') {
                                $count++;

                                ?>
                                <div class="fos-task-section">
                                <div class="fos-ordered-id"><?php echo $val['order_number'];?> <div class="fos-task-processing pull-right"><?php echo $val['status'];?> </div></div>
                                <div class="fos-ordered-cs-name"><?php echo $val['customer_name'];?></div>
                                <div class="fos-ordered-address"><?php echo $val['address'];?></div>
                                </div><?php
                            }
                        }
                    }

                    if($count == 0) { ?>
                        <div class="fos-no-orders">No Orders</div><?php
                    }
                    ?>
                </div>
            </div>
            </div><?php
        }
        if($main == 0 && $delstatus == 1 && $value['orders']['order_count'] != 0) {
            $main = 1;
            $delstatus = 0;
            ?><div class="fos-rt-section border-top-line"></div><?php
        }
    } ?>


<?php
    die();
}
?>

<?php if($action == 'viewOrder') { ?>

    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close my-close " data-dismiss="modal">×</button>
                <h4 class="modal-title">Order <?= $orderDetails['id'] ?> - <?= $orderDetails['status']?></h4>
            </div>
            <div class="modal-body fos-model-body">
                <table class="table table-bordered">
                    <thead>
                    <tr class="fos-new-top">
                        <td colspan="2">PICKUP BUSINESS DETAIL</td>
                        <td colspan="2">DELIVERY CUSTOMER DETAIL</td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><img class="fos-mod-img" src="<?= BASE_URL ?>/images/accepted.png"></td>
                        <td><?= $orderDetails['restaurant']['restaurant_name'] ?></td>
                        <td class="fos-user"><i class="fa fa-user"></i></td>
                        <td><?= $orderDetails['customer_name'] ?></td>
                    </tr>
                    <tr>
                        <td class="fos-user"><i class="fa fa-envelope"></i></td>
                        <td><?= $orderDetails['restaurant']['contact_email'] ?></td>
                        <td class="fos-user"><i class="fa fa-envelope"></i></td>
                        <td><?= $orderDetails['customer_email'] ?></td>
                    </tr>
                    <tr>
                        <td class="fos-user"><i class="fa fa-phone"></i></td>
                        <td><?= $orderDetails['restaurant']['contact_phone'] ?></td>
                        <td class="fos-user"><i class="fa fa-phone"></i></td>
                        <td><?= $orderDetails['customer_phone'] ?></td>
                    </tr>
                    <tr>
                        <td class="fos-user"><i class="fa fa-map-marker"></i></td>
                        <td><?= $orderDetails['restaurant']['contact_address'] ?></td>
                        <td class="fos-user"><i class="fa fa-map-marker"></i></td>
                        <td><?= $orderDetails['flat_no'].' '.$orderDetails['address']?></td>
                    </tr>
                    </tbody>
                </table>

                <table class="table table-bordered">
                    <thead>
                    <tr class="fos-new-top">
                        <td colspan="4" align="center">ORDER DETAILS</td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="fos-next">
                        <td>PRODUCT NAME</td>
                        <td>QTY</td>
                        <td>PRICE</td>
                        <td>TOTAL PRICE</td>
                    </tr>
                    <?php if(!empty($orderDetails['carts'])) {
                        foreach($orderDetails['carts'] as $key => $value) { ?>

                            <tr>
                                <td>
                                    <?= $value['menu_name'] ?>
                                    <?php
                                    if ($value['subaddons_name'] != '') { ?>
                                        <br><?= $value['subaddons_name'] ?>
                                        <?php
                                    }
                                    ?>

                                </td>
                                <td><?= $value['quantity'] ?></td>
                                <td><?= $siteSettings['site_currency'] ?> <?= number_format($value['menu_price'],2) ?></td>
                                <td><?= $siteSettings['site_currency'] ?> <?= number_format($value['total_price'],2) ?></td>
                            </tr>

                    <?php

                        }
                    } ?>
                    <tr>
                        <td colspan="3" class="fos-tot">Subtotal</td>
                        <td class="fos-tot"><?= $siteSettings['site_currency'] ?> <?= number_format($orderDetails['order_sub_total'],2) ?></td>
                    </tr>
                    <!--<tr>
                        <td colspan="3">Offer(25.00%)</td>
                        <td>$ 25.00</td>
                    </tr>-->
                    <tr>
                        <td colspan="3">Delivery Charge</td>
                        <td><?= $siteSettings['site_currency'] ?> <?= number_format($orderDetails['delivery_charge'],2) ?></td>
                    </tr>

                    <tr>
                        <td colspan="3">Tax(<?= number_format($orderDetails['tax_percentage'],2) ?>%)</td>
                        <td><?= $siteSettings['site_currency'] ?> <?= number_format($orderDetails['tax_amount'],2) ?></td>
                    </tr>
                    <?php if($orderDetails['voucher_code'] != '') { ?>
                        <tr>
                            <td colspan="3">Voucher</td>
                            <td><?= $siteSettings['site_currency'] ?> <?= number_format($orderDetails['voucher_amount'],2) ?></td>
                        </tr>

                    <?php } ?>
                    <tr>
                        <td colspan="3" class="fos-tot">Total</td>
                        <td class="fos-tot"><?= $siteSettings['site_currency'] ?> <?= number_format($orderDetails['order_grand_total'],2) ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>


<?php die(); } ?>
