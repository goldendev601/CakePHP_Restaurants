<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Manage Order
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Manage Order</li>
		</ol>
	</section>
	<section class="content clearfix">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title">Manage Order</h3>
						<a class="btn green pull-right" href="" data-toggle="modal" data-target="#drivers-details">Drivers Details</a>
					</div>
					<div class="box-body">
						<table class="dataTable table table-bordered table-hover">
							<thead>
								<tr>
									<th>S.No</th>
									<th>Date & Time</th>
									<th>Order Information</th>
									<th>Price</th>
									<th>Driver Name/ID </th>
									<th>Driver Name/ID </th>
									<th>Status</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>1</td>
									<td>04-01-2018</td>
									<td>test</td>
									<td>100</td>
									<td>ID 1000</td>
									<td>ID 1000</td>
									<td><a class="buttonStatus"><i class="fa fa-check"></i></a></td>
									<td>
									<span class="">
										<a class="buttonEdit"><i class="fa fa-pencil-square-o"></i></a>
										<a class="buttonAction"><i class="fa fa-trash-o"></i></a>
									</span>
									</td>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<div class="modal fade" id="drivers-details" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Available Drivers List</h4>
        </div>
        <div class="modal-body">
	        <table class="table table-bordered">
	        	<thead>
	        		<tr>
						<th>Drivers Name</th>
						<th>Drivers Status</th>
						<th>Order Id</th>
						<th>Order Status</th>
					</tr>
	        	</thead>
	        	<tbody>
	        		
	        	</tbody>
	        </table>
        </div>
      </div>
    </div>
  </div>