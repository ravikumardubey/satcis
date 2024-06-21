<?php
$this->load->view("admin/header");
$this->load->view("admin/sidebar");
$this->load->view("admin/stepsdoc");
$salt=$this->session->userdata('docsalt');
$userdata=$this->session->userdata('login_success');
$userid=$userdata[0]->id;
$token= $this->efiling_model->getToken();
$vals=$this->session->userdata('docdetail');
if(empty($vals)){
    echo "<span style='color:red'>Request not valid!</span>";die;
}
if(!empty($vals) && is_array($vals)){
    $doc_filing=$vals['doc_filing'];
    $filing_no=$vals['filingNo'];
    $doc_type=$vals['doc_type'];
    $doc_ids=$vals['id'];
}


$curYear=date('Y');
error_reporting(0);
?>

<?= form_open(false,['class'=>'wizard-form steps-basic wizard clearfix','name'=>'rpepcpbascidetail','id'=>'rpepcpbascidetail','autocomplete'=>'off']) ?>
       <?= form_fieldset('Search DFR ').'<i class="icon-plus-circle2 text-danger" style="position: absolute;padding: 9px 6px;"></i>'; ?>
		<input type="hidden" name="token" value="<?php echo $token; ?>" id="token">
	    <input type="hidden" name="saltNo" id="saltNo" value="<?php echo $salt; ?>">
	    <input type="hidden" name="tabno" id="tabno" value="1">
        <div class="col-md-12" >
    		<div class="row" >
    	       <td colspan="1" ><font size="2" ><a  href="maprint/<?php echo $filing_no; ?>/<?php echo $doc_filing; ?>/<?php echo $doc_type; ?>/<?php echo $doc_ids; ?>"><b><font
                size="4"><?php echo "Print"; ?></font></b></a></font></td>
                <br class="center">
                <?php echo strtoupper($doc_type); ?> is successfully registered:
                <?php
                echo "<br>";
                echo strtoupper($doc_type) . '/' . $doc_filing . '/' . $curYear;
                echo "<br>";
             ?>
            </div>
<?= form_fieldset_close(); ?>
</div>
<?= form_close();?>
<?php $this->load->view("admin/footer"); ?>
