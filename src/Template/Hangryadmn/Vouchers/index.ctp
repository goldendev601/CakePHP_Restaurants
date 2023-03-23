<div class="content-wrapper">
    <section class="content-header">
        <h1>Manage Voucher</h1> 
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
                       <a class="btn btn-primary pull-right" href="<?php echo ADMIN_BASE_URL;?>vouchers/addedit"><i class="fa fa-plus"></i> Add New</a>
                    </div>
                    <div class="box-body" id="ajaxReplace">
                        <table id="voucherTable" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Voucher Code</th>                                
                                    <th>Type </th>                               
                                    <th>Mode </th> 
                                    <th>Offer </th> 
                                    <th>Valid From </th>                               
                                    <th>Valid To </th> 
                                    <th>Status </th> 
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>  
                                <?php if(!empty($vouchersList)) {

                                    foreach($vouchersList as $key => $value) { ?>
                                        <tr>
                                            <td><?php echo $key+1 ;?></td>    
                                            <td><?php echo $value['voucher_code'];?></td>
                                            <td><?php echo $value['type_offer'];?></td>
                                            <td><?php echo ($value['offer_mode'] == 'free_delivery') ? 'free delivery': $value['offer_mode'];?></td>
                                            <td><?php
                                                if($value['offer_mode'] == 'free_delivery'){
                                                    echo $value['free_delivery'];
                                                }else{
                                                    echo $value['offer_value'];
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo $value['voucher_from'];?></td>
                                            <td><?php echo $value['voucher_to'];?></td>
                                            <td id="status_<?php echo $value['id'];?>">
                                                <?php if($value['status'] == '0') { ?>
                                                    <button  href="javascript:;" onclick="changeStatus('<?= $value['id'] ?>', '1', 'status', 'vouchers/ajaxaction', 'voucherStatuschange')"><i class="fa fa-close"></i>
                                                   </button>
                                               <?php }else { ?>
                                                   <button  href="javascript:;" onclick="changeStatus('<?= $value['id'] ?>', '0', 'status', 'vouchers/ajaxaction', 'voucherStatuschange')"><i class="fa fa-check"></i>
                                                  </button>
                                               <?php } ?>
                                            </td>                                            
                                            <td>
                                                <a href="<?php echo ADMIN_BASE_URL ?>vouchers/addedit/<?php echo $value['id'] ?>"><i class="fa fa-pencil"></i></a>
                                               <a id="<?php echo $value['id']; ?>" onclick="return deleteRecord(<?php echo $value['id']; ?>, 'vouchers/deleteVoucher', 'Vouchers', '', 'voucherTable')" href="javascript:;"><i class="fa fa-trash-o"></i></a>
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
        $('#voucherTable').DataTable({
            columnDefs: [ { orderable: false, targets: [-1,-2] } ]
        });
    });

    function changeStatus(id, changestaus, field, urlval, action)
    {
       $.ajax({
            type   : 'POST',
            url    : jssitebaseurl+'vouchers/ajaxaction',
            data   : {id:id, field:field ,changestaus:changestaus,action:action},
            success: function(data){
               //alert(data); return false;
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
                url    : jssitebaseurl+'vouchers/deleteVoucher',
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