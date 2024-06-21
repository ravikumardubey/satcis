<?php $this->load->view("admin/header"); ?>
<?php $this->load->view("admin/sidebar"); 
$listingdate=isset($_REQUEST['listingdatre'])?$_REQUEST['listingdatre']:date('d/m/Y');
?>
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="content">
 <?= form_open(false,['class'=>'wizard-form steps-basic wizard clearfix','name'=>'caselistingsub','id'=>'caselistingsub','autocomplete'=>'off']) ?>
   <div class="row">
        <div class="col-lg-12">
     		<div class="card">
        		<div class="row" id="myDIV1" >
        		    <div class="col-md-3">
                        <div class="form-card">
                             <div class="form-group">
                              	<label class="control-label" for="case_type_lower"><span class="custom"><font color="red">*</font></span>Listing Date:</label>
                              	<div class="input-group mb-3 mb-md-0">
                   					<?= form_input(['name'=>'listingdatre','class'=>'form-control datepicker','id'=>'listingdatre','value'=>$listingdate,'display'=>true]) ?>
                              	</div>
                             </div>
                         </div>
                     </div> 
                      <div class="col-md-3">
                        <div class="form-card">
                             <div class="form-group" style="margin-top:27px;">
                              	<button type="button" class="btn btn-primary" onclick="serchDFR();">Submit</button>
                             </div>
                         </div>
                     </div> 
                </div>
                <a target="_blank" href="<?php echo base_url(); ?>draftcauselist/<?php echo $listingdate; ?>">View Cause lisr date of <?php  echo date('d/m/Y',strtotime($listingdate)); ?>
            </div>
            
   		</div>
	</div> 
	<?= form_close();?>   
</div>  
     
<?php $this->load->view("admin/footer"); ?>		
<script>
function serchDFR(){
	with(document.caselistingsub){
	action = base_url+"cause_list";
	submit();
    	document.frm.submit11.disabled = true;  
     	document.frm.submit11.value = 'Please Wait...';  
     	return true;
	}
}
</script>