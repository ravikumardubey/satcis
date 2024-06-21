<?php
$this->load->view("admin/header");
$this->load->view("admin/sidebar");
$this->load->view("admin/stepsia");
$salt=$this->session->userdata('iasalt');
$userdata=$this->session->userdata('login_success');
$userid=$userdata[0]->id;
$token= $this->efiling_model->getToken();
$vals=$this->session->userdata('iadetail');
if(!empty($vals) && is_array($vals)){
    $url=$vals['url'];
    $ias=$vals['iaregisterd'];
}
 error_reporting(0);
?>
<?= form_open(false,['class'=>'wizard-form steps-basic wizard clearfix','name'=>'rpepcpbascidetail','id'=>'rpepcpbascidetail','autocomplete'=>'off']) ?>
       <?= form_fieldset('Search DFR ').'<i class="icon-plus-circle2 text-danger" style="position: absolute;padding: 9px 6px;"></i>'; ?>
		<input type="hidden" name="token" value="<?php echo $token; ?>" id="token">
	    <input type="hidden" name="saltNo" id="saltNo" value="<?php echo $salt; ?>">
	    <input type="hidden" name="tabno" id="tabno" value="1">
        <div class="col-md-12" >
    		<div class="row">
    		<?php            
                $html='<td colspan="1">
                 <div><a target="_blank" href="'.$url.'" style="color: #3F33FF" ><b>Print</b></a></div>
                 </td>
                 <div class="col-md-12">
                 <font color="#0000FF" size="5"> MA is successfully registered:  '.$ias.'</font>
                 </div>';
                echo $html; ?>
            </div>
<?= form_fieldset_close(); ?>
</div>
<?= form_close();?>
<?php $this->load->view("admin/footer"); ?>
