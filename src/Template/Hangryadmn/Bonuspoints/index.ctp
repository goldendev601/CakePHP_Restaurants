<div class="content-wrapper">
    <section class="content-header">
        <h1>
           Manage Bonuspoint
        </h1>
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
                       <a class="btn btn-primary pull-right" href="<?php echo ADMIN_BASE_URL;?>bonuspoints/add"><i class="fa fa-plus"></i> Add New</a>
                    </div>
                    <div class="box-body" id="ajaxReplace">
                        <table id="myTable" class="dataTable table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Restaurant Name</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>  
                                <?php if(!empty($bonusList)) {
                                    foreach($bonusList as $key => $value) { ?>
                                        <tr>
                                            <td><?php echo $key+1 ;?></td>    
                                            <td><?php echo $value['restaurant']['restaurant_name'];?></td>
                                            <td id="status_<?php echo $value['id'];?>">
                                                <?php if($value['status'] == '0') { ?>
                                                   <i class="fa fa-close"></i>
                                               <?php }else { ?>
                                                   <i class="fa fa-check"></i>
                                               <?php } ?>
                                            </td>                                            
                                            <td>
                                              <a href="<?php echo ADMIN_BASE_URL ?>bonuspoints/edit/<?php echo $value['restaurant']['id'];?>"><i class="fa fa-pencil"></i></a>
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
