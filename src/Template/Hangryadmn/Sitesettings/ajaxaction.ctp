<?php if($action == 'getStatelist') { ?>
    <?= $this->Form->input('site_state',[
         'type'  => 'select',
         'class' => 'form-control',        
         'empty' => 'Select State',
         'options'=> $stateList,
         'onchange'=> 'return cityFillters()',
         'label' => false
    ]) ?>
<?php die(); } ?>


<?php if($action == 'getCitylist') { ?>
    <?= $this->Form->input('site_city',[
        'type'  => 'select',
        'class' => 'form-control',
        'empty' => 'Select City',
        'options'=> $cityList, 
        'onchange' => 'return locationFillters()',       
        'label' => false
    ]) ?>
<?php die(); } ?>


<?php if($action == 'getLocationlist') { ?>
    <?= $this->Form->input('site_zip',[
        'type ' => 'select',        
        'class' => 'form-control',
        'empty' => 'Select Zipcode',
        'options'=> $locationList,         
        'label' => false
    ]) ?>
<?php die(); } ?>