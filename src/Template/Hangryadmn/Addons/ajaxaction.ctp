<?php if($action == 'addonStatusChange') { ?>
    <?php if($status == 'active'){?>        
            <button href="javascript:;" onclick="changeStatus('<?= $id ?>', '0', '<?= $field ?>', 'addons/ajaxaction', 'addonStatusChange')"><i class="fa fa-check"></i>
            </button>        
    <?php }else { ?>       
            <button href="javascript:;" onclick="changeStatus('<?= $id ?>', '1', '<?= $field ?>', 'addons/ajaxaction', 'addonStatusChange')"><i class="fa fa-close"></i>
            </button>        
    <?php } ?>
<?php die(); } ?>


<?php if($action == 'Addon') { ?>
      <table id="addonTable" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Addon Name</th>
                <th>Category Name</th>
                <th>Added Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php if(!empty($allAddons)) {
            foreach($allAddons as $key => $value) { ?>
                <tr>
                    <td><?php echo $key+1 ;?></td>
                    <td><?php echo $value['mainaddons_name'] ;?></td>
                    <td><?php echo $value['category']['category_name'];?></td>
                    <td><?php echo date('Y-m-d h:i A', strtotime($value['created'])); ?>
                    </td>
                    <td id="status_<?php echo $value['id'];?>">
                        <?php if($value['status'] == '0') { ?>
                            <button  href="javascript:;" onclick="changeStatus('<?= $value['id'] ?>', '1', 'status', 'addons/ajaxaction', 'addonStatusChange')"><i class="fa fa-close"></i>
                            </button>
                        <?php }else { ?>
                            <button  href="javascript:;" onclick="changeStatus('<?= $value['id'] ?>', '0', 'status', 'addons/ajaxaction', 'addonStatusChange')"><i class="fa fa-check"></i>
                            </button>
                        <?php } ?>
                    </td>
                    <td>
                        <a href="<?php echo ADMIN_BASE_URL ?>addons/edit/<?php echo $value['id'] ?>"><i class="fa fa-pencil"></i></a>
                        <a id="<?php echo $value['id']; ?>" onclick="return deleteRecord(<?php echo $value['id']; ?>, 'addons/deleteAddon', 'Addon', '', 'addonTable')" href="javascript:;"><i class="fa fa-trash-o"></i></a>
                    </td>
                </tr>
            <?php }
        } ?>
        </tbody>
    </table>
<?php die(); } ?>