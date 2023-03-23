<div class="container">
    <section class="main_wrapper">
        <div class="loginBg">
            <div class="row">
                <div class="loginBg_h4 clearfix"> <?php echo __('Login');?></div>
                <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
                    <div class="loginInnerBg clearfix m-t-10" id="login">
                        <div class="col-xs-12 no-padding margin-b-20 signup-head">
                            <?php echo __('Please fill out the below form-group');?>
                        </div>
                        <div class="social-logins clearfix social pluginLogo">
                            <div class="col-xs-12">
                                <a href="" class="facebook" onclick="return facebookLogin()">
                                    <img src="<?= BASE_URL ?>images/facebook.png" title="facebook" alt="facebook">
                                    <i><?php echo __('Login with Facebook');?></i>
                                    <div class="clr"></div>
                                </a>
                            </div>
                            <div class="col-xs-12">
                                <a href="<?php echo BASE_URL; ?>users/social/Google" class="googleplus">
                                    <img src="<?= BASE_URL ?>images/gplus.png" title="gplus" alt="gplus">
                                    <i><?php echo __('Login with Google+');?></i>
                                    <div class="clr"></div>
                                </a>
                            </div>
                        </div>
                        <div class="socialOr social pluginLogo"><span>[ <?php echo __('OR');?> ]</span></div>
                            <?=
                                $this->Form->create('User', ['class' => 'login-form',
                                    'method' => 'post'
                                    ]);
                            ?>
                            <div class="form-group"><?=
                                $this->Form->input('username',[
                                    'label' => false,
                                    'id' => 'username',
                                    'placeholder' => __('Email'),
                                    'class'=>'form-control placeholder-no-fix',
                                    'value' => $username,
                                    'autocomplete' => 'off',
                                    'div' => false
                                ]); ?>
                                <label id="userError" class="error"></label>

                            </div>
                            <div class="form-group"><?=
                                $this->Form->input('password',[
                                    'label' => false,
                                    'placeholder' => __('Password'),
                                    'id' => 'password',
                                    'class'=>'form-control placeholder-no-fix',
                                    'autocomplete' => 'off',
                                    'value' => $password,
                                    'div' => false
                                ]); ?>
                                <label id="pwdError" class="error"></label>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-12 pul-right">
                                    <div class="rememberpassword">
                                        <div class="checkbox checkbox-inline remember-checbox"><?=
                                                $this->Form->input("rememberMe",[
                                                    "type"=>"checkbox",
                                                    'label'=>false,
                                                    'id' => 'remember',
                                                    'div' =>false,
                                                    ($rememberMe == '1') ? 'checked' : ''
                                                ]);
                                            ?>
                                            <label for="remember"><?php echo __('Remember me', true);?></label>
                                        </div>
                                    </div>
                                </div>
                                <div id="forget" class="col-md-6 col-sm-6 col-xs-12 pul-left">
                                    <div class="rememberpassword text-right text-left-xs">
                                        <a href="javascript:void(0);" onclick="return forgotDiv('forgot');"class="linkRight" id="forgetPage"><?php echo __('Forget Password');?> ?</a>
                                    </div>
                                </div>
                            </div>
                            <div class="signup-footer margin-t-15-xs clearfix">
                                <div class="submit"><?=
                                        $this->Form->input("submit",[
                                                    'type' => 'submit',
                                                    'label'=>false,
                                                    'onclick'=>'return loginValid()',
                                                    'id' => 'submit',
                                                    'div' =>false
                                                ]); ?>
                                </div>
                                <div class="newuser"><?php echo __('New User');?> ?
                                    <?php if($currentpage != '/') { ?>
                                        <a href="<?php echo BASE_URL.'users/signup?redirect=%2F'.$currentpage?>"> <?php echo __('Create New Account');?> ?
                                        </a>
                                    <?php }else { ?>
                                        <a href="<?php echo BASE_URL.'users/signup'?>"> <?php echo __('Create New Account');?> ?
                                        </a>

                                    <?php } ?>
                                </div>
                            </div>

                        <!--http://localhost/foodlove/users/login?redirect=%2Fcheckouts-->

                        <?=  $this->Form->end(); ?>
                    </div>
                    <div style="display:none" id="forgotPwd" class="loginInnerBg clearfix">
                        <h4><?php echo __(' Forget Mail');?> </h4>  <?=
                         $this->Form->create('Users',[
                            'class' => 'login-form',
                            'id'=>'forgetmail',
                            'class' => 'login-form'
                        ]); ?>

                        <div class="form-group"><?=
                             $this->Form->input('forgotemail',[
                                'label' => false,
                                'placeholder' => __('Email'),
                                'id'=>'forgotemail',
                                'class'=>'form-control placeholder-no-fix',
                                'autocomplete' => 'off',
                                'div' => false
                            ]); ?>
                            <label id="forgotmailErr" class="error"></label>
                        </div>
                        <div class="signup-footer">
                            <div class="submit"><?=
                                $this->Form->input("submit",[
                                    'type' => 'submit',
                                    'label'=>false,
                                    'onclick'=>'return forgetPwdValid()',
                                    'id' => 'submit',
                                    'div' =>false
                                ]); ?>
                            </div>
                            <div class="newuser">
                                <?php echo __('Back to');?> <a href="javascript:void(0);" onclick="return forgotDiv('login');" id="loginPage" ><?php echo __('Login');?></a>
                            </div>
                        </div>
                       <?=  $this->Form->end(); ?>
                    </div>
                    <!-- <div class="socialOr"><span>Or</span></div> -->
                </div>
            </div>
        </div>
    </section>
</div>


<input type="hidden" id="appId" value="<?= APPID ?>">
<script type="text/javascript">

    function isValidEmailAddress(emailAddress) {
    var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
    return pattern.test(emailAddress);
    };

    function loginValid(){

        var username = $.trim($("#username").val());
        var password = $.trim($("#password").val());

        $("#userError").html('');
        $("#pwdError").html('');

        if(username == '') {           
            $("#userError").html('<?php echo __("Please enter your username");?>');
            $("#username").focus();
            return false;
        }else if(password == '') {
            $("#pwdError").html('<?php echo __("Please enter your password");?>');
            $("#password").focus();
            return false;
        }
    }

    function forgetPwdValid() {

        var forgotemail = $.trim($("#forgotemail").val());
        $('#forgotmailErr').html('');

        if(forgotemail == '') {
            $("#forgotmailErr").html('<?php echo __("Please enter your email");?>');
            $("#forgotemail").focus();
            return false;
        }else if(forgotemail != '' && !isValidEmailAddress(forgotemail)) {
            $("#forgotmailErr").html('<?php echo __("Please enter valid email");?>');
            $("#forgotemail").focus();
            return false;
        }else {
            $.post(jssitebaseurl+'users/forgotPassword', {
                'username' : forgotemail
            }, function (output) {

                if ($.trim(output) == 0) {
                    $("#forgotmailErr").html('<?php echo __("Invalid account");?>');
                    return false;
                }else if ($.trim(output) == 1) {
                    window.location.reload();
                }
            });
            return false;
        }
    }

    function forgotDiv(val){
        if(val == 'forgot'){
            $("#forgotPwd").show();
            $("#login").hide();
        }else{
            $("#forgotPwd").hide();
            $("#login").show();
        }
    }

    (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "https://connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

    function facebookLogin() {
        var appid = $("#appId").val();

        FB.init({
            appId      : appid, //live
            cookie     : true,  // enable cookies to allow the server to access
                                // the session
            xfbml      : true,  // parse social plugins on this page
            version    : 'v2.11' // use graph api version 2.8
        });

        FB.login(function(response)
        {
            var user_id = response.authResponse.userID;
            if (response.authResponse)
            {
                FB.api("/me?fields=name,email", function(response)
                {
                    if(response.email!="")
                    {
                        $.post(jssitebaseurl+'users/facebookLogin', {
                            'username' : response.email,'first_name' : response.name}, function (output){

                            if($.trim(output) == 0){
                                window.location.reload();
                            }else if($.trim(output) == 1){
                                FB.logout();                                
                                alert('<?php echo __("Email Already exists.");?>');
                                return false;

                            }else if($.trim(output) == 2){
                                FB.logout();                                
                                alert('<?php echo __("Your account has been deactivated. Please contact Admin.");?>');
                                return false;
                            }else if($.trim(output) == 3){
                                FB.logout();                                
                                alert('<?php echo __("Your account has been deleted. Please contact Admin.");?>');
                                return false;
                            }else{
                                FB.logout();                                
                                alert('<?php echo __("Required field empty");?>');
                                return false;
                            }
                        });

                    } else {
                        alert('<?php echo __("Sorry unkown error.");?>');
                        return false;
                    }
                });
            }
        },{scope: 'email'});

        return false;
    }
</script>