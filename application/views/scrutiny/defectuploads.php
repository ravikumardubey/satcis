<?php $this->load->view("admin/header"); ?>
<?php $this->load->view("admin/sidebar"); ?>
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
 <!--Angualrjs -->
<div class="content" style="padding-top:0px;margin-top: 32px;"  ng-controller="users" data-ng-init="usersInformation()" ng-app="my_app">
	<div class="col-sm-12 div-padd">
	    <div style="float:right">
   			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Defect Upload</button>
   		</div>
	    <div class="table-responsive">
			<table datatable="ng" id="example111111111111" class="table table-striped table-bordered" cellspacing="0"   width="100%">
           	  <thead>
                <tr>
                  <th scope="col">Sr.No</th>
                  <th scope="col">AL No.</th>
                  <th scope="col">Party</th>
                  <th scope="col">Defect Date</th>
                  <th scope="col">Create Date</th>
                  <th scope="col">View</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
      		<tbody>
      	<?php
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        $casety =$this->db->query("select * from defect_letter where user_id = '$user_id'");
        $rowval= $casety->result();
        $valerror='';
        if(!empty($rowval)){
        $valerror=true;
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
            }else{
                $valerror= false;
            }
                      ?>  
              </tbody>
            </table>
            <?php if($valerror==false){
                 echo "<span style='margin-left:420px;color:red'>Record not found</span>";
            }?>
		</div>
	</div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Defect Upload</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="row" style="margin-left: 120px;">
          		<div class="mb-3">
                  <label class="" for="flexRadioDefault1">
                  <input class="" type="radio" name="flexRadioDefault" id="flexRadioDefault2" checked>&nbsp;Diary Number</label>
                </div>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <div class="mb-3">
                  <input class="" type="radio" name="flexRadioDefault" id="flexRadioDefault2" checked>
                  <label class="form-check-label" for="flexRadioDefault2">&nbsp;Case Number</label>
           		 </div>
            </div>
          	<div class="row">
               	<div class="mb-3">
                  <label for="exampleFormControlInput1" class="form-label">AL No</label>
                  <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                </div>
   				 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <div class="mb-3">
                  <label for="exampleFormControlInput1" class="form-label">Year</label>
                  <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                </div>
          	</div>
          	<div class="row">
              	<div class="mb-3">
                  <label for="formFile" class="form-label">Default file input example</label>
                  <input class="form-control" type="file" id="formFile">
                </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<?php $this->load->view("admin/footer"); ?>				