<div class="content-wrapper">
    <section class="content-header">
        <h1> Manage Bonuspoint  </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>            
        </ol>
    </section>

    <section class="content clearfix">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                       <a class="btn btn-primary pull-right" href="<?php echo REST_BASE_URL;?>bonuspoints/add"><i class="fa fa-plus"></i> Add New</a>
                    </div>
                    <div class="box-body" id="ajaxReplace">
                        <table class="dataTable table table-bordered table-hover">
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
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<script>
    function changeStatus(id, changestaus, field, urlval, action)
    {
        $.ajax({
            type   : 'POST',
            url    : jssitebaseurl+''+urlval,
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


    function deleteRecord(id, urlval, action, page, loadDiv)
    {
        var str = "Are you sure want to delete this "+action;
        if(confirm(str))
        {
            $.ajax({
                type   : 'POST',
                url    : jssitebaseurl+''+urlval,
                data   : {id:id, page:page, action:action},
                success: function(data){
                    $("#ajaxReplace").html(data);
                    $("."+loadDiv).DataTable({
                        columnDefs: [ { orderable: false, targets: [-1,-2] } ]
                    });
                }
            });return false;
        }
    }
</script>
