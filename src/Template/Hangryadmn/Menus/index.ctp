<div class="content-wrapper">
    <section class="content-header">
        <h1> Manage Menu </h1>

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
                        <a class="btn btn-primary pull-right" href="<?php echo ADMIN_BASE_URL; ?>menus/add/">
                            <i class="fa fa-plus"></i> Add New
                        </a>
                    </div>
                    <div class="box-body" id="ajaxReplace">
                        <table id="menuTable" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Restaurant Name</th>
                                <th>Menu Name</th>
                                <th>Category</th>
                                <th>Added Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(!empty($menuDetails)) {
                                foreach($menuDetails as $key => $value) { ?>
                                    <tr>
                                        <td><?= $key + 1 ?></td>
                                        <td><?= $value['restaurant']['restaurant_name'] ?></td>
                                        <td><?= $value['menu_name'] ?></td>
                                        <td><?= $value['category']['category_name'] ?></td>
                                        <td><?= date('Y-m-d', strtotime($value['created'])) ?></td>
                                        <td id="status_<?php echo $value['id']; ?>">
                                            <?php if ($value['status'] == '0') { ?>
                                                <button class="btn btn-icon-toggle deactive" href="javascript:;"
                                                        onclick="changeStatus('<?= $value['id'] ?>', '1', 'status', 'menus/ajaxaction', 'menuStatus')">
                                                    <i class="fa fa-close"></i>
                                                </button>
                                            <?php } else { ?>
                                                <button class="btn btn-icon-toggle active" href="javascript:;"
                                                        onclick="changeStatus('<?= $value['id'] ?>', '0', 'status', 'menus/ajaxaction', 'menuStatus')">
                                                    <i class="fa fa-check"></i>
                                                </button>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <a href="<?php echo ADMIN_BASE_URL ?>menus/edit/<?php echo $value['id'] ?>">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                            <a id="<?php echo $value['id']; ?>"
                                               onclick="return deleteRecord(<?php echo $value['id']; ?>, 'menus/deletemenu', 'Menu', '', 'menuTable')"
                                               href="javascript:;">
                                                <i class="fa fa-trash-o"></i>
                                            </a>
                                        </td>
                                    </tr>

                                    <?php
                                }
                            }
                            ?>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).ready(function(){
        $('#menuTable').DataTable({
            columnDefs: [ { orderable: false, targets: [-1,-2] } ]
        });
    });

    function changeStatus(id, changestaus, field, urlval, action)
    {
        $.ajax({
            type   : 'POST',
            url    : jssitebaseurl+''+urlval,
            data   : {id:id, field:field ,changestaus:changestaus,action:action},
            success: function(data){
                //clearConsole();
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
                    $("#"+loadDiv).DataTable({
                        columnDefs: [ { orderable: false, targets: [-1,-2,-4] } ]
                    });
                }
            });return false;
        }
    }
</script>