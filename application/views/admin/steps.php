<?php
$userdata=$this->session->userdata('login_success');
$user_id=$userdata[0]->id;
$salt= $this->session->userdata('salt');
$basic= $this->efiling_model->data_list_where('sat_temp_appellant','salt',$salt);
$tab=@$basic[0]->tab_no;
$refiling= $this->session->userdata('refiling');
$pagename= basename($_SERVER['PHP_SELF']);
$disabeltab1='';
$one='btn btn-danger';
if($pagename=='basicdetail'){
    $disabeltab0='';
    $one="btn btn-success";

}


$disabeltab1='';
$applicanttab='btn btn-danger';
if($pagename=='applicant'){
    $disabeltab0='';
    $disabeltab1='';
    $one="btn btn-success";
    $applicanttab="btn btn-warning";
}

$disabeltab3='disabled';
$respondenttab='btn btn-warning';
if($pagename=='respondent'){
    $disabeltab0='';
    $disabeltab1='';
    $disabeltab3='';
    $one="btn btn-success";
    $applicanttab="btn btn-success";
    $respondenttab="btn btn-warning";
}


$disabeltab4='disabled';
$counseltab='btn btn-danger';
if($pagename=='counsel'){
    $disabeltab0='';
    $disabeltab1='';
    $disabeltab3='';
    $disabeltab4='';
    $one="btn btn-success";
    $applicanttab="btn btn-success";
    $respondenttab="btn btn-success";
    $counseltab="btn btn-warning";
}


$disabeltab5='disabled';
$ia_detailtab='btn btn-danger';
if($pagename=='ma_detail'){
    $disabeltab0='';
    $disabeltab1='';
    $disabeltab3='';
    $disabeltab4='';
    $disabeltab5='';
    $one="btn btn-success";
    $applicanttab="btn btn-success";
    $respondenttab="btn btn-success";
    $counseltab="btn btn-success";
    $ia_detailtab="btn btn-warning";
    
}


$disabeltab6='disabled';
$other_feetab='btn btn-danger';
if($pagename=='other_fee'){
    $disabeltab0='';
    $disabeltab1='';
    $disabeltab3='';
    $disabeltab4='';
    $disabeltab5='';
    $disabeltab6='';
    $one="btn btn-success";
    $applicanttab="btn btn-success";
    $respondenttab="btn btn-success";
    $counseltab="btn btn-success";
    $ia_detailtab="btn btn-success";
    $other_feetab="btn btn-warning";
}


$disabeltab7='disabled';
$document_upload='btn btn-danger';
if($pagename=='document_upload'){
    $disabeltab0='';
    $disabeltab1='';
    $disabeltab3='';
    $disabeltab4='';
    $disabeltab5='';
    $disabeltab6='';
    $disabeltab7='';
    $one="btn btn-success";
    $applicanttab="btn btn-success";
    $respondenttab="btn btn-success";
    $counseltab="btn btn-success";
    $ia_detailtab="btn btn-success";
    $other_feetab="btn btn-success";
    $document_upload="btn btn-warning";
}




$disabeltab8='disabled';
$payment_mode='btn btn-danger';
if($pagename=='payment_mode'){
    $disabeltab0='';
    $disabeltab1='';
    $disabeltab3='';
    $disabeltab4='';
    $disabeltab5='';
    $disabeltab6='';
    $disabeltab7='';
    $disabeltab8='';
    $one="btn btn-success";
    $applicanttab="btn btn-success";
    $respondenttab="btn btn-success";
    $counseltab="btn btn-success";
    $ia_detailtab="btn btn-success";
    $other_feetab="btn btn-success";
    $document_upload="btn btn-success";
    $payment_mode="btn btn-warning";
}



$disabeltab9='disabled';
$final_previewtab='btn btn-danger';
if($pagename=='final_preview'){
    $disabeltab0='';
    $disabeltab1='';
    $disabeltab3='';
    $disabeltab4='';
    $disabeltab5='';
    $disabeltab6='';
    $disabeltab7='';
    $disabeltab8='';
    $disabeltab9='';
    $one="btn btn-success";
    $applicanttab="btn btn-success";
    $respondenttab="btn btn-success";
    $counseltab="btn btn-success";
    $ia_detailtab="btn btn-success";
    $other_feetab="btn btn-success";
    $document_upload="btn btn-success";
    $payment_mode="btn btn-success";
    $final_previewtab='btn btn-warning';
}


?>
<div class="btn-group btn-breadcrumb breadcrumb-default">

     <?php if($salt==''){?>
     <a href="<?php echo base_url(); ?>basicdetail" class="<?php echo $one; ?> visible-lg-block visible-md-block"	<?php echo $disabeltab0; ?>  id="step_0">Basic Details</a>
     <?php }else{ ?>    
     <a href="<?php echo base_url(); ?>basic_detailsdraft" class="<?php echo $one; ?> visible-lg-block visible-md-block"	<?php echo $disabeltab0; ?>  id="step_0">Basic Details</a>
     <?php } ?>
     
     
    <a href="<?php echo base_url(); ?>applicant" class="<?php echo $applicanttab; ?> visible-lg-block visible-md-block <?php echo $disabeltab1; ?>" id="step_1">Appellant</a>
    <a href="<?php echo base_url(); ?>respondent" class="<?php echo $respondenttab; ?> visible-lg-block visible-md-block  <?php echo $disabeltab3; ?>"  id="step_3" >Respondent</a>
    <a href="<?php echo base_url(); ?>counsel"    class="<?php echo $counseltab; ?> visible-lg-block visible-md-block  <?php echo $disabeltab4; ?>"  id="step_4" >Counsel/ Representative </a>
    <a href="<?php echo base_url(); ?>ma_detail" class="<?php echo $ia_detailtab; ?> visible-lg-block visible-md-block  <?php echo $disabeltab5; ?>"  id="step_5" >MA Detail</a>
    <a href="<?php echo base_url(); ?>other_fee" class="<?php echo $other_feetab; ?> visible-lg-block visible-md-block  <?php echo $disabeltab6; ?>"  id="step_6" >Fee</a>
    <a href="<?php echo base_url(); ?>document_upload" class="<?php echo $document_upload; ?> visible-lg-block visible-md-block  <?php echo $disabeltab7; ?>"  id="step_7" >Document Upload</a>
    <a href="<?php echo base_url(); ?>payment_mode" class="<?php echo $payment_mode; ?> visible-lg-block visible-md-block <?php echo $disabeltab8; ?>"  id="step_8" >Payment Mode</a>
    <a href="<?php echo base_url(); ?>final_preview" class="<?php echo $final_previewtab; ?> visible-lg-block visible-md-block  <?php echo $disabeltab9; ?>"  id="step_9" >Final Preview</a>
</div> 
