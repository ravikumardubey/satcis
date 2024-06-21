<?php
$this->load->view("admin/header");
$this->load->view("admin/sidebar");
$this->load->view("admin/stepsdoc");
$salt=$this->session->userdata('docsalt');
$userdata=$this->session->userdata('login_success');
$userid=$userdata[0]->id;
$token= $this->efiling_model->getToken();
if($salt==''){
    $salt='';
}
$total=0;
$anx=0;
$filing_no='';
if($salt!=''){
    $basicia= $this->efiling_model->data_list_where('temp_docdetail','salt',$salt);
    if(!empty($basicia)){
        $basicia= $this->efiling_model->data_list_where('temp_docdetail','salt',$salt);
        $anx=isset($basicia[0]->totalanx)?$basicia[0]->totalanx:'';
        $filing_no=isset($basicia[0]->filing_no)?$basicia[0]->filing_no:'';
        $doctype=isset($basicia[0]->doctype)?$basicia[0]->doctype:'';
        $did=isset($basicia[0]->docids)?$basicia[0]->docids:'';
        if($doctype=='va'){
            $anx=1;
        }
        (int)$total=(int)$anx*25;
    }
}

$collectoive=0;
$feesd=$this->efiling_model->data_list_where('sat_temp_payment','salt',$salt);
foreach($feesd as $row){
    $collectoive+=$row->amount;
}


if($did==''){
    echo "<span style='color:red'>Please Select Document from party detail *</span>";die;
}

$display='';
if($doctype=='oth'){
    $display='display:none';
}
error_reporting(0);

?>

<form action="<?php echo base_url(); ?>ia_finalreceipt" target="_blank" class="wizard-form steps-basic wizard clearfix" id="finalsubmit" autocomplete="off" method="post" accept-charset="utf-8">
<?= form_fieldset().'<i class="icon-plus-circle2 text-danger" style="position: absolute;padding: 9px 6px;"></i>'; ?>
<input type="hidden" name="token" value="<?php echo $token; ?>" id="token">
<input type="hidden" name="filing_no" id="filing_no" value="<?php echo $filing_no; ?>">
<input type="hidden" name="saltNo" id="saltNo" value="<?php echo $salt; ?>">
<input type="hidden" name="tabno" id="tabno" value="7">
<input type="hidden" name="type" id="type" value="doc">
<input type="hidden" name="submittype" id="submittype" value="finalsave">
<div class="col-md-12" >
  	 		<FIELDSET>
            <LEGEND><b class="fa fa-upload">&nbsp;&nbsp;Payment  Details :</b></legend>
            <table id="example" class="display" cellspacing="0" border="1" width="100%">
            
          
            <?php
                $lable="Total Anexture"; 
                if($doctype=='va'){
                   $lable="Vakalatnama"; 
                   $anx=1;
                } 
            ?> 
            
                <thead>                    
                    <tr>
                        <th style="width:15%">Sr.No</th>                    
                         <th style="width:30%"><?php echo $lable; ?></th>        
                        <th style="width:40%">Amount</th>                   
                        <th style="width:70%">Total Fee</th>                    
                    </tr>
                </thead>
                <tbody>
                    <tr>
                    	<td>1</td>
                    	<td><?php echo '0'; ?></td>
                    	<td>0</td>
                    	<td><?php  echo '0'; ?></td>
                    </tr>
                </tbody>
            </table>
        </fieldset>

</div>   

<div class="row">
    <div class="offset-md-8 col-md-4 text-right">
        <input  type="button" value="Save and Next" id="feedetailsubmit" class="btn btn-success" onclick="rpepcpSubmitParty();">
		&nbsp;&nbsp;<input type="reset" value="Reset/Clear" class="btn btn-danger">
    </div>
</div>
<?= form_close();?>
<script>
function rpepcpSubmitParty(){
    var salt = document.getElementById("saltNo").value;
    var submittype = document.getElementById("submittype").value;
    var filing_no = document.getElementById("filing_no").value;
    var type = document.getElementById("type").value;
    var token=Math.random().toString(36).slice(2);
    var valtok='sat123'
    var token_hash=HASH(token+''+valtok);
    var dataa={};
    dataa['salt']=salt,
    dataa['filing_no']=filing_no,
    dataa['token']=token,
    dataa['type']=type, 
    dataa['submittype']=submittype, 
    $.ajax({
	    dataType: 'json',
        type: "POST",
        url: base_url+'docsave/'+token_hash,
        data: dataa,
        cache: false,
		beforeSend: function(){
			$('#feedetailsubmit').prop('disabled',true).val("Under proccess....");
		},
        success: function (resp) {
        	if(resp.data=='success') {
        		setTimeout(function(){
                    window.location.href = base_url+'doc_finalreceipt';
                 }, 250); 
			}else if(resp.error != '0') {
		        $.alert({
                  title: '<i class="fa fa-check-circle text-warning"></i>&nbsp;</b>Error!</b>',
                  content: '<p class="text-danger">'+resp.display+'</p>',
                  animationSpeed: 2000
                });
			}
        },
        error: function(){
			$.alert("Surver busy,try later.");
		},
		complete: function(){
		}
	 }); 
}

</script>   
<?php $this->load->view("admin/footer"); ?>