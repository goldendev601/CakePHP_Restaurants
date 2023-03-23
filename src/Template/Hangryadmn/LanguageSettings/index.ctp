<div class="content-wrapper">
    <section class="content-header">
        <h1>  Manage Language Settings </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>            
        </ol>
    </section>

    <section class="content clearfix">
        <div class="row">
            <div class="col-xs-12">
                <div class="box my-box">
                    <div class="box-header">
                       <a class="btn btn-primary pull-right" href="<?php echo ADMIN_BASE_URL;?>LanguageSettings/addEdit"><i class="fa fa-plus"></i> Add New</a>
                    </div>
                    <div class="box-body" id="ajaxReplace">
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
                                                <a href="<?php echo ADMIN_BASE_URL ?>LanguageSettings/editFile/<?php echo $value['language_code'] ?>" class="btn btn-sm btn-primary">EditFile</a>
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
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">

    $(document).ready(function(){
        $('#languageTable').DataTable({
            columnDefs: [ { orderable: false, targets: [-1,-2,-3,-4] } ]
        });
    });

    function changeStatus(id, changestaus, field, urlval, action)
    {
       $.ajax({
            type   : 'POST',
            url    : jssitebaseurl+'languageSettings/ajaxaction',
            data   : {id:id, field:field ,changestaus:changestaus,action:action},
            success: function(data){
                if(action == '') {
                    window.location.reload();
                }else {                    
                    $("#"+field+"_"+id).html(data);
                    return false;
                }
            }
        });
        return false;
    }

    function statusChange(id, action)
    {
       $.ajax({
            type   : 'POST',
            url    : jssitebaseurl+'languageSettings/ajaxaction',
            data   : {id:id, action:action},
            success: function(data){
                window.location.reload();
            }
        });
        return false;
    }

    function deleteRecord(id, urlval, action, page, loadDiv)
    {
        var str = "Are you sure want to delete this "+action;
        if(confirm(str))
        {
            $.ajax({
                type   : 'POST',
                url    : jssitebaseurl+'languageSettings/deletelanguage',
                data   : {id:id, page:page, action:action},
                success: function(data){
                    //alert(data); return false; 
                    $("#ajaxReplace").html(data);
                    $("#"+loadDiv).DataTable({
                        columnDefs: [ { orderable: false, targets: [-1,-2,-3,-4] } ]
                    });
                }
            });return false;
        }
    }
</script>