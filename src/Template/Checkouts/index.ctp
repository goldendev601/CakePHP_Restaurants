<script src="https://checkout.stripe.com/checkout.js"></script>
<script src="https://www.paypalobjects.com/api/checkout.js"></script>

<div class="container">
   <section class="main_wrapper">
      <div class="col-xs-12 col-sm-8 col-md-8 no-padding-left no-xs-padding">
         <div class="checkoutScrollWrapper">
         <?= $this->Form->create('checkoutForm',[
            'id' => 'checkoutForm',
            'url' => '/checkouts/placeOrder'
         ]);?>
         
         <input type="hidden" id="order_type" value="<?php echo $orderType ?>" name="order_type">
         <input type="hidden" id="paidFull" value="" name="paidFull">
         <input type="hidden" id="customerPhone" value="<?= $userDetails['phone_number'] ?>" name="">
         <input type="hidden" name="res-sp-token" id='res-sp-token' value="">
         <?php
          echo $this->Form->input('bySearch', [
              'id' => 'bySearch',
              'type' => 'hidden',
              'class' => 'form-horizontal',
              'value'=> SEARCHBY
          ]);?>          

          <input type="hidden" name="payerID" id='payerID' value="">
          <input type="hidden" name="paymentToken" id='paymentToken' value="">
          <input type="hidden" name="paymentID" id='paymentID' value="">
          <input type="hidden" name="payButton" id='payButton' value="">

         <div class="cust-info-checkout">
            <div class="col-sm-1 hidden-xs pul-right"><span class="delivery_count">1</span></div>
            <div class="col-sm-11 col-xs-12 no-xs-padding pul-left">
               <div class="deliveryaddr">
                  <div class="cust-info-head">
                     <?= ($orderType != 'pickup') ? 'Delivery Address &' : 'Pickup' ?><?php echo __('Timings');?>
                  </div>
                  <?php if($orderType != 'pickup') { ?>
                  <div class="add_new_address_div">
                     <a href="javascript:void(0);" data-target="#add_address_modal" data-toggle="modal" class="addnewAdrr"><img src="/images/plus.png"> <?php echo __('Add New Delivery Address');?></a>
                  </div>
                  <div class="checkoutWrapper clearfix">
                     <div class="row">
                        <?php if(!empty($addressBookLists)) {
                           foreach ($addressBookLists as $aKey => $aValue) { ?>
                        <div class="col-md-6 col-sm-6 m-b-15">
                           <label class="editAdrr deliveryRadio_<?= $aValue['id'] ?> <?= ($aKey == 0) ? 'active' : '' ?>">
                              <input onclick="showDeliveryCharge(<?= $aValue['id'] ?>)" type="radio" name="checkout_address" <?= ($aKey == 0) ? 'checked' : '' ?> value="<?= $aValue['id'] ?>">
                              <address>
                                 <p class="address_name"><?= $aValue['title'] ?></p>
                                 <p class="text-overflow">

                                    <?php if(SEARCHBY == 'Google') { ?>
                                        <?= ($aValue['flat_no'] != '') ? $aValue['flat_no'].',' : '' ?> <?= $aValue['address'] ?>
                                    <?php }else { ?>
                                        <!--<input type="hidden" value="<?/*= $aValue['deliveryMin'] */?>" id="deliveryMin_<?/*= $aValue['id'] */?>">-->
                                        <?= ($aValue['flat_no'] != '') ? $aValue['flat_no'].',' : '' ?> <?= $aValue['address'] ?>
                                        <?php if(SEARCHBY == 'area') { ?>
                                            <?php echo (!empty($aValue['location'])) ? ','.$aValue['location']['area_name'] : '' ?>

                                            <?php echo (!empty($aValue['city'])) ? ','.$aValue['city']['city_name'] : '' ?>
                                        <?php }else { ?>
                                            <?php echo (!empty($aValue['city'])) ? ','.$aValue['city']['city_name'] : '' ?>

                                            <?php echo (!empty($aValue['location'])) ? ','.$aValue['location']['zip_code'] : '' ?>
                                        <?php } ?>
                                        <?php echo (!empty($aValue['state'])) ? ','.$aValue['state']['state_name'] : '' ?>

                                    <?php } ?>
                                 </p>
                              </address>
                           </label>
                           <input type="hidden" value="<?= $aValue['deliveryCharge'] ?>" id="deliveryCharge_<?= $aValue['id'] ?>">
                            <input type="hidden" value="<?= $aValue['deliveryMin'] ?>" id="deliveryMin_<?= $aValue['id'] ?>">
                        </div>
                        <?php
                           }
                        } ?>
                         <span class="emptyAddress"></span>
                     </div>
                  </div>
                  <?php } ?>
                  <div class="checkout_res_name"><?php echo __(($orderType != 'pickup') ? 'Delivery Details' : 'Pickup Details' );?>
                  </div>
                  <div class="col-xs-12 no-padding">
                     <?php if(!empty($final) && $final[0]['currentStatus'] == 'Open') { ?>
                     <div class="form-group clearfix">
                        <div class="now-radio">
                           <input id="now-option" type="radio" name="deliverytime" onchange="deliveryNow(this)" checked value="now">
                           <label for="now-option"><?php echo __('ASAP');?></label>
                            <div class="pickup-time-div" style="height: 40px;">                                        
                                <span style="font-weight: 700; color: #e55b03; font-size: 20px;">
                                    <strong>
                                    <?php 
                                        date_default_timezone_set($restaurantDetails['timezoneList']);

                                        $date = date('H:i');
                                        $val_minimum_pickup_time = intval($restaurantDetails['minimum_pickup_time']);
                                        $val = '+'.$val_minimum_pickup_time.' minute';
                                        $due_date = date('g:i A', strtotime($val, strtotime($date)));
                                        echo $due_date;
                                    ?>
                                    </strong> Pickup Time 
                                </span>
                            </div>
                        </div>
                     </div>

                     <div class="col-sm-2 no-padding-left pul-right no-rtlpadding-right">
                       <div class="form-group clearfix">
                          <div class="later-radio">
                             <input id="late-option" type="radio" name="deliverytime" onchange="deliveryLater(this)" value="later">
                             <label for="late-option"><?php echo __('Later');?></label>
                          </div>
                       </div>
                     </div>

                     <?php }else { ?>                     
                       <div class="form-group clearfix">
                          <div class="later-radio">
                             <input id="late-option" checked type="radio" name="deliverytime" onchange="deliveryLater(this)" value="later">
                             <label for="late-option"><?php echo __('Later');?></label>
                          </div>
                       </div>                     
                     <?php } ?>

                      <div class="col-sm-10 col-md-10 no-padding">
                          <div style="display: none;" class="col-xs-12 no-padding m-b-20" id="showCalendar">
                              <div class="col-sm-4 col-xs-12 no-padding pul-right">
                                  <div class="choose_txt"><?php echo __('Choose');?> <?= ($orderType != 'pickup') ? 'Delivery' : 'Pickup' ?> <?php echo __('Date & Time');?></div>
                              </div>
                              <div class="col-sm-8 col-xs-12 no-padding-right no-xs-padding-left pul-left no-rtlpadding-left rtl-padding-R15">
                                  <div class="col-xs-12 col-sm-6 no-padding-left no-xs-padding m-b-xs-15">
                                      <input type="text" value="<?= date('Y-m-d') ?>" id="latertime" placeholder="30-12-2017" class="form-control no-radius address-customer-notes" name="delivery_date">
                                      <i class="fa fa-calendar" aria-hidden="true"></i>
                                  </div>
                                  <div class="col-xs-12 col-sm-6 no-padding no-xs-padding" id="timeLists">
                                      <?php if(!empty($array_of_time)) { ?>
                                          <select id="getDateTime" class="form-control no-radius" name="delivery_time">
                                              <?php
                                              foreach ($array_of_time as $key => $value) { ?>
                                                  <option value="<?php echo $value ?>"><?php echo $value ?></option>
                                                  <?php
                                              } ?>
                                          </select>
                                      <?php }else { ?>
                                          <input type="text" readonly class="form-control" id="deliveryTime" value="Closed">
                                          <?php
                                      } ?>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
                 
                  <span class="emptyTime"></span>
                  <div class="apply_voucher_part">
                     <div class="form-group clearfix">
                        <label><?php echo __('Any instructions for delivery (optional)');?></label>
                        <textarea name="order_description" id="order_description" class="form-control address-customer-notes" placeholder="Enter your comments here" rows="2" cols="30"></textarea>
                     </div>
                  </div>

               </div>
            </div>
         </div>

        <div class="cust-info-checkout">
            <div class="col-sm-1 hidden-xs pul-right"><span class="delivery_count">2</span></div>
            <div class="col-sm-11 col-xs-12 no-padding-right no-xs-padding pul-left">
               <div class="cust-info-head"><?php echo __('My Wallet');?></div>
                  <div class="col-sm-6 col-md-3 col-xs-12 no-padding-left pul-right no-rtlpadding-right">
                      <div class="use_wallet">
                          <?php if($userDetails['wallet_amount'] > 0){ ?>
                                <input type="checkbox" value="Yes" name="payment_wallet" id="use_wallet" onclick="return walletAmount()">
                          <?php } ?>
                          <label class="use_wallet_balance" for="use_wallet">
                             <div class="use_wallet_txt"><img src="/images/wallet.png"><?php echo __('My Wallet');?></div>
                          </label>
                          <input type="hidden" id="walletBalance" value="<?= $userDetails['wallet_amount'] ?>">
                       </div>
                  </div>
                  <div class="col-sm-6 col-md-4 col-xs-12 no-padding pul-right">
                      <div class="paymade_txt"><?php echo __('Current Balance');?><span class="paymade_amt"><?= $siteSettings['site_currency'] ?> <?= number_format($userDetails['wallet_amount'],2) ?></span></div>
                  </div>
                  <div class="col-sm-7 col-md-5 col-xs-12 no-padding text-right left-text pul-right" id="paidAmount" style="display: none">
                      <div class="paymade_txt"><?php echo __('Paid from My Wallet');?><span class="paymade_amt orage-colr"><?= $siteSettings['site_currency'] ?><span class="paidAmt"></span></span></div>
                  </div>
            </div>
         </div>
         <div class="cust-info-checkout <?= (empty($array_of_time)) ? 'mask_checkout': '' ?>" id="paymentInfo">
             <!--mask_checkout-->
            <div class="col-sm-1 hidden-xs pul-right"><span class="delivery_count"><?php echo ($userDetails['wallet_amount'] > 0) ? '3' : '2' ?></span></div>
            <div class="col-sm-11 col-xs-12 no-xs-padding pul-left">
               <div class="cust-info-head"><?php echo __('Payment Details');?><span  class="m-l-10" id="paymentErr"></span></div>
               <div class="col-xs-12 col-sm-4 col-md-4 no-padding left-payment-sec pul-right">
                  <div class="payment_tab">
                     <ul>
                         <?php if (!empty($paymentDetails)) {
                             $ccod = 0;
                             $hheartland = 0;
                             $ppaypal = 0;
                             foreach ($paymentDetails as $key => $val ) {

                                 if ($val['payment_method']['payment_method_name'] == 'COD') {
                                     $ccod = 1;
                                     ?>
                                     <li>
                                         <input type="radio" name="payment_method" id="cash" checked="checked" value="cod" onclick="return hidePayapl();">
                                         <label data-check="cod" for="cash"><i class="fa fa-money"></i>
                                             <span class="hidden-xs"><?php echo __('COD');?></span></label>
                                     </li>

                                  <?php } else if ($val['payment_method']['payment_method_name'] == 'Heartland') {
                                     $hheartland = 1;
                                     ?>
                                     <li>
                                         <input <?= ($ccod == 0) ? 'checked' : '' ?> type="radio" name="payment_method" id="ccpayment" value="heartland" onclick="return hidePayapl();">
                                         <label data-check="credit_card" for="ccpayment"><i class="fa fa-credit-card"></i> <span class="hidden-xs">Heartland</span></label>
                                     </li>

                                 <?php } else if ($val['payment_method']['payment_method_name'] == 'Paypal') {?>
                                     <li>
                                         <input <?= ($ccod == 0 && $hheartland == 0) ? 'checked' : '' ?> type="radio" name="payment_method" id="paypalPay" value="paypal" onclick="return showPaypal();">
                                         <label data-check="paypal" for="paypalPay"><i class="fa fa-paypal"></i>
                                             <span class="hidden-xs"><?php echo __('PayPal');?></span></label>
                                     </li>
                                 <?php }
                             }
                         }?>
                     </ul>
                  </div>
               </div>
               <div class="col-xs-12 col-sm-8 col-md-8 pull-right no-xs-padding pul-left">
                  <div class="tab-content payment_tab_content">

                      <?php if (!empty($paymentDetails)) {
                          $cod = 0;
                          $stripe = 0;
                          $paypal = 0;
                          foreach ($paymentDetails as $key => $val ) {

                              if ($val['payment_method']['payment_method_name'] == 'COD') {
                                  $cod = 1;
                                  ?>
                                  <div id="cod" class="common_content">
                                      <div class="col-sm-5 col-md-5 col-xs-5 no-xs-padding-left pul-right">
                                          <img src="/images/cod2.png">
                                      </div>
                                      <div class="col-sm-7 col-md-7 col-xs-7 no-padding-left no-xs-padding-right pul-left">
                                          <p class="cash_txt"><?php echo __('CASH');?></p>
                                          <div><?php echo __('Please keep the exact change to help us to provide you a better service');?></div>
                                      </div>
                                  </div>
                              <?php } else if ($val['payment_method']['payment_method_name'] == 'Stripe') {
                                  $stripe = 1;
                                  ?>
                                  <div id="credit_card" class="common_content" style="<?= ($cod != 0) ? 'display:none': '' ?>">
                                      <div class="creditcard_conent">
                                          <div  id="payment_content" class="load_money_card">
                                              <span class="stripeErr"></span>
                                              <!-- <?php if(count($userDetails['stripe_customers']) < 3) { ?>
                                                  <div class="col-xs-12 m-t-10 m-b-10 no-padding m-t-xs-0">
                                                      <span class="pull-right"><button onclick="return showAddCard();" class="btn btn-deafult add_card_btn"><?php echo __('Add Card');?></button></span>
                                                  </div>
                                              <?php } ?> -->

                                              <?php if(!empty($userDetails['stripe_customers'])) {
                                                  foreach ($userDetails['stripe_customers'] as $cKey => $cValue) { ?>

                                                      <div class="col-xs-12 no-padding">
                                                          <input type="radio" value="<?= $cValue['id'] ?>" id="ccard_select_<?= $cValue['id'] ?>" name="credit_card_choose">
                                                          <label for="ccard_select_<?= $cValue['id'] ?>">
                                                              <div class="credit_card">
                                                                  <div class="credit_cardrow">
                                                                      <div class="visa_img"><img src="/images/master.png"></div>
                                                                      <div class="credit_det">
                                                                          <div class="card_number">XXXX-XXXXXXXX-<?= $cValue['card_number'] ?> </div>
                                                                          <div class="card_validity"><?php echo __('Valid till');?> <?= $cValue['exp_month'] ?>/<?= $cValue['exp_year'] ?></div>
                                                                      </div>
                                                                  </div>
                                                              </div>
                                                          </label>
                                                      </div>
                                                      <?php
                                                  }
                                              }else { ?>
                                                  <?php echo __('No Record Found');?>
                                              <?php } ?>
                                          </div>
                                      </div>
                                  </div>
                              <?php } else if ($val['payment_method']['payment_method_name'] == 'Heartland') {
                                  $heartland = true;
                                  ?>
                                  <div id="credit_card" class="common_content" style="<?= ($cod != 0) ? 'display:none': '' ?>">
                                      <div class="creditcard_conent">
                                          <div  id="payment_content" class="load_money_card">
                                              <span class="heartlandErr"></span>
                                              <?php if(count($userDetails['stripe_customers']) < 3) { ?>
                                                  <div class="col-xs-12 m-t-10 m-b-10 no-padding m-t-xs-0">
                                                      <span class="pull-right"><button onclick="return showAddCard();" class="btn btn-deafult add_card_btn"><?php echo __('Add Card');?></button></span>
                                                  </div>
                                              <?php } ?>

                                              <?php if(!empty($userDetails['stripe_customers'])) {
                                                  foreach ($userDetails['stripe_customers'] as $cKey => $cValue) { ?>

                                                      <div class="col-xs-12 no-padding">
                                                          <input type="radio" value="<?= $cValue['id'] ?>" id="ccard_select_<?= $cValue['id'] ?>" name="credit_card_choose">
                                                          <label for="ccard_select_<?= $cValue['id'] ?>">
                                                              <div class="credit_card">
                                                                  <div class="credit_cardrow">
                                                                      <div class="visa_img"><img src="/images/master.png"></div>
                                                                      <div class="credit_det">
                                                                          <div class="card_number">XXXXXXXXXXXX<?= $cValue['card_number'] ?> </div>
                                                                          <div class="card_validity"><?php echo __('Valid till');?> <?= $cValue['exp_month'] ?>/<?= $cValue['exp_year'] ?></div>
                                                                      </div>
                                                                  </div>
                                                              </div>
                                                          </label>
                                                      </div>
                                                      <?php
                                                  }
                                              }else { ?>
                                                  <?php echo __('No cards available');?>
                                              <?php } ?>
                                          </div>
                                      </div>
                                  </div>
                              <?php } else if ($val['payment_method']['payment_method_name'] == 'Paypal') {?>
                                  <div id="paypal" class="common_content" style=" <?= ($cod != 0 || $stripe != 0) ? 'display:none': '' ?>" >
                                      <div class="col-sm-5 col-md-5 col-xs-5 no-xs-padding-left pul-right">
                                          <img src="/images/paypal.png">
                                      </div>
                                      <div class="col-sm-7 col-md-7 col-xs-7 no-padding-left no-xs-padding-right pul-left">
                                          <p class="cash_txt"><?php echo __('PayPal');?></p>
                                          <div><?php echo __('Please pay using by Paypal');?></div>
                                      </div>
                                  </div>
                              <?php }
                          }
                      }?>
                  </div>
               </div>
               <?=  $this->Form->end(); ?>
            </div>
         </div>
      </div>
      </div>
      <div class="col-xs-12 col-sm-4 col-md-4 no-padding">
         <div class="order_information_box2">
            <div class="order_inner_box2">
            <div class="col-xs-12 text-center no-padding">
               <div class="text-centet add_more_item"><a href="<?= BASE_URL ?>menu/<?= $restaurantDetails['seo_url'] ?>"><?php echo __('Add More Items');?></a></div>
            </div>
            <div class="right_res_name"><a><?php echo __('Your Ordered Items');?></a></div>
            <div class="view_cart_item"><a><?php echo __('View cart items');?></a></div>
            <div class="hide_cart_item">
               <div class="order_item">
                  <div class="order-item-name"><?php echo __('Item');?></div>
                  <div class="order-item-qty"><?php echo __('Qty');?></div>
                  <div class="order-item-price"><?php echo __('Price');?></div>
               </div>
               <?php if(!empty($cartsDetails)) {
                   foreach ($cartsDetails as $cKey => $cValue) { ?>
                       <div class="order_item_list">
                           <div class="order-item-namelist">
                               <?= $cValue['menu_name'] ?>
                               <?php
                               if ($cValue['subaddons_name'] != '') { ?>
                                   <br><?= $cValue['subaddons_name'] ?>
                                   <?php
                               }
                               ?>
                           </div>
                           <div class="order-item-qtylist"><?= $cValue['quantity'] ?></div>
                           <div class="order-item-pricelist"><?= ($siteSettings['site_currency']) ?> <?= number_format($cValue['total_price'], 2) ?></div>
                       </div>
                       <?php
                   }
               }?>                             
            </div>
            <div class="hide_cart_text">
               <a><?php echo __('hide cart items');?></a>
            </div>
            </div>
         </div>
      </div>
      <div class="col-xs-12 col-sm-4 col-md-4 no-padding m-t-20 m-t-xs-0 m-b-xs-15">
         <div class="order_information_inner">
            <div class="order_information_box">
               <div class="warehouse_head cust-info-head">
                  <span>
                      <img src="<?php echo BASE_URL.'uploads/storeLogos/'.$restaurantDetails['logo_name'] ?>" height="100" width="150" onerror="this.src='<?php echo BASE_URL;?>webroot/images/no_store.jpg'">
                  </span>
                  <span class="check_res_name"><?= $restaurantDetails['restaurant_name'] ?><br><span><?= $restaurantDetails['contact_address'] ?></span></span>
               </div>

               <div class="order_item_list">
                  <div class="order-total-txt"><?php echo __('Subtotal');?></div>
                  <div class="order-total-amount"><?= ($siteSettings['site_currency']) ?> <span class="subTotal"><?= number_format($subTotal,2) ?></span></div>
               </div>

               <?php if($orderType != 'pickup' && isset($deliveryCharge)) { ?>
                 <div class="order_item_list">
                    <div class="order-total-txt"><?php echo __('Delivery Fee');?></div>
                    <div class="order-total-amount"><?= ($deliveryCharge > 0) ? $siteSettings['site_currency'] : '' ?> <span id="deliveryAmt"> <?php echo ($deliveryCharge > 0) ? number_format($deliveryCharge,2) : 'Free' ?> </span></div>
                 </div>
               <?php } ?>

               <div class="order_item_list">
                  <div class="order-total-txt"><?php echo __('Tax');?> (<?= $restaurantDetails['restaurant_tax'] ?> %)</div>
                  <div class="order-total-amount"><?= ($siteSettings['site_currency']) ?> <span class="taxTotal"><?= number_format($taxAmount,2) ?></span></div>
               </div>

                <div class="order_item_list">
                    <div class="order-total-txt"><strong><?php echo __('Total Amount');?></strong></div>
                    <div class="order-total-amount"><strong><?= ($siteSettings['site_currency']) ?> <span class="normalTotal"><?= number_format($normalTotal,2) ?></span></strong></div>
                </div>

               <?php if($this->request->session()->read('offerAmount') != '') { ?>
                   <div class="offer-sec m-b-10">
                       <span class="pull-left"><?php echo __('Offer Amount');?> (<?= $this->request->session()->read('offerPercentage') ?> %)</span>
                       <span class="order-total-amount pull-right"><?= ($siteSettings['site_currency']) ?> <span class="offerTotal"><?= number_format($this->request->session()->read('offerAmount'),2) ?> (-)</span>
                   </div>
               <?php } ?>

                <div class="coupon_part" style="<?= ($offerMode == '') ? 'display:block': 'display:none' ?>">
                    <span class="pull-left coupon_txt col-sm-6 no-padding pul-right">
                        <input id="vouchercode" type="text" placeholder=" Have a coupon code?">
                    </span>
                    <span class="pull-right apply_coup pul-left"><a><?php echo __('APPLY COUPON');?></a></span>
                    <button class="pull-right apply_submit pul-left" style="display:none;" onclick="return applyCoupon(this)">Submit</button>
                </div>
                <span class="voucherErr"></span>

                <?php if($this->request->session()->read('rewardPoint') != '') { ?>
                    <div class="offer-sec m-b-10">
                        <span class="pull-left pul-right"><?php echo __('Redeem Amount');?> (<?= $this->request->session()->read('rewardPercentage') ?> %)</span>
                        <span class="order-total-amount pull-right pul-left"><?= ($siteSettings['site_currency']) ?> <span class="redeemTotal"><?= number_format($this->request->session()->read('rewardPoint'),2) ?> (-)</span>
                    </div>

                <?php } ?>

                <div class="order_item_list" id="voucherSection" style="<?= ($offerMode == '') ? 'display:none': 'display:block' ?>">
                    <div class="order-total-txt"><?php echo __('Vouchers');?></div>
                    <div class="order-total-amount"><?= ($siteSettings['site_currency']) ?>
                        <span class="voucherAmt"><?= number_format($voucherAmount,2) ?></span>(-)
                        <a onclick="return removeVoucher()">X</a>
                    </div>
                </div>

                 <div class="wallet-sec" style="display:none;">
                    <span class="pull-left pul-right"><img src="/images/wallet.png"><?php echo __('My Wallet');?></span>
                    <span class="order-total-amount pull-right pul-left"><?= ($siteSettings['site_currency']) ?> <span class="walletAmt"></span>(-)</span>
                </div>
                
                <div class="order_item_list">
                  <div class="order-total-txt"><strong><?php echo __('Payable Amount');?></strong></div>
                  <div class="order-total-amount"><strong><?= ($siteSettings['site_currency']) ?> <span class="totalAmt" id="totalOrderAmt"><?= number_format($totalAmount,2) ?></span></strong></div>
                </div>

               

                <!-- <div class="order_item_list" style="display: none" id="walletSection">
                  <div class="order-total-txt"><strong>Wallet Amount</strong></div>
                  <div class="order-total-amount"><strong><?= ($siteSettings['site_currency']) ?> <span class="walletAmt"></span></strong></div>
               </div> -->

                <div id="overAllBtn">
                    <div class="col-xs-12 no-padding" id="placeButton">
                        <button onclick="return placeOrder();" id="ordeSubmitbutton" class="btn btn-default subbtn checkoutBtn" type="submit" name="" value="Place Order"><?php echo __('Place Order');?> </button>
                    </div>

                    <div id="payaplButton" class="col-xs-12 no-padding" style="display: none"></div>

                </div>
                <?php if($this->request->session()->read('orderPoint') != '') { ?>
                    <div class="min-order-value reward_earning m-t-10"> <span class="reward_trophy"><i class="fa fa-trophy"></i></span><?php echo __('You will earn');?> <?= floor($this->request->session()->read('orderPoint')) ?> <?php echo __('Points') ?> </div>
                <?php } ?>


                <?php if($needOrderCount > 0) { ?>
                    <div id="blink_text" class="min-order-value reward_offer m-t-10"><span class="reward_offer_icon"><i class="fa fa-shield"></i></i></span><?= $needOrderCount ?> <?php echo __('more orders to get rewards offer');?></div>
                <?php } ?>




            </div>

         </div>
      </div>
   </section>
</div>
<div class="modal fade" id="add_address_modal" role="dialog">
   <div class="modal-dialog modal-md">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?php echo __('Add New Deliver Address');?></h4>
         </div>
            <div class="modal-body">
                <label class="checkAdderorr"></label>
                <div class="form-group clearfix">
                    <label class="control-label col-md-4 pul-right"><?php echo __('Address Title');?></label>
                    <div class="col-md-8 pul-left">
                        <div class="input text required">
                            <input type="text" class="form-control my-form-control" id="title">
                        </div>
                        <span class="titleErr"></span>
                    </div>
                </div>
                <div class="form-group clearfix">
                    <label class="control-label col-md-4 pul-right"><?php echo __('Door no / Flat no');?></label>
                    <div class="col-md-8 pul-left">
                        <div class="input text">
                            <input type="text" class="form-control my-form-control" id="flatno">
                        </div>
                        <span class="flatnoErr"></span>
                    </div>
                </div>
                <?php if(SEARCHBY == 'Google') { ?>
                    <div class="form-group clearfix">
                       <label class="control-label col-md-4 pul-right"><?php echo __('Address');?></label>
                       <div class="col-md-8 pul-left">
                          <div class="input text">
                             <input type="text"  class="form-control my-form-control checkoutAddress" id="address">
                          </div>
                       </div>
                    </div>
                <?php }else { ?>

                    <div class="form-group clearfix">
                        <label class="control-label col-md-4 pul-right"><?php echo __('Street Address');?></label>
                        <div class="col-md-8 pul-left">
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
                        <label class="control-label col-md-4 pul-right"><?php echo __('State');?></label>
                        <div class="col-md-8 pul-left">
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
                        <label class="control-label col-md-4 pul-right"><?php echo __('City');?></label>
                        <div class="col-md-8 pul-left">
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
                        <label class="control-label col-md-4"><?php echo __('Area/Zipcode');?></label>
                        <div class="col-md-8">
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
                <div class="form-group clearfix">
                   <div class="col-md-12">
                      <button onclick="return addAddress();" type="submit" class="btn btn-primary view-btn pop-btn"><?php echo __('Submit');?></button>
                   </div>
                </div>
            </div>
         </div>
      </div>
   </div>
<div class="modal fade" id="add_card_modal" role="dialog">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?php echo __('Add Your New Card');?></h4>
         </div>
         <div class="modal-body clearfix">
             <!-- <script src="https://js.stripe.com/v3/"></script> -->
            <script src="https://api2.heartlandportico.com/securesubmit.v1/token/gp-1.0.1/globalpayments.min.js"></script>
            <style>
              #payment-form iframe { min-height: 2em; }
            </style>

             <form action="" method="post" id="payment-form">
                <input type="hidden" value="addCard" name="action">
                <div class="form-row">
                    <label for="billing-zip-code">
                        <?php echo __('Billing Zip Code'); ?>
                    </label>
                    <div>
                        <input type="text" name="address_zip" />
                    </div>
                    <label for="credit-card-card-number">
                        <?php echo __('Credit or debit card');?>
                    </label>
                    <div id="credit-card-card-number"></div>
                    <label for="credit-card-card-number">
                        <?php echo __('Expiration Date');?>
                    </label>
                    <div id="credit-card-card-expiration"></div>
                    <label for="credit-card-card-cvv">
                        <?php echo __('Security Code');?>
                    </label>
                    <div id="credit-card-card-cvv"></div>

                    <!-- Used to display form errors -->
                    <div id="card-errors" role="alert"></div>
                </div>
                <div id="credit-card-submit"></div>
            </form>
            
         </div>
      </div>
   </div>
</div>

<div class="modal fade" id="userinfoModal" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo __('Update Profile');?></h4>
            </div>
            <div class="modal-body">
                <label class="checkAdderorr"></label>
                <div class="form-group clearfix">
                    <label class="control-label col-md-4 pul-right"><?php echo __('Phone Number');?></label>
                    <div class="col-md-8 pul-left">
                        <div class="input text required">
                            <input type="text" class="form-control my-form-control" id="phone_number" maxlength="15">
                        </div>
                        <span class="PhoneErr"></span>
                    </div>
                </div>

                <div class="form-group clearfix">
                    <div class="col-md-12">
                        <button onclick="return updatePhone();" type="submit" class="btn btn-primary view-btn pop-btn"><?php echo __('Submit');?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="useremailModal" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo __('Update Profile');?></h4>
            </div>
            <div class="modal-body">
                <label class="checkAdderorr"></label>
                <div class="form-group clearfix">
                    <label class="control-label col-md-4 pul-right"><?php echo __('Email Id');?></label>
                    <div class="col-md-8 pul-left">
                        <div class="input text required">
                            <input type="text" class="form-control my-form-control" id="username">
                        </div>
                        <span class="usernameErr"></span>
                    </div>
                </div>

                <div class="form-group clearfix">
                    <div class="col-md-12">
                        <button onclick="return updateEmail();" type="submit" class="btn btn-primary view-btn pop-btn"><?php echo __('Submit');?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function updatePhone() {
        var phone_number = $.trim($("#phone_number").val());
        if(phone_number == '') {
            $(".PhoneErr").addClass('error').html('Please enter your phone number');
            return false
        }else {
            $.ajax({
                type   : 'POST',
                url    : jssitebaseurl+'checkouts/updateProfile',
                data   : {phone_number:phone_number},
                success: function(data){
                    if($.trim(data) == 0) {
                        $("#customerPhone").val(phone_number);
                        $("#userinfoModal").modal('hide');
                        return false;
                    }
                    return false;

                }
            });return false;
        }
    }

    function updateEmail() {
        var username = $.trim($("#username").val());
        if(username == '') {
            $(".usernameErr").addClass('error').html('Please enter your email id');
            return false
        }else {
            $.ajax({
                type   : 'POST',
                url    : jssitebaseurl+'checkouts/updateProfile',
                data   : {username:username},
                success: function(data){
                    if($.trim(data) == 0) {
                       // $("#customerPhone").val(phone_number);
                        $("#useremailModal").modal('hide');
                        return false;
                    }
                    return false;

                }
            });return false;
        }
    }

    function addAddress() {
        $(".error").html('');
        var title = $.trim($("#title").val());
        var flatno = $.trim($("#flatno").val());
        var address = $.trim($("#address").val());
        var bySearch = $.trim($("#bySearch").val());

        var street_address = $.trim($("#street_address").val());
        var state_id    = $.trim($("#state_id").val());
        var city_id     = $.trim($("#city_id").val());
        var location_id = $.trim($("#location_id").val());


        if(title == '') {
            $(".titleErr").addClass('error').html('Please enter your title');
            $("#title").focus();
            return false;
        }else if(flatno == '') {
            $(".flatnoErr").addClass('error').html('Please enter your flatno');
            $("#flatno").focus();
            return false;
        }else if(address == '' && bySearch == 'Google') {
            $(".addressErr").addClass('error').html('Please enter your address');
            $("#address").focus();
            return false;
        }else if(bySearch != '' && bySearch != 'Google' && street_address == '') {
            $(".streetErr").addClass('error').html('Please enter your street');
            $("#street_address").focus();
            return false;
        }else if(bySearch != '' && bySearch != 'Google' && state_id == '') {
            $(".stateErr").addClass('error').html('Please select your state');
            $("#state_id").focus();
            return false;
        }else if(bySearch != '' && bySearch != 'Google' && city_id == '') {
            $("#contactInfo").click();
            $(".cityErr").addClass('error').html('Please  select your city');
            $("#city_id").focus();
            return false;
        }else if(bySearch != '' && bySearch != 'Google' && location_id == '') {
            $(".locationErr").addClass('error').html('Please select your location');
            $("#location_id").focus();
            return false;
        }else {
            $.ajax({
                type   : 'POST',
                url    : jssitebaseurl+'checkouts/addAddress',
                data   : {title:title,flat_no:flatno,address:address,street_address:street_address,state_id:state_id,city_id:city_id,location_id:location_id},
                success: function(data){

                    if($.trim(data) == 0) {
                        window.location.reload();
                        return false;
                    }else if($.trim(data) == 1) {
                        $(".titleErr").addClass('error').html('Required Fields Missing');
                        $("#title").focus();
                        return false;
                    }else if($.trim(data) == 2) {
                        $(".titleErr").addClass('error').html('Title Already exists');
                        $("#title").focus();
                        return false;
                    }else if($.trim(data) == 'error') {
                        $(".checkAdderorr").addClass('error').html('Sorry! Service not Available in this location');
                        $("#address").focus();
                        return false;
                    }
                    return false;
   
               }
           });return false;
       }
   }
   
   $(document).ready(function () {

       var paymentMethod = $('input:radio[name=payment_method]:checked').val();
       if(paymentMethod == 'paypal') {
           showPaypal();
       }else {
           hidePayapl();
       }
       initialize();
       checkMinimum();
       walletAmount();

       $.ajax({
           type   : 'POST',
           url    : jssitebaseurl+'checkouts/checkData',
           data   : {},
           success: function(data){
               console.log(data);
               if($.trim(data) == 'both') {
                   $("#userinfoModal").modal('show');
               }else if($.trim(data) == 'phone') {
                   $("#userinfoModal").modal('show');
               }else if($.trim(data) == 'email') {
                   $("#userinfoModal").modal('show');
               }
           }
       });return false;


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
   
   function showDeliveryCharge(id) {
        $(".editAdrr").removeClass('active')
        $(".deliveryRadio_"+id).addClass('active');
        var deliveryCharge = $("#deliveryCharge_"+id).val();
   
        if(deliveryCharge == 0) {
            $("#deliveryAmt").html('Free');
        }else {
            $("#deliveryAmt").html(deliveryCharge);
        }

        $("#deliveryAmtItem").html(deliveryCharge);
   
       /*var subtotal = $(".subTotal").html();
       var subtotal = parseFloat(subtotal.replace(',',''));

       var taxtotal = $(".taxTotal").html();
       var taxtotal = parseFloat(taxtotal.replace(',',''));

       var deliveryCharge = parseFloat(deliveryCharge.replace(',',''));
   
       var TotalAmount = subtotal;

       if(TotalAmount <= 0) {
           TotalAmount = deliveryCharge;
       }else {
           TotalAmount = TotalAmount + deliveryCharge + taxtotal;
       }
   
       $(".totalAmt").html(parseFloat(TotalAmount).toFixed(2));
       $("#totalOrderAmt").html(parseFloat(TotalAmount).toFixed(2));
       $(".itemTotalAmt").html(parseFloat(TotalAmount).toFixed(2));*/

       checkMinimum();
       walletAmount();
   
       return false;
   }
   
   function placeOrder() {
   
       //$("#ordeSubmitbutton").prop('disabled',true);
       $(".error").html('');
       var customerPhone = $("#customerPhone").val();
       var deliveryAddress = $('input:radio[name=checkout_address]:checked').val();
       var stripeId = $('input:radio[name=credit_card_choose]:checked').val();
       var paymentMethod = $('input:radio[name=payment_method]:checked').val();
       var deliverytime = $('input:radio[name=deliverytime]:checked').val();
       var orderType = $('#order_type').val();

       var walletAmt = $(".walletAmt").html();
       var totalOrderAmt = $("#totalOrderAmt").html();


       var delivery_date = $("#delivery_date").val();
       var deliveryTime = $("#deliveryTime").val();

       var isChecked = $('#use_wallet').is(':checked');

       var totalAmt = $(".totalAmt").html();

       var totalAmt = parseFloat(totalAmt.replace(',',''));


       if(customerPhone == '') {
           $("#ordeSubmitbutton").prop('disabled',false);
           alert('Sorry, Please update your contact number.')
           $("#userinfoModal").modal('show');
           return false;

       }else if((deliveryAddress == '' || deliveryAddress == undefined) && orderType == 'delivery') {
           $("#ordeSubmitbutton").prop('disabled',false);
           $(".emptyAddress").addClass('error').html('Sorry, but we are unable to continue without a delivery address.');
           return false;
   
       }else if(delivery_date == '' && deliverytime != 'now') {
           $("#ordeSubmitbutton").prop('disabled',false);
           $("#addressErr").addClass('error').html('Sorry, but we are unable to continue without a delivery date.');
           return false;
   
       }else if(deliveryTime == 'Closed' && deliverytime != 'now') {
           $("#ordeSubmitbutton").prop('disabled',false);
           $(".emptyTime").addClass('error').html('Sorry, Restaurant was closed.');
           return false;
   
       }else if(paymentMethod == '' || paymentMethod == undefined && totalOrderAmt > 0) {
           $("#ordeSubmitbutton").prop('disabled',false);
           $("#paymentErr").addClass('error').html('please choose payment.');
           return false;
   
       }else if(paymentMethod == 'Wallet' && walletAmt == '') {
           $("#ordeSubmitbutton").prop('disabled',false);
           $("#paymentErr").addClass('error').html('please choose payment.');
           return false;

       }else if(paymentMethod == 'Wallet' && totalAmt > 0) {
           $("#ordeSubmitbutton").prop('disabled',false);
           $("#paymentErr").addClass('error').html('please choose another payment.');
           return false;

       }else {

           if(paymentMethod == 'stripe' && (stripeId == '' || stripeId == undefined && totalOrderAmt > 0)) {
               $(".stripeErr").addClass('error').html('Please choose one card or add new card');
               return false;
   
               /*var handler = StripeCheckout.configure({
                   key: 'pk_test_6pRNASCoBOKtIshFeQd4XMUh',
                   image: 'https://stripe.com/img/documentation/checkout/marketplace.png',
                   locale: 'auto',
                   token: function(token) {
                       if(token.id != '') {
                           $("#res-sp-token").val(token.id);
                           $("#checkoutForm").submit();return false;
                       }
   
                   }
               });
   
               var payedAmount = $("#totalOrderAmt").html();
               // Open Checkout with further options:
               handler.open({
                   name: 'Stripe.com',
                   description: 'Stripe payment',
                   zipCode: false,
                   amount: payedAmount*100
               });
               $("#ordeSubmitbutton").prop('disabled',false);
               return false;
   
               // Close Checkout on page navigation:
               window.addEventListener('popstate', function() {
                   handler.close();
               });*/

           }else if(paymentMethod == 'paypal' && totalOrderAmt > 0) {

           }else {
               $("#checkoutForm").submit(); return false;
           }
           //
       }
   
   }

   function walletAmount() {
       var isChecked = $('#use_wallet').is(':checked');

       var walletBal = $("#walletBalance").val();
       var walletBal = parseFloat(walletBal.replace(',',''));

       var totalAmount = $(".totalAmt").html();

       var totalAmt = parseFloat(totalAmount.replace(',',''));

       if(isChecked == true) {

           var TotalAmount = totalAmt - walletBal;


           if(TotalAmount > 0) {
               $("#totalOrderAmt").html(parseFloat(TotalAmount).toFixed(2));
               $(".walletAmt").html(parseFloat(walletBal).toFixed(2));
               $(".paidAmt").html(parseFloat(walletBal).toFixed(2));
               $("#paidFull").val('No');

           }else {
               TotalAmount = 0;
               $("#totalOrderAmt").html(parseFloat(TotalAmount).toFixed(2));
               $(".paidAmt").html(parseFloat(totalAmt).toFixed(2));
               $(".walletAmt").html(parseFloat(totalAmt).toFixed(2));
               $("#paymentInfo").css('display','none');
               $("#placeButton").css('display','block');
               $("#payaplButton").css('display','none');
               $("#paidFull").val('Yes');

           }


           $("#paidAmount").css('display','block');
           //$("#walletSection").css('display','block');

       }else {
           var subtotal = $(".subTotal").html();
           var subtotal = parseFloat(subtotal.replace(',',''));

           var taxtotal = $(".taxTotal").html();
           var taxtotal = parseFloat(taxtotal.replace(',',''));


           //Offers
           var offerTotal = $(".offerTotal").html();

           if(offerTotal == '' || offerTotal == undefined) {
               offerTotal = 0.00;
           }else {
               offerTotal = parseFloat(offerTotal.replace(',',''));
           }

           //Redeem
           var redeemTotal = $(".redeemTotal").html();

           if(redeemTotal == '' || redeemTotal == undefined) {
               redeemTotal = 0.00;
           }else {
               redeemTotal = parseFloat(redeemTotal.replace(',',''));
           }

           //Voucher
           var voucherAmt = $(".voucherAmt").html();

           if(voucherAmt == '' || voucherAmt == undefined) {
               voucherAmt = 0.00;
           }else {
               voucherAmt = parseFloat(voucherAmt.replace(',',''));
           }


           var deliveryCharge = $.trim($("#deliveryAmt").html());

           if(deliveryCharge != 'Free' && deliveryCharge != '') {
               var deliveryCharge = parseFloat(deliveryCharge.replace(',',''));
           }else {
               var deliveryCharge = 0.00;
           }




           var totalAmt = subtotal - offerTotal - voucherAmt - redeemTotal;

           if(totalAmt <= 0) {
               totalAmt = deliveryCharge;
           }else {
               totalAmt = totalAmt + deliveryCharge + taxtotal;
           }

           var noramlTotal = subtotal + taxtotal + deliveryCharge

           //$(".normalTotal").html(noramlTotal);
           $(".normalTotal").html(parseFloat(noramlTotal).toFixed(2));

           var TotalAmount = totalAmt;
           $(".walletAmt").html(parseFloat(TotalAmount).toFixed(2));
           $(".paidAmt").html(parseFloat(totalAmt).toFixed(2));
           $("#totalOrderAmt").html(parseFloat(TotalAmount).toFixed(2));
           //$("#walletSection").css('display','none');
           $("#paidAmount").css('display','none');
           $("#paymentInfo").css('display','block');
           $("#paidFull").val('No');

       }

   }

   function showPaypal() {

       var customerPhone = $("#customerPhone").val();

       if(customerPhone == '') {
           $("#ordeSubmitbutton").prop('disabled',false);
           alert('Sorry, Please update your contact number.')
           $("#userinfoModal").modal('show');
           return false;

       }else {

           $("#placeButton").css('display','none');
           $("#payaplButton").css('display','block');

           var payButton = $("#payButton").val();
           if(payButton == '') {
               paypal.Button.render({

                   env: '<?= MODE ?>', // sandbox | production

                   // PayPal Client IDs - replace with your own
                   // Create a PayPal app: https://developer.paypal.com/developer/applications/create
                   client: {
               <?= MODE ?>:    '<?= CLIENT_ID ?>',
               //production: '<insert production client id>'
           },

               // Show the buyer a 'Pay Now' button in the checkout flow
               commit: true,

                   // payment() is called when the button is clicked
                   payment: function(data, actions) {

                   var amount = $.trim($("#totalOrderAmt").html());
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
                       if(data.payerID != '' && data.paymentToken != '' && data.paymentID != '') {
                           $("#payerID").val(data.payerID);
                           $("#paymentToken").val(data.paymentToken);
                           $("#paymentID").val(data.paymentID);
                           $("#checkoutForm").submit();return false;
                       }

                   });
               },
               onCancel: function (data) {

               },
               onError: function (err) {
                   alert('Payment Error');return false;
               }

           }, '#payaplButton');
           }

           $("#payButton").val(1)

       }


   }
   
   function hidePayapl() {
       $("#payaplButton").css('display','none');
       $("#placeButton").css('display','block');
   }
   
   function applyCoupon(id) {
      
       $(".error").html('');
       var vouchercode = $.trim($("#vouchercode").val());
       var deliveryAmt = $.trim($("#deliveryAmt").html());
       

       var subTotal = $.trim($(".subTotal").html());
       if(vouchercode == '') {
           $(".voucherErr").addClass('error').html('Please enter the voucher code');
           return false;
       }else {
           $.ajax({
               type   : 'POST',
               url    : jssitebaseurl+'checkouts/voucherApply',
               data   : {vouchercode:vouchercode,subTotal:subTotal,deliveryAmt:deliveryAmt},
               success: function(data){
                   if($.trim(data) == 'already') {
                       $(".voucherErr").addClass('error').html('Already used');
                       return false;
                   }else if($.trim(data) == 'no') {
                       $(".voucherErr").addClass('error').html('Not Available');
                       return false;
                   }else if($.trim(data) == 'Already Free Delivery Applied') {
                       $(".voucherErr").addClass('error').html('Already Free Delivery Applied');
                       return false;
                   }else if($.trim(data) == 'pickup') {
                       $(".voucherErr").addClass('error').html('This Voucher only applicable for delivery order');
                       return false;
                   }else if($.trim(data) == 'missing') {
                       $(".voucherErr").addClass('error').html('Please enter coupon code');
                       return false;
                   }else {
                        if($.trim(data) == 'free') {

                            $(".coupon_part").css('display','none');
                            $("#voucherSection").css('display','block');

                            var subtotal = $(".subTotal").html();
                            var subtotal = parseFloat(subtotal.replace(',',''));

                            var taxtotal = $(".taxTotal").html();
                            var taxtotal = parseFloat(taxtotal.replace(',',''));


                            //Offers
                            var offerTotal = $(".offerTotal").html();

                            if(offerTotal == '' || offerTotal == undefined) {
                                offerTotal = 0.00;
                            }else {
                                offerTotal = parseFloat(offerTotal.replace(',',''));
                            }

                            //Redeem
                            var redeemTotal = $(".redeemTotal").html();

                            if(redeemTotal == '' || redeemTotal == undefined) {
                                redeemTotal = 0.00;
                            }else {
                                redeemTotal = parseFloat(redeemTotal.replace(',',''));
                            }

                            //Voucher
                            var voucherAmt = $(".voucherAmt").html();

                            if(voucherAmt == '' || voucherAmt == undefined) {
                                voucherAmt = 0.00;
                            }else {
                                voucherAmt = parseFloat(voucherAmt.replace(',',''));
                            }


                            var deliveryCharge = $.trim($("#deliveryAmt").html());

                            if(deliveryCharge != 'Free' && deliveryCharge != '') {
                                var deliveryCharge = parseFloat(deliveryCharge.replace(',',''));
                            }else {
                                var deliveryCharge = 0.00;
                            }


                            totalAmount = subtotal - offerTotal - voucherAmt - redeemTotal;

                            if(totalAmount <= 0) {
                                totalAmount = deliveryCharge;
                            }else {
                                totalAmount = totalAmount + deliveryCharge + taxtotal;
                            }

                            $(".totalAmt").html(totalAmount);

                            var totalAmount = $(".totalAmt").html();


                            var totalAmt = parseFloat(totalAmount.replace(',',''));

                            //var totalAmt = parseFloat(totalAmt);

                            var deliveryAmt = $("#deliveryAmt").html();
                            var deliveryAmt = parseFloat(deliveryAmt.replace(',',''));

                            var TotalAmount = totalAmt - deliveryAmt;

                            $(".totalAmt").html(parseFloat(TotalAmount).toFixed(2));

                            $(".voucherAmt").html(parseFloat(deliveryAmt).toFixed(2))

                            walletAmount();


                        }else {

                            $(".coupon_part").css('display','none');
                            $("#voucherSection").css('display','block');

                            var subtotal = $(".subTotal").html();
                            var subtotal = parseFloat(subtotal.replace(',',''));

                            var taxtotal = $(".taxTotal").html();
                            var taxtotal = parseFloat(taxtotal.replace(',',''));


                            //Offers
                            var offerTotal = $(".offerTotal").html();

                            if(offerTotal == '' || offerTotal == undefined) {
                                offerTotal = 0.00;
                            }else {
                                offerTotal = parseFloat(offerTotal.replace(',',''));
                            }

                            //Redeem
                            var redeemTotal = $(".redeemTotal").html();

                            if(redeemTotal == '' || redeemTotal == undefined) {
                                redeemTotal = 0.00;
                            }else {
                                redeemTotal = parseFloat(redeemTotal.replace(',',''));
                            }

                            //Voucher
                            var voucherAmt = $(".voucherAmt").html();

                            if(voucherAmt == '' || voucherAmt == undefined) {
                                voucherAmt = 0.00;
                            }else {
                                voucherAmt = parseFloat(voucherAmt.replace(',',''));
                            }


                            var deliveryCharge = $.trim($("#deliveryAmt").html());


                            if(deliveryCharge != 'Free' && deliveryCharge != '') {
                                var deliveryCharge = parseFloat(deliveryCharge.replace(',',''));
                            }else {
                                var deliveryCharge = 0.00;
                            }


                            totalAmount = subtotal - offerTotal - voucherAmt - redeemTotal;

                            if(totalAmount <= 0) {
                                totalAmount = deliveryCharge;
                            }else {
                                totalAmount = totalAmount + deliveryCharge + taxtotal;
                            }



                            $(".totalAmt").html(totalAmount);

                            var totalAmount = $(".totalAmt").html();

                            var totalAmt = parseFloat(totalAmount.replace(',',''));

                            var totalAmt = parseFloat(totalAmt);
                            var TotalAmount = totalAmt - data;

                            $(".totalAmt").html(parseFloat(TotalAmount).toFixed(2));

                            $(".voucherAmt").html(parseFloat(data).toFixed(2))

                            walletAmount();

                        }


                   }
               }
           });

       }
   }
   
   function removeVoucher() {

       var subtotal = $(".subTotal").html();
       var subtotal = parseFloat(subtotal.replace(',',''));

       var taxtotal = $(".taxTotal").html();
       var taxtotal = parseFloat(taxtotal.replace(',',''));


       //Offers
       var offerTotal = $(".offerTotal").html();

       if(offerTotal == '' || offerTotal == undefined) {
           offerTotal = 0.00;
       }else {
           offerTotal = parseFloat(offerTotal.replace(',',''));
       }

       //Redeem
       var redeemTotal = $(".redeemTotal").html();

       if(redeemTotal == '' || redeemTotal == undefined) {
           redeemTotal = 0.00;
       }else {
           redeemTotal = parseFloat(redeemTotal.replace(',',''));
       }

       //Voucher
       var voucherAmt = $(".voucherAmt").html();

       if(voucherAmt == '' || voucherAmt == undefined) {
           voucherAmt = 0.00;
       }else {
           voucherAmt = parseFloat(voucherAmt.replace(',',''));
       }


       var deliveryCharge = $.trim($("#deliveryAmt").html());

       if(deliveryCharge != 'Free' && deliveryCharge != '') {
           var deliveryCharge = parseFloat(deliveryCharge.replace(',',''));
       }else {
           var deliveryCharge = 0.00;
       }


       totalAmount = subtotal - offerTotal - voucherAmt - redeemTotal;

       if(totalAmount <= 0) {
           totalAmount = deliveryCharge;
       }else {
           totalAmount = totalAmount + deliveryCharge + taxtotal;
       }

       $(".totalAmt").html(totalAmount);



       var totalAmount = $(".totalAmt").html();

       var totalAmt = parseFloat(totalAmount.replace(',',''));
       

       var totalAmt = parseFloat(totalAmt);
       var TotalAmount = totalAmt + voucherAmt;

       $(".totalAmt").html(parseFloat(TotalAmount).toFixed(2));

       $.ajax({
           type   : 'POST',
           url    : jssitebaseurl+'checkouts/removeVoucher',
           success: function(data){
               $(".voucherAmt").html('')
               $("#vouchercode").val('');
               $("#voucherSection").css('display','none');
               $(".coupon_part").css('display','block');

               walletAmount();

               return false;
           }
       });

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

   function showAddCard() {

       $("#add_card_modal").modal('show');
       return false;

   }


   // // Create a Stripe client
   // var stripe = Stripe('<?= STRIPE_PUBLISH; ?>');

   // // Create an instance of Elements
   // var elements = stripe.elements();

   // // Custom styling can be passed to options when creating an Element.
   // // (Note that this demo uses a wider set of styles than the guide below.)
   // var style = {
   //     base: {
   //         color: '#32325d',
   //         lineHeight: '18px',
   //         fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
   //         fontSmoothing: 'antialiased',
   //         fontSize: '16px',
   //         '::placeholder': {
   //             color: '#aab7c4'
   //         }
   //     },
   //     invalid: {
   //         color: '#fa755a',
   //         iconColor: '#fa755a'
   //     }
   // };

   // // Create an instance of the card Element
   // var card = elements.create('card', {style: style});

   // // Add an instance of the card Element into the `card-element` <div>
   // card.mount('#card-element');

   // // Handle real-time validation errors from the card Element.
   // card.addEventListener('change', function(event) {
   //     var displayError = document.getElementById('card-errors');
   //     if (event.error) {
   //         displayError.textContent = event.error.message;
   //     } else {
   //         displayError.textContent = '';
   //     }
   // });

   // // Handle form submission
   // var form = document.getElementById('payment-form');
   // form.addEventListener('submit', function(event) {
   //     event.preventDefault();

   //     stripe.createToken(card).then(function(result) {
   //         if (result.error) {
   //             // Inform the user if there was an error
   //             var errorElement = document.getElementById('card-errors');
   //             errorElement.textContent = result.error.message;
   //         } else {
   //             // Send the token to your server
   //             stripeTokenHandler(result.token);
   //         }
   //     });
   // });

   // function stripeTokenHandler(result) {


   //     // Insert the token ID into the form so it gets submitted to the server
   //     var form = document.getElementById('payment-form');
   //     var hiddenInput = document.createElement('input');
   //     hiddenInput.setAttribute('type', 'hidden');
   //     hiddenInput.setAttribute('name', 'stripe_token_id');
   //     hiddenInput.setAttribute('value', result.id);
   //     form.appendChild(hiddenInput);

   //     //Card Id
   //     var hiddenInput1 = document.createElement('input');
   //     hiddenInput1.setAttribute('type', 'hidden');
   //     hiddenInput1.setAttribute('name', 'card_id');
   //     hiddenInput1.setAttribute('value', result.card.id);
   //     form.appendChild(hiddenInput1);

   //     //Card Zipcode
   //     var hiddenInput2 = document.createElement('input');
   //     hiddenInput2.setAttribute('type', 'hidden');
   //     hiddenInput2.setAttribute('name', 'address_zip');
   //     hiddenInput2.setAttribute('value', result.card.address_zip);
   //     form.appendChild(hiddenInput2);

   //     //Card Brand
   //     var hiddenInput3 = document.createElement('input');
   //     hiddenInput3.setAttribute('type', 'hidden');
   //     hiddenInput3.setAttribute('name', 'card_brand');
   //     hiddenInput3.setAttribute('value', result.card.brand);
   //     form.appendChild(hiddenInput3);

   //     //Card country
   //     var hiddenInput4 = document.createElement('input');
   //     hiddenInput4.setAttribute('type', 'hidden');
   //     hiddenInput4.setAttribute('name', 'country');
   //     hiddenInput4.setAttribute('value', result.card.country);
   //     form.appendChild(hiddenInput4);

   //     //Card exp_month
   //     var hiddenInput5 = document.createElement('input');
   //     hiddenInput5.setAttribute('type', 'hidden');
   //     hiddenInput5.setAttribute('name', 'exp_month');
   //     hiddenInput5.setAttribute('value', result.card.exp_month);
   //     form.appendChild(hiddenInput5);

   //     //Card exp_year
   //     var hiddenInput6 = document.createElement('input');
   //     hiddenInput6.setAttribute('type', 'hidden');
   //     hiddenInput6.setAttribute('name', 'exp_year');
   //     hiddenInput6.setAttribute('value', result.card.exp_year);
   //     form.appendChild(hiddenInput6);

   //     //Card funding credit or debit
   //     var hiddenInput7 = document.createElement('input');
   //     hiddenInput7.setAttribute('type', 'hidden');
   //     hiddenInput7.setAttribute('name', 'card_type');
   //     hiddenInput7.setAttribute('value', result.card.funding);
   //     form.appendChild(hiddenInput7);

   //     //Card last4
   //     var hiddenInput8 = document.createElement('input');
   //     hiddenInput8.setAttribute('type', 'hidden');
   //     hiddenInput8.setAttribute('name', 'card_number');
   //     hiddenInput8.setAttribute('value', result.card.last4);
   //     form.appendChild(hiddenInput8);

   //     //Card client_ip
   //     var hiddenInput9 = document.createElement('input');
   //     hiddenInput9.setAttribute('type', 'hidden');
   //     hiddenInput9.setAttribute('name', 'client_ip');
   //     hiddenInput9.setAttribute('value', result.client_ip);
   //     form.appendChild(hiddenInput9);




   //     // Submit the form
   //     form.submit();
   // }



//?????????????????????????????????????????????????????????????????????????
    // credit card js
    // <?php echo __('Submit Payment');?>
    // Configure a Heartland / GP client

    GlobalPayments.configure({
        publicApiKey: '<?= $restaurantDetails['heartland_public_api_key']; ?>'
    });

    // Create an instance of card form
    const cardForm = GlobalPayments.ui.form({
        fields: {
            "card-number": {
                placeholder: "•••• •••• •••• ••••",
                target: "#credit-card-card-number"
            },
            "card-expiration": {
                placeholder: "MM / YYYY",
                target: "#credit-card-card-expiration"
            },
            "card-cvv": {
                placeholder: "•••",
                target: "#credit-card-card-cvv"
            },
            "submit": {
                target: "#credit-card-submit",
                text: "Add Card"
            }
        },
        styles: {
            // Custom styling can be passed to options when creating a form.
            // (Note that this demo uses a wider set of styles than the guide below.)
            '*': {
                color: '#32325d',
                'line-height': '18px',
                'font-family': '"Helvetica Neue", Helvetica, sans-serif',
                'font-smoothing': 'antialiased',
                'font-size': '16px',
                '::placeholder': {
                    color: '#aab7c4'
                }
            },
            '*.invalid': {
                color: '#fa755a',
                'icon-color': '#fa755a'
            }
        }
    });

    // Handle form submission
    cardForm.on('token-success', function (result) {
        // Insert the token ID into the form so it gets submitted to the server
        var form = document.getElementById('payment-form');
        var hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'heartland_token_id');
        hiddenInput.setAttribute('value', result.paymentReference);
        form.appendChild(hiddenInput);

       //Card Brand
       var hiddenInput3 = document.createElement('input');
       hiddenInput3.setAttribute('type', 'hidden');
       hiddenInput3.setAttribute('name', 'card_brand');
       hiddenInput3.setAttribute('value', result.details.cardType);
       form.appendChild(hiddenInput3);

       //Card exp_month
       var hiddenInput5 = document.createElement('input');
       hiddenInput5.setAttribute('type', 'hidden');
       hiddenInput5.setAttribute('name', 'exp_month');
       hiddenInput5.setAttribute('value', result.details.expiryMonth);
       form.appendChild(hiddenInput5);

       //Card exp_year
       var hiddenInput6 = document.createElement('input');
       hiddenInput6.setAttribute('type', 'hidden');
       hiddenInput6.setAttribute('name', 'exp_year');
       hiddenInput6.setAttribute('value', result.details.expiryYear);
       form.appendChild(hiddenInput6);

       //Card funding credit or debit
       var hiddenInput7 = document.createElement('input');
       hiddenInput7.setAttribute('type', 'hidden');
       hiddenInput7.setAttribute('name', 'card_type');
       hiddenInput7.setAttribute('value', 'credit');
       form.appendChild(hiddenInput7);

       //Card last4
       var hiddenInput8 = document.createElement('input');
       hiddenInput8.setAttribute('type', 'hidden');
       hiddenInput8.setAttribute('name', 'card_number');
       hiddenInput8.setAttribute('value', result.details.cardLast4);
       form.appendChild(hiddenInput8);


        // Submit the form
        form.submit();
    });

    cardForm.on('token-error', function (result) {
        // Inform the user if there was an error
        var errorElement = document.getElementById('card-errors');
        errorElement.textContent = result.reasons.map(r => r.message).join(' ');
    });



//?????????????????????????????????????????????????????????????????????????





   function checkMinimum() {


       var deliveryAddress = $('input:radio[name=checkout_address]:checked').val();

       var miniValue =  parseFloat($("#deliveryMin_"+deliveryAddress).val());
       var subtotal = $(".subTotal").html();

       var subtotal = parseFloat(subtotal.replace(',',''));
       //var deliveryAddress = parseFloat(deliveryAddress.replace(',',''));


       var orderType = $('#order_type').val();

       if(orderType == 'delivery') {
           if(miniValue <= parseFloat(subtotal)) {
               if($("#paymentInfo").hasClass('mask_checkout')) {
                   $("#overAllBtn").hide();
               }else {
                   $("#overAllBtn").show();
               }

           }else {
               if(deliveryAddress != '' && deliveryAddress != undefined) {
                   alert('Minimum order value '+miniValue+' for this address');
               }
               $("#overAllBtn").hide();
           }

       }

       if(deliveryAddress == '' || deliveryAddress == undefined) {
           $("#overAllBtn").show();
       }
       return false;

   }

  function flash(el, c1, c2) {
    var text = document.getElementById(el);    
    if (text) {
        text.style.color = (text.style.color == c2) ? c1 : c2;
    }
  }
  setInterval(function() {
      flash('blink_text', 'gray', 'red')
  }, 1000);
</script>

