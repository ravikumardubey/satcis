<?php $this->load->view("admin/header"); ?>
<?php $this->load->view("admin/sidebar"); 
$token= $this->efiling_model->getToken(); ?>
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="content" >
  <div class="row">
    <div class="col-lg-12 mx-auto">
      <!-- Accordion -->
      <div id="accordionExample" class="accordion shadow">
        <!-- Accordion item 1 -->
        <div class="card">
          <div id="headingFour" class="card-header bg-white shadow-sm border-0">
            <h6 class="mb-0 font-weight-bold"><a href="#" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" 
            aria-controls="collapseThree" class="d-block position-relative collapsed ">Serch Diary No / Case No. And Case list Here</a></h6>
          </div>
          <div id="collapseThree" aria-labelledby="headingThree" data-parent="#accordionExample" class="collapse show">
          <?= form_open(false,['class'=>'wizard-form steps-basic wizard clearfix','name'=>'caselistingsub','id'=>'caselistingsub','autocomplete'=>'off']) ?>
            <div class="card-body">
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
    						<label for="name"><span class="custom"><font color="red">*</font></span>AL No. </label> 
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
            			<input type="submit" name="nextsubmit" value="SEARCH" class="btn btn-success" id="nextsubmit" onclick="serchDFR();">
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
                            <input type="submit" name="nextsubmit" value="SEARCH" class="btn btn-success" id="nextsubmit" onclick="serchDFR();">
                         </div>
        			    </div>
        			</div>		
           		 </div>
           		  <?php  
        		    $case_type='';
        		    $vals='';
        		    $data=array();
        		    if(@$_REQUEST['appAnddef']=='1'){
        		        $fno=$_REQUEST['dfr_no'];
        		        $year=$_REQUEST['dfryear'];
        		        $data=$this->efiling_model->getCaseDetailsDfr($fno,$year);
        		        if(empty($data)){
        		            echo '<center style="color:red">Record not Found !</center>';
        		        }
        		    }
        		    if(@$_REQUEST['appAnddef']=='2'){
        		        $cno=$_REQUEST['case_no'];
        		        $year=$_REQUEST['year'];
        		        $case_type=$_REQUEST['case_type'];
        		        $data=$this->efiling_model->getCaseDetailsCaseNo($cno,$year,$case_type);
        		        if(empty($data)){
        		            echo '<center style="color:red">Record not Found !</center>';
        		        }
        		    }
        		?>
         	 </div>
        </div>
        <?php 
        $filing_no='';
        if(!empty($data)){
            foreach($data as $rowd){
    		   $casetype=$rowd->case_type;
    		   $filing_no=$rowd->filing_no;
    		   $petName=$rowd->pet_name.$this->efiling_model->fn_addition_party($filing_no,'1');
    		   $resName=$rowd->res_name.$this->efiling_model->fn_addition_party($filing_no,'2');
    		   $case_type_detail =$this->efiling_model->data_list_where('master_case_type','case_type_code',$casetype);
    		   $case_type_name='';
    		   if($case_type_detail!=''){
    		       $case_type_name= isset($case_type_detail[0]->short_name)?$case_type_detail[0]->short_name:'';
    		   }
    		}
    		
		?>
	
		<div class="panel-body">
		<div id="massage" style="color:green;align:center"></div>
		<div>
    		<div>
    			<input type="hidden" name="filingno" id="filingno" value="<?php echo $filing_no; ?>">
    			<table class="responstable" width="100%"  align="center" border="1px">
    				<tbody>
    					<tr>
    						<td><strong>Bench</strong></td>
    						<td>Mumbai</td>
    						<td><strong>Case Type</strong></td>
    						<td colspan="2">Appeal</td>
    					</tr>
    					<tr>
    						<td><strong>Case Title</strong></td>
    						<td><?php echo $petName ?> Vs. <?php echo $resName ?></td>
    					</tr>
    				</tbody>
    			</table>			
    		</div>
       <?php 
       $listingdae='';
       $purpose='';
       $remarks='';
       $judges='';
       $ampm='';
       $minut='';
       $hr='';
       $judge2='';
       $judge1='';
       $ismaster='';
       $lval=$this->efiling_model->data_list_where('case_allocation','filing_no',$filing_no); 
       if(!empty($lval) && is_array($lval)){
           foreach($lval as $vasl){
               $listingdae=date('d-m-Y',strtotime($vasl->listing_date));
               $purpose=$vasl->purpose;
               $remarks=$vasl->remarks;
               $ismaster=$vasl->ismaster;
               $judge1=$vasl->judge1;
               $judge2=$vasl->judge2;
               $ampm=$vasl->ampm;
               $time=$vasl->time;
               $vval=explode('.', $time);
               $minut=$vval[0];
               $hr=$vval[1];
           }
       }
       ?>
       <div class="card">
    		<div class="row" id="myDIV1" >
    		    <div class="col-md-3">
                    <div class="form-card">
                         <div class="form-group">
                          	<label class="control-label" for="case_type_lower"><span class="custom"><font color="red">*</font></span>Listing Date:</label>
                          	<div class="input-group mb-3 mb-md-0">
               					<?= form_input(['name'=>'listingdatre','class'=>'form-control datepicker','id'=>'listingdatre','value'=>$listingdae,'display'=>true]) ?>
                          	</div>
                         </div>
                     </div>
                 </div> 
          
               
    	  	  
    	  	     <div class="col-md-3">
                    <div class="form-card">
                      <div class="form-group">
                      	<label class="control-label" for="case_type_lower"><span class="custom"><font color="red">*</font></span>Whether court is sitting (Yes/No):</label>
                      	<div class="input-group mb-3 mb-md-0">
           					<select name="court_master" id="court_master"  class="form-control" >
								<option value="" Selected>Select</option>
                        		<option value="1" <?php if($ismaster=='1'){ echo "selected"; }?>>YES</option>
                                <option value="0" <?php if($ismaster=='0'){ echo "selected"; }?>>NO</option>
                            </select> 
                      	</div>
                      </div>
                    </div>
                 </div> 
    	  	  
    	  	  
    	  	   <div class="col-md-3">
         		  <div class="form-card">
          	         <div class="form-group">
          				<label class="control-label" for="caseYear"><span class="custom"><font color="red">*</font></span>Purpose:</label>
                      	   <div class="input-group mb-3 mb-md-0">
                               <select name="purpose" id="purpose" class="form-control">
                                <option value="">--------select purpose----- -</option>
                                <?php
                                $display = 'Y';
                                $sqlf = $this->db->query("select purpose_code,purpose_name,purpose_priority from master_purpose where display='true'  order by purpose_priority ASC");
                                $objval= $sqlf->result();
                                if(!empty($objval)){
                                    foreach($objval as $row){?>
                                        <option value="<?php echo htmlspecialchars($row->purpose_code); ?>" <?php if($row->purpose_code==$purpose){ echo "selected";} ?>> <?php echo htmlspecialchars($row->purpose_name); ?></option>
                                       <?php
                                    }
                                }
                                ?>
                              </select>
                      	  </div>
          		      </div>
         	        </div>
    	  	     </div>
<!--     	  	     
    	  	    <div class="col-md-3">
         		  <div class="form-card">
          	         <div class="form-group">
          				<label class="control-label" for="caseYear"><span class="custom"><font color="red">*</font></span>Display Name (Cause List):</label>
                      	  <div class="input-group mb-3 mb-md-0">
                            <select name="displayname" id="displayname" class="form-control">
                                <option value="">-select-</option>
                                <option value="1">Registrar</option>
                                 <option value="2">Court Master</option>
                            </select>
                      	</div>
          		    </div>
         	     </div>
    	  	  </div> -->
            </div>
            
             <div class="row">
    	       <div class="col-md-8	">
                    <div class="form-group required">
                 	   <label class="control-label" for="caseYear"><span class="custom"><font color="red">*</font></span>Display Name (Cause List):</label>
                 	    <div class="input-group mb-3 mb-md-0">
                            <select style="width: 80px; margin-right: 10px; display: inline-block" class="form-control" name="benchhr" id="benchhr">
        				        <option value="">Hour</option>
        					    <?php $i=0; for($i;$i<13;$i++){ if(strlen($i)<=1){?>
        						<option value="<?php echo '0'.$i; ?>" <?php if($hr==$i){echo "selected";} ?>><?php echo '0'.$i; ?></option>
        					    <?php }else{ ?>
        					        <option value="<?php echo $i; ?>" <?php if($hr==$i){echo "selected";} ?> ><?php echo $i; ?></option>
        					   <?php } }?>
        					</select>
        			
        					<select style="width: 50px; margin-right: 10px; display: inline-block" class="form-control" name="benchminut" id="benchminut">
        					    <option value="">Minute</option>
        						<?php $i=0; for($i;$i<61;$i++){ if(strlen($i)<=1){?>
        						   <option value="<?php echo '0'.$i; ?>" <?php if($minut==$i){echo "selected";} ?> ><?php echo '0'.$i; ?></option>
        					    <?php }else{ ?>
        					        <option value="<?php echo $i; ?>" <?php if($minut==$i){echo "selected";} ?> ><?php echo $i; ?></option>
        					   <?php } }?>
        					</select>
        					
        				    <select style="width: 50px; margin-right: 10px; display: inline-block" class="form-control" name="benchtime" id="benchtime">
        						<option value="">AM/PM/NOON</option>
        						<option value="AM" <?php if($ampm=='AM'){echo "selected";} ?>>AM</option>
        						<option value="PM" <?php if($ampm=='PM'){echo "selected";} ?>>PM</option>
        						<option value="NOON" <?php if($ampm=='NOON'){echo "selected";} ?>>NOON</option>
        					</select>
    					</div>
    			   </div>
    		   </div>
    		</div>
    		
    		<div class="row">
    		   <div class="col-md-3">
                    <div class="form-card">
                         <div class="form-group">
                          	<label class="control-label" for="case_type_lower">Remark:</label>
                          	<div class="input-group mb-3 mb-md-0">
               					<textarea class="form-control" id="remark"  name="remark" rows="4"><?php echo $remarks; ?></textarea>
                          	</div>
                         </div>
                   </div>
              </div>
    	      <div class="col-md-3">
         		  <div class="form-card">
          	         <div class="form-group">
          				<label class="control-label" for="caseYear"><span class="custom"><font color="red">*</font></span>Judge Name 1:</label>
                      	   <div class="input-group mb-3 mb-md-0">
                               <select name="judge1" id="judge1" class="form-control">
                                <option value="">--------Select Judje----- -</option>
                                <?php
                                $display = 'Y';
                                $sqlf = $this->db->query("select * from master_judge where display='$display'  order by judge_desg_code ASC");;
                                $objval= $sqlf->result();
                                if(!empty($objval)){
                                    foreach($objval as $row){
                                        $judge_code = $row->judge_code;
                                        $judge_desg_code = $row->judge_desg_code;
                                        $app= $this->efiling_model->data_list_where('master_desg','desg_code',$judge_desg_code);
                                        $judge_desg = $app[0]->desg_name;
                                        ?>
                                        <option value="<?php echo htmlspecialchars($row->judge_code); ?>" <?php if($judge_code==$judge1){echo "selected";}?>> <?php echo htmlspecialchars($row->judge_name) . " - " . htmlspecialchars($judge_desg); ?></option>
                                       <?php
                                    }
                                }
                                ?>
                              </select>
                      	  </div>
          		      </div>
         	        </div>
    	  	     </div>
    	  	     
    	  	     <div class="col-md-3">
         		  <div class="form-card">
          	         <div class="form-group">
          				<label class="control-label" for="caseYear"><span class="custom"><font color="red">*</font></span>Judge Name 2:</label>
                      	   <div class="input-group mb-3 mb-md-0">
                               <select name="judge2" id="judge2" class="form-control">
                                <option value="">--------Select Judje----- -</option>
                                <?php
                                $display = 'Y';
                                $sqlf = $this->db->query("select * from master_judge where display='$display'  order by judge_desg_code ASC");;
                                $objval= $sqlf->result();
                                if(!empty($objval)){
                                    foreach($objval as $row){
                                        $judge_code = $row->judge_code;
                                        $judge_desg_code = $row->judge_desg_code;
                                        $app= $this->efiling_model->data_list_where('master_desg','desg_code',$judge_desg_code);
                                        $judge_desg = $app[0]->desg_name;
                                        ?>
                                        <option value="<?php echo htmlspecialchars($row->judge_code); ?>" <?php if($judge_code==$judge2){echo "selected";}?>> <?php echo htmlspecialchars($row->judge_name) . " - " . htmlspecialchars($judge_desg); ?></option>
                                       <?php
                                    }
                                }
                                ?>
                              </select>
                      	  </div>
          		      </div>
         	        </div>
    	  	     </div>
    	  	     
    		    </div>
    	    
    	    
    	     
    	  	     
    	    <div class="col-md-3">
                 <div class="form-group required" style="margin-top: 29px;">
                    <input type="button" name="nextsubmit" value="Submit" class="btn btn-success" id="nextsubmit" onclick="listing();">
                 </div>
		    </div>
	    </div>
	    <?php } ?>		
    </div>	
  </div>
<?= form_close();?>       
<?php $this->load->view("admin/footer"); ?>	
 <script>
$('#listingdatre').datetimepicker({
    format: 'mm-dd-yyyy'
});
function  diary(){
   with(document.caselistingsub){
	action = base_url+"caselisting";
	submit();
    	document.frm.submit11.disabled = true;  
     	document.frm.submit11.value = 'Please Wait...';  
     	return true;
	}
}
function serchDFR(){
	with(document.caselistingsub){
	action = base_url+"caselisting";
	submit();
    	document.frm.submit11.disabled = true;  
     	document.frm.submit11.value = 'Please Wait...';  
     	return true;
	}
}

function listing(){
    var listingdate = document.getElementById("listingdatre").value;
    if(listingdate==''){
        alert("Please select Listing Date");
        return false;
    }
    var courtmaster =document.getElementById("court_master").value;
    if(courtmaster==''){
        alert("Please select court master");
        return false;
    }
    var displayname = $("#displayname").val();
    if(displayname==''){
    	  alert("Please Enter display name");
          return false;
    }
    var benchhr =document.getElementById("benchhr").value;
    if(benchhr==''){
        alert("Please Enter Time");
        return false;
    }
    var filingno =document.getElementById("filingno").value;
    if(filingno==''){
        alert("Please Enter Diary number");
        return false;
    }


    var judge1 =document.getElementById("judge1").value;
    if(judge1==''){
        alert("Please select judge1");
        return false;
    }

    var judge2 =document.getElementById("judge2").value;
    if(judge2==''){
        alert("Please select judge2");
        return false;
    }

    var purpose =document.getElementById("purpose").value;
    if(purpose==''){
        alert("Please select purpose");
        return false;
    }

    
    
    var benchminut =document.getElementById("benchminut").value;
    var benchtime =document.getElementById("benchtime").value;
    var remark =document.getElementById("remark").value;
    var dataa = {};
	dataa['filingno']=filingno,
	dataa['time']=benchhr,
	dataa['displayname']=displayname,
	dataa['listingdate']=listingdate,
	dataa['courtmaster']=courtmaster,
	dataa['token']='<?php  echo $token; ?>',
	dataa['benchminut']=benchminut,
	dataa['benchtime']=benchtime,
	dataa['remark']=remark,
	dataa['judge1']=judge1,
	dataa['judge2']=judge2,
	dataa['purpose']=purpose,
	$.ajax({
        type: "POST",
        url: base_url+'caselistingsubmit',
        data: dataa,
        cache: false,
		beforeSend: function(){
			//$('#nextsubmit').prop('disabled',true).val("Under proccess....");
		},
        success: function (resp) {
        	var resp = JSON.parse(resp);
        	if(resp.data=='success') {
    		    $('#massage').text("Case listed successfully ");
			}
			else if(resp.error != '0') {
				$.alert(resp.error);
			}
        },
        error: function(){
			$.alert("Surver busy,try later.");
		}
	}); 
}
</script> 