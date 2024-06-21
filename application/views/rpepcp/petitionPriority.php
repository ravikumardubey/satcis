<?php 

$this->load->view("admin/header"); 
$this->load->view("admin/sidebar"); 
$this->load->view("admin/stepsrpepcp"); 
$salt=$this->session->userdata('rpepcpsalt');
$token= $this->efiling_model->getToken();
$partytype='';
$filingno='';
$tab_no='';
$type='';
$partyid='';
$partyidval='';
$pid='';
$mainparty = '';
$partflagres='';
$partflagpet='';
$partyType='';

$app_party_id='';
$app_priority='';
$res_partyid='';
$res_priority='';

if($salt!=''){
    $basicrp= $this->efiling_model->data_list_where('rpepcp_reffrence_table','salt',$salt);
    $app_party_id=isset($basicrp[0]->app_party_id)?$basicrp[0]->app_party_id:'';
    $app_priority=isset($basicrp[0]->app_priority)?$basicrp[0]->app_priority:'';
    $res_partyid=isset($basicrp[0]->res_partyid)?$basicrp[0]->res_partyid:'';
    $res_priority=isset($basicrp[0]->res_priority)?$basicrp[0]->res_priority:'';
    
    $iano=isset($basicrp[0]->totalNoia)?$basicrp[0]->totalNoia:'';
    $anx=isset($basicrp[0]->totalNoAnnexure)?$basicrp[0]->totalNoAnnexure:'';
    $order_date=isset($basicrp[0]->order_date)?$basicrp[0]->order_date:'';
    if($order_date!=''){
        $orderd=date('d-m-Y',strtotime($order_date));
    }
    $filingno=isset($basicrp[0]->filing_no)?$basicrp[0]->filing_no:'';
    $tab_no=isset($basicrp[0]->tab_no)?$basicrp[0]->tab_no:'';
    $type=isset($basicrp[0]->type)?$basicrp[0]->type:'';
    $partyidval=isset($basicrp[0]->party_ids)?$basicrp[0]->party_ids:'';
    $pid = rtrim($partyidval, ',');
    $partyid=isset($basicrp[0]->party_ids)?$basicrp[0]->party_ids:'';
    $partyType=isset($basicrp[0]->partyType)?$basicrp[0]->partyType:'';
    if($partyid!=''){
        $partyid=explode(',', $partyid);
    }
}

if($partyType=='1'){
    $partytype='1';
}
if($partyType=='2'){
    $partytype='2';
}
if($partyType=='3'){
    $partytype='3';
}

$flag='';
$flag1='';
if($partytype!='3'){
    if($partyid[0]==""){
        echo "please Select party type";die;
    }
    if ($partyid[0] != "" ) {
        if ($partytype == 2) {
            $nameparty = 'Applicant';
            $flag = 1;
        }if ($partytype == 1) {
            $nameparty = 'Respondent';
            $flag = 2;
        }
     }
}


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<?= form_open(false,['class'=>'wizard-form steps-basic wizard clearfix','name'=>'rpepcpparty','id'=>'rpepcpparty','autocomplete'=>'off']) ?>
<?= form_fieldset('Set Priority For Party').'<i class="icon-plus-circle2 text-danger" style="position: absolute;padding: 9px 6px;"></i>'; ?>
            <input type="hidden" name="token" value="<?php echo $token; ?>" id="token">
            <input type="hidden" name="saltNo" id="saltNo" value="<?php echo $salt; ?>">
            <input type="hidden" name="filingno" id="filingno" value="<?php echo $filingno; ?>">
            <input type="hidden" name="type" id="type" value="<?php echo $type; ?>">
            <input type="hidden" name="tabno" id="tabno" value="<?php echo '3'; ?>">
            
            
            
            
            
            
	 		        <div class="col-md-12" >
                         <div class="table-responsive" id="va1">
                                <table id="examples"   class="table table-striped table-bordered"  width="100%">
                                        <thead>
                                            <tr style="background-color: #de8181;color: #fafafa;">
                                                <th width="800px">Applicant Name</th>
                                                <th width="100px">#</th>
                                                <th width="100px">Priority No</th>
                                            </tr>
                                        </thead>
                                    <tbody>
                                <?php
                                 $apid=explode(',', $app_party_id);
                                 $apri=explode(',', $app_priority);
                                 $valxxa=array_combine($apid, $apri);
                                 
                                 $srn='1';
                                  $len = sizeof($partyid);
                                  $len = $len - 1;
                                  for ($k = 0; $k < $len; $k++) {
                                    if ($partyid[$k] == 1) {
                                        $appsql =$this->efiling_model->data_list_where('sat_case_detail','filing_no',$filingno);
                                        $checked='';
                                        $sr='';
                                        foreach($appsql as $row){
                                            if($partytype == 2) {
                                                $mainparty = $row->res_name;
                                                $partflagres = 999;
                                                if($apid[$k]==1){
                                                    $checked='checked';
                                                    $sr=$apri[$k];
                                                }
                                            }
                                            if ($partytype == 1) {
                                                $partflagpet = 999;
                                                $mainparty = $row->pet_name;
                                                if($apid[$k]==1){
                                                    $checked='checked';
                                                    $sr=$apri[$k];
                                                }
                                            }
                                            if ($partytype == 3) {
                                                $partflagpet = 999;
                                                $mainparty = $row->pet_name;
                                            }
                                        }
                                        ?>
                                        <tr>
                                            <td><?php echo $mainparty; ?></td>
                                            <td><input type="checkbox" name="patyAddIdmain"  value="<?php echo '1'; ?>" <?php echo $checked; ?>></td>
                                            <td><input type="text" size='3' value="<?php echo $sr; ?>"  onkeypress="return /[0-9]/i.test(event.key)" name="numbermian" onkeyup="valcheck();"></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                
                                $sqladd1 = "select * from additional_party where filing_no='$filingno' and  party_id IN($pid)";
                                $query=$this->db->query($sqladd1);
                                $data = $query->result();
                                foreach($data as $rval){
                                    $srv='';
                                    $id = $rval->party_id;
                                    $pet_name11 = $rval->pet_name;
                                    $checked='';
                                    if(in_array($id, $apid)){
                                        $checked='checked';
                                        $srv=$valxxa[$id];
                                    }
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($pet_name11); ?></td>
                                         <td><input type="checkbox" name="patyAddIdmain" value="<?php echo $id; ?>" <?php echo $checked; ?>></td>
                                        <td><input type="text" size='3' value="<?php echo $srv; ?>" onkeypress="return /[0-9]/i.test(event.key)" name="numbermian" onkeyup="valcheck();"></td>
                                    </tr>
                                    <?php  } ?>
                                    </tbody>
                                </table>
                            </div>
                            
        
        
        
        
        
        
        
        
                            <div class="table-responsive" id="va2">
                                <table class="table table-bordered" width="100%">
                                    <thead>
                                        <tr style="background-color: #de8181;color: #fafafa;">
                                            <th width="800px">Respondent Name</th>
                                            <th width="100px">#</th>
                                            <th width="100px">Priority No</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $rpid=explode(',', $res_partyid);
                                    $rpri=explode(',', $res_priority);
                                    $valxx=array_combine($rpid, $rpri);
                                    
                                    if ($partflagpet != 999) {
                                        $sqlr = $this->efiling_model->data_list_where('sat_case_detail','filing_no',$filingno);
                                        foreach($sqlr as $rval){
                                            $checked='';
                                            $srnv='';
                                            if ($partytype != 2) {
                                                $mainparty1 = $rval->res_name;
                                                if(in_array('1', $rpid)){
                                                    $checked='checked';
                                                }
                                                $srnv=isset($valxx['1P'])?$valxx['1P']:'';
                                            }
                                            if ($partytype != 1) {
                                                $mainparty1 =$rval->pet_name;
                                                if(in_array('1', $rpid)){
                                                    $checked='checked';
                                                }
                                                $srnv=isset($valxx['1P'])?$valxx['1P']:'';
                                            }
                                        }
                                        ?>
                                        <tr> 
                                            <td><?php echo htmlspecialchars($mainparty1); ?></td>
                                            <td><input type="checkbox" name="patyAddId1" value="<?php echo '1P'; ?>" <?php echo $checked; ?>></td>
                                            <td><input type="text" size='3' value="<?php echo $srnv; ?>" name="number1" onkeypress="return /[0-9]/i.test(event.key)" onkeyup="valcheck1();"></td>
                                        </tr>
                                        <?php
                                    }
                                    
                                    
                                    $sqladd1 = "select * from additional_party where filing_no='$filingno' and  party_flag ='$flag'";
                                    $query=$this->db->query($sqladd1);
                                    $data = $query->result();
                                    foreach($data as $row){
                                        $checked='';
                                        $srnv='';
                                        $id = $row->party_id;
                                        $pet_name11 = $row->pet_name;
                                        if(in_array($id, $rpid)){
                                            $checked='checked';
                                            $srnv=$valxx[$id];
                                        }
                                        ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($pet_name11); ?></td>
                                              <td><input type="checkbox" name="patyAddId1" value="<?php echo $id; ?>" <?php echo $checked; ?>></td>
                                            <td><input type="text" size='3' value="<?php echo $srnv; ?>" onkeypress="return /[0-9]/i.test(event.key)" name="number1" onkeyup="valcheck1();"></td>
                                        </tr>
                                        <?php
                                    }
                                    
                                    if ($partflagres != 999) {
                                        $sql = $this->efiling_model->data_list_where('sat_case_detail','filing_no',$filingno);    
                                        foreach($sql as $rowr){
                                            $checked='';
                                            $srnv='';
                                            $mainpartyres1 = $rowr->res_name;
                                            if(in_array('1R',$rpid)){
                                                $checked='checked';
                                                $srnv=$valxx['1R'];
                                            }
                                        }
                                        ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($mainpartyres1); ?></td>
                                            <td><input type="checkbox" name="patyAddId1" value="<?php echo '1R'; ?>" <?php echo $checked; ?>></td>
                                            <td><input type="text" size='3' value="<?php echo $srnv; ?>" onkeypress="return /[0-9]/i.test(event.key)" name="number1" onkeyup="valcheck1();"></td>
                                        </tr>
                                        <?php
                                    }
                                    
                                    
                                    if ($partytype == 1)
                                        $flag1 = 1;
                                        $checked='checked';
                                        $sr='1';
                                     if ($partytype == 2)
                                        $flag1 = 2;
                                        $checked='checked';
                                        $sr='1';
                                    
                                    $sqladd1 = "select * from additional_party where filing_no='$filingno' and party_flag='$flag1' and  party_id NOT IN($pid)";
                                    $query=$this->db->query($sqladd1);
                                    $data = $query->result();
                                    foreach($data as $row){
                                        $checked='';
                                        $id = $row->party_id;
                                        $pet_name11 =$row->pet_name;
                                        if(in_array($id,$rpid)){
                                            $checked='checked';
                                            $srnv=$valxx[$id];
                                        }
                                        ?>          
                                        <tr>
                                            <td><?php echo htmlspecialchars($pet_name11); ?></td>
                                             <td><input type="checkbox" name="patyAddId1" value="<?php echo $id; ?>" <?php echo $checked; ?>></td>
                                            <td><input type="text" size='3' value="<?php echo $srnv; ?>" onkeypress="return /[0-9]/i.test(event.key)" name="number1" onkeyup="valcheck1();"></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>



			<br></br>
            <div class="row">
                <div class="offset-md-9 col-md-4" >
                    <input  type="button" value="Save and Next" class="btn btn-success" onclick="rpepcpSubmitPartyPrioirity();">&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="reset" value="Reset/Clear" class="btn btn-danger">
                </div>
           </div>
		<?= form_fieldset_close(); ?>
  </div>
<?= form_close();?>
<?php $this->load->view("admin/footer"); ?>
<script>
function load_app_respodent(){
	with(document.rpepcpparty){
	action = base_url+"petitionparty";
	submit();
    	document.frm.submit11.disabled = true;  
     	document.frm.submit11.value = 'Please Wait...';  
     	return true;
	}
}


       
        
function rpepcpSubmitPartyPrioirity(){
    var token = document.getElementById('token').value; 
    var salt = document.getElementById('saltNo').value;
    var filingno = document.getElementById('filingno').value;
	var type = document.getElementById('type').value;
	var tabno = document.getElementById('tabno').value;
	
	var checkboxes1 = document.getElementsByName('patyAddIdmain');
    var parit = document.getElementsByName("numbermian");
    var patyAddId = "";
    var count1 = 0;
    var p = "";
    for (var i = 0; i < checkboxes1.length; i++) {
        if (checkboxes1[i].checked) {
            patyAddId = patyAddId + checkboxes1[i].value + ',';
        }
        if (parit[count1].value != "") {
            p = p + parit[count1].value + ',';
        }
        count1++;
    }
    
    var checkboxesres = document.getElementsByName('patyAddId1');
    var parit1 = document.getElementsByName("number1");
    var patyAddIdres = "";
    var count11 = 0;
    var pp = "";
    for (var i = 0; i < checkboxesres.length; i++) {
        if (checkboxesres[i].checked) {
            patyAddIdres = patyAddIdres + checkboxesres[i].value + ',';
        }
        if (parit1[count11].value != "") {
            pp = pp + parit1[count11].value + ',';
        }
        count11++;
    }
    var dataa={};
	dataa['salt']=salt,
	dataa['filingno']=filingno,
	dataa['token']=token,
	dataa['type']=type,
	dataa['tabno']=tabno,
    dataa['appparty']=patyAddId,
    dataa['apppriority']=p,
    dataa['resparty']=patyAddIdres,
    dataa['respriority']=pp,
    $.ajax({
         type: "POST",
         url: base_url+"/petitionpartyPrioritySubmit",
         data: dataa,
         cache: false,
         success: function (resp) {
            var resp = JSON.parse(resp);
        	if(resp.data=='success') {
		       setTimeout(function(){
                    window.location.href = base_url+'petitionadv';
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

function valcheck(){
	  var values = [];
	  $('#va1 input:text').each(
	    function() {
	      if (values.indexOf(this.value) >= 0) {
	        $(this).css("border-color", "red");
	        $(this).val('');
	      } else {
	        $(this).css("border-color", ""); //clears since last check
	        values.push(this.value);
	      }
	    }
	  );

	}


	function valcheck1(){
	  var values = [];
	  $('#va2 input:text').each(
	    function() {
	      if (values.indexOf(this.value) >= 0) {
	        $(this).css("border-color", "red");
	        $(this).val('');
	      } else {
	        $(this).css("border-color", ""); //clears since last check
	        values.push(this.value);
	      }
	    }
	  );
	}
</script>   