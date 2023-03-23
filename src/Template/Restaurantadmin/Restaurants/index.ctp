<div class="content-wrapper">
    <section class="content-header">
        <h1>  Restaurant Detail </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Edit Restaurant</li>
        </ol>
    </section>
    <section class="content clearfix resttaurants-section">
        <div class="col-xs-12">
            <div class="row">
                <div class="box my-box">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab_1" data-toggle="tab" id="contactInfo">Contact Info</a></li>
                            <li><a href="#tab_2" data-toggle="tab" id="restaurantInfo">Restaurant Info</a></li>
                            <li> <?php if(SEARCHBY == 'Google'){?>
                                    <a href="#tab_3" data-toggle="tab" id="deliveryInfo" onclick="return showMapPolygon();">Delivery Info</a>
                                <?php }else{ ?>
                                    <a href="#tab_3" data-toggle="tab" id="deliveryInfo"
                                       onclick="return deliveryLocation();">Delivery Info</a>
                                <?php } ?>
                            </li>
                            <li><a href="#tab_4" data-toggle="tab" id="orderInfo" onclick="return getContactmail()">Order Info</a></li>
                           <!-- <li><a href="#tab_5" data-toggle="tab" id="commissionInfo">Commission</a></li> -->
                            <li><a href="#tab_6" data-toggle="tab" id="paymentInfo">PaymentMethods</a></li>
                           <!-- <li><a href="#tab_7" data-toggle="tab" id="invoiceInfo">Invoice Period</a></li> -->
                            <li><a href="#tab_8" data-toggle="tab">Meta Tag</a></li>
                            <li><a href="#tab_9" data-toggle="tab">Promotion</a></li>
                            <li><a href="#tab_10" data-toggle="tab">Facebook Ordering</a></li>
                            <li><a href="#tab_12" data-toggle="tab" id="pickupTimeInfo">Pickup</a></li>
                            <li><a href="#tab_14" data-toggle="tab" id="timeZone">Time Zone</a></li>
                            <!-- <li><a href="#tab_15" data-toggle="tab" id="pizzaMenu">Pizza Menu</a></li> -->
                            <!-- <li><a href="#tab_11" data-toggle="tab">Reward Point</a></li> -->
                        </ul>

                        <?php
                            echo $this->Form->create('restaurantAdd', [
                                'id' => 'restaurantAdd',
                                'class' => 'form-horizontal',                           
                                'enctype'  =>'multipart/form-data'
                            ]);
                            echo  $this->Form->input('resId', [
                                'id' => 'resId',
                                'class' => 'form-horizontal',
                                'type' => 'hidden',
                                'value' => !empty($id) ? $id : '',
                                'enctype'  =>'multipart/form-data'
                            ]);
                            echo $this->Form->input('bySearch', [
                                'id' => 'bySearch',
                                'type' => 'hidden',
                                'class' => 'form-horizontal',
                                'value'=> SEARCHBY
                            ]);
                        ?>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1">
                                <div class="box-body">
                                    <!--  Contact Info -->
                                    <div class="form-group clearfix">
                                        <label for="" class="col-md-2 col-sm-3 control-label">Contact Name</label>
                                        <div class="col-md-4 col-sm-5">
                                            <?= $this->Form->input('contact_name',[
                                                'type' => 'text',
                                                'id'   => 'contact_name',
                                                'class' => 'form-control',
                                                'placeholder' => 'Contact Name',
                                                'value' => $restDetails['contact_name'],
                                                'label' => false
                                            ]) ?>
                                            <span class="contactNameErr"></span>
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <label for="" class="col-md-2 col-sm-3 control-label">Contact Phone</label>
                                        <div class="col-md-4 col-sm-5">
                                            <?= $this->Form->input('contact_phone',[
                                                'type' => 'text',
                                                'id'   => 'contact_phone',
                                                'class' => 'form-control',
                                                'placeholder' => 'Contact Phone',
                                                'maxlength' => 11,
                                                'onkeypress' => 'return isNumberKey(event)',
                                                'value' => $restDetails['contact_phone'],
                                                'label' => false
                                            ]) ?>
                                        </div>
                                        <span class="contactPhoneErr"></span>
                                    </div>
                                    <div class="form-group clearfix">
                                        <label for="" class="col-md-2 col-sm-3 control-label">Contact Email</label>
                                        <div class="col-md-4 col-sm-5">
                                            <?= $this->Form->input('contact_email',[
                                                'type' => 'text',
                                                'id'   => 'contact_email',
                                                'class' => 'form-control',
                                                'placeholder' => 'Contact Email',
                                                'value' => $restDetails['contact_email'],
                                                'label' => false
                                            ]) ?>
                                        </div>
                                        <span class="contactemailErr"></span>
                                    </div>
                                    <?php if(SEARCHBY == 'Google'){?>
                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-2 control-label">Address<span class="help">*</span></label>
                                            <div class="col-sm-4">
                                                <?= $this->Form->input('contact_address',[
                                                    'type' => 'text',
                                                    'id'   => 'contact_address',
                                                    'class' => 'form-control',
                                                    'placeholder' => 'Contact Address',
                                                    'value' => $restDetails['contact_address'],
                                                    'label' => false
                                                ]) ?>
                                            </div>
                                            <span class="addressErr"></span>
                                        </div>
                                    <?php } else {?>
                                        <div class="form-group">
                                            <label for="street_address" class="col-sm-2 control-label">Street<span class="help">*</span></label>
                                            <div class="col-sm-4">
                                                <?= $this->Form->input('street_address',[
                                                    'type' => 'text',
                                                    'id'   => 'street_address',
                                                    'value' => $restDetails['street_address'],
                                                    'class' => 'form-control',
                                                    'placeholder' => 'Street Address',
                                                    'label' => false
                                                ]) ?>
                                            </div>
                                            <span class="streetErr"></span>
                                        </div>
                                        <div class="form-group">
                                            <label for="state_id" class="col-sm-2 control-label">State<span class="help">*</span></label>
                                            <div class="col-sm-4">
                                                <?= $this->Form->input('state_id',[
                                                    'type' => 'select',
                                                    'id'   => 'state_id',
                                                    'class' => 'form-control',
                                                    'options'=> $statelist,
                                                    'empty'  => 'select state',
                                                    'value' => $restDetails['state_id'],
                                                    'onchange' => 'cityList();',
                                                    'label' => false
                                                ]) ?>
                                            </div>
                                            <span class="stateErr"></span>
                                        </div>
                                        <div class="form-group">
                                            <label for="city_id" class="col-sm-2 control-label">City<span class="help">*</span></label>
                                            <div class="col-sm-4">
                                                <div id="cityList">
                                                    <?= $this->Form->input('city_id',[
                                                        'type' => 'select',
                                                        'id'   => 'city_id',
                                                        'options'=> $citylist,
                                                        'value' => $restDetails['city_id'],
                                                        'onchange' => 'locationList();',
                                                        'class' => 'form-control',
                                                        'empty'  => 'select city',
                                                        'label' => false
                                                    ]); ?>
                                                </div>
                                            </div>
                                            <span class="cityErr"></span>
                                        </div>
                                        <div class="form-group">
                                            <label for="location_id" class="col-sm-2 control-label">Location<span class="help">*</span></label>
                                            <div class="col-sm-4">
                                                <div id="locationList">
                                                    <?= $this->Form->input('location_id',[
                                                        'type' => 'select',
                                                        'id'   => 'location_id',
                                                        'options'=> $locationlist,
                                                        'value' => $restDetails['location_id'],
                                                        'class' => 'form-control',
                                                        'empty'  => 'select location',
                                                        'label' => false
                                                    ]) ?>
                                                </div>
                                            </div>
                                            <span class="locationErr"></span>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="tab-pane" id="tab_2">
                                <div class="box-body">
                                    <!--  RestaurantInfo -->
                                    <div class="form-group clearfix">
                                        <label for="" class="col-md-2 col-sm-4 control-label">Restaurant Name</label>
                                        <div class="col-md-4 col-sm-6">
                                            <?= $this->Form->input('restaurant_name',[
                                                'type' => 'text',
                                                'id'   => 'restaurant_name',
                                                'class' => 'form-control',
                                                'placeholder' => 'Restaurant Name',
                                                'value' => $restDetails['restaurant_name'],
                                                'label' => false
                                            ]) ?>
                                        </div>
                                        <span class="restnameErr"></span>
                                    </div>
                                    <div class="form-group clearfix">
                                        <label for="" class="col-md-2 col-sm-4 control-label">Restaurant Phone</label>
                                        <div class="col-md-4 col-sm-6">
                                            <?= $this->Form->input('restaurant_phone',[
                                                'type' => 'text',
                                                'id'   => 'restaurant_phone',
                                                'class' => 'form-control',
                                                'placeholder' => 'Restaurant Phone',
                                                'maxlength' => 11,
                                                'onkeypress' => 'return isNumberKey(event)',
                                                'value' => $restDetails['restaurant_phone'],
                                                'label' => false
                                            ]) ?>
                                        </div>
                                        <span class="restphoneErr"></span>
                                    </div>
                                    <div class="form-group clearfix">
                                        <label for="" class="col-md-2 col-sm-4 control-label">Restaurant Logo</label>
                                        <div class="col-md-4 col-sm-6">
                                            <?= $this->Form->input('restaurant_logo',[
                                                'type' => 'file',
                                                'id'   => 'restaurant_logo',
                                                'class' => 'form-control restLogo',
                                                'value' => $restDetails['logo_name'],
                                                'placeholder' => 'Restaurant Logo',
                                                'label' => false
                                            ]) ?>
                                        </div>
                                       <!--  <div class="col-sm-4">
                                            <img src="<?php echo BASE_URL.'webroot/uploads/storeImages/'.$id.'/storeLogo/'.$restDetails['logo_name'] ?>" width="80" height="80" onerror="this.src='<?php echo BASE_URL;?>webroot/images/no_store.jpg'">
                                        </div> -->
                                        <span class="restlogoErr"></span>
                                        <div class="">
                                            <img class="img-responsive img_fields margin-top-10" src="<?php echo BASE_URL.'webroot/uploads/storeLogos/'.$restDetails['logo_name'] ?>" width="100" height="100" onerror="this.src='<?php echo BASE_URL;?>webroot/images/no_store.jpg'">
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <label for="" class="col-md-2 col-sm-4 control-label">Restaurant Timings</label>
                                        <div class="col-md-3 col-sm-3 text-center"> First Open time and close time </div>
                                        <div class="col-md-3 col-sm-3 text-center"> Second Open time and close time </div>
                                    </div> <?php

                                        $mondayStatus = ($restDetails['monday_status'] == 'Close') 
                                            ? 'checked' : '';
                                        $mondayMask = ($restDetails['monday_status'] == 'Close') 
                                            ? 'closed' : '';

                                        $tuesdayStatus = ($restDetails['tuesday_status'] == 'Close') 
                                                ? 'checked' : '';
                                        $tuesdayMask = ($restDetails['tuesday_status'] == 'Close') 
                                        ? 'closed' : '';

                                        $wednesdayStatus = ($restDetails['wednesday_status'] == 'Close') 
                                            ? 'checked' : '';
                                        $wednesdayMask = ($restDetails['wednesday_status'] == 'Close') 
                                            ? 'closed' : '';
                                            
                                        $thursdayStatus = ($restDetails['thursday_status'] == 'Close') 
                                                ? 'checked' : '';
                                        $thursdayMask = ($restDetails['thursday_status'] == 'Close') 
                                        ? 'closed' : '';

                                        $fridayStatus = ($restDetails['friday_status'] == 'Close') 
                                            ? 'checked' : '';
                                        $fridayMask = ($restDetails['friday_status'] == 'Close') 
                                            ? 'closed' : '';
                                            
                                        $saturdayStatus = ($restDetails['saturday_status'] == 'Close') 
                                                ? 'checked' : '';
                                        $saturdayMask = ($restDetails['saturday_status'] == 'Close') 
                                        ? 'closed' : '';

                                        $sundayStatus = ($restDetails['sunday_status'] == 'Close') 
                                            ? 'checked' : '';
                                        $sundayMask = ($restDetails['sunday_status'] == 'Close') 
                                            ? 'closed' : ''; ?>

                                    <div class="form-group clearfix">
                                        <label for="" class="col-md-2 col-sm-4 control-label">Monday</label> 


                                        <div class="col-md-6 col-lg-3 closed_mask <?php echo $mondayMask; ?>">
                                            <div class="sliders_step1">
                                                <div id="monday_first" class="slotTime"></div>
                                            </div>
                                            <div class="timeappend">
                                                <span class="slider-time" id="monday_first_from"><?php echo $restDetails['monday_first_opentime'] ?></span> - <span class="slider-time2" id="monday_first_to"><?php echo $restDetails['monday_first_closetime'] ?></span>
                                            </div>
                                            <?= $this->Form->input('monday_first_opentime',
                                                ['type' => 'hidden',
                                                    'id' => 'monday_first_opentime',
                                                    'value' => $restDetails['monday_first_opentime'],
                                                    'label' => false
                                                ]);
                                            ?>
                                            <?= $this->Form->input('monday_first_closetime',
                                                ['type' => 'hidden',
                                                    'id' => 'monday_first_closetime',
                                                    'value' => $restDetails['monday_first_closetime'],
                                                    'label' => false
                                                ]);
                                            ?>
                                        </div>
                                        <div class="col-md-3 closed_mask <?php echo $mondayMask; ?>">
                                            <div class="sliders_step1">
                                                <div id="monday_second" class="slotTime"></div>
                                            </div>
                                            <div class="timeappend">
                                                <span class="slider-time" id="monday_second_from"><?php echo $restDetails['monday_second_opentime'] ?></span> - <span class="slider-time2" id="monday_second_to"><?php echo $restDetails['monday_second_closetime'] ?></span>
                                            </div>
                                            <?= $this->Form->input('monday_second_opentime',[
                                                'type' => 'hidden',
                                                'id' => 'monday_second_opentime',
                                                'value' => $restDetails['monday_second_opentime'],
                                                'label' => false ]);
                                            ?>
                                            <?= $this->Form->input('monday_second_closetime',[
                                                'type' => 'hidden',
                                                'id' => 'monday_second_closetime',
                                                'value' => $restDetails['monday_second_closetime'],
                                                'label' => false
                                            ]);
                                            ?>

                                        </div>

                                        <div class="col-md-2 col-sm-2 text-center">
                                            <?php 
                                            echo $this->Form->input('Close',[
                                                'class'=>'',
                                                'hiddenField'=>false,
                                                'div' => false,
                                                'checked' => $mondayStatus,
                                                'type' => 'checkbox',
                                                'id' => 'monday_status',
                                                'onchange' => 'closemask(this)',
                                                'name' => 'monday_status',
                                                'value'=> 'Close'
                                            ]);
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <label for="" class="col-md-2 col-sm-4 control-label">Tuesday</label>
                                        
                                        <div class="col-md-6 col-lg-3 closed_mask <?php echo $tuesdayMask; ?>">
                                            <div class="sliders_step1">
                                                <div id="tuesday_first" class="slotTime"></div>
                                            </div>
                                            <div class="timeappend">
                                                <span class="slider-time" id="tuesday_first_from"><?php echo $restDetails['tuesday_first_opentime'] ?></span> - <span class="slider-time2" id="tuesday_first_to"><?php echo $restDetails['tuesday_first_closetime'] ?></span>
                                            </div>
                                            <?= $this->Form->input('tuesday_first_opentime',
                                                array('type' => 'hidden',
                                                    'id' => 'tuesday_first_opentime',
                                                    'value' => $restDetails['tuesday_first_opentime'],
                                                    'label' => false));
                                            ?>
                                            <?= $this->Form->input('tuesday_first_closetime',
                                                array('type' => 'hidden',
                                                    'id' => 'tuesday_first_closetime',
                                                    'value' => $restDetails['tuesday_first_closetime'],
                                                    'label' => false));
                                            ?>
                                        </div>
                                        <div class="col-md-3 closed_mask <?php echo $tuesdayMask; ?>">
                                            <div class="sliders_step1">
                                                <div id="tuesday_second" class="slotTime"></div>
                                            </div>
                                            <div class="timeappend">
                                                <span class="slider-time" id="tuesday_second_from"><?php echo $restDetails['tuesday_second_opentime'] ?></span> - <span class="slider-time2" id="tuesday_second_to"><?php echo $restDetails['tuesday_second_closetime'] ?></span>
                                            </div>
                                            <?= $this->Form->input('tuesday_second_opentime',
                                                array('type' => 'hidden',
                                                    'id' => 'tuesday_second_opentime',
                                                    'value' => $restDetails['tuesday_second_opentime'],
                                                    'label' => false));
                                            ?>
                                            <?= $this->Form->input('tuesday_second_closetime',
                                                array('type' => 'hidden',
                                                    'id' => 'tuesday_second_closetime',
                                                    'value' => $restDetails['tuesday_second_closetime'],
                                                    'label' => false));
                                            ?>
                                        </div>
                                        <div class="col-md-2  col-sm-2 text-center"> <?php

                                            echo $this->Form->input('Close',[
                                                'class'=>'' ,
                                                'hiddenField'=>false,
                                                'div' => false,
                                                'type' => 'checkbox',
                                                'checked' => $tuesdayStatus,
                                                'id' => 'tuesday_status',
                                                'onchange' => 'closemask(this)',
                                                'name' => 'tuesday_status',
                                                'value'=> 'Close'
                                            ]); ?>
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <label for="" class="col-md-2 col-sm-4 control-label">Wednesday</label>
                                        <div class="col-md-6 col-lg-3 closed_mask <?php echo $wednesdayMask; ?>">
                                            <div class="sliders_step1">
                                                <div id="wednesday_first" class="slotTime"></div>
                                            </div>
                                            <div class="timeappend">
                                                <span class="slider-time" id="wednesday_first_from"><?php echo $restDetails['wednesday_first_opentime'] ?></span> - <span class="slider-time2" id="wednesday_first_to"><?php echo $restDetails['wednesday_first_closetime'] ?></span>
                                            </div>
                                            <?= $this->Form->input('wednesday_first_opentime',
                                                array('type' => 'hidden',
                                                    'id' => 'wednesday_first_opentime',
                                                    'value' => $restDetails['wednesday_first_opentime'],
                                                    'label' => false));
                                            ?>
                                            <?= $this->Form->input('wednesday_first_closetime',
                                                array('type' => 'hidden',
                                                    'id' => 'wednesday_first_closetime',
                                                    'value' => $restDetails['wednesday_first_closetime'],
                                                    'label' => false));
                                            ?>
                                        </div>
                                        <div class="col-md-3 closed_mask <?php echo $wednesdayMask; ?>">
                                            <div class="sliders_step1">
                                                <div id="wednesday_second" class="slotTime"></div>
                                            </div>
                                            <div class="timeappend">
                                                <span class="slider-time" id="wednesday_second_from"><?php echo $restDetails['wednesday_second_opentime'] ?></span> - <span class="slider-time2" id="wednesday_second_to"><?php echo $restDetails['wednesday_second_closetime'] ?></span>
                                            </div>
                                            <?= $this->Form->input('wednesday_second_opentime',
                                                array('type' => 'hidden',
                                                    'id' => 'wednesday_second_opentime',
                                                    'value' => $restDetails['wednesday_second_opentime'],
                                                    'label' => false));
                                            ?>
                                            <?= $this->Form->input('wednesday_second_closetime',
                                                array('type' => 'hidden',
                                                    'id' => 'wednesday_second_closetime',
                                                    'value' => $restDetails['wednesday_second_closetime'],
                                                    'label' => false));
                                            ?>
                                        </div>
                                        <div class="col-md-2 col-sm-2 text-center"> <?php

                                           echo $this->Form->input('Close',[
                                                'class'=>'' ,
                                                'hiddenField'=>false,
                                                'div' => false,
                                                'checked' => $wednesdayStatus,
                                                'type' => 'checkbox',
                                                'id' => 'wednesday_status',
                                                'onchange' => 'closemask(this)',
                                                'name' => 'wednesday_status',
                                                'value'=> 'Close'
                                            ]); ?>
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <label for="" class="col-md-2 col-sm-4 control-label">Thursday</label>
                                        <div class="col-md-6 col-lg-3 closed_mask <?php echo $thursdayMask; ?>">
                                            <div class="sliders_step1">
                                                <div id="thursday_first" class="slotTime"></div>
                                            </div>
                                            <div class="timeappend">
                                                <span class="slider-time" id="thursday_first_from"><?php echo $restDetails['thursday_first_opentime'] ?></span> - <span class="slider-time2" id="thursday_first_to"><?php echo $restDetails['thursday_first_closetime'] ?></span>
                                            </div>
                                            <?= $this->Form->input('thursday_first_opentime',
                                                array('type' => 'hidden',
                                                    'id' => 'thursday_first_opentime',
                                                    'value' => $restDetails['thursday_first_opentime'],
                                                    'label' => false));
                                            ?>
                                            <?= $this->Form->input('thursday_first_closetime',
                                                array('type' => 'hidden',
                                                    'id' => 'thursday_first_closetime',
                                                    'value' => $restDetails['thursday_first_closetime'],
                                                    'label' => false));
                                            ?>
                                        </div>
                                        <div class="col-md-3 closed_mask <?php echo $thursdayMask; ?>">
                                            <div class="sliders_step1">
                                                <div id="thursday_second" class="slotTime"></div>
                                            </div>
                                            <div class="timeappend">
                                                <span class="slider-time" id="thursday_second_from"><?php echo $restDetails['thursday_second_opentime'] ?></span> - <span class="slider-time2" id="thursday_second_to"><?php echo $restDetails['thursday_second_closetime'] ?></span>
                                            </div>
                                            <?= $this->Form->input('thursday_second_opentime',
                                                array('type' => 'hidden',
                                                    'id' => 'thursday_second_opentime',
                                                    'value' => $restDetails['thursday_second_opentime'],
                                                    'label' => false));
                                            ?>
                                            <?= $this->Form->input('thursday_second_closetime',
                                                array('type' => 'hidden',
                                                    'id' => 'thursday_second_closetime',
                                                    'value' => $restDetails['thursday_second_closetime'],
                                                    'label' => false));
                                            ?>
                                        </div>
                                        <div class="col-md-2 col-sm-2 text-center">
                                            <?php
                                            echo $this->Form->input('Close',[
                                                    'class'=>'' ,
                                                    'hiddenField'=>false,
                                                    'div' => false,
                                                    'type' => 'checkbox',
                                                    'id' => 'thursday_status',
                                                    'checked' => $thursdayStatus,
                                                    'onchange' => 'closemask(this)',
                                                    'name' => 'thursday_status',
                                                    'value'=> 'Close'
                                                ]
                                            ); ?>
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <label for="" class="col-md-2 col-sm-4 control-label">Friday</label>
                                        <div class="col-md-6 col-lg-3 closed_mask <?php echo $fridayMask; ?> ">
                                            <div class="sliders_step1">
                                                <div id="friday_first" class="slotTime"></div>
                                            </div>
                                            <div class="timeappend">
                                                <span class="slider-time" id="friday_first_from"><?php echo $restDetails['friday_first_opentime'] ?></span> - <span class="slider-time2" id="friday_first_to"><?php echo $restDetails['friday_first_closetime'] ?></span>
                                            </div>
                                            <?= $this->Form->input('friday_first_opentime',
                                                array('type' => 'hidden',
                                                    'id' => 'friday_first_opentime',
                                                    'value' => $restDetails['friday_first_opentime'],
                                                    'label' => false));
                                            ?>
                                            <?= $this->Form->input('friday_first_closetime',
                                                array('type' => 'hidden',
                                                    'id' => 'friday_first_closetime',
                                                    'value' => $restDetails['friday_first_closetime'],
                                                    'label' => false));
                                            ?>
                                        </div>
                                        <div class="col-md-3 closed_mask <?php echo $fridayMask; ?>">
                                            <div class="sliders_step1">
                                                <div id="friday_second" class="slotTime"></div>
                                            </div>
                                            <div class="timeappend">
                                                <span class="slider-time" id="friday_second_from"><?php echo $restDetails['friday_second_opentime'] ?></span> - <span class="slider-time2" id="friday_second_to"><?php echo $restDetails['friday_second_closetime'] ?></span>
                                            </div>
                                            <?= $this->Form->input('friday_second_opentime',
                                                array('type' => 'hidden',
                                                    'id' => 'friday_second_opentime',
                                                    'value' => $restDetails['friday_second_opentime'],
                                                    'label' => false));
                                            ?>
                                            <?= $this->Form->input('friday_second_closetime',
                                                array('type' => 'hidden',
                                                    'id' => 'friday_second_closetime',
                                                    'value' => $restDetails['friday_second_closetime'],
                                                    'label' => false));
                                            ?>
                                        </div>
                                        <div class="col-md-2  col-sm-2 text-center">
                                            <?php
                                            echo $this->Form->input('Close',[
                                                'class'=>'' ,
                                                'hiddenField'=>false,
                                                'div' => false,
                                                'type' => 'checkbox',
                                                'id' => 'friday_status',
                                                'checked' => $fridayStatus,
                                                'onchange' => 'closemask(this)',
                                                'name' => 'friday_status',
                                                'value'=> 'Close'
                                            ]); ?>
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <label for="" class="col-md-2 col-sm-4 control-label">Saturday</label>
                                        <div class="col-md-6 col-lg-3 closed_mask <?php echo $saturdayMask; ?> ">
                                            <div class="sliders_step1">
                                                <div id="saturday_first" class="slotTime"></div>
                                            </div>
                                            <div class="timeappend">
                                                <span class="slider-time" id="saturday_first_from"><?php echo $restDetails['saturday_first_opentime'] ?></span> - <span class="slider-time2" id="saturday_first_to"><?php echo $restDetails['saturday_first_closetime'] ?></span>
                                            </div>
                                            <?= $this->Form->input('saturday_first_opentime',
                                                array('type' => 'hidden',
                                                    'id' => 'saturday_first_opentime',
                                                    'value' => $restDetails['saturday_first_opentime'],
                                                    'label' => false));
                                            ?>
                                            <?= $this->Form->input('saturday_first_closetime',
                                                array('type' => 'hidden',
                                                    'id' => 'saturday_first_closetime',
                                                    'value' => $restDetails['saturday_first_closetime'],
                                                    'label' => false));
                                            ?>
                                        </div>
                                        <div class="col-md-3 closed_mask <?php echo $saturdayMask; ?>">
                                            <div class="sliders_step1">
                                                <div id="saturday_second" class="slotTime"></div>
                                            </div>
                                            <div class="timeappend">
                                                <span class="slider-time" id="saturday_second_from"><?php echo $restDetails['saturday_second_opentime'] ?></span> - <span class="slider-time2" id="saturday_second_to"><?php echo $restDetails['saturday_second_closetime'] ?></span>
                                            </div>
                                            <?= $this->Form->input('saturday_second_opentime',
                                                array('type' => 'hidden',
                                                    'id' => 'saturday_second_opentime',
                                                    'value' => $restDetails['saturday_second_opentime'],
                                                    'label' => false));
                                            ?>
                                            <?= $this->Form->input('saturday_second_closetime',
                                                array('type' => 'hidden',
                                                    'id' => 'saturday_second_closetime',
                                                    'value' => $restDetails['saturday_second_closetime'],
                                                    'label' => false));
                                            ?>
                                        </div>
                                        <div class="col-md-2  col-sm-2 text-center">
                                            <?php
                                            echo $this->Form->input('Close',[
                                                'class'=>'' ,
                                                'hiddenField'=>false,
                                                'div' => false,
                                                'type' => 'checkbox',
                                                'id' => 'saturday_status',
                                                'checked' => $saturdayStatus,
                                                'onchange' => 'closemask(this)',
                                                'name' => 'saturday_status',
                                                'value'=> 'Close'
                                            ]); ?>
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <label for="" class="col-md-2 col-sm-4 control-label">Sunday</label>
                                        <div class="col-md-6 col-lg-3 closed_mask <?php echo $sundayMask; ?> ">
                                            <div class="sliders_step1">
                                                <div id="sunday_first" class="slotTime"></div>
                                            </div>
                                            <div class="timeappend">
                                                <span class="slider-time" id="sunday_first_from"><?php echo $restDetails['sunday_first_opentime'] ?></span> - <span class="slider-time2" id="sunday_first_to"><?php echo $restDetails['sunday_first_closetime'] ?></span>
                                            </div>
                                            <?= $this->Form->input('sunday_first_opentime',
                                                array('type' => 'hidden',
                                                    'id' => 'sunday_first_opentime',
                                                    'value' => $restDetails['sunday_first_opentime'],
                                                    'label' => false));
                                            ?>
                                            <?= $this->Form->input('sunday_first_closetime',
                                                array('type' => 'hidden',
                                                    'id' => 'sunday_first_closetime',
                                                    'value' => $restDetails['sunday_first_closetime'],
                                                    'label' => false));
                                            ?>
                                        </div>
                                        <div class="col-md-3 closed_mask <?php echo $sundayMask; ?> ">
                                            <div class="sliders_step1">
                                                <div id="sunday_second" class="slotTime"></div>
                                            </div>
                                            <div class="timeappend">
                                                <span class="slider-time" id="sunday_second_from"><?php echo $restDetails['sunday_second_opentime'] ?></span> - <span class="slider-time2" id="sunday_second_to"><?php echo $restDetails['sunday_second_closetime'] ?></span>
                                            </div>
                                            <?= $this->Form->input('sunday_second_opentime',
                                                array('type' => 'hidden',
                                                    'id' => 'sunday_second_opentime',
                                                    'value' => $restDetails['sunday_second_opentime'],
                                                    'label' => false));
                                            ?>
                                            <?= $this->Form->input('sunday_second_closetime',
                                                array('type' => 'hidden',
                                                    'id' => 'sunday_second_closetime',
                                                    'value' => $restDetails['sunday_second_closetime'],
                                                    'label' => false));
                                            ?>
                                        </div>
                                        <div class="col-md-2  col-sm-2 text-center">
                                            <?php
                                            echo $this->Form->input('Close',[
                                                'class'=>'' ,
                                                'hiddenField'=>false,
                                                'div' => false,
                                                'type' => 'checkbox',
                                                'id' => 'sunday_status',
                                                'checked' => $sundayStatus,
                                                'onchange' => 'closemask(this)',
                                                'name' => 'sunday_status',
                                                'value'=> 'Close'
                                            ]);?>
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <label for="" class="col-md-2 col-sm-4 control-label">Tax</label>
                                        <div class="col-md-4 col-sm-6 no-padding-right">
                                            <div class="input-group">
                                                <div class="input text required">
                                                    <?= $this->Form->input('restaurant_tax',[
                                                        'type' => 'text',
                                                        'id'   => 'restaurant_tax',
                                                        'class' => 'form-control',
                                                        'placeholder' => 'Restaurant Tax',
                                                        'value' => $restDetails['restaurant_tax'],
                                                        'label' => false
                                                    ]) ?>
                                                </div>
                                                <span class="taxErr"></span>
                                                <div class="input-group-addon">%</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <label for="" class="col-md-2 col-sm-4 control-label">Cuisine</label>
                                        <div class="col-md-4 col-sm-6 no-padding-right">
                                            <?= $this->Form->input('restaurant_cuisine',[
                                                'type' => 'select',
                                                'multiple' => 'multiple',
                                                'id'   => 'restaurant_cuisine',
                                                'class' => 'form-control',
                                                'options' => $cuisinesList,
                                                'value' => $selectedCuisine,
                                                'label' => false
                                            ]) ?>
                                        </div>
                                        <span class="cuisineErr"></span>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Visibility</label>
                                        <div class="col-sm-4">
                                            <label class="radio-inline no-padding-left">
                                                <input type="radio" name="restaurant_visibility" class="minimal" <?= ($restDetails['restaurant_visibility'] == 'FOS') ? 'checked' : '' ?> value="FOS">
                                                FOS
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="restaurant_visibility" class="minimal" value="external" <?= ($restDetails['restaurant_visibility'] == 'external') ? 'checked' : '' ?>>
                                                External
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="restaurant_visibility" class="minimal" value="both" <?= ($restDetails['restaurant_visibility'] == 'both') ? 'checked' : '' ?>>
                                                Both
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <label for="" class="col-md-2 col-sm-4 control-label">Do You Wanna dispatch</label>
                                        <div class="col-md-4 col-sm-6">
                                            <label class="radio-inline no-padding-left">
                                                <input type="radio" name="restaurant_dispatch" class="minimal" <?= ($restDetails['restaurant_dispatch'] == 'Yes') ? 'checked' : '' ?> value="Yes">
                                                Yes
                                            </label>
                                            <label class="radio-inline no-padding-left">
                                                <input type="radio" name="restaurant_dispatch" class="minimal" value="No" <?= ($restDetails['restaurant_dispatch'] == 'No') ? 'checked' : '' ?>>
                                                No
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <label for="" class="col-md-2 col-sm-4 control-label">Pickup</label>
                                        <div class="col-md-4 col-sm-6">
                                            <label class="radio-inline no-padding-left">
                                                <input type="radio" name="restaurant_pickup" class="minimal" <?= ($restDetails['restaurant_pickup'] == 'Yes') ? 'checked' : '' ?> value="Yes">
                                                Yes
                                            </label>
                                            <label class="radio-inline no-padding-left">
                                                <input type="radio" name="restaurant_pickup" class="minimal" <?= ($restDetails['restaurant_pickup'] == 'No') ? 'checked' : '' ?> value="No">
                                                No
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <label for="" class="col-md-2 col-sm-4 control-label">Delivery</label>
                                        <div class="col-md-4 col-sm-6">
                                            <label class="radio-inline no-padding-left">
                                                <input type="radio" name="restaurant_delivery" class="minimal" <?= ($restDetails['restaurant_delivery'] == 'Yes') ? 'checked' : '' ?> value="Yes">Yes
                                            </label>
                                            <label class="radio-inline no-padding-left">
                                                <input type="radio" name="restaurant_delivery" class="minimal" value="No" <?= ($restDetails['restaurant_delivery'] == 'No') ? 'checked' : '' ?>>
                                                No
                                            </label>
                                        </div>

                                    </div>
                                    <div class="form-group clearfix">
                                        <label for="" class="col-md-2 col-sm-4 control-label">Book a table</label>
                                        <div class="col-md-4 col-sm-6">
                                            <label class="radio-inline no-padding-left">
                                                <input type="radio" name="restaurant_booktable" class="minimal" value="Yes" <?= ($restDetails['restaurant_booktable'] == 'Yes') ? 'checked' : '' ?> >
                                                Yes
                                            </label>
                                            <label class="radio-inline no-padding-left">
                                                <input type="radio" name="restaurant_booktable" class="minimal" value="No" <?= ($restDetails['restaurant_booktable'] == 'No') ? 'checked' : '' ?> >
                                                No
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <label class="col-md-2 col-sm-4  control-label"> Restaurant About </label>
                                        <div class="col-md-4 col-sm-6">
                                            <?= $this->Form->input('restaurant_about',[
                                                'type' => 'textarea',
                                                'id'   => 'restaurant_about',
                                                'class' => 'form-control',
                                                'value' => $restDetails['restaurant_about'],
                                                'label' => false
                                            ]) ?>
                                        </div>
                                        <span class="restaboutErr"></span>
                                    </div>
                                    <div class="form-group clearfix">
                                        <label class="col-md-2 col-sm-4 control-label">Username </label>
                                        <div class="col-md-4 col-sm-6">
                                            <?= $this->Form->input('username',[
                                                'type' => 'text',
                                                'id'   => 'username',
                                                'class' => 'form-control',
                                                'value' => $restDetails['username'],
                                                'label' => false
                                            ]) ?>
                                        </div>
                                        <span class="usernameErr"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_3">
                                <div class="box-body">
                                    <div class="row contain">
                                        <div class="col-md-offset-3 col-md-6 col-lg-4">
                                            <label id="deliveryCityErr" class="error"></label>
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <label class="col-md-3 col-sm-4  control-label">Estimation Time </label>
                                        <div class="col-md-4 col-sm-6">
                                            <?= $this->Form->input('estimate_time',[
                                                'type' => 'text',
                                                'id'   => 'estimate_time',
                                                'class' => 'form-control',
                                                'placeholder' => 'Delivery Estimated Time',
                                                'value' => $restDetails['estimate_time'],
                                                'label' => false
                                            ]) ?>
                                        </div>
                                        <span class="estimateErr"></span>
                                    </div>
                                    <!--Google-->
                                    <?php if(SEARCHBY == 'Google') {?>
                                        <div id="byGoogle">
                                            <div class="form-group clearfix">
                                                <label class="col-md-3 col-sm-4  control-label">Minimum Order </label>
                                                <div class="col-md-4 col-sm-6">
                                                    <?= $this->Form->input('minimum_order',[
                                                        'type' => 'text',
                                                        'id'   => 'minimum_order',
                                                        'class' => 'form-control',
                                                        'placeholder' => 'Minimum Order',
                                                        'value' => $restDetails['minimum_order'],
                                                        'label' => false
                                                    ]) ?>
                                                </div>
                                                <span class="minimumErr"></span>
                                            </div>
                                            <div class="form-group clearfix">
                                                <label class="col-md-3 col-sm-4  control-label">Free Delivery </label>
                                                <div class="col-md-4 col-sm-6">
                                                    <?= $this->Form->input('free_delivery',[
                                                        'type' => 'text',
                                                        'id'   => 'free_delivery',
                                                        'class' => 'form-control',
                                                        'placeholder' => 'Free Delivery',
                                                        'value' => $restDetails['free_delivery'],
                                                        'label' => false
                                                    ]) ?>
                                                </div>
                                                <span class="freedeliveryErr"></span>
                                            </div>
                                            <div class="form-group clearfix">
                                                <label for="" class="col-md-3 col-sm-4 control-label">Map Mode</label>
                                                <div class="col-md-4 col-sm-6">
                                                    <div class="col-sm-4">
                                                        <label class="radio-inline no-padding-left">
                                                            <input type="radio" name="map_mode" class="" <?= ($restDetails['map_mode'] == 'Circle') ? 'checked' : '' ?> value="Circle" onclick="return showMapPolygon();">
                                                            Circle
                                                        </label>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <label class="radio-inline no-padding-left">
                                                            <input type="radio" name="map_mode" class="" <?= ($restDetails['map_mode'] == 'Polygon') ? 'checked' : '' ?> value="Polygon" onclick="return showMapPolygon();">
                                                            Polygon
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group clearfix">
                                                <div class="col-sm-12 col-xs-12 col-md-3">
                                                    <div class="notifytext select-edit">
                                                        <i class="fa fa-bell-o"></i>
                                                        Select & click to edit
                                                    </div>
                                                    <div id="circleMapDiv" class="col-xs-12 no-padding">
                                                        <h1 class="mapheading">Start with a circle</h1>
                                                        <?php if(!empty($restDetails['delivery_settings'])) {
                                                            foreach($restDetails['delivery_settings'] as $key => $value) { ?>
                                                                <div class="radius_settings clearfix margin-b-10 text-right" id="radius_settings_<?= $key ?>">
                                                                    <div class="input-group margin-b-10">
                                                                        <?= $this->Form->input('DeliverySettings[' . $key . '][delivery_miles]', [
                                                                            'type' => 'text',
                                                                            'name' => 'DeliverySettings[' . $key . '][delivery_miles]',
                                                                            'class' => 'form-control settingcount',
                                                                            'placeholder' => 'Enter Miles (radius)',
                                                                            'label' => false,
                                                                            'id' => $key,
                                                                            'value' => $value['delivery_miles']
                                                                        ]) ?>
                                                                        <div id="rmve_<?= $key ?>" class="input-group-addon" style="cursor: pointer"
                                                                             onclick="return generateRadius(<?= $key ?>)"><i
                                                                                    class="fa fa-check"></i></div>
                                                                    </div>
                                                                    <div class="setColor" id="set_color_<?= $key ?>">
                                                                        <div class="margin-t-25 margin-b-10 col-xs-12 no-padding"
                                                                             id="delivery_charge_<?= $key ?>">
                           <span class="bgorange" style="background-color:<?= $value['radius_color'] ?>"><i
                                       class="fa fa-map-marker"></i></span>
                                                                            <a href="javascript:;" class="clsbtn close-btn pull-right"
                                                                               onclick="return removeCircle(<?= $key ?>)">
                                                                                <i class="fa fa-close"></i>
                                                                            </a>
                                                                            <div class="col-sm-7 no-padding margin-left-20">
                                                                                <?= $this->Form->input('DeliverySettings[' . $key . '][delivery_charge]', [
                                                                                    'type' => 'text',
                                                                                    'name' => 'DeliverySettings[' . $key . '][delivery_charge]',
                                                                                    'placeholder' => 'Price',
                                                                                    'class' => 'form-control deliver_Charge',
                                                                                    'id' => 'deliveryChargeId_' . $key,
                                                                                    'label' => false,
                                                                                    'value' => $value['delivery_charge']
                                                                                ]) ?>
                                                                                <?= $this->Form->input('DeliverySettings[' . $key . '][radius_color]', [
                                                                                    'type' => 'hidden',
                                                                                    'name' => 'DeliverySettings[' . $key . '][radius_color]',
                                                                                    'templates' => [
                                                                                        'inputContainer' => '{{content}}'
                                                                                        , 'div' => false
                                                                                    ],
                                                                                    'value' => $value['radius_color']
                                                                                ]) ?>
                                                                                <?= $this->Form->input('DeliverySettings[' . $key . '][map_type]', [
                                                                                    'type' => 'hidden',
                                                                                    'value' => $value['map_type'],
                                                                                    'name' => 'DeliverySettings[' . $key . '][map_type]'
                                                                                ]) ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php }
                                                        }else { ?>
                                                            <div class="input-group radius_settings"  id="radius_settings_0">
                                                                <div class="input text">
                                                                    <?= $this->Form->input('DeliverySettings[0][delivery_miles]', [
                                                                        'type' => 'text',
                                                                        'class' => 'form-control',
                                                                        'id' => '0',
                                                                        'placeholder' => 'Enter Miles (radius)',
                                                                        'name' => 'DeliverySettings[0][delivery_miles]',
                                                                        'label'=>false,
                                                                    ]) ?>
                                                                    <div class="input-group-addon" onclick="return generateRadius(0)" style="cursor: pointer"><i class="fa fa-check"></i> </div>
                                                                </div>
                                                                <div class="setColor" id="set_color_0"></div>
                                                            </div>
                                                        <?php } ?>
                                                        <div id="textAppend"></div>
                                                        <a href="javascript:;" class="addlocation" onclick="radiusTextAppend();">Add More</a>
                                                    </div>
                                                    <div id="deliveryCharge">
                                                        <?php if(!empty($restDetails['areamaps'])) {
                                                            foreach($restDetails['areamaps'] as $key => $value) { ?>
                                                                <div class="radius_setting margin-b-10" id="deliveryCharge_<?php echo $key ?>">
                                                                    <?= $this->Form->input('AreaMaps[0][delivery_charge]', [
                                                                        'type' => 'text',
                                                                        'id' => $key,
                                                                        'class' => 'form-control margin-b-10',
                                                                        'placeholder' => 'Enter Delivery Charge',
                                                                        'label'=>false,
                                                                        'name' => 'AreaMaps['.$key.'][delivery_charge]',
                                                                        'value' => $value['service_delivery_charge'],
                                                                        'id' => 'delCharge'.$key
                                                                    ]) ?>
                                                                    <input id="area_<?= $key ?>_coords" type="hidden" name="coords[<?= $key ?>]area" value='<?= $value['mapcoords'] ?>'>
                                                                    <input id="area_<?= $key ?>_record" type="hidden" name="record[<?= $key ?>]area" value='<?= $value['mappath'] ?>'>
                                                                    <input class="mapidCount" id="area_<?= $key ?>_mapid" type="hidden" name="mapid[<?= $key ?>]area" value='<?= $key ?>'>
                                                                    <div class="chargeError"></div>
                                                                </div>
                                                                <div class="col-sm-2 DeleteMap" id="DeleteMap<?= $key ?>"></div>
                                                                <?php
                                                            }
                                                        }else { ?>
                                                            <div class="radius_setting margin-b-10" id="deliveryCharge_0" style="display: none">
                                                                <?= $this->Form->input('AreaMaps[0][delivery_charge]', [
                                                                    'type' => 'text',
                                                                    'id' => 0,
                                                                    'class' => 'form-control margin-b-10',
                                                                    'placeholder' => 'Enter Delivery Charge',
                                                                    'label'=>false,
                                                                    'name' => 'AreaMaps[0][delivery_charge]',
                                                                    'id' => 'delCharge0'
                                                                ]) ?>
                                                                <input id="area_0_coords" type="hidden" name="coords[0]area"/>
                                                                <input id="area_0_record" type="hidden" name="record[0]area"/>
                                                                <input id="area_0_mapid" type="hidden" name="mapid[0]area"/>
                                                                <div class="chargeError"></div>
                                                            </div>
                                                        <?php } ?>
                                                        <div id="chargeAppend"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-xs-12 col-md-9">
                                                    <!--<div class="mapCircle"></div>-->
                                                    <div id="googleMapShow">
                                                    </div>
                                                    <div id="polygon-map" class="col-sm-12" style="display: none">
                                                        <div id="map-view" style="height: 600px; width: 100%;">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--AreaZipcode-->
                                    <?php } else {?>
                                        <div id="AreaZipcode">
                                            <div class="form-group">
                                                <div class="col-sm-9 col-sm-offset-3">
                                                    <div class="row">
                                                        <div class="col-sm-2">
                                                            <div class="labelname">City</div>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <div class="labelname">
                                                                <?php
                                                                $searchBy = ( SEARCHBY== 'zip') ? 'Postcode' : 'Areaname';
                                                                echo $searchBy; ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <div class="labelname">Minimum Order</div>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <div class="labelname">Delivery Charge</div>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <a onclick="appendDeliveryLocation();" class="btn btn-success">Add</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php if (!empty($deliveryLocation)) {
                                            foreach ($deliveryLocation as $delKey => $delVal) { ?>
                                                <div class="form-group" id="removeLocation_<?php echo $delKey; ?>">
                                                    <div class="col-sm-9 col-sm-offset-3">
                                                        <div class="row">
                                                            <div class="col-sm-2"> <?php
                                                                echo $this->Form->input('DeliveryLocation.city_name',[
                                                                    'class'=>'form-control',
                                                                    'type' => 'text',
                                                                    'placeholder'=>'City',
                                                                    'id'=>'city_name_'.$delKey,
                                                                    'name' => 'data[DeliveryLocation]['.$delKey.'][city_name]',
                                                                    'onkeyup' => 'getCityName(this.id);',
                                                                    'value' => $delVal['city']['city_name'],
                                                                    'label'=>false
                                                                ]); ?>
                                                            </div>
                                                            <div class="col-sm-2"><?php
                                                                echo $this->Form->input('DeliveryLocation.location_name',[
                                                                    'class'=>'form-control deliveryLocationName',
                                                                    'placeholder'=>'Location',
                                                                    'type' => 'text',
                                                                    'id' => 'location_name_'.$delKey,
                                                                    'placeholder' => $searchBy,
                                                                    'name' => 'data[DeliveryLocation]['.$delKey.'][location_name]',
                                                                    'onkeyup' => 'getLocationName(this.id, 0);',
                                                                    'value' => (SEARCHBY == 'zip') ? $delVal['location']['zip_code'] : $delVal['location']['area_name'],
                                                                    'label'=>false
                                                                ]);?>
                                                            </div>
                                                            <div class="col-sm-2"><?php
                                                                echo $this->Form->input('DeliveryLocation.minimum_order',[
                                                                    'class'=>'form-control',
                                                                    'placeholder'=>'Minimum Order',
                                                                    'type' => 'text',
                                                                    'id' => 'minimum_order_'.$delKey,
                                                                    'name' => 'data[DeliveryLocation]['.$delKey.'][minimum_order]',
                                                                    'value' => $delVal['minimum_order'],
                                                                    'label'=>false
                                                                ]); ?>
                                                            </div>
                                                            <div class="col-sm-2"> <?php
                                                                echo $this->Form->input('DeliveryLocation.delivery_charge',[
                                                                    'class'=>'form-control',
                                                                    'placeholder'=>'Delivery Charge',
                                                                    'type' => 'text',
                                                                    'id' => 'delivery_charge_'.$delKey,
                                                                    'name' => 'data[DeliveryLocation]['.$delKey.'][delivery_charge]',
                                                                    'value' => $delVal['minimum_order'],
                                                                    'label'=>false
                                                                ]);?>
                                                            </div>
                                                            <div class="col-sm-2">
                                                                <a onclick="removeLocation(<?php echo $delKey; ?>);"
                                                                   class="btn btn-danger">X</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                                <script> var j = '<?php echo $delKey+1; ?>';</script>
                                            <?php  } else { ?>
                                                <div class="form-group" id="removeLocation_0">
                                                    <div class="col-sm-9 col-sm-offset-3">
                                                        <div class="row">
                                                            <div class="col-sm-2"> <?php
                                                                echo $this->Form->input('DeliveryLocation.city_name',[
                                                                    'class'=>'form-control',
                                                                    'type' => 'text',
                                                                    'placeholder'=>'City',
                                                                    'id'=>'city_name_0',
                                                                    'name' => 'data[DeliveryLocation][0][city_name]',
                                                                    'onkeyup' => 'getCityName(this.id);',
                                                                    'label'=>false
                                                                ]); ?>
                                                            </div>
                                                            <div class="col-sm-2"><?php
                                                                echo $this->Form->input('DeliveryLocation.location_name',[
                                                                    'class'=>'form-control deliveryLocationName',
                                                                    'placeholder'=>'Location',
                                                                    'type' => 'text',
                                                                    'id' => 'location_name_0',
                                                                    'name' => 'data[DeliveryLocation][0][location_name]',
                                                                    'onkeyup' => 'getLocationName(this.id, 0);',
                                                                    'label'=>false
                                                                ]);?>
                                                            </div>
                                                            <div class="col-sm-2"><?php
                                                                echo $this->Form->input('DeliveryLocation.minimum_order',[
                                                                    'class'=>'form-control',
                                                                    'placeholder'=>'Minimum Order',
                                                                    'type' => 'text',
                                                                    'id' => 'minimum_order_0',
                                                                    'name' => 'data[DeliveryLocation][0][minimum_order]',
                                                                    'label'=>false
                                                                ]); ?>
                                                            </div>
                                                            <div class="col-sm-2"> <?php
                                                                echo $this->Form->input('DeliveryLocation.delivery_charge',[
                                                                    'class'=>'form-control',
                                                                    'placeholder'=>'Delivery Charge',
                                                                    'type' => 'text',
                                                                    'id' => 'delivery_charge_0',
                                                                    'data-attr'=>'delivery_charge',
                                                                    'name' => 'data[DeliveryLocation][0][delivery_charge]',
                                                                    'label'=>false
                                                                ]);
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <div class="appendDeliveryLocation"></div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>


                            <div class="tab-pane" id="tab_4">
                                <div class="box-body">
                                    <div class="form-group clearfix">
                                        <label for="" class="col-md-3 col-sm-4 control-label">Email Order</label>
                                        <div class="col-md-4 col-sm-6">
                                            <label class="radio-inline no-padding-left">
                                                <input type="radio" name="email_order" class="minimal mailOrder" <?= ($restDetails['email_order'] == 'Yes') ? 'checked' : '' ?> value="Yes">
                                                Yes
                                            </label>
                                            <label class="radio-inline no-padding-left">
                                                <input type="radio" name="email_order" class="minimal mailOrderNo" <?= ($restDetails['email_order'] == 'No') ? 'checked' : '' ?> value="No">
                                                No
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group clearfix" id="orderEmail" style="<?= ($restDetails['email_order'] == 'No') ? 'display:none' : '' ?>">
                                        <label class="col-md-3 col-sm-4  control-label">Order Email </label>
                                        <div class="col-md-4 col-sm-6">
                                            <?= $this->Form->input('order_email',[
                                                'type' => 'text',
                                                'id'   => 'order_email',
                                                'class' => 'form-control',
                                                'value' => $restDetails['order_email'],
                                                'label' => false
                                            ]) ?>
                                        </div>
                                        <span class="orderEmailErr"></span>
                                    </div>
                                    <div class="form-group clearfix">
                                        <label for="" class="col-md-3 col-sm-4 control-label">SMS Option</label>
                                        <div class="col-md-4 col-sm-6">
                                            <label class="radio-inline no-padding-left">
                                                <input type="radio" name="sms_option" class="minimal smsOrder" <?= ($restDetails['sms_option'] == 'Yes') ? 'checked' : '' ?> value="Yes">
                                                Yes
                                            </label>
                                            <label class="radio-inline no-padding-left">
                                                <input type="radio" name="sms_option" class="minimal smsOrderNo" <?= ($restDetails['sms_option'] == 'No') ? 'checked' : '' ?> value="No">
                                                No
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group clearfix" id="smsOrder" style="<?= ($restDetails['sms_option'] == 'No') ? 'display:none' : '' ?>">
                                        <label class="col-md-3 col-sm-4  control-label">Phone Number </label>
                                        <div class="col-md-4 col-sm-6">
                                            <?= $this->Form->input('sms_phonenumber',[
                                                'type' => 'text',
                                                'id'   => 'sms_phonenumber',
                                                'class' => 'form-control',
                                                'value' => $restDetails['sms_phonenumber'],
                                                'maxlength' => 11,
                                                'onkeypress' => 'return isNumberKey(event)',
                                                'label' => false
                                            ]) ?>
                                        </div>
                                        <span class="smsPhoneErr"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="tab_5">
                                <div class="box-body">
                                    <div class="form-group clearfix">
                                        <label class="col-md-3 col-sm-4  control-label">Restaurant Commission</label>
                                        <div class="col-md-4 col-sm-6">
                                            <?= $this->Form->input('restaurant_commission',[
                                                'type' => 'text',
                                                'id'   => 'restaurant_commission',
                                                'class' => 'form-control',
                                                'value' => $restDetails['restaurant_commission'],
                                                'label' => false,
                                                'readonly'
                                            ]) ?>
                                        </div>
                                        <span class="commissionErr"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="tab_6">
                                <div class="box-body">
                                    <div class="form-group">
                                        <?php /*if(!empty($restDetails['restaurant_payments'])) {
                                            foreach($restDetails['restaurant_payments'] as $kPay => $vPay){*/?><!--
                                                <label class="col-sm-2 control-label">
                                                    <?php /*echo $vPay['payment_method']['payment_method_name'];*/?>
                                                    <span class="help">*</span>
                                                </label>
                                                <div class="col-sm-4">
                                                    <label class="radio-inline no-padding-left">
                                                        <input type="radio" id="payment_<?php /*echo $vPay['payment_method']['id'];*/?>" name="payment_id[<?php /*echo $vPay['payment_method']['id'];*/?>]" class="minimal" value="Y" <?/*= ($vPay['payment_status'] == 'Y') ? 'checked' : '' */?> > Yes
                                                    </label>
                                                    <label class="radio-inline">
                                                        <input type="radio" id="payment_<?php /*echo $vPay['payment_method']['id'];*/?>" name="payment_id[<?php /*echo $vPay['payment_method']['id'];*/?>]" class="minimal" value="N" <?/*= ($vPay['payment_status'] == 'N') ? 'checked' : '' */?>> No
                                                    </label>
                                                </div>-->

                                        <?php
                                        foreach ($paymentList as $kPay => $vPay) {
                                            ?>
                                            
                                            <label class="col-sm-2 control-label">
                                                <span class="help">*</span>
                                            </label>
                                            <div class="col-sm-4">                                            
                                                <label class="radio-inline no-padding-left">
                                                    <input type="radio"
                                                           id="payment_<?php echo $vPay['id']; ?>"
                                                           name="payment_id[<?php echo $vPay['id']; ?>]"
                                                           class="minimal"
                                                           value="Y" checked>
                                                    Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio"
                                                           id="payment_<?php echo $vPay['id']; ?>"
                                                           name="payment_id[<?php echo $vPay['id']; ?>]"
                                                           class="minimal"
                                                           value="N">
                                                    No
                                                </label>
                                            </div>
                                        <?php } ?>
                                        <span class="paymethodErr"></span>
                                        
                                        <div id="heartland_settings" class="col-sm-12">
                                            <h4>Heartland Settings</h4>
                                            <div class="form-group clearfix">
                                                <label class="col-md-3 col-sm-4  control-label">Public API Key</label>
                                                <div class="col-md-4 col-sm-6">
                                                    <?= $this->Form->input('heartland_public_api_key',[
                                                        'type' => 'text',
                                                        'id'   => 'heartland_public_api_key',
                                                        'class' => 'form-control',
                                                        'value' => $restDetails['heartland_public_api_key'],
                                                        'label' => false
                                                    ]) ?>
                                                </div>
                                            </div>
                                            <div class="form-group clearfix">
                                                <label class="col-md-3 col-sm-4  control-label">Secret API Key</label>
                                                <div class="col-md-4 col-sm-6">
                                                    <?= $this->Form->input('heartland_secret_api_key',[
                                                        'type' => 'text',
                                                        'id'   => 'heartland_secret_api_key',
                                                        'class' => 'form-control',
                                                        'value' => $restDetails['heartland_secret_api_key'],
                                                        'label' => false
                                                    ]) ?>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>


                            <div class="tab-pane" id="tab_7">
                                <div class="box-body">
                                    <div class="form-group clearfix">
                                        <label class="col-md-3 col-sm-4  control-label">Invoice Period</label>
                                        <div class="col-md-4 col-sm-6">
                                            <?= $this->Form->input('invoice_period',[
                                                'type' => 'select',
                                                'id'   => 'invoice_period',
                                                'class' => 'form-control',
                                                'options' => [
                                                    '15' => '15day',
                                                    '30' => '30day'
                                                ],
                                                'value' => $restDetails['invoice_period'],
                                                'label' => false
                                            ]) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="tab_8">
                                <div class="box-body">
                                    <div class="form-group clearfix">
                                        <label class="col-md-3 col-sm-4  control-label">Meta Titles </label>
                                        <div class="col-md-4 col-sm-6">
                                            <?= $this->Form->input('meta_title',[
                                                'type' => 'textarea',
                                                'id'   => 'meta_title',
                                                'class' => 'form-control',
                                                'value' => $restDetails['meta_title'],
                                                'label' => false
                                            ]) ?>
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <label class="col-md-3 col-sm-4  control-label">Meta Keywords </label>
                                        <div class="col-md-4 col-sm-6">
                                            <?= $this->Form->input('meta_keyword',[
                                                'type' => 'textarea',
                                                'id'   => 'meta_keyword',
                                                'class' => 'form-control',
                                                'value' => $restDetails['meta_keyword'],
                                                'label' => false
                                            ]) ?>
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <label class="col-md-3 col-sm-4  control-label">Meta Descriptions</label>
                                        <div class="col-md-4 col-sm-6">
                                            <?= $this->Form->input('meta_description',[
                                                'type' => 'textarea',
                                                'id'   => 'meta_description',
                                                'class' => 'form-control',
                                                'value' => $restDetails['meta_description'],
                                                'label' => false
                                            ]) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="tab_9">
                                <div id="promo_image" class="form-group">
                                    <label class="col-md-3 control-label">Promotion Image <span class="star">*</span></label>
                                    <div class="col-md-9 col-lg-9">
                                        <a href="javascript:void(0);" class="addNew flt" onclick="return promotionImage();">Add New Image</a><span class="note-point"><b>Note :</b> Approximately image width and height should be 414px X 300px</span>
                                        <div class="clearfix"></div>
                                        <?php
                                        if(!empty($EditPromoImgList)) {
                                            foreach ($EditPromoImgList as $key => $value) {

                                                if(!empty($value)) { ?>
                                                    <div class="col-xs-3">
                                                        <div class="promoimagewrap" id="imagelist_<?php echo $value['id'];?>">
                                                            <img src="<?php echo BASE_URL.'webroot/uploads/storeBanners/'. $value['promo_image']; ?>" alt="" width="150" height="150" onerror="this.src='<?php echo BASE_URL;?>webroot/images/no_store.jpg'">
                                                            <a class="close-link" onclick="deletePromoImage('<?php echo $value['id'];?>');">X</a>
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                            }
                                        } ?>
                                        <div id="promoStore" class="promoStoreImage"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="tab_10">
                                <div id="promo_image" class="form-group">
                                    <label class="col-md-3 control-label">Facebook Order <span class="star">*</span></label>
                                    <div class="col-md-9 col-lg-9">
                                        <div class="clearfix"></div>
                                        <?php
                                        $appId = $siteSettings['facebook_api_id'];
                                        $secretKey = $siteSettings['facebook_secret_key'];
                                        $resId = $id;
                                        ?>

                                        <a target="_blank" class="add_to_fb" href="https://www.facebook.com/dialog/pagetab?app_id=<?php echo $appId; ?>&display=popup&next=<?php echo ADMIN_BASE_URL.'restaurants/faceBookAdd/'.$resId; ?>"><span class="icon"><i class="fa fa-facebook"></i></span> Add to facebook</a>

                                        <?php
                                        if($restDetails['fb_page_id'] != '') { ?>
                                            <a class="btn btn-link margin-t-25" target="_blank" href="<?php echo $restDetails['fb_page_url']; ?>"> <?php echo $restDetails['fb_page_url']; ?>
                                            </a>
                                            <?php

                                        }
                                        ?>


                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane new-item" id="tab_11">
                                <div class="row contain">
                                    <div class="col-md-offset-3 col-md-6 col-lg-4">
                                        <label id="orderError" class="error"></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Reward Point</label>
                                    <div class="col-sm-4">
                                        <label class="radio-inline no-padding-left">
                                            <input type="radio" name="reward_option" class="minimal" value="Yes" <?= ($restDetails['reward_option'] == 'Yes') ? 'checked' : '' ?> >
                                            Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="reward_option" class="minimal" value="No" <?= ($restDetails['reward_option'] == 'No') ? 'checked' : '' ?>>
                                            No
                                        </label>

                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="tab_12">
                                <div class="box-body">                               
                                    <div class="form-group clearfix">
                                        <label for="" class="col-md-2 col-sm-3 control-label">Minimum Pickup Time</label>
                                        <div class="col-md-4 col-sm-5">
                                            <?= $this->Form->input('minimum_pickup_time',[
                                                'type' => 'text',
                                                'id'   => 'minimum_pickup_time',
                                                'class' => 'form-control',
                                                'placeholder' => '25',
                                                'value' => $restDetails['minimum_pickup_time'],
                                                'label' => false
                                            ]) ?>
                                            <span class="minimumPickupTimeErr"></span>
                                        </div>
                                        <label for="" class="col-md-2 col-sm-3 control-label" style="
                                                                    text-align: left;">mins</label>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="tab_14">
                                <div class="box-body">                               
                                    <div class="form-group clearfix">
                                        <label for="" class="col-md-2 col-sm-3 control-label">Time Zone</label>
                                        <div class="col-md-4 col-sm-6 no-padding-right">
                                            <?= $this->Form->input('restaurant_timezone',[
                                                'type' => 'select',                                                
                                                'id'   => 'restaurant_timezone',
                                                'class' => 'form-control',
                                                'options' => $timezoneList,
                                                'value' => $selectedTimezone,
                                                'label' => false
                                            ]) ?>
                                        </div>
                                    <span class="timezoneErr"></span>                                       
                                    </div>
                                </div>
                            </div>

                            <div class="box-footer">
                                <a type="submit" class="btn btn-default m-r-15" href="<?php echo REST_BASE_URL ?>restaurants">Cancel</a>
                                <button type="submit" class="btn btn-info" onclick=" return addRestaurant();">Submit</button>
                            </div>
                        </div>
                        <?= $this->Form->end();?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    function cityList() {
        var state_id = $.trim($("#state_id").val());
        var bySearch = $.trim($("#bySearch").val());

        $.ajax({
            type   : 'POST',
            url    : jssitebaseurl+'restaurants/ajaxaction',
            data   : {state_id:state_id, bySearch:bySearch, action: 'getCity'},
            success: function(data){
                $('#cityList').html(data);
                return false;
            }
        });
        return false;
    }

    function locationList() {
        var state_id = $.trim($("#state_id").val());
        var city_id = $.trim($("#city_id").val());
        var bySearch = $.trim($("#bySearch").val());
        if(state_id != ''){
            $.ajax({
                type   : 'POST',
                url    : jssitebaseurl+'restaurants/ajaxaction',
                data   : {city_id:city_id, bySearch:bySearch, action: 'getLocation'},
                success: function(data){
                    $('#locationList').html(data);
                    return false;
                }
            });
            return false;
        }
    }

    function isValid(mailAddress){
        var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
        return pattern.test(mailAddress);
    }

    function addRestaurant() {
        // alert("come  here");

        $(".error").html('');
        var Url   = jssitebaseurl+'restaurants/checkEmail';
        var resId = $("#resId").val();

        var contact_name = $.trim($("#contact_name").val());
        var contact_phone = $.trim($("#contact_phone").val());
        var contact_email = $.trim($("#contact_email").val());

        var bySearch      = $.trim($("#bySearch").val());
        var contact_address = $.trim($("#contact_address").val());
        var street_address = $.trim($("#street_address").val());
        var state_id    = $.trim($("#state_id").val());
        var city_id     = $.trim($("#city_id").val());
        var location_id = $.trim($("#location_id").val());

        //Restaurant Info
        var restaurant_name = $.trim($("#restaurant_name").val());
        var restaurant_phone = $.trim($("#restaurant_phone").val());
        var restaurant_logo = $.trim($("#restaurant_logo").val());
        var restaurant_tax = $.trim($("#restaurant_tax").val());
        var restaurant_cuisine = $.trim($("#restaurant_cuisine").val());
        var username = $.trim($("#username").val());
        var password = $.trim($("#password").val());

        //Delivery Info
        var estimate_time = $.trim($("#estimate_time").val());
        var minimum_order = $.trim($("#minimum_order").val());
        var free_delivery = $.trim($("#free_delivery").val());

        //Order Info
        var email_order = $('input[name="email_order"]:checked').val();
        var sms_option = $('input[name="sms_option"]:checked').val();

        var order_email = $.trim($("#order_email").val());
        var sms_phonenumber = $.trim($("#sms_phonenumber").val());


        //Commission Info
        var restaurant_commission = $.trim($("#restaurant_commission").val());

        // Minimum Pickup Time
        var minimum_pickup_time = $.trim($("#minimum_pickup_time").val());

        // Pickup Timezone
        var restaurant_timezone = $.trim($("#restaurant_timezone").val());  

        // // Pizza Menu Name
        // var pizza_menu_name = $.trim($("#pizza_menu_name").val());

        // // Pizza Menu Details
        // var pizza_menu_details = $.trim($("#pizza_menu_details").val());

        if(contact_name == '') {
            $("#contactInfo").click();
            $(".contactNameErr").addClass('error').html('Please enter your contact name');
            $("#contact_name").focus();
            return false;
        }else if(contact_phone == '') {
            $("#contactInfo").click();
            $(".contactPhoneErr").addClass('error').html('Please enter your contact phone');
            $("#contact_phone").focus();
            return false;
        }else if(contact_email == '') {
            $("#contactInfo").click();
            $(".contactemailErr").addClass('error').html('Please enter your contact email');
            $("#contact_email").focus();
            return false;
        }else if(contact_email != '' && !isValid(contact_email)) {
            $("#contactInfo").click();
            $(".contactemailErr").addClass('error').html('Please enter your valid contact email');
            $("#contact_email").focus();
            return false;
        }else if(bySearch != '' && bySearch == 'Google' && contact_address == '') {
            $("#contactInfo").click();
            $(".addressErr").addClass('error').html('Please enter your contact address');
            $("#contact_address").focus();
            return false;
        }else if(bySearch != '' && bySearch != 'Google' && street_address == '') {
            $("#contactInfo").click();
            $(".streetErr").addClass('error').html('Please enter your street');
            $("#street_address").focus();
            return false;
        }else if(bySearch != '' && bySearch != 'Google' && state_id == '') {
            $("#contactInfo").click();
            $(".stateErr").addClass('error').html('Please select your state');
            $("#state_id").focus();
            return false;
        }else if(bySearch != '' && bySearch != 'Google' && city_id == '') {
            $("#contactInfo").click();
            $(".cityErr").addClass('error').html('Please  select your city');
            $("#city_id").focus();
            return false;
        }else if(bySearch != '' && bySearch != 'Google' && location_id == '') {
            $("#contactInfo").click();
            $(".locationErr").addClass('error').html('Please select your location');
            $("#location_id").focus();
            return false;
        }else if(restaurant_name == '') {
            $("#restaurantInfo").click();
            $(".restnameErr").addClass('error').html('Please enter restaurant name');
            $("#restaurant_name").focus();
            return false;
        }else if(restaurant_phone == '') {
            $("#restaurantInfo").click();
            $(".restphoneErr").addClass('error').html('Please enter restaurant phone');
            $("#restaurant_phone").focus();
            return false;
        }/*else if(restaurant_logo == '') {
            $("#restaurantInfo").click();
            $(".restlogoErr").addClass('error').html('Please enter restaurant logo');
            $("#restaurant_name").focus();
            return false;
        }*/else if(restaurant_tax == '') {
            $("#restaurantInfo").click();
            $(".taxErr").addClass('error').html('Please enter restaurant tax');
            $("#restaurant_tax").focus();
            return false;
        }else if(restaurant_cuisine == '') {
            $("#restaurantInfo").click();
            $(".cuisineErr").addClass('error').html('Please select a cuisine');
            $("#restaurant_cuisine").focus();
            return false;
        }else if(username == '') {
            $("#restaurantInfo").click();
            $(".usernameErr").addClass('error').html('Please enter username');
            $("#username").focus();
            return false;
        }else if(username != '' && !isValid(username)) {
            $("#restaurantInfo").click();
            $(".usernameErr").addClass('error').html('Please enter valid username');
            $("#username").focus();
            return false;
        }else if(estimate_time == '') {
            $("#deliveryInfo").click();
            $(".estimateErr").addClass('error').html('Please enter estimate Time');
            $("#estimate_time").focus();
            return false;
        }else if(minimum_pickup_time == '') {
            $("#pickupTimeInfo").click();
            $(".minimumPickupTimeErr").addClass('error').html('Please enter Minimum Pick up Time');
            $("#minimum_pickup_time").focus();
            return false;
        }else if(restaurant_timezone == '') {
            $("#timeZone").click();
            $(".timezoneErr").addClass('error').html('Please enter Pick up Timezone');
            $("#restaurant_timezone").focus();   
        }else if(bySearch != '' && bySearch == 'Google' && minimum_order == '') {
            $("#deliveryInfo").click();
            $(".minimumErr").addClass('error').html('Please enter minimum order');
            $("#minimum_order").focus();
            return false;
        }else if(bySearch != '' && bySearch == 'Google' && free_delivery == '') {
            $("#deliveryInfo").click();
            $(".freedeliveryErr").addClass('error').html('Please enter free delivery amount');
            $("#free_delivery").focus();
            return false;
        }else if(email_order == 'Yes' && order_email == '') {
            $("#orderInfo").click();
            $(".orderEmailErr").addClass('error').html('Please enter order email');
            $("#order_email").focus();
            return false;

        }else if(order_email != '' && !isValid(order_email)) {
            $("#orderInfo").click();
            $(".orderEmailErr").addClass('error').html('Please enter valid order  email');
            $("#order_email").focus();
            return false;

        }else if(sms_option === 'Yes' && sms_phonenumber === '') {
            $("#orderInfo").click();
            $(".smsPhoneErr").addClass('error').html('Please enter order phone number');
            $("#sms_phonenumber").focus();
            return false;
        }else if(restaurant_commission == '') {
            $("#commissionInfo").click();
            $(".commissionErr").addClass('error').html('Please enter commission');
            $("#restaurant_commission").focus();
            return false;
        }else {
            $.post(
                Url,
                {
                    'contact_email': username,
                    'restname': restaurant_name,
                    'id' : resId,
                    'minimum_pickup_time' : minimum_pickup_time,
                    'restaurant_timezone': restaurant_timezone
                },
                function (data) {
                    if($.trim(data) == 'rest') {
                        $("#restaurantInfo").click();
                        $(".restnameErr").addClass('error').html('Restaurant name already exists');
                        $("#restaurant_name").focus();
                        return false;

                    }else if($.trim(data) == 'user') {
                        $("#restaurantInfo").click();
                        $(".usernameErr").addClass('error').html('Email Already exists');
                        $("#username").focus();
                        return false;
                    }else {
                        $("#restaurantAdd").submit();
                    }
                    return false;
                }
            );
        }
        return false;
    }

    //DeliveryInfo Location
    function deliveryLocation(){

        var searchBy    = $.trim($("#bySearch").val());
        var stateId     = $.trim($("#state_id").val());

        if(searchBy != '' && searchBy != 'Google' && stateId == '') {
            $("#contactInfo").click();
            $(".stateErr").addClass('error').html('Please select your state');
            $("#state_id").focus();
            return false;
        }
    }

    //Delivery Info
    function showMapPolygon() {

        var resId     = $('#resId').val();
        var Url         = jssitebaseurl+'restaurants/ajaxaction';
        var Address     = $('#contact_address').val();
        // var distance    = $('#StoreDeliveryDistance').val();

        var mapType =   $.trim($('input[name="map_mode"]:checked').val());
        if (Address == '') {
            $("#contactInfo").click();
            $(".addressErr").addClass('error').html('Please enter your contact address');
            $("#contact_address").focus();
            return false;
        } else if(mapType == 'Circle') {

            $("#circleMapDiv").show();
            $("#polygon-map").hide();
            $("#googleMapShow").show();
            $("#deliveryCharge").hide();

            $.post(
                Url,
                {
                    'address': Address,
                    'resId': resId,
                    'action': 'showMapEdit'
                },
                function (data) {
                    $('#googleMapShow').html(data);
                    return false;
                }
            );
        } else if(mapType == 'Polygon') {
            $("#polygon-map").show();
            $("#circleMapDiv").hide();
            $("#googleMapShow").hide();
            $("#deliveryCharge").show();
            var mapDetails = '';
            $.post(
                Url,{'address': Address, 'action': 'showPolygonmap'},
                function (data) {
                    showAreaMap(data);
                }
            );
        }
    }

    function showAreaMap(mapDetails) {

        var Url = jssitebaseurl+'restaurants';
        //Basic
        var i,j;
        var l = 0;
        var overlay, image, selectedShape,
            polys   = new Array(),
            auth    = false;
        var lat   = $.trim($("#store_latitude").val());
        var lng   = $.trim($("#store_longitude").val());
        var resid = $.trim($("#resId").val());
        if(lat == '' && lng == '') {
            var mapDet  =  mapDetails.split("###");
            lat = mapDet[0];
            lng = mapDet[1];
        }
        var cartodbMapOptions = {
            zoom: 15,
            center: new google.maps.LatLng( lat, lng ),
            disableDefaultUI: true,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        // Init the map
        map = new google.maps.Map(document.getElementById("map-view"),cartodbMapOptions);
        getPolygons();
        var mapManualId   =   0;
        var Color = ["#800080","#00FF00","#FF00FF","#FF0000","#0000FF","#808000","#008000","#00FFFF","#C71585"];
        var drawingManager = new google.maps.drawing.DrawingManager({
            drawingControl: true,
            drawingControlOptions: {
                position: google.maps.ControlPosition.TOP_RIGHT,
                drawingModes: [google.maps.drawing.OverlayType.POLYGON]
            },
            polygonOptions: {
                fillColor: Color[i],
                fillOpacity: 0.3,
                strokeColor: '#AA2143',
                strokeWeight: 2,
                clickable: true,
                zIndex: 1,
                editable: true
            }
        });
        $(".delivery_Charges").each(function() {
            mapManualId++;
        });
        //alert(mapManualId);
        drawingManager.id   =   l;
        drawingManager.setMap(map);
        var myLatlng = new google.maps.LatLng(lat,lng);
        var marker = new google.maps.Marker({
            position: myLatlng
        });
        marker.setMap(map);
        $(".mapidCount").each(function() {
            l++;
        });
        google.maps.event.addListener(drawingManager, 'overlaycomplete', function(e) {
            var newShape = e.overlay;
            newShape.type = e.type;
            google.maps.event.addListener(newShape, 'click', function() {
                setSelection(this);
            });
            setSelection(newShape);
            if(l == 0) {
                $("#deliveryCharge_0").show();
                storePolygon(newShape.getPath(), l);
            } else {
                if(($("#delCharge" + (l-1)).val() == '')){
                    alert('Please enter delivery Charge');
                    deleteSelectedShape();
                    return false;
                } else if(($("#delCharge" + (l-1)).val() == 0)){
                    alert('Please enter valid amount');
                    $("#delCharge" + (l-1)).val('');
                    return false;
                } else {
                    polygonChargeAppend(l);
                    storePolygon(newShape.getPath(), l);
                }
            }

            newShape.setEditable(false);
            l++;
        });

        /*google.maps.event.addListener(map, 'click', function (event) {
         alert(this.id);
         //Once you have the id here, you can trigger the color change
         });*/
        google.maps.event.addListener(map, 'click', clearSelection);

        function polygonChargeAppend(nextId) {

            $("#chargeAppend").append(
                '<div class="delivery_Charges" id="deliveryCharge_' + nextId + '">' +
                '<div class="col-sm-12">' +
                '<div class="row">' +
                '<div class="input text">'+
                '<input id="delCharge'+ nextId +'" class="form-control margin-b-10" type="text" placeholder="Enter Delivery Charge" name="AreaMaps['+ nextId +'][delivery_charge]">' +
                '</div>'+
                '<input id="area_'+ nextId +'_coords" type="hidden" name="coords['+ nextId +']area"/>'+
                '<input id="area_'+ nextId +'_record" type="hidden" name="record['+ nextId +']area"/>'+
                '<input id="area_'+ nextId +'_mapid" type="hidden" name="mapid['+ nextId +']area"/>'+
                '</div>' +
                '</div>' +
                '</div>'
            );
        }

        function getPolygons() {
            var i;
            $.post(Url + '/getPolygonList',{'resid':resid}, function(response){
                var mappoints = '';
                response = JSON.parse(response);
                for(i in response.rows) {
                    if(response.rows[i].mapcoords != '') {
                        var
                            coords = JSON.parse(response.rows[i].mapcoords).coordinates[0][0],
                            poly   = new Array();

                        for (j in coords) {
                            poly.push(new google.maps.LatLng(coords[j][1], coords[j][0]))
                        }

                        drawPolygon( response.rows[i].id, poly, response.rows[i].colorcode );
                    }
                    mappoints = '<span class="bgorange" style="cursor:pointer;background:'+response.rows[i].colorcode+'" onclick="deleteStoreMap('+response.rows[i].id+')"><i class="fa fa-close"></i></span>';
                    $("#DeleteMap"+i).html(mappoints);
                }
            });
        }

        function drawPolygon(id, poly, colorcode) {
            var options = { paths: poly,
                strokeColor: '#AA2143',
                strokeOpacity: 1,
                strokeWeight: 2,
                fillColor: colorcode,
                fillOpacity: 0.3 };

            newPoly = new google.maps.Polygon(options);
            newPoly.id = id;
            newPoly.setMap(map);
            google.maps.event.addListener(newPoly, 'click', function() {
                this.setEditable(true);
                setSelection(this);
            });

            polys.push(newPoly);
        }

        function clearSelection() {
            if(selectedShape) {
                selectedShape.setEditable(false);
                selectedShape = null;
            }
        }

        function setSelection(shape) {
            clearSelection();
            selectedShape = shape;
            shape.setEditable(true);
        }

        function deleteSelectedShape(cout) {
            if(selectedShape) {
                selectedShape.setMap(null);
                $("#deliveryCharge_"+cout).remove();
            }
        }

        function storePolygon(path, id) {

            var
                coords  = new Array(),
                pathcords = new Array(),
                payload = {type: "MultiPolygon", coordinates: new Array()};

            payload.coordinates.push(new Array());
            payload.coordinates[0].push(new Array());

            for (var i = 0; i < path.length; i++) {
                coord = path.getAt(i);
                coords.push( coord.lng() + " " + coord.lat() );
                payload.coordinates[0][0].push([coord.lng(),coord.lat()])
            }
            $('#deliveryCharge_0').show();
            if(id != '') {
                $.post(Url + '/getCharge',{'mapid':id,'resid':resid}, function(data){

                    var answer = data.split("^^^");
                    $('#minOrder').val(answer[0]);
                    $('#delCharge').val(answer[1]);
                    return false;
                });
            }
            var q = JSON.stringify(payload);
            $('#area_'+ id +'_coords').val(q);
            $('#area_'+ id +'_record').val(coords);
            $('#area_'+ id +'_mapid').val(id);
        }
    }

    var i = 0;
    function radiusTextAppend(){

        var settingsCount = $('.radius_settings').length;
        var j = $('.deliver_Charge').length;
        var k = $('.settingcount').length;
        $(".settingcount").each(function() {
            if($(this).val() == '') {
                alert("please enter the radius");
                return false;
            }
            k--;
        });
        $(".deliver_Charge").each(function() {
            if($(this).val() == '') {
                alert("please enter the delivery charge");
                return false;
            }
            j--;
        });

        if (j == 0 && k == 0) {
            $("#textAppend").append('<div class="radius_settings" id="radius_settings_' + settingsCount + '">' +
                '<div class="input-group margin-b-10">' +
                '<input type="text" class="form-control settingcount" name="DeliverySettings[' + settingsCount + '][delivery_miles]" placeholder="Enter Mile (radius)" id="' + settingsCount + '">' +
                '<div id="rmve_' + settingsCount + '" class="input-group-addon" onclick="return generateRadius(' + settingsCount + ')" style="cursor: pointer"><i class="fa fa-check"></i> </div>' +
                '</div>' +
                '<div class="setColor" id="set_color_' + settingsCount + '"></div>' +
                '</div>'
            );
        }
        i++;
    }

    function generateRadius(id) {
        

        var miles   =   $("#"+id).val();
        if (isNaN($.trim(miles))) {
            alert('Enter number only');
            $('#'+id).val('');
            return false;
        }
        if($.trim(miles) == ''){
            alert('Enter radius');
            return false;
        }
        if($.trim(miles) <= 0){
            alert('Please Enter valid radius');
            return false;
        }
        var Id  =   id;
        if(Id>0) {
            for(Id;Id>=0;Id--){
                var existmiles  =   $("#"+(Id-1)).val();
                if($.trim(miles) == $.trim(existmiles)) {
                    alert("Radius already exist");
                    return false;
                }
            }
        }


        var resid        = $('#resId').val();
        var address      = $('#contact_address').val();
        var latitude     = $('#store_latitude').val();
        var longitude    = $('#store_longitude').val();
        var radius_count = $('.radius_count').length;
        var Url          = jssitebaseurl+'restaurants/ajaxaction';

        $('#circle_'+id).remove();
        $.post(
            Url,
            {'resid':resid, 'miles':miles, 'address':address, 'latitude':latitude, 'longitude':longitude, 'circleCount':id,
                'radius_count':radius_count, 'action':'getCircle'},
            function(response){

                var resultCircle = $.trim(response).split('**');
                var result = resultCircle[0];
                var color = resultCircle[1];
                $('#delivery_charge_'+id+'').remove();
                $('#circle_'+id+'').remove();
                $('.mapCircle').append(
                    '<div class="radius_count" id="circle_'+id+'">'+result+'</div>'
                );

                $('#set_color_'+id+'').append(
                    '<div class="col-sm-12 no-padding margin-t-25 margin-b-10" id="delivery_charge_'+id+'">'+
                    '<span class="bgorange" style="background-color: '+color+'"><i class="fa fa-map-marker"></i></span>'+
                    '<a href="javascript:;" class="clsbtn" onclick="return removeCircle('+id+')">' +
                    '<i class="fa fa-close"></i>'+
                    '</a>'+
                    '<div class="col-sm-7 no-padding margin-left-20">' +
                    '<input type="text" id="deliveryChargeId_'+id+'" class="form-control deliver_Charge" name="DeliverySettings['+id+'][delivery_charge]" placeholder="Price">' +
                    '<input type="hidden" name="DeliverySettings['+id+'][radius_color]" value="'+color+'">' +
                    '<input type="hidden" name="DeliverySettings['+id+'][map_type]" value="Radius">' +
                    '<input type="hidden" name="DeliverySettings['+id+'][delivery_miles]" value="'+miles+'">' +

                    '</div>'+
                    '</div>'
                );
            }
        );
        // }
    }

    function removeCircle(id) {
        var Url         = jssitebaseurl+'restaurants/ajaxaction';

        $.post(Url,{'id':id,'action' : 'removeCircle'},function(response) {
                $('#'+id).remove();
                $('#rmve_'+id).remove();
                $('#set_color_'+id).html('');
                $('.mapCircle').append(response);
                $('#circle_'+id).hide();
            }
        );
    }

    function deleteStoreMap(id)
    {
        var Url         = jssitebaseurl+'restaurants/ajaxaction';
        $.post(Url,{'id':id,'action' : 'deleteStoreMap'}, function(response){
            if($.trim(response) == 'success'){
                window.location.reload();
                return false;
            }
        });
    }

    function getContactmail() {
        var order_email = $("#order_email").val();
        if(order_email == '') {
            var contact_email = $("#contact_email").val();
            $("#order_email").val(contact_email);
        }
    }

    function getImage() {
        var restaurant_logo = $("#restaurant_logo").html();
        alert(restaurant_logo);
    }

    function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }
    var special_row3=0;
    function promotionImage(){
        $('.promoStoreImage').append(
            '<span class="col-xs-3" id="promoimage_'+special_row3+'"><label for="data[res_promotion]['+special_row3+']" class="promoimagewrap">'+
            '<input class="hide" type="file" name="data[res_promotion]['+special_row3+']" id="data[res_promotion]['+special_row3+']" size="25" />'+
            '<a class="close-link" onclick="removeImage('+special_row3+');">X</a>'+
            '</label></span>');
        special_row3++;
    }

    function removeImage(id){
        $('#promoimage_'+id).remove();
    }

    function deletePromoImage(id) {

        var check = confirm("Are Sure You Want Delete");

        if($.trim(check) == 'true') {
            $.post(jssitebaseurl+'restaurants/deleteProcess',{'id':id,'model':'Promo'}, function(response) {
                //alert(response);
                $('#imagelist_'+id).remove();
            });
        }
    }

    //Location based delivery

    var locationRow = (typeof j != 'undefined') ? j : 1;
    function appendDeliveryLocation() {
        $('.appendDeliveryLocation').append(
            '<div class="form-group" id="removeLocation_'+locationRow+'">'+
            '<div class="col-sm-9 col-sm-offset-3">'+
            '<div class="row">'+
            '<div class="col-sm-2">'+
            '<input type="text" class="form-control" name=data[DeliveryLocation]['+locationRow+'][city_name]" id="city_name_'+locationRow+'" onkeyup="getCityName(this.id);" placeholder="City">'+
            '</div>'+
            '<div class="col-sm-2">'+
            '<input type="text" class="form-control deliveryLocationName" name=data[DeliveryLocation]['+locationRow+'][location_name]" id="location_name_'+locationRow+'" onkeyup="getLocationName(this.id, '+locationRow+');" placeholder="Postcode  ">'+
            '</div>'+
            '<div class="col-sm-2">'+
            '<input type="text" class="form-control" name=data[DeliveryLocation]['+locationRow+'][minimum_order]" id="minimum_order_'+locationRow+'" placeholder="Min order">'+
            '</div>'+
            '<div class="col-sm-2">'+
            '<input type="text" class="form-control" name=data[DeliveryLocation]['+locationRow+'][delivery_charge]" id="delivery_charge_'+locationRow+'" placeholder="Del Charge">'+
            '</div>'+
            '<div class="col-sm-2">'+
            '<a onclick="removeLocation('+locationRow+');" class="btn btn-danger">X</a>'+
            '</div>'+
            '</div>'+
            '</div>'+
            '</div>'
        );
        locationRow++;
    }

    function removeLocation(removeId) {
        $('#removeLocation_'+removeId).remove();
        return false;
    }


    function getCityName(fieldId) {
        var stateId = $('#state_id').val();
        if (stateId == '') {
            $("#contactInfo").click();
            $(".stateErr").addClass('error').html('Please select your state');
            $("#state_id").focus();
            return false;
        } else {
            $.ajax({
                type   : 'POST',
                url    : jssitebaseurl+'restaurants/getCityName',
                data   : {'state_id' : stateId},
                success: function(response){
                    var cityName = response.split(',');
                    $('#'+fieldId).autocomplete({
                        source: cityName,
                    });
                    return false;
                }
            });
            return false;
        }
    }


    function getLocationName(fieldId, $cityFieldId) {
        var stateId = $('#state_id').val();
        var cityName = $('#city_name_'+$cityFieldId).val();

        if (stateId == '') {
            $("#contactInfo").click();
            $(".stateErr").addClass('error').html('Please select your state');
            $("#state_id").focus();
            return false;
        } else if (cityName == '') {
            $("#deliveryCityErr").html("Please enter the city");
            $("#city_name_"+$cityFieldId).focus();
            return false;
        } else {
            $.ajax({
                type   : 'POST',
                url    : jssitebaseurl+'restaurants/getLocationName',
                data   : {'stateId' : stateId, 'cityName' : cityName},
                success: function(response){
                    var LocationName = response.split(',');
                    $('#'+fieldId).autocomplete({
                        source: LocationName,
                        select : function(event, ui) {
                            checkLocationAlreadyExist(fieldId, ui.item.value);
                            return false;
                        }
                    });
                    return false;
                }
            });
            return false;
        }
    }

    function checkLocationAlreadyExist(fieldId, locationName) {

        var i = 0;
        $('.deliveryLocationName').each(
            function() {
                if (this.value == locationName) {
                    i++;
                }
            }
        );

        if (i > 0) {
            searchBy = $('#searchBy').val();
            $('#'+fieldId).val('');
            $("#deliveryCityErr").html(searchBy+" already exist");
            return false;
        } else {
            $('#'+fieldId).val(locationName);
            $("#deliveryCityErr").html('');
            return false;
        }
    }

    function pluginDetail(resId) {
        $('#resId').html(resId);
        $('#pluginPopUp').modal('show');
        return false;
    }

</script>

<div aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="pluginPopUp" class="modal fade" >
    <div role="document" class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Plugin Script</h4>
            </div>
            <div id="trackingContent" class="modal-body clearfix">
                <div class="col-xs-12">
                    <p>Integrate Our Plugin to your website.</p>
                    <ul class="plugin_steps">
                        <li>
                            Step1:
                            <div class="form-horizontal">
                                <div class="col-sm-12 padding20 margin-b-10 margin-t-10">
                                    <p>Just copy this HTML snippets and paste it before closing of body tag &lt;body&gt;..&lt;/body&gt;.</p>
                                    <code class="col-xs-12 margin-b-20" cols="100" rows="" readonly="true">
                                        &lt;script type="text/javascript"&gt;<br>
                                        var resId = "<span id="resId"></span>";<br>
                                        &lt;/script&gt;<br>
                                        &lt;script type="text/javascript" src="<?php echo DIST_URL; ?>widget/js/widget.js"&gt;&lt;/script&gt;

                                    </code>
                                </div>
                            </div>
                        </li>
                        <li>
                            Step2:
                            <div class="form-horizontal">
                                <div class="col-sm-12 padding10 margin-t-10">
                                    <p>Add this class (MOA-order) in tag which you need for Order Online.</p>
                                    <p class="color5">For Example : &lt;a class="MOA-order"&gt; Order Online &lt;/a&gt;
                                    </p></div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

