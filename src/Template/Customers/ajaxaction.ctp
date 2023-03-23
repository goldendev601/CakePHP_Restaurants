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


<?php if($action == 'editgetCity') { ?>
    <?= $this->Form->input('city_id',[
        'type' => 'select',
        'id'   => 'editcity_id',
        'class' => 'form-control',
        'empty'  => 'select city',
        'onchange' => 'editlocationList();',
        'options'=> $citylist,
        'label' => false
    ]) ?>
<?php die(); } ?>

<?php if($action == 'editgetLocation') { ?>
    <?= $this->Form->input('location_id',[
        'type' => 'select',
        'id'   => 'editlocation_id',
        'class' => 'form-control',
        'empty'  => 'select location',
        'options'=> $locaionlist,
        'label' => false
    ]) ?>
<?php die(); } ?>


<?php if($action == 'editAddress') { ?>
    <input type="hidden" value="<?= $id ?>" id="editId">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo __("Edit New Address");?></h4>
            </div>
            <div class="modal-body clearfix">
                <div class="form-group clearfix">
                    <label class="control-label col-md-4"><?php echo __("Address Title");?></label>
                    <div class="col-md-8">
                        <input type="text" value="<?= $addressDetails['title'] ?>" id="edittitle" name="title" class="form-control my-form-control">
                        <span class="edittitleErr"></span>
                    </div>
                </div>

                <div class="form-group clearfix">
                    <label class="control-label col-md-4"><?php echo __("Door no/Flat no");?></label>
                    <div class="col-md-8">
                        <input type="text" value="<?= $addressDetails['flat_no'] ?>" id="editflat_no" name="flat_no" class="form-control my-form-control" >
                        <span class="editflatnoErr"></span>
                    </div>
                </div>

                <?php if(SEARCHBY == 'Google') { ?>
                    <div class="form-group clearfix">
                        <label class="control-label col-md-4"> <?php echo __("Address");?></label>
                        <div class="col-md-8">
                            <input type="text" value="<?= $addressDetails['address'] ?>" id="editaddress" name="address" class="form-control my-form-control">
                            <span class="editaddressErr"></span>
                        </div>
                    </div>
                <?php }else { ?>
                    <div class="form-group clearfix">
                        <label class="control-label col-md-4"> <?php echo __("Street Address");?></label>
                        <div class="col-md-8">
                            <?= $this->Form->input('street_address',[
                                'type' => 'text',
                                'id'   => 'editstreet_address',
                                'class' => 'form-control',
                                'value' => $addressDetails['address'],
                                'label' => false
                            ]) ?>
                            <span class="editstreetErr"></span>
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label class="control-label col-md-4"> <?php echo __("State");?></label>
                        <div class="col-md-8">
                            <?= $this->Form->input('state_id',[
                                'type' => 'select',
                                'id'   => 'editstate_id',
                                'class' => 'form-control',
                                'options'=> $statelist,
                                'value'  => $addressDetails['state_id'],
                                'onchange' => 'editcityList();',
                                'empty'  => 'select State',
                                'label' => false
                            ]) ?>
                            <span class="editstateErr"></span>
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label class="control-label col-md-4"> <?php echo __("City");?></label>
                        <div class="col-md-8">
                            <div id="editcityList">
                                <?= $this->Form->input('city_id',[
                                    'type' => 'select',
                                    'id'   => 'editcity_id',
                                    'class' => 'form-control',
                                    'options' => $citylist,
                                    'value'  => $addressDetails['city_id'],
                                    'empty'  => 'select City',
                                    'label' => false
                                ]) ?>
                            </div>
                            <span class="editcityErr"></span>
                        </div>
                    </div>

                    <div class="form-group clearfix">
                        <label class="control-label col-md-4"> <?php echo __("Area/Zipcode");?></label>
                        <div class="col-md-8">
                            <div id="editlocationList">
                                <?= $this->Form->input('location_id',[
                                    'type' => 'select',
                                    'id'   => 'editlocation_id',
                                    'class' => 'form-control',
                                    'options' => $locaionlist,
                                    'value'  => $addressDetails['location_id'],
                                    'empty'  => 'select Area/Zipcode',
                                    'label' => false
                                ]) ?>
                            </div>
                            <span class="editlocationErr"></span>
                        </div>
                    </div>

                <?php } ?>
                <div class="col-xs-8 col-xs-offset-4">
                    <button onclick="return updateAddress()" type="submit" class="btn btn-primary view-btn">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            initialize();
        });
        // This example displays an address form, using the autocomplete feature
        // of the Google Places API to help users fill in the information.
        var placeSearch, autocomplete,autocomplete1;
        var componentForm = {
            street_number: 'short_name',
            route: 'long_name',
            locality: 'long_name',
            administrative_area_level_1: 'short_name',
            country: 'long_name',
            postal_code: 'short_name'
        };

        function initialize() {
            $.ajax({
                type   : 'POST',
                url    : jssitebaseurl+'users/getLocation',
                success: function(data){
                    if($.trim(data) != '') {
                        var code = $.trim(data);
                    }else {
                        var code = $.trim('IND');
                    }

                    var code = $("#isoCode").val();

                    // Create the autocomplete object, restricting the search
                    autocomplete1 = new google.maps.places.Autocomplete(
                        /** @type {HTMLInputElement} */ (document.getElementById('editaddress')),
                        { types: ['geocode'],componentRestrictions: {country: code} });
                    google.maps.event.addListener(autocomplete1, 'place_changed', function() {
                        fillInAddress();
                    });
                }
            });
        }

        //The START and END in square brackets define a snippet for our documentation:
        //[START region_fillform]
        function fillInAddress() {
            // Get the place details from the autocomplete object.
            var place = autocomplete1.getPlace();
            // Get each component of the address from the place details
            // and fill the corresponding field on the form.
            for (var i = 0; i < place.address_components.length; i++) {
                var addressType = place.address_components[i].types[0];
                if (componentForm[addressType]) {
                    var val = place.address_components[i][componentForm[addressType]];
                }
            }
        }
        // [END region_fillform]

        // [START region_geolocation]
        // Bias the autocomplete object to the user's geographical location,
        // as supplied by the browser's 'navigator.geolocation' object.
        function geolocate() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var geolocation = new google.maps.LatLng(
                        position.coords.latitude, position.coords.longitude);
                    autocomplete.setBounds(new google.maps.LatLngBounds(geolocation,
                        geolocation));
                });
            }
        }
    </script>
<?php
    die();
} ?>

<?php if($action == 'orderStatus') { ?>

    <div class="my_orderbox_top">
        <div class="col-sm-7 col-xs-12 no-padding-left no-xs-padding-right">
            <div class="col-sm-2 col-xs-5 no-padding-left my_order_resimg">

                <img src="<?php echo REST_LOGO ?>/<?= $orderDetails['restaurant']['id'] ?>/storeLogo/<?= $orderDetails['restaurant']['logo_name'] ?>" onerror="this.src='<?php echo BASE_URL;?>images/no_store.jpg'">
            </div>
            <div class="col-sm-9 col-xs-7 no-padding-left no-xs-padding">
                <div class="my_order_resname"><?= $orderDetails['restaurant']['restaurant_name'] ?></div>
                <div class="my_order_resadd"><?= $orderDetails['restaurant']['contact_address'] ?></div>
            </div>
        </div>
        <div class="col-sm-5 col-xs-12 text-right no-padding-right no-xs-padding m-t-xs-15">
            <?php if($orderDetails['status'] == 'Pending') { ?>
                <div class="my_order_delivery"><?= $orderDetails['status'] ?>
                    <span class="label-warning">
                        <i class="fa fa-clock-o"></i>
                    </span>
                </div>
            <?php }else if($orderDetails['status'] != 'Pending' && $orderDetails['status'] != 'Delivered' && $orderDetails['status'] != 'Failed') { ?>
                <div class="my_order_delivery"><?= $orderDetails['status'] ?>
                    <span class="label-warning">
                        <i class="fa fa-clock-o"></i>
                    </span>
                </div>
                <?php
            }else if($orderDetails['status'] == 'Delivered') { ?>
                <div class="my_order_delivery">Delivered On : 2017-11-23 <span class="label-success"><span class="fa fa-check"></span></span>
                </div>
                <?php
            }else if($orderDetails['status'] == 'Failed') { ?>
                <div class="my_order_delivery"><?= $orderDetails['status'] ?>
                    <span class="label-danger">
                        <i class="fa fa-close"></i>
                    </span>
                </div>
            <?php } ?>
            <div class="my_order_id"><?= $orderDetails['order_number'] ?> | <?= date('Y-m-d h:i A',strtotime($orderDetails['created'])) ?></div>
        </div>
    </div>

    <div class="my_orderbox_bottom">
        <span class="pull-left">
        <a href="<?= BASE_URL ?>customers/orderView/<?= $orderDetails['id'] ?>" class="btn btn-deault view-btn2"><?php echo __('VIEW');?></a>
            <?php if(!empty($orderDetails['reviews'][0]['rating'])
                && ($orderDetails['status'] == 'Delivered')){
                $rating = $orderDetails['reviews'][0]['rating'] * 20;?>
                <span class="">
        <span class="review_rating_outer">
        <span class="review_rating_grey"></span>
        <span class="review_rating_green" style="width:<?php echo $rating;?>%;"></span>
        </span>
        </span>
            <?php } else {
                if($orderDetails['status'] == 'Delivered') { ?>
                    <a href="javascript:;" class="btn btn-deault review-btn2" onclick="reviewPopup(<?= $orderDetails['id']?>);"><?php echo __('REVIEW');?></a>
                <?php }
            }?>
        </span>
        <span class="my_order_total pull-right"><?php echo __('Total');?>: <?= $siteSettings['site_currency'] ?> <?= number_format($orderDetails['order_grand_total'],2) ?></span>
    </div>
<?php die(); } ?>