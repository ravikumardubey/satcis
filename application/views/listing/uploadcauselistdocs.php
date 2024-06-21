<?php $this->load->view("admin/header"); ?>
<?php $this->load->view("admin/sidebar"); 
foreach($benchid as $row){
    $listingdate=isset($row->from_list_date)?$row->from_list_date:'';
    $listingdate=date('d-m-Y',strtotime($listingdate));
    $court_no=$row->court_no;
    $benchid=$row->id;
}
?>
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="content">
<?= form_open(false,['class'=>'wizard-form steps-basic wizard clearfix',
    'name'=>'caselistingsub','id'=>'caselistingsub','autocomplete'=>'off',]) ?>
<div class="row">
<input type="hidden" name="benchid" id="benchid" value="<?php echo $benchid; ?>">
<input type="hidden" name="courtno" id="courtno" value="<?php echo $court_no; ?>">
    <div class="col-lg-12">
 		<div class="card">
    		<div class="row" id="myDIV1" >
    		    <div class="col-md-3">
                    <div class="form-card">
                         <div class="form-group">
                          	<label class="control-label" for="case_type_lower"><span class="custom"><font color="red">*</font></span>Listing Date:</label>
                          	<div class="input-group mb-3 mb-md-0">
               					<?= form_input(['name'=>'listingdatre','class'=>'form-control','id'=>'listingdatre','value'=>$listingdate,'display'=>true,'readonly'=>true]) ?>
                          	</div>
                         </div>
                     </div>
                 </div> 
                 
                <div class="mb-3">
                  <label for="formFile" class="form-label"><span class="custom"><font color="red">*</font></span><b></>Select Files</b></label>
                  <input class="form-control" type="file" id="browse_file" name="browse_file">
                </div>
                 
                  <div class="col-md-3">
                    <div class="form-card">
                         <div class="form-group" style="margin-top:27px;">
                          	<button type="button"  value="submit" class="btn btn-primary" onclick="uploadcauselist();">Upload</button>
                         </div>
                     </div>
                 </div> 
            </div>
        </div>
	</div>
</div>  
</div>    
<?= form_close();?>  
<script>
function uploadcauselist(){
 	var file_data =  $('#browse_file')[0].files[0];   
    var form_data = new FormData();                  
	var enterdate =  $("#listingdatre").val();
	var benchid = $("#benchid").val();
	var courtno = $("#courtno").val();
	if(enterdate==''){
		alert("Please enter date");
		return false;
	}
	if(benchid==''){
		alert("Please enter valid bench id");
		return false;
	}
	if(courtno==''){
		alert("Please enter court number");
		return false;
	}
	var oldfile= $("#oldfile").val();
	form_data.append('file', file_data);
	form_data.append('enterdate', enterdate);
	form_data.append('court_no', courtno);
	form_data.append('benchid', benchid);
	form_data.append('schemas', '<?php echo 'delhi';?>');
	form_data.append('action', 'uploadcauselist');
	$.ajax({
        type: "POST",
        url: base_url+'ajaxuploadcauselist',
        data: form_data,
        dataType: 'text', 
        cache: false,
        contentType: false,
        processData: false,
        success: function (resp) {
                var resp = JSON.parse(resp);
            	if(resp.data=='success') {
        		    setTimeout(function(){
                        window.location.href = base_url+'causelist_upload';
                     }, 250);
    			}
    			else if(resp.data == 'error') {
    				$.alert(resp.display);
    			}
			}
        });
}
</script>
<?php $this->load->view("admin/footer"); ?>		
