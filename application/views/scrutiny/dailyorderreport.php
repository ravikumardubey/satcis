<?php $this->load->view("admin/header"); ?>
<?php $this->load->view("admin/sidebar"); ?>
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="content" style="padding-top:0px;">
	<div class="row">
		<div class="card" style="width: 100%; padding: 0px 12px;border-top: 0px;margin-top: 0px; border-top-right-radius: 0px; border-top-left-radius: 0px;">
        <div class="container">
			<h6>Order List</h6>
              	 <table id="example" class="display nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sr.No</th>
                                <th>AL No.</th>
                                <th>Party</th>
                                <th>Order Date</th>
                                <th>Case Type</th>
                                <th>Created Date</th>
                                <th>View</th>
                            </tr>
                        </thead>
                        <tbody>
                         <?php 
                            $party= $this->efiling_model->data_list('order_details');

                            if(is_array(@$party)){
                                $i=1;
                                foreach($party as $val){
                                    $dfr=$this->efiling_model->createdfr($val->filing_no);
                                    $case=$this->efiling_model->createcaseno($val->filing_no);
                                    
                                    $file_name=  $val->path;
									$encrypted=   $file_name;
                             /*       if (file_exists($file_name)){
                                        $key="01234567890123456789012345678901"; // 32 bytes
                                        $vector="1234567890123412"; // 16 bytes
                                        $encrypted = myCrypt($file_name, $key, $vector);
                                    }  */
                                    
                             ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $dfr; ?></td>
                                <td><?php echo $val->pet_name.$this->efiling_model->fn_addition_party($val->filing_no,1); ?><span style="color:red"> VS <br></span><?php echo $val->res_name.$this->efiling_model->fn_addition_party($val->filing_no,2); ?></td>
                                <td><?php echo  date('d-m-Y',strtotime($val->date_of_order)); ?></td>
                                <td><?php echo  $val->case_type ?></td>
                                <td><?php echo date('Y'); ?></td>
                                <td><a target="_blank" href="<?php echo base_url(); ?><?php echo $encrypted; ?>">View</a></td>
                                <!--td><a  href="view_doc/<?php //echo $encrypted; ?>">View</a></td-->
                            </tr>
                         <?php $i++;
                                }
                            } 
                            
                         /*   function myCrypt($value, $key, $iv){
                                $encrypted_data = openssl_encrypt($value, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
                                return base64_encode($encrypted_data);
                            } */
                            
                            ?>   
                        </tbody>
                    </table>
              </div>
        </div>
	</div>
</div>	
<script>
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
</script>
<?php $this->load->view("admin/footer"); ?>