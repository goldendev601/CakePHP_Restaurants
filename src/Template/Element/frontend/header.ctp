<div class="pluginmobileapp mobileapp" data-os="android">
    <div class="logo-img-app">
        <span class="app_close">X</span>
        <span class="logoinstall"></span>
        <span class="app-cont"><?php echo __("Android Customer App");?><p><?php echo __("Get your app");?></p></span>
        <a class="app-get" href="https://play.google.com/store/apps/details?id=com.foodorderingsystem.app&hl=en"><?php echo __("GET");?></a>
    </div>
</div>

<div class="pluginmobileapp mobileapp" data-os="ios">
    <div class="logo-img-app">
        <span class="app_close">X</span>
        <span class="logoinstall"></span>
        <span class="app-cont"><?php echo __("iOS Customer App");?><p><?php echo __("Get your app");?></p></span>
        <a class="app-get" href="https://itunes.apple.com/in/app/comeneat-food-ordering-system/id1162551676?mt=8"><?php echo __("GET");?></a>
    </div>
</div>

<header class="<?php if($controller == 'Users' && $action == 'index'){ ?>top-header<?php } else{ ?> top-header2<?php } ?>">
    <div class="container">
        <?php
            if($this->request->session()->read('signed_request') == '') { ?>
            <?php if($controller == 'Users' && $action == 'index'){ ?>
                <a href="<?php echo BASE_URL ?>" class="logo fosplugin">
                    <!--Comeneat Logo Link-->
                    <!--<img src="https://res.cloudinary.com/dntzmscli/image/upload/v1518944119/logo.png">-->

                    <!--Live Link-->
                    <img src=<?php echo DRIVERS_LOGO_URL.'/uploads/siteImages/siteLogo/'.$siteSettings['site_logo'] ?>>
                </a>
            <?php } else{ ?>
                <a href="<?php echo BASE_URL ?>" class="logo2 fosplugin pul-right">
                    <!--<img src="https://res.cloudinary.com/dntzmscli/image/upload/v1518944119/logo.png">-->

                    <img src=<?php echo DRIVERS_LOGO_URL.'/uploads/siteImages/siteLogo/'.$siteSettings['site_logo'] ?>>
                </a>
            <?php } ?>
        <?php }else { ?>

        <?php } ?>

        <ul class="nav navbar-nav navbar-right pull-left ulclass hidden-xs" id="widget-menu" style="display:none;">
            <li><a href="<?php echo BASE_URL ?>menu/<?php echo $this->request->session()->read('restName') ?>"> <?php echo __('Menu', true); ?></a> </li>
        </ul>

        <ul class="<?php if($controller == 'Users' && $action == 'index'){ ?>headerlist <?php } else{ ?>headerlist2<?php } ?> pull-right hidden-xs pul-left">

            <?php if($controller == 'Menus') { ?>
                <li>
                    <a class="cartDropdown" href="javascript:void(0);"><i class="fa fa-shopping-cart"></i> <?= $siteSettings['site_currency'] ?> <span class="cartTotal"><?php echo number_format($subTotal,2) ?></span>
                    </a>
                </li>
            <?php } ?>
            <?php if($controller == 'Searches') { ?>
                <li><a href="<?= BASE_URL ?>users/changeLocation"><?php echo __('Change Location');?></a></li>
            <?php } ?>
            <input type="hidden" id="logginUser" value="<?php echo (!empty($logginUser['id'])) ? $logginUser['id'] : '' ?>" >
            <?php if(empty($logginUser) || $logginUser['role_id'] != '3') {  ?>
            <?php
                if($this->request->session()->read('signed_request') == '') { ?>
                    <li><a href="<?php echo BASE_URL.'users/signup';?>"><?php echo __('Sign Up');?></a></li>
                    <li><a href="<?php echo BASE_URL.'users/login';?>"><?php echo __('Login');?></a></li>
            <?php } ?>
            <?php }else { ?>
                <li><a href="<?php echo BASE_URL.'customers';?>"><?php echo __("My Account");?></a></li>
            <?php
                if($this->request->session()->read('signed_request') == '') { ?>
                    <li><a href="<?php echo BASE_URL.'users/logout';?>"><?php echo __("Logout");?></a></li>
            <?php } ?>
            <?php } ?>

            <?php

            if($siteSettings['multiple_language'] == 'Yes') { ?>

                <li class="dropdown pointer">
                    <?php
                    if(!empty($langDet)) {
                        foreach ($langDet as $key => $value) {
                            if ($value['language_default'] == 1) { ?>
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <?php echo $this->request->session()->read('languageCode');?> <i class="fa fa-caret-down"></i>
                                </a>
                            <?php
                            }
                        }
                    }?>

                    <ul class="dropdown-menu lang-drop">
                        <?php
                        if(!empty($langDet)) {
                            foreach ($langDet as $key => $value) {
                                if ($value['language_code'] != $this->request->session()->read('languageCode')) {?>
                                    <li>
                                        <a href="javascript:void(0)" onclick="return changeLanguage('<?php echo $value['language_code'];?>')"><?php echo $value['language_code'];?>
                                        </a>
                                    </li>
                                <?php
                                }
                            }
                        }?>
                    </ul>
                </li>
            <?php } ?>
        </ul>
        <?php if($action != 'apppage') { ?>
            <span id="showRightPush" class="navSlideMenu pull-right visible-xs pul-left">
                <i class="fa fa-bars"></i>
            </span>
        <?php } ?>

        <nav id="cbp-spmenu-s2" class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-right visible-xs">  
            <?php if($controller == 'Searches') { ?>
                <a href="<?= BASE_URL ?>users/changeLocation"><?php echo __('Change Location');?></a>
            <?php } ?>
            <?php if(empty($logginUser)) {  ?>
            <?php
                if($this->request->session()->read('signed_request') == '') { ?>
                    <a href="<?php echo BASE_URL.'users/signup';?>"><?php echo __('Sign Up');?></a>
                    <a href="<?php echo BASE_URL.'users/login';?>"><?php echo __('Login');?></a>
            <?php } ?>
            <?php }else { ?>
                <a href="<?php echo BASE_URL.'customers';?>"><?php echo __('My Account');?></a>
            <?php
                if($this->request->session()->read('signed_request') == '') { ?>
                    <a href="<?php echo BASE_URL.'users/logout';?>"><?php echo __('Logout');?></a>
            <?php } ?>
            <?php } ?>
        </nav>
    </div>
</header>


<script type="text/javascript">
    var menuRight = document.getElementById( 'cbp-spmenu-s2' ),
        showRightPush = document.getElementById( 'showRightPush' ),
        menuHideClass=document.getElementsByClassName('SlideMenuClose'),
        body = document.body;
    showRightPush.onclick = function() {
        classie.toggle( this, 'active' );
        classie.toggle( body, 'cbp-spmenu-push-toleft' );
        classie.toggle( menuRight, 'cbp-spmenu-open' );
        disableOther( 'showRightPush' );
        $("#showRightPush").toggleClass("toggle-close-icon");;
    };
    for (var i = 0; i < menuHideClass.length; i++){
        menuHideClass[i].onclick = function() {
            classie.toggle( this, 'active' );
            classie.toggle( body, 'cbp-spmenu-push-toleft' );
            classie.toggle( menuRight, 'cbp-spmenu-open' );
            disableOther( 'showRightPush' );
            jQuery("#showRightPush").show();
        };
    }
    
    function disableOther( button ) {
        if( button !== 'showRightPush' ) {
            classie.toggle( showRightPush, 'disabled' );
        }
    }

    if (window.location==top.location) {
        // console.log('Window');
    } else {
        $('.zopim').remove();
    }

    function changeLanguage(languageCode) {
        $.ajax({
            type   : 'POST',
            url    : jssitebaseurl+'Pages/languagechange',
            data   : {languageCode:languageCode},
            success: function(data){
               if(data == 'success') {
                    window.location.reload();
               } else if(data == 'success') {
                    alert('<?php echo __("Language code is empty");?>');
                    return false;
               }
            }
        });
        return false;
    }
</script>