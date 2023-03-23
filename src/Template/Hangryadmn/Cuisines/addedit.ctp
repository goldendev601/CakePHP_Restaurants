<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <?php if(!empty($id)) {?> Edit <?php }else{?> Add <?php }?>Cuisine
        </h1>
        <ol class="breadcrumb">
            <li>
              <a href="<?php echo ADMIN_BASE_URL ;?>dashboard">
                 <i class="fa fa-dashboard"></i> Home</a>
            </li>           
            <li class="active">
               <a href="<?php echo ADMIN_BASE_URL ;?>cuisines">Manage Cuisine</a>
            </li>
        </ol>
    </section>

    <section class="content clearfix">
        <div class="col-xs-12">
            <div class="row">
                <div class="box my-box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><!-- Cuisine --></h3>
                    </div>
                        <?php
                            if(!empty($cuisinesList)) {
                                echo $this->Form->create($cuisinesList, [
                                    'id' => 'cuisineAddEditForm'                                
                                ]);                                                          
                             } else {
                                echo $this->Form->create('cuisineAdd', [
                                    'id' => 'cuisineAddEditForm'
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
                                <label for="cuisine_name" class="col-sm-2 control-label">Cuisine Name<span class="star">*</span></label>
                                <div class="col-sm-4">
                                    <?= $this->Form->input('cuisine_name',[
                                            'type' => 'text',
                                            'id'   => 'cuisine_name',
                                            'class' => 'form-control',
                                            'placeholder' => 'Cuisine Name',
                                            'label' => false
                                        ]) ?>
                                      <span class="nameErr"></span>    
                                </div>
                            </div>                            
                        </div>
                        <div class="col-xs-12 no-padding m-t-20">
                            <button type="button" class="btn btn-info m-r-15" onclick="cuisineAddEdit();">Submit</button>
                            <a class="btn btn-default" href="<?php echo ADMIN_BASE_URL?>cuisines/">Cancel</a>
                            
                        </div>
                    <?= $this->Form->end();?>
                </div>
            </div>
        </div>
    </section>
</div>


<script type="text/javascript">

    function cuisineAddEdit(){

        var cuisine_name = $.trim($("#cuisine_name").val());        
        var editid        = $.trim($("#editid").val());  //alert(editid);  
        $('.error').html('');

        if(cuisine_name == '') {
            $('.nameErr').addClass('error').html('Please enter cuisine name');
            $("#cuisine_name").focus();
            return false;
        }else {

            $.ajax({
                type   : 'POST',
                url    : jssitebaseurl+'cuisines/cuisineCheck',
                data   : {id:editid, cuisine_name:cuisine_name},
                success: function(data){
                    if($.trim(data) == 0) {
                        $("#cuisineAddEditForm").submit();
                    }else {
                        $(".nameErr").addClass('error').html('This cuisine name already exists');
                        $("#cuisine_name").focus();
                        return false;
                    }
                }
            });
           return false;
        }
    }        
</script>