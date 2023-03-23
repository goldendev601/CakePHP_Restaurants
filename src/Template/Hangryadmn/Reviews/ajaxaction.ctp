<?php if($action == 'reviewStatusChange') { ?>
    <?php if($status == 'active'){?>        
            <button href="javascript:;" onclick="changeStatus('<?= $id ?>', '0', '<?= $field ?>', 'reviews/ajaxaction', 'reviewStatusChange')"><i class="fa fa-check"></i>
            </button>        
    <?php }else { ?>       
            <button href="javascript:;" onclick="changeStatus('<?= $id ?>', '1', '<?= $field ?>', 'reviews/ajaxaction', 'reviewStatusChange')"><i class="fa fa-close"></i>
            </button>        
    <?php } ?>
<?php die(); } ?>


<?php if($action == 'reviews') { ?>      
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
                           <a id="<?php echo $value['id']; ?>" onclick="return deleteRecord(<?php echo $value['id']; ?>, 'reviews/deleteReview', 'reviews', '', 'reviewTable')" href="javascript:;"><i class="fa fa-trash-o"></a>
                        </td>                                        
                    </tr>  
            <?php } 
            } ?>                           
        </tbody>
    </table>
<?php die(); } ?>