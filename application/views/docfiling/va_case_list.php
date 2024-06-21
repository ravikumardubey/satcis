<?php $this->load->view("admin/header"); ?>
<?php $this->load->view("admin/sidebar"); ?>
<?php 
defined('BASEPATH') OR exit('No direct script access allowed');  
if($this->input->post()) {
    $year=$this->input->post('year');
}else{
    $year=date('Y');
}
error_reporting(0);
?>
<div class="content" style="padding-top:12px;">
	<div class="row">
		<div class="card" style="width: 100%; padding: 12px 12px;border-top: 0px;margin-top: 0px; border-top-right-radius: 0px;border-top-left-radius: 0px;">
            <?php 
                echo form_fieldset('<small class="fa fa-list"></small>&nbsp;&nbsp;Document Detail','ia_list'); ?>

                         <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="ia_list_table" style="width: 100%;">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">Sr. No</th>
                                        <th scope="col">MF No.</th>
                                        <th scope="col">Case No.</th>
                                        <th scope="col">Document Name.</th>
                                        <th scope="col">Filed By.</th>
                                        <th scope="col">Filing Date.</th>
                                        <th scope="col">Matter</th>
                                        <th scope="col">View Reciept</th>
                                        <th scope="col">Hard Copy Submit</th>
                                    </tr>
                                </thead>
                                <tbody>
                           <?php  
                           $i=1;
                           foreach($va as $maval){
                               $natureName='';
                                $doc=$maval->doc_id;
                               $hscquery =  $this->efiling_model->data_list_where('master_document','did',$doc);
                               if(!empty($hscquery)){
                                    $natureName = $hscquery[0]->d_name;
                               }
                               $doc_type =$maval->doc_type;
                               $date = DateTime::createFromFormat("Y-m-d", $maval->entry_date);
                               $year= $date->format("Y");
                               $filing_no= $maval->filing_no;
                               if($filing_no!=''){
                                   $filing_No = substr($filing_no, 5, 6);
                                   $filing_No = ltrim($filing_No, 0);
                                   $filingYear = substr($filing_no, 11, 4);
                                   $val= "DFR/$filing_No/$filingYear";
                               }
                               $addparty=$maval->add_party_code;
                               $main_party=$maval->party_flag;
                               $ptype=2;
                               if($main_party=='P'){
                                   $ptype=1;
                               }
                               $mainparty='';
                               $partyid = explode(",", $addparty);
                               $pid = rtrim($addparty, ',');
                               $len = sizeof($partyid);
                               for ($k = 0; $k < $len; $k++) {
                                   if ($partyid[$k] == 1) {
                                       $sql = $this->efiling_model->data_list_where('sat_case_detail','filing_no',$filing_no);
                                       foreach ($sql as $row) {
                                           $flass_type = 'A-';
                                           if ($ptype == 2) {
                                               $flass_type = 'R-';
                                           }
                                           if ($ptype == 2) {
                                               $mainparty = $row->res_name.'('.$flass_type.'1)';
                                           }
                                           if ($ptype == 1) {
                                               $mainparty = $row->pet_name.'('.$flass_type.'1)';
                                           }
                                       }
                                   }
                               }
                               
                               if($addparty!='TP'){
                                   $sqladd1 = $this->db->query("select * from additional_party where  party_id IN($pid) order by partysrno");
                                   $sql_party11 = $sqladd1->result();
                                   $pet_name11 = '';
                                   foreach ($sql_party11 as $row) {
                                       $id = $row->party_id;
                                       $flass_type = 'A-';
                                       if ($ptype == 2) {
                                           $flass_type = 'R-';
                                       }
                                       $pet_name11 .= $row->pet_name.'('.$flass_type.$row->partysrno.'), ';
                                   }
                               }
                               
                               
                           ?>
                                  <tr>
                                       <th scope="row"><?php echo $i; ?></th>
                                       <td><a href="<?php echo base_url(); ?>va_detail/<?php echo $maval->id; ?>"><?php echo $maval->vakalatnama_no; ?>/<?php echo $year; ?></a></td>
                                       <td><?php echo $val; ?></td>
                                       <td><?php echo $natureName; ?></td>
                                      <td><?php if($mainparty !='') { $mainparty = $mainparty.','; } echo $mainparty.$pet_name11; ?></td>
                                       <td><?php echo date('d/m/Y',strtotime($maval->entry_date)); ?></td>
                                       <td><?php echo $maval->matter; ?></td>
                                       <td><a target="_blank" href="maprint/<?php echo $maval->filing_no; ?>/<?php echo $maval->vakalatnama_no; ?>/<?php echo $doc_type; ?>">View Receipt</a></td>
                                       <td>Yes/No</td>
                                    </tr>
                            <?php $i++; } ?>
                               </tbody>
                            </table>
                        </div>
              <?php  echo form_fieldset_close(); ?>
        </div>
	</div>
</div>
 <?php $this->load->view("admin/footer"); ?>
<script type="text/javascript">
    $.(document).ready(function(){
        $('#ia_list_table').DataTable();
    });
</script>