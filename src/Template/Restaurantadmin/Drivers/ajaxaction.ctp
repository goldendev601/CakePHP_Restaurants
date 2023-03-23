<?php if($action == 'driverStatusChange') { ?>
    <?php if($status == 'active'){?>        
            <button href="javascript:;" onclick="changeStatus('<?= $id ?>', '0', '<?= $field ?>', 'drivers/ajaxaction', 'driverStatusChange')"><i class="fa fa-check"></i>
            </button>        
    <?php }else { ?>       
            <button href="javascript:;" onclick="changeStatus('<?= $id ?>', '1', '<?= $field ?>', 'drivers/ajaxaction', 'driverStatusChange')"><i class="fa fa-close"></i>
            </button>        
    <?php } ?>
<?php die(); } ?>


<?php if($action == 'driverLoginStatus') { ?>
    <?php if($is_logged == 'active'){?>
        <button href="javascript:;" onclick="changeStatus('<?= $id ?>', '0', '<?= $field ?>', 'drivers/ajaxaction', 'driverLoginStatus')"><i class="fa fa-truck" aria-hidden="true"></i>
        </button>
    <?php } else { ?>
        <button href="javascript:;" onclick="changeStatus('<?= $id ?>', '1', '<?= $field ?>', 'drivers/ajaxaction', 'driverLoginStatus')"><i class="fa fa-lock" aria-hidden="true"></i>
        </button>
    <?php } ?>
<?php die(); } ?>


<?php if($action == 'Driver') { ?>      
     <table class="dataTable table table-bordered table-hover">
        <thead>
            <tr>
            <th>S.No</th>
            <th>Driver Name</th>  
            <th>Phone Number</th> 
            <th>Email ID</th>  
            <th>Payout</th>        
            <th>Status</th>               
            <th>Action</th>
        </tr>
    </thead>
        <tbody>  
            <?php if(!empty($driverList)) {
                foreach($driverList as $key => $value) { ?>
                    <tr>
                        <td><?php echo $key+1 ;?></td>    
                        <td><?php echo $value['driver_name'] ;?></td>
                         <td><?php echo $value['phone_number'] ;?></td>
                        <td><?php echo $value['username'] ;?></td>
                        <td><?php echo $value['payout'] ;?></td>
                        <td id="status_<?php echo $value['id'];?>">
                            <?php if($value['status'] == '0') { ?>
                                <button  href="javascript:;" onclick="changeStatus('<?= $value['id'] ?>', '1', 'status', 'drivers/ajaxaction', 'driverStatusChange')"><i class="fa fa-close"></i>
                               </button>
                           <?php }else { ?>
                               <button  href="javascript:;" onclick="changeStatus('<?= $value['id'] ?>', '0', 'status', 'drivers/ajaxaction', 'driverStatusChange')"><i class="fa fa-check"></i>
                              </button>
                           <?php } ?>
                        </td>  
                        <td>
                            <a href="<?php echo REST_BASE_URL ?>drivers/edit/<?php echo $value['id'] ?>"><i class="fa fa-pencil"></i></a>
                           <a id="<?php echo $value['id']; ?>" onclick="return deleteRecord(<?php echo $value['id']; ?>, 'drivers/deleteDriver', 'Driver', '', 'dataTable')" href="javascript:;"><i class="fa fa-trash-o"></i></a>
                        </td>                                        
                    </tr>  
            <?php } 
            } ?>                            
        </tbody>
    </table>
<?php die(); } ?>