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
$tab_no='';
$type='';
$iano='';
$anx='';
$iapartys='';
$filing_no='';
if($salt!=''){
    $basicia= $this->efiling_model->data_list_where('temp_docdetail','salt',$salt);
    $type=isset($basicia[0]->type)?$basicia[0]->type:'';
    $filing_no=isset($basicia[0]->filing_no)?$basicia[0]->filing_no:'';
    $tab_no=isset($basicia[0]->tab_no)?$basicia[0]->tab_no:'';
    $iapartys=isset($basicia[0]->partys)?$basicia[0]->partys:'';
}
$partys=explode(',', $iapartys);
$partytype=isset($basicia[0]->partyType)?$basicia[0]->partyType:'1';
$selected_radio1=isset($_REQUEST['partyType'])?$_REQUEST['partyType']:$partytype;


$filing_no = $filing_no;
$partyType = $partytype;
if ($partyType == '2') {
    $flags = 'R';
} else if ($partyType == '1') {
    $flags = 'P';
}
$disabled='';
error_reporting(0);
?>
<?= form_open(false,['class'=>'wizard-form steps-basic wizard clearfix','name'=>'rpepcpbascidetail','id'=>'rpepcpbascidetail','autocomplete'=>'off']) ?>
       <?= form_fieldset('Party Detail ').'<i class="icon-plus-circle2 text-danger" style="position: absolute;padding: 9px 6px;"></i>'; ?>
		<input type="hidden" name="token" value="<?php echo $token; ?>" id="token">
	    <input type="hidden" name="filing_no" id="filing_no" value="<?php echo $filing_no; ?>">
	    <input type="hidden" name="saltNo" id="saltNo" value="<?php echo $salt; ?>">
	    <input type="hidden" name="tabno" id="tabno" value="2">
	     <input type="hidden" name="type" id="type" value="doc">
        <div class="col-md-12" >
           <div class="row alert alert-warning" style="padding: 8px 0px 0px; font-weight: 600;font-size: 18px; margin: -20px auto 12px; max-width: 100%;">
            <div class="col-md-4">
                <label class="text-danger">Select Type</label>
            </div>
           <?php  $partytype=isset($_REQUEST['partyType'])?$_REQUEST['partyType']:$partytype;?>
            <div class="col-md-6 md-offset-2">
                <label for="org" class="form-check-label font-weight-semibold">
                    <?= form_radio(['name'=>'partyType','onclick'=>'showparty_ia_details(1)','value'=>'1','checked'=>('1' == $partytype) ? TRUE : FALSE,'id'=>'pet']); ?>Appellant&nbsp;&nbsp;
                </label>
                <label for="indv" class="form-check-label font-weight-semibold">
                    <?= form_radio(['name'=>'partyType','onclick'=>'showparty_ia_details(2)','value'=>'2','checked'=>('2' == $partytype) ? TRUE : FALSE,'id'=>'res']); ?>Respondent&nbsp;&nbsp;
                </label>
              <!--  <label for="inp" class="form-check-label font-weight-semibold">
                    <?= form_radio(['name'=>'partyType','onclick'=>'showparty_ia_details(3)','value'=>'3','checked'=>('3' == $partytype) ? TRUE : FALSE,'id'=>'tParty']); ?> Third Party
                </label> --> 
            </div>
        </div>       
        <div class="row">
        <table class="table" style="border: 1px;solid;">
        	<tr style="background-color:#e1cece">
        		<th>Sr.No</th>
        		<th>Party Name</th>
        		<th>Action</th>
        	</tr>
        	<tr>
            <?php 
          if($selected_radio1!=3){
            $st = $this->efiling_model->data_list_where('sat_case_detail','filing_no',$filing_no);
	        foreach ($st as $row) {
	            $filing_no = htmlspecialchars($row->filing_no);
	            $petName = $row->pet_name;
	            $resName = $row->res_name;
	        }
	        $checked='';
	        if ($selected_radio1 == 1) {
	            $party_tags = 'A';
	                if(in_array('1', $partys)){
	                    $checked="checked";
	                }
	            $option_value = '
                    <tr> 
                        <td>1</td>
                        <td><label for="additionla_partyy">'.$petName. '(A1) </label></td>
                        <td><input type="checkbox" id="additionla_partyy1" onclick="avc(this);" name="additionla_partyy" value="1" '.$checked.'></td>
                    </tr>';
	        } else {
	            $party_tags = 'R';
	            if(in_array('1', $partys)){
	                $checked="checked";
	            }
	            $option_value = '
                     <tr><td>1</td>
                    <td><label for="additionla_partyy">'.$resName . '(R1) </label></td>
                    <td><input type="checkbox" id="additionla_partyy1" onclick="avc(this);" name="additionla_partyy" value="1" '.$checked.'> </td>
                    </tr>';
	        }
	        $where =array('filing_no'=>$filing_no,'party_flag'=>$selected_radio1);    
	        $addParty =  $this->efiling_model->select_inparty('additional_party',$where);
            $ii = 2;
            foreach ($addParty as $row) {
                $party_id=$row->party_id;
                $checked='';
                if(in_array($party_id, $partys)){
                    $checked="checked";
                }
                $option_value .= '
                    <tr>
                        <td>'.$ii.'</td>
                        <td><label for="vehicle1">'.$row->pet_name . '(' . $party_tags . $row->partysrno . ')'.'</label></td>
                        <td><input type="checkbox" id="additionla_partyy'.$row->party_id.'" onclick="avc(this);"  name="additionla_partyy" value='.$row->party_id.' '.$checked.'> </td>
                    </tr>
               ';
                $ii++;
            }
	        echo $option_value;
	        ?>
	       
    	</table>

       
        <?php  } if($selected_radio1==3){?>
         <div  class="col-md-12" id="annId_review">
        	<fieldset  style="padding-right: 0px;"><legend class="customlavelsub">Third Party Details</legend>
            <div class="row alert alert-warning" style="padding: 8px 0px 0px; font-weight: 600;font-size: 18px; margin: -20px auto 12px; max-width: 100%;">
                <div class="col-md-4">
                    <label class="text-danger">Select Mode</label>
                </div>
                <div class="col-md-6 md-offset-2">
                    <label for="org" class="form-check-label font-weight-semibold">
                        <?= form_radio(['name'=>'org','id'=>"bd1" ,'value'=>'1','onclick'=>'orgshow();','checked'=>'1']); ?>
                        Organization&nbsp;&nbsp;
                    </label>
                    <label for="indv" class="form-check-label font-weight-semibold">
                        <?= form_radio(['name'=>'org','id'=>'po1' ,'value'=>'2' ,'onclick'=>'orgshow();','checked'=>'']); ?>
                        Individual&nbsp;&nbsp;
                    </label>
                    <label for="inp" class="form-check-label font-weight-semibold">
                    <?php //echo  $salt=htmlspecialchars($_REQUEST['salt']); ?>
                    </label>
                </div>
            </div>
        	<div class="row">
        		<div class="col-md-4"  id="org" >
        			<div class="form-group required">
        				<label><span class="custom"><font color="red">*</font></span>Select Organization :</label>
        				<select name="select_org_app1" class="form-control" id="select_org_app1" onchange="apple_org_details_ia1(this.value)">
        						<option value="">Select Org Name</option>
                                <?php $hscquerytttt ='';
                                foreach ($hscquerytttt as $row) { ?>
                                <option value="<?php echo htmlspecialchars($row->org_id); ?>"><?php echo htmlspecialchars($row->orgdisp_name); ?></option>
                                <?php }  ?>
                        </select>
                    </div>
        		</div>
        		<div class="col-md-4" id="ind" style="display:none">
        		   <div class="form-group required">
                		<label><span class="custom"><font color="red">*</font></span>Select Organization:</label>
                       <input type="text" name="select_org_app1" id="select_org_app1" class="form-control" />
                    </div>
        		</div>
        		
        		<div class="col-md-4">
            		<div class="form-group required">
                		<label><span class="custom"><font color="red">*</font></span>Party Name:</label>
                       <input type="text" name="petName1" id="petName1" class="form-control" />
                    </div>
        		</div>
        	    <div class="col-md-4">
            		<div class="form-group required">
                		<label><span class="custom"><font color="red">*</font></span>Designation: </label>
                       <input type="text" name="degingnation1" value="" id="degingnation1" class="form-control" />
                    </div>
        		</div>
        	</div>       		
           <div class="row">  	
        	    <div class="col-md-4">
            		<div class="form-group required">
                		<label><span class="custom"><font color="red">*</font></span> Address Of Appeliant: </label>
                       <textarea name="petAddress1" id="petAddress1" class="form-control" cols="25"></textarea>
                    </div>
        		</div>	
        		 <div class="col-md-4">
            		<div class="form-group required">
                	   <label><span class="custom"><font color="red">*</font></span>State Name : </label>
                       <select name="dstate1" class="form-control" id="dstate1" onchange="showCity(this);">
                    		<option selected="selected">Select State Name</option>
                    		<?php	$hscquery = $this->efiling_model->data_list('master_psstatus');
                    	       foreach ($hscquery as $row ){ ?>
                    			<option value="<?php echo htmlspecialchars($row->state_code);?>"><?php echo htmlspecialchars($row->state_name);?></option>
                     		<?php } ?>
                	   </select>
                    </div>
        		</div>
        		  <div class="col-md-4">
            		<div class="form-group required">
                		<label><span class="custom"><font color="red">*</font></span> District: </label>
                        <select name="ddistrict1" class="form-control" id="ddistrict1">
                        	<option selected="selected">Select District Name</option>
                    	</select>
                    </div>
        		</div>
           </div>
            	       
           <div class="row">  	
        	    <div class="col-md-4">
            		<div class="form-group required">
                		<label><span class="custom"><font color="red">*</font></span> Pincode: </label>
                       <input type="text" name="pincode1" value=" " id="pincode1" class="form-control" onkeypress="return isNumberKey(event)"maxlength="6" />
                    </div>
        		</div>
        		<div class="col-md-4">
            		<div class="form-group required">
                		<label><span class="custom"><font color="red">*</font></span> Mobile Number: </label>
                       <input type="text" name="petmobile1" id="petmobile1" class="form-control"  onkeypress="return isNumberKey(event)"maxlength="10" value=""/>
                    </div>
        		</div>
        		<div class="col-md-4">
            		<div class="form-group required">
                		<label><span class="custom"><font color="red">*</font></span>Phone Number: </label>
                       <input type="text" name="petPhone1" id="petPhone1" class="form-control" maxlength="11"  value="" onkeypress="return isNumberKey(event)"/>
                    </div>
        		</div>
           </div>		   
           <div class="row">  	
        	    <div class="col-md-4">
            		<div class="form-group required">
                		<label><span class="custom"><font color="red">*</font></span>Email ID: </label>
                       <input type="text" name="petEmail1" id="petEmail1" class="form-control" value="" />
                    </div>
        		</div>
        		
        		 <div class="col-md-4">
            		<div class="form-group required">
                		<label><span class="custom"><font color="red">*</font></span>Fax No: </label>
                       <input type="text" name="petFax1" id="petFax1" class="form-control" value="" maxlength="11"  onkeypress="return isNumberKey(event)"/>
                    </div>
        		</div>
           </div>
     
        
        <div class="col-md-12" id="condetail">
	      <legend class="customlavelsub">Council Details</legend>
	            <div class="row">  	
        		    <div class="col-md-4">
                		<div class="form-group required">
                    		<label><span class="custom"><font color="red">*</font></span>Counsel Name: </label>
                           <select name="councilCode" class="form-control" id="councilCode" onchange="showUserOrg(this.value)">
                                <option value="">Select Council Name</option>
                                <?php
                                $adv = $this->efiling_model->data_list('master_advocate'); 
                                foreach($adv as $row) {
                                    $selected = '';
                                    if ($pet_adv_id == $row->adv_code) {
                                        $selected = 'selected';
                                    }?>
                                    <option <?php echo $selected; ?>value="<?php echo htmlspecialchars($row->adv_code); ?>"><?php echo htmlspecialchars($row->adv_name . '(' . $row->adv_reg . ')'); ?></option>
                                    <?php }  ?>
                            </select>
                        </div>
        			</div>
        			<div class="col-md-4">
                		<div class="form-group required">
                    		<label><span class="custom"><font color="red">*</font></span>Address Of Counsel: </label>
                            <textarea name="counselAdd" readonly id="counselAdd" class="form-control"   cols="25"><?php echo htmlspecialchars(isset($pet_cou_addm)?$pet_cou_addm:''); ?></textarea>
                        </div>
        			</div>
        			<div class="col-md-4">
                		<div class="form-group required">
                    		<label><span class="custom"><font color="red">*</font></span>State Name : </label>
                            <input type="text" name="cdstate" class="form-control" readonly id="cdstate"  maxlength="50" value=""/>
                        </div>
        			</div>
	            </div>
	            <div class="row"> 
	               <div class="col-md-4">
                		<div class="form-group required">
							<input type="hidden" name="cddistrict" id="cddistrict" value=""> 
                    		<label><span class="custom"><font color="red">*</font></span>District : </label>
                            <input type="text" name="ddistrictname"  id="ddistrictname" class="form-control" maxlength="50" value="" readonly>
                        </div>
        			</div>
        			 <div class="col-md-4">
                		<div class="form-group required">
                    		<label><span class="custom"><font color="red">*</font></span>Pincode : </label>
                           <input type="text" name="counselPin" readonly  value=""  id="counselPin" class="form-control"  onkeypress="return isNumberKey(event)" maxlength="6"/>
                        </div>
        			</div>	
        			  <div class="col-md-4">
                		<div class="form-group required">
                    		<label><span class="custom"><font color="red">*</font></span>Fax No: </label>
                            <input type="text" name="counselFax" readonly id="counselFax"  class="form-control" value="" maxlength="11"  onkeypress="return isNumberKey(event)"/>
                        </div>
        			</div>
				</div>
				<div class="row"> 
	               <div class="col-md-4">
                		<div class="form-group required">
                    		<label><span class="custom"><font color="red">*</font></span>Mobile Number : </label>
                            <input type="text" readonly name="counselMobile"   id="counselMobile" class="form-control" maxlength="10"   value="" onkeypress="return isNumberKey(event)"/>
                        </div>
        			</div>
        			 <div class="col-md-4">
                		<div class="form-group required">
                    		<label><span class="custom"><font color="red">*</font></span>Phone Number : </label>
                            <input type="text" readonly name="counselPhone" id="counselPhone" class="form-control" maxlength="11" value=""  onkeypress="return isNumberKey(event)"/>
                        </div>
        			</div> 	 
        			 <div class="col-md-4">
                		<div class="form-group required">
                    		<label><span class="custom"><font color="red">*</font></span>Email ID : </label>
                            <input type="text" readonly name="counselEmail" id="counselEmail" class="form-control" value=""/>
                        </div>
        			</div>	
				</div>
				<div style="float:right">
           			<input type="button" name="nextsubmit" id="nextsubmit" value="Add More Counsel" class="btn1" onclick="addMoreCouncel();">
      			</div>
     		</div>
		<?php  } ?>
		</div>
		 <?php $doc=$this->efiling_model->data_list_where('master_document','display', '1');   ?>
		 	<div class="row" style="margin-top:50px">
             <div class="col-md-4">
            		<div class="form-group required" id="vaids">
                		<label><span class="custom"><font color="red">*</font></span>Docuemnt Type : </label>
                           <select id="doc_dtype" class="form-control" name="doc_dtype" onClick="openmatter(this.value);">
                            <option value="">Select Document Type</option>
                            <?php foreach($doc as $row){ ?>
                            <option value="<?php echo $row->pay . " " . $row->did; ?>" ><?php echo $row->d_name; ?> </option>
                            <?php   } ?>
                        
                          </select>
                    </div>
                    
                    <div class="form-group required" id="notvaids" style="display:none">
                		<label><span class="custom"><font color="red">*</font></span>Docuemnt Type : </label>
                           <select id="doc_dtype11" class="form-control" name="doc_dtype11" onClick="openmatter111(this.value);">
                            <option value="">Select Document Type</option>
                            <?php foreach($doc as $row){
                                if($row->did==23 || $row->did==32){?>
                            <option value="<?php echo $row->pay . " " . $row->did; ?>" ><?php echo $row->d_name; ?> </option>
                            <?php  } 
                            } ?>
                        
                          </select>
                    </div>
                    
			 </div>
			    <div class="col-md-4" id="anexture" style="display: none;">
                    <div class="col-auto">
                        <label class="visually-hidden" for="inputPassword"><font color="red">*</font>Total No. of Annexure</label>
                        <input type="text" class="form-control" id="toalannexure" name="toalannexure"  value="<?php echo $anx; ?>" placeholder="Annexure" onkeypress="return isNumberKey(event);" maxlength="3">
                    </div>
        		</div>
        		
        		 <div class="col-md-4" id="matterval" style="display: none;">
                    <div class="col-auto">
                        <label class="visually-hidden" for="inputPassword"><font color="red">*</font>Matter</label>
                        <textarea rows="4" cols="25" name="mattertext" id="mattertext" class="form-control"></textarea>
                    </div>
        		</div>
        		<input type="hidden" name="novavalcount" id="novavalcount" value="">
			</div>
		    <br><br><br>
			<div class="row">
                <div class="offset-md-8 col-md-4">
                    <input type="button" value="Save and Next" class="btn btn-success" id="ia_save" onclick="docpartysave();" <?php echo $disabled; ?>>
					<input type="reset" value="Reset/Clear" class="btn btn-danger">
                </div>
            </div>
            </div>
<?= form_fieldset_close(); ?>
<?= form_close();?>
 <?php $this->load->view("admin/footer"); ?>

<script>
function openmatter(val){
	var paper = document.getElementById("doc_dtype").value;
    var ar = paper.split(" ");
    if (ar[0] == 'ma') {
    	$("#anexture").show();
    	$("#matterval").show();
    }else  if (ar[0] == 'va') {
    	$("#anexture").hide();
    	$("#matterval").show();
    }
    else  if (ar[0] == 'oth') {
    	$("#anexture").hide();
    	$("#matterval").show();
    }
    else{
	   $("#anexture").hide();
	}
}


function openmatter111(val){
	var paper = document.getElementById("doc_dtype11").value;
    var ar = paper.split(" ");
    if (ar[0] == 'ma') {
    	$("#anexture").show();
    	$("#matterval").show();
    }else  if (ar[0] == 'va') {
    	$("#anexture").hide();
    	$("#matterval").show();
    }
    else  if (ar[0] == 'oth') {
    	$("#anexture").hide();
    	$("#matterval").show();
    }
    else{
	   $("#anexture").hide();
	}
}





function avc(str_str){
   var checkboxes1 = document.getElementsByName('additionla_partyy');
    var patyAddId = "";
    var patyAddIdc="";
    for (var i = 0; i < checkboxes1.length; i++) {
        if (checkboxes1[i].checked) {
            patyAddId = checkboxes1[i].value;
        }
    }
    var filing_no = $("#filing_no").val();
    var partyType = $('input[name=partyType]:checked').val();
    var data = {};
    data['action'] = "chekva";
    data['party_id'] = patyAddId;
    data['filing_no'] = filing_no;
    data['partyType'] = partyType;
    $.ajax({
        type: "POST",
        url: base_url+'chekva',
        data: data,
        dataType: "html",
        success: function (resp) {
        	var resp = JSON.parse(resp);
        	if(resp.data=='success') {
        		$('#notvaids').hide();
	    		$('#vaids').show();
	    		document.getElementById("novavalcount").value='0';
        		$('#ia_save').prop('disabled',false);
			}else if(resp.error== '1') {
		    	alert("Your vakalatnam not on record.");
		    	if(resp.error== '1'){
		    		$('#notvaids').show();
		    		$('#notvaids').show();
		    		$('#vaids').hide();
		    		document.getElementById("novavalcount").value='1';
			    }
				//$('#additionla_partyy'+patyAddId).prop('checked' , false);
				//$('#ia_save').prop('disabled',true);
			}
        },
        error: function(){
			$.alert("Surver busy,try later.");
		},
		complete: function(){
			$('#petitioner_save').prop('disabled',false).val("Submit");
		}
    }); 
}

function docpartysave(){
	var salt       = $("#saltNo").val();
    var tabno      = document.getElementById("tabno").value;
    var filing_no  = document.getElementById("filing_no").value;
    var type = document.getElementById("type").value;
    var partyType = $('input[name=partyType]:checked').val();
    var checkboxes1 = document.getElementsByName('additionla_partyy');
    var novavalcount = document.getElementById("novavalcount").value;
	var paper = document.getElementById("doc_dtype").value;
	if(novavalcount==1){
		var paper = document.getElementById("doc_dtype11").value;
    }
	var mattertext = document.getElementById("mattertext").value;
    var ar = paper.split(" ");
    var doctype=ar[0];
    var doc_dtype = ar[1];
    var patyAddId = "";
    var count1 = 0;
    var selected = [];
    for (var i = 0; i < checkboxes1.length; i++) {
        if (checkboxes1[i].checked) {
            patyAddId = patyAddId + checkboxes1[i].value + ',';
            count1++;
        }
    }
    if(patyAddId==''){
    	alert("Please check party !");
    	return fasle;
    }

    if(doctype!='va' && doctype!='oth'){  
        var toalannexure= document.getElementById("toalannexure").value;
        if(toalannexure==''){
           alert("Please fill total number of annexure !");
           return false;
        } 
    }
    
    var token=Math.random().toString(36).slice(2); 
    var token_hash=HASH(token+'docparty');
 
    var dataa = {};
	dataa['salt'] = salt,
	dataa['tab_no']=tabno,
	dataa['token']=token,
	dataa['filing_no']=filing_no,
	dataa['type']=type,
	dataa['partyids']=patyAddId,
	dataa['partyType']=partyType,
	dataa['docidval']=doc_dtype,
	dataa['doctype']=doctype,
	dataa['toalannexure']=toalannexure,
	dataa['matter']=mattertext,
	$.ajax({
        type: "POST",
        url: base_url+'docpartysave/'+token_hash,
        data: dataa,
        cache: false,
		beforeSend: function(){
			$('#petitioner_save').prop('disabled',true).val("Under proccess....");
		},
        success: function (resp) {
        	var resp = JSON.parse(resp);
        	if(resp.data=='success') {
		       setTimeout(function(){
                    window.location.href = base_url+'doc_upload_doc';
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
			$('#petitioner_save').prop('disabled',false).val("Submit");
		}
	 }); 
}


$(function(){
    $(".datepicker").datepicker({maxDate: new Date()});
});


function showparty_ia_details(){
	with(document.rpepcpbascidetail){
	action = base_url+"doc_partydetail";
	submit();
    	document.frm.submit11.disabled = true;  
     	document.frm.submit11.value = 'Please Wait...';  
     	return true;
	}
}
 

function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}




    
    
</script>   