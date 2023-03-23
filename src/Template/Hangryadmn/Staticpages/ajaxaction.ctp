<?php if($action == 'staticstatuschange') { ?>
    <?php if($status == 'active'){?>        
            <button href="javascript:;" onclick="changeStatus('<?= $id ?>', '0', '<?= $field ?>', 'Staticpages/ajaxaction', 'staticstatuschange')"><i class="fa fa-check"></i>
            </button>        
    <?php }else { ?>       
            <button href="javascript:;" onclick="changeStatus('<?= $id ?>', '1', '<?= $field ?>', 'Staticpages/ajaxaction', 'staticstatuschange')"><i class="fa fa-close"></i>
            </button>        
    <?php } ?>
<?php die(); } ?>


<?php if($action == 'Staticpage') { ?>
    <table id="staticpagesTable" class="table table-bordered table-hover">
        <thead>
        <tr>
            <th class="no-sort">S.No</th>
            <th>Title</th>
            <th>Seo Url</th>
            <th>Sort Order</th>
            <th>Added Date</th>
            <th class="no-sort">Status</th>
            <th class="no-sort">Action</th>
        </tr>
        </thead>
        <tbody id="staticpage_list">
        <?php if(!empty($staticList)) {
            foreach($staticList as $key => $value) { ?>
                <tr id="record<?php echo $value['id'];?>">
                    <td><?php echo $key+1 ;?></td>
                    <td><?php echo $value['title'] ;?></td>
                    <td><?php echo $value['seo_url'] ;?></td>
                    <td id="sort_order_<?php echo $value['id'] ;?>"><?php echo $value['sortorder'] ;?></td>
                    <td><?php echo date('Y-m-d h:i A', strtotime($value['created'])); ?>
                    </td>
                    <td id="status_<?php echo $value['id'];?>">
                        <?php if($value['status'] == '0') { ?>
                            <button  href="javascript:;" onclick="changeStatus('<?= $value['id'] ?>', '1', 'status', 'Staticpages/ajaxaction', 'staticstatuschange')"><i class="fa fa-close"></i>
                            </button>
                        <?php }else { ?>
                            <button  href="javascript:;" onclick="changeStatus('<?= $value['id'] ?>', '0', 'status', 'Staticpages/ajaxaction', 'staticstatuschange')"><i class="fa fa-check"></i>
                            </button>
                        <?php } ?>
                    </td>
                    <td>
                        <a href="<?php echo ADMIN_BASE_URL ?>Staticpages/addEdit/<?php echo $value['id'] ?>"><i class="fa fa-pencil"></i></a>
                        <a id="<?php echo $value['id']; ?>" onclick="return deleteRecord(<?php echo $value['id']; ?>, 'Staticpages/deletestatic', 'Staticpage', '', 'pagesTable')" href="javascript:;"><i class="fa fa-trash-o"></i></a>
                    </td>
                </tr>
            <?php }
        } ?>
        </tbody>
    </table>
<?php die(); } ?>


