<?php if($action == 'customerStatusChange') { ?>
    <?php if($status == 'active'){?>        
            <button href="javascript:;" onclick="changeStatus('<?= $id ?>', '0', '<?= $field ?>', 'customers/ajaxaction', 'customerStatusChange')"><i class="fa fa-check"></i>
            </button>        
    <?php }else { ?>       
            <button href="javascript:;" onclick="changeStatus('<?= $id ?>', '1', '<?= $field ?>', 'customers/ajaxaction', 'customerStatusChange')"><i class="fa fa-close"></i>
            </button>        
    <?php } ?>
<?php die(); } ?>

<?php if($action == 'customerAddressStatusChange') { ?>
    <?php if($status == 'active'){?>
        <button href="javascript:;" onclick="changeStatus('<?= $id ?>', '0', '<?= $field ?>', 'customers/ajaxaction', 'customerAddressStatusChange')"><i class="fa fa-check"></i>
        </button>
    <?php }else { ?>
        <button href="javascript:;" onclick="changeStatus('<?= $id ?>', '1', '<?= $field ?>', 'customers/ajaxaction', 'customerAddressStatusChange')"><i class="fa fa-close"></i>
        </button>
    <?php } ?>
    <?php die(); } ?>


<?php if($action == 'Customer') { ?>      
      <table id="customerTable" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Customer Name</th>  
                <th>Email ID</th>  
                <th>Phone Number</th>  
                <th>Wallet</th>        
                <th>Status</th>
                <th>Options</th> 
                <th>Wallet</th> 
                <th>Action</th>
            </tr>
        </thead>
        <tbody>  
            <?php if(!empty($customerList)) {
                foreach($customerList as $key => $value) { ?>
                    <tr>
                        <td><?php echo $key+1 ;?></td>    
                        <td><?php echo $value['first_name'] ;?></td>
                        <td><?php echo $value['username'] ;?></td>
                        <td><?php echo $value['phone_number'] ;?></td>
                        <td><?php echo $value['wallet_amount'] ;?></td>
                        <td id="status_<?php echo $value['id'];?>">
                            <?php if($value['status'] == '0') { ?>
                                <button  href="javascript:;" onclick="changeStatus('<?= $value['id'] ?>', '1', 'status', 'customers/ajaxaction', 'customerStatusChange')"><i class="fa fa-close"></i>
                               </button>
                           <?php }else { ?>
                               <button  href="javascript:;" onclick="changeStatus('<?= $value['id'] ?>', '0', 'status', 'customers/ajaxaction', 'customerStatusChange')"><i class="fa fa-check"></i>
                              </button>
                           <?php } ?>
                        </td>   
                        <td><a href="<?php echo ADMIN_BASE_URL ?>customers/customerIndex/<?php echo $value['id'] ?>">AddressBook</a></td>
                        <td><a href="#">AddMoney</a></td>
                        <td>
                            <a href="<?php echo ADMIN_BASE_URL ?>customers/edit/<?php echo $value['id'] ?>"><i class="fa fa-pencil"></i></a>
                           <a id="<?php echo $value['id']; ?>" onclick="return deleteRecord(<?php echo $value['id']; ?>, 'customers/deleteCustomer', 'Customer', '', 'customerTable')" href="javascript:;"><i class="fa fa-trash-o"></i></a>
                        </td>                                        
                    </tr>  
            <?php } 
            } ?>                           
        </tbody>
    </table>
<?php die(); } ?>

<?php if($action == 'CustomerAddress') { ?>
    <table id="myTable" class="dataTable table table-bordered table-hover">
        <thead>
        <tr>
            <th>S.No</th>
            <th>Title</th>
            <th>Flat No</th>
            <th>Address</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php if(!empty($addressbookList)) {
            foreach($addressbookList as $key => $value) { ?>
                <tr id="custAddDel_<?php echo $value['id'] ;?>">
                    <td><?php echo $key+1 ;?></td>
                    <td><?php echo $value['title'] ;?></td>
                    <td><?php echo $value['flat_no'] ;?></td>
                    <td><?php echo $value['address'] ;?></td>
                    <td id="status_<?php echo $value['id'];?>">
                        <?php if($value['status'] == '0') { ?>
                            <button  href="javascript:;" onclick="changeStatus('<?= $value['id'] ?>', '1', 'status', 'customers/ajaxaction', 'customerAddressStatusChange')"><i class="fa fa-close"></i>
                            </button>
                        <?php }else { ?>
                            <button  href="javascript:;" onclick="changeStatus('<?= $value['id'] ?>', '0', 'status', 'customers/ajaxaction', 'customerAddressStatusChange')"><i class="fa fa-check"></i>
                            </button>
                        <?php } ?>
                    </td>
                    <td>
                        <a href="<?php echo ADMIN_BASE_URL ?>customers/customerAddressbook/<?php echo $value['id'] ?>"><i class="fa fa-pencil"></i></a>
                        <a id="<?php echo $value['id']; ?>" onclick="return deleteRecord(<?php echo $value['id']; ?>, 'customers/deleteCustomer', 'CustomerAddress', '', 'dataTable')" href="javascript:;"><i class="fa fa-trash-o"></i></a>
                    </td>
                </tr>
            <?php }
        } ?>
        </tbody>
    </table>
    <?php die(); } ?>
