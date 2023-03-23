<?php if($action == 'getTiming') {
    if(!empty($array_of_time)) { ?>
        <select id="deliveryTime" class="form-control ui-timepicker-input" name="delivery_time">
            <?php
            foreach ($array_of_time as $key => $value) { ?>
                <option value="<?php echo $value ?>"><?php echo $value ?></option>
                <?php
            } ?>
        </select>
        <?php
    }else { ?>
        <input type="text" readonly class="form-control" id="deliveryTime" value="Closed">@@closed
    <?php }
    ?>

<?php die(); } ?>

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
