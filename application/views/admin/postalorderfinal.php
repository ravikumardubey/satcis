<?php 

$bd=htmlspecialchars($app);
if($bd==3)
	$name="Online Payment";	
?>

<script>
$(document).ready(function(){
	 $(".datepicker" ).datepicker({ dateFormat: "dd-mm-yy" });
	}); 
</script>
	
<?php if($bd==3){?>
		<div class="row">
            <div class="col-lg-3">
                <div>
                	<label for="name"><span class="custom"><span><font color="red">*</font></span>Bharat Kosh (NTRP) </span></label>
                    <?= form_input(['name'=>'ntrp','class'=>'form-control','id'=>'ntrp','placeholder'=>'NTRP','pattern'=>'[A-Za-z0-9_]{1,200}','onKeyValidate'=>'onKeyValidate(event,alpha);','maxlength'=>'200','value'=>'NTRP','title'=>'Designation only alphanumeric']) ?>
                </div>
             </div>   
             
             <div class="col-lg-3"> 
                <div>
                	<label for="name"><span class="custom"><span><font color="red">*</font></span>Date of Transction</span></label>
                    <?= form_input(['name'=>'ntrpdate','class'=>'form-control datepicker','id'=>'ntrpdate','placeholder'=>'ntrpdate','maxlength'=>'200','title'=>'Designation only alphanumeric','readonly'=>'1']) ?>
                </div>
            </div>
            
            <div class="col-lg-3">
                <div>	
                	<label for="name"><span class="custom"><span><font color="red">*</font></span>Challan/Ref. Number</span></label>
                    <?= form_input(['name'=>'ntrpno','class'=>'form-control','id'=>'ntrpno','placeholder'=>'ntrp no','pattern'=>'[0-9]{1,13}','onkeypress'=>'return  isNumberKey(event)','maxlength'=>'13','title'=>'Designation only alphanumeric']) ?>
                </div>
            </div>
            
             <div class="col-lg-3">
                <div>
                	<label for="name"><span class="custom"><span><font color="red">*</font></span>Amount in Rs.</span></label>
               	    <?= form_input(['name'=>'ntrpamount','class'=>'form-control','id'=>'ntrpamount','onkeypress'=>'return isNumberKey(event)','placeholder'=>'ntrp amount','pattern'=>'[0-9]{1,7}','onKeyValidate'=>'isNumberKey(event,alpha);','maxlength'=>'7','title'=>'Designation only alphanumeric']) ?>
                </div>
            </div>
        </div>
        <div class="row ">
                    <div class="col-md-4" style="margin-top:25px;margin-left: 1022px;">
   		 	<input type="button" name="btnSubmit" id="btnSubmit" value="Final Submit" class="btn btn-success btn-lg float-left" onclick="finalsubmit()">
          </div>
          
        
          
        </div>
<?php } ?>



