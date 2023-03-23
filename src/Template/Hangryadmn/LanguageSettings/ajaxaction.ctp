<?php if($action == 'languagestatuschange') { ?>
    <?php if($status == 'active'){?>        
            <button href="javascript:;" onclick="changeStatus('<?= $id ?>', '0', '<?= $field ?>', 'LanguageSettings/ajaxaction', 'languagestatuschange')"><i class="fa fa-check"></i>
            </button>        
    <?php }else { ?>       
            <button href="javascript:;" onclick="changeStatus('<?= $id ?>', '1', '<?= $field ?>', 'LanguageSettings/ajaxaction', 'languagestatuschange')"><i class="fa fa-close"></i>
            </button>        
    <?php } ?>
<?php die(); } ?>


<?php if($action == 'languagedefaultchange') { ?>
    <?php if($language_default == 'enable'){?>
        <div class="label label-table">
            <a class="statusColor" href="javascript:;" onclick="changeStatus('<?= $id ?>', '0', '<?= $field ?>', 'LanguageSettings/ajaxaction', 'languagedefaultchange')"><img src="<?= BASE_URL . 'webroot/images/star_yellow.png'?>" alt="Enable">
            </a>
        </div>
    <?php }else { ?>
        <div class="label label-table">
            <a class="statusColor" href="javascript:;" onclick="changeStatus('<?= $id ?>', '1', '<?= $field ?>', 'LanguageSettings/ajaxaction', 'languagedefaultchange')"><img src="<?= BASE_URL . 'webroot/images/star_grey.png'?>" alt="Disable">
            </a>
        </div>
    <?php } ?>
<?php die(); } ?>

<?php if($action == 'Languagepage') { ?>      
    <table id="languageTable" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Language Name</th>
                <th>Language Code</th>
                <th>Added Date</th>
                <th>Default Language</th>
                <th>Status</th>
                <th>Edit File</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>  
            <?php if(!empty($languageList)) {
                foreach($languageList as $key => $value) { ?>
                    <tr>
                        <td><?php echo $key+1 ;?></td>    
                        <td><?php echo $value['language_name'] ;?></td>
                        <td><?php echo $value['language_code'] ;?></td>
                        <td><?php echo date('Y-m-d h:i A', strtotime($value['created'])); ?>
                        </td>
                        <td align="center" id="language_default_<?php echo $value['id'];?>">
                            <?php if($value['language_default'] == '0') { ?>
                                <div class="label label-table">
                                    <a class="statusColor" href="javascript:;" onclick="statusChange('<?= $value['id'] ?>', 'languagedefaultchange')"> <img src="<?= BASE_URL . 'webroot/images/star_grey.png'?>" alt="Disable">
                                    </a>
                                </div>
                            <?php }else { ?>
                                <div class="label label-table">
                                    <a class="statusColor" href="javascript:;" onclick="statusChange('<?= $value['id'] ?>', 'languagedefaultchange',)"> <img src="<?= BASE_URL . 'webroot/images/star_yellow.png'?>" alt="Enable">
                                    </a>
                                </div>
                            <?php } ?>
                        </td>
                        <td id="status_<?php echo $value['id'];?>">
                            <?php if($value['status'] == '0') { ?>
                                <button  href="javascript:;" onclick="changeStatus('<?= $value['id'] ?>', '1', 'status', 'LanguageSettings/ajaxaction', 'languagestatuschange')"><i class="fa fa-close"></i>
                               </button>
                           <?php }else { ?>
                               <button  href="javascript:;" onclick="changeStatus('<?= $value['id'] ?>', '0', 'status', 'LanguageSettings/ajaxaction', 'languagestatuschange')"><i class="fa fa-check"></i>
                              </button>
                           <?php } ?>
                        </td> 
                        <td>
                            <a href="<?php echo ADMIN_BASE_URL ?>LanguageSettings/editFile/<?php echo $value['language_code'] ?>">EditFile</a>
                        </td>                                           
                        <td>
                            <a href="<?php echo ADMIN_BASE_URL ?>LanguageSettings/addEdit/<?php echo $value['id'] ?>"><i class="fa fa-pencil"></i></a>
                           <a id="<?php echo $value['id']; ?>" onclick="return deleteRecord(<?php echo $value['id']; ?>, 'LanguageSettings/deletelanguage', 'Languagepage', '', 'languageTable')" href="javascript:;"><i class="fa fa-trash-o"></i></a>
                        </td>                                        
                    </tr>  
            <?php } 
            } ?>                            
        </tbody>
    </table>
<?php die(); } ?>