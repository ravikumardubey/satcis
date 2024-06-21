

function  diary(){
   with(document.rpepcpbascidetail){
	action = base_url+"back_log";
	submit();
    	document.frm.submit11.disabled = true;  
     	document.frm.submit11.value = 'Please Wait...';  
     	return true;
	}
}

function serchDFR(){
	with(document.rpepcpbascidetail){
	action = base_url+"back_log";
	submit();
    	document.frm.submit11.disabled = true;  
     	document.frm.submit11.value = 'Please Wait...';  
     	return true;
	}
}


function deletePartyPet(e, ee) {
    var filing_no = document.getElementById("filing_no").value;
    var partyid = e;
    var party = ee;
    var dataa = {};
	dataa['id'] =partyid,
	dataa['party'] =party,
	dataa['filing_no'] =filing_no,
	$.ajax({
        type: "POST",
        url: base_url+'deletePartyPet',
        data: dataa,
        cache: false,
		beforeSend: function(){
			$('#deletedbutton'+partyid).prop('enabled',true).val("Under proccess....");
		},
        success: function (resp) {
    		$('#trid'+partyid).hide();
    		location.reload();
        },
        error: function(){
			$.alert("Surver busy,try later.");
		},
		complete: function(){
			$('#nextsubmit').prop('enabled',false).val("Add More Appellant");
		}
	 }); 
}  



function deletePartyRes(e, ee) {
    var filing_no = document.getElementById("filing_no").value;
    var partyid = e;
    var party = ee;
    var dataa = {};
	dataa['id'] =partyid,
	dataa['party'] =party,
	dataa['filing_no'] =filing_no,
	$.ajax({
        type: "POST",
        url: base_url+'deletePartyPet',
        data: dataa,
        cache: false,
		beforeSend: function(){
			$('#deletedbutton'+partyid).prop('enabled',true).val("Under proccess....");
		},
        success: function (resp) {
    		$('#idvalres'+partyid).hide();
    		location.reload();
        },
        error: function(){
			$.alert("Surver busy,try later.");
		},
		complete: function(){
			$('#nextsubmit').prop('enabled',false).val("Add More Appellant");
		}
	 }); 
} 




function Addmoreapplicantbacklog(){
  var filing_no=document.getElementById("filing_no").value;
  var pan_no = document.getElementById("apppanPet").value;
  var appage = document.getElementById("appagePet").value;
  var pet_name = document.getElementById("petNamePet").value;
  var appfather = document.getElementById("appfatherPet").value;
  var pet_address = document.getElementById("petAddressPet").value;
  var dstate = document.getElementById("dstatePet").value;
  var ddistrict = document.getElementById("ddistrictPet").value;
  var pet_pin = document.getElementById("pincodePet").value;
  var pet_email = document.getElementById("petEmailPet").value;
  var pet_mobile = document.getElementById("petmobilePet").value;
  var pet_phone = document.getElementById("petPhonePet").value;
  var pet_fax = document.getElementById("petFaxPet").value;
  var orgid = document.getElementById("orgidPet").value;
  var idtype = document.getElementById("idtype").value;
  var org='';
  var checkboxes = document.getElementsByName('orgPet');
  for (var i = 0; i < checkboxes.length; i++) {
	    if (checkboxes[i].checked) {
	        org = checkboxes[i].value;
	    }
  }
   if(org==''){
		alert("Please select party type");
		return false;
	}
	
	if(idtype==''){
		alert("Please select document type");
		return false;
	}
   var dataa={};
    dataa['apppan']=pan_no,
    dataa['filing_no']=filing_no,
    dataa['pet_name']=pet_name,
    dataa['appfather']=appfather,
    dataa['appage']=appage,
    dataa['pet_address']=pet_address,
    dataa['dstate']=dstate,
    dataa['ddistrict']=ddistrict,
    dataa['pet_pin']=pet_pin,
    dataa['pet_email']=pet_email,
    dataa['pet_mobile']=pet_mobile,
    dataa['pet_phone']=pet_phone,
    dataa['pet_fax']=pet_fax,
    dataa['orgid']=orgid,
    dataa['partytype']=org,
    dataa['idtype']=idtype,
    $.ajax({
	    dataType: 'json',
        type: "POST",
        url: base_url+'additionalbacklogPet',
        data: dataa,
        cache: false,
		beforeSend: function(){
			$('#nextsubmit').prop('disabled',true).val("Under proccess....");
		},
        success: function (resp) {
        	if(resp.data=='success') {
        		$.alert({
					title: '<i class="fa fa-check-circle text-success"></i>&nbsp;</b>Success</b>',
					content: '<p class="text-success">Applicant detail Updated successfully.</p>',
					animationSpeed: 2000
				});
				location.reload();
			}else if(resp.error != '0') {
				$.alert({
					title: '<i class="fa fa-check-circle text-warning"></i>&nbsp;</b>Error</b>',
					content: '<p class="text-success">'+resp.display+'</p>',
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

//Applicant save save
function up_date(){
  var filing_no=document.getElementById("filing_no").value;
  var pan_no = document.getElementById("apppan").value;
  var appage = document.getElementById("appage").value;
  var pet_name = document.getElementById("petName").value;
  var appfather = document.getElementById("appfather").value;
  var pet_address = document.getElementById("petAddress").value;
  var dstate = document.getElementById("dstate").value;
  var ddistrict = document.getElementById("ddistrict").value;
  var pet_pin = document.getElementById("pincode").value;
  var pet_email = document.getElementById("petEmail").value;
  var pet_mobile = document.getElementById("petmobile").value;
  var pet_phone = document.getElementById("petPhone").value;
  var pet_fax = document.getElementById("petFax").value;
  var orgid = document.getElementById("orgid").value;
  var idtype = document.getElementById("idtype").value;
  var dataa={};
    dataa['apppan']=pan_no,
    dataa['filing_no']=filing_no,
    dataa['pet_name']=pet_name,
    dataa['appfather']=appfather,
    dataa['appage']=appage,
    dataa['pet_address']=pet_address,
    dataa['dstate']=dstate,
    dataa['ddistrict']=ddistrict,
    dataa['pet_pin']=pet_pin,
    dataa['pet_email']=pet_email,
    dataa['pet_mobile']=pet_mobile,
    dataa['pet_phone']=pet_phone,
    dataa['pet_fax']=pet_fax,
    dataa['orgid']=orgid,
    dataa['idtype']=idtype,
    
    $.ajax({
	    dataType: 'json',
        type: "POST",
        url: base_url+'update_backlogfiling',
        data: dataa,
        cache: false,
		beforeSend: function(){
			$('#nextsubmit').prop('disabled',true).val("Under proccess....");
		},
        success: function (resp) {
        	if(resp.data=='success') {
        		$.alert({
					title: '<i class="fa fa-check-circle text-success"></i>&nbsp;</b>Success</b>',
					content: '<p class="text-success">Applicant detail Updated successfully.</p>',
					animationSpeed: 2000
				});
			}else if(resp.error != '0') {
				$.alert({
					title: '<i class="fa fa-check-circle text-warning"></i>&nbsp;</b>Error</b>',
					content: '<p class="text-success">'+resp.display+'</p>',
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




//Respondent save
function up_dateresRes (){
  var filing_no=document.getElementById("filing_no").value;
  var pet_name = document.getElementById("resNameRes").value;
  var pet_address = document.getElementById("resAddressRes").value;
  var dstate = document.getElementById("stateResRes").value;
  var ddistrict = document.getElementById("ddistrictnameRes").value;
  var pet_pin = document.getElementById("respincodeRes").value;
  var pet_email = document.getElementById("resEmailRes").value;
  var pet_mobile = document.getElementById("resMobileRes").value;
  var pet_phone = document.getElementById("resPhoneRes").value;
  var pet_fax = document.getElementById("resFaxRes").value;
  var resorgid = document.getElementById("resorgidRes").value;
  
  var org='';
  var checkboxes = document.getElementsByName('orgshowresRes');
  for (var i = 0; i < checkboxes.length; i++) {
	    if (checkboxes[i].checked) {
	        org = checkboxes[i].value;
	    }
  }
  
    var dataa={};
    dataa['filing_no']=filing_no,
    dataa['res_name']=pet_name,
    dataa['res_address']=pet_address,
    dataa['res_state']=dstate,
    dataa['res_district']=ddistrict,
    dataa['res_pin']=pet_pin,
    dataa['res_email']=pet_email,
    dataa['res_mobile']=pet_mobile,
    dataa['res_phone']=pet_phone,
    dataa['res_fax']=pet_fax,
    dataa['resorgid']=resorgid,
    dataa['partytype']=org;
  
    $.ajax({
	    dataType: 'json',
        type: "POST",
        url: base_url+'update_backlogfilingresRes',
        data: dataa,
        cache: false,
		beforeSend: function(){
			$('#nextsubmitres').prop('disabled',true).val("Under proccess....");
		},
        success: function (resp) {
        	if(resp.data=='success') {
        		$.alert({
					title: '<i class="fa fa-check-circle text-success"></i>&nbsp;</b>Success</b>',
					content: '<p class="text-success">Role Updated successfully.</p>',
					animationSpeed: 2000
				});
				location.reload();
			}else if(resp.error != '0') {
				$.alert({
					title: '<i class="fa fa-check-circle text-warning"></i>&nbsp;</b>Error</b>',
					content: '<p class="text-success">'+resp.display+'</p>',
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





//Respondent save
function up_dateres(){
  var filing_no=document.getElementById("filing_no").value;
  var pet_name = document.getElementById("resName").value;
  var pet_address = document.getElementById("resAddress").value;
  var dstate = document.getElementById("stateRes").value;
  var ddistrict = document.getElementById("ddistrictname").value;
  var pet_pin = document.getElementById("respincode").value;
  var pet_email = document.getElementById("resEmail").value;
  var pet_mobile = document.getElementById("resMobile").value;
  var pet_phone = document.getElementById("resPhone").value;
  var pet_fax = document.getElementById("resFax").value;
  var resorgid = document.getElementById("resorgid").value;
  var dataa={};
    dataa['filing_no']=filing_no,
    dataa['res_name']=pet_name,
    dataa['res_address']=pet_address,
    dataa['res_state']=dstate,
    dataa['res_district']=ddistrict,
    dataa['res_pin']=pet_pin,
    dataa['res_email']=pet_email,
    dataa['res_mobile']=pet_mobile,
    dataa['res_phone']=pet_phone,
    dataa['res_fax']=pet_fax,
    dataa['resorgid']=resorgid,
  
    $.ajax({
	    dataType: 'json',
        type: "POST",
        url: base_url+'update_backlogfilingres',
        data: dataa,
        cache: false,
		beforeSend: function(){
			$('#nextsubmitres').prop('disabled',true).val("Under proccess....");
		},
        success: function (resp) {
        	if(resp.data=='success') {
        		$.alert({
					title: '<i class="fa fa-check-circle text-success"></i>&nbsp;</b>Success</b>',
					content: '<p class="text-success">Role Updated successfully.</p>',
					animationSpeed: 2000
				});
			}else if(resp.error != '0') {
				$.alert({
					title: '<i class="fa fa-check-circle text-warning"></i>&nbsp;</b>Error</b>',
					content: '<p class="text-success">'+resp.display+'</p>',
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

function searchorg(val){
	$.ajax({
		type: 'post',
		url: base_url+'getorg',
		data: {key:val},
		dataType: 'html',
		cache: false,
		beforeSend: function(){
		 	//$('#loading_modal').modal();
		},
		success: function(retn){
		    $('#regnum_autofill').show();
			$('#regnum_autofill').html(retn);
		 	
		},
		error: function(){
			$.alert('Error : server busy, try later');
		},
		complete: function(){
			document.getElementById("loading_modal").style.display = 'none';
			$('#loading_modal').modal('hide');
		}
	});	
}



function getorgPet(val){
	$.ajax({
		type: 'post',
		url: base_url+'getorgPet',
		data: {key:val},
		dataType: 'html',
		cache: false,
		beforeSend: function(){
		 	//$('#loading_modal').modal();
		},
		success: function(retn){
		    $('#regnum_autofillPet').show();
			$('#regnum_autofillPet').html(retn);
		 	
		},
		error: function(){
			$.alert('Error : server busy, try later');
		},
		complete: function(){
			document.getElementById("loading_modal").style.display = 'none';
			$('#loading_modal').modal('hide');
		}
	});	
}




function showorgPet(str) {
    var dataa = {};
    dataa['q'] = str;
    $.ajax({
        type: "POST",
        url: base_url+'orgdata',
        data: dataa,
        cache: false,
        success: function (petSection) {
        	 var data1 = petSection;
             var data2 = JSON.parse(data1);
             if (str != 0) {  
                 var stateid=data2[0].stcode;
                 var districtid=data2[0].dcode;
                 $("#ddistrictPet option[value='"+data2[0].dcode+"']").attr("selected","selected");
                 document.getElementById("pincodePet").value = data2[0].pin;
                 document.getElementById("petEmailPet").value = data2[0].mail;
                 document.getElementById("petmobilePet").value = data2[0].mob;
                 document.getElementById("petPhonePet").value = data2[0].ph;
                 document.getElementById("petFaxPet").value = data2[0].fax;
                 document.getElementById("petNamePet").value = data2[0].org_name;
                 document.getElementById("petAddressPet").value = data2[0].address;
                 document.getElementById("dstatePet").value = data2[0].stcode;
                 document.getElementById("ddistrictPet").value = data2[0].dcode;
                 document.getElementById("orgidPet").value = data2[0].orgid;
            	 showCityselectededit(stateid,districtid);
                 document.getElementById("apppanPet").value = '';
             }
        },
        error: function(){
			$.alert('Error : server busy, try later');
		},
		complete: function(){
			$('#regnum_autofillPet').hide();

			
		}
    });
}




function showorg(str) {
    var dataa = {};
    dataa['q'] = str;
    $.ajax({
        type: "POST",
        url: base_url+'orgdata',
        data: dataa,
        cache: false,
        success: function (petSection) {
        	 var data1 = petSection;
             var data2 = JSON.parse(data1);
             if (str != 0) {  
                 var stateid=data2[0].stcode;
                 var districtid=data2[0].dcode;
                 $("#ddistrict option[value='"+data2[0].dcode+"']").attr("selected","selected");
                 document.getElementById("pincode").value = data2[0].pin;
                 document.getElementById("petEmail").value = data2[0].mail;
                 document.getElementById("petmobile").value = data2[0].mob;
                 document.getElementById("petPhone").value = data2[0].ph;
                 document.getElementById("petFax").value = data2[0].fax;
                 document.getElementById("petName").value = data2[0].org_name;
                 document.getElementById("petAddress").value = data2[0].address;
                 document.getElementById("dstate").value = data2[0].stcode;
                 document.getElementById("ddistrict").value = data2[0].dcode;
                 document.getElementById("orgid").value = data2[0].orgid;
            	 showCityselectededit(stateid,districtid);
                 document.getElementById("apppan").value = '';

             }
        },
        error: function(){
			$.alert('Error : server busy, try later');
		},
		complete: function(){
			$('#regnum_autofill').hide();

			
		}
    });
}

function searchorg_resp(val){
	$.ajax({
		type: 'post',
		url: base_url+'getorg_resp',
		data: {key:val},
		dataType: 'html',
		cache: false,
		beforeSend: function(){
		 	//$('#loading_modal').modal();
		},
		success: function(retn){
            $('#regnum_autofill_r').show();
			$('#regnum_autofill_r').html(retn);
		 	
		},
		error: function(){
			$.alert('Error : server busy, try later');
		},
		complete: function(){
			document.getElementById("loading_modal").style.display = 'none';
			$('#loading_modal').modal('hide');
		}
	});	
}


function searchorg_respRes(val){
	$.ajax({
		type: 'post',
		url: base_url+'getorg_respRes',
		data: {key:val},
		dataType: 'html',
		cache: false,
		beforeSend: function(){
		 	//$('#loading_modal').modal();
		},
		success: function(retn){
            $('#regnum_autofill_rRes').show();
			$('#regnum_autofill_rRes').html(retn);
		 	
		},
		error: function(){
			$.alert('Error : server busy, try later');
		},
		complete: function(){
			document.getElementById("loading_modal").style.display = 'none';
			$('#loading_modal').modal('hide');
		}
	});	
}

 
function showorg_respRes(str) {
    var dataa = {};
    dataa['q'] = str;
    $.ajax({
        type: "POST",
        url: base_url+'orgdataRes',
        data: dataa,
        cache: false,
        success: function (petSection) {
        	 var data1 = petSection;
             var data2 = JSON.parse(data1);
             if (str != 0) {  
                 var stateidres=data2[0].stcode;
                 var districtidres=data2[0].dcode;
                 $("#ddistrictRes option[value='"+data2[0].dcode+"']").attr("selected","selected");
                 document.getElementById("respincodeRes").value = data2[0].pin;
                 document.getElementById("resEmailRes").value = data2[0].mail;
                 document.getElementById("resMobileRes").value = data2[0].mob;
                 document.getElementById("resPhoneRes").value = data2[0].ph;
                 document.getElementById("resFaxRes").value = data2[0].fax;
                 document.getElementById("resNameRes").value = data2[0].org_name;
                 document.getElementById("resAddressRes").value = data2[0].address;
                 document.getElementById("stateResRes").value = data2[0].stcode;
                 document.getElementById("ddistrictnameRes").value = data2[0].dcode;
                 document.getElementById("resorgidRes").value = data2[0].orgid;                 
            	 showCityselectededitres(stateidres,districtidres);
             }
        },
        error: function(){
			$.alert('Error : server busy, try later');
		},
		complete: function(){
			$('#regnum_autofill_rRes').hide();

			
		}
    });
}

function showorg_resp(str) {
    var dataa = {};
    dataa['q'] = str;
    $.ajax({
        type: "POST",
        url: base_url+'orgdata',
        data: dataa,
        cache: false,
        success: function (petSection) {
        	 var data1 = petSection;
             var data2 = JSON.parse(data1);
             if (str != 0) {  
                 var stateidres=data2[0].stcode;
                 var districtidres=data2[0].dcode;
                 $("#ddistrict option[value='"+data2[0].dcode+"']").attr("selected","selected");
                 document.getElementById("respincode").value = data2[0].pin;
                 document.getElementById("resEmail").value = data2[0].mail;
                 document.getElementById("resMobile").value = data2[0].mob;
                 document.getElementById("resPhone").value = data2[0].ph;
                 document.getElementById("resFax").value = data2[0].fax;
                 document.getElementById("resName").value = data2[0].org_name;
                 document.getElementById("resAddress").value = data2[0].address;
                 document.getElementById("stateRes").value = data2[0].stcode;
                 document.getElementById("ddistrictname").value = data2[0].dcode;
                 document.getElementById("resorgid").value = data2[0].orgid;                 
            	 showCityselectededitres(stateidres,districtidres);
                 document.getElementById("apppan").value = '';
             }
        },
        error: function(){
			$.alert('Error : server busy, try later');
		},
		complete: function(){
			$('#regnum_autofill_r').hide();

			
		}
    });
}


$(document).on("change","#dstate",function(){
            var state_id=$(this).val();
    if (state_id.length > 0) {
        $.ajax({
            type: "POST",
            url: base_url+"district",
            data: "state_id=" + state_id,
            cache: false,
            success: function (districtdata) {
                $("#ddistrict").html(districtdata);
            }
        });
    }
});

$(document).on("change","#stateRes",function(){
            var state_id=$(this).val();
    if (state_id.length > 0) {
        $.ajax({
            type: "POST",
            url: base_url+"district",
            data: "state_id=" + state_id,
            cache: false,
            success: function (districtdata) {
                $("#ddistrictname").html(districtdata);
            }
        });
    }
});

function showCityselectededit(sid,did) {
    var dataa = {};
    dataa['stateid'] = sid;
    dataa['districtid'] = did;
    $.ajax({
        type: "POST",
        url: base_url+"districtselected",
        data: dataa,
        cache: false,
        success: function (districtdata) {
            $("#ddistrict").html(districtdata);
        }
    });
}

function showCityselectededitres(sid,did) {
    var dataa = {};
    dataa['stateid'] = sid;
    dataa['districtid'] = did;
    $.ajax({
        type: "POST",
        url: base_url+"districtselectedres",
        data: dataa,
        cache: false,
        success: function (districtdata) {
            $("#ddistrictname").html(districtdata);
        }
    });
}


function backlogsavenext(){
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
    
    var filing_no = document.getElementById("filing_no").value;
    if(filing_no==''){
        alert("filing number is not valid!");
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
	dataa['filing_no']=filing_no;
	$.ajax({
        type: "POST",
        url: base_url+'backlogbasicdetailsave',
        data: dataa,
        cache: false,
		beforeSend: function(){
			//$('#petitioner_save').prop('disabled',true).val("Under proccess....");
		},
        success: function (resp) {
        	var resp = JSON.parse(resp);
        	if(resp.data=='success') {
    		   $(".massagebasic").text("Successfully updated ");
			}
			else if(resp.error != '0') {
				$.alert({
					title: '<i class="fa fa-exclamation text-danger"></i>&nbsp;</b>Error</b>',
					content: '<p class="text-danger">'+resp.massage+'</p>',
					animationSpeed: 200
				});
			}
        },
        error: function(){
			$.alert("Surver busy,try later.");
		}
	}); 
}

function up_councelAA(){
	var filing_no = document.getElementById("filing_no").value;
    if(filing_no==''){
        alert("filing number is not valid!");
        return false;
    }
    var conName = document.getElementById("advnameAA").value;
    if(conName==''){
	    alert("Please enter councel name!");
        return false;
	}
	
    var conPhone = document.getElementById("counselPhoneAA").value;
    var conEmail = document.getElementById("counselEmailAA").value;
    if(conEmail==''){
	    alert("Please enter councel email!");
        return false;
	}
    var conAddress = document.getElementById("conAddressAA").value;
    var conpincode = document.getElementById("counselPinAA").value;
    var conFax = document.getElementById("counselFaxAA").value;
    var conMobile = document.getElementById("counselMobileAA").value;
    if(conMobile==''){
	    alert("Please enter mobile number!");
        return false;
	}
	var ddistrictname= document.getElementById("cddistrictAA").value;
	var dstatename= document.getElementById("cdstateAA").value;
	var councilCode= document.getElementById("councilCodeAA").value;
	
    var dataa = {};
	dataa['filing_no']=filing_no;
	dataa['conName']=conName,
	dataa['conPhone']=conPhone,
	dataa['conEmail']=conEmail;
	dataa['conAddress']=conAddress;
	dataa['conFax']=conFax;
	dataa['conpincode']=conpincode;
	dataa['conmobile']=conMobile;
	dataa['ddistrictname']=ddistrictname;
	dataa['dstatename']=dstatename;
	dataa['councilCode']=councilCode;
	$.ajax({
        type: "POST",
        url: base_url+'backlogcouncelsaveAA',
        data: dataa,
        cache: false,
		beforeSend: function(){
			//$('#councelsubmit').prop('disabled',true).val("Under proccess....");
		},
        success: function (resp) {
        	var resp = JSON.parse(resp);
        	if(resp.data=='success') {
    		   $.alert({
					title: '<i class="fa fa-check-circle text-success"></i>&nbsp;</b>Success</b>',
					content: '<p class="text-success">Councel detail Updated successfully.</p>',
					animationSpeed: 2000
				});
				 location.reload();
			}
			
			else if(resp.error != '0') {
				$.alert({
					title: '<i class="fa fa-check-circle text-warning"></i>&nbsp;</b>Error</b>',
					content: '<p class="text-success">'+resp.display+'</p>',
					animationSpeed: 2000
				});
			}
        },
        error: function(){
			$.alert("Surver busy,try later.");
		}
	}); 
}


function up_councel(){
	var filing_no = document.getElementById("filing_no").value;
    if(filing_no==''){
        alert("filing number is not valid!");
        return false;
    }
    var conName = document.getElementById("advname").value;
    if(conName==''){
	    alert("Please enter councel name!");
        return false;
	}
	
    var conPhone = document.getElementById("conPhone").value;
    var conEmail = document.getElementById("conEmail").value;
    if(conEmail==''){
	    alert("Please enter councel email!");
        return false;
	}
    var conAddress = document.getElementById("conAddress").value;
    var conpincode = document.getElementById("conpincode").value;
    var conFax = document.getElementById("conFax").value;
    var conMobile = document.getElementById("conMobile").value;
    if(conMobile==''){
	    alert("Please enter mobile number!");
        return false;
	}
	var ddistrictname= document.getElementById("cddistrict").value;
	var dstatename= document.getElementById("cdstate").value;
	var councilCode= document.getElementById("councilCode").value;
	
    var dataa = {};
	dataa['filing_no']=filing_no;
	dataa['conName']=conName,
	dataa['conPhone']=conPhone,
	dataa['conEmail']=conEmail;
	dataa['conAddress']=conAddress;
	dataa['conFax']=conFax;
	dataa['conpincode']=conpincode;
	dataa['conmobile']=conMobile;
	dataa['ddistrictname']=ddistrictname;
	dataa['dstatename']=dstatename;
	dataa['councilCode']=councilCode;
	$.ajax({
        type: "POST",
        url: base_url+'backlogcouncelsave',
        data: dataa,
        cache: false,
		beforeSend: function(){
			//$('#councelsubmit').prop('disabled',true).val("Under proccess....");
		},
        success: function (resp) {
        	var resp = JSON.parse(resp);
        	if(resp.data=='success') {
    		   $.alert({
					title: '<i class="fa fa-check-circle text-success"></i>&nbsp;</b>Success</b>',
					content: '<p class="text-success">Councel detail Updated successfully.</p>',
					animationSpeed: 2000
				});
			}
			else if(resp.error != '0') {
				$.alert({
					title: '<i class="fa fa-check-circle text-warning"></i>&nbsp;</b>Error</b>',
					content: '<p class="text-success">'+resp.display+'</p>',
					animationSpeed: 2000
				});
			}
        },
        error: function(){
			$.alert("Surver busy,try later.");
		}
	}); 
}



function disposedstatus(){
	var status= document.getElementById("status").value;
	var disposeddate= document.getElementById("disposeddate").value;
	var filing_no= document.getElementById("filing_no").value;
    var dataa = {};
	dataa['status']=status;
	dataa['disposeddate']=disposeddate,
	dataa['filing_no']=filing_no,
	$.ajax({
        type: "POST",
        url: base_url+'caseupdatestatus',
        data: dataa,
        cache: false,
		beforeSend: function(){
			//$('#councelsubmit').prop('disabled',true).val("Under proccess....");
		},
        success: function (resp) {
        	var resp = JSON.parse(resp);
        	if(resp.data=='success') {
    		   $.alert({
					title: '<i class="fa fa-check-circle text-success"></i>&nbsp;</b>Success</b>',
					content: '<p class="text-success">Councel detail Updated successfully.</p>',
					animationSpeed: 2000
				});
			}
			else if(resp.error != '0') {
				$.alert({
					title: '<i class="fa fa-check-circle text-warning"></i>&nbsp;</b>Error</b>',
					content: '<p class="text-success">'+resp.display+'</p>',
					animationSpeed: 2000
				});
			}
        },
        error: function(){
			$.alert("Surver busy,try later.");
		}
	}); 
}


function backserchrecordvaladv(val){
	$.ajax({
		type: 'post',
		url: base_url+'getAdv',
		data: {key:val},
		dataType: 'html',
		cache: false,
		beforeSend: function(){
		 	//$('#loading_modal').modal();
		},
		success: function(retn){
		    $('#regnum_autofilladv').show();
			$('#regnum_autofilladv').html(retn);
		},
		error: function(){
			$.alert('Error : server busy, try later');
		},
		complete: function(){
			document.getElementById("loading_modal").style.display = 'none';
			$('#loading_modal').modal('hide');
		}
	});	
}


 
function showUserOrg1(str) {
	var dataa = {};
	dataa['q'] = str;
    $.ajax({
        type: "POST",
        url: base_url+'getAdvDetail1',
        data: dataa,
        cache: false,
        success: function (petSection) {
        	 var data1 = petSection;
             console.log(data1);
             var data2 = JSON.parse(data1);
             if (str != 0) {
                 document.getElementById("councilCode").value = data2[0].adv_code;
                 document.getElementById("advname").value = data2[0].adv_name;
                 document.getElementById("conAddress").value = data2[0].address;
                 document.getElementById("conMobile").value = data2[0].mob;
                 document.getElementById("conEmail").value = data2[0].mail;
                 document.getElementById("conPhone").value = data2[0].ph;
                 document.getElementById("conpincode").value = data2[0].pin;
                 document.getElementById("conFax").value = data2[0].fax;
                 document.getElementById("cdstate").value = data2[0].stcode;
                 document.getElementById("dstatename").value = data2[0].stname;
                 document.getElementById("cddistrict").value = data2[0].dcode;
                 document.getElementById("ddistrictname").value = data2[0].dname;

             }
        },
        error: function(){
			$.alert('Error : server busy, try later');
		},
		complete: function(){
		     $('#regnum_autofilladv').hide();
			$('#loading_modal').modal('hide');
		}
    });
}

function showUserOrg1AA(str) {
	var dataa = {};
	dataa['q'] = str;
    $.ajax({
        type: "POST",
        url: base_url+'getAdvDetail1',
        data: dataa,
        cache: false,
        success: function (petSection) {
        	 var data1 = petSection;
             console.log(data1);
             var data2 = JSON.parse(data1);
             if (str != 0) {
                 document.getElementById("councilCodeAA").value = data2[0].adv_code;
                 document.getElementById("advnameAA").value = data2[0].adv_name;
                 document.getElementById("conAddressAA").value = data2[0].address;
                 document.getElementById("counselMobileAA").value = data2[0].mob;
                 document.getElementById("counselEmailAA").value = data2[0].mail;
                 document.getElementById("counselPhoneAA").value = data2[0].ph;
                 document.getElementById("counselPinAA").value = data2[0].pin;
                 document.getElementById("counselFaxAA").value = data2[0].fax;
                 document.getElementById("cdstateAA").value = data2[0].stcode;
                 document.getElementById("dstatenameAA").value = data2[0].stname;
                 document.getElementById("cddistrictAA").value = data2[0].dcode;
                 document.getElementById("ddistrictnameAA").value = data2[0].dname;

             }
        },
        error: function(){
			$.alert('Error : server busy, try later');
		},
		complete: function(){
		     $('#regnum_autofillAA').hide();
			$('#loading_modal').modal('hide');
		}
    });
}





function backserchrecordvaladvAA(val){
	$.ajax({
		type: 'post',
		url: base_url+'getAdvAA',
		data: {key:val},
		dataType: 'html',
		cache: false,
		beforeSend: function(){
		 	//$('#loading_modal').modal();
		},
		success: function(retn){
		    $('#regnum_autofillAA').show();
			$('#regnum_autofillAA').html(retn);
		},
		error: function(){
			$.alert('Error : server busy, try later');
		},
		complete: function(){
			document.getElementById("loading_modal").style.display = 'none';
			$('#loading_modal').modal('hide');
		}
	});	
}




function showUserOrg(str) {
	var dataa = {};
	dataa['q'] = str;
    if (str == '0') {
        document.getElementById("counselAdd").value = "";
        document.getElementById("counselMobile").value = "";
        document.getElementById("counselEmail").value = "";
        document.getElementById("counselPhone").value = "";
        document.getElementById("counselPin").value = "";
        document.getElementById("counselFax").value = "";
        document.getElementById("cdstate").value = "";
        document.getElementById("dstatename").value = "";
        document.getElementById("cddistrict").value = "";
        document.getElementById("ddistrictname").value = "";
    }
    $.ajax({
        type: "POST",
        url: base_url+'getAdvDetail',
        data: dataa,
        cache: false,
        success: function (petSection) {
        	 var data1 = petSection;
             console.log(data1);
             var data2 = JSON.parse(data1);
             if (str != 0) {
                 document.getElementById("councilCode").value = data2[0].adv_code;
                 document.getElementById("advname").value = data2[0].adv_name;
                 document.getElementById("conAddress").value = data2[0].address;
                 document.getElementById("conMobile").value = data2[0].mob;
                 document.getElementById("conEmail").value = data2[0].mail;
                 document.getElementById("conPhone").value = data2[0].ph;
                 document.getElementById("conpincode").value = data2[0].pin;
                 document.getElementById("conFax").value = data2[0].fax;
                 document.getElementById("cdstate").value = data2[0].stcode;
                 document.getElementById("dstatename").value = data2[0].stname;
                 document.getElementById("cddistrict").value = data2[0].dcode;
                 document.getElementById("ddistrictname").value = data2[0].dname;
                 $('#regnum_autofilladv').hide();
             }
        },
        error: function(){
			$.alert('Error : server busy, try later');
		},
		complete: function(){
		    $('#regnum_autofilladv').hide();
			$('#regnum_autofilladv').modal('hide');
		}
    });
}



function showparty(radio_selected, faling_no) {
    $.ajax({
        type: "POST",
        url: base_url+"dropdown_party_details",
        data: "radio_selected=" + radio_selected + "&faling_no=" + faling_no,
        cache: false,
        success: function (districtdata) {
            $("#additionla_partyyval").html(districtdata);
        }
    });
}
    
    
 function iadispasol() {
    var casestatus1 = document.getElementById("iastatus").value;
    if (casestatus1 == 'D') {
        document.getElementById("iadispasolId1").style.display = 'block';
    } else {
        document.getElementById("iadispasolId1").style.display = 'none';
    }
}


 function fn_add_ia_insert() {
        var action = 'add_ia_module';      
        var filed_by = $("input[name='appAnddef']:checked").val();
        var salt = $("#salt").val();
        var additionla_partyy = $("#additionla_partyy").val();
        var iaNo = $("#iaNo").val();
        var iaYear = $("#iaYear").val();
        var appAnddef = $("#appAnddef").val();
        var ianature = $("#ianature").val();
        var iastatus= $("#iastatus").val();
        var matter = $("#matter").val();
        var iadisdate= $("#iadisdate").val();
        var iafilingdate= $("#iafilingdate").val();
        var data = {};
        data['filed_by'] = filed_by;
        data['action'] = action;
        data['salt'] =salt;
        data['additionla_partyy'] = additionla_partyy;
        data['iaNo'] = iaNo;
        data['iaYear'] = iaYear;
        data['appAnddef'] =appAnddef;
        data['ianature'] =ianature;
        data['iastatus'] =iastatus;
        data['matter'] =matter;
        data['iadisdate'] = iadisdate;
        data['iafilingdate'] = iafilingdate;
        $.ajax({
            type: "POST",
            url: base_url+"i_ia_insert",
            data: data,
            dataType: "html",
            success: function (data) {
                $('#exampleModal').modal('hide');
                location.reload();
            },
            error: function (request, error) {
                alert("Duplicate IA Number");
            }
        });
    }



 function delete_party_ia(faling_no, ia_faling_no) {
        var r = confirm("Are you sure want to delete?");
        if (r == true) {
            var data = {};
            data['action'] = "ia_party_delete";
            data['faling_no'] = faling_no;
            data['ia_faling_no'] = ia_faling_no;
            $.ajax({
                type: "POST",
                url: base_url+"delete_edit_ia",
                data: data,
                dataType: "html",
                success: function (data) {
					location.reload();
                    $("#rowval"+ia_faling_no).hide();
                },
                error: function (request, error) {
                    $("#table_data_ia_data").html(data);
                }
            });
        }
    }

    
    
    
    
    
function deletePartyadvAA(e) {
    var dataa = {};
	dataa['id'] =e,
	$.ajax({
        type: "POST",
        url: base_url+'deleteAdvocateAA',
        data: dataa,
        cache: false,
		beforeSend: function(){
			$('#deletesubmit').prop('enabled',true).val("Under proccess....");
		},
        success: function (resp) {
        	 var data2 = JSON.parse(resp);
             if (data2.data=='success') {  
                 $('#example').html(data2.display);
                 location.reload();
             }
             if (data2.error=='1') {  
             	$.alert({
					title: '<i class="fa fa-check-circle text-danger"></i>&nbsp;</b>Error</b>',
					content: '<p class="text-success">'+data2.massage+'</p>',
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
    
