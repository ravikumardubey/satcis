<?php 
$this->load->view("admin/header");
$this->load->view("admin/sidebar");
$status='';
$disposel_date='';
$filing_no='';
 defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?= form_open(false,['class'=>'wizard-form steps-basic wizard clearfix','name'=>'rpepcpbascidetail','id'=>'rpepcpbascidetail','autocomplete'=>'off']) ?>

 <style>
.autosuggest {
    list-style: none;
    margin: 0;
    padding: 0;
    position: absolute;
    left: 15px;
    top: 65px;
    z-index: 1;
    background: #fff;
    width: 94%;
    box-shadow: 0px 5px 5px rgba(0, 0, 0, 0.2);
    overflow-y: auto;
    max-height: 280px;
}
.autosuggest li {
    padding: 8px 10px;
    font-size: 13px;
    color: #26c0d9;
    cursor: pointer;
}
.autosuggest li:hover {
    background: #f5f5f5;
}
#accordionExample{
padding: 0 5px;
}
</style>   

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
      <div class="row">
        <div class="col-lg-12 mx-auto">
    		<div class="card-body">
                 <div class="row">
    			  	<div class="col-md-3">
                         <div class="form-group required">
    						<label for="name"><span class="custom"><font color="red">*</font></span>Appeal Lodging No </label> 
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
                            	<label for="name"><span class="custom"><font color="red">*</font></span>Appeal Lodging No </label> 
                            	  <?= form_input(['name'=>'dfr_no','class'=>'form-control','id'=>'dfr_no','value'=>$dfr_no,'onkeypress'=>'return isNumberKey(event)','placeholder'=>'Enter DFR NO.','pattern'=>'[0-9]{1,8}','maxlength'=>'8','title'=>'DFR No. should be numeric only.','required'=>'true']) ?>
                             </div>
                      </div>
                       <div class="col-md-3">
                         <div class="form-card">
                          	  <div class="form-group">
                          		<label class="control-label" for="caseYear"><span class="custom"><font color="red">*</font></span>Year:</label>
                              	<div class="input-group mb-3 mb-md-0">
                                 <?php
                                 $year1=array();
                                 $year = $dfryear;
                                 $curryear=date('Y');
                                 $year1=[''=>'- Select Year -'];
                                 for ($i = $curryear; $i > 2009 ; $i--) {
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
           		 </div>
           		 <div id="massagecreatedfr"></div>
           		  <div id="massagecreatedfrssss"></div>
    
         <?php  
   		  
		    $case_type='';
		    $vals='';
		    $data=array();
		    if(@$_REQUEST['appAnddef']=='1'){
		        $fno=$_REQUEST['dfr_no'];
		        $year=$_REQUEST['dfryear'];
		        $data=$this->efiling_model->getCaseDetailsDfr($fno,$year);
		        $type='dfrwise';
		        if(empty($data)){?>
		            <center id="notfound"><span style="color:red">Record not Found !</span><br>Do you want to Create New Filing number <a href="#" onclick="createdfr('<?php echo $fno; ?>','<?php echo $year; ?>','<?php echo $type; ?>')"><h7>Click Here</h7></a></center>
		        <?php }
		    }
		    if(@$_REQUEST['appAnddef']=='2'){
		       $cno=isset($_REQUEST['case_no'])?$_REQUEST['case_no']:'';
		       $year=isset($_REQUEST['year'])?$_REQUEST['year']:'';
		       $case_type=isset($_REQUEST['case_type'])?$_REQUEST['case_type']:'';
               $data=array();
               if($case_type!='' && $cno!=''){
		            $data=$this->efiling_model->getCaseDetailsCaseNo($cno,$year,$case_type);
                }
                $type='casewise';
		        if(empty($data)){?>
		            <center id="notfound"><span style="color:red">Record not Found !</span><br>Do you want to Create New Filing number 
		            <a href="#" onclick="createdfrcasewise('<?php echo $cno; ?>','<?php echo $year; ?>','<?php echo $case_type; ?>','<?php echo $type; ?>')"><h7>Click Here</h7></a></center>
		       <?php }
		    }
		    $filing_no=@$data[0]->filing_no;
		    $res_id='';
		    $pet_id='';
		    $apealtyp='';
		    $ipenalty='';
		    $opauthority='';
		    $iorderdate='';
		    $rimpugnedorder='';
		    $iordernumber='';
		    $delayinfiling='';
		    $app= $this->efiling_model->data_list_where('sat_case_detail','filing_no',$filing_no);
		    if(!empty($app) && is_array($app)){
		        foreach($app as $val){
		            $apealtyp=$val->apeal_type;
		            $act=$val->act;
		            $ipenalty=$val->ipenalty;
		            $opauthority=$val->opauthority;
		            $iorderdate=date('d-m-Y');
		            $rimpugnedorder=date('d-m-Y');
		            $iordernumber=$val->iordernumber;
		            $delayinfiling=$val->delayinfiling;
		            
		            $pet_id=$val->pet_id;
		            $res_id=$val->res_id;
		            
		            $status=$val->status;
		            $disposel_date=$val->disposel_date;
		        }
		    }
		    $checked1='checked';
		    $checked2='';
		    $checked3='';
		    if($apealtyp=='SEBI'){
		        $checked1="checked";
		    }
		    if($apealtyp=='IRDAI'){
		        $checked2="checked";
		    }
		    if($apealtyp=='PFRDA'){
		        $checked3="checked";
		    }
		    if($checked1!=''){
		        $apealtyp="SEBI";
		    }
		    $filing_no=$data[0]->filing_no;
		    
		?>	
        <input type="hidden" name="filing_no" id="filing_no" value="<?php echo @$data[0]->filing_no; ?>">
          <!-- Accordion -->
      <div id="accordionExample" class="accordion shadow">
        <!-- Accordion item 1 -->
       	 <div class="card" style="padding:2px 5px;">
        <!-- -Start Regulator -->
    	   <div class="card">
          	<div id="heading1" class="card-header bg-white shadow-sm border-0">
            	<h6 class="mb-0 font-weight-bold"><a href="#" data-toggle="collapse" data-target="#collapse1" aria-expanded="false"  	aria-controls="collapse1" class="d-block position-relative collapsed ">Regulator Detail</a></h6>
          	</div>
          	<div class="massagebasic" style="color:green;text-align: center;"></div>
      			<div id="collapse1" aria-labelledby="heading1" data-parent="#accordionExample" class="collapse">
        			<div class="card-body ">
          				<div class="content" style="padding-top:0px;">
                    		<div class="row">
                    			<div class="card" style="width: 100%; order-top: 0px;margin-top: 0px; border-top-right-radius: 0px; border-top-left-radius: 0px;border-bottom: 1px solid #dfdfdf;">
                    			<?= form_open(false,['class'=>'wizard-form steps-basic wizard clearfix','id'=>'chkList','autocomplete'=>'off']) ?>
                                      <div style="background-color: #ffe0e0;">
                                         <div class="row">      
                                             <div class="col-md-3"> 
                                				 <input type = "radio" name = "appeal_type" id = "appeal_type" value ="SEBI" <?php echo $checked1; ?> onclick="getregulator(this.value);">
                                				 <label for="name"><span class="custom"><sup class="text-danger">*</sup></span>SEBI Appeal</span></label>
                            				 </div>
                            				  <div class="col-md-3">
                                				  <input type = "radio"name = "appeal_type" id ="appeal_type" value ="IRDAI" <?php echo $checked2; ?>  onclick="getregulator(this.value);">
                                				  <label for="name"><span class="custom"><sup class="text-danger">*</sup></span>IRDA Appeal </span></label>
                            				 </div>
                            				 <div class="col-md-3">
                                				  <input type = "radio"name = "appeal_type" id ="appeal_type" value ="PFRDA" <?php echo $checked3; ?>  onclick="getregulator(this.value);">
                                				  <label for="name"><span class="custom"><sup class="text-danger">*</sup></span>PFRDA Appeal </span></label>
                            				 </div>
                        				</div>
                    				</div>
                        			<?php   $actval=$this->efiling_model->data_list_where('master_act','status',1); ?>
                        		      <div class="row" style="padding:5px;">
                        				<div class="col-md-4">
                        					<div>
                        					<label for="name"><span class="custom">ACTS/ Rules </span>: <sup class="text-danger">*</sup></label>
                    						  <select name="relevant_acts" id="relevant_acts" class="form-control" required>
                        						     <option value="" selected>----Select Act-------</option>
                        						  <?php if(!empty($actval)){
                        						      foreach($actval as $row){?>
                            						  <option value="<?php echo $row->id; ?>" <?php if($act='1'){echo "selected";} ?>><?php echo $row->act_full_name; ?></option>
                            				          <?php } 
                            						  }?>
                    						  </select>
                        					</div>
                        				</div>
                        				<div class="col-md-4">
                        					<div required">
                        					<label for="name"><span class="custom"> Order Passing Authority</span>  :<sup class="text-danger">*</sup></label>
                    						   <select name="order_passing" id="order_passing" class="form-control" required>
                    						  </select>
                        					</div>
                        				</div>
                            			<div class="col-md-4">
                                            <div required">
                                                <label>Imposition of Penalty : <sup class="text-danger">*</sup> </label>
                                                 <input type="text" name="penalty" id="penalty"  class="form-control" required="true" maxlength="12" 
                                                 pattern="[0-9]{1,10}" title="Kindly enter [0-9], Max 4 Characters is allowed" value="<?php echo $ipenalty; ?>"  onkeypress="return isNumberKey(event)"  placeholder="PENALTY" onkeypress="serchrecordvalapp(this.value);">
                                            </div>
                                        </div>
                        		   </div>
                        		<br>
                    			<hr>
                    			 <div style="padding:font-weight: 600;font-size:20px;  max-width: 100%;" align="center">
                    				<div class="col-md-4"><label class="text-danger">IMPUGNED ORDER DETAILS</label><br><br></div>
                    			</div>
                    	        <div class="row" style="padding:5px;">
                    	        	<div class="col-md-3">
                    					<label class="control-label" for="impugned_no"><span class="custom">Impugned Order Number :</label>
                    					<div class="input-group mb-3 mb-md-0">
                                        <input name="impugned_no" id="impugned_no" maxlength="25" placeholder="Impugned Order Number" class="form-control"    value="<?php echo $iordernumber; ?>"> 
                    					</div>
                    				</div>
                    				
                    				<div class="col-md-3">
                    					<label for="name"><span class="custom"><sup class="text-danger">*</sup></span> Impugned Order Date :</span></label>                            
                                         <div class="input-group mb-3 mb-md-0">
                                            <?= form_input(['name'=>'impugned_date','id'=>"impugned_date",'value'=>$iorderdate,'class'=>'form-control datepicker','placeholder'=>'Impugned Order Date ','required'=>true,'title'=>'Impugned Order Date .']) ?>
                                         </div>
                                     </div>
                    				<div class="col-md-3">
                    					<label for="name"><span class="custom">Date of receipt of  Impugned Order </span>:</label>
                						<div class="input-group mb-3 mb-md-0">
                                        <?= form_input(['name'=>'receipt_impdate','id'=>"receipt_impdate",'value'=>$rimpugnedorder,'class'=>'form-control datepicker',
                                            'placeholder'=>'Date of receipt of Impugned Order','title'=>'Date of receipt of Impugned Order.']) ?>
                                        </div>
                    				</div>
                    				<div class="col-md-3">
                    					<label class="control-label" for="delay_filing"><span class="custom">Delay in Filing :</label>
                    					<div class="input-group mb-3 mb-md-0">
                                        <input name="delay_filing" id="delay_filing" placeholder="Delay in Filing" class="form-control" value="<?php echo $delayinfiling; ?>"> 
                    					</div>
                    				</div>					
                        		</div>
                        		<div id="delaymsg" style="margin-left: 800px;color:#0b0b0c;font-size: 22px;"></div>	
                        	</div>
                      </div>  
                        <div class="row">
                            <div class="offset-md-11 col-md-1">
                            	<i class="icon-file-plus" style="position: absolute; margin: 9px 4px; z-index: 9; color: white;"></i>
                            	<input type="button" value="Save" class="btn btn-success" onclick="backlogsavenext();" style="padding-left:24px;">
                             </div>
                        </div>
                    </div>
              	</div>
             </div>
		</div>
        <!-- -End Regulator -->    	
            	
        
        <!-- -Start Applicant  -->    	
        <div class="card">
          <div id="headingOne" class="card-header bg-white shadow-sm border-0">
            <h6 class="mb-0 font-weight-bold"><a href="#" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false "   aria-controls="collapseOne" class="d-block position-relativ">Applicant</a></h6>
          </div>
          <div id="collapseOne" aria-labelledby="headingOne" data-parent="#accordionExample" class="collapse ">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group required">
                            <label>Appellant Name<span class="text-danger">*</span></label>
                            <input type="hidden" name="orgid" value="<?php echo $pet_id;?>" id="orgid" class="txt">
                            <input type="text" name="petName" value="<?php echo $data[0]->pet_name?> "id=petName  class="form-control" onkeypress="searchorg(this.value);">
                    		<ul class="autosuggest" id="regnum_autofill">
                    		</ul>
                        </div>
                    </div>
                    <div class="col-md-4" >
                        <div class="form-group">
                            <label><span class="custom"><font color="red">*</font></span>Appellant Age:</label>
                            <?= form_input(['name'=>'appage','id'=>"appage",'onkeypress'=>'return isNumberKey(event)','value'=> $data[0]->appage,'class'=>'form-control','placeholder'=>'',
                                'maxlength'=>'2', 'pattern'=>'[0-9]{0,10}', 'title'=>'Phone allowed only numeric']) ?>
                        </div>
                    </div>
                    
                    <div class="col-md-4" >
                        <div class="form-group">
                            <label>Father`s Name:</label>
                            <?= form_input(['name'=>'appfather','id'=>"appfather",'value'=>$data[0]->appfather,'class'=>'form-control','placeholder'=>'',
                                'maxlength'=>'125', 'pattern'=>'[a-z]{0,10}', 'title'=>'Father Name']) ?>
                        </div>
                    </div>
      
      
                   <div class="col-md-4" id="apppandividtype">
                        <div class="form-group">
                            <label><span class="custom"><font color="red">*</font></span>ID Type:</label>
                            <select id="idtype" name="idtype" class="form-control">
                               <option value="pancard">Pancard</option>
                                <option value="Dl">Driving Licanece</option>
                                <option value="voter">Voter Id</option>
                            </select>

                        </div>
                     </div>
                     
                      <div class="col-md-4" >
                        <div class="form-group">
                            <label><span class="custom"><font color="red">*</font></span>ID Number:</label>
                            <?= form_input(['name'=>'apppan','id'=>"apppan",'value'=>$data[0]->apppan,'class'=>'form-control','placeholder'=>' ','maxlength'=>'10', 'pattern'=>'[a-z]{0,10}', 'title'=>'Phone allowed only numeric']) ?>
                        </div>
                      </div>
                     <div class="col-md-4">
                        <div class="form-group required">
                          <label>State Name :<span class="text-danger">*</span></label>
                          <input type="hidden" name="resstatename" value="" id="resstatename" class="txt">
                         <?php 
                          $state= $this->efiling_model->data_list('master_psstatus');
                          $state1[]='- Please Select state-';
                          $state2=[];
                          foreach ($state as $val){
                              $state1[$val->state_code] = $val->state_name; 
                              if($val->state_code==$data[0]->pet_state) {
                                $state_code=$val->state_code;
                                ?>
                                <script>
                                var abx= <?php echo $state_code;?>;
                                </script>
                                <?php
                              }
                            }
                          echo form_dropdown('dstate',$state1,$state_code,['class'=>'form-control' ,'id'=>'dstate']);  ?>
                        </div>
                    </div>
                    
                      <div class="col-md-4">
                        <div class="form-group required">
                            <input type="hidden" name="petdistrictname" value="" id="petdistrictname" class="txt">
                            <label>District:<span class="text-danger">*</span></label>  
                            <input type="hidden" name="ddistrictres" value="<?php  echo $data[0]->pet_dist; ?>" id="ddistrictres" class="txt">
                            <?php   $petdis=  $this->efiling_model->data_list_where('master_psdist','state_code',$data[0]->pet_state);     
                               $city1[]='- Please Select city-';
                               foreach ($petdis as $val){
                                $city1[$val->district_code] = $val->district_name; 
                                if($val->district_code==$data[0]->pet_district) {
                                  $dist_code=$val->district_code;
                                }
                                if($val->district_code==$data[0]->res_district) {
                                  $dist_code_resp=$val->district_code;
                                }
                              }
                             echo form_dropdown('ddistrict',$city1,$dist_code,['class'=>'form-control','id'=>'ddistrict']);  ?>
                        </div>
                    </div>
            	
                     <div class="col-md-4">
                        <div class="form-group">
                            <label><span class="custom"><font color="red">*</font></span>Email ID:</label>
                            <?= form_input(['name'=>'petEmail','class'=>'form-control','value'=>$data[0]->pet_email,'id'=>'petEmail','placeholder'=>'']) ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group required">
                            <label><font color="red">*</font></span>Mobile</label>
                            <?= form_input(['name'=>'petmobile','value'=>$data[0]->pet_mobile,'class'=>'form-control', 'onkeypress'=>'return isNumberKey(event)','id'=>'petmobile','placeholder'=>'','pattern'=>'[0-9]{10,10}','maxlength'=>'10','title'=>'Mobile number should be numeric and 10 digit only.']) ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Phone Number:</label>
                            <?= form_input(['name'=>'petPhone','id'=>"petPhone",'onkeypress'=>'return isNumberKey(event)','value'=>$data[0]->pet_phone,'class'=>'form-control','placeholder'=>' ','maxlength'=>'10', 'pattern'=>'[0-9]{0,10}', 'title'=>'Phone allowed only numeric']) ?>
                        </div>
                    </div>
           
                    <div class="col-md-4">
                        <div class="form-group required">
                            <label>Pincode</label>
                                <?= form_input(['name'=>'pincode','value'=>$data[0]->pet_pin,'class'=>'form-control', 'onkeypress'=>'return isNumberKey(event)',
                                    'id'=>'pincode','placeholder'=>'','pattern'=>'[0-9 ]{0,6}','maxlength'=>'6','title'=>'Pincode info allowed only alphanumeric ']) ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group required">
                            <label>Fax No:</label>
                             <?= form_input(['name'=>'petFax','value'=>$data[0]->pet_fax,'class'=>'form-control', 
                                 'onkeypress'=>'return isNumberKey(event)', 'id'=>'petFax','placeholder'=>'','maxlength'=>'10','title'=>'petFax info allowed only alphanumeric ']) ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group required">
                            <label>Address:</label>
                            <?= form_textarea(['name'=>'petAddress','value'=>$data[0]->pet_address,'id'=>'petAddress','class'=>'form-control','rows' => '2','cols'=>'2','placeholder'=>'','maxlength'=>'400','title'=>'Address Of Appellant allowed only alphanumeric ']) ?>
                        </div>
                    </div>
                </div>
                <input type="button" id="nextsubmit" value="Add Applicant" class="btn btn-primary" onclick="up_date();">		
            </div>
        </div>
      </div>  
         <!-- -End  Applicant  --> 
   
         
      <!-- -Start additional Applicant  -->    	
        <div class="card">
        
          <div id="headingadditionalapplicant" class="card-header bg-white shadow-sm border-0">
            <h6 class="mb-0 font-weight-bold"><a href="#" data-toggle="collapse" data-target="#collapseadditonalapplicant" aria-expanded="false "   
            aria-controls="collapseadditonalapplicant" class="d-block position-relativ">Additional Applicant Detail</a></h6>
          </div>
          <div id="collapseadditonalapplicant" aria-labelledby="headingadditionalapplicant" data-parent="#accordionExample" class="collapse ">
           	<div class="card-body">
                <div class="row alert alert-warning" style="padding: 8px 0px 0px; font-weight: 600;font-size: 18px;  max-width: 100%;">
                    <div class="col-md-4">
                        <label class="text-danger">Select Mode</label>
                    </div>
                    <div class="col-md-6 md-offset-2">
                        <label for="org" class="form-check-label font-weight-semibold">
                            <?= form_radio(['name'=>'orgPet','id'=>"bd1" ,'value'=>'1','onclick'=>'orgshowbaklog(this.value)','checked'=>'']); ?> Organization&nbsp;&nbsp;
                        </label>
                        <label for="indv" class="form-check-label font-weight-semibold">
                            <?= form_radio(['name'=>'orgPet','id'=>'po1' ,'value'=>'2' ,'onclick'=>'orgshowbaklog(this.value)','checked'=>'']); ?> Individual&nbsp;&nbsp;
                        </label>
                        <label for="inp" class="form-check-label font-weight-semibold">
                        <?php //echo  $salt=htmlspecialchars($_REQUEST['salt']); ?>
                        </label>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group required">
                            <label>Appellant Name<span class="text-danger">*</span></label>
                            <input type="hidden" name="orgidPet" value="" id="orgidPet" class="txt">
                            <input type="text" name="petNamePet" value="" id="petNamePet"  class="form-control" onkeypress="getorgPet(this.value);">
                    		<ul class="autosuggest" id="regnum_autofillPet">
                    		</ul>
                        </div>
                    </div>
                    
                    <div class="col-md-4" id="appagediv" style="display:none">
                        <div class="form-group">
                            <label><span class="custom"><font color="red">*</font></span>Appellant Age:</label>
                            <?= form_input(['name'=>'appagePet','id'=>"appagePet",'onkeypress'=>'return isNumberKey(event)','value'=> '','class'=>'form-control','placeholder'=>'',
                                'maxlength'=>'2', 'pattern'=>'[0-9]{0,10}', 'title'=>'Phone allowed only numeric']) ?>
                        </div>
                    </div>
                    
                    <div class="col-md-4" id="appfatherdiv" style="display:none">
                        <div class="form-group">
                            <label>Father`s Name:</label>
                            <?= form_input(['name'=>'appfatherPet','id'=>"appfatherPet",'value'=>'','class'=>'form-control','placeholder'=>'',
                                'maxlength'=>'125', 'pattern'=>'[a-z]{0,10}', 'title'=>'Father Name']) ?>
                        </div>
                    </div>
      
      
                      <div class="col-md-4" id="apppandividtypeadd" style="display:none">
                        <div class="form-group">
                            <label><span class="custom"><font color="red">*</font></span>ID Type:</label>
                            <select id="idtype" name="idtype" class="form-control">
                               <option value="pancard">Pancard</option>
                                <option value="Dl">Driving Licanece</option>
                                <option value="voter">Voter Id</option>
                            </select>

                        </div>
                     </div>
                     
                      <div class="col-md-4" id="apppandiv" style="display:none">
                        <div class="form-group">
                            <label><span class="custom"><font color="red">*</font></span>Pancard Number:</label>
                            <?= form_input(['name'=>'apppanPet','id'=>"apppanPet",'value'=>'','class'=>'form-control','placeholder'=>' ','maxlength'=>'10', 'pattern'=>'[a-z]{0,10}', 'title'=>'Phone allowed only numeric']) ?>
                        </div>
                      </div>
                     <div class="col-md-4">
                        <div class="form-group required">
                          <label>State Name :<span class="text-danger">*</span></label>
                          <input type="hidden" name="resstatenamePet" value="" id="resstatenamePet" class="txt">
                         <?php 
                          $state= $this->efiling_model->data_list('master_psstatus');
                          $state1[]='- Please Select state-';
                          $state2=[];
                          foreach ($state as $val){
                              $state1[$val->state_code] = $val->state_name; 
                              if($val->state_code==$data[0]->pet_state) { ?>
                                <?php
                              }
                            }
                          echo form_dropdown('dstatePet',$state1,'',['class'=>'form-control' ,'id'=>'dstatePet']);  ?>
                        </div>
                    </div>
                    
                      <div class="col-md-4">
                        <div class="form-group required">
                            <input type="hidden" name="petdistrictnamePet" value="" id="petdistrictnamePet" class="txt">
                            <label>District:<span class="text-danger">*</span></label>  
                            <input type="hidden" name="ddistrictresPet" value="<?php  echo $data[0]->pet_dist; ?>" id="ddistrictresPet" class="txt">
                            <?php   $petdis=  $this->efiling_model->data_list_where('master_psdist','state_code',$data[0]->pet_state);     
                               $city1[]='- Please Select city-';
                               foreach ($petdis as $val){
                                $city1[$val->district_code] = $val->district_name; 
                              }
                             echo form_dropdown('ddistrictPet',$city1,'',['class'=>'form-control','id'=>'ddistrictPet']);  ?>
                        </div>
                    </div>
            	
                     <div class="col-md-4">
                        <div class="form-group">
                            <label><span class="custom"><font color="red">*</font></span>Email ID:</label>
                            <?= form_input(['name'=>'petEmailPet','class'=>'form-control','value'=>'','id'=>'petEmailPet','placeholder'=>'']) ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group required">
                            <label><font color="red">*</font></span>Mobile</label>
                            <?= form_input(['name'=>'petmobilePet','value'=>'','class'=>'form-control', 'onkeypress'=>'return isNumberKey(event)','id'=>'petmobilePet','placeholder'=>'','pattern'=>'[0-9]{10,10}','maxlength'=>'10','title'=>'Mobile number should be numeric and 10 digit only.']) ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Phone Number:</label>
                            <?= form_input(['name'=>'petPhonePet','id'=>"petPhonePet",'onkeypress'=>'return isNumberKey(event)','value'=>'','class'=>'form-control','placeholder'=>' ','maxlength'=>'10', 'pattern'=>'[0-9]{0,10}', 'title'=>'Phone allowed only numeric']) ?>
                        </div>
                    </div>
                </div>
        
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group required">
                            <label>Pincode</label>
                                <?= form_input(['name'=>'pincodePet','value'=>'','class'=>'form-control', 'onkeypress'=>'return isNumberKey(event)',
                                    'id'=>'pincodePet','placeholder'=>'','pattern'=>'[0-9 ]{0,6}','maxlength'=>'6','title'=>'Pincode info allowed only alphanumeric ']) ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group required">
                            <label>Fax No:</label>
                             <?= form_input(['name'=>'petFaxPet','value'=>'','class'=>'form-control', 
                                 'onkeypress'=>'return isNumberKey(event)', 'id'=>'petFaxPet','placeholder'=>'','maxlength'=>'10','title'=>'petFax info allowed only alphanumeric ']) ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group required">
                            <label>Address:</label>
                            <?= form_textarea(['name'=>'petAddressPet','value'=>'','id'=>'petAddressPet','class'=>'form-control','rows' => '2','cols'=>'2','placeholder'=>'','maxlength'=>'400','title'=>'Address Of Appellant allowed only alphanumeric ']) ?>
                        </div>
                    </div>
                </div>
                <input type="button" id="nextsubmit" value="Add" class="btn btn-primary" onclick="Addmoreapplicantbacklog();">		
            </div>

                
            <div class="d-block text-center text-warning">
                <div class="table-responsive text-secondary" id="add_petitioner_list">
                    <span class="fa fa-spinner fa-spin fa-3x" style="display:none"></span>
                    <table id="addmorerecordapp" class="display" border="1" width="100%">
                        <thead>
                            <tr>
                                <th>Sr. No. </th>
                                <th>Appellant Name</th>
                                <th>Mobile</th>
                                <th>Email</th>
                                <th>Address</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                   		 <?php 
                        $additionalparty=$this->efiling_model->data_list_where('additional_party','filing_no',$filing_no); 
                        $i=2;
                        if(!empty($additionalparty)){
                            foreach($additionalparty as $val){
                                if($val->party_flag=='1'){
                                $petName=$val->pet_name;
                                if (is_numeric($val->pet_name)) {
                                    $orgname=$this->efiling_model->data_list_where('master_org','org_id',$val->pet_name);
                                    $petName=$orgname[0]->org_name;
                                }
                            ?>
                            <tr id="trid<?php echo $val->id; ?>">
                            <td><?php echo $i; ?></td>
                                <td><?php echo $petName; ?>(A-<?php echo $i; ?>)</td>
                                <td> <?php echo $val->pet_mobile ?></td>
                                <td><?php echo $val->pet_email ?></td>
                                <td><?php echo $val->pet_address ?></td>
                                <td><input type="button" name="nextsubmit" id="deletedbutton<?php echo $val->id; ?>>" value="Delete" class="btn1"   onclick="deletePartyPet('<?php echo $val->id; ?>','appleant')"></td>
                            </tr>
                            <?php 
                            $i++; } }
                        }else{
                            $val= "<span style='color:Red'>Reccord Not found";
                        }
                             ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
      </div>  
         <!-- -End  Applicant  --> 
  
        <!-- start  Respondent -->
        <div class="card">
          <div id="headingTwo" class="card-header bg-white shadow-sm border-0">
            <h6 class="mb-0 font-weight-bold"><a href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" 
            class="d-block position-relative collapsed">Respondent</a></h6>
          </div>
      		<div id="collapseTwo" aria-labelledby="headingTwo" data-parent="#accordionExample" class="collapse">
        		<div class="card-body">
        			<div class="row">
                        <div class="col-md-4">
                            <div class="form-group required">
                            <label>Respondent Name:<span class="text-danger">*</span></label>
                            <input type="hidden" name="resorgid" value="<?php echo $res_id;?>" id="resorgid" class="txt">
                            <input type="text" name="resName" value="<?php echo $data[0]->res_name;?> "id=resName  class="form-control" onkeypress="searchorg_resp(this.value);">
                            <!-- <input type="text" name="petName" value="<?php echo $data[0]->pet_name?> "id=petName  class="form-control" onkeypress="searchorg(this.value);"> -->
                    		<ul class="autosuggest" id="regnum_autofill_r">
                    		</ul>
                    		  </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group required">
                                <label>State Name:<span class="text-danger">*</span></label>
                                <input type="hidden" name="resstatename" value="" id="resstatename" class="txt">
                                 <?php
                                 $state= $this->efiling_model->data_list('master_psstatus');
                                 $state1[]='- Please Select state-';
                                 foreach ($state as $val)
                                     $state1[$val->state_code] = $val->state_name; 
                                     echo form_dropdown('stateRes',$state1,$data[0]->res_state,['class'=>'form-control','onchange'=>"",'id'=>'stateRes','value'=>'']); 
                                ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Phone Number:</label>
                                <?= form_input(['name'=>'resPhone','value'=>$data[0]->res_phone,'id'=>"resPhone",'onkeypress'=>'return isNumberKey(event)','class'=>'form-control','placeholder'=>'','pattern'=>'[0-9]{0,15}','maxlength'=>'10','title'=>'Phone allowed only numeric']) ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group required">
                                <input type="hidden" name="ddistrictres" value="<?php  echo $res[0]->pet_dist; ?>" id="ddistrictres" class="txt">
                                <label>District:<span class="text-danger">*</span></label>                    
                    	        <?php
                                //  $petdis= '';
                                  //  $city1[]='- Please Select city-';
                                   echo form_dropdown('ddistrictname',$city1,$dist_code_resp
                                   ,['class'=>'form-control','id'=>'ddistrictname']);  ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Email ID:<span class="text-danger">*</span></label>
                                <?= form_input(['name'=>'resEmail','value'=>$data[0]->res_email,'class'=>'form-control','id'=>'resEmail','placeholder'=>'','pattern'=>'[.-@A-Za-z0-9]{1,200}','maxlength'=>'200','title'=>'Email allowed only alphanumeric']) ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group required">
                                <label>Address:</label>
                                <?= form_textarea(['name'=>'resAddress','value'=>$data[0]->res_address,'id'=>'resAddress','class'=>'form-control','rows' => '2','cols'=>'2','placeholder'=>'','pattern'=>'[A-Za-z0-9 ]{4,200}','maxlength'=>'200','title'=>'Address Of Appellant allowed only alphanumeric']) ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group required">
                                <label>Pincode</label>
                                <?= form_input(['name'=>'respincode','value'=>$data[0]->res_pin,'class'=>'form-control','id'=>'respincode','placeholder'=>'', 'onkeypress'=>'return isNumberKey(event)','pattern'=>'[0-9]{0,6}','maxlength'=>'6','title'=>'Pincode info allowed only numeric']) ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group required">
                                <label>Fax No:</label>
                                 <?= form_input(['name'=>'resFax','value'=>$data[0]->res_fax,'class'=>'form-control','id'=>'resFax','placeholder'=>'', 'onkeypress'=>'return isNumberKey(event)','pattern'=>'[0-9 ]{0,12}','maxlength'=>'12','title'=>'petFax info allowed only numeric']) ?>
                            </div>
                        </div>
    
                        <div class="col-md-4">
                            <div class="form-group required">
                                <label>Mobile<span class="text-danger">*</span></label>
                                <?= form_input(['name'=>'resMobile','value'=>$data[0]->res_mobile,'class'=>'form-control','id'=>'resMobile','onkeypress'=>'return isNumberKey(event)','placeholder'=>'','pattern'=>'[0-9]{0,10}','maxlength'=>'10','title'=>'Mobile number should be numeric and 10 digit only.']) ?>
                            </div>
                        </div>
                    </div>
                    <input type="button" name="nextsubmitres" id="nextsubmitres" value="Add Respondent" class="btn btn-primary btn-md" onclick="up_dateres();">       
       			 </div>
            </div>
         </div>   
       <!--End Respondent -->
       
        <!-- start  additional  Respondent -->
        <div class="card">
          <div id="headingadditionalrespondent" class="card-header bg-white shadow-sm border-0">
            <h6 class="mb-0 font-weight-bold"><a href="#" data-toggle="collapse" data-target="#collapseadditoalrespondent" aria-expanded="false" aria-controls="#collapseadditoalrespondent" 
            class="d-block position-relative collapsed">Additional  Respondent Detail</a></h6>
          </div>
      		<div id="collapseadditoalrespondent" aria-labelledby="headingadditionalrespondent" data-parent="#accordionExample" class="collapse">
        		<div class="card-body">
        		
        		   <div class="row alert alert-warning" style="padding: 8px 0px 0px; font-weight: 600;font-size: 18px;  max-width: 100%;">
                            <div class="col-md-4">
                                <label class="text-danger">Select Mode</label>
                            </div>
                            <div class="col-md-6 md-offset-2">
                                <label for="org" class="form-check-label font-weight-semibold">
                                    <?= form_radio(['name'=>'orgshowresRes','id'=>"bd1" ,'value'=>'1','onclick'=>'orgshowresRes();','checked'=>@$ptype1]); ?> Organization&nbsp;&nbsp;
                                </label>
                                <label for="indv" class="form-check-label font-weight-semibold">
                                    <?= form_radio(['name'=>'orgshowresRes','id'=>'po1' ,'value'=>'2' ,'onclick'=>'orgshowresRes();','checked'=>$ptype2]); ?> Individual&nbsp;&nbsp;
                                </label>
                            </div>
                        </div>
                        
        			<div class="row">
                        <div class="col-md-4">
                            <div class="form-group required">
                            <label>Respondent Name:<span class="text-danger">*</span></label>
                            <input type="hidden" name="resorgidRes" value="" id="resorgidRes" class="txt">
                            <input type="text" name="resNameRes" value=" " id="resNameRes"  class="form-control" onkeypress="searchorg_respRes(this.value);">
                    		<ul class="autosuggest" id="regnum_autofill_rRes">
                    		</ul>
                    		  </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group required">
                                <label>State Name:<span class="text-danger">*</span></label>
                                <input type="hidden" name="resstatenameRes" value="" id="resstatenameRes" class="txt">
                                 <?php
                                 $state= $this->efiling_model->data_list('master_psstatus');
                                 $state1[]='- Please Select state-';
                                 foreach ($state as $val)
                                     $state1[$val->state_code] = $val->state_name; 
                                     echo form_dropdown('stateResRes',$state1,'',['class'=>'form-control','onchange'=>"",'id'=>'stateResRes','value'=>'']); 
                                ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Phone Number:</label>
                                <?= form_input(['name'=>'resPhoneRes','value'=>'','id'=>"resPhoneRes",'onkeypress'=>'return isNumberKey(event)','class'=>'form-control','placeholder'=>'','pattern'=>'[0-9]{0,15}','maxlength'=>'10','title'=>'Phone allowed only numeric']) ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group required">
                                <input type="hidden" name="ddistrictresRes" value="" id="ddistrictresRes" class="txt">
                                <label>District:<span class="text-danger">*</span></label>                    
                    	        <?php echo form_dropdown('ddistrictnameRes',$city1,'',['class'=>'form-control','id'=>'ddistrictnameRes']);  ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Email ID:<span class="text-danger">*</span></label>
                                <?= form_input(['name'=>'resEmailRes','value'=>'','class'=>'form-control','id'=>'resEmailRes','placeholder'=>'','pattern'=>'[.-@A-Za-z0-9]{1,200}','maxlength'=>'200','title'=>'Email allowed only alphanumeric']) ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group required">
                                <label>Address:</label>
                                <?= form_textarea(['name'=>'resAddressRes','value'=>'','id'=>'resAddressRes','class'=>'form-control','rows' => '2','cols'=>'2','placeholder'=>'','pattern'=>'[A-Za-z0-9 ]{4,200}','maxlength'=>'200','title'=>'Address Of Appellant allowed only alphanumeric']) ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group required">
                                <label>Pincode</label>
                                <?= form_input(['name'=>'respincodeRes','value'=>'','class'=>'form-control','id'=>'respincodeRes','placeholder'=>'', 'onkeypress'=>'return isNumberKey(event)','pattern'=>'[0-9]{0,6}','maxlength'=>'6','title'=>'Pincode info allowed only numeric']) ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group required">
                                <label>Fax No:</label>
                                 <?= form_input(['name'=>'resFaxRes','value'=>'','class'=>'form-control','id'=>'resFaxRes','placeholder'=>'', 'onkeypress'=>'return isNumberKey(event)','pattern'=>'[0-9 ]{0,12}','maxlength'=>'12','title'=>'petFax info allowed only numeric']) ?>
                            </div>
                        </div>
    
                        <div class="col-md-4">
                            <div class="form-group required">
                                <label>Mobile<span class="text-danger">*</span></label>
                                <?= form_input(['name'=>'resMobileRes','value'=>'','class'=>'form-control','id'=>'resMobileRes','onkeypress'=>'return isNumberKey(event)','placeholder'=>'','pattern'=>'[0-9]{0,10}','maxlength'=>'10','title'=>'Mobile number should be numeric and 10 digit only.']) ?>
                            </div>
                        </div>
                    </div>
                    <input type="button" name="nextsubmitres" id="nextsubmitres" value="Add" class="btn btn-primary btn-md" onclick="up_dateresRes();">       
       			 </div>
        
                        <div class="table-responsive text-secondary" id="add_petitioner_list">
                            <span class="fa fa-spinner fa-spin fa-3x" style="display:none"></span>
                            <table id="addmorerecordapp" class="display" cellspacing="0" border="1" width="100%">
                                <thead>
                                    <tr>
                                        <th>Sr.No.</th>
                                        <th>Respondent Name</th>
                                        <th>Mobile</th>
                                        <th>Email</th>
                                        <th>Address</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                $additionalresparty=$this->efiling_model->data_list_where('additional_party','filing_no',$filing_no);
                                $i=2;
                                if(!empty($additionalresparty)){
                                    foreach($additionalresparty as $val){
                                        if($val->party_flag=='2'){
                                        $resName=$val->pet_name;
                                        if (is_numeric($val->pet_name)) {
                                            $orgname=$this->efiling_model->data_list_where('master_org','org_id',$val->res_name);
                                            $resName=$orgname[0]->orgdisp_name;
                                        }
                                        ?>
                                        <tr id="idvalres<?php echo $val->id; ?>">
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $resName; ?>(R-<?php echo $i; ?>)</td>
                                            <td> <?php echo $val->pet_mobile ?></td>
                                            <td><?php echo $val->pet_email ?></td>
                                            <td><?php echo $val->pet_address ?></td>
                                            <td><input type="button" name="nextsubmit" id="nextsubmit" value="Delete" class="btn1"   onclick="deletePartyRes('<?php echo $val->id; ?>','res')"></td>
                                        </tr>
                                        <?php 
                                        $i++; }
                                    }
                                    }else{
                                        $val= "<span style='color:Red'>Reccord Not found";
                                    }
                                 ?> 
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
         </div>   
         <!--End additional   Respondent -->

        <!-- Accordion item 3 -->
   		<div class="card">
              	<div id="headingThree" class="card-header bg-white shadow-sm border-0">
                	<h6 class="mb-0 font-weight-bold"><a href="#" data-toggle="collapse" data-target="#collapseThreee" aria-expanded="false"  aria-controls="collapseThreee" class="d-block position-relative collapsed ">Counsel</a></h6>
              	</div>
          		<div id="collapseThreee" aria-labelledby="headingThree" data-parent="#accordionExample" class="collapse">
            		<div class="card-body ">
              			<div class="row">
                             <div class="col-md-4 form-group required" id="div-add">
                                <label><span class="text-danger">*</span>Name :</label>
                                <input type="hidden" name="councilCode" value="" id="councilCode" class="txt">
                            	<input type="text" name="advname" value="" id=advname  class="form-control" onkeypress="backserchrecordvaladv(this.value);">
                    			<ul class="autosuggest" id="regnum_autofilladv">
                    			</ul>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Phone Number:</label>
                                    <?= form_input(['name'=>'conPhone','value'=>$data[0]->pet_counsel_phone,'id'=>"conPhone",'onkeypress'=>'return isNumberKey(event)','class'=>'form-control','placeholder'=>'','pattern'=>'[0-9]{0,15}','maxlength'=>'10','title'=>'Phone allowed only numeric']) ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Email ID:<span class="text-danger">*</span></label>
                                    <?= form_input(['name'=>'conEmail','value'=>$data[0]->pet_counsel_email,'class'=>'form-control','id'=>'conEmail','placeholder'=>'','pattern'=>'[.-@A-Za-z0-9]{1,200}','maxlength'=>'200','title'=>'Email allowed only alphanumeric']) ?>
                                </div>
                            </div>
                    
                            <div class="col-md-4">
                                <div class="form-group required">
                                    <label>Address:</label>
                                    <?= form_textarea(['name'=>'conAddress','value'=>$data[0]->pet_counsel_address,'id'=>'conAddress','class'=>'form-control','rows' => '2','cols'=>'2','placeholder'=>'','pattern'=>'[A-Za-z0-9 ]{4,200}','maxlength'=>'200','title'=>'Address Of Appellant allowed only alphanumeric']) ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group required">
                                    <label>Pincode</label>
                                    <?= form_input(['name'=>'conpincode','value'=>$data[0]->pet_counsel_pin,'class'=>'form-control','id'=>'conpincode','placeholder'=>'', 'onkeypress'=>'return isNumberKey(event)','pattern'=>'[0-9]{0,6}','maxlength'=>'6','title'=>'Pincode info allowed only numeric']) ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group required">
                                    <label>Fax No:</label>
                                     <?= form_input(['name'=>'conFax','value'=>$data[0]->pet_counsel_fax,'class'=>'form-control','id'=>'conFax','placeholder'=>'', 'onkeypress'=>'return isNumberKey(event)','pattern'=>'[0-9 ]{0,12}','maxlength'=>'12','title'=>'petFax info allowed only numeric']) ?>
                                </div>
                            </div>
        
                            <div class="col-md-4">
                                <div class="form-group required">
                                    <label>Mobile<span class="text-danger">*</span></label>
                                    <?= form_input(['name'=>'conMobile','value'=>$data[0]->pet_counsel_mobile,'class'=>'form-control','id'=>'conMobile','onkeypress'=>'return isNumberKey(event)','placeholder'=>'','pattern'=>'[0-9]{0,10}','maxlength'=>'10','title'=>'Mobile number should be numeric and 10 digit only.']) ?>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group required">
                                    <label><span class="text-danger">*</span>District</label>   
                                        <?php
                                        $c_pet_district=  $this->efiling_model->data_list('master_advocate','adv_code',$pet_council_adv); 
                                        $cdisrName= $c_pet_district[0]->adv_dist; 
                                        $cpetdis=  $this->efiling_model->data_list_where('master_psdist','district_code',$cdisrName); 
                                        ?>
                                    <input type="hidden" name="cddistrict" readonly="" id="cddistrict" class="txt" maxlength="50" value="<?php echo $c_pet_district[0]->adv_dist; ?>">                                 
                                    <?= form_input(['name'=>'ddistrictname','value'=>$cpetdis[0]->district_name,'class'=>'form-control','id'=>'ddistrictname','placeholder'=>'ddistrictname','maxlength'=>'100', 'readonly' => 'readonly','title'=>'District Name should be Alfa numeric only.']) ?>
                                </div>
                            </div>
                        
                           <div class="col-md-4">
                                <div class="form-group required">
                                    <label>State Name  :</label>
                                    <?php   
                                    $c_pet_state=  $this->efiling_model->data_list('master_advocate','adv_code',$pet_council_adv); 
                                    $statecode= isset($c_pet_state[0]->state_code)?$c_pet_state[0]->state_code:''; 
                                    $pet_state= isset($app[0]->pet_state)?$app[0]->pet_state:''; 
                                    if($statecode=='' && $pet_state!=''){
                                        $statecode= $pet_state;
                                    }
                                    $cstate=  $this->efiling_model->data_list_where('master_psstatus','state_code',$statecode);
                                    ?>
                                    <input type="hidden" name="cdstate" readonly="" id="cdstate" class="txt" maxlength="50" value="<?php echo $cstate[0]->state_code?>">
                                    <?= form_input(['name'=>'dstatename','value'=> $cstate[0]->state_name,'class'=>'form-control', 'readonly' => 'readonly','id'=>'dstatename', 'placeholder'=>'State name','maxlength'=>'5','title'=>' Counsel District name should be alphanumeric.']) ?>
                                </div>
                            </div>
                        
                        </div>
                        <input type="button" name="councelsubmit" id="councelsubmit" value="Add Counsel" class="btn btn-primary btn-md" onclick="up_councel();">
                  	</div>
               </div>
           </div>    
	      <!-- --Councel end -->
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	            
	      <div class="card">
              	<div id="headingThreeadditional" class="card-header bg-white shadow-sm border-0">
                	<h6 class="mb-0 font-weight-bold"><a href="#" data-toggle="collapse" data-target="#collapseThreeeAdditional" aria-expanded="false"  
                	aria-controls="collapseThreeeAdditional" class="d-block position-relative collapsed ">Additional Counsel Detail</a></h6>
              	</div>
          		<div id="collapseThreeeAdditional" aria-labelledby="headingThreeadditional" data-parent="#accordionExample" class="collapse">
            		<div class="card-body ">
              			<div class="row">
                             <div class="col-md-4 form-group required" id="div-add">
                                <label><span class="text-danger">*</span>Name :</label>
                                <input type="hidden" name="councilCodeAA" value="" id="councilCodeAA" class="txt">
                            	<input type="text" name="advnameAA" value="" id="advnameAA"  class="form-control" onkeypress="backserchrecordvaladvAA(this.value);">
                    			<ul class="autosuggest" id="regnum_autofillAA">
                    			</ul>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Phone Number:</label>
                                    <?= form_input(['name'=>'counselPhoneAA','value'=>'','id'=>"counselPhoneAA",'onkeypress'=>'return isNumberKey(event)','class'=>'form-control','placeholder'=>'','pattern'=>'[0-9]{0,15}','maxlength'=>'10','title'=>'Phone allowed only numeric']) ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Email ID:<span class="text-danger">*</span></label>
                                    <?= form_input(['name'=>'counselEmailAA','value'=>'','class'=>'form-control','id'=>'counselEmailAA','placeholder'=>'','pattern'=>'[.-@A-Za-z0-9]{1,200}','maxlength'=>'200','title'=>'Email allowed only alphanumeric']) ?>
                                </div>
                            </div>
                    
                            <div class="col-md-4">
                                <div class="form-group required">
                                    <label>Address:</label>
                                    <?= form_textarea(['name'=>'conAddressAA','value'=>'','id'=>'conAddressAA','class'=>'form-control','rows' => '2','cols'=>'2','placeholder'=>'','pattern'=>'[A-Za-z0-9 ]{4,200}','maxlength'=>'200','title'=>'Address Of Appellant allowed only alphanumeric']) ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group required">
                                    <label>Pincode</label>
                                    <?= form_input(['name'=>'counselPinAA','value'=>'','class'=>'form-control','id'=>'counselPinAA','placeholder'=>'', 'onkeypress'=>'return isNumberKey(event)','pattern'=>'[0-9]{0,6}','maxlength'=>'6','title'=>'Pincode info allowed only numeric']) ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group required">
                                    <label>Fax No:</label>
                                     <?= form_input(['name'=>'counselFaxAA','value'=>'','class'=>'form-control','id'=>'counselFaxAA','placeholder'=>'', 'onkeypress'=>'return isNumberKey(event)','pattern'=>'[0-9 ]{0,12}','maxlength'=>'12','title'=>'petFax info allowed only numeric']) ?>
                                </div>
                            </div>
        
                            <div class="col-md-4">
                                <div class="form-group required">
                                    <label>Mobile<span class="text-danger">*</span></label>
                                    <?= form_input(['name'=>'counselMobileAA','value'=>'','class'=>'form-control','id'=>'counselMobileAA','onkeypress'=>'return isNumberKey(event)','placeholder'=>'','pattern'=>'[0-9]{0,10}','maxlength'=>'10','title'=>'Mobile number should be numeric and 10 digit only.']) ?>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group required">
                                    <label><span class="text-danger">*</span>District</label>   
                                    <input type="hidden" name="cddistrictAA" readonly="" id="cddistrictAA" class="txt" maxlength="50" value="">                                 
                                    <?= form_input(['name'=>'ddistrictnameAA','value'=>'','class'=>'form-control','id'=>'ddistrictnameAA','placeholder'=>'ddistrictname','maxlength'=>'100', 'readonly' => 'readonly','title'=>'District Name should be Alfa numeric only.']) ?>
                                </div>
                            </div>
                        
                           <div class="col-md-4">
                                <div class="form-group required">
                                    <label>State Name  :</label>
                                    <?php   
                                    $c_pet_state=  $this->efiling_model->data_list('master_advocate','adv_code',$pet_council_adv); 
                                    $statecode= isset($c_pet_state[0]->state_code)?$c_pet_state[0]->state_code:''; 
                                    $pet_state= isset($app[0]->pet_state)?$app[0]->pet_state:''; 
                                    if($statecode=='' && $pet_state!=''){
                                        $statecode= $pet_state;
                                    }
                                    $cstate=  $this->efiling_model->data_list_where('master_psstatus','state_code',$statecode);
                                    ?>
                                    <input type="hidden" name="cdstateAA" readonly="" id="cdstateAA" class="txt" maxlength="50" value="">
                                    <?= form_input(['name'=>'dstatenameAA','value'=> '','class'=>'form-control', 'readonly' => 'readonly','id'=>'dstatenameAA', 'placeholder'=>'State name','maxlength'=>'5','title'=>' Counsel District name should be alphanumeric.']) ?>
                                </div>
                            </div>
                        
                        </div>
                        <input type="button" name="councelsubmit" id="councelsubmit" value="Add Counsel" class="btn btn-primary btn-md" onclick="up_councelAA();">
                  	</div>
                  	
                  	<?php  
           $html='';
           $html.='
            <table id="example" class="display" cellspacing="0" border="1" width="100%">
	        <thead>
    	        <tr>
        	        <th>Sr. No. </th>
        	        <th>Name</th>
        	        <th>Registration No.</th>
                    <th>Address</th>
        	        <th>Mobile</th>
        	        <th>Email</th>
        	        <th>Delete</th>
    	        </tr>
	        </thead>
	        <tbody>';
           $advocatelist=$this->efiling_model->data_list_where('additional_advocate','filing_no',$filing_no);
           if(!empty($advocatelist)){
               $i=2;
               foreach($advocatelist as $val){
                   $counseladd=$val->adv_code;
                   $advType=$val->advType;
                   if (is_numeric($val->adv_code)) {
                       $orgname=$this->efiling_model->data_list_where('master_advocate','adv_code',$counseladd);
                       $adv_name=$orgname[0]->adv_name;
                       $adv_reg=$orgname[0]->adv_reg;
                       $address=$val->adv_address;
                       $pin_code=$val->counsel_pin;
                       $counselmobile=$val->adv_mob_no;
                       $counselemail=$val->adv_email;
                       $id=$val->id;
                       if($val->adv_state!=''){
                           $st2 = $this->efiling_model->data_list_where('master_psstatus','state_code',$val->adv_state);
                           $statename= $st2[0]->state_name;
                       }
                       if($val->adv_district!=''){
                           $st1 = $this->efiling_model->data_list_where('master_psdist','district_code',$val->adv_district);
                           $ddistrictname= $st1[0]->district_name;
                       }
                   }
                   $html.='<tr>
            	        <td>'.$i.'</td>
            	        <td>'.$adv_name.'</td>
            	        <td>'.$adv_reg.'</td>
                        <td>'.$address.' '.$ddistrictname.' ('.$statename.')  '.$pin_code.'</td>
            	        <td>'.$counselmobile.'</td>
            	        <td>'.$counselemail.'</td>
                        <td><center><input type="button" name="nextsubmit" id="nextsubmit" value="Delete" class="btn1" onclick="deletePartyadvAA('.$id.')"></center></td>
        	        </tr>';
                   $i++;
               }
           }
           echo $html;
	         ?>
                </tbody></table></div>
               
           </div>    
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
	      
         <!-- --Case Status --> 
	     <div class="card">
          	<div id="heading5" class="card-header bg-white shadow-sm border-0">
            	<h6 class="mb-0 font-weight-bold"><a href="#" data-toggle="collapse" data-target="#collapse5" aria-expanded="false"  aria-controls="collapse5" class="d-block position-relative collapsed ">Case Status</a></h6>
          	</div>
      		<div id="collapse5" aria-labelledby="heading5" data-parent="#accordionExample" class="collapse">
        		<div class="card-body ">
          			<div class="row">
                         <div class="col-md-4">
        					<div required">
        					<label for="name"><span class="custom">status</span>  :<sup class="text-danger">*</sup></label>
    						   <select name="status" id="status" class="form-control" required>
    						   		<option value="P" <?php if($status=='P'){ echo "selected";}?>>Pending</option>
    						   		<option value="D" <?php if($status=='D'){ echo "selected";}?>>Disposed</option>
    						  </select>
        					</div>
        				</div>
            			<div class="col-md-4">
                            <div required">
                                <label>Disposed Date : <sup class="text-danger">*</sup> </label>
                                <input type="text" name="disposeddate" id="disposeddate"  class="form-control datepicker" required="true" maxlength="12"  
                                value="<?php echo $disposel_date; ?>"  onkeypress="return isNumberKey(event)"   onkeypress="serchrecordvalapp(this.value);">
                            </div>
                        </div>
                        <div class="col-md-3">
                        	<input type="button" name="nextsubmit" id="nextsubmit" value="Submit" class="btn btn-primary btn-md" onclick="disposedstatus();">
                        </div>
                    </div>
              	</div>
             </div>
		</div>

		 <!-- --Ma Detail start --> 
            <div class="card">
              	<div id="headingma" class="card-header bg-white shadow-sm border-0">
                	<h6 class="mb-0 font-weight-bold"><a href="#" data-toggle="collapse" data-target="#collapseMA" aria-expanded="false"  
                		aria-controls="collapseMA" class="d-block position-relative collapsed ">MA Detail</a></h6>
              	</div>
              	
          		<div id="collapseMA" aria-labelledby="headingma" data-parent="#accordionExample" class="collapse">
          		 <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"> Add MA</button>
            		<div class="card-body ">
              			<div class="row">
                           	<table width="100%">
                                <thead>
                                    <tr>
                                        <th scope="col">Sr. No</th>
                                        <th scope="col">MA No</th>
                                        <th scope="col">MA Year</th>
                                        <th scope="col">MA Nature</th>
                                        <th scope="col">Status</th>
                                        <th scope="col" style="width: 13%;">Filing Date</th>
                                        <th scope="col" style="width: 26%;">Filed by</th>
                                        <th scope="col" style="width: 26%;">IA Disposel Date</th>
                                        <th scope="col">Delete</th>
                                    </tr>
                                </thead>
                                <?php
                             //   echo "select * from sat_case_detail where filing_no='$filing_no'";die;
                                $query =$this->db->query("select * from sat_case_detail where filing_no='$filing_no'");
                                $row1= $query->result();
                                foreach($row1 as $row){
                                    $petName_namme = $row->pet_name;
                                    $resName_namee = $row->res_name;
                                }
                                
                                $ia_detail_query =$this->db->query("select * from satma_detail where filing_no='$filing_no'");
                                $row1= $ia_detail_query->result();
                                $fgfgfg = 1;
                                if(!empty($row1)){
                                foreach($row1 as $row_iad){
                                    $ia_fil_nom = htmlspecialchars($row_iad->filing_no);
                                    $ia_fil_no_unq = htmlspecialchars($row_iad->ia_filing_no);
                                    $ia_nom = htmlspecialchars($row_iad->ia_no);
                                    $additional_partyqq = htmlspecialchars($row_iad->additional_party);
                                    $ia_yearm = htmlspecialchars($row_iad->entry_date);
                                    $ia_naturecodem = htmlspecialchars($row_iad->ia_nature);
                                    $ia_statusm = htmlspecialchars($row_iad->status);
                                    $ia_fil_datem = htmlspecialchars($row_iad->entry_date);
                                    $filed_bym = htmlspecialchars($row_iad->filed_by);
                                    $filed_bym11 = htmlspecialchars($row_iad->filed_by);
                                    $ia_nature_namem = '';
                                    if ($ia_naturecodem != '' || $ia_naturecodem != NULL) {
                                        if ($ia_naturecodem != 'D') {
                                                $ia_naturem = $this->db->query("select nature_name from moster_ma_nature where nature_code = '$ia_naturecodem'");
                                                $row1= $ia_naturem->result();
                                                foreach($row1 AS $roes){
                                                    $ia_nature_namem = $roes->nature_name;
                                                }
                                        }
                                    }
                                    if ($ia_statusm == 'D') {
                                        $ia_statusnamem = 'Disposal';
                                        $color='red';
                                    } else if ($ia_statusm == 'P') {
                                        $ia_statusnamem = 'Pending';
                                        $color='green';
                                    }
                                    $ia_fil_datem_exp = explode("-", $ia_fil_datem);
                                    $ia_fil_datem_format = $ia_fil_datem_exp[2] . '-' . $ia_fil_datem_exp[1] . '-' . $ia_fil_datem_exp[0];
                                    
                                    $additional_partyqq_name='';
                                   
                                   $hashlcdmod = $ia_fil_no_unq; ?>
                                    <tr id="rowval<?php echo $ia_fil_nom; ?>">
                                        <td scope="row"><?php echo $fgfgfg; ?></td>
                                        <td> <?php echo $ia_nom; ?></td>
                                        <td><?php echo substr($ia_fil_no_unq, 11, 4); ?></td>
                                        <td><?php echo $ia_nature_namem; ?></td>
                                        <td style="color:<?php echo $color; ?>"><?php echo $ia_statusnamem; ?></td>
                                        <td><?php echo $ia_fil_datem_format; ?></td>
                                        <td><?php echo $additional_partyqq_name; ?></td>
                                        <td><?php echo $disdate; ?></td>
                                        <td> <input type="button" class="btn btn-info" value="Delete"  onclick="delete_party_ia('<?php echo $ia_fil_nom; ?>','<?php echo $ia_fil_no_unq; ?>')"></td>
                                    </tr>
                                    <?php
                                    $fgfgfg++; } 
                                } ?>
                            </table>	
                        </div>
                  	</div>
                 </div>
           </div>      
	      <!-- --Ma Detail End --> 
	</div>

<!-- Add MA -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add MA </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
      		<div class="modal-body">
         			<form id="ia_form_id">
                        <div class="col-sm-12 div-padd">
                          	<div class="row">
                                <div class="col-sm-4 div-padd">
                                    <label for="name"><span class="custom">Applicant</span></label>
                                    <input type="radio" onclick="showparty('1','<?php echo $filing_no; ?>')"   name="appAnddef" id="app"  value="<?php echo htmlspecialchars("1"); ?>"/>
                                </div>
                                <div class="col-sm-4 div-padd">
                                    <label for="name"><span class="custom">Respondent</span></label>
                                    <input type="radio" onclick="showparty('2','<?php echo $filing_no; ?>')" name="appAnddef" id="def"  value="<?php echo htmlspecialchars("2"); ?>"/>
                                </div>
                             </div>
                        </div>
						<input type="hidden" name="salt" id="salt" value="<?php echo $filing_no; ?>">
                        <div class="col-sm-12 div-padd">
                            <div><label for="email"><span class="custom"><span><font color="red"></font></span>Additional Party:</span></label></div>
                            <div id="additionla_partyyval"></div>
                        </div>
                        
                        <div class="col-sm-12 div-padd">
                        	<div class="row">
                                 <div class="col-sm-4 div-padd">
                                    <div><label for="email"><span class="custom"><span><font   color="red"></font></span>MA No:</span></label>
                                    </div>
                                    <?php $iaNo = isset($_REQUEST['iaNo']) ? $_REQUEST['iaNo'] : ''; ?>
                                    <div><input type="text" name="iaNo" id="iaNo" class="form-control" onkeypress="return isNumberKey(event)"   value="<?php echo htmlspecialchars(htmlentities($iaNo)); ?>"   maxlength="7"/></div>
                                 </div>
                                 <div class="col-sm-4 div-padd">
                                    <div><label for="email"><span class="custom"><span><font    color="red"></font></span>MA Year:</span></label>
                                    </div>
                                    <?php $iaYear = isset($_REQUEST['iaYear']) ? $_REQUEST['iaYear'] : ''; ?>
                                    <div>
                                        <select name="iaYear" class="form-control select_box" id="iaYear">
                                           <?php
                                                $year='2023';
                                                for ($i= date('Y'); $i >= 2000; $i--) { ?>
                                                <option <?php if ($year == $i) { echo 'selected';} ?> value="<?php echo $i; ?>"><?php echo $i ?></option>     
                                                <?php  } ?>
                                        </select>
                                    </div>
                            	  </div>
                                  <div class="col-sm-4 div-padd">
                                    <div><label for="name"><span class="custom"><font   color="red"></font></span>MA Nature </label></div>
                                    <div>
                                        <select name="ianature" class="form-control select_box" id="ianature"  onchange="openTextBox();">
                                            <?php
                                            $query=$this->db->query("select * from moster_ma_nature order by nature_name asc");
                                            $rowval= $query->result();
                                            foreach ($rowval as  $row) {
                                                print "<option value=" . htmlspecialchars($row->nature_code) . ">" . htmlspecialchars($row->nature_name) . "</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 div-padd">
                       		 <div class="row">
                       		    <div class="col-sm-4 div-padd">
                                    <div><label for="name"><span class="custom"><font  color="red"></font></span>Status</label></div>
                                    <div>
                                        <select name="iastatus" class="form-control select_box" id="iastatus" onchange="iadispasol();">
                                            <option value="P">Pending</option>
                                            <option value="D">Disposal</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4 div-padd">
                                    <div><label for="name"><span class="custom"><font   color="red"></font></span>Filing  Date</label></div>
                                    <div><input type="text" name="iafilingdate" id="iafilingdate"  maxlength="10"    class="form-control datepicker"    value=""/>
                                    </div>
                                </div>
                                <div class="col-sm-4 div-padd" id="iadispasolId1" style="display: none">
                                    <div><label for="name"><span class="custom"><font   color="red"></font></span>Disposal  Date</label></div>
                                    <div><input type="text" name="iadisdate" id="iadisdate"   class="form-control datepicker" maxlength="10" value=""/></div>
                                </div>
                        	 </div>
                        </div>
                        
                         <div class="col-sm-12  div-padd">
                    		<div class="row">
                              
                                <div class="col-sm-6 div-padd" id="matterId" style="display: none">
                                    <div><label for="name"><span class="custom"><font  color="red"></font></span>Matter </label></div>
                                    <div>
                                        <input style="height: 32px; margin-left: 13px; width: 282px;" class="form-control" type="text" name="matter" id="matter">
                                    </div>
                                    <input type="hidden" value="<?php echo $filing_no; ?>" name="filingOn">
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-sm-12 div-padd" id="IADetail" style="display: none"></div>
                </form>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="fn_add_ia_insert()">Save changes</button>
                </div>
          	</div>
          	</div>
    	</div>
      </div>
    </div>
</div>
</div>
	
	
	
	
 <!-- --End Case status --> 

<?= form_close();?>
<script>
$( document ).ready(function() {
    getregulator('<?php echo $apealtyp; ?>');
});


function createdfr(dfr,year,type){
	$.ajax({
		url: base_url+'createdfr',
		type: 'post',
		data: {"dfr":dfr,"year":year,"type":type},
		dataType: 'json',
		beforeSend: function(){
			$('#nextsubmit').find(":selected").text("Feaching Authority.....");
		},
		success: function(resp){
			if(resp.error=='0'){
				var filing=resp.filingno;
				$('#filing_no').val(filing);
				$('#massagecreatedfr').val(resp.massage);
			}else{
				$('#notfound').hide();
				$('#massagecreatedfrssss').text("filing number generated please reload page ");
				$.alert({
					title: '<i class="fa fa-exclamation text-danger"></i>&nbsp;</b>Error</b>',
					content: '<p class="text-danger">'+retrn.massage+'</p>',
					animationSpeed: 200
				});
			}
		},
		error: function(){
			alert('Server error, try later.');
		}
	});
}




function createdfrcasewise(caseno,year,casetype,type){
	$.ajax({
		url: base_url+'createdfrcasewise',
		type: 'post',
		data: {"caseno":caseno,"year":year,"casetype":casetype,"type":type},
		dataType: 'json',
		beforeSend: function(){
			$('#nextsubmit').find(":selected").text("Feaching Authority.....");
		},
		success: function(resp){
			if(resp.error=='0'){
				var filing=resp.filingno;
				$('#filing_no').val(filing);
				$('#massagecreatedfrssss').val(resp.massage);
				$.alert({
					title: '<i class="fa fa-exclamation text-danger"></i>&nbsp;</b>Error</b>',
					content: '<p class="text-danger">'+resp.massage+'</p>',
					animationSpeed: 200
				});
			}else if(resp.error=='1'){
				$('#notfound').hide();
				$('#massagecreatedfrssss').text(resp.massage);
				$.alert({
					title: '<i class="fa fa-exclamation text-danger"></i>&nbsp;</b>Error</b>',
					content: '<p class="text-danger">'+resp.massage+'</p>',
					animationSpeed: 200
				});
			}
		},
		error: function(){
			alert('Server error, try later.');
		}
	});
}


function getregulator(val){
   var appeal_type_id = val;
   var oauth='<?php echo $opauthority; ?>';
   if(appeal_type_id !='') {
  		$.ajax({
  			url: base_url+'getauthority',
  			type: 'post',
  			data: {"appeal_type_id":appeal_type_id},
  			dataType: 'json',
  			beforeSend: function(){
  				$('#order_passing').find(":selected").text("Feaching Authority.....");
  			},
  			success: function(resp){
  				if(resp.error=='0'){
  					$('#order_passing').removeAttr('disabled').empty();
  					$('#order_passing').html('<option value="">-----Select Order Passing Authority-----</option>');
  					$.each(resp.data, function(index, itemData) {
  						var option='<option value="'+itemData.order_pass_auth_id+'">'+itemData.order_passing_authority+'</option>';
  						if(oauth==itemData.order_pass_auth_id){
  							option='<option value="'+itemData.order_pass_auth_id+'" selected="selected">'+itemData.order_passing_authority+'</option>';
  						}
  						$('#order_passing').append(option);
					});
  				}
  				else {
  					$('#order_passing').find(":selected").text("-----Select Order Passing Authority-----");
  					alert(resp.error);
  				}
  			},
  			error: function(){
  				alert('Server error, try later.');
  			}
  		});
  	}
  	else {
  		$('#order_passing').attr('disabled',true).empty();
  		$('#order_passing').html('<option value="">-----Select Order Passing Authority----</option>');
  		return false;
  	}
}


 function orgshowbaklog(val) {
 alert(val);
    if (val == 1) {
        document.getElementById("appagediv").style.display = 'none';
        document.getElementById("appfatherdiv").style.display = 'none';
        document.getElementById("apppandiv").style.display = 'none';
        document.getElementById("apppandividtypeadd").style.display = 'none';
    }
    if (val == 2) {
        document.getElementById("appagediv").style.display = 'block';
        document.getElementById("appfatherdiv").style.display = 'block';
        document.getElementById("apppandiv").style.display = 'block';
        document.getElementById("apppandividtypeadd").style.display = 'block';
    }
} 

</script>        
<?php $this->load->view("admin/footer"); ?>		