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
	<table border="0" width="100%" class="std table table-responsive table-sm">
    <form name="frm" method="post" action="causelist_upload" onSubmit="return validate();" autocomplete="off">
	<?php echo $this->session->userdata('error') ?>  
	<tr><td colspan="10">        <p align="center"><b><font face="Verdana" size="2">CAUSELIST REPORT</font></b></p></td>
	</tr>
    <tr>
		<th width="20%" align="right"><font face="Verdana" size="2"><span class="error">*</span><b>From Date</font></th>
		<td width="20%" align="left"><input type="text"  name="from_date" class="form-control datepicker" size="11" value="<?=set_value('from_date'); ?>"  class="datepicker"></td>
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
<th align="left"><font face="Verdana" size="2"><b>COURT NO </b></font></th>
<th align="left"><font face="Verdana" size="2"><b>BENCH DATE </b></font></th>
<th align="left"><font face="Verdana" size="2"><b>BENCH TIME</b></font></th>
<th  align="left"><font face="Verdana" size="2"><b>MEMBER</b></font></th>
<th  align="left"><font face="Verdana" size="2"><b>PRESIDING</b></font></th>
<th  align="left"><font face="Verdana" size="2"><b>VIEW CAUSE LIST</b></font></th>
<th  align="left"><font face="Verdana" size="2"><b>UPLOAD CAUSE LIST</b></font></th>
<th  align="left"><font face="Verdana" size="2"><b>DELETE</b></font></th>
</tr>
<?php
foreach($benches as $row1): 
extract($row1);    
$flag=1;
 ?>
<tr align="left">
<td size="2"> <?php echo $count = $count + 1; ?></td>
<td align="center" size="2"><?=$court_no?></td>
<td align="right" size="2"><?=date('d/m/Y',strtotime($from_list_date))?></td>
<td align="left" size="2"><?=$from_time?></td>
<td align="left" nowrap="nowrap" size="2">
<?php

function myCrypt($value, $key, $iv){
    $encrypted_data = openssl_encrypt($value, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
    return base64_encode($encrypted_data);
}


	$benchJudgeData=$this->db->get_where('bench_judge',['from_list_date'=>$from_list_date])->result_array();
	foreach($benchJudgeData as $benchJudgeRow):
		$judge_code= $benchJudgeRow['judge_code'];
		$query = $this->db->select('judge_name')->get_where('master_judge', ['judge_code' => $judge_code])->result_array();
			foreach($query as $row):
			$judge=$row['judge_name'];
			echo $judge.'<br>';
			endforeach;
		endforeach;
		
		$bench_id= $benchJudgeRow['bench_id'];
		$stlu =$this->db->query("select * from sat_causelist where  court_no='$court_no' AND bench_id='$bench_id' AND entry_date='$from_list_date'");
		$docurl= $stlu->result();
		$fullpath='';
		$dociod='';
		if(!empty($docurl)){
		    foreach ($docurl as $row){
		        $fullpath=$row->filename;
		        $key="01234567890123456789012345678901"; // 32 bytes
		        $vector="1234567890123412"; // 16 bytes
		        $fullpath = myCrypt($fullpath, $key, $vector);
		        
		        $dociod=$row->id;
    		}
		}
	
?>


</td>
<td align="left" nowrap="nowrap"><font color="red" size="2"><?php $presiding=@$row1['presiding'];$query = $this->db->select('judge_name')->get_where('master_judge', ['judge_code' => $presiding])->row_array(); echo @$query['judge_name']; ?></font></td>
<td align="left" nowrap="nowrap"><a href="<?php echo base_url(); ?>causelist_view/<?php echo base64_encode($fullpath); ?>" target="_blank">VIEW</a></td>
<td align="left" nowrap="nowrap"><a href="<?=base_url('uploadcauselistdoc/'.$id)?>"><?php if($dociod!=''){?>Uploaded<?php }else{ ?><button type="button" class="btn btn-xs btn-warning ">Upload</button><?php } ?></a></td>
<td align="left" nowrap="nowrap"><a href="javascript:void(0)" data-id="<?=$dociod?>" id="<?=$dociod?>" data-target="removecauselist" class="remove">DELETE CAUSE LIST</a></td>
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

<style>
.disable-click{
    pointer-events:none;
}
</style>