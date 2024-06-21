<?php $this->load->view("admin/header"); ?>
<?php $this->load->view("admin/sidebar"); 
//echo "hii reached correctly"; die;

$listingdate=isset($_REQUEST['listingdatre'])?$_REQUEST['listingdatre']:'';
if($listingdate==''){
    $listingdate=date('d-m-Y');
$listingdate=date('d-m-Y', strtotime($listingdate. ' + 1 days'));
}
?>
<link rel="stylesheet" href="<?=base_url('asset/sweetalert2/sweetalert2.min.css')?>">
<script src="<?=base_url('asset/sweetalert2/sweetalert2.all.min.js')?>"></script>
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="content">
 <?= form_open('create_causelist',['class'=>'wizard-form steps-basic wizard clearfix','action'=>'create_causelist','name'=>'create_causelist','autocomplete'=>'off']) ?>
 	<?php echo $this->session->userdata('error') ?> 
   <div class="row">
        <div class="col-lg-12">
     		<div class="card">
        		<div class="row" id="myDIV1" >
					<div class="col-md-1">
                        <div class="form-card">
                            <div class="form-group" style="margin-top:27px;" >
                              <label class="control-label" for="case_type_lower"><span class="custom"><font color="red">*</font></span>Listing Date:</label>
                            </div>
                        </div>
                    </div> 
        		    <div class="col-md-3">
                        <div class="form-card">
                            <div class="form-group" style="margin-top:20px;">
                   				<?= form_input(['name'=>'listingdatre','class'=>'form-control datepicker','id'=>'listingdatre','value'=>$listingdate,'display'=>true]) ?>
                            </div>
                        </div>
                    </div>
                   
                    <div class="col-md-3">
                        <div class="form-card">
                            <div class="form-group" style="margin-top:20px;">
                              	<button type="submit" name="submit1" value="submit1" class="btn btn-primary" >Submit</button>
                            </div>
                        </div>
                    </div> 
                </div>
			</div>
        </div>
	</div> 
	
	
	<?php
			
		$i=1;
		if(!empty($filedcase) && is_array($filedcase))
		{		
	?>		
			<table cellspacing="1" cellpadding="1" border="0" width="95%" class="fixed header_content" align="center" style="text-align:center" bgcolor="#F0F8FF">
			<tr>
				<th>S.No.</th>
				<th>Case No/Diary Number.</th>
				<th>Party Name</th>
				<th>Name of Counsel For Appellant(Mr./Mrs.)</th>
				<th>Name of Counsel For Respondent(Mr./Mrs.)</th>
			</tr>
	<?php
           foreach($filedcase as  $row){ 
		  // $row['filing_no']; die;
               $dd =$this->db->get_where('sat_case_detail',['filing_no'=>$row['filing_no']])->row_array();
			   //print_r($this->db->last_query());  die;
			   //print_r( $dd); die;
				$case_no=$dd['case_no'];
				$petName=$dd['pet_name'];
				$resName=$dd['res_name'];
				$fil_no=$row['filing_no'];
				$purpose=$row['purpose'];
				$remarks=$row['remarks'];
				$bench=$row['bench_nature'];
				$court_no=$row['court_no'];
				$dfr=$diary=substr($fil_no,6,9);
              
               //applicant advocat
               $advnames='';
               if(!empty($dd['pet_adv'])){
				 //  echo "jhgjhjh"; die;
                   $advname = $this->db->get_where('master_advocate',['adv_code'=>$dd['pet_adv']])->row_array();
				   //print_r($this->db->last_query());  die;
                  $pet_adv= $advname['adv_name'];
               }
               
               //Respondent advocat
               $res_adv='';
			  
               if(!empty($dd)&& $dd['res_adv']>0){
                   $advname = $this->db->get_where('master_advocate',['adv_code'=>$dd['res_adv']])->row_array();
				   
				   //print_r($this->db->last_query());  die;
				   if(!empty($advname)){
                   $res_adv= $advname['adv_name'];
				   }
               }
               
          //form-control datepicker hasDatepicker     
               
               ?>
			<tr>
                <td><b><?php echo $i; ?></td>
                <td><center><?php echo $dfr; ?></center><!--span><center>In</center></span--></td>
                <td><?php echo $petName; ?><span style="color:red"><b>Vs </b></span><?php echo $resName; ?></td>
                <td><?php echo $pet_adv; ?></td>
                <td><?php echo $res_adv; ?></td>
              </tr>
				<input type="hidden" name="filing_no" value="<?php print $fil_no; ?>">
				<input type="hidden" name="case_no" value="<?php print $case_no; ?>">
				<input type="hidden" name="pet_name" value="<?php echo $petName; ?>">
				<input type="hidden" name="res_name" value="<?php print $resName; ?>">
				<input type="hidden" name="pet_adv" value="<?php print $pet_adv; ?>">
				<input type="hidden" name="res_adv" value="<?php print $res_adv; ?>">
				<input type="hidden" name="res_adv" value="<?php print $res_adv; ?>">
				<input type="hidden" name="purpose" value="<?php print $purpose; ?>">
				<input type="hidden" name="remarks" value="<?php print $remarks; ?>">
				<input type="hidden" name="listingdate" value="<?php print $listingdate; ?>">
	
	
	
	
          <?php $i++;  }  ?>
		  </table>
		  <div class="col-md-12" >
		<div class="form-group" style="margin-top:20px; text-align:center;">
			<button type="submit" name="submit" value="submit"  class="btn btn-primary" >Submit</button>
		</div>				
	</div>
	
      <?php  } 		
	  if(empty($filedcase))
					{
						echo "<tr><td><span style='color:red'><center>NO RECORD FOUND FOR THIS DATE.</center></td></tr>";
					}
					?>
		
		
</div>  
    <?= form_close();?>    
<?php $this->load->view("admin/footer"); ?>		
<script>
function serchDFR(){
	with(document.caselistingsub){
	action = base_url+"cause_list";
	submit();
    	document.frm.submit11.disabled = true;  
     	document.frm.submit11.value = 'Please Wait...';  
     	return true;
	}
}
   	$(".datepicker" ).datepicker({ 
		dateFormat: "dd-mm-yy",
		minDate: 'now' + 1
		 // maxDate: 'now'
		 });
</script>