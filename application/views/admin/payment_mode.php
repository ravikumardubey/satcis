<?php defined('BASEPATH') OR exit('No direct script access allowed'); error_reporting(); 
$this->load->view("admin/header");
$this->load->view("admin/sidebar"); 
$token= $this->efiling_model->getToken(); 
$salt=$this->session->userdata('salt'); 
?>
<script src="<?=base_url('asset/admin_js_final/customs.js')?>"></script> 
<div id="rightbar"> 
<?php  include 'steps.php';?>
<div class="content" style="padding-top:0px;">
<script>
 paymentMode();
</script>
	<div class="row">
		<div class="card" style="width: 100%; padding: 0px 12px;border-top: 0px;margin-top: 0px; border-top-right-radius: 0px; border-top-left-radius: 0px;">
            <?= form_open(false,['class'=>'wizard-form steps-basic wizard clearfix','id'=>'payment_mode','autocomplete'=>'off']) ?>
                <div class="content clearfix">
                    <?= form_fieldset('Fee Details').
                        '<i class="icon-plus-circle2 text-danger" style="position: absolute;padding: 9px 6px;"></i>'.
                        '<div class="date-div text-success">Date & Time : &nbsp;<small>'.date('D M d, Y H:i:s').'&nbsp;IST</small></div>';
                        ?>
                        <input type="hidden"  id="tabno" name="tabno" value="<?php echo '8'; ?>">
					    <input type="hidden" id="saltNo" name="saltNo" value="<?php echo $salt; ?>">
    					<input type="hidden" name="token" value="<?php echo $token; ?>" id="token">
    			  <?php
					    $courtfee=0;
					    $count=0;
					    $feeval=0;
					    $ipenalty='';
					    $apealtype='';
					    $opauthority='';
					    $regulatorname='';
					    $orderpassingauthority='';
                        $st=$this->efiling_model->data_list_where('sat_temp_appellant','salt', $salt);
                        if(!empty($st)){
                            $appname=isset($st[0]->pet_name)?$st[0]->pet_name:''; 
                            $ipenalty = $st[0]->ipenalty;
                            $apealtype = $st[0]->apeal_type;
                            $opauthority = $st[0]->opauthority; 
                            $stfee=$this->efiling_model->data_list_where('master_regulator','order_pass_auth_id', $opauthority);
                            if(!empty($stfee)){
                                $regulatorfeeval=$stfee[0]->fee;
                                $orderpassingauthority=$stfee[0]->order_passing_authority;
                                $regulatorname=$stfee[0]->order_passing_authority;
                            }
                        }

                        $additionalparty=$this->efiling_model->data_list_where('sat_temp_additional_party','salt',$salt);
                        $val=0;
                        if($appname!=''){
                            $val='1';
                        }
                        $count= count($additionalparty)+$val;
                        $applicantfee=$this->efiling_model->calculateapp($count);
                        
                        
                        $exclusiveamount='500';
                        if($regulatorname=='Adjudicating Officer'){
                            if($ipenalty<'10000'){
                                $courtfee='500';
                            }
                            
                            if($ipenalty>'10000'){  
                                $courtfee='1200';
                            }
                            
                            if($ipenalty=='100000'){
                                $courtfee=(int)$feeval;
                            }
                            
                            if($ipenalty>'100000'){
                                $var=$ipenalty-100000;
                                $var2=$var/100000;
                                $var3=ceil($var2);
                                $courtfee=($var3*500)+1200;
                            }
                        }
                        
                        if($regulatorname!='Adjudicating Officer'){
                            $courtfee=$applicantfee;
                        }
                        ?>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group required">
                                    <label>Regulator Fee</label>
                                    <?= form_input(['name'=>'totalFee3','id'=>'totalFee3','class'=>'form-control','readonly'=>'true','value'=>$courtfee]) ?>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group required">
                                    <label>MA Fee</label>
                                    <?= form_input(['name'=>'iaFee1','id'=>'iaFee1','class'=>'form-control','readonly'=>'true','value'=>$mafee]) ?>
                                </div>
                            </div>  
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Total Fee</label>
                                    <?= form_input(['name'=>'total','id'=>'total','class'=>'form-control','readonly'=>'true','value'=>$courtfee]) ?>
                                </div>
                            </div>
                        </div>
                        </fieldset>                       
		                 <div class="row">
                            <div class="offset-md-8 col-md-4">
                            	<?php
                    			echo '<i class="icon-file-plus" style="position: absolute; margin: 9px 4px; z-index: 9; color: white;"></i>'.
                                    form_submit(['value'=>'Save & Next','class'=>'btn btn-success','id'=>'feedetailsubmit','style'=>'padding-left:24px;']).
                                     '<i class="icon-trash-alt" style="position: absolute; z-index: 9; margin: 10px 4px; color:white;"></i>'.
                    			     form_reset(['value'=>'Reset/Clear','class'=>'btn btn-danger','style'=>'padding-left: 24px;']);
                            	?>
                            </div>
                        </div>
                    <?= form_fieldset_close(); ?>
                </div>
            <?= form_close();?>
        </div>
	</div>
    </div>
    
</div>	

<script>

function feeCalculation() {
    var exp = document.getElementById("payAmount").value;
    var total = document.getElementById("total").value;
    var totalNoIA1 = document.getElementById("totalNoIA").value;
    var salt = document.getElementById("saltNo").value;
    var iafee = totalNoIA1 * 1000;
    var dataa={};
    dataa['resexp']=exp,
    dataa['salt']=salt,
    dataa['total']=total,
    $.ajax({
	    dataType: 'json',
        type: "POST",
        url: base_url+'feeCalculation',
        data: dataa,
        cache: false,
		beforeSend: function(){
			//$('#feedetailsubmit').prop('disabled',true).val("Under proccess....");
		},
        success: function (resp) {
        	if(resp.data=='success') {
        		$('#feeCalculation').show();
        		$('#left_amountd_fee').val(resp.value);
        		
			}else if(resp.error != '0') {
				$.alert(resp.error);
			}
        },
        error: function(){
			$.alert("Surver busy,try later.");
		},
		complete: function(){
			
		}
	 }); 
	 
    document.getElementById("feeCalculation").style.display = 'block';
}


$('#payment_mode').submit(function(e){
	e.preventDefault();
	var token = document.getElementById("token").value;
	var tabno = document.getElementById("tabno").value;
    var salt = document.getElementById("saltNo").value;
    var radios = document.getElementsByName("bd");
    var bd = 0;
    for (var i = 0; i < radios.length; i++) {
        if (radios[i].checked) {
            var bd = radios[i].value;
        }
    }
    var totalFee3 = document.getElementById("totalFee3").value;
    var iaFee1 = document.getElementById("iaFee1").value;
    var tabno= document.getElementById("tabno").value;
    var dataa = {};
	dataa['salt'] =salt,
	dataa['totalFee'] = totalFee3,
	dataa['iaFee'] =  iaFee1,
	dataa['tabno']=tabno;
	$.ajax({
	    dataType: 'json',
        type: "POST",
        url: base_url+'payfeedetailsave',
        data: dataa,
        cache: false,
		beforeSend: function(){
			$('#feedetailsubmit').prop('disabled',true).val("Under proccess....");
		},
        success: function (resp) {
        	if(resp.data=='success') {

    		   setTimeout(function(){
                    window.location.href = base_url+'final_preview';
                 }, 250);
         
         
        	    document.getElementById("loading_modal").style.display = 'none';
        	    $('#step_7').removeClass('btn-danger btn-warning btn-info').addClass('btn-success');
        	    $('#step_8').removeClass('btn-danger btn-warning btn-info').addClass('btn-warning');
			}
			else if(resp.error != '0') {
				$.alert(resp.error);
			}
        },
        error: function(){
			$.alert("Surver busy,try later.");
		},
		complete: function(){
			$('#feedetailsubmit').prop('disabled',false).val("Submit");
		}
	 }); 
});


function paymentMode(sel) {
    var radios = document.getElementsByName("bd");
    var bd = 0;
    for (var i = 0; i < radios.length; i++) {
        if (radios[i].checked) {
            var bd = radios[i].value;
        }
    }
    if (bd > 0) {
         $.ajax({
            type: "POST",
            url: base_url+"postalOrder",
            data: "app=" + bd,
            cache: false,
            success: function (data) {
            	$('#payMode').html(data);
            }
        });
    }
}

function isNumberKey(evt){ 
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57)){
      return false;
    }else{
		 return true;
    }
}

</script>
<?php $this->load->view("admin/footer"); ?>