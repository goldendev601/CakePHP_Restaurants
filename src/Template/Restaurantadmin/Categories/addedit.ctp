<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <?php if(!empty($id)) {?> Edit <?php }else{?> Add <?php }?>Category
        </h1>
        <ol class="breadcrumb">
            <li>
              <a href="https://www.hangrymenu.com/restaurantadmin/dashboard">
                 <i class="fa fa-dashboard"></i> Home</a>
            </li>           
            <li class="active">
               <a href="https://www.hangrymenu.com/restaurantadmin/categories">Manage Category</a>
            </li>
        </ol>
    </section>

    <section class="content clearfix">
        <div class="col-xs-12">
            <div class="row">
                <div class="box my-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <!-- Category -->
                        </h3>
                    </div>
                        <?php
                            if(!empty($catList)) {
                                echo $this->Form->create($catList, [
                                    'id' => 'catAddEditForm'                                
                                ]);                                                          
                             } else {
                                echo $this->Form->create('catAdd', [
                                    'id' => 'catAddEditForm'
                                ]);
                            } 

                            echo $this->Form->input('editid',[
                                'type' => 'hidden',
                                'id'   => 'editid',
                                'class' => 'form-control',
                                'value' => !empty($id) ? $id : '',
                                'label' => false
                            ]);                         
                        ?>                    
                        <div class="box-body">
                            <div class="form-group">
                                <label for="category_name" class="col-sm-2 control-label">Category Name<span class="star">*</span></label>
                                <div class="col-sm-4">
                                    <?= $this->Form->input('category_name',[
                                            'type' => 'text',
                                            'id'   => 'category_name',
                                            'class' => 'form-control',
                                            'placeholder' => 'Category Name',
                                            'label' => false
                                        ]) ?>
                                      <span class="catErr"></span>    
                                </div>
                            </div>                            
                        </div>
                        <div class="col-xs-12 no-padding m-t-20">
                            <button type="button" class="btn btn-info m-r-15" onclick="categoryAddEdit();">Submit</button>
                            <a class="btn btn-default" href="https://www.hangrymenu.com/restaurantadmin/categories/">Cancel</a>
                            
                        </div>
                    <?= $this->Form->end();?>
                </div>
            </div>
        </div>
    </section>
</div>


<script type="text/javascript">

    function categoryAddEdit(){

        var category_name = $.trim($("#category_name").val());        
        var editid        = $.trim($("#editid").val());   
        $('.error').html('');

        if(category_name == '') {
            $('.catErr').addClass('error').html('Please enter category name');
            $("#category_name").focus();
            return false;
        }else {

            $.ajax({
                type   : 'POST',
                url    : jssitebaseurl+'categories/categoryCheck',
                data   : {id:editid, category_name:category_name},
                success: function(data){
                    if($.trim(data) == 0) {
                        $("#catAddEditForm").submit();
                    }else {
                        $(".catErr").addClass('error').html('This category name already exists');
                        $("#category_name").focus();
                        return false;
                    }
                }
            });
           return false;
        }
    }        
</script>
