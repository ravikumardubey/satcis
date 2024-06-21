<?php  $this->load->view("admin/header"); ?>
<?php  $this->load->view("admin/sidebar"); ?>
<link rel="stylesheet" href="<?=base_url('asset/sweetalert2/sweetalert2.min.css')?>">
<script src="<?=base_url('asset/sweetalert2/sweetalert2.all.min.js')?>"></script>
<script>
function submitForm()
{
	//alert('hjjhj'); //die;
 	with(document.frm)
	{
	
	 action ="<?php echo base_url() ?>case_proceeding";
	submit();
	}
}
</script>
<div class="content">
	<div class="row">
              <div class="container">
<?= form_open('proceedingAction',['name'=>'frm','autocomplete'=>'off']) ?>
<table border="0" width="100%" class="std table table-responsive table-sm">
  <tr><td colspan="6" align="right" valign="top">
      <div align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="3"><b>
        <u><B>CASE PROCEEDINGS</B></u></b></font></div>
    </td>
  </tr>

<?php

$case_type=set_value('case_type'); 
//$filing_no=set_value('filing_no'); 
$app_type="cno"; 


/*if($sessionUserid=="8")
{
$case_type=2;
}*/
?>

<?php  if(!empty($caseDetails)) { ?>
<tr>
<td colspan="6"> 
<center><b> <font> PARTY DETAILS:    </font><font color ="red" size="3"> 
<?php


 print $pet_name; ?>
<?php if($res_name !='--') print '      Vs     '. htmlspecialchars(htmlentities($res_name)); } ?></font></b> </center>
</td>
</tr>
<?php if(!empty($caseDetails) && empty($caseAllocations)) {   ?>
<tr>
<td colspan="6"> 
<center><b> <font color ="red" size="3"><?php echo "CASE IS NOT LISTED. LIST IT TO PROCEED." ?>
</font> <br>
</td>
</tr>
<?php } ?>


<?php if(!empty($caseAllocations)) { ?>
<tr>


<td  colspan="1" align="left" nowrap="nowrap"><div align="right"><b>Next Date Of Hearing <font face="Verdana, Arial, Helvetica, sans-serif" size="2"><?php ?>
</div></td>
<td colspan="1">
<input name="cat_code"    type="hidden" size="10" maxlength="10" value="<?=$cat_code?>">
<?=form_input('next_list_date',set_value('next_list_date',(isset($next_list_date))?$next_list_date:''),['id'=>'fromup','class'=>'form-control datepicker ','required'=>true,'maxlength'=>'10']) ?><b>DD-MM-YYYY</b>

</td>

<td  colspan="1" align="right"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b><span class="error">*</span>Purpose</td>

<td colspan="1" >
<?php $purpose_code=5; 

$purposeArray1[]='SELECT';
foreach($purposeArray as $row)
$purposeArray1[$row['purpose_code']]=$row['purpose_name']; ?>
<?=form_dropdown('purpose',$purposeArray1,set_value('purpose',(isset($purpose))?$purpose:'',false),['class'=>'form-control','required'=>'required']);?>
</td>
</tr>

<tr>
<td  colspan="1" align="right"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b><span class="error">*</span>Action</td>
<td colspan="1">
<?php 
$actionArray1[]='SELECT';
foreach($actionArray as $row)
$actionArray1[$row['action_code']]=$row['action_type']; ?>
<?=form_dropdown('todays_action',$actionArray1,set_value('todays_action',(isset($todays_action))?$todays_action:'',false),['class'=>'form-control','required'=>'required']);
?>    
</td>
<?php $remark=10;
		$getRemarksArray1[]='SELECT';
		foreach($getRemarksArray as $row)
		$getRemarksArray1[$row['code']]=$row['name']; ?>
	<td  colspan="1" align="left" nowrap="nowrap"><div align="right"><b><span class="error">*</span><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Remarks</div></td>
	<td colspan="1"> 
	<?=form_dropdown('remark',$getRemarksArray1,set_value('remark',(isset($remark))?$remark:'',false),['class'=>'form-control','required'=>'required']);
?>
</td>


</tr>
<?php //echo "bench".$ben;

if(($case_type==2)&&($ben==2))
{

$list_before=10;
}
?>
<tr>
    <!--td colspan="1"  align="left" nowrap="nowrap"><div align="right"><b><span class="error">*</span><font face="Verdana, Arial, Helvetica, sans-serif" size="2">To be listed before</div>
	</td-->
	<!--td >
	<? //=form_dropdown('bench_no',$getBenchNatureListArray,set_value('bench_no',(isset($bench_no))?$bench_no:'',false),['class'=>'form-control','required'=>'required']);?>    
</td-->

</tr>

<input type="hidden" name="filing_no" value ="<?=$filing_no?>">
<tr><td colspan="6" align="center">
        <div align="center">
        <input type="submit" name="submit1" value="Submit" class="btn btn-success" onClick="return validate();">

      
</div>
    </td></tr>
<?php } ?>

</table>
</div>
</div>
</div>
	
<?= form_close();?>
  <script>

$(document).ready(function() {
	var msg = <?php echo "'".$this->session->userdata('Success')."'"; ?>;
	if(msg !== ''){
			swal(
				'Success',
				msg,
				'success'
				)
	}
});
	$(document).ready(function() {
		
		var msg = <?php echo "'".$this->session->userdata('Error')."'"; ?>;
		if(msg !== ''){
			swal(
				'Error',
				msg,
				'error'
				)
		}
		});
</script>
<?php $this->session->unset_userdata('Success');?>
<?php $this->session->unset_userdata('Error');?>
<?php $this->load->view("admin/footer"); ?>	
<script>
function serchDFR(){
	with(document.caselistingsub){
	action = base_url+"cause_list";
	submit();
    	document.frm.submit11.disabled = true;  
     	document.frm.submit11.value = 'Please Wait...';  
     	return true;
	}
}
 	 $(".datepicker" ).datepicker({ 
		 dateFormat: "dd-mm-yy",
	minDate: 'now' + 1
	//	  maxDate: 'now'
		 });

</script>