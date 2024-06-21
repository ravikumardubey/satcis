<?php  $this->load->view("admin/header"); ?>
<?php  $this->load->view("admin/sidebar"); 
$listingdate=isset($_REQUEST['listingdatre'])?$_REQUEST['listingdatre']:'';
if($listingdate==''){
    $listingdate=date('d/m/Y');
}
?>
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
  <!-- SweetAlert2 -->
<link rel="stylesheet" href="<?=base_url('asset/sweetalert2/sweetalert2.min.css')?>">
<script src="<?=base_url('asset/sweetalert2/sweetalert2.all.min.js')?>"></script>

<script language="javascript">
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
  return false;
}


function validate()

{
with(document.frm)
{


if(bench_code.value == "")
			{
				alert("Please select Bench Nature");
				bench_code.focus();
				return false;
			}

if(court_bench.value == "")
			{
				alert("Enter Bench No");
				court_bench.focus();
				return false;
			}
var iChars = "!@#$%^&*()+=-[]\\\';,./{}|\":<>? ";
             for (var i = 0; i < court_bench.value.length; i++) 
                {
  	        if (iChars.indexOf(court_bench.value.charAt(i)) != -1)
                {
  	        alert ("Cannot Enter Special Character in Bench No ");
  	        return false;
  	        }
                }
if(court_no.value == "")
			{
				alert("Enter Court No");
				court_no.focus();
				return false;
			}

var iChars = "!@#$%^&*()+=-[]\\\';,./{}|\":<>? ";
             for (var i = 0; i < court_no.value.length; i++) 
                {
  	        if (iChars.indexOf(court_no.value.charAt(i)) != -1)
                {
  	        alert ("Cannot Enter Special Character in Court No ");
  	        return false;
  	        }
                }
if(case_no.value == "")
			{
				alert("Enter Court No");
				case_no.focus();
				return false;
			}


if(from_time.value == "")
			{
				alert("Enter Court Start Hour");
				from_time.focus();
				return false;
			}
var iChars = "!@#$%^&*()+=-[]\\\';,./{}|\"<>? ";
             for (var i = 0; i < from_time.value.length; i++) 
                {
  	        if (iChars.indexOf(from_time.value.charAt(i)) != -1)
                {
  	        alert ("Cannot Enter Special Character in Time ");
                from_time.focus();
  	        return false;
  	        }
                }



if(from_list_date.value == "")
			{
				alert("From  Listing Date Cannot Be Blank");
				from_list_date.focus();
				return false;
			}
 var rgx = /^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/;

               if(from_list_date.value !="")
               {        
               if(!from_list_date.value.match(rgx)){
               alert("Please enter valid To From Listing Date ");
               from_list_date.focus();
               return false;
               }
}

if(to_list_date.value == "")
			{
				alert("To Listing Date");
				to_list_date.focus();
				return false;
			}
 var rgx = /^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/;

               if(to_list_date.value !="")
               {        
               if(!to_list_date.value.match(rgx)){
               alert("Please enter valid To Listing Date ");
               to_list_date.focus();
               return false;
               }


var str1 = from_list_date.value;
var str2 = to_list_date.value;

var dt1   = parseInt(str1.substring(0,2),10); 
var mon1  = parseInt(str1.substring(3,5),10);
var yr1   = parseInt(str1.substring(6,10),10); 
var dt2   = parseInt(str2.substring(0,2),10); 
var mon2  = parseInt(str2.substring(3,5),10); 
var yr2   = parseInt(str2.substring(6,10),10);
mon1 = mon1 -1 ;
mon2 = mon2 -1 ;
var from_list = new Date(yr1, mon1, dt1); 
var to_list = new Date(yr2, mon2, dt2); 
if(from_list >to_list)
{
alert(" From Listing Date  should be greater than To Listing Date ");
from_list_date.focus();
return false;
}

if(to_time.value == "")
			{
				alert("Enter Court End Hour");
				to_hour.focus();
				return false;
			}
var iChars = "!@#$%^&*()+=-[]\\\';,./{}|\"<>? ";
             for (var i = 0; i < to_time.value.length; i++) 
                {
  	        if (iChars.indexOf(to_time.value.charAt(i)) != -1)
                {
  	        alert ("Cannot Enter Special Character in Time ");
                to_time.focus();
  	        return false;
  	        }
                }


if(from_time.value.length <5)
{
alert("Invalid From Time , From Time Must Be in 24 Hour Date Format");
from_time.focus();
return false;
}

if(to_time.value.length <5)
{
alert("Invalid To Time ,To Time Must Be in 24 Hour Date Format");
to_time.focus();
return false;
}


if(from_time.value > to_time.value)
{
alert("Court Start Time Must be Lesser Than  Court End Time");
to_time.focus();
return false;

}
		
		if(no_of_judge.value > judge_count.value)
			{
				alert("Select More Judges from the list");
				judge_code.focus();
				return false;
			}      
		
		
			if(presiding.value == "")
			{
				alert("Please enter presiding judge code");
				presiding.focus();
				return false;
			}



var iChars = "!@#$%^&*()+=-[]\\\';,./{}|\":<>? ";
             for (var i = 0; i < presiding.value.length; i++) 
                {
  	        if (iChars.indexOf(presiding.value.charAt(i)) != -1)
                {
  	        alert ("Cannot Enter Special Character in Presiding Judge Code ");
  	        return false;
  	        }
                }
			
               }
	}
}


function submitForm()
{
 	with(document.frm)
	{
              

		action = " ";

		submit();
	}
}
function addjudge()
{
 	with(document.frm)
	{

		action = "bench_composition_action.php";

		submit();
	}
}
function submitForm2()
{
 	with(document.frm)
	{
           if(judge_code.value == "" || isNaN(judge_code.value))
	       {
		alert("Please Enter Judge Code!");
		judge_code.focus();
		return false;
	       }
		action = "bench_composition.php";
		submit();
	}
}

function addNumbers(val)
                {
                       
                        var c=document.getElementById("from_time").value.length;
                        if(c==2){
                        var newval = val+ ":";
                        document.getElementById("from_time").value=newval;
                         }

                }
function addNumbers1(val)
                {
                       
                        var c=document.getElementById("to_time").value.length;
                        if(c==2){
                        var newval = val+ ":";
                        document.getElementById("to_time").value=newval;
                         }

                }

</script>


<body>

<center> 
<div class="content">
	<div class="row">
              <div class="container">
<form name="frm" method="post" action="savebench" onSubmit="return validate();">
<?php echo $this->session->userdata('error') ?>
<p>

<table cellspacing="0" align="center" cellpadding="2" border="0" width="75%" class="std table table-responsive " align="center">  
<tr>
 <td colspan="10">
        <p align="center"><b><font face="Verdana" size="2">BENCH COMPOSITION/CONSTITUTION</font></b></p></td>
    </tr>
	
	<?php // if($this->session->set_flashdata('message')) { echo '<div class="message"> '; 	echo '<p>'.$this->session->set_flashdata('message').'</p>'; } ?>
    <tr>
      <td colspan="10">
        <p align="center"><font face="Verdana" size="2">Fields marked with a <span class="error">*</span> are compulsory.</font></td>
    </tr>

		<tr>
			<th width="25%" align="left" >
						<font face="Verdana" size="2" align="left"><span class="error">*</span><b>Bench Nature<b></font>
					</th>
			<td align="left">
			  	<?php // print_r($benchNatures); die; ?>
					
					
					<?php
					$bench=[''=>'SELECT'];
					foreach($benchNatures as $row)
					$bench[$row['bench_code']]=$row['bench_name'];
					///print_r($bench1); die;
					echo form_dropdown('bench_nature',$bench,set_value('bench_nature',(isset($bench_nature))?$bench_nature:'',false),['class'=>'form-control','required'=>true,'onChange'=>'javascript:submitForm();','required'=>'required','id'=>'test']);
					?>
			</td>
					<th width="25%" colspan="3">
						<font color="red">
							<?php	echo"<b>NUMBER OF JUDGES:</b>   ".$no_of_judges ."<br>"; ?>
						</font>
						
					</th>
		</tr>

			<tr>
				<th width="25%" align="left">
				<font face="Verdana" size="2"><span class="error">*</span><b>Listing Date</b></font>
				</th>
				<td width="25%" align="left">
				<?=form_input('from_list_date',set_value('from_list_date',(isset($from_list_date))?$from_list_date:''),['id'=>'fromup','class'=>'form-control datepicker','required'=>true,'maxlength'=>'10']) ?><b>DD-MM-YYYY</b>
				</td>
			</tr>

			<tr>
				<th width="25%" align="left">
				<font face="Verdana" size="2"><span class="error">*</span><b>From Time</b></font>
				</th>
				<td width="25%" align="left">
				
				<input type="time" name="from_time" class="form-control" placeholder="hrs:mins" pattern="^([0-1]?[0-9]|2[0-4]):([0-5][0-9])(:[0-5][0-9])?$" class="inputs time" required>
				</td>
			</tr>	
			<tr><td width="25%" colspan="6" align="center"><b><FONT COLOR="RED">Note:</FONT> TIME MUST BE IN 12 HOUR FORMAT (HH:MM)</b></td></tr>
			<tr>
				<th width="25%" align="left" nowrap="nowrap"><font face="Verdana" size="2"><span class="error">*</span><b>Court No.</b></font></th>
				<td width="25%" align="left">
				<?= form_input('court_no',set_value('court_no',(isset($court_no))?$court_no:''),['class'=>'form-control','onKeyup'=>'javascript:toNumbers(this.value)','maxlength'=>'3','required'=>true,'size'=>1]) ?>
				</td>
			</tr>
			<tr>
				<td width="25%" align="center" valign="top" colspan="6"><font face="Verdana" size="3"><span class="error"><b>Hon'ble Justice Sitting Composition</b></span></font></td></tr>
				<tr>
					<td width="25%" colspan=6 >
						<table width="100%" cellspacing="1" align="center" cellpadding="2" class="tbl table">
							<?php for($j=1;$j<=$no_of_judges;$j++):?>
								<tr>
									<td width="25%"  align="right" nowrap="nowrap"><font face="Verdana" size="2" ><span class="error">*</span><b>Select Judge<?php echo htmlspecialchars($j);?></b></font></td>
									<td width="25%" align="left">
										<select name="judge[<?=$j?>]"  style='width:300px;' class="form-control" required="required">
											<option value=""> SELECT</option>
											<?php	foreach($judges as $row):	?>
											<option value="<?=$row['judge_code'];?>"> <?=$row['judge_name']; ?></option>
											<?php	endforeach;	?>
										</select>
									</td>
								</tr>
								<?php endfor;?>
							<?php
							if($no_of_judges >1)
							{
							?>
								<tr>
									<td width="25%"  align="right" nowrap="nowrap"><font face="Verdana" size="2" ><span class="error">*</span><b><i>Select Presiding Judge</b></i></font></td>
									<td width="25%" align="left">
										<select name="presiding"  style='width:300px;' class="form-control" required="required">
											<option value=""> SELECT</option>
											<?php
											$m=0;
											for($i=1;$i<=$no_of_judges;$i++)
											{
												$m++;
												?>
											<option value="<?=$i;?>"> Judge <?php echo htmlspecialchars($m);?></option>";
												<?php
											}
											?>
										</select>
									</td>
								</tr>
							<?php
							}
							?>


							<tr>
								<td width="25%" colspan="4" align="center">




									<input type="submit"  value="Submit" class="button btn btn-success" onClick="validate();" >
								</td>

							</tr>

						</table>
						</td>
						</tr>

 </table>
</form>
</div></div></div>

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
</script>
<?php $this->session->unset_userdata('Success');?>
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
		minDate: 'now'
		 // maxDate: 'now'
		 });

</script>