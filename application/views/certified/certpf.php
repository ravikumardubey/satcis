<?php
$this->load->view("admin/header");
$this->load->view("admin/sidebar");
$this->load->view("admin/stepscert");
$salt=$this->session->userdata('certsalt');
$userdata=$this->session->userdata('login_success');
$userid=$userdata[0]->id;
$token= $this->efiling_model->getToken();
if($salt!=''){
    $cavd= $this->efiling_model->data_list_where('certifiedtemp','salt',$salt);
    $filing_no=isset($cavd[0]->filing_no)?$cavd[0]->filing_no:'';
    $selected_radio1=isset($cavd[0]->partyType)?$cavd[0]->partyType:'';
    $iapartys=isset($cavd[0]->partyids)?$cavd[0]->partyids:'';
}
$partys=explode(',', $iapartys);

?>
<div class="content" style="padding-top:0px;">
<div class="card"  id="dvContainer" style="width: 100%; padding: 0px 12px;border-top: 0px;margin-top: 0px;  border-top-left-radius: 0px;">
    <form action="<?php echo base_url(); ?>/iaaction" target="_blank" class="wizard-form steps-basic wizard clearfix" id="finalsubmit" autocomplete="off" method="post" accept-charset="utf-8">
       <div class="content clearfix" id="mainDiv1">
        <input type="hidden" name="token" value="<?php echo $token; ?>" id="token">
	    <input type="hidden" name="saltNo" id="saltNo" value="<?php echo $salt; ?>">
	    <input type="hidden" name="tabno" id="tabno" value="4">
	    <input type="hidden" name="type" id="type" value="cert">
		<FIELDSET> 
		<table class="table" style="border: 1px;solid;">
        	<tr style="background-color:#e1cece">
        		<th>Sr.No</th>
        		<th>Party Name</th>
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
                    </tr>';
	        } else {
	            $party_tags = 'R';
	            if(in_array('1', $partys)){
	                $checked="checked";
	            }
	            $option_value = '
                     <tr><td>1</td>
                    <td><label for="additionla_partyy">'.$resName . '(R1) </label></td>
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
                    </tr>
               ';
                $ii++;
            }
	        echo $option_value;
	        ?>
	       
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
            
                $warr=array('salt'=>$salt,'user_id'=>$userid,'display'=>'Y','submit_type'=>'cert');
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
            <LEGEND><b class="fa fa-upload">&nbsp;&nbsp;Matter Details :</b></legend> 
            <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">SR.No.</th>
                      <th scope="col">Matter</th>
                      <th scope="col">Order Date</th>
                      <th scope="col">Total No. Page </th>
                      <th scope="col">Not Set</th>
                      <th scope="col">Amount</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php 
                  $cert= $this->efiling_model->data_list_where('temp_certified_copy_matters','salt',$salt);
                  $i=1;
                  foreach($cert as $val){
                      if($val->meta_type=='1'){
                          $valss="Apply of Certified Copy of Order/Judgment";
                      }
                      if($val->meta_type=='2'){
                          $valss="Apply of Certified Copy of Appeal Book";
                      }
                  ?>
                    <tr>
                      <th scope="row"><?php echo $i; ?></th>
                      <td><?php echo $valss; ?></td>
                      <td><?php echo isset($val->order_date)?$val->order_date:'-'; ?></td>
                      <td><?php echo $val->total_no_page; ?></td>
                      <td><?php echo $val->no_set; ?></td>
                      <td><?php echo '0'; ?></td>
                      
                    </tr>
                 <?php $i++;} ?>
                  </tbody>
                </table>
            </fieldset>

	</div>
	<?= form_close();?>    
 </div>
<div class="row">
    <div class="offset-md-8 col-md-4">
        <input  type="button" value="Save and Next" id="iafpsubmit" class="btn btn-success" onclick="iafpsubmit();">
		&nbsp;&nbsp;<input type="reset" value="Reset/Clear" class="btn btn-danger">
    </div>
</div>
</div>

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
                var valurl= rtn.data;
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
    var tabno= document.getElementById("tabno").value;
    var type= document.getElementById("type").value;
    var token=Math.random().toString(36).slice(2); 
    var token_hash=HASH(token+'certfinalsaves');
    var dataa = {};
	dataa['salt']  =salt;
	dataa['token'] =token;
	dataa['tabno'] =tabno;
	dataa['type'] =type;
	$.ajax({
        type: "POST",
        url: base_url+'certfpsave/'+token_hash,
        data: dataa,
        cache: false,
        beforeSend: function(){
        	$('#iafpsubmit').prop('disabled',true).val("Under proccess....");
        },
        success: function (resp) {
        	var resp = JSON.parse(resp);
        	if(resp.data=='success') {
         	   setTimeout(function(){
                    window.location.href = base_url+'certpayment';
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