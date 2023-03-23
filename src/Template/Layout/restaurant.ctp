<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= 'Restaurant Admin' ?>

    </title>
    <?= $this->Html->meta('icon') ?>
    <script>
        var jssitebaseurl = "<?php echo REST_BASE_URL; ?>";        
    </script>
    <?= $this->fetch('meta') ?>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic"> -->
    <link rel="stylesheet" href="<?php echo BOWER_URL; ?>jquery-ui/jquery-ui.css">
    <link rel="stylesheet" href="<?php echo BOWER_URL; ?>bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo BOWER_URL; ?>font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo BOWER_URL; ?>Ionicons/css/ionicons.min.css">
    <link rel="stylesheet" href="<?php echo BOWER_URL; ?>bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="<?php echo BOWER_URL; ?>iCheck/skins/all.css">
    <link rel="stylesheet" href="<?php echo BOWER_URL; ?>select2/dist/css/select2.min.css">
    <link rel="stylesheet" href="<?php echo BOWER_URL; ?>datatables/media/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo BOWER_URL; ?>summernote/dist/summernote.css">
    <link rel="stylesheet" href="<?php echo DIST_URL; ?>/css/backend.css">
    <script src="<?php echo BOWER_URL; ?>jquery/dist/jquery.min.js"></script>

    <script src="<?php echo BOWER_URL; ?>bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="<?php echo BOWER_URL; ?>jquery-ui/jquery-ui.min.js"></script>
    <script src="<?php echo BOWER_URL; ?>select2/dist/js/select2.full.min.js"></script>
    <script src="<?php echo BOWER_URL; ?>bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="<?php echo DIST_URL; ?>js/enscroll.js"></script>
    <script src="<?php echo BOWER_URL; ?>iCheck/icheck.min.js"></script>
    <script src="<?php echo BOWER_URL; ?>datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo BOWER_URL; ?>datatables/media/js/dataTables.bootstrap.min.js"></script>
    <script src="<?php echo BOWER_URL; ?>summernote/dist/summernote.min.js"></script>
    <script src="<?php echo DIST_URL; ?>js/backend.js"></script>
    <script src="<?php echo DIST_URL; ?>js/backend-common.js"></script>

</head>
<body <?php if($controller == 'Users' &&  $action == 'login'){ ?> class="login-bg-color" <?php }?>>

<?php
echo $this->Form->input('isoCode', [
    'id' => 'isoCode',
    'type' => 'hidden',
    'class' => 'form-horizontal',
    'value'=> $countryCode
]);
?>



    <?= $this->Flash->render() ?>

    <span class="statusshow" style="display:none;"></span>

    <?php if(!empty($logginUser)) { ?>
        <div class="wrapper">

            <?php
                echo $this->element('restaurant/header');
            ?>

            <?=
            $this->element('restaurant/leftside');
            ?>


            <?= $this->fetch('content') ?>

        </div>
    <?php }else { ?>
        <?= $this->fetch('content') ?>

    <?php } ?>

    <input type="hidden" value="<?= $controller ?>" id="Controller">
    <input type="hidden" value="<?= $action ?>" id="Action">
    <input type="hidden" value="<?= isset($logginUser['id']) ? $logginUser['id'] : '' ?>" id="restId">

    <?php
    if($controller == 'Restaurants' &&  $action == 'index') {
        ?>
        <script src="<?php echo DIST_URL; ?>js/onlyaddress.js"></script>
    <?php } ?>

    <?php
    if($controller == 'Restaurants' &&  $action == 'index' || 
        $controller == 'Dispatches' &&  $action == 'index') {
        ?>
        <script src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyAql4yBAyykHUGfXRicgL5_1YH9-ZeWk3s&sensor=false&libraries=places,geometry,drawing"></script>
    <?php } ?>

    <script src="https://js.pusher.com/4.1/pusher.min.js"></script>

    <script>
        $(document).ready(function () {
            // Enable pusher logging - don't include this in production
            Pusher.logToConsole = true;
            var controller = $("#Controller").val();
            var Action = $("#Action").val();
            var restId = $("#restId").val();

            var pusher = new Pusher('<?php echo PUSHER_AUTHKEY ?>', {
                encrypted: true
            });

            var channel = pusher.subscribe('my-channel');
            channel.bind('my-event', function(data) {

                $(".statusshow").show();
                $(".statusshow").html(data.message);
                setTimeout(function(){
                    $(".statusshow").css("display",'none');
                },3000);

                if(controller == 'Orders' && (Action == 'index' || Action == 'collectionorder')) {
                    if(data.id == restId) {
                        showOrders();
                    }

                }
            });



            var channel = pusher.subscribe('my-channelCustomer');
            channel.bind('my-eventCustomer', function(data) {

                if(data.restaurant_id == restId) {

                    $(".statusshow").show();
                    $(".statusshow").html(data.message);
                    setTimeout(function(){
                        $(".statusshow").css("display",'none');
                    },3000);

                    showOrders();
                }

            });

            var channel = pusher.subscribe('my-channelDriver');
            channel.bind('my-eventDriver', function(data) {
                $(".statusshow").show();
                $(".statusshow").html(data.message);
                setTimeout(function(){
                    $(".statusshow").css("display",'none');
                },3000);
            });
        })

        function closemask(id) {
            if($(id).prop("checked") === true){
                $(id).closest(".form-group").find(".closed_mask").addClass("closed");
            }
            else{
                $(id).closest(".form-group").find(".closed_mask").removeClass("closed");
            }        
        };
    </script>

</body>
</html>
