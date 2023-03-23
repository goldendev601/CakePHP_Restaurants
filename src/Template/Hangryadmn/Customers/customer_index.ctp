<div class="content-wrapper">
    <section class="content-header">
        <h1> Manage Customer AddressBook</h1>   
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo ADMIN_BASE_URL ;?>dashboard">
                    <i class="fa fa-dashboard"></i> Home</a>
            </li>
            <li class="active">
                <a href="<?php echo ADMIN_BASE_URL ;?>customers">Manage Customer</a>
            </li>
        </ol>
    </section>
    <section class="content clearfix">
        <div class="row">
            <div class="col-xs-12">
                <div class="box my-box">
                    <div class="box-body" id="ajaxReplace">
                        <table id="myTable" class="dataTable table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Title</th>  
                                    <th>Flat No</th>  
                                    <th>Address</th>  
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>  
                                <?php if(!empty($addressbookList)) {
                                    foreach($addressbookList as $key => $value) { ?>
                                        <tr id="custAddDel_<?php echo $value['id'] ;?>">
                                            <td><?php echo $key+1 ;?></td>    
                                            <td><?php echo $value['title'] ;?></td>
                                            <td><?php echo $value['flat_no'] ;?></td>
                                            <td><?php echo $value['address'] ;?></td>
                                            <td id="status_<?php echo $value['id'];?>">
                                                <?php if($value['status'] == '0') { ?>
                                                    <button  href="javascript:;" onclick="changeStatus('<?= $value['id'] ?>', '1', 'status', 'customers/ajaxaction', 'customerAddressStatusChange')"><i class="fa fa-close"></i>
                                                   </button>
                                               <?php }else { ?>
                                                   <button  href="javascript:;" onclick="changeStatus('<?= $value['id'] ?>', '0', 'status', 'customers/ajaxaction', 'customerAddressStatusChange')"><i class="fa fa-check"></i>
                                                  </button>
                                               <?php } ?>
                                            </td>   
                                            <td>
                                                <a href="<?php echo ADMIN_BASE_URL ?>customers/customerAddressbook/<?php echo $value['id'] ?>"><i class="fa fa-pencil"></i></a>
                                               <a id="<?php echo $value['id']; ?>" onclick="return deleteRecord(<?php echo $value['id']; ?>, 'customers/deleteCustomer', 'CustomerAddress', '', 'dataTable')" href="javascript:;"><i class="fa fa-trash-o"></i></a>
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
    function changeStatus(id, changestaus, field, urlval, action)
    {
       $.ajax({
            type   : 'POST',
            url    : jssitebaseurl+'customers/ajaxaction',
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
            //$("#maska").show();$(".ui-loader").show();
            $.ajax({
                type   : 'POST',
                url    : jssitebaseurl+'customers/deleteCustomer',
                data   : {id:id, page:page, action:action},
                success: function(data){
                    if (data == 'success') {
                        $("#custAddDel_"+id).remove();
                    }

                    /*$("#ajaxReplace").html(data);
                    $("."+loadDiv).DataTable({
                        columnDefs: [ { orderable: false, targets: [-1,-2,-4] } ]
                    });*/
                }
            });return false;
        }
    }
</script>