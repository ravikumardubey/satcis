<?php
error_reporting(0);
$this->load->view("admin/header");
$this->load->view("admin/sidebar");
$this->load->view("admin/stepsdoc");
$salt=$this->session->userdata('docsalt');
if($salt!=''){
    $salt=$this->session->unset_userdata('docsalt');
}
$userdata=$this->session->userdata('login_success');
$userid=$userdata[0]->id;
$checkcpass=$userdata[0]->is_password;
$token= $this->efiling_model->getToken();
$anx='';
error_reporting(0);
?>
<?= form_open(false,['class'=>'wizard-form steps-basic wizard clearfix','name'=>'rpepcpbascidetail','id'=>'rpepcpbascidetail','autocomplete'=>'off']) ?>
       <?= form_fieldset('Search').'<i class="icon-plus-circle2 text-danger" style="position: absolute;padding: 9px 6px;"></i>'; ?>
		<input type="hidden" name="token" value="<?php echo $token; ?>" id="token">
	    <input type="hidden" name="saltNo" id="saltNo" value="<?php echo $salt; ?>">
	    <input type="hidden" name="tabno" id="tabno" value="1">
	     <input type="hidden" name="iatype" id="iatype" value="doc">
        <div class="col-md-12" >
            <div class="row">
            <?php 
            $checked1='';
            $checked2='';
            $appAnddef=isset($_REQUEST['appAnddef'])?$_REQUEST['appAnddef']:'';
            $checked1="checked";
            $classcase="";
            $classdfr="";
            $classcase="none";
            if($appAnddef=='1'){
                $checked1="checked";
                $classcase="none";
                $classdfr="";
            }
            if($appAnddef=='2'){
                $checked2="checked";
                $classcase="";
                $classdfr='none';
            }
    		?>
			  	<div class="col-md-3">
                     <div class="form-group required">
						<label for="name"><span class="custom"><font color="red">*</font></span>AL No.</label> 
						<input type="radio" name="appAnddef" onclick="diary();" id="app" value="1" <?php echo $checked1; ?>> 
					 </div>
				</div>
			    <div class="col-md-3">
                    <div class="form-group required">
						<label for="name"><span class="custom"><font color="red">*</font></span>Case No </label> 
						<input type="radio" name="appAnddef" onclick="diary();" id="def" value="2" <?php echo $checked2; ?>> 
					</div>
				</div> 
			 </div>     
    		<div class="row" id="myDIV" style="display:<?php echo $classdfr; ?>">		         
    			  <div class="col-md-3">
    			  		<?php 
    			  		$dfr_no=isset($_REQUEST['dfr_no'])?$_REQUEST['dfr_no']:'';
    			  		$dfryear=isset($_REQUEST['dfryear'])?$_REQUEST['dfryear']:'';
    			  		?>
                         <div class="form-group required">
                        	<label for="name"><span class="custom"><font color="red">*</font></span>AL No.</label> 
                        	  <?= form_input(['name'=>'dfr_no','class'=>'form-control','id'=>'dfr_no','value'=>$dfr_no,'onkeypress'=>'return isNumberKey(event)','placeholder'=>'Enter DFR NO.','pattern'=>'[0-9]{1,8}','maxlength'=>'8','title'=>'DFR No. should be numeric only.','required'=>'true']) ?>
                         </div>
                  </div>
                  
                   <div class="col-md-3">
                     <div class="form-card">
                      	  <div class="form-group">
                      		<label class="control-label" for="caseYear"><span class="custom"><font color="red">*</font></span>Case Year:</label>
                          	<div class="input-group mb-3 mb-md-0">
                             <?php
                             $year1=array();
                             $year = $dfryear;
                             $curryear=date('Y');
                             $year1=[''=>'- Select Year -'];
                             for ($i = $curryear; $i > 2000 ; $i--) {
                                 $year1[$i]=$i;
                             }
                              echo form_dropdown('dfryear',$year1,$year,['class'=>'form-control','id'=>'dfryear','required'=>'true']); 
                             ?>
                          	</div>
                          </div>
                     </div>
                  </div>
                   <div class="col-md-3">
                         <div class=" col-md-3" style="margin-top: 29px;">
                			<input type="button" name="nextsubmit" value="SEARCH" class="btn btn-success" id="nextsubmit" onclick="serchDFR();">
                        </div>
    			  </div>
             </div>
             <?php 
             $casetype=isset($_REQUEST['case_type'])?$_REQUEST['case_type']:'';
             $caseno=isset($_REQUEST['case_no'])?$_REQUEST['case_no']:'';
             $caseyear=isset($_REQUEST['year'])?$_REQUEST['year']:'';
             
             ?>
    		 <div class="row" id="myDIV1" style="display: <?php echo $classcase; ?>;">
        		  <div class="col-md-3">
                        <div class="form-card">
                          <div class="form-group">
                          	<label class="control-label" for="case_type_lower"><span class="custom"><font color="red">*</font></span>Case Type:</label>
                          	<div class="input-group mb-3 mb-md-0">
                              <?php
                              $lowercasetype= $this->efiling_model->getCaseTypeia('master_case_type');
                              $lowercasetype1=[''=>'- Select Case Type -'];
                              foreach ($lowercasetype as $val)
                                  $lowercasetype1[$val->case_type_code] = $val->short_name.' ('.$val->case_type_name.')';  
                                  echo form_dropdown('case_type',$lowercasetype1,$casetype,['class'=>'form-control','id'=>'case_type','required'=>'true']); 
                                 ?>
                          	</div>
                          </div>
                     </div>
                  </div>
                      
               	  <div class="col-md-3">
                     <div class="form-group required">
                         <label for="name"><span class="custom"><font color="red">*</font></span>Case NO: </label> 
                           <?= form_input(['name'=>'case_no','class'=>'form-control','id'=>'case_no','value'=>$caseno,'onkeypress'=>'return isNumberKey(event)','placeholder'=>'Enter Case No.','pattern'=>'[0-9]{1,8}','maxlength'=>'8','title'=>'Case should be numeric only.']) ?>
                     </div>
    			  </div>
    			  
    			   <div class="col-md-3">
                     <div class="form-card">
                          <div class="form-group">
                          	<label class="control-label" for="caseYear"><span class="custom"><font color="red">*</font></span>Case Year:</label>
                          	<div class="input-group mb-3 mb-md-0">
                             <?php
                             $year1=array();
                             $year = $dfryear;
                             $curryear=date('Y');
                             $year1=[''=>'- Select Year -'];
                             for ($i = $curryear; $i > 2000 ; $i--) {
                                 $year1[$i]=$i;
                             }
                              echo form_dropdown('year',$year1,$caseyear,['class'=>'form-control','id'=>'year','required'=>'true']); 
                             ?>
                          	</div>
                          </div>
                     </div>
    			  </div>
    			  
    			   <div class="col-md-3">
                     <div class="form-group required" style="margin-top: 29px;">
                        <input type="button" name="nextsubmit" value="SEARCH" class="btn btn-success" id="nextsubmit" onclick="serchDFR();">
                     </div>
    			  </div>
    		</div>		
      <?= form_fieldset_close();   
      if($dfr_no!='' || $caseno!='' ){

      ?>
      <fieldset id="" style="display: block">
        <legend class="customlavelsub">Case Details</legend>
          <div style="margin-top:50px">
    		<?php  
    		    $case_type='';
    		    $vals='';
    		    $data=array();
    		    if($_REQUEST['appAnddef']=='1'){
    		        $fno=$_REQUEST['dfr_no'];
    		        $year=$_REQUEST['dfryear'];
    		        $data=$this->efiling_model->getCaseDetailsDfrdoc($fno,$year);
    		        if(empty($data)){
    		            echo '<center style="color:red">Record not Found !</center>';
    		        }
    		    }
    		    if($_REQUEST['appAnddef']=='2'){
    		        $cno=$_REQUEST['case_no'];
    		        $year=$_REQUEST['year'];
    		        $case_type=$_REQUEST['case_type'];
    		        $data=$this->efiling_model->getCaseDetailsCaseNodoc($cno,$year,$case_type);
    		        if(empty($data)){
    		            echo '<center style="color:red">Record not Found !</center>';
    		        }
    		    }
    		?>
    		</div>
    		<div class="row">
    		 <table class="table" border="1px">
                <thead>
                 <tr style="background-color:#e1cece">
                    <th>S.No.</th>
                    <th>AL No.</th>
                    <th>Case No.</th>
                    <th>Party Detail</th>
                    <th>Case Status</th>
                     <!-- <th>Action</th>-->
                  </tr>
                </thead>
                <tbody>
                  <?php $i=1;
                  if(!empty($data) && is_array($data)){
                  foreach($data as $val){
                    $dfrno=$this->efiling_model->getDFRformate($val->filing_no);
                    $case_no=$this->efiling_model->getCASEformate($val->case_no);
                    $pet= $val->pet_name.$this->efiling_model->fn_addition_party($val->filing_no,'1');
                    $res= $val->res_name.$this->efiling_model->fn_addition_partyr($val->filing_no,'2');
                    ?>
                  <tr>
                    <input type="hidden" name="filing_no" id="filing_no" value="<?php echo $val->filing_no; ?>">
                    <td><?php echo $i; ?></td>
                    <td><?php echo $dfrno; ?></td>
                    <td><?php echo $case_no; ?></td>
                    <td><?php echo $pet; ?> <span style="color:red"><b>&nbsp;Vs</b></span> &nbsp;<?php echo $res; ?></td>
                    <td><?php  if($val->status=='D'){ echo "Disposal"; }else{ echo "Pending"; } ?></td>
                    <!-- <td><input type="radio" id="huey" name="drone" value="huey" checked></td> -->
                  </tr>
                <?php $i++;}
                  }?>  
                </tbody>
              </table>
            </div>
 
    		<div class="row">
                <div class="offset-md-8 col-md-4 text-right" >
                    <input type="button" value="Save and Next" id="docbasicdetail" class="btn btn-success" onclick="docSubmitBasic();">
					<input type="reset" value="Reset/Clear" class="btn btn-danger">
                </div>
            </div>
       </fieldset>
<?php } ?>
<?= form_fieldset_close(); ?>
     </div>
<?= form_close();?>
 <?php $this->load->view("admin/footer"); ?>
 

<script>

function docSubmitBasic(){
	var salt       = $("#saltNo").val();
    var tabno      = document.getElementById("tabno").value;
    var token      = document.getElementById("token").value;
    var filing_no  = document.getElementById("filing_no").value;
    var type      = document.getElementById("iatype").value;
    
    var token=Math.random().toString(36).slice(2); 
    var token_hash=HASH(token+'docbasicdetail');
 
    var dataa = {};
	dataa['tab_no']=tabno,
	dataa['token']=token,
	dataa['filing_no']=filing_no,
	dataa['type']=type,
	$.ajax({
        type: "POST",
        url: base_url+'saveDocbasic/'+token_hash,
        data: dataa,
        cache: false,
		beforeSend: function(){
			$('#docbasicdetail').prop('disabled',true).val("Under proccess....");
		},
        success: function (resp) {
        	var resp = JSON.parse(resp);
        	if(resp.data=='success') {
		       setTimeout(function(){
                    window.location.href = base_url+'doc_partydetail';
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

function  diary(){
   with(document.rpepcpbascidetail){
	action = base_url+"doc_basic_detail";
	submit();
    	document.frm.submit11.disabled = true;  
     	document.frm.submit11.value = 'Please Wait...';  
     	return true;
	}
}

$(function(){
    $(".datepicker").datepicker({maxDate: new Date()});
});

function serchDFR(){
	with(document.rpepcpbascidetail){
	action = base_url+"doc_basic_detail";
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