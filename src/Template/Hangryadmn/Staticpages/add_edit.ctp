<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Staticpage
        </h1>
        <ol class="breadcrumb">
            <li>
              <a href="<?php echo ADMIN_BASE_URL ;?>dashboard">
                 <i class="fa fa-dashboard"></i> Home</a>
            </li>           
            <li class="active">
               <a href="<?php echo ADMIN_BASE_URL ;?>Staticpages">Manage Staticpage</a>
            </li>
        </ol>
    </section>

    <section class="content clearfix">
        <div class="col-xs-12">
            <div class="row">
                <div class="box my-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <?php if(!empty($id)) {?> Edit <?php }else{?> Add <?php }?>Staticpage
                        </h3>
                    </div>
                        <?php
                            if(!empty($staticList)) {
                                echo $this->Form->create($staticList, [
                                    'id' => 'staticAddEditForm'                                
                                ]);                                                          
                             } else {
                                echo $this->Form->create('staticAdd', [
                                    'id' => 'staticAddEditForm'
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
                                <label for="title" class="col-sm-2 control-label">Title<span class="star">*</span></label>
                                <div class="col-sm-4">
                                    <?= $this->Form->input('title',[
                                            'type' => 'text',
                                            'id'   => 'title',
                                            'class' => 'form-control',
                                            'placeholder' => 'Please Enter Title',
                                            'label' => false
                                        ]) ?>
                                      <span class="titleErr"></span>    
                                </div>
                            </div>                            
                        </div>
                        <div class="box-body">
                                <div class="form-group">
                                    <label for="content" class="col-md-2 control-label">Content</label>
                                    <div class="col-md-9 word-editor"><?php
                                        echo $this->Form->input('content',
                                                array('label'=>false,
                                                     'type'=>'textarea',
                                                     'class' => 'form-control summernote',)); ?>
                                        <span class="contentErr"></span>
                                    </div>
                                </div>
                            </div>
                        <div class="col-xs-12 no-padding m-t-20">
                             <button type="button" class="btn btn-info m-r-15" onclick="staticAddEdit();">Submit</button>
                            <a class="btn btn-default" href="<?php echo ADMIN_BASE_URL?>Staticpages/">Cancel</a>
                           
                        </div>
                    <?= $this->Form->end();?>
                </div>
            </div>
        </div>
    </section>
</div>


<script type="text/javascript">

    function staticAddEdit(){

        var title = $.trim($("#title").val());        
        var content = $.trim($("#content").val());        
        var editid        = $.trim($("#editid").val());  //alert(editid);  
        $('.error').html('');

        if(title == '') {
            $('.titleErr').addClass('error').html('Please Enter title');
            $("#title").focus();
            return false;
        } else if (content == ''){
            $('.contentErr').addClass('error').html('Please Enter Content');
            $("#content").focus();
            return false;
        } else if (title != ''){

            $.ajax({
                type   : 'POST',
                url    : jssitebaseurl+'staticpages/staticCheck',
                data   : {id:editid, title:title},
                success: function(data){
                    if($.trim(data) == 0) {
                        $("#staticAddEditForm").submit();
                    }else {
                        $(".titleErr").addClass('error').html('This Title already exists');
                        $("#title").focus();
                        return false;
                    }
                }
            });
           return false;
        }
    }

</script>