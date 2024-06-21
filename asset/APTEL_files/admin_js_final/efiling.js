function maAction() {
    var filingOn = document.getElementById("filing_no").value;
    var partyType1 = $('input[name=userType]:checked').val();
    var total_feeeee = $('#total_feeee').val();
    var collection_ammount = $('#collection_ammount').val();
    var matter = $('#matter').val();
    var checkboxes1 = document.getElementsByName('patyAddId');
     var checkboxes1 = document.getElementsByName('additionla_partyy');
    var patyAddId = "";
    var count1 = 0;
    var selected = [];
    for (var i = 0; i < checkboxes1.length; i++) {
        if (checkboxes1[i].checked) {
        	patyAddId = patyAddId + checkboxes1[i].value + ',';
            count1++;
        }
    }
    
    if(partyType1!='3'){ 
        if (patyAddId == '') {
            alert('pleae select Additional Party');
            return false;
        } 
    }



    if(partyType1=='3'){ 
    	var select_org_app = document.getElementById('select_org_app1').value;
    	if(select_org_app==''){
    		alert("Select Organization.");
    		return false;
        }
        var petName = document.getElementById('petName1').value;
        var dstate = document.getElementById('dstate1').value;
        var petmobile = document.getElementById('petmobile1').value;
        var degingnation = document.getElementById('degingnation1').value;
        var ddistrict = document.getElementById('ddistrict1').value;
        var petPhone = document.getElementById('petPhone1').value;
        var petAddress = document.getElementById('petAddress1').value;
        var pincode = document.getElementById('pincode1').value;
        var petEmail	 = document.getElementById('petEmail1').value;
        var petFax = document.getElementById('petFax1').value;
     }
     var paper1 = document.getElementById("paper_master").value;
     var ar1 = paper1.split(" ");
     var paper2 = ar1[0];
     var pid = ar1[1];
     if (paper2 == 'ma') {
        var totalNoAnnexure = document.getElementById("totalNoAnnexure").value;
        if(totalNoAnnexure=='0' || totalNoAnnexure==''){
        	  alert("Please Enter Annexure should greater than zero!");
              document.totalNoAnnexure.focus();
              return false;
        }
     }

     if (paper2 == 'va'){
        var state = document.getElementById("dstate").value;
        var dist = document.getElementById("ddistrict").value;
        var counselAdd = document.getElementById("counselAdd").value;
        var counselPin = document.getElementById("counselPin").value;
        var counselMobile = document.getElementById("counselMobile").value;
        var counselPhone = document.getElementById("counselPhone").value;
        var counselEmail = document.getElementById("counselEmail").value;
        var counselFax = document.getElementById("counselFax").value;
        var councilCode = document.getElementById("councilCode").value;
        if (councilCode == "" || councilCode == 'Select Council Name') {
            alert("Please Select Council Name!");
            document.councilCode.focus();
            return false;
        }
        if (state == "" || state == 'Select State Name') {
            alert("Please Select State!");
            document.dstate.focus();
            return false;
        }
        if (dist == "" || dist == 'Select District Name') {
            alert("Please Select District !");
            document.ddistrict.focus();
            return false;
        }
    }
    var radios = document.getElementsByName("bd");
    var bd = 0;
    for (var i = 0; i < radios.length; i++) {
        if (radios[i].checked) {
            var bd = radios[i].value;
        }
    }

    if (bd == 3) {
        var ddno = $("#ntrpno").val();
        var amountRs = $("#ntrpamount").val();
        var dddate = $("#ntrpdate").val();
        var dbankname = $("#ntrp").val();
        if (dbankname == "") {
            alert("Please Enter Bank name");
            document.ntrp.focus();
            return false;
        }
        if (ddno == "") {
            alert("Please Enter Challan No/Ref.No");
            document.ntrpno.focus();
            return false
        }
        if (dddate == "") {
            alert("Please Enter Date of Transction");
            document.ntrpdate.focus();
            return false
        }
        if (amountRs == "") {
            alert("Please Enter Amount ");
            document.ntrpamount.focus();
            return false
        }
        
        
        var collectamount = $("#collectamount").val();
        if(collectamount==''){
         	var collectamount=0;
        }
        var totalamount = $("#totalamount").val();
        var val= parseInt(collectamount)+parseInt(amountRs);
        if(totalamount > val){
    	   alert("Total amount and paid amount should be equal ");
           return false
         }
    }

    var select_org_app = document.getElementsByName('select_org_app').value;
	if(select_org_app==''){
		alert("Select Organization.");
		return false;
    }
    if ( paper2 == 'ma') {
    	var dataa={};
            dataa['matter']=matter, 
            dataa['collection_ammount']=collection_ammount,
            dataa['total_feeeee']=total_feeeee, 
            dataa['filingNo']=filingOn,
            dataa['type']=partyType1,
            dataa['select_org_app']=select_org_app,
            dataa['petName']=petName, 
            dataa['dstate']=dstate,
        	dataa['petmobile']=petmobile,
        	dataa['degingnation']=degingnation,
        	dataa['ddistrict']=ddistrict,
        	dataa['petPhone']=petPhone,
        	dataa['petAddress']=petAddress,
        	dataa['pincode']=pincode,
        	dataa['petEmail']=petEmail,
        	dataa['petFax']=petFax,
        	dataa['addparty']=patyAddId,
        	dataa['totalA']=totalNoAnnexure ,
        	dataa['dbankname']=dbankname,
            dataa['amountRs']=amountRs,
            dataa['ddno']=ddno,
            dataa['dddate']=dddate,
            dataa['bd']=bd,
            dataa['pid']=pid,
            dataa['paper2']=paper2,
        	$.ajax({
        		type: 'post',
        		url: base_url+'ma_action',
        		data: dataa,
        		success: function(retrn){
        			if(retrn){
        				$("#document_filing_div_id").empty();
        			    $("#annId").empty();
        			    $("#document_filing_div_id_text_print").html(retrn);
        			}
        		},
        		error: function(){
        			$.alert('Server busy, try later.');
        		},
        		complete: function(){
        			  document.getElementById("payMode").style.display = 'block';					
        		}
        	});
    }
    if (paper2 == 'va') {
    	var dataa={};
            dataa['matter']=matter ,
        	dataa['collection_ammount']=collection_ammount ,
            dataa['total_feeeee']=total_feeeee,
            dataa['filingNo']=filingOn , 
            dataa['type']=partyType1 ,
            dataa['select_org_app']=select_org_app, 
            dataa['petName']=petName,  
            dataa['dstate']=dstate ,
        	dataa['petmobile']=petmobile, 
        	dataa['degingnation']=degingnation , 
        	dataa['ddistrict']=ddistrict ,
        	dataa['petPhone']=petPhone , 
        	dataa['petAddress']=petAddress , 
        	dataa['pincode']=pincode , 
        	dataa['petEmail']=petEmail , 
        	dataa['petFax']= petFax , 
        	dataa['addparty']=patyAddId,
            dataa['dbankname']= dbankname , 
            dataa['amountRs']= amountRs, 
            dataa['ddno']=ddno ,
            dataa['dddate']= dddate ,
            dataa['bd']= bd ,
            dataa['pid']= pid ,
            dataa['paper2']= paper2, 
            dataa['cadd']= counselAdd, 
            dataa['cpin']=counselPin, 
            dataa['cmob']=counselMobile,
            dataa['cemail']= counselEmail,
            dataa['cfax']=counselFax,
            dataa['counselpho']= counselPhone,
            dataa['st']= state,
            dataa['dist']= dist,
            dataa['councilCode']= councilCode,   
        	$.ajax({
        		type: 'post',
        		url: base_url+'ma_action',
        		data: dataa,
        		success: function(retrn){
        			if(retrn){
        				$("#document_filing_div_id").empty();
        			    $("#annId").empty();
        			    $("#document_filing_div_id_text_print").html(retrn);
        			}
        		},
        		error: function(){
        			$.alert('Server busy, try later.');
        		},
        		complete: function(){
        			  document.getElementById("payMode").style.display = 'block';					
        		}
        	});
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
	        		$('#add_amount_list').html(resp.display);
	        		$('#remainamount').val(resp.remain);
	        		$('#collectamount').val(resp.paid);
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
	    if (bd == 3) {
	        document.getElementById("ntrpno").value = "";
	        document.getElementById("ntrpdate").value = "";
	        document.getElementById("ntrpamount").value = "";
	    }
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
    }
}


function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;

    return true;
}


   
   
function underSection(sel) {
    var act_id = sel.options[sel.selectedIndex].value;
    var case_typed = $("#caseType").val();
    act_iddd = act_id;
    casyy_type = case_typed;
    var dataa = {};
    dataa['state_id'] = act_id;
    dataa['case_typed'] = case_typed;
    if (act_id.length > 0) {
        $.ajax({
            type: "POST",
            url: base_url+'undersection',
            data: dataa,
            cache: false,
            success: function (petSection) {
                $("#petSection").html(petSection);
                $("#petSection").val("")
                if (casyy_type == '1' && act_iddd == '1') {
                    $("#petSection").val("1");
                } else if (casyy_type == '1' && act_iddd == '3') {
                    $("#petSection").val("5");
                } else if (casyy_type == '1' && act_iddd == '2') {
                    $("#petSection").val("3");
                } else if ((casyy_type == '2' || casyy_type == '4') && act_iddd == '1') {
                    $("#petSection").val("2");
                } else if ((casyy_type == '2' || casyy_type == '4') && act_iddd == '3') {
                    $("#petSection").val("6");
                } else if ((casyy_type == '2' || casyy_type == '4') && act_iddd == '2') {
                    $("#petSection").val("4");
                }

            }
        });
    }
}
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   

	function underSection(sel) {
	    var act_id = sel.options[sel.selectedIndex].value;
	    var case_typed = $("#caseType").val();
	    act_iddd = act_id;
	    casyy_type = case_typed;
	    var dataa = {};
	    dataa['state_id'] = act_id;
	    dataa['case_typed'] = case_typed;
	    if (act_id.length > 0) {
	        $.ajax({
	            type: "POST",
	            url: base_url+'undersection',
	            data: dataa,
	            cache: false,
	            success: function (petSection) {
	                $("#petSection").html(petSection);
	                $("#petSection").val("")
	                if (casyy_type == '1' && act_iddd == '1') {
	                    $("#petSection").val("1");
	                } else if (casyy_type == '1' && act_iddd == '3') {
	                    $("#petSection").val("5");
	                } else if (casyy_type == '1' && act_iddd == '2') {
	                    $("#petSection").val("3");
	                } else if ((casyy_type == '2' || casyy_type == '4') && act_iddd == '1') {
	                    $("#petSection").val("2");
	                } else if ((casyy_type == '2' || casyy_type == '4') && act_iddd == '3') {
	                    $("#petSection").val("6");
	                } else if ((casyy_type == '2' || casyy_type == '4') && act_iddd == '2') {
	                    $("#petSection").val("4");
	                }
	
	            }
	        });
	    }
	}




	
	function addMoreAppdoc() {
	    var salt = $("#filing_no").val();
	    var bd = $('input[name=userType]:checked').val();
	    
	    var partyN = new Array();
	    var x = document.getElementById("additionla_partyy");
	    var j = 0;
	    for (var i = 0; i < x.length; i++) {
	        if (x[i].checked) {
	        	 partyN[j] = party_id + x[i].value + ',';
	        	  j++;
	        }
	    }
	    var checkboxes1 = document.getElementsByName('additionla_partyy');
	    var partyN = new Array();
	    var j = 0;
	    var count1 = 0;
	    for (var i = 0; i < checkboxes1.length; i++) {
	        if (checkboxes1[i].checked) {
	        	 partyN[j] =checkboxes1[i].value;
	        	 j++;
	        }
	    }
	    
	    if(partyN.length==0){
		    alert("Please Cheack party name !");
	        document.additionla_partyy.focus();
	        return false;
	    }
	    
	    
	    var counselAdd = $("#counselAdd").val();
	    var counselMobile = $("#counselMobile").val();
	    var counselEmail = $("#counselEmail").val();
	    var counselPhone = $("#counselPhone").val();
	    var counselPin = $("#counselPin").val();
	    var counselFax = $("#counselFax").val();
	    var state = $("#dstate").val();
	    var dist = $("#ddistrict").val();
	    var councilCode = $("#councilCode").val();
	    if (councilCode == "" || councilCode == 'Select Council Name') {
	        alert("Please Select Council Name!");
	        document.councilCode.focus();
	        return false;
	    }
	    if (state == "" || state == 'Select State Name') {
	        alert("Please Select State!");
	        document.filing.dstate.focus();
	        return false;
	    }
	     var data = {};
	     data['cadd'] =counselAdd,
	     data['cpin'] =counselPin,
		 data['cmob'] =counselMobile,
		 data['cemail'] =counselEmail,
		 data['cfax'] = counselFax,
		 data['salt'] =salt,
		 data['counselpho'] =counselPhone,
		 data['st'] =state,
		 data['dist'] =dist,
		 data['councilCode'] =councilCode,
		 data['bd'] =bd,
		 data['pcode'] =partyN,
		 $.ajax({
	        type: "POST",
	        url: base_url+"/filingaction/additionalAdvocate_action_new",
	        data: data,
	        dataType: "html",
	        success: function(data) {
	            $("#advlisting").html(data);
	            document.getElementById("advlisting").style.display = 'block';
	            document.getElementById("partyName").value = "Select Party Name";
	            document.getElementById("dstate").value = "Select State Name";
	            document.getElementById("ddistrict").value = "Select District Name";
	            document.getElementById("counselAdd").value = "";
	            document.getElementById("counselPin").value = "";
	            document.getElementById("counselMobile").value = "";
	            document.getElementById("counselPhone").value = "";
	            document.getElementById("counselEmail").value = "";
	            document.getElementById("counselFax").value = "";
	            document.getElementById("councilCode").value = "Select Council Name";
	            document.getElementById("ddistrictname").value = "";
	            document.getElementById("dstatename").value = "";  
	        },
	        error: function(request, error) {
	            $("#div_check_valaltanama_ia").html(data);
	        }
	    });
	}

	function deletePay(e, s) {
	    var payid = e;
	    var salt = $("#filing_no").val();
	    var bd = $('input[name=userType]:checked').val();
	    var data = {};
	    data['salt'] =salt,
	    data['ptype'] =bd,
		data['payid'] =payid,
	    $.ajax({
	        type: "POST",
	        url: base_url+"/filingaction/delete_additional_advocate",
	        data: data,
	        dataType: "html",
	        success: function(data) {
	            $("#advlisting").html(data);
	        },
	        error: function(request, error) {
	            $("#div_check_valaltanama_ia").html(data);
	        }
	    });
	}
	
	
	


	





