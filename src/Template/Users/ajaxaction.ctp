<?php if($action == 'getLocation') { ?>
    <?= $this->Form->input('location_id',[
        'type' => 'select',
        'id'   => 'location_id',
        'class' => 'selectpicker',
        'empty'  => __('Select Your Area/Zipcode'),
        'options'=> $locaionlist,
        'data-live-search' => 'true',
        'label' => false
    ]) ?>

<?php die(); } ?>