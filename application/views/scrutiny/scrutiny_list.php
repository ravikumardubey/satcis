<?php $this->load->view("admin/header"); ?>
<?php $this->load->view("admin/sidebar"); ?>
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
 <!--Angualrjs -->
<div class="content" style="padding-top:0px;margin-top: 32px;"  >
	<div class="col-sm-12 div-padd">
    <?php
    $query=$this->db->query("select s.filing_no,a.case_no,s.defects from scrutiny as s left 
    join sat_case_detail as a on a.filing_no=s.filing_no where a.case_no is NULL  AND s.defects='Y' 
    or s.defects is NULL order by s.filing_no asc");
    $rowval= $query->result();
    if(!empty($rowval)){
    ?> 
	    <div class="table-responsive">
			<table id="example111111111111" class="table table-striped table-bordered" cellspacing="0"   width="100%">
           	  <thead>
                <tr>
                  <th scope="col">Sr.No</th>
                  <th scope="col">AL No</th>
                  <th scope="col">Party</th>
                  <th scope="col">Date Of filing</th>
                  <th scope="col">Create Defect</th>
                  <th scope="col">Status</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
      		<tbody>
     		 <?php 
     		 $i=1;
     		 foreach($rowval as $row){
     		     $caseDetail=$this->efiling_model->getCasedetail($row->filing_no);
               ?>
                <tr>
                  <th scope="row"><?php echo $i; ?></th>
                  <td><?php echo substr($row->filing_no,8,3);?>/<?php echo substr($row->filing_no,11,4);?></td>
                  <td style="width: 350px;"><?php echo $caseDetail[0]->pet_name;?> <span style="color:red">VS </span><?php echo $caseDetail[0]->res_name;?></td>
                  <td><?php echo  date('d/m/Y',strtotime($caseDetail[0]->dt_of_filing));?></td>
                   <th><a href="<?php echo base_url(); ?>createdefect/<?php echo  $caseDetail[0]->filing_no; ?>">Create</th>
                  <td><?php
                  $val="Scrutiny";
                  if($row->defects=='Y'){
                      $val="<span style='color:green'>Cure Defect</span>";
                      echo "<span style='color:red'>Defective</span>";
                  }
                  ?></td>
                  <th><a href="scrutinyform/<?php echo  $caseDetail[0]->filing_no; ?>"><?php echo $val; ?></th>
                </tr>
               <?php $i++;} 
                }
              ?>  
              </tbody>
            </table>
		</div>
	</div>
</div>
<?php $this->load->view("admin/footer"); ?>				