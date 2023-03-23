<div class="content-wrapper">
    <section class="content-header">
        <h1> Manage Coupon</h1> 
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>            
        </ol>
    </section>
    
    <section class="content clearfix">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                       <a class="btn btn-primary pull-right" href="<?php echo REST_BASE_URL;?>coupons/add"><i class="fa fa-plus"></i> Add New</a>
                    </div>
                    <div class="box-body" id="ajaxReplace">
                        <table id="myTable" class="dataTable table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Coupon Code</th>                                
                                    <th>Coupon Mode</th> 
                                    <th>Coupon Offer</th>                               
                                    <th>Eligible Points</th> 
                                    <th>Status </th> 
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>  
                                <?php if(!empty($couponsList)) {
                                    foreach($couponsList as $key => $value) { ?>
                                        <tr>
                                            <td><?php echo $key+1 ;?></td>
                                            <td><?php echo $value['coupon_code'];?></td>
                                            <td><?php echo $value['coupon_type'];?></td>
                                            <td><?php echo $value['coupon_offer'];?></td>
                                            <td><?php echo $value['eligible_points'];?></td>
                                            <td id="status_<?php echo $value['id'];?>">
                                                <?php if($value['status'] == '0') { ?>
                                                    <button  href="javascript:;" onclick="changeStatus('<?= $value['id'] ?>', '1', 'status', 'coupons/ajaxaction', 'couponStatuschange')"><i class="fa fa-close"></i>
                                                   </button>
                                               <?php }else { ?>
                                                   <button  href="javascript:;" onclick="changeStatus('<?= $value['id'] ?>', '0', 'status', 'coupons/ajaxaction', 'couponStatuschange')"><i class="fa fa-check"></i>
                                                  </button>
                                               <?php } ?>
                                            </td>                                            
                                            <td>
                                                <a href="<?php echo REST_BASE_URL ?>coupons/edit/<?php echo $value['id'] ?>"><i class="fa fa-pencil"></i></a>
                                               <a id="<?php echo $value['id']; ?>" onclick="return deleteRecord(<?php echo $value['id']; ?>, 'coupons/deleteCoupon', 'Coupons', '', 'myTable')" href="javascript:;"><i class="fa fa-trash-o"></i></a>
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
    function changeStatus(id, changestaus, field, urlval, action)
    {
       $.ajax({
            type   : 'POST',
            url    : 'http://localhost/foodlove/restaurantadmin/coupons/ajaxaction',
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
                url    : 'http://localhost/foodlove/restaurantadmin/coupons/deleteCoupon',
                data   : {id:id, page:page, action:action},
                success: function(data){
                    $("#ajaxReplace").html(data);                    
                }
            });return false;
        }
    }
</script>