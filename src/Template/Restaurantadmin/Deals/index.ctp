<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Manage Deal
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Manage Deal</li>
		</ol>
	</section>
	<section class="content clearfix">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title">Manage Deal</h3>
						<a class="btn green pull-right" href="<?php echo REST_BASE_URL; ?>Deals/add">Add New <i class="fa fa-plus"></i></a>
					</div>
					<div class="box-body">
						<table class="dataTable table table-bordered table-hover">
							<thead>
								<tr>
									<th><input type="checkbox" class="minimal"></th>
									<th>Deals Name</th>
									<th>Product Name</th>
									<th>Added Date</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><input type="checkbox" class="minimal"></td>
									<td>Combo Deal</td>
									<td>Chicken</td>
									<td>2016-05-26 10:42:36</td>
									<td><a class="buttonStatus"><i class="fa fa-check"></i></a></td>
									<td>
									<span class="">
										<a href="<?php echo REST_BASE_URL; ?>Deals/edit" class="buttonEdit"><i class="fa fa-pencil-square-o"></i></a>
										<a href="<?php echo REST_BASE_URL; ?>Deals/edit"  class="buttonAction"><i class="fa fa-trash-o"></i></a>
									</span>
									</td>
								</tr>
								<tr>
									<td><input type="checkbox" class="minimal"></td>
									<td>Combo Deal</td>
									<td>Chicken</td>
									<td>2016-05-26 10:42:36</td>
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