<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Manage Book a Table
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Manage Book a Table</li>
		</ol>
	</section>
	<section class="content clearfix">
		<div class="alert alert-success" id="orderMessage" style="display:none;"> 
			Successfully book a table status changed</div>
		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title">Manage Addons</h3>
					</div>
					<div class="box-body">
						<div class="table-toolbar"></div>
						<table id="bookTable" class="table table-bordered table-hover checktable">
							<thead>
								<tr>
									<th>S.No</th>
									<th>Book a Table Id</th>
									<th>Customer Name</th>
									<th>Booking Date</th>
									<th>Booking Time</th>
									<th>Phone</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody><?php
                            
                            	$count = 1;
                                foreach($bookaTables as $key => $value) { ?>
									<tr id="orderList_<?php echo $value['id']; ?>" class="odd gradeX">
										<td><?php echo $count; ?></td>
										<td><?php echo $value['booking_id']; ?>	 </td>
										<td><?php echo $value['customer_name']; ?> </td>
										<td><?php echo $value['booking_date']; ?> </td>
										<td><?php echo $value['booking_time']; ?> </td>
										<td><?php echo $value['booking_phone']; ?> </td>

										<td id="bookTableStatus_<?php echo $value['id']; ?>" align="center"> <?php
											if ($value['status'] == 'Pending') {

                                                echo $this->Form->input('bookStatus',[
                                                    'type'=>'select',
                                                    'id' => 'bookStatus'.$value['id'],
                                                    'class'=>'form-control book_status',
                                                    'options'=> $status,
                                                    'onChange' => "bookaTableStatus(".$value['id'].");",
                                                    'label'=> false,
                                                    'value' => $value['status']
                                                ]); ?>

												<div id="reason_<?php echo $value['id']; ?>"></div> <?php
											} else {
												echo $value['status'];
											} ?> </td>

										<td align="center">
											<a class="track_order buttonEdit" href="javascript:void(0);" data-target="#trackid" class=""  data-toggle="modal" onclick="return viewBookTableDetails(<?php echo $value['id'];?>);"><i class="fa fa-search"></i></a>
										</td>
									</tr><?php $count++;
								} ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<div class="modal fade" id="trackid" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
				<h4 class="modal-title center" id="myModalLabel">Book a Table Details</h4>
			</div>
			<div class="modal-body" >
				<div id="bookaTable">

				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

    $(document).ready(function(){
        $('#bookTable').DataTable({
            columnDefs: [ { orderable: false, targets: [-1,-2] } ]
        });
    });

function bookaTableStatus(bookId) {

    var status = $.trim($('#bookStatus' + bookId).val());
   // alert(status);

    if (status != 'Cancel' && status != 'Pending') {
        $.post(jssitebaseurl + 'bookaTables/bookStatus', {'bookId': bookId, 'status': status}, function (response) {
        	//alert(response);
            $('#orderMessage').show();
            $('#bookTableStatus_'+bookId).html(status);
            setTimeout(function () {
                $('#orderMessage').fadeOut();
            }, 3000);

        });
    } else if (status == 'Cancel') {
        html = '<textarea class="form-control margin-t-10 margin-b-10" id="failedReason_' + bookId + '" rows="4" cols="10"></textarea>' +
            '<input type="button" value="Submit" class="btn btn-default" onclick="return changeBookStatus(' + bookId + ');">';
        $("#reason_" + bookId).append(html);
    } else {
        $("#reason_" + bookId).html('');
    }
}

function changeBookStatus(bookId) {
    var reason = $('#failedReason_' + bookId).val();
    //alert(reason);
    if (reason != '') {
        $.post(jssitebaseurl+'bookaTables/bookStatus',{'bookId':bookId, 'status':'Cancel', 'reason':reason}, function(response) {
            $('#orderMessage').show();
            $('#bookTableStatus_'+bookId).html('Cancel');
            setTimeout(function () {
                $('#orderMessage').fadeOut();
            }, 3000);
        });
    } else {
        alert('Please enter the reason for cancel booking table');
    }
}


function viewBookTableDetails(bookId) {
    $('#trackid').show();
    $('#bookaTable').load(jssitebaseurl+'bookaTables/bookatableDetails', {'bookId' : bookId}, function(response) {
    	
    });
    return false;
}
</script>