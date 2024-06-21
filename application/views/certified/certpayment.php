<?php
$this->load->view("admin/header");
$this->load->view("admin/sidebar");
$this->load->view("admin/stepscert");
$salt=$this->session->userdata('certsalt');
$userdata=$this->session->userdata('login_success');
$userid=$userdata[0]->id;
$token= $this->efiling_model->getToken();
if($salt==''){
    $salt='';
}
$tab_no='';
$total=0;
$filing_no='';
$ianature='';
if($salt!=''){
    $basicia= $this->efiling_model->data_list_where('certifiedtemp','salt',$salt);
    if(!empty($basicia)){
        foreach($basicia as $row){
            $filing_no=$row->filing_no;
        }
    }
    
    
    $cert= $this->efiling_model->data_list_where('temp_certified_copy_matters','salt',$salt);
    if(!empty($cert)){
        foreach($cert as $row){
            $total+=$row->total;
        }
    } 
}

$collectoive=0;
$feesd=$this->efiling_model->data_list_where('sat_temp_payment','salt',$salt);
foreach($feesd as $row){
    $collectoive+=$row->amount;
}

?>

<form action="<?php echo base_url(); ?>ia_finalreceipt" target="_blank" class="wizard-form steps-basic wizard clearfix" id="finalsubmit" autocomplete="off" method="post" accept-charset="utf-8">
<?= form_fieldset().'<i class="icon-plus-circle2 text-danger" style="position: absolute;padding: 9px 6px;"></i>'; ?>
<input type="hidden" name="token" value="<?php echo $token; ?>" id="token">
<input type="hidden" name="filing_no" id="filing_no" value="<?php echo $filing_no; ?>">
<input type="hidden" name="saltNo" id="saltNo" value="<?php echo $salt; ?>">
<input type="hidden" name="tabno" id="tabno" value="6">
<input type="hidden" name="type" id="type" value="ia">
<input type="hidden" name="submittype" id="submittype" value="finalsave">
<div class="col-md-12" > 
<div id="payMode_review_petion">
    <div class="row">
       <div class="form-check">
      <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked">
      <label class="form-check-label" for="flexCheckChecked">
        Please check if you want to proceed .
      </label>
</div>
    </div>  
</div>  
<div class="row">
    <div class="offset-md-8 col-md-4">
        <input  type="button" value="Save and Next" class="btn btn-success" id="feedetailsubmit" onclick="rpepcpSubmitParty();">
		&nbsp;&nbsp;<input type="reset" value="Reset/Clear" class="btn btn-danger">
    </div>
</div>

<?= form_fieldset_close(); ?>
  </div>
<?= form_close();?>
<?php $this->load->view("admin/footer"); ?>
<script>
function rpepcpSubmitParty(){
     if(!document.getElementById('flexCheckChecked').checked){
            $.alert({
                  title: '<i class="fa fa-check-circle text-warning"></i>&nbsp;</b>Error!</b>',
                  content: '<p class="text-danger">Please Confirm before proceed</p>',
                  animationSpeed: 2000
                });
                
            return false;
     }
    var salt = document.getElementById("saltNo").value;
    var submittype = document.getElementById("submittype").value;
    var filing_no = document.getElementById("filing_no").value;
    var type = document.getElementById("type").value;
    var token=Math.random().toString(36).slice(2);
    var valtok='cat';
    var token_hash=HASH(token+''+valtok);
    var dataa={};
    dataa['salt']=salt,
    dataa['filing_no']=filing_no,
    dataa['token']=token,
    dataa['type']=type, 
    dataa['submittype']=submittype, 
    $.ajax({
	    dataType: 'json',
        type: "POST",
        url: base_url+'certfinalsave/'+token_hash,
        data: dataa,
        cache: false,
		beforeSend: function(){
			$('#feedetailsubmit').prop('disabled',true).val("Under proccess....");
		},
        success: function (resp) {
        	if(resp.data=='success') {
        		   setTimeout(function(){
                    window.location.href = base_url+'certreceipt';
                 }, 250); 
			}else if(resp.error != '0') {
		        $.alert({
                  title: '<i class="fa fa-check-circle text-warning"></i>&nbsp;</b>Error!</b>',
                  content: '<p class="text-danger">'+resp.display+'</p>',
                  animationSpeed: 2000
                });
			}
        },
        error: function(){
			$.alert("Surver busy,try later.");
		},
		complete: function(){
		}
	 }); 
}


paymentMode(3);
function paymode(){
	with(document.rpepcpparty){
	action = base_url+"petitionparty";
	submit();
    	document.frm.submit11.disabled = true;  
     	document.frm.submit11.value = 'Please Wait...';  
     	return true;
	}
}



function addMoreAmountrpepcp(){
    var salt = document.getElementById("saltNo").value;
    var radios = document.getElementsByName("bd");
    var bd = 0;
    for (var i = 0; i < radios.length; i++) {
        if (radios[i].checked) {
            var bd = radios[i].value;
        }
    }
    var totalamount = document.getElementById("totalamount").value;
    var remainamount = document.getElementById("remainamount").value;
    var filing_no = document.getElementById("filing_no").value;
    var type = document.getElementById("type").value;
    
    if (bd == 3) {
        var dbankname = document.getElementById("ntrp").value;
        if (dbankname == "") {
            alert("Please Enter Bank name");
            document.filing.ntrp.focus();
            return false;
        }
        var ddno = document.getElementById("ntrpno").value;
            var vasks = ddno.toString().length;
            if(Number(vasks) != 13){
               alert("Please Enter 13  Digit Challan No/Ref.No");
               document.ntrpno.focus();
               return false
             }

        if (ddno == "") {
            alert("Please Enter Challan No/Ref.No");
            document.filing.ntrpno.focus();
            return false
        }
        var dddate = document.getElementById("ntrpdate").value;
        if (dddate == "") {
            alert("Please Enter Date of Transction");
            document.filing.ntrpdate.focus();
            return false
        }
        var amountRs = document.getElementById("ntrpamount").value;
        if (amountRs == "") {
            alert("Please Enter Amount ");
            document.filing.ntrpamount.focus();
            return false
        }
    }
    var dataa={};
    dataa['dbankname']=dbankname,
    dataa['amountRs']=amountRs,
    dataa['ddno']=ddno,
    dataa['dddate']=dddate,
    dataa['bd']=bd,
    dataa['totalamount']=totalamount,
    dataa['salt']=salt,
    dataa['filing_no']=filing_no,
    dataa['token']='<?php echo $token; ?>',
    dataa['type']=type, 
    $.ajax({
	    dataType: 'json',
        type: "POST",
        url: base_url+'addMoreddrpepcp',
        data: dataa,
        cache: false,
		beforeSend: function(){
			//$('#feedetailsubmit').prop('disabled',true).val("Under proccess....");
		},
        success: function (resp) {
        	if(resp.data=='success') {
        		$('#payModepay').html(resp.display);
        		$('#remainamount').val(resp.remain);
        		$('#collectamount').val(resp.paid);
				$('#ntrpno').val('');
				$('#ntrpamount').val('');
				$('#ntrpdate').val('');
        		
			}else if(resp.error != '0') {
				$.alert(resp.error);
			}
        },
        error: function(){
			$.alert("Surver busy,try later.");
		},
		complete: function(){
		}
	 }); 
 }
     
     
function paymentMode(values) {
  if(values==3){
	  document.getElementById("payMode_review_petion").innerHTML = resp;
      document.getElementById("payMode_review_petion").style.display = 'block';
  }
}


function deletePayrpepcp(e) {
    var payid = e;
    var radios = document.getElementsByName("bd");
    var bd = 0;
    for (var i = 0; i < radios.length; i++) {
        if (radios[i].checked) {
            var bd = radios[i].value;
        }
    }
    var salt = document.getElementById("saltNo").value;
    var totalamount = document.getElementById("totalamount").value;
    var remainamount = document.getElementById("remainamount").value;
    var filing_no = document.getElementById("filing_no").value;
    var dataa={};
    dataa['payid']=payid,
    dataa['salt']=salt,
    dataa['bd']=bd,
    dataa['totalamount']=totalamount,
    dataa['remainamount']=remainamount,
   dataa['filing_no']=filing_no,
    $.ajax({
	    dataType: 'json',
        type: "POST",
        url: base_url+'addMoreddrpepcp',
        data: dataa,
        cache: false,
		beforeSend: function(){
			//$('#feedetailsubmit').prop('disabled',true).val("Under proccess....");
		},
        success: function (resp) {
        	if(resp.data=='success') {
        		  document.getElementById("payModepay").innerHTML = resp.display;
                  document.getElementById("payModepay").style.display = 'block'; 
			}else if(resp.error != '0') {
				$.alert(resp.error);
			}
        },
        error: function(){
			$.alert("Surver busy,try later.");
		},
		complete: function(){
		}
	 });
}
</script>   