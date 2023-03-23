<div class="content-wrapper">
    <section class="content-header">
        <h1> Manage Customer </h1>   
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
                       <a class="btn btn-primary pull-right" href="<?php echo ADMIN_BASE_URL;?>customers/add"><i class="fa fa-plus"></i> Add New</a>
                    </div>
                    <div class="box-body" id="ajaxReplace">
                        <table id="customerTable" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Customer Name</th>  
                                    <th>Email ID</th>  
                                    <th>Phone Number</th>  
                                    <th>Referred By</th>
                                    <th>Wallet</th>
                                    <th>Status</th>
                                    <th>Options</th> 
                                    <th>Wallet</th> 
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>  
                                <?php if(!empty($customerList)) {
                                    foreach($customerList as $key => $value) { ?>
                                        <tr>
                                            <td><?php echo $key+1 ;?></td>    
                                            <td><?php echo $value['first_name'] ;?></td>
                                            <td><?php echo $value['username'] ;?></td>
                                            <td><?php echo $value['phone_number'] ;?></td>
                                            <td><?php echo $value['referred_by'] ;?></td>
                                            <td><?php echo $value['wallet_amount'] ;?></td>
                                            <td id="status_<?php echo $value['id'];?>">
                                                <?php if($value['status'] == '0') { ?>
                                                    <button  href="javascript:;" onclick="changeStatus('<?= $value['id'] ?>', '1', 'status', 'customers/ajaxaction', 'customerStatusChange')"><i class="fa fa-close"></i>
                                                   </button>
                                               <?php }else { ?>
                                                   <button  href="javascript:;" onclick="changeStatus('<?= $value['id'] ?>', '0', 'status', 'customers/ajaxaction', 'customerStatusChange')"><i class="fa fa-check"></i>
                                                  </button>
                                               <?php } ?>
                                            </td>   
                                            <td><a href="<?php echo ADMIN_BASE_URL ?>customers/customerIndex/<?php echo $value['id'] ?>">AddressBook</a></td>
                                            <td><a href="#" onclick="return addMoney('<?= $value['id'] ?>')" >AddMoney</a></td>
                                            <td>
                                                <a href="<?php echo ADMIN_BASE_URL ?>customers/edit/<?php echo $value['id'] ?>"><i class="fa fa-pencil"></i></a>
                                               <a id="<?php echo $value['id']; ?>" onclick="return deleteRecord(<?php echo $value['id']; ?>, 'customers/deleteCustomer', 'Customer', '', 'customerTable')" href="javascript:;"><i class="fa fa-trash-o"></i></a>
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
        $('#customerTable').DataTable({
            columnDefs: [ { orderable: false, targets: [-1,-2] } ]
        });
    });

    function addMoney(id) {
        $("#customerId").val(id);
        $("#walletAdd").modal('show');
    }

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
            $.ajax({
                type   : 'POST',
                url    : jssitebaseurl+'customers/deleteCustomer',
                data   : {id:id, page:page, action:action},
                success: function(data){
                    $("#ajaxReplace").html(data);
                    $("#"+loadDiv).DataTable({
                        columnDefs: [ { orderable: false, targets: [-1,-2,-4] } ]
                    });
                }
            });return false;
        }
    }

    function addMoneyToAccount() {
        $(".error").html('');
        var amount = $.trim($("#amount").val());
        var purpose = $.trim($("#purpose").val());
        var customerId = $.trim($("#customerId").val());

        if(amount == '') {
            $(".amountErr").addClass('error').html('Please enter your amount');
            $("#amount").focus();
            return false;
        }else if(amount <= 0) {
            $(".amountErr").addClass('error').html('Please enter valid amount');
            $("#amount").focus();
            return false;
        }else if(purpose == '') {
            $(".purposeErr").addClass('error').html('Please enter purpose');
            $("#purpose").focus();
            return false;
        }else {
            $('#addwallet').hide();
            $.ajax({
                type   : 'POST',
                url    : jssitebaseurl+'customers/addMoney',
                data   : {id:customerId, amount:amount, purpose:purpose},
                success: function(data){
                    if($.trim(data) == 1) {
                        $('#addwallet').show();
                        window.location.reload();
                    }else{
                        $(".amountErr").addClass('error').html('error acquired');
                        $("#amount").focus();
                        return false;
                    }
                }
            });return false;
        }
    }
</script>


<div class="modal fade" id="walletAdd" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel1">Add Money</h4>
            </div>
            <div class="modal-body">
                <form>
                    <input type="hidden" id="customerId">
                    <span class="failedreason"> </span>
                    <div class="form-group ">
                        <label for="message-text" class="control-label">Amount:</label>
                        <input type="text" class="form-control" id="amount"></input>
                    </div>
                    <span class="amountErr"></span>

                    <div class="form-group ">
                        <label for="message-text" class="control-label">Message:</label>
                        <textarea class="form-control" id="purpose"></textarea>
                    </div>
                    <span class="purposeErr"></span>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button id="addwallet" onclick="return addMoneyToAccount()" type="button" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</div>