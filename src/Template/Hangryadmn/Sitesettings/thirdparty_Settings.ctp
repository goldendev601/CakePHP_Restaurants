<div class="content-wrapper">
	<section class="content-header">
		<h1>
			ThirdParty Settings
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> ThirdParty Settings</a></li>
			<li class="active">Settings</li>
		</ol>
	</section>
	<section class="content clearfix">
		<div class="col-xs-12">
			<div class="row">
				<div class="col-xs-12 no-padding">
					<div class="nav-tabs-custom">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#sms" data-toggle="tab">SMS</a></li>
							<li ><a href="#facebook" data-toggle="tab">Facbook</a></li>
							<li ><a href="#google" data-toggle="tab">Google+</a></li>
							<li><a href="#googlekey" data-toggle="tab">Googleapi Key</a></li>
							<li><a href="#mailchimp" data-toggle="tab">Mailchimp</a></li>
							<li><a href="#pusher" data-toggle="tab">Pusher Notification</a></li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane active" id="sms">
								<?php 
									if(!empty($EditThirdPartyList)){
	                            		echo $this->Form->create($EditThirdPartyList,[
											'id' => 'thirdPartyForm',
											'onsubmit'=> 'return thirdPartySettingsValid()'
										]); 
			                        }else{
			                            echo $this->Form->create('thirdPartySetting',[
											'id' => 'thirdPartyForm',
											'onsubmit'=> 'return thirdPartySettingsValid()'
										]); 
	                        		}
	                        	?>
	                        	<?= $this->Form->input('userId',[
		                            'type' => 'hidden',
		                            'id'   => 'editid',
		                            'value' => !empty($EditThirdPartyList['id']) ? $EditThirdPartyList['id'] : ''
                        		]) ?>
								<div class="form-group clearfix">
									<label class="col-md-3 control-label">Sms Option</label>
									<div class="col-md-6 col-lg-4">
										<div class="radio-list">
											<label class="radio-inline smtp"> <?php 
											$sms_option_yes = ['Yes' => 'Yes'];
											$sms_option_no = ['No' => 'No'];
	                                         echo $this->Form->radio('sms_option',
	                                         	$sms_option_yes,
                      							['checked'=>$sms_option_yes,
                      								'label'=>false,
                      								'legend'=>false,
                      								//'checked' => 'checked',
                      								'hiddenField'=>false]); ?> 
                                            Yes</label>
                                            <label class="radio-inline smtp">  <?= 
	                                            $this->Form->radio('sms_option',$sms_option_no,
                           							['checked'=>$sms_option_no,
                           								'label'=>false,
                           								'legend'=>false,
                           								'hiddenField'=>false]); ?>  
			                               No</label>
										</div>
									</div>
								</div>
								<div id="yes">
									<div class="form-group clearfix">
										<label class="col-md-3 control-label">SMS Token</label>
										<div class="col-md-6 col-lg-4"> <?=
											$this->Form->input('sms_token',
									                            ['class' => 'form-control',
									                             'label' => false]); ?>
									        <label id="smsError" class="error"></label>
										</div>
									</div>	
									<div class="form-group clearfix">
										<label class="col-md-3 control-label">Sms SID</label>
										<div class="col-md-6 col-lg-4"> <?=
											 $this->Form->input('sms_id',
									                            ['class' => 'form-control',
									                            	  'type'  => 'text',
									                                  'label' => false]); ?>
									        <label id="smsError1" class="error"></label>
										</div>
									</div>	
									<div class="form-group clearfix">
										<label class="col-md-3 control-label">Source Number</label>
										<div class="col-md-6 col-lg-4"> <?php
											echo $this->Form->input('sms_source_number',
									                            ['class' => 'form-control',
									                                  'label' => false]);
									        echo $this->Form->hidden('id'); ?>
									        <label id="smsError2" class="error"></label>
										</div>
									</div>
								</div>	
							</div>
							<!-- Facebook -->
							<div class="tab-pane" id="facebook">
								<div class="form-group clearfix">
									<label class="col-md-3 control-label">Api id</label>
									<div class="col-md-6 col-lg-4"> <?=
										$this->Form->input('facebook_api_id',
											['class' => 'form-control',
												'type'  => 'text',
												'label' => false]); ?>
										<label id="facebookError" class="error"></label>		
									</div>
								</div>
								<div class="form-group clearfix">
									<label class="col-md-3 control-label">Secret key</label>
									<div class="col-md-6 col-lg-4"> <?=
										$this->Form->input('facebook_secret_key',
											['class' => 'form-control',
												'type'  => 'text',
												'label' => false]); ?>
										<label id="facebookError1" class="error"></label>
									</div>
								</div>
							</div>
							<!-- Google+ key -->
							<div class="tab-pane" id="google">
								<div class="form-group clearfix">
									<label class="col-md-3 control-label">Api id</label>
									<div class="col-md-6 col-lg-4"> <?=
										$this->Form->input('google_api_id',
											['class' => 'form-control',
												'type'  => 'text',
												'label' => false]); ?>
										<label id="googleError" class="error"></label>		
									</div>
								</div>
								<div class="form-group clearfix">
									<label class="col-md-3 control-label">Secret key</label>
									<div class="col-md-6 col-lg-4"> <?=
										$this->Form->input('google_secret_key',
											['class' => 'form-control',
												'type'  => 'text',
												'label' => false]); ?>
										<label id="googleError1" class="error"></label>		
									</div>
								</div>
							</div>
							<div class="tab-pane" id="googlekey">
								<?php
									for($i = 1;$i <= 5;$i++) { ?>
										<div class="form-group clearfix">
											<label class="col-md-3 control-label">Google key <?php echo $i;?></label>
											<div class="col-md-6 col-lg-4"> <?=
												$this->Form->input('google_key'.$i,
													['class' => 'form-control',
														'type'  => 'text',
														'label' => false]); ?>
											</div>
										</div>
								<?php } ?>
								<label id="keyError" class="error"></label>
							</div>
							<div class="tab-pane" id="mailchimp">
								<div class="form-group clearfix">
									<label class="col-md-3 control-label">Key</label>
									<div class="col-md-6 col-lg-4"> <?=
										 $this->Form->input('mailchimp_key',
											['class' => 'form-control',
												'type'  => 'text',
												'label' => false]); ?>
										<label id="mailchimpError" class="error"></label>		
									</div>
								</div>
								<div class="form-group clearfix">
									<label class="col-md-3 control-label">List id</label>
									<div class="col-md-6 col-lg-4"> <?=
										 $this->Form->input('mailchimp_list_id',
											['class' => 'form-control',
												'type'  => 'text',
												'label' => false]); ?>
										<label id="mailchimpError1" class="error"></label>		
									</div>
								</div>
							</div>
							<div class="tab-pane" id="pusher">
								<div class="form-group clearfix">
									<label class="col-md-3 control-label">Pusher Key</label>
									<div class="col-md-6 col-lg-4"> <?=
										 $this->Form->input('pusher_key',
											['class' => 'form-control',
												'type'  => 'text',
												'label' => false]); ?>
										<label id="pusherError" class="error"></label>		
									</div>
								</div>
								<div class="form-group clearfix">
									<label class="col-md-3 control-label">Pusher Secret Key</label>
									<div class="col-md-6 col-lg-4"> <?=
										$this->Form->input('pusher_secret',
											['class' => 'form-control',
												'type'  => 'text',
												'label' => false]); ?>
										<label id="pusherError1" class="error"></label>		
									</div>
								</div>
								<div class="form-group clearfix">
									<label class="col-md-3 control-label">App id</label>
									<div class="col-md-6 col-lg-4"> <?=
										 $this->Form->input('pusher_id',
											['class' => 'form-control',
												'type'  => 'text',
												'label' => false]); ?>
										<label id="pusherError2" class="error"></label>		
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xs-12 no-padding m-t-20">
						<button class="btn btn-info m-r-15" type="submit">Submit</button>
						<a href="<?= ADMIN_BASE_URL?>sitesettings/thirdpartySettings" class="btn btn-default">Cancel</a>
					</div>
				<?=  $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</section>
</div>


<script type="text/javascript">
function thirdPartySettingsValid() {
	
    
   var SmsToken         = $.trim($("#sms-token").val());
   var SmsId            = $.trim($("#sms-id").val());
   var SmsSourceNumber  = $.trim($("#sms-source-number").val());

   var mailchimpkey   = $.trim($("#mailchimp-key").val());
   var mailchimplist  = $.trim($("#mailchimp-list-id").val());

   var facbookapi     = $.trim($("#facebook-api-id").val());
   var facbooksecret  = $.trim($("#facebook-secret-key").val());

   var googleapi      = $.trim($("#google-api-id").val());
   var googlesecret   = $.trim($("#google-secret-key").val());

   var PusherKey      = $.trim($("#pusher-key").val());
   var PusherSecret   = $.trim($("#pusher-secret").val());
   var PusherId       = $.trim($("#pusher-id").val());

   var GoogleKey1      = $.trim($("#google-key1").val());

    $("#smsError").html("");
    $("#smsError1").html("");
    $("#smsError2").html("");
    $("#facebookError").html("");
    $("#facebookError").html("");
    $("#facebookError1").html("");
    $("#googleError").html("");
    $("#googleError1").html("");
    $("#keyError").html("");
    $("#mailchimpError").html("");
    $("#mailchimpError1").html("");
    $("#pusherError").html("");
    $("#pusherError1").html("");
    $("#pusherError2").html("");

    if ($("#sms-option-yes").is(":checked")) {
        
        if(SmsToken == ''){
	        $('.nav-tabs a[href="#sms"]').tab('show');
	        $("#smsError").html("Please enter sms token id");
	        $("#sms-token").focus();
        	return false;       
	    }else if(SmsId == ''){
	        $('.nav-tabs a[href="#sms"]').tab('show');
	        $("#smsError1").html("Please enter sms auth id");
	        $("#sms-id").focus();
	        return false;       
	    }else if(SmsSourceNumber == ''){
	        $('.nav-tabs a[href="#sms"]').tab('show');
	        $("#smsError2").html("Please enter sms source number");
	        $("#sms-source-number").focus();
	        return false;       
	    }
	}
    if(facbookapi == ''){
        $('.nav-tabs a[href="#facebook"]').tab('show');
        $("#facebookError").html("Please enter facebook api key");
        $("#facebook-api-id").focus();
        return false;
    }else if(facbooksecret == ''){
        $('.nav-tabs a[href="#facebook"]').tab('show');
        $("#facebookError1").html("Please enter facebook secret key");
        $("#facebook-secret-key").focus();
        return false;
    }else if(googleapi == ''){
        $('.nav-tabs a[href="#google"]').tab('show');
        $("#googleError").html("Please enter google api key");
        $("#google-api-id").focus();
        return false;
    }else if(googlesecret == ''){
        $('.nav-tabs a[href="#google"]').tab('show');
        $("#googleError1").html("Please enter google secret key");
        $("#google-secret-key").focus();
        return false;
    }else if(GoogleKey1 == ''){
        $('.nav-tabs a[href="#googlekey"]').tab('show');
        $("#keyError").html("Please enter google key");
        $("#google-key").focus();
        return false;
    }else if(mailchimpkey == ''){
        $('.nav-tabs a[href="#mailchimp"]').tab('show');
        $("#mailchimpError").html("Please enter mailchimp key");
        $("#mailchimp-key").focus();
        return false;
    }else if(mailchimplist == ''){
        $('.nav-tabs a[href="#mailchimp"]').tab('show');
        $("#mailchimpError1").html("Please enter mailchimp list");
        $("#mailchimp-list-id").focus();
        return false;
    }else if(PusherKey == ''){
        $('.nav-tabs a[href="#pusher"]').tab('show');
        $("#pusherError").html("Please enter pusher key");
        $("#pusher-key").focus();
        return false;
    }else if(PusherSecret == ''){
        $('.nav-tabs a[href="#pusher"]').tab('show');
        $("#pusherError1").html("Please enter pusher secret key");
        $("#pusher-secret").focus();
        return false;
    }else if(PusherId == ''){
        $('.nav-tabs a[href="#pusher"]').tab('show');
        $("#pusherError2").html("Please enter pusher app id");
        $("#pusher-id").focus();
        return false;
    } 
}
</script>
