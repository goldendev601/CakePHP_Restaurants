<div class="content-wrapper">
    <section class="content-header">
        <h1> Send Mail All User  </h1>
        <ol class="breadcrumb">
            <li>
              <a href="<?php echo ADMIN_BASE_URL ;?>dashboard">
                 <i class="fa fa-dashboard"></i> Home</a>
            </li>           
            <li class="active">
               <a href="<?php echo ADMIN_BASE_URL ;?>newsletters">Manage Newsletter</a>
            </li>
        </ol>
    </section>

    <section class="content clearfix">
        <div class="col-xs-12">
            <div class="row">
                <div class="box my-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <?php if(!empty($id)) {?> Edit <?php }else{?> Add <?php }?>Newsletter
                        </h3>
                    </div>
                        <?php
                            echo $this->Form->create('newsletterForm',[
                                'id' => 'newsletterForm'
                            ]);
                        ?>  

                        <div class="box-body">
                            <div class="form-group">
                                <label for="subject" class="col-sm-2 control-label">Subject<span class="star">*</span></label>
                                <div class="col-sm-4">
                                    <?= $this->Form->input('subject',[
                                            'type' => 'text',
                                            'id'   => 'subject',
                                            'class' => 'form-control',
                                            'placeholder' => 'Please Enter subject',
                                            'label' => false
                                        ]) ?>
                                      <span class="subjectErr"></span>    
                                </div>
                            </div>                            
                        </div>

                        <div class="box-body">
                            <div class="form-group">
                                <label for="to" class="col-sm-2 control-label">To<span class="star">*</span></label>
                                <div class="col-sm-4">
                                    <?= $this->Form->input('to',[
                                            'type' => 'text',
                                            'value' => 'All',
						 					'readonly' => true,
                                            'class' => 'form-control',
                                            'label' => false
                                        ]) ?>
                                      <span class="toErr"></span>    
                                </div>
                            </div>                            
                        </div>

                        <div class="box-body">
                            <div class="form-group">
                                <label for="content" class="col-md-2 control-label">Content</label>
                                <div class="col-md-9 word-editor">
                                    <?= $this->Form->input('content',[
                                             'label'=>false,
                                             'type'=>'textarea',
                                             'class' => 'form-control summernote',
                                        ]); ?>
                                    <span class="contentErr"></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 no-padding m-t-20">
                             <button type="button" class="btn btn-info m-r-15" onclick="newsletterAdd();">Submit</button>
                            <a class="btn btn-default" href="<?php echo ADMIN_BASE_URL?>newsletters/">Cancel</a>
                        </div>

                    <?= $this->Form->end();?>
                </div>
            </div>
        </div>
    </section>
</div>


<script type="text/javascript">

    function newsletterAdd(){

        var subject = $.trim($("#subject").val());       
        var content = $.trim($("#content").val());        
        var editid        = $.trim($("#editid").val());  
        $('.error').html('');

        if(subject == '') {
            $('.subjectErr').addClass('error').html('Please Enter subject');
            $("#subject").focus();
            return false;
        } else if (content == ''){
            $('.contentErr').addClass('error').html('Please Enter Content');
            $("#content").focus();
            return false;
        } else if (subject != ''){
             $("#newsletterForm").submit();
        }
    }
</script>