<?php if($action == 'countryStatusChange') { ?>
    <?php if($status == 'active'){?>        
            <button href="javascript:;" onclick="changeStatus('<?= $id ?>', '0', '<?= $field ?>', 'countries/ajaxaction', 'countryStatusChange')"><i class="fa fa-check"></i>
            </button>        
    <?php }else { ?>       
            <button href="javascript:;" onclick="changeStatus('<?= $id ?>', '1', '<?= $field ?>', 'countries/ajaxaction', 'countryStatusChange')"><i class="fa fa-close"></i>
            </button>        
    <?php } ?>
<?php die(); } ?>


<?php if($action == 'Country') { ?>      
      <table id="countryTable" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Country Name</th>
                <th>Currency Name</th> 
                <th>Phone Code</th>                                 
                <th>Added Date</th>                               
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>  
            <?php if(!empty($countriesList)) {
                foreach($countriesList as $key => $value) { ?>
                    <tr>
                        <td><?php echo $key+1 ;?></td>
                        <td><a href=<?php echo ADMIN_BASE_URL ?>states/index/<?php echo $value['iso_code'] ?>><?php echo $value['country_name'] ;?></a></td>
                        <td><?php echo $value['currency_name'] ;?></td>
                        <td><?php echo $value['phone_code'] ;?></td>
                        <td><?php echo date('Y-m-d h:i A', strtotime($value['created'])); ?>
                        </td>
                        <td id="status_<?php echo $value['id'];?>">
                            <?php if($value['status'] == '0') { ?>
                                <button  href="javascript:;" onclick="changeStatus('<?= $value['id'] ?>', '1', 'status', 'countries/ajaxaction', 'countryStatusChange')"><i class="fa fa-close"></i>
                               </button>
                           <?php }else { ?>
                               <button  href="javascript:;" onclick="changeStatus('<?= $value['id'] ?>', '0', 'status', 'countries/ajaxaction', 'countryStatusChange')"><i class="fa fa-check"></i>
                              </button>
                           <?php } ?>
                        </td>                                            
                        <td>
                            <a href="<?php echo ADMIN_BASE_URL ?>countries/edit/<?php echo $value['id'] ?>"><i class="fa fa-pencil"></i></a>
                           <a id="<?php echo $value['id']; ?>" onclick="return deleteRecord(<?php echo $value['id']; ?>, 'countries/deleteCountry', 'Country', '', 'countryTable')" href="javascript:;"><i class="fa fa-trash-o"></i></a>
                        </td>                                        
                    </tr>  
            <?php } 
            } ?>                            
        </tbody>
    </table>
<?php die(); } ?>