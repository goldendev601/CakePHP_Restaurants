<div class="content-wrapper">
    <section class="content-header">
        <h1> Change Password </h1>
        <ol class="breadcrumb">
            <li> <a href="<?php echo ADMIN_BASE_URL ;?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"><a href="<?php echo ADMIN_BASE_URL ;?>addons">Change Password</a></li>
        </ol>
    </section>

    <section class="content clearfix">
        <div class="col-xs-12">
            <div class="row">
                <div class="box my-box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><!-- Addons --></h3>
                        <span class="commonErr"></span>
                    </div>
                    <?php
                        echo $this->Form->create('passwordForm',[
                            'id' => 'passwordForm'
                        ]);
                    ?>

                    <div class="box-body">
                        <div class="col-xs-12 form-group clearfix">
                            <label for="category_id" class="col-sm-2 control-label">Current Password<span class="star">*</span></label>
                            <div class="col-sm-4">
                                <?= $this->Form->input('oldPassword',[
                                    'type' => 'password',
                                    'id'   => 'oldPassword',
                                    'class' => 'form-control',
                                    'label' => false
                                ]); ?>
                            </div>
                            <span class="oldErr"></span>
                        </div>
                    </div>

                    <div class="box-body">
                        <div class="col-xs-12 form-group clearfix">
                            <label for="category_id" class="col-sm-2 control-label">New Password<span class="star">*</span></label>
                            <div class="col-sm-4">
                                <?= $this->Form->input('newPassword',[
                                    'type' => 'password',
                                    'id'   => 'newPassword',
                                    'class' => 'form-control',
                                    'label' => false
                                ]); ?>
                            </div>
                            <span class="newErr"></span>
                        </div>
                    </div>

                    <div class="box-body">
                        <div class="col-xs-12 form-group clearfix">
                            <label for="category_id" class="col-sm-2 control-label">Confirm Password<span class="star">*</span></label>
                            <div class="col-sm-4">
                                <?= $this->Form->input('confirmPassword',[
                                    'type' => 'password',
                                    'id'   => 'confirmPassword',
                                    'class' => 'form-control',
                                    'label' => false
                                ]); ?>
                            </div>
                            <span class="confirmErr"></span>
                        </div>
                    </div>

                    <div class="col-xs-12 no-padding m-t-20">
                        <button type="submit" class="btn btn-info m-r-15" onclick="return changePassword();">Submit</button>

                    </div>
                    <?= $this->Form->end();?>
                </div>
            </div>
        </div>
    </section>
</div>


<script type="text/javascript">

    function changePassword() {
        $(".error").html('');
        var oldPassword = $.trim($("#oldPassword").val());
        var newpassword = $.trim($("#newPassword").val());
        var confirmpassword = $.trim($("#confirmPassword").val());

        if(oldPassword == '') {
            $(".oldErr").addClass('error').html('Please enter your current password');
            $("#oldPassword").focus();
            return false;
        }else if(newpassword == '') {
            $(".newErr").addClass('error').html('Please enter your new password');
            $("#newPassword").focus();
            return false;
        }else if(confirmpassword == '') {
            $(".confirmErr").addClass('error').html('Please enter your confirm password');
            $("#confirmPassword").focus();
            return false;

        }else if(newpassword != confirmpassword) {
            $(".newErr").addClass('error').html('New password and confirm password mismatch');
            $("#newPassword").focus();
            return false;
        }
    }
    
</script>