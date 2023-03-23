<div class="content-wrapper">
    <section class="content-header">
        <h1>
           <?php if(!empty($id)) {?> Edit <?php }else{?> Add <?php }?> Voucher
        </h1>
        <ol class="breadcrumb">
            <li>
              <a href="<?php echo ADMIN_BASE_URL ;?>dashboard">
                 <i class="fa fa-dashboard"></i> Home</a>
            </li>           
            <li class="active">
               <a href="<?php echo ADMIN_BASE_URL ;?>vouchers">Manage Voucher</a>
            </li>
        </ol>
    </section>

    <section class="content clearfix">
        <div class="col-xs-12">
            <div class="row">
                <div class="box my-box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><!-- Vouchers --></h3>
                    </div>
                        <?php
                            if(!empty($vouchersList)) {
                                echo $this->Form->create($vouchersList, [
                                    'id' => 'voucherAddEditForm'                                
                                ]);                                                          
                             } else {
                                echo $this->Form->create('voucherAdd', [
                                    'id' => 'voucherAddEditForm'
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
                                <label for="voucher_code" class="col-sm-2 control-label">Voucher Code</label>
                                <div class="col-sm-4">
                                    <?= $this->Form->input('voucher_code',[
                                            'type' => 'text',
                                            'id'   => 'voucher_code',
                                            'class' => 'form-control',
                                            'placeholder' => 'Voucher Code',
                                            'label' => false
                                        ]) ?>
                                      <span class="codeErr"></span>    
                                </div>
                            </div>                            
                        </div>
                        
                       <div class="box-body">
                        <div class="form-group clearfix">
                            <label class="col-sm-2 control-label">Type Of Use</label>
                            <div class="col-sm-4">
                                <label class="radio-inline no-padding-left">
                                    <input id="single" type="radio" name="type_offer" class="minimal" checked = "checked" value="single" <?php if(!empty($vouchersList['type_offer'])) {echo ($vouchersList['type_offer'] == 'multiple') ? 'checked' : '' ; }?>>
                                    Single
                                </label>
                                <label class="radio-inline">
                                    <input id="multiple" type="radio" name="type_offer" class="minimal" value="multiple" <?php if(!empty($vouchersList['type_offer'])) {echo ($vouchersList['type_offer'] == 'multiple') ? 'checked' : '' ; }?>>
                                    Multiple
                                </label> 
                                <span class="typeErr"></span>                                   
                            </div>
                        </div>
                        </div>

                      <div class="box-body">
                            <div class="form-group clearfix">
                                <label class="col-sm-2 control-label">Type of Offer</label>
                                <div class="col-sm-4">
                                    <label class="radio-inline margin-r-15">
                                        <input id="price" type="radio" checked="checked" name="offer_mode" class="" value="price" <?php if(!empty($vouchersList['offer_mode'])) { echo ($vouchersList['offer_mode'] == 'price') ? 'checked' : '' ;}?> onclick="voucherOfferType('price');">
                                        Price
                                    </label>
                                    <label class="radio-inline no-padding-left margin-r-15">
                                        <input id="percentage" type="radio" name="offer_mode" class="" value="percentage" <?php if(!empty($vouchersList['offer_mode'])) {echo ($vouchersList['offer_mode'] == 'percentage') ? 'checked' : '' ;}?> onclick="voucherOfferType('percentage');">
                                        Percentage
                                    </label>
                                    <label class="radio-inline">
                                        <input id="free_delivery" type="radio" name="offer_mode" class="" value="free_delivery" <?php if(!empty($vouchersList['offer_mode'])) { echo ($vouchersList['offer_mode'] == 'free_delivery') ? 'checked' : '' ;}?> onclick="voucherOfferType('free_delivery');">
                                        Free Delivery
                                    </label> 
                                    <span class="modeErr"></span>
                                </div>
                            </div>
                       </div>
                                           
                        <div class="box-body" id="voucherOfferValue" <?php if( !empty($vouchersList['offer_mode']) && $vouchersList['offer_mode'] == 'free_delivery') { ?>style="display:none;"<?php } ?>>                        
                            <div class="form-group clearfix">
                                <label for="offer_value" class="col-sm-2 control-label">Offer Value</label>
                                <div class="col-sm-4">
                                    <?= $this->Form->input('offer_value',[
                                            'type' => 'text',
                                            'id'   => 'offer_value',
                                            'class' => 'form-control offer_value',
                                            'placeholder' => 'Offer Value',
                                            'label' => false
                                        ]) ?>
                                      <span class="valueErr"></span>    
                                </div>
                            </div>                            
                        </div>

                        <div class="box-body">
                            <div class="form-group clearfix">
                               <label class="col-sm-2 control-label">Date Range <span class="star">*</span></label>
                               <div class="col-md-6 col-lg-5">
                                  <div class="input-group input-medium">
                                       <?= $this->Form->input('voucher_from',[
                                            'type' => 'text',
                                            'id'   => 'voucher_from',
                                            'class' => 'form-control',
                                            'placeholder' => 'From Date',
                                            'label' => false
                                        ]) ?>                                       
                                     <span class="fromErr"></span>    
                                     <span class="input-group-addon"> to </span>
                                      <?= $this->Form->input('voucher_to',[
                                            'type' => 'text',
                                            'id'   => 'voucher_to',
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
                        <button type="button" class="btn btn-info m-r-15" onclick="return voucherAddEdit();">Submit</button>
                            <a class="btn btn-default" href="<?php echo ADMIN_BASE_URL?>vouchers/">Cancel</a>
                            
                        </div>
                    <?= $this->Form->end();?>
                </div>
            </div>
        </div>
    </section>
</div>


<script type="text/javascript">

    function voucherAddEdit(){

        var voucher_code = $.trim($("#voucher_code").val()); 
        var type_offer   = $.trim($("input[name='type_offer']:checked").val()); 
        var offer_mode   = $.trim($("input[name='offer_mode']:checked").val()); 
        var offer_value  = $.trim($("#offer_value").val()); 
        var from_date    = $.trim($("#voucher_from").val());       
        var to_date      = $.trim($("#voucher_to").val());    
        var eligible_points      = $.trim($("#eligible_points").val());    
        var editid       = $.trim($("#editid").val());  
        $('.error').html('');

        if(voucher_code == '') {
            $('.codeErr').addClass('error').html('Please enter voucher code');
            $("#voucher_code").focus();
            return false;
        }else if(type_offer == '') {
            $('.typeErr').addClass('error').html('Please select type of use');
            $("#single").focus();
            return false;
        }else if(offer_mode == '') {
            $('.modeErr').addClass('error').html('Please select type of offer');
            $("#free_delivery").focus();
            return false;               
        }else if( (offer_mode == 'price' || offer_mode == 'percentage') && offer_value == '') {
            $('.valueErr').addClass('error').html('Please enter offer value');
            $("#offer_value").focus();
            return false;               
        }else if(from_date == ''){
            $('.fromErr').addClass('error').html('Please select voucher from');
            $("#voucher_from").focus();
            return false;   
        }else if(to_date == ''){
            $('.toErr').addClass('error').html('Please select voucher to');
            $("#voucher_to").focus();
            return false;   
        }else{

            $.ajax({
                type   : 'POST',
                url    : jssitebaseurl+'vouchers/voucherCheck',
                data   : {id:editid, voucher_code:voucher_code},
                success: function(data){
                    if($.trim(data) == 0) {
                        $("#voucherAddEditForm").submit();
                    }else {                       
                        $(".codeErr").addClass('error').html('This voucher code already exists');
                        $("#voucher_code").focus();
                        return false;
                    }
                }
            });
           return false;            
        }
    }       

</script>