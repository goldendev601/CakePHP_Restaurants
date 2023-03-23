<div class="content-wrapper">
    <section class="content-header">
      <span class="pull-left order-id-view">
      Invoice : #FOS0008INV
      </span>
        <span class="pull-right">
      <a href="<?= ADMIN_BASE_URL ?>invoices" class="order-back">Back</a>
      </span>
    </section>
    <section class="content">
        <div class="order_view_page">
            <div class="col-xs-12">
                <span class="pull-left">Cretated : <?= date('Y-m-d h:i A',strtotime($invoiceDetails['created'])) ?></span>
                <span class="pull-right">Period : <?= date('Y-m-d',strtotime($invoiceDetails['start_date'])) ?> to <?= date('Y-m-d',strtotime($invoiceDetails['end_date'])) ?></span>
            </div>
            <div class="col-xs-12 m-b-20">
                <div class="col-sm-4 col-xs-12 no-padding">
                    <div class="order-id-view">Client:</div>
                    <p><?= $invoiceDetails['restaurant']['contact_name'] ?> </p>
                    <p><?= $invoiceDetails['restaurant']['restaurant_name'] ?> </p>
                    <p><?= $invoiceDetails['restaurant']['contact_address'] ?></p>
                </div>
                <div class="col-sm-4 col-xs-12">
                    <!--<div class="order-id-view">About:</div>
                    <p>Welivered </p>-->
                </div>
                <div class="col-sm-4 col-xs-12 text-right no-padding">
                    <div class="order-id-view">Payment Details:</div>
                    <p>V.A.T Reg #: 123 </p>
                </div>
            </div>
            <div class="col-xs-12">
                <table class="table table-bordered table-striped">
                    <tr>
                        <thead>
                        <th colspan="2">Invoice breakdown</th>
                        <th style="text-align:right;">Amount</th>
                        </thead>
                    </tr>
                    <tr>
                        <td>Customers paid cash for</td>
                        <td align="right"><?= $invoiceDetails['cod_count'] ?> orders</td>
                        <td align="right"><?= $siteSettings['site_currency'] ?> <?= number_format($invoiceDetails['cod_price'],2) ?></td>
                    </tr>
                    <tr>
                        <td>Customers paid card</td>
                        <td align="right"><?= $invoiceDetails['card_count'] ?> orders</td>
                        <td align="right"><?= $siteSettings['site_currency'] ?> <?= number_format($invoiceDetails['card_price'],2) ?></td>
                    </tr>

                    <tr>
                        <td>Customers paid Paypal</td>
                        <td align="right"><?= $invoiceDetails['paypal_count'] ?> orders</td>
                        <td align="right"><?= $siteSettings['site_currency'] ?> <?= number_format($invoiceDetails['paypal_price'],2) ?></td>
                    </tr>
                    <tr>
                        <td>Customers paid wallet for</td>
                        <td align="right"><?= $invoiceDetails['wallet_count'] ?> orders</td>
                        <td align="right"><?= $siteSettings['site_currency'] ?> <?= number_format($invoiceDetails['wallet_price'],2) ?></td>
                    </tr>
                    <tr>
                        <td style="font-size:16px;font-weight:normal" align="right" colspan="2">Total value for :</td>
                        <td style="font-size:16px;font-weight:normal" align="right"><?= $siteSettings['site_currency'] ?> <?= number_format($invoiceDetails['subtotal'],2) ?></td>
                    </tr>

                    <tr>
                        <td style="font-size:16px;font-weight:normal" align="right" colspan="2">Online Payments (Including Tax, Delivery charge, Tips) :</td>
                        <td style="font-size:16px;font-weight:normal" align="right"><?= $siteSettings['site_currency'] ?> <?= $invoiceDetails['gross_sale_amount'] ?></td>
                    </tr>
                    <tr>
                        <td style="font-size:16px;font-weight:normal" align="right" colspan="2">Commission(<?= $invoiceDetails['restaurant_commission'] ?>%) :</td>
                        <td style="font-size:16px;font-weight:normal" align="right"><?= $siteSettings['site_currency'] ?> <?= number_format($invoiceDetails['commision'],2) ?></td>
                    </tr>
                    <tr>
                        <td style="font-size:16px;font-weight:normal" align="right" colspan="2">Total Card Fee :</td>
                        <td style="font-size:16px;font-weight:normal" align="right"><?= $siteSettings['site_currency'] ?> <?= number_format($invoiceDetails['cardfee_total'],2) ?></td>
                    </tr>
                    <tr>
                        <td style="font-size:16px;font-weight:normal" align="right" colspan="2">Total Commission with Card fee :</td>
                        <td style="font-size:16px;font-weight:normal" align="right"><?= $siteSettings['site_currency'] ?> <?= number_format($invoiceDetails['commissionTotal'],2) ?></td>
                    </tr>
                    <tr>
                        <td style="font-size:16px;font-weight:normal" align="right" colspan="2">VAT (<?= $invoiceDetails['tax'] ?>%) :</td>
                        <td style="font-size:16px;font-weight:normal" align="right"><?= $siteSettings['site_currency'] ?> <?= number_format($invoiceDetails['commision_tax'],2) ?></td>
                    </tr>
                    <tr>
                        <td style="font-size:16px;font-weight:normal" align="right" colspan="2">Total Commission from Restaurant :</td>
                        <td style="font-size:16px;font-weight:normal" align="right"><?= $siteSettings['site_currency'] ?> <?= number_format($invoiceDetails['commisionGrand'],2) ?></td>
                    </tr>
                    <tr>
                        <td style="font-size:16px;font-weight:normal" align="right" colspan="2">Restaurant Owned ( <?= $siteSettings['site_currency'] ?> <?= number_format($invoiceDetails['gross_sale_amount'],2) ?> - <?= $siteSettings['site_currency'] ?> <?= number_format($invoiceDetails['commisionGrand'],2) ?> ) :</td>
                        <td style="font-size:16px;font-weight:normal" align="right"> <?= ($invoiceDetails['restaurant_owned_total'] < 0) ? '-' : '' ?>  <?= $siteSettings['site_currency'] ?>  <?= number_format($invoiceDetails['restaurant_owned_total'],2) ?></td>
                    </tr>
                </table>
                <!--<div class="col-xs-12 no-padding text-right">
                    <a class="btn btn-sm btn-info">
                        Print <i class="fa fa-print"></i>
                    </a>
                    <a class="btn btn-sm btn-info">
                        DownloadPDF <i class="fa fa-file-pdf-o"></i>
                    </a>
                </div>-->
            </div>

        </div>
    </section>
</div>

