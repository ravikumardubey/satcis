<?php
	if(!$this->session->userdata('login_success')) {
		redirect(base_url(), 'refresh');
		exit();	
	}
	$userdata=$this->session->userdata('login_success');
	$fname=ucfirst($userdata[0]->username);
?>
<!DOCTYPE html>
<html ng-app="my_app">
<head>
	 <meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>e_filing Dashboard</title>
	<link href="<?=base_url('asset/admin_css_final/styles.css')?>" rel="stylesheet">
	<link href="<?=base_url('asset/admin_css_final/bootstrap.min.css')?>" rel="stylesheet">
	<link href="<?=base_url('asset/admin_css_final/bootstrap_limitless.min.css')?>" rel="stylesheet">
	<link href="<?= base_url('asset/APTEL_files/jquery-confirm.css');?>" rel="stylesheet">	
	<link href="<?=base_url('asset/admin_css_final/layout.min.css')?>" rel="stylesheet">
	<link href="<?=base_url('asset/admin_css_final/components.min.css')?>" rel="stylesheet">
	<link href="<?=base_url('asset/admin_css_final/colors.min.css')?>" rel="stylesheet">
    <link href="<?= base_url('asset/APTEL_files/fontawesome.css'); ?>" rel="stylesheet">
    <link href="<?= base_url('asset/admin_css_final/customs.css'); ?>" rel="stylesheet">
    <link href="<?= base_url('asset/admin_css_final/dataTables.bootstrap4.min.css'); ?>" rel="stylesheet">
	<link href="<?= base_url('asset/admin_css_final/buttons.dataTables.min.css'); ?>" rel="stylesheet">
	<link href="<?= base_url('asset/admin_css_final/jquery.dataTables.min.css'); ?>" rel="stylesheet">	
	<script src="<?=base_url('asset/admin_js_final/ckeditor.js')?>"></script>
	<script src="<?=base_url('asset/admin_js_final/jquery.min.js')?>"></script>
	<link href="<?=base_url('asset/admin_css_final/css.css')?>" rel="stylesheet">
     <link href="<?=base_url('asset/admin_css_final/main.css')?>" rel="stylesheet">
     <script type="text/javascript" src="<?= base_url('asset/APTEL_files/jquery.dataTables.min.js'); ?>"></script>
    <script type="text/javascript" src="<?= base_url('asset/APTEL_files/dataTables.bootstrap.min.js');?>"></script>
    <script type="text/javascript" src="<?= base_url('asset/APTEL_files/angular.min.js'); ?>"></script>
    <script type="text/javascript" src="<?= base_url('asset/APTEL_files/angular-datatables.min.js'); ?>"></script>
    <script type="text/javascript" src="<?= base_url('asset/APTEL_files/user_list.js'); ?>"></script>
</head>	

<body>
	<header style="background: #fff">
		<div class="upper">
			<div class="inner">
				<img src="<?= base_url('asset/APTEL_files/sat-logo.png');?>" class="left_logo">
				<div class="right_logo">
					<!-- <img src="<?= base_url('asset/APTEL_files/logo_header.png');?>" class="text-center"> -->
					<img src="<?= base_url('asset/APTEL_files/logo_header2.png');?>" class="text-right" style="height:44px;">
				</div>
			</div>
		</div>
		<div class="lower">
			<nav>
				<ul class="lt">
					<li><a href="">Home</a></li>
				</ul>
				<ul class="rt">
					<li class="hassubmenu"><a href="">Welcome, <?= strtoupper($fname); ?></a>
						<ul>
							<li><a href="<?php echo base_url(); ?>myprofile">My Profile</a></li>
							<li><a href="<?php echo base_url(); ?>editprofile">Edit Profile</a></li>
							<li><a href="<?php echo base_url(); ?>change_password" data-value="change_password">Change&nbsp;Password</a></li>
							<li><a href="<?php echo base_url(); ?>logout">Logout</a></li>
						</ul>
					</li>
					<!-- <li><a href="<?php echo base_url(); ?>logout">Logout</a></li> -->
				</ul>
			</nav>
		</div>
	</header>