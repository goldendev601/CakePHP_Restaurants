<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = 'CakePHP: the rapid development php framework';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>  
    <?= $this->fetch('meta') ?>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
	<link rel="stylesheet" href="<?php echo BOWER_URL; ?>bootstrap/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo BOWER_URL; ?>font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?php echo BOWER_URL; ?>Ionicons/css/ionicons.min.css">
	<link rel="stylesheet" href="<?php echo BOWER_URL; ?>bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
	<link rel="stylesheet" href="<?php echo BOWER_URL; ?>iCheck/skins/all.css">
	<link rel="stylesheet" href="<?php echo BOWER_URL; ?>select2/dist/css/select2.min.css">
	<link rel="stylesheet" href="<?php echo BOWER_URL; ?>datatables/media/css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo BOWER_URL; ?>summernote/dist/summernote.css">
	<link rel="stylesheet" href="<?php echo DIST_URL; ?>/css/backend.css">
</head>
<body class="hold-transition login-page">
	<?= $this->Flash->render() ?>
	<div class="login-box">
		<div class="login-logo">
			<a href=""><b>COMENEAT</b></a>
		</div>
		<div class="login-box-body">
			<p class="login-box-msg">Login</p>
			<form action="" method="post">
				<div class="form-group has-feedback">
					<input type="email" class="form-control" placeholder="Email">
					<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
				</div>
				<div class="form-group has-feedback">
					<input type="password" class="form-control" placeholder="Password">
					<span class="glyphicon glyphicon-lock form-control-feedback"></span>
				</div>
				<div class="form-group has-feedback clearfix">
					<div class="checkbox icheck">
						<label>
						<input class="minimal" type="checkbox"> Remember Me
						</label>
					</div>
				</div>
				<div class="form-group has-feedback clearfix">
						<button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
				</div>
			</form>
			<a href="#">I forgot my password</a>
		</div>
	</div>
	<script src="<?php echo BOWER_URL; ?>jquery/dist/jquery.min.js"></script>
	<script src="<?php echo BOWER_URL; ?>bootstrap/dist/js/bootstrap.min.js"></script>
	<script src="<?php echo BOWER_URL; ?>select2/dist/js/select2.full.min.js"></script>
	<script src="<?php echo BOWER_URL; ?>bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
	<script src="<?php echo BOWER_URL; ?>iCheck/icheck.min.js"></script>
	<script src="<?php echo BOWER_URL; ?>datatables/media/js/jquery.dataTables.min.js"></script>
	<script src="<?php echo BOWER_URL; ?>datatables/media/js/dataTables.bootstrap.min.js"></script>
	<script src="<?php echo BOWER_URL; ?>summernote/dist/summernote.min.js"></script>
	<script src="<?php echo DIST_URL; ?>js/backend.js"></script>
	<script src="<?php echo DIST_URL; ?>js/backend-common.js"></script>
</body>
</html>
