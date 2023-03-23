<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Dashboard
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo ADMIN_BASE_URL ;?>dashboard">
                    <i class="fa fa-dashboard"></i> Home</a>
            </li>
            <li class="active">
                <a href="<?php echo ADMIN_BASE_URL ;?>menus">Manage Menu</a>
            </li>
        </ol>
    </section>
    <section class="content clearfix">
        <div class="col-xs-12">
            <div class="row">
                <div class="box my-box">
                    <div class="box-header with-border">
                        <h3 class="box-title"></h3>
                    </div>
                    <?php
                        echo $this->Form->create('menuAddForm', [
                            'id' => 'menuAddForm',
                            'class' => 'form-horizontal'
                        ]);
                    ?>
                        <div class="box-body">
                            <div class="form-group">
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

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Menu Name<span class="help">*</span></label>
                                <div class="col-sm-4">
                                    <?= $this->Form->input('menu_name',[
                                        'type' => 'text',
                                        'id'   => 'menu_name',
                                        'class' => 'form-control',
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
                                        'empty' => 'Please select a category',
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
                                        <input type="radio" name="menu_type" class="minimal" checked value="veg">
                                        Veg
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="menu_type" class="minimal" value="nonveg">
                                        Non Veg
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="menu_type" class="minimal" value="others">
                                        Others
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Price Option</label>
                                <div class="col-sm-4">
                                    <label class="radio-inline no-padding-left">

                                        <input type="radio" name="price_option" class="minimal"  value ="single" id="price-option-single" checked>
                                        Single
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="price_option" class="minimal" value="multiple" id="price-option-multiple">
                                        Mulitple
                                    </label>
                                    <span class="przOPtionErr"></span>
                                </div>
                            </div>

                            <div class="form-group" id="singlePrice">
                                <label class="col-sm-2 control-label">Menu Price<span class="help">*</span></label>
                                <div class="col-sm-4">
                                    <?= $this->Form->input('menu_price',[
                                        'type' => 'text',
                                        'id'   => 'menu_price',
                                        'class' => 'form-control menuPrice',
                                        'label' => false
                                    ]) ?>
                                </div>
                                <span class="priceErr"></span>
                            </div>

                            <div class="form-group" id="multiple" style="display: none">
                                <label class="col-md-3 control-label">&nbsp;</label>
                                <div class="col-md-8 col-lg-8">
                                    <div class="row addPriceTop">
                                        <div class="col-lg-7 margin-b-10">
                                            <div class="row">
                                                <div class="col-md-6">
                                                <?php
                                                    echo $this->Form->input('MenuDetail.sub_name',
                                                      ['class'=>'form-control multipleValidate multipleprice',
                                                        'placeholder'=>'Menu Name',
                                                        'id'=>'multiple_menu_0',
                                                        'data-attr'=>'product name',
                                                        'name' => 'data[MenuDetail][0][sub_name]',
                                                        'label'=>false
                                                      ]);
                                                ?>
                                                </div>
                                                <div class="col-md-3">
                                               <?php
                                                    echo $this->Form->input('MenuDetail.orginal_price',
                                                        ['class'=>'form-control multipleValidate menuPrice',
                                                            'placeholder'=>'Price',
                                                            'type' => 'text',
                                                            'id' => 'multiple_menuprice_0',
                                                            'data-attr'=>'original price',
                                                            'name' => 'data[MenuDetail][0][orginal_price]',
                                                            'label'=>false
                                                        ]); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="commonErr_0"></span>
                                    </div>
                                    <div id="moreOption"></div>
                                    <a class="addPrice btn green btn btn-success" href="javascript:void(0);" onclick="multipleOption();"><i class="fa fa-plus"></i> Add Price</a>
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-sm-2 control-label">Addons</label>
                                <div class="col-sm-4">
                                    <label class="radio-inline no-padding-left">
                                        <input type="radio" name="menu_addons" class="minimal" value="Yes" id="addonsyes">
                                        Yes
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="menu_addons" class="minimal" value="No" checked id="addonsno">
                                        No
                                    </label>
                                </div>
                            </div>

                            <div id="getShowAddons" class="col-xs-12"></div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Description</label>
                                <div class="col-sm-4">
                                    <textarea name="menu_description" class="form-control" placeholder="Enter Description"></textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Popular</label>
                                <div class="col-sm-4">
                                    <label class="radio-inline no-padding-left">
                                        <input type="radio" name="popular_dish" class="minimal" value="Yes" checked>
                                        Yes
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="popular_dish" class="minimal" value="No">
                                        No
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Spicy</label>
                                <div class="col-sm-4">
                                    <label class="radio-inline no-padding-left">
                                        <input type="radio" name="spicy_dish" class="minimal" value="Yes" checked>
                                        Yes
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="spicy_dish" class="minimal" value="No">
                                        No
                                    </label>
                                </div>
                            </div>

                            
                        </div>
                        <div class="col-xs-12 no-padding m-t-20">
                                <button class="btn btn-info m-r-15" type="submit" onclick=" return addMenu();">Submit</button>
                                <a class="btn btn-default" href="<?php echo ADMIN_BASE_URL ?>menus"> Cancel</a>
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
        var restaurant_id = $.trim($("#restaurant_id").val());
        var menu_name     = $.trim($("#menu_name").val());
        var category_id   = $.trim($("#category_id").val());
        var price_option  = $.trim($("input[name='price_option']:checked").val());
        var menu_price    = $.trim($("#menu_price").val());

        var menu_addons = $.trim($("input[name='menu_addons']:checked").val());

        if(restaurant_id == '') {
            $(".restErr").addClass('error').html('Please select restaurant');
            $("#restaurant_id").focus();
            return false;
        }else if(menu_name == '') {
            $(".menuErr").addClass('error').html('Please enter menu name');
            $("#menu_name").focus();
            return false;
        }else if(category_id == '') {
            $(".categoryErr").addClass('error').html('Please select category');
            $("#category_id").focus();
            return false;
        }else  if(price_option != '' && (price_option == 'single') && (menu_price == '') ) {
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
                    addonCount--;
                }

            });
        }else {
            var addonCount = 0;
        }


        if(error == 0 && menuLength == 0 && addonCount == 0){
            $.ajax({
                type   : 'POST',
                url    : jssitebaseurl+'menus/checkMenu',
                data   : {id:'', category_id:category_id,menu_name:menu_name,restaurant_id:restaurant_id},
                success: function(data){
                    if($.trim(data) == '1') {
                        $(".menuErr").addClass('error').html('This menu name already exists');
                        $("#menu_name").focus();
                        return false;
                    }else {
                        $("#menuAddForm").submit();
                    }
                }
            });return false;
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


    function getAddons(option) {
        var price_option     = $.trim($("input[name='price_option']:checked").val());
        var restaurant_id    = $.trim($("#restaurant_id").val()) ;
        var category_id      = $.trim($("#category_id").val()) ;
        var menu_name        = $.trim($("#menu_name").val()) ;

        if(category_id == ''){
            $(".categoryErr").addClass('error').html('Please enter the category name');
            $("#category_id").focus();
            return false;

        }else {
            if (option == 'Yes') {
                var $menuLength = '';
                if (price_option == 'multiple') {
                    $menuLength = $('.addPriceTop').length;
                }

                $.ajax({
                    type   : 'POST',
                    url    : jssitebaseurl+'menus/ajaxaction',
                    data   : {'productId' : '','restaurant_id' : restaurant_id,'category_id' : category_id,'price_option' : price_option,'menuLength' : $menuLength,'action' : 'getAddons'},
                    success: function(response){
                        $("#getShowAddons").html(response);
                        $('#getShowAddons').show();

                        if (price_option == 'multiple') {
                            var multipleLength = $('.multipleMenu').length;
                            var j = 0;
                            for (j = 1; j <= multipleLength; j++) {
                                appendMultipleSubAddons(j);
                            }
                        }
                    }
                });return false;

            } else {
                $('#getShowAddons').hide();
                return false;
            }
        }
    }
</script>