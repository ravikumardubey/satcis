<?php
$this->load->view("admin/header");
$this->load->view("admin/sidebar");
$this->load->view("admin/stepsia");
$salt=$this->session->userdata('iasalt');
$userdata=$this->session->userdata('login_success');
$userid=$userdata[0]->id;
$token= $this->efiling_model->getToken();
if($salt==''){
    $salt='';
}
$tab_no='';
$type='';
$totalia='';
$anx='';
$filing_no='';
$ianature='';
if($salt!=''){
    $basicia= $this->efiling_model->data_list_where('temp_iadetail','salt',$salt);
    $anx=isset($basicia[0]->totalanx)?$basicia[0]->totalanx:'';
    $type=isset($basicia[0]->type)?$basicia[0]->type:'';
    $filing_no=isset($basicia[0]->filing_no)?$basicia[0]->filing_no:'';
    $tab_no=isset($basicia[0]->tab_no)?$basicia[0]->tab_no:'';
    $totalia=isset($basicia[0]->totalia)?$basicia[0]->totalia:'';
    $ianature=isset($basicia[0]->ianature)?$basicia[0]->ianature:'';
}

?>
<div id="rightbar"> 
	<form action="#" id="frmCounsel" autocomplete="off">    
        <input type="hidden" name="token" value="<?php echo $token; ?>" id="token">
	    <input type="hidden" name="filing_no" id="filing_no" value="<?php echo $filing_no; ?>">
	    <input type="hidden" name="saltNo" id="saltNo" value="<?php echo $salt; ?>">
	    <input type="hidden" name="tabno" id="tabno" value="3">
	    <input type="hidden" name="type" id="type" value="ia">
          
          
        <div class="content" style="padding-top:0px;">
    		<fieldset id="iaNature" style="display:block"><legend class="customlavelsub">IA Nature</legend>
    		 		<div class="col-md-2">
                        <div><label for="phone"><span class="custom"><font color="red">*</font>Total No Of IA:</span></label></div>
                        <div id="phone"><input type="text" name="totalNoIA" id="totalNoIA" class="form-control" maxlength="1"   value="<?php echo $totalia; ?>" onkeypress="return isNumberKey(event)"/></div>
                    </div></br></br>
                   <div class="table-responsive">
                    <table datatable="ng" id="examples"  class="table table-striped table-bordered" cellspacing="0"  width="100%">
                        <thead>
                            <tr><th>#</th>                  
                                <th>MA Nature Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $ianocc=explode(',', $ianature);
                            $aDetail=  $this->efiling_model->data_list('master_ma_type');
                            foreach($aDetail as $row) {
                                $valchecked='';
                                if(is_array($ianocc)){
                                    if(in_array($row->mid, $ianocc)){
                                        $valchecked="checked";
                                    }
                                }
                            ?>
                            <tr>
                                <td><input type = "checkbox" name = "natureCode" value ="<?php echo htmlspecialchars($row->mid); ?>" onclick="openTextBox(this);" <?php echo $valchecked; ?>></td>
                                <td><?php echo htmlspecialchars($row->name);?></td>
                            </tr>
                        <?php }?>
                        </tbody>
                    </table>
                     <div class="col-sm-12 div-padd" id="matterId" style="display: none">
                            <div><label for="name"><span class="custom"> <font color="red"></font> </span>Matter </label></div>
                            <div><textarea rows="4" cols="110" name="matter" id="matter"  class="txtblock"></textarea></div>
                      </div>
                </div>
             </fieldset>
             <div class="row">
                <div class="offset-md-8 col-md-4">
                    <input style="margin-left: 200px;" type="button" value="Save and Next" class="btn btn-success" onclick="iasubmit();">
					&nbsp;&nbsp;<input type="reset" value="Reset/Clear" class="btn btn-danger">
                </div>
            </div>

        </div>
     </form>
</div>  
<?php $this->load->view("admin/footer"); ?>
<script>
function iasubmit() {
    var salt = document.getElementById('saltNo').value; 
    var totalNoIA = document.getElementById('totalNoIA').value; 
    var tabno= document.getElementById('tabno').value;    
    var filingno = document.getElementById('filing_no').value;
    var type = document.getElementById('type').value;  
    var totalNoIA = $("#totalNoIA").val();
    if (totalNoIA == "") {
        alert("Please Enter Total No IA");
        document.filing.totalNoIA.focus();
        return false
    }
    var iaNature = "";
    var count = 0;
    var checkboxes = document.getElementsByName('natureCode');
    var selected = [];
    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].checked) {
            iaNature = iaNature + checkboxes[i].value + ',';
            count++;
        }
    }
    var ia = document.getElementById("totalNoIA").value;
    var dataa = {};
    dataa['salt'] = salt;
    dataa['filing_no'] = filingno;
    dataa['type'] = type;
    dataa['iaNature']=iaNature;
    dataa['totalia']=ia;
    dataa['tabno']=tabno;
    dataa['token'] = '<?php echo $token; ?>';
     $.ajax({
         type: "POST",
         url: base_url+"/iadetailsave",
         data: dataa,
         cache: false,
         success: function (resp) {
        	var resp = JSON.parse(resp);
        	if(resp.data=='success') {
		       setTimeout(function(){
                    window.location.href = base_url+'ia_upload_doc';
               }, 250); 
			}
			else if(resp.error != '0') {
				$.alert(resp.error);
			}
         },
         error: function (request, error) {
			$('#loading_modal').fadeOut(200);
         }
     }); 
} 

function openTextBox() {
    var checkboxes = document.getElementsByName('natureCode');
    var iaNature1 = "";
    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].checked) {
            var iaNature1 = checkboxes[i].value;
        }
    }
    if (iaNature1 == 12) {
        document.getElementById("matterId").style.display = 'block';
    }else{
    	document.getElementById("matterId").style.display = 'none';
    }
}

</script>