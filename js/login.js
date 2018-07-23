(function( $ ) {

//** User Login **//
$('#frm_user_login').validate({
	errorElement: 'span',
	errorClass: 'error',
	focusInvalid: false,
	
	rules: {
		email: {
			required: true,
			email:true
		},
		password: {
			required: true,
			minlength:6
		}
	},
	messages: {
		email: {
			required: 'Email is required.',
			email:"Please enter valid email address."
		},
		password: {
			required: 'Password is required.',
			minlength: 'Password should contain minimum 6 Characters.'
		}			
	},
	submitHandler: function(form){
		$('#frm_user_login button').attr("disabled", true);
		$('form span.ajax-loader').css('visibility', 'visible');
		$('#frm_user_login span.error').remove();
		$('#frm_user_login #messages .error-msg').hide();
		$('#frm_user_login #messages .success-msg').hide();
		var email 		= $.trim($('#email').val());
		var password	= $.trim($('#password').val());
		var login_nonce = $('#login_nonce').val();
		
		$.ajax({
			url: login.ajaxurl,
			type: 'POST',
			dataType :"json",
			data: {
				action			: 'user_login',
				email 			: email,
				password 		: password,
				login_nonce 	: login_nonce,
			},
			cache: false,
			success: function(response){
				$('#frm_user_login button').attr("disabled", false);
				$('form span.ajax-loader').css('visibility', 'hidden');
				if(response.errorStatus){
					if(response.hiddenError){
						$('#frm_user_login #messages .error-msg').text(response.hiddenErrorMsg);
						$('#frm_user_login #messages').show();
						$('#frm_user_login #messages .error-msg').css('display', 'inline-block');
					}                        
					if(response.usersError){
						$('#frm_user_login #messages .error-msg').text(response.usersErrorMsg);
						$('#frm_user_login #messages').show();
						$('#frm_user_login #messages .error-msg').css('display', 'inline-block');
					}						
					if(response.errorEmail){
						$( "#email" ).after( '<span id="email-error" class="error">'+ response.errorEmailMsg +'</span>' );
					}
					if(response.errorPassword){
						$( "#password" ).after( '<span id="password-error" class="error">'+ response.errorPasswordMsg +'</span>' );
					}
				} else {
					$("#frm_user_login").trigger('reset');
					$('#frm_user_login #messages .success-msg').text(response.success);
					$('#frm_user_login #messages').show();
					$('#frm_user_login #messages .success-msg').css('display', 'inline-block');
					//location.reload(true);
					window.location = response.redirecturl;				
				}
			},
				
		});
	}
	
});

//** User Registration **//
$('#frm_user_signup').validate({
	errorElement: 'span',
	errorClass: 'error',
	focusInvalid: false,
	rules: {
		signup_firstname: {
			required: true
		},
		signup_lastname: {
			required: true
		},
		signup_email: {
			required: true,
			email:true
		},
		signup_password: {
			required: true,
			minlength: 6,
			maxlength: 18,
		},
		signup_confirmpassword: {
			required: true,
			equalTo: '#signup_password'
		},
	},
	messages: {
		signup_firstname: {
			required: 'Firstname is required.',
		},
		signup_lastname: {
			required: 'Lastname is required.',
		},
		signup_email: {
			required: 'Email is required.',
			email:'Please enter valid email address.'
		},
		signup_password: {
			required: 'Password is required.',
			minlength: 'Password should contain minimum 6 & maximum 18 Characters.',
			minlength: 'Password should contain minimum 6 & maximum 18 Characters.'
		},
		signup_confirmpassword: {
			required: 'Please confirm password.',
			equalTo: 'Password does not matched.'
		},
	},
	submitHandler: function(form){
		$('#frm_user_signup button').attr("disabled", true);
		$('form span.ajax-loader').css('visibility', 'visible');
		$('#frm_user_signup span.error').remove();
		$('#frm_user_signup #signup-messages .error-msg').hide();
		$('#frm_user_signup #signup-messages .success-msg').hide();
		
		var firstname 		= $.trim($('#signup_firstname').val());
		var lastname		= $.trim($('#signup_lastname').val());
		var email 			= $.trim($('#signup_email').val());
		var companyname		= $.trim($('#signup_companyname').val());
		var country			= $.trim($('#signup_country').val());
		var password		= $.trim($('#signup_password').val());
		var confirmpassword	= $.trim($('#signup_confirmpassword').val());
		var signup_nonce 	= $('#signup_nonce').val();
		
		$.ajax({
			url: login.ajaxurl,
			type: 'POST',
			dataType :"json",
			data: {
				action			: 'user_signup',
				firstname 		: firstname,
				lastname 		: lastname,
				email 			: email,
				companyname 	: companyname,
				country 		: country,
				password 		: password,
				confirmpassword : confirmpassword,
				signup_nonce 	: signup_nonce,
			},
			cache: false,
			success: function(response){
				$('#frm_user_signup button').attr("disabled", false);
				$('form span.ajax-loader').css('visibility', 'hidden');
				if(response.errorStatus){
					if(response.hiddenError){
						$('#frm_user_signup #signup-messages .error-msg').text(response.hiddenErrorMsg);
						$('#frm_user_signup #signup-messages').show();
						$('#frm_user_signup #signup-messages .error-msg').css('display', 'inline-block');
					}                        
					if(response.usersError){
						$('#frm_user_signup #signup-messages .error-msg').text(response.usersErrorMsg);
						$('#frm_user_signup #signup-messages').show();
						$('#frm_user_signup #signup-messages .error-msg').css('display', 'inline-block');
					}
					
					if(response.errorfirstname){
						$( "#signup_firstname" ).after( '<span id="signup_firstname-error" class="error">'+ response.errorfirstnameMsg +'</span>' );
					}
					if(response.errorlastname){
						$( "#signup_lastname" ).after( '<span id="signup_lastname-error" class="error">'+ response.errorlastnameMsg +'</span>' );
					}			
					if(response.erroremail){
						$( "#signup_email" ).after( '<span id="signup_email-error" class="error">'+ response.erroremailMsg +'</span>' );
					}
					if(response.errorpassword){
						$( "#signup_password" ).after( '<span id="signup_password-error" class="error">'+ response.errorpasswordMsg +'</span>' );
					}
					if(response.errorconfirmpassword){
						$( "#signup_confirmpassword" ).after( '<span id="signup_confirmpassword-error" class="error">'+ response.errorconfirmpasswordMsg +'</span>' );
					}
				} else {
					$("#frm_user_signup").trigger('reset');
					$('#frm_user_signup #signup-messages .success-msg').text(response.success);
					$('#frm_user_signup #signup-messages').show();
					$('#frm_user_signup #signup-messages .success-msg').css('display', 'inline-block');
					//location.reload(true);
					//window.location = response.redirecturl;				
				}
			},
				
		});
	}
	
});


//** Forgot Password **//
$('#frm_forgot_password').validate({
	errorElement: 'span',
	errorClass: 'error',
	focusInvalid: false,
	
	rules: {
		email: {
			required: true,
			email:true
		}
	},
	messages: {
		email: {
			required: 'Email is required.',
			email:"Please enter valid email address."
		}			
	},
	submitHandler: function(form){
		$('#frm_forgot_password button').attr("disabled", true);
		$('form span.ajax-loader').css('visibility', 'visible');
		$('#frm_forgot_password span.error').remove();
		$('#frm_forgot_password #messages .error-msg').hide();
		$('#frm_forgot_password #messages .success-msg').hide();
		var email 		= $.trim($('#email').val());
		var forgot_password_nonce = $('#forgot_password_nonce').val();
		
		$.ajax({
			url: login.ajaxurl,
			type: 'POST',
			dataType :"json",
			data: {
				action			: 'user_forgot_password',
				email 			: email,
				forgot_password_nonce : forgot_password_nonce,
			},
			success: function(response){
				$('#frm_forgot_password button').attr("disabled", false);
				$('form span.ajax-loader').css('visibility', 'hidden');
				if(response.errorStatus){
					if(response.hiddenError){
						$('#frm_forgot_password #messages .error-msg').text(response.hiddenErrorMsg);
						$('#frm_forgot_password #messages').show();
						$('#frm_forgot_password #messages .error-msg').css('display', 'inline-block');
					}                        
					if(response.usersError){
						$('#frm_forgot_password #messages .error-msg').text(response.usersErrorMsg);
						$('#frm_forgot_password #messages').show();
						$('#frm_forgot_password #messages .error-msg').css('display', 'inline-block');
					}						
					if(response.errorEmail){
						$( "#email" ).after( '<span id="email-error" class="error">'+ response.errorEmailMsg +'</span>' );
					}
				} else {
					$("#frm_forgot_password").trigger('reset');
					$('#frm_forgot_password #messages .success-msg').text(response.success);
					$('#frm_forgot_password #messages').show();
					$('#frm_forgot_password #messages .success-msg').css('display', 'inline-block');		
				}
			},
				
		});
	}
	
});


//** Reset Password **//
$('#frm_change_password').validate({
	errorElement: 'span',
	errorClass: 'error',
	focusInvalid: false,
	
	rules: {
		new_password: {
			required: true,
			minlength: 6,
			maxlength: 18,
		},
		renew_password: {
			required: true,
			equalTo: '#new_password'
		}
	},
	messages: {
		new_password: {
			required: 'Password is required.',
			minlength: 'Password should contain minimum 6 Characters.',
			maxlength: 'Password should contain maximum 18 Characters.',
		},
		renew_password: {
			required: 'Compare new password.',
			equalTo: 'Password does not matched.'
		}			
	},
	submitHandler: function(form){
		$('#frm_change_password button').attr("disabled", true);
		$('form span.ajax-loader').css('visibility', 'visible');
		$('#frm_change_password span.error').remove();
		$('#frm_change_password #messages .error-msg').hide();
		$('#frm_change_password #messages .success-msg').hide();
		var new_password 		= $.trim($('#new_password').val());
		var renew_password		= $.trim($('#renew_password').val());
		var user_id 			= $('#user_id').val();
        var cp_activation_token = $('#cp_activation_token').val();
		var reset_password_nonce= $('#reset_password_nonce').val();
		
		$.ajax({
			url: login.ajaxurl,
			type: 'POST',
			dataType :"json",
			data: {
				action				: 'user_reset_pasword',
				new_password 		: new_password,
				renew_password 		: renew_password,
				user_id 			: user_id,
                activation_token	: cp_activation_token,
				reset_password_nonce: reset_password_nonce,
			},
			cache: false,
			success: function(response){
				$('#frm_change_password button').attr("disabled", false);
				$('form span.ajax-loader').css('visibility', 'hidden');
				if(response.errorStatus){    
					if(response.hiddenError){
						$('#frm_change_password #messages .error-msg').text(response.hiddenErrorMsg);
						$('#frm_change_password #messages').show();
						$('#frm_change_password #messages .error-msg').css('display', 'inline-block');
					}                        
					if(response.usersError){
						$('#frm_change_password #messages .error-msg').text(response.usersErrorMsg);
						$('#frm_change_password #messages').show();
						$('#frm_change_password #messages .error-msg').css('display', 'inline-block');
					}						
					if(response.errorPassword){
						$( "#new_password" ).after( '<span id="new_password-error" class="error">'+ response.errorPasswordMsg +'</span>' );
					}
					if(response.errorRepassword){
						$( "#renew_password" ).after( '<span id="renew_password-error" class="error">'+ response.errorRepasswordMsg +'</span>' );
					}
				} else {
					$("#frm_change_password").trigger('reset');
					$('#frm_change_password #messages .success-msg').text(response.success);
					$('#frm_change_password #messages').show();
					$('#frm_change_password #messages .success-msg').css('display', 'inline-block');
					//location.reload(true);
					window.location = response.redirecturl;
				}
			},
				
		});
	}
	
});


//** Email Popup **//
$('#emailForm').validate({
	errorElement: 'div',
	errorClass: 'helper-text',
	focusInvalid: false,
	
	rules: {
		mail_from: {
			required: true,
			email:true
		},
	},
	messages: {
		mail_from: {
			required: 'Email is required.',
			email:"Please enter valid email address."
		},
		mail_to: {
			required: 'Email is required.',
			email:"Please enter valid email address."
		}			
	},
	submitHandler: function(form){
		//$('#emailForm button').text("SENDING...");		
		$('#emailForm button').attr("disabled", true);		
		$('form span.ajax-loader').css('visibility', 'visible');
		$('#emailForm div.helper-text').remove();
		$('#emailForm #messages .error-msg').hide();
		$('#emailForm #messages .success-msg').hide();
		add_email();
		var property 	= $.trim($('#property').val());
		var mail_from 	= $.trim($('#mail_from').val());
		var mail_to		= $.trim($('#mail_to').val());
		var mail_message= $.trim($('#mail_message').val());
		var popup_nonce = $('#popup_nonce').val();
		if ( $('#emails_output').children().length <= 0 ) {
			check_email(mail_to);
		}
		var emails = [];
		$("#emails_output p").each(function(){
			var email = $(this).text();
			emails.push(email);
		});
		var serializedArr = JSON.stringify( emails );
		var unpackArr 	  = JSON.parse( serializedArr );		
		$.ajax({
			url: login.ajaxurl,
			type: 'POST',
			dataType :"json",
			data: {
				action			: 'email_to_friend',
				property 		: property,
				mail_from 		: mail_from,
				mail_to 		: mail_to,
				emailAry		: unpackArr,
				mail_message 	: mail_message,
				popup_nonce 	: popup_nonce,
			},
			cache: false,
			success: function(response){
				$('#emailForm button').attr("disabled", false);
				//$('#emailForm button').text("SEND");
				$('form span.ajax-loader').css('visibility', 'hidden');
				if(response.errorStatus){
					if(response.hiddenError){
						$('#emailForm #messages .error-msg').text(response.hiddenErrorMsg);
						$('#emailForm #messages').show();
						$('#emailForm #messages .error-msg').show();
					}                        
					if(response.usersError){
						$('#emailForm #messages .error-msg').text(response.usersErrorMsg);
						$('#emailForm #messages').show();
						$('#emailForm #messages .error-msg').show();
					}						
					if(response.errorEmailFrom){
						$( "#mail_from" ).after( '<span id="mail_from-error" class="error">'+ response.errorEmailFromMsg +'</span>' );
					}
				} else {
					if($(".helper-text").length == 0) {
						$("#mail_from").after( '<div class="helper-text">(your email)</div>' );
					}
					$("#emailForm").trigger('reset');
					$("#emails_output").text('');
					$('#emailForm #messages .success-msg').text(response.success);
					$('#emailForm #messages').show();
					$('#emailForm #messages .success-msg').show();			
				}
			},
				
		});
		return false;
	}
	
});

var mailid = 1;
var max_width = $('#emails_output p:first').width();
$("#add_mail").click(function(event){
	event.preventDefault();
	add_email();
});

function add_email(){
	
	/*var max_width = $('#emails_output').children(0).outerWidth();
	console.log(max_width);
	$('#emails_output p').each(function(){
		var current_width = $(this).outerWidth();
		if (current_width > max_width) {
			max_width = current_width;
		}
		$(this).css('width', max_width);
	});*/
	
	
	var mail = $("#mail_to").val();
	if( mail != '' && validateEmail(mail) ) {
		$("#emails_output").append('<p id="mail'+ mailid +'"><span>'+ mail +'</span><a href="#" class="remove_email" title="Remove"></a></p>');
		$("#emails_output").show();
		$("#mail_to").val('');
	mailid++;
	} else {
		return false;
	}
}

function check_email(email){
	$("#mail_to-error").remove();
	if( email == ''){
		$('#mail_to').after('<div id="mail_to-error" class="helper-text">Email is required.</div>');
		return false;
	} else if(!validateEmail(email)){
		$('#mail_to').after('<div id="mail_to-error" class="helper-text">Please enter valid email address.</div>');
		return false;
	} else {
		$("#mail_to-error").remove();
		return false;
	}
}

function validateEmail(email) {
  var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
  return reg.test(email);
}

/*$( "#mail_to" ).keyup(function() {
	var mailto = $(this).val();
	check_email(mailto);
});*/

$('#mail_to').on('keyup keypress blur change select', function() {
    var mailto = $(this).val();
	check_email(mailto);
});

$("#emails_output a").live("click", function(event){
	event.preventDefault();
	$(this).parent().remove();
});
$("#modal_close").click(function(){
	$('#myModal').modal('toggle');
	$("#emailForm").trigger('reset');
	$(".error-msg").html("").hide();
	$(".success-msg").html("").hide(); 	
	$("#mail_from").siblings('#mail_from-error').remove();
	$("#mail_to").siblings('#mail_to-error').remove();
	if($(".helper-text").length == 0) {
		$("#mail_from").after( '<div class="helper-text">(your email)</div>' );
	} else {
		$(".helper-text").html("(your email)").show();
	}
});
$('#myModal').on('hidden.bs.modal', function (e) {
	$("#emailForm").trigger('reset');
	$(".error-msg").html("").hide();
	$(".success-msg").html("").hide();
	$("#mail_from").siblings('#mail_from-error').remove();
	$("#mail_to").siblings('#mail_to-error').remove();
	if($(".helper-text").length == 0) {
		$("#mail_from").after( '<div class="helper-text">(your email)</div>' );
	} else {
		$(".helper-text").html("(your email)").show();
	}	
});


})( jQuery );


