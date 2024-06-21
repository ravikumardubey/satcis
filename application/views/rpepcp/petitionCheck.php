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
                <input type="hidden" value="" id="allCheck" readonly />
                <input type="hidden" name="token" value="<?php echo $token; ?>" id="token">
                <input type="hidden" name="saltNo" id="saltNo" value="<?php echo $salt; ?>">
                <input type="hidden" name="filingno" id="filingno" value="<?php echo $filingno; ?>">
                <input type="hidden" name="type" id="type" value="<?php echo $type; ?>">
                <input type="hidden" name="tabno" id="tabno" value="<?php echo '7'; ?>">
        
                <div class="content clearfix">
                        <?= form_fieldset('Checklist Details').
                        '<i class="icon-plus-circle2 text-danger" style="position: absolute;padding: 9px 6px;"></i>'.
                        '<div class="date-div text-success"></div>';
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
                            	<?php
                    			echo '<i class="icon-file-plus" style="position: absolute; margin: 9px 4px; z-index: 9; color: white;"></i>'.
                                    form_submit(['value'=>'Save & Next','class'=>'btn btn-success','id'=>'chkBtn','style'=>'padding-left:24px;','disabled'=>'disabled']).
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
    $('#loading_modal').fadeOut(200);
    $("input[type='radio']").click(function(e){
        var noyes=0;
        $('input:radio').each(function () {
            var $this = $(this);
            if ($(this).prop('checked') && ($(this).val()=='Yes' || $(this).val()=='NA')) {
                noyes++;       
            }        
        });
        var count=noyes;
        if(noyes){
            $('#allCheck').val(noyes);
            $('#chkBtn').removeAttr('disabled',false);
        }
        else {
            $('#chkBtn').attr('disabled',true);            
        }
    });

    $('#chkList').submit(function(e){
        e.preventDefault();
        var allCheck=$('#allCheck').val();
        if(allCheck){
            $.ajax({
                type: 'post',
                url: base_url+'chk_listdatarpepcp',
                data: $('form').serialize(),
                dataType: 'json',
                success: function(rtn){
                    if(rtn.error=='0'){
                        setTimeout(function(){
                            window.location.href = base_url+'petitionCfee';
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

    });
</script>
</div>
<?php $this->load->view("admin/footer"); ?>
 