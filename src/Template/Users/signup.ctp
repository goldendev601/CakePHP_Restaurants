<div class="container">
    <section class="main_wrapper">
        <div class="loginBg">
            <div class="row">
                <div class="loginBg_h4 clearfix"> <?php echo __('Create Account');?></div>
                <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
                    <div class="loginInnerBg clearfix m-t-10">
                        <div class="col-xs-12 no-padding m-b-20 signup-head">
                            <?php echo __('Please fill out this form, and we all send you an activation email so you can verify your email address and sign in');?>.
                        </div>
                            <?= 
                                $this->Form->create('customerSignup',[
                                    'id' => 'customerSignup'
                                ]); ?>
                            <div class="form-group"><?=
                                $this->Form->input('first_name',[
                                    'class' => 'form-control',
                                    'id' => 'CustomerFirstName',
                                    'value' => (isset($firstName) && $firstName != '') ? $firstName : '',
                                    'placeholder' => __('Firstname'),
                                ]);?>
                                <label id="firstnameErr" class="error"></label>
                            </div>
                            <div class="form-group"><?=
                                $this->Form->input('last_name',[
                                    'class' => 'form-control',
                                    'id' => 'CustomerLastName',
                                    'placeholder' => __('Lastname')
                                ]);?>
                                <label id="lastnameErr" class="error"></label>
                            </div>
                            <div class="form-group"><?=
                                $this->Form->input('email',[
                                    'class' => 'form-control',
                                    'id' => 'CustomerEmail',
                                    'value' => (isset($userName) && $userName != '') ? $userName : '',
                                    'placeholder' => __('Email')
                                ]);?>
                                <label id="mailErr" class="error"></label>
                            </div>
                            <div class="form-group"><?=
                                $this->Form->input('password',[
                                    'class' => 'form-control',
                                    'id' => 'CustomerPassword',
                                    'placeholder' => __('Password')
                                ]);?>
                                <label id="pwdErr" class="error"></label>
                            </div>
                            <div class="form-group"><?=
                                $this->Form->input('confirmPassword',[
                                    'class' => 'form-control',
                                    'type'   => 'password',
                                    'id' => 'UserConfirPassword',
                                    'placeholder' => __('Confirm Password')
                                ]);?>
                                <label id="cpwdErr" class="error"></label>
                            </div>
                            <div class="form-group clearfix"><?=
                                $this->Form->input('phone_number',[
                                    'class' => 'form-control',
                                    'id' => 'CustomerPhone',
                                    'placeholder' => __('Phone Number'),
                                    'maxlength' => 15,
                                    'onkeypress' => 'return isNumberKey(event)'
                                ]);?>
                                <label id="phoneErr" class="error"></label>
                            </div>

                            <div class="form-group clearfix"><?=
                                $this->Form->input('referral_code',[
                                    'class' => 'form-control',
                                    'id' => 'referral_code',
                                    'placeholder' => __('Referral Code'),
                                    'value' => $referralCode,
                                    ($referralCode != '') ? 'readonly' : ''
                                ]);?>
                                <label id="phoneErr" class="error"></label>
                            </div>
                            <div class="signup-footer">
                                <button type="button" onclick="customerSignupValid();">Submit</button>
                                <div class="newuser">
                                    <?php echo __('Already User');?> ?

                                    <?php if($currentpage != '/') { ?>
                                        <a href="<?php echo BASE_URL.'users/login?redirect=%2F'.$currentpage?>"> <?php echo __('Sign In');?></a>
                                    <?php }else { ?>
                                        <a href="<?php echo BASE_URL.'users/login'?>"><?php echo __('Sign In');?></a>
                                    <?php } ?>
                                </div>
                            </div>
                        <?=  $this->Form->end(); ?>
                    </div>
                </div>
            </div>
        </div>    
    </section>
</div>


<script type="text/javascript">
    function isValidEmailAddress(emailAddress) {
        var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
            return pattern.test(emailAddress);
    };
//-----------------------------------------------------------------------
    function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }
//-------------------------------------------------------------------------
    function customerSignupValid(){
        
        var firstname = $.trim($("#CustomerFirstName").val());
        var lastname  = $.trim($("#CustomerLastName").val());
        var email     = $.trim($("#CustomerEmail").val());
        var password  = $.trim($("#CustomerPassword").val());
        var cf_password = $.trim($("#UserConfirPassword").val());
        var phone       = $.trim($("#CustomerPhone").val());

        $("#firstnameErr").html('');
        $("#lastnameErr").html('');
        $("#mailErr").html('');
        $("#pwdErr").html('');
        $("#cpwdErr").html('');
        $("#phoneErr").html('');

        if(firstname == '') {
            $("#firstnameErr").html('<?php echo __("Please enter your firstname");?>');
            $("#CustomerFirstName").focus();
            return false;
        }else if(lastname == '') {
            $("#lastnameErr").html('<?php echo __("Please enter your lastname");?>');
            $("#CustomerLastName").focus();
            return false;
        }else if(email == '') {
            $("#mailErr").html('<?php echo __("Please enter your username");?>');
            $("#CustomerEmail").focus();
            return false;
        }else if(email != '' && !isValidEmailAddress(email)) {
            $("#mailErr").html('<?php echo __("Please enter valid username");?>');
            $("#CustomerEmail").focus();
            return false;
        }else if(password == '') {
            $("#pwdErr").html('<?php echo __("Please enter your password");?>');
            $("#cpwdErr").html('');
            $("#CustomerPassword").focus();
            return false;
        }else if(cf_password == '') {
            $("#cpwdErr").html('<?php echo __("Please enter your confirm password");?>');
            $("#UserConfirPassword").focus();
            return false;
        }else if(password != cf_password) {
            $("#cpwdErr").html('<?php echo __("Password and confirm password should be matched");?>');
            $("#UserConfirPassword").focus();
            return false;
        }else if(phone == '') {
            $("#phoneErr").html('<?php echo __("Please enter your phone number");?>');
            $("#CustomerPhone").focus();
            return false;
        }else{
            $.ajax({
                type   : 'POST',
                url    : jssitebaseurl+'users/checkUsername',
                data   : {username:email},
                success: function(data){
                    if($.trim(data) == 0) {
                        $("#customerSignup").submit();
                    }else {
                        $("#mailErr").html('<?php echo __("This  username already exists");?>');
                        $("#CustomerEmail").focus();
                        return false;
                    }
                }
            });
            return false;
        }
    }
</script>
