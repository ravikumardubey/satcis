<?php  $this->load->view("admin/header"); ?>
<?php  $this->load->view("admin/sidebar"); 
$listingdate=isset($_REQUEST['listingdatre'])?$_REQUEST['listingdatre']:'';
if($listingdate==''){
    $listingdate=date('d-m-Y');
}
?>
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<link rel="stylesheet" href="<?=base_url('asset/sweetalert2/sweetalert2.min.css')?>">
<script src="<?=base_url('asset/sweetalert2/sweetalert2.all.min.js')?>"></script>
<!-- div class="panel panel-default">

	<div class="panel-heading">
		<div class="left">BENCH COMPOSITION REPORT</div>
		<div class="right"><a href="<?=base_url('bench/composition')?>"><button type="button" class="btn btn-xs btn-warning">CREATE BENCH</button></a></div>
	</div>
	<div class="panel-body">
		<div class="col-md-12 well"-->
	<table border="0" width="100%" class="std table table-responsive table-sm">
    <form name="frm" method="post" action="viewbench" onSubmit="return validate();" autocomplete="off">
	<?php echo $this->session->userdata('error') ?>  
	<tr><td colspan="10">        <p align="center"><b><font face="Verdana" size="2">BENCH COMPOSITION REPORT</font></b></p></td>
		<td colspan="10">   <a href="<?=base_url('composition')?>"><button type="button" class="btn btn-xs btn-warning">CREATE BENCH</button></a></td>
	</tr>

    <tr>
		<th width="20%" align="right"><font face="Verdana" size="2"><span class="error">*</span><b>From Date</font></th>
		<td width="20%" align="left"><input type="text"  name="from_date" class="form-control datepicker" size="11" value="<?=set_value('from_date'); ?>"  class="datepicker"></td>

		<th width="20%" align="left"><font face="Verdana" size="2"><span class="error">*</span><b>To Date</font></th>
		<td width="20%" align="left"><input type="text"  name="to_date" size="11" class="form-control datepicker" value="<?=set_value('to_date'); ?>" class=""></td>
		
		<td align="left">
		<input type="submit" class="text btn btn-info" name="submit1" value="View Report" onClick="return validate();">
		</td>
	</tr>
    </form>
	</table>

<?php 
$count=0;
if(!empty($benches)): ?>
  <table width="100%" border="0" cellpadding="1" cellspacing="1" class="tbl table table-responsive" align="center">
<tr>

<th  align="left"><font face="Verdana" size="2"><b>Sl. No.</b></font></th>
<th  align="left"><font face="Verdana" size="2"><b>BENCH NATURE</b></font></th>
<!--th align="left"><font face="Verdana" size="2"><b>BENCH NO</b></font></th-->
<th align="left"><font face="Verdana" size="2"><b>COURT NO </b></font></th>
<th align="left"><font face="Verdana" size="2"><b>BENCH DATE </b></font></th>
<th align="left"><font face="Verdana" size="2"><b>BENCH TIME</b></font></th>
<!--th align="left"><font face="Verdana" size="2"><b>TO DATE </b></font></th>
<th align="left"><font face="Verdana" size="2"><b>TO TIME</b></font></th-->
<th  align="left"><font face="Verdana" size="2"><b>MEMBER</b></font></th>
<th  align="left"><font face="Verdana" size="2"><b>PRESIDING</b></font></th>
<th  align="left"><font face="Verdana" size="2"><b>DELETE</b></font></th>
</tr>
<?php
foreach($benches as $row1): 
extract($row1);    
$flag=1;
 ?>

<tr align="left">
<td size="2"> <?php echo $count = $count + 1; ?></td>
<td align="left" size="2"><?=$bench_nature?></td>

<!--td align="center"><?//=$bench_no?></td-->
<td align="center" size="2"><?=$court_no?></td>
<td align="right" size="2"><?=date('d/m/Y',strtotime($from_list_date))?></td>
<td align="left" size="2"><?=$from_time?></td>
<!--td align="right"><?//=date('d/m/Y',strtotime($to_list_date))?></td>
<td align="left"><?//=$to_time?></td-->
<td align="left" nowrap="nowrap" size="2">
<?php
	
	$benchJudgeData=$this->db->get_where('bench_judge',['from_list_date'=>$from_list_date,'from_time'=>$from_time])->result_array();
	//print_r($benchJudgeData); die;
	foreach($benchJudgeData as $benchJudgeRow):
	//extract($benchJudgeRow);
		$judge_code= $benchJudgeRow['judge_code'];
		$query = $this->db->select('judge_name')->get_where('master_judge', ['judge_code' => $judge_code])->result_array();
			foreach($query as $row):
			$judge=$row['judge_name'];
			echo $judge.'<br>';
			endforeach;
		endforeach;
?>
</td>

<td align="left" nowrap="nowrap"><font color="red" size="2"><?php $presiding=@$row1['presiding'];
$query = $this->db->select('judge_name')->get_where('master_judge', ['judge_code' => $presiding])->row_array(); echo @$query['judge_name']; ?></font></td>
<td align="left" nowrap="nowrap">
<a href="javascript:void(0)" data-id="<?=$id?>" id="<?=$id?>" data-target="removeBench" class="remove">DELETE</a></td>


</td>
</tr>
<?php endforeach;  endif;?>
<?php  if(empty($benches)) { ?>
<tr><td colspan=7><p align="center"><font face="Verdana" size="2" color="red">No Record Found</font></p></td> <?php  } ?></tr>
</table>


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
	var date = $('.datepicker').datepicker({ dateFormat: 'dd-mm-yy' }).val();
</script>
