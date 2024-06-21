<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
$userdata=$this->session->userdata('login_success'); 
$checkcpass=$userdata[0]->is_password;
$username=$userdata[0]->username;
$userid=$userdata[0]->id;
?>
<div class="page-content" style="margin-top: 110px;"> <!-- Close in footer bar-->
    <div class="sidebar sidebar-dark sidebar-main sidebar-expand-md" style="z-index:0">
    	<div class="sidebar-mobile-toggler text-center">
    		<a href="#" class="sidebar-mobile-main-toggle"><i class="icon-arrow-left8"></i></a>
    		Navigation
    		<a href="#" class="sidebar-mobile-expand">
    			<i class="icon-screen-full"></i>
    			<i class="icon-screen-normal"></i>
    		</a>
    	</div>
    	<div class="sidebar-content">
    		<div class="sidebar-user" >
    			<div class="card-body">
    				<div class="media">
    					<div class="mr-1">
    						<ul class="navbar-nav">
                				<li class="nav-item">
                					<a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
                						<i class="icon-paragraph-justify3"></i>
                					</a>
                				</li>
                			</ul>
    					</div>
    					<div class="media-body">
    						<div class="media-title font-weight-semibold"><?php echo $userdata[0]->username; ?></div>
    						<div class="font-size-xs opacity-50">
    							<i class="icon-pin font-size-sm"></i> &nbsp;<?php echo $userdata[0]->country; ?>
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>

 
 
 
				<div class="card card-sidebar-mobile">
					<ul class="nav nav-sidebar" data-nav-type="accordion">
					<li class="nav-item nav-item-submenu"><a href="<?php echo base_url(); ?>dashboard" class="nav-link"><i class="icon-copy"></i> <span>Dashboard</span></a></li>
					<?php
                    $menu_list =$this->efiling_model->menu_list($userid,$username); 
                    if(!empty($menu_list) && is_array($menu_list)) {
                        foreach($menu_list as $key=>$value) { 
                        ?>
						<li class="nav-item nav-item-submenu">
							<a href="#" class="nav-link"><i class="icon-copy"></i> <span><?php echo $key; ?> </span></a>
							<ul class="nav nav-group-sub" data-submenu-title="Layouts">    
							<?php  if(!empty($value) && is_array($value)) { 
                                foreach($value as $sub_menu) { 
                                    $menu = $sub_menu['page_name'];
                                ?>							
        						<a href="<?php echo  base_url(); ?><?php echo $menu; ?>" class="nav-link"><i class="icon-copy"></i> <span><?php echo $sub_menu['name']; ?> </span></a></li>
							<?php } }?>
							</ul>
						</li>
					 <?php } } ?>
					</ul>
				</div>
			</div>	
 		</div>
 		
 		<?php 
 		$salt= $this->session->userdata('salt'); 
 		?>
 		<div class="content-wrapper"> 
 		<!-- Close in footer bar-->
			<div class="page-header page-header-light">
				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="index.html" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
							<span class="breadcrumb-item active">Dashboard</span>
						</div>
						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>
					<div class="header-elements d-none">
						<div class="breadcrumb justify-content-center">
							<div class="breadcrumb-elements-item dropdown p-0">
                                	<input type="text" class="form-control text-success" name="droft_no" id="droft_no"  value="REFF-<?php echo $salt; ?>" style="border: 0 none;width: 120px; float:right;font-weight:600;" readonly />
							</div>
						</div>
					</div>
				</div>
			</div>