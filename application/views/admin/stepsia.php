<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$userdata=$this->session->userdata('login_success');
$user_id=$userdata[0]->id;
$salt= $this->session->userdata('iasalt');
$basic= $this->efiling_model->data_list_where('temp_iadetail','salt',$salt);
$pagename= basename($_SERVER['PHP_SELF']);

$disabeltab0='disabled';
$iabasicdetailtab='btn btn-danger';
if($pagename=='iabasic_detail'){
    $disabeltab0='';
    $iabasicdetailtab="btn btn-success";
    $iapartytab="btn btn-warning";
}

$disabeltab1='disabled';
$iapartytab='btn btn-danger';
if($pagename=='ia_partydetail'){
    $disabeltab1='';
    $iabasicdetailtab="btn btn-success";
    $iapartytab="btn btn-warning";
}



$disabeltab2='disabled';
$iadetailtab='btn btn-danger';
if($pagename=='ia_detail_ia'){
    $disabeltab0='';
    $disabeltab1='';
    $disabeltab2='';
    $iabasicdetailtab="btn btn-success";
    $iapartytab="btn btn-success";
    $iadetailtab="btn btn-warning";
}


$disabeltab3='disabled';
$iadocumenttab='btn btn-danger';
if($pagename=='ia_upload_doc'){
    $disabeltab0='';
    $disabeltab1='';
    $disabeltab2='';
    $disabeltab3='';
    $iabasicdetailtab="btn btn-success";
    $iapartytab="btn btn-success";
    $iadetailtab="btn btn-success";
    $iadocumenttab="btn btn-warning";
}

$disabeltab4='disabled';
$iachecklisttab='btn btn-danger';
if($pagename=='ia_checklist'){
    $disabeltab0='';
    $disabeltab1='';
    $disabeltab2='';
    $disabeltab3='';
    $disabeltab4='disabled';
    $iabasicdetailtab="btn btn-success";
    $iapartytab="btn btn-success";
    $iadetailtab="btn btn-success";
    $iadocumenttab="btn btn-success";
    $iachecklisttab="btn btn-warning";
}


$disabeltab5='disabled';
$iafinaptab='btn btn-danger';
if($pagename=='ia_finalprivew'){
    $disabeltab0='';
    $disabeltab1='';
    $disabeltab2='';
    $disabeltab3='';
    $disabeltab4='';
    $disabeltab5='';
    $iabasicdetailtab="btn btn-success";
    $iapartytab="btn btn-success";
    $iadetailtab="btn btn-success";
    $iadocumenttab="btn btn-success";
    $iachecklisttab="btn btn-success";
    $iafinaptab="btn btn-warning";
}


$disabeltab6='disabled';
$iapaymenttab='btn btn-danger';
if($pagename=='ia_payment'){
    $disabeltab0='';
    $disabeltab1='';
    $disabeltab2='';
    $disabeltab3='';
    $disabeltab4='';
    $disabeltab5='';
    $disabeltab6='';
    $iabasicdetailtab="btn btn-success";
    $iapartytab="btn btn-success";
    $iadetailtab="btn btn-success";
    $iadocumenttab="btn btn-success";
    $iachecklisttab="btn btn-success";
    $iafinaptab="btn btn-success";
    $iapaymenttab="btn btn-warning";
}

$disabeltab7='disabled';
$iarecepttab='btn btn-danger';
if($pagename=='ia_finalreceipt'){
    $disabeltab0='';
    $disabeltab1='';
    $disabeltab2='';
    $disabeltab3='';
    $disabeltab4='';
    $disabeltab5='';
    $disabeltab6='';
    $disabeltab7='';
    $iabasicdetailtab="btn btn-success";
    $iapartytab="btn btn-success";
    $iadetailtab="btn btn-success";
    $iadocumenttab="btn btn-success";
    $iachecklisttab="btn btn-success";
    $iafinaptab="btn btn-success";
    $iapaymenttab="btn btn-success";
    $iarecepttab="btn btn-warning";
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
    <a href="<?php echo base_url(); ?>iabasic_detail" class="<?php echo $iabasicdetailtab; ?> <?php echo $class; ?> visible-lg-block visible-md-block" <?php echo $disabeltab0; ?>>Basis Details</a>
    <a href="<?php echo base_url(); ?>ia_partydetail" class="<?php echo $iapartytab; ?> <?php echo $class; ?> visible-lg-block visible-md-block " <?php echo $disabeltab1; ?>>Party Details</a>
    <a href="<?php echo base_url(); ?>ia_detail_ia" class="<?php echo $iadetailtab; ?> <?php echo $class; ?>  visible-lg-block visible-md-block" <?php echo $disabeltab2; ?>>MA Details</a>
    <a href="<?php echo base_url(); ?>ia_upload_doc" class="<?php echo $iadocumenttab; ?> <?php echo $class; ?> visible-lg-block visible-md-block "    <?php echo $disabeltab3; ?>>Upload Documents</a>
    <a href="<?php echo base_url(); ?>ia_checklist" class="<?php echo $iachecklisttab; ?> <?php echo $class; ?>  visible-lg-block visible-md-block "  <?php echo $disabeltab4; ?>>Checklist</a>
    <a href="<?php echo base_url(); ?>ia_finalprivew"    class="<?php echo $iafinaptab; ?> <?php echo $class; ?>  visible-lg-block visible-md-block "  <?php echo $disabeltab5; ?>>Final Preview </a>
    <a href="<?php echo base_url(); ?>ia_payment" class="<?php echo $iapaymenttab; ?> <?php echo $class; ?> visible-lg-block visible-md-block "  <?php echo $disabeltab6; ?>>Payment</a>
    <a href="<?php echo base_url(); ?>ia_finalreceipt" class="<?php echo $iarecepttab; ?> <?php echo $class; ?> visible-lg-block visible-md-block"  <?php echo $disabeltab7; ?>>Receipt
</a>
  
</div> 
