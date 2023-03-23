<div class="content-wrapper">
    <section class="content-header">
        <h1> Add Coupon </h1> 
        <ol class="breadcrumb">
            <li>
              <a href="<?php echo REST_BASE_URL ;?>dashboard">
                 <i class="fa fa-dashboard"></i> Home</a>
            </li>           
            <li class="active">
               <a href="<?php echo REST_BASE_URL ;?>coupons">Manage Coupon</a>
            </li>
        </ol>
    </section>

    <section class="content clearfix">
        <div class="col-xs-12">
            <div class="row">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><!-- Coupons --></h3>
                    </div>
                        <?php
                            echo $this->Form->create('couponAdd', [
                                'id' => 'couponAddForm'
                            ]);
                            echo $this->Form->input('resid',[
                                'type' => 'hidden',
                                'id'   => 'resid',
                                'class' => 'form-control',
                                'placeholder' => 'Please Select Restaurant',
                                'value' => !empty($resList['id']) ? $resList['id'] : '',
                                'label' => false
                            ]);
                        ?> 

                        <div class="box-body">
                            <div class="form-group">
                                <label for="coupon_code" class="col-sm-2 control-label">Coupon Code</label>
                                <div class="col-sm-4">
                                    <?= $this->Form->input('coupon_code',[
                                            'type' => 'text',
                                            'id'   => 'coupon_code',
                                            'class' => 'form-control',
                                            'placeholder' => 'Please Enter Coupon Code',
                                            'label' => false
                                        ]) ?>
                                      <span class="codeErr"></span>    
                                </div>
                            </div>                            
                        </div>
                        
                       <div class="box-body">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Coupon Mode</label>
                                <div class="col-sm-4">                                  
                                   <label class="radio-inline no-padding-left">
                                        <input id="single" type="radio" name="coupon_type" class="minimal" value="single" checked="checked">
                                        Single
                                    </label>
                                    <label class="radio-inline">
                                        <input id="multiple" type="radio" name="coupon_type" class="minimal" value="multiple">
                                        Multiple
                                    </label> 
                                    <span class="typeErr"></span>                                   
                                </div>
                            </div>
                        </div>  

                        <div class="box-body">
                            <div class="form-group">
                                <label for="coupon_offer" class="col-sm-2 control-label">Coupon Offer</label>
                                <div class="col-sm-4">
                                    <?= $this->Form->input('coupon_offer',[
                                            'type' => 'text',
                                            'id'   => 'coupon_offer',
                                            'class' => 'form-control',
                                            'placeholder' => 'Please Enter Coupon offer Amount',
                                            'label' => false
                                        ]) ?>
                                      <span class="couponOfferErr"></span>    
                                </div>
                            </div>                            
                        </div>

                        <div class="box-body">
                            <div class="form-group">
                                <label for="coupon_offer" class="col-sm-2 control-label">Eligible to Purchase Coupon</label>
                                <div class="col-sm-4">
                                    <?= $this->Form->input('eligible_points',[
                                            'type' => 'text',
                                            'id'   => 'eligible_points',
                                            'class' => 'form-control',
                                            'placeholder' => 'Please Enter Eligible Points',
                                            'label' => false
                                        ]) ?>
                                      <span class="eligiblePointsErr"></span>    
                                </div>
                            </div>                            
                        </div>                                    
                        
                        <div class="box-footer">
                            <a class="btn btn-default" href="<?php echo REST_BASE_URL?>coupons/">Cancel</a>
                            <button type="button" class="btn btn-info pull-right" onclick="couponAdd();">Submit</button>
                        </div>
                    <?= $this->Form->end();?>
                </div>
            </div>
        </div>
    </section>
</div>


<script type="text/javascript">   

    function couponAdd(){
       
        var coupon_code = $.trim($("#coupon_code").val()); 
        var coupon_mode   = $.trim($("input[name='coupon_type']:checked").val()); 
        var coupon_offer = $.trim($("#coupon_offer").val());       
        var eligible_points    = $.trim($("#eligible_points").val());       
        $('.error').html('');

        if(coupon_code == '') {
            $('.codeErr').addClass('error').html('Please enter coupon code');
            $("#coupon_code").focus();
            return false;
        }else if(coupon_mode == '') {
            $('.typeErr').addClass('error').html('Please select coupon type');
            $("#single").focus();
            return false;
        }else if(coupon_offer == ''){
            $('.couponOfferErr').addClass('error').html('Please enter coupon offer');
            $("#coupon_offer").focus();
            return false;   
        }else if(eligible_points == ''){
            $('.eligiblePointsErr').addClass('error').html('Please enter Eligible Points');
            $("#eligible_points").focus();
            return false;   
        } else {
            $("#couponAddForm").submit();
        }
    }       

</script>