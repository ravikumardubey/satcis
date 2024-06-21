<?php $this->load->view("admin/header"); ?>
<?php $this->load->view("admin/sidebar"); ?>
<script>
function submitForm() {
    with (document.frm) {
        if (next_list_date.value == "") {
            alert("Enter ORDER DATE....");
            next_list_date.value = '';
            next_list_date.focus();
            return false;
        }        
        action = base_url+'defectletterupload';
        submit();
        document.frm.submit1.disabled = true;
        document.frm.submit1.value = 'Please Wait...';
        return true;
    }
}

function submitForm() {
    with (document.frm) {
        if (next_list_date.value == "") {
            alert("Enter ORDER DATE....");
            next_list_date.value = '';
            next_list_date.focus();
            return false;
        }

        action = base_url+'defectletterupload';
        submit();
        document.frm.submit1.disabled = true;
        document.frm.submit1.value = 'Please Wait...';
        return true;
    }
}
</script>

<form name="frm" method="post" action="">
            <table class="table table-hovered table-bordered table-stripped ">
                <tr><th valign="top" align="center" colspan="12"> <b><font face="Verdana" size="3"><u>Defect upload</u></font> </b>  </tr>
                <form name="frm" method="post" action="#">
                    <tr><td colspan="16"></td></tr>
                    <?php                    
                    $next_list_date = isset($_REQUEST['next_list_date']) ? $_REQUEST['next_list_date'] : ''; 
                    if($next_list_date==''){
                        $next_list_date = date('d-m-Y');
                    } 
                    $court_no_no = isset($_REQUEST['court_no']) ? $_REQUEST['court_no'] : ''; 
                    $where='';
                    if($next_list_date){
                        $bsd=explode('-', $next_list_date);
                        $next_list_datexx=$bsd[2].'-'.$bsd[1].'-'.$bsd[0];
                        $where =" where  entry_date ='$next_list_datexx'";
                    } 
                    ?>
                    <tr>
                         <td colspan="16"><!--<font color="red">*</font><font size="1">ORDER DATE:</font> -->
                            <input placeholder="Order Date" style="float:left; width: 200px; margin-right: 15px;" type="text" id="next_list_date" name="next_list_date" class="form-control datepicker" readonly="readonly" size="8" autocomplete="off" maxlength="10" value="<?php print htmlspecialchars($next_list_date); ?>"/>    
                            	<input id="submit1" type="button" class="btn btn-sm btn-success" name="submit1" value="SEARCH" onClick="return submitForm();">
                            	<button type="button"  style="float:right;" onclick="fn_final_order('new');" class="btn btn-sm btn-success">New Defect upload</button>	 
                            </br>
                        </td>
                    </tr>
                    <tr>
                    	<th>Sr. No.</th>
                        <th>AL No</th>
                        <th>Party.</th> 
                        <th>Uploaded Date</th>
                        <th>Defect Create On</th>
                        <th align='center'>Defect latter</th>
                    </tr>
                    <?php
                    if ($next_list_date) {
                        $query =$this->db->query("select * from sat_uploadeddefectlatter $where");
                        $rowval= $query->result();
                        if (empty($rowval)) { ?>
                        <tr><td align="center" colspan="16"><font color="red" size="4"><b>No Record   Found </b></font></td></tr>
                        <?php }
                        $pt_name='';
                        $rs_name='';
                        if (!empty($rowval)) {
                            $counter = 1;
                            foreach ($rowval as $rw2) {                              
                               $filing_no=$rw2->full_filing_no;
                               $id=$rw2->id;
                               $filename= explode($filing_no.'/',$rw2->file_name);                       
                                if ($filing_no != '') {
                                    $query =$this->db->query("select * from sat_case_detail where filing_no='$filing_no'");
                                    $rowval= $query->result();
                                    foreach($rowval as  $rw21) {
                                        $filing_no98 = htmlspecialchars($rw21->filing_no);
                                        $case_no = htmlspecialchars($rw21->case_no);
                                        $pt_name = htmlspecialchars($rw21->pet_name);
                                        $rs_name = htmlspecialchars($rw21->res_name);
                                    }
                                }
                                                                                   
                                $query =$this->db->query("select acd.res_name,acd.case_no,mct.short_name from sat_case_detail as acd LEFT 
                                JOIN master_case_type mct ON acd.case_type=mct.case_type_code  where acd.filing_no= '$filing_no' ");
                                
                        
                                $row1= $query->result();
                                $res_name=$row1[0]->res_name;
                                $case_no=$row1[0]->case_no;
                                //Case No
                                $valc='';      
                                $valcccc='View';
                                if($case_no!=''){
                                    $valc= substr($case_no,-8);
                                    $ac=substr_replace($valc ,"",-4);
                                    $bc= substr($valc, -4);
                                    $valc=$row1[0]->short_name . -$ac.'/'.$bc;
                                }
                                $valdfr='';
                                if($filing_no!=''){
                                    $val= substr($filing_no,-8);
                                    $a=substr_replace($val ,"",-4);
                                    $b= substr($val, -4);
                                    $valdfr='AL No. - '. $a.'/'.$b;
                                }
                                $file_name=   $rw2->file_name;  
                                if (file_exists($file_name)){
                                    $key="01234567890123456789012345678901"; // 32 bytes
                                    $vector="1234567890123412"; // 16 bytes
                                    $encrypted = myCrypt($file_name, $key, $vector);
                                }  
                                
                                
                                
                                
                              
                                
                                ?>
                                <tr id="id<?php echo $counter; ?>">
                                    <td><?php echo $counter . '.'; ?></td>
                                    <td><?php echo $valdfr; ?></td>
                                    <td><?php echo $pt_name; ?>Vs <?php echo $rs_name; ?></td> 
                                    <td><?php echo $rw2->entry_date; ?></td>
                                    <td><?php echo $rw2->entry_date; ?></td> 
                                    <td> <a target="_blank" href="defect_view/<?php echo base64_encode($encrypted); ?>">VIEW</a></td>                                
                                </tr>
                                <?php
                                $counter++;
                            }
                        }
                    }
                    
                    
                    function myCrypt($value, $key, $iv){
                        $encrypted_data = openssl_encrypt($value, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
                        return base64_encode($encrypted_data);
                    }
                    
                    ?>
            </table>
        </div>
    </section>

<div id="upload_final_order" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
	<form id="order_upload_form_id" enctype="multipart/form-data">
        <div class="modal-content">
            <h4 class="modal-title">New Defect upload</h4><h2 id="sucess_msg"> </h2>
               <div class="modal-header">
                	<button type="button" class="close" data-dismiss="modal">&times;</button>
               </div>
               <div class="col-sm-12 " id="caseanddiary" style="display:block">
                   <div class="row">
                        <div class="col-sm-4 ">
                            <label for="name"><span class="custom"><font color="red">*</font></span>DFR No </label>
                            <input type="radio" name="appAnddef1" onclick="diary('1');" id="app" value="1" checked="">
                        </div>
                        <div class="col-sm-4 ">
                            <label for="name"><span class="custom"><font color="red">*</font></span>Case No </label>
                            <input type="radio" name="appAnddef1" onclick="diary('2');" id="def" value="2" unchecked="">
                        </div>     
                    </div>
               </div>
              <div class="modal-body">
        	     <div class="row" >
            	  	 <div class="col-sm-4">
            	  	    <label> Date</label>
            	  		<input type="text"   name="enterdate" id="enterdate" value=""  class="form-control datepicker" readonly="readonly" >
            	  	 </div>
            	 </div>   	 
            	 <div class="row" id="dfrwise">  	
            	  	 <div class="col-sm-4">
            	  		<label>AL No.</label>
            	  		<input type="text"  class="form-control"  name="filing_no" id="filing_no" value="">
            	  	 </div>
            	  	
                	 <div class="col-sm-4">
                   		<?php  $diaryYear= isset($_REQUEST['diaryYear']) ? $_REQUEST['diaryYear'] :'';?>
                         <div><label for="name"><span class="custom"><font color="red">*</font></span>Year</label></div>
                        <div>
                    		<?php $year = date('Y');
                            if ($diaryYear == "") {
                                $diaryYear = $year;
                            }
                            ?>
                    		<select name="year" class="form-control"  id="year">
                            <?php for ($i = 1990; $i <= $year; $i ++) {
                                  if ($diaryYear == $i) {
                             ?>
                    		<option selected="selected" value="<?php echo $i?>"><?php echo $i?> </option>
                    		<?php } else {?>
                    		<option value="<?php echo $i?>"><?php echo $i?> </option>
                    		<?php }}?>
                     		</select>
                    	</div>
            		</div>
            	</div>	
                 <div class="row" id="casewise" style="display:none">
            	  	 <div class="col-sm-4">
            	  		<label>Case Type</label>
            	  		<?php $caseType = isset($_REQUEST['caseType']) ? $_REQUEST['caseType'] : ''; ?>
                            <select name="caseType" class="form-control" id="caseType">
                                <?php
                                $query=$this->db->query("select short_name,case_type_code from master_case_type where display='true'  order by case_type_code");
                                if (!empty($query->result())) { 
                                    foreach($query->result() as $row) {
                                        $ctc = $row->case_type_code;
                                        if ($caseType == $ctc) {
                                            print "<option value=" . htmlspecialchars($row->case_type_code) . " selected>" . htmlspecialchars($row->short_name) . "</option>";
                                        } else {
                                            print "<option value=" . htmlspecialchars($row->case_type_code) . ">" . htmlspecialchars($row->short_name) . "</option>";
                                        }
                                    }
                                }
                                ?>
                            </select>
            	  	 </div>         	  	 
            	  	 <div class="col-sm-4">
            	  		<label>Case No.</label>
            	  		<input type="text"  class="form-control"  name="case_no" id="case_no" value="">
            	  	 </div>
                	 <div class="col-sm-4">
                   		<?php  $caseYear= isset($_REQUEST['caseYear']) ? $_REQUEST['caseYear'] :'';?>
                         <div><label for="name"><span class="custom"><font color="red">*</font></span>Year</label></div>
                        <div>
                    		<?php $year = date('Y');
                    		if ($caseYear == "") {
                    		    $caseYear = $year;
                            }
                            ?>
                    		<select name="caseYear" class="form-control"  id="caseYear">
                            <?php for ($i = 1990; $i <= $year; $i ++) {
                                if ($caseYear == $i) {
                             ?>
                    		<option selected="selected" value="<?php echo $i?>"><?php echo $i?> </option>
                    		<?php } else {?>
                    		<option value="<?php echo $i?>"><?php echo $i?> </option>
                    		<?php }}?>
                     		</select>
                    	</div>
            		</div>
            	</div>		
            	<br>   
        	   <div class="row">
        	   		<div class="col-sm-4">
            	     <div class="custom-file">
            	        <label class="custom-file-label" for="validatedCustomFile">Choose file...</label>
                        <input type="file" value=""  class="custom-file-input" id="browse_file" required>
                       <input type="hidden" value=""  name="oldfile" class="custom-file-input" id="oldfile" required>
                      </div>
                    </div>   
        	   </div>
        	   <input type="hidden" id="action" name="action" value="">
              </div>
              <div class="modal-footer">
        		<button type="button" id="upload_order_file" class="btn btn-success">Upload</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
        </div>
      </div>
    </div> 
 </form>


<script> initSample();</script>
<script>
function fn_final_order(order_id,filing_no,enterdate,filename,year) { 
	if(order_id!='new'){
    	$("#filing_no").val(filing_no);
    	$('#filing_no').attr('readonly', true);
    	$("#enterdate").val(enterdate);
    	$("#year option[value='"+year+"']").attr("selected","selected");
    	$("#oldfile").val(filename);
    	$("#action").val('update_upload_judgment');
	}
	if(order_id=='new'){
    	$("#filing_no").val('');
    	$('#filing_no').attr('readonly', false);
    	$("#enterdate").val('');
    	$("#oldfile").val('');
    	$("#action").val('upload_judgment');
	}
	$("#upload_final_order").modal('show');
}



$(document).on('click','#upload_order_file',function(e){
	e.preventDefault();
	var action=$("#action").val();
 	var file_data =  $('#browse_file')[0].files[0];   
    var form_data = new FormData();                  
	var enterdate =  $("#enterdate").val();
	var court_no = $("#court_no1").val();
	if(enterdate==''){
		alert("Please enter date");
		return false;
	}
	var type='';
	var radios = document.getElementsByName("appAnddef1");
    for (var i = 0; i < radios.length; i++) {
        if (radios[i].checked) {
            var type = radios[i].value;
        }
    }

    if(type=='1'){ 
		var filing_no = $("#filing_no").val();
		var year= $("#year").val();
		if(filing_no==''){
			alert("Please filing No.");
			return false;
		}	
    }
    if(type=='2'){ 
		var caseno = $("#case_no").val();
		if(caseno==''){
			alert("Please Case No.");
			return false;
		}
		var caseyear= $("#caseYear").val();
		if(caseyear==''){
			alert("Please Case Year.");
			return false;
		}
		var casetype= $("#caseType").val();
		if(casetype==''){
			alert("Please Case Type.");
			return false;
		}
    }
	var oldfile= $("#oldfile").val();
	form_data.append('typeval', type);	
	form_data.append('file', file_data);
	form_data.append('filing_no', filing_no);
	form_data.append('enterdate', enterdate);
	form_data.append('court_no', court_no);
	form_data.append('schemas', '<?php echo 'delhi';?>');
	form_data.append('action', action);
	form_data.append('oldfile', oldfile);
	form_data.append('year', year);
	form_data.append('caseno', caseno);
	form_data.append('caseyear', caseyear);
	form_data.append('casetype', casetype);
	$.ajax({
        type: "POST",
        url: base_url+'ajax_upload_defect',
        data: form_data,
        dataType: 'text', 
        cache: false,
        contentType: false,
        processData: false,
        success: function (resp) {
                var resp = JSON.parse(resp);
            	if(resp.data=='success') {
        		    setTimeout(function(){
                        window.location.href = base_url+'defectletterupload';
                     }, 250);
    			}
    			else if(resp.data == 'error') {
    				$.alert(resp.display);
    			}
			}
        });
});





function flagchange(id){
	 var x = confirm("Are you sure you want to update ?");
	  if (x){
	    $.ajax({
	        type: "POST",
	        url: "ajax_upload_order.php",
	        data: {'flagchange':true,id: id},
	        success: function (data) {
	            alert("Final Order Saved");
	            location.reload(true);
	        },
	        error: function (textStatus, errorThrown) {
	            alert("error");
	        }
	    });
	  } else{
	    return false;
	  } 
}

function save_final(order_id) {
    var action_type = "final_save";
    $.ajax({
        type: "POST",
        url: "edit_order.php",
        data: {action_type: action_type, order_id: order_id},
        success: function (data) {
            alert("Final Order Saved");
            $("#modify_order" + order_id).attr("disabled", "disabled");
        },
        error: function (textStatus, errorThrown) {
            alert("error");
        }

    });
}


function diary(val){
	if(val=='2'){
		$('#dfrwise').hide();
		$('#casewise').show();
	}
	if(val=='1'){
		$('#dfrwise').show();
		$('#casewise').hide();
	}
}

$('.datepicker').datepicker({changeYear: true, 
	dateFormat: 'dd/mm/yy' ,
	changeMonth: true, 
	yearRange : 'c-20:c+10'});


function deleteval(conndfr,maindfr,conndate,srno){
	var x = confirm("Are you sure you want to update ?");
	  if (x){
	    $.ajax({
	        type: "POST",
	        url: "deleterecord.php",
	        data: {'conndfr':conndfr,maindfr: maindfr,conndate:conndate},
	        success: function (data) {
	            $('#'+srno).hide();
	        },
	        error: function (textStatus, errorThrown) {
	            alert("error");
	        }
	    });
	  } else{
	    return false;
	  } 
}


function judjmentdeleteval(id,srno){
	var x = confirm("Are you sure you want to update ?");
	  if (x){
	    $.ajax({
	        type: "POST",
	        url: "judj_deleterecord.php",
	        data: {'id':id},
	        success: function (data) {
	            $('#id'+srno).hide();
	        },
	        error: function (textStatus, errorThrown) {
	            alert("error");
	        }
	    });
	  } else{
	    return false;
	  } 
}

 </script>
<?php $this->load->view("admin/footer"); ?>