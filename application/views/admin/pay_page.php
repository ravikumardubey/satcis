<?php 
$token= $this->efiling_model->getToken();
    error_reporting(0);
    $saltmain= $_REQUEST['saltNo'];
    if($saltmain==''){
        header(base_url());
    }
    $userdata=$this->session->userdata('login_success');
    $fname=$userdata[0]->fname;
    $lname=$userdata[0]->lname;
    $token= $this->efiling_model->getToken();
    $salt=$this->session->userdata('salt'); 
?>
<!DOCTYPE html>
<html >
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>e_filing pay</title>
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
	<script>
	function submitForm(){
		with(document.frmPay){
			action="http://training.pfms.gov.in/bharatkosh/bkepay";
			submit();
			return true;
		}
	}
	</script>
	<style>
	.content-wrapper {
    overflow: hidden;
        }
	</style>
</head>	
<body onload="paymentMode1();">
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
				
					<li class="hassubmenu"><a href="">Welcome, <?= strtoupper($fname.' '.$lname); ?></a>
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
	
	  <div class="page-content" style="margin-top: 110px;"> <!-- Close in footer bar-->
 		<div class="content-wrapper"> <!-- Close in footer bar-->
			<div class="page-header page-header-light">
				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="<?php echo base_url(); ?>index" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>Back</a>
							<span class="breadcrumb-item active">Dashboard</span>
						</div>
						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>
					<div class="header-elements d-none">
						<div class="breadcrumb justify-content-center">
							<div class="breadcrumb-elements-item dropdown p-0">
								  <div style="margin-left: 80px;" class="srchWrap">
                    					<input type="test" class="form-control" name="droft_no" id="droft_no"  value="" readonly>
                   				</div> 
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="container">
	       <div id="secondDiv"></div>
			<div id="mainDiv1">
			<form name="frmPay" method="post" >
			 <input type="hidden" name="saltmain" id="saltmain" value="<?php echo  $saltmain; ?>">
  			 <input type="hidden" name="org1" id="org1" value="<?php echo  $_REQUEST['org']; ?>">
             <input type="hidden" name="orgres1" id="orgres1" value="<?php echo  $_REQUEST['orgres']; ?>">
             <input type="hidden" name="token" id="token" value="<?php echo  $token; ?>">  
            <?php
					    $courtfee=0;
					    $count=0;
					    $feeval=0;
					    $ipenalty='';
					    $apealtype='';
					    $opauthority='';
					    $regulatorname='';
					    $orderpassingauthority='';
                        $st=$this->efiling_model->data_list_where('sat_temp_appellant','salt', $salt);
                        if(!empty($st)){
                            $appname=isset($st[0]->pet_name)?$st[0]->pet_name:''; 
                            $ipenalty = $st[0]->ipenalty;
                            $apealtype = $st[0]->apeal_type;
                            $opauthority = $st[0]->opauthority; 
                            $stfee=$this->efiling_model->data_list_where('master_regulator','order_pass_auth_id', $opauthority);
                            if(!empty($stfee)){
                                $regulatorfeeval=$stfee[0]->fee;
                                $orderpassingauthority=$stfee[0]->order_passing_authority;
                                $regulatorname=$stfee[0]->order_passing_authority;
                            }
                        }

                        $additionalparty=$this->efiling_model->data_list_where('sat_temp_additional_party','salt',$salt);
                        $val=0;
                        if($appname!=''){
                            $val='1';
                        }
                        $count= count($additionalparty)+$val;
                        $applicantfee=$this->efiling_model->calculateapp($count);
                        
                        
                        $exclusiveamount='500';
                        if($regulatorname=='Adjudicating Officer'){
                            if($ipenalty<'10000'){
                                $courtfee='500';
                            }
                            
                            if($ipenalty>'10000'){  
                                $courtfee='1200';
                            }
                            
                            if($ipenalty=='100000'){
                                $courtfee=(int)$feeval;
                            }
                            
                            if($ipenalty>'100000'){
                                $var=$ipenalty-100000;
                                $var2=$var/100000;
                                $var3=ceil($var2);
                                $courtfee=($var3*$exclusiveamount)+1200;
                            }
                        }
                        
                        if($regulatorname!='Adjudicating Officer'){
                            $courtfee=$applicantfee;
                        }
                        ?>

                    <table class="table" id="example">
                      <thead>
                         <tr style="background-color: #ebdada">
                          <th scope="col">S.NO</th>
                          <th scope="col">Regulator</th>
                          <th scope="col">Fees</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td><?php echo htmlspecialchars(1);?></td>
                          <td><?php echo $orderpassingauthority;?></td>
                          <td><?php echo $courtfee;?></td>
                        </tr>
                      </tbody>
                    </table>
                <!--xml create-->
				<?php 
				$salt=rand('0000','9999');
                 //Write code to fetch the all value to pass in the variable   	 
                $email='';
                
                $departmentcode='010';						//Fixed
                $merchantBatchCode=$salt;		//SaltID
                $OrderCode=$salt;	//SaltID
                //$merchantBatchCode='9007641209609303';		//SaltID
                //$OrderCode='9007641209609303';				//SaltID
                
                $orderbactachAmount='1';
                $InstallationId='10070';					//Fixed
                $description='APTEL RECEIPT(COURT FEE)';		//Fixed
                $cartdetailAmount='1';
                
                $OrderContent='326';						//Fixed
                $PaymentTypeId='51';						//Fixed
                $PAOCode='013455';							//Fixed
                $DDOCode='213459';	
                //Fixed
                $ShopperEmailAddress='dubey.ravi7@gmail.com';
                $shipaddfirst='Ravi Kumar';
                $shipaddlast='dubey';
                $shipaddress1='Police line sidhi';
                $shippincode='486661';
                $shipcity='Sidhi';
                $shipstateregion='New Delhi';
                $shipstate='Delhi';
                $shipcountry='INDIA';
                $shipmobileno='9958663113';
                
                $billaddfirst='ravi ';
                $billaddlast='Police line sidhi';
                $billaddress1='Police line sidhi';
                $billpincode='110092';
                $billcity='Delhi';
                $billstateregion='Delhi';
                $billstate='New Delhi';
                $billcountry='INDIA';
                $billmobileno='9958663113';
                $strbharatxml="<BharatKoshPayment DepartmentCode='$departmentcode' Version='1.0'><Submit><OrderBatch TotalAmount='$orderbactachAmount' Transactions='1' merchantBatchCode='$merchantBatchCode'><Order InstallationId='$InstallationId' OrderCode='$salt'><CartDetails><Description/><Amount CurrencyCode='INR' exponent='0' value='$cartdetailAmount' /><OrderContent>$OrderContent</OrderContent><PaymentTypeId>$PaymentTypeId</PaymentTypeId><PAOCode>$PAOCode</PAOCode><DDOCode>$DDOCode</DDOCode></CartDetails><PaymentMethodMask><Include Code='Online'/></PaymentMethodMask><Shopper><ShopperEmailAddress>$ShopperEmailAddress</ShopperEmailAddress></Shopper><ShippingAddress><Address><FirstName>$shipaddfirst</FirstName><LastName>$shipaddlast</LastName><Address1>$shipaddress1</Address1><Address2/><PostalCode>$shippincode</PostalCode><City>$shipcity</City><StateRegion>$shipstateregion</StateRegion><State>$shipstate</State><CountryCode>$shipcountry</CountryCode><MobileNumber>$shipmobileno</MobileNumber></Address></ShippingAddress><BillingAddress><Address><FirstName>$billaddfirst</FirstName><LastName>$billaddlast</LastName><Address1>$billaddress1</Address1><Address2/><PostalCode>$billpincode</PostalCode><City>$billcity</City><StateRegion>$billstateregion</StateRegion><State>$billstate</State><CountryCode>$billcountry</CountryCode><MobileNumber>$billmobileno</MobileNumber></Address></BillingAddress><StatementNarrative/></Order></OrderBatch></Submit></BharatKoshPayment>";  
                $url = 'localhost:8090/signXml';
                //open connection
                $ch = curl_init();
                
                if($fields_string!==NULL){
                    $fields_string = http_build_query($strbharatxml);
                    $fields_string =http_build_query($post_array);
                }
                
             
                //set the url, number of POST vars, POST data
                curl_setopt($ch,CURLOPT_URL, $url);
                //curl_setopt($ch,CURLOPT_POST, count($fields_string));
                curl_setopt($ch,CURLOPT_POST, 1);
                curl_setopt($ch,CURLOPT_POSTFIELDS, $strbharatxml);
                curl_setopt($ch,CURLOPT_HTTPHEADER,array('Content-Type: text/plain'));
                ?>    
                <input type="hidden" value="<?php echo $totalfee; ?>"  name="collectamount" id="collectamount"/>   
                <input type="hidden" value="<?php echo $totalfee; ?>"  name="total" id="total"/>   
                <input type="hidden" value="<?php $result = curl_exec($ch); ?>"  name="bharrkkosh" id="bharrkkosh"/>        
                <input type="hidden" name="total_amount_amount" id="total_amount_amount" value="<?php echo $totalfee; ?>">
       		
	                <div class="row alert alert-warning">
                        <div class="col-md-4">
                            <label class="text-danger">Select Mode</label>
                        </div>
                        <div class="col-md-6 md-offset-2">
                            <label for="org" class="form-check-label font-weight-semibold">
                                <input type="radio" name="orgres" value="1" checked="checked" id="bd1" onclick="paymentMode1();"> Online&nbsp;&nbsp;
                            </label>
                            <label for="indv" class="form-check-label font-weight-semibold">
                                <?= form_radio(['name'=>'orgres','id'=>'partial' ,'value'=>'3' ,'onclick'=>'paymentMode1();','checked'=>'checked']); ?>
                                Offline Payment&nbsp;&nbsp;
                            </label>
                        </div>
                    </div>
                    <div class="row" id="buttonpay" style="display: none;">
                        <div class="offset-md-8 col-md-4" style="margin-left: 81.33333%;">
                		<input type="submit"  name="go" id="gobtn" value="Pay Online"  onClick="javascript:submitForm();" class="btn btn-success" id="nextsubmit">
						<input type="reset" value="Reset/Clear" class="btn btn-danger">
                        </div>
                    </div> 
                     <div class="row" id="payMode"></div> 
                     <div class="row" id="paydetail">
          
                     </div> 
				</div>        
			</form>	
			 		<?= form_fieldset_close(); ?>
			<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
			<div class="navbar navbar-expand-lg navbar-light">
				<div class="text-center d-lg-none w-100">
					<button type="button" class="navbar-toggler dropdown-toggle" data-toggle="collapse" data-target="#navbar-footer">
						<i class="icon-unfold mr-2"></i>
						Footer
					</button>
				</div>
				<div class="navbar-collapse collapse" id="navbar-footer"> </div>
			</div>
		</div> 
	</div> 
	<!---- Loading Modal ------------->
        	<div class="modal fade bd-example-modal-lg" data-backdrop="static" data-keyboard="false" tabindex="-1" id="loading_modal">
        	    <div class="modal-dialog modal-sm" style="margin: 25% 50%; text-align: center;">
        	        <div class="modal-content" style="width: 90px; height: 90px; padding: 15px 25px;">
        	            <span class="fa fa-spinner fa-spin fa-3x"></span>
        	            <p class="text-danger" style="margin: 12px -12px;">Loading.....</p>
        	        </div>
        	    </div>
        	</div>
	
	
		  <div class="modal fade bd-example-modal-lg" id="getCodeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
             <div class="modal-dialog modal-lg">
                <!-- Modal content-->
                    <div class="modal-content">
                     <form action="certifiedsave.php" method="post">
                          <div class="modal-header" style="background-color: cadetblue;">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                          </div>
                          <div id="viewsss">
                          </div>
                      </form>
                  </div>
             </div>
         </div>  
</body>
<script>    
function paymentMode1() {
    var radios = document.getElementsByName("orgres");
    var bd = 0;
    for (var i = 0; i < radios.length; i++) {
        if (radios[i].checked) {
            var bd = radios[i].value;
        }
    }
    if (bd == 1) {
    	document.getElementById('buttonpay').style.display = 'block';
        document.getElementById("payMode").style.display = 'none';
      //  document.getElementById("listamount").style.display = 'none';
    }
   
    if (bd == 3) {
    	document.getElementById("buttonpay").style.display = 'none';
    //	document.getElementById("listamount").style.display = 'block';
         $.ajax({
            type: "POST",
            url: base_url+"postalorderfinal",
            data: "app=" + bd,
            cache: false,
            success: function (data) {
            	document.getElementById("payMode").style.display = 'block';
            	$('#payMode').html(data);
            }
        });
         
    }
}


function deletePay(e) {
    var payid = e;
    var radios = document.getElementsByName("orgres");
    var bd = 0;
    for (var i = 0; i < radios.length; i++) {
        if (radios[i].checked) {
            var bd = radios[i].value;
        }
    }
    var salt = document.getElementById("saltmain").value;
    var dataa={};
    dataa['payid']=payid,
    dataa['salt']=salt,
    dataa['bd']=bd,
    $.ajax({
	    dataType: 'json',
        type: "POST",
        url: base_url+'addMoredd',
        data: dataa,
        cache: false,
		beforeSend: function(){
			$('#loading_modal').modal();
		},
        success: function (resp) {
        	if(resp.data=='success') {
				$('#id'+e).hide();
				setTimeout(function () { location.reload(1); }, 100);
			}
        },
        error: function(){
			$.alert("Surver busy,try later.");
		},
		complete: function(){
		}
	 });
    document.getElementById("addmoreaddpay").style.display = 'block';
    document.getElementById("addmoreadd").style.display = 'none';
}


function addMoreAmount() {
    	var saltmain = document.getElementById("saltmain").value;
    	var radios = document.getElementsByName("orgres");
    	var totalamm = document.getElementById("total").value;
    	var bd = 0;
        for (var i = 0; i < radios.length; i++) {
            if (radios[i].checked) {
                var bd = radios[i].value;
            }
        }
       if (bd == 3) {
        var dbankname = document.getElementById("ntrp").value;
        if (dbankname == "") {
            alert("Please Enter Bank name");
            document.filing.ntrp.focus();
            return false;
        }
        var ddno = document.getElementById("ntrpno").value;
		var vasks = ddno.toString().length;
        if(Number(vasks) != 13){
          			alert("Please Enter 13  Digit Challan No/Ref.No");
                    document.filing.ntrpno.focus();
                    return false
        }
        if (ddno == "") {
            alert("Please Enter Challan No/Ref.No");
            document.filing.ntrpno.focus();
            return false
        }
        var dddate = document.getElementById("ntrpdate").value;
        
        if (dddate == "") {
            alert("Please Enter Date of Transction");
            document.filing.ntrpdate.focus();
            return false
        }
        var amountRs = document.getElementById("ntrpamount").value;
        if (amountRs == "") {
            alert("Please Enter Amount ");
            document.filing.ntrpamount.focus();
            return false
        }
    }
    var dataa={};
    dataa['dbankname']=dbankname,
    dataa['amountRs']=amountRs,
    dataa['ddno']=ddno,
    dataa['dddate']=dddate,
    dataa['bd']=bd,
    dataa['salt']=saltmain,
    dataa['totalam']=totalamm,
    $.ajax({
	    dataType: 'json',
        type: "POST",
        url: base_url+'addMoredd',
        data: dataa,
        cache: false,
		beforeSend: function(){
			//$('#feedetailsubmit').prop('disabled',true).val("Under proccess....");
		},
        success: function (resp) {
        	if(resp.data=='success') {
        	   $("#paydetail").html(resp.display);
        		 $.alert({
					title: '<i class="fa fa-check-circle text-success"></i>&nbsp;</b>Success</b>',
					content: '<p class="text-success">Amount added successfully.</p>',
					animationSpeed: 2000
				 });
			}else if(resp.error != '0') {
				$.alert(resp.massage);
			}
        },
        error: function(){
			$.alert("Surver busy,try later.");
		},
		complete: function(){
		}
	 }); 
    if (bd == 1) {
        document.getElementById("dbankname").value = "";
        document.getElementById("ddno").value = "";
        document.getElementById("dddate").value = "";
        document.getElementById("amountRs").value = "";
    }
    if (bd == 3) {
        document.getElementById("ntrpno").value = "";
        document.getElementById("ntrpdate").value = "";
        document.getElementById("ntrpamount").value = "";
    }
}

function popitup(filingno) {
 	 var dataa={};
      dataa['filingno']=filingno,
       $.ajax({
           type: "POST",
           url: base_url+"/filingaction/filingPrintSlip",
           data: dataa,
           cache: false,
           success: function (resp) {
         	  $("#getCodeModal").modal("show");
          	  document.getElementById("viewsss").innerHTML = resp; 
           },
           error: function (request, error) {
				$('#loading_modal').fadeOut(200);
           }
       }); 
 }
</script>
<script src="<?=base_url('asset/admin_js_final/jquery.min.js')?>"></script>
<script src="<?= base_url('asset/admin_js_final/jquery.dataTables.min.js'); ?>"></script> 
<script src="<?= base_url('asset/admin_js_final/dataTables.bootstrap4.min.js'); ?>"></script>
<script src="<?=base_url('asset/admin_js_final/bootstrap.bundle.min.js')?>"></script>
<script src="<?=base_url('asset/admin_js_final/blockui.min.js')?>"></script>
<script src="<?=base_url('asset/admin_js_final/d3.min.js')?>"></script>
<script src="<?=base_url('asset/admin_js_final/d3_tooltip.js')?>"></script>
<script src="<?=base_url('asset/admin_js_final/switchery.min.js')?>"></script>
<script src="<?=base_url('asset/admin_js_final/bootstrap_multiselect.js')?>"></script>
<script src="<?=base_url('asset/admin_js_final/moment.min.js')?>"></script>
<script src="<?=base_url('asset/admin_js_final/daterangepicker.js')?>"></script>
<script src="<?=base_url('asset/admin_js_final/app.js')?>"></script>
<script src="<?=base_url('asset/admin_js_final/dashboard.js')?>"></script>
<script src="<?=base_url('asset/admin_js_final/jquery-ui.js')?>"></script>
<script src="<?=base_url('asset/admin_js_final/customs.js')?>"></script>
<script src="<?= base_url('asset/APTEL_files/jquery-confirm.js');?>"></script>
<script src="<?=base_url('asset/admin_js_final/efiling.js')?>"></script>
<script src="<?= base_url('asset/APTEL_files/hash.js'); ?>"></script>
<script type="text/javascript">
	var base_url='', salt='';
	base_url ='<?php echo base_url(); ?>';
    salt='<?php echo hash("sha512",strtotime(date("d-m-Y i:H:s"))); ?>';
</script>

</html>
