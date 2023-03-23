<div class="content-wrapper">
    <section class="content-header">
        <h1> Manage Reviews </h1>   
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>            
        </ol>
    </section>
    <section class="content clearfix">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body" id="ajaxReplace">
                        <table id="myTable" class="dataTable table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Order Id</th> 
                                    <th>Customer Name</th>
                                    <th>rating</th>  
                                    <th>Message</th>   
                                </tr>
                            </thead>
                            <tbody>  
                                <?php if(!empty($allReviews)) {
                                    foreach($allReviews as $key => $value) { ?>
                                        <tr>
                                            <td><?php echo $key+1 ;?></td>    
                                            <td><?php echo $value['order_id'] ;?></td>
                                            <td><?php echo $value['customer_name'] ;?></td>
                                            <td><?php echo $value['rating'] ;?></td>
                                            <td><?php echo $value['message'] ;?></td>
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
    function changeStatus(id, changestaus, field, urlval, action)
    {
       $.ajax({
            type   : 'POST',
            url    : jssitebaseurl+'reviews/ajaxaction',
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
            //$("#maska").show();$(".ui-loader").show();
            $.ajax({
                type   : 'POST',
                url    : jssitebaseurl+'reviews/deleteReview',
                data   : {id:id, page:page, action:action},
                success: function(data){
                    $("#ajaxReplace").html(data);                    
                }
            });return false;
        }
    }
</script>