<div class="container">
   <section class="main_wrapper">
      <div class="col-xs-12 no-padding">
         <div class="cust-info-checkout">
            <div class="cust-thanks-wrapper">
               <?php echo __('Thanks for your Order');?> 
               <a href="<?= BASE_URL ?>customers/orderView/<?= $orderDetails['id'] ?>">
                  <?php if(!empty($orderDetails)) { echo $orderDetails['order_number']; }?>
               </a>
            </div>
            <p class="cust-thanks-content"><?php echo __('We will update the status of order soon');?>.</p>
         </div>
      </div>
      <div class="clearfix"></div>
      <div class="running_scooter"><img src="<?php echo BASE_URL;?>images/scooter-running.gif"></div>

      <div class="panel panel-default thanks_order">
         <div class="panel-heading"><?php echo __('Order Information');?></div>
         <div class="panel-body">
           <?php if(!empty($orderDetails)) {?>
               <ul class="thanks_order_head">
                  <li><?php echo __('Item Name');?></li>
                  <li><?php echo __('Qty');?></li>
                  <li><?php echo __('Price');?></li>
               </ul>
               
                 <?php if(!empty($orderDetails['carts'])) {
                       foreach ($orderDetails['carts'] as $key => $value) {?>  
                       <ul class="thanks_order_item">
                        <li> <?= $value['menu_name'];?>
                             <?php if(!empty($value['subaddons_name'])) {
                               echo $value['subaddons_name'];
                              }?>
                        </li>
                        <li> <?= $value['quantity'];?> </li>
                        <li> <?= $siteSettings['site_currency'];?>
                         <?= number_format($value['total_price'],2);?></li>
                          </ul> 
                    <?php }
                  }?>  
               
               <ul class="thanks_total">
                  <li><?php echo __('Subtotal');?></li>
                  <li><?= $siteSettings['site_currency'];?>
                   <?= number_format($orderDetails['order_sub_total'],2);?></li>
               </ul>

               <?php if($orderDetails['tax_percentage'] > 0) { ?>
                   <ul class="thanks_total">
                      <li><?php echo __('Tax');?> (<?= $orderDetails['tax_percentage'] ?> %)</li>
                      <li><?= $siteSettings['site_currency'];?>
                       <?= number_format($orderDetails['tax_amount'],2);?></li>
                   </ul>
               <?php } ?>


               <?php if($orderDetails['offer_amount'] > 0) { ?>
                   <ul class="thanks_total">
                      <li><?php echo __('Offer');?> (<?= $orderDetails['offer_percentage'] ?> %)</li>
                      <li><?= $siteSettings['site_currency'];?>
                       <?= number_format($orderDetails['offer_amount'],2);?></li>
                   </ul>
               <?php } ?>


               <?php if($orderDetails['voucher_amount'] > 0) { ?>
                   <ul class="thanks_total">
                      <li><?php echo __('Vouchers');?></li>
                      <li><?= $siteSettings['site_currency'];?>
                       <?= number_format($orderDetails['voucher_amount'],2);?></li>
                   </ul>
               <?php } ?>

               <?php if($orderDetails['reward_used'] == 'Y') { ?>

                   <ul class="thanks_total">
                       <li><?php echo __('Redeem Amount');?> (<?= $orderDetails['reward_offer_percentage'] ?> %)</li>
                       <li><?= $siteSettings['site_currency'];?>
                           <?= number_format($orderDetails['reward_offer'],2);?></li>
                   </ul>

               <?php } ?>

                <?php if($orderDetails['order_type'] == 'delivery') { ?>
                   <ul class="thanks_total">
                      <li><?php echo __('Delivery Fee');?></li>
                      <li>
                         <?php echo ($orderDetails['delivery_charge'] <= 0) ? 'Free' : $siteSettings['site_currency'].' '.number_format($orderDetails['delivery_charge'],2); ?>
                       </li>
                   </ul>
                    <?php } ?>
               
               <ul class="thanks_alltotal">
                  <li><?php echo __('Total');?></li>
                  <li><?= $siteSettings['site_currency'];?>
                   <?= number_format($orderDetails['order_grand_total'],2);?></li>
               </ul>  
            <?php }?>          
         </div>
      </div>
   </section>
</div>

<script>
    $(document).ready(function() {
        goToAck('<?= $orderDetails['id'] ?>')
    });
    function goToAck(orderid) {

        //$("#printer_respone").modal('show');
        return false;
        var refreshId = setInterval(function() {
            $.post(jssitebaseUrl + '/ajaxFile.php', {
                'action': 'check_print_res',
                'orderid': orderid
            }, function(output) {
                if (output == 'Y') {
                    $("#printer_respone").load(jssitebaseUrl + "/ajaxAction.php", {
                        'action': 'check_printer_response',
                        'orderid': orderid
                    }, function(output) {
                        clearInterval(refreshId);
                    });
                }
            });
        }, 10000);
    }

</script>

<div class="modal fade" id="printer_respone" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo __('Order Knowledgement');?></h4>
            </div>
            <div class="modal-body clearfix">
                <div class="printer">Request is being processed</div>
                <div class="loading_icon"><img height="50" width="50" src="<?php echo DIST_URL; ?>img/loader.gif"" alt="" title="" /></div>
                <div class="printer">Order is under acknowledgement</div>
                <div class="printer">Please wait....</div>
            </div>
        </div>
    </div>
</div>