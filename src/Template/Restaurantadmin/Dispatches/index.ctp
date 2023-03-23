<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Dispatch
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dispatch</li>
        </ol>
    </section>
    <section class="content clearfix">
        <span class="statusshow" style="display:none;"></span>
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body" id="ajaxReplace">
                        <table id="orderpage" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Date & Time</th>
                                <th>Order Information</th>
                                <th>Price</th>
                                <th>Driver Name</th>
                                <th>Status</th>
                                <th>Action</th>
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

    $(document).ready(function(){
        $('#orderpage').DataTable({
            columnDefs: [ { orderable: false, targets: [-1,-2] } ]
        });

        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher('<?php echo PUSHER_AUTHKEY ?>', {
            encrypted: true
        });

        var channel = pusher.subscribe('my-channelReject');
        channel.bind('my-eventReject', function(data) {

            showOrders();

            $(".statusshow").show();
            $(".statusshow").html('Driver Reject the order '+data.id);

            setTimeout(function(){
                $(".statusshow").css("display",'none');
            },3000);

        });

        var channel = pusher.subscribe('my-channelTrack');
        channel.bind('my-eventTrack', function(data) {

            if(data.status == 'Driver Accepted' || data.status == 'Collected' || data.status == 'Delivered') {

                $(".statusshow").show();
                $(".statusshow").html(data.message);
                setTimeout(function(){
                    $(".statusshow").css("display",'none');
                },3000);
            }


            showOrders();


        });
    });

    $(document).ready(function () {
        showOrders();
    });

    function showOrders() {
        $("#orderpage").DataTable().destroy();
        $('#orderpage').DataTable( {
            serverSide: true,
            processing: true,
            "ajax": {
                "url": jssitebaseurl+"dispatches/getOrderDetails",
                "type": "POST",
                'data': {

                }
            },
            "columns": [
                { "data": "Id"  },
                { "data": "Date & Time"  },
                { "data": "Order Information"  },
                { "data": "Price"  },
                { "data": "Driver Name"  },
                { "data": "Status"  },
                { "data": "Action"  }

            ]
        });

        setTimeout(function() {
            showOrders();
        }, 100000);
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

    function getDriverList(id) {
        if(id != '') {
            $.ajax({
                'type' : 'POST',
                'url' : jssitebaseurl+'orders/ajaxaction',
                'data': {id:id,action:'getDriverLists'},
                success: function(data) {
                    $("#driverListPopup").modal('show');
                    $("#assignOrder").html(data);
                }
            })
        }
    }

    function viewTrack(ordId) {

        $('#trackOrderId').val(ordId);
        $('#trackid').modal('show');
        $('#initialmap').html('');
        $.ajax({
            'type' : 'POST',
            'url' : jssitebaseurl+'orders/ajaxaction',
            'data': {id:ordId,action:'InitialTracking'},
            success: function(data) {
                $("#initialmap").html(data);
                trackings();
            }
        });

        return false;
    }

    function trackings() {
        var ordId = $('#trackOrderId').val();

        if (ordId != '' && $('#trackid:hidden').length == 0) {

            $.ajax({
                'type' : 'POST',
                'url' : jssitebaseurl+'orders/ajaxaction',
                'data': {id:ordId,action:'LoadTrackingMap'},
                success: function(data) {
                    removeMapIcons();
                    $.trim(data);
                    var result = data.split('||@@||');
                    $('#TrackingMap').html(result[0]);
                }
            });
        }
        setTimeout(function() {
            trackings();
        }, 5000);
        return false;
    }

    //Remove all icons from map
    function removeMapIcons() {
        deleteMarkers();
        if ($('[name=direction]').val() == 'available') {
            directions1Display.setMap(null);
            directions1Display.setPanel(null);
        }
    }

    //Delete all marker
    function deleteMarkers() {
        for (var i = 0; i < markers.length; i++) {
            markers[i].setMap(null);
        }

    }

    //Assign Order
    function assignOrder(ord, driver) {

        $('#assign'+driver).hide();
        $('#waiting'+driver).show();

        $.ajax({
            'type' : 'POST',
            'url' : jssitebaseurl+'orders/assignOrder',
            'data': {id:ord,driver:driver},
            success: function(data) {
                if (data == 1) {
                    $("#driverListPopup").modal('hide');
                    showOrders();
                    return false;

                }
            }
        });


        return false;
    }

    function disclaimOrder(orderId) {
        $.ajax({
            'type' : 'POST',
            'url' : jssitebaseurl+'orders/orderStatus',
            'data': {id:orderId,status:'Accepted'},
            success: function(data) {
                if (data == 1) {
                    showOrders()
                    return false;
                }
            }
        });
    }

    function submitReason(id) {
        var status = 'Failed';
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

<div class="modal fade" id="driverListPopup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel1">Online Drivers</h4>
            </div>
            <div class="modal-body" >

                <table class="table table-striped table-bordered table-hover" id="sample_12">
                    <thead>
                    <tr>
                        <th> S.No </th>
                        <th>Driver</th>
                        <th>Vehicle</th>
                        <th>Status</th>
                        <th class="sorting_asc">Distance</th>
                        <th>ETA</th>
                        <th>Assign</th>
                    </tr>
                    </thead>
                    <tbody id = "assignOrder">
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="trackid" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                <h4 class="modal-title center" id="myModalLabel">Map</h4>
            </div>
            <div class="modal-body" >
                <div id="trackingDistance"> </div>
                <input type="hidden" id="trackOrderId" value=""/>
                <div id="initialmap">
                    <?php
                    //echo $this->GoogleMap->map();
                    ?>
                </div>
                <div id="TrackingMap"></div>
            </div>
        </div>
    </div>
</div>


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