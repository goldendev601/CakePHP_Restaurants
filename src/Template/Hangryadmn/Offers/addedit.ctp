<div class="content-wrapper">
    <section class="content-header">
        <h1>
           <?php if(!empty($id)) {?> Edit <?php }else{?> Add <?php }?> Offer
        </h1>
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
                            if(!empty($offerList)) {
                                echo $this->Form->create($offerList, [
                                    'id' => 'offerAddEditForm'                                
                                ]);                                                          
                             } else {
                                echo $this->Form->create('offerAdd', [
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
                            <div class="form-group">
                                <label for="offer_percentage" class="col-sm-2 control-label">Offer Percentge(%)<span class="star">*</span></label>
                                <div class="col-sm-4">
                                    <?= $this->Form->input('offer_percentage',[
                                            'type' => 'text',
                                            'id'   => 'offer_percentage',
                                            'class' => 'form-control offer_percentage',
                                            'placeholder' => 'Offer Percentage',
                                            'label' => false
                                        ]) ?>
                                      <span class="percentErr"></span>    
                                </div>
                            </div>                            
                        </div>

                        <div class="box-body">
                            <div class="form-group">
                                <label for="offer_price" class="col-sm-2 control-label">Offer Price<span class="star">*</span></label>
                                <div class="col-sm-4">
                                    <?= $this->Form->input('offer_price',[
                                            'type' => 'text',
                                            'id'   => 'offer_price',
                                            'class' => 'form-control offer_price',
                                            'placeholder' => 'Offer Price',
                                            'label' => false
                                        ]) ?>
                                      <span class="priceErr"></span>    
                                </div>
                            </div>                            
                        </div>

                         <div class="box-body">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Delivery</label>
                                <div class="col-sm-4">
                                    <label class="checkbox-inline no-padding-left"> First User
                                        <?= $this->Form->input('first_user',[
                                            'type' => 'checkbox',
                                            'id'   => 'first_user',
                                            'class' => 'minimal deliveryType',
                                            'placeholder' => 'First User',
                                            'label' => false
                                        ]) ?>
                                    </label>
                                    <label class="checkbox-inline">Min Delivery
                                        <?= $this->Form->input('min_delivery',[
                                            'type' => 'checkbox',
                                            'id'   => 'min_delivery',
                                            'class' => 'minimal deliveryType',
                                            'placeholder' => 'Min Delivery',
                                            'label' => false
                                        ]) ?>
                                    </label>
                                    <label class="checkbox-inline">Free Delivery
                                        <?= $this->Form->input('free_delivery',[
                                            'type' => 'checkbox',
                                            'id'   => 'free_delivery',
                                            'class' => 'minimal deliveryType',
                                            'placeholder' => 'Free Delivery',
                                            'label' => false
                                        ]) ?>
                                    </label>
                                </div>
                            </div>
                         </div>

                    <div class="box-body" id ="showUserPrice" style="display:none;">
                        <div class="form-group">
                            <label for="user_price" class="col-sm-2 control-label">User Price<span class="star">*</span></label>
                            <div class="col-sm-4">
                                <?= $this->Form->input('user_price',[
                                    'type' => 'text',
                                    'id'   => 'user_price',
                                    'class' => 'form-control user_price',
                                    'placeholder' => 'User Price',
                                    'label' => false
                                ]) ?>
                                <span class="userPriceErr"></span>
                            </div>
                        </div>
                    </div>

                    <div class="box-body" id ="showMinPrice" style="display:none;">
                        <div class="form-group">
                            <label for="min_price" class="col-sm-2 control-label">Offer Price<span class="star">*</span></label>
                            <div class="col-sm-4">
                                <?= $this->Form->input('min_price',[
                                    'type' => 'text',
                                    'id'   => 'min_price',
                                    'class' => 'form-control min_price',
                                    'placeholder' => 'Min Price',
                                    'label' => false
                                ]) ?>
                                <span class="minPriceErr"></span>
                            </div>
                        </div>
                    </div>

                    <div class="box-body" id ="showFreePrice" style="display:none;">
                        <div class="form-group">
                            <label for="free_price" class="col-sm-2 control-label">Free Price<span class="star">*</span></label>
                            <div class="col-sm-4">
                                <?= $this->Form->input('free_price',[
                                    'type' => 'text',
                                    'id'   => 'free_price',
                                    'class' => 'form-control free_price',
                                    'placeholder' => 'Free Price',
                                    'label' => false
                                ]) ?>
                                <span class="freePriceErr"></span>
                            </div>
                        </div>
                    </div>

                        <div class="box-body">
                            <div class="form-group">
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

        var percentage = $.trim($("#offer_percentage").val());        
        var price      = $.trim($("#offer_price").val());        
        var delivery   = $.trim($(".deliveryType").length);             
        var from_date  = $.trim($("#offer_from").val());        
        var to_date    = $.trim($("#offer_to").val());        
        var editid     = $.trim($("#editid").val());
        $('.error').html('');

        if(percentage == '') {
            $('.percentErr').addClass('error').html('Please enter offer percentage');
            $("#offer_percentage").focus();
            return false;
        }else if(price == '') {
            $('.priceErr').addClass('error').html('Please enter offer price');
            $("#offer_price").focus();
            return false;               
        }else if(from_date == ''){
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