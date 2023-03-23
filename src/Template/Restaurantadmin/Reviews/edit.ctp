<div class="content-wrapper">
    <section class="content-header">
        <h1> Edit Customer </h1>        
        <ol class="breadcrumb">
            <li>
              <a href="<?php echo ADMIN_BASE_URL ;?>dashboard">
                 <i class="fa fa-dashboard"></i> Home</a>
            </li>           
            <li class="active">
               <a href="<?php echo ADMIN_BASE_URL ;?>customers">Manage Customer</a>
            </li>
        </ol>
    </section>

    <section class="content clearfix">
        <div class="col-xs-12">
            <div class="row">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <!-- Customer -->
                        </h3>
                    </div>
                        <?php
                            if(!empty($customerList)) {
                                echo $this->Form->create($customerList,[
                                    'id' => 'customerEditForm'                                
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
                                <label for="first_name" class="col-sm-2 control-label">First Name<span class="star">*</span></label>
                                <div class="col-sm-4">
                                    <?= $this->Form->input('first_name',[
                                            'type' => 'text',
                                            'id'   => 'first_name',
                                            'class' => 'form-control',
                                            'placeholder' => 'First Name',
                                            'label' => false
                                        ]) ?>
                                      <span class="firstErr"></span>    
                                </div>
                            </div>                            
                        </div>

                        <div class="box-body">
                            <div class="form-group">
                                <label for="last_name" class="col-sm-2 control-label">Last Name<span class="star">*</span></label>
                                <div class="col-sm-4">
                                    <?= $this->Form->input('last_name',[
                                            'type' => 'text',
                                            'id'   => 'last_name',
                                            'class' => 'form-control',
                                            'placeholder' => 'Last Name',
                                            'label' => false
                                        ]) ?>
                                      <span class="lastErr"></span>    
                                </div>
                            </div>                            
                        </div>

                        <div class="box-body">
                            <div class="form-group">
                                <label for="phone_number" class="col-sm-2 control-label">Phone Number<span class="star">*</span></label>
                                <div class="col-sm-4">
                                    <?= $this->Form->input('phone_number',[
                                            'type' => 'text',
                                            'id'   => 'phone_number',
                                            'class' => 'form-control',
                                            'placeholder' => 'Phone Number',
                                            'maxlength' => 11,
                                            'onkeypress' => 'return isNumberKey(event)',
                                            'label' => false
                                        ]) ?>
                                      <span class="phoneErr"></span>    
                                </div>
                            </div>                            
                        </div>

                        <div class="box-body">
                            <div class="form-group">
                                <label for="username" class="col-sm-2 control-label">Email<span class="star">*</span></label>
                                <div class="col-sm-4">
                                    <?= $this->Form->input('username',[
                                            'type' => 'email',
                                            'id'   => 'username',
                                            'class' => 'form-control',
                                            'placeholder' => 'Email',
                                            'label' => false
                                        ]) ?>
                                      <span class="emailErr"></span>    
                                </div>
                            </div>                            
                        </div>

                        <div class="box-footer">
                            <a class="btn btn-default" href="<?php echo ADMIN_BASE_URL?>customers/">Cancel</a>
                            <button type="button" class="btn btn-info pull-right" onclick="customerAddEdit();">Submit</button>
                        </div>
                    <?= $this->Form->end();?>
                </div>
            </div>
        </div>
    </section>
</div>


<script type="text/javascript">

    function isValid(mailAddress){
        var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
        return pattern.test(mailAddress);
    }


    function customerAddEdit(){

        var first_name   = $.trim($("#first_name").val());  
        var last_name    = $.trim($("#last_name").val());                
        var phone_number = $.trim($("#phone_number").val());                
        var username     = $.trim($("#username").val()); 
        var editid       = $.trim($("#editid").val());              
        $('.error').html('');

        if(first_name == '') {
            $('.firstErr').addClass('error').html('Please enter first name');
            $("#first_name").focus();
            return false;
        }else if(last_name == '') {
            $('.lastErr').addClass('error').html('Please enter last name');
            $("#last_name").focus();
            return false;
        }else if(phone_number == '') {
            $('.phoneErr').addClass('error').html('Please enter phone number');
            $("#phone_number").focus();
            return false;
        }else if(username == '') {
            $('.emailErr').addClass('error').html('Please enter email');
            $("#username").focus();
            return false;
        }else if(username != '' && !isValid(username)) {
            $('.emailErr').addClass('error').html('Please enter valid email');
            $("#username").focus();
            return false;
        }else {
            $.ajax({
                type   : 'POST',
                url    : jssitebaseurl+'customers/customerCheck',
                data   : {id:editid, username:username},
                success: function(data){
                    if($.trim(data) == 0) {
                        $("#customerEditForm").submit();
                    }else {
                        $(".emailErr").addClass('error').html('This customer email already exists');
                        $("#username").focus();
                        return false;
                    }
                }
            });
           return false;
        }
    }

    function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }
</script>