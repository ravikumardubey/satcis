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
$certpartys='';
$filing_no='';
if($salt!=''){
    $basicia= $this->efiling_model->data_list_where('certifiedtemp','salt',$salt);
    $filing_no=isset($basicia[0]->filing_no)?$basicia[0]->filing_no:'';
    $tab_no=isset($basicia[0]->tab_no)?$basicia[0]->tab_no:'';
    $certpartys=isset($basicia[0]->partyids)?$basicia[0]->partyids:'';
}
$partys=explode(',', $certpartys);
$partytype=isset($basicia[0]->partyType)?$basicia[0]->partyType:'1';
$selected_radio1=isset($_REQUEST['partyType'])?$_REQUEST['partyType']:$partytype;
$partyType = $partytype;
if ($partyType == '2') {
    $flags = 'R';
} else if ($partyType == '1') {
    $flags = 'P';
}
$disabled='';
?>  
  
  <div class="card" style="width: 100%; padding: 0px 12px;border-top: 0px;margin-top: 0px; border-top-right-radius: 0px; border-top-left-radius: 0px;">            
    <?= form_open(false,['class'=>'wizard-form steps-basic wizard clearfix','id'=>'addMasterPaper','autocomplete'=>'off','onsubmit'=>'return upd_master_paper();']) ?>
        <div class="content clearfix" id="document_filing_div_id">
            <?= form_fieldset('<small class="fa fa-upload"></small>&nbsp;&nbsp; Cetified Copy '); ?>

                
        <input type="hidden" name="token" value="<?php echo $token; ?>" id="token">
	    <input type="hidden" name="filing_no" id="filing_no" value="<?php echo $filing_no; ?>">
	    <input type="hidden" name="saltNo" id="saltNo" value="<?php echo $salt; ?>">
	    <input type="hidden" name="tabno" id="tabno" value="3">
	     <input type="hidden" name="type" id="type" value="cert">
	 
   <div id="matter">
     <div class="row">
          <div class="col-lg-6" >
				 <div><label for="name"><span class="custom"><font color="red">*</font></span>Matter</label></div>
				 <div>
					<select name="matterId" class="form-control" id="matterId" onchange="opendiv();">
						<option value="" selected="selected">Select Matter</option>
						<option value="1">Apply of Certified Copy of Order/Judgment</option>
						<option value="2">Apply of Certified Copy of Appeal Book</option>
					</select>
				</div>
		  </div>
		  
	</div> 
	
	<input type="hidden" name="cnt" id="cnt" value="1">
	<!-----Section One-------->
	<div id="orderId" style="display: none">
	<div id="error_div"></div>
	    <div class="col-lg-12">
			<div class="row" >
				<div class="col-lg-3">
					<div><label for="dtoforder"><span class="custom"><font color="red">*</font>Date Of Order:</span></label></div>
					<div><input  type="text"  name="dtoforder0"  id="dtoforder0" class="form-control form-control datepicker" maxlength="12" value=""  onkeypress="return isNumberKey(event)" autocomplete="off"/></div>
				</div>
				<div class="col-lg-3">
					<div><label for="nopage"><span class="custom"><font color="red">*</font>No  Of Pages:</span></label></div>
					<div><input type="text" name="nopage0" id="nopage0" class="form-control" maxlength="4" value="" onkeyup="calculate();"  onkeypress="return isNumberKey(event)" /></div>
				</div>
				<div class="col-lg-3">
					<div><label for="noset"><span class="custom"><font color="red">*</font>No.Of set:</span></label></div>
					<div><input type="text" name="noset0" id="noset0" class="form-control" maxlength="4" value="" onkeyup="calculate();"  onkeypress="return isNumberKey(event)" /></div>
				</div>
				<div class="col-lg-3">
					<div><label for="total_amount"><span class="custom"><font color="red">*</font>Total:</span></label></div>
					<div></div><input type="text" name="total0" id="total0" class="form-control"  maxlength="4" value="" onkeypress="return isNumberKey(event)"  onkeypress="return isNumberKey(event)" readonly />
				</div>
			</div>	
	   	</div>
	   	
	   	<div class="col-lg-6">
			<div style="margin-top: 30px;">
				<label for="name"><span class="custom"></span></label>
				<button type="button" onclick="addMore();" class="btn btn-success">Add More</button>
			</div>
	   </div> 
	</div>
	<!-----Section One ENd-------->
	<!-----Section Two-------->		
	<div  id="appealBook" style="display: none">
	    <div class="col-lg-12">
           <div class="row">
				<div class="col-lg-2">
					<div><label for="dtoforder2"><span class="custom"><font color="red">*</font>Starting No of Page:</span></label></div>
					<div><input type="text"  name="nopage20" id="nopage20" maxlength="12" value="" class="form-control"    onkeypress="return isNumberKey(event)"  autocomplete="off"/></div>
				</div>
				<div class="col-lg-2">
					<div><label for="nopages2"><span class="custom"><font color="red">*</font>Ending Page:</span></label></div>
					<div><input type="text" name="end_nopage20" id="end_nopage20" class="form-control" maxlength="4" value="" onblur="calculate1();" onkeypress="return isNumberKey(event)" /></div>
				</div>
				<div class="col-lg-2">
					<div><label for="noset2"><span class="custom"><font color="red">*</font>Total Page:</span></label></div>
					<div><input type="text" name="total_page20" id="total_page20" class="form-control" maxlength="4" value="" onkeyup="calculate1();"  onkeypress="return isNumberKey(event)"/></div>
				</div>
				<div class="col-lg-3">
					<div><label for="total_amount2"><span class="custom"><font color="red">*</font>No. of sets:</span></label></div>
					<div><input type="text" name="noset20" id="noset20" class="form-control"	maxlength="4" value=""   onkeyup="calculate1();" onkeypress="return isNumberKey(event)" /></div>
				</div>
				
				<div class="col-lg-3">
				    <div><label for="noset2"><span class="custom"><font color="red">*</font>Amount:</span></label></div>
					<input type="hidden" name="totalamount" id="totalamount2" class="form-control" maxlength="4" value="" readonly />
				    <input type="text" name="total20" id="total20" class="form-control" maxlength="4" value="" readonly />
				</div>
			</div>				
	   </div> 
	   		<div class="col-lg-6">
			<div style="margin-top: 30px;">
				<label for="name"><span class="custom"></span></label>
				<button type="button" onclick="addMore();" class="btn btn-success">Add</button>
			</div>
	   </div> 
	</div>
	<br>
</div>
	<div class="row">
        <div class="offset-md-8 col-md-4">
            <input  type="button" value="Save and Next" id='mattersave' class="btn btn-success" onclick="mattersubmit();">
    		&nbsp;&nbsp;<input type="reset" value="Reset/Clear" class="btn btn-danger">
        </div>
    </div>
</div>
<div>
		<table class="table">
  <thead>
    <tr>
      <th scope="col">SR.No.</th>
      <th scope="col">Matter</th>
      <th scope="col">Order Date</th>
      <th scope="col">Not Set</th>
      <th scope="col">Amount</th>
      <th scope="col">Delete</th>
    </tr>
  </thead>
  <tbody>
  <?php 
  $cert= $this->efiling_model->data_list_where('temp_certified_copy_matters','salt',$salt);
  $i=1;
  foreach($cert as $val){
      if($val->meta_type=='1'){
          $valss="Apply of Certified Copy of Order/Judgment";
      }
      if($val->meta_type=='2'){
          $valss="Apply of Certified Copy of Appeal Book";
      }
  ?>
    <tr>
      <th scope="row"><?php echo $i; ?></th>
      <td><?php echo $valss; ?></td>
      <td><?php echo $val->order_date; ?></td>
      <td><?php echo $val->no_set; ?></td>
      <td><?php echo $val->total; ?></td>
      <td><a href="#" onclick="deletecert('<?php echo $val->id; ?>');">Delete</a></td>
    </tr>
 <?php $i++;} ?>
  </tbody>
</table>
</div>
</div>
</div>
<?php $this->load->view("admin/footer"); ?>
<script>



function addMore() {
	var patyAddId="";
    var count1=0;
	var partyType = '<?php echo $selected_radio1 ?>';
	var filingNo =document.getElementById('filing_no').value;	
    if(partyType!='3'){	
    	if(partyType < 1){
            alert("Please check party type");
            return false;
    	 }
    }
	var meta_type=document.getElementById('matterId').value; 
	if(meta_type==''){
		 alert("Please Select matter.!");
         return false;
	}

	if(meta_type=='1'){
    	var dtoforder=document.getElementById('dtoforder0').value;
    	var nopage=document.getElementById('nopage0').value;
    	var noset=document.getElementById('noset0').value;
    	var total_amount=document.getElementById('total0').value;
    	  if (dtoforder == '') {
              alert("Enter Order Date.!");
              return false;
          }
    	  if (nopage == '') {
              alert("Enter Order page no.!");
              return false;
          }
    	  if (noset == '') {
              alert("Enter Order page no.!");
              return false;
          }
    	  if (total_amount == '') {
              alert("Enter Order page no.!");
              return false;
          }  
		var dataa={};
	    dataa['patyAddId']=partyType,
	    dataa['filingNo']=filingNo,
	    dataa['matterId']=meta_type,
	    dataa['orderdate']=dtoforder,
	    dataa['total_page2']=nopage,
	    dataa['no_set']=noset,
	    dataa['total']=total_amount,
	    dataa['salt']='<?php echo $salt; ?>',
        $.ajax({
    		type: 'post',
    		url: base_url+'copycertifiedCopysave',
    		data: dataa,
    		success: function(retrn){
    			var resp = JSON.parse(retrn);
    			if(resp.data=='success'){
				     setTimeout(function(){
	                    window.location.href = base_url+'matter';
	                 }, 250); 
    			}else{
    				alert(resp.massage);
            	}
    		}
    	 });
	}
	// matters 2
	if(meta_type==2){
		var nopage2=document.getElementById('nopage20').value;
		var end_nopage2=document.getElementById('end_nopage20').value;
		var total_page2=document.getElementById('total_page20').value;
		var noset2=document.getElementById('noset20').value;
		var total_amount=document.getElementById('total20').value;
        if (nopage2 == '') {
              alert("Enter No Of Page.!");
              return false;
        }
        if (end_nopage2 == '') {
              alert("Enter Ending no of page.!");
              return false;
        }
        if (total_page2 == '') {
              alert("Enter total no page.!");
              return false;
        }
        if (noset2 == '') {
              alert("Enter no of set .!");
              return false;
        }  
		var dataa={};
	    dataa['patyAddId']=partyType,
	    dataa['filingNo']=filingNo,
	    dataa['matterId']=meta_type,
	    dataa['nopage2']=nopage2,
	    dataa['end_nopage2']=end_nopage2,
	    dataa['total_page2']=total_page2,
	    dataa['no_set']=noset2,
	    dataa['total']=total_amount,
	    dataa['salt']='<?php echo $salt; ?>',
	    $.ajax({
			type: 'post',
			url: base_url+'copycertifiedCopysave',
			data: dataa,
			success: function(retrn){
				var resp = JSON.parse(retrn);
				var resp = JSON.parse(retrn);
    			if(resp.data=='success'){
				     setTimeout(function(){
	                    window.location.href = base_url+'matter';
	                 }, 250); 
    			}else{
    				alert(resp.massage);
            	}
			}
		 });
	 }
}





function opendiv(){
	var matterId=document.getElementById("matterId").value; 
	if(matterId==1){
		document.getElementById("orderId").style.display ='block';
		document.getElementById("appealBook").style.display ='none';
	}if(matterId==2){
		document.getElementById("appealBook").style.display ='block';
		document.getElementById("orderId").style.display ='none';
	}
}


//calculate fields count Section1 
function calculate(){ 
    for(var i = 0; i <= 5; i++){
	 	var val1 = document.getElementById('nopage'+i).value;
	 	var val2 = document.getElementById('noset'+i).value; 
	 	var totalamount=parseInt(val1)*parseInt(val2) * parseInt(25);
	 	if(Number.isNaN(totalamount)){
			document.getElementById('total'+i).value=0
	 	}else{
	 		document.getElementById('total'+i).value=totalamount
    	}
		totalcalculate(i);
    } 
}
// Total count Section 1 
function totalcalculate(j){
	 var total=0;
	  for(var i = 0; i <= j; i++){
  	 	total +=parseInt(document.getElementById('total'+i).value);
  	 	if(Number.isNaN(total)){
  		 	document.getElementById('total_amount').value=0; 
  	 	}else{
  	 		document.getElementById('total_amount').value=total; 
      	}
      }
}

// calculate fields count Section2
function calculate1(){ 
    for(var i = 0; i <= 5; i++){
	 	var val1 = document.getElementById('nopage2'+i).value;
	 	var val2 = document.getElementById('end_nopage2'+i).value; 
	 	var val3 = document.getElementById('noset2'+i).value; 
	 	if(document.getElementById('nopage2'+i).value >  document.getElementById('end_nopage2'+i).value){
    	 	alert("End page should  greater than start page number.");
    	 	return false;
    	} 
	 	var totalamount= parseInt(val2) -  parseInt(val1)+1;
	 	if(Number.isNaN(totalamount)){
			document.getElementById('total_page2'+i).value=0
	 	}else{
	 		document.getElementById('total_page2'+i).value=totalamount;
    	}
		var totalamount_amount=parseInt(totalamount) * parseInt(val3);
	 	if(Number.isNaN(totalamount_amount)){
			document.getElementById('total2'+i).value=0
	 	}else{
	 		document.getElementById('total2'+i).value=totalamount_amount * 25;
    	}
		totalcalculate1(i);
    } 
}
// Total count Section2 
function totalcalculate1(j){
	 var total=0;
	  for(var i = 0; i <= j; i++){
  	 	total +=parseInt(document.getElementById('total2'+i).value);
  		 document.getElementById('total_amount2').value=total; 
      }
}


function deletecert(val){
   var dataa = {};
   dataa['salt']='<?php echo $salt; ?>',
   dataa['id']=val,
   dataa['token']='<?php echo $token; ?>',
    $.ajax({
		type: 'post',
		url: base_url+'deletecopycertified',
		data: dataa,
		success: function(retrn){
			var resp = JSON.parse(retrn);
   			if(resp.data=='success'){
				     setTimeout(function(){
	                    window.location.href = base_url+'matter';
	                 }, 250); 
   			}else{
   				alert(resp.massage);
           	}
		}
	 });
 }

function mattersubmit(){
   var token=Math.random().toString(36).slice(2); 
   var token_hash=HASH(token+'mattersave');
   var dataa = {};
   var tabno= $('#tabno').val();
   var type= $('#type').val();
   var filing_no= $('#filing_no').val();
   dataa['salt']='<?php echo $salt; ?>',
   dataa['token']=token,
   dataa['filing_no']=filing_no,
   dataa['type']=type,
   dataa['tabno']=tabno,
    $.ajax({
		type: 'post',
		url: base_url+'savematter/'+token_hash,
		data: dataa,
		beforeSend: function(){
			$('#mattersave').prop('disabled',true).val("Under proccess....");
		},
		success: function(retrn){
			var resp = JSON.parse(retrn);
   			if(resp.data=='success'){
			     setTimeout(function(){
                    window.location.href = base_url+'certuploaddoc';
                 }, 250); 
   			}else{
   				alert(resp.massage);
           	}
		}
	 });
}

</script>