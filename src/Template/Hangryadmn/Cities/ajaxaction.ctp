<?php if($action == 'getStateList') { ?>
    <?= $this->Form->input('state_id',[
        'type' => 'select',
        'id'   => 'state_id',
        'class' => 'form-control',
        'empty'  => 'Select State Name',
        'options'=> $statelist,        
        'label' => false
    ]) ?>
<?php die(); } ?>


<?php if($action == 'cityStatusChange') { ?>
    <?php if($status == 'active'){?>        
            <button href="javascript:;" onclick="changeStatus('<?= $id ?>', '0', '<?= $field ?>', 'cities/ajaxaction', 'cityStatusChange')"><i class="fa fa-check"></i>
            </button>        
    <?php }else { ?>       
            <button href="javascript:;" onclick="changeStatus('<?= $id ?>', '1', '<?= $field ?>', 'cities/ajaxaction', 'cityStatusChange')"><i class="fa fa-close"></i>
            </button>        
    <?php } ?>
<?php die(); } ?>


<?php if($action == 'City') { ?>      
      <table id="cityTable" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>S.No</th>
                <th>City Name</th>
                <th>State Name</th>                                     
                <th>Added Date</th>                               
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>  
            <?php if(!empty($citiesList)) {
                foreach($citiesList as $key => $value) { ?>
                    <tr>
                        <td><?php echo $key+1 ;?></td>
                        <td><a href=<?php echo ADMIN_BASE_URL ?>locations/index/<?php echo $value['id'] ?>><?php echo $value['city_name'] ;?></a></td>
                        <td><?php echo $value['state']['state_name'];?></td>
                        <td><?php echo date('Y-m-d h:i A', strtotime($value['created'])); ?>
                        </td>
                        <td id="status_<?php echo $value['id'];?>">
                            <?php if($value['status'] == '0') { ?>
                                <button  href="javascript:;" onclick="changeStatus('<?= $value['id'] ?>', '1', 'status', 'cities/ajaxaction', 'cityStatusChange')"><i class="fa fa-close"></i>
                               </button>
                           <?php }else { ?>
                               <button  href="javascript:;" onclick="changeStatus('<?= $value['id'] ?>', '0', 'status', 'cities/ajaxaction', 'cityStatusChange')"><i class="fa fa-check"></i>
                              </button>
                           <?php } ?>
                        </td>                                            
                        <td>
                            <a href="<?php echo ADMIN_BASE_URL ?>cities/edit/<?php echo $value['id'] ?>"><i class="fa fa-pencil"></i></a>
                           <a id="<?php echo $value['id']; ?>" onclick="return deleteRecord(<?php echo $value['id']; ?>, 'cities/deleteCity', 'City', '', 'cityTable')" href="javascript:;"> <i class="fa fa-trash-o"></i></a>
                        </td>                                        
                    </tr>  
            <?php } 
            } ?>                            
        </tbody>
    </table>
<?php die(); } ?>