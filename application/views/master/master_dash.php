<?php $this->load->view("admin/header"); ?>
<?php $this->load->view("admin/sidebar"); ?>
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style>

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

.dbox__icon {
    position: absolute;
    transform: translateY(-50%) translateX(-50%);
    left: 50%;
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
}
.dbox__count a {
    font-size: 30px;
    color: #26a69a !important;
    font-weight: bold !important;
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
    transform: translateY(-50%) translateX(-50%);
    position: absolute;
    left: 50%;
}
.dbox__action__btn {
    border: none;
    background: #FFF;
    border-radius: 19px;
    padding: 7px 16px;
    text-transform: uppercase;
    font-weight: 500;
    font-size: 11px;
    letter-spacing: .5px;
    color: #003e85;
    box-shadow: 0 3px 5px #d4d4d4;
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
.dbox_row {
    overflow: hidden;
}
.col-6 {
    width: 50%;
    float: left;
}
.col-12 {
    width: 100%;
}
.col-6 a, .col-12 a {
    text-decoration: none;
    color: #fff;
    font-weight: bold;
}
.dbox_label {
    color: #263238;
    font-size: 16px;
    font-weight: bold;
    margin-top: 10px;
    text-shadow: 1px 1px 1px #fff;
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
.infobox {
    list-style: none;
    padding: 0;
    text-align: center;
    width: 100%;
    margin: 0;
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
    margin-top: 10px;
}
</style>
<div class="content" style="padding-top:0px;">
	<div class="row">

		<div class="card">
          <div class="row"> 
            <div class="col-md-4">
    			<div class="dbox">
    				<div class="dbox__body">
    					<div class="dbox_label">Advocate</div>
                        <div class="divider"></div>
    					<div class="dbox_row">
    						<div class="col-6">
    							<span class="dbox__count"><a href="#"><?php echo count($adv_varified);?></a></span>
    							<span class="dbox__title">Varified </span>
    						</div>
    						<div class="col-6">
    							<span class="dbox__count"><a href="#"><?php echo 0;?></a></span>
    							<span class="dbox__title">Not Varified</span>
    						</div>
    					</div>
                        <div class="divider"></div>
                        <ul class="infobox">
                            <li class="dbox__title">
                                <div>
                                    <a href="<?php echo base_url(); ?>advocate_list" data-value="advocate_list"  class="nav-link active">Know More</a>
                                </div>
                            </li>
                        </ul>
    				</div>			
    			</div>
    		</div>
    		<div class="col-md-4">
    			<div class="dbox">
    				<div class="dbox__body">
        				<div class="dbox_label">Registered User</div>
                        <div class="divider"></div>
    					<div class="dbox_row">
    						<div class="col-6">
    							<span class="dbox__count"><a href="#"><?php echo count($euser_varified);?></a></span>
    							<span class="dbox__title">Active </span>
    						</div>
    						<div class="col-6">
    							<span class="dbox__count"><a href="#"><?php echo count($euser_nonvarified);?></a></span>
    							<span class="dbox__title">Not Active</span>
    						</div>
    					</div>
                        <div class="divider"></div>
                        <ul class="infobox">
                            <li class="dbox__title">
                                <div>
                                    <a href="<?php echo base_url(); ?>euser_list" data-value="euser_list"  class="nav-link active">Know More</a>
                                </div>
                            </li>
                        </ul>
    				</div>
    			</div>
    		</div>
    		<div class="col-md-4">
    			<div class="dbox">
    				
    			   <div class="dbox__body">
        				<div class="dbox_label">Organization</div>
                        <div class="divider"></div>
    					<div class="dbox_row">
    						<div class="col-6">
    							<span class="dbox__count"><a href="#"><?php echo count($org_varified);?></a></span>
    							<span class="dbox__title">Varified </span>
    						</div>
    						<div class="col-6">
    							<span class="dbox__count"><a href="#"><?php echo count($org_nonvarified);?></a></span>
    							<span class="dbox__title">Non Varified </span>
    						</div>
    					</div>
                        <div class="divider"></div>
                        <ul class="infobox">
                            <li class="dbox__title">
                                <div>
                                    <a href="<?php echo base_url(); ?>org_list" data-value="org_list"  class="nav-link active">Know More</a>
                                </div>
                            </li>
                        </ul>
    				</div>		
    			</div>
    		</div>
    		
    		
    		
    	       <div class="col-md-4">
                    <div class="dbox"> <!-- dbox_inline -->
                        <div class="dbox__body">
                            <div class="dbox_label">Detail</div>
                            <div class="divider"></div>
                            <ul class="infobox">
                               <a href="<?php echo base_url();?>checkslists">Check List detail</a><br>
                               <a href="<?php echo base_url();?>doc_master">Document Master</a><br>
                               <a href="<?php echo base_url();?>regu_master">Master Regulator</a><br>
                               <a href="<?php echo base_url();?>act_master">Master ACT</a><br>
                               <a href="<?php echo base_url();?>ma_master">Master MA</a><br>
                               <a href="<?php echo base_url();?>usrole_master">Master USER_ROLE</a><br>
                               <a href="<?php echo base_url();?>master_objection">Master Objection</a><br>
                               <a href="<?php echo base_url();?>masterjudge">Master Judge</a><br>
                            </ul>
                            <div class="divider"></div>
                        </div>                    			
                    </div>
                </div>

          </div>
        </div>
	</div>
</div>
<?php $this->load->view("admin/footer"); ?>  
  <script>
    $('.nav-link').click(function() { 
        var content = $(this).data('value');
        if(content!=''){
        	$('.steps').empty().load(base_url+'/efiling/'+content);
        }
    });
        
    </script>	