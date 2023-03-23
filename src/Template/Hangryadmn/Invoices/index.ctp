<div class="content-wrapper">
    <section class="content-header">
        <h1> Manage Invoice </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>
    <section class="content clearfix">
        <div class="row">
            <div class="col-xs-12">
                <div class="box my-box">
                    <div class="box-header">
                        <!--<a class="btn btn-primary pull-right" href="<?php /*echo ADMIN_BASE_URL;*/?>drivers/add"><i class="fa fa-plus"></i> Add New</a>-->
                    </div>
                    <div class="box-body" id="ajaxReplace">
                        <table id="driverTable" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Invoice ID</th>
                                <th>Restaurant Name</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(!empty($invoiceDetails)) {
                                foreach($invoiceDetails as $key => $value) { ?>
                                    <tr>
                                        <td><?php echo $key+1 ;?></td>
                                        <td><?php echo $value['ref_id'] ;?></td>
                                        <td><?php echo $value['restaurant']['restaurant_name'] ;?></td>
                                        <td><?php echo date('Y-m-d',strtotime($value['start_date'])) ;?></td>
                                        <td><?php echo date('Y-m-d',strtotime($value['end_date'])) ;?></td>
                                        <td>
                                            <a href="<?php echo ADMIN_BASE_URL ?>invoices/view/<?php echo $value['id'] ?>">View</a>
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

</script>