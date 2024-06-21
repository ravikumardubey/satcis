<?php 
$this->load->view("admin/header");
$this->load->view("admin/sidebar"); 
$token= $this->efiling_model->getToken();
?>
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
 <!--Angualrjs -->
<div class="content">
	<div class="row">
              <div class="container">
			  <h6>List Of Fresh Admission Matter </h6>
              	 <table id="example" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sr.No</th>
                                <th>Diary No.</th>
                                <th>Case No.</th>
                                <th>Date of Filing</th>
                                <th>Case Type</th>
                                <th>Party</th> 
                                <th>Action</th>       
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
                                 $dfr=$this->efiling_model->createdfr($val->filing_no);
                                 $case=$this->efiling_model->createcaseno($val->filing_no);
                                 
                             ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><a href="<?php echo base_url();?>dfrdetail/<?php echo $val->filing_no; ?>" ><?php echo $dfr; ?></a></td>
                                <td><?php echo $case; ?></td>
                                <td><?php echo date('d/m/Y',strtotime($val->dt_of_filing)); ?></td>
                                <td><?php echo $case_type_name; ?></td>
                                <td><?php echo $val->pet_name.$this->efiling_model->fn_addition_party($val->filing_no,1); ?><span style="color:red"> VS <br></span><?php echo $val->res_name.$this->efiling_model->fn_addition_party($val->filing_no,2); ?></td>				
                     			<td><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalLong" onclick="getDiary('<?php echo $val->filing_no; ?>');">Assign Date</button></td>
                         <?php $i++; } } ?>   
                        </tbody>
                    </table>
              </div>
        </div>
	</div>       
	
	
	<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Assign Date</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
          	 <input type="hidden" id="diaryno" name="diaryno" value="">
             <div class="form-group">
                <label for="exampleFormControlTextarea1">Date<sup class="text-danger">*</sup></label>
                <input data-date-format="dd/mm/yyyy" class="form-control" id="datepicker">
             </div>
             <div class="form-group">
                <label for="exampleFormControlTextarea1">Remark<sup class="text-danger">*</sup></label>
                <textarea class="form-control" id="remarks" name="remarks" rows="3"></textarea>
              </div>
             
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="givedate();">Save changes</button>
          </div>
        </div>
      </div>
    </div>

                  
<?php $this->load->view("admin/footer"); ?>
<script>
    function getDiary(val){
    	$('#diaryno').val(val);	
    }
    
    function givedate(){
        var date=document.getElementById("datepicker").value;
        if(date==""){
        	alert("Please Enter Impugned Order Number");
            return false;
        }   
        var remarks=document.getElementById("remarks").value;
        if(remarks==''){
            alert("Please Enter remarks");
            return false;
        }
        var filingno=document.getElementById("diaryno").value;
        
        var dataa = {};
    	dataa['filingno']=filingno;
    	dataa['dateas']=date;
    	dataa['remarks']=remarks;
    	
    	$.ajax({
            type: "POST",
            url: base_url+'asigndate/<?php echo $token; ?>',
            data: dataa,
            cache: false,
    		beforeSend: function(){
    			//$('#petitioner_save').prop('disabled',true).val("Under proccess....");
    		},
            success: function (resp) {
            	var resp = JSON.parse(resp);
            	if(resp.data=='success') {
   					 $.alert({
                      title: '<i class="fa fa-check-circle text-success"></i>&nbsp;</b>Success!</b>',
                      content: '<p class="text-danger">'+resp.massage+'</p>',
                      animationSpeed: 2000
                    });
    			}
    			else if(resp.error != '0') {
    				$.alert({
                      title: '<i class="fa fa-check-circle text-warning"></i>&nbsp;</b>Error!</b>',
                      content: '<p class="text-danger">'+resp.massage+'</p>',
                      animationSpeed: 2000
                    });
    			}
            },
            error: function(){
    			$.alert("Surver busy,try later.");
    		}
    	}); 
    }
    
    
    
    

    $(document).ready(function() {
        $('#example').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        } );
    } );
    var date = $('#datepicker').datepicker({ dateFormat: 'dd/mm/yy' }).val();

</script>
</script>