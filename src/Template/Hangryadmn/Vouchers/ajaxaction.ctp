<?php if($action == 'voucherStatuschange') { ?>
    <?php if($status == 'active'){?>        
            <button href="javascript:;" onclick="changeStatus('<?= $id ?>', '0', '<?= $field ?>', 'vouchers/ajaxaction', 'voucherStatuschange')"><i class="fa fa-check"></i>
            </button>        
    <?php }else { ?>       
            <button href="javascript:;" onclick="changeStatus('<?= $id ?>', '1', '<?= $field ?>', 'vouchers/ajaxaction', 'voucherStatuschange')"><i class="fa fa-close"></i>
            </button>        
    <?php } ?>
<?php die(); } ?>


<?php if($action == 'Vouchers') { ?>      
      <table id="voucherTable" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Voucher Code</th>                                
                <th>Type </th>                               
                <th>Mode </th> 
                <th>Offer </th> 
                <th>Valid From </th>                               
                <th>Valid To </th> 
                <th>Status </th> 
                <th>Action</th>
            </tr>
        </thead>
        <tbody>  
            <?php if(!empty($vouchersList)) {
                foreach($vouchersList as $key => $value) { ?>
                    <tr>
                        <td><?php echo $key+1 ;?></td>    
                        <td><?php echo $value['voucher_code'];?></td>
                        <td><?php echo $value['type_offer'];?></td>
                                                
                        <td><?php if($value['offer_mode'] == 'free_delivery'){
                               echo 'free delivery';
                          }else{
                               echo $value['offer_mode'];
                          }?></td>                       
                        
                        <td><?php if($value['offer_mode'] == 'free_delivery'){
                               echo $value['free_delivery'];
                            }else{
                               echo $value['offer_value'];
                            } ?></td>                        
                        <td><?php echo $value['voucher_from'];?></td>
                        <td><?php echo $value['voucher_to'];?>
                        </td>
                        <td id="status_<?php echo $value['id'];?>">
                            <?php if($value['status'] == '0') { ?>
                                <button  href="javascript:;" onclick="changeStatus('<?= $value['id'] ?>', '1', 'status', 'vouchers/ajaxaction', 'voucherStatuschange')"><i class="fa fa-close"></i>
                               </button>
                           <?php }else { ?>
                               <button  href="javascript:;" onclick="changeStatus('<?= $value['id'] ?>', '0', 'status', 'vouchers/ajaxaction', 'voucherStatuschange')"><i class="fa fa-check"></i>
                              </button>
                           <?php } ?>
                        </td>                                            
                        <td>
                            <a href="<?php echo ADMIN_BASE_URL ?>vouchers/addedit/<?php echo $value['id'] ?>"><i class="fa fa-pencil"></i></a>
                           <a id="<?php echo $value['id']; ?>" onclick="return deleteRecord(<?php echo $value['id']; ?>, 'vouchers/deleteVoucher', 'Vouchers', '', 'voucherTable')" href="javascript:;"><i class="fa fa-trash-o"></i></a>
                        </td>                                        
                    </tr>  
            <?php } 
            } ?>                            
        </tbody>
    </table>
<?php die(); } ?>