<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title><?= $siteSettings['meta_title'] ?> </title>
    <?= $this->Html->meta('icon') ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= $siteSettings['meta_description'] ?>" />
    <meta name="keywords" content="<?= $siteSettings['meta_keywords'] ?>" />
    <link rel="stylesheet" href="<?php echo BOWER_URL; ?>bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo BOWER_URL; ?>font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo DIST_URL; ?>/css/simple-line-icons.css">

    <?php if($controller == 'Myaccounts') { ?>
        <link rel="stylesheet" href="<?php echo DIST_URL; ?>/css/icomoon.css">
    <?php } ?>

    <link rel="stylesheet" href="<?php echo BOWER_URL; ?>jquery-ui/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="<?php echo DIST_URL; ?>/css/animate.css">
    <link rel="stylesheet" type="text/css" href="<?php echo DIST_URL; ?>/css/bootstrap-dropdownhover.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo DIST_URL; ?>/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="<?php echo BOWER_URL; ?>datatables/media/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo DIST_URL; ?>/css/style.css">
    <?php if($this->request->session()->read('languageCode') != 'EN') { ?>
        <link rel="stylesheet" type="text/css" href="<?php echo DIST_URL; ?>/css/style-rtl.css">
    <?php } ?>

    <script src="<?php echo BOWER_URL; ?>jquery/dist/jquery.min.js"></script>
    <script src="<?php echo BOWER_URL; ?>jquery-ui/jquery-ui.min.js"></script>
    <script src="<?php echo BOWER_URL; ?>bootstrap/dist/js/bootstrap.min.js"></script>

    <script src="<?php echo BOWER_URL; ?>datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo BOWER_URL; ?>datatables/media/js/dataTables.bootstrap.min.js"></script>
    <script src="<?php echo DIST_URL; ?>js/enscroll.js"></script>
    <script src="<?php echo DIST_URL; ?>js/slidemenu.js"></script>
    <script src="<?php echo DIST_URL; ?>js/bootstrap-select.min.js"></script>
    <script src="<?php echo DIST_URL; ?>js/bootstrap-dropdownhover.min.js"></script>
    <script src="<?php echo DIST_URL; ?>js/page-scroll.js"></script>
    <script src="<?php echo DIST_URL; ?>js/common.js"></script>

    <?php if($controller == 'Users' || $controller == 'Checkouts' || $controller == 'Customers') { ?>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAql4yBAyykHUGfXRicgL5_1YH9-ZeWk3s&libraries=places"></script>
    <?php } ?>

    <script>
        var jssitebaseurl = "<?php echo BASE_URL; ?>";
    </script>
    <?php echo ANALYTIC_CODE; ?>
</head>

<body class="">
    <?php
        echo $this->Form->input('isoCode', [
            'id' => 'isoCode',
            'type' => 'hidden',
            'class' => 'form-horizontal',
            'value'=> $countryCode
        ]);
    ?>

    <?= $this->Flash->render() ?>
    <div class="">
        <?php if(!isset($this->request->params['pass'][1]) && $this->request->params['pass'][1] != 'mobile') { ?>
            <?php
                echo $this->element('frontend/header');
            ?>
        <?php } ?>
        <?= $this->fetch('content') ?>
    </div>

    <?php if(!isset($this->request->params['pass'][1]) && $this->request->params['pass'][1] != 'mobile') { ?>
        <?php
            echo $this->element('frontend/footer');
        ?>
    <?php } ?>

    <div id="fb-root"></div>
    <script>
        if (window.location==top.location) {
            // console.log('Window');
        } else {
            $('body').addClass('fospluginwrapper');
            $("#widget-menu").show();
            $('.fosplugin').remove();
        }

        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.11&appId=181681299092370&autoLogAppEvents=1';
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>

    <script>
        $(document).ready(function(){
            var isMobile = {
                Android: function() {
                    return navigator.userAgent.match(/Android/i);
                },
                BlackBerry: function() {
                    return navigator.userAgent.match(/BlackBerry/i);
                },
                iOS: function() {
                    return navigator.userAgent.match(/iPhone|iPad|iPod/i);
                },
                Opera: function() {
                    return navigator.userAgent.match(/Opera Mini/i);
                },
                Windows: function() {
                    return navigator.userAgent.match(/IEMobile/i);
                },
                any: function() {
                    return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
                }
            };
            if( isMobile.Android() ){
                $('[data-os=android]').addClass("active").show();
            }
            if( isMobile.iOS() ){
                $('[data-os=ios]').addClass("active").show();
                $(".headercuis,.products .product__detail-title a").addClass("removepreline");
            }
            $(".app_close,.app-cont").click(function(){
                $(".mobileapp").removeClass("active").remove();
                setCookie("osType", "mobile", 365);
            });
            var osTypeVal = getCookie("osType");
            if (osTypeVal != "") {
                $(".mobileapp").removeClass("active").remove();
            }

            function setCookie(cname, cvalue, exdays) {
                var d = new Date();
                d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
                var expires = "expires="+d.toUTCString();
                document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
            }

            function getCookie(cname) {
                var name = cname + "=";
                var ca = document.cookie.split(';');
                for(var i = 0; i < ca.length; i++) {
                    var c = ca[i];
                    while (c.charAt(0) == ' ') {
                        c = c.substring(1);
                    }
                    if (c.indexOf(name) == 0) {
                        return c.substring(name.length, c.length);
                    }
                }
                return "";
            }
        });
    </script>
    <?php if(!isset($this->request->params['pass'][1]) && $this->request->params['pass'][1] != 'mobile') { ?>
        <!--Start of Zopim Live Chat Script-->
        <?php echo ZOPIM_CODE; ?>
        <!--End of Zopim Live Chat Script-->
    <?php } ?>
</body>
</html>
