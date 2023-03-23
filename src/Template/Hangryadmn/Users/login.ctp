
<div class="login-box">
    <div class="login-logo">
        <a href="">           
            <img src="../images/login-logo.png">
        </a>
    </div>
    <div class="login-box-body">
        <div id="login">
           <!--  <p class="login-box-msg">Login</p> -->
            <form action="" method="post">
                <div class="form-group has-feedback">
                    <input type="text" id="username" class="form-control customized-input" placeholder="Email address" name="username">
                    <!-- <span class="glyphicon glyphicon-envelope form-control-feedback"></span> -->
                    <span class="userErr"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control customized-input" placeholder="Password" id="password" name="password">
                    <!-- <span class="glyphicon glyphicon-lock form-control-feedback"></span> -->
                    <span class="passErr"></span>
                </div>
                <div id="forget" class="col-md-12 col-sm-12 col-xs-12 no-padding">
                    <div class="rememberpassword text-right text-left-xs">
                        <a href="javascript:void(0);" onclick="return forgotDiv('forgot');"class="linkRight" id="forgetPage"><?php echo __('Forget Password');?> ?</a>
                    </div>
                </div>
                <div class="form-group has-feedback">
                    <input type="checkbox" name="rememberme" id="rememberme">
                    <label for="rememberme">Remember me</label>
                </div>

                <div class="form-group has-feedback clearfix button-div">
                    <button type="submit" onclick="return adminLogin()" class="btn btn-light btn-block btn-customized">Log In</button>
                </div>
                <!-- <div class="form-group has-feedback clearfix">
                    <button class="btn btn-block btn-customized-google"><img src="../images/google-icon.png" width="25px"> Log in with Google</button>
                </div> -->

                
            </form>

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
                        'class' => 'btn btn-primary btn-block btn-flat',
                        'onclick'=>'return forgetPwdValid()',
                        'id' => 'submit',
                        'div' =>false
                    ]); ?>
                </div>
                <div class="newuser pull-right ">
                    <?php echo __('Back to');?> <a href="javascript:void(0);" onclick="return forgotDiv('login');" id="loginPage" ><?php echo __('Login');?></a>
                </div>
            </div>
            <?=  $this->Form->end(); ?>
        </div>

    </div>
</div>


<style type="text/css">
    .login-box,
    .register-box {
      width: 50%;
      height: -webkit-fill-available;
      background-color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      float: right;
      margin: unset;
      flex-direction: column;
    }
    .login-page{background-color: #f05a28;}
    .login-box-body {
        margin-top: 30px;
        padding: 10px;
        width: 40%;
    }
    .customized-input {
        border: none;
        border-bottom: 1px solid #ccc;
        height: 50px;
        color: #ccc;
    }
    #forget {
        margin-top: -50px;
        margin-right: 10px;
        width: max-content;
        float: right;
    }

    .btn-customized {
        height: 50px;
        background-color: #f1f3f7;
        color: #9faabb;
    }

    .btn-customized-google {
        height: 50px;
        background-color: white;
        color: lightblue;
        border: 1px solid #ccc;
    }

    .button-div {
        margin-top: 20px;
    }
</style>

<script>
    function adminLogin() {
        $(".error").html('');
        var username = $.trim($("#username").val());
        var password = $.trim($("#password").val());

        if(username == '') {
            $(".userErr").addClass('error').html('Please enter your username');
            $("#username").focus();
            return false;
        }else if(password == '') {
            $(".passErr").addClass('error').html('Please enter your password');
            $("#password").focus();
            return false;
        }
    }
</script>