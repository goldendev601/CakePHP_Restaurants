<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Restaurant
        </h1>
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
                        <a class="btn btn-primary pull-right" href="<?php echo ADMIN_BASE_URL; ?>restaurants/add/">
                            <i class="fa fa-plus"></i> Add New
                        </a>
                    </div>
                    <div class="box-body" id="ajaxReplace">
                        <table id="restaurantTable" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Restaurant Name</th>
                                <th>Phone Number</th>
                                <th>Address</th>
                                <th>Added Date</th>
                                <th>Options</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(!empty($restaurantLists)) {
                                foreach($restaurantLists as $key => $value) { ?>
                                    <tr>
                                        <td><?= $key+1 ?></td>
                                        <td><?= $value['restaurant_name'] ?></td>
                                        <td><?= $value['restaurant_phone'] ?></td>
                                        <td><?= $value['contact_address'] ?></td>
                                        <td><?= date('Y-m-d',strtotime($value['created'])) ?></td>
                                        <td><a href="javascript:void(0);"  onclick="return pluginDetail('<?php echo $value['seo_url'] ; ?>');">Script</i></a></td>
                                        <td id="status_<?php echo $value['id'];?>">
                                            <?php if($value['status'] == '0') { ?>
                                                <button class="btn btn-icon-toggle deactive"  href="javascript:;" onclick="changeStatus('<?= $value['id'] ?>', '1', 'status', 'restaurants/ajaxaction', 'restaurantStatus')"><i class="fa fa-close"></i>
                                                </button>
                                            <?php }else { ?>
                                                <button class="btn btn-icon-toggle active"  href="javascript:;" onclick="changeStatus('<?= $value['id'] ?>', '0', 'status', 'restaurants/ajaxaction', 'restaurantStatus')"><i class="fa fa-check"></i>
                                                </button>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <a href="<?php echo ADMIN_BASE_URL ?>restaurants/edit/<?php echo $value['id'] ?>">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                            <a id="<?php echo $value['id']; ?>" onclick="return deleteRecord(<?php echo $value['id']; ?>, 'restaurants/deleterestaurant', 'Restaurant', '', 'restaurantTable')" href="javascript:;">
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
        $('#restaurantTable').DataTable({
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

    function pluginDetail(resId) {
        $('#resId').html(resId);
        $('#pluginPopUp').modal('show');
        return false;
    }
</script>

<div aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="pluginPopUp" class="modal fade" >
    <div role="document" class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Plugin Script</h4>
            </div>
            <div id="trackingContent" class="modal-body clearfix">
                <div class="col-xs-12">
                    <p>Integrate Our Plugin to your website.</p>
                    <ul class="plugin_steps">
                        <li>
                            Step1:
                            <div class="form-horizontal">
                                <div class="col-sm-12 padding20 margin-b-10 margin-t-10">
                                    <p>Just copy this HTML snippets and paste it before closing of body tag &lt;body&gt;..&lt;/body&gt;.</p>
                                    <code class="col-xs-12 margin-b-20" cols="100" rows="" readonly="true">
                                        &lt;script type="text/javascript"&gt;<br>
                                        var resId = "<span id="resId"></span>";<br>
                                        &lt;/script&gt;<br>
                                        &lt;script type="text/javascript" src="<?php echo DIST_URL; ?>widget/js/widget.js"&gt;&lt;/script&gt;

                                    </code>
                                </div>
                            </div>
                        </li>
                        <li>
                            Step2:
                            <div class="form-horizontal">
                                <div class="col-sm-12 padding10 margin-t-10">
                                    <p>Add this class (fos-order) in tag which you need for Order Online.</p>
                                    <p class="color5">For Example : &lt;a class="fos-order"&gt; Order Online &lt;/a&gt;
                                    </p></div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>