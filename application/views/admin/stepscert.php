<?php
$userdata=$this->session->userdata('login_success');
$user_id=$userdata[0]->id;
$salt= $this->session->userdata('certsalt');
$class='';
if($salt!=''){
    $class="disableClick";
}
//$basic= $this->efiling_model->data_list_where('certifiedtemp','salt',$salt);
$pagename= basename($_SERVER['PHP_SELF']);


$disabeltab1='disableClick';

$disabeltab2='disableClick';

$disabeltab3='disableClick';

$disabeltab4='disableClick';

$disabeltab5='disableClick';

$disabeltab6='disableClick';

$disabeltab7='disableClick';

$tab1='btn btn-danger';
if($pagename=='certbasicdetail'){
    $disabeltab1='';
    $tab1="btn btn-success";
    $tab2="btn btn-danger";
}


$tab2='btn btn-danger';
if($pagename=='certpartydetail'){
    $disabeltab1='';
    $disabeltab2='';
    $tab1="btn btn-success";
    $tab2="btn btn-success";
    $tab3="btn btn-danger";
}

$tab3='btn btn-danger';
if($pagename=='matter'){
    $disabeltab1='';
    $disabeltab2='';
    $disabeltab3='';
    $tab1="btn btn-success";
    $tab2="btn btn-success";
    $tab3="btn btn-success";
    $tab4="btn btn-danger";
}


$tab4='btn btn-danger';
if($pagename=='certuploaddoc'){
    $disabeltab1='';
    $disabeltab2='';
    $disabeltab3='';
    $disabeltab4='';
    $tab1="btn btn-success";
    $tab2="btn btn-success";
    $tab3="btn btn-success";
    $tab4="btn btn-success";
    $tab5="btn btn-danger";
}



$tab5='btn btn-danger';
if($pagename=='certpf'){
    $disabeltab1='';
    $disabeltab2='';
    $disabeltab3='';
    $disabeltab4='';
    $disabeltab5='';
    $tab1="btn btn-success";
    $tab2="btn btn-success";
    $tab3="btn btn-success";
    $tab4="btn btn-success";
    $tab5="btn btn-success";
    $tab6="btn btn-danger";
}


$tab6='btn btn-danger';
if($pagename=='certpayment'){
    $disabeltab1='';
    $disabeltab2='';
    $disabeltab3='';
    $disabeltab4='';
    $disabeltab5='';
    $disabeltab6='';
    $tab1="btn btn-success";
    $tab2="btn btn-success";
    $tab3="btn btn-success";
    $tab4="btn btn-success";
    $tab5="btn btn-success";
    $tab6="btn btn-success";
    $tab7="btn btn-danger";
}



$tab7='btn btn-danger';
if($pagename=='certreceipt'){
    $disabeltab1='disableClick';
    $disabeltab2='disableClick';
    $disabeltab3='disableClick';
    $disabeltab4='disableClick';
    $disabeltab5='disableClick';
    $disabeltab6='disableClick';
    $tab1="btn btn-success";
    $tab2="btn btn-success";
    $tab3="btn btn-success";
    $tab4="btn btn-success";
    $tab5="btn btn-success";
    $tab6="btn btn-success";
}


?>
<style>
.disableClick{
    pointer-events: none;
}
</style>




<div class="btn-group btn-breadcrumb breadcrumb-default">
    <a href="<?php echo base_url(); ?>certbasicdetail" class="<?php echo $tab1; ?> <?php echo $class; ?> visible-lg-block visible-md-block <?php echo $disabeltab1; ?>" >Case Detail</a>
    <a href="<?php echo base_url(); ?>certpartydetail" class="<?php echo $tab2; ?> visible-lg-block visible-md-block <?php echo $disabeltab2; ?>" >Party Details</a>
      <a href="<?php echo base_url(); ?>matter" class="<?php echo $tab3; ?> visible-lg-block visible-md-block <?php echo $disabeltab3; ?>"  >Matter</a>
    <a href="<?php echo base_url(); ?>certuploaddoc" class="<?php echo $tab4; ?> visible-lg-block visible-md-block <?php echo $disabeltab4; ?>" >Upload Documents</a>
    <a href="<?php echo base_url(); ?>certpf" class="<?php echo $tab5; ?> visible-lg-block visible-md-block <?php echo $disabeltab5; ?>"  >Final Preview</a>
    <a href="<?php echo base_url(); ?>certpayment"    class="<?php echo $tab6; ?> visible-lg-block visible-md-block <?php echo $disabeltab6; ?>"  >Payment </a>
    <a href="<?php echo base_url(); ?>certreceipt" class="<?php echo $tab7; ?> visible-lg-block visible-md-block <?php echo $disabeltab7; ?>"  >Receipt</a>
    
</div> 
