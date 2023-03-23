<div class="content-wrapper">
    <section class="content-header">
        <h1> Edit Addon</h1>
        <ol class="breadcrumb">
            <li> <a href="<?php echo ADMIN_BASE_URL ;?>dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"> <a href="<?php echo ADMIN_BASE_URL ;?>addons">Manage Addon</a></li>
        </ol>
    </section>

    <section class="content clearfix">
        <div class="col-xs-12">
            <div class="row">
                <div class="box my-box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><!-- Addons --></h3>
                        <span class="commonErr"></span>
                    </div>

                    <?php
                        if(!empty($allAddons)) {
                            echo $this->Form->create($allAddons, [
                                'id' => 'addonEditForm'
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
                        <div class="col-xs-12 form-group clearfix">
                            <label for="category_id" class="col-sm-2 control-label">Category Name<span class="star">*</span></label>
                            <div class="col-sm-4">
                                <?= $this->Form->input('category_id',[
                                    'type' => 'select',
                                    'id'   => 'category_id',
                                    'class' => 'form-control',
                                    'options'=> $categorylist,
                                    'value' => isset($allAddons['category_id']) ? $allAddons['category_id'] :  '',
                                    'empty'  => 'Select Category Name',
                                    'label' => false
                                ]); ?>
                                <span class="categoryErr"></span>
                            </div>
                        </div>

                        <div class="col-xs-12 form-group clearfix" id="mainAddons">
                            <label class="col-md-2 control-label">Addons Name <span class="star">*</span></label>
                            <div class="col-md-6 col-lg-3">
                                <?= $this->Form->input('mainaddons_name',[
                                    'type' => 'text',
                                    'id'   => 'mainaddons_name',
                                    'value' => isset($allAddons['mainaddons_name']) ? $allAddons['mainaddons_name'] :  '',
                                    'class' => 'form-control',
                                    'placeholder'  => 'Mainaddon Name',
                                    'label' => false
                                ]); ?>
                            </div>
                            <div class="col-md-6 col-lg-2">
                                <?= $this->Form->input('mainaddons_mini_count',[
                                    'type' => 'text',
                                    'id'   => 'mainaddons_mini_count',
                                    'value' => isset($allAddons['mainaddons_mini_count']) ? $allAddons['mainaddons_mini_count'] :  '',
                                    'class' => 'form-control mini_count',
                                    'placeholder'  => 'Mini Count',
                                    'label' => false
                                ]); ?>
                            </div>
                            <div class="col-md-6 col-lg-2">
                                <?= $this->Form->input('mainaddons_count',[
                                    'type' => 'text',
                                    'id'   => 'mainaddons_count',
                                    'value' => isset($allAddons['mainaddons_count']) ? $allAddons['mainaddons_count'] :  '',
                                    'class' => 'form-control mix_count',
                                    'placeholder'  => 'Count',
                                    'label' => false
                                ]); ?>
                            </div>

                            <div class="col-md-6 col-lg-2">
                                <a href="javascript:;" onclick="addSubAddons()" class="btn btn-success">Add Sub Addons</a>
                            </div>
                        </div>

                        <div class="col-xs-12" id="subAddonsList">
                           <?php if(!empty($allAddons)) {
                            foreach ($allAddons['subaddons'] as $key => $value) { ?>
                               <div class="form-group clearfix" id="removeSubaddon_<?php echo $value['id'];?>">
                                    <div class="col-md-6 col-lg-3 col-md-offset-2">
                                        <?= $this->Form->input('Main[subaddons]['.$key.'][subaddons_name]',[
                                            'type' => 'text',
                                            'id'   => 'subaddons_name_'.$value['id'],
                                            'value' => $value['subaddons_name'],
                                            'class' => 'form-control subadons  subName_'.$key,
                                            'placeholder'  => 'Subaddon Name',
                                            'label' => false
                                        ]); ?>
                                    </div>
                                    <div class="col-md-6 col-lg-2">
                                        <?= $this->Form->input('Main[subaddons]['.$key.'][subaddons_price]',[
                                            'type' => 'text',
                                            'id'   => 'subaddons_price_'.$value['id'],
                                            'value' => $value['subaddons_price'],
                                            'class' => 'form-control subadonsprice subPrice',
                                            'placeholder'  => 'Price',
                                            'label' => false
                                        ]); ?>
                                    </div>
                                  <?php if(!empty($key !=0)) { ?>
                                       <div class="col-md-6 col-lg-2">
                                           <a class="btn btn-danger" href="javascript:;" onclick="removeSubAddons('<?php echo $value['id']; ?>');">X</a>
                                       </div>
                                   <?php } ?>
                               </div>
                            <?php }
                              }
                              ?>
                        </div>
                    </div>

                    <div class="col-xs-12 no-padding m-t-20">
                         <button type="button" class="btn btn-info m-r-15" onclick="addonAddEdit();">Submit</button>
                        <a class="btn btn-default" href="<?php echo ADMIN_BASE_URL?>addons/">Cancel</a>
                       
                    </div>
                    <?= $this->Form->end();?>
                </div>
            </div>
        </div>
    </section>
</div>


<script type="text/javascript">

    var sub = 0;
    function addSubAddons(){
        //alert($('.subName_'+sub+'').length);
        if($('.subName_'+sub+'').length !== 0) {
            sub++;
            addSubAddons();
            return false;
        }
        var cat = $.trim($('#category_id').val());
        var addon = $.trim($('#mainaddons_name').val());
        var mini  = parseInt($('#mainaddons_mini_count').val());
        var maxi  = parseInt($('#mainaddons_count').val());

        if(cat == ''){
            alert('Please select category name');
            return false;
        }

        if(addon == ''){
            alert('Please enter main addons name');
            return false;
        }
        if(mini  < maxi) {
            $('#subAddonsList').append(
                '<div class="form-group clearfix" id="removeSubaddon_'+sub+'">'+
                '<div class="col-md-6 col-lg-3 col-md-offset-2">'+
                '<input type="text" id="subaddons_name_'+sub+'" class="form-control subName" name="Main[subaddons]['+sub+'][subaddons_name]" placeholder="Subaddon Name" >'+
                '</div>'+
                '<div class="col-md-6 col-lg-2">'+
                '<input type="text" id="subaddons_price_'+sub+'" class="form-control subPrice" name="Main[subaddons]['+sub+'][subaddons_price]" placeholder="Price">'+
                '</div>'+
                '<div class="col-md-6 col-lg-2">'+
                '<a href="javascript:;" onclick="removeSubAddons('+sub+');" class="btn btn-danger">X</a>'+
                '</div>'+
                '</div>'
            );
            sub++;

        } else {
            alert('Check your addons minimum maximum count');
            return false;
        }
    }

    function removeSubAddons(id){
        $('#removeSubaddon_'+id).remove();
        return false;
    }

    function addonAddEdit() {

        var catId = $.trim($("#category_id").val());
        var name  = $.trim($("#mainaddons_name").val());
        var min   = $.trim($("#mainaddons_mini_count").val());
        var max   = $.trim($("#mainaddons_count").val());
        var editid   = $.trim($("#editid").val());
        var subName  = $(".subadons").length;
        var subPrice = $(".subadonsprice").length;
        $('.error').html('');

        if (catId == '') {
            $(".commonErr").addClass('error').html('Please select category name');
            $("#category_id").focus();
            return false;
        } else if (name == '') {
            $(".commonErr").addClass('error').html('Please enter addons name');
            $("#mainaddons_name").focus();
            return false;
        } else if (min == '') {
            $(".commonErr").addClass('error').html('Please enter mini count');
            $("#mainaddons_mini_count").focus();
            return false;
        } else if (isNaN(min) || min < 0) {
            $(".commonErr").addClass('error').html('Please enter valid mini count');
            $("#mainaddons_mini_count").focus();
            return false;
        } else if (max == '') {
            $(".commonErr").addClass('error').html('Please enter main count');
            $("#mainaddons_count").focus();
            return false;
        } else if (isNaN(max) || max <= 0) {
            $(".commonErr").addClass('error').html('Please enter valid main count');
            $("#mainaddons_count").focus();
            return false;
        } else if (min > max) {
            $(".commonErr").addClass('error').html('Please enter mini addon count lessthan are equal main addon count');
            $("#mainaddons_mini_count").focus();
            return false;
        } else if (subName > 0) {

            if (subName > 0) {
                var N = subName;
                $(".subadons").each(function () {
                    if ($(this).val() == '') {
                        $("." + this.id).addClass('error').html("Please enter subaddons name");
                        $(this).focus();
                        return false;
                    } else {
                        N--;
                    }
                });
            }
            if (subPrice > 0) {
                var P = subPrice;
                $(".subadonsprice").each(function () {
                    if ($(this).val() == '') {
                        $("." + this.id).addClass('error').html("Please enter subaddons price");
                        $(this).focus();
                        return false;
                    } else if ($(this).val() <= 0) {
                        $("." + this.id).addClass('error').html("price should be greater than zero!");
                        $(this).focus();
                        return false;

                    } else {
                        P--;
                    }
                });
            }

            if (N == 0 && P == 0) {
                $.ajax({
                    type: 'POST',
                    url: jssitebaseurl+'addons/addonCheck',
                    data: {id: editid, category_id: catId, mainaddons_name: name},
                    success: function (data) {
                        if ($.trim(data) == 0) {
                            $("#addonEditForm").submit();
                        } else {
                            $(".commonErr").addClass('error').html('This addon name already exists');
                            $("#mainaddons_name").focus();
                            return false;
                        }
                    }
                });
                return false;
            }
        }
    }

</script>