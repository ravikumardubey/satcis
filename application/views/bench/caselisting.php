<?php  $this->load->view("admin/header"); ?>
<?php  $this->load->view("admin/sidebar"); 
//$listing_date= isset($next_list_date)?$next_list_date:'';
$listing_date= $next_list_date; //die;
if($listing_date==''){
    $listing_date=date('d-m-Y');
}
?>
<script>
function submitForm1()
{
	//alert('hjjhj');
 	with(document.frm)
	{
	
	 action = " ";
	submit();
	}
}
</script>
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
  <!-- SweetAlert2 -->
<link rel="stylesheet" href="<?=base_url('asset/sweetalert2/sweetalert2.min.css')?>">
<script src="<?=base_url('asset/sweetalert2/sweetalert2.all.min.js')?>"></script>
<? $getBenchNatureListArray= set_value('getBenchNatureListArray'); ?>

<?=form_open('Bench/listingaction',['autocomplete'=>'off', 'name'=>'frm'])?>
<?php echo $this->session->userdata('error') ?> 
<table class="std table table-sm" align="center">
<tr><td colspan="10">        <p align="center"><b><font face="Verdana" size="2">CASE LISTING ALLOCATION</font></b></p></td></tr>
    <input type="hidden" name="filing_no" value="<?=@$filing_no;?>">

<tr>
<td colspan="6"> 

<center><b> <font> PARTY DETAILS</font><font color ="red" size="3">  <br>
<?php echo  $pet_name; ?>
<?php if($res_name !='--') print '      Vs     '. htmlspecialchars(htmlentities($res_name)); ?></font></b> </center>
</td>
<tr>
<td colspan="6"> 

<center><b> <font> </font><font color ="red" size="3">  <br>
</td></tr>

<tr>
<td  align="left" nowrap="nowrap"><div align="right"><span class="error">*</span><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><?php echo $label;?></div></td>
<td colspan="2">

      <input name="listing_date"   class="datepicker form-control-sm" type="text" size="10" maxlength="10" onChange="javascript:submitForm1();" value="<?=set_value('listing_date');?>"   > DD-MM-YYYY
</tr>

<tr>
<td  align="right"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><span class="error">*</span>Purpose</td>
<td colspan="4" >
			<?php 
			
				$purpose=[''=>'SELECT'];
				foreach($purposeArray as $row)
				$purpose[$row['purpose_code']]=$row['purpose_name']; 
				echo form_dropdown('purpose',$purpose,set_value('master_purpose',(isset($master_purpose))?$master_purpose:'',false),['class'=>'form-control-sm','required'=>'required']);
			?>

</td>
</tr>
<?php  //echo "bench".$ben;


?>
<tr>


      <td  align="left" nowrap="nowrap"><div align="right"><span class="error">*</span><font face="Verdana, Arial, Helvetica, sans-serif" size="2">To be listed before</div></td>
 <td colspan="2">
	<?php 
	$bench=[''=>'SELECT'];
	//$listing_date=set_value('listing_date');
	//$listing_date= (set_value('listing_date'))?set_value('listing_date'):$listing_date;
	
	//$benchcodes=[];
	//echo $listing_date; die;
	//$query = $this->db->get_where('bench',['from_list_date'=>date('Y-m-d',strtotime($listing_date))])->result_array();  //print_r($query); die;
	//if(!empty($query)):
	
	//foreach($query as $row) 
	//array_push($benchcodes,$row['bench_nature']);
	
	
	//print_r($benchcodes); 
	//$tt= $this->db->where_in('bench_code',$benchcodes)->get('bench_nature')->result_array();  
	//$tt= $this->db->get_where('bench_nature',['from_list_date'=>date('Y-m-d',strtotime($listing_date))])->result_array(); 
	//print_r($tt);
	//foreach($tt as $row1)
	foreach($benchNatureArray as $row1)
	//$bench=$row1('bench_name');
		$bench[$row1['bench_code']]=$row1['bench_name'];
	//echo $bench; //die;
	//endif;
	echo form_dropdown('bench_nature',$bench,set_value('bench_nature',(isset($bench_nature))?$bench_nature:'',false),['class'=>'form-control-sm','required'=>'required']);
?>

 
</td>


</tr>
<tr>
<?php if(!empty($getRemarksArray)):
	foreach($getRemarksArray as $row)
	$remarks1[$row['name']]=$row['name'];
	endif;
$remarks=10;?>

      <td  align="left" nowrap="nowrap"><div align="right"><span class="error">*</span><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Remarks</div></td>
 <td colspan="2">
 
 <?=form_dropdown('remarks',$remarks1,set_value('remarks',(isset($remarks))?$remarks:'',false),['class'=>'form-control-sm','required'=>'required']);
?>

</td>


</tr>

<!--input type="hidden" name="bench_no" value ="">
<input type="hidden"  name="court_no" value =""-->
<!--input type="hidden" name="from_time" value ="">
<input type="hidden" name="to_time" value =""-->
<input type="hidden" name="filing_no" value ="<?=$filing_no?>">

<tr><td colspan="4" align="center"><div align="center">
        <input type="submit"  value="Submit" class="btn btn-success"  onClick="return validate();">

      
</div></td></tr>

</table>

<?=form_close()?>
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
$(document).on('click','.remove',function() {
        $.ajax({
            url : $(this).attr("data-target"),
            type : 'POST',data : {"id":+this.id},
            beforeSend: function(){ $("body").addClass("loading");},
            success : function(response) {     },
            complete: function(){$("body").removeClass("loading");}
        });
        $(this).closest('tr').remove();
    });
	$(".datepicker" ).datepicker({ 
		dateFormat: "dd-mm-yy",
		minDate: 'now' + 1
		 // maxDate: 'now'
		 });
</script>