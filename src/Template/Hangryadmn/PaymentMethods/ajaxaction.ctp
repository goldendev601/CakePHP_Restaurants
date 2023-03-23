<?php if($action == 'paymentstatuschange') { ?>
    <?php if($status == 'active'){?>        
            <button href="javascript:;" onclick="changeStatus('<?= $id ?>', '0', '<?= $field ?>', 'PaymentMethods/ajaxaction', 'paymentstatuschange')"><i class="fa fa-check"></i>
            </button>        
    <?php }else { ?>       
            <button href="javascript:;" onclick="changeStatus('<?= $id ?>', '1', '<?= $field ?>', 'PaymentMethods/ajaxaction', 'paymentstatuschange')"><i class="fa fa-close"></i>
            </button>        
    <?php } ?>
<?php die(); } ?>


<?php if($action == 'PaymentMethod') { ?>
    <table id="paymentTable" class="table table-bordered table-hover">
        <thead>
        <tr>
            <th>S.No</th>
            <th>Payment Method Name</th>
            <th>Payment Method Image</th>
            <th>Added Date</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php if(!empty($paymentList)) {
            foreach($paymentList as $key => $value) { ?>
                <tr>
                    <td><?php echo $key+1 ;?></td>
                    <td><?php echo $value['payment_method_name'] ;?></td>
                    <td>
                        <img src = "<?php echo BASE_URL.'webroot/images/payment/'.$value['payment_method_image'] ;?>" alt = "Test Image" width="50" height="50"/></td>
                    <td><?php echo date('Y-m-d h:i A', strtotime($value['created'])); ?>
                    </td>
                    <td id="status_<?php echo $value['id'];?>">
                        <?php if($value['status'] == '0') { ?>
                            <button  href="javascript:;" onclick="changeStatus('<?= $value['id'] ?>', '1', 'status', 'PaymentMethods/ajaxaction', 'paymentstatuschange')"><i class="fa fa-close"></i>
                            </button>
                        <?php }else { ?>
                            <button  href="javascript:;" onclick="changeStatus('<?= $value['id'] ?>', '0', 'status', 'PaymentMethods/ajaxaction', 'paymentstatuschange')"><i class="fa fa-check"></i>
                            </button>
                        <?php } ?>
                    </td>
                    <td>
                        <a href="<?php echo ADMIN_BASE_URL ?>PaymentMethods/edit/<?php echo $value['id'] ?>"><i class="fa fa-pencil"></i></a>
                        <a id="<?php echo $value['id']; ?>" onclick="return deleteRecord(<?php echo $value['id']; ?>, 'PaymentMethods/deletepayment', 'PaymentMethod', '', 'paymentTable')" href="javascript:;"><i class="fa fa-trash-o"></i></a>
                    </td>
                </tr>
            <?php }
        } ?>
        </tbody>
    </table>
<?php die(); } ?>