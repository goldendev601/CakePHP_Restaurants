<?php if($action == 'offerStatuschange') { ?>
    <?php if($status == 'active'){?>        
            <button href="javascript:;" onclick="changeStatus('<?= $id ?>', '0', '<?= $field ?>', 'offers/ajaxaction', 'offerStatuschange')"><i class="fa fa-check"></i>
            </button>        
    <?php }else { ?>       
            <button href="javascript:;" onclick="changeStatus('<?= $id ?>', '1', '<?= $field ?>', 'offers/ajaxaction', 'offerStatuschange')"><i class="fa fa-close"></i>
            </button>        
    <?php } ?>
<?php die(); } ?>


<?php if($action == 'Offers') { ?>
    <table id="offerTable" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>S.No</th>
                    <th>Restaurant Name</th>
                    <th>Offer From</th>                                
                    <th>Offer To</th>                               
                    <th>Status</th>
                    <th>Action</th>
            </tr>
        </thead>
        <tbody>  
            <?php if(!empty($offerList)) {
                foreach($offerList as $key => $value) { ?>
                    <tr>
                        <td><?php echo $key+1 ;?></td>  
                        <td><?php echo $value['restName'];?></td> 
                        <td><?php echo $value['offer_from'];?></td>
                        <td><?php echo $value['offer_to'];?></td>
                        <td id="status_<?php echo $value['id'];?>">
                            <?php if($value['status'] == '0') { ?>
                                <button  href="javascript:;" onclick="changeStatus('<?= $value['id'] ?>', '1', 'status', 'offers/ajaxaction', 'offerStatuschange')"><i class="fa fa-close"></i>
                               </button>
                           <?php }else { ?>
                               <button  href="javascript:;" onclick="changeStatus('<?= $value['id'] ?>', '0', 'status', 'offers/ajaxaction', 'offerStatuschange')"><i class="fa fa-check"></i>
                              </button>
                           <?php } ?>
                        </td>                                            
                        <td>
                            <a href="<?php echo ADMIN_BASE_URL ?>offers/edit/<?php echo $value['id'] ?>"><i class="fa fa-pencil"></i></a>
                           <a id="<?php echo $value['id']; ?>" onclick="return deleteRecord(<?php echo $value['id']; ?>, 'offers/deleteOffer', 'Offers', '', 'offerTable')" href="javascript:;"> <i class="fa fa-trash-o"></i></a>
                        </td>                                        
                    </tr>  
            <?php } 
            } ?>
        </tbody>
    </table>
<?php die(); } ?>