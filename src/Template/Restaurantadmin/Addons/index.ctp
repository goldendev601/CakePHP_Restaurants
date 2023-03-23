<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Manage Addons
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Manage Addons</li>
		</ol>
	</section>
	<section class="content clearfix">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title">Manage Addons</h3>
						<a class="btn btn-primary pull-right" href="<?php echo "https://www.hangrymenu.com/restaurantadmin/";?>addons/add"><i class="fa fa-plus"></i> Add New</a>							
					</div>
					<div class="box-body" id="ajaxReplace">
						<table id="addonTable" class="table table-bordered table-hover">
							<thead>
								<tr>
									<th>S.No</th>
									<th>Addons Name</th>
									<th>Category</th>
									<th>Added Date</th>
									<th>Status</th>
 									<th>Action</th>									
								</tr>
							</thead>
							<tbody>
			                                <?php if(!empty($addonsList)) {
                            				    foreach($addonsList as $key => $value) { ?>
                                    				<tr>
                                       				    <td><?php echo $key+1 ;?></td>
                                        			    <td><?php echo $value['mainaddons_name'] ;?></td>
                                       			            <td><?php echo $value['category']['category_name'];?></td>
                                                                    <td><?php echo date('Y-m-d h:i A', strtotime($value['created'])); ?>
                                                                    </td>
                                                                    <td id="status_<?php echo $value['id'];?>">
                                                                        <?php if($value['status'] == '0') { ?>
                                                                            <button  href="javascript:;" onclick="changeStatus('<?= $value['id'] ?>', '1', 'status', 'addons/ajaxaction', 'addonStatusChange')"><i class="fa fa-close"></i>
                                                                            </button>
                                                                        <?php }else { ?>
                                                                             <button  href="javascript:;" onclick="changeStatus('<?= $value['id'] ?>', '0', 'status', 'addons/ajaxaction', 'addonStatusChange')"><i class="fa fa-check"></i>
                                                                             </button>
                                                                        <?php } ?>
                                                                    </td>
                                                                    <td>
                                                                        <a href="<?php echo "https://www.hangrymenu.com/restaurantadmin/" ;?>addons/edit/<?php echo $value['id'] ?>">
                                                                        <i class="fa fa-pencil"></i></a>
                                                                        <a id="<?php echo $value['id']; ?>" onclick="return deleteRecord(<?php echo $value['id']; ?>, 'addons/deleteAddon', 'Addon', '', 'addonTable')" href="javascript:;">
                                                                        <i class="fa fa-trash-o"></i></a>
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
        $('#addonTable').DataTable({
            columnDefs: [ { orderable: false, targets: [-1, -2] } ]
        });
    });

    function changeStatus(id, changestaus, field, urlval, action)
    {
        $.ajax({
            type   : 'POST',
            url    : jssitebaseurl+'addons/ajaxaction',
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
                url    : jssitebaseurl+'addons/deleteAddon',
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

</script>
