<?php $this->load->view("admin/header"); ?>
<?php $this->load->view("admin/sidebar"); ?>
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php

$retval= base64_decode($msg);
$retunvakex=explode('/', $retval);

$fil_no = isset($filing_no)?$filing_no:$retunvakex[0];

$msg=isset($retunvakex[1])?$retunvakex[1]:'';
$msg2=isset($retunvakex[2])?$retunvakex[2]:'';

$msg1=isset($retunvakex[0])?$retunvakex[0]:'';

$query =$this->db->query("select * from sat_case_detail where filing_no = '$fil_no'  and (case_no is null or case_no  = '') ");
$rowval= $query->result();
if(!empty($rowval)){
    $filing_no = htmlspecialchars($rowval[0]->filing_no);
    $pet_name = htmlspecialchars($rowval[0]->pet_name);
    $res_name = htmlspecialchars($rowval[0]->res_name);
    $dt_of_filing = htmlspecialchars($rowval[0]->dt_of_filing);
    $case_type = htmlspecialchars($rowval[0]->case_type);
    $case_no = $rowval[0]->case_no;
    $oa_ref_no = $rowval[0]->oa_ref_no;
}


$rr=explode('-',$msg2);
$casenova='';

if(!empty($rr) && $rr[2]!=''){
	$casenova=$rr[0].' APPEAL '.ltrim($rr[1],'0').'/'.$rr[2];
}

//echo $filing_no;die;


if ($fil_no != '') {
?>
 



<form name="form2" method="post" action="<?php echo base_url();?>scrutinyaction">
<div class="content" >
    <div class="col-sm-12 ">
    <fieldset>
        <legend class="customlavel2">Applicant And Defendant Details</legend>
        <?php if ($filing_no == '') {?>
            <div><label for="filingandcase"><span class="custom">
            <font color="#0000FF"><?php echo htmlspecialchars("This Diary Number and Year Is Not Valid OR Already Taken Appeal No"); ?></font></span></label>
            </div>
        <?php }
            if ($filing_no != '') {?>   
        	<div>
           		 <label for="filingandcase">
           		 	<span class="custom">
                    <font  color="#0000FF">
                        <?php     if ($pet_name != '') {     echo htmlspecialchars($pet_name) . $this->efiling_model->fn_addition_party($filing_no, '1');     }    ?>
                    </font>
                	<font color="#FF0000">&nbsp;&nbsp;Vs&nbsp;&nbsp;</font>
                    <font  color="#0000FF">
                   		<?php 	 if ($res_name != '') {   echo htmlspecialchars($res_name) . $this->efiling_model->fn_addition_party($filing_no, '2'); }  ?>
                    </font>
                    </span>
                </label>
            </div>
            <div>
                <span class="custom">
               		 <font  color="#0000FF"> <?php echo htmlspecialchars("Date of filing :  $dt_of_filing"); ?></font>
                </span> 
            </div>
            <div>
                <span class="custom">
                	<font color="#0000FF"> <?php echo htmlspecialchars($statusName); ?></font>
                </span>
            </div>
        <?php }
        }?>
        </fieldset>
    </div>
    <center><span style="color:red"><h3><?php if(htmlspecialchars($msg)!=''){ echo htmlspecialchars($msg).' '.$casenova; } ?><h3></span></center>
    <?php    
    $userdata=$this->session->userdata('login_success');
    $userid=$userdata[0]->id;
    $warr=array('filing_no'=>$filing_no,'display'=>'Y');
    $docData =$this->efiling_model->list_uploaded_docs('efile_documents_upload',$warr); 

    ?>
	<table border="0" width="100%">
        <thead>
            <tr>
                <th class="tbl-accordion-section" style="background-color:#444444;">
                    <div class="row" style="padding:10px;">
                        <div class="col-md-6" style="color:#fff;padding-top:8px;">  Documents </div> 
                        <div class="col-md-6 text-right">
                            <a class="btn btn-primary text-white" >Documents (Total Document :- <?php echo count($docData); ?>)</a>
                        </div>
                    </div>
                </th>
            </tr>
            <tr>
                <td class="row docrow">
                    <?php if(!empty($docData)){
                        foreach($docData as $row){?>
                            <div class="col-md-4 doclist"><a onclick="viewAllDocs('<?php echo $row->id; ?>')" style="cursor: pointer"><i class="fa fa-angle-double-right"></i>
                                <?php echo $row->document_type; ?> (1)</a>
                            </div>
                            <input type="hidden" name="filenameval" id="filenameid<?php echo $row->id; ?>" value="<?php echo $row->file_url; ?>">
                    <?php }
                    } ?>                        
                </td>
            </tr>
        </thead>
    </table>
  <br>  
            
  
    <div class="col-sm-12 div-padd">
    <div class="main">
		<table class="table table-bordered" style="flex: 1;">
          <thead>
            <tr>
              <th scope="col" class="bg-dark">#</th>
              <th scope="col" class="bg-dark">Objection</th>
              <th scope="col" class="bg-dark">Is Defect ?</th>
              <th scope="col" class="bg-dark">Remark</th>
            </tr>
          </thead>
          <?php 
          $obj =$this->db->query("select * from master_objection");
          $objval= $obj->result();
          if(!empty($objval)){
              $i=1;
              foreach($objval as $row){
                  
                  $warr=array('objection_code'=>$row->id,'filing_no'=>$filing_no);
                  $obj =$this->efiling_model->data_list_mulwhere('objection_details',$warr); 
//echo "<pre>";print_r($obj);die;

          ?>
          <tbody>
            <tr>
              <td scope="row"  style="width:10px"><?php echo  $i; ?></td>
              <td style="width:110px"><?php echo  $row->objection_name; ?></td>
              <td style="width:60px">
                <select name="status[]" style="width:200px;height: 36px;" class="form-control">
                    <option value="Yes" <?php if($obj[0]->status=='Yes'){ echo "selected";}?>>Yes</option>
                    <option value="No"  <?php if($obj[0]->status=='No'){ echo "selected";}?>>No</option>
                </select>
               </td>
              	<td style="width:160px">
                  	<textarea name="comment[]"  class="form-control" name="w3review" rows="1" cols="20">
                  	<?php echo $obj[0]->comments;?>
                    </textarea>
                    <input type="hidden" name="id_check[]" maxlength="3" readonly="readonly" value="<?php echo $row->id; ?>" size="2">
                    <input type="hidden" name="filing_no" maxlength="3" readonly="readonly" value="<?php echo $filing_no; ?>" size="2">
             	</td>
             	
              </tr>
            <?php  $i++;}
            } ?>
           <div class="modal fade" id="updPdf" role="dialog">
                <div class="modal-dialog modal-dialog-centered" style="min-width: 90%;">
                    <div class="modal-content">
                        <div class="modal-header">
                        </div>
                        <div class="modal-body">                   
                            <iframe style="width: 100%; height: 560px" id="frameID" src=""></iframe>
                        </div>
                    </div>
                </div>
            </div>
            
          </tbody>
        </table>
		<div class="docviewer d-none">
            <div class="pdfviewer viewerWindow2">
                <div class="header">
                    <span class="title">Document Viewer</span>
                    <i class="fa fa-close closeBtn" onclick="closePdfviewer();"></i>
                </div>

                <div class="body">
                    <div class="frameWindow" id="frameWindow1">
                        <div class="pdfThumbWrap"> 
							<div class="pdfThumbItem" id="Form-L1" style="height: 100%;">  
							<div class="header">
								<div class="title">Form-L</div>
							</div>
							<iframe id="viewdoc" src=""></iframe></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>



        
        
        <div class="col-sm-4 div-padd,"><h3>Enable/Disable Tab For Refile</h3></div>
     	<table class="table">
          <thead>
            <tr>
              <th scope="col">Sr.No</th>
              <th scope="col">Tab Name</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
          <?php
          $obj =$this->db->query("select * from defecttab_master order by id asc");
          $i=1;
          $objval= $obj->result();
          if(!empty($objval)){
              foreach($objval as $row){
              $disabled='';
              if($row->tab_no=='5' || $row->tab_no=='6'  ||  $row->tab_no=='8'){
                  $disabled="disabled";
              }
              ?>
            <tr>
              <th scope="row"><?php echo $i; ?></th>
              <td><?php echo $row->name; ?> </td>
              <td><input type="checkbox" class="form-check-input" name="tabval[]" value="<?php echo $row->id; ?>" id="tabval" checked="checked" <?php echo $disabled; ?>> </td>
            </tr>
           <?php $i++; } 
          }?> 
          </tbody>
        </table>
                            
                            
        
        <div class="col-sm-12">
        	<div class="row">
                <div class="col-sm-4">
                    <p>
                        <label for="in_notification_date">Notification Date:</label>
                        <input id="in_notification_date" type="text" name="notification_date" class="form-control datepicker" 
                        readonly="readonly" value="<?php echo date('d-m-Y'); ?>" size="6"  maxlength="10" onkeyup="javascript:addNumbers1(this.value)">
                    </p>
                </div>
                <div class="col-sm-4">
                    <p><label for="in_searchby">Status:</label>
                        <select id="in_searchby" name="searchby" class="form-control" onchange="fn_new_date_hearin(this.value)">
                            <option value="">select</option>
                            <option value="1" selected="">DEFECTIVE  </option>
                            <option value="2">DEFECT FREE with Registration
                            </option><option value="3">DEFECT FREE without Registration  </option>
                        </select>
                    </p>
                </div>
           </div>
        </div>
    </div> 
	<div style="float:right">    
    <button type="button" class="btn btn-danger">Cancel</button>
    <button type="submit" class="btn btn-success" name="defectsubmit">Submit</button>
    </div>    
</div>
</form>

<script>
function viewAllDocs(updId) { 
    $(".docviewer").removeClass("d-none");
	var filename= $('#filenameid'+updId).val();
   	href='https://e-commcourt.gov.in/sat'+'/'+filename;   
    $('#viewdoc').attr("src", href + "#view=VFit" + "&toolbar=0" + "&navpanes=0");
}


function closePdfviewer() {
  $(".docviewer").addClass("d-none");
}
</script>
<?php $this->load->view("admin/footer"); ?>				