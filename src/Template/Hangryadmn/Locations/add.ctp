<div class="content-wrapper">
    <section class="content-header">
        <h1> Add Location </h1>          
        
        <ol class="breadcrumb">
            <li>
              <a href="<?php echo ADMIN_BASE_URL ;?>dashboard">
                 <i class="fa fa-dashboard"></i> Home</a>
            </li>           
            <li class="active">
               <a href="<?php echo ADMIN_BASE_URL ;?>locations">Manage Location</a>
            </li>
        </ol>
    </section>

    <section class="content clearfix">
        <div class="col-xs-12">
            <div class="row">
                <div class="box my-box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><!-- Locations --></h3>
                    </div>
                        <?php
                            echo $this->Form->create('areaAdd', [
                                'id' => 'areaAddForm'
                            ]);                        
                        ?>                        
                        <div class="box-body">
                            <div class="form-group">
                                <label for="state_id" class="col-sm-2 control-label">State Name<span class="star">*</span></label>
                                <div class="col-sm-4">                                    
                                    <?= $this->Form->input('state_id',[
                                        'type' => 'select',
                                        'id'   => 'state_id',
                                        'class' => 'form-control',
                                        'options'=> $statelist,
                                        'onchange' => 'getCityList(this.value);',
                                        'empty'  => 'Select State Name',
                                        'label' => false
                                     ]) ?>                                      
                                    <span class="stateErr"></span>    
                                </div>
                            </div>                            
                        </div>

                        <div class="box-body">
                            <div class="form-group">
                                <label for="city_id" class="col-sm-2 control-label">City Name<span class="star">*</span></label>
                                <div class="col-sm-4">
                                   <div id="cityList">
                                      <?= $this->Form->input('city_id',[
                                            'type' => 'select',
                                            'id'   => 'city_id',
                                            'class' => 'form-control',
                                            'empty'  => 'Select City Name',
                                            'label' => false
                                        ]); ?>
                                    </div>    
                                  <span class="cityErr"></span>    
                                </div>
                            </div>                            
                        </div>

                        <div class="box-body">
                            <div class="form-group">
                                <label for="area_name" class="col-sm-2 control-label">Area Name</label>
                                <div class="col-sm-4">
                                    <?= $this->Form->input('area_name',[
                                            'type' => 'text',
                                            'id'   => 'area_name',
                                            'class' => 'form-control',
                                            'placeholder' => 'Area Name',
                                            'label' => false
                                        ]) ?>
                                      <span class="areaErr"></span>    
                                </div>
                            </div>                            
                        </div>

                        <div class="box-body">
                            <div class="form-group">
                                <label for="zip_code" class="col-sm-2 control-label">Zipcode</label>
                                <div class="col-sm-4">
                                    <?= $this->Form->input('zip_code',[
                                            'type' => 'text',
                                            'id'   => 'zip_code',
                                            'class' => 'form-control',
                                            'placeholder' => 'Zipcode',
                                            'label' => false
                                        ]) ?>
                                      <span class="zipcodeErr"></span>    
                                </div>
                            </div>                            
                        </div>

                        <div class="col-xs-12 no-padding m-t-20">
                            <button type="button" class="btn btn-info m-r-15" onclick="locationAddEdit();">Submit</button>
                            <a class="btn btn-default" href="<?php echo ADMIN_BASE_URL?>locations/">Cancel</a>
                            
                        </div>
                    <?= $this->Form->end();?>
                </div>
            </div>
        </div>
    </section>
</div>


<script type="text/javascript">
 function getCityList(id){  
        if(id != ''){
            $.ajax({
                type   : 'POST',
                url    : jssitebaseurl+'locations/ajaxaction',
                data   : {state_id:id, action: 'getCityList'},
                success: function(data){                
                    $('#cityList').html(data);
                    return false;
                }
            });
           return false;
        }  
    }

   function locationAddEdit(){
           
        var state_id   = $.trim($("#state_id").val());  
        var city_id    = $.trim($("#city_id").val());        
        var area_name  = $.trim($("#area_name").val());
        $('.error').html('');

        if(state_id == '') {
            $('.stateErr').addClass('error').html('Please select state name');
            $("#state_id").focus();
            return false;
        }else if(city_id == '') {
            $('.cityErr').addClass('error').html('Please select city name');
            $("#city_id").focus();
            return false;
        }else if(area_name != '') {
            $.ajax({
                type   : 'POST',
                url    : jssitebaseurl+'locations/locationCheck',
                data   : {city_id:city_id, area_name:area_name},
                success: function(data){
                    if($.trim(data) == 0) {
                        $("#areaAddForm").submit();
                    }else {
                        $(".areaErr").addClass('error').html('This city name already exists');
                        $("#area_name").focus();
                        return false;
                    }
                }
            });
            return false;
        }else {
            $("#areaAddForm").submit();
        }
    }          
</script>