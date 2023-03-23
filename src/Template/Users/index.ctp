<div class="banner">
    <div class="banner-content">
        <div class="container">
          <div class="col-md-8 col-sm-10 col-md-offset-2 col-sm-offset-1 no-padding clearfix">
              <?php
                  echo $this->Form->input('bySearch', [
                      'id' => 'bySearch',
                      'type' => 'hidden',
                      'class' => 'form-horizontal',
                      'value'=> SEARCHBY
                  ]);
              ?>

             <?php if(SEARCHBY != 'Google') { ?>
                    <div class="col-md-5 col-sm-5 searchbox no-xs-padding">
                        <?= $this->Form->input('city_id',[
                            'type' => 'select',
                            'id'   => 'city_id',
                            'class' => 'selectpicker',
                            'options'=> $citylist,
                            'empty'  =>  __('Select Your City'),
                            'onchange' => 'locationList();',
                            'data-live-search' => 'true',
                            'label' => false
                        ]) ?>
                        <span class="cityErr"></span>
                        <i class="fa fa-caret-down" aria-hidden="true"></i>
                    </div>

                    <div class="col-md-4 col-sm-4 searchbox no-padding-left no-xs-padding">
                        <div id="locationList">
                            <?= $this->Form->input('location_id',[
                                'type' => 'select',
                                'id'   => 'location_id',
                                'class' => 'selectpicker',
                                'empty'  => __('Select Your Area/Zipcode'),
                                'data-live-search' => 'true',
                                'label' => false
                            ]) ?>
                        </div>
                        <span class="locateErrr"></span>
                        <i class="fa fa-caret-down" aria-hidden="true"></i>
                    </div>
                <?php }else { ?>
                    <div class="col-md-9 col-sm-9 col-xs-12 no-padding home_searchbox m-b-10 pul-right">
                        <input id="searchbox" class="form-control" placeholder="<?php echo __('Type delivery location'); ?>">
                        <span onclick="return getLocation()" class="loacte_img"><span class="locatemeicon"></span> <span id="get-mylocation" class="loacte_txt"><?php echo __('Locate Me'); ?></span></span>
                    </div>
                <?php } ?>
                <div class="col-md-3 col-sm-3 col-xs-12 no-padding no-xs-padding pul-left">
                    <button onclick="return goToSearch()" type="button" class="btn btn-default find-rest <?php if(SEARCHBY == 'Google') { ?>find-res-home <?php } ?>"><?php echo __('FIND RESTAURANT'); ?></button>
                </div>
                <div class="col-xs-12 banner-text"><?php echo __('We deliver to your doorstep'); ?></div>
            </div>
        </div>
    </div>
</div>

<section id="hiw_section" class="howitworks hidden-xs">
    <div class="container">
        <h2><?php echo __('HOW IT WORKS'); ?></h2>
        <div class="col-md-3 col-sm-3 col-xs-12 pul-right">
            <div class="pannel-image home-searchlogo">
            </div>
            <h3 class="panel-title"><?php echo __('DISCOVER'); ?></h3>
            <p class="pannel-p"><?php echo __('Find Your Local restaurants');?></p>
        </div>
        <div class="col-md-3 col-sm-3 col-xs-12 pul-right">
            <div class="pannel-image home-chooseres">
            </div>
            <h3 class="panel-title"><?php echo __('ADD YOUR MENU');?></h3>
            <p class="pannel-p"><?php echo __('Choose Your favourite Menu');?></p>
        </div>
        <div class="col-md-3 col-sm-3 col-xs-12 pul-right">
            <div class="pannel-image home-placeholder">
            </div>
            <h3 class="panel-title"><?php echo __('PLACE ORDER');?></h3>
            <p class="pannel-p"><?php echo __('And Pay by Card Or Cash');?></p>
        </div>
        <div class="col-md-3 col-sm-3 col-xs-12 pul-right">
            <div class="pannel-image home-delivery">
            </div>
            <h3 class="panel-title"><?php echo __('GET DELIVERED');?></h3>
            <p class="pannel-p"><?php echo __('And enjoy with your food');?></p>
        </div>
    </div>
</section>

<section class="partener-with-us hidden-xs">
    <div class="container">
        <div class="single-item">
            <div class="col-sm-5 col-md-4 col-md-offset-1 no-padding pul-right">
                <div class="app_title"><?php echo __('CUSTOMER APP');?></div>
                <h1 class="foor-ord-head"><?php echo __('Online Ordering for');?> <br> <?php echo __('  ');?></h1>
                <p class="ord-del-cont"><?php echo __('Bring your restaurant online and allow your customers to pre-order takeaways and deliveries for their next meal');?></p>
                <div class="down-app">
                    <a class="sprite-androidapp" href="https://play.google.com/store/apps/details?id=com.foodorderingsystem.app"></a>
                    <a class="sprite-iosapp" href="https://itunes.apple.com/us/app/comeneat-driver-app/id1161719610?mt=8"></a>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-sm-offset-1 col-md-ffset-1 text-right home-app pul-left">
                <img src="images/mobile-4.png">
            </div>
        </div>
    </div>
</section>

<section class="orange_section hidden-xs">
    <div class="container">
        <div class="single-item">
            <div class="col-sm-6 col-md-7 col-lg-8 home-app manage-res-img">
                <img src="images/mobile-with-mockup.png">
            </div>
            <div class="col-sm-6 col-md-5 col-lg-4 pull-right pul-left">
                <div class="orange_content_part">
                    <div class="app_title"><?php echo __('RESTAURANT APP');?></div>
                    <h1 class="foor-ord-head"><?php echo __('Manage your restaurant');?> <br> <?php echo __('anytime anywhere');?></h1>
                    <p class="ord-del-cont"><?php echo __('Bring your restaurant online and allow your customers to pre-order takeaways and deliveries for their next meal.');?></p>
                    <div class="down-app text-right">
                        <a class="sprite-androidapp" href="https://play.google.com/store/apps/details?id=roamsoft.comeneatorder.project&hl=en"></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="live-tracking hidden-xs">
    <div class="container">
        <div class="single-item">
            <div class="col-md-offset-1 col-md-12 col-sm-12 col-md-offset-0 no-padding">
                <div class="col-sm-5 col-md-5 col-md-offset-1 col-lg-4  col-lg-offset-1 pul-right">
                    <div class="app_title customer-app-new"><?php echo __('CUSTOMER APP');?></div>
                    <h1 class="foor-ord-head"><?php echo __('Live Order Tracking');?> <br> <?php echo __('for your customers');?></h1>
                    <p class="ord-del-cont"><?php echo __('Through our system, Customer can <br> track driver location instanly for their orders');?></p>
                    <div class="down-app">
                        <a class="sprite-androidapp" href="https://play.google.com/store/apps/details?id=com.comeneat.grocerydriver"></a>
                        <a class="sprite-iosapp" href="https://itunes.apple.com/us/app/comeneat-driver-app/id1161719610?mt=8"></a>
                    </div>
                </div>
                <div class="col-sm-7 col-md-6 col-lg-7 text-center pul-left">
                    <img src="images/mobile-3.gif">
                </div>
            </div>
        </div>
    </div>
</section>

<section class="get-start hidden-xs">
    <div class="container">
        <div class="col-xs-12">
            <h5 class="add-your-resta"><?php echo __('GET STARTED');?></h5>
            <p class="add-para"><?php echo __('Join the thousands of other restaurants who benefit <br> from having their menus on');?> <?= $siteSettings['site_name'] ?></p>
        </div>
        <div class="col-xs-12 text-center">
            <a class="signuptext" href="<?= BASE_URL ?>restaurantSignup"><?php echo __('SIGNUP NOW');?></a>
        </div>
    </div>
</section>

<script>
    $(document).ready(function () {
        initialize();
        //getLocation();
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
                //establishment

                // Create the autocomplete object, restricting the search
                autocomplete1 = new google.maps.places.Autocomplete(
                    /** @type {HTMLInputElement} */ (document.getElementById('searchbox')),
                    { types: ['geocode'],componentRestrictions: {country: code} });
                google.maps.event.addListener(autocomplete1, 'place_changed', function() {
                    fillInAddress();
                });
            }
        });
    }

    // The START and END in square brackets define a snippet for our documentation:
    // [START region_fillform]
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

        var searchLocation = $("#searchbox").val();
        var bySearch = $.trim($("#bySearch").val());

        if(searchLocation != '') {
            $.ajax({
                type   : 'POST',
                url    : jssitebaseurl+'users/search',
                data   : {searchLocation:searchLocation},
                success: function(data){
                    window.location.href = jssitebaseurl+'searches';
                }
            });return false;
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

    function goToSearch() {

        $(".error").html('');
        var bySearch = $.trim($("#bySearch").val());
        var location = $("#searchbox").val();
        var city_id = $("#city_id").val();
        var location_id = $("#location_id").val();


        if(bySearch == 'Google' && location == '') {
            $(".locationErr").addClass('error').html('Please select your location');
            $("#searchbox").focus();
            return false;
        }else if(bySearch != 'Google' && city_id == '') {
            $(".cityErr").addClass('error').html('Please select your city');
            $("#city_id").focus();
            return false;

        }else if(bySearch != 'Google' && location_id == '') {
            $(".locateErrr").addClass('error').html('Please select your area or postcode');
            $("#location_id").focus();
            return false;
        }else if(bySearch != 'Google') {
            $.ajax({
                type   : 'POST',
                url    : jssitebaseurl+'users/search',
                data   : {city_id:city_id,location_id:location_id},
                success: function(data){
                    window.location.href = jssitebaseurl+'searches';
                }
            });return false;
        }else {
            $.ajax({
                type   : 'POST',
                url    : jssitebaseurl+'users/search',
                data   : {searchLocation:location},
                success: function(data){
                    window.location.href = jssitebaseurl+'searches';
                }
            });return false;
        }
    }

    function locationList() {
        var city_id = $.trim($("#city_id").val());
        var bySearch = $.trim($("#bySearch").val());

        if(city_id != ''){
            $.ajax({
                type   : 'POST',
                url    : jssitebaseurl+'users/ajaxaction',
                data   : {city_id:city_id, bySearch:bySearch, action: 'getLocation'},
                success: function(data){
                    $('#locationList').html(data);
                    $(".selectpicker").selectpicker();
                    return false;
                }
            });
            return false;
        }
    }

    function getLocation() {

        /* HTML5 Geolocation */
        navigator.geolocation.getCurrentPosition(
            function( position ){ // success cb

                /* Current Coordinate */
                var lat = position.coords.latitude;
                var lng = position.coords.longitude;
                var google_map_pos = new google.maps.LatLng( lat, lng );

                /* Use Geocoder to get address */
                var google_maps_geocoder = new google.maps.Geocoder();
                google_maps_geocoder.geocode(
                    { 'latLng': google_map_pos },
                    function( results, status ) {

                        if ( status == google.maps.GeocoderStatus.OK && results[0] ) {
                            $("#searchbox").val(results[0].formatted_address);
                            var searchLocation = $("#searchbox").val();
                            var bySearch = $.trim($("#bySearch").val());

                            if(searchLocation != '') {
                                $.ajax({
                                    type   : 'POST',
                                    url    : jssitebaseurl+'users/search',
                                    data   : {searchLocation:searchLocation},
                                    success: function(data){
                                        window.location.href = jssitebaseurl+'searches';
                                    }
                                });return false;
                            }
                        }
                    }
                );
            }
        );
    }
</script>