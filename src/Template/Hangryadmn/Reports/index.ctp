<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Reports
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>
    <section class="content clearfix">
        <div class="row">
            <div class="col-xs-12">
                <div class="box my-box">

                    <div class="box-body" id="ajaxReplace">
                        <table id="orderpage" class="orderpage table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Order ID</th>
                                <th>Customer Name</th>
                                <th>Restaurant Name</th>
                                <th>Delivery Date</th>
                                <th>Status</th>
                                <th>Order Date</th>
                            </tr>
                            </thead>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>

    $(document).ready(function () {
        $('#orderpage').DataTable({
            columnDefs: [ { orderable: false, targets: [-2] } ]
        });
        showOrders();
    });

    function showOrders() {
        $("#orderpage").DataTable().destroy();
        $('#orderpage').DataTable( {
            serverSide: true,
            processing: true,
            "ajax": {
                "url": jssitebaseurl+"reports/getOrderDetails",
                "type": "POST",
                'data': {

                }
            },
            "columns": [
                { "data": "Id"  },
                { "data": "Order ID"  },
                { "data": "Customer Name"  },
                { "data": "Restaurant Name"  },
                { "data": "Delivery Date"  },
                { "data": "Status"  },
                { "data": "Order Date"  }

            ]
        });
    }


    function changeOrderStatus(id) {
        var status = $("#currentStatus_"+id+" option:selected").val();
        if(status == 'Failed') {
            $("#orderId").val(id);
            $("#failedReason").modal('show');return false;
        }
        if(status != '' ) {
            $.ajax({
                'type' : 'POST',
                'url' : jssitebaseurl+'orders/changeStatus',
                'data': {id:id,status:status},
                success: function(data) {
                    if($.trim(data) == '1') {
                        showOrders();
                    }
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
                url    : jssitebaseurl+''+urlval,
                data   : {id:id, page:page, action:action},
                success: function(data){

                    $("#ajaxReplace").html(data);
                    $("."+loadDiv).DataTable({
                        columnDefs: [ { orderable: false, targets: [-1,-2,-4] } ]
                    });
                }
            });return false;
        }
    }

    function submitReason(id) {
        var status = 'failed';
        var failed_reason = $("#failedreason").val();
        var id = $("#orderId").val();

        if(status != '' && failed_reason == '') {
            $(".failedreason").addClass('error').html("Please enter your reason");
            $("#failedreason").focus();
            return false;

        }else{
            $.ajax({
                'type' : 'POST',
                'url' : jssitebaseurl+'orders/changeStatus',
                'data': {id:id,status:status,failed_reason:failed_reason},
                success: function(data) {
                    if($.trim(data) == '1') {
                        $("#failedReason").modal('hide');
                        $("#failedreason").val('');
                        showOrders();
                    }
                }
            })
        }
    }
</script>

<div class="modal fade" id="failedReason" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel1">Failed Reason</h4>
            </div>
            <div class="modal-body">
                <form>
                    <input type="hidden" id="orderId">
                    <span class="failedreason"> </span>
                    <div class="form-group">
                        <label for="message-text" class="control-label">Message:</label>
                        <textarea class="form-control" id="failedreason"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button onclick="submitReason()" type="button" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</div>