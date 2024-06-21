<?php
$this->load->view("admin/header");
$this->load->view("admin/sidebar");
$this->load->view("admin/stepsia");
$salt=$this->session->userdata('iasalt');
$userdata=$this->session->userdata('login_success');
$userid=$userdata[0]->id;
$token= $this->efiling_model->getToken();
if($salt==''){
    $salt='';
}
$tab_no='';
$type='';
$totalia='';
$anx='';
$filing_no='';
$ianature='';
$iapartys='';
$iano=0;
$selected_radio1='';
if($salt!=''){
    $basicia= $this->efiling_model->data_list_where('temp_iadetail','salt',$salt);
    $anx=isset($basicia[0]->totalanx)?$basicia[0]->totalanx:'';
    $type=isset($basicia[0]->type)?$basicia[0]->type:'';
    $filing_no=isset($basicia[0]->filing_no)?$basicia[0]->filing_no:'';
    $tab_no=isset($basicia[0]->tab_no)?$basicia[0]->tab_no:'';
    $totalia=isset($basicia[0]->totalia)?$basicia[0]->totalia:'';
    $ianature=isset($basicia[0]->ianature)?$basicia[0]->ianature:'';
    $iano=isset($basicia[0]->totalia)?$basicia[0]->totalia:'';
	  $iapartys=isset($basicia[0]->iapartys)?$basicia[0]->iapartys:'';
	  $selected_radio1=isset($basicia[0]->party_flag)?$basicia[0]->party_flag:'';
}
 error_reporting(0);
$partys=explode(',', $iapartys);
?>
<div class="content" style="padding-top:0px;">
<div class="card"  id="dvContainer" style="width: 100%; padding: 0px 12px;border-top: 0px;margin-top: 0px;  border-top-left-radius: 0px;">
    <form action="<?php echo base_url(); ?>/iaaction" target="_blank" class="wizard-form steps-basic wizard clearfix" id="finalsubmit" autocomplete="off" method="post" accept-charset="utf-8">
       <div class="content clearfix" id="mainDiv1">
        <input type="hidden" name="token" value="<?php echo $token; ?>" id="token">
	    <input type="hidden" name="filing_no" id="filing_no" value="<?php echo $filing_no; ?>">
	    <input type="hidden" name="saltNo" id="saltNo" value="<?php echo $salt; ?>">
	    <input type="hidden" name="tabno" id="tabno" value="6">
	    <input type="hidden" name="type" id="type" value="ia">



       <FIELDSET> 
        	<legend class="customlavelsub">Case Details</legend>
          <div style="margin-top:50px">
  
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
				  $data= $this->efiling_model->data_list_where('sat_case_detail','filing_no',$filing_no);
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
            <div class="row" style="margin-top:50px">
                <div class="col-auto">
                    <label class="visually-hidden" for="inputPassword"><font color="red">*</font>Total No. of Annexure</label>
                    <input type="text" class="form-control" id="toalannexure" name="toalannexure"  value="<?php echo $anx; ?>" placeholder="Annexure" onkeypress="return isNumberKey(event);" maxlength="3" readonly>
                </div>
    		</div>
        	
        </FIELDSET>

			
		<FIELDSET> 
		<table class="table" style="border: 1px;solid;">
        	<tr style="background-color:#e1cece">
        		<th>Sr.No</th>
        		<th>Party Name</th>
        		<th>Action</th>
        	</tr>
        	<tr>
			<legend><b>Party Details</b></legend>
            <?php 
   
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
                        <td><input type="checkbox" id="additionla_partyy" onclick="fn_check_valaltanama(this.value)" name="additionla_partyy" value="1" '.$checked.'></td>
                    </tr>';
	        } else {
	            $party_tags = 'R';
	            if(in_array('1', $partys)){
	                $checked="checked";
	            }
	            $option_value = '
                     <tr><td>1</td>
                    <td><label for="additionla_partyy">'.$resName . '(R1) </label></td>
                    <td><input type="checkbox" id="additionla_partyy" onclick="fn_check_valaltanama(this.value)" name="additionla_partyy" value="1" '.$checked.'> </td>
                    </tr>';
	        }
	        $where =array('filing_no'=>$filing_no,'party_flag'=>$selected_radio1);    
	        $addParty =  $this->efiling_model->select_in('additional_party',$where);
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
                        <td><input type="checkbox" id="additionla_partyy" onclick="fn_check_valaltanama(this.value)"  name="additionla_partyy" value='.$row->party_id.' '.$checked.'> </td>
                    </tr>
               ';
                $ii++;
            }
	        echo $option_value;
	        ?>
	       
    	</table>
     </FIELDSET> 
	 
	 
        <FIELDSET> 
        	<legend><b>MA Details</b></legend>
        	<table  id="examples"  class="table table-striped table-bordered" cellspacing="0"  width="100%">
                <thead>
                    <tr><th>#</th>                  
                        <th>MA Nature</th>
                        <th>Fees</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if($salt!=''){
                        $basicia= $this->efiling_model->data_list_where('temp_iadetail','salt',$salt);
                        $ianature=isset($basicia[0]->ianature)?$basicia[0]->ianature:'';
                    }
                    $ianocc=explode(',', $ianature);
                    $i=1;
                    foreach($ianocc as $row) {
                        if($row!=''){
                        $aDetail= $this->efiling_model->data_list_where('moster_ma_nature','nature_code',$row);
                      
                    ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo htmlspecialchars($aDetail[0]->nature_name);?></td>
                        <td><?php echo htmlspecialchars($aDetail[0]->fee);?></td>
                    </tr>
                <?php  $i++;} }?>
                </tbody>
      	    </table>
        </FIELDSET>


 		<FIELDSET>
            <LEGEND><b class="fa fa-upload">&nbsp;&nbsp;Uploaded Documents Details :</b></legend>
            <table id="example" class="display" cellspacing="0" border="1" width="100%">
                <thead>                    
                    <tr>
                        <th style="width:15%">Party Type</th>                    
                        <th style="width:60%">Document Type</th>                    
                        <th style="width:5%">No of Pages</th>                    
                        <th style="width:15%">Last Update</th>                   
                        <th style="width:5%">View</th>
                    </tr>
                </thead>
                <tbody>
                <?php
            
                $warr=array('salt'=>$salt,'user_id'=>$userid,'display'=>'Y','submit_type'=>'ia');
                $docData =$this->efiling_model->list_uploaded_docs('temp_documents_upload',$warr);
                
                if(@$docData) {
                    foreach ($docData as $docs) {
                        $document_filed_by = $docs->document_filed_by;
                        $document_type = $docs->document_type;
                        $no_of_pages = $docs->no_of_pages;
                        $file_id = $docs->id;
                        $update_on = $docs->update_on;
                        
                        echo'<tr>
                                <td>'.$document_filed_by.'</td>
                                <td>'.$document_type.'</td>
                                <td>'.$no_of_pages.'</td>
                                <td>'.date('d-m-Y H:i:s', strtotime($update_on)).'</td>
                                <td id="updDocId"><a href="javascript:void();" tagId="'.$file_id.'"><i class="fa fa-eye"></i></a></td>
                        </tr>';
                    }
                }
                else echo'<tr><td colspan=5 class="text-danger text-center h3">No document uploaded!</td></tr>';
                ?>
                </tbody>
            </table>
        </fieldset>

        <FIELDSET> 
        	<legend><b>Court Fee Detail</b></legend>
        	<table id="examples"  class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>Details</th>
                    <th>Fee</th>
                </tr>
                </thead>
                 <tbody>
                    <?php
                    $totalis=0;
                    if($salt!=''){
                        $basicia= $this->efiling_model->data_list_where('temp_iadetail','salt',$salt);
                        $ianature=isset($basicia[0]->ianature)?$basicia[0]->ianature:'';
                    }
                    $ianocc=explode(',', $ianature);
                    $i=1;
                    foreach($ianocc as $row) {
                        if($row!=''){
                        $aDetail= $this->efiling_model->data_list_where('moster_ma_nature','nature_code',$row);
                        $totalis+=$aDetail[0]->fee;
                    ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo htmlspecialchars($aDetail[0]->nature_name);?></td>
                        <td><?php echo htmlspecialchars($aDetail[0]->fee);?></td>
                    </tr>
                <?php 
                $total=$totalis+$anx*25;
                $i++;} }?>
                
                   <tr>
                        <td><?php echo $i; ?></td>
                        <td>Annexture</td>
                        <td><?php echo '0'; ?></td>
                    </tr>
                   <input type="hidden" id="totalfee" name="totalfee" value="<?php echo '0'; ?>">
                </tbody>
            </table>
       
	</div>
	
	<div class="row">
        <div class="offset-md-8 col-md-4" style="margin-left: 75%;">
            <input  type="button" value="Save and Next" class="btn btn-success" onclick="iafpsubmit();">
    		&nbsp;&nbsp;<input type="reset" value="Reset/Clear" class="btn btn-danger">
        </div>
    </div>
	<?= form_close();?>    
 </div></div>

 <div class="modal fade bd-example-modal-lg" id="getCodeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-lg">
        <!-- Modal content-->
            <div class="modal-content">
             <form action="certifiedsave.php" method="post">
                  <div class="modal-header" style="background-color: cadetblue;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>
                  <div id="viewsss">
                  </div>
              </form>
          </div>
     </div>
</div>  

<!-- Display Uploaded file PDF -->
<div class="modal fade" id="updPdf" role="dialog">
    <div class="modal-dialog modal-dialog-centered" style="min-width: 90%;">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body" id="frame">                   
                <iframe style="width: 100%; height: 560px" id="frameID" src=""></iframe>
            </div>
        </div>
    </div>
</div>        
<script>   
function dicript(textval){
    var DataEncrypt = textval;
    var DataKey = CryptoJS.enc.Utf8.parse("01234567890123456789012345678901");
    var DataVector = CryptoJS.enc.Utf8.parse("1234567890123412");
    var decrypted = CryptoJS.AES.decrypt(DataEncrypt, DataKey, { iv: DataVector });        
    return  CryptoJS.enc.Utf8.stringify(decrypted);
}


$('#updDocId > a').click(function(e){
    e.preventDefault();
    var updId='', href='';
    updId=$(this).attr('tagId');
    $.ajax({
        type: 'post',
        url: base_url+'uploaded_docs_display',
        data: {docId: updId},
        dataType: 'json',
        success: function(rtn){
            debugger;
            if(rtn.error == '0'){
                var valurl= dicript(rtn.data)
                href=base_url+'order_view/'+btoa(valurl);  
                $('#frameID').attr("src", href + "#view=VFit" + "&toolbar=0" + "&navpanes=0");   
            }
            else $.alert(rtn.error);
        }
    });
    $('#updPdf').modal('show');
});


function printPage() {
    change("testdiv", "true");
    window.print();
}


function iafpsubmit(){
    var salt = document.getElementById("saltNo").value;
    var token = document.getElementById("token").value;
    var tabno= document.getElementById("tabno").value;
    var type= document.getElementById("type").value;
    var filing_no= document.getElementById("filing_no").value;
    var totalfee= document.getElementById("totalfee").value;
    var dataa = {};
	dataa['salt']  =salt;
	dataa['token'] =token;
	dataa['tabno'] =tabno;
	dataa['type'] =type;
	dataa['filing_no'] =filing_no;
	dataa['totalfee'] =totalfee;
	$.ajax({
        type: "POST",
        url: base_url+'iafpsave',
        data: dataa,
        cache: false,
        beforeSend: function(){
        	$('#other_feesave').prop('disabled',true).val("Under proccess....");
        },
        success: function (resp) {
        	var resp = JSON.parse(resp);
        	if(resp.data=='success') {
         	   setTimeout(function(){
                    window.location.href = base_url+'ia_payment';
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
        	$('#other_feesave').prop('disabled',false).val("Submit");
        }
    }); 
}

function printPage(){
    var divContents = $("#dvContainer").html();
    var printWindow = window.open('', '', 'height=400,width=800');

    printWindow.document.write(divContents);

    printWindow.document.close();
    printWindow.print();
}
</script>
<?php $this->load->view("admin/footer"); ?>