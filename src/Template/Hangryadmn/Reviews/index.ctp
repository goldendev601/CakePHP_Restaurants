<div class="content-wrapper">
    <section class="content-header">
        <h1> Manage Reviews </h1>   
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
                        <table id="reviewTable" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Order Id</th> 
                                    <th>Restaurant Name</th> 
                                    <th>rating</th>  
                                    <th>Message</th>        
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>  
                                <?php if(!empty($allReviews)) {
                                    foreach($allReviews as $key => $value) { ?>
                                        <tr>
                                            <td><?php echo $key+1 ;?></td>    
                                            <td><?php echo $value['order_id'] ;?></td>
                                            <td><?php echo $value['restaurant_name'] ;?></td>
                                            <td><?php echo $value['rating'] ;?></td>
                                            <td><?php echo $value['message'] ;?></td>
                                            <td id="status_<?php echo $value['id'];?>">
                                                <?php if($value['status'] == '0') { ?>
                                                    <button  href="javascript:;" onclick="changeStatus('<?= $value['id'] ?>', '1', 'status', 'reviews/ajaxaction', 'reviewStatusChange')"><i class="fa fa-close"></i>
                                                   </button>
                                               <?php }else { ?>
                                                   <button  href="javascript:;" onclick="changeStatus('<?= $value['id'] ?>', '0', 'status', 'reviews/ajaxaction', 'reviewStatusChange')"><i class="fa fa-check"></i>
                                                  </button>
                                               <?php } ?>
                                            </td>   
                                            <td>
                                               <a id="<?php echo $value['id']; ?>" onclick="return deleteRecord(<?php echo $value['id']; ?>, 'reviews/deleteReview', 'reviews', '', 'reviewTable')" href="javascript:;"><i class="fa fa-trash-o"></i></a>
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
        $('#reviewTable').DataTable({
            columnDefs: [ { orderable: false, targets: [-1,-2] } ]
        });
    });

    function changeStatus(id, changestaus, field, urlval, action)
    {
       $.ajax({
            type   : 'POST',
            url    : jssitebaseurl+'reviews/ajaxaction',
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
                url    : jssitebaseurl+'reviews/deleteReview',
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