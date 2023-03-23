<?php if($action == 'bonusStatus' && $field == 'status') { ?>
    <?php if($status == 'active'){?>
        <button class="btn btn-icon-toggle active" type="button" onclick="changeStatus('<?= $id ?>', '0', '<?= $field ?>', 'bonuspoints/ajaxaction', 'bonusStatus')">
            <i class="fa fa-check"></i>
        </button>
    <?php }else {?>
        <button class="btn btn-icon-toggle active" type="button" onclick="changeStatus('<?= $id ?>', '1', '<?= $field ?>', 'bonuspoints/ajaxaction', 'bonusStatus')">
            <i class="fa fa-close"></i>
        </button>
    <?php }?>
<?php exit();} ?>


<?php if($action == 'Bonuspoint') { ?>

    <table  class="dataTable table table-bordered table-hover">
        <thead>
        <tr>
            <th>S.No</th>
            <th>No of Orders</th>
            <th>No of Points</th>
            <th>Added Date</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php if(!empty($bonusList)) {
            foreach($bonusList as $key => $value) { ?>
                <tr>
                    <td><?= $key + 1; ?></td>
                    <td><?= $value['no_oforder'] ;?></td>
                    <td><?= $value['no_ofpoint']; ?></td>
                    <td><?= date('Y-m-d', strtotime($value['created'])) ;?></td>
                    <td id="status_<?php echo $value['id']; ?>">
                        <?php if ($value['status'] == '0') { ?>
                            <button class="btn btn-icon-toggle deactive" href="javascript:;"
                                    onclick="changeStatus('<?= $value['id'] ?>', '1', 'status', 'bonuspoints/ajaxaction', 'bonusStatus')">
                                <i class="fa fa-close"></i>
                            </button>
                        <?php } else { ?>
                            <button class="btn btn-icon-toggle active" href="javascript:;"
                                    onclick="changeStatus('<?= $value['id'] ?>', '0', 'status', 'bonuspoints/ajaxaction', 'bonusStatus')">
                                <i class="fa fa-check"></i>
                            </button>
                        <?php } ?>
                    </td>
                    <td>
                        <a id="<?php echo $value['id']; ?>"
                           onclick="return deleteRecord(<?php echo $value['id']; ?>, 'bonuspoints/deleteBonus', 'Bonuspoint', '', 'dataTable')"
                           href="javascript:;">
                            <i class="fa fa-trash-o"></i>
                        </a>
                    </td>
                </tr>
            <?php }
        } ?>
        </tbody>
    </table>

<?php die();} ?>
