<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Payment Settings
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Payment Settings</a></li>
			<li class="active">Settings</li>
		</ol>
	</section>
	<section class="content clearfix">
		<div class="col-xs-12">
			<div class="row">
				<div class="col-xs-12 no-padding">
                    <?php
                    if(!empty($EditPaymentList)){
                        echo $this->Form->create($EditPaymentList,[
                            'id' => 'siteForm',
                            'onsubmit'=> 'return paymentSettingvalidate()'
                        ]);
                    }else{
                        echo $this->Form->create('paymentSetting',[
                            'id' => 'siteForm',
                            'onsubmit'=> 'return paymentSettingvalidate()'
                        ]);
                    }
                    ?>
                    <?= $this->Form->input('userId',[
                        'type' => 'hidden',
                        'id'   => 'editid',
                        'value' => !empty($EditPaymentList['id']) ? $EditPaymentList['id'] : ''
                    ]) ?>
                    <div class="nav-tabs-custom">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#stripe" data-toggle="tab">Stripe</a></li>
							<li class=""><a href="#paypal" data-toggle="tab">Paypal</a></li>
						</ul>
						<div class="tab-content">

                            <div class="tab-pane active" id="stripe">
								<div class="stripeDiv">
									<div class="form-group clearfix">
										<label class="col-md-3 control-label">Stripe Mode <span class="star">*</span></label>
										<div class="col-md-6 col-lg-4">		
											<div class="radio-list">			
												<label class="radio-inline"> <?php 
					                                    $option3 = array('Live'  => 'Live Mode');
					                                    $option4 = array('Test'   => 'Test Mode');
					                                   	echo $this->Form->radio('stripe_mode',$option3,
					                           							['checked'=>$option3,
					                           								'label'=>false,
					                           								'checked'=>'checked',
					                           								'legend'=>false,                        
					                           								'hiddenField'=>false]); ?>
					                            Live</label>
												<label class="radio-inline"><?php
					                                echo  $this->Form->radio('stripe_mode',$option4,
					                           							['checked'=>$option4,
					                           								'label'=>false,
					                           								'legend'=>false,
					                           								'hiddenField'=>false]); 
					                           		echo  $this->Form->hidden('id');?>
					                           Test </label>
					                        </div>							
										</div>
									</div>
									<div id="Test">
										<div class="form-group clearfix">
											<label class="col-md-3 control-label">Stripe Test Secret Key <span class="star">*</span></label>
											<div class="col-md-6 col-lg-4"><?=
	                    						$this->Form->input('stripe_secretkeyTest',
	                    										['class'=>'form-control',
	                    											  'autocomplete' => 'off',
	                                                                  'label' => false,
	                    											  'div' => false]); ?>
	                    						<label id="stripeError" class="error"></label>
											</div>
										</div>	
										<div class="form-group clearfix">
											<label class="col-md-3 control-label">Stripe Test Publish Key <span class="star">*</span></label>
											<div class="col-md-6 col-lg-4"><?=
	                    						$this->Form->input('stripe_publishkeyTest',
	                    										['class'=>'form-control',
	                    											  'autocomplete' => 'off',
	                                                                  'label' => false,
	                    											  'div' => false]); ?>
	                    						<label id="stripeError1" class="error"></label>
											</div>
										</div>
									</div>
									<div id="Live">
										<div class="form-group clearfix">
											<label class="col-md-3 control-label">Stripe Live Secret Key <span class="star">*</span></label>
											<div class="col-md-6 col-lg-4"><?=
	                    					$this->Form->input('stripe_secretkey',
	                    										['class'=>'form-control',
	                    											  'autocomplete' => 'off',
	                                                                  'label' => false,
	                    											  'div' => false]); ?>
	                    						<label id="stripeError2" class="error"></label>
											</div>
										</div>	
										<div class="form-group clearfix">
											<label class="col-md-3 control-label">Stripe Live Publish Key <span class="star">*</span></label>
											<div class="col-md-6 col-lg-4"><?=
	                    						 $this->Form->input('stripe_publishkey',
	                    										['class'=>'form-control',
	                    											  'autocomplete' => 'off',
	                                                                  'label' => false,
	                    											  'div' => false]); ?>
	                    						<label id="stripeError3" class="error"></label>
											</div>
										</div>
									</div>
								</div>
							</div>
                            <div class="tab-pane" id="paypal">
                                <div class="paypalDiv">
                                    <div class="form-group clearfix">
                                        <label class="col-md-3 control-label">Paypal Mode <span class="star">*</span></label>
                                        <div class="col-md-6 col-lg-4">
                                            <div class="radio-list">
                                                <label class="radio-inline"> <?php
                                                    $option1 = array('Live'  => 'Live Mode');
                                                    $option2 = array('Test'   => 'Test Mode');
                                                    echo $this->Form->radio('paypal_mode',$option1,
                                                        ['checked'=>$option1,
                                                            'label'=>false,
                                                            'checked'=>'checked',
                                                            'legend'=>false,
                                                            'hiddenField'=>false]); ?>
                                                    Live</label>
                                                <label class="radio-inline"><?php
                                                    echo  $this->Form->radio('paypal_mode',$option2,
                                                        ['checked'=>$option2,
                                                            'label'=>false,
                                                            'legend'=>false,
                                                            'hiddenField'=>false]);
                                                    echo  $this->Form->hidden('id');?>
                                                    Test </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="TestPaypal">
                                        <div class="form-group clearfix">
                                            <label class="col-md-3 control-label">Paypal Client ID <span class="star">*</span></label>
                                            <div class="col-md-6 col-lg-4"><?=
                                                $this->Form->input('test_clientid',
                                                    ['class'=>'form-control',
                                                        'autocomplete' => 'off',
                                                        'id' => 'test_clientid',
                                                        'label' => false,
                                                        'div' => false]); ?>
                                                <label id="paypalError" class="error"></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="LivePaypal">
                                        <div class="form-group clearfix">
                                            <label class="col-md-3 control-label">Paypal Client ID <span class="star">*</span></label>
                                            <div class="col-md-6 col-lg-4"><?=
                                                $this->Form->input('live_clientid',
                                                    ['class'=>'form-control',
                                                        'autocomplete' => 'off',
                                                        'id' => 'live_clientid',
                                                        'label' => false,
                                                        'div' => false]); ?>
                                                <label id="paypalError2" class="error"></label>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
						</div>
					</div>
					<div class="col-xs-12 no-padding">
                        <button class="btn btn-info m-r-15" type="submit">Submit</button>
                        <a href="<?= ADMIN_BASE_URL?>sitesettings/paymentSettings" class="btn btn-default">Cancel</a>
                        </div>
                    <?=  $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</section>
</div>



<script>
function paymentSettingvalidate () {

    var StripeSecretkey    = $.trim($('#stripe-secretkey').val());
    var StripePublishkey   = $.trim($('#stripe-publishkey').val());

    var StripeSecretkeyTest  = $.trim($('#stripe-secretkeytest').val());
    var StripePublishkeyTest = $.trim($('#stripe-publishkeytest').val());

    var test_clientid    = $.trim($('#test_clientid').val());
    var live_clientid   = $.trim($('#live_clientid').val());

    $("#stripeError").html("");
    $("#stripeError1").html("");
    $("#stripeError2").html("");
    $("#stripeError3").html("");


    if ($("#stripe-mode-live").is(":checked")) {
        if(StripeSecretkey == ''){
          $("#stripeError2").html("Please enter stripe secret key");
          $("#stripe-secretkey").focus();
          return false;
        }else if(StripePublishkey == ''){
          $("#stripeError3").html("Please enter stripe publish key");
          $("#stripe-publishkey").focus();
          return false;
        }
    }else {
        if(StripeSecretkeyTest == ''){
          $("#stripeError").html("Please enter stripe secret key");
          $("#stripe-secretkeytest").focus();
          return false;
        }else if(StripePublishkeyTest == ''){
          $("#stripeError1").html("Please enter stripe publish key");
          $("#stripe-publishkeytest").focus();
          return false;
        }
    }

    if ($("#paypal-mode-live").is(":checked")) {
        $("#paypalError2").html("Please enter paypal live client id");
        $("#live_clientid").focus();
        return false;
    } else {
         $("#paypalError").html("Please enter paypal test client id");
         $("#test_clientid").focus();
         return false;
    }
}
</script>