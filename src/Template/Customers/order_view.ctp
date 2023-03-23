<div class="container">
    <section class="main_wrapper">
        <div class="col-xs-12 no-padding order-history-view">
            <!-- <a href="<?= BASE_URL ?>customers/orderView/<?= $orderDetails['id'].".pdf"; ?>" class="btn btn-warning"><i class="fa fa-file-pdf-o m-r-10"></i>Download in PDF</a> -->
            <a onclick="return documentPrints();" href="javascript:void(0);" class="btn btn-info m-l-10"><i class="fa fa-print m-r-10"></i><span class="hidden-xs"><?php echo __('Print Page');?></span></a>
            <a href="<?= BASE_URL ?>customers" class="btn btn-default pull-right pul-left"><i class="fa fa-angle-double-left m-r-10"></i><span class="hidden-xs"><?php echo __('Back to Order History');?></span></a>
        </div>
        <div class="col-xs-12 col-sm-8 col-md-8 order_view  no-padding-left no-xs-padding pul-right no-rtlpadding-righ rtl-padding-l15">
            <div class="panel panel-default m-t-20">
                <div class="panel-heading"><?php echo __('Order Info');?></div>
                <div class="panel-body">
                    <div class="form-group clearfix">
                        <label class="control-label col-xs-4 col-sm-4 no-xs-padding pul-right"><?php echo __('Order ID');?></label>
                        <label class="control-label col-xs-1 col-sm-1 no-xs-padding pul-right">:</label>
                        <label class="control-label col-xs-7 col-sm-7 no-xs-padding-left font-medium pul-right"><?= $orderDetails['order_number'] ?></label>
                    </div>
                    <div class="form-group clearfix">
                        <label class="control-label col-xs-4 col-sm-4 no-xs-padding pul-right"><?php echo __('Restaurant Name');?></label>
                        <label class="control-label col-xs-1 col-sm-1 no-xs-padding pul-right">:</label>
                        <label class="control-label col-xs-7 col-sm-7 no-xs-padding-left font-medium pul-right"><?= $orderDetails['restaurant']['restaurant_name'] ?></label>
                    </div>
                    <div class="form-group clearfix">
                        <label class="control-label col-xs-4 col-sm-4 no-xs-padding pul-right"><?php echo __('Order At');?></label>
                        <label class="control-label col-xs-1 col-sm-1 no-xs-padding pul-right">:</label>
                        <label class="control-label col-xs-7 col-sm-7 no-xs-padding-left font-medium pul-right"><?= date('Y-m-d h:i A',strtotime($orderDetails['created'])) ?></label>
                    </div>
                    <div class="form-group clearfix">
                        <label class="control-label col-xs-4 col-sm-4 no-xs-padding pul-right"><?php echo __('Order Status');?></label>
                        <label class="control-label col-xs-1 col-sm-1 no-xs-padding pul-right">:</label>
                        <label class="control-label col-xs-7 col-sm-7 no-xs-padding-left font-medium pul-right"><?= $orderDetails['status'] ?></label>
                    </div>
                    <?php if(!empty($orderDetails['status']) && ($orderDetails['status'] == 'Failed')){?>
                        <div class="form-group clearfix">
                            <label class="control-label col-xs-4 col-sm-4 no-xs-padding pul-right">
                             <?php echo __('Failed Reason');?></label>
                            <label class="control-label col-xs-1 col-sm-1 no-xs-padding pul-right">:</label>
                            <label class="control-label col-xs-7 col-sm-7 no-xs-padding-left font-medium pul-right">
                            <?= $orderDetails['failed_reason'] ?></label>
                        </div>
                     <?php } ?>
                </div>
            </div>
            <div class="panel panel-default m-t-20">
                <div class="panel-heading"><?php echo __('Customer Info');?></div>
                <div class="panel-body">
                    <div class="form-group clearfix">
                        <label class="control-label col-xs-4 col-sm-4 no-xs-padding pul-right"><?php echo __('Customer Name');?></label>
                        <label class="control-label col-xs-1 col-sm-1 no-xs-padding pul-right">:</label>
                        <label class="control-label col-xs-7 col-sm-7 no-xs-padding-left font-medium pul-right"><?= $orderDetails['customer_name'] ?></label>
                    </div>
                    <div class="form-group clearfix">
                        <label class="control-label col-xs-4 col-sm-4 no-xs-padding pul-right"><?php echo __('Customer Email');?></label>
                        <label class="control-label col-xs-1 col-sm-1 no-xs-padding pul-right">:</label>
                        <label class="control-label col-xs-7 col-sm-7 no-xs-padding-left font-medium pul-right"><?= $orderDetails['customer_email'] ?></label>
                    </div>
                    <div class="form-group clearfix">
                        <label class="control-label col-xs-4 col-sm-4 no-xs-padding pul-right"><?php echo __('Phone Number');?></label>
                        <label class="control-label col-xs-1 col-sm-1 no-xs-padding pul-right">:</label>
                        <label class="control-label col-xs-7 col-sm-7 no-xs-padding-left font-medium pul-right"><?= $orderDetails['customer_phone'] ?></label>
                    </div>
                    <?php if($orderDetails['order_type'] == 'delivery') { ?>
                        <div class="form-group clearfix">
                            <label class="control-label col-xs-4 col-sm-4 no-xs-padding pul-right"><?php echo __('Address');?></label>
                            <label class="control-label col-xs-1 col-sm-1 no-xs-padding pul-right">:</label>
                            <label class="control-label col-xs-7 col-sm-7 no-xs-padding-left font-medium pul-right"><?= $orderDetails['flat_no'] ?>, <?= $orderDetails['address'] ?></label>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <div class="panel panel-default m-t-20">
                <div class="panel-heading"><?= ($orderDetails['order_type'] == 'delivery') ? 'Delivery' : 'Pickup' ?> <?php echo __('Info');?></div>
                <div class="panel-body">
                    <div class="form-group clearfix">
                        <label class="control-label col-xs-4 col-sm-4 no-xs-padding pul-right"><?= ($orderDetails['order_type'] == 'delivery') ? 'Delivery' : 'Pickup' ?> <?php echo __('Date');?></label>
                        <label class="control-label col-xs-1 col-sm-1 no-xs-padding pul-right">:</label>
                        <label class="control-label col-xs-7 col-sm-7 no-xs-padding-left font-medium pul-right"><?= date('Y-m-d',strtotime($orderDetails['delivery_date'])) ?></label>
                    </div>
                    <div class="form-group clearfix">
                        <label class="control-label col-xs-4 col-sm-4 no-xs-padding pul-right"><?= ($orderDetails['order_type'] == 'delivery') ? 'Delivery' : 'Pickup' ?> <?php echo __('Time');?></label>
                        <label class="control-label col-xs-1 col-sm-1 no-xs-padding pul-right">:</label>
                        <label class="control-label col-xs-7 col-sm-7 no-xs-padding-left font-medium pul-right"><?= ($orderDetails['assoonas'] == 'now') ? 'ASAP' : $orderDetails['delivery_time'] ?></label>
                    </div>
                </div>
            </div>
            
            <div class="panel panel-default m-t-20">
                <div class="panel-heading"><?php echo __('Payment Info');?></div>
                <div class="panel-body">
                    <div class="form-group clearfix">
                        <label class="control-label col-xs-4 col-sm-4 no-xs-padding pul-right"><?php echo __('Payment Method');?></label>
                        <label class="control-label col-xs-1 col-sm-1 no-xs-padding pul-right">:</label>
                        <label class="control-label col-xs-7 col-sm-7 no-xs-padding-left font-medium pul-right"><?= ucfirst($orderDetails['payment_method']) ?></label>
                    </div>
                    <div class="form-group clearfix">
                        <label class="control-label col-xs-4 col-sm-4 no-xs-padding pul-right"><?php echo __('Payment Status');?></label>
                        <label class="control-label col-xs-1 col-sm-1 no-xs-padding pul-right">:</label>
                        <label class="control-label col-xs-7 col-sm-7 no-xs-padding-left font-medium pul-right">
                            <?= ($orderDetails['payment_status'] == 'NP') ? 'Not Paid' : 'Paid' ?>
                        </label>
                    </div>

                    <?php if($orderDetails['split_payment'] == 'Yes') { ?>
                        <div class="form-group clearfix">
                            <label class="control-label col-xs-4 col-sm-4 no-xs-padding pul-right"><?php echo __('Wallet Amount');?></label>
                            <label class="control-label col-xs-1 col-sm-1 no-xs-padding pul-right">:</label>
                            <label class="control-label col-xs-7 col-sm-7 no-xs-padding-left font-medium pul-right">
                                <?= $siteSettings['site_currency'] ?> <?= number_format($orderDetails['wallet_amount'],2) ?>
                            </label>
                        </div>
                    <?php } ?>


                    <?php if($orderDetails['payment_method'] != 'cod' && $orderDetails['payment_method'] != 'Wallet' && $orderDetails['transaction_id'] != '') { ?>
                        <div class="form-group clearfix">
                            <label class="control-label col-xs-4 col-sm-4 no-xs-padding"><?php echo __('Transaction ID');?></label>
                            <label class="control-label col-xs-1 col-sm-1 no-xs-padding">:</label>
                            <label class="control-label col-xs-7 col-sm-7 no-xs-padding-left font-medium">
                                <?= ($orderDetails['payment_method'] == 'stripe') ? $orderDetails['transaction_id'] : $orderDetails['paymentID'] ?>
                            </label>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>


        <div class="col-xs-12 col-sm-4 col-md-4 no-padding m-t-20 pul-left">
            <div class="order_information_box2">
                <div class="right_res_name"><a><?php echo __('Your Order Items');?></a></div>
                <div class="view_cart_item" style=""><a><i class="fa fa-eye"></i> <?php echo __('View cart items');?></a></div>
                <div class="hide_cart_item" style="display: none;">
                    <div class="order_item">
                        <div class="order-item-name"><?php echo __('Item');?></div>
                        <div class="order-item-qty"><?php echo __('Qty');?></div>
                        <div class="order-item-price"><?php echo __('Price');?></div>
                    </div>
                    <?php if(!empty($orderDetails['carts'])) {
                        foreach ($orderDetails['carts'] as $cKey => $cValue) { ?>
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
                <div class="hide_cart_text" style="display: none;">
                    <a><i class="fa fa-eye-slash"></i> <?php echo __('hide cart items');?></a>
                </div>
            </div>
        </div>


        <div class="col-xs-12 col-sm-4 col-md-4 no-padding m-t-20  m-b-xs-15 pul-left">
            <div class="order_information_box2">
                <div class="warehouse_head cust-info-head">

                    <span>
                        <img src="<?php echo BASE_URL.'uploads/storeLogos/'.$orderDetails['restaurant']['logo_name'] ?>" height="100" width="150" onerror="this.src='<?php echo BASE_URL;?>webroot/images/no_store.jpg'">

                    </span>
                    <span class="check_res_name"><?= $orderDetails['restaurant']['restaurant_name'] ?><br><span><?= $orderDetails['restaurant']['contact_address'] ?></span></span>
                </div>
                <div class="order_item_list">
                    <div class="order-total-txt"><?php echo __('Subtotal');?></div>
                    <div class="order-total-amount"><?= $siteSettings['site_currency'] ?> <span class="subTotal"><?= number_format($orderDetails['order_sub_total'],2) ?></span></div>
                </div>
                <?php if($orderDetails['order_type'] == 'delivery') { ?>
                    <div class="order_item_list">
                        <div class="order-total-txt"><?php echo __('Delivery Fee');?></div>
                        <div class="order-total-amount">

                            <?php echo ($orderDetails['delivery_charge'] <= 0) ? 'Free' : $siteSettings['site_currency'].' '.number_format($orderDetails['delivery_charge'],2); ?>
                        </div>
                    </div>
                <?php } ?>

                <?php if($orderDetails['tax_amount'] > 0) { ?>

                    <div class="order_item_list">
                        <div class="order-total-txt"><?php echo __('Tax');?>(<?= $orderDetails['tax_percentage'] ?> %)</div>
                        <div class="order-total-amount"><?= $siteSettings['site_currency'] ?> <span class="taxTotal"><?= number_format($orderDetails['tax_amount'],2) ?></span></div>
                    </div>
                <?php } ?>


                <?php if($orderDetails['offer_amount'] > 0) { ?>

                    <div class="order_item_list">
                        <div class="order-total-txt"><?php echo __('Offer');?> (<?= $orderDetails['offer_percentage'] ?> %)</div>
                        <div class="order-total-amount"><?= $siteSettings['site_currency'] ?> <span class="taxTotal"><?= number_format($orderDetails['offer_amount'],2) ?></span></div>
                    </div>
                <?php } ?>


                <?php if($orderDetails['voucher_amount'] > 0) { ?>

                    <div class="order_item_list">
                        <div class="order-total-txt"><?php echo __('Voucher');?></div>
                        <div class="order-total-amount"><?= $siteSettings['site_currency'] ?> <span class="taxTotal"><?= number_format($orderDetails['voucher_amount'],2) ?></span></div>
                    </div>
                <?php } ?>


                <?php if($orderDetails['reward_used'] == 'Y') { ?>

                    <div class="order_item_list">
                        <div class="order-total-txt"><?php echo __('Redeem Amount');?> (<?= $orderDetails['reward_offer_percentage'] ?> %)</div>
                        <div class="order-total-amount"><?= $siteSettings['site_currency'] ?> <span class="taxTotal"><?= number_format($orderDetails['reward_offer'],2) ?></span></div>
                    </div>
                <?php } ?>


                <div class="order_item_list">
                    <div class="order-total-txt"><strong><?php echo __('Total');?></strong></div>
                    <div class="order-total-amount"><strong><?= $siteSettings['site_currency'] ?> <span class="totalAmt"><?= number_format($orderDetails['order_grand_total'],2) ?></span></strong></div>
                </div>

                <?php if($orderDetails['order_point'] > 0) { ?>

                    <div class="order_item_list">
                        <?php if($orderDetails['status'] != 'Delivered') { ?>
                            <div class="order-total-txt"><?php echo __('You will Earn');?> </div>
                        <?php }else { ?>
                            <div class="order-total-txt"><?php echo __('You Earned');?> </div>
                        <?php } ?>
                        <div class="order-total-amount"><span class="taxTotal"><?=$orderDetails['order_point'] ?> </span><?php echo __('Points');?></div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
</div>

<script>
    function documentPrints() {
        window.print();
    }
</script>