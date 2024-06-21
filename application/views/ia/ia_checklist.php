<?php
$this->load->view("admin/header");
$this->load->view("admin/sidebar");
$this->load->view("admin/stepsia");
$token= $this->efiling_model->getToken();
$salt=$this->session->userdata('iasalt');
if($salt==''){
    $this->load->view('ia/iabasic_detail',$data);
}
$userdata=$this->session->userdata('login_success');
$userid=$userdata[0]->id;
if($salt==''){
    $salt='';
}
$tab_no='';
$type='';
$totalia='';
$anx='';
$filing_no='';
$ianature='';
if($salt!=''){
    $basicia= $this->efiling_model->data_list_where('temp_iadetail','salt',$salt);
    $anx=isset($basicia[0]->totalanx)?$basicia[0]->totalanx:'';
    $type=isset($basicia[0]->type)?$basicia[0]->type:'';
    $filing_no=isset($basicia[0]->filing_no)?$basicia[0]->filing_no:'';
    $tab_no=isset($basicia[0]->tab_no)?$basicia[0]->tab_no:'';
    $totalia=isset($basicia[0]->totalia)?$basicia[0]->totalia:'';
    $ianature=isset($basicia[0]->ianature)?$basicia[0]->ianature:'';
}

?>
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

<?php defined('BASEPATH') OR exit('No direct script access allowed'); error_reporting(0); ?>
<div class="content" style="padding-top:0px;">
	<div class="row">
		<div class="card" style="width: 100%; padding: 0px 12px;border-top: 0px;margin-top: 0px; border-top-right-radius: 0px; border-top-left-radius: 0px;">
            <?= form_open(false,['class'=>'wizard-form steps-basic wizard clearfix','id'=>'chkList','autocomplete'=>'off']) ?>
            <input type="hidden" name="token" value="<?php echo $token; ?>" id="token">
    	    <input type="hidden" name="filing_no" id="filing_no" value="<?php echo $filing_no; ?>">
    	    <input type="hidden" name="saltNo" id="saltNo" value="<?php echo $salt; ?>">
    	    <input type="hidden" name="tabno" id="tabno" value="5">
    	    <input type="hidden" name="type" id="type" value="ia">
            <input type="hidden" value="" id="allCheck" readonly />
                <div class="content clearfix">
                        <?= form_fieldset('Checklist Details').
                        '<i class="icon-plus-circle2 text-danger" style="position: absolute;padding: 9px 6px;"></i>'.
                        '<div class="date-div text-success">Date & Time : &nbsp;<small>'.date('D M d, Y H:i:s').'&nbsp;IST</small></div>';
                        ?>
                      	<div class="row">   
                      	   <table class="table">
                              <thead>
                                <tr>
                                <th scope="col" style="width:2%;">S.No.</th>                                  
                                  <th scope="col" style="width:50%;">Checklist</th>
                                  <th scope="col" style="width:18%;">Action</th>
                                </tr>
                              </thead>
                              <tbody>
                              
                               <?php 
                                $i=1; foreach($checklist as $val){ ?>
                                    <tr>
                                      <td><?php echo $i; ?>.</td>
                                      <td><?php echo $val->c_name; ?></td>  
                                      <td>
                                      <input type="hidden" maxlength="200" name="value<?php echo $i; ?>" value="<?php echo $val->id; ?>" class="form-control">
                                      <input type="radio" name="check<?php echo $i; ?>" value="<?php echo $val->action_one; ?>">&nbsp;<?php echo ucfirst($val->action_one); ?>&nbsp;&nbsp;&nbsp;&nbsp;
                                    </tr>
                                <?php $i++; } ?>       
                              </tbody>
                            </table>
                      	</div>

                        <div class="row">
                            <div class="offset-md-8 col-md-4">
                                <input  type="button" value="Save and Next" class="btn btn-success" onclick="iachecksubmit();">
            					&nbsp;&nbsp;<input type="reset" value="Reset/Clear" class="btn btn-danger">
                            </div>
                        </div>
                    <?= form_fieldset_close(); ?>
                </div>
            <?= form_close();?>
        </div>
	</div>
</div>
<?php $this->load->view("admin/footer"); ?>
<script>
    $('#loading_modal').fadeOut(200);
    $("input[type='radio']").click(function(e){
        var noyes=0;
        $('input:radio').each(function () {
            var $this = $(this);
            if ($(this).prop('checked') && ($(this).val()=='Yes' || $(this).val()=='NA')) {
                noyes++;       
            }        
        });
        if(noyes==9){
            $('#allCheck').val(noyes);
            $('#chkBtn').removeAttr('disabled',false);
        }
        else {
            $('#chkBtn').attr('disabled',true);            
        }
    });

    function iachecksubmit(){
        var allCheck=$('#allCheck').val();
        if(allCheck){
            $.ajax({
                type: 'post',
                url: base_url+'chk_listdataIA',
                data: $('form').serialize(),
                dataType: 'json',
                success: function(rtn){
                    if(rtn.error=='0'){
                         setTimeout(function(){
                            window.location.href = base_url+'ia_finalprivew';
                         }, 250); 
                    }
                    else if(rtn.error=='1'){
                        $.alert(rtn.error);
                    }
                },
                error: function(){
                    $.alert("Server busy, try later!");
                }
            });
        }else {
            $('#allCheck').val('0');
            $('#chkBtn').attr('disabled',true);
            return false;
        }

    }
</script>
</div>

 