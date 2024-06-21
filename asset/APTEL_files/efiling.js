function addMoreAmount() {
    var salt = document.getElementById("saltNo").value;
    var radios = document.getElementsByName("bd");
    var bd = 0;
    for (var i = 0; i < radios.length; i++) {
        if (radios[i].checked) {
            var bd = radios[i].value;
        }
    }

    if (bd == 1) {
        var dbankname = document.getElementById("dbankname").value;
        if (dbankname == "") {
            alert("Please Enter Bank name");
            document.filing.dbankname.focus();
            return false;
        }
        var ddno = document.getElementById("ddno").value;
        if (ddno == "") {
            alert("Please Enter DD No");
            document.filing.ddno.focus();
            return false
        }
        var dddate = document.getElementById("dddate").value;
        if (dddate == "") {
            alert("Please Enter DD Date ");
            document.filing.dddate.focus();
            return false
        }
        var amountRs = document.getElementById("amountRs").value;
        if (amountRs == "") {
            alert("Please Enter Amount ");
            document.filing.amountRs.focus();
            return false
        }
    }
    if (bd == 2) {
        var dbankname = document.getElementById("postofficename").value;
        var amountRs = document.getElementById("ipoamount").value;
        var dddate = document.getElementById("ipodate").value;
        if (dbankname == "") {
            alert("Please Enter Post Office Name ");
            document.filing.postofficename.focus();
            return false
        }
        if (amountRs == "") {
            alert("Please Enter Amount ");
            document.filing.ipoamount.focus();
            return false
        }
        if (dddate == "") {
            alert("Please Enter Ip Date ");
            document.filing.ipodate.focus();
            return false
        }
    }
    if (bd == 3) {
        var dbankname = document.getElementById("ntrp").value;
        if (dbankname == "") {
            alert("Please Enter Bank name");
            document.filing.ntrp.focus();
            return false;
        }
        var ddno = document.getElementById("ntrpno").value;
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
    var dataa = {};
	dataa['dbankname'] =dbankname,
	dataa['amountRs'] =amountRs,
	dataa['ddno'] =ddno,
	dataa['dddate'] = dddate,
	dataa['bd'] =  bd,
	dataa['salt'] =salt,
	$.ajax({
	    dataType: 'json',
        type: "POST",
        url: base_url+'addMoredd',
        data: dataa,
        cache: false,
		beforeSend: function(){
			$('#btnSubmit').prop('disabled',true).val("Under proccess....");
		},
        success: function (resp) {
        	var resp = JSON.parse(resp);
        	if(resp.data=='success') {
        		var next_clickid= document.getElementById("step_8");
        		$.('#next_clickid'). trigger ('click');
			}
			else if(resp.error != '0') {
				$.alert(resp.error);
			}
        },
        error: function(){
			$.alert("Surver busy,try later.");
		},
		complete: function(){
			$('#btnSubmit').prop('disabled',false).val("Submit");
		}
	 }); 
    if (bd == 1) {
        document.getElementById("dbankname").value = "";
        document.getElementById("ddno").value = "";
        document.getElementById("dddate").value = "";
        document.getElementById("amountRs").value = "";
    }if (bd == 2) {
        document.getElementById("postofficename").value = "";
        document.getElementById("ipoamount").value = "";
        document.getElementById("ipodate").value = "";
    }if (bd == 3) {
        document.getElementById("ntrpno").value = "";
        document.getElementById("ntrpdate").value = "";
        document.getElementById("ntrpamount").value = "";
    }
}

/*function addMore() {
    var cnt1 = Number(document.getElementById("cnt").value);
    var firs_comind = $("#commission").val();
    var dataa = {};
    dataa['cnt'] = cnt1;
    dataa['firs_comind'] = firs_comind;
    $.ajax({
        type: "POST",
        url: base_url+'addmorecommition',
        data: dataa,
        cache: false,
        success: function (petSection) {
            $("#product").append(petSection);
            $(".commission1dddddddd").val(firs_comind);
        }
    });
    document.getElementById("cnt").value = cnt1 + 1;
}
*/
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


function addMoreApp() {
    var salt = document.getElementById("saltNo").value;
    var petName = document.getElementById("petName").value;
    var degingnation = document.getElementById("degingnation").value;
    var petAddress = document.getElementById("petAddress").value;
    var dstate = document.getElementById("dstate").value;
    var ddistrict = document.getElementById("ddistrict").value;
    var pincode = document.getElementById("pincode").value;
    var petmobile = document.getElementById("petmobile").value;
    var petPhone = document.getElementById("petPhone").value;
    var petEmail = document.getElementById("petEmail").value;
    var petFax = document.getElementById("petFax").value;
    var counselAdd = document.getElementById("counselAdd").value;
    var counselPin = document.getElementById("counselPin").value;
    var counselMobile = document.getElementById("counselMobile").value;
    var counselPhone = document.getElementById("counselPhone").value;
    var counselEmail = document.getElementById("counselEmail").value;
    var counselFax = document.getElementById("counselFax").value;
    var councilCode = document.getElementById("councilCode").value;
    if (petName == "") {
        alert("Please Enter Org / Appellant Name ");
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
    if (councilCode == "") {
        alert("Please Select Council Code");
        return false;
    }
    var dataa = {};
	dataa['patname'] =petName,
	dataa['petAdv'] =petAddress,
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
	$.ajax({
	    dataType: 'json',
        type: "POST",
        url: base_url+'addMoreAppellant',
        data: dataa,
        cache: false,
		beforeSend: function(){
			$('#nextsubmit').prop('disabled',true).val("Under proccess....");
		},
        success: function (resp) {
        	var resp = JSON.parse(resp);
        	if(resp.data=='success') {
				$.alert({
				});	
			}
			else if(resp.error != '0') {
				$.alert(resp.error);
			}
        },
        error: function(){
			$.alert("Surver busy,try later.");
		},
		complete: function(){
			$('#nextsubmit').prop('disabled',false).val("Submit");
		}
	 }); 
}