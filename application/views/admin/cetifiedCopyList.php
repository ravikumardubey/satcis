 
<?php $this->load->view("admin/header"); ?>
<?php $this->load->view("admin/sidebar"); ?>

<?php
$case_details= $this->db->query("select cp.id as certify_copy_id,cp.certify_number,cno.filing_no,cno.case_no,cno.pet_name from sat_case_detail as cno inner join certified_copy as cp on cp.filling_no=cno.filing_no");
$case_details = $case_details->result();
?>

<div class="container margin-top-30">
    <div ng-controller="users" class="container">
        <div class="col-md-12">
            <strong> Certified Copy List </strong>
            <div class="table-responsive">
                <table  id="example"
                       class="table table-striped table-bordered" cellspacing="0"
                       width="100%">
                    <thead>
                    <tr>
                        <th scope="col">Sr. No</th>
                        <th scope="col">CFR No</th>
                        <th scope="col">AL No.</th>
                        <th scope="col">Case No</th>
                        <th scope="col">Main Party</th>
                        <th scope="col">Print</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php  $i=1;
                    foreach($case_details as $row) {
                       $val= substr($row->filing_no,5);
                       $a=substr_replace($val ,"",-4);
                       $b= substr($val, -4);
                       $val= $a.'/'.$b;
                        ?>
                        <tr>
                           <th scope="row"><?php echo $i; ?></th>
                           <th scope="row"><?php echo  $row->certify_number; ?></th>
                           <td><?php echo $val; ?></td>
                           <td><?php echo $row->case_no; ?></td>
                           <td><?php echo $row->pet_name; ?></td>
                           <td scope="col"><a target="_blank"  href="<?php echo base_url(); ?>certifyreceipt/<?php echo $row->certify_number; ?>/<?php echo $row->filing_no; ?>">Receipt</a> /
                           <a target="_blank" href="<?php echo base_url(); ?>receipt_certify_matters/<?php echo $row->certify_number ?>/<?php echo $row->filing_no; ?>">View Matters</a>
                       </tr>
                   <?php $i++;} ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Form modal -->
    </div>

<?php $this->load->view("admin/footer"); ?>  

<script>

$(document).ready(function() {
    $('#example').DataTable();
} );
</script>


 <script>
 
 function popitupaaaa(url) 
   {
    newwindow=window.open(url,'name','height=600,width=800,screenX=500,screenY=500');
    if (window.focus) {newwindow.focus()}
    return false;
   }
 
 
   function popitup(url) 
   {
    newwindow=window.open(url,'name','height=600,width=800,screenX=500,screenY=500');
    if (window.focus) {newwindow.focus()}
    return false;
   }
   </script>
