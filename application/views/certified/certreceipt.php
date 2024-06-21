<?php
$this->load->view("admin/header");
$this->load->view("admin/sidebar");
$this->load->view("admin/stepscert");
$salt=$this->session->userdata('certsalt');
$userdata=$this->session->userdata('login_success');
$userid=$userdata[0]->id;
$token= $this->efiling_model->getToken();

$cert=$this->session->userdata('certdetail');
if(empty($cert)){
    redirect(base_url('certbasicdetail'));
}
$certified_no=$cert['certified_no'];
$filingNo=$cert['filingNo'];


?>
<div class="content" style="padding-top:0px;">
<div class="card"  id="dvContainer" style="width: 100%; padding: 0px 12px;border-top: 0px;margin-top: 0px;  border-top-left-radius: 0px;">
    
<fieldset id ="" style="display: block"><legend class="customlavelsub">Payment Receipt.</legend>
        <div class="col-sm-4 div-padd">
    		<label for="name"><span class="custom"></span>
    		<a target="_blank" href="<?php echo base_url(); ?>certifyreceiptview/<?php echo base64_encode($certified_no); ?>/<?php echo base64_encode($filingNo)?>">Receipt</a>
        </div>
</fieldset>
</div>
<?= form_close();?>    
</div>

<?php $this->load->view("admin/footer"); ?>