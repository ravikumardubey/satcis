<?php 
error_reporting(0);
$this->load->view("admin/header"); 
$this->load->view("admin/sidebar"); 
$this->load->view("admin/stepsrpepcp"); 
$salt=$this->session->userdata('rpepcpsalt');
$userdata=$this->session->userdata('login_success');
$userid=$userdata[0]->id;
$checkcpass=$userdata[0]->is_password;
$token= $this->efiling_model->getToken();
if($salt==''){
    $salt='';
}
$orderd='';
$tab_no='';
$type='';
$iano='';
$anx='';
$filing_no='';
if($salt!=''){
    $basicrp= $this->efiling_model->data_list_where('rpepcp_reffrence_table','salt',$salt);
    $iano=isset($basicrp[0]->totalNoia)?$basicrp[0]->totalNoia:'';
    $anx=isset($basicrp[0]->totalNoAnnexure)?$basicrp[0]->totalNoAnnexure:'';
    $type=isset($basicrp[0]->type)?$basicrp[0]->type:'';
    $order_date=isset($basicrp[0]->order_date)?$basicrp[0]->order_date:'';
    if($order_date!=''){
        $orderd=date('d-m-Y',strtotime($order_date));
    }
    $filing_no=isset($basicrp[0]->filing_no)?$basicrp[0]->filing_no:'';
    $tab_no=isset($basicrp[0]->tab_no)?$basicrp[0]->tab_no:'';
}
?>


<?= form_open(false,['class'=>'wizard-form steps-basic wizard clearfix','name'=>'valscc','id'=>'valscc','autocomplete'=>'off']) ?>
       <?= form_fieldset('Search Here ').'<i class="icon-plus-circle2 text-danger" style="position: absolute;padding: 9px 6px;"></i>'; ?>
		<input type="hidden" name="token" value="<?php echo $token; ?>" id="token">
	    <input type="hidden" name="saltNo" id="saltNo" value="<?php echo $salt; ?>">
	    <input type="hidden" name="tabno" id="tabno" value="1">
        <div class="col-md-12" >
            <div class="row">
            <?php 
            $checked1='checked';
            $checked2='';
            
            $classcase='';
            $classdfr='';
            $classcase='none';
            $appAnddef=isset($_REQUEST['appAnddef'])?$_REQUEST['appAnddef']:'';
            if($appAnddef==1){
                $checked1="checked";
                $classcase='none';
                $classdfr='';
            }
            if($appAnddef==2){
                $classcase='';
                $classdfr='none';
                $checked2="checked";
            }
    		?>
			  	<div class="col-md-3">
                     <div class="form-group required">
						<label for="name"><span class="custom"><font color="red">*</font></span>AL No. </label> 
						<input type="radio" name="appAnddef" onclick="serchDFR();" id="app" value="1" <?php echo $checked1; ?>> 
					 </div>
				</div>
			    <div class="col-md-3">
                    <div class="form-group required">
						<label for="name"><span class="custom"><font color="red">*</font></span>Case No </label> 
						<input type="radio" name="appAnddef" onclick="serchDFR();" id="def" value="2" <?php echo $checked2; ?>> 
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
                        	<label for="name"><span class="custom"><font color="red">*</font></span>AL No. </label> 
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
                			<input type="button"  name="nextsubmit" value="SEARCH" class="btn btn-success" id="nextsubmit" onclick="serchDFR();">
                        </div>
    			  </div>
             </div>
             <?php 
             $casetype=isset($_REQUEST['case_type'])?$_REQUEST['case_type']:'';
             $caseno=isset($_REQUEST['case_no'])?$_REQUEST['case_no']:'';
             $caseyear=isset($_REQUEST['year'])?$_REQUEST['year']:'';
             ?>
    		 <div class="row" id="myDIV1" style="display:<?php echo $classcase; ?>">
        		  <div class="col-md-3">
                        <div class="form-card">
                          <div class="form-group">
                          	<label class="control-label" for="case_type_lower"><span class="custom"><font color="red">*</font></span>Case Type:</label>
                          	<div class="input-group mb-3 mb-md-0">
                              <?php
                              $lowercasetype= $this->efiling_model->getCaseTyperpcpep();
                              $lowercasetype1=[''=>'- Select Case Type -'];
                              foreach ($lowercasetype as $val)
                                  $lowercasetype1[$val->case_type_code] = $val->short_name;  
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
                             $curryear=date('Y');
                             if($year==''){ $year='2020';}
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
                        <input type="button"  name="nextsubmit" value="SEARCH" class="btn btn-success" id="nextsubmit" onclick="serchDFR();">
                     </div>
    			  </div>
    		</div>		
      <?= form_fieldset_close();   
      if($dfr_no!='' || $caseno!='' ){

      ?>
      <fieldset id="" style="display: block">
        <legend class="customlavelsub">Case Detail</legend>
        	<div class="row">
        		<div class="col-md-4" >
        		    <label class="visually-hidden" for="inputEmail"><font color="red">*</font><span style="color:red">Please select Petition type</span></label>
                	<select class="form-control" aria-label="Default select example" name="rpepcptype" id="rpepcptype" >
                      <option value="">Please Select</option>
                      <option value="rp" <?php if($type=='rp'){ echo "selected";}?>>RP (Review Petition)</option>
                    </select>
                </div>
        	</div>
          <div style="margin-top:50px">
    		<?php 
    		$flag='';
    		if($appAnddef!=''){
        		if($appAnddef=='1'){
        		    $fno=$_REQUEST['dfr_no'];
        		    $year=$_REQUEST['dfryear'];
        		    $data=$this->efiling_model->getCaseDetailsDfr($fno,$year);
        		    if(empty($data)){
        		        $flag='1';
        		        echo '<center style="color:red">Record not Found !</center>';
        		    }
        		}
        		if($appAnddef=='2'){
        		    $cno=$_REQUEST['case_no'];
        		    $year=$_REQUEST['year'];
        		    $case_type=$_REQUEST['case_type'];
        		    $data=$this->efiling_model->getCaseDetailsCaseNo($cno,$year,$case_type);
        		    if(empty($data)){
        		        $flag='1';
        		        echo '<center style="color:red">Record not Found !</center>';
        		    }
        		}
    		}else{
    		    $flag='1';
    		    echo '<center style="color:red">Please select Type !</center>';
    		}
    		
    		if($flag==''){
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
                  <?php 
                  $res='';
                  $pet='';
                  $i=1;
                  $status='';
                  foreach($data as $val){
                        $status= $val->status;
                        $filing_no=$val->filing_no; 
                        $dfrno=$this->efiling_model->getDFRformate($val->filing_no);
                        $case_no=$this->efiling_model->getCASEformate($val->case_no);
                        $pet= $val->pet_name.$this->efiling_model->fn_addition_party($val->filing_no,'1');
                        $res= $val->res_name.$this->efiling_model->fn_addition_partyr($val->filing_no,'2');
                    }
                    ?>
                  <tr>
                    <input type="hidden" name="filing_no" id="filing_no" value="<?php echo $filing_no; ?>">
                    <td><?php echo $i; ?></td>
                    <td><?php echo $dfrno; ?></td>
                    <td><?php echo $case_no; ?></td>
                    <td><?php echo $pet; ?><span style="color:red"> <b>&nbsp;Vs</b></span> &nbsp;<?php echo $res; ?></td>
                    <td><?php  if($status=='D'){ echo "Disposal"; }else{ echo "Pending"; } ?></td>
                    <!-- <td><input type="radio" id="huey" name="drone" value="huey" checked></td> -->
                  </tr>
                </tbody>
              </table>
            </div>
      		
            <div class="row" style="margin-top:50px">
                <div class="col-auto">
                    <label class="visually-hidden" for="inputEmail"><font color="red">*</font>Date of Order Challenged in this Petition:</label>
                    <input type="text" class="form-control datepicker" name="orderdate" value="<?php echo $orderd; ?>" id="orderdate" placeholder="" readonly>
                </div>
                <div class="col-auto">
                    <label class="visually-hidden" for="inputPassword"><font color="red">*</font>Total No. of Annexure</label>
                    <input type="text" class="form-control" id="toalannexure" name="toalannexure"  value="<?php echo $anx; ?>" placeholder="Annexure" onkeypress="return isNumberKey(event);" maxlength="3">
                </div>
                 <div class="col-auto">
                    <label class="visually-hidden" for="inputPassword"><font color="red">*</font>Total No. of IA</label>
                    <input type="text" class="form-control" id="totalnoIa" name="totalnoIa" value="<?php echo $iano; ?>" placeholder="Total no. IA" onkeypress="return isNumberKey(event);" maxlength="2">
                </div>
    		</div>
    		
    		<div class="row" style="margin-top:50px">
                  <div class="col-auto">
                    <label class="visually-hidden" for="inputPassword"><font color="red">*</font>Subject Matter</label>
                    <textarea id="subject" class="form-control" name="subject" rows="4" cols="50"></textarea>
                </div>
    		</div>
    		
    		
    		<div class="row">
                <div class="offset-md-8 col-md-4">
                    <input type="submit" value="Save and Next" class="btn btn-success" onclick="rpepcpSubmitBasic();">
					<input type="reset" value="Reset/Clear" class="btn btn-danger">
                </div>
            </div>
            <?php } ?>
       </fieldset>
<?php } ?>
<?= form_fieldset_close(); ?>
     </div>
<?= form_close();?>
 <?php $this->load->view("admin/footer"); ?>
 

<script>

function rpepcpSubmitBasic(){
	var salt       = $("#saltNo").val();
    var tabno      = document.getElementById("tabno").value;
    var token      = document.getElementById("token").value;
    var filing_no  = document.getElementById("filing_no").value;
    var rpepcptype = document.getElementById("rpepcptype").value;
    var orderdate   = document.getElementById("orderdate").value;
    var toalannexure= document.getElementById("toalannexure").value;
    var totalnoIa = document.getElementById("totalnoIa").value;
    var subject = document.getElementById("subject").value;
    if(rpepcptype==''){
       alert("Please select RP/EP/CP !");
       return false;
    }
    if(orderdate==''){
       alert("Please select orderdate !");
       return false;
    }
    if(toalannexure==''){
       alert("Please fill total number of annexure !");
       return false;
    }
     if(totalnoIa==''){
       alert("Please fill total no of IA !");
       return false;
    }
    
    if(subject==''){
        alert("Please Enter Subject !");
        return false;
    }

    var dataa = {};
	dataa['salt'] = salt,
	dataa['tab_no']=tabno,
	dataa['token']=token,
	dataa['filing_no']=filing_no,
	dataa['rpepcptype']=rpepcptype,
	dataa['orderdate']=orderdate,
	dataa['toalannexure']=toalannexure,
	dataa['totalnoIa']=totalnoIa,
	dataa['user_id']='<?= $userid ?>',
	dataa['subject']=subject,
	
	$.ajax({
        type: "POST",
        url: base_url+'saveNextRPEPCbasic',
        data: dataa,
        cache: false,
		beforeSend: function(){
			$('#petitioner_save').prop('disabled',true).val("Under proccess....");
		},
        success: function (resp) {
        	var resp = JSON.parse(resp);
        	if(resp.data=='success') {
		       setTimeout(function(){
                    window.location.href = base_url+'petitionparty';
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

function serchDFR(){
	with(document.valscc){
	action = base_url+"review_petition_filing";
	submit();
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