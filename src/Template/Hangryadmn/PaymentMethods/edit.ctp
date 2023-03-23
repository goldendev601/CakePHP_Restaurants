<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Payment Method
        </h1>
        <ol class="breadcrumb">
            <li>
              <a href="<?php echo ADMIN_BASE_URL ;?>dashboard">
                 <i class="fa fa-dashboard"></i> Home</a>
            </li>           
            <li class="active">
               <a href="<?php echo ADMIN_BASE_URL ;?>PaymentMethods">Manage Payment Method</a>
            </li>
        </ol>
    </section>

    <section class="content clearfix">
        <div class="col-xs-12">
            <div class="row">
                <div class="box my-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            Edit Payment Method
                        </h3>
                    </div>
                        <?php
                            if(!empty($paymentDetail)) {
                                echo $this->Form->create($paymentDetail, [
                                    'id' => 'paymentEditForm',
                                    'type' => 'file'                               
                                ]);                                                          
                             } 
                            echo $this->Form->input('editId',[
                                'type' => 'hidden',
                                'id'   => 'editId',
                                'class' => 'form-control',
                                'value' => !empty($id) ? $id : '',
                                'label' => false
                            ]);                         
                        ?>                    
                        <div class="box-body">
                            <div class="form-group">
                                <label for="paymentMethodName" class="col-sm-3 control-label">Payment Method Name<span class="star">*</span></label>
                                <div class="col-sm-4">
                                    <?= $this->Form->input('paymentMethodName',[
                                            'type' => 'text',
                                            'id'   => 'paymentMethodName',
                                            'class' => 'form-control',
                                            'placeholder' => 'Please Enter Payment Method Name',
                                            'value' => isset($paymentDetail['payment_method_name']) ? $paymentDetail['payment_method_name'] : '',
                                            'label' => false
                                        ]) ?>
                                      <span class="paymentMethodNameErr"></span>    
                                </div>
                            </div>                            
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label for="paymentMethodImage" class="col-sm-3 control-label">Payment Methos Image<span class="star">*</span></label>
                                <div class="col-sm-6">
                                    <input type="hidden" id="paymentMethodImageValue" value="<?php echo $paymentDetail['payment_method_image'];?>">
                                    <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                        <div class="form-control" data-trigger="fileinput"><i class="glyphicon glyphicon-file fileinput-exists"></i>
                                            <span class="fileinput-filename payment_method_image"><?php echo $paymentDetail['payment_method_image'];?></span>
                                        </div>
                                        <span class="input-group-addon btn btn-default btn-file">
                                            <span class="fileinput-new">Select file</span>
                                            <span class="fileinput-exists">Change</span>
                                            <input type="file" id="paymentMethodImage" name="paymentMethodImage" value="<?php echo $paymentDetail['payment_method_image'];?>">
                                        </span>
                                        <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remove
                                        </a>
                                    </div>
                                    <span class="paymentMethodImageErr"></span>
                                </div>
                                <div class="col-sm-2">
                                    <img src="<?php echo PAYMENTS_LOGO_URL. DS .$paymentDetail['payment_method_image'] ?>" alt="No image" class="img-responsive img-rounded" width="50"/>
                                </div>
                            </div>                            
                        </div>
                        <div class="col-xs-12 no-padding m-t-20">
                            <button type="button" class="btn btn-info m-r-15" onclick="return paymentAddEdit();">Submit</button>
                            <a class="btn btn-default" href="<?php echo ADMIN_BASE_URL?>PaymentMethods/">Cancel</a>
                        </div>
                    <?= $this->Form->end();?>
                </div>
            </div>
        </div>
    </section>
</div>


<script type="text/javascript">

    function paymentAddEdit(){
        var paymentMethodName = $.trim($("#paymentMethodName").val());
        var paymentMethodImage = $("#paymentMethodImageValue").val();
        $('.error').html('');
        if(paymentMethodName == '') {
            $('.paymentMethodNameErr').addClass('error').html('Please Enter Payment Method Name');
            $("#paymentMethodName").focus();
            return false;
        } else if(paymentMethodImage == ''){
            $('.paymentMethodImageErr').addClass('error').html('Please select Payment Method Image');
            $("#paymentMethodImage").focus();
            return false;
        } else {
            $("#paymentEditForm").submit();
            return false;
        }
    }

</script>