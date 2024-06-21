<?php $this->load->view("admin/header"); 
$this->load->view("admin/sidebar"); 
$this->load->view("admin/stepsrpepcp"); 
$salt=$this->session->userdata('rpepcpsalt');
$token= $this->efiling_model->getToken();
$partytype='';
$filingno='';
$tab_no='';
$type='';
$iano='';
$party_ids='';
if($salt!=''){
    $basicrp= $this->efiling_model->data_list_where('rpepcp_reffrence_table','salt',$salt);
    $iano=isset($basicrp[0]->totalNoia)?$basicrp[0]->totalNoia:'';
    $anx=isset($basicrp[0]->totalNoAnnexure)?$basicrp[0]->totalNoAnnexure:'';
    $type=isset($basicrp[0]->type)?$basicrp[0]->type:'';
    $order_date=isset($basicrp[0]->order_date)?$basicrp[0]->order_date:'';
    if($order_date!=''){
        $orderd=date('d-m-Y',strtotime($order_date));
    }
    $filingno=isset($basicrp[0]->filing_no)?$basicrp[0]->filing_no:'';
    $tab_no=isset($basicrp[0]->tab_no)?$basicrp[0]->tab_no:'';
    $type=isset($basicrp[0]->type)?$basicrp[0]->type:'';
    $partyType=isset($basicrp[0]->partyType)?$basicrp[0]->partyType:'';
    $party_ids=isset($basicrp[0]->party_ids)?$basicrp[0]->party_ids:'';
    $ianature=isset($basicrp[0]->iaNature)?$basicrp[0]->iaNature:'';
}
$partyType=isset($_REQUEST['partyType'])?$_REQUEST['partyType']:$partyType;
if($partyType=='1'){
    $partytype='1';
}
if($partyType=='2'){
    $partytype='2';
}
if($partyType=='3'){
    $partytype='3';
}
if($salt!=''){
?>

<?php } ?>
<?= form_open(false,['class'=>'wizard-form steps-basic wizard clearfix','autocomplete'=>'off']) ?>
<?= form_fieldset().'<i class="icon-plus-circle2 text-danger" style="position: absolute;padding: 9px 6px;"></i>'; ?>
<input type="hidden" name="token" value="<?php echo $token; ?>" id="token">
<input type="hidden" name="saltNo" id="saltNo" value="<?php echo $salt; ?>">
<input type="hidden" name="filingno" id="filingno" value="<?php echo $filingno; ?>">
<input type="hidden" name="type" id="type" value="<?php echo $type; ?>">
<input type="hidden" name="tabno" id="tabno" value="<?php echo '10'; ?>">
<input type="hidden" name="submittype" id="submittype" value="finalsave">
<div class="col-md-12" >

<fieldset style="padding-right: 0px;"><legend class="customlavel2">Payment Detail for <?php echo strtoupper($type); ?></legend>
  <div  id="divstype">
     <div class="row">
        <div class="col-md-12">
            <script>
            get_date1();
            paymentMode_review_petion('3');
            </script>
            <?php
            $totalann = $anx;
           if ($type == 'rp') {
               $court_fee = '0'; // 30000;
               $processfee ='0'; //  2000;
            }

            ?>

        <div class="row">
            <div class="col-lg-3">
                <div><label for="name"><span class="custom"><span><font color="red"></font></span>Court Fee</span></label>
                </div>
                <div><font color="red"><b><?php echo $court_fee; ?></b></font></div>
            </div>
            <div class="col-lg-3">
                <div><label for="name"><span class="custom"><span><font color="red"></font></span>Process Fee</span></label>
                </div>
                <div><font color="red"><b><?php echo $processfee; ?></b></font></div>
            </div>
            <div class="col-lg-3">
                <div><label for="name"><span class="custom"><span><font color="red"></font></span>Annexure/Other Fee/IA Fee</span></label>
                </div>
                <div><font color="red"><b><?php echo '0'; ?></b></font></div>
            </div>
            <div class="col-lg-3">
                <div><label for="name"><span class="custom"><span><font color="red"></font></span>Total Fee</span></label>
                </div>
                <div><font color="red"><b><?php echo '0'; ?></b></font></div>
            </div>
        </div>
    </div>
  </div>          	 
</div>

</fieldset>


<div class="row">
    <div class="offset-md-8 col-md-4" >
        <input type="button" value="Final Save" class="btn btn-success" onclick="rpepcpSubmitParty();">
		&nbsp;&nbsp;<input type="reset" value="Reset/Clear" class="btn btn-danger">
    </div>
</div>

<?= form_fieldset_close(); ?>
  </div>
<?= form_close();?>
<?php $this->load->view("admin/footer"); ?>
<script>

paymentMode(3);
function online(){
document.getElementById("payMode_review_petion").style.display = 'none';
}

function paymode(){
	with(document.rpepcpparty){
	action = base_url+"petitionparty";
	submit();
    	document.frm.submit11.disabled = true;  
     	document.frm.submit11.value = 'Please Wait...';  
     	return true;
	}
}


function rpepcpSubmitParty(){
    var salt = document.getElementById("saltNo").value;
    var radios = document.getElementsByName("bd");
    var submittype = document.getElementById("submittype").value;
    
    var bd = 0;
    for (var i = 0; i < radios.length; i++) {
        if (radios[i].checked) {
            var bd = radios[i].value;
        }
    }
    var filing_no = document.getElementById("filingno").value;
    var type = document.getElementById("type").value;
    var dataa={};
    dataa['salt']=salt,
    dataa['filing_no']=filing_no,
    dataa['token']='<?php echo $token; ?>',
    dataa['type']=type, 
    dataa['submittype']=submittype, 
    $.ajax({
	    dataType: 'json',
        type: "POST",
        url: base_url+'rpepcpsave',
        data: dataa,
        cache: false,
		beforeSend: function(){
			//$('#feedetailsubmit').prop('disabled',true).val("Under proccess....");
		},
        success: function (resp) {
        	if(resp.data=='success') {
        		 setTimeout(function(){
                    window.location.href = base_url+'petitionReceipt';
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
    var filing_no = document.getElementById("filingno").value;
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
				$.alert(resp.massage);
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
	 var dataa={};
     dataa['app']=values;
     dataa['type']='review';
      $.ajax({
          type: "POST",
          url: base_url+"rpcpeppayment",
          data: dataa,
          cache: false,
          success: function (resp) {
        	  document.getElementById("payMode_review_petion").innerHTML = resp;
              document.getElementById("payMode_review_petion").style.display = 'block';
          },
          error: function (request, error) {
			$('#loading_modal').fadeOut(200);
          }
      }); 
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
    var filing_no = document.getElementById("filingno").value;
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
        	    setTimeout(function(){ 
                     window.location.reload(true);
	                   $('#rightbar').empty().load(base_url+'/loadpage/petitionPay'); 
       			}, 500);
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


$(function() {  
    $(".demoDatepicker").datepicker({
        dateFormat: 'dd/mm/yy',
        changeMonth: true,
        changeYear: true,
        constrainInput: false
    });
});
</script>   