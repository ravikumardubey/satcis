$(function() {
	/*$.confirm({
        animationSpeed: 2000,
        title:'I am sachin teotia',
        content: '' +
			    '<form action="" class="formName">' +
			    '<div class="form-group">' +
			    '<label>Enter something here</label>' +
			    '<select class="userType form-control" required="true">'+
			    '<option value="">-----Select User Type-----</option><option value="advocate">Advocate</option>'+
			    '</select>'+
			    '</div>' +
			    '</form>',
			    buttons: {
			        formSubmit: {
			            text: 'Submit',
			            btnClass: 'btn-blue',
			            action: function () {
			                var name = this.$content.find('.userType').val();
			                if(!name){
			                    $.alert('provide a valid name');
			                    return false;
			                }
			                $.alert('Your name is ' + name);
			            }
			        },
			        cancel: function () {
			            //close
			        },
			    },
			    onContentReady: function () {
			        // bind to events
			        var jc = this;
			        this.$content.find('form').on('submit', function (e) {
			            // if the user submits the form by pressing enter in the field.
			            e.preventDefault();
			            jc.$$formSubmit.trigger('click'); // reference the button and click it
			        });
			    }
    });*/

	var nav = $('#main-menu-container');
    $(window).scroll(function () {
        if ($(this).scrollTop() > 90) {
            nav.addClass("f-nav");
        } else {
            nav.removeClass("f-nav");
        }
    });

	$('#refresh').click(function(e){
	  	e.preventDefault();
	  	var url='';
	  	url=window.location.href;

	  	$.ajax({
	  		type: 'post',
	  		url: url+'crefresh_val',
	  		type: 'html',
	  		success: function(respons){
	  			
	  			$('#captcha_data').html(respons);
	  		},
	  		error: function(){
	  			$.alert('Server error, try later!');
	  		}
	  	});
	});
	
	
	
	$('#refreshcaptacha').click(function(e){
	  	e.preventDefault();
	  	var url='';
	  	url=window.location.href;

	  	$.ajax({
	  		type: 'post',
	  		url: url+'crefresh_val',
	  		type: 'html',
	  		success: function(respons){

	  			$('#captcha_data_pass').html(respons);
	  		},
	  		error: function(){
	  			$.alert('Server error, try later!');
	  		}
	  	});
	});
	

       $('#refreshcaptachanew').click(function(e){
	  	e.preventDefault();
	  	var url='';
	  	url=window.location.href;
	  	$.ajax({
	  		type: 'post',
	  		url: url+'crefresh_val',
	  		type: 'html',
	  		success: function(respons){
	  			$('#captcha_data_pass_new').html(respons);
	  		},
	  		error: function(){
	  			$.alert('Server error, try later!');
	  		}
	  	});
	});

	
	

	$('#e_filing').click(function(e){
		e.preventDefault();
		$('#loginModal').modal();
	});

	$('#userLogin').submit(function(e){
		e.preventDefault();
		
		var loginid=$('#loginId').val(), pass=$('#pwd').val(), captcha=$('#skey').val();

		if(loginid != '' && pass != '' && captcha != ''){
			var pass=HASH(pass), pass=HASH(pass+captcha);
			$.ajax({
					type: 'POST',
					url: base_url+'mystart/confirm_login',
					data: {eid: loginid, pass: pass, skey: captcha},
					beforeSend: function(){		
						$('#pwd').val(pass);
						$('#login-btn').val('Please wait....').attr('disabled', true);
						$('.responseMsg').addClass('hide'), $('#loading_modal').modal();
					},
					success: function(respons){
						if(respons.login_type=='success') {						
							$('#loginId').val(''), $('#pwd').val(''), $('#skey').val('');
							$('#loginModal').addClass('hide');
							$('#disclaimer').modal('show');
							$('#lurole').val(respons.role);
							//window.location.href=base_url+'loginSuccess';
						}else if(respons.login_type=='woring skey') {
							$('#captcha_data').html(respons.captcha);
							$('.responseMsg').removeClass('hide');
							$('#responseMsg').text('Enter valid captcha!');
							$('#skey').val(''), $('#pwd').val('');
							return false;
						}else if(respons.login_type=='failed') {
							$('#captcha_data').html(respons.captcha);
							$('.responseMsg').removeClass('hide');
							$('#responseMsg').text('Username and password not correct!');
							$.alert('Username and password not correct!');
							$('#loginId').val(''), $('#pwd').val(''), $('#skey').val('');
							return false;
						}else if(respons.login_type=='woring attempt') {
							$('#captcha_data').html(respons.captcha);
							$('.responseMsg').removeClass('hide');
							$('#responseMsg').text('User account is locked! Please contact system administrator!');
							$.alert('User account is locked! Please contact system administrator!');
							$('#loginId').val(''), $('#pwd').val(''), $('#skey').val('');
							return false;
						}
					},
					error: function(){
						//$.alert('Server busy, try later!');
						$.alert('\n<strong>Entered login details under verification, Please try later!</strong>');
						return false;
					},
					complete: function() {
						$('#loading_modal').modal('hide');
						$('#login-btn').val('Login').attr('disabled', false);
					},
					dataType: 'json'
			});
		}else {
			$.alert('Enter valid credentials!');
			return false;
		}
	});
	
	
	$('#agree').click(function(e){
		e.preventDefault();
		if(this.checked){
			var login_urole=$.trim($('#lurole').val()), nextUrl=base_url+'loginSuccess';
			$(this).attr('disabled',true);

			if(login_urole=='1')
				nextUrl=base_url+'loginSuccess/admin';
			window.location.href=nextUrl;
		}
	  });

	$('.notagree').click(function(){
		  window.location.href=base_url+'close';
	});

	$('#state').change(function(e){
	  	e.preventDefault();
	  	var state_id=$.trim($(this).val());
	  	if(state_id !='') {
	  		$.ajax({
	  			url: base_url+'getDistrict',
	  			type: 'post',
	  			data: {"state_code":state_id},
	  			dataType: 'json',
	  			beforeSend: function(){
	  				$('#district').find(":selected").text("Feaching Districts.....");
	  				$('#loading_modal').modal();
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
	  					$.alert(resp.error);
	  				}
	  			},
	  			error: function(){
	  				$.alert('Server error, try later.');
	  			},
	  			complete: function(){
	  				$('#loading_modal').modal('hide');
	  			}

	  		});
	  	}
	  	else {
	  		$.alert("Please select valid state name.");
	  		$('#district').attr('disabled',true).empty();
	  		$('#district').html('<option value="">-----Select District Name-----</option>');
	  		return false;
	  	}
	});


  	/********** Verify Loginid/mobile/email ************/
	$('#loginid, #mobilenumber, #email').change(function(e) {
	  	var clickId='', clickId_value='', data={};
	  	 	//$('.motp_msg').addClass('hide');
	  	    clickId=$(this).attr('id'), clickId_value=$.trim($(this).val());
	  	    data[clickId]=clickId_value;

	  	    if(!/^[a-z0-9\\_-]+$/i.test(clickId_value) && clickId=='loginid') {
	  	    	$.alert('Login ID must contain only letters, numbers, dashes or underscores.');
	  	    	$(this).val("").focus();
	  	    	return false;
	  	    }

	  	    if(!(/[0-9 -()+]+$/.test(clickId_value) || clickId_value.length ==10) && clickId=='mobilenumber' ) {
	  	    	$.alert('Mobile Number must contain only numbers & 10 digits.');
	  	    	$(this).val("").focus();
	  	    	return false;
	  	    }

	  	    if(!/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(clickId_value) && clickId=='email') {
	  	    	$.alert('Enter valid email id');
	  	    	$(this).val("").focus();
	  	    	return false;
	  	    }

		  	$.ajax({
		  		url: base_url+'verifyField',
		  		type: 'post',
		  		data: data,
		  		dataType: 'json',
		  		beforeSend: function() {
					if(clickId != 'loginid') {
		  				$('#'+clickId).prop('readonly',true);
					}
		  			$('.lallowNot, .mallowNot, .eallowNot').addClass('hide');
		  		},
		  		success: function(resp) {

		  			if(resp.data == 'found') {
		  				if(clickId == 'loginid')
		  					$('.lallowNot').removeClass('hide');
		  				if(clickId == 'mobilenumber')
		  					$('.mallowNot').removeClass('hide');
		  				if(clickId == 'email')
		  					$('.eallowNot').removeClass('hide');

		  				$('#'+clickId).prop('readonly',false).val("");
		  			}
		  			else if(resp.data=='not found') {
		  				if(clickId == 'mobilenumber')
		  					$('.motp').removeClass('hide');	 
		  				if(clickId == 'email')
		  					$('.eotp').removeClass('hide');
		  			}
		  			else {
		  				$.alert(resp.error);
		  				$('#'+clickId).prop('readonly',false).val("");
		  			}
		  		},
		  		error: function() {
		  			$.alert('Server error, try later');
		  		}
		  	});
	});

  	//************* Send OTP *****************/
	$('.motp, .eotp').click(function(e){
		  	e.preventDefault();
		  	var sendType='', getVal='', otp_type, msg='', dataArray={}, classname='';
		  	sendType=$(this).attr("class"); //$('.updFileDiv').addClass('hide');


		  	if(sendType.search("motp") > 0) {
		  		classname='mtop';
		  		otp_type="mobilenumber";
		  		getVal=$("#mobilenumber").val();
		  		validationReg = /[0-9 -()+]+$/;
		  		msg="Enter valid mobile number.";

		  	}
		  	else if(sendType.search("eotp") > 0)  {
		  		classname='etop';
		  		otp_type="email";
		  		getVal=$("#email").val();
		  		validationReg = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		  		msg="Enter valid email id.";
		  	}

			if(!validationReg.test(getVal) || getVal == ''){
			     $.alert(msg);
			     $('#'+otp_type).val("").prop("readonly", false);
			     return false;
			}
			else {
				$(this).addClass('hide');
				dataArray[otp_type]=getVal;
			  	$.ajax({
			  		url: base_url+'sendVcode',
			  		type: 'post',
			  		data: dataArray,
			  		dataType: 'json',
			  		beforeSend: function() {
			  			if(otp_type== 'mobilenumber')
			  				$('.motp_msg').removeClass('hide').html('OTP is sending .....');
			  			else
			  				$('.eotp_msg').removeClass('hide').html('OTP is sending .....');
			  		},
			  		success: function(retr) {
			  			if(otp_type== 'mobilenumber' && retr.data=='success') { 
			  				$('.motp_msg').html('<i class="fa fa-check-circle"></i>&nbsp;OTP has been send.<input type="number" placeholder="Enter OTP" value="" id="mobileOtp" onchange="verify_otp(\'mobileOtp\');" class="form-control">');
			  				$.alert("OTP has been send on your enterd mobile number.");
			  				$('#mobileOtp').focus();
			  			}
			  			else if(otp_type == 'email' && retr.data=='success') {
			  				$('.eotp_msg').html('<i class="fa fa-check-circle"></i>&nbsp;OTP has been send.<input type="number" placeholder="Enter OTP" value="" id="emailOtp" onchange="verify_otp(\'emailOtp\');" class="form-control">');
			  				$.alert("OTP has been send on your enterd email id.");		  					  					
		  					$('#idptype').attr('disabled',false), $('#emailOtp').focus();
			  			}
			  			else if(retr.error !='0') {
			  				$.alert(retr.error);
			  			}
			  		},
			  		error: function(){
			  			$.alert("Server busy, try later");
			  		},
			  		complete: function() {
			  			$.alert("Resend OTP button will be show after 2 Minute.")
			  			setTimeout(function(){ 
					  		if(otp_type=='mobilenumber') {
					  			$('.motp').removeClass('hide').text('Resend OTP'); 
					  			//$.alert("If not received OTP still, then click on resend button");
					  			//$('.motp_msg').addClass('hide');				  			
					  		}

					  		if(otp_type=='email') {
					  			$('.eotp').removeClass('hide').text('Resend OTP'); 
					  			//$.alert("If not received OTP still, then click on resend button");
					  			//$('.motp_msg').addClass('hide');				  			
					  		}
					  	}, 120000);
			  		}
			  	});
			}
	}); 

	$('#idptype').change(function(e){
	  	var emailVerify='', mobileVerify='';
	  	emailVerify=$.trim($('.eotp_msg').text()), mobileVerify=$.trim($('.motp_msg').text());

	    if(mobileVerify !='OTP Verified.' || emailVerify != 'OTP Verified.') {
	    	$.alert('Kindly verify both OTP(s) first.');
	    	$('#mobilenumber').focus(), $('#idptype').val("");
	    	return false;
	    }	    

	  	else if($(this).val() != "") {
	  		$('#idproof').attr('disabled',false).click();
	  	}
	  	else $.alert("select valid ID Proof Type");
	});

	/****** Upload Required Documents ****** */
	$('#idproof').change(function(e){
	    e.preventDefault();
	    var requ_doc_type=$('#idptype').val(), rdt_text=$('#idptype').find("option:selected").text(), 
	    	token=Math.random().toString(36).slice(2), token_hash=HASH(token+'upddoc'), umobile=$('#mobilenumber').val();

	    if(requ_doc_type != '' && requ_doc_type != 'undefined') {
	        formdata = new FormData();
	        if($(this).prop('files').length > 0) {

	            file =$(this).prop('files')[0], name = $.trim(file.name), name=name.toLowerCase(), size = file.size, type = $.trim(file.type), type=type.toLowerCase();
	            var dots = name.match(/\./g).length, extarray=name.split('.'), ext=extarray[1].toLowerCase(), validImageTypes = ["image/gif","image/jpeg","image/png"]; 
	            
	            if(file != undefined && name.match(/\.(jpg|jpeg|png|gif|pdf)$/) && ($.inArray(type, validImageTypes) > 0 || type == "application/pdf")) { 
	                
	                if(dots > 1){  
	                    $.alert('More than one dot (.) not allowed in uploding file!');
	                    $('#req_doc').val(''); return false;
	                }
	                else if (size > 1999990) {  
	                    $.alert('Please select file size less than 2000 KB.');
	                    $('#req_doc').val(''); return false;
	                }
	                else {
	                	$(this).attr('disabled',true), $('.file_name_span').removeClass('hide').html(file.name); 
	                    formdata.append("userfile", file), 
	                    formdata.append("reqdoctype", requ_doc_type),
	                    formdata.append("token", token);
	                    formdata.append("userMobile",umobile);
	                    $.ajax({
	                        type:'post',
	                        url: base_url+'idproofUpd/'+token_hash,
	                        data: formdata,
	                        processData: false,
	                        contentType: false,
	                        dataType: 'JSON',
	                        success: function(response){
	                            if(response.data=='success') {
									var flName='';
									flName=base_url+'asset/users_idproof/'+response.file_name;
	                                $.alert({
										title: '<i class="fa fa-check-circle text-success"></i>&nbsp;</b>Congrates</b>',
										content: '<p class="text-success">ID Proof document uploaded successfully.</p>',
										animationSpeed: 2000
									});

	                                $('#idptype').prop('disabled',true), $('#id_number').prop('disabled',false).focus();
	                                $('#regButtonfinal').prop('disabled',false);

									$('#iframDisplay').attr("src", flName );
									$('#idDisplay').show();
	                                
	                            }else if(response.error !='0') {
	                                $.alert(response.error);
	                            }
	                        },
	                        error: function(xhr,status){
	                            $.alert('Server busy, try later');
	                        },
	                     
	            
	                    });
	                }

	            }else {
	                $.alert("Please Choose Valid Document");
	                $('#idproof').val(''); return false;
	            }
	        }
	    }else {
	        $.alert("Please select document type for upload!");
	        $('#idproof').val(''); return false;
	    }
	});

	//*********** Submit function for registration *****************//
	$('#regForm').submit(function(e){
		e.preventDefault();
		var regForm=new FormData(this);
		    //regForm.append('chooseUType', $.trim($('#selectedOption').text())),
		    regForm.append('idoctype', $.trim($('#idptype').val()));
		$.ajax({
				type: 'post',
				url:  base_url+'regUser',
				data: regForm,
				dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
				beforeSend: function(){
					$('#regButton').prop('disabled',true).val("Under proccess....");
				},
				success: function(resp){
					if(resp.data=='success') {
						$('#stepone_user').hide();
						$('#document').show();
						$('#id_reff').val(resp.ins);
						/*$.alert({
							title: '<i class="fa fa-check-circle"></i>&nbsp;</b>Congrates</b>',
							content: '<p class="text-success">Dear user you are registered successfully.'+"\r\nYour login credentials send to registred mobile number.</p>",
							animationSpeed: 2000
						});
						$(this).trigger("reset");
						setTimeout(function(){ 
							window.location.reload(true);
					  	}, 9000);*/
						
					}
					else if(resp.error != '0') {
						$.alert(resp.error);
					}
				},
				error: function(){
					$.alert("Surver busy,try later.");
				},
				complete: function(){
					$('#regButton').prop('disabled',false).val("Submit");
				}
		});
	});
	
	
	
	
	//*********** Submit function for registration *****************//
	$('#regFormFinal').submit(function(e){
		e.preventDefault();
		   var token=Math.random().toString(36).slice(2);  
		   var token_hash=HASH(token+'upddoc'); 	   
		   var idptype= $('#idptype').val();
		   var id_reff= $('#id_reff').val();
		   var id_number= $('#id_number').val();
		   var data={};
		   data['idoctype']= idptype;
		   data['id_number']=window.btoa(id_number)
		   data['id_reff']=id_reff;
		   data['token']=token;
		   data['token_hash']=token_hash;
		    $.ajax({
				type: 'post',
				url:  base_url+'regFormFinal',
				data: data,
				dataType: 'json',
				beforeSend: function(){
					$('#regButton').prop('disabled',true).val("Under proccess....");
				},
				success: function(resp){
					if(resp.data=='success') {
						$.alert({
							title: '<i class="fa fa-check-circle"></i>&nbsp;</b>Congrates</b>',
							content: '<p class="text-success">Dear user you are registered successfully.'+"\r\nYour login credentials send to registred mobile number.</p>",
							animationSpeed: 2000
						});
						$(this).trigger("reset");
						setTimeout(function(){ 
							window.location.reload(true);
					  	}, 9000);
						
					}
					else if(resp.error != '0') {
						$.alert(resp.error);
					}
				},
				error: function(){
					$.alert("Surver busy,try later.");
				},
				complete: function(){
					$('#regButton').prop('disabled',false).val("Submit");
				}
		});
	});
	
	

	$('#id_number').keyup(function(){
		var idnumber=$(this).val();
		if( idnumber != '') {
			if(idnumber.length > 4)
				$('#regButton').prop('disabled',false);
		}
		else {
			$.alert('Please enter valid document number.');
			$(this).focus(), $('#regButton').prop('disabled',true);;
			return false;
		}
	});

	//********* Recover Password ***************//
	$('#forgetForm').submit(function(e){
		e.preventDefault();
		var fdata=new FormData(this);
		$.ajax({
				type: 'post',
				url:  base_url+'recoverPwd',
				data: fdata,
				dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function(){
                	$('#recover_pass').attr('disabled',true).val('Password recovring.....');
                },
                success: function(retn){
                	if(retn.data=='success'){
                		$('#forgetForm').trigger("reset");
                		$.alert({
							title: '<i class="fa fa-check-circle"></i>&nbsp;</b>Congrates</b>',
							content: "<p class='text-success'>Your Password is : "+ retn.password +"\r\nYour password has been send on registred mobile number.\r\nKindly check in mobile message box.</p>",
							animationSpeed: 2000
						});
						$(this).trigger("reset");
						setTimeout(function(){ 
							window.location.reload(true);
					  	}, 12000);
                	}
                	else if(retn.error !='0') {
                		$.alert(retn.error);
                		$('#forgetForm').trigger("reset");
                		return false;
                	}
                },
                error: function(){
                	$.alert('Server busy, try later.');
                },
                complete: function(){
                	$('#recover_pass').attr('disabled',false).val('Recover Password?');
                }

		});
	});	

	/*************** For Advocate ***************/
	$('#enrolment_number').change(function(e){
		e.preventDefault();
		var adv_barid=$.trim($(this).val()), adv_code={};
		/*if($.isNumeric(adv_barid)==true) adv_code['adv_code']=adv_barid;
		else*/ 
			adv_code['enrolment_number']=adv_barid;

		if(adv_barid !='') {
			if(adv_barid.length > 4) {
				$.ajax({
						type: 'post',
						url: base_url+'getAdvDetails',
						data: adv_code,
						dataType: 'json',
						cache: false,
						beforeSend: function(){
							$('#loading_modal').modal(), $('.motp, .eotp, .motp_msg, .eotp_msg').addClass('hide');
						},
						success: function(retn){

							if(retn.error =='0') {
								if($.trim(retn[0].adv_sex).toLowerCase()=='m')
									gender='male';

								$('textarea[name="address"]').val(retn[0].address).attr('readonly',true);
								$('#gender').val(gender).attr('selected',true).attr('readonly',true);						
								$('#state > option').each(function(){
									if($(this).val()==$.trim(retn[0].state_code)) {
											$(this).attr('selected',true);
											$('#state').attr('readonly',true);
									}
									else 	$(this).attr('selected',false);
								});
								$('#district').empty().removeAttr('disabled',false).attr('readonly',true).append('<option value="'+retn[0].adv_dist+'">'+retn[0].district_name);				

								//$('#state').trigger("change");
								$('input[name="fname"]').val(retn[0].adv_name).attr('readonly',true);
								$('input[name="pincode"]').val(retn[0].adv_pin).attr('readonly',true);
								
								var mobile=$.trim(retn[0].adv_mobile), emailId=$.trim(retn[0].email);
								/*mobile=$.trim(retn[0].adv_mobile).substring(0,6)+'XXXX',emailId=$.trim(retn[0].email),emailid_len=emailId.indexOf('@'),
								    final_email=emailId.substring(0,(emailid_len-4))+'XXXX'+emailId.substring(emailid_len);*/

								$.alert({
									title: '<div class="fa fa-exclamation text-danger"></div>&nbsp;Information',
									content: "<p class='alert alert-success'>Dear user kindly verify all details.</p>",
									animationSpeed: 2000
								});

								$('.motp, .eotp').removeClass("hide"); 
								$('input[name="mobilenumber"]').val(mobile),						
								$('input[name="email"]').val(emailId);
							}else {
								var error='';
								    error=retn.error;								    
								$('#enrolment_number').focus();
								$('#add_adv').show();
								$.alert({
									title: '<div class="fa fa-exclamation text-danger"></div>&nbsp;Error',
									content: "<p class='alert alert-danger'>"+error+"</p>",
									animationSpeed: 2000
								});
							}		
						},
						error: function(){
							$.alert('Error : server busy, try later');
						},
						complete: function(){
							$('#loading_modal').modal('hide');
						}
				});
			}
		}
		else {
			$.alert('Enter valid enrolment number.');
			$(this).val("").focus();
			return false;
		}
	});
	
	
	
	


	/*********** User Type ****************/
	$('#user_type').change(function(e){
		e.preventDefault();
		var ut=$(this).val();

		$('#regForm').find('input[type="text"],select,textarea').removeAttr('readonly',false).val(""),$('input[name="orgdisp_name"]').removeAttr('required',false),
		$('#district').empty().attr('disabled',true), $('.motp, .eotp, .motp_msg, .eotp_msg').addClass('hide'),
		$('.selfName').removeClass('hide').find('input[name="fname"],select').attr('required',true).removeAttr('disabled',false);

		$(this).val(ut);

		$('.adv_div, .company_div').addClass('hide').find('input').prop('disabled',true);
		if(ut=='advocate'){
			$('.adv_div').removeClass('hide').find('input').prop('disabled',false).focus();
		}
		if(ut=='lf'){
			$('.company_div').removeClass('hide').find('select').prop('disabled',false);
			$.ajax({
					type: 'post',
					url: base_url+'getOrgData',
					data: {'org_id':'1'},
					dataType: 'json',
					cache: false,
					beforeSend: function(){
						$('#loading_modal').modal();
					},
					success: function(retn){
						$('#org_name').empty();
						$.each(retn, function(){
							$('#org_name').append('<option value="'+this['org_id']+'">'+this['org_name']+'</option>');
						});
						$('#org_name').append('<option value="0" style="color:#a94442;font-weight:600;">Other</option>');
					},
					error: function(){
						$.alert('Error: server busy, try later');
					},
					complete: function(){
						$('#loading_modal').modal('hide');
					}
			});
		}
	});

	$('#skey').keyup(function(e){
		e.preventDefault();
		$('#login-btn').attr('disabled',false);
	});

	$('input[name="fname"]').keyup(function(e){
		e.preventDefault();
		var fname=$(this).val(), uType=$('#user_type').val();
		if(fname !='' && uType =='') {
			$(this).val(""), $('#user_type').focus();
			$.alert({
				title: '<i class="fa fa-exclamation text-danger small">&nbsp;Error :</i>',
				content: '<p class="text-success">Kindly choose user type first.</p>',
				animationSpeed: 500
			});
		}
	});

	$('#org_name').change(function(e){
		e.preventDefault();
		var orgId=$.trim($(this).val());
		if(orgId=='0') {

			$('input[name="orgdisp_name"]').val("").removeAttr('disabled',false);
			$('input[name="short_org_name"]').val("").removeAttr('disabled',false);
			$('input[name="org_desg"]').val("").removeAttr('disabled',false);
			$('input[name="org_admin"]').val("").removeAttr('disabled',false).attr('placeholder','Administrator/Organization Name');
			return false;
		}
		else {
			$.ajax({
					type: 'post',
					url: base_url+'getOrgfurther',
					data: { 'org_id': orgId},
					cache: false,
					dataType: 'json',
					beforeSend: function(){
						$('#loading_modal').modal(),
						$('.selfName').addClass('hide').find('input[type="text"],select').removeAttr('required',false).attr('disabled',true);
					},
					success: function(retn){

						$('#state > option').each(function(){
							if($(this).val()==$.trim(retn[0].state)) {
									$(this).attr('selected',true);
									$('#state').attr('readonly',true);
							}
							else 	$(this).attr('selected',false);
						});

						$('#district').empty().removeAttr('disabled',false).attr('readonly',true).append('<option value="'+retn[0].district+'">'+retn[0].district_name);

						$('textarea[name="address"]').val(retn[0].org_address).attr('readonly',true);
						$('input[name="pincode"]').val(retn[0].pin).attr('readonly',true);
						$('input[name="orgdisp_name"]').val(retn[0].orgdisp_name).removeAttr('disabled',false);
						$('input[name="short_org_name"]').val(retn[0].short_org_name).removeAttr('disabled',false);
						$('input[name="org_desg"]').val(retn[0].org_desg).removeAttr('disabled',false);
						$('input[name="org_admin"]').val(retn[0].orgdisp_name).removeAttr('disabled',false);
						
						var mobile=$.trim(retn[0].adv_mobile), emailId=$.trim(retn[0].email);

						$.alert({
							title: '<div class="fa fa-exclamation text-danger"></div>&nbsp;Information',
							content: "<p class='alert alert-success'>Dear user kindly verify all details.</p>",
							animationSpeed: 2000
						});

						$('.motp, .eotp').removeClass("hide"); 
						$('input[name="mobilenumber"]').val(mobile),						
						$('input[name="email"]').val(emailId);				

					},
					error: function() {
						$.alert({
							title: '<span class="fa fa-exclamation small text-danger">&nbsp;Error :</span>',
							content: '<p class="text-success">Server busy, try later</p>',
							animationSpeed: 800
						});
					},
					complete: function(){
						$('#loading_modal').modal('hide');
					}
			});
		}
	});

});

function corporate_firm(type) {
	var type=$.trim(type);
	if(type=='yes') {
		$('#enrolment_number').removeAttr('disabled',false).attr('required',true).focus();
	}
	if(type=='no') {
		$('#enrolment_number').val("").attr('disabled',true).removeAttr('required',false),$('input[name="fname"]').focus();
		debugger;
		$('#regForm').find('input[type="text"],select').removeAttr('readonly',false).val(""),
		$('#district').empty().attr('disabled',true);
	}
}

//********** Verify OTP ************//	
function verify_otp(click_id) {	   	   
	  	var getMotp='', intReg = /[0-9 -()+]+$/, data={}, id='';
	  	id=click_id, getMotp=$('#'+id).val();
	  	var getMotphas=HASH(getMotp+'upddoc'); 
	  	if(!intReg.test(getMotp) || getMotp.length != 4) {
	  		$.alert("Enter valid OTP");
	  		$('#'+id).val("").focus();
	  		return false;
	  	}
	  	else {
	  		data["post_otp"]=getMotp;
	  		data["otp_type"]=id;
	  		data["getMotphas"]=getMotphas;
	  		$.ajax({
		  		type: 'post',
		  		url: base_url+'verifyOtP',
		  		data: data,
		  		dataType: 'json',
		  		success: function(retrn) {
		  			if(retrn.data=="success" && id=="mobileOtp" && retrn.token==getMotphas) {
		  				$('#mobileOtp').addClass('hide'),
		  				$('.motp_msg').removeClass('hide').html('<i class="fa fa-check-circle"></i>&nbsp;OTP Verified.');
						$('#regButton').attr('disabled',false);
						$('#loginid').attr('readonly',true);
		  			}
		  			else if(retrn.data=="success" && id=="emailOtp" && retrn.token==getMotphas) {
		  				$('#emailOtp').addClass('hide'),
		  				$('.eotp_msg').removeClass('hide').html('<i class="fa fa-check-circle"></i>&nbsp;OTP Verified.');
		  			}
		  			else if(retrn.error!='0'){
		  				$.alert(retrn.error);
		  				$('#'+id).val("").focus();
		  			}
		  		},
		  		error: function(){
		  			$.alert("Server busy, try later.");
		  		}
		  	});
	  	}

}