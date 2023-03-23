<div class="content-wrapper">
    <section class="content-header">
        <h1> Manage Driver </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">
                <a href="<?php echo ADMIN_BASE_URL ;?>drivers">Manage Driver</a>
            </li>
        </ol>
    </section>
    <section class="content clearfix">
        <div class="row">
            <div class="col-xs-12">
                <div class="box my-box">
                    <div class="box-header">
                        <?php if(!empty($invoiceDetails)) {?>
                            <a class="btn btn-primary pull-right" href="#">Withdraw</a>
                        <?php }?>
                    </div>
                    <div class="box-body" id="ajaxReplace">
                        <table id="driverTable" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Invoice Number</th>
                                <th>Invoice Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Detail</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(!empty($invoiceDetails)) {
                                foreach($invoiceDetails as $key => $value) {

                                    ?>
                                    <tr>
                                        <td><?php echo $key+1 ;?></td>
                                        <td><?php echo h($value['invoice_number']) ;?></td>
                                        <td><?php echo date('Y-m-d',strtotime($value['invoice_date'])) ;?></td>
                                        <td><?php echo $value['invoice_amount'] ;?></td>


                                        <td>
                                            <?php if($value['status'] == 'unpaid') { ?>
                                                <select id="currentStatus_<?= $value['id'] ?>" onchange="invoiceStatus(<?= $value['id'] ?>)">
                                                    <option value="unpaid">Unpaid</option>
                                                    <option value="paid">Paid</option>
                                                </select>
                                                <?php
                                            }else { ?>
                                                Paid
                                                <?php
                                            } ?>
                                        </td>

                                        <td>
                                            <a href="<?php echo ADMIN_BASE_URL ?>drivers/billingDetails/<?php echo $value['id'] ?>">View</a>
                                        </td>
                                    </tr>
                                <?php }
                            } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">

    $(document).ready(function(){
        $('#driverTable').DataTable({
            columnDefs: [ { orderable: false, targets: [-1,-2] } ]
        });
    });

    function invoiceStatus(id) {
        var status = $("#currentStatus_"+id+" option:selected").val();

        if(status != '' ) {
            $.ajax({
                'type' : 'POST',
                'url' : jssitebaseurl+'drivers/invoiceStatus',
                'data': {id:id,status:status},
                success: function(data) {
                    $("#ajaxReplace").html(data);
                    $('#driverTable').DataTable({
                        columnDefs: [ { orderable: false, targets: [-1,-2] } ]
                    });
                }
            })
        }
    }

    function deleteRecord(id, urlval, action, page, loadDiv)
    {
        var str = "Are you sure want to delete this "+action;
        if(confirm(str))
        {
            $.ajax({
                type   : 'POST',
                url    : jssitebaseurl+'drivers/deleteDriver',
                data   : {id:id, page:page, action:action},
                success: function(data){
                    $("#ajaxReplace").html(data);
                    $("#"+loadDiv).DataTable({
                        columnDefs: [ { orderable: false, targets: [-1,-2] } ]
                    });
                }
            });return false;
        }
    }
</script>