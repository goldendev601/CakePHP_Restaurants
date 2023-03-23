<div class="content-wrapper">
    <section class="content-header">
        <h1> Driver </h1>        
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>
    <section class="content clearfix">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Driver</h3>
                        <a class="btn green pull-right btn btn-primary" href="<?php echo REST_BASE_URL;?>drivers/add">Add New <i class="fa fa-plus"></i></a>
                    </div>
                    <div class="box-body" id="ajaxReplace">
                        <table class="dataTable table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Driver Name</th>  
                                    <th>Phone Number</th> 
                                    <th>Email ID</th>  
                                    <th>Payout</th>
                                    <th>Is Logged</th>
                                    <th>Status</th>               
                                    <th>Action</th>
                                    <th>Billing</th>
                                </tr>
                            </thead>
                            <?php if(!empty($driverList)) { ?>
                                <?php foreach ($driverList as $key => $value) { ?>
                                <tr>
                                    <td><?php echo $key+1;?></td>
                                    <td><?php echo $value['driver_name'];?></td>
                                    <td><?php echo $value['phone_number'];?></td>
                                    <td><?php echo $value['username'];?></td>
                                    <td><?php echo $value['payout'];?></td>
                                    <td id="is_logged_<?php echo $value['id'];?>">
                                        <?php if($value['is_logged'] == '0') { ?>
                                            <button  href="javascript:;" onclick="changeStatus('<?= $value['id'] ?>', '1', 'is_logged', 'drivers/ajaxaction', 'driverLoginStatus')"><i class="fa fa-lock" aria-hidden="true"></i>
                                            </button>
                                        <?php }else { ?>
                                            <button  href="javascript:;" onclick="changeStatus('<?= $value['id'] ?>', '0', 'is_logged', 'drivers/ajaxaction', 'driverLoginStatus')"><i class="fa fa-truck" aria-hidden="true"></i>
                                            </button>
                                        <?php } ?>
                                    </td>
                                    <td id="status_<?php echo $value['id'];?>">
                                        <?php if($value['status'] == '0') { ?>
                                            <button  href="javascript:;" onclick="changeStatus('<?= $value['id'] ?>', '1', 'status', 'drivers/ajaxaction', 'driverStatusChange')"><i class="fa fa-close"></i>
                                           </button>
                                       <?php }else { ?>
                                           <button  href="javascript:;" onclick="changeStatus('<?= $value['id'] ?>', '0', 'status', 'drivers/ajaxaction', 'driverStatusChange')"><i class="fa fa-check"></i>
                                          </button>
                                       <?php } ?>
                                    </td>  
                                    <td>
                                        <a href="<?php echo REST_BASE_URL;?>drivers/edit/<?php echo $value['id'] ?>"><i class="fa fa-pencil"></i></a>
                                       <a id="<?php echo $value['id']; ?>" onclick="return deleteRecord(<?php echo $value['id']; ?>, 'drivers/deleteDriver', 'Driver', '', 'dataTable')" href="javascript:;"><i class="fa fa-trash-o"></i></a>
                                    </td>
                                    <td>
                                        <a href="<?php echo REST_BASE_URL ?>drivers/billing/<?php echo $value['id'] ?>">Billing Details</a>
                                    </td>
                                </tr>
                        <?php   }
                            } ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<script type="text/javascript">
    function changeStatus(id, changestaus, field, urlval, action)
    {
       $.ajax({
            type   : 'POST',
            url    : jssitebaseurl+'drivers/ajaxaction',
            data   : {id:id, field:field ,changestaus:changestaus,action:action},
            success: function(data){
               
                if(action == '') {
                    window.location.reload();
                }else {                    
                    $("#"+field+"_"+id).html(data);
                    return false;
                }
            }
        });
        return false;
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
                    $("."+loadDiv).DataTable({
                        columnDefs: [ { orderable: false, targets: [-1,-2] }]
                    });                
                }
            });return false;
        }
    }
</script>