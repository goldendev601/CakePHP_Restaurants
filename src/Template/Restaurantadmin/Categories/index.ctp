<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Manage Category
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Manage Category</li>
		</ol>
	</section>
	<section class="content clearfix">
		<div class="row">
			<div class="col-xs-12">
				<div class="box" my-box>
					<div class="box-header">
						<h3 class="box-title">Category</h3>
						<a class="btn btn-primary pull-right" href="https://www.hangrymenu.com/restaurantadmin/categories/addedit"><i class="fa fa-plus"></i> Add New</a>
					</div>
					<div class="box-body" id="ajaxReplace">
						<table id="categoryTable" class="table table-bordered table-hover">
							<thead>
								<tr>
									<th>SNo</th>
									<th>Category Name</th>
									<th>Sort Order</th>
									<th>Category Id</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>  
                                <?php if(!empty($catList)) {
                                    foreach($catList as $key => $value) { ?>
                                        <tr id="record<?php echo $value['id'];?>">
                                            <td><?php echo $key+1 ;?></td>    
                                            <td><?php echo $value['category_name'] ;?></td>
                                            <td id="sort_order_<?php echo $value['id'] ;?>"><?php echo $value['sortorder'] ;?></td>
                                            <td><?php echo date('Y-m-d h:i A', strtotime($value['created'])); ?>
                                            </td>
                                            <td id="status_<?php echo $value['id'];?>">
                                                <?php if($value['status'] == '0') { ?>
                                                    <button  href="javascript:;" onclick="changeStatus('<?= $value['id'] ?>', '1', 'status', 'categories/ajaxaction', 'categorystatuschange')"><i class="fa fa-close"></i>
                                                   </button>
                                               <?php }else { ?>
                                                   <button  href="javascript:;" onclick="changeStatus('<?= $value['id'] ?>', '0', 'status', 'categories/ajaxaction', 'categorystatuschange')"><i class="fa fa-check"></i>
                                                  </button>
                                               <?php } ?>
                                            </td>                                            
                                            <td>
                                                <a href="https://www.hangrymenu.com/restaurantadmin/categories/addedit/<?php echo $value['id'] ?>"><i class="fa fa-pencil"></i></a>
                                               <a id="<?php echo $value['id']; ?>" onclick="return deleteRecord(<?php echo $value['id']; ?>, 'categories/deletecategory', 'Category', '', 'categotryTable')" href="javascript:;"><i class="fa fa-trash-o"></i></a>
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
        $('#categotryTable').DataTable({
            columnDefs: [ { orderable: false, targets: [-1,-2] } ]
        });
    });

    $(document).ready(function(){
        $("#categotryTable").tableDnD({
            onDragClass: "myDragClass",
            onDrop: function (table, row) {
                var rows = table.tBodies[0].rows;
                var data = '';
                var debugStr = "Row dropped was " + row.id + ". New order: ";

                for (var i = 0; i < rows.length; i++) {
                    debugStr += rows[i].id + "^";
                    data += rows[i].id;
                    $("#sort_order_" + rows[i].id).html(i+1);
                }

                $.ajax({
                    type: 'POST',
                    url: jssitebaseurl+'categories/sortOrder',
                    data: {data: data},
                    success: function (data) {
                        //alert(data);
                        window.location.reload();
                    }
                });
                return false;
            }
        });
    });

    function changeStatus(id, changestaus, field, urlval, action)
    {
       $.ajax({
            type   : 'POST',
            url    : jssitebaseurl+'categories/ajaxaction',
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
                url    : jssitebaseurl+'categories/deletecategory',
                data   : {id:id, page:page, action:action},
                success: function(data){
                    $("#ajaxReplace").html(data);                
                }
            });return false;
        }
    }
</script>
