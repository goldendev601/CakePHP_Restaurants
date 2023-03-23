<div class="container">
    <section class="main_wrapper">
        <div class="banner-carousel">
            <div id="myCarousel" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    <?php if(!empty($bannerLists)) {
                        foreach($bannerLists as $key => $value) { ?>
                            <li data-target="#myCarousel" data-slide-to="<?= $key ?>" class="<?= ($key == 0) ? 'active' : '' ?>"></li>
                            <?php
                        }
                    } ?>
                    <!--<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                    <li data-target="#myCarousel" data-slide-to="1"></li>
                    <li data-target="#myCarousel" data-slide-to="2"></li>-->
                </ol>
                <div class="carousel-inner">
                    <?php if(!empty($bannerLists)) {
                        foreach($bannerLists as $key => $value) { ?>
                            <div class="item <?= ($key == 0) ? 'active' : '' ?>">
                                <img src="<?= $value['banner_image'] ?>">
                            </div>
                            <?php
                        }
                    } ?>

                </div>
            </div>
        </div>
        <div class="filter-wrapper-outer">
            <div class="row">
                <div class="col-sm-4 col-xs-12 m-b-xs-15 pul-right">
                    <label for="type_offers" class="filter-wrapper">
                        <input type="checkbox" value="offers" id="type_offers" name="resTypes" class="resTypes hide">
                        <img alt="offers" src="images/offers-image.png">
                        <span class="filter-wrapper-mask">
                             <span class="filter-wrapper-head"><?php echo __('Offers Near You');?></span>
                             <span class="filter-wrapper-cont"><?= $offerCount ?> Restaurants</span>
                         </span>
                    </label>
                </div>
                <div class="col-sm-4 col-xs-12 m-b-xs-15 pul-right">
                    <label for="type_delivery" class="filter-wrapper">
                        <input type="checkbox" value="delivery" id="type_delivery" name="resTypes" class="resTypes hide">
                        <img alt="Delivery" src="images/delivery-image.png">
                        <span class="filter-wrapper-mask">
                     <span class="filter-wrapper-head"><?php echo __('Delivery Restaurants');?></span>
                     <span class="filter-wrapper-cont"><?= $deliveryCount; ?> Restaurants</span>
                     </span>
                    </label>
                </div>
                <div class="col-sm-4 col-xs-12 m-b-xs-15 pul-right">
                    <label for="type_collection" class="filter-wrapper">
                        <input type="checkbox" value="pickup" id="type_collection" name="resTypes" class="resTypes hide">
                        <img alt="Collection" src="images/pickup-image.png">
                        <span class="filter-wrapper-mask">
                     <span class="filter-wrapper-head"><?php echo __('Collection Restaurants');?></span>
                     <span class="filter-wrapper-cont"><?= $pickupCount; ?> Restaurants</span>
                     </span>
                    </label>
                </div>
            </div>
        </div>
        <div class="col-xs-12 no-padding m-b-20">
            <div class="col-md-3 col-sm-3 col-xs-12 m-b-xs-15 no-padding pul-right">
                <div class="cuisinefil">
                    <div class="cuisinefil_head"><?php echo __('FILTER RESULTS');?></div>
                    <div class="cuisinefil_subhead">Cuisine(s)</div>
                    <ul class="cuisinefil_list">
                        <?php if(!empty($sideCuisines)) {
                            foreach ($sideCuisines as $key => $value) { ?>
                                <li>
                                    <input id="<?php echo $key ?>" type="checkbox" class="hide" value="<?php echo $key ?>">
                                    <label for="<?php echo $key ?>">

                                        <span class="pull-left pul-right"><?= $key ?></span><span class="pull-right filt_count pul-left"><?= $value ?></span></label>
                                </li>
                                <?php

                            }
                        }?>
                    </ul>
                </div>
            </div>
            <div class="col-xs-12 col-md-9 col-sm-9 no-padding pul-left no-rtlpadding-left rtl-padding-R15 no-xs-rtl-padding">
                <?php if(!empty($result)) {
                    foreach($result as $key => $value) { ?>
                        <?php
                        $offers = (!empty($value['offers'])) ? 'offers' : '';

                        ?>
                            <?php if($value['ordering_switch_status'] == 'true') { ?>
                                <div class="searchres_box col-xs-12 no-padding-right no-padding-left-xs m-b-15 no-rtlpadding-left" data-category="<?php echo $value['cuisineLists'] ?>,<?= ($value['restaurant_delivery'] == 'Yes') ? 'delivery' : '' ?>,<?= ($value['restaurant_pickup'] == 'Yes') ? 'pickup' : '' ?>,<?= $offers ?>">
                                    <a class="flower" href="<?= BASE_URL ?>menu/<?= $value['seo_url'] ?>" >
                                        <div class="right-rest-info">
                                        
                                            <div class="rest-details">
                                                <div class="col-md-2 col-sm-12 col-xs-12 no-xs-padding no-padding-left pul-right no-rtlpadding-right">
                                                    <div class="res-img-box">
                                                        <span class="res-logo-helper"></span>

                                                        <img src="<?php echo BASE_URL.'uploads/storeLogos/'.$value['logo_name'] ?>" height="100" width="150" onerror="this.src='<?php echo BASE_URL;?>webroot/images/no_store.jpg'">

                                                    </div>
                                                </div>
                                                <div class="col-md-8 col-sm-12 col-xs-12 no-padding pul-right">
                                                    <div class="col-xs-6 col-sm-6 no-padding-left pul-right no-xs-rtl-padding">
                                                        <div class="firstheadrestname"><?php echo $value['restaurant_name'] ?></div>
                                                        <?php
                                                        if (!empty($rewardData) && $value['reward_option'] == 'Yes'): ?>
                                                            <img class="rewardsIconWrap" src="<?php echo BASE_URL ?>images/reward-points.png">
                                                                <?php
                                                        endif;
                                                        ?>

                                                        <div class="firstheadercuis"><?php echo $value['cuisineRecord'] ?></div>
                                                    </div>
                                                    <div class="col-xs-6 col-sm-6 hidden-md hidden-lg no-xs-padding-right no-xs-rtl-padding">
                                                        <div class="rest-ratting-star text-right">
                                                            <div class="ratingstar-outer">
                                                                <span style="width:<?= $value['finalReview'] ?>%" class="ratingstar-new"></span>
                                                            </div>
                                                        </div>
                                                        <div class="restaurant-reviews pull-right pul-left"><?= $value['totalRating'] ?> <?php echo __('Reviews');?></div>

                                                    </div>
                                                    <div class="col-xs-12 no-padding no-rtlpadding-left rtl-padding-R15 no-xs-rtl-padding">
                                                        <div class="food-order-details">
                                                            <div class="no-padding cost-details">
                                                                <div class="priceing-datails"><?= $siteSettings['site_currency'] ?> <?php echo number_format($value['minimum_order'], 2) ?></div>
                                                                <p class="content-details"><?php echo __('Min. Cost');?></p>
                                                            </div>
                                                            <div class="no-padding cost-details">
                                                                <div class="priceing-datails text-right-xs"><?= $value['to_distance'] ?><?php echo __('miles');?></div>
                                                                <p class="content-details text-right-xs"><?php echo __('Distance');?></p>
                                                            </div>
                                                            <div class="clearfix visible-xs"></div>
                                                            <div class="no-padding cost-details">
                                                                <div class="priceing-datails"><?= $siteSettings['site_currency'] ?> <?= number_format($value['delivery_charge'],2) ?></div>
                                                                <p class="content-details"><?php echo __('Del. Fee');?></p>
                                                            </div>
                                                            <div class="no-padding min-cost">
                                                                <div class="time-log"><?= $value[$currentDay.'_first_opentime'] ?> - <?= $value[$currentDay.'_first_closetime'] ?></div>
                                                                <div class="time-log"><?= $value[$currentDay.'_second_opentime'] ?> - <?= $value[$currentDay.'_second_closetime'] ?></div>
                                                            </div>
                                                            <div class="no-padding min-cost xs-left">

                                                                <?php if(!empty($value['restOffers'])) { ?>

                                                                    <?php if($value['restOffers']['first_user'] == 'Y') { ?>
                                                                        <div class="offer-log" data-toggle="tooltip" data-placement="top" title="<?php echo $value['restOffers']['free_percentage']?>% off for first time over <?= $siteSettings['site_currency'] ?> <?php echo $value['restOffers']['free_price'] ?>"><i class="fa fa-usd"></i>
                                                                            <?php echo $value['restOffers']['free_percentage']?>% off for first time over <?= $siteSettings['site_currency'] ?> <?php echo $value['restOffers']['free_price'] ?>
                                                                        </div>
                                                                    <?php } ?>

                                                                    <?php if($value['restOffers']['normal'] == 'Y') { ?>
                                                                        <div class="offer-log" data-toggle="tooltip" data-placement="top" title="<?php echo $value['restOffers']['normal_percentage']?>% off for over <?= $siteSettings['site_currency'] ?> <?php echo $value['restOffers']['normal_price'] ?>"><i class="fa fa-usd"></i>
                                                                            <?php echo $value['restOffers']['normal_percentage']?>% off for over <?= $siteSettings['site_currency'] ?> <?php echo $value['restOffers']['normal_price'] ?>
                                                                        </div>
                                                                    <?php } ?>

                                                                    <?php
                                                                }else { ?>
                                                                    <span class="no-offer"><img src="<?= BASE_URL ?>images/no-offer.png">no offers</span>
                                                                <?php } ?>
                                                                <?php /*if(SEARCHBY == 'Google' && $value['free_delivery'] > 0) { */?><!--
                                                                    <div class="offer-log"><i class="fa fa-usd"></i>

                                                                        Free Delivery over <?php /*$siteSettings['site_currency'] */?> <?php /*echo $value['free_delivery']*/?>
                                                                    </div>

                                                                --><?php /*} */?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-12 col-xs-12 no-padding mobile-tab pul-right">
                                                    <div class="col-md-12 hidden-sm hidden-xs no-padding text-right">
                                                        <div class="rest-ratting-star">
                                                            <div class="ratingstar-outer">
                                                                <span style="width:<?= $value['finalReview'] ?>%" class="ratingstar-new"></span>
                                                            </div>
                                                        </div>
                                                        <div class="restaurant-reviews pull-right pul-left"><?= $value['totalRating'] ?> <?php echo __('Reviews');?></div>
                                                    </div>
                                                    <div class="col-xs-12 col-md-8 col-sm-8 m-t-5  no-padding">
                                                    </div>
                                                    <div class="col-xs-12 no-padding-right no-xs-padding m-t-20 m-t-xs-20 no-rtlpadding-left">                                                
                                                        <button href="#" class="order-now-btn"><?= (($value['currentStatus'] == 'Open') ? 'ORDER NOW' : (($value['currentStatus'] == 'PreOrder') ? 'PRE ORDER' : 'PRE ORDER')) ?>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>                                    

                                        </div>
                                    </a>
                                </div>

                            <?php }
                                 else { ?>
                                <?php } ?>
                        <?php
                    }
                }else { ?>
                    <div class="no-record">
                        No Record Founds
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
</div>

<script>
    //Filter Cuisine and restaurant types
    var $filterCheckboxes = $('input[type="checkbox"]');
    $filterCheckboxes.on('change', function() {
        var selectedFilters = {};

        $filterCheckboxes.filter(':checked').each(function() {
            if (!selectedFilters.hasOwnProperty(this.name)) {
                selectedFilters[this.name] = [];
            }
            selectedFilters[this.name].push(this.value);
        });

        // create a collection containing all of the filterable elements
        var filteredResults = $('.searchres_box');

        // loop over the selected filter name -> (array) values pairs
        $.each(selectedFilters, function(name, filterValues) {

            // filter each .flower element
            filteredResults = filteredResults.filter(function() {

                var matched = false;
                var currentFilterValues = $(this).data('category').split(',');

                // loop over each category value in the current .flower's data-category
                $.each(currentFilterValues, function(_, currentFilterValue) {

                    // if the current category exists in the selected filters array
                    // set matched to true, and stop looping. as we're ORing in each
                    // set of filters, we only need to match once

                    if ($.inArray(currentFilterValue, filterValues) != -1) {
                        console.log(filterValues);
                        matched = true;
                        return false;
                    }
                });

                // if matched is true the current .flower element is returned
                return matched;

            });
        });
        $('.searchres_box').hide().filter(filteredResults).show();
        var itemcount = $(".searchres_box:visible").length;
        if(itemcount == 0) {
            $('#storeCount').html(itemcount);
            $('#nostore').show();
        } else {
            $('#nostore').hide();
            $('#storeCount').html(itemcount);
            $('.searchres_box').hide().filter(filteredResults).show();
        }

    });

</script>

<style type="text/css">
    #order-close-alert {
        font-size: 12px;
        font: italic;
        font-style: italic;
        color: rgb(150, 0, 0);
        text-align: center;
    }
</style>
