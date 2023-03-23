<div class="content-wrapper">
    <section class="content-header">
		<span class="pull-left order-id-view">
			ORDER ID: <?php echo $orderDetails['order_number']; ?>
		</span>
        <span class="pull-right">
        <a id="btnExport_xls" class="btn btn-primary pull-right">Export via .xls</a>
        <a id="btnExport_csv" class="btn btn-success pull-right" style="margin-right: 5px;" onclick="exportTableToCSV('exported_table.csv')">Export via csv</a>
			<button class="btn btn-danger pull-right" style="margin-right: 5px;" onclick="window.history.go(-1);">Back</button>
		</span>

    </section>
    <section class="content">
        <div class="order_view_page">
            <div class="col-xs-12">
                <div class="common-header">Restaurant and Customer Details</div>
                <div class="col-sm-6">
                    <table class="table table-bordered table-striped" id="restaurant_details_table">
                        <tr>
                            <td>Restaurant Name & Address</td>
                            <td><?php echo $orderDetails['restaurant']['restaurant_name']; ?> /
                                <?php echo $orderDetails['restaurant']['contact_address']; ?> </td>
                        </tr>
                        <?php if($orderDetails['order_type'] == 'delivery') { ?>
                            <tr>
                                <td>Customer Address</td>
                                <td><?php echo $orderDetails['address']; ?></td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td><?= ($orderDetails['order_type'] == 'delivery') ? 'Delivery' : 'Pickup' ?> Date</td>
                            <td><?php echo date('Y-m-d',strtotime($orderDetails['delivery_date'])); ?></td>
                        </tr>
                        <tr>
                            <td><?= ($orderDetails['order_type'] == 'delivery') ? 'Delivery' : 'Pickup' ?> Time</td>
                            <td><?php echo $orderDetails['delivery_time']; ?></td>
                        </tr>
                        <tr>
                            <td>Customer Name</td>
                            <td><?php echo $orderDetails['customer_name']; ?></td>
                        </tr>
                        <tr>
                            <td>Customer Email</td>
                            <td><?php echo $orderDetails['customer_email']; ?></td>
                        </tr>
                    </table>
                </div>
                <div class="col-sm-6">
                    <table class="table table-bordered table-striped" id="customer_details_table">
                        <tr>
                            <td>Phone Number</td>
                            <td><?php echo $orderDetails['customer_phone']; ?></td>
                        </tr>
                        <?php if($orderDetails['split_payment'] == 'Yes') { ?>
                            <tr>
                                <td>Payment Type</td>
                                <td><?php echo strtoupper(($orderDetails['payment_method'] == 'stripe') ? 'Card' : $orderDetails['payment_method']).' & '.'Wallet'; ?></td>
                            </tr>
                        <?php }else { ?>
                            <tr>
                                <td>Payment Type</td>
                                <td><?php echo strtoupper(($orderDetails['payment_method'] == 'stripe') ? 'Card' : $orderDetails['payment_method']) ?></td>
                            </tr>
                        <?php } ?>

                        <tr>
                            <td>Payment Status</td>
                            <td><?php echo ($orderDetails['payment_status'] == 'P') ? 'Paid': 'Not Paid';?></td>
                        </tr>
                        <tr>
                            <td>Order Status</td>
                            <td><?php echo $orderDetails['status']; ?></td>
                        </tr>
                        <?php if(!empty($orderDetails['status']) && ($orderDetails['status'] == 'Failed')){?>      <tr>
                                    <td>Failed Reason</td>
                                    <td><?php echo $orderDetails['failed_reason']; ?></td>
                                </tr>
                        <?php }?>  
                        <tr>
                            <td>Order Type</td>
                            <td><?php echo $orderDetails['order_type']; ?></td>
                        </tr>
                        <?php if($orderDetails['split_payment'] == 'Yes') { ?>
                            <tr>
                                <td>Wallet Amount</td>
                                <td><?php echo number_format($orderDetails['wallet_amount'],2) ?></td>
                            </tr>
                        <?php }?>
                    </table>
                </div>
            </div>
            <div class="col-xs-12">
                <div class="common-header">Order Details</div>
                <table class="table table-bordered table-striped" id="order_details_table">
                    <tr>
                        <thead>
                        <th>S.No</th>
                        <th>Menu Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th align="right">Total Price</th>
                        </thead>
                    </tr> <?php
                    foreach ($orderDetails['carts'] as $key => $value) { ?>

                        <tr>
                            <td><?php echo $key+1; ?> </td>
                            <td><?php echo $value['menu_name']; ?><br>
                                <?php echo $value['subaddons_name']; ?></td>
                            <td><?php echo $value['quantity']; ?></td>
                            <td><?= $siteSettings['site_currency'] ?> <?php echo number_format($value['menu_price'], 2); ?></td>
                            <td align="right"><?= $siteSettings['site_currency'] ?><?php
                                echo number_format($value['total_price'], 2); ?></td>
                        </tr> <?php

                    } ?>

                    <tr>
                        <td style="font-weight:bold" align="right" colspan="4">Subtotal</td>
                        <td style="font-weight:bold" align="right">
                            <?= $siteSettings['site_currency'] ?><?php echo number_format($orderDetails['order_sub_total'], 2); ?></td>
                    </tr>

                    <?php if($orderDetails['tax_amount'] > 0) { ?>
                        <tr>
                            <td style="font-weight:bold" align="right" colspan="4">
                                Tax (<?php echo $orderDetails['tax_percentage']; ?> %)
                            </td>
                            <td style="font-weight:bold" align="right">
                                <?= $siteSettings['site_currency'] ?><?php echo number_format($orderDetails['tax_amount'], 2); ?></td>
                        </tr> <?php
                    }?>
                    <?php if($orderDetails['order_type'] == 'delivery') { ?>
                        <tr>
                            <td style="font-weight:bold" align="right" colspan="4">Delivery Fee</td>
                            <td style="font-weight:bold" align="right">
                                <?php if($orderDetails['delivery_charge'] > 0) { ?>
                                    <?= $siteSettings['site_currency'] ?><?php echo number_format($orderDetails['delivery_charge'], 2); ?>
                                <?php }else { ?>
                                    Free
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>

                    <?php if($orderDetails['offer_amount'] > 0) { ?>
                        <tr>
                            <td style="font-weight:bold" align="right" colspan="4">
                                Offer (<?php echo $orderDetails['offer_percentage']; ?> %)
                            </td>
                            <td style="font-weight:bold" align="right">
                                <?= $siteSettings['site_currency'] ?><?php echo number_format($orderDetails['offer_amount'], 2); ?></td>
                        </tr> <?php
                    }?>

                    <?php if($orderDetails['reward_used'] == 'Y') { ?>
                        <tr>
                            <td style="font-weight:bold" align="right" colspan="4">
                                Redeem Amount (<?php echo $orderDetails['reward_offer_percentage']; ?> %)
                            </td>
                            <td style="font-weight:bold" align="right">
                                <?= $siteSettings['site_currency'] ?><?php echo number_format($orderDetails['reward_offer'], 2); ?></td>
                        </tr> <?php
                    }?>


                    <?php if($orderDetails['voucher_amount'] > 0) { ?>
                        <tr>
                            <td style="font-weight:bold" align="right" colspan="4">
                                Voucher (<?php echo $orderDetails['voucher_percentage']; ?> %)
                            </td>
                            <td style="font-weight:bold" align="right">
                                <?= $siteSettings['site_currency'] ?><?php echo number_format($orderDetails['voucher_amount'], 2); ?></td>
                        </tr> <?php
                    }?>
                    <tr>
                        <td style="font-size:18px;font-weight:bold" align="right" colspan="4">
                            Total</td>
                        <td style="font-size:18px;font-weight:bold" align="right">
                            <?= $siteSettings['site_currency'] ?><?php echo number_format($orderDetails['order_grand_total'], 2); ?></td>
                    </tr>

                    <?php if($orderDetails['order_point'] > 0) { ?>

                        <div class="order_item_list">
                            <?php if($orderDetails['status'] != 'Delivered') { ?>
                                <div class="order-total-txt"><?php echo __('You will Earn');?> </div>
                            <?php }else { ?>
                                <div class="order-total-txt"><?php echo __('You Earned');?> </div>
                            <?php } ?>
                            <div class="order-total-amount"><span class="taxTotal"><?=$orderDetails['order_point'] ?> </span><?php echo __('Points');?></div>
                        </div>
                        <tr>
                            <td style="font-size:18px;font-weight:bold" align="right" colspan="4">
                                <?php if($orderDetails['status'] != 'Delivered') { ?>
                                    <?php echo __('You will Earn');?>
                                <?php }else { ?>
                                    <?php echo __('Customer Earned');?>
                                <?php } ?>
                            </td>
                            <td style="font-size:18px;font-weight:bold" align="right">
                                <?php echo $orderDetails['order_point']; ?> <?php echo __('Points');?></td>
                        </tr>


                    <?php } ?>
                </table>
            </div>
            <?php if($orderDetails['status'] == 'Delivered' && $orderDetails['order_proof'] != '') { ?>
                <div class="col-xs-12">
                    <img src="<?= $orderDetails['order_proof'] ?>">
                </div>
            <?php } ?>
        </div>
    </section>
</div>


<script>
    $("#btnExport_xls").click(function (e) {
    
        //getting values of current time for generating the file name
        var dt = new Date();
        var day = dt.getDate();
        var month = dt.getMonth() + 1;
        var year = dt.getFullYear();
        var hour = dt.getHours();
        var mins = dt.getMinutes();
        var postfix = day + "." + month + "." + year + "_" + hour + "." + mins;
        //creating a temporary HTML link element (they support setting file names)
        var a_restaurant = document.createElement('a');
        var a_customer = document.createElement('a');
        var a_order = document.createElement('a');
        //getting data from our div that contains the HTML table
        var data_type = 'data:application/vnd.ms-excel;charset=utf-8';
        
        var table_html_restaurant = $('#restaurant_details_table')[0].outerHTML;
        var table_html_customer = $('#customer_details_table')[0].outerHTML;
        var table_html_order = $('#order_details_table')[0].outerHTML;        
      
        table_html_restaurant = table_html_restaurant.replace(/<tfoot[\s\S.]*tfoot>/gmi, '');
        table_html_customer = table_html_customer.replace(/<tfoot[\s\S.]*tfoot>/gmi, '');
        table_html_order = table_html_order.replace(/<tfoot[\s\S.]*tfoot>/gmi, '');
        
        var css_html = '<style>td {border: 0.5pt solid #c0c0c0} .tRight { text-align:right} .tLeft { text-align:left} </style>';       
        
        a_restaurant.href = data_type + ',' + encodeURIComponent('<html><head>' + css_html + '</' + 'head><body>' + table_html_restaurant + '</body></html>');       
        a_customer.href = data_type + ',' + encodeURIComponent('<html><head>' + css_html + '</' + 'head><body>' + table_html_customer + '</body></html>');     
        a_order.href = data_type + ',' + encodeURIComponent('<html><head>' + css_html + '</' + 'head><body>' + table_html_order + '</body></html>');      
     
        a_restaurant.download = 'restaurant_details_table_' + postfix + '.xls';   
        a_customer.download = 'customer_details_table_' + postfix + '.xls';  
        a_order.download = 'order_details_table' + postfix + '.xls';     
     
        a_restaurant.click();  
        a_customer.click(); 
        a_order.click(); 

        e.preventDefault();
    });

    function downloadCSV(csv, filename) {
        var csvFile;
        var downloadLink;
        // CSV file
        csvFile = new Blob([csv], {type: "text/csv"});
        // Download link
        downloadLink = document.createElement("a");
        // File name
        downloadLink.download = filename;
        // Create a link to the file
        downloadLink.href = window.URL.createObjectURL(csvFile);
        // Hide download link
        downloadLink.style.display = "none";
        // Add the link to DOM
        document.body.appendChild(downloadLink);
        // Click download link
        downloadLink.click();
    }

    function exportTableToCSV(filename) {
        var csv = [];
        var rows = document.querySelectorAll("table tr");
        
        for (var i = 0; i < rows.length; i++) {
            var row = [], cols = rows[i].querySelectorAll("td, th");
            
            for (var j = 0; j < cols.length; j++) 
                row.push(cols[j].innerText);
            
            csv.push(row.join(","));        
        }

        // Download CSV file
        downloadCSV(csv.join("\n"), filename);
    }

</script>