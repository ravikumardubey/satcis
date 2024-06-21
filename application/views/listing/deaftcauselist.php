<?php
$token= $this->efiling_model->getToken();
error_reporting(0);
$saltmain=$this->session->userdata('salt');
if($saltmain==''){
    header(base_url());
}
$userdata=$this->session->userdata('login_success');
$fname=$userdata[0]->fname;
$lname=$userdata[0]->lname;
$token= $this->efiling_model->getToken();
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
</head>	
<body > 
	<div class="container">    		
			 <table cellspacing="1" cellpadding="1" border="0" width="95%" class="fixed" align="center" style="text-align:center"
        bgcolor="#F0F8FF">
        <div id="testdiv" class="printtext" style="visibility: visible;"><a href="javascript:printPage();">
                <font size="4" color="red">
                    Print</font>
            </a></div>
    </table>
        <table cellspacing="1" cellpadding="1" border="0" width="95%" class="fixed header_content" align="center" style="text-align:center" bgcolor="#F0F8FF">
            <tr>
                <td colspan="10" align="center" style="text-align:center"><b> Securities Appellate Tribunal,Mumbai, Maharashtra 400021 </b> </td>
            </tr>
            <tr>
                <td colspan="10" align="center" style="text-align:center"><b><u>  Cause List for Monday the 7th November 2022                    </u></b>td>
            </tr>
            <tr>
                <td colspan="10" align="center" style="text-align:center"><b> TIME :11.00 AM</b></td>
            </tr>

            <tr>
                <td colspan="10" align="center" style="color:red; text-align:center"><b><u><b>(There will be no sitting of this Bench.Next date may be obtained from Court Master at 11.00 A.M.)</b><br><br>                    </u></b></td>
            </tr>
            <tr>
                <td colspan="10" align="center" style="text-align: center;">
                    <table class="no_padding" style="display: inline-block;">
                        <tr valign="top">
    						 <td><b>CORAM: </b></td>   
                            <td><b>  HON'BLE  MR. JUSTICE R.K. GAUBA, Officiating Chairperson</b></td>
                        </tr>
    						 <td><b></b></td>   <!---- //Ravi kumar dubey 31122019 // assign by sakeel--->
                            <td><b>
                                    HON'BLE 
                                    MR. SANDESH KUMAR SHARMA, Technical Member(Electricity)                            </b>
                            </td>
                        </tr>
                    </table>
                </td>
                </b>
            </tr>
		</table>
    <table cellspacing="0" cellpadding="0" border="1" width="95%" class="fixed custom_table" align="center"  bgcolor="#F0F8FF">
        <tr>
            <th>S.No.</th>
            <th>Case No/Diary Number.</th>
            <th>Party Name</th>
            <th>Name of Counsel For Appellant(Mr./Mrs.)</th>
            <th>Name of Counsel For Respondent(Mr./Mrs.)</th>
        </tr>
        
        <?php
        if(!empty($caseallo) && is_array($caseallo)){
            $i=1;
           foreach($caseallo as  $row){ 
               $dd =$this->efiling_model->data_list_where('sat_case_detail','filing_no',$row->filing_no);
               $petName=$dd[0]->pet_name.$this->efiling_model->fn_addition_party($row->filing_no,'1');
               $resName=$dd[0]->res_name.$this->efiling_model->fn_addition_party($row->filing_no,'2');
               $dfr=$this->efiling_model->createdfr($row->filing_no);
               ?>
              <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $dfr; ?></td>
                <td><?php echo $petName; ?><span style="color:red"><b>Vs </b></span><?php echo $resName; ?></td>
                <td><?php echo $dd[0]->pet_adv; ?></td>
                <td><?php echo $dd[0]->res_adv; ?></td>
              </tr>
          <?php $i++;  } 
        }else{
            echo "Record not found ";die;
        } ?>
    </table>
    
    
    
    
    
    <table cellspacing="1" cellpadding="1" border="0" width="95%" class="fixed" align="center" bgcolor="#F0F8FF">
        <tr>
            <td align="right" colspan="10" style="text-align:right">
                </br></br></br></br>
                Sd/
                </br>
                Registrar  
                </br>
                APTEL
            </td>
        </tr>
    </table>
    </div> 
</body>
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
