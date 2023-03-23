<div class="content-wrapper">
    <section class="content-header">
        <h1>  Edit Menu </h1>

        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>
    <section class="content clearfix">
        <div class="col-xs-12">
            <div class="row">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"></h3>
                    </div>
                    <?php
                    echo $this->Form->create('menuAddForm', [
                        'id' => 'menuAddForm',
                        'class' => 'form-horizontal'
                    ]);
                    ?>
                    <input type="hidden" value="<?= $id ?>" name="editedId" id="resId">
                    <input type="hidden" value="<?= $resId ?>" name="restaurant_id" id="RestId">
                    <div class="box-body">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Menu Name<span class="help">*</span></label>
                            <div class="col-sm-4">
                                <?= $this->Form->input('menu_name',[
                                    'type' => 'text',
                                    'id'   => 'menu_name',
                                    'class' => 'form-control',
                                    'value' => $menuDetails['menu_name'],
                                    'label' => false
                                ]) ?>
                            </div>
                            <span class="menuErr"></span>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Category Name<span class="help">*</span></label>
                            <div class="col-sm-4">
                                <?= $this->Form->input('category_id',[
                                    'type' => 'select',
                                    'id'   => 'category_id',
                                    'class' => 'form-control',
                                    'options' => $categoryList,
                                    'value' => $menuDetails['category_id'],
                                    'label' => false
                                ]) ?>
                            </div>
                            <span class="categoryErr"></span>
                        </div>

                        <!-- radio -->
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Menu Type</label>
                            <div class="col-sm-4">
                                <label class="radio-inline no-padding-left">
                                    <input type="radio" name="menu_type" class="minimal" <?= ($menuDetails['menu_type'] == 'veg') ? 'checked' : '' ?> value="veg">
                                    Veg
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="menu_type" class="minimal" value="nonveg" <?= ($menuDetails['menu_type'] == 'nonveg') ? 'checked' : '' ?> >
                                    Non Veg
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="menu_type" class="minimal" value="other" <?= ($menuDetails['menu_type'] == 'other') ? 'checked' : '' ?>>
                                    Other
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Price Option</label>
                            <div class="col-sm-4">
                                <label class="radio-inline no-padding-left">
                                    <input type="radio" name="price_option" class="minimal" <?= ($menuDetails['price_option'] == 'single') ? 'checked' : '' ?> value="single" id="price-option-single">
                                    Single
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="price_option" class="minimal" <?= ($menuDetails['price_option'] == 'multiple') ? 'checked' : '' ?> value="multiple" id="price-option-multiple">
                                    Mulitple
                                </label>
                            </div>
                        </div>

                        <div class="form-group" id="singlePrice" style="<?php echo ($menuDetails['price_option'] != 'single') ? 'display:none' : ''; ?>">
                            <label class="col-sm-2 control-label">Menu Price<span class="help">*</span></label>
                            <div class="col-sm-4">
                                <?= $this->Form->input('menu_price',[
                                    'type' => 'text',
                                    'id'   => 'menu_price',
                                    'class' => 'form-control',
                                    'value' => $menuDetails['menu_details'][0]['orginal_price'],
                                    'label' => false
                                ]) ?>
                            </div>
                            <span class="priceErr"></span>
                        </div>

                        <div class="form-group" id="multiple" style="<?php echo ($menuDetails['price_option'] == 'multiple') ? 'display:block' : 'display:none'; ?>">
                            <label class="col-md-3 control-label">&nbsp;</label>
                            <div class="col-md-8 col-lg-8">
                                <?php if($menuDetails['price_option'] == 'multiple') {
                                    foreach ($menuDetails['menu_details'] as $mkey => $mvalue) { ?>
                                        <div id="moreProuct<?php echo $mkey; ?>" class="row addPriceTop">
                                            <div class="col-lg-7 margin-b-10">
                                                <div class="row">
                                                    <div class="col-md-6"><?php
                                                        echo $this->Form->input('MenuDetail.sub_name',
                                                            array('class'=>'form-control multipleValidate multipleprice',
                                                                'placeholder'=>'Menu Name',
                                                                'id' => 'multiple_menu_'.$mkey,
                                                                'name' => 'data[MenuDetail]['.$mkey.'][sub_name]',
                                                                'value' => $mvalue['sub_name'],
                                                                'label'=>false)); ?>
                                                    </div>
                                                    <div class="col-md-3"><?php
                                                        echo $this->Form->input('MenuDetail.orginal_price',
                                                            array('class'=>'form-control multipleValidate menuPrice',
                                                                'placeholder'=>'Price',
                                                                'type' => 'text',
                                                                'id' => 'multiple_menuprice_'.$mkey,
                                                                'data-attr'=>'original price',
                                                                'name' => 'data[MenuDetail]['.$mkey.'][orginal_price]',
                                                                'value' => $mvalue['orginal_price'],
                                                                'label'=>false)); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="commonErr_<?php echo $mkey ?>"></span>
                                        </div>

                                        <?php
                                    }
                                }else{ ?>
                                    <div class="row addPriceTop">
                                        <div class="col-lg-7 margin-b-10">
                                            <div class="row">
                                                <div class="col-md-6"><?php
                                                    echo $this->Form->input('MenuDetail.sub_name',
                                                        array('class'=>'form-control multipleValidate multipleprice',
                                                            'placeholder'=>'Menu Name',
                                                            'id' => 'multiple_menu_0',
                                                            'name' => 'data[MenuDetail][0][sub_name]',
                                                            'label'=>false)); ?>
                                                </div>
                                                <div class="col-md-3"><?php
                                                    echo $this->Form->input('MenuDetail.orginal_price',
                                                        array('class'=>'form-control multipleValidate menuPrice',
                                                            'placeholder'=>'Price',
                                                            'type' => 'text',
                                                            'id' => 'multiple_menuprice_0',
                                                            'data-attr'=>'original price',
                                                            'name' => 'data[MenuDetail][0][orginal_price]',
                                                            'label'=>false)); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div id="moreOption"></div>
                                <a class="addPrice btn green btn btn-success" href="javascript:void(0);" onclick="multipleOption();"><i class="fa fa-plus"></i> Add Price</a>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Popular</label>
                            <div class="col-sm-4">
                                <label class="radio-inline no-padding-left">
                                    <input type="radio" name="popular_dish" class="minimal" value="Yes" <?= ($menuDetails['popular_dish'] == 'Yes') ? 'checked' : '' ?> >
                                    Yes
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="popular_dish" class="minimal" value="No" <?= ($menuDetails['popular_dish'] == 'No') ? 'checked' : '' ?> >
                                    No
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Spicy</label>
                            <div class="col-sm-4">
                                <label class="radio-inline no-padding-left">
                                    <input type="radio" name="spicy_dish" class="minimal" value="Yes" <?= ($menuDetails['spicy_dish'] == 'Yes') ? 'checked' : '' ?> >
                                    Yes
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="spicy_dish" class="minimal" value="No" <?= ($menuDetails['spicy_dish'] == 'No') ? 'checked' : '' ?>>
                                    No
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Addons</label>
                            <div class="col-sm-4">
                                <label class="radio-inline no-padding-left">
                                    <input type="radio" name="menu_addons" class="minimal" value="Yes" <?= ($menuDetails['menu_addon'] == 'Yes') ? 'checked' : '' ?> id="addonsyes">
                                    Yes
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="menu_addons" class="minimal" value="No" <?= ($menuDetails['menu_addon'] == 'No') ? 'checked' : '' ?> id="addonsno">
                                    No
                                </label>
                            </div>
                        </div>

                        <div id="getShowAddons" class="col-xs-12"></div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Description</label>
                            <div class="col-sm-4">
                                <textarea name="menu_description" placeholder="Enter Description"><?php 
                                echo $menuDetails['menu_description'];?></textarea>
                            </div>
                        </div>                      
                    </div>
                     <div class="box-footer">
                             <a class="btn btn-default m-r-15" href="<?php echo REST_BASE_URL ?>menus"> Cancel</a>
                            <button class="btn btn-info" type="submit" onclick=" return addMenu();">Submit</button>
                        </div>
                    <?= $this->Form->end();?>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    function addMenu() {

        $(".error").html('');
        var error = 0;
        var menu_name    = $("#menu_name").val();
        var category_id  = $("#category_id").val();
        var price_option = $.trim($("input[name='price_option']:checked").val());
        var menu_price  = $("#menu_price").val();
        var resId       = $("#resId").val();
        var restaurant_id = $("#RestId").val();

        var menu_addons = $.trim($("input[name='menu_addons']:checked").val());

        if(menu_name == '') {
            $(".menuErr").addClass('error').html('Please enter menu name');
            $("#menu_name").focus();
            return false;
        }else if(category_id == '') {
            $(".categoryErr").addClass('error').html('Please select category');
            $("#category_id").focus();
            return false;
        }else if(price_option != '' && (price_option == 'single') && (menu_price == '') ) {
            $(".priceErr").addClass('error').html('Please enter the amount');
            $("#menu_price").focus();
            error = 1;
            return false;
        }else if(price_option != '' && price_option == 'multiple') {
            var menuLength = $('.multipleprice').length;
            $('.multipleprice').each(function () {
                var id = this.id;
                var key = id.split('_');

                if($("#"+id).val() == '') {
                    alert(key[2]);
                    $('.commonErr_'+key[2]).addClass('error').html('Please enter the name');
                    $("#"+id).focus();
                    return false
                }else if($("#multiple_menuprice_"+key[2]).val() == '') {
                    $('.commonErr_'+key[2]).addClass('error').html('Please enter the amount');
                    $("#multiple_menuprice_"+key[2]).focus();
                    return false
                }else {
                    menuLength--;
                    if(menuLength == 0) {
                        error = 0;
                    }
                }
            });
        }else {
            var menuLength = 0;
            error = 0;
        }

        if(menu_addons == 'Yes') {

            var addonCount = $(".mainAdd").length;
            $(".mainAdd").each(function () {
                var id = $(this).attr('data-category');
                var mini = $("#mini_"+id).val();

                var selectAddons = $(".subAdd_"+id+':checked').length;

                if(selectAddons < mini){
                    error ++;
                    $(".subaddonErr_"+id).addClass('error').html('You should select minimum '+ mini +' addons');
                    //$('.popupbutton').removeClass('dim');
                    return false;
                }else {
                    addonCount--
                }

            });
        }else {
            var addonCount = 0;
        }


        if(error == 0 && menuLength == 0 && addonCount == 0){
            $.ajax({
                type   : 'POST',
                url    : jssitebaseurl+'menus/checkMenu',
                data   : {id:resId, category_id:category_id,menu_name:menu_name,restaurant_id:restaurant_id},
                success: function(data){
                    if($.trim(data) == '1') {
                        $(".menuErr").addClass('error').html('This menu name already exists');
                        $("#menu_name").focus();
                        return false;
                    }else {
                        $("#menuAddForm").submit();
                    }
                }
            });
            return false;
        }
        return false;
    }


    var i = 1;
    function multipleOption() {
        if($("#multiple_menu_"+i).length != 0) {
            i++;
            multipleOption();
            return false;
        }
        html =  '<div id = "moreProuct'+i+'" class="row addPriceTop multipleMenu">'+
            '<div class="col-lg-7 margin-b-10">'+
            '<div class="row">'+
            '<div class="col-md-6">'+
            '<div class="input text">'+
            '<input type="text" id="multiple_menu_'+i+'" data-attr="product name" maxlength="100" placeholder="Menu Name" class="form-control multipleValidate multipleprice" name="data[MenuDetail][' + i + '][sub_name]">'+
            '</div>'+
            '</div>'+
            '<div class="col-md-3">'+
            '<div class="input number">'+
            '<input type="text" id="multiple_menuprice_'+i+'" data-attr="original price" step="any" placeholder="Price" class="form-control multipleValidate menuPrice" name="data[MenuDetail][' + i + '][orginal_price]">'+
            '</div>'+
            '</div>'+
            '<span class="ItemRemove btn btn-danger" onclick="removeOption('+i+');"><i class="fa fa-times"></i></span>'+
            '</div>'+
            '</div>'+
            '<span class="commonErr_'+i+'"></span>'+
            '</div>';
        appendMultipleSubAddons(i);
        i++;
        $('#moreOption').append(html);
        html = '';
        return false;
    }

    function appendMultipleSubAddons(removeId) {
        var multipleLength = $('.multipleMenu').length;
        var i = 1;

        $('.productAddonsMenu').each(function() {
            var subaddonName = $(this).attr('id');
            multipleAddon = '<div class="col-md-3 col-lg-2 removeAppendAddon_'+removeId+'">'+
                '<input class="form-control singleValidate" type="text" name="'+subaddonName+'[]">'+
                '</div>';
            $('#appendMultiplePrice_'+i).append(multipleAddon);
            i++;
        });
    }

    function removeOption(id) {
        $('#moreProuct' + id).remove();
        $('.removeAppendAddon_'+id).remove();
    }
    var option  = $.trim($("input[name='menu_addons']:checked").val());

    getAddons(option);
    $("#price-option-multiple").click(function () {
        $("#singlePrice").css('display','none');
        $("#multiple").css('display','block');
    });

    $("#price-option-single").click(function () {
        $("#multiple").css('display','none');
        $("#singlePrice").css('display','block');
    });

    function getAddons(option) {

        var category_id      = $.trim($("#category_id").val()) ;
        var editedId         = $.trim($("#resId").val()) ;
        var restaurant_id    = $.trim($("#RestId").val()) ;
        var price_option     = $.trim($("input[name='price_option']:checked").val());
        var menu_name        = $.trim($("#menu_name").val()) ;

        if(category_id == ''){
            $(".categoryErr").addClass('error').html('Please enter the category name');
            $("#category_id").focus();
            return false;

        }else {
            if (option == 'Yes') {
                var menuLength = '';
                if (price_option == 'multiple') {
                    menuLength = $('.addPriceTop').length;
                }

                $.ajax({
                    type   : 'POST',
                    url    : jssitebaseurl+'menus/ajaxaction',
                    data   : {'menuId' : editedId,'restaurant_id' : restaurant_id,'category_id' : category_id,'price_option' : price_option,'menuLength' : menuLength,'action' : 'getAddons'},
                    success: function(response){
                        if (price_option == 'multiple') {
                            var multipleLength = $('.multipleMenu').length;
                            var j = 0;
                            for (j = 1; j <= multipleLength; j++) {
                                appendMultipleSubAddons(j);
                            }
                        }
                        $("#getShowAddons").html(response);
                        $('#getShowAddons').show();
                    }
                });
                return false;

            } else {
                $('#getShowAddons').hide();
                return false;
            }
        }
    }
</script>