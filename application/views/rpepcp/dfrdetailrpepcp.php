<?php $this->load->view("admin/header"); ?>
<?php $this->load->view("admin/sidebar"); ?>
<?php defined('BASEPATH') OR exit('No direct script access allowed');
$userdata=$this->session->userdata('login_success');
$user_id=$userdata[0]->id;
?>
<style>
.nav_wrap {
	display: none;
}

.responstable {
	margin: 0.5em 0;
	width: 100%;
	overflow: hidden;
	background: #FFF;
	color: #024457;
	border-radius: 10px;
	border: 1px solid #31708f;
}

.responstable tr {
	border: 1px solid #D9E4E6;
}

.responstable tr:nth-child(odd) {
	background-color: #EAF3F3;
}

.responstable th {
	display: none;
	border: 1px solid #FFF;
	background-color: #31708f;
	color: #FFF;
	padding: 0.5em;
}

.responstable th:first-child {
	display: table-cell;
	text-align: center;
}

.responstable th:nth-child(2) {
	display: table-cell;
}

.responstable th:nth-child(2) span {
	display: none;
}

.responstable th:nth-child(2):after {
	content: attr(data-th);
}

@media ( min-width : 480px) {
	.responstable th:nth-child(2) span {
		display: block;
	}
	.responstable th:nth-child(2):after {
		display: none;
	}
}

.responstable td {
	display: block;
	word-wrap: break-word;
	max-width: 7em;
}

.responstable td:first-child {
	display: table-cell;
	text-align: left;
	border-right: 1px solid #D9E4E6;
}

@media ( min-width : 480px) {
	.responstable td {
		border: 1px solid #D9E4E6;
	}
}

.responstable th, .responstable td {
	margin: .5em 1em;
}

@media ( min-width : 480px) {
	.responstable th, .responstable td {
		display: table-cell;
		padding: 0.3em;
	}
}

.error {
	color: red;
	display: none;
}

.radio2 {
	position: relative;
	right: -20px;
	cursor: pointer;
}

.radio1 {
	cursor: pointer;
}

.activ {
	background: #281A1A;
}

.btnTbl {
	width: 100%;
}

.btnTbl td {
	text-align: center;
}

#accordion {
	width: 100%;
	max-width: 1200px;
	padding-top: 30px;
	margin: 0 auto;
}

#collapseFour1 {
	display: block;
	width: 100%;
	max-width: 1200px;
	margin: 0 auto;
}
</style>

<div class="panel-group" id="accordion" role="tablist"
	aria-multiselectable="true">
	<div class="panel panel-default">
		<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="https://efiling.nclat.gov.in/previewCompanyActCIS.drt?e_ref=Jy3Ykr4HRg7yHjG/ZX3V4uYSa3rygGSoD3zgOYcRfaM=#collapseFour"aria-expanded="false" aria-controls="collapseThree">
			<div class="panel-heading" role="tab" id="headingThree" style="background-color: #778899;">
				<h4 class="panel-title" style="color: white; text-align: center">	SUBMITTED CASE PREVIEW</h4>
			</div>
		</a>
		
		<?php 
		$filing_no='';
		if(!empty($filedcase)){
    		foreach($filedcase as $rowd){
    		   $casetype=$rowd->case_type;
    		   $filing_no=$rowd->filing_no;
    		   $petName=$rowd->pet_name.$this->efiling_model->fn_addition_party($filing_no,'1');
    		   $resName=$rowd->res_name.$this->efiling_model->fn_addition_party($filing_no,'2');
    		   
    		   
    		   $case_type_detail =$this->efiling_model->data_list_where('master_case_type','case_type_code',$casetype);
    		   $case_type_name='';
    		   if($case_type_detail!=''){
    		       $case_type_name= isset($case_type_detail[0]->short_name)?$case_type_detail[0]->short_name:'';
    		   }
    		   
    		    $bcode =$rowd->bench;
    		    $ben =$this->efiling_model->data_list_where('master_benches','bench_code',$bcode);
    		    $bench_name =isset($ben[0]->name)?$ben[0]->name:'';

    		    $pet_sub_section =$rowd->pet_sub_section;
    		    $subsec =$this->efiling_model->data_list_where('master_under_section','section_code',$pet_sub_section);
    		    $subsec = isset($subsec[0]->section_name)?$subsec[0]->section_name:'';

    		    $act = $rowd->pet_section;
    		    $hscqueryact11 =$this->efiling_model->data_list_where('master_regulator','id',$casetype);
    		    $act = isset($hscqueryact11[0]->act_short_name)?$hscqueryact11[0]->act_short_name:'';
    		   
    		}
		}?>
		<div id="collapseFour1" role="tabpanel" aria-labelledby="headingThree" style="display: block;">
			<div class="panel-body">
				<table class="responstable" width="100%" cellpadding="0"	cellspacing="0" align="center" border="1px">
					<tbody>
						<tr>
							<th colspan="10"><p style="font-size: 20px; text-align: center;">Basic Details</p></th>
						</tr>
						<tr>
							<td><strong>Aptel Location</strong></td>
							<td><?php echo "Mumbai"; ?></td>
							<td><strong>Case Type</strong></td>
							<td colspan="2">RP</td>
						</tr>
						<tr>
							<td><strong>Case Title</strong></td>
							<td><?php echo $petName ?> Vs. <?php echo $resName ?></td>
						</tr>
						<tr>
						</tr>
						
						<tr style="text-align: center; background: #024457;">
							<th colspan="5">
								<p style="font-size: 20px; text-align: center;">Act & Sections</p>
							</th>
						</tr>
						<tr>
							<td align="left" colspan="1"><?php echo $act; ?></td>
							<td align="left" colspan="2"><strong><?php echo $subsec; ?></strong></td>
							<td align="left" colspan="2"><strong>1&2</strong></td>
						</tr>
					</tbody>
				</table>			
			</div>
		</div>
	</div>
	
	
	
	
		<div id="collapseFour1" role="tabpanel" aria-labelledby="headingThree" style="display: block;">
			<div class="panel-body">
				<table class="responstable" width="100%" cellpadding="0"	cellspacing="0" align="center" border="1px">
	                    <tr style="text-align: center; background: #024457;">
							<th colspan="5"><p style="font-size: 20px; text-align: center;">Impugned Order	Details </p></th>
						</tr>
						<tr style="text-align: center; background: #024457;">
							<th>Sr.No</th>
							<th>Commission</th>
							<th>Case No</th>
							<th>Decision Date</th>
							<th>Communicaion Date</th>
						</tr>
						<?php $comm =$this->efiling_model->data_list_where('lower_court_detail','filing_no',$filing_no);
						if(!empty($comm)){
						$i=1;
						foreach($comm as $comval){
						    $commid =$comval->commission;
						    $commv =$this->efiling_model->data_list_where('master_commission','id',$commid);
						    $commission = isset($commv[0]->short_name)?$commv[0]->short_name:'';
						    $casetype=$comval->case_type;
						    $case_type_detail =$this->efiling_model->data_list_where('master_case_type','case_type_code',$casetype);
						    $case_type_name='';
						    if($case_type_detail!=''){
						        $case_type_name= isset($case_type_detail[0]->short_name)?$case_type_detail[0]->short_name:'';
						    }
						    ?>
						<tr style="text-align: center;"	>
							<td  width="30px"><?php echo $i; ?></td>
							<td  width="300px"><strong><?php echo $commission; ?></strong></td>
							<td  width="50px"><strong><?php echo $case_type_name; ?>-&nbsp;<?php echo $comval->case_no; ?>/&nbsp;<?php echo $comval->case_year; ?></strong></td>
							<td  width="50px"><strong><?php echo date('d/m/Y',strtotime($comval->decision_date)); ?></strong></td>
							<td  width="50px"><strong><?php echo date('d/m/Y',strtotime($comval->communication_date)); ?></strong></td>
						</tr>
						<?php $i++;  }
						} ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	
	
	

	<div class="panel panel-default">
		<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="https://efiling.nclat.gov.in/previewCompanyActCIS.drt?e_ref=Jy3Ykr4HRg7yHjG/ZX3V4uYSa3rygGSoD3zgOYcRfaM=#collapseOne"	aria-expanded="false" aria-controls="collapseThree">
			<div class="panel-heading" role="tab" id="headingThree" style="background-color: #778899;">
				<h4 class="panel-title" style="color: white;">	<i class="more-less glyphicon glyphicon-plus"></i> APPELLANT'S LIST
				</h4>
			</div>
		</a>

		<div id="collapseOne11" class="panel-collapse  " role="tabpanel"
			aria-labelledby="headingThree">
			<div class="panel-body">
				<table id="table" class="responstable" width="100%" cellpadding="0"	cellspacing="0" border="1px">
					<thead>
						<tr>
							<th class="footable-filtering footable-sortable"	data-breakpoints="xs" data-sort-initial="descending">S. No.</th>
							<th class="footable-filtering footable-sortable">Applellant name</th>
							<th data-breakpoints="xs">Applellant address</th>
							<th data-breakpoints="xs sm">State</th>
							<th data-breakpoints="xs sm md">District</th>
							<th data-breakpoints="xs sm md">Pincode</th>
							<th data-breakpoints="xs sm md">Mobile Number</th>
							<th data-breakpoints="xs sm md">E-mail Id</th>
						</tr>
					</thead>
					<tbody>
					
					<?php $cdv=$this->efiling_model->data_list_where('sat_case_detail','filing_no',$filing_no);
					if(!empty($cdv)){
						$i=1;
						$statename='';
						$ddistrictname='';
						foreach($cdv as $cdvr){
						    if($cdvr->pet_state!=''){
						        $st2 = $this->efiling_model->data_list_where('master_psstatus','state_code',$cdvr->pet_state);
						        $statename= $st2[0]->state_name;
						    }
						    if($cdvr->pet_district!=''){
						        $st1 = $this->efiling_model->data_list_where('master_psdist','district_code',$cdvr->pet_district);
						        $ddistrictname= $st1[0]->district_name;
						    }
						    ?>
						    
						<tr>
							<td><?php echo $i; ?></td>
							<td><?php echo $cdvr->pet_name;?></td>
							<td><?php echo $cdvr->pet_address; ?></td>
							<td><?php echo $statename; ?></td>
							<td><?php echo $ddistrictname;?> </td>
							<td><?php echo $cdvr->pet_pin;?></td>
							<td><?php echo $cdvr->pet_mobile;?></td>
							<td><?php echo $cdvr->pet_email;?></td>
						</tr>
			           <?php  } 
					}else{ echo "Record not found!";}?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="panel panel-default">
		<a class="collapsed" role="button" data-toggle="collapse"	data-parent="#accordion"	href="https://efiling.nclat.gov.in/previewCompanyActCIS.drt?e_ref=Jy3Ykr4HRg7yHjG/ZX3V4uYSa3rygGSoD3zgOYcRfaM=#collapseThree"	aria-expanded="false" aria-controls="collapseThree">
			<div class="panel-heading" role="tab" id="headingThree"
				style="background-color: #778899;">
				<h4 class="panel-title" style="color: white;">
					<i class="more-less glyphicon glyphicon-plus"></i> RESPONDENT'S	LIST
				</h4>
			</div>
		</a>
		<div id="collapseThree11" class="panel-collapse" role="tabpanel" aria-labelledby="headingThree">
			<div class="panel-body">
				<table id="table" class="responstable" width="100%" cellpadding="0" cellspacing="0" border="1px">
					<thead style="background-color: #167F92;">
						<tr>
							<th class="footable-filtering footable-sortable" data-breakpoints="xs" data-sort-initial="descending">S. No.</th>
							<th class="footable-filtering footable-sortable">Respondent name</th>
							<th data-breakpoints="xs">Respondent address</th>
							<th data-breakpoints="xs sm">State</th>
							<th data-breakpoints="xs sm md">District</th>
							<th data-breakpoints="xs sm md">Pincode</th>
							<th data-breakpoints="xs sm md">Mobile Number</th>
							<th data-breakpoints="xs sm md">E-mail Id</th>
						</tr>
					</thead>
					<tbody>
					<?php $cdvr=$this->efiling_model->data_list_where('sat_case_detail','filing_no',$filing_no);
					if(!empty($cdvr)){
						$i=1;
						$statename='';
						$ddistrictname='';
						foreach($cdvr as $cdvr){
					    if($cdvr->res_state!=''){
					        $st2 = $this->efiling_model->data_list_where('master_psstatus','state_code',$cdvr->res_state);
					        $statename= $st2[0]->state_name;
					    }
					    if($cdvr->res_district!=''){
					        $st1 = $this->efiling_model->data_list_where('master_psdist','district_code',$cdvr->res_district);
					        $ddistrictname= $st1[0]->district_name;
					    }
					    ?>
						<tr>
							<td><?php echo $i; ?></td>
							<td><?php echo $cdvr->res_name;?></td>
							<td><?php echo $cdvr->res_address; ?></td>
							<td><?php echo $statename; ?></td>
							<td><?php echo $ddistrictname;?> </td>
							<td><?php echo $cdvr->res_pin;?></td>
							<td><?php echo $cdvr->res_mobile;?></td>
							<td><?php echo $cdvr->res_email;?></td>
						</tr>
			           <?php  } 
					}
					else{ echo "Record not found!";}?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="panel panel-default">
		<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" >
			<div class="panel-heading" role="tab" id="headingThree"	style="background-color: #778899;">
				<h4 class="panel-title" style="color: white;"><i class="more-less glyphicon glyphicon-plus"></i>UPLOADED DOCUMENT'S LIST</h4>
			</div>
		</a>
		<div id="collapseTwo11" class="panel-collapse " role="tabpanel"
			aria-labelledby="headingThree">
			<div class="panel-body">
				<table id="table" class="responstable" width="100%" cellpadding="0"	cellspacing="0" border="1px">
					<thead>
						<tr>
							<th class="footable-filtering footable-sortable"	data-breakpoints="xs" data-sort-initial="descending">S. No.</th>
							<th class="footable-filtering footable-sortable">Document Filed	By</th>
							<th data-breakpoints="xs sm">Sub Document Type</th>
							<th data-breakpoints="xs">No. of Pages</th>
							<th data-breakpoints="xs sm md">Document Name</th>
							<th data-breakpoints="xs sm md">View Document</th>
						</tr>
					</thead>
					<tbody>
					<?php 
					$array=array('filing_no'=>$filing_no,'user_id'=>$user_id);
				    $stQ=$this->efiling_model->data_list_mulwhere('efile_documents_upload',$array);
					if(!empty($stQ)){
					    $i=1;
					    foreach($stQ as $doc){
							$val='';
							if($doc->valumeno!=''){
								$val='&nbsp;&nbsp;&nbsp;&nbsp<span style="color:red;">(Vol-'.$doc->valumeno.')';
							}
							?>
						<tr>
							<td><?php echo $i; ?></td>
							<td><?php echo $doc->document_filed_by; ?></td>
							<td><?php echo $doc->document_type; ?><?php echo $val; ?></td>
							<td><?php echo $doc->no_of_pages; ?></td>
							<td><?php echo $doc->doc_name;; ?></td>
							<td><a	href="<?php echo base_url(); ?>view_doc/<?php echo base64_encode($doc->file_url); ?>" target="_blank">View</a></td>
						</tr>
					<?php 
					$i++;  }
					}else{
					    echo "Record not found";
					} ?>	
					</tbody>
				</table>
			</div>
		</div>
	</div>
	
	
		<div class="panel panel-default">
		<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" >
			<div class="panel-heading" role="tab" id="headingThree"	style="background-color: #778899;">
				<h4 class="panel-title" style="color: white;"><i class="more-less glyphicon glyphicon-plus"></i>MA  LIST</h4>
			</div>
		</a>
		<div id="collapseTwo11" class="panel-collapse " role="tabpanel"
			aria-labelledby="headingThree">
			<div class="panel-body">
				<table id="table" class="responstable" width="100%" cellpadding="0"	cellspacing="0" border="1px">
					<thead>
						<tr>
							<th class="footable-filtering footable-sortable"	data-breakpoints="xs" data-sort-initial="descending">S. No.</th>
							<th class="footable-filtering footable-sortable">MA Number</th>
							<th data-breakpoints="xs sm">MA Nature</th>
							<th data-breakpoints="xs">Filied Date</th>
						</tr>
					</thead>
					<tbody>
					<?php $ias = $this->efiling_model->data_list_where('satma_detail','filing_no',$filing_no); 
					if(!empty($ias)){
					    $i=1;
					    foreach($ias as $ia){
					        if($ia->ia_nature!=''){
					            $st1 = $this->efiling_model->data_list_where('moster_ma_nature','nature_code',$ia->ia_nature);
					            $nature_name= $st1[0]->nature_name;
					        }
					        $ia_fil_no_unq = $ia->ia_filing_no;
					        ?>
						<tr>
							<td><?php echo $i; ?></td>
							<td>MA-<?php echo $ia->ia_no;; ?>/<?php echo substr($ia_fil_no_unq, 11, 4); ?></td>
							<td><?php echo $nature_name; ?></td>
							<td><?php echo date('d/m/Y',strtotime($ia->entry_date)); ?></td>
						</tr>
					<?php 
					$i++;  }
					}else{
					    echo "Record not found";
					} ?>	
					</tbody>
				</table>
			</div>
		</div>
	</div>
	
	
	
	<div class="panel panel-default">
		<a class="collapsed" role="button" data-toggle="collapse"
			data-parent="#accordion"
			href="https://efiling.nclat.gov.in/previewCompanyActCIS.drt?e_ref=Jy3Ykr4HRg7yHjG/ZX3V4uYSa3rygGSoD3zgOYcRfaM=#collapseTwo1"
			aria-expanded="false" aria-controls="collapseThree">
			<div class="panel-heading" role="tab" id="headingThree"
				style="background-color: #778899;">
				<h4 class="panel-title" style="color: white;">
					<i class="more-less glyphicon glyphicon-plus"></i> ADVOCATE'S LIST
				</h4>
			</div>
		</a>
		<div id="collapseTwo11" class="panel-collapse" role="tabpanel"	aria-labelledby="headingThree">
			<div class="panel-body">
				<table id="table" class="responstable" width="100%" border="1px">
					<thead>
						<tr>
							<th class="footable-filtering footable-sortable"data-breakpoints="xs" data-sort-initial="descending">S.No.</th>
							<th class="footable-filtering footable-sortable">Applellant/Respondent	Name</th>
							<th data-breakpoints="xs sm md">Advocate Email</th>
							<th data-breakpoints="xs sm md">Advocate Mobile</th>
						</tr>
					</thead>
					<tbody>
				<?php 	if(!empty($filedcase)){
				    $advocate='';
    		          foreach($filedcase as $rowd){ 
    		              if($rowd->pet_adv!=''){
    		                  $st1 = $this->efiling_model->data_list_where('master_advocate','adv_code',$rowd->pet_adv);
    		                  if(!empty($st1)){
    		                      $advocate= $st1[0]->adv_name;
    		                  }
    		              }
    		              if($advocate==''){
    		                  $st1 = $this->efiling_model->data_list_where('efiling_users','id',$rowd->pet_adv);
    		                  if(!empty($st1) && is_array($st1)){
    		                      $advocate= $st1[0]->fname.' '.$st1[0]->lname;
    		                  }
    		              }
    		          ?>
					     <tr>
							<td>1</td>
							<td><?php echo $advocate; ?></td>
							<td><?php echo $rowd->pet_counsel_email; ?></td>
							<td><?php echo $rowd->pet_counsel_mobile; ?></td>
						</tr>
						
					<?php } } 
				    $stQ = $this->efiling_model->data_list_where('additional_advocate','filing_no',$filing_no); 
					if(!empty($stQ)){
					    $i=2;
					    $advocate='';
					    foreach($stQ as $adv){
					        if($adv->adv_code!=''&& $adv->advType=='1'){
					            $st1 = $this->efiling_model->data_list_where('master_advocate','adv_code',$adv->adv_code);
					            if(!empty($st1) && is_array($st1)){
					               $advocate= $st1[0]->adv_name;
					            }
					        }
					        if($adv->adv_code!=''&& $adv->advType=='2'){
					            $st1 = $this->efiling_model->data_list_where('efiling_users','id',$adv->adv_code);
					            if(!empty($st1) && is_array($st1)){
					                $advocate= $st1[0]->fname.' '.$st1[0]->lname;
					            }
					        }
					        ?>
						<tr>
							<td><?php echo $i; ?></td>
							<td><?php echo $advocate; ?></td>
							<td><?php echo $adv->adv_email; ?></td>
							<td><?php echo $adv->adv_mob_no; ?></td>
						</tr>
						<?php  $i++; }
					} ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view("admin/footer"); ?>
 