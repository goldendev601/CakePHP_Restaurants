<div class="content-wrapper">
    <section class="content-header">
        <h1> Add Country </h1>           
        
        <ol class="breadcrumb">
            <li>
              <a href="<?php echo ADMIN_BASE_URL ;?>dashboard">
                 <i class="fa fa-dashboard"></i> Home</a>
            </li>           
            <li class="active">
               <a href="<?php echo ADMIN_BASE_URL ;?>countries">Manage Country</a>
            </li>
        </ol>
    </section>

    <section class="content clearfix">
        <div class="col-xs-12">
            <div class="row">
                <div class="box my-box">
                    <div class="box-header with-border">
                        <h3 class="box-title"> 
                            <!-- Country -->
                        </h3>
                    </div>
                        <?php                            
                            echo $this->Form->create('countryAdd', [
                                'id' => 'countryAddForm'
                            ]);                                                   
                        ?>   

                        <div class="box-body">
                            <div class="form-group">
                                <label for="country_name" class="col-sm-2 control-label">Country Name<span class="star">*</span></label>
                                <div class="col-sm-4">
                                    <?= $this->Form->input('country_name',[
                                            'type' => 'text',
                                            'id'   => 'country_name',
                                            'class' => 'form-control',
                                            'placeholder' => 'Country Name',
                                            'label' => false
                                        ]) ?>
                                      <span class="countryErr"></span>    
                                </div>
                            </div>                            
                        </div>

                        <div class="box-body">
                            <div class="form-group">
                                <label for="currency_name" class="col-sm-2 control-label">Currency Name<span class="star">*</span></label>
                                <div class="col-sm-4">
                                    <?= $this->Form->input('currency_name',[
                                            'type' => 'text',
                                            'id'   => 'currency_name',
                                            'class' => 'form-control',
                                            'placeholder' => 'Currency Name',
                                            'label' => false
                                        ]) ?>
                                      <span class="currencyErr"></span>    
                                </div>
                            </div>                            
                        </div>

                        <div class="box-body">
                            <div class="form-group">
                                <label for="currency_code" class="col-sm-2 control-label">Currency Code<span class="star">*</span></label>
                                <div class="col-sm-4">
                                    <?= $this->Form->input('currency_code',[
                                            'type' => 'text',
                                            'id'   => 'currency_code',
                                            'class' => 'form-control',
                                            'placeholder' => 'Currency Code',
                                            'label' => false
                                        ]) ?>
                                      <span class="curcodeErr"></span>    
                                </div>
                            </div>                            
                        </div>

                        <div class="box-body">
                            <div class="form-group">
                                <label for="currency_symbol" class="col-sm-2 control-label">Currency Symbol<span class="star">*</span></label>
                                <div class="col-sm-4">
                                    <?= $this->Form->input('currency_symbol',[
                                            'type' => 'text',
                                            'id'   => 'currency_symbol',
                                            'class' => 'form-control',
                                            'placeholder' => 'Currency Symbol',
                                            'label' => false
                                        ]) ?>
                                      <span class="symbolErr"></span>    
                                </div>
                            </div>                            
                        </div>
    
                        <div class="box-body">
                            <div class="form-group">
                                <label for="phone_code" class="col-sm-2 control-label">Phone code<span class="star">*</span></label>
                                <div class="col-sm-4">
                                    <?= $this->Form->input('phone_code',[
                                            'type' => 'text',
                                            'id'   => 'phone_code',
                                            'class' => 'form-control',
                                            'placeholder' => 'Phone code',
                                            'label' => false
                                        ]) ?>
                                      <span class="phoneErr"></span>    
                                </div>
                            </div>                            
                        </div>

                        <div class="box-body">
                            <div class="form-group">
                                <label for="iso_code" class="col-sm-2 control-label">ISO code<span class="star">*</span></label>
                                <div class="col-sm-4">
                                    <?= $this->Form->input('iso_code',[
                                            'type' => 'text',
                                            'id'   => 'iso_code',
                                            'class' => 'form-control',
                                            'placeholder' => 'ISO code',
                                            'label' => false
                                        ]) ?>
                                      <span class="isoErr"></span>    
                                </div>
                            </div>                            
                        </div>

                        <div class="col-xs-12 no-padding m-t-20">
                             <button type="button" class="btn btn-info m-r-15" onclick="countryAddEdit();">Submit</button>
                            <a class="btn btn-default" href="<?php echo ADMIN_BASE_URL?>countries/">Cancel</a>
                           
                        </div>

                    <?= $this->Form->end();?>
                </div>
            </div>
        </div>
    </section>
</div>


<script type="text/javascript">

    function countryAddEdit(){
       
        var country_name    = $.trim($("#country_name").val());        
        var currency_name   = $.trim($("#currency_name").val());        
        var currency_code   = $.trim($("#currency_code").val());        
        var currency_symbol = $.trim($("#currency_symbol").val());        
        var phone_code      = $.trim($("#phone_code").val());        
        var iso_code        = $.trim($("#iso_code").val()); 
        $('.error').html('');

         if(country_name == '') {
            $('.countryErr').addClass('error').html('Please enter country name');
            $("#country_name").focus();
            return false;

        }else if(currency_name == '') {
            $('.currencyErr').addClass('error').html('Please enter currency name');
            $("#currency_name").focus();
            return false;
        
        }else if(currency_code == '') {
            $('.curcodeErr').addClass('error').html('Please enter currency code');
            $("#currency_code").focus();
            return false;
        
        }else if(currency_symbol == '') {
            $('.symbolErr').addClass('error').html('Please enter currency symbol');
            $("#currency_symbol").focus();
            return false;
        
        }else if(phone_code == '') {
            $('.phoneErr').addClass('error').html('Please enter phone code');
            $("#phone_code").focus();
            return false;
        
        }else if(iso_code == '') {
            $('.isoErr').addClass('error').html('Please enter iso code');
            $("#iso_code").focus();
            return false;
        
        }else {

            $.ajax({
                type   : 'POST',
                url    : jssitebaseurl+'countries/countryCheck',
                data   : {country_name:country_name},

                success: function(data){
                    if($.trim(data) == 0) {
                        $("#countryAddForm").submit();
                    }else {
                        $(".countryErr").addClass('error').html('This country name already exists');
                        $("#country_name").focus();
                        return false;
                    }
                }
            });
           return false;
        }
    }        
</script>