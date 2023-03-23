<?php if($action == 'reviews') { ?>
    <?php if($status == 'active'){?>        
            <button href="javascript:;" onclick="changeStatus('<?= $id ?>', '0', '<?= $field ?>', 'reviews/ajaxaction', 'reviewStatusChange')"><i class="fa fa-check"></i>
            </button>        
    <?php }else { ?>       
            <button href="javascript:;" onclick="changeStatus('<?= $id ?>', '1', '<?= $field ?>', 'reviews/ajaxaction', 'reviewStatusChange')"><i class="fa fa-close"></i>
            </button>        
    <?php } ?>
<?php die(); } ?>


<?php if($action == 'reviews') { ?>      
      <table id="myTable" class="dataTable table table-bordered table-hover">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Order Id</th> 
                <th>Customer Name</th>
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
                    </tr>  
            <?php } 
            } ?>                           
        </tbody>
    </table>
<?php die(); } ?>