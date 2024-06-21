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
    if(!empty($basicrp)){
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
        $ianature=isset($basicrp[0]->iaNature)?$basicrp[0]->iaNature:'';
    }
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
<div id="rightbar"> 
	<form action="#" id="frmCounsel" autocomplete="off">    
        <input type="hidden" name="token" value="<?php echo $token; ?>" id="token">
        <input type="hidden" name="saltNo" id="saltNo" value="<?php echo $salt; ?>">
        <input type="hidden" name="filingno" id="filingno" value="<?php echo $filingno; ?>">
        <input type="hidden" name="type" id="type" value="<?php echo $type; ?>">
        <input type="hidden" name="tabno" id="tabno" value="<?php echo '5'; ?>">
        
        <input type="hidden" name="tia" id="tia" value="<?php echo $iano; ?>">
        <input type="hidden" name="tanx" id="tanx" value="<?php echo $anx; ?>">
          
          
        <div class="content" style="padding-top:0px;">
    		<fieldset id="iaNature" style="display:block"><legend class="customlavelsub">IA Details</legend>
    		 		<div class="col-md-2">
                        <div><label for="phone"><span class="custom"><font color="red">*</font>Total No Of IA:</span></label></div>
                        <div id="phone"><input type="text" name="totalNoIA" id="totalNoIA" class="form-control" maxlength="1"   value="<?php echo $iano; ?>" onkeypress="return isNumberKey(event)"/></div>
                    </div></br></br>
                   <div class="table-responsive">
                    <table datatable="ng" id="examples"  class="table table-striped table-bordered" cellspacing="0"  width="100%">
                        <thead>
                            <tr><th>#</th>                  
                                <th>IA Nature Nam</th>
                                <th>Fees</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $ianocc=explode(',', $ianature);
                          
                            $ianocc=explode(',', $ianature);
                            
                            $aDetail= $this->efiling_model->data_list('moster_ma_nature');
                            foreach($aDetail as $row) {
                                $valchecked='';
                                if(is_array($ianocc)){
                                    if(in_array($row->nature_code, $ianocc)){
                                        $valchecked="checked";
                                    }
                                }
                            ?>
                            <tr>
                                <td><input type = "checkbox" name = "natureCode" value ="<?php echo htmlspecialchars($row->nature_code); ?>" onclick="openTextBox(this);" <?php echo $valchecked; ?>></td>
                                <td><?php echo htmlspecialchars($row->nature_name);?></td>
                                <td><?php echo htmlspecialchars($row->fee);?></td>
                            </tr>
                        <?php }?>
                        </tbody>
                    </table>
                </div>
             </fieldset>
             <div class="row">
                <div class="offset-md-8 col-md-4">
                    <input  type="button" value="Save and Next" class="btn btn-success" onclick="rpepcpIAsubmit();">
					&nbsp;&nbsp;<input type="reset" value="Reset/Clear" class="btn btn-danger">
                </div>
            </div>

        </div>
     </form>
</div>  
<?php $this->load->view("admin/footer"); ?>
<script>
function rpepcpIAsubmit() {
    var salt = document.getElementById('saltNo').value; 
    var tia = document.getElementById('tia').value; 
    var tanx = document.getElementById('tanx').value;    
    var tabno= document.getElementById('tabno').value;    
    var filingno = document.getElementById('filingno').value;
    var type = document.getElementById('type').value;  
    var totalNoIA = $("#totalNoIA").val();
    if (totalNoIA == "") {
        alert("Please Enter Total No IA");
        document.filing.totalNoIA.focus();
        return false
    }
    var iaNature = "";
    var count = 0;
    var checkboxes = document.getElementsByName('natureCode');
    var selected = [];
    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].checked) {
            iaNature = iaNature + checkboxes[i].value + ',';
            count++;
        }
    }
    var ia = document.getElementById("totalNoIA").value;
    if(ia!=count){
        var msg = "You should be enter total no of IA -> " + ia + " so you cannot less then -> " + ia + " IA nature"
        alert(msg);
        return false;
    }
    var dataa = {};
    dataa['salt'] = salt;
    dataa['filingno'] = filingno;
    dataa['type'] = type;
    dataa['iaNature']=iaNature;
    dataa['tabno']=tabno;
    dataa['token'] = '<?php echo $token; ?>';
     $.ajax({
         type: "POST",
         url: base_url+"/rpepcpIAsubmit",
         data: dataa,
         cache: false,
         success: function (resp) {
        	var resp = JSON.parse(resp);
        	if(resp.data=='success') {
		       setTimeout(function(){
                    window.location.href = base_url+'petitionDoc';
               }, 250);
			}
			else if(resp.error != '0') {
				$.alert(resp.error);
			}
         },
         error: function (request, error) {
			$('#loading_modal').fadeOut(200);
         }
     }); 
} 
</script>