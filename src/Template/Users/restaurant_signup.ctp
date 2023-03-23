<div class="container">
	<section class="main_wrapper">
		<div class="row">
			<div class="loginBg_h4 clearfix m-b-10"> <?php echo __('Create Account');?></div>
			<div class="col-xs-12 col-md-12 col-md-offset-0 col-sm-10 col-sm-offset-1">
				<div class="loginInnerBg clearfix">
					<?=
			 			$this->Form->create('User',[
			 			    'class' => 'login-form',
	                        'id' => 'RestSignupForm'
		 			]); ?>
						<div class="row">
							<div class="col-md-6">
								<div class="col-md-11">
									<div class="form-group">
										<?=
									 		$this->Form->input('restaurant.restaurant_name',[
											'class'=>'form-control',
											'id'   =>'restaurant_name'
										]); ?>
											<label id="resnameErr" class="error"></label>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="col-md-11">
									<div class="form-group">
										<?=
											$this->Form->input('restaurant.contact_name',[
												'class'=>'form-control',
		                                      	'id'   =>'contact_name'
										]); ?>
										<label id="contactnameErr" class="error"></label> 
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6">
								<div class="col-md-11">
									<div class="form-group"><?=
										$this->Form->input('restaurant.contact_phone',[
											'class'=>'form-control',
											'id'   =>'contact_phone',
											'maxlength' => 11,
	                            			'onkeypress' => 'return isNumberKey(event)'
										]); ?>
										<label id="contactPhoneErr" class="error"></label>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="col-md-11">
									<div class="form-group"><?=
										$this->Form->input('User.username',[
											'class'=>'form-control',
											'id'   =>'username'
					                    ]); ?> 
					                    <label id="usernameErr" class="error"></label> 
									</div>
								</div>
							</div>
						</div>


						<div class="row">
							<div class="col-md-6">
								<div class="col-md-11">
									<div class="form-group"><?=
										$this->Form->input('User.password',[
											'class'=>'form-control',
											'id'   =>'password'
										]); ?>
										<label id="passwordErr" class="error"></label> 
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="col-md-11">
									<div class="form-group"><?=
											$this->Form->input('User.confirm_password',[
												'class'=>'form-control',
											  	'type' => 'password',
											  	'id'   =>'confirm_password'
											]); ?> 
											<label id="cf-pwdErr" class="error"></label> 
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="signup-footer">
								<div class="col-md-6">
									<div class="col-md-11">
	                                    <button type="button" onclick="restaurantSignupValid();">Save</button>
									</div>
								</div>
								<div class="col-md-6">
									<div class="col-md-11">
										<div class="margin-t-5 text-right">
											<a class="linkRight" href="<?php echo BASE_URL.'restaurantadmin' ?>"><?php echo __('Already User');?></a>
										</div>
									</div>
								</div>
							</div>
							<?= $this->Form->end();?>
						</div>
				</div>	
			</div>
		</div>
	</section>	
</div>	

<script type="text/javascript">	
    function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }
//---------------------------------------------------------------------
    function isValidEmailAddress(emailAddress) {
        var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
            return pattern.test(emailAddress);
    }
//--------------------------------------------------------------------
     function restaurantSignupValid(){
        
        var resName      = $.trim($("#restaurant_name").val());
        var contactName  = $.trim($("#contact_name").val());
        var contactPhone = $.trim($("#contact_phone").val());
        var username     = $.trim($("#username").val());
        var password     = $.trim($("#password").val());
        var cf_password  = $.trim($("#confirm_password").val());

        $("#resnameErr").html('');
		$("#contactnameErr").html('');
		$("#contactPhoneErr").html('');
		$("#usernameErr").html('');
		$("#passwordErr").html('');
		$("#cf-pwdErr").html('');		

        if(resName == '') {
            $("#resnameErr").addClass('error').html('<?php echo __("Please enter your restaurant name");?>');
            $("#restaurant_name").focus();
            return false;
        }else if(contactName == '') {
            $("#contactnameErr").addClass('error').html('<?php echo __("Please enter the contact name");?>');
            $("#contact_name").focus();
            return false;
        }else if(contactPhone == '') {
            $("#contactPhoneErr").addClass('error').html('<?php echo __("Please enter the contact number");?>');
            $("#contact_phone").focus();
            return false;
        }else if(username == '') {
            $("#usernameErr").addClass('error').html('<?php echo __("Please enter your username");?>');
            $("#username").focus();
            return false;
        }else if(username != '' && !isValidEmailAddress(username)) {
            $("#usernameErr").addClass('error').html('<?php echo __("Please enter your valid username");?>');
            $("#username").focus();
            return false;
        }else if(password == '') {
            $("#passwordErr").addClass('error').html('<?php echo __("Please enter your password");?>');
            $("#cf-pwdErr").html('');
            $("#password").focus();
            return false;
        }else if(cf_password == '') {
            $("#cf-pwdErr").addClass('error').html('<?php echo __("Please enter your confirm password");?>');
            $("#confirm_password").focus();
            return false;
        }else if(password != cf_password) {
            $("#cf-pwdErr").addClass('error').html('<?php echo __("Password and confirm password should be matched");?>');
            $("#confirm_password").focus();
            return false;
        }else{
            $.ajax({
                type   : 'POST',
                url    : jssitebaseurl+'users/checkUsername',
                data   : {username:username},
                success: function(data){
                    if($.trim(data) == 0) {
                        $("#RestSignupForm").submit();
                    }else {
                        $("#usernameErr").html('<?php echo __("Username already exists");?>');
                        $("#username").focus();
                        return false;
                    }
                }
            });
            return false;
        }
    }
</script>