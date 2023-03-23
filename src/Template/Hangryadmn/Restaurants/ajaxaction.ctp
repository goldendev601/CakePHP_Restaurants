<?php if($action == 'getCity') { ?>
    <?= $this->Form->input('city_id',[
        'type' => 'select',
        'id'   => 'city_id',
        'class' => 'form-control',
        'empty'  => 'select city',
        'onchange' => 'locationList();',
        'options'=> $citylist,
        'label' => false
    ]) ?>
 <?php die(); } ?>

<?php if($action == 'getLocation') { ?>
    <?= $this->Form->input('location_id',[
        'type' => 'select',
        'id'   => 'location_id',
        'class' => 'form-control',
        'empty'  => 'select location',
        'options'=> $locaionlist,
        'label' => false
    ]) ?>
<?php die(); } ?>


<?php if($action == 'showMapAdd') {
    echo $this->GoogleMap->map();
    echo $this->GoogleMap->addMarker(
        "map_canvas",
        1,
        [
            'latitude' => $latitude,
            'longitude' => $longitude
        ],
        [
            'markerIcon' => BASE_URL.'/images/store.png',
            'windowText' => ''
        ]
    );

die(); } ?>


<?php if($action == 'showMapEdit') { ?>

    <input type="hidden" id="restLatitude" value="<?= $latitude ?>">
    <input type="hidden" id="restLongitude" value="<?= $longitude ?>">

<?php

    echo $this->GoogleMap->map();
    echo $this->GoogleMap->addMarker(
        "map_canvas",
        1,
        [
            'latitude' => $latitude,
            'longitude' => $longitude
        ],
        [
            'markerIcon' => BASE_URL.'/images/store.png',
            'windowText' => ''
        ]
    );
    //echo "<pre>";print_r($store);die();

    ?>

    <div class="mapCircle">
        <?php foreach ($restDetails as $key => $val) { ?>
            <?php $id = $key+1 ?>
            <div class="radius_count" id="circle_<?= $key ?>">
                <?= $this->GoogleMap->addCircle(
                    "map_canvas",
                    "Circle".$key,
                    [
                        "latitude"  => $latitude,
                        "longitude" => $longitude
                    ],
                    $val['delivery_miles'] * 1000,
                    [
                        "fillColor"   => $val['radius_color'],
                        "fillOpacity" => 0.3
                    ]
                ); ?>
            </div>
        <?php } ?>
    </div>
<?php
die(); } ?>


<?php if($action == 'getCircle') {

    $addCircle =  $this->GoogleMap->addCircle(
        "map_canvas",
        "Circle".$position,
        [
            "latitude"  => $latitude,
            "longitude" => $longitude
        ],
        $miles,
        [
            "fillColor"   => $color,
            "fillOpacity" => 0.3
        ]
    );
    echo $addCircle.'**'.$color;

die(); } ?>

<?php if($action == 'restaurantStatus' && $field == 'status') { ?>
    <?php if($status == 'active'){?>
        <button class="btn btn-icon-toggle active" type="button" onclick="changeStatus('<?= $id ?>', '0', '<?= $field ?>', 'restaurants/ajaxaction', 'restaurantStatus')">
            <i class="fa fa-check"></i>
        </button>
    <?php }else {?>
        <button class="btn btn-icon-toggle deactive" type="button" onclick="changeStatus('<?= $id ?>', '1', '<?= $field ?>', 'restaurants/ajaxaction', 'restaurantStatus')">
            <i class="fa fa-close"></i>
        </button>
    <?php }?>
    <?php exit();} ?>

<?php if($action == 'Restaurants') { ?>
    <table id="restaurantTable" class="table table-bordered table-hover">
        <thead>
        <tr>
            <th>S.No</th>
            <th>Restaurant Name</th>
            <th>Phone Number</th>
            <th>Address</th>
            <th>Added Date</th>
            <th>Options</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php if(!empty($restaurantLists)) {
            foreach($restaurantLists as $key => $value) { ?>
                <tr>
                    <td><?= $key+1 ?></td>
                    <td><?= $value['restaurant_name'] ?></td>
                    <td><?= $value['restaurant_phone'] ?></td>
                    <td><?= $value['contact_address'] ?></td>
                    <td><?= date('Y-m-d',strtotime($value['created'])) ?></td>
                    <td><span class="label label-success">Approved</span></td>
                    <td id="status_<?php echo $value['id'];?>">
                        <?php if($value['status'] == '0') { ?>
                            <button class="btn btn-icon-toggle deactive"  href="javascript:;" onclick="changeStatus('<?= $value['id'] ?>', '1', 'status', 'restaurants/ajaxaction', 'restaurantStatus')"><i class="fa fa-close"></i>
                            </button>
                        <?php }else { ?>
                            <button class="btn btn-icon-toggle active"  href="javascript:;" onclick="changeStatus('<?= $value['id'] ?>', '0', 'status', 'restaurants/ajaxaction', 'restaurantStatus')"><i class="fa fa-check"></i>
                            </button>
                        <?php } ?>
                    </td>
                    <td>
                        <a href="<?php echo ADMIN_BASE_URL ?>restaurants/edit/<?php echo $value['id'] ?>">
                            <i class="fa fa-pencil"></i>
                        </a>
                        <a id="<?php echo $value['id']; ?>" onclick="return deleteRecord(<?php echo $value['id']; ?>, 'restaurants/deleterestaurant', 'Restaurant', '', 'restaurantTable')" href="javascript:;">
                            <i class="fa fa-trash-o"></i>
                        </a>
                    </td>
                </tr>
                <?php
            }
        }
        ?>
        </tfoot>
    </table>
<?php exit();} ?>
