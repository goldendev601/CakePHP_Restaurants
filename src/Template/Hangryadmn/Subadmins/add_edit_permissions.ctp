<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <?php if(!empty($id)) {?> Edit <?php }else{?> Add <?php }?>SubAdmins
        </h1>
        <ol class="breadcrumb">
            <li>
              <a href="<?php echo ADMIN_BASE_URL ;?>dashboard">
                 <i class="fa fa-dashboard"></i> Home</a>
            </li>           
            <li class="active">
               <a href="<?php echo ADMIN_BASE_URL ;?>subadmins">Manage SubAdmins</a>
            </li>
        </ol>
    </section>

    <section class="content clearfix">
        <div class="col-xs-12">
            <div class="row">
                <div class="box my-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            
                        </h3>
                    </div>
                        <?php if(!empty($subAdminDetail)) {
                                echo $this->Form->create($subAdminDetail, [
                                    'id' => 'permissionAddEdit',
                                    'type'  =>'file'
                                ]);
                            } else {
                                echo $this->Form->create('subadmins', [
                                    'id' => 'permissionAddEdit',
                                    'type'  =>'file'
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
                                <label for="category_name" class="col-sm-2 control-label">First Name<span class="star">*</span></label>
                                <div class="col-sm-4">
                                    <?= $this->Form->input("first_name",[
                                        "type" => "text",
                                        "class" => "form-control",
                                        "id" => 'first_name',
                                        "div" => false,
                                        "placeholder" => 'Enter Sub Username',
                                        'value' => isset($subAdminDetail['first_name']) ? $subAdminDetail['first_name'] : '',
                                        "label" => false
                                    ]);?>
                                      <span class="firstNameErr"></span>
                                </div>
                            </div>                            
                        </div>

                        <div class="box-body">
                            <div class="form-group">
                                <label for="category_name" class="col-sm-2 control-label">Last Name<span class="star">*</span></label>
                                <div class="col-sm-4">
                                    <?= $this->Form->input("last_name",[
                                        "type" => "text",
                                        "class" => "form-control",
                                        "id" => 'last_name',
                                        "div" => false,
                                        "placeholder" => 'Enter last name',
                                        'value' => isset($subAdminDetail['last_name']) ? $subAdminDetail['last_name'] : '',
                                        "label" => false
                                    ]);?>
                                      <span class="lastNameErr"></span>
                                </div>
                            </div>
                        </div>

                        <div class="box-body">
                            <div class="form-group">
                                <label for="category_name" class="col-sm-2 control-label">Phone Number<span class="star">*</span></label>
                                <div class="col-sm-4">
                                    <?= $this->Form->input("phone_number",[
                                        "type" => "text",
                                        "class" => "form-control",
                                        "id" => 'phone_number',
                                        "maxlength" => 11,
                                        "onkeypress" => 'return isNumberKey(event)',
                                        "div" => false,
                                        "placeholder" => 'Enter Phone number',
                                        'value' => isset($subAdminDetail['phone_number']) ? $subAdminDetail['phone_number'] : '',
                                        "label" => false
                                    ]);?>
                                    <span class="phoneErr"></span>
                                </div>
                            </div>
                        </div>

                        <div class="box-body">
                            <div class="form-group">
                                <label for="category_name" class="col-sm-2 control-label">Address<span class="star">*</span></label>
                                <div class="col-sm-4">
                                    <?= $this->Form->input("address",[
                                        "type" => "text",
                                        "class" => "form-control",
                                        "id" => 'contact_address',
                                        "div" => false,
                                        "placeholder" => 'Enter Address',
                                        'value' => isset($subAdminDetail['address']) ? $subAdminDetail['address'] : '',
                                        "label" => false
                                    ]);?>
                                    <span class="addressErr"></span>
                                </div>
                            </div>
                        </div>

                        <div class="box-body">
                            <div class="form-group">
                                <label for="category_name" class="col-sm-2 control-label">Subadmin Gmail<span class="star">*</span></label>
                                <div class="col-sm-4">
                                    <?= $this->Form->input("username",[
                                        "type" => "text",
                                        "class" => "form-control",
                                        "id" => 'username',
                                        "div" => false,
                                        "placeholder" => 'Enter Sub Username',
                                        'value' => isset($username) ? $username : '',
                                        "label" => false
                                    ]);?>
                                      <span class="userNameErr"></span>
                                </div>
                            </div>
                        </div>
                        <?php if(empty($subAdminDetail)) {?>
                        <div class="box-body">
                            <div class="form-group">
                                <label for="password" class="col-sm-2 control-label">Subadmin Password<span class="star">*</span></label>
                                <div class="col-sm-4">
                                    <?= $this->Form->input("password",[
                                        "type" => "password",
                                        "class" => "form-control",
                                        "id" => 'password',
                                        "div" => false,
                                        "placeholder" => 'Enter Sub Password',
                                        "label" => false
                                    ]);?>
                                      <span class="passwordErr"></span>    
                                </div>
                            </div>                            
                        </div>
                        <?php } ?>
                         <div class="box-body">
                            <div class="col-sm-12 col-sm-offset-3">
                                <ul class="category clearfix">
                                    <?php foreach ($module as $key => $val) { ?>
                                    <li>
                                        <span class="folder-add">-</span>
                                        <a>
                                    <input type="checkbox" name="category[]" id="<?php echo 'category'.$val['id'];?>" value="<?=  $val['id'] ?>" <?php echo (in_array($val['id'], $subAdminPermission)) ? 'checked' : ''?>>
                                            <label for="<?php echo 'category'.$val['id'];?>"><?= $val['modulesname'] ?>
                                            </label>
                                        </a>
                                        <ul class="subcategory">
                                            <?php foreach ($val['subModules'] as $keys => $values) { ?>
                                            <li>
                                                <a>
                                                    <input type="checkbox"
                                                           name="category[]"
                                                           id="<?php echo 'subcategory'.$values['id'];?>"
                                                           value="<?= $values['id'] ?>" <?php echo (in_array($values['id'], $subAdminPermission)) ? 'checked' : ''?>>
                                                    <label for="<?php echo 'subcategory'.$values['id'];?>">
                                                        <?= $values['modulesname'] ?></label>
                                                </a>
                                            </li>
                                            <?php } ?>
                                        </ul>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                        <div class="col-xs-12 no-padding m-t-20">
                             <button type="button" class="btn btn-info m-r-15" onclick="permissionsAddEdit();">Submit</button>
                            <a class="btn btn-default" href="<?php echo ADMIN_BASE_URL?>subadmins/">Cancel</a>
                        </div>
                    <?= $this->Form->end();?>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }

    function isValid(mailAddress){
        var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
        return pattern.test(mailAddress);
    }

    function permissionsAddEdit(){
        $(".error").html('');
        var editid = $('#editid').val();
        var first_name = $('#first_name').val();
        var last_name = $('#last_name').val();
        var contact_address = $('#contact_address').val();
        var phone_number = $('#phone_number').val();
        var username = $('#username').val();
        var password = $("#password").val();
        var editid       = $.trim($("#editid").val());       

        if(first_name == '') {
            $('#first_name').after('<label class="error">Please enter firstname</label>');
            $('#first_name').focus();
            return false;
        }else if(last_name == '') {
            $('#last_name').after('<label class="error">Please enter lastname</label>');
            $('#last_name').focus();
            return false;
        }else if(phone_number == '') {
            $('#phone_number').after('<label class="error">Please enter phone number</label>');
            $('#phone_number').focus();
            return false;
        }else if(contact_address == '') {
            $('#contact_address').after('<label class="error">Please enter contact address</label>');
            $('#contact_address').focus();
            return false;
        }else if(username == '') {
            $('#username').after('<label class="error">Please enter username</label>');
            $('#username').focus();
            return false;
        } else if(username != '' && !isValid(username)) {
            $('#username').after('<label class="error">Please enter valid email</label>');
            $("#username").focus();
            return false;
        } else if(password == '') {
            $('#password').after('<label class="error">Please enter password</label>');
            $('#password').focus();
            return false;
        }else {
            $.ajax({
                type   : 'POST',
                url    : jssitebaseurl+'subadmins/checkUsername',
                data   : {id:editid, username:username},
                success: function(data){
                    if($.trim(data) == 0) {
                        $("#permissionAddEdit").submit();
                    }else {
                        $('#username').after('<label class="error">This username already exists</label>');
                        $('#username').focus();
                        return false;
                    }
                }
            });
            return false;

        }
    }
</script>
