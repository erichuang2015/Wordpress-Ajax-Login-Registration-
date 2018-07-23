<?php

//Generate Random String 
function generateRandomString($length = 20) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}

//Change post type name to Blog
function revcon_change_post_label() {
    global $menu;
    global $submenu;
    $menu[5][0] = 'Blogs';
    $submenu['edit.php'][5][0] = 'Blog';
    $submenu['edit.php'][10][0] = 'Add Blog';
}
function revcon_change_post_object() {
    global $wp_post_types;
    $labels = &$wp_post_types['post']->labels;
    $labels->name = 'Blogs';
    $labels->singular_name = 'Blog';
    $labels->add_new = 'Add Blog';
    $labels->add_new_item = 'Add Blog';
    $labels->edit_item = 'Edit Blog';
    $labels->new_item = 'Blog';
    $labels->view_item = 'View Blog';
    $labels->search_items = 'Search Blog';
    $labels->not_found = 'No blog found';
    $labels->not_found_in_trash = 'No blog found in Trash';
    $labels->all_items = 'All Blog';
    $labels->menu_name = 'Blog';
    $labels->name_admin_bar = 'Blog';
}
 
add_action( 'admin_menu', 'revcon_change_post_label' );
add_action( 'init', 'revcon_change_post_object' );

// Favicon Icon set in backend
function add_favicon() {
    if (get_field('favicon_icon', 'option')) {
        $favicon_url = get_field('favicon_icon', 'option');
        echo '<link rel="shortcut icon" href="' . $favicon_url . '" />';
    }
}

add_action('login_head', 'add_favicon');
add_action('admin_head', 'add_favicon');

// enable SVG Files 
function add_svg_to_upload_mimes($upload_mimes) {
    $upload_mimes['svg'] = 'image/svg+xml';
    $upload_mimes['svgz'] = 'image/svg+xml';
    return $upload_mimes;
}

add_filter('upload_mimes', 'add_svg_to_upload_mimes', 10, 1);

// hide admin bar from font-end
function hide_admin_bar_from_front_end() {
    if (is_blog_admin()) {
        return true;
    }
    return false;
}
add_filter('show_admin_bar', 'hide_admin_bar_from_front_end');

// Page Banner Function
function page_banner(){
	if( get_field('display_page_banner') ){
		echo '<section class="small-banner" style="background-image: url('. get_field('banner_image') .');">';
			if( get_field('display_call_button') ){
				echo '<div class="call-icon">';
					echo '<a href="'. get_field('banner_contact_page', 'option') .'"><img src="'. get_template_directory_uri() .'/assets/images/phone-icon.png" alt="phone-icon"></a>';
				echo '</div>';
			}
			if( get_field('banner_text') ){
				echo '<div class="banner-text">';
					echo '<div class="container">';
						echo '<h1>'. get_field('banner_text') .'</h1>';
					echo '</div>';
				echo '</div>';
			}
		echo '</section>';
	}
}

// add body class
add_filter('body_class', 'custom_body_classes', 1, 2);
function custom_body_classes($classes, $post) {
	/*if (!isset($_COOKIE['display_cookie_section'])) {
		$classes[] = 'display_cookie';
	}*/
	if (is_page_template('page-template/contact-page.php') ) {
		$classes[] = 'contact-page';
	}
	if (is_page_template('page-template/arrange-viewing-page.php') ) {
		$classes[] = 'arrange-viewing';
	}
	if (is_page_template('page-template/property-valuation-page.php') ) {
		$classes[] = 'property-valuation';
	}
	return $classes;
}

function my_wpcf7_form_elements($html) {
	$text = 'SELECT*';
	$html = str_replace('<option value="">---</option>', '<option value="">' . $text . '</option>', $html);
	return $html;
}
add_filter('wpcf7_form_elements', 'my_wpcf7_form_elements');

// cookies section
function display_cookies() {
	return;
    if (get_field('display_cookies_section', 'option') && get_field('cookies_content', 'option')) {
		echo '<div class="cookies-text">';
			echo '<div class="container">';
				echo get_field('cookies_content', 'option');
				if (get_field('cookies_link', 'option')) {
					echo ' <a href="'. get_field('cookies_link', 'option') .'">'. __('Learn more', 'textdomain').'</a>';
				} else {
					echo ' <a href="#">'. __('Learn more', 'textdomain').'</a>';
				}
				echo '<a href="#" id="btn-cookie-close"><img src="'. get_template_directory_uri() .'/assets/images/cookies-close.png" alt="cookies-close"></a>';
			echo '</div>';
		echo '</div>';
	}
}


// Submenu walker class
class textdomain_Walker_Nav_Menu extends Walker_Nav_Menu {

    function start_lvl(&$output, $depth = 0, $args = array()) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul>\n";
    }

    function end_lvl(&$output, $depth = 0, $args = array()) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }

}

// Blog prev next
function get_custom_the_post_navigation( $args = array() ) {
    $args = wp_parse_args( $args, array(
        'prev_text' => __( 'previous', 'jaspar' ),
		'next_text' => __( 'next', 'jaspar' ),
    ) );
    $navigation  = '<ul class="list-inline">';
    $navigation .= get_next_post_link( '<li>%link</li>', $args['prev_text'], false );
    $navigation .= get_previous_post_link( '<li>%link</li>', $args['next_text'], false );
    $navigation .= '</ul>';
    return $navigation;
}

// formate email template
function make_email_template($mail_content = "", $tokens = array()){
	$pattern = '##%s##';
	$map = array();
	foreach($tokens as $var => $value){
		$map[sprintf($pattern, $var)] = $value;
	}
	$mail_message = strtr($mail_content, $map);
	return $mail_message;
}



function add_first_and_last($items) {
  $items[1]->classes[] = 'first-menu-item';
  $items[count($items)]->classes[] = 'last-menu-item';
  return $items;
}

add_filter('wp_nav_menu_objects', 'add_first_and_last');

function add_menu_parent_class($items) {
    $parents = array();
    foreach ($items as $item) {
        if ($item->menu_item_parent && $item->menu_item_parent > 0) {
            $parents[] = $item->menu_item_parent;
        }
    }
    foreach ($items as $item) {
        if (in_array($item->ID, $parents)) {
            $item->classes[] = 'sub-menu';
        }
    }
    return $items;
}
add_filter('wp_nav_menu_objects', 'add_menu_parent_class');


// Blog short description
function get_blog_excerpt($length = 250){
	if( get_field('blog_short_description') ){
		global $post;
		$excerpt = get_field('blog_short_description');
		$excerpt = strip_shortcodes($excerpt);
		$excerpt = strip_tags($excerpt); 
		$excerpt = substr($excerpt, 0, $length);
		$excerpt = substr($excerpt, 0, strripos($excerpt, " "));
		$excerpt = $excerpt .' […]';
		return $excerpt;
	}
}


/*Allow Span tags in editor*/
function myextensionTinyMCE($init) {
    $ext = 'span[id|name|class|style]';
    if ( isset( $init['extended_valid_elements'] ) ) {
        $init['extended_valid_elements'] .= ',' . $ext;
    } else {
        $init['extended_valid_elements'] = $ext;
    }
    return $init;
}
add_filter('tiny_mce_before_init', 'myextensionTinyMCE' );

// Ajax Load more blog
add_action('wp_ajax_nopriv_more_blog_ajax', 'more_blog_ajax_fnc');
add_action('wp_ajax_more_blog_ajax', 'more_blog_ajax_fnc');

function more_blog_ajax_fnc() {
    $output	  = array();
	$offset   = (isset($_POST['offset'])) ? $_POST['offset'] : 1;
	$category = (isset($_POST['category'])) ? $_POST['category'] : 'all';
	header("Content-Type: text/html");
	$blog_args = array(
		'post_type' 	 => 'post',
		'posts_per_page' => 3,
		'post_status' 	 => 'publish',
		'offset'         => $offset
	);
	if( $category != 'all' ){
		$blog_args['tax_query'] = array(
			array(
				'taxonomy' => 'category',
				'field'    => 'slug',
				'terms'    => $category,
			),
		);
	}
	
	$blog_query 			= new WP_Query($blog_args);
	$count 					= $blog_query->post_count;
	$total_blog				= $blog_query->found_posts;
	//$blog_count 			= $count + $offset;
	$output['load'] 		= 0;
	
	if ($blog_query->have_posts()) {
		$output['html'] = '';
		$blog_count 	= $offset;
		while ($blog_query->have_posts()) {
			$blog_query->the_post();
			$output['html'] .= '<div class="news-box" id="post-'. get_the_ID() .'">';
				$output['html'] .= '<div class="inner-box">';
					$blogImage = '';
					if( has_post_thumbnail() ){
						$blogImageObj = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'large');
						$blogImage 	  = $blogImageObj[0];
					} else {
						$blogImage = get_template_directory_uri().'/assets/images/new-blanckImg.jpg';
					}
					$output['html'] .= '<div class="img">';
						$blog_brand_logo = get_template_directory_uri().'/assets/images/email_logo.png';
						if( get_field('blog_brand_logo') ){
							$blog_brand_logo_obj = get_field('blog_brand_logo');
							$blog_brand_logo	 = $blog_brand_logo_obj['url'];
						}
						$output['html'] .= '<div class="brand-logo"><img src="'. $blog_brand_logo .'"></div>';
						$output['html'] .= '<a href="'. get_permalink() .'"><img src="'. $blogImage .'" alt="'. get_the_title() .'"></a>';
					$output['html'] .= '</div>';
					
					$output['html'] .= '<div class="name"><a href="'. get_permalink() .'">'. get_the_title() .'</a></div>';
					$output['html'] .= '<p>'. __('By', 'textdomain').' '.get_the_author();
					if(get_the_author_meta('user_description')){ 
						$output['html'] .= ' | '.get_the_author_meta('user_description');
					}
					$output['html'] .= get_blog_excerpt(235);
					$output['html'] .= '</p>';
					$output['html'] .= '<div class="bottom-row">';
						$output['html'] .= '<div class="date">'. get_the_date('j F  y') .'</div>';
						$output['html'] .= '<a href="'. get_permalink() .'" class="btn">'. __('read more', 'textdomain') .'</a>';
					$output['html'] .= '</div>';
				$output['html'] .= '</div>';
			$output['html'] .= '</div>';
			$blog_count++;
		}		
		wp_reset_postdata();
		if($blog_count < $total_blog){
			$output['load'] = 1;
		}
		$output['new_offset'] = $blog_count;
	}
	echo json_encode($output);
	exit;
}

//** User login **//
add_action( 'wp_ajax_user_login', 'user_login_function' );
add_action( 'wp_ajax_nopriv_user_login', 'user_login_function' );
function user_login_function(){
	// global $loginUrl, $signupUrl, $profileUrl;
	 $response 		= array();
	 $email		  	= $_POST['email'];
	 $password		= $_POST['password'];
	 $login_nonce  	= $_POST['login_nonce'];
	 $response['redirecturl']   = "";
	 $response['errorStatus'] 	= false;
     $response['hiddenError'] 	= false;
     $response['errorEmail'] 	= false;
     $response['errorPassword'] = false;
     $response['usersError'] 	= false;
     $user = get_user_by( 'email', $email);
    
    if ( ! wp_verify_nonce( $login_nonce, 'login-nonce' ) ){
        $response['errorStatus'] 	= true;
        $response['hiddenError'] 	= true;
        $response['hiddenErrorMsg'] = __('Error: Please fill-up the form and submit it again', 'textdomain');
    }    
    if( empty ( $email )){
        $response['errorStatus'] 	= true;
        $response['errorEmail'] 	= true;
        $response['errorEmailMsg'] 	= __('Email is required.', 'textdomain');
    } else if(!is_email( $email )){
		$response['errorStatus'] 	= true;
        $response['errorEmail'] 	= true;
        $response['errorEmailMsg'] 	= __('Please enter a valid email address.', 'textdomain');
	} else {
		if($user) {
			if ( get_user_meta( $user->ID, 'is_activated', 1 ) != true ) {
				$response['errorStatus'] 	= true;
				$response['usersError'] 	= true;
				$response['usersErrorMsg'] 	= __('Your account may be inactive or blocked.', 'textdomain');
			}
		}
	}
	  
    if( empty ( $password )){
        $response['errorStatus'] 		= true;
        $response['errorPassword'] 		= true;
        $response['errorPasswordMsg'] 	= __('Password is required.', 'textdomain');
    } else if(!wp_check_password($password, $user->user_pass, $user->ID)) {
		$response['errorStatus'] 		= true;
        $response['usersError'] 		= true;
        $response['usersErrorMsg'] 		= __('Email address/password you entered is incorrect.', 'textdomain');
	}
		
	if($response['errorStatus'] == false) {
		$creds = array('user_login' => $email, 'user_password' => $password );
		$user2 = wp_signon( $creds, false );
		wp_set_current_user($user2->ID); 
		wp_set_auth_cookie($user2->ID, true, false ); 
		do_action( 'wp_login', $user2->user_login );
		if( !is_wp_error($user2) ) {
			$response['redirecturl'] = get_field('b2b_user_detail_page','option');
			$response['errorStatus'] = false;
			$response['success'] = __('Login successfully completed.', 'textdomain');
		} else {
			$response['errorStatus'] = true;
            $response['usersError'] = true;
            $response['usersErrorMsg'] = __('Email address/password you entered is incorrect.', 'textdomain');
		}
	}
	echo json_encode($response);
	exit;
}



/*************************/
//** User registration **//
add_action( 'wp_ajax_user_signup', 'user_signup_function' );
add_action( 'wp_ajax_nopriv_user_signup', 'user_signup_function' );
function user_signup_function(){
	 $response 		  = array();
	 $firstname		  = $_POST['firstname'];
	 $lastname		  = $_POST['lastname'];
	 $email			  = $_POST['email'];
	 $companyname	  = $_POST['companyname'];
	 $country		  = $_POST['country'];
	 $password		  = $_POST['password'];
	 $confirmpassword = $_POST['confirmpassword'];
	 $signup_nonce    = $_POST['signup_nonce'];
	 
	 $response['errorStatus'] 			= false;
     $response['hiddenError'] 			= false;
     $response['errorfirstname'] 		= false;
     $response['errorlastname'] 		= false;
     $response['erroremail'] 			= false;
     $response['errorcompanyname'] 		= false;
     $response['errorcountry'] 			= false;
     $response['errorpassword'] 		= false;
     $response['errorconfirmpassword'] 	= false;
    
    if ( ! wp_verify_nonce( $signup_nonce, 'signup-nonce' ) ){
        $response['errorStatus'] = true;
        $response['hiddenError'] = true;
        $response['hiddenErrorMsg'] = __('Error: Please fill-up the form and submit it again', 'textdomain');
    }    
	if( empty ( $firstname )){
		$response['errorStatus'] = true;
		$response['errorfirstname'] = true;
		$response['errorfirstnameMsg'] = __('Firstname is required.', 'textdomain');
	}
	if( empty ( $lastname )){
        $response['errorStatus'] = true;
        $response['errorlastname'] = true;
        $response['errorlastnameMsg'] = __('Lastname is required.', 'textdomain');
    }
    if( empty ( $email )){
        $response['errorStatus'] = true;
        $response['erroremail'] = true;
        $response['erroremailMsg'] = __('Email is required.', 'textdomain');
    } else if(!is_email( $email )){
		$response['errorStatus'] = true;
        $response['erroremail'] = true;
        $response['erroremailMsg'] = __('Please enter a valid email address.', 'textdomain');
	} else if(email_exists( $email )){
		$response['errorStatus'] = true;
        $response['erroremail'] = true;
        $response['erroremailMsg'] = __('Email already exist.Try with different Email address.', 'textdomain');
	}
    if( empty ( $password )){
        $response['errorStatus'] = true;
        $response['errorpassword'] = true;
        $response['errorpasswordMsg'] = __('Password is required.', 'textdomain');
    } else if( strlen($password) < 6 ){
		$response['errorStatus'] = true;
        $response['errorpassword'] = true;
        $response['errorpasswordMsg'] = __('Password is not less than 6 character.', 'textdomain');
	} else if( strlen($password) > 18 ){
		$response['errorStatus'] = true;
        $response['errorpassword'] = true;
        $response['errorpasswordMsg'] = __('Password is not more than 18 character.', 'textdomain');
	}
    if( empty ( $confirmpassword )){
        $response['errorStatus'] = true;
        $response['errorconfirmpassword'] = true;
        $response['errorconfirmpasswordMsg'] = __('Please confirm password.', 'textdomain');
    } else if( $confirmpassword != $password){
		$response['errorStatus'] = true;
        $response['errorconfirmpassword'] = true;
        $response['errorconfirmpasswordMsg'] = __('Password does not matched.', 'textdomain');
	}
	
	if($response['errorStatus'] == false){
        $new_user_id = wp_insert_user(array(
            'first_name' => ucfirst($firstname),
            'last_name'	 => ucfirst($lastname),
            'user_email' => $email,
            'user_pass'	 => $password,
            'user_login' => $email,
            'role'		 => 'b2b'
        ));
        wp_new_user_notification($new_user_id);
        if( !is_wp_error($new_user_id) ){
			if($new_user_id){
				$activation_token 		= generateRandomString();
                $activation_page_url 	= home_url().'/wp-admin/user-edit.php?user_id='.$new_user_id;
                //$activation_page_url 	= home_url().'/login/?account-activation=yes&user_id='.$new_user_id.'&activation_token='.$activation_token;
				
				update_user_meta( $new_user_id, 'companyname', $companyname );
				update_user_meta( $new_user_id, 'country', $country );
				update_user_meta( $new_user_id, 'activation_token', $activation_token );
				update_user_meta( $new_user_id, 'is_activated', 0 );
				update_user_meta( $new_user_id, 'user_active_status', 'registered' );
				$mail_header  = '<!DOCTYPE html>
								<html>
								<head>
									<title></title>
									<meta charset="utf-8">
								</head>
								<body style="margin: 0px; padding: 0px;">';
				$mail_footer  = '</body></html>';		
				
                /******* User Email Template Start *********/
                if( get_field('user_to', 'option') ){
					$mailTotokens = array('EMAIL' => $email);
					$to 		  = make_email_template(get_field('user_to', 'option'), $mailTotokens );
				} else {
					$to 		  = $email;
				}
                $subject = get_field('user_subject', 'option');				
				$user_headers[] = 'From: '. get_bloginfo('name') . '<'.get_field('user_from', 'option').'>';
                $user_headers[] = 'Content-Type: text/html; charset=UTF-8';
				$mail_content = $mail_header;
				if(get_field('user_message_body', 'option')){
					$tokens = array(
								'FIRSTNAME' => ucfirst($firstname),
								'LASTNAME'  => ucfirst($lastname),
							);
					$mail_content .= make_email_template(get_field('user_message_body', 'option'), $tokens );
				} else {
					$mail_content .= '<p>We have received your enquiry and will get back to you within 5 business days.</p>';
				}
				$mail_content .= $mail_footer;
				wp_mail( $to, $subject, $mail_content, $user_headers );
				/********* User Email Template End *********/
				/******* Admin Email Template Start *********/
                if( get_field('admin_to', 'option') ){
					$admin_to 	  = get_field('admin_to', 'option');
				} else {
					$admin_to 	  = '';
				}
                $admin_subject  = get_field('admin_subject', 'option');				
				$admin_headers[] = 'From: '. get_bloginfo('name') . '<'.get_field('admin_from', 'option').'>';
                $admin_headers[] = 'Content-Type: text/html; charset=UTF-8';
				$admin_mail_content = $mail_header;
				$activation_link = '<a href="'. $activation_page_url .'">Active User</a>';
				if(get_field('user_message_body', 'option')){
					$tokens = array(
								'FIRSTNAME' 		=> ucfirst($firstname),
								'LASTNAME'  		=> ucfirst($lastname),
								'EMAIL'  			=> $email,
								'ACTIVATION_LINK'   => $activation_link,
							);
					$admin_mail_content .= make_email_template(get_field('admin_message_body', 'option'), $tokens );
				} else {
					$admin_mail_content .= '<p>We have received your enquiry and will get back to you within 5 business days.</p>';
				}
				$admin_mail_content .= $mail_footer;
				wp_mail( $admin_to, $admin_subject, $admin_mail_content, $admin_headers );
				/********* Admin Email Template End *********/
               
                $response['errorStatus'] = false;
                $response['success'] = __('Thanks for your registration. You will receive an email when your account is activated.', 'textdomain');
			}
		} else {
			$response['errorStatus'] = true;
            $response['usersError'] = true;
            $response['usersErrorMsg'] = __('Error: Please fill-up the form and submit it again', 'textdomain');
		}
		
	}
	echo json_encode($response);
	exit;
}

//*** User Activation field ***//
add_action( 'show_user_profile', 'user_profile_activation' );
add_action( 'edit_user_profile', 'user_profile_activation' );
function user_profile_activation( $user ) {
    $is_activated = get_the_author_meta( 'is_activated', $user->ID );
    echo '<h3>'. __('User Status', 'textdomain').'</h3>';
    echo '<table class="form-table">';
    echo '<tr>';
        echo '<th><label for="user_status">'. __('User Status', 'textdomain') .'</label></th>';
        echo '<td>';
			echo '<select name="user_status" id="user_status">';
				echo '<option value="1"';
					if( $is_activated == 1 ){ echo 'selected="selected"'; }
				echo '>Active</option>';
				echo '<option value="0"';
					if( $is_activated == 0 ){ echo 'selected="selected"'; }
				echo '>Inactive</option>';
			echo '</select>';
		echo '</td>';
        
   echo '</tr>';
    echo '</table>';
}
add_action( 'personal_options_update', 'save_user_profile_activation' );
add_action( 'edit_user_profile_update', 'save_user_profile_activation' );
function save_user_profile_activation( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) ) { 
        return false; 
    }
    
    //$user = get_user_by( 'id', $user_id);
    $user = get_userdata( $user_id );
    update_user_meta( $user_id, 'is_activated', $_POST['user_status'] );
    $user_active_status = get_the_author_meta( 'user_active_status', $user_id );
    if( $user_active_status == 'registered' ){
		update_user_meta( $user_id, 'user_active_status', 'active' );
		
		/******* User Email Template Start *********/
		$mail_header  = '<!DOCTYPE html>
						<html>
						<head>
							<title></title>
							<meta charset="utf-8">
						</head>
						<body style="margin: 0px; padding: 0px;">';
		$mail_footer  = '</body></html>';
		if( get_field('user_to', 'option') ){
			$mailTotokens = array('EMAIL' => $user->data->user_email);
			$to 		  = make_email_template(get_field('user_to', 'option'), $mailTotokens );
		} else {
			$to 		  = $user->data->user_email;
		}
		$subject = get_field('user_subject', 'option');				
		$user_headers[] = 'From: '. get_bloginfo('name') . '<'.get_field('user_from', 'option').'>';
		$user_headers[] = 'Content-Type: text/html; charset=UTF-8';
		$mail_content = $mail_header;
		$firstname = $user->first_name;
		$lastname  = $user->last_name;
		if(get_field('user_activation_message', 'option')){
			$tokens = array(
						'FIRSTNAME' => ucfirst($firstname),
						'LASTNAME'  => ucfirst($lastname),
					);	
			$mail_content .= make_email_template(get_field('user_activation_message', 'option'), $tokens );
		} else {
			$mail_content .= '<p>We have received your enquiry and will get back to you within 5 business days.</p>';
		}
		$mail_content .= $mail_footer;
		wp_mail( $to, $subject, $mail_content, $user_headers );
		/********* User Email Template End *********/
		
	}
    
    
}

/*************************/



//** Forgot Password **//
add_action( 'wp_ajax_user_forgot_password', 'user_forgot_password_function' );
add_action( 'wp_ajax_nopriv_user_forgot_password', 'user_forgot_password_function' );
function user_forgot_password_function(){
	// global $loginUrl, $signupUrl, $profileUrl;
	 $response 		= array();
	 $email		  	= $_POST['email'];
	 $forgot_password_nonce  	= $_POST['forgot_password_nonce'];
	 $response['errorStatus'] 	= false;
     $response['hiddenError'] 	= false;
     $response['errorEmail'] 	= false;
     $response['usersError'] 	= false;
     $user = get_user_by( 'email', $email);
    
    if ( ! wp_verify_nonce( $forgot_password_nonce, 'forgot-password' ) ){
        $response['errorStatus'] 	= true;
        $response['hiddenError'] 	= true;
        $response['hiddenErrorMsg'] = __('Error: Please fill-up the form and submit it again', 'textdomain');
    }    
    
    if( empty ( $email )){
        $response['errorStatus'] = true;
        $response['errorEmail'] = true;
        $response['errorEmailMsg'] = __('Email is required.', 'textdomain');
    } else if(!is_email( $email )){
		$response['errorStatus'] = true;
        $response['errorEmail'] = true;
        $response['errorEmailMsg'] = __('Please enter a valid email address.', 'textdomain');
	} else if(!email_exists( $email )){
		$response['errorStatus'] = true;
        $response['errorEmail'] = true;
        $response['errorEmailMsg'] = __('User does not exist. Try with different Email address.', 'textdomain');
	}
	
	if($response['errorStatus'] == false) {
        $user = get_user_by( 'email', $email);
		if($user) {
			$activation_token 		= generateRandomString();	
			$admin_email 			= get_option('admin_email');
			$to						= $email;
			$activation_page_url 	= home_url().'/forgot-password?reset-new-password=yes&user_id='.$user->ID.'&cp_activation_token='.$activation_token;
		
			$subject = 'textdomain Residential Password Reset';
            $headers[] = 'From: textdomain Residential <'.$admin_email.'>';
            $headers[] = 'Content-Type: text/html; charset=UTF-8';
            
            /******* Email Template *********/
            
            $message = '<!DOCTYPE html>
			<html>
			<head>
			<title></title>
			<meta charset="utf-8">
			</head>
			<body style="margin: 0px; padding: 0px;">
			<table width="100%" cellpadding="0" cellspacing="0" bgcolor="#f9f9f9">
				<tr>
					<td align="center" valign="middle" width="560">
						<!--[if (gte mso 9)|(IE)]>
						<table align="center" border="0" cellspacing="0" cellpadding="0" width="640">
						<tr>
						<td align="center" valign="top" width="560">
						<![endif]-->
						<table width="100%" cellpadding="0" cellspacing="0" style="max-width: 560px;" class="wrapper">
							<tr>
								<td align="center" valign="top" style="padding: 34px 0;">
									<a href="http://115.160.244.10:8082/wordpress-projects/textdomain-residential"><img src="http://115.160.244.10:8082/wordpress-projects/textdomain-residential/wp-content/uploads/2018/03/email_logo.png" alt="textdomain_logo"></a>
								</td>
							</tr>
						</table>
						<!--[if (gte mso 9)|(IE)]>
						</td>
						</tr>
						</table>
						<![endif]-->
					</td>    
				</tr>
			</table>
			<table width="100%" cellpadding="0" cellspacing="0" bgcolor="#ffffff">
				<tr>
					<td align="center" valign="middle" width="560">
						<!--[if (gte mso 9)|(IE)]>
						<table align="center" border="0" cellspacing="0" cellpadding="0" width="640">
						<tr>
						<td align="center" valign="top" width="560">
						<![endif]-->
						<table width="100%" cellpadding="0" cellspacing="0" style="max-width: 560px;" class="wrapper">
							<tr>
								<td align="left" valign="top" style="padding: 34px 0 34px 0;">
									<table cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td align="left" valign="top" style="border-top: solid 2px #f8f8f8;">
												<table cellpadding="0" cellspacing="0" width="100%">
													<tr>
														<td align="left" valign="top" style="padding: 30px 0 0 0;font-family: Arial,Helvetica,sans-serif; font-weight: bold; color: #ec008c; font-size: 21px;">Reset Password</td>
													</tr>
													<tr>
														<td align="left" valign="top" style="padding: 24px 0 0 0;font-family: Arial,Helvetica,sans-serif; color: #000000; font-size: 14px;">Dear '.$user->first_name.' '.$user->last_name.',</td>
													</tr>
													<tr>
														<td align="left" valign="top" style="padding: 18px 0 0 0;font-family: Arial,Helvetica,sans-serif; color: #000000; font-size: 14px;">We got request to reset your password.</td>
													</tr>
													<tr>
														<td align="left" valign="top" style="padding: 18px 0 0 0;font-family: Arial,Helvetica,sans-serif; color: #000000; font-size: 14px;">For reset your password <a href="'. $activation_page_url .'" target="_blank" style="color: #ec008c; ">Click here</a>.</td>
													</tr>
													<tr>
														<td align="left" valign="top" style="padding: 18px 0 0 0;font-family: Arial,Helvetica,sans-serif; color: #000000; font-size: 14px;">If you ingnore this message, password won&apos;t be changed.</td>
													</tr>
													<tr>
														<td align="left" valign="top" style="padding: 18px 0 0 0;font-family: Arial,Helvetica,sans-serif; color: #000000; font-size: 14px;">All the best,</td>
													</tr>
													<tr>
														<td align="left" valign="top" style="padding: 14px 0 0 0;font-family: Arial,Helvetica,sans-serif; color: #000000; font-size: 14px;">textdomain Residential </td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
						<!--[if (gte mso 9)|(IE)]>
						</td>
						</tr>
						</table>
						<![endif]-->
					</td>
					
				</tr>
			</table>
			<table width="100%" cellpadding="0" cellspacing="0" bgcolor="#f9f9f9">
				<tr>
					<td align="center" valign="middle" width="560">
						<!--[if (gte mso 9)|(IE)]>
						<table align="center" border="0" cellspacing="0" cellpadding="0" width="640">
						<tr>
						<td align="center" valign="top" width="560">
						<![endif]-->
						<table width="100%" cellpadding="0" cellspacing="0" style="max-width: 560px;" class="wrapper">
							<tr>
								<td align="left" valign="top" style="padding: 0 0 30px 0" >
									<table cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td align="left" valign="top" style="padding: 26px 0 0 0;font-family: Arial,Helvetica,sans-serif; font-weight: bold; color: #ec008c; font-size: 21px;">textdomain Residential</td>
										</tr>
										<tr>
											<td align="left" valign="top" style="padding: 12px 0 0 0;font-family: Arial,Helvetica,sans-serif; color: #000000; font-size: 14px;">50 George Street, </td>
										</tr>
										<tr>
											<td align="left" valign="top" style="padding: 12px 0 0 0;font-family: Arial,Helvetica,sans-serif; color: #000000; font-size: 14px;">London W1U 7GA, </td>
										</tr>
										<tr>
											<td align="left" valign="top" style="padding: 12px 0 0 0;font-family: Arial,Helvetica,sans-serif; color: #000000; font-size: 14px;">United Kingdom</td>
										</tr>
										<tr>
											<td align="left" valign="top" style="padding: 12px 0 0 0;font-family: Arial,Helvetica,sans-serif; color: #000000; font-size: 14px;"><span style="color: #ec008c; padding: 0 5px 0 0">T</span> <a href="tel:+442074871698" style="color: #000000;text-decoration:none;">+44 20 7487 1698</a> </td>
										</tr>
										<tr>
											<td align="left" valign="top" style="padding: 12px 0 0 0;font-family: Arial,Helvetica,sans-serif; color: #000000; font-size: 14px;"><span style="color: #ec008c; padding: 0 5px 0 0">E</span> <a href="mailto:newhomes@textdomain.com" style="color: #000000">newhomes@textdomain.com</a> </td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
						<!--[if (gte mso 9)|(IE)]>
						</td>
						</tr>
						</table>
						<![endif]-->
					</td>
					
				</tr>
			</table>
			</body>
			</html>';
            
			/*$message = '
				<p>Dear '.$user->first_name.' '.$user->last_name.',</p>
				<p>We got request to reset your password.</p>
				<p><a href="'. $activation_page_url .'" target="_blank" style="text-decoration:none; color:#9FB559">Reset Password</a></p>
				<p>If you ingnore this message, password wonts be chnage.</p>
				
				<p>Regards,<br />textdomain Residential Team.</p>';*/

			/***********************/
            
            
			if ( wp_mail( $to, $subject, $message, $headers ) )
				$mailSent = 1;
			else
				$mailSent = 0;
				
			update_user_meta( $user->ID, 'cp_activation_token', $activation_token );
			update_user_meta( $user->ID, 'cp_password_reset', 0 );
			$response['activationUrl'] = $activation_page_url;
			$response['errorStatus'] = false;
			$response['success'] = __('Please check your email to reset your password.', 'textdomain');
		} else {
			$response['errorStatus'] = true;
            $response['usersError'] = true;
            $response['usersErrorMsg'] = __('Error: Please fill-up the form and submit it again', 'textdomain');
		}
	}
	echo json_encode($response);
	exit;
}

//** User Change password **//
add_action( 'wp_ajax_user_reset_pasword', 'user_reset_pasword_function' );
add_action( 'wp_ajax_nopriv_user_reset_pasword', 'user_reset_pasword_function' );
function user_reset_pasword_function(){
	$response = array();
	$user_password 	  	 = trim( $_POST['new_password']);
	$user_repassword 	 = trim( $_POST['renew_password']);
	$reset_password_nonce= trim( $_POST['reset_password_nonce']);
	$user_id 			 = trim( $_POST['user_id']);
    $cp_activation_token = trim( $_POST['activation_token']);
	
	$response['redirecturl']   = "";
	$response['errorStatus'] 	= false;
	$response['hiddenError'] 	= false;
    $response['usersError'] 	= false;
    $response['errorPassword'] 	= false;
    $response['errorRepassword']= false;
    
    if ( ! wp_verify_nonce( $reset_password_nonce, 'reset-password-nonce' ) ){
        $response['errorStatus'] = true;
        $response['hiddenError'] = true;
        $response['hiddenErrorMsg'] = __('Error: Please fill-up the form and submit it again', 'textdomain');
    }
    if( empty ( $user_password )){
        $response['errorStatus'] = true;
        $response['errorPassword'] = true;
        $response['errorPasswordMsg'] = __('Password is required.', 'textdomain');
    } else if( strlen($user_password) < 6 ){
		$response['errorStatus'] = true;
        $response['errorPassword'] = true;
        $response['errorPasswordMsg'] = __('Password is not less than 6 character.', 'textdomain');
	} else if( strlen($user_password) > 18 ){
		$response['errorStatus'] = true;
        $response['errorPassword'] = true;
        $response['errorPasswordMsg'] = __('Password is not more than 18 character.', 'textdomain');
	} 
	
    if( empty ( $user_repassword )){
        $response['errorStatus'] = true;
        $response['errorRepassword'] = true;
        $response['errorRepasswordMsg'] = __('Please confirm password.', 'textdomain');
    } else if( $user_repassword != $user_password){
		$response['errorStatus'] = true;
        $response['errorRepassword'] = true;
        $response['errorRepasswordMsg'] = __('Password does not matched.', 'textdomain');
	}
	if($response['errorStatus'] == false){
		$db_activation_token	= get_user_meta( $user_id, 'cp_activation_token', 1 );
        if($db_activation_token == $cp_activation_token){
            $pass_reset	= get_user_meta( $user_id, 'cp_password_reset', 1 );
            if( $pass_reset == 0 ) {
                $userdata = array(
                    'ID'        =>  $user_id,
                    'user_pass' =>  $user_password
                );  
                $updated_user_id = wp_update_user($userdata);                
                if($user_id == $updated_user_id) {
                    update_user_meta( $user_id, 'cp_password_reset', 1 );
                    delete_user_meta($user_id, 'cp_activation_token');
                    update_user_meta( $user_id, 'is_activated', 1 );
                    $response['errorStatus'] = false;
                    $response['redirecturl'] = home_url().'/login';
                    $response['success'] = __('Password updated successfully !!', 'textdomain');
                }
            } else {
                $response['errorStatus'] = true;
                $response['usersError'] = true;
                $response['usersErrorMsg'] = __('Error: Please fill-up the form and submit it again', 'textdomain');
            }
        } else {
            $response['errorStatus'] = true;
            $response['usersError'] = true;
            $response['usersErrorMsg'] = __('Activation token is invalid.', 'textdomain');
        }
	}
	echo json_encode($response);
	exit;
}


//** Email Popup **//
add_action( 'wp_ajax_email_to_friend', 'email_to_friend_function' );
add_action( 'wp_ajax_nopriv_email_to_friend', 'email_to_friend_function' );
function email_to_friend_function(){
	$response = array();
	$property_id 	 = trim( $_POST['property']);
	$mail_from 	  	 = trim( $_POST['mail_from']);
	$mail_to 	 	 = trim( $_POST['mail_to']);
	$mails 	 	 	 = $_POST['emailAry'];
	$mail_message 	 = trim( $_POST['mail_message']);
	$popup_nonce	 = trim( $_POST['popup_nonce']);
	
	$response['redirecturl']    	= "";
	$response['errorStatus'] 		= false;
	$response['hiddenError'] 		= false;
    $response['usersError'] 		= false;
    $response['errorEmailTo'] 		= false;
    $response['errorEmailFrom']  	= false;
    $response['errorEmailMessage']	= false;
    
    /*if ( !wp_verify_nonce( $popup_nonce, 'mailpopup-nonce' ) ){
        $response['errorStatus'] = true;
        $response['hiddenError'] = true;
        $response['hiddenErrorMsg'] = __('Error: Please fill-up the form and submit it again.', 'textdomain');
    }*/
    if( empty ( $mail_from )){
        $response['errorStatus'] = true;
        $response['errorEmailFrom'] = true;
        $response['errorEmailFromMsg'] = __('Email is required.', 'textdomain');
    } else if(!is_email( $mail_from )){
		$response['errorStatus'] = true;
        $response['errorEmail'] = true;
        $response['errorEmailFromMsg'] = __('Please enter a valid email address.', 'textdomain');
	}
	/*if( empty ( $mail_to )){
        $response['errorStatus'] = true;
        $response['errorEmail'] = true;
        $response['errorEmailToMsg'] = __('Email is required.', 'textdomain');
    } else if(!is_email( $mail_to )){
		$response['errorStatus'] = true;
        $response['errorEmail'] = true;
        $response['errorEmailToMsg'] = __('Please enter a valid email address.', 'textdomain');
	}*/
    
	if($response['errorStatus'] == false){
		if( !empty($mails) ){
			
			$to			 = $mails;		
			$subject   	 = 'textdomain Residential';
            $headers[] 	 = 'From: textdomain Residential <'.$mail_from.'>';
            $headers[] 	 = 'Content-Type: text/html; charset=UTF-8';
            
            /******* Email Template *********/
            $message = '<!DOCTYPE html>
				<html>
				<head>
				<title></title>
				<meta charset="utf-8">
				</head>
				<body style="margin: 0px; padding: 0px;">
				<table width="100%" cellpadding="0" cellspacing="0" bgcolor="#f9f9f9">
					<tr>
						<td align="center" valign="middle" width="560">
							<!--[if (gte mso 9)|(IE)]>
							<table align="center" border="0" cellspacing="0" cellpadding="0" width="640">
							<tr>
							<td align="center" valign="top" width="560">
							<![endif]-->
							<table width="100%" cellpadding="0" cellspacing="0" style="max-width: 560px;" class="wrapper">
								<tr>
									<td align="center" valign="top" style="padding: 34px 0;">
										<a href="http://115.160.244.10:8082/wordpress-projects/textdomain-residential"><img src="http://115.160.244.10:8082/wordpress-projects/textdomain-residential/wp-content/uploads/2018/03/email_logo.png" alt="textdomain_logo"></a>
									</td>
								</tr>
							</table>
							<!--[if (gte mso 9)|(IE)]>
							</td>
							</tr>
							</table>
							<![endif]-->
						</td>    
					</tr>
				</table>
				<table width="100%" cellpadding="0" cellspacing="0" bgcolor="#ffffff">
					<tr>
						<td align="center" valign="middle" width="560">
							<!--[if (gte mso 9)|(IE)]>
							<table align="center" border="0" cellspacing="0" cellpadding="0" width="640">
							<tr>
							<td align="center" valign="top" width="560">
							<![endif]-->
							<table width="100%" cellpadding="0" cellspacing="0" style="max-width: 560px;" class="wrapper">
								<tr>
									<td align="left" valign="top" style="padding: 34px 0 34px 0;">
										<table cellpadding="0" cellspacing="0" width="100%">
											<tr>
												<td align="left" valign="top" style="border-top: solid 2px #f8f8f8;">
													<table cellpadding="0" cellspacing="0" width="100%">
														<tr>
															<td align="left" valign="top" style="padding: 30px 0 0 0;font-family: Arial,Helvetica,sans-serif; font-weight: bold; color: #ec008c; font-size: 21px;">textdomain Residential</td>
														</tr>
														<tr>
															<td align="left" valign="top" style="padding: 24px 0 0 0;font-family: Arial,Helvetica,sans-serif; color: #000000; font-size: 14px;"></td>
														</tr>
														<tr>
															<td align="left" valign="top" style="padding: 18px 0 0 0;font-family: Arial,Helvetica,sans-serif; color: #000000; font-size: 14px;">'. $mail_message .'</td>
														</tr>
														<tr>
															<td align="left" valign="top" style="padding: 18px 0 0 0;font-family: Arial,Helvetica,sans-serif; color: #000000; font-size: 14px;">Property : '. get_permalink($property_id) .'</td>
														</tr>
														<tr>
															<td align="left" valign="top" style="padding: 18px 0 0 0;font-family: Arial,Helvetica,sans-serif; color: #000000; font-size: 14px;">If you are not correct recipient, please ingnore this message.</td>
														</tr>
														<tr>
															<td align="left" valign="top" style="padding: 18px 0 0 0;font-family: Arial,Helvetica,sans-serif; color: #000000; font-size: 14px;">All the best,</td>
														</tr>
														<tr>
															<td align="left" valign="top" style="padding: 14px 0 0 0;font-family: Arial,Helvetica,sans-serif; color: #000000; font-size: 14px;">textdomain Residential </td>
														</tr>
													</table>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
							<!--[if (gte mso 9)|(IE)]>
							</td>
							</tr>
							</table>
							<![endif]-->
						</td>
						
					</tr>
				</table>
				<table width="100%" cellpadding="0" cellspacing="0" bgcolor="#f9f9f9">
					<tr>
						<td align="center" valign="middle" width="560">
							<!--[if (gte mso 9)|(IE)]>
							<table align="center" border="0" cellspacing="0" cellpadding="0" width="640">
							<tr>
							<td align="center" valign="top" width="560">
							<![endif]-->
							<table width="100%" cellpadding="0" cellspacing="0" style="max-width: 560px;" class="wrapper">
								<tr>
									<td align="left" valign="top" style="padding: 0 0 30px 0" >
										<table cellpadding="0" cellspacing="0" width="100%">
											<tr>
												<td align="left" valign="top" style="padding: 26px 0 0 0;font-family: Arial,Helvetica,sans-serif; font-weight: bold; color: #ec008c; font-size: 21px;">textdomain Residential</td>
											</tr>
											<tr>
												<td align="left" valign="top" style="padding: 12px 0 0 0;font-family: Arial,Helvetica,sans-serif; color: #000000; font-size: 14px;">50 George Street, </td>
											</tr>
											<tr>
												<td align="left" valign="top" style="padding: 12px 0 0 0;font-family: Arial,Helvetica,sans-serif; color: #000000; font-size: 14px;">London W1U 7GA, </td>
											</tr>
											<tr>
												<td align="left" valign="top" style="padding: 12px 0 0 0;font-family: Arial,Helvetica,sans-serif; color: #000000; font-size: 14px;">United Kingdom</td>
											</tr>
											<tr>
												<td align="left" valign="top" style="padding: 12px 0 0 0;font-family: Arial,Helvetica,sans-serif; color: #000000; font-size: 14px;"><span style="color: #ec008c; padding: 0 5px 0 0">T</span> <a href="tel:+442074871698" style="color: #000000;text-decoration:none;">+44 20 7487 1698</a> </td>
											</tr>
											<tr>
												<td align="left" valign="top" style="padding: 12px 0 0 0;font-family: Arial,Helvetica,sans-serif; color: #000000; font-size: 14px;"><span style="color: #ec008c; padding: 0 5px 0 0">E</span> <a href="mailto:newhomes@textdomain.com" style="color: #000000">newhomes@textdomain.com</a> </td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
							<!--[if (gte mso 9)|(IE)]>
							</td>
							</tr>
							</table>
							<![endif]-->
						</td>
						
					</tr>
				</table>
				</body>
				</html>';
            
			/***********************/
            
            
			if ( wp_mail( $to, $subject, $message, $headers ) )
				$mailSent = 1;
			else
				$mailSent = 0;
				
			$response['errorStatus'] = false;
			$response['success'] 	 = __('Mail sent successfully !', 'textdomain');
			
		} else {
			$response['errorStatus'] = true;
			$response['usersError'] = true;
			$response['usersErrorMsg'] = __('Error: Please fill-up the form and submit it again.', 'textdomain');
		}
	}
	echo json_encode($response);
	exit;
}

/*********** Contact forms GDPR start **************/
function contact_form_filter($formData) {
	
    if($formData->posted_data['wpgdprc'] == 1){
		$formData->posted_data['GDPR_Compliant'] = 'Yes';
	} else {
		$formData->posted_data['GDPR_Compliant'] = 'No';
	}
	unset($formData->posted_data['wpgdprc']);
	
	// For contact form
	if($formData->posted_data['_wpcf7'] == 565){
		$formData->posted_data['username'] 		= custom_simple_crypt( $formData->posted_data['username'], 'e' );
		$formData->posted_data['email'] 		= custom_simple_crypt( $formData->posted_data['email'], 'e' );
		$formData->posted_data['phone'] 		= custom_simple_crypt( $formData->posted_data['phone'], 'e' );
		$formData->posted_data['message'] 		= custom_simple_crypt( $formData->posted_data['message'], 'e' );
		$formData->posted_data['GDPR_Compliant']= custom_simple_crypt( $formData->posted_data['GDPR_Compliant'], 'e' );
	}
	// For Arrange Viewing Form
	if($formData->posted_data['_wpcf7'] == 605){
		$formData->posted_data['username'] 		= custom_simple_crypt( $formData->posted_data['username'], 'e' );
		$formData->posted_data['email'] 		= custom_simple_crypt( $formData->posted_data['email'], 'e' );
		$formData->posted_data['telephone'] 	= custom_simple_crypt( $formData->posted_data['telephone'], 'e' );
		$formData->posted_data['address'] 		= custom_simple_crypt( $formData->posted_data['address'], 'e' );
		$formData->posted_data['viewingPlace'] 	= custom_simple_crypt( $formData->posted_data['viewingPlace'], 'e' );
		$formData->posted_data['GDPR_Compliant']= custom_simple_crypt( $formData->posted_data['GDPR_Compliant'], 'e' );
	}
	// For Property Valuation Form
	if($formData->posted_data['_wpcf7'] == 606){
		$formData->posted_data['select1'] 		= custom_simple_crypt( $formData->posted_data['select1'], 'e' );
		$formData->posted_data['select2'] 		= custom_simple_crypt( $formData->posted_data['select2'], 'e' );
		$formData->posted_data['firstName'] 	= custom_simple_crypt( $formData->posted_data['firstName'], 'e' );
		$formData->posted_data['lastName'] 		= custom_simple_crypt( $formData->posted_data['lastName'], 'e' );
		$formData->posted_data['email'] 		= custom_simple_crypt( $formData->posted_data['email'], 'e' );
		$formData->posted_data['telephone'] 	= custom_simple_crypt( $formData->posted_data['telephone'], 'e' );
		$formData->posted_data['houseName'] 	= custom_simple_crypt( $formData->posted_data['houseName'], 'e' );
		$formData->posted_data['streetName'] 	= custom_simple_crypt( $formData->posted_data['streetName'], 'e' );
		$formData->posted_data['town'] 			= custom_simple_crypt( $formData->posted_data['town'], 'e' );
		$formData->posted_data['postcode'] 		= custom_simple_crypt( $formData->posted_data['postcode'], 'e' );
		$formData->posted_data['comments']		= custom_simple_crypt( $formData->posted_data['comments'], 'e' );
		$formData->posted_data['GDPR_Compliant']= custom_simple_crypt( $formData->posted_data['GDPR_Compliant'], 'e' );
	}
	// For Potential Purchaser Form
	if($formData->posted_data['_wpcf7'] == 19804 || $formData->posted_data['_wpcf7'] == 13321){
		$formData->posted_data['purchaserName'] 		= custom_simple_crypt( $formData->posted_data['purchaserName'], 'e' );
		$formData->posted_data['purchaserMobile'] 		= custom_simple_crypt( $formData->posted_data['purchaserMobile'], 'e' );
		$formData->posted_data['purchaserEmail'] 		= custom_simple_crypt( $formData->posted_data['purchaserEmail'], 'e' );
		$formData->posted_data['GDPR_Compliant']		= custom_simple_crypt( $formData->posted_data['GDPR_Compliant'], 'e' );
	}
    return $formData;
}
add_filter('cfdb_form_data', 'contact_form_filter');
function alter_wpcf7_posted_data( $data ) {
	if(!array_key_exists('wpgdprc', $data)){
		$data['wpgdprc'] = 0;
	}
    return $data;
}
add_filter("wpcf7_posted_data", "alter_wpcf7_posted_data");
function custom_simple_crypt( $string, $action = 'e' ) {
    $secret_key = 'QWERTYUIOPPOIUYTREWQ';
    $secret_iv = 'ASDFGHJKLLKJHGFDSAAS';
 
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $key = hash( 'sha256', $secret_key );
    $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
 
    if( $action == 'e' ) {
        $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
    }
    else if( $action == 'd' ){
        $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
    }
 
    return $output;
}
function CF7_decrypt_data_func( $cell, $aCol ) {
	if( $aCol != 'Submitted' && $aCol != 'Submitted From' ){
		$cell = custom_simple_crypt($cell, 'd');
	}
    return $cell;
}
add_filter( 'CF7_decrypt_data', 'CF7_decrypt_data_func', 10, 3 );


add_action( 'wp_ajax_property_title_autocomplete', 'fn_get_property_autocomplete_list' );
add_action( 'wp_ajax_nopriv_property_title_autocomplete', 'fn_get_property_autocomplete_list' );

function fn_get_property_autocomplete_list()
{
	global $wpdb;
	$autocomplete_result = array();
	$search_term 		 = $_POST['search_term'];

	$search_query 	= "SELECT *  FROM $wpdb->posts WHERE post_title LIKE '%".$search_term."%' AND post_type = 'property'";
	$properties 	= $wpdb->get_results( $search_query );

	if( count($properties) > 0 )
	{
		foreach( $properties as $property )
		{
			$post_title =  strtolower($property->post_title);
			$post_title = esc_attr( $post_title);
			$post_title = str_replace('&#039;', "'", $post_title);
			$post_title = str_replace('&amp;', "&", $post_title);
			array_push($autocomplete_result,$post_title);
		}
	}


	
	echo json_encode($autocomplete_result); //encode into JSON format and output

	exit;
}

function action_mc4wp_form_success( $form ) {
	$form_data = $form->raw_data;
	$userEmail = $form_data['EMAIL'];	
	$mail_header  = '<!DOCTYPE html>
						<html>
						<head>
							<title></title>
							<meta charset="utf-8">
						</head>
						<body style="margin: 0px; padding: 0px;">';
	$mail_footer  = '</body></html>';
	if( get_field('mailchimp_to', 'option') ){
		$to 		  = get_field('mailchimp_to', 'option');
	} else {
		$to 		  = get_option('admin_email');
	}
	$subject = get_field('mailchimp_subject', 'option');				
	$user_headers[] = 'From: '. get_bloginfo('name') . '<'.get_field('mailchimp_from', 'option').'>';
	$user_headers[] = 'Content-Type: text/html; charset=UTF-8';
	$mail_content = $mail_header;
	if(get_field('mailchimp_message_body', 'option')){
		$tokens = array(
					'USER_EMAIL' => ucfirst($userEmail),
				);	
		$mail_content .= make_email_template(get_field('mailchimp_message_body', 'option'), $tokens );
	} else {
		$mail_content .= '<p>We have received your enquiry and will get back to you within 5 business days.</p>';
	}
	$mail_content .= $mail_footer;
	wp_mail( $to, $subject, $mail_content, $user_headers );
}
add_action( 'mc4wp_form_success', 'action_mc4wp_form_success', 10, 1 );

//** Remove privacy policy checkbox required field  **//
use WPGDPRC\Includes\Extensions\CF7;
remove_filter( 'wpcf7_validate_wpgdprc', array(CF7::getInstance(), 'validateField'), 10, 2);
