<?php
// [signin_form]
add_shortcode('signin_form', 'signin_form_fnc');
function signin_form_fnc( $atts ) {
	/*$a = shortcode_atts( array(
        'number' => '7',
        'cat' 	 => 'awards',
    ), $atts );*/
    $output = '';
    $output .= '<form id="frm_user_login" method="post">';
		$output .= '<div class="form-group">';
			$output .= '<input type="text" name="email" id="email" class="form-control" placeholder="email address*">';
		$output .= '</div>';
		$output .= '<div class="form-group">';
			$output .= '<input type="password" name="password" id="password" class="form-control" placeholder="password*">';
		$output .= '</div>';
		$output .= '<div class="forgotten-pass">';
			$output .= '<a href="'. home_url() .'/forgot-password/">'. __('Forgotten Password', 'textdomain') .'</a>';
		$output .= '</div>';
		$output .= '<div class="form-group submit-button">';
			$output .= '<input type="hidden" name="login_nonce" id="login_nonce" value="'. wp_create_nonce('login-nonce') .'" />';
			$output .= '<button type="submit" class="btn">'. __('Submit', 'textdomain') .'</button>';
			$output .= '<span class="ajax-loader"></span>';
		$output .= '</div>';
		$output .= '<div id="messages">';
			$output .= '<div class="error-msg" style="width: 100%; margin-left: 0px; margin-top: 20px; padding: 0.4em 1em;color: #333; border: 2px solid #f7e700; display:none;"></div>';
			$output .= '<div class="success-msg" style="width: 100%; margin-left: 0px; margin-top: 20px; padding: 0.4em 1em;color: green; border: 2px solid green; display:none;"></div>';
		$output .= '</div>';		
	$output .= '</form>';
    
    return $output;
}


// [signup_form]
add_shortcode('signup_form', 'signup_form_fnc');
function signup_form_fnc( $atts ) {
	/*$a = shortcode_atts( array(
        'number' => '7',
        'cat' 	 => 'awards',
    ), $atts );*/
    $output = '';
    $output .= '<form id="frm_user_signup" method="post">';
		$output .= '<div class="form-group">';
			$output .= '<input type="text" name="signup_firstname" id="signup_firstname" class="form-control" placeholder="first name*">';
		$output .= '</div>';
		$output .= '<div class="form-group">';
			$output .= '<input type="text" name="signup_lastname" id="signup_lastname" class="form-control" placeholder="last name*">';
		$output .= '</div>';
		$output .= '<div class="form-group">';
			$output .= '<input type="text" name="signup_email" id="signup_email" class="form-control" placeholder="email address*">';
		$output .= '</div>';
		$output .= '<div class="form-group">';
			$output .= '<input type="text" name="signup_companyname" id="signup_companyname" class="form-control" placeholder="company name">';
		$output .= '</div>';
		$output .= '<div class="form-group">';
			$output .= '<input type="text" name="signup_country" id="signup_country" class="form-control" placeholder="country of origin">';
		$output .= '</div>';
		$output .= '<div class="form-group">';
			$output .= '<input type="password" name="signup_password" id="signup_password" class="form-control" placeholder="password*">';
		$output .= '</div>';
		$output .= '<div class="form-group">';
			$output .= '<input type="password" name="signup_confirmpassword" id="signup_confirmpassword" class="form-control" placeholder="confirm password*">';
		$output .= '</div>';
		$output .= '<div class="form-group submit-button">';
			$output .= '<input type="hidden" name="signup_nonce" id="signup_nonce" value="'. wp_create_nonce('signup-nonce') .'" />';
			$output .= '<button type="submit" class="btn">'. __('Submit', 'textdomain') .'</button>';
			$output .= '<span class="ajax-loader"></span>';
		$output .= '</div>';
		$output .= '<div id="signup-messages">';
			$output .= '<div class="error-msg" style="width: 100%; margin-left: 0px; margin-top: 20px; padding: 0.4em 1em;color: #333; border: 2px solid #f7e700; display:none;"></div>';
			$output .= '<div class="success-msg" style="width: 100%; margin-left: 0px; margin-top: 20px; padding: 0.4em 1em;color: green; border: 2px solid green; display:none;"></div>';
		$output .= '</div>';		
	$output .= '</form>';
    
    return $output;
}


// [forgot_password_form]
add_shortcode('forgot_password_form', 'forgot_password_form_fnc');
function forgot_password_form_fnc( $atts ) {
	/*$a = shortcode_atts( array(
        'number' => '7',
        'cat' 	 => 'awards',
    ), $atts );*/
   $output = '';
    if(isset($_GET['reset-new-password'])){
		if( isset ( $_GET['user_id']) && isset( $_GET['cp_activation_token']) && !empty( $_GET['user_id'])  && !empty( $_GET['cp_activation_token']) ){
			$user_id 		= $_GET['user_id'];
			$cp_user_token	= get_user_meta( $user_id, 'cp_activation_token', 1 );
			if( $cp_user_token != $_GET['cp_activation_token'] ){
				$output .= '<div id="messages">';
					$output .= '<div class="error-msg" style="margin-left: 0px; padding: 0.2em 1em;color: #333; border: 1px solid #f7e700;"><span>'. __('The link has expired.', 'textdomain') .'</span></div>';
				$output .= '</div>';
			} else {
				$output .= '<form id="frm_change_password" method="post">';
					$output .= '<div class="form-group">';								
						$output .= '<input type="password" name="new_password" id="new_password" class="form-control" placeholder="new password*">';
					$output .= '</div>';
					$output .= '<div class="form-group">';								
						$output .= '<input type="password" name="renew_password" id="renew_password" class="form-control" placeholder="confirm new password*">';
					$output .= '</div>';
					$output .= '<div class="form-group submit-button">';
						$output .= '<input type="hidden" class="input-text" name="user_id" id="user_id" value="'. $_GET['user_id'] .'">';
						$output .= '<input type="hidden" class="input-text" name="cp_activation_token" id="cp_activation_token" value="'. $_GET['cp_activation_token'] .'">';
						$output .= '<input type="hidden" name="reset_password_nonce" id="reset_password_nonce" value="'. wp_create_nonce('reset-password-nonce') .'" />';
						$output .= '<button type="submit" class="btn">'. __('Submit', 'textdomain') .'</button>';
						$output .= '<span class="ajax-loader"></span>';
					$output .= '</div>';
					$output .= '<div id="messages">';
						$output .= '<div class="error-msg" style="width: 100%; margin-left: 0px; margin-top: 20px; padding: 0.4em 1em;color: #333; border: 2px solid #f7e700; display:none;"></div>';
						$output .= '<div class="success-msg" style="width: 100%; margin-left: 0px; margin-top: 20px; padding: 0.4em 1em;color: green; border: 1px solid green; display:none;"></div>';
					$output .= '</div>';
				$output .= '</form>';
			}
		}
	} else {
		$output .= '<form id="frm_forgot_password" method="post">';
			$output .= '<div class="form-group">';								
				$output .= '<input type="text" name="email" id="email" class="form-control" placeholder="email address*">';
			$output .= '</div>';
			$output .= '<div class="form-group submit-button">';
				$output .= '<input type="hidden" name="forgot_password_nonce" id="forgot_password_nonce" value="'. wp_create_nonce('forgot-password') .'" />';
				$output .= '<button type="submit" class="btn">'. __('Submit', 'textdomain') .'</button>';
				$output .= '<span class="ajax-loader"></span>';
			$output .= '</div>';
			$output .= '<div id="messages">';
				$output .= '<div class="error-msg" style="width: 100%; margin-left: 0px; margin-top: 20px; padding: 0.4em 1em;color: #333; border: 2px solid #f7e700; display:none;"></div>';
				$output .= '<div class="success-msg" style="width: 100%; margin-left: 0px; margin-top: 20px; padding: 0.4em 1em;color: green; border: 1px solid green; display:none;"></div>';
			$output .= '</div>';
		$output .= '</form>';
	}
    return $output;
}
