<div class="content-wrapper">
    <section class="content-header">
        <h1> Customer Address Book</h1>        
        <ol class="breadcrumb">
            <li>
              <a href="<?php echo ADMIN_BASE_URL ;?>dashboard">
                 <i class="fa fa-dashboard"></i> Home</a>
            </li>           
            <li class="active">
               <a href="<?php echo ADMIN_BASE_URL ;?>customers">Manage Customer</a>
            </li>
        </ol>
    </section>

    <section class="content clearfix">
        <div class="col-xs-12">
            <div class="row">
                <div class="box my-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <!-- Customer -->
                        </h3>
                    </div>
                        <?php
                            if(!empty($addressbookList)) {
                                echo $this->Form->create($addressbookList,[
                                    'id' => 'customerAddressbookEditForm'                                
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
                                <label for="title" class="col-sm-2 control-label">Title<span class="star">*</span></label>
                                <div class="col-sm-4">
                                    <?= $this->Form->input('title',[
                                            'type' => 'text',
                                            'id'   => 'title',
                                            'class' => 'form-control',
                                            'placeholder' => 'Please Enter Title',
                                            'label' => false
                                        ]) ?>
                                      <span class="titleErr"></span>    
                                </div>
                            </div>                            
                        </div>

                        <div class="box-body">
                            <div class="form-group">
                                <label for="flat_no" class="col-sm-2 control-label">Flat No<span class="star">*</span></label>
                                <div class="col-sm-4">
                                    <?= $this->Form->input('flat_no',[
                                            'type' => 'text',
                                            'id'   => 'flat_no',
                                            'class' => 'form-control',
                                            'placeholder' => 'Please Enter Flat No',
                                            'label' => false
                                        ]) ?>
                                      <span class="flatNoErr"></span>    
                                </div>
                            </div>                            
                        </div>
                        <?php
                        if ($siteSettings['address_mode'] != 'Google') { ?>
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="address" class="col-sm-2 control-label">Address<span class="star">*</span></label>
                                    <div class="col-sm-4">
                                        <?= $this->Form->input('address',[
                                                'type' => 'text',
                                                'id'   => 'address',
                                                'class' => 'form-control',
                                                'placeholder' => 'Please Enter Address',
                                                'label' => false
                                            ]) ?>
                                          <span class="addressErr"></span>    
                                    </div>
                                </div>                            
                            </div>
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="landmark" class="col-sm-2 control-label">Landmark<span class="star">*</span></label>
                                    <div class="col-sm-4">
                                        <?= $this->Form->input('landmark',[
                                                'type' => 'text',
                                                'id'   => 'landmark',
                                                'class' => 'form-control',
                                                'placeholder' => 'Please Enter landmark',
                                                'label' => false
                                            ]) ?>
                                          <span class="landmarkErr"></span>    
                                    </div>
                                </div>                            
                            </div>
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="state_id" class="col-sm-2 control-label">State<span class="star">*</span></label>
                                    <div class="col-sm-4">
                                        <?= $this->Form->input('state_id',[
                                                'type' => 'select',
                                                'id'   => 'state_id',
                                                'class' => 'form-control',
                                                'options'=> array($stateList),
                                                'empty' => __('Please Select state'),
                                                'label' => false
                                            ]) ?>
                                          <span class="stateIdErr"></span>    
                                    </div>
                                </div>                            
                            </div>
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="city_id" class="col-sm-2 control-label">City<span class="star">*</span></label>
                                    <div class="col-sm-4">
                                        <?= $this->Form->input('city_id',[
                                                'type' => 'select',
                                                'id'   => 'city_id',
                                                'class' => 'form-control',
                                                'options'=> array($cityList),
                                                'onchange' => 'cityFillters();',
                                                'empty' => __('Please Select City'),
                                                'label' => false
                                            ]) ?>
                                          <span class="cityIdErr"></span>    
                                    </div>
                                </div>                            
                            </div>
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="location_id" class="col-sm-2 control-label">Location<span class="star">*</span></label>
                                    <div class="col-sm-4">
                                        <?= $this->Form->input('location_id',[
                                                'type' => 'text',
                                                'id'   => 'location_id',
                                                'class' => 'form-control',
                                                'onchange' => 'locationFillters();',
                                                'options'=> array($locationList),
                                                'empty' => __('Please Select Location'),
                                                'label' => false
                                            ]) ?>
                                          <span class="locationErr"></span>    
                                    </div>
                                </div>                            
                            </div>
                        <?php } else { ?>
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="address" class="col-sm-2 control-label">Address<span class="star">*</span></label>
                                    <div class="col-sm-4">
                                        <?= $this->Form->input('address',[
                                                'type' => 'text',
                                                'id'   => 'address',
                                                'class' => 'form-control',
                                                'placeholder' => 'Please Enter Address',
                                                'label' => false
                                            ]) ?>
                                          <span class="addressErr"></span>    
                                    </div>
                                </div>                            
                            </div>
                        <?php } ?>
                        <div class="col-xs-12 no-padding m-t-20">
                        <button type="submit" class="btn btn-info m-r-15" onclick="customerAddressbookAddEdit();">Submit</button>
                            <a class="btn btn-default" href="<?php echo ADMIN_BASE_URL?>customers">Cancel</a>
                            
                        </div>
                    <?= $this->Form->end();?>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    function customerAddressbookAddEdit(){
        var title   = $.trim($("#title").val());  
        var flat_no    = $.trim($("#flat_no").val());                
        var address = $.trim($("#address").val());                
        var editid       = $.trim($("#editid").val());              
        $('.error').html('');

        if(title == '') {
            $('.titleErr').addClass('error').html('Please enter Title');
            $("#title").focus();
            return false;
        }else if(flat_no == '') {
            $('.flatNoErr').addClass('error').html('Please enter Flat No');
            $("#flat_no").focus();
            return false;
        }else if(address == '') {
            $('.addressErr').addClass('error').html('Please enter Address');
            $("#address").focus();
            return false;
        }else {
            $.ajax({
                type   : 'POST',
                url    : jssitebaseurl+'customers/customerAddressCheck',
                data   : {id:editid, title:title},
                success: function(data){
                    if($.trim(data) == 0) {
                        $("#customerAddressbookEditForm").submit();
                    }else {
                        $(".titleErr").addClass('error').html('This Title already exists');
                        $("#title").focus();
                        return false;
                    }
                }
            });
           return false;
        }
    }
    function isNumberKey(evt){
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }

    //City Fillter Process
    function cityFillters() {
        var id = $('#city_id').val();
        $.ajax({
                type   : 'POST',
                url    : jssitebaseurl+'customers/cityfillter',
                data   : {id:id},
                success: function(response){
                     $("#city_id").html(response);
                }
            });
    }
    //Location Fillter Process
    function locationFillters() {
        var id = $('#location_id').val();
        $.ajax({
                type   : 'POST',
                url    : jssitebaseurl+'customers/locationfillter',
                data   : {id:id},
                success: function(response){
                     $("#location_id").html(response);
                }
            });
    }

 /*   function initialize($id) {
        autocomplete = new google.maps.places.Autocomplete(
        document.getElementById($id),
        {types: ['geocode']});
     
        google.maps.event.addListener(autocomplete, 'place_changed', function() {
            fillInAddress();
        });
    }

    
    function fillInAddress() {
        var place = autocomplete.getPlace();
        for (var i = 0; i < place.address_components.length; i++) {
        var addressType = place.address_components[i].types[0];
            if (!componentForm[addressType]) {
            } else {
                var val = place.address_components[i][componentForm[addressType]];
                document.getElementById(addressType).value = val;
            }
        }
    }*/
</script>