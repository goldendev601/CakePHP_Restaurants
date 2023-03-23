<?php if($action == 'getCityList') { ?>
    <?= $this->Form->input('city_id',[
        'type' => 'select',
        'id'   => 'city_id',
        'class' => 'form-control',
        'empty'  => 'Select City Name',
        'options'=> $citylist,        
        'label' => false
    ]) ?>
<?php die(); } ?>

<?php if($action == 'locationStatusChange') { ?>
    <?php if($status == 'active'){?>        
            <button href="javascript:;" onclick="changeStatus('<?= $id ?>', '0', '<?= $field ?>', 'locations/ajaxaction', 'locationStatusChange')"><i class="fa fa-check"></i>
            </button>        
    <?php }else { ?>       
            <button href="javascript:;" onclick="changeStatus('<?= $id ?>', '1', '<?= $field ?>', 'locations/ajaxaction', 'locationStatusChange')"><i class="fa fa-close"></i>
            </button>        
    <?php } ?>
<?php die(); } ?>


<?php if($action == 'Location') { ?>      
      <table id="locationTable" class="table table-bordered table-hover">
        <thead>
            <tr>
                 <th>S.No</th>
                 <th>Area Name</th>  
                 <th>Zipcode</th>   
                 <th>City Name</th>
                 <th>State Name</th>                             
                 <th>Added Date</th>                               
                 <th>Status</th>
                 <th>Action</th>
            </tr>
        </thead>
        <tbody>  
            <?php if(!empty($locationList)) {
                foreach($locationList as $key => $value) { ?>
                    <tr>
                        <td><?php echo $key+1 ;?></td>    
                        <td><?php echo $value['area_name'] ;?></td>
                        <td><?php echo $value['zip_code'] ;?></td>
                        <td><?php echo $value['city']['city_name'] ;?></td>
                        <td><?php echo $value['state']['state_name'] ;?></td>
                        <td><?php echo date('Y-m-d h:i A', strtotime($value['created'])); ?>
                        </td>
                        <td id="status_<?php echo $value['id'];?>">
                            <?php if($value['status'] == '0') { ?>
                                <button  href="javascript:;" onclick="changeStatus('<?= $value['id'] ?>', '1', 'status', 'locations/ajaxaction', 'locationStatusChange')"><i class="fa fa-close"></i>
                               </button>
                           <?php }else { ?>
                               <button  href="javascript:;" onclick="changeStatus('<?= $value['id'] ?>', '0', 'status', 'locations/ajaxaction', 'locationStatusChange')"><i class="fa fa-check"></i>
                              </button>
                           <?php } ?>
                        </td>                                            
                        <td>
                            <a href="<?php echo ADMIN_BASE_URL ?>locations/edit/<?php echo $value['id'] ?>">Edit</a>
                           <a id="<?php echo $value['id']; ?>" onclick="return deleteRecord(<?php echo $value['id']; ?>, 'locations/deleteLocation', 'Location', '', 'locationTable')" href="javascript:;"> Delete</a>
                        </td>                                        
                    </tr>  
            <?php } 
            } ?>                            
        </tbody>
    </table>
<?php die(); } ?>