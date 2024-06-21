<?php $this->load->view("admin/header"); ?>
<?php $this->load->view("admin/sidebar"); ?>
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
 <!--Angualrjs -->
<div class="content" >
<h6>Filed Case List</h6>
	<div class="row">
		<div style="width:100%">
              	 <table id="example" class="table table-striped table-bordered" >
                        <thead>
                            <tr>
                                <th>Sr.No</th>
                                <th>AL No.</th>
                                <th>Case No.</th>
                                <th>Date of Filing</th>
                                <th>Case Type</th>
                                <th>Party</th>
                                <th>Status</th>    
                            </tr>
                        </thead>
                        <tbody>
                         <?php

                         if(is_array(@$filedcase)){
                          $i=1;
                          foreach($filedcase as $val){ 
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
                                        // $val2="<span style='color:green'>Cure Defect</span>";
                                         $val2= "<span style='color:red'><b>Defective</b></span>";
                                     }
                                 }
                                 
                                 if($val->case_no!=''){
                                     $val2= "<span style='color:Green'><b>Registerd</b></span>";
                                 }
                                 $dfr=$this->efiling_model->createdfr($val->filing_no);
                                 $case=$this->efiling_model->createcaseno($val->filing_no);
                             ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><a href="<?php echo base_url();?>dfrdetail/<?php echo $val->filing_no; ?>" ><?php echo $dfr; ?></a></td>
                                <td><?php echo $case; ?></td>
                                <td><?php echo $val->dt_of_filing; ?></td>
                                <td><?php echo $case_type_name; ?></td>
                                <td><?php echo $val->pet_name.$this->efiling_model->fn_addition_party($val->filing_no,1); ?><span style="color:red"> VS <br></span><?php echo $val->res_name.$this->efiling_model->fn_addition_party($val->filing_no,2); ?></td>
                                <td><?php echo $val2; ?></td> 
                 						
                         <?php $i++; } } ?>   
                        </tbody>
                    </table>
        </div>
	</div>
</div>                           
</div>	

 <?php $this->load->view("admin/footer"); ?>
  <script>

  function viewdetails(id){
  	var dataa={};
      dataa['adv_id']=id;
   	  $.ajax({
           type: "POST",
           url: base_url+"dfr_detail",
           data: dataa,
           cache: false,
           success: function (resp) {
           	var resp = JSON.parse(resp);
           	if(resp.data=='success') {
           		$('#record').html(resp.value);
   			}
           },
           error: function (request, error) {
				$('#loading_modal').fadeOut(200);
           }
       });
  }


  
    $('.nav-link').click(function() { 
        var content = $(this).data('value');
        if(content!=''){
        	$('.steps').empty().load(base_url+'/efiling/'+content);
        }
    });
    $(document).ready(function() {
        $('#example').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        } );
    } );


    function EditModal(val){
    	  $.ajax({
              type: "POST",
              url: base_url+"filingaction/editapplant_respondent",
              data: "filingno=" + val,
              cache: false,
              success: function (data) {
            	  $("#detailccopy").html(data);
            	  $("#getCodeModal").modal("show");   
              }
          });
    }



    function additionla_party(val){
      	  $.ajax({
              type: "POST",
              url: base_url+"filingaction/edit_additonalparty",
              data: "filingno=" + val,
              cache: false,
              success: function (data) {
            	  $("#detailAPcopy").html(data);
            	  $("#getAPModal").modal("show");   
              }
          });
    }

    function additionla_advocate(val){
    	  $.ajax({
            type: "POST",
            url: base_url+"filingaction/additionla_advocate",
            data: "filingno=" + val,
            cache: false,
            success: function (data) {
          	  $("#detailAAcopy").html(data);
          	  $("#getAAModal").modal("show");   
            }
        });
  }
    
    
    function document_filing(val){
    	  $.ajax({
            type: "POST",
            url: base_url+"filingaction/edit_document_filing",
            data: "filingno=" + val,
            cache: false,
            success: function (data) {
          	  $("#detailDFcopy").html(data);
          	  $("#getDFModal").modal("show");   
            }
        });
     }

    function ia_details_filing(val){
  	  $.ajax({
          type: "POST",
          url: base_url+"filingaction/edit_ia_details_filing",
          data: "filingno=" + val,
          cache: false,
          success: function (data) {
        	  $("#detailIAcopy").html(data);
        	  $("#getIAModal").modal("show");   
          }
      });
   }


    
    function review_petition_filing(val){
  	  $.ajax({
          type: "POST",
          url: base_url+"filingaction/review_petition_filing",
          data: "filingno=" + val,
          cache: false,
          success: function (data) {
        	  $("#detailRPcopy").html(data);
        	  $("#getRPModal").modal("show");   
          }
      });
   }


    function execution_petition_filing(val){
    	  $.ajax({
            type: "POST",
            url: base_url+"filingaction/edit_execution_petition_filing",
            data: "filingno=" + val,
            cache: false,
            success: function (data) {
          	  $("#detailEPcopy").html(data);
          	  $("#getEPModal").modal("show");   
            }
        });
     }

    function contempt_petition_filing(val){
  	  $.ajax({
          type: "POST",
          url: base_url+"filingaction/edit_contempt_petition_filing",
          data: "filingno=" + val,
          cache: false,
          success: function (data) {
        	  $("#detailCPcopy").html(data);
        	  $("#getCPModal").modal("show");   
          }
      });
   }
    </script>