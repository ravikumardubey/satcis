<?php
$this->load->view("admin/header");
$this->load->view("admin/sidebar");
defined('BASEPATH') OR exit('No direct script access allowed');
$token= $this->efiling_model->getToken();
?>
 <!--Angualrjs -->

	<div class="row">
		<div class="card" style="width: 100%;">
              <div class="container">
			   <h6>Defective Cases  List </h6>
              	 <table id="example" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sr. No</th>
                                <th>DFR. No.</th>
                                <th>Date of Filing</th>
                                <th>Case Type</th>
                                <th>Case Title</th>
                                <th>Status</th>
                                <th>Date of Upload</th>
                                <th style="width: 68px;">Due Date</th>
                                <th>View Latter</th>
                                <th>Pending Defects</th>
                            </tr>
                        </thead>
                        <tbody>
                         <?php
                         function myCrypt($value, $key, $iv){
                             $encrypted_data = openssl_encrypt($value, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
                             return base64_encode($encrypted_data);
                         }
                         if(is_array(@$defect)){
                          $i=1;
                          foreach($defect as $val){
                                 $case_type_name='';
                                 if ($val->case_type == '1') {
                                     $case_type_name = 'Appeal(APL)';
                                 }
                                 $status='';
                                 if($val->status=='P'){
                                     $status='Pending';
                                 }
                                 $vasl=$this->efiling_model->data_list_where('scrutiny','filing_no',$val->filing_no);
                                 $val2="Under Scrutiny";
                                 if(!empty($vasl)){
                                     if($vasl[0]->defects=='Y'){
                                         $val2= "<span style='color:red'><b>Defective</b></span>";
                                     }
                                 }
                                 
                                 if($val->case_no!=''){
                                     $val2= "<span style='color:Green'><b>Registerd</b></span>";
                                 }
                                 $dfr=$this->efiling_model->createdfr($val->filing_no);
                                 $case=$this->efiling_model->createcaseno($val->filing_no);
                                 $links='-';
                                 if($vasl[0]->defects=='Y'){
                                     $duwdateval='-';
                                     $uploaddate='-';
                                     $disablesbtn='disabled';
                                     $st2 = $this->efiling_model->data_list_where('sat_uploadeddefectlatter','filing_no',$val->filing_no);
                                     if(!empty($st2) && is_array($st2)){
                                         $date1= date('d-m-Y',strtotime($val->notification_date));
                                         $date2 = strtotime($date1);
                                         $duedateaa = strtotime("+7 day", $date2);
                                         $duwdateval= date('d-m-Y', $duedateaa);
                                         $uploaddate= date('d-m-Y',strtotime($val->notification_date));
                                         $vasl=$this->efiling_model->data_list_where('sat_uploadeddefectlatter','filing_no',$val->filing_no);
                                         $file_name=  $vasl[0]->file_name;
                                         $docid= $vasl[0]->id;

                                         $disablesbtn='';
                                     }
                                     
                                     
                                     if (file_exists($file_name)){
                                         $key="01234567890123456789012345678901"; // 32 bytes
                                         $vector="1234567890123412"; // 16 bytes
                                         $encrypted = myCrypt($file_name, $key, $vector);
                                     } 
                                     
                                     
                                     if($val->flag=='1'){
                                         $disablesbtn='disabled';
                                     }
                                     if($val->flag=='2'){
                                         $disablesbtn='';
                                     }
                                     
                                     
                                     $label="View Re-defect";
					
                                     if($val->flag=='0' ||  $val->flag=='1'){
                                         $disablesbtn1='disabled-link';
                                         $label="-";
                                     }
         
                                     
                                     if($val->flag!='3'){
                                     ?>
                      
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $dfr; ?></td>
                                <td><?php echo date('d-m-Y',strtotime($val->dt_of_filing)); ?></td>
                                <td><?php echo $case_type_name; ?></td>
                                <td><?php echo $val->pet_name; ?> <?php echo $this->efiling_model->fn_addition_party($val->filing_no,'1');?><span style="color:red"> VS <br></span><?php echo $val->res_name; ?> <?php echo $this->efiling_model->fn_addition_party($val->filing_no,'2');?></td>
                                <td><?php echo $val2; ?></td> 
                                <td><?php echo $uploaddate; ?></td> 
                                <td><span style="color:red"><?php echo $duwdateval; ?></span></td> 
                                <td><a target="_blank" href="defect_view/<?php echo base64_encode($encrypted); ?>">VIEW</a></td> 
                                <td><a target="_blank" href="<?php echo base_url(); ?>pendingdefect/<?php echo $val->filing_no; ?>"   class="<?php //echo $disablesbtn1; ?>"><?php echo $label; ?></td> 

                         	</tr>
                         <?php $i++;}  
                                 }
                            } 
                         } ?>  
                        </tbody>
                    </table>
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


	<style>
.disabled-link {
  pointer-events: none;
}
</style>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="text-align: center;">
        <h5 class="modal-title "  id="exampleModalLabel">Confirm</h5>
        <input type="hidden" id="btnval" name="btnval" value="">
        <input type="hidden" id="filing_noval" name="filing_noval" value="">
        <button type="button" class="close " data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="alert alert-primary" role="alert" >
        	<p id="massage"></p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
        <button type="button" class="btn btn-primary" id="btncon" onclick="redirectpage();">Yes</button>
      </div>
    </div>
  </div>
</div>
                      
<?php $this->load->view("admin/footer"); ?>
<script>
$(document).ready(function() {
    $('#example').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    } );
} );


function pdfviewdfect(val){
    var docid = val;
    var token=Math.random().toString(36).slice(2); 
    var token_hash=HASH(token+'docidval');
    var data = {};
    data['token'] = token;
    data['docid'] = docid;
    $.ajax({
    	type: "POST",
        url: base_url+'defectshowpdf/'+token_hash,
        data: data,
        cache: false,
        success: function(resp) {
        	var resp = JSON.parse(resp);
        	if(resp.data=='success') {
 				href=resp.value;   
                $('#frameID').attr("src", href + "#view=VFit" + "&toolbar=0" + "&navpanes=0");
			}else if(resp.error == '1') {
				$.alert(resp.display);
			}
        },
     });
      $('#updPdf').modal('show');
}



</script>
