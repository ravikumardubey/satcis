//basic detail js
function otherMatter() {
    var otherMatterLower = document.getElementById("case_type_lower").value;
    if (otherMatterLower == 17) {
        document.getElementById("anyOtherLower").style.display = 'block';
        document.getElementById("show").style.display = 'none';
    } else {
        document.getElementById("anyOtherLower").style.display = 'none';
        document.getElementById("show").style.display = 'inline-block';
    }
}






function droftserch(val){
	var dataa={};
    dataa['saltNo']=val;
	$.ajax({
        type: "POST",
        url: base_url+"draftrefiling",
        data: dataa,
        cache: false,
        success: function (resp) {
        	var resp = JSON.parse(resp);
        	if(resp.data=='success') {
        		$('.steps').empty().load(base_url+'/efiling/case_filing_steps');
			}
        },
        error: function (request, error) {
			$('#loading_modal').fadeOut(200);
        }
    });
}


$(document).ready(function() {
	 var act_id = $("#act").val();
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
});


function isNumberKey(evt){ 
    var act_id = $("#case_type_lower").val();
	if(act_id!=17){
       var charCode = (evt.which) ? evt.which : event.keyCode
       if (charCode > 31 && (charCode < 48 || charCode > 57)){
         return false;
       }else{
   		 return true;
       }
	}
}


function isNumberKey1(evt){ 
	var cnt =document.getElementById("cnt").value;
	var i;
	for (i = 0; i < cnt; i++) {
     var otherMatterLower = document.getElementById("case_type_lower"+i).value;
    	if(otherMatterLower!=17){
           var charCode = (evt.which) ? evt.which : event.keyCode
           if (charCode > 31 && (charCode < 48 || charCode > 57)){
             return false;
           }else{
       		 return true;
           }
    	}
	}
}
 

function otherMatter1() {
	var cnt =document.getElementById("cnt").value;
	var i;
	for (i = 0; i < cnt; i++) {
     var otherMatterLower = document.getElementById("case_type_lower"+i).value;
        if (otherMatterLower == 17) {
            document.getElementById("anyOtherLower"+i).style.display = 'block';
            $("#caseNo"+i).removeClass("allownumericwithoutdecimal");
        } else {
            document.getElementById("anyOtherLower"+i).style.display = 'none';
            $("#caseNo"+i).addClass("allownumericwithoutdecimal");
        }
        
	}
}

function fn_check_caveat() {
    $("#testdiv_cavetor_details").hide();
    $("#caveator_details_data").empty();
    var commission = $("#commission").val();
    var case_no = $("#caseNo").val();
    var case_year = $("#caseYear").val();
    var decision_date = $("#ddate").val();


    if (commission == '') {
        alert('select Comission ');
        return false;
    }
    if (case_no == '') {
        alert('Enter Case No');
        return false;
    }
    if (case_year == '') {
        alert('Select Year');
        return false;
    }
    if (decision_date == '') {
        alert('Enetr Decision date');
        return false;
    }
    var data = {};
    data['action'] = 'check_caveat_data';
    data['case_no'] = case_no;
    data['case_year'] = case_year;
    data['decision_date'] = decision_date;
    data['commission'] = commission;
    $.ajax({
        type: "POST",
        url: base_url+"filing_ajax",
        data: data,
        dataType: "html",
        success: function (data) {
            $("#testdiv_cavetor_details").show();
            $("#caveator_details_data").html(data);
        },
        error: function (request, error) {
            console.log("Something error.");
        }
    });
}











$('#petitioner_detail').submit(function(e){ 
	e.preventDefault();
	var salt = document.getElementById("saltNo").value;
    var petName = document.getElementById("petName").value;
    var degingnation = document.getElementById("degingnation").value;
    var dstate = document.getElementById("dstate").value;
    var ddistrict = document.getElementById("ddistrict").value;
    if(ddistrict==''){
    	var ddistrict= document.getElementById("petdistrictname").value;
    }
    var petAddress = document.getElementById("petAddress").value;
    var pincode = document.getElementById("pincode").value;
    var petmobile = document.getElementById("petmobile").value;
    var petPhone = document.getElementById("petPhone").value;
    var petEmail = document.getElementById("petEmail").value;
    var petFax = document.getElementById("petFax").value;
    var councilCode = document.getElementById("councilCode").value;
    var counselAdd = document.getElementById("counselAdd").value;
    var counselPin = document.getElementById("counselPin").value;
    var counselMobile = document.getElementById("counselMobile").value;
    var counselPhone = document.getElementById("counselPhone").value;
    var counselEmail = document.getElementById("counselEmail").value;
    var counselFax = document.getElementById("counselFax").value;
    var tabno = document.getElementById("tabno").value;

    var totalNoAppellants = Number(document.getElementById("totalNoAppellants").value);
    var count = Number(document.getElementById("count").value);
    var val=totalNoAppellants;
    if(count!=val){
    	alert("Appellants should not greater-than total No Appellants");
    	return false;
    }

    var org='';
    var checkboxes = document.getElementsByName('org');
    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].checked) {
            org = checkboxes[i].value;
        }
    }
    
    if (petName == "") {
        alert("Please Enter Org / Appellant Name!");
        document.filing.petName.focus();
        return false;
    }
    if (dstate == "" || dstate == 'Select State Name') {
        alert("Please Select State!");
        document.filing.dstate.focus();
        return false;
    }
    if (ddistrict == "" || ddistrict == 'Select District Name') {
        alert("Please Select District !");
        document.filing.ddistrict.focus();
        return false;
    }

    if (totalNoAppellants == "") {
        alert("Please Enter Number Of Appellants");
        document.filing.totalNoAppellants.focus();
        return false;
    }
    
    var dataa = {};
	dataa['patname'] =petName,
	dataa['petAddress'] =petAddress,
	dataa['pin'] =pincode,
	dataa['petMob'] = petmobile,
	dataa['petph'] =  petPhone,
	dataa['petemail'] =petEmail,
	dataa['petfax'] =  petFax,
	dataa['cadd'] = counselAdd,
	dataa['cpin'] =  counselPin,
	dataa['cmob'] = counselMobile,
	dataa['cemail'] =  counselEmail,
	dataa['cfax'] = counselFax,
	dataa['salt'] = salt,
	dataa['petdeg'] = degingnation,
	dataa['counselpho'] = counselPhone,
	dataa['dstate'] = dstate,
	dataa['ddistrict'] = ddistrict,
	dataa['councilCode'] =  councilCode,
	dataa['totalNoAppellants']=totalNoAppellants;
	dataa['tabno']=tabno;
	dataa['org']=org;
	dataa['noapplent']=val;
	
	$.ajax({
        type: "POST",
        url: base_url+'orgshowres',
        data: dataa,
        cache: false,
		beforeSend: function(){
			$('#petitioner_save').prop('disabled',true).val("Under proccess....");
		},
        success: function (resp) {
        	var resp = JSON.parse(resp);
        	if(resp.data=='success') {
        		$('#rightbar').empty().load(base_url+'/loadpage/respondent');
        	    document.getElementById("loading_modal").style.display = 'none';
        	    $('#step_2').removeClass('btn-danger btn-warning btn-info').addClass('btn-success');
        	    $('#step_3').removeClass('btn-danger btn-warning btn-info').addClass('btn-warning');
			}
			else if(resp.error != '0') {
				$.alert(resp.error);
			}
        },
        error: function(){
			$.alert("Surver busy,try later.");
		},
		complete: function(){
			$('#petitioner_save').prop('disabled',false).val("Submit");
		}
	 }); 
});









/*
function orgshow() {
    var checkboxes = document.getElementsByName('org');
    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].checked) {
            idorg = checkboxes[i].value;
        }
    }
    if (idorg == 1) {
    	$('#orgDetail').empty().load(base_url+'/efiling/orgdetail');
        document.getElementById("orgDetail").style.display = 'block';
        document.getElementById("appleDetail").style.display = 'none';
        $("#appleDetail").empty();
    }
    if (idorg == 2) {
        $('#appleDetail').empty().load(base_url+'/efiling/appellantDetail');
        document.getElementById("orgDetail").style.display = 'none';
        $("#orgDetail").empty();
        document.getElementById("appleDetail").style.display = 'block';
    }
}



function showUserApp(str) {
    var dataa = {};
    dataa['q'] = str;
    $.ajax({
        type: "POST",
        url: base_url+'org',
        data: dataa,
        cache: false,
        success: function (petSection) {
        	 var data1 = petSection;
             console.log(data1);
             var data2 = JSON.parse(data1);
             if (str != 0) {
            	 alert("ok");
                 document.getElementById("petAddress").value = data2[0].address;
                 document.getElementById("petmobile").value = data2[0].mob;
                 document.getElementById("petEmail").value = data2[0].mail;
                 document.getElementById("petPhone").value = data2[0].ph;
                 document.getElementById("pincode").value = data2[0].pin;
                 document.getElementById("petFax").value = data2[0].fax;
                 document.getElementById("dstate").value = data2[0].stcode;
                 document.getElementById("petstatename").value = data2[0].stname;
                 document.getElementById("ddistrict").value = data2[0].dcode;
                 document.getElementById("petdistrictname").value = data2[0].dname;
                 document.getElementById("degingnation").value = data2[0].desg;
             }
        }
    });
}
*/

/*function showUserOrg(str) {
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
                 document.getElementById("counselAdd").value = data2[0].address;
                 document.getElementById("counselMobile").value = data2[0].mob;
                 document.getElementById("counselEmail").value = data2[0].mail;
                 document.getElementById("counselPhone").value = data2[0].ph;
                 document.getElementById("counselPin").value = data2[0].pin;
                 document.getElementById("counselFax").value = data2[0].fax;
                 document.getElementById("cdstate").value = data2[0].stcode;
                 document.getElementById("dstatename").value = data2[0].stname;
                 document.getElementById("cddistrict").value = data2[0].dcode;
                 document.getElementById("ddistrictname").value = data2[0].dname;
             }
        }
    });
}
*/

function showUser(str) {
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
                document.getElementById("counselAdd").value = data2[0].address;
                document.getElementById("counselMobile").value = data2[0].mob;
                document.getElementById("counselEmail").value = data2[0].mail;
                document.getElementById("counselPhone").value = data2[0].ph;
                document.getElementById("counselPin").value = data2[0].pin;
                document.getElementById("counselFax").value = data2[0].fax;
                document.getElementById("cdstate").value = data2[0].stcode;
                document.getElementById("dstatename").value = data2[0].stname;
                document.getElementById("cddistrict").value = data2[0].dcode;
                document.getElementById("ddistrictname").value = data2[0].dname;
             }
        }
    });
}


/*function deleteParty(e, ee) {
    var count = document.getElementById("count").value;
    var partyid = e;
    var party = ee;
    var salt = document.getElementById("saltNo").value;
    var dataa = {};
	dataa['id'] =partyid,
	dataa['party'] =party,
	dataa['salt'] =salt,
	$.ajax({
	
        type: "POST",
        url: base_url+'deleteParty',
        data: dataa,
        cache: false,
		beforeSend: function(){
			$('#deletesubmit').prop('enabled',true).val("Under proccess....");
		},
        success: function (resp) {
        		$('#addmorerecordapp').html(resp);
			$('#count').val(val);
        },
        error: function(){
			$.alert("Surver busy,try later.");
		},
		complete: function(){
			$('#nextsubmit').prop('enabled',false).val("Add More Appellant");
		}
	 }); 
}   
*/

function showCity(sel) {
    var state_id = sel.options[sel.selectedIndex].value;
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
}

function isNumberKey(evt){ 
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57)){
      return false;
    }else{
		 return true;
    }
}
/*************************************************END basic detail js********************/


/*************************************************applant section js********************/


/*************************************************applant section js********************/