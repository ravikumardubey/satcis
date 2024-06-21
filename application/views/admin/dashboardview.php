<?php defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view("admin/header"); 
 $this->load->view("admin/sidebar"); 
 $userdata=$this->session->userdata('login_success');
 $userid=$userdata[0]->id;?>
<style>
.card-sidebar-mobile {
    padding: 0 0 !important;
}
.sidebar-user {
    margin-bottom: -5px;
}
.sidebar-user .card-body {
    padding: 10px 10px 0;
}
.navbar-nav-link {
    color: #fff;
}
.navbar-nav-link:hover {
    color: #fff;
}
.card {
    width: 100%;
    padding: 0px 12px;
    border-top: 0px;
    margin: 0px auto 40px auto;
    border-top-right-radius: 0px;
    border-top-left-radius: 0px;
    box-shadow: none;
    border: none;
}
.dbox {
    position: relative;
    background: rgb(231,227,210);
    background: -moz-linear-gradient(0deg, rgba(231,227,210,1) 0%, rgba(241,239,227,1) 100%);
    background: -webkit-linear-gradient(0deg, rgba(231,227,210,1) 0%, rgba(241,239,227,1) 100%);
    background: linear-gradient(0deg, rgba(231,227,210,1) 0%, rgba(241,239,227,1) 100%);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#e7e3d2",endColorstr="#f1efe3",GradientType=1);
    border-radius: 4px;
    text-align: center;
    margin: 20px 0 0;
    border: 1px solid #c1bdad;
    box-shadow: 3px 5px 8px rgba(0, 0, 0, 0.15);
}
.dbox.bg_alt {
    background: rgb(209,228,226);
    background: -moz-linear-gradient(0deg, rgba(209,228,226,1) 0%, rgba(238,255,253,1) 100%);
    background: -webkit-linear-gradient(0deg, rgba(209,228,226,1) 0%, rgba(238,255,253,1) 100%);
    background: linear-gradient(0deg, rgba(209,228,226,1) 0%, rgba(238,255,253,1) 100%);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#d1e4e2",endColorstr="#eefffd",GradientType=1);
    border: 1px solid #b7c5c3;
}
.dbox.dbox_inline {
    width: 33%;
    display: inline-block;
    margin-right: 15px;
}
.dbox__icon {
    position: absolute;
    transform: translateY(-50%) translateX(-50%);
    left: 50%;
    display: none;
}
.dbox__icon:before {
    width: 75px;
    height: 75px;
    position: absolute;
    background: #fda299;
    background: rgba(253, 162, 153, 0.34);
    content: '';
    border-radius: 50%;
    left: -17px;
    top: -17px;
    z-index: -2;
}
.dbox__icon:after {
    width: 60px;
    height: 60px;
    position: absolute;
    background: #f79489;
    background: rgba(247, 148, 137, 0.91);
    content: '';
    border-radius: 50%;
    left: -10px;
    top: -10px;
    z-index: -1;
}
.dbox__icon > i {
    background: #ff5444;
    border-radius: 50%;
    line-height: 40px;
    color: #FFF;
    width: 40px;
    height: 40px;
	font-size:22px;
}
.dbox__body {
    padding: 0px 20px 0px 20px;
}
.dbox__count {
    display: block;
    font-size: 20px;
    color: #8e4d46;
    font-weight: bold;
    padding: 6px 0;
    margin-bottom: 8px;
    text-shadow: 1px 1px 1px #fff;
}
.dbox__title {
    font-size: 13px;
    color: #263238;
    font-weight: bold;
    text-transform: uppercase;
    text-shadow: 1px 1px 1px #fff;
}
.dbox__action {
    transform: translateY(-140%) translateX(-50%);
    position: absolute;
    left: 50%;
}
.dbox__action__btn {
    border: none;
    background: #FFF;
    border-radius: 19px;
    padding: 0px 8px;
    text-transform: uppercase;
    font-weight: 500;
    font-size: 11px;
    letter-spacing: .5px;
    color: #003e85;
    box-shadow: 0 3px 5px #d4d4d4;
}

.dbox__action__btn .nav-link {
    padding: 0.4rem 1.25rem;
}


.dbox--color-2 {
    background: rgb(252, 190, 27);
    background: -moz-linear-gradient(top, rgba(252, 190, 27, 1) 1%, rgba(248, 86, 72, 1) 99%);
    background: -webkit-linear-gradient(top, rgba(252, 190, 27, 1) 1%, rgba(248, 86, 72, 1) 99%);
    background: linear-gradient(to bottom, rgba(252, 190, 27, 1) 1%, rgba(248, 86, 72, 1) 99%);
    filter: progid: DXImageTransform.Microsoft.gradient( startColorstr='#fcbe1b', endColorstr='#f85648', GradientType=0);
}
.dbox--color-2 .dbox__icon:after {
    background: #fee036;
    background: rgba(254, 224, 54, 0.81);
}
.dbox--color-2 .dbox__icon:before {
    background: #fee036;
    background: rgba(254, 224, 54, 0.64);
}
.dbox--color-2 .dbox__icon > i {
    background: #fb9f28;
}

.dbox--color-3 {
    background: rgb(183,71,247);
    background: -moz-linear-gradient(top, rgba(183,71,247,1) 0%, rgba(108,83,220,1) 100%);
    background: -webkit-linear-gradient(top, rgba(183,71,247,1) 0%,rgba(108,83,220,1) 100%);
    background: linear-gradient(to bottom, rgba(183,71,247,1) 0%,rgba(108,83,220,1) 100%);
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#b747f7', endColorstr='#6c53dc',GradientType=0 );
}
.dbox--color-3 .dbox__icon:after {
    background: #b446f5;
    background: rgba(180, 70, 245, 0.76);
}
.dbox--color-3 .dbox__icon:before {
    background: #e284ff;
    background: rgba(226, 132, 255, 0.66);
}
.dbox--color-3 .dbox__icon > i {
    background: #8150e4;
}

.infobox {
    list-style: none;
    padding: 0;
    text-align: left;
    width: 100%;
    margin: 10px auto 10px;
}
.infobox li {
    position: relative;
    padding-bottom: 6px;
}
.infobox li a, .infobox li span {
    color: #bf3139;
    display: inline-block;
    text-align: right;
    font-size: 13px;
    font-weight: bold;
    position: absolute;
    right: 0;
    top: 0;
}
.infobox li div {
    display: inline-block;
}
.infobox li div a {
    display: inline-block;
    text-align: left;
    position: relative;
}
.divider {
    border-top: 1px solid #c1bdad;
    border-bottom: 1px solid #fff;
    margin-top: -8px;
    margin-bottom: -2px;
}
.usericon {
    display: inline-block;
    border-radius: 50%;
    border: 1px solid #c1bdad;
    font-size: 40px;
    width: 90px;
    height: 90px;
    position: relative;
    margin-top: 29px;
    background: rgba(255, 255, 255, 0.50);
}
.usericon i {
    position: absolute;
    left: 0;
    right: 0;
    top: 50%;
    transform: translateY(-50%);
}
.username {
    padding-bottom: 15px;
    padding-top: 5px;
    font-weight: bold;
    color: #263238;
    text-shadow: 1px 1px 1px #fff;
    font-size: 14px;
}
</style>
<?php
        $userdata=$this->session->userdata('login_success');
        $userName=strtoupper(@$userdata[0]->fname.' '.@$userdata[0]->lname);


?>
<div class="content" style="padding-top:0px;">
	<div class="row">
		<div class="card">
            <div class="row">
               <div class="col-md-4">
                    <div class="dbox bg_alt">
                        <div class="dbox__body">
                            <div class="usericon">
                                <i class="fa fa-user"></i>
                            </div>
                            <div class="username"><?= $userName; ?></div>
                            <div class="divider"></div>
                            <ul class="infobox">
                                <li class="dbox__title">
                                    <div><a href="<?= base_url('myprofile') ?>">My Profile</a></div>
                                    <a href="<?= base_url('logout') ?>">Logout</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <?php 
                //RPEPCP
                $rpepcp_count=$this->efiling_model->data_list_where('rpepcp_reffrence_table','user_id',$userid);
                $rpepcp_count= count($rpepcp_count);
                ?>
                <div class="col-md-4">
                    <div class="dbox">
                        <div class="dbox__icon">
                            <i class="fa fa-user"></i>
                        </div>
                        <div class="dbox__body">
                            <span class="dbox__count">Draft Cases</span>
                            <div class="divider"></div>
                            <ul class="infobox">
                                <li class="dbox__title">Original Filing 
                                    <a href="javascript:void(0);" db-table="aptel_temp_appellant" ctype="all"><?php count($draft); ?></a>
                                </li>
                                <li class="dbox__title">RP
                                    <a href="javascript:void(0);"><?php echo count($rpepcp); ?></a>
                                </li>
                                <li class="dbox__title">Documents Filing <a href="javascript:void(0);" >0</a></li>
                                <li class="dbox__title">MA <a href="javascript:void(0);" db-table="rpepcp_reffrence_table" ctype="IA"><?= count($iadetail); ?></a></li>
                            </ul>
                            <div class="divider"></div>
                            <ul class="infobox">
                                <li class="dbox__title">Total <span><?= count($draft)+count($rpepcp)+count($iadetail); ?></span></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="dbox">
                        <div class="dbox__icon">
                            <i class="fa fa-user"></i>
                        </div>
                        <div class="dbox__body">
                            <span class="dbox__count">Cases Filed</span>  
                            <div class="divider"></div>  					
                            <ul class="infobox">
                                <li class="dbox__title">Original Filing <a href="<?php echo base_url(); ?>filedcase_list"><?php echo count($apeeal);?></a></li>
                                <li class="dbox__title">RP <a href="javascript:void(0);"><?php echo count($rpepcp);?></a></li>
                                <li class="dbox__title">Documents Filing <a href="javascript:void(0);"><?php echo count($draft);?></a></li>
                                <li class="dbox__title">MA <a href="javascript:void(0);"><?= count(@$iadetail); ?></a></li>
                            </ul>
                            
                            <div class="divider"></div>
                            <ul class="infobox">
                                <li class="dbox__title">Total <a href="#" class="alt"><?php echo count($apeeal)+(int)count($rpepcp)+(int)count($draft)+(int)count(@$iadetail);?></a></li>
                            </ul>
                        </div> 
                    </div>
                </div>
            </div>
         
            <div class="row"> 
                <div class="col-md-4">
                    <div class="dbox">
                        <div class="dbox__icon">
                            <i class="fa fa-user"></i>
                        </div>
                        <div class="dbox__body">
                            <span class="dbox__count">Defective Cases</span>	
                            <div class="divider"></div>	
                            <ul class="infobox">
                                <li class="dbox__title">Original Filing <a href="<?php echo base_url(); ?>defective_list"><?php echo count($defective);?></a></li>
                                <li class="dbox__title">RP <a href="#"><?php echo count($draft);?></a></li>
                                <!-- <li class="dbox__title">Document Filing <a href="#"><?php echo count($draft);?></a></li>
                                <li class="dbox__title">IA <a href="#"><?php echo count($defective);?></a></li> -->
                            </ul>
                            <div class="divider"></div>
                            <ul class="infobox">
                                <li class="dbox__title">Total <a href="#" class="alt"><?php echo count($draft)+count($draft)+count($draft);?></a></li>
                            </ul>
                        </div>				
                    </div>
                </div>
                
                
                <div class="col-md-4">
                    <div class="dbox">
                        <div class="dbox__icon">
                            <i class="fa fa-user"></i>
                        </div>
                        <div class="dbox__body">
                            <span class="dbox__count">Under Scrutiny</span>
                            <div class="divider"></div>
                            <ul class="infobox">
                                <li class="dbox__title">Original Filing <a href="#">0</a></li>
                                <li class="dbox__title">RP <a href="#">0</a></li>
                                <!-- <li class="dbox__title">Document Filing <a href="#">0</a></li>
                                <li class="dbox__title">IA <a href="#"><?php echo count($defective);?></a></li> -->
                            </ul>
                            <div class="divider"></div>
                            <ul class="infobox">
                                <li class="dbox__title">Total <a href="#" class="alt"><?php echo count($defective);?></a></li>
                            </ul>
                        </div> 		
                    </div>
                </div>
                

                <div class="col-md-4">
                    <div class="dbox"> <!-- dbox_inline -->
                        <div class="dbox__icon">
                            <i class="fa fa-user"></i>
                        </div>
                        <div class="dbox__body">
                            <span class="dbox__count">Registered Cases</span>
                            <div class="divider"></div>
                            <ul class="infobox">
                                <li class="dbox__title">Original Filing <a href="#"><?php echo count($apeeal);?></a></li>
                                <li class="dbox__title">RP <a href="#"><?php echo count($rpepcp);?></a></li>
                            </ul>
                            <div class="divider"></div>
                            <ul class="infobox">
                                <li class="dbox__title">Total <a href="#" class="alt"><?php echo count($apeeal)+count($rpepcp);?></a></li>
                            </ul>
                        </div>                    			
                    </div>
                </div>

            </div>

        </div>
	<!-- </div>
    <div class="row">
        <div class="card w-100" style="padding: 0px 12px;">
            <?php 
                echo form_fieldset('Case Status ',['style'=>'margin-top:12px;border: 2px solid #4cb060;']).
                 '<i class="icon-plus-circle2 text-danger" style="position: absolute;padding: 21px 6px;"></i>';

                echo'<div class="d-block text-center text-warning">
                        <div class="table-responsive text-secondary" id="add_petitioner_list">
                            <span class="fa fa-spinner fa-spin fa-3x"></span>
                        </div>
                    </div>';
                echo form_fieldset_close();
            ?>
        </div>
    </div> -->

</div>	
 <?php $this->load->view("admin/footer"); ?>
<?php $this->load->view("admin/dashboard-modals"); ?>