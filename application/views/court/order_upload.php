<?php  $this->load->view("admin/header"); ?>
<?php  $this->load->view("admin/sidebar"); ?>
<link rel="stylesheet" href="<?=base_url('asset/sweetalert2/sweetalert2.min.css')?>">
<script src="<?=base_url('asset/sweetalert2/sweetalert2.all.min.js')?>"></script>
<script type="text/javascript">
/*function burstCache(){
if (!navigator.onLine) {
 document.body.innerHTML = 'Loading...';
 window.location = '../cestat_web/admin/login.php';
 }else
 {
 	history.go(+1);
 }
 }*/
</script>


<script language="javascript">
function getOption(id) {
       
	   if(id=="P")
	   { $("#disp").show();   }
	 //  { $("#disp").hide();   }
    if(id=="D")
	   { $("#disp").hide();   }
	   
    }
function submitForm()
{
 	with(document.frm)
	{		
		action = "order_upload.php";
		submit();
	}
}
function submitForm11()
{
 	with(document.frm)
	{		
		action = "order_upload.php";
		submit();
	}
}

function submitForm2()
{
 	with(document.frm)
	{

 		
		if(case_type.value == "select")
		{
			alert("Please select Case Type");
			case_type.focus();
			return false;
		}
		if(case_no.value=="")
		{
			alert("Please Enter Case No.");
			case_no.focus();
			return false;
		}
		if(isNaN(case_no.value) == true)
		{
			alert("Please enter  numeric Case No.");
			case_no.select();
			return false;
		}
		if(case_year.value=="")
		{
			alert("Please Enter Case Year");
			case_year.focus();
			return false;
		}
		if(isNaN(case_year.value) == true)
		{
			alert("Please enter  numeric Case Year");
			case_year.select();
			return false;
		}
		if(case_year.value.length!=4)
		{
			alert("Please Enter 4 digit case year");
			case_year.select();
			return false;
		}
		
		action = "<?php echo base_url() ?>order";
		submit();
	}
}


function addNumbers(val)
{
	var c=document.getElementById("order_date").value.length;
	if(c==2 || c==5  )
	{
		var newval = val+ "/";
		document.getElementById("order_date").value=newval;
	}

}
function validate(str)
{
	with(document.frm)
	{
	

		if(main_party.value=="")
		{
			alert("Please enter valid case no/dairy no");
			return false;
		}

             
       		 if(userfile.value=='')
       		{
    	   		alert("Please Upload the file");
    	   		userfile.focus();
			return false;
       		}

                oSelect=document.getElementById("judge_code");
		var count=0;
		for(var i=0;i<oSelect.options.length;i++)
		{
			if(oSelect.options[i].selected) { count++; }
		}
		if(count<1)
		{
			alert("Must select at least One JUDGE");
			return false;
		}

if(order_type.options[order_type.selectedIndex].value == "")
		{
			alert("Please Select Order Type");
			order_type.focus();
			return false;
		}	

		
		

var rgx = /^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/;

		if(order_date.value=="")
		{
			alert("Please enter valid order Date");
			order_date.focus();
			return false;
		}

		if(order_date.value !="")
               	{        
               		if(!order_date.value.match(rgx))
			{
               			alert("Please enter valid order Date ");
               			order_date.select();
               			return false;
               		}
               	}


                var fup = document.getElementById('userfile');
        	var fileName = fup.value;
        	var ext = fileName.substring(fileName.lastIndexOf('.') + 1);

    		if(ext =="pdf" || ext=="Pdf" || ext=="PDF")
    		{
      	 		 return true;
    		}
    		else
    		{
       	 		alert("Upload PDF only");
        		return false;
   	 	}
                


		
	}
}






</script>
<body onLoad="burstCache();">
<?php
$filing_no=set_value('filing_no');
$error=set_value('error');
$caseTypesArray=set_value('caseTypesArray');


?>
<table width="95%" border="0" cellspacing="2" cellpadding="2" align="center" class="std">
  <tr><td colspan="6" align="right" valign="top">
      <div align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="3"><b>
        <u><B>Daily & FINAL ORDER UPLOADING FORM</B></u></b></font></div>
    </td>
  </tr>
 <tr>
    <td colspan="6" align="right" valign="top">
      <div align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Fields
        marked with a <span class="error">*</span> are compulsory</font></div>
    </td>
  </tr>
  <tr><td>
<?php echo $error;?>
	  </td></tr>
<form name="frm" method="post" enctype="multipart/form-data" action="orderaction" onSubmit="return validate();">
     <input type="hidden" name="act" value="download">

<?php
$search_by="cno";
if($search_by=="cno")
{
$sql="select * from master_case_type  order by short_name asc";
?>
<tr>
 
<td width="15%"  align="right"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b><span class="error">*</span>Case Type</td>
<td width="15%" align="left">


<?php

	$caseTypesArray1=[''=>'SELECT'];
	$caseTypesArray= $this->db->get_where('master_case_type',['display'=>true])->result_array();
	foreach ($caseTypesArray as $val)
	$caseTypesArray1[$val['case_type_code']]=$val['short_name'].' ('.$val['case_type_name'].')';
            //$caseTypesArray1[$val->case_type_code] = $val->short_name.' ('.$val->case_type_name.')';  
	echo form_dropdown('case_type',$caseTypesArray1,set_value('case_type',(isset($case_type))?$case_type:'',false),['class'=>'form-control','required'=>'required']); 
?>


</td>


<td width="15%" align="right" ><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b><span class="error">*</span>Case No</td>
<td width="15%" align="left"><input type="text"  class="form-control" maxlength="7" size="8" name="case_no" value="<?php print set_value('case_no'); ?>" onFocus="SetBg(this)" onBlur="UnSetBg(this)"> </td> 
<td width="15%"  align="right"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b><span class="error">*</span>Case Year</td>
<td width="15%" align="left"><input type="text"   class="form-control" maxlength="4" size="4" name="case_year" value="<?php print set_value('case_year'); ?>" onFocus="SetBg(this)" onBlur="UnSetBg(this)"> </td> 
<td width="15%">&nbsp;
<input type="button" name="button" value="Go" class="btn btn-info" size="20" onClick="return submitForm2();">
	</td>
 
</tr>



<?php
}?>
<?php  if(!empty($caseDetails)) {  ?>
<tr>
	<td colspan="6" align="center">
	<font size="2" color="black">
	<b><u>PARTY DETAIL</u></b>
	</font>
	</td>
</tr>
<tr>
	<td colspan="6" align="center">
	<font size="2" color="RED">
<?php
	echo $main_party=htmlentities(htmlspecialchars($pet_name))."  <b><font color=black> Vs. </font></b>". htmlentities(htmlspecialchars($res_name));
?>
<input type="hidden" name="main_party" value="<?php print $main_party; ?>">

<input type="hidden" name="filing_no" value="<?php echo $caseDetails['filing_no']; ?>">
<input type="hidden" name="pet_name" value="<?php echo $pet_name; ?>">
<input type="hidden" name="res_name" value="<?php echo $res_name; ?>">
<input type="hidden" name="status" value="<?php echo $status; ?>">

	</font>
	</td>
</tr>


<tr>
<td  width="15%"align="right"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b><span class="error">*</span>Upload File
</td>
<td align="left">
<input type="file" name="userfile" id="userfile">
</td></tr>





<tr>
<td  align="right"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b><span class="error">*</span>Member Name
</td>
<td width="15%" align="left">
<?php
$judgeArray1[]='SELECT';
	foreach ($judgeArray as $val)
	$judgeArray1[$val['judge_code']]=$val['hon_text'].' ('.$val['judge_name'].')';
            //$caseTypesArray1[$val->case_type_code] = $val->short_name.' ('.$val->case_type_name.')';  
	echo form_dropdown('judge_code',$judgeArray1,set_value('judge_code',(isset($judge_code))?$judge_code:'',false),['class'=>'form-control','required'=>'required']); 
?>
</td></tr>


<tr>
<td align="right" width="160"> <font face="Verdana, Arial, Helvetica, sans-serif" size="2">
<b><span class="error">*</span>Order Type </font></td> 
<td width="15%" align="left" >
<?php $status1= array(''=>'SELECT','O'=>'Daily Order','M'=>'Misc Order','F'=>'Final Order','I'=>'Misc Order 2','N'=>'Misc Order 3','J'=>'Misc Order 4');	?>
		<?=form_dropdown('order_type',$status1,set_value('order_type',(isset($order_type))?$order_type:''),['class'=>'form-control','onchange'=>"getOption(this.value);",'id'=>'select1']);  ?>
</td>
</tr>
<tr>
<td  align="right"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b><span class="error">*</span>Order Date</td>
<td align="left"><input type="text" class="form-control datepicker" maxlength="10" size="10" name="order_date" id="fromup" value="<?php set_value('order_date'); ?>"   >         <b>(DD/MM/YYYY)</b></td>
</tr>
<tr>
<td  width="15%" align="right"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b><span class="error"></span>Remarks</td>
<td align="left" colspan="5">
<textarea name ="remarks" onFocus="SetBg(this);this.select()" onBlur="UnSetBg(this)" rows="1" cols ="50"></textarea>
</td>
</tr>






<tr>
	<td colspan="6" align="center"><br><br>
<input type="submit" name="submit1" value="Submit" class="btn btn-info" onClick="return validate('upload');">
<input type="hidden" name="case_no_send" value="<?php //print htmlentities(htmlspecialchars($case_no_send)); ?>">
<input type="hidden" name="dt_of_filing" value="<?php //print htmlentities(htmlspecialchars($dt_of_filing)); ?>">
<input type="hidden" name="case_type_send" value="<?php //print htmlentities(htmlspecialchars($case_type_send)); ?>">
<input type="hidden" name="csrf" value="<? //echo htmlentities(htmlspecialchars($key))?>" >
</td></tr>
<?php }  ?>
</table>
</form>
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
    var date = $('.datepicker').datepicker({ dateFormat: 'dd-mm-yy' }).val();

</script>
<?php $this->load->view("admin/footer"); ?>	
