<div class="content-wrapper">
    <section class="content-header">
        <h1>
           <?php if(!empty($id)) {?> Edit <?php }?> State
        </h1>
        <ol class="breadcrumb">
            <li>
              <a href="<?php echo ADMIN_BASE_URL ;?>dashboard">
                 <i class="fa fa-dashboard"></i> Home</a>
            </li>           
            <li class="active">
               <a href="<?php echo ADMIN_BASE_URL ;?>states">Manage State</a>
            </li>
        </ol>
    </section>

    <section class="content clearfix">
        <div class="col-xs-12">
            <div class="row">
                <div class="box my-box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><!-- Categories --></h3>
                    </div>
                        <?php
                            if(!empty($statesList)) {
                                echo $this->Form->create($statesList, [
                                    'id' => 'stateEditForm'                                
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
                                <label for="country_id" class="col-sm-2 control-label">Country Name<span class="star">*</span></label>
                                <div class="col-sm-4">
                                    <?= $this->Form->input('country_id',[
                                        'type' => 'select',
                                        'id'   => 'country_id',
                                        'class' => 'form-control',
                                        'options'=> $countrylist,
                                        'values' => isset($statesList['country_id']) ? $statesList['country_id'] :  '',
                                        'empty'  => 'Select Country Name',
                                        'label' => false
                                     ]) ?>
                                     <span class="countryErr"></span>    
                                </div>
                            </div>                            
                        </div>

                        <div class="box-body">
                            <div class="form-group">
                                <label for="state_name" class="col-sm-2 control-label">State Name<span class="star">*</span></label>
                                <div class="col-sm-4">
                                    <?= $this->Form->input('state_name',[
                                            'type' => 'text',
                                            'id'   => 'state_name',
                                            'class' => 'form-control',
                                            'placeholder' => 'State Name',
                                            'label' => false
                                        ]) ?>
                                      <span class="stateErr"></span>    
                                </div>
                            </div>                            
                        </div>

                        <div class="col-xs-12 no-padding m-t-20">
                            <button type="button" class="btn btn-info m-r-15" onclick="stateAddEdit();">Submit</button>
                            <a class="btn btn-default" href="<?php echo ADMIN_BASE_URL?>states/">Cancel</a>
                            
                        </div>

                    <?= $this->Form->end();?>
                </div>
            </div>
        </div>
    </section>
</div>


<script type="text/javascript">

    function stateAddEdit(){

        var country_id = $.trim($("#country_id").val());        
        var state_name = $.trim($("#state_name").val());
        var editid     = $.trim($("#editid").val());    
        $('.error').html('');

        if(country_id == '') {
            $('.countryErr').addClass('error').html('Please select country name');
            $("#country_id").focus();
            return false;

        }else if(state_name == '') {        
            $('.stateErr').addClass('error').html('Please enter state name');
            $("#state_name").focus();
            return false;
        }else {

            $.ajax({
                type   : 'POST',
                url    : jssitebaseurl+'states/stateCheck',
                data   : {id:editid ,country_id:country_id, state_name:state_name},
                success: function(data){
                    if($.trim(data) == 0) {
                        $("#stateEditForm").submit();
                    }else {
                        $(".stateErr").addClass('error').html('This state name already exists');
                        $("#state_name").focus();
                        return false;
                    }
                }
            });
           return false;
        }
    }        
</script>