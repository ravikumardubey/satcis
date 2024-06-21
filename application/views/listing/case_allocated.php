<?php $this->load->view("admin/header"); ?>
<?php $this->load->view("admin/sidebar"); ?>
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
 <!--Angualrjs -->
<div class="content" style="padding-top:0px;margin-top: 32px;"  ng-controller="users" data-ng-init="usersInformation()" ng-app="my_app">

	<div class="row">
		<div class="card" style="width: 100%; padding: 0px 12px;border-top: 0px;margin-top: 0px; border-top-right-radius: 0px; border-top-left-radius: 0px;">
              <div class="container">
			<h6>Case Allocation List</h6>
              	 <table id="example" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sr.No</th>
                                <th>Diary No.</th>
                                <th>Listing Date</th>
                                <th>Case No.</th>
                                <th>Party</th>
                                <th>Status</th>
                                <th>Court</th>
                                <th>Purpose</th>
                                <th>Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                         <?php

                         if(is_array(@$filedcase)){
                          $i=1;
                          foreach($filedcase as $val){ 
                                 $vasl=$this->efiling_model->data_list_where('sat_case_detail','filing_no',$val->filing_no);
                                 if ($vasl[0]->case_type == '1') {
                                     $case_type_name = 'Appeal(APL)';
                                 }
                                 $status='';
                                 if($vasl[0]->status=='P'){
                                     $status='Pending';
                                 }
     
                                 $val2='Pending';
                                 if($vasl[0]->status=='D'){
                                     $val2='Disposed';
                                 }
                                 $dfr=$this->efiling_model->createdfr($val->filing_no);
                                 $case=$this->efiling_model->createcaseno($val->filing_no);
                             ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $dfr; ?></td>
                                <td><?php echo date('d/m/Y',strtotime($val->listing_date)); ?></td>
                                <td><?php echo $case; ?></td>
                                <td><?php echo $vasl[0]->pet_name.$this->efiling_model->fn_addition_party($val->filing_no,1); ?><span style="color:red"> VS 
                                <br></span><?php echo $vasl[0]->res_name.$this->efiling_model->fn_addition_party($val->filing_no,2); ?></td>
                                <td><?php echo $val2; ?></td>
                                
                                <td></td>
                                <td></td>
                                
                                <td><?php echo  $val->remarks; ?></td>                    						
                         <?php $i++; } } ?>   
                        </tbody>
                    </table>
              </div>
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
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    } );
} );
</script>