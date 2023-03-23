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


<?php if($action == 'showMapEdit') {
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
        'options'=> $locationlist,
        'label' => false
    ]) ?>
 <?php die(); } ?>

