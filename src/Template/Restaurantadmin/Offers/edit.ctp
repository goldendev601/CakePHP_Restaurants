<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Edit Offer
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li>Manage Offer</li>
			<li class="active">Edit Offer</li>

		</ol>
	</section>
	 <section class="content clearfix">
        <div class="col-xs-12">
            <div class="row">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><!-- Offers --></h3>
                    </div>
                        <?php
                            if(!empty($offerList)) {
                                echo $this->Form->create($offerList, [
                                    'id' => 'offerAddEditForm'                                
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
                            <div class="form-group clearfix">
                                <label class="col-sm-2 control-label">Offer Type</label>
                                <div class="col-sm-4">
                                    <label class="checkbox-inline no-padding-left"> First Order Offer
                                         <?= $this->Form->input('first_user',[
                                            'type' => 'checkbox',
                                            'id'   => 'first_user',
                                            'class' => 'minimal deliveryType',
                                            'checked' => ($offerList['first_user'] == 'Y') ? 'checked' : '',
                                            'placeholder' => 'First User',
                                            'label' => false
                                        ]) ?>
                                    </label>
                                    <label class="checkbox-inline">Normal Offer
                                         <?= $this->Form->input('min_delivery',[
                                            'type' => 'checkbox',
                                            'id'   => 'min_delivery',
                                            'class' => 'minimal deliveryType',
                                            'placeholder' => 'Min Delivery',
                                            'checked' => ($offerList['normal'] == 'Y') ? 'checked' : '',
                                            'label' => false
                                        ]) ?>
                                    </label>
                                    <span class="deliveryErr"></span>
                                </div>
                            </div>
                         </div>

                         <div id="showUserPrice" <?php if($offerList['first_user'] == 'Y') { ?>style="display:block;"<?php } else { ?>style="display:none;" <?php } ?>>

                            <div class="box-body">
                                <div class="form-group clearfix">
                                    <label for="user_price" class="col-sm-2 control-label">Minimum Order Value<span class="star">*</span></label>
                                    <div class="col-sm-4">
                                        <?= $this->Form->input('free_price',[
                                            'type' => 'text',
                                            'id'   => 'free_price',
                                            'class' => 'form-control user_price',
                                            'placeholder' => 'Enter order value',
                                            'label' => false
                                        ]) ?>
                                        <span class="freePriceErr"></span>
                                    </div>
                                </div>
                            </div>
                             <div class="box-body">
                                 <div class="form-group clearfix">
                                     <label for="user_price" class="col-sm-2 control-label">Offer(%)<span class="star">*</span></label>
                                     <div class="col-sm-4">
                                         <?= $this->Form->input('free_percentage',[
                                             'type' => 'text',
                                             'id'   => 'free_percentage',
                                             'class' => 'form-control user_price',
                                             'placeholder' => 'Enter offer value',
                                             'label' => false
                                         ]) ?>
                                         <span class="freePercentErr"></span>
                                     </div>
                                 </div>
                             </div>
                         </div> 

                    <div class="box-body" id ="showMinPrice" <?php if($offerList['normal'] == 'Y') { ?>style="display:block;"<?php } else { ?>style="display:none;" <?php } ?>>
                        <div class="form-group clearfix">
                            <label for="min_price" class="col-sm-2 control-label">Minimum Order Value<span class="star">*</span></label>
                            <div class="col-sm-4">
                                <?= $this->Form->input('normal_price',[
                                    'type' => 'text',
                                    'id'   => 'normal_price',
                                    'class' => 'form-control min_price',
                                    'placeholder' => 'Enter order value',
                                    'label' => false
                                ]) ?>
                                <span class="normalPriceErr"></span>
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <label for="min_price" class="col-sm-2 control-label">Offer (%)<span class="star">*</span></label>
                            <div class="col-sm-4">
                                <?= $this->Form->input('normal_percentage',[
                                    'type' => 'text',
                                    'id'   => 'normal_percentage',
                                    'class' => 'form-control min_price',
                                    'placeholder' => 'Enter offer value',
                                    'label' => false
                                ]) ?>
                                <span class="normalPercentErr"></span>
                            </div>
                        </div>
                    </div>
                        <div class="box-body">
                            <div class="form-group clearfix">
                               <label class="col-sm-2 control-label">Date Range <span class="star">*</span></label>
                               <div class="col-md-6 col-lg-5">
                                  <div class="input-group input-medium">
                                       <?= $this->Form->input('offer_from',[
                                            'type' => 'text',
                                            'id'   => 'offer_from',
                                            'class' => 'form-control',
                                            'placeholder' => 'From Date',
                                            'label' => false
                                        ]) ?>                                       
                                     <span class="fromErr"></span>    
                                     <span class="input-group-addon"> to </span>
                                      <?= $this->Form->input('offer_to',[
                                            'type' => 'text',
                                            'id'   => 'offer_to',
                                            'class' => 'form-control',
                                            'placeholder' => 'To Date',
                                            'label' => false
                                        ]) ?>                                      
                                     <span class="toErr"></span>          
                                  </div>
                               </div>
                            </div>
                        </div>  

                        <div class="box-footer">
                            <a class="btn btn-default m-r-15" href="<?php echo REST_BASE_URL?>offers/">Cancel</a>
                            <button type="submit" class="btn btn-info" onclick="offerAddEdit();">Submit</button>
                        </div>
                    <?= $this->Form->end();?>
                </div>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript">

   function offerAddEdit(){

        var ckbox = $('#first_user').is(':checked');
        var ckbox1 = $('#min_delivery').is(':checked');
    
        var delivery   = $('.deliveryType:checked').length;      
        var free_price = $.trim($("#free_price").val()); 
        var normal_price  = $.trim($("#normal_price").val()); 
        var free_percentage = $.trim($("#free_percentage").val()); 
        var normal_percentage = $.trim($("#normal_percentage").val()); 
        var from_date  = $.trim($("#offer_from").val());        
        var to_date    = $.trim($("#offer_to").val());
      
        
        $('.error').html('');
        if(delivery == 0){
            $('.deliveryErr').addClass('error').html('Please choose your delivery type');
            $("#min_delivery").focus();
            return false;   
        }
        if(ckbox == true || (ckbox == true && ckbox1 == true) ){
            if(free_percentage == '') {
                $('.freePercentErr').addClass('error').html('Please enter free percentage');
                $("#free_percentage").focus();
                return false; 
            }else if(free_price == '') {
                $('.freePriceErr').addClass('error').html('Please enter free price');
                $("#free_price").focus();
                return false;
            }   
        }
        if(ckbox1 == true || (ckbox == true && ckbox1 == true) ){
            if(normal_price == '') {
                $('.normalPriceErr').addClass('error').html('Please enter normal price');
                $("#normal_price").focus();
                return false;               
            }else if(normal_percentage == '') {
                $('.normalPercentErr').addClass('error').html('Please enter normal percentage');
                $("#normal_percentage").focus();
                return false;
            }
        }
        if(from_date == ''){
            $('.fromErr').addClass('error').html('Please select offer from');
            $("#offer_from").focus();
            return false;   
        }else if(to_date == ''){
            $('.toErr').addClass('error').html('Please select offer to');
            $("#offer_to").focus();
            return false;   
        }else{
            $("#offerAddEditForm").submit();
        }
    } 

</script>