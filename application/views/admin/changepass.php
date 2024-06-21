<?php $this->load->view("admin/header"); ?>
<?php $this->load->view("admin/sidebar"); ?>
<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
$token=rand(1000,9999);
$md_db = hash('sha256',$token);
$token1=$md_db;
$this->session->set_userdata('passval',$token1);
?>
<div class="content" style="padding-top:0px;">
<div class="row">
<input type="hidden" name="passval" id="passval" value="<?php echo $token1; ?>">
	<div class="card" style="width: 100%; padding: 0px 12px;border-top: 0px;margin-top: 0px; border-top-right-radius: 0px; border-top-left-radius: 0px;">            
    <?= form_open(false,['class'=>'wizard-form steps-basic wizard clearfix']) ?>
        <div class="content clearfix" id="document_filing_div_id">
          <?= form_fieldset('<small class="fa fa-upload"></small>&nbsp;&nbsp; Change Password'); ?>
            <div class="date-div text-success">Date & Time : &nbsp;<small><?php echo date('D M d, Y H:i:s'); ?>&nbsp;IST</small></div>
            <div class="row">
            
                <div class="col-md-3">
                    <div class="form-group required">
                        <label for="password"><span class="custom"><font color="red">*</font></span>Old Password </label>
                        <input type="password" class="form-control" id="oldpass" name="oldpass" aria-describedby="emailHelp" placeholder="Enter old Password">
                    </div>
                </div>
                
                
                <div class="col-md-3">
                    <div class="form-group required">
                        <label for="password"><span class="custom"><font color="red">*</font></span>Password </label>
                        <input type="password" class="form-control" id="password" name="password" aria-describedby="emailHelp" placeholder="Enter Password">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group required">
                        <label for="conpass"><span class="custom"><font color="red">*</font></span>Confirm Password </label>
                        <input type="password" class="form-control" id="conpass" name="conpass"  placeholder="Enter Confirm Password">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" name="form_data" id="form_data" class="btn btn-primary" onclick="submitpassword();">Submit</button>
                </div>         
            </div>
        </div> 
	</div>                   
</div>
<?= form_close(); ?>  
 <?php $this->load->view("admin/footer"); ?>        
<script>
function submitpassword() {
	var password=document.getElementById("password").value;
	var conpass=document.getElementById("conpass").value;
	var passval=document.getElementById("passval").value;
	var oldpass=document.getElementById("oldpass").value;
	
    var rules = [{
        exp: /[0-9]/,
        msg: 'Must contain at least one digit'
    }, {
        exp: /[a-z]/,
        msg: 'Must contain at least one lowercase'
    }, {
        exp: /[A-Z]/,
        msg: 'Must contain at least one uppercase'
    }, {
        exp: /[@!%&*\s]/,
        msg: 'Must contain at least one special move'
    }, {
        exp: /^.{8,20}$/,
        msg: 'Must be 8-20 characters long'
    }];
    
    var input = conpass;
    
    var pass = true;
    for (var i = 0; i < rules.length; i++) {
        var rule = rules[i];
        if (!rule.exp.test(input)) {
            pass = false;
            $.alert({
				title: '<i class="fa fa-check-circle text-danger"></i>&nbsp;</b>Error</b>',
				content: '<p class="text-danger">'+rule.msg+'</p>',
				animationSpeed: 2000
			});
			 return false;
        }
    }


	var pass=HASH(oldpass);
	var conpass= HASH(conpass);
	var password= HASH(password);



    var p = document.getElementById('password').value,
        errors = [];
    if (p.length < 8) {
        errors.push("Your password must be at least 8 characters"); 
    }
    if (p.search(/[a-z]/i) < 0) {
        errors.push("Your password must contain at least one  letter.");
    }
    if (p.search(/[0-9]/) < 0) {
        errors.push("Your password must contain at least one digit."); 
    }
    if (errors.length > 0) {
        alert(errors.join("\n"));
        return false;
    }
    
    
	
	if(oldpass==''){
        alert("Please Enter old Password.");
        return false;
	}
	if(password==''){
        alert("Please Enter Password.");
        return false;
	}
	if(conpass==''){
        alert("Please Enter Confirm Password.");
        return false;
	}
    if (password != conpass) {
         alert("Passwords do not match.");
         return false;
     }
    var dataa={};
    dataa['password']=password,
    dataa['conpass']=conpass,
    dataa['passval']=passval,
    dataa['oldpass']=pass,
	 $.ajax({
         type: "POST",
         url:  base_url+"changepassword",
         data: dataa,
         cache: false,
         dataType: 'json',
         success: function(data) {
        	  if(data.data=='success') {
                  $.alert({
						title: '<i class="fa fa-check-circle text-success"></i>&nbsp;</b>Congrates</b>',
						content: '<p class="text-success">Your password update successfully.</p>',
						animationSpeed: 2000
					});
                  $('#form_data').prop('disabled',true);
                  setTimeout(function(){ 
					window.location.href='logout';
				  }, 6000);
                  
              }else if(data.error !='0') {
                  $.alert(data.massage);
              }
         }
     });				
}
</script>