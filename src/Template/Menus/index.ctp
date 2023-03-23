<div class="container">
    <section class="main_wrapper">
        <div class="inner_wrapper">
            <div class="col-md-8 col-sm-8 col-xs-12 no-padding-left no-xs-padding-right fos_width_100 pul-right no-rtlpadding-right rtl-padding-l15 no-xs-rtl-padding">
                <div class="restaurant-box">
                    <div class="restaurant-box-top fos_hide_part">
                        <div class="col-md-3 col-sm-3 no-padding-left hidden-xs pul-right no-rtlpadding-right">
                            <div class="res-img-box">
                                <span class="res-logo-helper"></span>
                                <?php if($restDetails['logo_name'] != '') { ?>
                                    <img src="<?php echo BASE_URL.'uploads/storeLogos/'.$restDetails['logo_name'] ?>" height="100" width="150" onerror="this.src='<?php echo BASE_URL;?>webroot/images/no_store.jpg'">
                                <?php }else { ?>
                                    <img src="<?php echo BASE_URL;?>images/no_store.jpg">
                                <?php } ?>
                            </div>
                        </div>
                        <div class="col-md-9 col-sm-9 col-xs-12 no-padding pul-left">
                            <div class="restaurant-searchname">
                                <span class="headrestname pul-right"><?php echo $restDetails['restaurant_name'] ?></span>
                                <span class="pull-right hidden-xs pul-left">
                        <span class="rating_star_big rating_star_big-block hidden-xs valigntop pul-right">
                        <span class="rating_star_big_gold" style="width:<?= $restDetails['finalReview'] ?>%"></span>
                        </span>

                        <span class="restaurant-reviews rest-reviews-right valigntop"><?php echo __('Reviews');?></span>
                        </span>
                            </div>
                            <div class="headeraddr"> <?= $restDetails['contact_address'] ?> </div>
                            <div class="headercuis"> <?= $restDetails['cuisineLists'] ?> </div>
                            <div class="col-xs-12 no-padding visible-xs">
                                <span class="rating_star_big rating_star_big-block hidden-xs valigntop">
                                <span class="rating_star_big_gold" style="width:<?= $restDetails['finalReview'] ?>%"></span>
                                </span>
                                <span class="restaurant-reviews rest-reviews-right pull-xs-right valigntop"><?php echo __('Reviews');?></span>
                            </div>

                            <div class="restaurant-km">
                                <div class="col-xs-4 visible-xs no-padding">
                                    <!-- <img class="serach-res-image" src="<?= BASE_URL ?>images/foodbanner.jpg"> -->
                                     <?php if($restDetails['logo_name'] != '') { ?>
                                        <img class="serach-res-image" src="<?php echo BASE_URL.'uploads/storeLogos/'.$restDetails['logo_name'] ?>" height="100" width="150" onerror="this.src='<?php echo BASE_URL;?>webroot/images/no_store.jpg'">
                                    <?php }else { ?>
                                        <img src="<?php echo BASE_URL;?>images/no_store.jpg">
                                    <?php } ?>
                                </div>
                                <div class="col-xs-8 col-sm-12 no-sm-padding-left">
                                    <div class="headermenudel">
                                        <?php echo __("Del.Time");?> : <?= $restDetails['estimate_time'] ?> <?php echo __("Mins");?>
                                    </div>
                                    <div class="headermenudel">
                                       <?php echo __("Del. Fee");?>: <?php echo ($restDetails['delivery_charge'] <= 0) ? 'Free' : $siteSettings['site_currency'].' '.number_format($restDetails['delivery_charge'],2); ?>
                                    </div>
                                    <div class="headermenudel pluginLogo no-padding">
                                        <?php echo __("Distance");?> : <i class="fa fa-map-marker"></i> 
                                        <?= $restDetails['distance']; ?> <?php echo __("Miles");?>
                                    </div>
                                </div>
                            </div>
                            <?php
                            if (!empty($rewardData) && $restDetails['reward_option'] == 'Yes'): ?>
                            <div class="reward_icon_res">
                                <img src="<?php echo BASE_URL ?>images/reward-points.png">
                                    <?php echo __('Earn');?> <?= $rewardData['reward_totalpoint'];?>
                                    <?php echo __('point & Get');?> <?= $rewardData['reward_percentage'];?>% <?php echo __('off');?>.
                            </div>
                                <?php
                            endif;
                            ?>
                        </div>
                    </div>

                    <div class="newmenu_tab">
                        <ul class="menutab_ul">
                            <li><a class="active" data-href="restaurant-menu"><span class="visible-xs"><img  src="<?= BASE_URL ?>images/menulist.png"></span><span class="hidden-xs"><?php echo __('Menu');?></span></a></li>
                            <li class="fos_hide_part"><a data-href="restaurant-info"><span class="visible-xs"><img src="<?= BASE_URL ?>images/infolist.png"></span><span class="hidden-xs"><?php echo __('Info');?></span></a></li>
                            <li class="fos_hide_part"><a data-href="restaurant-reviews"><span class="visible-xs"><img src="<?= BASE_URL ?>images/reviewlist.png"></span><span class="hidden-xs"><?php echo __('Reviews');?></span></a></li>
                            <li><a data-href="restaurant-offers"><span class="visible-xs"><img src="<?= BASE_URL ?>images/offerlist.png"></span><span class="hidden-xs"><?php echo __('Offers');?></span></a></li>
                            <?php if($restDetails['restaurant_booktable'] == 'Yes') { ?>
                                <li><a data-href="restaurant-bookatable"><span class="visible-xs"><img src="<?= BASE_URL ?>images/tablelist.png"></span> <span class="hidden-xs"><?php echo __('Book a table');?></span></a></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
                
                <div class="restaurant-tab-content">
                    <div class="tab-content" id="restaurant-menu">
                        <div class="fos_menu_cata col-md-3 col-sm-3 col-xs-12 no-padding pul-right">
                            <div class="search_category visible-xs">
                                <span><?php echo __("Search By Categories");?></span>
                                <span class="pull-right"><i class="fa fa-angle-down"></i></span>
                            </div>
                            <div class="cuisinefil">
                                <div class="res_cuisinefil_head"><?php echo __('Categories');?></div>
                                <ul class="cuisinefil_list">
                                    <li><a class="sideAllCuisine"><span class="pull-left pul-right">
                                    <?php echo __("All");?></span></a></li>
                                    <?php if(!empty($categoryList)) {
                                        foreach ($categoryList as $key => $value) { ?>
                                            <li><a class="sideCuisine" id="<?= $key ?>"><span class="pull-left pul-right"><?= $value ?></span></a></li>
                                            <?php
                                        }
                                    } ?>
                                </ul>
                            </div>
                        </div>

                        <div class="fos_menu_item col-md-9 col-sm-9 col-xs-12 no-padding-right no-xs-padding m-t-xs-20 pul-left no-rtlpadding-left rtl-padding-R15 no-xs-rtl-padding">
                            <div class="searchMenudet fos_hide_part">
                                <input type="search" class="searchInput" placeholder="I'm looking for...">
                                <a class="searchMenuFormClick" href=""></a>
                            </div>
                            <div class="food-item-div">
                                <?php if(!empty($restDetails['restaurant_menus'])) {
                                    $catId = '';
                                    foreach ($restDetails['restaurant_menus'] as $key => $value) {
                                        if (isset($categoryList[$value['category_id']]) && $categoryList[$value['category_id']] != '') {
                                            ?>
                                            <div class="signle-food-item-div sample menu_category_<?= $value['category_id'] ?> ">
                                                <?php if ($value['category_id'] != $catId) { ?>
                                                    <div class="food-item-head"><?= $categoryList[$value['category_id']] ?></div>
                                                <?php } ?>
                                                <div class="food-item-list">
                                                    <div class="food-item-border">
                                                        <div class="col-md-9 col-sm-8 col-xs-8 no-padding pul-right">
                                                            <div class="food-item-name"><?= $value['menu_name'] ?></div>
                                                            <div class="product__detail-category"><?= $value['menu_description'] ?></div>
                                                        </div>
                                                        <div class="col-md-3 col-sm-4 col-xs-4 text-right no-padding pul-left">
                                                            <div class="food-item-name food-item-pricename"><?= $siteSettings['site_currency'] ?> <?= number_format($value['menu_details'][0]['orginal_price'], 2) ?>
                                                            </div>
                                                            <div class="plus_btn">
                                                                <?php if ($value['price_option'] == 'single' && $value['menu_addon'] == 'No') { ?>
                                                                    <a>
                                                                        <img onclick="addToCart(<?= $value['id'] ?>,'add')"
                                                                             src="<?= BASE_URL ?>images/plus.png">
                                                                    </a>
                                                                <?php } else { ?>
                                                                    <a>
                                                                        <img onclick="getMenuDetails(<?= $value['id'] ?>)"
                                                                             src="<?= BASE_URL ?>images/plus.png">
                                                                    </a>
                                                                <?php } ?>
                                                            </div>          
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                            $catId = $value['category_id'];
                                        }
                                    }
                                } ?>
                            </div>
                        </div>
                    </div>

                    <div class="tab-content" id="restaurant-info" style="display:none;">
                        <div class="panel panel-default">
                            <div class="rest_about">
                                <h3><?php echo __('About Restaurant');?></h3>
                            </div>
                            <div class="panel-body">
                                <p><?= $restDetails['restaurant_about'] ?></p>
                            </div>
                        </div>
                      
                        <div class="panel panel-default">
                            <div class="rest_about">
                                <h3><?php echo __('Get Directions');?></h3>
                            </div>
                            <div class="panel-body">
                                <div class="info_map">
                                    <div id="map" style="height:250px;"><?php echo __('Map');?></div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="rest_about">
                                <h3><?php echo __('Opening Hours');?></h3>
                            </div>
                            <div class="panel-body">
                                <div class="info_days">
                                    <div class="col-sm-12 days_cont col-xs-12">
                                        <div class="pull-left margin-b-5 pul-right"><?php echo __('Monday');?></div>
                                        <div class="pull-right pul-left">
                                            <?php if($restDetails['monday_status'] != 'Close') { ?>
                                                <p><?= $restDetails['monday_first_opentime'] ?> - <?= $restDetails['monday_first_closetime'] ?></p>
                                                <p><?= $restDetails['monday_second_opentime'] ?> - <?= $restDetails['monday_second_closetime'] ?></p>
                                            <?php }else { ?>
                                                <button class="btn btn-xs pull-right info_close" type="button"><?php echo __('Closed');
                                                    ?></button>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 days_cont col-xs-12">
                                        <div class="pull-left margin-b-5 pul-right"><?php echo __('Tuesday');?></div>
                                        <div class="pull-right pul-left">
                                            <?php if($restDetails['tuesday_status'] != 'Close') { ?>
                                                <p><?= $restDetails['tuesday_first_opentime'] ?> - <?= $restDetails['tuesday_first_closetime'] ?></p>
                                                <p><?= $restDetails['tuesday_second_opentime'] ?> - <?= $restDetails['tuesday_second_closetime'] ?></p>
                                            <?php }else { ?>
                                                <button class="btn btn-xs pull-right info_close" type="button"><?php echo __('Closed');
                                                    ?></button>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 days_cont col-xs-12">
                                        <div class="pull-left margin-b-5 pul-right"><?php echo __('Wednesday');?></div>
                                        <div class="pull-right pul-left">
                                            <?php if($restDetails['wednesday_status'] != 'Close') { ?>
                                                <p><?= $restDetails['wednesday_first_opentime'] ?> - <?= $restDetails['wednesday_first_closetime'] ?></p>
                                                <p><?= $restDetails['wednesday_second_opentime'] ?> - <?= $restDetails['wednesday_second_closetime'] ?></p>
                                            <?php }else { ?>
                                                <button class="btn btn-xs pull-right info_close" type="button"><?php echo __('Closed');
                                                    ?></button>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 days_cont col-xs-12">
                                        <div class="pull-left margin-b-5 pul-right"><?php echo __('Thursday');?></div>
                                        <div class="pull-right pul-left">
                                            <?php if($restDetails['thursday_status'] != 'Close') { ?>
                                                <p><?= $restDetails['thursday_first_opentime'] ?> - <?= $restDetails['thursday_first_closetime'] ?></p>
                                                <p><?= $restDetails['thursday_second_opentime'] ?> - <?= $restDetails['thursday_second_closetime'] ?></p>
                                            <?php }else { ?>
                                                <button class="btn btn-xs pull-right info_close" type="button"><?php echo __('Closed');
                                                    ?></button>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 days_cont col-xs-12">
                                        <div class="pull-left margin-b-5 pul-right"><?php echo __('Friday');?></div>
                                        <div class="pull-right pul-left">
                                            <?php if($restDetails['friday_status'] != 'Close') { ?>
                                                <p><?= $restDetails['friday_first_opentime'] ?> - <?= $restDetails['friday_first_closetime'] ?></p>
                                                <p><?= $restDetails['friday_second_opentime'] ?> - <?= $restDetails['friday_second_closetime'] ?></p>
                                            <?php }else { ?>
                                                <button class="btn btn-xs pull-right info_close" type="button"><?php echo __('Closed');
                                                    ?></button>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 days_cont col-xs-12">
                                        <div class="pull-left margin-b-5 pul-right"><?php echo __('Saturday');?></div>
                                        <div class="pull-right pul-left">
                                            <?php if($restDetails['saturday_status'] != 'Close') { ?>
                                                <p><?= $restDetails['saturday_first_opentime'] ?> - <?= $restDetails['saturday_first_closetime'] ?></p>
                                                <p><?= $restDetails['saturday_second_opentime'] ?> - <?= $restDetails['saturday_second_closetime'] ?></p>
                                            <?php }else { ?>
                                                <button class="btn btn-xs pull-right info_close" type="button"><?php echo __('Closed');
                                                    ?></button>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 days_cont col-xs-12">
                                        <div class="pull-left margin-b-5 pul-right"><?php echo __('Sunday');?></div>
                                        <div class="pull-right pul-left">
                                            <?php if($restDetails['sunday_status'] != 'Close') { ?>
                                                <p><?= $restDetails['sunday_first_opentime'] ?> - <?= $restDetails['sunday_first_closetime'] ?></p>
                                                <p><?= $restDetails['sunday_second_opentime'] ?> - <?= $restDetails['sunday_second_closetime'] ?></p>
                                            <?php }else { ?>
                                                <button class="btn btn-xs pull-right info_close" type="button"><?php echo __('Closed');
                                                    ?></button>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-content" id="restaurant-reviews" style="display:none;">
                        <div class="res_reviews">
                            <div class="col-xs-12">
                                <?php
                                if(!empty($restDetails['reviews'])) {
                                    foreach($restDetails['reviews'] as $key => $val){ ?>
                                        <div class="review_contWrapper">
                                            <div class="col-sm-10 col-xs-7 no-padding">
                                                <span><strong><?php echo $val['customer_name']; ?></strong></span>
                                            </div>
                                            <div class="col-sm-2 col-xs-5 no-padding"><span class="rating_star rating_star_info pull-right no-margin"><span style="width:<?= $val['ratingCount'] ?>%" class="rating_star_gold"></span></span>
                                            </div>
                                            <div class="col-xs-12 no-padding m-t-10 text-justify"><?php echo $val['message'];?></div>
                                            <div class="col-xs-12 no-padding m-t-10 text-right">
                                                <i><?php echo date('Y-m-d',strtotime($val['created']));?></i>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                }else {?>
                                    <div class="noreview_contWrapper">
                                        <?php echo __('No Record Found');?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <div class="tab-content" id="restaurant-offers" style="display:none;">
                        <div class="res_offers">
                            <div class="col-xs-12  m-t-20">
                                <?php if(!empty($restDetails['offerLists'])) {?>
                                    <?php if($restDetails['offerLists']['first_user'] == 'Y') { ?>
                                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                            <div class="coupons-section1">
                                                <div class="coupons-section2">
                                                    <h4> <?= $restDetails['offerLists']['free_percentage'] ?>% <span class="offer"><?php echo __("OFF");?></span></h4>
                                                    <div class="coupons-section3">
                                                        <p class="text-center">
                                                            <strong><?php echo __("Offer Type");?> : <?php echo __("FirstUser");?></strong>
                                                        </p>
                                                        <p class="text-center">
                                                          <strong><?php echo __("Validity");?></strong></p>
                                                        <p class="text-center">
                                                          <strong><?= $restDetails['offerLists']['offer_from'] ?> <span class="to">to</span> <?= $restDetails['offerLists']['offer_to'] ?></strong>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <?php if($restDetails['offerLists']['normal'] == 'Y') { ?>
                                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                            <div class="coupons-section1">
                                                <div class="coupons-section2">
                                                    <h4> <?= $restDetails['offerLists']['normal_percentage'] ?> % <span class="offer"><?php echo __("OFF");?></span></h4>
                                                    <div class="coupons-section3">
                                                        <p class="text-center">
                                                            <strong><?php echo __("Offer Type");?> : <?php echo __("Normal");?></strong>
                                                        </p>
                                                        <p class="text-center">
                                                          <strong><?php echo __("Validity");?></strong></p>
                                                        <p class="text-center">
                                                        <strong><?= $restDetails['offerLists']['offer_from'] ?> <span class="to">to</span> <?= $restDetails['offerLists']['offer_to'] ?></strong>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <?php if($restDetails['free_delivery'] > 0) { ?>
                                        <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                            <div class="coupons-section1">
                                                <div class="coupons-section2">
                                                    <h4><span class="offer"><?php echo __('FREE DELIVERY');?></span></h4>
                                                    <div class="coupons-section3">
                                                        <p class="text-center">
                                                         <strong><?php echo __("Free Delivery over");?> <?= $siteSettings['site_currency'] ?> <?= number_format($restDetails['free_delivery'],2) ?>
                                                         </strong>
                                                         </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <?php
                                }else { ?>
                                   <div class="col-xs-12 m-b-20 text-center"><?php echo __("No Offers");?></div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <div class="tab-content" id="restaurant-bookatable" style="display:none;">
                        <div class="res_booktable">
                            <div class="col-xs-12">
                                <?=
                                $this->Form->create('BookaTable', [
                                    'class' => 'bookRequest form-horizontal',
                                    'method' => 'post',
                                    'id' => 'bookaTableForm'
                                ]); ?>
                                <?=
                                $this->Form->input('resId', [
                                    'type' => 'hidden',
                                    'id' => 'resid',
                                    'value'=>$restDetails['id']
                                ]); ?>
                                <?=
                                $this->Form->input('cartrestId', [
                                    'type' => 'hidden',
                                    'id' => 'cartrestId',
                                    'value'=>$cartRestaurantId
                                ]); ?>
                                <h3 class="text-center"><i aria-hidden="true" class="fa fa-book"></i> <?php echo __('Book a table');?></h3>
                                <div class="col-xs-12 text-center" style="display:none;" id="succDiv">
                                    <span class="bookaTableSuccess alert alert-success"><?php echo __('Your booking request sent successfully');?></span>
                                </div>
                                <div class="col-md-10 col-sm-10 col-xs-12 padding-t-b-20">
                                    <div class="form-group clearfix">
                                        <label class="col-sm-4 control-label name pul-right" for="guestCount">
                                            <?php echo __('Guest Count');?></label>
                                        <div class="col-sm-8 pul-left"><?php
                                            $guests=['1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10','11'=>'11'];
                                            echo $this->Form->input('guest_count',[
                                                'type'  => 'select',
                                                'class'   => 'form-control bookRequest',
                                                'options' => $guests,
                                                'id'=>'BookaTableGuestCount',
                                                'empty'   => __('Select Guest'),
                                                'label'   => false
                                            ]); ?>
                                            <span id="bookError" class="error"></span>
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <label class="col-sm-4 control-label name pul-right" for="BookaTableBookingDate">
                                            <?php echo __('Date/Time');?> </label>
                                        <div class="col-sm-8 no-padding pul-left">
                                            <div class="col-sm-6 col-xs-12 calender-field">
                                                <?=
                                                $this->Form->input('booking_date',
                                                    ['class' => 'form-control bookRequest bookdate',
                                                        'label'   => false,
                                                        'maxlength'=>250,
                                                        'id'=>'BookaTableBookingDate',
                                                        'value' => $currentDate,
                                                        'readonly'=>true
                                                    ]); ?>
                                                <i class="fa fa-calendar"></i>
                                                <span id="bookError1" class="error"></span>
                                            </div>
                                            <div class="col-sm-6 col-xs-12" id="timeList"><?=
                                                $this->Form->input('booking_time',[
                                                    'type'    => 'select',
                                                    'class'   => 'form-control bookRequest',
                                                    'options' => !empty($array_of_time) ? $array_of_time:'Closed',
                                                    'empty'   => __('Select Time'),
                                                    'id' => 'getDateTime',
                                                    'value' => !empty($array_of_time) ? $array_of_time:'Closed',
                                                    'label'   => false
                                                ]); ?>
                                                <span id="bookError2" class="error"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <label class="col-sm-4 control-label name pul-right" for="BookaTableCustomerName">
                                            <?php echo __('Name');?> </label>
                                        <div class="col-sm-8 pul-left"><?=
                                            $this->Form->input('customer_name',[
                                                'class' => 'form-control bookRequest',
                                                'id'=>'BookaTableCustomerName',
                                                'maxlength' => 250,
                                                'label'   => false
                                            ]); ?>
                                            <span id="bookError3" class="error"></span>
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <label class="col-sm-4 control-label name pul-right" for="BookaTableCustomerEmail">
                                            <?php echo __('Email');?> </label>
                                        <div class="col-sm-8 pul-left"><?=
                                            $this->Form->input('booking_email',[
                                                'class' => 'form-control bookRequest',
                                                'id'=>'BookaTableBookingEmail',
                                                'maxlength' => 250,
                                                'label'   => false
                                            ]); ?>
                                            <span id="bookError4" class="error"></span>
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <label class="col-sm-4 control-label name pul-right" for="bookingPhone">
                                            <?php echo __('Phone');?></label>
                                        <div class="col-sm-8 pul-left"><?=
                                            $this->Form->input('booking_phone',[
                                                'class' => 'form-control bookRequest',
                                                'id'=>'BookaTableBookingPhone',
                                                'maxlength' => 250,
                                                'label'   => false
                                            ]); ?>
                                            <span id="bookError5" class="error"></span>
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <label class="col-sm-4 control-label name pul-right" for="bookingInstruction">
                                            <?php echo __('Your Instructions');?></label>
                                        <div class="col-sm-8 pul-left"> <?=
                                            $this->Form->input('booking_instruction',[
                                                'class' => 'form-control bookRequest',
                                                'label' => false,
                                                'id' => 'BookaTableBookingInstruction',
                                                'type'  => 'textarea'
                                            ]); ?>
                                            <span id="bookError6" class="error"></span>
                                        </div>
                                    </div>
                                    <div class="form-group clearfix">
                                        <label class="col-sm-4 control-label pul-right"></label>
                                        <div class="col-sm-8 m-b-10 pul-left">
                                            <?=
                                            $this->Form->input('Submit',[
                                                'class' => 'btn btn-primary submit-book',
                                                'label' => false,
                                                'value'=>'Submit',
                                                'type'  => 'button',
                                                'onclick' => 'return validateBook();'
                                            ]); ?><i class="fa fa-check submit-check"></i>
                                        </div>
                                    </div>
                                    <?= $this->Form->end(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-4 col-xs-12 no-padding cart-outer-wrapper m-t-xs-20 pul-left">
                <div id="cart_wrapper" class="cart-wrapper">
                    <form>
                        <div class="cart-innerwrapper">
                            <div class="cart-hide-wrap cart-box">
                                <div class="col-md-12 text-left warehouse_head"><?php echo __('Your Cart');?>
                                </div>
                            </div>
                            <div class="pic-del text-center cart-hide-wrap">
                                <?php if($restDetails['restaurant_pickup'] == 'Yes') { ?>
                                    <div class="pickup-div">
                                        <input type="radio" id="res-pickup" name="deliveryType" onclick="return orderType('pickup');" value="pickup" <?= ($orderType == 'pickup' || $restDetails['restaurant_delivery'] != 'Yes') ? 'checked' : '' ?>>
                                        <label for="res-pickup"><?php echo __('Pickup');?></label>
                                    </div>
                                <?php } ?>
                                <?php

                                if($restDetails['delivery_switch_status'] == 'true') {
                                    if($restDetails['restaurant_delivery'] == 'Yes') { ?>
                                        <div class="delivery-div">
                                            <input type="radio" id="res-delivery" name="deliveryType" <?= ($orderType != 'pickup') ? 'checked' : '' ?> onclick="return orderType('delivery');" value="delivery">
                                            <label for="res-delivery"><?php echo __('Delivery');?></label>
                                        </div>
                                    <?php } 
                                } else {
                                }
                                ?>
                            </div>
                            <div class="pickup-time-hide-wrap cart-box">
                                <div class="col-md-12 text-left warehouse_head"><?php echo __('Pickup Time');?>
                                </div>
                            </div>                            
                            <div class="pic-del text-center cart-hide-wrap" id="pickup-div" style="background-color: rgb(0, 128, 0);">
                                <?php if($restDetails['restaurant_pickup'] == 'Yes') { ?>
                                    <div class="pickup-time-div" style="height: 40px;">                                        
                                        <span style="font-weight: 700; color: white; font-size: 20px;">
                                            <strong>
                                            <?php 
                                                date_default_timezone_set($restDetails['timezoneList']);

                                                $date = date('H:i');
                                                $val_minimum_pickup_time = intval($restDetails['minimum_pickup_time']);
                                                $val = '+'.$val_minimum_pickup_time.' minute';
                                                $due_date = date('g:i A', strtotime($val, strtotime($date)));
                                                echo $due_date;
                                            ?>
                                                
                                            </strong> Pickup Time 
                                        </span>
                                        
                                    </div>
                                <?php } ?>                              
                            </div>

                            <div id="cartDetails">
                                <?php if(!empty($cartsDetails)) { ?>
                                    <div class="cart-hide-wrap cart-items-scroll">
                                        <?php
                                        foreach ($cartsDetails as $key => $value) { ?>
                                            <div class="menu-item-wrapper">
                                                <div class="menu-item-sno"><?php echo $key+1; ?></div>
                                                <div class="menu-item-middle">
                                                    <div class="menu-item-name-wrap">
                                                        <div class="menu-item-name"><?= $value['menu_name'] ?></div>
                                                        <div class="subAddons">
                                                            <?php echo $value['subaddons_name'] ?>
                                                        </div>
                                                        <div class="menu-item-text"></div>
                                                    </div>
                                                    <div class="menu-item-quantity">
                                                        <a  class="menu-item-qty-icon" onclick="updateToCart(<?= $value['id'] ?>,'remove')">
                                                            <i class="icon icon-minus"></i>
                                                        </a>
                                                        <span class="quantitycount text-center"><?= $value['quantity'] ?></span>
                                                        <a class="menu-item-qty-icon" onclick="updateToCart(<?= $value['id'] ?>,'add')">
                                                            <i class="icon icon-plus"></i>
                                                        </a>
                                                    </div>
                                                    <div class="menu-item-delete"><i onclick="updateToCart(<?= $value['id'] ?>,'delete')" class="icon icon-close"></i></div>
                                                    <div class="menu-item-amount"><?= $siteSettings['site_currency'] ?> <?= number_format($value['total_price'],2) ?></div>
                                                </div>
                                            </div>
                                            <?php
                                        } ?>
                                        <div class="bill-section">
                                            <div class="subtotal">
                                                <span class="subtotal-txt">
                                                  <?php echo __('Subtotal');?></span>
                                                <span class="subtotal-amt">
                                                   <?= $siteSettings['site_currency'] ?> <?php echo number_format($subTotal,2) ?></span>
                                            </div>
                                            <div class="subtotal" id="deliveryAmt">
                                                <span class="subtotal-txt">
                                                  <?php echo __('Delivery Fee');?></span>
                                                <span class="subtotal-amt">
                                                 <?php echo ($deliveryCharge == 0) ? 'Free' : $siteSettings['site_currency'].' '.number_format($deliveryCharge,2); ?>
                                            </div>
                                            <div class="subtotal">
                                                <span class="subtotal-txt">Tax (<?= number_format($restDetails['restaurant_tax'],2) ?>%)</span>
                                                <span class="subtotal-amt"><?= $siteSettings['site_currency'] ?> <?php echo number_format($taxAmount,2) ?></span>
                                            </div>

                                            <?php if($this->request->session()->read('offerAmount') != '') { ?>
                                                <div class="subtotal">
                                                    <span class="subtotal-txt"><?php echo __('Offer Amount');?> (<?= $this->request->session()->read('offerPercentage') ?> %)</span>
                                                    <span class="subtotal-amt"><?= ($siteSettings['site_currency']) ?> <span class="offerTotal"><?= number_format($this->request->session()->read('offerAmount'),2) ?> (-)</span>
                                                </div>
                                            <?php } ?>
                                            <?php if($this->request->session()->read('rewardPoint') != '') { ?>
                                                <div class="subtotal">
                                                    <span class="subtotal-txt"><?php echo __('Redeem Amount');?> (<?= $this->request->session()->read('rewardPercentage') ?> %)</span>
                                                    <span class="subtotal-amt"><?= ($siteSettings['site_currency']) ?> <span class="redeemTotal"><?= number_format($this->request->session()->read('rewardPoint'),2) ?> (-)</span>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 no-padding">
                                        <a class="pull-left viewcart" data-view-cart="View Cart" data-hide-cart="Hide Cart" onclick="viewcart(this);"><?php echo __('View Cart');?></a>
                                       
                                        <div class="cart-checkout" id="deliveryTotal">
                                            <button onclick="return gotoCheckout()" id="submitBtn" <?php echo (empty($cartsDetails) || $subTotal < $restDetails['minimum_order']) ? 'disabled' : ''; ?> class="btn-checkout" href="javascript:void(0)"> <?php echo __(($currentStatus == 'Open') ? 'Checkout' : (($currentStatus == 'PreOrder') ? 'Pre Order' : 'Pre Order'));?> ( <?= $siteSettings['site_currency'] ?> <?php echo number_format($totalAmount,2) ?> )</button>
                                        </div>
                                        <div class="cart-checkout" id="pickupTotal" style="display: none">
                                            <button onclick="return gotoCheckout()" id="submitBtn" <?php echo (empty($cartsDetails)) ? 'disabled' : ''; ?> class="btn-checkout" href="javascript:void(0)"> <?php echo __(($currentStatus == 'Open') ? 'Checkout' : (($currentStatus == 'PreOrder') ? 'Pre Order' : 'Pre Order'));?> ( <?= $siteSettings['site_currency'] ?> <?php echo number_format($withOutDelivery,2) ?> )</button>
                                        </div>
                                    </div>
                                    <?php if($restDetails['restaurant_delivery'] == 'Yes') { ?>
                                        <div class="min-order-value minimumsection"> <?php echo __('Minimum Order');?> <?= $siteSettings['site_currency'] ?> <?= number_format($restDetails['minimum_order'],2) ?> </div>
                                    <?php } ?>

                                    <?php if($this->request->session()->read('orderPoint') != '') { ?>
                                        <div class="common-class"><div id="blink_text" class="min-order-value reward_earning"><span class="reward_trophy"><i class="fa fa-trophy"></i></span> <?php echo __('You will earn');?> <?= floor($this->request->session()->read('orderPoint')) ?> <?php echo __('Points') ?> </div></div>
                                    <?php } ?>

                                <?php }else { ?>
                                    <div class="cart-wrapper">
                                        <div class="cart-innerwrapper">
                                            <div class="cart-box">
                                                <div class="col-md-12 text-left warehouse_head"><?php echo __('Your Cart Empty');?>
                                                </div>
                                            </div>
                                            <div class="no-cart">
                                                <div class="no-cart-image text-center"><img src="<?= BASE_URL ?>images/cartitem.png"></div>
                                                <div class="no-cart-text"><?php echo __('No Item(s) Added');?></div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                } ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

           <div class="clickcarttocheckout visible-xs">
                <a href="#cart_wrapper" rel="m_PageScroll2id"><i class="fa fa-shopping-cart"></i></a>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="addons_popup" role="dialog"></div>

<div class="modal fade" id="cartErrorPopup" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">x</button>
                <h4 class="modal-title"><?php echo __('Items already in cart');?></h4>
            </div>
            <input type="hidden" id="newMenu" value="">
            <input type="hidden" id="newType" value="">
            <input type="hidden" id="cartId" value="">
            <input type="hidden" id="addonsMenu" value="">
            <div class="modal-body clearfix">               
                <span><?php echo __('Your cart contains items from other restaurant. Would you like to reset your cart for adding items from this restaurant?');?></span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bt-default no_btn" data-dismiss="modal"><?php echo __('');?>NO</button>
                <button class="btn orng_btn" onclick="return addNewCart()"><?php echo __('YES,START FRESH');?></button>
            </div>
        </div>
    </div>
</div>


<script>

    function addNewCart() {
        var menuId = $("#newMenu").val();
        var cartId = $("#cartId").val();
        var addonsMenu = $("#addonsMenu").val();
        var type = $("#newType").val();
        $("#cartErrorPopup").modal('hide');

        $.ajax({
            type   : 'POST',
            url    : jssitebaseurl+'menus/clearCart',
            data   : {menuid:menuId},
            success: function(data){
                if(menuId != '') {
                    addToCart(menuId,type);
                }else if(addonsMenu != '') {
                    variantCart(addonsMenu)
                }else {
                    updateToCart(cartId,type)
                }
            }
        });
    }

    function addToCart(menuId,type) {
        var cartResid = $.trim($("#cartrestId").val());
        var resid     = $.trim($("#resid").val());

        var order_type = $('input[name="deliveryType"]:checked').val()
        $.ajax({
            type   : 'POST',
            url    : jssitebaseurl+'menus/ajaxaction',
            data   : {menuid:menuId,action:'cartUpdate',type:type,orderType:order_type,resid:resid},
            success: function(data){

                if($.trim(data) == 0) {
                    $("#newMenu").val(menuId);
                    $("#newType").val(type);
                    $("#cartErrorPopup").modal('show');
                }else {

                    var result = data.split('@@');
                    $("#cartDetails").html(result[0]);
                    $("#minimum_order").val(result[1]);
                    $(".cartTotal").html(result[2]);
                    if(result[1] == '1') {
                        $("#submitBtn").attr('disabled',false);
                    }else {
                        $("#submitBtn").attr('disabled',true);
                    }
                    scrollCart1();
                    orderType(order_type);
                    return false;
                }
            }
        });return false;
    }

    function updateToCart(cartId,type) {
        var resid = $.trim($("#resid").val());

        var order_type = $('input[name="deliveryType"]:checked').val()
        $.ajax({
            type   : 'POST',
            url    : jssitebaseurl+'menus/ajaxaction',
            data   : {id:cartId,action:'cartEdit',type:type,orderType:order_type,resid:resid},
            success: function(data){

                var result = data.split('@@');
                $("#cartDetails").html(result[0]);
                $("#minimum_order").val(result[1]);
                $(".cartTotal").html(result[2]);
                if(result[1] == '1') {
                    $("#submitBtn").attr('disabled',false);
                }else {
                    $("#submitBtn").attr('disabled',true);
                }
                scrollCart1();
                orderType(order_type);
                return false;
            }
        });return false;

    }

    function variantCart(menuId) {

        var addonsLength = $(".MainAddonsName").length;
        var errorCount = 0;
        var addonsCheck = 0;


        $(".MainAddonsName").each(function(){

            var id = $(this).attr("id");
            var AddonCount = 0;
            var mini = $(".mini_"+id).val();
            var maxi = $(".maxi_"+id).val();
            var selectAddons = $(".checkCount_"+id+':checked').length;

            if(selectAddons < mini){
                errorCount ++;                
                $("#subaddonErr_"+id).addClass('submenu-addon error').html('<?php echo __("You should select minimum ")?>'+  mini  +' <?php echo __(" addons")?>');
                $('.popupbutton').removeClass('dim');
                return false;
            }

            if(selectAddons > maxi){
                errorCount ++;
                $("#subaddonErr_"+id).addClass('submenu-addon error').html('<?php echo __("You should select maximum ") ?>'+  maxi  +'<?php echo __(" addons")?>');               
                $('.popupbutton').removeClass('dim');
                return false;
            }
            $("#subaddonErr_"+id).html('');
            addonsCheck++;
        });

        if(errorCount == 0 && addonsLength == addonsCheck){
            var addonsLength = $(".addon_ss").length;

            var id = $('[name=addons_radio]:checked').val();
            if (id == undefined) {
                id = $('#productAddonsSingle').val();
            }
            var menuId = $('#menuId').val();
            var quantity  = $('#quantity').val();
            var subaddons = '';

            $(".addon_ss:checked").each(function(){
                subaddons += $(this).val()+','
            });

            var orderType = $('input[name="deliveryType"]:checked').val();

            if(quantity > 0) {
                var resid = $.trim($("#resid").val());
                $.ajax({
                    type   : 'POST',
                    url    : jssitebaseurl+'menus/ajaxaction',
                    data   : {'id':id, 'quantity':quantity, 'subaddons' : subaddons,'menuid' : menuId,action:'cartUpdate',type:'',orderType:orderType,resid:resid},
                    success: function(data){

                        if($.trim(data) == 0) {

                            $("#addons_popup").modal('hide');
                            $("#addonsMenu").val(menuId);
                            $("#cartErrorPopup").modal('show');
                        }else {
                            var result = data.split('@@');
                            $("#cartDetails").html(result[0]);
                            $("#minimum_order").val(result[1]);
                            $(".cartTotal").html(result[2]);
                            if(result[1] == '1') {
                                $("#submitBtn").attr('disabled',false);
                            }else {
                                $("#submitBtn").attr('disabled',true);
                            }
                            $("#addons_popup").modal('hide');
                            scrollCart1();
                            return false;
                        }
                    }
                });return false;
            }

        } else {
            return false;
        }
    }

    function getMenuDetails(id) {
        if(id != '') {
            $.ajax({
                type   : 'POST',
                url    : jssitebaseurl+'menus/ajaxaction',
                data   : {menuid:id,action:'getMenuDetails'},
                success: function(data){
                    //var result = data.split('@@');
                    $("#addons_popup").html(data);
                    $("#addons_popup").modal('show');
                    return false;
                    $("#minimum_order").val(result[1]);
                    if(result[1] == '1') {
                        $("#submitBtn").attr('disabled',false);
                    }else {
                        $("#submitBtn").attr('disabled',true);
                    }
                    scrollCart1();
                    return false;
                },
            });
            return false;
        }
    }

    function scrollCart1(){
        var headfootcart = $(window).height() - ($(".top-header2").outerHeight() + $(".res_footer").outerHeight() + $(".cart-box").outerHeight() + $(".pic-del").outerHeight()+$(".min-order-value").outerHeight() + $(".cart-checkout").outerHeight()+80);
       
        if($(window).width()>767){
            $(".cart-items-scroll").css("max-height",headfootcart);
            $(".cart-items-scroll").enscroll('destroy');
            $(".cart-items-scroll").enscroll();
            $(".fospluginwrapper .cart-items-scroll").enscroll('destroy');             
        }
        else{
            $(".cart-items-scroll").css("max-height","inherit");
            $(".cart-items-scroll").enscroll('destroy');
            $(".fospluginwrapper .cart-items-scroll").enscroll('destroy');            
        }
    }

    function getDetails(id) {
        if(id != '') {
            $(".showClass").addClass('hide');
            $(".menuPrice_"+id).removeClass('hide');
            $(".menuPrice_"+id).addClass('showClass');
        }
    }
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB8HDZTR6HrOQbudXQN8cSAorzi4uaZy1A&callback=initMap"></script>
<script>
    function initMap() {
        var lat       = '<?php echo $restDetails["sourcelatitude"];?>';
        var lon       = '<?php echo $restDetails["sourcelongitude"];?>';
        var lattitude = parseFloat(lat);
        var long      = parseFloat(lon);
        var uluru = {lat: lattitude, lng: long};
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 8,
            center: uluru
        });
        var marker = new google.maps.Marker({
            position: uluru,
            map: map
        });
    }

    function orderType(type) {
        $.ajax({
            type   : 'POST',
            url    : jssitebaseurl+'menus/orderType',
            data   : {type:type},
            success: function(data){
                if(type == 'pickup') {
                    $("#deliveryAmt").css('display','none');
                    $("#deliveryTotal").css('display','none');
                    $("#pickupTotal").css('display','block');
                    $(".minimumsection").css('display','none');

                }else {
                    $("#deliveryAmt").css('display','block');
                    $("#deliveryTotal").css('display','block');
                    $("#pickupTotal").css('display','none');
                    $(".minimumsection").css('display','block');
                }
            }
        });
    }

    function increment() {
        var quantity = $("#quantity").val();
        quantity++;
        $("#quantity").val(quantity);
    }
    function decrement() {
        var quantity = $("#quantity").val();
        quantity--;
        if(quantity != 0) {
            $("#quantity").val(quantity);
        }
    }

    function gotoCheckout() {
        var logginUser = $("#logginUser").val();
        window.location.href = jssitebaseurl+'checkouts';
        /*if(logginUser == '') {
            window.location.href = jssitebaseurl+'checkouts/login'
        }else {
            window.location.href = jssitebaseurl+'checkouts'
        }*/
        return false;
    }

    $(document).ready(function () {
        var type = $('input[name="deliveryType"]:checked').val();
        orderType(type);
    })

    function validateBook() {
        //alert("33333");
        var customerId = $("#logginUser").val();

        var BookaTableGuestCount    = $.trim($("#BookaTableGuestCount").val());
        //alert(BookaTableGuestCount);
        var BookaTableBookingDate   = $.trim($("#BookaTableBookingDate").val());
        var BookaTableBookingTime   = $.trim($("#getDateTime").val());
        var BookaTableCustomerName  = $.trim($("#BookaTableCustomerName").val());
        var BookaTableBookingEmail  = $.trim($("#BookaTableBookingEmail").val());
        var BookaTableBookingPhone  = $.trim($("#BookaTableBookingPhone").val());
        var emailRegex              = new RegExp(/^([\w\.\-]+)@([\w\-]+)((\.(\w){2,3})+)$/i);
        $('#bookError').html('');
        $('#bookError1').html('');
        $('#bookError2').html('');
        $('#bookError3').html('');
        $('#bookError4').html('');
        $('#bookError5').html('');
        $('#bookError6').html('');

        if(customerId == '') {
            alert('<?php echo __("Please login");?>');
            return false;
        }else if(BookaTableGuestCount == ''){
            $("#bookError").html('<?php echo __("Please select guest count");?>');
            $("#BookaTableGuestCount").focus();
            return false;
        } else if(BookaTableBookingDate == ''){           
            $("#bookError1").html('<?php echo __("Please select booking date");?>');
            $("#BookaTableBookingDate").focus();
            return false;
        } else if(BookaTableBookingTime == ''){
            $("#bookError2").html('<?php echo __("Please select booking time");?>');            
            $("#getDateTime").focus();
            return false;
        } else if(BookaTableCustomerName == ''){
            $("#bookError3").html('<?php echo __("Please enter the customer name");?>');
            $("#BookaTableCustomerName").focus();
            return false;
        } else if(BookaTableBookingEmail == ''){           
            $("#bookError4").html('<?php echo __("Please enter the email");?>');
            $("#BookaTableBookingEmail").focus();
            return false;
        } else if (!emailRegex.test(BookaTableBookingEmail)) {
            $("#bookError4").html('<?php echo __("Please enter valid email");?>');            
            $("#BookaTableBookingEmail").focus();
            return false;
        } else if(BookaTableBookingPhone == ''){
            $("#bookError5").html('<?php echo __("Please enter the phone no");?>');             
            $("#BookaTableBookingPhone").focus();
            return false;
        } else if(isNaN(BookaTableBookingPhone)){
            $("#bookError5").html('<?php echo __("Please enter the valid phone no");?>');             
            $("#BookaTableBookingPhone").focus();
            return false;
        } else {

            var formData = ($("#bookaTableForm").serialize());
            $.post(jssitebaseurl+'BookaTables/bookaTable/',{'formData':formData}, function(res) {
                $("#succDiv").show();
                $(".bookRequest").find('input[type=text],textarea,select').val('');
                setTimeout(function(){
                    $("#succDiv").hide();
                },5000);

            });
        }
        return false;
    }

    $(".sideCuisine").click(function () {
        $(".sample").addClass('hide');
        $(".menu_category_"+this.id).removeClass('hide');
    })

    $(".sideAllCuisine").click(function () {
        $(".sample").removeClass('hide');
    });    
</script>

<?php if(!empty($cartsDetails)) { 
    if($this->request->session()->read('orderPoint') != '') { ?>
        <script type="text/javascript">
             function flash(el, c1, c2) {
                var text = document.getElementById(el);
                text.style.color = (text.style.color == c2) ? c1 : c2;
                }
                setInterval(function() {
                    flash('blink_text', 'gray', 'red')
                }, 1000);
        </script>
    <?php 
    }
} ?>
