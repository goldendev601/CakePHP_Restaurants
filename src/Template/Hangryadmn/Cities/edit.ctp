<div class="content-wrapper">
    <section class="content-header">
        <h1>
             Edit City
        </h1>
        <ol class="breadcrumb">
            <li>
              <a href="<?php echo ADMIN_BASE_URL ;?>dashboard">
                 <i class="fa fa-dashboard"></i> Home</a>
            </li>           
            <li class="active">
              <a href="<?php echo ADMIN_BASE_URL ;?>cities">Manage City</a>
            </li>
        </ol>
    </section>

    <section class="content clearfix">
        <div class="col-xs-12">
            <div class="row">
                <div class="box my-box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><!-- Categories --></h3>
                    </div>
                        <?php
                            if(!empty($citiesList)) {
                                echo $this->Form->create($citiesList, [
                                    'id' => 'catEditForm'                                
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
                                <label for="country_id" class="col-sm-2 control-label">Country Name<span class="star">*</span></label>
                                <div class="col-sm-4">
                                  <?= $this->Form->input('country_id',[
                                        'type' => 'select',
                                        'id'   => 'country_id',
                                        'class' => 'form-control',
                                        'options'=> $countrylist,
                                        'empty'  => 'Select Country Name',
                                        'value'=> isset($citiesList['country_id']) ? $citiesList['country_id'] :  '',
                                        'onchange' => 'getStateList(this.value);',
                                        'label' => false
                                    ]); ?>
                                  <span class="countryErr"></span>    
                                </div>
                            </div>                            
                        </div>

                         <div class="box-body">
                            <div class="form-group">
                                <label for="state_id" class="col-sm-2 control-label">State Name<span class="star">*</span></label>
                                <div class="col-sm-4">
                                    <div id="stateList">
                                        <?= $this->Form->input('state_id',[
                                            'type' => 'select',
                                            'id'   => 'state_id',
                                            'class' => 'form-control',
                                            'empty'  => 'Select State Name',
                                            'options'=> $statelist,
                                            'value' => isset($citiesList['state_id']) ? $citiesList['state_id'] :  '',
                                            'label' => false
                                         ]) ?>
                                    </div>    
                                    <span class="stateErr"></span>    
                                </div>
                            </div>                            
                        </div>

                        <div class="box-body">
                            <div class="form-group">
                                <label for="city_name" class="col-sm-2 control-label">City Name<span class="star">*</span></label>
                                <div class="col-sm-4">
                                    <?= $this->Form->input('city_name',[
                                            'type' => 'text',
                                            'id'   => 'city_name',
                                            'class' => 'form-control',
                                            'placeholder' => 'City Name',
                                            'label' => false
                                        ]) ?>
                                      <span class="cityErr"></span>    
                                </div>
                            </div>                            
                        </div>

                        <div class="col-xs-12 no-padding m-t-20">
                             <button type="button" class="btn btn-info m-r-15" onclick="cityAddEdit();">Submit</button>
                            <a class="btn btn-default" href="<?php echo ADMIN_BASE_URL?>cities/">Cancel</a>
                           
                        </div>
                    <?= $this->Form->end();?>
                </div>
            </div>
        </div>
    </section>
</div>


<script type="text/javascript">

    function getStateList(id){         
        $.ajax({
            type   : 'POST',
            url    : jssitebaseurl+'cities/ajaxaction',
            data   : {country_id:id, action: 'getStateList'},
            success: function(data){                
                $('#stateList').html(data);
                return false;
            }
        });
       return false;        
    }

     function cityAddEdit(){

        var country_id = $.trim($("#country_id").val());        
        var state_id   = $.trim($("#state_id").val());       
        var city_name  = $.trim($("#city_name").val());   
        var editid     = $.trim($("#editid").val());         
       
        $('.error').html('');

        if(country_id == '') {
            $('.countryErr').addClass('error').html('Please select country name');
            $("#country_id").focus();
            return false;
        }else if(state_id == '') {
            $('.stateErr').addClass('error').html('Please select state name');
            $("#state_id").focus();
            return false;
        }else if(city_name == '') {
            $('.cityErr').addClass('error').html('Please enter state name');
            $("#city_name").focus();
            return false;
        }else {

            $.ajax({
                type   : 'POST',
                url    : jssitebaseurl+'cities/cityCheck',
                data   : {id:editid, state_id:state_id, city_name:city_name},
                success: function(data){
                    if($.trim(data) == 0) {
                        $("#catEditForm").submit();
                    }else {
                        $(".cityErr").addClass('error').html('This city name already exists');
                        $("#city_name").focus();
                        return false;
                    }
                }
            });
           return false;
        }
    }         
</script>