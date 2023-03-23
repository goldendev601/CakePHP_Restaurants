<div class="content-wrapper">
	<section class="content-header">
		<h1> Site Settings </h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Site Settings</a></li>
			<li class="active">Settings</li>
		</ol>
	</section>

	<section class="content clearfix">
		<div class="col-xs-12">
			<div class="row">
				<div class="">
                    <?php
                        if(!empty($EditSettingsList)){
                            echo $this->Form->create($EditSettingsList,[
                                'id' => 'siteForm',
                                'enctype'  =>'multipart/form-data'
                            ]);
                        }else{
                            echo $this->Form->create('siteSetting',[
                                'id' => 'siteForm',
                                'enctype'  =>'multipart/form-data'
                            ]);
                        }
                        echo $this->Form->input('userId',[
                            'type' => 'hidden',
                            'id'   => 'editid',
                            'value' => !empty($EditSettingsList['id']) ? $EditSettingsList['id'] : ''
                        ]);
                    ?>
					<div class="nav-tabs-custom">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#site" data-toggle="tab">Site</a></li>
							<li id="contactInfo"><a href="#contact" data-toggle="tab">Contact</a></li>
							<li id="locationInfo"><a href="#location" data-toggle="tab">Location</a></li>
							<li><a href="#analytics" data-toggle="tab">Analytics Code</a></li>
							<li><a href="#mail" data-toggle="tab">Mail Setting</a></li>
							<li><a href="#invoice" data-toggle="tab">Invoice</a></li>
							<li><a href="#offline" data-toggle="tab">Offline</a></li>
							<li><a href="#MetaTags" data-toggle="tab">Meta Tags</a></li>
							<li><a href="#assign" data-toggle="tab">Order Assign</a></li>
							<li><a href="#Language" data-toggle="tab">Language</a></li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane active" id="site">
								<div class="form-group clearfix">
									<label class="col-md-3 control-label">Site Name <span class="star">*</span></label>
									<div class="col-md-6 col-lg-4">
										<?= $this->Form->input('site_name',[
											'class' => 'form-control',
											'label' => false]);
										 ?>
										<label id="siteError" class="error"></label>
									</div>
								</div>
								<div class="form-group clearfix">
									<label class="col-md-3 control-label">Logo </label>
									<div class="col-md-6 col-lg-4"> 
                        				<?= $this->Form->input('site_logo',
                        								['label' => false,
                        									  'class' => 'required',
                                                              'value' =>  $EditSettingsList['site_logo'],
                        									  'type'  => 'file']); ?>
                        				<div class="col-sm-4 backlogocol margin-t-10 no-padding">
                        					<img width="100" height="100" src="<?php echo DRIVERS_LOGO_URL.'/uploads/siteImages/siteLogo/'.$EditSettingsList['site_logo']; ?>">
                        				</div>
									</div>
								</div>
								<div class="form-group clearfix">
									<label class="col-md-3 control-label">Site Fav Icon </label>
									<div class="col-md-6 col-lg-4"> <?=
                        				 $this->Form->input('site_fav',
                        								['label' => false,
                        									  'class' => 'required',
                                                              'value' =>  $EditSettingsList['site_fav'],
                        									  'type'  => 'file']); ?>
                        				<div class="col-sm-4 backlogocol margin-t-10 no-padding">
                        					<img width="100" height="100" src="<?php echo DRIVERS_LOGO_URL.'/uploads/siteImages/siteFav/'.$EditSettingsList['site_fav']; ?>">
                        				</div>
									</div>
								</div>
								<div class="form-group clearfix">
									<label class="col-md-3 control-label">Address Mode <span class="star">*</span></label>
									<div class="col-md-6 col-lg-4">
										<div class="radio-list">
											<label class="radio-inline"> <?php
												$option1 = ['Google'  => 'Google'];
												$option2 = ['Normal' => 'Normal'];?>

												<?= $this->Form->radio('address_mode',$option1,
														['checked'=>$option1,
														'label'=>false,
														'legend'=>false,
														'checked' => 'checked',
														'hiddenField'=>false]); ?>
											Google</label>
											<label class="radio-inline">  <?=
												 $this->Form->radio('address_mode',$option2,
														['checked'=>$option2,
														'label'=>false,
														'legend'=>false,
														'hiddenField'=>false]); ?>
											Normal</label>
										</div>
									</div>
								</div>
								<div class="form-group clearfix" id="normal_address" style="display: none;">
									<label class="col-md-3 control-label">Search By Option <span class="star">*</span></label>
									<div class="col-md-6 col-lg-4">
										<div class="radio-list">
											<label class="radio-inline"> <?php
	                                          	$option1 = ['area' => 'Area Name'];
												$option2 = ['zip'  => 'Zipcode'];?>
            									
	                                          	<?= $this->Form->radio('search_by',$option1,
	                                          							['checked'=>$option1,
	                                          								'label'=>false,
	                                          								'legend'=>false,
	                                          								'checked' => 'checked',
	                                          								'hiddenField'=>false]); ?> 
                                            Area Name</label>
                                            <label class="radio-inline
                                            ">  <?=
	                                           	$this->Form->radio('search_by',$option2,
                               							['checked'=>$option2,
                               								'label'=>false,
                               								'legend'=>false,
                               								'hiddenField'=>false]); ?>
			                                Zipcode</label>
										</div>
									</div>
								</div>
							</div>
							<!-- contact -->
							<div class="tab-pane" id="contact">
								<div class="form-group clearfix">
									<label class="col-md-3 control-label">Admin Name <span class="star">*</span></label>
									<div class="col-md-6 col-lg-4"> <?=
										 $this->Form->input('admin_name',
								                            ['class' => 'form-control',
								                             'label' => false]); ?>
								    	<label id="contactError" class="error"></label>
									</div>
								</div>
								<div class="form-group clearfix">
									<label class="col-md-3 control-label">Admin Email <span class="star">*</span></label>
									<div class="col-md-6 col-lg-4"> <?=
										 $this->Form->input('admin_email',
								                            ['class' => 'form-control',
								                             'label' => false]); ?>
								    	<label id="contactError1" class="error"></label>
									</div>
								</div>
								<div class="form-group clearfix">
									<label class="col-md-3 control-label">Contact us Email <span class="star">*</span></label>
									<div class="col-md-6 col-lg-4"> <?=
										 $this->Form->input('contact_us_email',
								                            ['class' => 'form-control',
								                             'label' => false]); ?>
								    	<label id="contactError2" class="error"></label>
									</div>
								</div>
								<div class="form-group clearfix">
									<label class="col-md-3 control-label">Invoice Email <span class="star">*</span></label>
									<div class="col-md-6 col-lg-4"> <?=
										 $this->Form->input('invoice_email',
								                            ['class' => 'form-control',
								                                  'label' => false]); ?>
								    	<label id="contactError3" class="error"></label>
									</div>
								</div>	
								<div class="form-group clearfix">
									<label class="col-md-3 control-label">Site Contact Phone <span class="star">*</span></label>
									<div class="col-md-6 col-lg-4"> <?=
										 $this->Form->input('contact_phone',
								                            ['class' => 'form-control',
								                            'onkeypress' => 'return isNumberKey(event)',
								                            'maxlength' => 11,
								                            'label' => false]); ?>
								    	<label id="contactError4" class="error"></label>
									</div>
								</div>	
								<div class="form-group clearfix">
									<label class="col-md-3 control-label">Order Email <span class="star">*</span></label>
									<div class="col-md-6 col-lg-4"> <?=
										 $this->Form->input('order_email',
								                            ['class' => 'form-control',
								                                  'label' => false]); ?>
								    	<label id="contactError5" class="error"></label>
									</div>
								</div>	
							</div>
							<!-- location -->

							<div class="tab-pane" id="location">

								<div class="form-group clearfix">
									<label class="col-md-3 control-label">Site Address <span class="star">*</span></label>
									<div class="col-md-6 col-lg-4"> <?=
										 $this->Form->input('site_address',
								                            ['class' => 'form-control',
								                             'label' => false]); ?>
								    	<label id="locationError" class="error"></label>
									</div>
								</div>

								<div class="form-group clearfix">
									<label class="col-md-3 control-label">Site Country <span class="star">*</span></label>
									<div class="col-md-6 col-lg-4">
                                        <?= $this->Form->input('site_country',[
                                                'type'=>'select',
                                                'class'=>'form-control',
                                                'options'=> $countryList,
                                                'value' => !empty($EditSettingsList['site_country']) ? $EditSettingsList['site_country'] :  '',
                                                'onchange' => 'return getCurrency(),stateFillters()',
                                                'empty' => 'Select Country',
                                                'label'=> false
                                         ]); ?>
										<label id="locationError1" class="error"></label>	 		
									</div>
								</div>

								<div class="form-group clearfix">
									<label class="col-md-3 control-label">Site State <span class="star">*</span></label>
									<div class="col-md-6 col-lg-4">
                                        <div id="stateList">
                                            <?=	$this->Form->input('site_state',[
                                                  'type' => 'select',
                                                  'class' => 'form-control',
                                                  'empty' => 'Select State',
                                                  'options'=> $stateList,
                                                  'onchange'=> 'return cityFillters()',
                                                  'value' => !empty($EditSettingsList['site_state']) ? $EditSettingsList['site_state'] :  '',
                                                  'label' => false
                                            ]); ?>
                                        </div>
				                    	<label id="locationError2" class="error"></label>              
									</div>
								</div>

								<div class="form-group clearfix">
									<label class="col-md-3 control-label">Site City <span class="star">*</span></label>
									<div class="col-md-6 col-lg-4">
                                        <div id="cityList">
                                            <?=	$this->Form->input('site_city',[
                                                'type' => 'select',
                                                'class' => 'form-control',                                                
                                                'empty' => 'Select City',
                                                'options'=> $cityList, 
                                                'onchange' => 'return locationFillters()',      
                                                'value' => !empty($EditSettingsList['site_city']) ? $EditSettingsList['site_city'] :  '',
                                                'label' => false
                                            ]); ?>
                                        </div>
				                        <label id="locationError3" class="error"></label>        	
									</div>
								</div>
                                
								<div class="form-group clearfix">
									<label class="col-md-3 control-label">Site Zipcode <span class="star">*</span></label>
									 <div class="col-md-6 col-lg-4">
                                         <div id="locationList">
                                             <?= $this->Form->input('site_zip',[
                                                 'type' => 'select',
                                                 'class' => 'form-control',                                                
                                                 'empty' => 'Select Zipcode',
                                                 'options'=> $locationList,
                                                 'value' => !empty($EditSettingsList['site_zip']) ? $EditSettingsList['site_zip'] :  '',
                                                 'label' => false
                                             ]); ?>
                                         </div>
				                        <label id="locationError4" class="error"></label> 
									</div>
								</div>

                                <div class="form-group clearfix">
                                    <label class="col-md-3 control-label">Site Currency<span class="star">*</span></label>
                                    <div class="col-md-6 col-lg-4">
                                        <?= $this->Form->input('site_currency',[
                                            'class' => 'form-control',
                                            'readonly' => true,
                                            'label' => false
                                         ]); ?>
                                    </div>
                                </div>

								<div class="form-group clearfix">
									<label class="col-md-3 control-label">Site Time Zone <span class="star">*</span></label>
									<div class="col-md-6 col-lg-4"> <?=
										 $this->Form->input('site_timezone',
								                            ['class' => 'form-control',
								                            'readonly' => true,
								                             'label' => false]); ?>
								        <label id="locationError" class="error"></label>
									</div>
								</div>	
							</div>

							<div class="tab-pane" id="analytics">
								<div class="form-group clearfix">
									<label class="col-md-3 control-label">Google Analytics Code </label>
									<div class="col-md-6 col-lg-4"> <?=
										$this->Form->input('google_analytics',
								                            ['class' => 'form-control',
								                            'type'  => 'textarea',
								                             'label' => false]); ?>
									</div>
								</div>	
								<div class="form-group clearfix">
									<label class="col-md-3 control-label">Zopim Analytics Code </label>
									<div class="col-md-6 col-lg-4"> <?=
										$this->Form->input('woopra_analytics',
								                            ['class' => 'form-control',
								                            	'type'  => 'textarea',
								                                'label' => false]); ?>
									</div>
								</div>
							</div>
							<div class="tab-pane" id="mail">
								<div class="form-group clearfix">
									<label class="col-md-3 control-label">Mail Option <span class="star">*</span></label>
									<div class="col-md-6 col-lg-4">
										<div class="radio-list">
											<label class="radio-inline smtp"><?php  
	                                          $options1 = ['SMTP' 	 => 'SMTP'];
	                                          $options2 = ['Normal' => 'Normal']; ?>
            									
	                                          <?= $this->Form->radio('mail_option',$options1,
	                                          							['checked'=>$options1,
	                                          								'label'=>false,
	                                          								'legend'=>false,
	                                          								'checked' => 'checked',
	                                          								'hiddenField'=>false]); ?> 
                                            SMTP</label>
                                            <label class="radio-inline smtp">  <?=
	                                            $this->Form->radio('mail_option',$options2,
	                                           							['checked'=>$options2,
	                                           								'label'=>false,
	                                           								'legend'=>false,
	                                           								'hiddenField'=>false]); ?>  
			                                Normal</label>
										</div>
									</div>
								</div>
								<div id="smtp">
									<div class="form-group clearfix">
										<label class="col-md-3 control-label">SMTP Host <span class="star">*</span></label>
										<div class="col-md-6 col-lg-4"> <?=
											$this->Form->input('smtp_host',
									                            ['class' => 'form-control',
									                             'label' => false]); ?>
									        <label id="mailError" class="error"></label>
										</div>
									</div>
									<div class="form-group clearfix">
										<label class="col-md-3 control-label">SMTP Port <span class="star">*</span></label>
										<div class="col-md-6 col-lg-4"> <?=
											$this->Form->input('smtp_port',
									                            ['class' => 'form-control',
									                                  'label' => false]); ?>
									        <label id="mailError1" class="error"></label>
										</div>
									</div>
									<div class="form-group clearfix">
										<label class="col-md-3 control-label">SMTP Username<span class="star">*</span></label>
										<div class="col-md-6 col-lg-4"> <?=
											$this->Form->input('smtp_username',
									                            ['class' => 'form-control',
									                                  'label' => false]); ?>
									        <label id="mailError2" class="error"></label>
										</div>
									</div>

									<div class="form-group clearfix">
										<label class="col-md-3 control-label">SMTP Password <span class="star">*</span></label>
										<div class="col-md-6 col-lg-4"> <?=
											$this->Form->input('smtp_password',
									                            ['class' => 'form-control',
									                             'label' => false]); ?>
									        <label id="mailError3" class="error"></label>
										</div>
									</div>
								</div>
								
							</div>
							<div class="tab-pane" id="invoice">
								<div class="form-group clearfix">
									<label class="col-md-3 control-label">VAT No</label>
									<div class="col-md-6 col-lg-4"> <?php
										echo $this->Form->input('vat_no',
								                            ['class' => 'form-control',
								                            	  'type'  => 'text',
								                                  'label' => false]); ?>
								        <label id="invoiceError" class="error"></label>
									</div>
								</div>				
								<div class="form-group clearfix">
									<label class="col-md-3 control-label">VAT (%)</label>
									<div class="col-md-6 col-lg-4"> <?=
										$this->Form->input('vat_percent',
								                            ['class' => 'form-control',
								                            	  'type'  => 'text',
								                                  'label' => false]); ?>
								        <label id="invoiceError1" class="error"></label>
									</div>
								</div>			
								<div class="form-group clearfix">
									<label class="col-md-3 control-label">Credit Card Fee (%)</label>
									<div class="col-md-6 col-lg-4"> <?=
										 $this->Form->input('card_fee',
								                            ['class' => 'form-control',
								                            	  'type'  => 'text',
								                                  'label' => false]); ?>
								        <label id="invoiceError2" class="error"></label>
									</div>
								</div>	
								<!--<div class="form-group clearfix">
									<label class="col-md-3 control-label">Invoice Time Period</label>
									<div class="col-md-6 col-lg-4"> <?php
/*
										$invoiceTimes = ['7 days'  => '7 days','15 days' => '15 Days'];

										echo $this->Form->input('invoice_duration',
													['type'=>'select',
											 		'class'=>'form-control',
											 		'options'=> $invoiceTimes,
											 		'empty' => 'Select Invoice Period Time',
											 		'label'=> false]); */?>
										<label id="invoiceError3" class="error"></label>	 		
									</div>
								</div>-->
							</div>
							<div class="tab-pane" id="offline">
								<div class="form-group clearfix">
									<label class="col-md-3 control-label">Offline Status <span class="star">*</span></label>
									<div class="col-md-6 col-lg-4">
										<div class="radio-list">
											<label class="radio-inline"> <?php  
	                                          	$options3 = ['Yes' => 'Yes'];
	                                          	$options4 = ['No'  => 'No']; 
                
		                                        echo $this->Form->radio('offline_status',$options3,
		                                          							['checked'=>$options3,
	                                          								'label'=>false,
	                                          								'legend'=>false,
	                                          								'checked' => 'checked',
	                                          								'hiddenField'=>false]); ?> 
	                                        Yes</label>
	                                            <label class="radio-inline">  <?= 
		                                            $this->Form->radio('offline_status',$options4,
		                                           							['checked'=>$options4,
	                                           								'label'=>false,
	                                           								'legend'=>false,
	                                           								'hiddenField'=>false]); ?>  
				                            No</label>
										</div>
									</div>
								</div>	
								<div class="form-group clearfix" id="offlineReason">
									<label class="col-md-3 control-label">Offline Notes</label>
									<div class="col-md-6 col-lg-4"> <?=
										$this->Form->input('offline_notes',
								                            ['class' => 'form-control',
								                                  'label' => false]); ?>
								        <label id="offlineError" class="error"></label>
									</div>
								</div>
							</div>
							<div class="tab-pane" id="MetaTags">
								<div class="form-group clearfix">
									<label class="col-md-3 control-label">Meta Titles</label>
									<div class="col-md-6 col-lg-4">
                                        <?=
										 $this->Form->input('meta_title',
                                            ['class' => 'form-control',
                                             'label' => false,
                                             'type' => 'textarea']);
										 ?>
									</div>
								</div>	
								<div class="form-group clearfix">
									<label class="col-md-3 control-label">Meta Keywords</label>
										<div class="col-md-6 col-lg-4"><?=

											$this->Form->input('meta_keywords',
									                            ['class' => 'form-control',
								                                 'label' => false,
								                                 'type' => 'textarea']); ?>
										</div>
								</div>
								<div class="form-group clearfix">
									<label class="col-md-3 control-label">Meta Descriptions</label>
										<div class="col-md-6 col-lg-4"><?=

											$this->Form->input('meta_description',
									                            ['class' => 'form-control',
									                             'label' => false,
									                             'type' => 'textarea']); ?>
										</div>
								</div>
							</div>
							<div class="tab-pane" id="assign">
								<div class="form-group clearfix">
									<label class="col-md-3 control-label">Assign Status <span class="star">*</span></label>
									<div class="col-md-6 col-lg-4">
										<div class="radio-list">
											<label class="radio-inline"> <?php  
	                                          $options5 = ['Yes' => 'Yes'];
	                                          $options6 = ['No'  => 'No']; 
            
	                                          echo $this->Form->radio('assign_status',$options5,
	                                          							['checked'=>$options5,
                                          								'label'=>false,
                                          								'legend'=>false,
                                          								'checked' => 'checked',
                                          								'hiddenField'=>false]); ?> 
                                            Yes</label>
                                            <label class="radio-inline">  <?= 
	                                           $this->Form->radio('assign_status',$options6,
	                                           							['checked'=>$options6,
	                                           							'label'=>false,
	                                           							'legend'=>false,
	                                           							'hiddenField'=>false]); ?>  
			                                No</label>
										</div>
									</div>
								</div>	
								<div class="form-group clearfix" id="assign_miles">
									<label class="col-md-3 control-label">Assign Miles</label>
									<div class="col-md-6 col-lg-4"> <?=
										$this->Form->input('assign_miles',
								                            ['class' => 'form-control',
							                            	 'type'  => 'text',
							                                 'label' => false]); ?>
							            <label id="assignError" class="error"></label>
									</div>
								</div>	
							</div>
							<div class="tab-pane" id="Language">
								<div class="form-group clearfix">
									<label class="col-md-3 control-label">Do You Want to Multiple Language?<span class="star">*</span></label>
									<div class="col-md-6 col-lg-4">
										<div class="radio-list">
											<label class="radio-inline"> <?php  
	                                          $options7 = ['No' => 'No'];
	                                          $options8 = ['Yes'  => 'Yes']; 
            
	                                          echo $this->Form->radio('multiple_language',$options7,['checked'=>$options7,
                                          								'label'=>false,
                                          								'legend'=>false,
                                          								'checked' => 'checked',
                                          								'hiddenField'=>false]); ?> 
                                            No</label>
                                            <label class="radio-inline">  <?= 
	                                           $this->Form->radio('multiple_language',
	                                           	$options8,['checked'=>$options8,
	                                           							'label'=>false,
	                                           							'legend'=>false,
	                                           							'hiddenField'=>false]); ?>  
			                                Yes</label>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>					
						<div class="col-xs-12 no-padding">
							<button class="btn btn-info m-r-15" type="submit" onclick="return siteSettingsValidate();">Submit</button>
							<a href="<?= ADMIN_BASE_URL?>sitesettings/index" class="btn btn-default">Cancel</a>
						</div>
					
                    <?= $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</section>
</div>


<script type="text/javascript">
    var i = 1;
    function multipleBonus() {
        if($("#bonusmore_"+i).length != 0) {
            i++;
            multipleBonus();
            return false;
        }
        html = '<div class="col-md-7 col-md-offset-2 margin-bottom" id="bonusmore_'+i+'">'+
            '<div class="row">'+
            '<div class="col-md-4">'+
            '<div class="input text">'+
            '<input id="no_order_'+i+'" class="form-control orderlist" name="data[Bonus][' + i + '][no_order]" placeholder="Order" type="text" data-attr="no order">'+
            '</div>'+
            '</div>'+
            '<div class="col-md-3">'+
            '<div class="input text">'+
            '<input id="no_point_'+i+'" class="form-control pointlist " name="data[Bonus][' + i + '][no_point]" placeholder="Point"  type="text" data-attr="no point">'+
            '</div>'+
            '</div>'+
            '<div class="col-md-1">'+
            '<span class="BonusRemove" onclick="removeBonus('+i+');">'+
            '<i class="fa fa-times"></i>'+
            '</span>'+
            '</div>'+
            '</div>'+
            '<span class="commonErr_'+i+'"></span>'+
            '</div>';
        i++;
        $('#moreOption').append(html);
        html = '';
        return false;
    }

    function removeBonus(id) {
        $('#bonusmore_'+id).remove();
    }


    function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }

function isValidEmailAddress(emailAddress) {

    var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
    return pattern.test(emailAddress);
};

    

function siteSettingsValidate() 
{
	var SiteName   	 = $.trim($("#site-name").val());
	var Sitelogo     = $.trim($("#site-logo").val());
	var SiteFavicon  = $.trim($("#site-fav").val());
	var Search_by    = $.trim($("input[name='search_by']:checked").val());
	var Address_mode = $.trim($("input[name='address_mode']:checked").val());

	var AdminName 		= $.trim($("#admin-name").val());
	var AdminEmail 		= $.trim($("#admin-email").val());
	var ContactUsEmail 	= $.trim($("#contact-us-email").val());
	var InvoiceEmail 	= $.trim($("#invoice-email").val());
	var ContactPhone 	= $.trim($("#contact-phone").val());
	var OrderEmail 		= $.trim($("#order-email").val());

	var SiteAddress		= $.trim($("#site-address").val());
	var SiteCountry 	= $.trim($("#site-country").val());
	var SiteState	    = $.trim($("#site-state").val());
	var SiteCity 		= $.trim($("#site-city").val());
	var SiteZip 		= $.trim($("#site-zip").val());

	var mail_option     = $.trim($("input[name='mail_option']:checked").val());
	var SmtpHost 		= $.trim($("#smtp-host").val());
	var SmtpPort 		= $.trim($("#smtp-port").val());
	var SmtpUsername 	= $.trim($("#smtp-username").val());
	var SmtpPassword 	= $.trim($("#smtp-password").val());

	var VatNo 			= $.trim($("#vat-no").val());
	var VatPercent 		= $.trim($("#vat-percent").val());
    var CardFee 		= $.trim($("#card-fee").val());
	var InvoiceDuration = $.trim($("#invoice-duration").val());

	var assign_status   = $.trim($("input[name='assign_status']:checked").val());
    var AssignMiles     = $.trim($("#assign-miles").val());

    var offline_notes     = $.trim($("#offline-notes").val());
    var offline_status_yes = ($('#offline-status-yes:checked').val() == undefined ? 'NO' : $('#offline-status-yes:checked').val());

	var img  = $('#site-logo').val().split('.').pop().toLowerCase();
	var icon = $('#site-fav').val().split('.').pop().toLowerCase();

    	
	$('#siteError').html('');
	$('#siteError1').html('');
	$('#siteError2').html('');
	$('#contactError').html('');
	$('#contactError1').html('');
	$('#contactError2').html('');
	$('#contactError3').html('');
	$('#contactError4').html('');
	$('#contactError5').html('');
	$('#locationError').html('');
	$('#locationError1').html('');
	$('#locationError2').html('');
	$('#locationError3').html('');
	$('#locationError4').html('');
	$('#mailError').html('');
	$('#mailError1').html('');
	$('#mailError2').html('');
	$('#mailError3').html('');
	$('#invoiceError').html('');
	$('#invoiceError1').html('');
	$('#invoiceError2').html('');
	$('#invoiceError3').html('');
	$('#offlineError').html('');
	$("#assignError").html("");

	if(SiteName == ''){
		$('.nav-tabs a[href="#site"]').tab('show');
		$("#siteError").html("Please enter site name");
		$("#site-name").focus();
		return false;
	}/*else if(Sitelogo == ''){
		$('.nav-tabs a[href="#site"]').tab('show');
		$("#siteError1").html('Please select site logo');
		$("#site-logo").focus();
		return false;
	}else if(Sitelogo != '' && $.inArray(img, ['gif','png','jpg','jpeg']) == -1){
		$('.nav-tabs a[href="#site"]').tab('show');
		$("#siteError1").html("Please Select the Valid Image Type");
		$("#site-logo").focus();
		return false;
	}else if(SiteFavicon == ''){
		$('.nav-tabs a[href="#site"]').tab('show');
		$("#siteError2").html('Please select site favIcon');
		$("#site-fav").focus();
		return false;
	}else if(SiteFavicon != '' && $.inArray(icon,['ico']) == -1){
		$('.nav-tabs a[href="#site"]').tab('show');
		$("#siteError2").html("Please select Fav Icon as .ico Format");
		$("#site-fav").focus();
		return false;
	}*/else if(AdminName == ''){
		$('.nav-tabs a[href="#contact"]').tab('show');
		$("#contactError").html("Please enter admin name");
		$("#admin-name").focus();
		return false;		
	}else if(AdminEmail == ''){
	    $('.nav-tabs a[href="#contact"]').tab('show');
		$("#contactError1").html("Please enter admin email");
		$("#admin_email").focus();
		return false;		
	}else if(AdminEmail != '' && !isValidEmailAddress(AdminEmail)) {
		$('.nav-tabs a[href="#contact"]').tab('show');
        $("#contactError1").html('Please enter valid email address');
        $("#admin_email").focus();
        return false;
    }else if(ContactUsEmail == ''){
	    $('.nav-tabs a[href="#contact"]').tab('show');
		$("#contactError2").html("Please enter contact us email");
		$("#contact-us-email").focus();
		return false;		
	}else if(ContactUsEmail != '' && !isValidEmailAddress(ContactUsEmail)) {
		$('.nav-tabs a[href="#contact"]').tab('show');
        $("#contactError2").html('Please enter valid contact us email address');
        $("#contact-us-email").focus();
        return false;
    }else if(InvoiceEmail == ''){
	    $('.nav-tabs a[href="#contact"]').tab('show');
		$("#contactError3").html("Please enter invoice email");
		$("#invoice-email").focus();
		return false;		
	}else if(InvoiceEmail != '' && !isValidEmailAddress(InvoiceEmail)) {
		$('.nav-tabs a[href="#contact"]').tab('show');
        $("#contactError3").html('Please enter valid invoice email address');
        $("#invoice-email").focus();
        return false;
    }else if(ContactPhone == ''){
	    $('.nav-tabs a[href="#contact"]').tab('show');
		$("#contactError4").html("Please enter site contact phone");
		$("#contact-phone").focus();
		return false;		
	}else if(OrderEmail == ''){
	    $('.nav-tabs a[href="#contact"]').tab('show');
		$("#contactError5").html("Please enter order email");
		$("#order-email").focus();
		return false;		
	}else if(OrderEmail != '' && !isValidEmailAddress(OrderEmail)) {
		$('.nav-tabs a[href="#contact"]').tab('show');
        $("#contactError5").html('Please enter valid orderemail address');
        $("#order-email").focus();
        return false;
    }else if(SiteAddress == ''){
	    $('.nav-tabs a[href="#location"]').tab('show');
		$("#locationError").html("Please enter site address");
		$("#site-address").focus();
		return false;		
	}else if(SiteCountry == ''){
	     $('.nav-tabs a[href="#location"]').tab('show');
		$("#locationError1").html("Please enter site country");
		$("#site-country").focus();
		return false;		
	}else if(SiteState == ''){
	    $('.nav-tabs a[href="#location"]').tab('show');
		$("#locationError2").html("Please enter state name");
		$("#site-state").focus();
		return false;		
	}else if(SiteCity == ''){
	     $('.nav-tabs a[href="#location"]').tab('show');
		$("#locationError3").html("Please enter site city");
		$("#site-city").focus();
		return false;		
	}else if(SiteZip == ''){
	    $('.nav-tabs a[href="#location"]').tab('show');
		$("#locationError4").html("Please enter site zipcode");
		$("#site-zip").focus();
		return false;		
	}
	if ($("#mail-option-smtp").is(":checked")) {

		if(SmtpHost == ''){
	        $('.nav-tabs a[href="#mail"]').tab('show');
			$("#mailError").html("Please enter smtp host");
			$("#smtp-host").focus();
			return false;		
		}else if(SmtpPort == ''){
	        $('.nav-tabs a[href="#mail"]').tab('show');
			$("#mailError1").html("Please enter smtp port");
			$("#smtp-port").focus();
			return false;		
		}else if(SmtpUsername == ''){
	        $('.nav-tabs a[href="#mail"]').tab('show');
			$("#mailError2").html("Please enter smtp username");
			$("#smtp-username").focus();
			return false;		
		}else if(SmtpPassword == ''){
	        $('.nav-tabs a[href="#mail"]').tab('show');
			$("#mailError3").html("Please enter smtp password");
			$("#smtp-password").focus();
			return false;		
		}
	}
	if(VatNo == ''){
	    $('.nav-tabs a[href="#invoice"]').tab('show');
		$("#invoiceError").html("Please enter VAT no");
		$("#vat-no").focus();
		return false;		
	}else if(VatPercent == ''){
	    $('.nav-tabs a[href="#invoice"]').tab('show');
		$("#invoiceError1").html("Please enter VAT");
		$("#vat-percent").focus();
		return false;		
	}else if(CardFee == ''){
		$('.nav-tabs a[href="#invoice"]').tab('show');        
		$("#invoiceError2").html("Please enter card fee");
		$("#card-fee").focus();
		return false;		
	}/*else if(InvoiceDuration == ''){
        alert(4);
	    $('.nav-tabs a[href="#invoice"]').tab('show');
		$("#invoiceError3").html("Please select invoice time period");
		$("#invoice-duration").focus();
		return false;		
	}*/
	
    if(offline_status_yes == 'Yes' && offline_status_yes != undefined)
    {
        if(offline_notes == '')
        {
        	$('.nav-tabs a[href="#offline"]').tab('show');
            $("#offlineError").html("Please Enter Offline Notes");
    		$("#offline-notes").focus();
    		return false; 
        }
    }
    if(assign_status == 'Yes' && AssignMiles == ''){
	    $('.nav-tabs a[href="#assign"]').tab('show');
	    $("#assignError").html("Please enter miles");
	    $("#assign-miles").focus();
	    return false;
  	}	
    
}


//Get Currency symbol
function getCurrency(){
    var SiteCountry = $.trim($("#site-country").val());
    if(SiteCountry != ''){
        $.ajax({
            'type' : 'POST',
            'url' : jssitebaseurl+'sitesettings/getCurrency',
            'data': {SiteCountry:SiteCountry},
            success: function(data) {
                if(data != ''){
                	var obj = JSON.parse(data);
                    $("#site-currency").val(obj.currency_symbol);
                    $("#site-timezone").val(obj.timezone);
                }
            }
        })
    }else{
        $("#site-currency").val('');
        $("#site-timezone").val('');
    }
}


//Get StateList
function stateFillters(){
    var country_id = $.trim($("#site-country").val());    
    $.ajax({
        'type' : 'POST',
        'url' : jssitebaseurl+'sitesettings/ajaxaction',
        'data': {country_id : country_id , action: 'getStatelist'},
        success: function(data) {
            $("#stateList").html(data);
        }
     })
}



//Get CityList
function cityFillters(){
    var state_id = $.trim($("#site-state").val());    
    $.ajax({
        'type' : 'POST',
        'url' : jssitebaseurl+'sitesettings/ajaxaction',
        'data': {state_id : state_id , action: 'getCitylist'},
        success: function(data) {
            $("#cityList").html(data);
        }
    })    
}


//Get LocationList
function locationFillters(){
    var city_id = $.trim($("#site-city").val());    
    $.ajax({
        'type' : 'POST',
        'url' : jssitebaseurl+'sitesettings/ajaxaction',
        'data': {city_id : city_id, action: 'getLocationlist'},
        success: function(data) {
            $("#locationList").html(data);
        }
    })    
}
</script>