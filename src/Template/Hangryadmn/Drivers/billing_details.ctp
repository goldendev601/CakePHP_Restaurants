<div class="content-wrapper">
    <section class="content-header">
		<span class="pull-left order-id-view">
			Invoice Details - <?= $invoiceDetails['invoice_number'] ?>
		</span>
        <span class="pull-right">
			<a href="<?= ADMIN_BASE_URL ?>drivers/billing/<?= $invoiceDetails['driver_id'] ?>" class="order-back">Back</a>
		</span>
    </section>
    <section class="content">
        <div class="order_view_page">
            <div class="col-xs-12">
                <table class="table table-bordered table-striped">
                    <tr>
                        <thead>
                        <th>ID</th>
                        <th>Payout Type</th>
                        <th>Per Distance</th>
                        <th>Delivery Distance</th>
                        <th align="right">Total Price</th>
                        </thead>
                    </tr>
                    <?php if(!empty($orderDetails)) {
                        foreach ($orderDetails as $key => $value) { ?>

                            <tr>
                                <td><?= $value['order_number'] ?></td>
                                <td><?= strtoupper($value['payout_type']) ?></td>
                                <td><?= ($value['payout_type'] == 'distance') ? $value['payout_amount'] : '-' ?></td>
                                <td><?= ($value['payout_type'] == 'distance') ? $value['distance'] : '-' ?></td>
                                <td align="right"><?= $siteSettings['site_currency'] ?> <?= ($value['payout_type'] == 'distance') ? number_format($value['distance_amount'],2) : number_format($value['payout_amount'],2) ?></td>

                            </tr>
                    <?php

                        }
                    } ?>
                    <tr>
                        <td style="font-size:15px" align="right" colspan="4">Total</td>
                        <td style="font-size:18px;font-weight:bold" align="right"><?= $siteSettings['site_currency'] ?> <?= number_format($invoiceDetails['invoice_amount'],2) ?></td>
                    </tr>

                </table>
            </div>
        </div>
    </section>
</div>
