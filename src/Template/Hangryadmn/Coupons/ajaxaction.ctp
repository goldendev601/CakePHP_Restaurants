<?php if($action == 'couponStatuschange') { ?>
    <?php if($status == 'active'){?>        
            <button href="javascript:;" onclick="changeStatus('<?= $id ?>', '0', '<?= $field ?>', 'coupons/ajaxaction', 'couponStatuschange')"><i class="fa fa-check"></i>
            </button>        
    <?php }else { ?>       
            <button href="javascript:;" onclick="changeStatus('<?= $id ?>', '1', '<?= $field ?>', 'coupons/ajaxaction', 'couponStatuschange')"><i class="fa fa-close"></i>
            </button>        
    <?php } ?>
<?php die(); } ?>


<?php if($action == 'Coupons') { ?>      
    <table id="couponTable" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Restaurant Name</th>                                
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
                        <td><?php echo $value['restaurant']['restaurant_name'];?></td>
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
                            <a href="<?php echo ADMIN_BASE_URL ?>coupons/edit/<?php echo $value['id'] ?>"><i class="fa fa-pencil"></i></a>
                           <a id="<?php echo $value['id']; ?>" onclick="return deleteRecord(<?php echo $value['id']; ?>, 'coupons/deleteCoupon', 'Coupons', '', 'couponTable')" href="javascript:;"><i class="fa fa-trash-o"></i></a>
                        </td>                                        
                    </tr>  
            <?php } 
            } ?>                            
        </tbody>
    </table>
<?php die(); } ?>