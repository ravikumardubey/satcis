<?php $this->load->view("admin/header");
$this->load->view("admin/sidebar"); 
$token= $this->efiling_model->getToken();
$salt=$this->session->userdata('salt'); 
?>
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<script src="<?=base_url('asset/admin_js_final/customs.js')?>"></script> 
<style>
.srchWrap {
    margin-left: 194px;
    position: relative;
    float: right;
    width: 100%;
    margin-right: 10px;
}
.srchWrap input {
    padding-left: 35px;
    font-size: 16px;
}
.srchWrap input:focus {
    border: 1px solid #2196f3 !important;
}
.srchWrap i {
    position: absolute;
    left: 12px;
    top: 14px;
}
</style>
<div id="rightbar"> 
<?php  include 'steps.php';?>
<?php defined('BASEPATH') OR exit('No direct script access allowed'); error_reporting(0); ?>
<div class="content" style="padding-top:0px;">
	<div class="row">
		<div class="card" style="width: 100%; padding: 0px 12px;border-top: 0px;margin-top: 0px; border-top-right-radius: 0px; border-top-left-radius: 0px;">
            <?= form_open(false,['class'=>'wizard-form steps-basic wizard clearfix','id'=>'other_fee','autocomplete'=>'off']) ?>
                <div class="content clearfix">
                    <?= form_fieldset('Other Fee Details').
                        '<i class="icon-plus-circle2 text-danger" style="position: absolute;padding: 9px 6px;"></i>'.
                        '<div class="date-div text-success">Date & Time : &nbsp;<small>'.date('D M d, Y H:i:s').'&nbsp;IST</small></div>';
                        ?>
                      	<div class="row">
                      	<input type="hidden"  id="tabno" name="tabno" value="<?php echo '6'; ?>">
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

                            <table class="table">
                              <thead>
                                 <tr style="background-color: #ebdada">
                                  <th scope="col">S.NO</th>
                                  <th scope="col">Regulator</th>
                                  <th scope="col">Fees</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <td><?php echo htmlspecialchars(1);?></td>
                                  <td><?php echo $orderpassingauthority;?></td>
                                  <td><?php echo $courtfee;?></td>
                                </tr>
                              </tbody>
                            </table>
                            
                            
                      	</div>

                        <div class="row">
                            <div class="offset-md-8 col-md-4">
                            	<?php
                    			echo '<i class="icon-file-plus" style="position: absolute; margin: 9px 4px; z-index: 9; color: white;"></i>'.
                                    form_submit(['value'=>'Save & Next','class'=>'btn btn-success','id'=>'other_feesave','style'=>'padding-left:24px;']).
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
    var tabno= document.getElementById("tabno").value;
    var dataa = {};
	dataa['salt'] =salt;
	dataa['token']=token;
	dataa['tabno']=tabno;
	$.ajax({
        type: "POST",
        url: base_url+'otherFeesave',
        data: dataa,
        cache: false,
		beforeSend: function(){
			$('#other_feesave').prop('disabled',true).val("Under proccess....");
		},
        success: function (resp) {
        	var resp = JSON.parse(resp);
        	if(resp.data=='success') {
        		setTimeout(function(){
                    window.location.href = base_url+'document_upload';
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