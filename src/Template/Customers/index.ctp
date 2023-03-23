<script src="https://www.paypalobjects.com/api/checkout.js"></script>
<div class="container no-padding">
    <span class="statusshow" style="display:none;"></span>
   <section class="myaccont_main_wrapper">
      <div class="customerMyAccount">
         <div class="myacc-profshow">
            <div class="col-sm-6 no-padding-left pul-right no-rtlpadding-right">
                  <div class="myaccount-name"><?= $customerDetails['first_name'] ?></div>
                  <div class="myaccount-num"><?= $customerDetails['phone_number'] ?></div>
                  <div class="myaccount-mail"><?= $customerDetails['username'] ?></div>
            </div>
             <div class="col-sm-6 no-padding-right pul-left no-rtlpadding-left">
                 <!-- <span class="pull-right reward_head_btn pul-left"><button class="btn btn-deafult add_new_add_btn"><?php echo __('Earned Points :');?> <?= $customerTotalPoints['total_points'] ?></button></span> -->
             </div>
         </div>

         <div class="myaccount-inner">
            <div class="myaccount-side-menu">
               <ul class="myaccount-tabs">
                  <li class="active" data-content="orderhistory_content"><a href="javascript:void(0);"><i class="fa fa-bars visible-xs"></i><span class="hidden-xs"><?php echo __('My Orders',true);?></span></a>
                  </li>
                  <!-- <li data-content="reward_content"><a href="javascript:void(0);"><i class="fa fa-credit-card visible-xs"></i><span class="hidden-xs"><?php /* echo __('Reward Points',true); */ ?></span></a></li> -->
                  <li data-content="wallet_content"><a href="javascript:void(0);"><i class="fa fa-google-wallet visible-xs"></i><span class="hidden-xs"><?php echo __('My Wallet',true);?></span></a></li>
                  <li data-content="profile_content"><a href="javascript:void(0);"><i class="fa fa-user visible-xs"></i><span class="hidden-xs"><?php echo __('Profile',true);?></span></a></li>
                  <li data-content="setting_content"><a href="javascript:void(0);"><i class="fa fa-cog visible-xs"></i><span class="hidden-xs"><?php echo __('Settings',true);?></span></a></li>
                  <li data-content="payment_content"><a href="javascript:void(0);"><i class="fa fa-credit-card visible-xs"></i><span class="hidden-xs"><?php echo __('Payments',true);?></span></a></li>
                  <li data-content="address_content"><a href="javascript:void(0);"><i class="fa fa-address-card visible-xs"></i><span class="hidden-xs"><?php echo __('Address Book',true);?></span></a></li>
                   <?php if(!empty($referrals)) { ?>
                      <li data-content="referral_content"><a href="javascript:void(0);"><i class="fa fa-user visible-xs"></i><span class="hidden-xs"><?php echo __('Referral Friend',true);?></span></a></li>
                   <?php } ?>
               </ul>
            </div>

            <?php
               echo $this->Form->input('bySearch', [
                   'id' => 'bySearch',
                   'type' => 'hidden',
                   'class' => 'form-horizontal',
                   'value'=> SEARCHBY
               ]);               
            ?>

             <?php
               echo $this->Form->input('customerId', [
                   'id' => 'customerId',
                   'type' => 'hidden',
                   'class' => 'form-horizontal',
                   'value'=> $logginUser['id']
               ]);
            ?>
            <div class="myaccount-right">
          <!-- OrderHistory -->  
               <div id="orderhistory_content" class="orderhistory_content common_content">
                  <div class="orderhistory_title"><?php echo __('Order History');?></div>
                  <?php if(!empty($customerDetails['orders'])) {
                     foreach ($customerDetails['orders'] as $oKey => $oValue) { ?>
                  <div class="my_orderbox orderId_<?= $oValue['order_number'] ?>">
                     <div class="my_orderbox_top">
                        <div class="col-sm-7 col-xs-12 no-padding-left no-xs-padding-right pul-right no-rtlpadding-right">
                           <div class="col-sm-2 col-xs-5 no-padding-left my_order_resimg pul-right no-rtlpadding-right">
                               <img src="<?php echo BASE_URL.'uploads/storeLogos/'.$oValue['restaurant']['logo_name'] ?>" height="100" width="150" onerror="this.src='<?php echo BASE_URL;?>webroot/images/no_store.jpg'">
                           </div>
                           <div class="col-sm-9 col-xs-7 no-padding-left no-xs-padding">
                              <div class="my_order_resname"><?= $oValue['restaurant']['restaurant_name'] ?></div>
                              <div class="my_order_resadd"><?= $oValue['restaurant']['contact_address'] ?></div>
                           </div>
                        </div>

                        <div class="col-sm-5 col-xs-12 text-right no-padding-right no-xs-padding m-t-xs-15 pul-left no-rtlpadding-left">
                           <?php if($oValue['status'] == 'Pending') { ?>
                           <div class="my_order_delivery"><?= $oValue['status'] ?>
                              <span class="label-warning">
                              <i class="fa fa-clock-o"></i>
                              </span>
                           </div>
                           <?php }else if($oValue['status'] != 'Pending' && $oValue['status'] != 'Delivered' && $oValue['status'] != 'Failed') { ?>
                           <div class="my_order_delivery"><?= $oValue['status'] ?>
                              <span class="label-warning">
                              <i class="fa fa-clock-o"></i>
                              </span>
                           </div>
                           <?php
                              }else if($oValue['status'] == 'Delivered') { ?>
                           <div class="my_order_delivery">Delivered On : 2017-11-23 <span class="label-success"><span class="fa fa-check"></span></span>
                           </div>
                           <?php
                              }else if($oValue['status'] == 'Failed') { ?>
                           <div class="my_order_delivery"><?= $oValue['status'] ?>
                              <span class="label-danger">
                              <i class="fa fa-close"></i>
                              </span>
                           </div>
                           <?php } ?>
                           <div class="my_order_id"><?= $oValue['order_number'] ?> | <?= date('Y-m-d h:i A',strtotime($oValue['created'])) ?></div>
                        </div>
                     </div>
                     <div class="my_orderbox_bottom">
                        <span class="pull-left pul-right">
                        <a href="<?= BASE_URL ?>customers/orderView/<?= $oValue['id'] ?>" class="btn btn-deault view-btn2"><?php echo __('VIEW');?></a>
                        <?php if(!empty($oValue['reviews'][0]['rating']) 
                           && ($oValue['status'] == 'Delivered')){
                           $rating = $oValue['reviews'][0]['rating'] * 20;?>
                        <span class="">
                        <span class="review_rating_outer">
                        <span class="review_rating_grey"></span>
                        <span class="review_rating_green" style="width:<?php echo $rating;?>%;"></span>
                        </span>
                        </span>
                        <?php } else {
                           if($oValue['status'] == 'Delivered') { ?>
                        <a href="javascript:;" class="btn btn-deault review-btn2" onclick="reviewPopup(<?= $oValue['id']?>);"><?php echo __('REVIEW');?></a>
                        <?php } 
                           }?>                                          
                        </span>
                        <span class="my_order_total pull-right pul-left"><?php echo __('Total');?>: <?= $siteSettings['site_currency'] ?> <?= number_format($oValue['order_grand_total'],2) ?></span>
                     </div>
                  </div>
                  <?php
                     }
                     } ?>
               </div>
          <!-- Wallet -->
               <div id="wallet_content" class="wallet_content common_content" style="display:none;">
                  <div class="orderhistory_title"><?php echo __('Wallet');?> <span class="pull-right pul-left"><button class="btn btn-deafult" data-toggle="modal" data-target="#wallet_history_modal"><?php echo __('Wallet History');?> </button></span></div>
                  <div class="mywallet_amt">
                     <div class="mywallet_amt_text"><?php echo __('Balance');?> : <?= $siteSettings['site_currency'] ?> <?= number_format($customerDetails['wallet_amount'],2) ?></div>
                  </div>
                  <div class="add_money_btn">
                     <button class="btn btn-deault view-btn load_money_btn"><?php echo __('LOAD MONEY');?></button>
                  </div>
                  <div class="load_money_content" style="display:none;">
                     <div class="my_wallet_add">
                        <div class="col-md-2 col-sm-3 col-xs-12 add_money no-padding-left m-b-xs-15 pul-right">
                           <input type="text" class="form-control add_money_form" placeholder="0.00" id="addAmount">
                        </div>
                        <div class="col-md-2 col-sm-3 col-xs-12 add_money text-center m-b-xs-15 pul-right">
                           <input type="radio" id="hundred" name="money" onclick="return addMoney('100')">
                           <label for="hundred">100</label>
                        </div>
                        <div class="col-md-2 col-sm-3 col-xs-12 add_money text-center m-b-xs-15 pul-right">
                           <input type="radio" id="twohundred" name="money" onclick="return addMoney('200')">
                           <label for="twohundred">200</label>
                        </div>
                        <div class="col-md-2 col-sm-3 col-xs-12 add_money text-center m-b-xs-15 pul-right">
                           <input type="radio" id="threehundred" name="money" onclick="return addMoney('500')">
                           <label for="threehundred">500</label>
                        </div>
                        <div class="col-xs-12">
                           <span class="amountErr"></span>
                        </div>
                     </div>

                     <div class="load_money_card">
                        <?php if(!empty($customerDetails['stripe_customers'])) {
                           foreach ($customerDetails['stripe_customers'] as $cKey => $cValue) { ?>
                        <div class="col-sm-6 col-xs-12 no-padding-left no-xs-padding-right">
                           <input type="radio" id="check_card_<?= $cValue['id'] ?>" name="load_card" value="<?= $cValue['id'] ?>">
                           <label for="check_card_<?= $cValue['id'] ?>">
                              <div class="credit_card">
                                 <span><img src="images/master.png"></span>
                                 <div class="credit_det">
                                    <div class="card_number">XXXX-XXXXXXXX-<?= $cValue['card_number'] ?> </div>
                                    <div class="card_validity">Valid till <?= $cValue['exp_month'] ?>/<?= $cValue['exp_year'] ?></div>
                                 </div>                                 
                              </div>
                           </label>
                        </div>
                        <?php
                           }
                           }else { ?>
                        <?php echo __('No Record Found');?>
                        <?php } ?>
                        <span class="cardErr"></span>
                         <div id="paypal-button-container"></div>
                     </div>

                     <div class="add_money_btn">
                        <button id="loadMoneyId" onclick="return loadMoney()" class="btn btn-deault view-btn"><?php echo __('ADD MONEY');?></button>
                     </div>
                  </div>
               </div>
           <!-- Profile -->  
               <div id="profile_content" class="profile_content common_content" style="display:none;">
                  <div class="orderhistory_title m-b-20"><?php echo __('Profile Details');?></div>
                  <?=
                     $this->Form->create('profile',[
                         'class' => 'profile-form',
                         'id' => 'ProfileForm',
                         'enctype'  =>'multipart/form-data',
                         'method' => 'post'
                     ]);
                     ?>
                  <input type="hidden" name="action" value="profileUpdate">
                  <div class="col-sm-6 col-md-6 col-xs-12  border-right xs-border no-padding-left pul-right no-rtlpadding-right rtl-padding-l15">
                     <div class="form-group clearfix m-b-20">
                        <label class="control-label"><?php echo __('First Name');?></label>
                        <input id="first_name" name="first_name" type="text" class="form-control control-form-control" value="<?php echo $customerDetails['first_name'];?>" placeholder="Your First Name">
                        <span class="firstErr"></span>
                     </div>
                     <div class="form-group clearfix m-b-20">
                        <label class="control-label"><?php echo __('Last Name');?></label>
                        <input id="last_name" name="last_name" type="text" class="form-control control-form-control" value="<?php echo $customerDetails['last_name'];?>" placeholder="Your Last Name">
                        <span class="lastErr"></span>
                     </div>
                     <div class="form-group clearfix m-b-20">
                        <label class="control-label"><?php echo __('Phone Number');?></label>
                        <input maxlength="15" id="phone_number" name="phone_number" type="text" class="form-control control-form-control" value="<?php echo $customerDetails['phone_number'];?>" placeholder="Your Phone Number">
                        <span class="phoneErr"></span>
                     </div>
                  </div>

                  <div class="col-sm-6 col-md-6 col-xs-12 no-xs-padding pul-left">
                     <div class="form-group clearfix m-b-20">
                        <div class="col-sm-7 no-padding pul-right">
                           <label class="control-label"><?php echo __('Do You Wanna Newsletter');?></label>
                        </div>
                        <div class="col-sm-5 news_input no-xs-padding pul-left">
                           <input id="newsletter_yes" value="Y" name="newsletter" type="radio" class="" <?php if(!empty($customerDetails['newsletter'])) {echo ($customerDetails['newsletter'] == 'Y') ? 'checked' : '' ; }?> > <?php echo __('Yes');?>
                           <input id="newsletter_no" value="N" name="newsletter" type="radio" class=""  <?php if(!empty($customerDetails['newsletter'])) {echo ($customerDetails['newsletter'] == 'N') ? 'checked' : '' ; }?> > <?php echo __('No');?>
                           <span class="newsLetterErr"></span>
                        </div>
                     </div>                     
                  </div>
                  <div class="col-xs-12 text-center no-padding-left m-t-10">
                     <button onclick="return profileUpdate();" class="btn btn-deault view-btn"><?php echo __('UPDATE');?></button>
                  </div>
                  <?=  $this->Form->end(); ?>                  
               </div>
           <!-- Settings -->     
               <div id="setting_content" class="setting_content common_content" style="display:none;">
                  <div class="col-sm-6 col-md-6 col-xs-12  border-right xs-border no-padding-left">
                     <?=
                        $this->Form->create('signup',[
                            'class' => 'login-form',
                            'id' => 'UserSignupForm',
                            'method' => 'post'
                        ]); ?>
                     <input type="hidden" name="action" value="usernameUpdate">
                     <div class="orderhistory_title m-b-20"> <?php echo __('Email Settings');?></div>
                     <div class="form-group clearfix m-b-20">
                        <label class="control-label"> <?php echo __('Current User Email');?></label>
                        <div class="username"><?= $customerDetails['username'] ?></div>
                     </div>
                     <div class="form-group clearfix m-b-20">
                        <label class="control-label"><?php echo __('New User Email');?></label>
                        <input id="username" name="username" type="text" class="form-control control-form-control" placeholder="New User Email">
                        <span class="myaccountUserErr"></span>
                     </div>
                     <div class="col-xs-12 text-left no-padding-left">
                        <button onclick="return changeUsername()" class="btn btn-deault view-btn"><?php echo __('UPDATE');?></button>
                     </div>
                     <?=  $this->Form->end(); ?>
                  </div>

                  <div class="col-sm-6 col-md-6 col-xs-12 border-left no-xs-padding">
                     <?=
                        $this->Form->create('Password',[
                            'class' => 'login-form',
                            'id' => 'UserPasswordForm',
                            'method' => 'post'
                        ]); ?>
                     <input type="hidden" name="action" value="passwordUpdate">
                     <div class="password_setting_div">
                        <div class="orderhistory_title m-b-20"><?php echo __('Password Settings');?></div>
                        <div class="form-group clearfix m-b-20">
                           <label class="control-label"><?php echo __('Current password');?></label>
                           <input type="password" name="oldPassword" class="form-control control-form-control" placeholder="Current Password">
                            <span class="oldPasswordErr"></span>
                        </div>
                        <div class="form-group clearfix m-b-20">
                           <label class="control-label"><?php echo __('New Passowrd');?></label>
                           <input id="newPassword" name="newPassword" type="password" class="form-control control-form-control" placeholder="New Password">
                           <span class="newPasswordErr"></span>
                        </div>
                        <div class="form-group clearfix m-b-20">
                           <label class="control-label"><?php echo __('Confirm Password');?></label>
                           <input id="confirmPassword" name="confirmPassword" type="password" class="form-control control-form-control" placeholder="Confirm Password">
                           <span class="confirmPasswordErr"></span>
                        </div>
                        <div class="col-xs-12 text-left no-padding-left">
                           <button class="btn btn-deault view-btn" onclick="return updatePassword()"><?php echo __('UPDATE');?></button>
                        </div>
                     </div>
                     <?=  $this->Form->end(); ?>
                  </div>
               </div>
            <!-- Payments -->
               <div id="payment_content" class="payment_content common_content" style="display:none;">
                   <!-- <div class="orderhistory_title m-b-20"><?php echo __('Saved Card Details');?>
                       <?php if(count($customerDetails['stripe_customers']) < 3) { ?>
                           <span class="pull-right">
                               <button class="btn btn-deafult add_card_btn" onclick="return showAddCard()"><?php echo __('Add Card');?></button>
                           </span>
                       <?php } ?>
                  </div> -->
                  <div id="showAllCard">
                     <?php if(!empty($customerDetails['stripe_customers'])) {
                        foreach ($customerDetails['stripe_customers'] as $cKey => $cValue) { ?>
                     <div class="col-sm-6 col-xs-12 no-padding-left m-b-10">
                        <div class="credit_card">
                           <span><img src="images/master.png"></span>
                           <div class="credit_det">
                              <div class="card_number">XXX-XXXXXXXX-<?= $cValue['card_number']?> </div>
                              <div class="card_validity"><?php echo __('Valid till');?> <?= $cValue['exp_month']?>/<?= $cValue['exp_year']?></div>
                           </div>
                           <div class="credit_det">
                              <div class="card_delete hidden-xs hidden-sm"><a onclick="return deleteCard(<?= $cValue['id'] ?>)"><?php echo __('DELETE');?></a></div>
                              <div class="card_delete visible-xs visible-sm"><i onclick="return deleteCard(<?= $cValue['id'] ?>)" class="fa fa-trash"></i></div>
                           </div>
                        </div>
                     </div>
                     <?php
                        }
                        }else { ?>
                     <?php echo __('No Record Found');?>
                     <?php } ?>
                  </div>
                  <div id="add_card_modal" style="display: none">
                     <script src="https://js.stripe.com/v3/"></script>
                     <form action="" method="post" id="payment-form">
                        <input type="hidden" value="addCard" name="action">
                        <div class="form-row">
                           <label for="card-element">
                           <?php echo __('Credit or debit card');?>
                           </label>
                           <div id="card-element">
                              <!-- a Stripe Element will be inserted here. -->
                           </div>
                           <!-- Used to display form errors -->
                           <div id="card-errors" role="alert"></div>
                        </div>
                        <button class="btn orng_btn m-t-10"><?php echo __('Submit Payment');?></button>
                     </form>
                  </div>
               </div>
          <!-- Review -->
               <div id="reward_content" class="address_content common_content" style="display:none;">
                  <div class="orderhistory_title m-b-20">
                     Reward Points
                      <span class="day_rem">
                          <?php if($remainingDays <= 1) {
                              echo $remainingDays
                              ?>
                            <?php echo __('Day Remaining');?>
                          <?php }else {
                              echo $remainingDays
                              ?>
                              <?php echo __('Days Remaining');?>

                          <?php } ?>
                      </span>
                  </div>

                  <table id="reward_table" class="table table-responsive">
                     <thead>
                         <tr>
                           <th>S.No</th>
                           <th>Order-ID</th>
                           <th>Restaurant Name</th>
                           <th>Total Value</th>
                           <th>Points</th>
                           <th>Type</th>
                           <th>Date</th>
                        </tr>
                     </thead>
                     <tbody>
                     <?php if(!empty($customerPoints) && !empty($rewardPoint)) {
                         foreach ($customerPoints as $cKey => $cValue) { ?>
                             <tr>
                                 <td><?= $cKey+1 ?></td>
                                 <td><?= $cValue['order']['order_number'] ?></td>
                                 <td><?= $cValue['restaurant_name'] ?></td>
                                 <td><?= $siteSettings['site_currency'] ?> <?= number_format($cValue['total'],2) ?></td>
                                 <td><?= $cValue['points'] ?> <?= ($cValue['type'] == 'Spent') ? '('. $siteSettings['site_currency'].' '. number_format($cValue['order']['reward_offer'],2).')' : '' ?></td>
                                 <td><?= $cValue['type'] ?></td>
                                 <td><?= date('Y-m-d h:i A',strtotime($cValue['created'])) ?></td>
                             </tr>
                     <?php
                         }
                     }else { ?>

                     <?php

                     }
                     ?>
                     </tbody>
                  </table>
               </div>
          <!-- Address -->     
               <div id="address_content" class="address_content common_content" style="display:none;">
                  <div class="orderhistory_title m-b-20"><?php echo __('Address Book');?>
                     <span class="pull-right"><button data-target="#my_account_address_add" data-toggle="modal" class="btn btn-deafult add_new_add_btn" class="addnewAdrr"><?php echo __('Add New Address');?></button></span>
                  </div>
                  <?php if(!empty($customerDetails['address_books'])) {
                     foreach ($customerDetails['address_books'] as $key => $value) { ?>
                  <div class="col-sm-6 col-xs-12 no-padding-left no-xs-padding">
                     <div class="addnew_addressbox">
                        <div class="col-md-1 col-sm-2 col-xs-2 no-padding-left pul-right no-rtlpadding-right rtl-padding-l15"><span class="address_box_icon"><i class="fa fa-briefcase"></i></span></div>
                        <div class="col-md-11 col-sm-10 col-xs-10 no-padding">
                           <div class="card_number m-b-5"><?= $value['title'] ?></div>
                           <?php if(SEARCHBY == 'Google') { ?>
                           <div class="card_validity"><?= $value['flat_no'] ?>, <?= $value['address'] ?></div>
                           <?php }else { ?>
                           <div class="card_validity"><?= $value['flat_no'] ?>,
                              <?= $value['address'] ?>
                              <?php echo (!empty($value['city'])) ? ','.$value['city']['city_name'] : '' ?>
                              <?php if(SEARCHBY == 'area') { ?>
                              <?php echo (!empty($value['location'])) ? ','.$value['location']['area_name'] : '' ?>
                              <?php }else { ?>
                              <?php echo (!empty($value['location'])) ? ','.$value['location']['zip_code'] : '' ?>
                              <?php } ?>
                              <?php echo (!empty($value['state'])) ? ','.$value['state']['state_name'] : '' ?>
                           </div>
                           <?php } ?>
                           <div class="address_edit_del"><a href="" onclick="return editAddress(<?= $value['id'] ?>)" ><?php echo __('EDIT');?></a><a onclick="return deleteAddress(<?= $value['id'] ?>)"><?php echo __('DELETE');?></a></div>
                        </div>
                     </div>
                  </div>
                  <?php
                     }
                     }else { ?>
                  <?php echo __('No Record Found');?>
                  <?php } ?>
               </div>
          <!-- Refferal -->     
               <div id="referral_content" class="address_content common_content" style="display:none;">
                     <div class="refferral-rightContent">
                        <h4>Invite your friend to <?= $siteSettings['site_name'] ?>, you get <?= $siteSettings['site_currency'] ?> <?= $referrals['invite_amount'] ?>  after your friend<br> registered with us. Your friend will also get <?= $siteSettings['site_currency'] ?> <?= $referrals['receive_amount'] ?></h4>
                        <p class="referral-codeHead">Your referral code is</p>
                        <span class="referral-code"><?= $customerDetails['referral_code'] ?></span>
                        <div class="col-xs-12 no-padding text-center refferral-rightContent-socail">
                           <div class="col-sm-4 col-xs-12">
                              <a target="_blank" href="http://www.facebook.com/sharer.php?u=<?= $customerDetails['referral_url'] ?>" class="refferral-facebook"><i class="fa fa-facebook"></i><span>Share on Facebook</span></a>
                           </div>
                           <div class="col-sm-4 col-xs-12">
                              <a href="https://plus.google.com/share?url=<?= $customerDetails['referral_url'] ?>" target="_blank" class="refferral-google"><i class="fa fa-google-plus"></i><span>Share on Google+</span></a>
                           </div>
                           <div class="col-sm-4 col-xs-12">
                              <a href="https://twitter.com/share?url=<?= $customerDetails['referral_url'] ?>" target="_blank" class="refferral-twitter"><i class="fa fa-twitter"></i><span>Share on Twitter</span></a>
                           </div>
                        </div>
                        <div class="col-xs-12 text-center refferal-border-top">
                           <div class="col-sm-10 col-sm-offset-1 col-xs-12 no-padding">
                              <input id="referralUrl" class="refferral-rightContent-search" type="text" value="<?= $customerDetails['referral_url'] ?>">
                              <input id="copyBtn" class="refferral-rightContent-copy" type="button" onclick="return copyUrl();" value="Copy">
                              <span class="successful-referrals">How does  referral friend work</span>
                           </div>
                        </div>
                        <div class="col-xs-12 no-padding refeerral-how-its-div">
                           <div class="col-sm-4 col-xs-12">
                              <div class="refeerral-how-its">
                                 <span class="refeerral-how-its-img">
                                     <i><img src="images/invite-frd.png"></i> 
                                    
                                 </span>
                                 <span class="refeerral-how-cont">Invite your Friends</span>
                              </div>
                           </div>
                           <div class="col-sm-4 col-xs-12">
                              <div class="refeerral-how-its">
                                 <span class="refeerral-how-its-img">
                                     <i><img src="images/sucessful.png"></i>
                                    
                                 </span>
                                 <span class="refeerral-how-cont">Successful Registration</span>
                              </div>
                           </div>
                           <div class="col-sm-4 col-xs-12">
                              <div class="refeerral-how-its">
                                 <span class="refeerral-how-its-img">
                                    <i><img src="images/gift.png"></i>
                                 </span>
                                 <span class="refeerral-how-cont">You will get <?= $siteSettings['site_currency'] ?> <?= $referrals['invite_amount'] ?></span>
                              </div>
                           </div>
                        </div>
                        <div class="view-referrals">View your Successful Referrals<i class="fa fa-plus pull-right"></i>
                           <div class="table-responsive table-view-referrals"  style="display:none;">
                              <table class="table">
                                 <thead>
                                    <tr>
                                       <th>S.No</th>
                                       <th>Referral Name</th>
                                       <th>Email ID</th>
                                       <th>Date</th>
                                       <th>Status</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <?php if(!empty($referredList)) {
                                        foreach($referredList as $rKey => $rValue) { ?>
                                            <tr>
                                                <td><?= $rKey+1 ?></td>
                                                <td><?= $rValue['first_name'] .' '. $rValue['last_name'] ?></td>
                                                <td><?= $rValue['username'] ?></td>
                                                <td><?= date('Y-m-d h:i A',strtotime($rValue['created'])) ?></td>
                                                <td>Active</td>
                                            </tr>
                                    <?php
                                        }
                                    }else { ?>

                                    <?php } ?>
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
</div>

<!-- WalletHistory -->  
<div class="modal fade" id="wallet_history_modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo __('Wallet Transaction History');?></h4>
            </div>
            <div class="modal-body">
               <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>S.no</th>
                        <th>Purpose</th>
                        <th>Date/Time</th>
                        <th>Amount</th>
                        <th>Transaction details</th>
                    </tr>
                    </thead><?php
                        foreach ($walletHistoryList as $key => $value) { ?>
                            <tr>
                                <td><?php echo $key+1 ;?></td>
                                <td><?php echo $value['purpose']; ?></td>
                                <td><?php echo date('Y-m-d',strtotime($value['created'])); ?></td>
                                <td><?= $siteSettings['site_currency'] ?> <?php echo number_format($value['amount'],2); ?> </td>
                                <td><?php echo $value['transaction_details']; ?></td>
                            </tr><?php
                        }
                    ?>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- AddressBook -->  
<div class="modal fade" id="my_account_address_add" role="dialog">
   <div class="modal-dialog modal-md">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?php echo __('Add New Address');?></h4>
         </div>
         <div class="modal-body clearfix">
            <div class="form-group clearfix">
               <label class="control-label col-md-4 col-xs-12"><?php echo __('Address Title');?></label>
               <div class="col-md-8 col-xs-12">
                  <input type="text" value="" id="title" name="title" class="form-control my-form-control">
                  <span class="titleErr"></span>
               </div>
            </div>
            <div class="form-group clearfix">
               <label class="control-label col-md-4 col-xs-12"><?php echo __('Door no/Flat no');?></label>
               <div class="col-md-8 col-xs-12">
                  <input type="text" value="" id="flat_no" name="flat_no" class="form-control my-form-control" >
                  <span class="flatnoErr"></span>
               </div>
            </div>
            <?php if(SEARCHBY == 'Google') { ?>
            <div class="form-group clearfix">
               <label class="control-label col-md-4 col-xs-12"> <?php echo __('Address');?></label>
               <div class="col-md-8 col-xs-12">
                  <input type="text" id="address" name="address" class="form-control my-form-control">
                  <span class="addressErr"></span>
               </div>
            </div>
            <?php }else { ?>
            <div class="form-group clearfix">
               <label class="control-label col-md-4 col-xs-12"> <?php echo __('Street Address');?></label>
               <div class="col-md-8 col-xs-12">
                  <?= $this->Form->input('street_address',[
                     'type' => 'text',
                     'id'   => 'street_address',
                     'class' => 'form-control',
                     'label' => false
                     ]) ?>
                  <span class="streetErr"></span>
               </div>
            </div>
            <div class="form-group clearfix">
               <label class="control-label col-md-4 col-xs-12"> <?php echo __('State');?></label>
               <div class="col-md-8 col-xs-12">
                  <?= $this->Form->input('state_id',[
                     'type' => 'select',
                     'id'   => 'state_id',
                     'class' => 'form-control',
                     'options'=> $statelist,
                     'empty'  => 'select State',
                     'onchange' => 'cityList();',
                     'label' => false
                     ]) ?>
                  <span class="stateErr"></span>
               </div>
            </div>
            <div class="form-group clearfix">
               <label class="control-label col-md-4 col-xs-12"> <?php echo __('City');?></label>
               <div class="col-md-8 col-xs-12">
                  <div id="cityList">
                     <?= $this->Form->input('city_id',[
                        'type' => 'select',
                        'id'   => 'state_id',
                        'class' => 'form-control',
                        'empty'  => 'select City',
                        'label' => false
                        ]) ?>
                  </div>
                  <span class="cityErr"></span>
               </div>
            </div>
            <div class="form-group clearfix">
               <label class="control-label col-md-4 col-xs-12"> <?php echo __('Area/Zipcode');?></label>
               <div class="col-md-8 col-xs-12">
                  <div id="locationList">
                     <?= $this->Form->input('location_id',[
                        'type' => 'select',
                        'id'   => 'state_id',
                        'class' => 'form-control',
                        'empty'  => 'select Area/Zipcode',
                        'label' => false
                        ]) ?>
                  </div>
                  <span class="locationErr"></span>
               </div>
            </div>
            <?php } ?>
            <div class="col-sm-8 col-sm-offset-4 col-xs-12">
               <button onclick="return addAddress()" type="submit" class="btn btn-primary view-btn"><?php echo __('Submit');?></button>
            </div>
         </div>
      </div>
   </div>
</div>

<div class="modal fade" id="my_account_address_edit" role="dialog">
   <div id="editAddress">
   </div>
</div>

<!-- ReviewPopup -->
<div class="modal fade" id="reviewPopup">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title"> <?php echo __('Review');?></h4>
         </div>
         <div class="modal-body">
            <div class="form-group clearfix">
               <label class="col-sm-4 control-label text-right" > <?php echo __('Rating');?></label>
               <?php                          
                  echo $this->Form->create('review', [
                      'id' => 'reviewAddForm',
                      'class'=>"form-horizontal"
                  ]);          
                  ?> 
               <input type="hidden" name="action" value="reviewUpdate">
               <div class="col-sm-7 margin-t-5">
                  <div class="stars inline-block">
                     <input id="ReviewRating1" class="star-1" name="data[reviews][rating]" value="1" type="radio">
                     <label for="ReviewRating1">1</label>
                     <input id="ReviewRating2" class="star-2" name="data[reviews][rating]" value="2" type="radio">
                     <label for="ReviewRating2">2</label>
                     <input id="ReviewRating3" class="star-3" name="data[reviews][rating]" value="3" type="radio" checked>
                     <label for="ReviewRating3">3</label>
                     <input id="ReviewRating4" class="star-4" name="data[reviews][rating]" value="4" type="radio">
                     <label for="ReviewRating4">4</label>
                     <input id="ReviewRating5" class="star-5" name="data[reviews][rating]" value="5" type="radio">
                     <label for="ReviewRating5">5</label>
                     <input id="order_id" name="data[reviews][order_id]" value="" type="hidden">                           
                     <span></span>
                  </div>
               </div>
            </div>
            <div class="form-group clearfix">
               <label class="control-label col-sm-4 text-right"> <?php echo __('Message');?> </label> 
               <div class="col-sm-7 margin-t-5">
                  <?= $this->Form->input('data[reviews][message]',[
                     'type' => 'textarea',
                     'id'   => 'message',
                     'row' => 5,
                     'class' => 'form-control',
                     'placeholder' => 'Message',
                     'label' => false
                     ]) ?>
                  <span class="msgErr"></span>  
               </div>
            </div>
            <div class="form-group clearfix">
               <div class="col-xs-12 text-center">
                  <button type="submit" class="btn purple btn-primary"><?php echo __('Submit');?></button>
               </div>
            </div>
            <?= $this->Form->end();?>
         </div>
      </div>
   </div>
</div>

<script src="https://js.pusher.com/4.1/pusher.min.js"></script>
<script>
    $(document).ready(function () {
        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher('<?php echo PUSHER_AUTHKEY ?>', {
            encrypted: true
        });

        var channel = pusher.subscribe('my-channelCustomer');
        channel.bind('my-eventCustomer', function(data) {
            var customerId = $("#customerId").val();

            if(data.customer_id == customerId) {
                var result = data.message.split(' ');
                //result[3]
                $.post(jssitebaseurl+'customers/ajaxaction', {
                    'id' : result[3],
                    'action' : 'orderStatus'
                }, function (output) {
                    $(".orderId_"+result[3]).html(output);
                });

                $(".statusshow").show();
                $(".statusshow").html(data.message);
                setTimeout(function(){
                    $(".statusshow").css("display",'none');
                },3000);
            }
        });
    });

   function profileUpdate() {
   
        $(".error").html('');
        var first_name   = $.trim($("#first_name").val());  
        var last_name    = $.trim($("#last_name").val());                
        var phone_number = $.trim($("#phone_number").val()); 
        var newsletter   = $.trim($("input[name='newsletter']:checked").val());          
   
        if(first_name == '') {
            $(".firstErr").addClass('error').html('<?php echo __("Please enter first name");?>');
            $("#first_name").focus();
            return false;
        }else if(last_name == '') {
            $(".lastErr").addClass('error').html('<?php echo __("Please enter last name");?>');
            $("#last_name").focus();
            return false;
        }else if(phone_number == '') {
            $(".phoneErr").addClass('error').html('<?php echo __("Please enter phone number");?>');
            $("#phone_number").focus();
            return false;
        }else if(last_name == '') {
            $(".lastErr").addClass('error').html('<?php echo __("Please enter last name");?>');
            $("#last_name").focus();
            return false;
        }else if(newsletter == '') {
            $(".lastErr").addClass('error').html('<?php echo __("Please choose newsletter");?>');
            $("#newsletter").focus();
            return false;
        }else {
            $("#ProfileForm").submit();
            return false;
        }
        return false;
    }
   
   function isValid(mailAddress){
        var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
        return pattern.test(mailAddress);
    }
   
   function changeUsername() {
   
        $(".error").html('');
        var username = $("#username").val();
        if(username == '') {
            $(".myaccountUserErr").addClass('error').html('<?php echo __("Please enter your username");?>');
            $("#username").focus();
            return false;
        }else if(username != '' && !isValid(username)) {
            $('.myaccountUserErr').addClass('error').html('<?php echo __("Please enter valid username");?>');
            $("#username").focus();
            return false;
        }else {
            $.post(jssitebaseurl+'users/checkUsername', {
                'username' : username
            }, function (output) {   
                if(output == '0') {
                    $("#UserSignupForm").submit();
                    return false;   
                }else {
                    $(".myaccountUserErr").addClass('error').html('<?php echo __("email already exists");?>');
                    $("#username").focus();
                    return false;
                }
            });
        }
        return false;
    }
   
    function updatePassword() {
        $(".error").html('');
        var oldPassword = $("#oldPassword").val();
        var newPassword = $("#newPassword").val();
        var confirmPassword = $("#confirmPassword").val();
        if(oldPassword == '') {
            $(".oldPasswordErr").addClass('error').html('<?php echo __("Please enter your old password");?>');
            $("#oldPassword").focus();
            return false;
        }else if(newPassword == '') {
            $(".newPasswordErr").addClass('error').html('<?php echo __("Please enter your new password");?>');
            $("#newPassword").focus();
            return false;
        }else if(confirmPassword == '') {
            $(".confirmPasswordErr").addClass('error').html('<?php echo __("Please enter your Confirm password");?>');
            $("#confirmPassword").focus();
            return false;
        }else if(confirmPassword != newPassword) {
            $(".confirmPasswordErr").addClass('error').html('<?php echo __("Confirm password mismatch");?>');
            $("#confirmPassword").focus();
            return false;
        }else {
            $("#UserPasswordForm").submit();
            return false;
        }
        return false;
    }

   
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
                    /** @type {HTMLInputElement} */ (document.getElementById('address')),
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
   
    function addAddress() {
        $(".error").html('');
        var title = $.trim($("#title").val());
        var flat_no = $.trim($("#flat_no").val());
        var address = $.trim($("#address").val());
        var bySearch      = $.trim($("#bySearch").val());
   
        var street_address = $.trim($("#street_address").val());
        var state_id    = $.trim($("#state_id").val());
        var city_id     = $.trim($("#city_id").val());
        var location_id = $.trim($("#location_id").val());
   
        if(title == '') {
            $(".titleErr").addClass('error').html('<?php echo __("Please enter your title");?>');
            $("#title").focus();
            return false;
        }else if(flat_no == '') {
            $(".flatnoErr").addClass('error').html('<?php echo __("Please enter your flat no");?>');
            $("#flat_no").focus();
            return false;
        }else if(bySearch != '' && bySearch == 'Google' && address == '') {
            $(".addressErr").addClass('error').html('<?php echo __("Please enter your address");?>');
            $("#address").focus();
            return false;
        }else if(bySearch != '' && bySearch != 'Google' && street_address == '') {
            $(".streetErr").addClass('error').html('<?php echo __("Please enter your street");?>');
            $("#street_address").focus();
            return false;
        }else if(bySearch != '' && bySearch != 'Google' && state_id == '') {
            $(".stateErr").addClass('error').html('<?php echo __("Please select your state");?>');
            $("#state_id").focus();
            return false;
        }else if(bySearch != '' && bySearch != 'Google' && city_id == '') {
            $("#contactInfo").click();
            $(".cityErr").addClass('error').html('<?php echo __("Please select your city");?>');
            $("#city_id").focus();
            return false;
        }else if(bySearch != '' && bySearch != 'Google' && location_id == '') {
            $(".locationErr").addClass('error').html('<?php echo __("Please select your location");?>');
            $("#location_id").focus();
            return false;
        }else {
   
            $.ajax({
                type   : 'POST',
                url    : jssitebaseurl+'customers/addAddress',
                data   : {title:title,flat_no:flat_no,address:address,street_address:street_address,state_id:state_id,city_id:city_id,location_id:location_id},
                success: function(data){
                    if($.trim(data) == 0) {
                        window.location.reload();
                        return false;
                    }else if($.trim(data) == 1) {
                        $(".titleErr").addClass('error').html('<?php echo __("Required Fields Missing");?>');
                        $("#title").focus();
                        return false;
                    }else if($.trim(data) == 2) {
                        $(".titleErr").addClass('error').html('<?php echo __("Title Already exists");?>');
                        $("#title").focus();
                        return false;
                    }else if($.trim(data) == 'error') {
                        $(".addressErr").addClass('error').html('<?php echo __("Sorry! Service not Available in this location");?>');
                        $("#address").focus();
                        return false;
                    }
                    return false;   
                }
            });
            return false;   
        }   
    }
   
    function updateAddress() {
        $(".error").html('');
        var editId = $.trim($("#editId").val());
        var title = $.trim($("#edittitle").val());
        var flat_no = $.trim($("#editflat_no").val());
        var address = $.trim($("#editaddress").val());
   
        var bySearch      = $.trim($("#bySearch").val());
   
        var street_address = $.trim($("#editstreet_address").val());
        var state_id    = $.trim($("#editstate_id").val());
        var city_id     = $.trim($("#editcity_id").val());
        var location_id = $.trim($("#editlocation_id").val());   
   
        if(title == '') {
            $(".edittitleErr").addClass('error').html('Please enter your title');
            $("#edittitle").focus();
            return false;
        }else if(flat_no == '') {
            $(".editflatnoErr").addClass('error').html('Please enter your flat no');
            $("#editflat_no").focus();
            return false;
        }else if(bySearch != '' && bySearch == 'Google' && address == '') {
            $(".editaddressErr").addClass('error').html('Please enter your address');
            $("#editaddress").focus();
            return false;
        }else if(bySearch != '' && bySearch != 'Google' && street_address == '') {
            $(".editstreetErr").addClass('error').html('Please enter your street');
            $("#editstreet_address").focus();
            return false;
        }else if(bySearch != '' && bySearch != 'Google' && state_id == '') {
            $(".editstateErr").addClass('error').html('Please select your state');
            $("#editstate_id").focus();
            return false;
        }else if(bySearch != '' && bySearch != 'Google' && city_id == '') {
            $(".editcityErr").addClass('error').html('Please  select your city');
            $("#editcity_id").focus();
            return false;
        }else if(bySearch != '' && bySearch != 'Google' && location_id == '') {
            $(".editlocationErr").addClass('error').html('Please select your location');
            $("#editlocation_id").focus();
            return false;
        }else {
   
            $.ajax({
                type   : 'POST',
                url    : jssitebaseurl+'customers/editAddress',
                data   : {title:title,flat_no:flat_no,address:address,editId:editId,street_address:street_address,state_id:state_id,city_id:city_id,location_id:location_id},
                success: function(data){
                    if($.trim(data) == 0) {
                        window.location.reload();
                        return false;
                    }else if($.trim(data) == 1) {
                        $(".edittitleErr").addClass('error').html('Required Fields Missing');
                        $("#edittitle").focus();
                        return false;
                    }else if($.trim(data) == 2) {
                        $(".edittitleErr").addClass('error').html('Title Already exists');
                        $("#edittitle").focus();
                        return false;
                    }else if($.trim(data) == 'error') {
                        $(".addressErr").addClass('error').html('Sorry! Service not Available in this location');
                        $("#address").focus();
                        return false;
                    }
                    return false;
   
                }
            });return false;
   
        }
   
    }
   
    function editAddress(id) {
        if(id != '') {
            $.ajax({
                type   : 'POST',
                url    : jssitebaseurl+'customers/ajaxaction',
                data   : {id:id,action:'editAddress'},
                success: function(data){
                    $("#editAddress").html(data);
                    $("#my_account_address_edit").modal('show');
                    return false;
   
                }
            });return false;
        }
    }
   
    function deleteAddress(id) {
        var str = "Are you sure want to delete this address";
        if(confirm(str)) {
            if (id != '') {
                $.ajax({
                    type: 'POST',
                    url: jssitebaseurl + 'customers/deleteAddress',
                    data: {id: id},
                    success: function (data) {
                        if (data == '0') {
                            window.location.reload();
                            return false;
                        }
   
                    }
                });
                return false;
            }
        }
   
    }
   
    function deleteCard(id) {
        var str = "Are you sure want to delete this card";
        if(confirm(str)) {
            if (id != '') {
                $.ajax({
                    type: 'POST',
                    url: jssitebaseurl + 'customers/deleteCard',
                    data: {id: id},
                    success: function (data) {
                        if (data == '0') {
                            window.location.reload();
                            return false;
                        }
   
                    }
                });
                return false;
            }
        }
   
    }
   
    // Create a Stripe client
    var stripe = Stripe('<?= STRIPE_PUBLISH; ?>');
   
    // Create an instance of Elements
    var elements = stripe.elements();
   
    // Custom styling can be passed to options when creating an Element.
    // (Note that this demo uses a wider set of styles than the guide below.)
    var style = {
        base: {
            color: '#32325d',
            lineHeight: '18px',
            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
            fontSmoothing: 'antialiased',
            fontSize: '16px',
            '::placeholder': {
                color: '#aab7c4'
            }
        },
        invalid: {
            color: '#fa755a',
            iconColor: '#fa755a'
        }
    };
   
    // Create an instance of the card Element
    var card = elements.create('card', {style: style});
   
    // Add an instance of the card Element into the `card-element` <div>
    card.mount('#card-element');
   
    // Handle real-time validation errors from the card Element.
    card.addEventListener('change', function(event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });
   
    // Handle form submission
    var form = document.getElementById('payment-form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();
   
        stripe.createToken(card).then(function(result) {
            if (result.error) {
                // Inform the user if there was an error
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
            } else {
                // Send the token to your server
                //stripeTokenHandler(result.token);
                //alert(result.token.id);return false;
                stripeTokenHandler(result.token);
            }
        });
    });
   
    function stripeTokenHandler(result) {
   
   
        // Insert the token ID into the form so it gets submitted to the server
        var form = document.getElementById('payment-form');
        var hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'stripe_token_id');
        hiddenInput.setAttribute('value', result.id);
        form.appendChild(hiddenInput);
   
        //Card Id
        var hiddenInput1 = document.createElement('input');
        hiddenInput1.setAttribute('type', 'hidden');
        hiddenInput1.setAttribute('name', 'card_id');
        hiddenInput1.setAttribute('value', result.card.id);
        form.appendChild(hiddenInput1);
   
        //Card Zipcode
        var hiddenInput2 = document.createElement('input');
        hiddenInput2.setAttribute('type', 'hidden');
        hiddenInput2.setAttribute('name', 'address_zip');
        hiddenInput2.setAttribute('value', result.card.address_zip);
        form.appendChild(hiddenInput2);
   
        //Card Brand
        var hiddenInput3 = document.createElement('input');
        hiddenInput3.setAttribute('type', 'hidden');
        hiddenInput3.setAttribute('name', 'card_brand');
        hiddenInput3.setAttribute('value', result.card.brand);
        form.appendChild(hiddenInput3);
   
        //Card country
        var hiddenInput4 = document.createElement('input');
        hiddenInput4.setAttribute('type', 'hidden');
        hiddenInput4.setAttribute('name', 'country');
        hiddenInput4.setAttribute('value', result.card.country);
        form.appendChild(hiddenInput4);
   
        //Card exp_month
        var hiddenInput5 = document.createElement('input');
        hiddenInput5.setAttribute('type', 'hidden');
        hiddenInput5.setAttribute('name', 'exp_month');
        hiddenInput5.setAttribute('value', result.card.exp_month);
        form.appendChild(hiddenInput5);
   
        //Card exp_year
        var hiddenInput6 = document.createElement('input');
        hiddenInput6.setAttribute('type', 'hidden');
        hiddenInput6.setAttribute('name', 'exp_year');
        hiddenInput6.setAttribute('value', result.card.exp_year);
        form.appendChild(hiddenInput6);
   
        //Card funding credit or debit
        var hiddenInput7 = document.createElement('input');
        hiddenInput7.setAttribute('type', 'hidden');
        hiddenInput7.setAttribute('name', 'card_type');
        hiddenInput7.setAttribute('value', result.card.funding);
        form.appendChild(hiddenInput7);
   
        //Card last4
        var hiddenInput8 = document.createElement('input');
        hiddenInput8.setAttribute('type', 'hidden');
        hiddenInput8.setAttribute('name', 'card_number');
        hiddenInput8.setAttribute('value', result.card.last4);
        form.appendChild(hiddenInput8);
   
        //Card client_ip
        var hiddenInput9 = document.createElement('input');
        hiddenInput9.setAttribute('type', 'hidden');
        hiddenInput9.setAttribute('name', 'client_ip');
        hiddenInput9.setAttribute('value', result.client_ip);
        form.appendChild(hiddenInput9);
   
   
        // Submit the form
        form.submit();
    }
    function showAddCard() {
   
        $("#showAllCard").css('display','none');
        $("#add_card_modal").css('display','block');
        return false;
    }
   
    function addMoney(amount) {
        $("#addAmount").val(amount);
    }
   
    function loadMoney() {
        $(".error").html('');
        $("#loadMoneyId").attr('disabled',true);
        var amount = $("#addAmount").val();
        var cardId = $('input:radio[name=load_card]:checked').val();
   
        if(amount == '') {
            $(".amountErr").addClass('error').html('Please enter the amount');
            $("#addAmount").focus();
            $("#loadMoneyId").attr('disabled',false);
            return false;
        }else if(amount != '' && amount <= 0) {
            $(".amountErr").addClass('error').html('Please enter valid amount');
            $("#addAmount").focus();
            $("#loadMoneyId").attr('disabled',false);
            return false;
   
        }else if(cardId == '' || cardId == undefined) {
            $(".cardErr").addClass('error').html('Please select any card');
            $("#loadMoneyId").attr('disabled',false);
            return false;
        }else {
            $(".ui-loader").css('display','block');
            $.ajax({
                type: 'POST',
                url: jssitebaseurl + 'customers/addMoney',
                data: {amount: amount,cardId:cardId},
                success: function (data) {
                    if (data == '0') {
                        window.location.reload();
                        return false;
                    }
                }
            });
            return false;
        }
    }
   
    function cityList() {
        var state_id = $.trim($("#state_id").val());
        var bySearch = $.trim($("#bySearch").val());
   
        $.ajax({
            type   : 'POST',
            url    : jssitebaseurl+'customers/ajaxaction',
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
                url    : jssitebaseurl+'customers/ajaxaction',
                data   : {city_id:city_id, bySearch:bySearch, action: 'getLocation'},
                success: function(data){
                    $('#locationList').html(data);
                    return false;
                }
            });
            return false;
        }
    }
   
   
    function editcityList() {
        var state_id = $.trim($("#editstate_id").val());
        var bySearch = $.trim($("#bySearch").val());
   
        $.ajax({
            type   : 'POST',
            url    : jssitebaseurl+'customers/ajaxaction',
            data   : {state_id:state_id, bySearch:bySearch, action: 'editgetCity'},
            success: function(data){
                $('#editcityList').html(data);
                return false;
            }
        });
        return false;
    }
   
   
    function editlocationList() {
        var state_id = $.trim($("#editstate_id").val());
        var city_id = $.trim($("#editcity_id").val());
        var bySearch = $.trim($("#bySearch").val());
        if(state_id != ''){
            $.ajax({
                type   : 'POST',
                url    : jssitebaseurl+'customers/ajaxaction',
                data   : {city_id:city_id, bySearch:bySearch, action: 'editgetLocation'},
                success: function(data){
                    $('#editlocationList').html(data);
                    return false;
                }
            });
            return false;
        }
    }
   
    function reviewPopup(id){
       $("#reviewPopup").modal('show');
       $('#order_id').val(id);
    }
   
     function writeReview(){
       $("#reviewPopup").modal('show');
    }

    $(".view-referrals").click(function(){
       $(".table-view-referrals").slideToggle("slow");
        $("i", this).toggleClass("fa fa-plus fa fa-minus");
   });

    function copyUrl() {

        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($("#referralUrl").val()).select();
        document.execCommand("copy");
        $temp.remove();
        $("#copyBtn").val('Copied');
        setTimeout(function(){
            $("#copyBtn").val('Copy');
        },3000);
    }

    //twitter page sharing
    var twitterShare = document.querySelector('[data-js="twitter-share"]');

    twitterShare.onclick = function(e) {
        e.preventDefault();
        var twitterWindow = window.open('https://twitter.com/share?url=' + document.URL, 'twitter-popup', 'height=350,width=600');
        if(twitterWindow.focus) { twitterWindow.focus(); }
        return false;
    }
    //facebook page sharing

    var facebookShare = document.querySelector('[data-js="facebook-share"]');

    facebookShare.onclick = function(e) {
        e.preventDefault();
        var facebookWindow = window.open('https://www.facebook.com/sharer/sharer.php?u=' + document.URL, 'facebook-popup', 'height=350,width=600');
        if(facebookWindow.focus) { facebookWindow.focus(); }
        return false;
    }
</script>

<script>
    paypal.Button.render({

        env: '<?= MODE ?>', // sandbox | production

        // PayPal Client IDs - replace with your own
        // Create a PayPal app: https://developer.paypal.com/developer/applications/create
        client: {
            <?= MODE ?>:    '<?= CLIENT_ID ?>',
        },

        // Show the buyer a 'Pay Now' button in the checkout flow
        commit: true,

        // payment() is called when the button is clicked
        payment: function(data, actions) {

            var amount = $.trim($("#addAmount").val());

            // Make a call to the REST api to create the payment
            return actions.payment.create({
                payment: {
                    transactions: [
                        {
                            amount: { total: amount, currency: 'USD' }
                        }
                    ]
                }
            });
        },

        // onAuthorize() is called when the buyer approves the payment
        onAuthorize: function(data, actions) {

            // Make a call to the REST api to execute the payment
            return actions.payment.execute().then(function() {
                var amount = $.trim($("#addAmount").val());
                if(data.payerID != '' && data.paymentToken != '' && data.paymentID != '') {
                    $(".ui-loader").css('display','block');
                    $.ajax({
                        type: 'POST',
                        url: jssitebaseurl + 'customers/addMoneyPaypal',
                        data: {amount: amount,payment_id:data.paymentID},
                        success: function (data) {
                            if (data == '0') {
                                window.location.reload();
                                return false;
                            }
                        }
                    });
                    return false;
                }
            });
        },
        onCancel: function (data) {

        },
        onError: function (err) {
            alert('Please select or entered valid amount');return false;
        }

    }, '#paypal-button-container');

</script>

