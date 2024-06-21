<?php 
$name='';
$bd=$_REQUEST['app'];
if($bd==2){
	$name="Post Office Details";
}if($bd==1){
	$name="Bank Details";
}
if($bd==3){
    $name="Transaction Details";
}
if($bd==3){  ?>

<div class="row">
     <div class="col-lg-4">
            <div><label for="name"><span class="custom"><span><font color="red">*</font></span> Fee</span></label></div>
        <div><input type="text" name="ntrp" id="ntrp" class="form-control" value="NTRP" onkeypress="return onKeyValidate(event,alpha);" /></div>
        <div><label for="name"><span class="custom"><span><font color="red">*</font></span>Date of Transction</span></label></div>
        <div><input type="text" name="ntrpdate" id="ntrpdate"  placeholder="02/02/1989" autocomplete="off" class="form-control alert-danger datepicker" readonly="true" onclick="get_date1(this);"  value="" /></div>
    </div>
    <div class="col-lg-4">
        <div><label for="name"><span class="custom"><span><font color="red">*</font></span>Challan/Ref. Number</span></label></div>
        <div><input type="text" maxlength="13" name="ntrpno" id="ntrpno" class="form-control" value="" onkeypress="return isNumberKey(event)" /></div>
    </div>
    <div class="col-lg-4">
        <div><label for="name"><span class="custom"><span><font color="red">*</font></span>Amount in Rs.</span></label></div>
        <div><input type="text" name="ntrpamount" id="ntrpamount" class="form-control" value="" maxlength="7" autocomplete="off" onkeypress="return isNumberKey(event)"/></div>
    </div>
</div>      
<?php  } ?>
<br>
<div class="row ">
   <div class="col-md-4">
	 <input type="button" name="btnSubmit" id="btnSubmit" value="Add Amount" class="btn btn-success btn-lg float-left" onclick="addMoreAmountrpepcp()"/>
    </div>
</div> 
<div id="add_amount_list">
</div>




