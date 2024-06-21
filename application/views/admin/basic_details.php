<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view("admin/header");
$this->load->view("admin/sidebar");
$token= $this->efiling_model->getToken();
$salt=$this->session->userdata('salt');
$apealtyp='';
$ipenalty='';
$opauthority='';
$iorderdate='';
$rimpugnedorder='';
$iordernumber='';
$delayinfiling='';
$app= $this->efiling_model->data_list_where('sat_temp_appellant','salt',$salt);
if(!empty($app) && is_array($app)){
    foreach($app as $val){
        $apealtyp=$val->apeal_type;
        $act=$val->act;
        $ipenalty=$val->ipenalty;
        $opauthority=$val->opauthority;
        $iorderdate=date('d-m-Y',strtotime($val->iorderdate));
        $rimpugnedorder=date('d-m-Y',strtotime($val->rimpugnedorder));
        $iordernumber=$val->iordernumber;
        $delayinfiling=$val->delayinfiling;
    }
}

$checked1='checked';
$checked2='';
$checked3='';
if($apealtyp=='SEBI'){
    $checked1="checked";
}
if($apealtyp=='IRDAI'){
    $checked2="checked";
}
if($apealtyp=='PFRDA'){
    $checked3="checked";
}
if($checked1!=''){
    $apealtyp="SEBI";
}
?>
<script src="<?=base_url('asset/admin_js_final/customs.js')?>"></script> 
<div id="rightbar"> 
<?php  include 'steps.php'; ?>
 <?= form_fieldset('Basic Details').
'<i class="icon-plus-circle2 text-danger" style="position: absolute;padding: 9px 6px;"></i>'.
'<div class="date-div text-success">Date & Time : &nbsp;<small>'.date('D M d, Y H:i:s').'&nbsp;IST</small></div>';
?>
	<div class="content" style="padding-top:0px;">
		<div class="row">
			<div class="card" style="width: 100%; padding: 0px 12px;border-top: 0px;margin-top: 0px; border-top-right-radius: 0px; border-top-left-radius: 0px;">
    			<?= form_open(false,['class'=>'wizard-form steps-basic wizard clearfix','id'=>'chkList','autocomplete'=>'off']) ?>
                    <input type="hidden"  id="tabno" name="tabno" value="<?php echo '1'; ?>">
                    <input type="hidden" name="token" value="<?php echo $token; ?>" id="token"> 
                      <div class="content clearfix" style="background-color: #ffe0e0;">
                         <div class="row">      
                             <div class="col-md-3"> 
                				 <input type = "radio" name = "appeal_type" id = "appeal_type" value ="SEBI" <?php echo $checked1; ?> onclick="getregulator(this.value);">
                				 <label for="name"><span class="custom"><sup class="text-danger">*</sup></span>SEBI Appeal</span></label>
            				 </div>
            				  <div class="col-md-3">
                				  <input type = "radio"name = "appeal_type" id ="appeal_type" value ="IRDAI" <?php echo $checked2; ?>  onclick="getregulator(this.value);">
                				  <label for="name"><span class="custom"><sup class="text-danger">*</sup></span>IRDA Appeal </span></label>
            				 </div>
            				 <div class="col-md-3">
                				  <input type = "radio"name = "appeal_type" id ="appeal_type" value ="PFRDA" <?php echo $checked3; ?>  onclick="getregulator(this.value);">
                				  <label for="name"><span class="custom"><sup class="text-danger">*</sup></span>PFRDA Appeal </span></label>
            				 </div>
        				</div>
    				</div>
    			<?php   $actval=$this->efiling_model->data_list_where('master_act','status',1); ?>
    		      <div class="row">
    				<div class="col-md-4">
    					<div required">
    					<label for="name"><span class="custom">ACTS/ Rules </span>: <sup class="text-danger">*</sup></label>
						  <select name="relevant_acts" id="relevant_acts" class="form-control" required>
    						     <option value="" selected>----Select Act-------</option>
    						  <?php if(!empty($actval)){
    						      foreach($actval as $row){?>
        						  <option value="<?php echo $row->id; ?>" <?php if($act='1'){echo "selected";} ?>><?php echo $row->act_full_name; ?></option>
        				          <?php } 
        						  }?>
						  </select>
    					</div>
    				</div>
    				<div class="col-md-4">
    					<div required">
    					<label for="name"><span class="custom"> Order Passing Authority</span>  :<sup class="text-danger">*</sup></label>
						   <select name="order_passing" id="order_passing" class="form-control" required>
						  </select>
    					</div>
    				</div>
        			<div class="col-md-4">
                        <div required">
                            <label>Imposition of Penalty : <sup class="text-danger">*</sup> </label>
                             <input type="text" name="penalty" id="penalty"  class="form-control" required="true" maxlength="12" 
                             pattern="[0-9]{1,10}" title="Kindly enter [0-9], Max 12 Characters is allowed" value="<?php echo $ipenalty; ?>"  onkeypress="return isNumberKey(event)"  placeholder="PENALTY" onkeypress="serchrecordvalapp(this.value);">
                        </div>
                    </div>
    		   </div>
    		<br>
			<hr>
			 <div style="padding:font-weight: 600;font-size:20px;  max-width: 100%;" align="center">
				<div class="col-md-4"><label class="text-danger">IMPUGNED ORDER DETAILS</label><br><br></div>
			</div>
		
	        <div class="row">
	        
	        	<div class="col-md-3">
					<label class="control-label" for="impugned_no"><span class="custom">Impugned Order Number :</label>
					<div class="input-group mb-3 mb-md-0">
                    <input name="impugned_no" id="impugned_no" maxlength="30" placeholder="Impugned Order Number" class="form-control"    value="<?php echo $iordernumber; ?>"> 
					</div>
				</div>
				
				<div class="col-md-3">
					<label for="name"><span class="custom"><sup class="text-danger">*</sup></span> Impugned Order Date :</span></label>                            
                     <div class="input-group mb-3 mb-md-0">
                        <?= form_input(['name'=>'impugned_date','id'=>"impugned_date",'value'=>$iorderdate,'class'=>'form-control datepicker','placeholder'=>'Impugned Order Date ','required'=>true,'title'=>'Impugned Order Date .','readonly'=>true]) ?>
                     </div>
                 </div>
				<div class="col-md-3">
					<label for="name"><span class="custom">Date of receipt of  Impugned Order </span>:</label>
						<div class="input-group mb-3 mb-md-0">
                        <?= form_input(['name'=>'receipt_impdate','id'=>"receipt_impdate",'value'=>$rimpugnedorder,'class'=>'form-control datepicker',
                            'placeholder'=>'Date of receipt of Impugned Order','title'=>'Date of receipt of Impugned Order.','readonly'=>true]) ?>
                        </div>
				</div>
			
				<div class="col-md-3">
					<label class="control-label" for="delay_filing"><span class="custom">Delay in Filing :</label>
					<div class="input-group mb-3 mb-md-0">
                    <input name="delay_filing" id="delay_filing" placeholder="Delay in Filing" class="form-control" value="<?php echo $delayinfiling; ?>"> 
					</div>
				</div>					
    		</div>
    		<div id="delaymsg" style="margin-left: 800px;color:#0b0b0c;font-size: 22px;"></div>	
    	</div>
  </div>  
    <div class="row">
        <div class="offset-md-8 col-md-4 ">
        	<i class="icon-file-plus" ></i><input type="button" value="Save &amp; Next" class="btn btn-success buttonshift" onclick="savenext();" >
            <i class="icon-trash-alt"></i><input type="reset" value="Reset/Clear" class="btn btn-danger buttonshift" >
         </div>
    </div>
</div>


</fieldset> 	
<?= form_close();?>	
<script>
$( document ).ready(function() {
    getregulator('<?php echo $apealtyp; ?>');
});

$('#receipt_impdate').change(function(){
	if($('#receipt_impdate').val()!=''){
        	const start= $(this).datepicker("getDate");
        	const today=new Date();
        	days = new Date(today- start) / (1000 * 60 * 60 * 24);
        	days=Math.round(days);
        	if(days <= 45){
        		$("#delay_filing").val('0');		
        		$('#delaymsg').html('No delay ');	
        	}
        	else if(days > 45) {
        		$("#delay_filing").val(days-45);
        		$('#delaymsg').html('Delay :'+(days-45)+ ' days');
        	}
	} else if($('#impugned_date').val()!=''){
		if($('#receipt_impdate').val() == '') {
    		var start= $("#impugned_date").datepicker("getDate");
    		var today=new Date();		
    		days = new Date(today- start) / (1000 * 60 * 60 * 24);
    		days=Math.round(days);
    		
    		if(days <= 45){
    			$("#delay_filing").val('0');	
    			$('#delaymsg').html('No delay ');	
    		}
    		else if(days > 45) {
    			$("#delay_filing").val(days-45);
    		    $('#delaymsg').html('Delay :'+(days-45)+ ' days');
    		}
    	} 
	}
});



$('#impugned_date').datepicker({
	    dateFormat: 'dd-mm-yy',
		maxDate:new Date(),
        onSelect: function(date){
            $("#receipt_impdate").datepicker( "option", "minDate", $("#impugned_date").datepicker("getDate") );
            $("#receipt_impdate").datepicker( "option", "maxDate", 'new Date()' );
        	if($('#impugned_date').val()!=''){
            	if($('#receipt_impdate').val() == '') {
                		var start= $("#impugned_date").datepicker("getDate");
                		var today=new Date();		
                		days = new Date(today- start) / (1000 * 60 * 60 * 24);
                		days=Math.round(days);
                		if(days <= 45){
                			$("#delay_filing").val(0);	
                			$('#delaymsg').html('No delay ');	
                		}
                		else if(days > 45) {
                			$("#delay_filing").val('Delay  :'+(days-45));
                			$('#delaymsg').html(' Delay :'+(days-45)+ ' days');
                		}
            	}else if($('#receipt_impdate').val() != '') {
            		var start= $("#impugned_date").datepicker("getDate");
            		var start2= $("#receipt_impdate").datepicker("getDate");
            		if(start>=start2){
            			var today=new Date();		
                		days = new Date(today- start) / (1000 * 60 * 60 * 24);
                		days=Math.round(days);
                		if(days <= 45){
                			$("#delay_filing").val(0);	
                			$('#delaymsg').html('No delay ');	
                		}else if(days > 45) {
                			$("#delay_filing").val('Delay  :'+(days-45));
                    		$('#delaymsg').html(' Delay :'+(days-45)+ ' days');
                    	}
            		}
            	}
        	}else{
        		$("#delay_filing").val('');
        		$('#delaymsg').html('');
        
        	}
        }
	});

	$("#receipt_impdate").datepicker({
		dateFormat: 'dd-mm-yy',
	}); 

$('#receipt_impdate').change(function(){
		debugger;
		if($('#impugned_date').val()==''){
		    $('#receipt_impdate').val('');
			$.alert("Please enter impugned order date first.");
    		return false;
		}
		if($('#receipt_impdate').val()!='' && $('#impugned_date').val()!=''){
			const start= $(this).datepicker("getDate");
	        const today=new Date();
	        days = new Date(today- start) / (1000 * 60 * 60 * 24);
	        days=Math.round(days);
			if(days <= 45){
		      $("#delay_filing").val(0);		
		      $('#delaymsg').html('No delay ');	
	        }else if(days > 45) {
		               $("#delay_filing").val('Delay :'+(days-45));
		               $('#delaymsg').html('Delay :'+(days-45)+ ' days');
	                }
} else if($('#impugned_date').val()!='' && $('#receipt_impdate').val()==''){
		var start= $("#impugned_date").datepicker("getDate");
		var today=new Date();		
		days = new Date(today- start) / (1000 * 60 * 60 * 24);
		days=Math.round(days);
		
		if(days <= 45){
			$("#delay_filing").val(0);	
		$('#delaymsg').html('No delay ');	

		}
		else if(days > 45) {
			$("#delay_filing").val('Delay  :'+(days-45));
			$('#delaymsg').html('Delay :'+(days-45)+ ' days');

	} 
		
	}
});

function savenext(){
    var appeal_type='';
    var checkboxes = document.getElementsByName('appeal_type');
    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].checked) {
        	appeal_type = checkboxes[i].value;
        }
    }
    var relevantacts = document.getElementById("relevant_acts").value;
    if(relevantacts==''){
        alert("Please select ACTS/ Rules");
        return false;
    }
    
    var orderpassing =document.getElementById("order_passing").value;
    if(orderpassing==''){
        alert("Please select Order Passing Authority");
        return false;
    }

    
    var receiptimpdate = $("#receipt_impdate").val();
    if(receiptimpdate==''){
    	var receiptimpdate = '';
    }
    var penalty =document.getElementById("penalty").value;
    if(penalty==''){
        alert("Please Enter Imposition of Penalty");
        return false;
    }
    var impugneddate =document.getElementById("impugned_date").value;
    if(impugneddate==''){
        alert("Please Enter Impugned Order Number");
        return false;
    }


    
    var impugnedno =document.getElementById("impugned_no").value;
    if(impugnedno==''){
        alert("Please Enter Impugned Order Number");
        return false;
    }

    var delayfiling =document.getElementById("delay_filing").value;
    if(delayfiling=="No delay"){
    	delayfiling='0';
    }

    var dataa = {};
	dataa['appeal_type']=appeal_type;
	dataa['relevantacts']=relevantacts,
	dataa['orderpassing']=orderpassing,
	dataa['penalty']=penalty;
	dataa['impugneddate']=impugneddate;
	dataa['receiptimpdate']=receiptimpdate;
	dataa['impugnedno']=impugnedno;
	dataa['delayfiling']=delayfiling;
	$.ajax({
        type: "POST",
        url: base_url+'saveNextcheck',
        data: dataa,
        cache: false,
		beforeSend: function(){
			//$('#petitioner_save').prop('disabled',true).val("Under proccess....");
		},
        success: function (resp) {
        	var resp = JSON.parse(resp);
        	if(resp.data=='success') {
    		    setTimeout(function(){
                    window.location.href = base_url+'applicant';
                 }, 250);
			}
			else if(resp.error != '0') {
				$.alert(resp.error);
			}
        },
        error: function(){
			$.alert("Surver busy,try later.");
		}
	}); 
}




function getregulator(val){
   var appeal_type_id = val;
   var oauth='<?php echo $opauthority; ?>';
   if(appeal_type_id !='') {
  		$.ajax({
  			url: base_url+'getauthority',
  			type: 'post',
  			data: {"appeal_type_id":appeal_type_id},
  			dataType: 'json',
  			beforeSend: function(){
  				$('#order_passing').find(":selected").text("Feaching Authority.....");
  			},
  			success: function(resp){
  				if(resp.error=='0'){
  					$('#order_passing').removeAttr('disabled').empty();
  					$('#order_passing').html('<option value="">-----Select Order Passing Authority-----</option>');
  					$.each(resp.data, function(index, itemData) {
  						var option='<option value="'+itemData.order_pass_auth_id+'">'+itemData.order_passing_authority+'</option>';
  						if(oauth==itemData.order_pass_auth_id){
  							option='<option value="'+itemData.order_pass_auth_id+'" selected="selected">'+itemData.order_passing_authority+'</option>';
  						}
  						$('#order_passing').append(option);
					});
  				}
  				else {
  					$('#order_passing').find(":selected").text("-----Select Order Passing Authority-----");
  					alert(resp.error);
  				}
  			},
  			error: function(){
  				alert('Server error, try later.');
  			}
  		});
  	}
  	else {
  		$('#order_passing').attr('disabled',true).empty();
  		$('#order_passing').html('<option value="">-----Select Order Passing Authority----</option>');
  		return false;
  	}
}
</script>
</div>
<?php $this->load->view("admin/footer"); ?>
 