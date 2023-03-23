<div class="content-wrapper">
    <section class="content-header">
        <h1> Add Offer </h1>        
        <ol class="breadcrumb">
            <li>
              <a href="<?php echo ADMIN_BASE_URL ;?>dashboard">
                 <i class="fa fa-dashboard"></i> Home</a>
            </li>           
            <li class="active">
               <a href="<?php echo ADMIN_BASE_URL ;?>offers">Manage Offer</a>
            </li>
        </ol>
    </section>

    <section class="content clearfix">
        <div class="col-xs-12">
            <div class="row">
                <div class="box my-box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><!-- Offers --></h3>
                    </div>
                        <?php
                            echo $this->Form->create('offerAdd', [
                                'id' => 'offerAddEditForm'
                            ]);                   
                        ?>

                        <div class="box-body">
                            <div class="form-group clearfix">
                                <label class="col-sm-2 control-label">Restaurant Name<span class="help">*</span></label>
                                <div class="col-sm-4">
                                    <?= $this->Form->input('restaurant_id',[
                                        'type' => 'select',
                                        'id'   => 'restaurant_id',
                                        'class' => 'form-control',
                                        'options' => $restaurantLists,
                                        'empty' => 'Please select a Restaurant',
                                        'label' => false
                                    ]) ?>
                                </div>
                                <span class="restErr"></span>
                            </div>
                        </div>

                      
                         <div class="box-body">
                            <div class="form-group clearfix">
                                <label class="col-sm-2 control-label">Offer Type</label>
                                <div class="col-sm-4">
                                    <label class="checkbox-inline no-padding-left"> First Order Offer
                                        <?= $this->Form->input('first_user',[
                                            'type' => 'checkbox',
                                            'id'   => 'first_user',
                                            'class' => 'minimal deliveryType',
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
                                            'label' => false
                                        ]) ?>
                                    </label>
                                    <span class="deliveryErr"></span>
                                </div>
                            </div>
                         </div>

                         <div id="showUserPrice" style="display:none;">

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

                    <div class="box-body" id ="showMinPrice" style="display:none;">
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
                        <div class="form-group">
                            <label for="min_price clearfix" class="col-sm-2 control-label">Offer(%)<span class="star">*</span></label>
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

                        <div class="col-xs-12 no-padding m-t-20">
                        <button type="button" class="btn btn-info m-r-15" onclick="offerAddEdit();">Submit</button>
                            <a class="btn btn-default" href="<?php echo ADMIN_BASE_URL?>offers/">Cancel</a>
                            
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
        var restaurantId = $.trim($("#restaurant_id").val()); 
        var normal_price  = $.trim($("#normal_price").val()); 
        var free_percentage = $.trim($("#free_percentage").val()); 
        var normal_percentage = $.trim($("#normal_percentage").val()); 
        var from_date  = $.trim($("#offer_from").val());        
        var to_date    = $.trim($("#offer_to").val());
      
        
        $('.error').html('');
        if(restaurantId == ''){
            $('.restErr').addClass('error').html('Please select your restaurant');
            $("#restaurant_id").focus();
            return false;   
        }
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

