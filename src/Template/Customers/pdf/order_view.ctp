<html>
<head>
    <title></title>
</head>
<body>
    <div style="diplay:block;width:650px;background:#fff;margin:0 auto;padding:50px;border:1px solid #ddd;">
         <?php if(!empty($orderDetails)){ ?>
            <table  cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td style="width:50%;text-align:left;font:bold 24px Arial;padding-bottom:20px;line-height:50px;"><?= $orderDetails['order_number'];?></td>
                    <td style="width:50%;text-align:right;font:bold 24px Arial;padding-bottom:20px;line-height:50px;"><?= date('Y-m-d h:i A',strtotime($orderDetails['created']));?></td>
                </tr>
            </table>
            <table style="border:3px solid #ddd;"  cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td align="center" bgcolor="#eee" colspan="2" style="border-bottom:1px solid #eee;font:bold 14px Arial;padding:10px 20px;line-height:40px;"><?php echo __("Customer & Order information");?></td>
                </tr>   
                <tr>
                    <td width="50%" style="border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:20px;"><?php echo __("Customer Name");?></td>
                    <td width="50%" style="border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:20px;">: <?= $orderDetails['customer_name'];?></td>
                </tr>
                <tr>
                    <td width="50%" style="border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:20px;"><?php echo __("Customer Email");?></td>
                    <td width="50%" style="border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:20px;">: <?= $orderDetails['customer_email'];?></td>
                </tr>
                <?php if(!empty($orderDetails) && $orderDetails['order_type'] == 'delivery'){ ?>
                    <tr>
                        <td width="50%" style="border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:20px;"><?php echo __("Address");?></td>
                        <td width="50%" style="border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:20px;">:
                         <?= $orderDetails['flat_no'];?>, <?= $orderDetails['address'];?></td>
                    </tr>
                <?php }?>
                <tr>
                    <td width="50%" style="border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:20px;"><?php echo __("Phone Number");?></td>
                    <td width="50%" style="border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:20px;">: <?= $orderDetails['customer_phone'];?></td>
                </tr>                
                <tr>
                    <td width="50%" style="border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:20px;"><?= ($orderDetails['order_type'] == 'delivery') ? 'Delivery' : 'Pickup' ?> <?php echo __("Date");?></td>
                    <td width="50%" style="border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:20px;">: <?= $orderDetails['delivery_date'];?> </td>
                </tr>
                <tr>
                    <td width="50%" style="border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:20px;"><?= ($orderDetails['order_type'] == 'delivery') ? 'Delivery' : 'Pickup' ?> <?php echo __("Time");?></td>
                    <td width="50%" style="border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:20px;">: <?= ($orderDetails['assoonas'] == 'now') ? 'ASAP' : $orderDetails['delivery_time'];?> </td>
                </tr>                
                <tr>
                    <td width="50%" style="border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:20px;"><?php echo __("Order Type");?></td>
                    <td width="50%" style="border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:20px;">: <?= $orderDetails['order_type'];?></td>
                </tr>
                <tr>
                    <td width="50%" style="border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:20px;"><?php echo __("Payment Method");?></td>
                    <td width="50%" style="border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:20px;">: <?= $orderDetails['payment_method'];?></td>
                </tr>
                <tr>
                    <td width="50%" style="border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:20px;"><?php echo __("Payment Status");?></td>
                    <td width="50%" style="border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:20px;">: 
                    <?php echo ($orderDetails['payment_status'] == 'P') ? 'Paid' : 'NotPaid';?></td>
                </tr>
                <tr>
                    <td width="50%" style="border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:20px;"><?php echo __("Restaurant Name");?></td>
                    <td width="50%" style="border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:20px;">: <?= $orderDetails['restaurant']['status'];?></td>
                </tr>
                <tr>
                    <td width="50%" style="border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:20px;"><?php echo __("Order Status");?></td>
                    <td width="50%" style="border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:20px;">: <?= $orderDetails['status'];?></td>
                </tr>
            </table>
            <table style="border:3px solid #ddd; margin-top:20px"  cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <th bgcolor="#eee" style="width:10%;border-bottom:1px solid #eee;font:bold 14px Arial;padding:10px 20px;line-height:30px;"><?php echo __("S.No");?></th>
                <th bgcolor="#eee" align="left" style="width:45%;border-bottom:1px solid #eee;font:bold 14px Arial;padding:10px 20px;line-height:30px;"><?php echo __("Item Name");?></th>
                <th bgcolor="#eee" style="width:5%;border-bottom:1px solid #eee;font:bold 14px Arial;padding:10px 20px;line-height:30px;"><?php echo __("Qty");?></th>
                <th bgcolor="#eee" align="right" style="width:20%;border-bottom:1px solid #eee;font:bold 14px Arial;padding:10px 20px;line-height:30px;"><?php echo __("Price");?></th>
                <th bgcolor="#eee" align="right" style="width:20%;border-bottom:1px solid #eee;font:bold 14px Arial;padding:10px 20px;line-height:30px;"><?php echo __("Total Price");?></th>
            </tr>

            <?php if(!empty($orderDetails['carts'])) {
                foreach ($orderDetails['carts'] as $cKey => $cValue) { ?>
                <tr>
                    <td align="center" style="width:10%;border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:14px;"><?= $cKey+1?></td>
                    <td align="left" style="width:45%;border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:14px;">
                        <?= $cValue['menu_name'];?>
                        <?php
                        if ($cValue['subaddons_name'] != '') { ?>
                            <br>
                            <?php echo $cValue['subaddons_name'];?>
                        <?php } ?>                            
                    </td>
                    <td style="width:5%;border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:14px;"><?= $cValue['quantity'];?></td>
                    <td align="right" style="width:20%;border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:14px;">
                    <?= ($siteSettings['site_currency']);?>
                     <?= number_format($cValue['menu_price'], 2) ;?></td>
                    <td align="right" style="width:20%;border-bottom:1px solid #eee;font:normal 14px Arial;padding:10px 20px;line-height:14px;"><?= ($siteSettings['site_currency']);?> <?= number_format($cValue['total_price'], 2) ?></td>
                </tr>  
               <?php
               }
             }?>          
            <tr>
                <td colspan="4" align="right"  style="border-bottom:1px solid #eee;font:bold 14px Arial;padding:10px 20px;line-height:14px;"><?php echo __("Sub Total");?></td>
                <td align="right" style="border-bottom:1px solid #eee;font:bold 14px Arial;padding:10px 20px;line-height:14px;"><?= $siteSettings['site_currency'];?> 
                <?= number_format($orderDetails['order_sub_total'],2);?></td>
            </tr>
            <?php if($orderDetails['tax_amount'] > 0) { ?>
                <tr>
                    <td colspan="4" align="right"  style="border-bottom:1px solid #eee;font:bold 14px Arial;padding:10px 20px;line-height:14px;"><?php echo __("Tax");?></td>
                    <td align="right" style="border-bottom:1px solid #eee;font:bold 14px Arial;padding:10px 20px;line-height:14px;"><?= $siteSettings['site_currency'];?>
                    <?= number_format($orderDetails['tax_amount'],2);?></td>
                </tr>
            <?php } ?>
            <?php if($orderDetails['order_type'] == 'delivery') { ?>
                <tr>
                    <td colspan="4" align="right"  style="border-bottom:1px solid #eee;font:bold 14px Arial;padding:10px 20px;line-height:14px;"><?php echo __("Delivery Fees");?></td>
                    <td align="right" style="border-bottom:1px solid #eee;font:bold 14px Arial;padding:10px 20px;line-height:14px;"><?php echo ($orderDetails['delivery_charge'] <= 0) ? 'Free' : $siteSettings['site_currency'].' '.number_format($orderDetails['delivery_charge'],2); ?></td>
                </tr>
            <?php } ?>
            <tr>
                <td colspan="4" align="right"  style="border-bottom:1px solid #eee;font:bold 16px Arial;padding:10px 20px;line-height:14px;"><?php echo __("Total");?></td>
                <td align="right" style="border-bottom:1px solid #eee;font:bold 16px Arial;padding:10px 20px;line-height:14px;"><?= $siteSettings['site_currency'];?>
                <?= number_format($orderDetails['order_grand_total'],2);?></td>
            </tr>
        </table>

        <?php }?>   
    </div>        
</body>
</html>