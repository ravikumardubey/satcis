<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$userdata=$this->session->userdata('login_success');
$user_id=$userdata[0]->id;
$salt= $this->session->userdata('docsalt');
$basic= $this->efiling_model->data_list_where('temp_iadetail','salt',$salt);
$pagename= basename($_SERVER['PHP_SELF']);

$disabeltab0='disableClick';
$iabasicdetailtab='btn btn-warning';
if($pagename=='idoc_basic_detail'){
    $disabeltab0='';
    $iabasicdetailtab="btn btn-danger";
}

$disabeltab1='disableClick';
$iapartytab='btn btn-danger';
if($pagename=='doc_partydetail'){
    $disabeltab1='';
    $iabasicdetailtab="btn btn-success";
    $iapartytab="btn btn-warning";
}



$disabeltab3='disableClick';
$iadocumenttab='btn btn-danger';
if($pagename=='doc_upload_doc'){
    $disabeltab0='';
    $disabeltab1='';
    $disabeltab3='';
    $iabasicdetailtab="btn btn-success";
    $iapartytab="btn btn-success";
    $iadetailtab="btn btn-success";
    $iadocumenttab="btn btn-warning";
}
$disabeltab8='disableClick';
$iaadvocatetab='btn btn-danger';
$disabeltab4='disableClick';
$iachecklisttab='btn btn-danger';
if($pagename=='doc_checklist'){
    $disabeltab0='';
    $disabeltab1='';
    $disabeltab3='';
    $disabeltab4='disabled';
    $iabasicdetailtab="btn btn-success";
    $iapartytab="btn btn-success";
    $iadocumenttab="btn btn-success";
    $iaadvocatetab="btn btn-success";
    $iachecklisttab="btn btn-warning";
}


$disabeltab5='disableClick';
$iafinaptab='btn btn-danger';
if($pagename=='doc_finalprivew'){
    $disabeltab0='';
    $disabeltab1='';
    $disabeltab3='';
    $disabeltab4='';
    $disabeltab5='';
    $iabasicdetailtab="btn btn-success";
    $iapartytab="btn btn-success";
    $iadocumenttab="btn btn-success";
    $iachecklisttab="btn btn-success";
    $iaadvocatetab="btn btn-success";
    $iafinaptab="btn btn-warning";
}


$disabeltab6='disableClick';
$iapaymenttab='btn btn-danger';
if($pagename=='doc_payment'){
    $disabeltab0='';
    $disabeltab1='';
    $disabeltab3='';
    $disabeltab4='';
    $disabeltab5='';
    $disabeltab6='';
    $iabasicdetailtab="btn btn-success";
    $iapartytab="btn btn-success";
    $iadocumenttab="btn btn-success";
    $iachecklisttab="btn btn-success";
    $iafinaptab="btn btn-success";
    $iapaymenttab="btn btn-warning";
    $iaadvocatetab="btn btn-success";
}

$disabeltab7='disableClick';
$iarecepttab='btn btn-danger';
if($pagename=='doc_finalreceipt'){
    $disabeltab0='disableClick';
    $disabeltab1='disableClick';
    $disabeltab3='disableClick';
    $disabeltab4='disableClick';
    $disabeltab5='disableClick';
    $disabeltab6='disableClick';
    $disabeltab7='disableClick';
    $iabasicdetailtab="btn btn-success";
    $iapartytab="btn btn-success";
    $iadocumenttab="btn btn-success";
    $iachecklisttab="btn btn-success";
    $iafinaptab="btn btn-success";
    $iapaymenttab="btn btn-success";
    $iaadvocatetab="btn btn-success";
    $iarecepttab="btn btn-warning";
}



if($pagename=='doc_councel'){
    $disabeltab0='';
    $disabeltab1='';
    $disabeltab3='';
    $disabeltab4='';
    $disabeltab5='';
    $disabeltab6='';
    $disabeltab7='';
    $disabeltab8='';
    $iabasicdetailtab="btn btn-success";
    $iapartytab="btn btn-success";
    $iadocumenttab="btn btn-success";
    $iachecklisttab="btn btn-danger";
    $iafinaptab="btn btn-danger";
    $iapaymenttab="btn btn-danger";
    $iaadvocatetab="btn btn-warning";
}




$class='';
if($salt!=''){
    $class="disableClick";
}

$class='';
if($salt!=''){
    $class="disableClick";
}

?>

<style>
.disableClick{
    pointer-events: none;
}
</style>

<div class="btn-group btn-breadcrumb breadcrumb-default">
    <a href="<?php echo base_url(); ?>doc_basic_detail" class="<?php echo $iabasicdetailtab; ?> <?php echo $class; ?> visible-lg-block visible-md-block <?php echo $disabeltab0; ?>">Basis Details</a>
    <a href="<?php echo base_url(); ?>doc_partydetail" class="<?php echo $iapartytab; ?> visible-lg-block visible-md-block <?php echo $disabeltab1; ?>">Party Details</a>
    <a href="<?php echo base_url(); ?>doc_upload_doc" class="<?php echo $iadocumenttab; ?> visible-lg-block visible-md-block <?php echo $disabeltab3; ?>"    >Upload Documents</a>
    <a href="<?php echo base_url(); ?>doc_councel" class="<?php echo $iaadvocatetab; ?> visible-lg-block visible-md-block <?php echo $disabeltab8; ?>"    >Advocate Detail</a> 
    <a href="<?php echo base_url(); ?>doc_checklist" class="<?php echo $iachecklisttab; ?> visible-lg-block visible-md-block <?php echo $disabeltab4; ?>"  >Checklist</a>
    <a href="<?php echo base_url(); ?>doc_finalprivew"    class="<?php echo $iafinaptab; ?> visible-lg-block visible-md-block <?php echo $disabeltab5; ?>"  >Final Preview </a>
    <a href="<?php echo base_url(); ?>doc_payment" class="<?php echo $iapaymenttab; ?> visible-lg-block visible-md-block <?php echo $disabeltab6; ?>"  >Payment</a>
    <a href="<?php echo base_url(); ?>doc_finalreceipt" class="<?php echo $iarecepttab; ?> visible-lg-block visible-md-block <?php echo $disabeltab7; ?>"  >Receipt
</a>
  
</div> 
