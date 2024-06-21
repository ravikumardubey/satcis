<?php 
$token= $this->efiling_model->getToken();
    error_reporting(0);
    $saltmain= $_REQUEST['saltNo'];
    if($saltmain==''){
        header(base_url());
    }
    $apealdata=$this->session->userdata('apealdata');
   // print_R($apealdata);die;
    $filingformate=$apealdata['filingformate'];
    $filingno=$apealdata['filingno'];
    $printIAno=$apealdata['iafiling'];
    $iafee=$apealdata['ia_filing'];

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
							<a href="<?php echo base_url(); ?>loginSuccess" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>Back</a>
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

<?php 
$html='';
$html.='<fieldset>
                       <legend style="color: red;font-size:15">Diary Number :</legend>
    	                  <td colspan="1">
                             <font size="2">
                                    <a href="javascript: void(0);" style="color: #3F33FF" onclick="return popitup('.$filingno.');">
                    	       <b>Print</b>
                    			</a>
    						</font>
    					</td>
        
                    <div class="col-md-12"><font color="#0000FF" size="5">Case is successfully registered With Appeal Lodging no - '.$filingformate.'</br>';
if($iafee > 0 && $iafee!=0){
    $html.="MA Number :";
    $html.= "<br>";
    $html.=$printIAno;
}

$html.='</font>
                   	 </div>
    			</fieldset>';
echo $html;
?>

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
