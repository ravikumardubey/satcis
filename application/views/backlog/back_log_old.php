<?php $this->load->view("admin/header"); ?>
<?php $this->load->view("admin/sidebar"); ?>
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?= form_open(false,['class'=>'wizard-form steps-basic wizard clearfix','name'=>'rpepcpbascidetail','id'=>'rpepcpbascidetail','autocomplete'=>'off']) ?>
<div class="content" >
  <div class="row">
    <div class="col-lg-12 mx-auto">
      <!-- Accordion -->
      <div id="accordionExample" class="accordion shadow">

        <!-- Accordion item 1 -->
        
        <div class="card">
          <div id="headingFour" class="card-header bg-white shadow-sm border-0">
            <h6 class="mb-0 font-weight-bold"><a href="#" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" 
            aria-controls="collapseThree" class="d-block position-relative collapsed ">Serch Diary No / Case No.</a></h6>
          </div>
          <div id="collapseThree" aria-labelledby="headingThree" data-parent="#accordionExample" class="collapse show">
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
						<label for="name"><span class="custom"><font color="red">*</font></span>DFR No </label> 
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
                            	<label for="name"><span class="custom"><font color="red">*</font></span>DFR NO: </label> 
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
        		    if($_REQUEST['appAnddef']=='1'){
        		        $fno=$_REQUEST['dfr_no'];
        		        $year=$_REQUEST['dfryear'];
        		        $data=$this->efiling_model->getCaseDetailsDfr($fno,$year);
        		        if(empty($data)){
        		            echo '<center style="color:red">Record not Found !</center>';
        		        }
        		    }
        		    if($_REQUEST['appAnddef']=='2'){
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
        //print_r($data);
        ?>
      
    		
        <div class="card">
          <div id="headingOne" class="card-header bg-white shadow-sm border-0">
            <h6 class="mb-0 font-weight-bold"><a href="#" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false "
             aria-controls="collapseOne" class="d-block position-relativ">Applicant</a></h6>
          </div>
          <div id="collapseOne" aria-labelledby="headingOne" data-parent="#accordionExample" class="collapse ">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group required">
                            <label>Appellant Name<span class="text-danger">*</span></label>
                            <input type="hidden" name="orgid" value="" id="orgid" class="txt">
                            <input type="text" name="petName" value="" id=petName  class="form-control" onkeypress="serchrecordvalapp(this.value);">
                    		<ul class="autosuggest" id="regnum_autofill">
                    		</ul>
                            <div class="col-md-9" id="recordadv"></div>
                        </div>
                    </div>
                    <div class="col-md-4" id="appagediv">
                        <div class="form-group">
                            <label><span class="custom"><font color="red">*</font></span>Appellant Age:</label>
                            <?= form_input(['name'=>'appage','id'=>"appage",'onkeypress'=>'return isNumberKey(event)','value'=>'','class'=>'form-control','placeholder'=>'',
                                'maxlength'=>'2', 'pattern'=>'[0-9]{0,10}', 'title'=>'Phone allowed only numeric']) ?>
                        </div>
                    </div>
                    
                    <div class="col-md-4" id="appfatherdiv">
                        <div class="form-group">
                            <label>Father`s Name:</label>
                            <?= form_input(['name'=>'appfather','id'=>"appfather",'value'=>'','class'=>'form-control','placeholder'=>'',
                                'maxlength'=>'125', 'pattern'=>'[a-z]{0,10}', 'title'=>'Father Name']) ?>
                        </div>
                    </div>
      
                      <div class="col-md-4" id="apppandiv">
                        <div class="form-group">
                            <label><span class="custom"><font color="red">*</font></span>Pancard Number:</label>
                            <?= form_input(['name'=>'apppan','id'=>"apppan",'value'=>'','class'=>'form-control','placeholder'=>' ',
                                'maxlength'=>'10', 'pattern'=>'[a-z]{0,10}', 'title'=>'Phone allowed only numeric']) ?>
                        </div>
                      </div>
                     <div class="col-md-4">
                        <div class="form-group required">
                            <label>State Name :<span class="text-danger">*</span></label>
                           <?php 
    
                          $state= $this->efiling_model->data_list('master_psstatus');
                          $state1[]='- Please Select state-';
                          foreach ($state as $val)
                              $state1[$val->state_code] = $val->state_name;  
                              echo form_dropdown('dstate',$state1,'',['class'=>'form-control','onchange'=>"showCity(this);" ,'id'=>'dstate']);  ?>
                        </div>
                    </div>
                    
                      <div class="col-md-4">
                        <div class="form-group required">
                            <input type="hidden" name="petdistrictname" value="" id="petdistrictname" class="txt">
                            <label>District:<span class="text-danger">*</span></label>                    
                	        <?php   $petdis= '';
                               $city1[]='- Please Select city-';
                               echo form_dropdown('ddistrict',$city1,'',['class'=>'form-control','id'=>'ddistrict']);  ?>
                        </div>
                    </div>
            	
                     <div class="col-md-4">
                        <div class="form-group">
                            <label><span class="custom"><font color="red">*</font></span>Email ID:</label>
                            <?= form_input(['name'=>'petEmail','class'=>'form-control','value'=>'','id'=>'petEmail','placeholder'=>'']) ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group required">
                            <label><font color="red">*</font></span>Mobile</label>
                            <?= form_input(['name'=>'petmobile','value'=>'','class'=>'form-control', 'onkeypress'=>'return isNumberKey(event)','id'=>'petmobile','placeholder'=>'','pattern'=>'[0-9]{10,10}','maxlength'=>'10','title'=>'Mobile number should be numeric and 10 digit only.']) ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Phone Number:</label>
                            <?= form_input(['name'=>'petPhone','id'=>"petPhone",'onkeypress'=>'return isNumberKey(event)','value'=>'','class'=>'form-control','placeholder'=>' ','maxlength'=>'10', 'pattern'=>'[0-9]{0,10}', 'title'=>'Phone allowed only numeric']) ?>
                        </div>
                    </div>
                </div>
        
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group required">
                            <label>Pincode</label>
                                <?= form_input(['name'=>'pincode','value'=>'','class'=>'form-control', 'onkeypress'=>'return isNumberKey(event)',
                                    'id'=>'pincode','placeholder'=>'','pattern'=>'[0-9 ]{0,6}','maxlength'=>'6','title'=>'Pincode info allowed only alphanumeric ']) ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group required">
                            <label>Fax No:</label>
                             <?= form_input(['name'=>'petFax','value'=>'','class'=>'form-control', 
                                 'onkeypress'=>'return isNumberKey(event)', 'id'=>'petFax','placeholder'=>'','maxlength'=>'10','title'=>'petFax info allowed only alphanumeric ']) ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group required">
                            <label>Address:</label>
                            <?= form_textarea(['name'=>'petAddress','value'=>'','id'=>'petAddress','class'=>'form-control','rows' => '2','cols'=>'2','placeholder'=>'','maxlength'=>'400','title'=>'Address Of Appellant allowed only alphanumeric ']) ?>
                        </div>
                    </div>
                </div>
                <input type="button" id="nextsubmit" value="Add Appellant" class="btn btn-primary" onclick="addMoreApp();">		
            </div>
          </div>
        </div>

        <!-- Accordion item 2 -->
        <div class="card">
          <div id="headingTwo" class="card-header bg-white shadow-sm border-0">
            <h6 class="mb-0 font-weight-bold"><a href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" 
            class="d-block position-relative collapsed">Respondent</a></h6>
          </div>
          <div id="collapseTwo" aria-labelledby="headingTwo" data-parent="#accordionExample" class="collapse">
            <div class="card-body">
      
            </div>
          </div>
        </div>

        <!-- Accordion item 3 -->
        <div class="card">
          <div id="headingThree" class="card-header bg-white shadow-sm border-0">
            <h6 class="mb-0 font-weight-bold"><a href="#" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" 
            aria-controls="collapseThree" class="d-block position-relative collapsed ">Councel</a></h6>
          </div>
          <div id="collapseThree" aria-labelledby="headingThree" data-parent="#accordionExample" class="collapse">
            <div class="card-body p-5">
              <p class="font-weight-light m-0">Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et.</p>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
  </div>

<?= form_close();?>
<script>

function  diary(){
   with(document.rpepcpbascidetail){
	action = base_url+"back_log";
	submit();
    	document.frm.submit11.disabled = true;  
     	document.frm.submit11.value = 'Please Wait...';  
     	return true;
	}
}

function serchDFR(){
	with(document.rpepcpbascidetail){
	action = base_url+"back_log";
	submit();
    	document.frm.submit11.disabled = true;  
     	document.frm.submit11.value = 'Please Wait...';  
     	return true;
	}
}
</script>        
<?php $this->load->view("admin/footer"); ?>		