<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Language Settings
        </h1>
        <ol class="breadcrumb">
            <li>
              <a href="<?php echo ADMIN_BASE_URL ;?>dashboard">
                 <i class="fa fa-dashboard"></i> Home</a>
            </li>           
            <li class="active">
               <a href="<?php echo ADMIN_BASE_URL ;?>LanguageSettings">Manage Language Setting</a>
            </li>
        </ol>
    </section>

    <section class="content clearfix">
        <div class="col-xs-12">
            <div class="row">
                <div class="box my-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <?php if(!empty($id)) {?> Edit <?php }else{?> Add <?php }?>Language Setting
                        </h3>
                    </div>
                        <?php
                            if(!empty($languageList)) {
                                echo $this->Form->create($languageList, [
                                    'id' => 'languageAddEditForm'                                
                                ]);                                                          
                             } else {
                                echo $this->Form->create('languageAdd', [
                                    'id' => 'languageAddEditForm'
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
                                <label for="language_name" class="col-sm-2 control-label">Language Name<span class="star">*</span></label>
                                <div class="col-sm-4">
                                    <?= $this->Form->input('language_name',[
                                            'type' => 'text',
                                            'id'   => 'language_name',
                                            'class' => 'form-control',
                                            'placeholder' => 'Please Enter Language Name',
                                            'label' => false
                                        ]) ?>
                                      <span class="languageNameErr"></span>    
                                </div>
                            </div>                            
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label for="language_code" class="col-sm-2 control-label">Language Code<span class="star">*</span></label>
                                <div class="col-sm-4">
                                    <?= $this->Form->input('language_code',[
                                            'type' => 'text',
                                            'id'   => 'language_code',
                                            'class' => 'form-control',
                                            'placeholder' => 'Please Enter Language Code',
                                            'label' => false
                                        ]) ?>
                                      <span class="languageCodeErr"></span>    
                                </div>
                            </div>                            
                        </div>
                        <div class="col-xs-12 no-padding m-t-20">
                            <button type="button" class="btn btn-info m-r-15" onclick="languageAddEdit();">Submit</button>
                            <a class="btn btn-default" href="<?php echo ADMIN_BASE_URL?>LanguageSettings/">Cancel</a>
                         </div>
                    <?= $this->Form->end();?>
                </div>
            </div>
        </div>
    </section>
</div>


<script type="text/javascript">

    function languageAddEdit(){

        var language_name = $.trim($("#language_name").val());        
        var language_code = $.trim($("#language_code").val());        
        var editid        = $.trim($("#editid").val());  //alert(editid);  
        $('.error').html('');

        if(language_name == '') {
            $('.languageNameErr').addClass('error').html('Please Enter Language Name');
            $("#language_name").focus();
            return false;
        } else if (language_code == ''){
            $('.languageCodeErr').addClass('error').html('Please Enter Language Code');
            $("#language_code").focus();
            return false;
        } else if (language_name != ''){

            $.ajax({
                type   : 'POST',
                url    : jssitebaseurl+'languageSettings/languageCheck',
                data   : {id:editid, language_name:language_name},
                success: function(data){
                    if($.trim(data) == 0) {
                        $("#languageAddEditForm").submit();
                    }else {
                        $(".languageNameErr").addClass('error').html('This Language Name already exists');
                        $("#language_name").focus();
                        return false;
                    }
                }
            });
           return false;
        }
    }

</script>