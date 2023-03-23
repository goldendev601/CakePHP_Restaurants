<div class="content-wrapper">
    <section class="content-header">
        <h1> Edit Driver </h1>        
        <ol class="breadcrumb">
            <li>
              <a href="<?php echo ADMIN_BASE_URL ;?>dashboard">
                 <i class="fa fa-dashboard"></i> Home</a>
            </li>           
            <li class="active">
               <a href="<?php echo ADMIN_BASE_URL ;?>drivers">Manage Driver</a>
            </li>
        </ol>
    </section>

    <section class="content clearfix">
        <div class="col-xs-12">
            <div class="row">
                <div class="box my-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <!-- Driver -->
                        </h3>
                    </div>
                        <?php
                            if(!empty($driverList)) {
                                echo $this->Form->create($driverList,[
                                    'id' => 'driverEditForm'                               
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
                                <label for="driver_name" class="col-sm-2 control-label">Driver Name<span class="star">*</span></label>
                                <div class="col-sm-4">
                                    <?= $this->Form->input('driver_name',[
                                            'type' => 'text',
                                            'id'   => 'driver_name',
                                            'class' => 'form-control',
                                            'placeholder' => 'Driver Name',
                                            'label' => false
                                        ]) ?>
                                      <span class="driverErr"></span>    
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
                                            'maxlength' => 15,
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

                        <div class="box-body">
                            <div class="form-group">
                                <label for="vechile_name" class="col-sm-2 control-label">Vechile Name<span class="star">*</span></label>
                                <div class="col-sm-4">
                                    <?= $this->Form->input('vechile_name',[
                                            'type' => 'text',
                                            'id'   => 'vechile_name',
                                            'class' => 'form-control',
                                            'placeholder' => 'Vechile Name',
                                            'label' => false
                                        ]) ?>
                                      <span class="vechileErr"></span>    
                                </div>
                            </div>                            
                        </div>

                        <!--<div class="box-body">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Invoice</label>
                                <div class="col-sm-4">
                                    <label class="radio-inline margin-r-15">
                                        <input id="weekly" type="radio" checked="checked" name="invoice" class="" value="weekly" <?php /*if(!empty($driverList['invoice'])) {echo ($driverList['invoice'] == 'weekly') ? 'checked' : '' ; }*/?>>
                                        Weekly
                                    </label>
                                    <label class="radio-inline no-padding-left margin-r-15">
                                        <input id="daily" type="radio" name="invoice" class="" value="daily" <?php /*if(!empty($driverList['invoice'])) {echo ($driverList['invoice'] == 'daily') ? 'checked' : '' ; }*/?>>
                                        Daily
                                    </label>
                                    <span class="invoiceErr"></span>
                                </div>
                            </div>
                        </div>-->

                        <div class="box-body">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Payout</label>
                                <div class="col-sm-4">
                                    <label class="radio-inline margin-r-15">
                                        <input id="perorder" type="radio" name="payout" class="" value="perorder" <?php if(!empty($driverList['payout'])) {echo ($driverList['payout'] == 'perorder') ? 'checked' : '' ; }?>>
                                        Perorder
                                    </label>
                                    <label class="radio-inline no-padding-left">
                                        <input id="distance" type="radio" name="payout" class="" value="distance" <?php if(!empty($driverList['payout'])) {echo ($driverList['payout'] == 'distance') ? 'checked' : '' ; }?>>
                                        Distance
                                    </label>
                                    <span class="payoutErr"></span>
                                </div>
                            </div>
                        </div>

                        <div class="box-body">
                            <div class="form-group">
                                <label for="payout_amount" class="col-sm-2 control-label">Payout Amount<span class="star">*</span></label>
                                <div class="col-sm-4">
                                    <?= $this->Form->input('payout_amount',[
                                        'type' => 'text',
                                        'id'   => 'payout_amount',
                                        'class' => 'form-control',
                                        'placeholder' => 'Payout Amount',
                                        'label' => false
                                    ]) ?>
                                    <span class="payoutAmountErr"></span>
                                </div>
                                <span class="disHide" style="<?php echo ($driverList['payout'] == 'distance') ? 'display:block': 'display:none' ?>">Per Miles</span>
                            </div>
                        </div>

                        <div class="box-body">
                            <div class="form-group">
                                <div class="col-sm-4">
                                    <span>Note Invoice Report daily</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 no-padding m-t-20">
                             <button type="button" class="btn btn-info m-r-15" onclick="driverAddEdit();">Submit</button>
                            <a class="btn btn-default" href="<?php echo ADMIN_BASE_URL?>drivers/">Cancel</a>
                            
                        </div>
                    <?= $this->Form->end();?>
                </div>
            </div>
        </div>
    </section>
</div>


<script type="text/javascript">

    $(document).ready(function(){

        $("input[name='payout']").on('click', function (){
            var val = $(this).val();
            if (val != 'distance') {
                $( ".disHide" ).hide();
            } else {
                $(".disHide").show();
            }
        });
    });

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


    function driverAddEdit(){

        var driver_name  = $.trim($("#driver_name").val());  
        var phone_number = $.trim($("#phone_number").val()); 
        var username     = $.trim($("#username").val());  
        var vechile_name = $.trim($("#vechile_name").val());    
        var payout       = $.trim($("input[name='payout']:checked").val());
        var payout_amount = $.trim($("#payout_amount").val());
        //var invoice       = $.trim($("input[name='invoice']:checked").val());
        var editid       = $.trim($("#editid").val());  
        
        $('.error').html('');

        if(driver_name == '') {
            $('.driverErr').addClass('error').html('Please enter driver name');
            $("#driver_name").focus();
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
        }else if(vechile_name == '') {
            $('.vechileErr').addClass('error').html('Please enter vechile name');
            $("#vechile_name").focus();
            return false;
        }else if(payout == '') {
            $('.payoutErr').addClass('error').html('Please enter choose payout');
            $("#perorder").focus();
            return false;
        }else if(payout_amount == '') {
            $('.payoutAmountErr').addClass('error').html('Please enter payout amount');
            $("#payout_amount").focus();
            return false;
        }/*else if(invoice == '') {
            $('.invoiceErr').addClass('error').html('Please enter choose invoice');
            $("#weekly").focus();
            return false;
        }*/else {
            $.ajax({
                type   : 'POST',
                url    : jssitebaseurl+'drivers/driverCheck',
                data   : {id: editid,phone_number:phone_number},
                success: function(data){
                    if($.trim(data) == 0) {
                        $("#driverEditForm").submit();
                    }else {
                        $(".phoneErr").addClass('error').html('This phone number already exists');
                        $("#phone_number").focus();
                        return false;
                    }
                }
            });
           return false;
        }
    }    
</script>