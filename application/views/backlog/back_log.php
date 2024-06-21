<?php $this->load->view("admin/header"); ?>
<?php $this->load->view("admin/sidebar"); ?>
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>
<script>
function submitForm1()
{
	//alert('hjjhj'); //die;
 	with(document.frm)
	{
	
	 action ="<?php echo base_url() ?>backlogsave";
	submit();
	}
}
 
function getOption(id) {
       
	   if(id=="P")
	   { $("#disp").show();   }
	 //  { $("#disp").hide();   }
    if(id=="D")
	   { $("#disp").hide();   }
	   
    }

$(document).on('change','#stateRes',function(e){ console.log('asdsa');
 /////respondent
   // var state_id = sel.options[sel.selectedIndex].value;
    if ($('#stateRes option:selected').val() > 0) {
        $.ajax({
            type: "POST",
            url: base_url+"district",
            data: "state_id=" +$('#stateRes option:selected').val(),
            cache: false,
            success: function (districtdata) {
                $("#ddistrictname").html(districtdata);
            }
        });
    }
});
</script>


<?= form_open(false,['class'=>'wizard-form steps-basic wizard clearfix','name'=>'frm','autocomplete'=>'off']) ?>

			
        	
<div class="content" >
  <div class="row" <?=$this->session->userdata('success');?>>
    <div class="col-lg-12 mx-auto">
      <!-- Accordion -->
      <div id="accordionExample" class="accordion shadow">

        <!-- Accordion item 1 -->
        
        <div class="card">
          <div id="headingFour" class="card-header bg-white shadow-sm border-0">
            <h6 class="mb-0 font-weight-bold"><a href="#" data-toggle="collapse" data-target="#collapseFour" aria-expanded="true" 
            aria-controls="collapseFour" class="d-block position-relative collapsed ">Serch Case No.</a></h6>
          </div>
          <div id="collapseFour" aria-labelledby="headingFour" data-parent="#accordionExample" class="collapse show">
            <div class="card-body">
                  		
                 <?php 
                 error_reporting(0);
                 //$casetype=isset($_REQUEST['case_type'])?$_REQUEST['case_type']:'';
                 $casetype=$this->input->post('case_type')?$this->input->post('case_type'):'';
                 $caseno=$this->input->post('case_no')?$this->input->post('case_no'):'';
                 $caseyear=$this->input->post('year')?$this->input->post('year'):'';
                 ?>
        		 <div class="row" id="myDIV1" style="display: <?php  ?>;">
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
                               //  $year1=array();
                                // $year = $dfryear;
                                 $curryear=date('Y');
                                 $year1=[''=>'- Select Year -'];
                                 for ($i = $curryear; $i > 2000 ; $i--) {
                                     $year1[$i]=$i;
                                 }
                                  echo form_dropdown('case_year',$year1,set_value('case_year',(isset($case_year))?$case_year:'',false),['class'=>'form-control','id'=>'year','required'=>'true']); 
                                 ?>
                              	</div>
                              </div>
                         </div>
        			  </div>
        			   <div class="col-md-3">
                         <div class="form-group required" style="margin-top: 29px;">
                            <input type="submit" name="submit" value="SEARCH" class="btn btn-success" id="nextsubmit" onclick="serchDFR();">
                         </div>
        			  </div>
					 
					<!--div class="col-md-3">
						<p align="center"><font face="Verdana" color="red" size="2"><b><?php //echo $this->session->userdata('success'); ?></b></font></td>
					</div-->
					  
        			</div>		
           		 </div>
           		
         	 </div>
        </div>
        
		<?php if(!empty($caseDetails)) 
		{ echo "<div class=col-md-5 align=right><font color=red><b>record already exists</b></font></div>"; }
		if($this->input->Post() && empty($caseDetails))
		{
        //print_r($data);
        ?>

        
      
    	<div class="card">
          <div id="headingOne" class="card-header bg-white shadow-sm border-0">
            <h6 class="mb-0 font-weight-bold"><a href="#" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false "
             aria-controls="collapseOne" class="d-block position-relativ">Basic Details</a></h6>
          </div>
          <div id="collapseOne" aria-labelledby="headingOne" data-parent="#accordionExample" class="collapse ">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group required">
                            <label>Impugned Order No<span class="text-danger">*</span></label>
                            <input type="hidden" name="orgid" value="" id="orgid" class="txt">
                            <input type="text" name="iordernumber" value="" id="iordernumber"  class="form-control" >
                    		<ul class="autosuggest" id="regnum_autofill">
                    		</ul>
                            <div class="col-md-9" id="recordadv"></div>
                        </div>
                    </div>
            
					<div class="col-md-4">
                        <div class="form-group">
                            <label><span class="custom"><font color="red">*</font></span>Impugned Order Date</label>
                            <?= form_input(['name'=>'iorderdate','class'=>'datepicker form-control','value'=>'','id'=>'iorderdate','placeholder'=>'']) ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group required">
                            <label><font color="red">*</font></span>Received Impugned Order</label>
                            <?= form_input(['name'=>'rimpugnedorder','value'=>'','class'=>'datepicker form-control', 'onkeypress'=>'return isNumberKey(event)','id'=>'rimpugnedorder','placeholder'=>'','title'=>'Mobile number should be numeric and 10 digit only.']) ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label><font color="red">*</font></span>Date of Filing:</label>
                            <?= form_input(['name'=>'dt_of_filing','id'=>"dt_of_filing",'onkeypress'=>'return isNumberKey(event)','value'=>'','class'=>'datepicker form-control','placeholder'=>' ' ]) ?>
                        </div>
					</div>
					<div class="col-md-4">
                        <div class="form-group">
                            <label><font color="red">*</font></span>Registration Date:</label>
                            <?= form_input(['name'=>'regis_date','id'=>"regis_date",'onkeypress'=>'return isNumberKey(event)','value'=>'','class'=>'datepicker form-control','placeholder'=>' ']) ?>
                        </div>
					</div>
                    <div class="col-md-4">
                        <div class="form-group required">
                            <label><font color="red">*</font></span>Penalty Imposed</label>
                                <?= form_input(['name'=>'ipenalty','value'=>'','class'=>'form-control', 'id'=>'ipenalty','placeholder'=>'','pattern'=>'[0-9 ]{0,6}','maxlength'=>'6','title'=>'Pincode info allowed only alphanumeric ']) ?>
                        </div>
                    </div>
                </div>
        
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group required">
                            <label><font color="red">*</font></span>Status</label>
							
                                <?php 
								  
								$status1= array(''=>'SELECT','P'=>'Pending','D'=>'Disposed');		 ?>
		<?=form_dropdown('status',$status1,set_value('status',(isset($status))?$status:''),['class'=>'form-control','onchange'=>"getOption(this.value);",'id'=>'select1','pattern'=>'[P,D]','maxlength'=>'1','title'=>'Status must be D or P']);  ?>
							
								
					   </div>
                    </div>
					
					
                    <!--div id="disp" class="col-md-4" style="display:none;"--> 
                    <div  class="col-md-4" >
                        <div class="form-group required">
                            <label><font color="red">*</font></span>Hearing/Disposal date</label>
                             <?= form_input(['name'=>'disposel_date','value'=>'','class'=>'datepicker form-control', 'id'=>'disposel_date','placeholder'=>'','maxlength'=>'10','title'=>'petFax info allowed only alphanumeric ']) ?>
                        </div>
                    </div>
					
					
                </div>
				
				<div id="disp" class="row" style="display:none;">
				<div  class="col-md-4" >
                        <div class="form-group required">
                            <label><font color="red">*</font></span>Bench Nature</label>
                      <?php      $bench1[]='SELECT';
					  $bench= $this->efiling_model->data_list('bench_nature');
					  //print_r($bench);
					foreach($bench as $row)
					$bench1[$row->bench_code] = $row->bench_name;  ?>
					
					<?=form_dropdown('bench_nature',$bench1,set_value('bench_nature',(isset($bench_nature))?$bench_nature:'',false),['class'=>'form-control','required'=>true,'onChange'=>'javascript:submitForm();','required'=>'required','id'=>'test']);   ?>
					  </div>
                    </div>
				<div  class="col-md-4" >
                        <div class="form-group required">
                            <label><font color="red">*</font></span>Purpose</label>
                             <?php 
			
				$purpose1[]='SELECT';
				$purposeArray= $this->efiling_model->data_list('master_purpose',['display'=>true]);
				foreach($purposeArray as $row)
				$purpose1[$row->purpose_code]= $row->purpose_name; 
				echo form_dropdown('purpose',$purpose1,set_value('purpose',(isset($purpose))?$purpose:'',false),['class'=>'form-control','required'=>'required']);
			?> </div>
                    </div>
				
				<div  class="col-md-4">
                        <div class="form-group required">
                            <label><font color="red">*</font></span>Court No.</label>
							 <?= form_input(['name'=>'court_no','value'=>'','class'=>'form-control', 'id'=>'court_no','placeholder'=>'','pattern'=>'[0-9 ]{0,6}','maxlength'=>'1','title'=>'Pincode info allowed only number ']) ?>
                      				
					   </div>
                    </div>
				</div>
                <!--input type="button" id="nextsubmit" value="Add Details" class="btn btn-primary" onclick="addMoreApp();"-->		
            </div>
          </div>
        </div>

 <!-- Accordion item 2 -->		
        <div class="card">
		<div id="headingTwo" class="card-header bg-white shadow-sm border-0">
            <h6 class="mb-0 font-weight-bold"><a href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" 
            class="d-block position-relative collapsed">Appellant Details</a></h6>
          </div>
          
          <div id="collapseTwo" aria-labelledby="headingTwo" data-parent="#accordionExample" class="collapse">
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
            
					<div class="col-md-4">
                        <div class="form-group required">
                            <label>State Name :<span class="text-danger">*</span></label>
                           <?php 
    
                          $state= $this->efiling_model->data_list('master_psstatus');
                          $state1[]='- Please Select state-';
                          foreach ($state as $val)
                              $state1[$val->state_code] = $val->state_name;  
                              echo form_dropdown('pet_state',$state1,set_value('pet_state',(isset($pet_state))?$pet_state:''),['class'=>'form-control','onchange'=>"showCity(this);",'id'=>'pet_state']);  ?>
							
                        </div>
                    </div>
                    
                      <div class="col-md-4">
                        <div class="form-group required">
                            <!--input type="hidden" name="petdistrictname" value="" id="petdistrictname" class="txt"-->
                            <label>District:<span class="text-danger">*</span></label>                    
                	        <?php  $pet_state= set_value('pet_state'); //die;
						
								  $city1[]='- Please Select city-';
                               echo form_dropdown('ddistrict',$city1,set_value('ddistrict',(isset($ddistrict))?$ddistrict:''),['class'=>'form-control','id'=>'ddistrict']);  ?>
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
				<div class="row">
				<div class="col-md-4">
                        <div class="form-group required">
                            <label>Pet Advocate Name :<span class="text-danger">*</span></label>
                           <?php 
    
                          $adv= $this->efiling_model->data_list('master_advocate');
                          $adv1[]='- Please Select advocate-';
                          foreach ($adv as $val)
                              $adv1[$val->adv_code] = $val->adv_name;  
                              echo form_dropdown('pet_adv',$adv1,set_value('pet_adv',(isset($pet_adv))?$pet_adv:''),['class'=>'form-control','id'=>'pet_adv']);  ?>
							
                        </div>
                    </div>
					</div>
                <!--input type="button" id="nextsubmit" value="Add Appellant" class="btn btn-primary" onclick="addMoreApp();"-->		
            </div>
          </div>
        </div>

        <!-- Accordion item 3 -->
        <div class="card">
          <div id="headingThree" class="card-header bg-white shadow-sm border-0">
            <h6 class="mb-0 font-weight-bold"><a href="#" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree" 
            class="d-block position-relative collapsed">Respondent Details</a></h6>
          </div>
          <div id="collapseThree" aria-labelledby="headingThree" data-parent="#accordionExample" class="collapse">
            <div class="card-body">
				<div class="row">
                    <div class="col-md-4">
                        <div class="form-group required">
                            <label>Respondent Name<span class="text-danger">*</span></label>
                            <input type="hidden" name="orgid" value="" id="orgid" class="txt">
                            <input type="text" name="resName" value="" id=resName  class="form-control" onkeypress="serchrecordvalapp(this.value);">
                    		<ul class="autosuggest" id="regnum_autofill">
                    		</ul>
                            <div class="col-md-9" id="recordadv"></div>
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
                              echo form_dropdown('stateRes',$state1,set_value('stateRes',(isset($stateRes))?$stateRes:''),['class'=>'form-control','id'=>'stateRes']);  ?>
							
                        </div>
                    </div>
                    
                      <div class="col-md-4">
                        <div class="form-group required">
                            <input type="hidden" name="petdistrictname" value="" id="petdistrictname" class="txt">
                            <label>District:<span class="text-danger">*</span></label>                    
                	        <?php  $pet_state= set_value('pet_state'); //die;
						
								  $city1[]='- Please Select city-';
                               echo form_dropdown('ddistrictname',$city1,set_value('ddistrictname',(isset($ddistrictname))?$ddistrictname:''),['class'=>'form-control','id'=>'ddistrictname']);  ?>
                        </div>
                    </div>
            	
                     <div class="col-md-4">
                        <div class="form-group">
                            <label><span class="custom"><font color="red">*</font></span>Email ID:</label>
                            <?= form_input(['name'=>'resEmail','class'=>'form-control','value'=>'','id'=>'resEmail','placeholder'=>'']) ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group required">
                            <label><font color="red">*</font></span>Mobile</label>
                            <?= form_input(['name'=>'resmobile','value'=>'','class'=>'form-control', 'onkeypress'=>'return isNumberKey(event)','id'=>'resmobile','placeholder'=>'','pattern'=>'[0-9]{10,10}','maxlength'=>'10','title'=>'Mobile number should be numeric and 10 digit only.']) ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Phone Number:</label>
                            <?= form_input(['name'=>'resPhone','id'=>"resPhone",'onkeypress'=>'return isNumberKey(event)','value'=>'','class'=>'form-control','placeholder'=>' ','maxlength'=>'10', 'pattern'=>'[0-9]{0,10}', 'title'=>'Phone allowed only numeric']) ?>
                        </div>
                    </div>
                </div>
				<div class="row">
                    <div class="col-md-4">
                        <div class="form-group required">
                            <label>Pincode</label>
                                <?= form_input(['name'=>'respincode','value'=>'','class'=>'form-control', 'onkeypress'=>'return isNumberKey(event)',
                                    'id'=>'respincode','placeholder'=>'','pattern'=>'[0-9 ]{0,6}','maxlength'=>'6','title'=>'Pincode info allowed only alphanumeric ']) ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group required">
                            <label>Fax No:</label>
                             <?= form_input(['name'=>'resFax','value'=>'','class'=>'form-control', 
                                 'onkeypress'=>'return isNumberKey(event)', 'id'=>'resFax','placeholder'=>'','maxlength'=>'10','title'=>'petFax info allowed only alphanumeric ']) ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group required">
                            <label>Address:</label>
                            <?= form_textarea(['name'=>'resAddress','value'=>'','id'=>'resAddress','class'=>'form-control','rows' => '2','cols'=>'2','placeholder'=>'','maxlength'=>'400','title'=>'Address Of Appellant allowed only alphanumeric ']) ?>
                        </div>
                    </div>
                </div>
				<div class="row">
				<div class="col-md-4">
                        <div class="form-group required">
                            <label>Res Advocate Name :<span class="text-danger">*</span></label>
                           <?php 
    
                          $adv= $this->efiling_model->data_list('master_advocate');
                          $adv1[]='- Please Select advocate-';
                          foreach ($adv as $val)
                              $adv1[$val->adv_code] = $val->adv_name;  
                              echo form_dropdown('res_adv',$adv1,set_value('res_adv',(isset($res_adv))?$res_adv:''),['class'=>'form-control','id'=>'res_adv']);  ?>
							
                        </div>
                    </div>
				</div>
            </div>
          </div>
        </div>

        <!-- Accordion item 3 -->
        <!--div class="card">
          <div id="headingFour" class="card-header bg-white shadow-sm border-0">
            <h6 class="mb-0 font-weight-bold"><a href="#" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" 
            aria-controls="collapseFour" class="d-block position-relative collapsed ">Councel</a></h6>
          </div>
          <div id="collapseFour" aria-labelledby="headingFour" data-parent="#accordionExample" class="collapse">
            <div class="card-body p-5">
              <p class="font-weight-light m-0">Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et.</p>
            </div>
          </div>
        </div-->
		<div class="col-md-3">
                         <div class="form-group required" style="margin-top: 29px;">
                            <input type="Submit"  value="Submit" class="btn btn-success" id="nextsubmit" onclick="submitForm1();">
                         </div>
        			  </div>
		<?php } ?>
      </div>
    </div>
  </div>
  </div>

<?= form_close();?>

  <script>

$(document).ready(function() {
	var msg = <?php echo "'".$this->session->userdata('Success')."'"; ?>;
	if(msg !== ''){
			swal(
				'Success',
				msg,
				'success'
				)
	}
});
</script>
<?php $this->session->unset_userdata('Success');?>
<script>
var date = $('.datepicker').datepicker({ dateFormat: 'dd-mm-yy' }).val();

function validateEmail(email) {
var re =/^[a-z_A-Z\-0-9\.\*\#\$\!\~\%\^\&\-\+\?\|]+@+[a-zA-Z\-0-9]+(.com)$/;  
return re.test(email); 
}

</script>
        
<?php $this->load->view("admin/footer"); ?>		