<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view("admin/header");
$this->load->view("admin/sidebar");
$this->load->view("admin/stepsrpepcp");
$salt=$this->session->userdata('rpepcpsalt');
$token= $this->efiling_model->getToken();
$partytype='';
$filingno='';
$tab_no='';
$type='';
if($salt!=''){
    $basicrp= $this->efiling_model->data_list_where('rpepcp_reffrence_table','salt',$salt);
    $iano=isset($basicrp[0]->totalNoia)?$basicrp[0]->totalNoia:'';
    $anx=isset($basicrp[0]->totalNoAnnexure)?$basicrp[0]->totalNoAnnexure:'';
    $type=isset($basicrp[0]->type)?$basicrp[0]->type:'';
    $order_date=isset($basicrp[0]->order_date)?$basicrp[0]->order_date:'';
    if($order_date!=''){
        $orderd=date('d-m-Y',strtotime($order_date));
    }
    $filingno=isset($basicrp[0]->filing_no)?$basicrp[0]->filing_no:'';
    $tab_no=isset($basicrp[0]->tab_no)?$basicrp[0]->tab_no:'';
    $type=isset($basicrp[0]->type)?$basicrp[0]->type:'';
}
$partyType=isset($_REQUEST['partyType'])?$_REQUEST['partyType']:'';
if($partyType=='1'){
    $partytype='1';
}
if($partyType=='2'){
    $partytype='2';
}
if($partyType=='3'){
    $partytype='3';
}

?>
<?php defined('BASEPATH') OR exit('No direct script access allowed'); error_reporting(0); ?>
<div class="content" style="padding-top:0px;">
	<div class="row">
		<div class="card" style="width: 100%; padding: 0px 12px;border-top: 0px;margin-top: 0px; border-top-right-radius: 0px; border-top-left-radius: 0px;">
            <?= form_open(false,['class'=>'wizard-form steps-basic wizard clearfix','id'=>'other_fee','autocomplete'=>'off']) ?>
                <div class="content clearfix">
                    <?= form_fieldset('Other Fee Details').
                        '<i class="icon-plus-circle2 text-danger" style="position: absolute;padding: 9px 6px;"></i>'.
                        '<div class="date-div text-success"></div>';
                        ?>
                      	<div class="row">

					    <?php 
                        $st=$this->efiling_model->data_list_where('rpepcp_reffrence_table','salt', $salt);
                        ?>
                        <input type="hidden" name="token" value="<?php echo $token; ?>" id="token">
                        <input type="hidden" name="saltNo" id="saltNo" value="<?php echo $salt; ?>">
                        <input type="hidden" name="filingno" id="filingno" value="<?php echo $filingno; ?>">
                        <input type="hidden" name="type" id="type" value="<?php echo $type; ?>">
                        <input type="hidden" name="tabno" id="tabno" value="<?php echo '8'; ?>">
                            <div class="table-responsive">
                                <table id="examples"
                                       class="table table-striped table-bordered" cellspacing="0"
                                       width="100%">
                                    <thead>
                                    <tr>
                                        <th>Sr. No.</th>
                                        <th></th>
                                        <th>Fee Document Name</th>
                                        <th>Fee</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                        
                                    <?php
                                    $aDetail = $this->efiling_model->data_list('master_fee_detail');
                                    $fgfg = 1;
                                    $i = 0;
                                    foreach($aDetail as $row) {
                                        $fee='';
                                        if($row->doc_code=='11'){
                                            $fee=$row->doc_fee*$anx;
                                        }
                                        if($row->doc_code=='9'){
                                            $fee=$row->doc_fee;
                                        }
                                        
                                        if($row->doc_code=='7'){
                                            $fee=$row->doc_fee*1;
                                        }
                                        $val='Checked';
                                        ?>
                                        <tr>
                                            <td> <?php echo $fgfg; ?></td>
                                            <td><input type="checkbox" name="otherFeeCode" value="<?php echo $row->doc_code; ?>" <?php echo $val;?> disabled></td>
                                            <td> <?php echo htmlspecialchars($row->doc_name); ?></td>
                                            <td> 0</td>
                                        </tr>
                                        <?php $fgfg++;
                                        $i++;
                                    } ?>
                                    </tbody>
                                </table>
                            </div>
                      	</div>
						<br>
                        <div class="row">
                            <div class="offset-md-8 col-md-4">
                            	<?php
                    			echo '<i class="icon-file-plus" style="position: absolute; margin-left: 200px;margin: 9px 4px; z-index: 9; color: white;"></i>'.
                                    form_submit(['value'=>'Next','class'=>'btn btn-success','id'=>'other_feesave','style'=>'padding-left:24px;']).
                                     '<i class="icon-trash-alt" style="position: absolute; z-index: 9; margin: 10px 4px; color:white;"></i>'.
                    			     form_reset(['value'=>'Reset/Clear','class'=>'btn btn-danger','style'=>'padding-left: 24px;']);
                                    //  .form_button(['id'=>'','value'=>'false','content'=>'&nbsp;Next','class'=>'icon-arrow-right8 btn btn-primary']);
                            	?>
                            </div>
                        </div>
                    <?= form_fieldset_close(); ?>
                </div>
            <?= form_close();?>
        </div>
	</div>
</div>	
<script>
$('#other_fee').submit(function(e){
	e.preventDefault();
    var salt = document.getElementById("saltNo").value;
    var token = document.getElementById("token").value;
    var checkboxes1 = document.getElementsByName('otherFeeCode');
    var otherFee = "";
    var count1 = 0;
    var selected = [];
    for (var i = 0; i < checkboxes1.length; i++) {
        if (checkboxes1[i].checked) {
            otherFee = otherFee + checkboxes1[i].value + ',';
            count1++;
        }
    }
    var tabno= document.getElementById("tabno").value;
    var dataa = {};
	dataa['salt']  =salt;
	dataa['token'] =token;
	dataa['fee']   =otherFee;
	dataa['tabno'] =tabno;
	$.ajax({
        type: "POST",
        url: base_url+'otherFeesaveRPEPCP',
        data: dataa,
        cache: false,
		beforeSend: function(){
			$('#other_feesave').prop('disabled',true).val("Under proccess....");
		},
        success: function (resp) {
        	var resp = JSON.parse(resp);
        	if(resp.data=='success') {
        		setTimeout(function(){
                    window.location.href = base_url+'petitionFP';
                 }, 250);
			}
			else if(resp.error != '0') {
				$.alert(resp.error);
			}
        },
        error: function(){
			$.alert("Surver busy,try later.");
		},
		complete: function(){
			$('#other_feesave').prop('disabled',false).val("Submit");
		}
	 }); 

});
</script>
<?php $this->load->view("admin/footer"); ?>