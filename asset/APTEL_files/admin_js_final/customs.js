$(document).ready(function(){
	$('.nav-link').click(function() { 
	    $('.nav-link').closest('li').removeClass("active");   
	    $(this).closest('li').addClass("active");  
	    var content = $(this).data('value');
	    if(content!=''){
	    	if(content=='caveat'){
	    		$('.steps').empty().load(base_url+'/mystart/'+content);
	    	}else if(content=='review_petition_filing'){
	    		$('.steps').empty().load(base_url+'/filingaction/'+content);
	    	}else if(content=='edit_contempt_petition_filing'){
	    		$('.steps').empty().load(base_url+'/filingaction/'+content);
	    	}else if(content=='edit_execution_petition_filing'){
	    		$('.steps').empty().load(base_url+'/filingaction/'+content);
	    	}else if(content=='edit_ia_details_filing'){
	    		$('.steps').empty().load(base_url+'/filingaction/'+content);
	    	}else if(content=='edit_document_filing'){
	    		$('.steps').empty().load(base_url+'/filingaction/'+content);
	    	}
	    	if(content!='caveat'){
	    		$('.steps').empty().load(base_url+'/efiling/'+content);
	    	}
	    }
	});
});


/*$(document).ready(function(){
	 $(".datepicker" ).datepicker({ 
		 dateFormat: "dd/mm/yy",
		 maxDate: 'now'});
	}); 
*/

$(document).ready(function(){
	$('.btn-breadcrumb > a').click(function(e){
		e.preventDefault();		
		$('#loading_modal').modal('show');
		var click_event=$.trim($(this).attr('id')), click_event_text=$.trim($(this).text()),
	
		programe_name='', page_load_url='',click_id_number='';

		click_event_text=click_event_text.toLowerCase(), programe_name=click_event_text.replace(' ','_'), 
		page_load_url =base_url+'/loadpage/'+programe_name, click_id_number=parseInt(click_event.slice(-1));

		$('#'+click_event).removeClass('btn-danger btn-warning btn-info').addClass('btn-warning');
		
		for(var i=1; i<click_id_number; i++) {
			$('#step_'+i).removeClass('btn-danger btn-warning btn-info').addClass('btn-success');
		}
		for(var j=(click_id_number+1); j<=8; j++) {
			$('#step_'+j).removeClass('btn-success btn-warning btn-info').addClass('btn-danger');
		}

		$('#rightbar').empty().load(page_load_url);

		$.ajax({
				type: 'post',
				url: base_url+'list_data',
				data: {"table_name": programe_name},
				dataType: 'html',
				success: function(get_list) {
					$('#'+programe_name+'_list').empty().html(get_list);
				},
				error: function(){
					alert('Server error,try later');
				},
				complete: function(){
					$('#'+programe_name+'_table').DataTable();
				}

		});
		
		setTimeout(function() {
		  $('#loading_modal').modal('hide');
		}, 500);
	});

});
	$('#state').change(function(e){
	  	e.preventDefault();
	  	var state_id=$.trim($(this).val());
	  	if(state_id !='') {
	  		$.ajax({
	  			url: base_url+'getDistrict',
	  			type: 'post',
	  			data: {"state_id":state_id},
	  			dataType: 'json',
	  			beforeSend: function(){
	  				$('#district').find(":selected").text("Feaching Districts.....");
	  			},
	  			success: function(resp){
	  				if(resp.error=='0'){
	  					$('#district').removeAttr('disabled').empty();
	  					$('#district').html('<option value="">-----Select District Name-----</option>');
	  					$.each(resp.data, function(index, itemData) {
	  						var option='<option value="'+itemData.district_id+'">'+itemData.district_name+'</option>';
	  						$('#district').append(option);
						});
	  				}
	  				else {
	  					$('#district').find(":selected").text("-----Select District Name-----");
	  					alert(resp.error);
	  				}
	  			},
	  			error: function(){
	  				alert('Server error, try later.');
	  			}

	  		});
	  	}
	  	else {
	  		alert("Please select valid state name.");
	  		$('#district').attr('disabled',true).empty();
	  		$('#district').html('<option value="">-----Select District Name-----</option>');
	  		return false;
	  	}
  	});







/*function get_date(id) {

$('#add_btn_commission').removeAttr('disabled');
$(id).datepicker({
	autoclose: true,
	immediateUpdates: true,
	format: 'yyyy-mm-dd',
	startView: 0,
	todayHighlight: true,
	startDate: '-2W',
	endDate: '+4W'
});
}*/

function add_more_commision_details() {
var commission='',commission_text='', natureOrder='', order_type_text='', caseNo='', caseYear='', ddate='';
    commission_text=$("select[name='commission'] > option:selected").text(), order_type_text=$("select[name='nature_of_order'] > option:selected").text();

commission = $("select[name='commission']").val();
if (commission == "") {
    $.alert("Please Select Commission");
    $("select[name='commission']").focus();
    return false;
}

natureOrder = $("select[name='nature_of_order']").val();
if (natureOrder == "") {
    $.alert("Please Select Case Type");
    $("select[name='case_type']").focus();
    return false;
}

caseNo = $("input[name='case_no']").val();
if (caseNo == "" || !/^[0-9]+$/.test(caseNo)) {
    $.alert("Case nuber must be numeric only.");
    $("input[name='case_no']").val("");
    return false;
}

caseYear = $("select[name='case_year']").val();
if (caseYear == "") {
    $.alert("Please Enter Case Year");
    $("select[name='case_year']").focus();
    return false;
}

ddate = $("input[name='decision_date']").val();
if (ddate == "") {
    $.alert("Please Enter Decision Date (YYYY-MM-DD)!");
    $("input[name='decision_date']").focus();
    return false;
}
var data = {};
data['commission'] = commission;
data['case_type'] = natureOrder;
data['case_no'] = caseNo;
data['case_year'] = caseYear;
data['decision_date'] = ddate;
data['filing_no'] = salt;
$.ajax({
    type: "post",
    url: "add_more_commision",
    data: data,
    dataType: "html",
    beforeSend: function(){
    	$('#add_btn_commission').prop('disabled',true);
    },
    success: function (data) {
    	if(data=='success') {
    		$.alert("Successfully Updated Your Record");
	        $("select[name='commission']").val('');
	        $("select[name='nature_of_order']").val('');
	        $("input[name='case_no']").val('');
	        $("select[name='case_year']").val('');
	        $("input[name='decision_date']").val('');
	        $("#new_commission_added").removeClass('d-none').find('p').html("Successfully Updated Your Record"),
	        $("tbody#add_comm").append('<tr><td>'+commission_text+'</td><td>'+order_type_text+'</td><td>'+caseNo+'</td><td>'+caseYear+'</td><td>'+ddate+'</td></tr>');
	    }else {
	    	$.alert(data);
	    	$("#new_commission_added").removeClass('d-none').find("p").html(data);
	    }
    },
    error: function (request, error) {
        $.alert('Something Wrong!');
    },
    complete: function(){
    	$('#add_btn_commission').prop('disabled',false);
    }
});
}

function view_caveat_recipt(filing_no,activeA) {
event.preventDefault();
$(activeA).removeClass('text-danger').addClass('text-success');
$('#caveat_filing_table').find('button.fa-print').attr('disabled',true);
$.ajax({
	type: 'post',
	url: base_url+'caveat_recipt',
	data: {'filing_no':filing_no},
	dataType: 'html',
	cache: false,
	success: function(retrn){
		$('#caveatRecipt').modal();
		$('#caveatRecipt').find('.container_inner').html(retrn);
	},
	error: function(){
		$.alert('Server busy, try later');
	},
	complete: function(){
		$('#caveat_filing_table').find('button.fa-print').removeAttr('disabled');
	}
});
}

function printRecipt(){
var divtoPrint=document.getElementById("printable-div");
newWin=window.open("");
newWin.document.write(divtoPrint.outerHTML);
newWin.print();
newWin.close();
}

function get_district(id) {
	event.preventDefault();
	var state_id=$.trim($(id).val());
	if(state_id !='') {
		$.ajax({
			url: base_url+'getDistrict',
			type: 'post',
			data: {"state_code":state_id},
			dataType: 'json',
			beforeSend: function(){
				$('#district').find(":selected").text("Feaching Districts.....");
			},
			success: function(resp){
				if(resp.error=='0'){
					$('#district').removeAttr('disabled').empty();
					$('#district').html('<option value="">-----Select District Name-----</option>');
					$.each(resp.data, function(index, itemData) {
						var option='<option value="'+itemData.district_code+'">'+itemData.district_name+'</option>';
						$('#district').append(option);
				});
				}
				else {
					$('#district').find(":selected").text("-----Select District Name-----");
					alert(resp.error);
				}
			},
			error: function(){
				alert('Server error, try later.');
			}

		});
	}
	else {
		alert("Please select valid state name.");
		$('#district').attr('disabled',true).empty();
		$('#district').html('<option value="">-----Select District Name-----</option>');
		return false;
	}
}

function addCaveatDetails(){
event.preventDefault();

$.ajax({
		url: base_url+'addNewCaveat',
			type: 'post',
			data: $("#addCaveat").serialize(),
			dataType: 'json',
		beforeSend: function(){
			/*$('#loading_modal').modal('show'), */$('#caveat_submit_btn').val(' Under proccess.....').attr('disabled',true);
		},
		success: function(retrn){

			if(retrn.data=='success') {
				if(retrn.ia_number !='0') {
					$.confirm({
				        animationSpeed: 2000,
				        title: '<i class="fa fa-check-circle text-success"></i>&nbsp;</b>Congrates</b>',
				        content: '<p class="text-success">Caveat successfully inserted for forther action,<br>Kindly notedown Caveat Diary No. <u class="text-danger">'+retrn.ia_number+'</u></p>',
					});
					$("#addCaveat").trigger("reset");
					$('#caveat').trigger("click");

				} else {
					$.alert({
						title: '<i class="fa fa-check-circle text-success"></i>&nbsp;</b>Congrates</b>',
						content: '<p class="text-success">Caveat successfully saved.</p>',
						animationSpeed: 2000
					});
					$("#addCaveat").find('input,select,textarea').attr('disabled',true);
					$('#fee_div').removeClass('d-none').find('input').removeAttr('disabled').attr('required',true);
					$('#caveatee').removeClass('d-none').find('input').removeAttr('disabled').attr('required',true);
				}
			}else {
				$.alert({
					title: '<i class="fa fa-exclamation text-danger"></i>&nbsp;</b>Error</b>',
					content: '<p class="text-danger">'+retrn.error+'</p>',
					animationSpeed: 200

				});
			}
		},
		error: function(){
			$.alert('Server busy,try later');
		},
		complete: function(){
			/*$('#loading_modal, .modal-backdrop').modal('hide').addClass('d-none'), */$('#caveat_submit_btn').val(' Save').removeAttr('disabled');
		}
});
}

function get_org_data(id) {
event.preventDefault();
var org_id='', org_text='';
org_id=$(id).val(), org_text=$('select[name=organization] option:selected').text();
$('input[name=caveat_name]').val(org_text);
$.ajax({
	type: 'post',
	url: base_url+'getOrgfurther',
	data: {'org_id': org_id},
	dataType: 'json',
	cache: false,
	success: function(retrn){
		$('textarea[name=caveat_address]').val(retrn[0].org_address);
		$('select[name=caveat_state] > option').each(function(){
			if($(this).val()==$.trim(retrn[0].state)) {
					$(this).attr('selected',true);
			}
			else 	$(this).attr('selected',false);
		});
		$('select[name=caveat_district]').empty().removeAttr('disabled',false).append('<option value="'+retrn[0].district+'">'+retrn[0].district_name);				
		$('input[name=caveat_pin]').val(retrn[0].pin);
		$('input[name=caveat_email]').val(retrn[0].email);
		$('input[name=caveat_mobile]').val(retrn[0].mobile_no);
	},
	error: function(){
		$.alert('Server busy, try later');
	}
});
}

function get_adv_data(id) {
	event.preventDefault();
	var adv_code='';
	adv_code=$(id).val();
	$.ajax({
		type: 'post',
		url: base_url+'list_data',
		data: {'adv_code': adv_code,'table_name':'master_advocate'},
		dataType: 'json',
		cache: false,
		success: function(data2){
			  showCity(data2[0].state_code, data2[0].district_code);
              $("#counselAdd").val(data2[0].address);
              $("#counselMobile").val(data2[0].adv_mobile);
              $("#counselEmail").val(data2[0].email);
              $("#counselPhone").val(data2[0].adv_phone);
              $("#counselPin").val(data2[0].adv_pin);
              $("#counselFax").val(data2[0].adv_fax);
              $("#dstate").val(data2[0].state_code);
              $("#dstatename").val(data2[0].state_name);
              $("#ddistrict").val(data2[0].district_code);
              $("#ddistrictname").val(data2[0].district_name);
		},
		error: function(){
			$.alert('Server busy, try later');
		}
	});
}

function trans_date(id){
$.alert('Choose date.');
$(id).datepicker({
	autoclose: true,
	immediateUpdates: true,
	format: 'yyyy-mm-dd',
	startView: 0,
	todayHighlight: true,
	startDate: '-1W',
	endDate: 'today'
});

}

function active_payment_div(divName,id){
$('#bdd, #po, #olp').removeAttr('checked');
$('#online_payment_div, #postal_order_div, #bankdd_div').addClass('d-none').find('input').attr('disabled',true);

$(id).attr('checked', true);
$('#'+divName).removeClass('d-none').find('input').removeAttr('disabled');
}

function show_hide(id) {
var id_text=$.trim($(id).text());
if(id_text=='Add Caveat'){
	$('#caveat-form-div').removeClass('d-none');
	$(id).html('&nbsp;&nbsp;Hide Caveat Form').removeClass('fa-eye').addClass('fa-eye-slash');
}
else {
	$('#caveat-form-div').addClass('d-none');
	$(id).html('&nbsp;&nbsp;Add Caveat').removeClass('fa-eye-slash').addClass('fa-eye');
}
}

function search_ia(id) {
event.preventDefault();
var selected_year=$('select[name=year]').val(), page_url='';
    page_url=base_url+'loadpage/ia_list';

$.ajax({
		type: 'post',
		url: page_url,
		dataType: 'html',
		data: {'year': selected_year},
		cache: false,
		beforeSend: function(){				
			$(id).attr('disabled',true);
		},
		success: function(ia_data){
			$('#rightbar').empty().html(ia_data);
			$('#ia_list_table').DataTable();
		},
		error: function(){
			$.alert('Server busy,try later.');
		},
		complete: function(){				
			$(id).removeAttr('disabled');
		}
});
}



function paper_updoad(mp_value){
	if(mp_value!=''){
		$('.file-upload').removeClass('disabled');
		$('input[name=upd_paper]').attr('disabled',false).click();
	}
	else {
		$.alert('Select valid paper master');
		return false;
	}
}

function choose_mpfile(){
	event.preventDefault();
	var requ_doc_type=$.trim($('select[name=paper_master]').val()), rdt_text=$('select[name=paper_master]').find("option:selected").text(), 
    	token=Math.random().toString(36).slice(2), token_hash=HASH(token+'upddoc');

	var salt = document.getElementById("saltNo").value;
	if(salt==''){
        var salt = Math.ceil(Math.random() * 100000);
        document.getElementById("saltNo").value = salt;
    }
    if(requ_doc_type != '' && requ_doc_type != 'undefined') {
        formdata = new FormData();
        if($('input[name=upd_paper]').prop('files').length > 0) {
            file =$('input[name=upd_paper]').prop('files')[0], name = $.trim(file.name), name=name.toLowerCase(), size = file.size, type = $.trim(file.type), type=type.toLowerCase();
            var dots = name.match(/\./g).length, extarray=name.split('.'), ext=extarray[1].toLowerCase();            
            if(file != undefined && name.match(/\.(pdf)$/) && type == "application/pdf") {        
                if(dots > 1){  
                    $.alert('More than one dot (.) not allowed in uploding file!');
                    $('#req_doc').val(''); return false;
                }
                else if (size > 1999990) {  
                    $.alert('Please select file size less than 2000 KB.');
                    $('#req_doc').val(''); return false;
                }
                else {
                	$('input[name=upd_paper]').attr('disabled',true), $('.file_name_span').removeClass('hide').html(file.name); 
                    formdata.append("choose_file", file), 
                    formdata.append("reqdoctype", requ_doc_type),
                    formdata.append("token", token),
                    formdata.append("filing_no", $('input[name=filing_no]').val()),
                    formdata.append("filing_year", $('select[name=filing_year]').val()),
                    formdata.append("user_type",$('input[name=userType]:checked').val());
                    formdata.append("salt",salt);
                    $.ajax({
                        type:'post',
                        url: base_url+'docupd_paper/'+token_hash,
                        data: formdata,
                        processData: false,
                        contentType: false,
                        dataType: 'JSON',
                        beforeSend: function(){
                        	$('#loading_modal').modal(), $('.ia_fee_div, .pmode, .ia_nature_div, #vakalatnama_fieldset').addClass('d-none'),
                        	$('select[name=ia_nature_name], select[name=council_name]').attr('disabled',true), 
                        	$('.tannexure').removeClass('d-none').find('input[name=annexure]').prop('disabled',false);
                        },
                        success: function(response){
                            if(response.data=='success') {
                            	var first_two_char=requ_doc_type.substring(0,2).toLowerCase();
                                $.alert({
									title: '<i class="fa fa-check-circle text-success"></i>&nbsp;</b>Congrates</b>',
									content: '<p class="text-success">ID Proof document uploaded successfully.</p>',
									animationSpeed: 2000
								});
                                $('select[name=paper_master] option:selected').prop('disabled',true).addClass('text-success');
                                $('.file-upload').addClass('disabled');
                                $('input[name=upd_paper]').prop('disabled',true);
                                $('.pmode').removeClass('d-none');
                                $('input[name=payment_mode], .upd-save').prop('disabled',false);

                                $('select[name=paper_master] > option').each(function(){
									if($(this).val().substring(0,2).toLowerCase()!=first_two_char)
										$(this).prop('disabled',true).addClass('text-danger');
								});

                                if(first_two_char=='ia') {
                                	$('.ia_fee_div, .ia_nature_div').removeClass('d-none'),
                                	$('select[name=ia_nature_name]').removeAttr('disabled',false);
                                }
                                else if(first_two_char=='va') {
                                	$('#vakalatnama_fieldset').removeClass('d-none'),
                                	$('select[name=council_name]').prop('disabled',false),
                                	$('.tannexure').addClass('d-none').find('input[name=annexure]').prop('disabled',true);
                                }

                            }else if(response.error !='0') {
                                $.alert(response.error);
                            }
                        },
                        error: function(xhr,status){
                            $.alert('Server busy, try later');
                        },
                        complete: function(){	                        	
							$('#loading_modal, .modal-backdrop').removeClass('show').addClass('d-none');
							$('body').removeAttr('style class');
                        }
            
                    });
                }

            }else {
                $.alert("Please Choose Valid Document");
                $('input[name=upd_paper]').prop('disabled',true);
                $('.file-upload').addClass('disabled');
                $('select[name=paper_master]').val(''); return false;
            }
        }
    }else {
        $.alert("Please select document type for upload!");
        $('#idproof').val(''); return false;
    }
}


function upd_master_paper(){
	event.preventDefault();
	if($('input[name=payment_mode]:checked').length == 0) {
		$.alert('Kindly choose payment option first.');
		return false;
	}
	else {
		var url=base_url+'upd_master_paper';
		$.ajax({
			 	type: 'post',
			 	url: url,
			 	data: $('#addMasterPaper').serialize(),
			 	dataType: 'json',
			 	beforeSend: function(){
			 		$('#loading_modal').modal('show'), $('.upd-save').val(' Under proccess.....').attr('disabled',true);
			 	},
			 	success: function(retrn){
			 		if(retrn.data=='success'){
			 			$.alert({
							title: '<i class="fa fa-check-circle text-success"></i>&nbsp;</b>Congrates</b>',
							content: '<p class="text-success">Document with details successfully saved.</p>',
							animationSpeed: 2000
						});
			 			$('#addMasterPaper').trigger('reset');
			 			$('#document_filing').trigger("click");
			 		}
			 		else {
			 			$.alert({
							title: '<i class="fa fa-exclamation-triangle text-danger"></i>&nbsp;</b>Warning</b>',
							content: '<p class="text-danger">'+retrn.error+'</p>',
							animationSpeed: 2000
						});
			 		}
			 	},
				error: function(){
					$.alert('Server busy, try later'), $('#loading_modal .modal-backdrop').removeClass('show').addClass('d-none');
					$('body').removeAttr('style class');
					$('.upd-save').val(' Save').removeAttr('disabled');
				},
				complete: function() {
					$('#loading_modal, .modal-backdrop').removeClass('show').addClass('d-none');
					$('body').removeAttr('style class');
					$('.upd-save').val(' Save').removeAttr('disabled');
				}
		})
	}
}

