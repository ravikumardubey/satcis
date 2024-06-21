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
        action = base_url+'upload_causelist';
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

        action = base_url+'upload_causelist';
        submit();
        document.frm.submit1.disabled = true;
        document.frm.submit1.value = 'Please Wait...';
        return true;
    }
}
</script>

<form name="frm" method="post" action="">
            <table class="table table-hovered table-bordered table-stripped ">
                <tr><th valign="top" align="center" colspan="12"> <b><font face="Verdana" size="3"><u>Uploaded Causelists </u></font> </b>  </tr>
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
                            	
                            </br>
                        </td>
                    </tr>
               </table>     
                <table style="width:100%">
                      	<tr style="border: 1;background-color: silver;">
                        	<th  align="center"><font face="Verdana" size="2"><b>Sr. No.</b></font></th>
                        	<th align="center"><font face="Verdana" size="2"><b>Uploaded Date </b></font></th>
                        	<th align="center"><font face="Verdana" size="2"><b>Court No.</b></font></th>
                        	<th align="center"><font face="Verdana" size="2"><b>Bench No.</b></font></th>
                        	<th align="center"><font face="Verdana" size="2"><b>View Cause List.</b></font></th>
                        	<th align="center"><font face="Verdana" size="2"><b>Uploaded By.</b></font></th>
                        	<th  align="center"><font face="Verdana" size="2"><b>Delete</b></font></th>
                    	</tr>
                        <tbody class="list">
	<?php
	$listing_date=date('Y-m-d');
	$qry1=$this->db->query("select id,bench_id,court_no,filename,url,created_date,upload_by from sat_causelist 
 where created_date = '$listing_date' ");
	$judgerow1= $qry1->result();
	foreach($judgerow1 as $rows){
    $count++;
    $hash34 = $rows->id; ?>
    <tr id="<?php echo htmlspecialchars(base64_encode($hash34));?>_container">
	<td class="sno"><?php echo htmlspecialchars($count).")."; ?></td>
	<?php
	$listing_date = htmlspecialchars( $rows->created_date);
        list($year,$month,$day)=explode('-',$listing_date);
        $listing_date=$day.'/'.$month.'/'.$year;
    ?>
	<td class="listing_date"><?php echo $listing_date; ?></td>
		<td class="court_no"><?php echo $cn; ?></td>
		<?php 
		$url=htmlspecialchars( $rows->url);
		$bnaturecode=htmlspecialchars( $rows->bench_id);
		$userid=$judgerow1['upload_by'];
		$stlu =$this->db->query("select * from users where id= '$userid'");
		$judgerow1= $qry1->result();
		foreach ($judgerow1 as $row){
		    $username=$row->username;
		}
		
		
		

		?>
		<td class="bench_nature"><?php echo htmlspecialchars($btime); ?></td>
		<td class="bench_nature"><a target="_blank" href="../court/order_view_dailyorder.php?path=<?php echo base64_encode($url); ?>">Cause List <?php echo $cn; ?> AND Date <?php echo $listing_date; ?></a></td>
		<td class="bench_nature"><?php echo htmlspecialchars($username); ?></td>

</td>
<td align="center">

 <a href="javascript:void(0)"   onclick="delete_fun('<?php echo htmlspecialchars(base64_encode($judgerow1["id"]));?>');"><i class="fa fa-remove" style="font-size:20px;color:red"></i></a>

</td>
</tr>
<?php } ?>
    </tbody>
  </table>




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
        url: base_url+'ajax_upload_defect_cause_list',
        data: form_data,
        dataType: 'text', 
        cache: false,
        contentType: false,
        processData: false,
        success: function (resp) {
                var resp = JSON.parse(resp);
            	if(resp.data=='success') {
        		    setTimeout(function(){
                        window.location.href = base_url+'causelist_upload';
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


function diarycauselist(val){
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