<?php
/*
Plugin Name: Mailchimp as a Registration
Plugin URI: https://wordpress.org/plugins/mailchimp-as-a-registration/
Description: Enables mailchimp to be used as registration when opening up WP to registrations, thus reducing spammers as they must be authenticated through mailchimp. Also includes a Terms & Condition dialog with  Accept/Decline feature
Author: PressPage Entertainment Inc.
Version: 1.1
Author URI: https://presspage.info
*/


if (get_option('mailchimp_registration') == 'on') {
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-dialog');

	add_action('register_form','mailchimp_register_form');	
	add_filter('registration_errors', 'mailchimp_registration_errors', 10, 3);
}

function mailchimp_register_form() {
		
		$first_name = ( isset( $_POST['first_name'] ) ) ? $_POST['first_name']: '';
		$terms = get_option('registration_terms');
		$termsdialogtitle = get_option('registration_terms_dialog_title');

        ?>
		<link href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" rel="stylesheet"/>
		<script type="text/javascript">
			jQuery(document).ready(function($) {
				$("#wp-submit").attr("disabled","disabled");
				
				
				$("#view_terms").click(function(){
					$( "#terms" ).dialog({
					      resizable: false,
					      height:"auto",
						  width:"auto",
					      modal: true,
					      buttons: {
					        "Decline": function() {
							  $("#wp-submit").attr("disabled","disabled");
					          $( this ).dialog( "close" );
					        },
					        "Accept": function() {
							  $("#wp-submit").removeAttr("disabled");;
					          $( this ).dialog( "close" );
					        }
					      }
					});
				});
			}); //end onload stuff
		</script>
        <p>
            <label for="first_name"><?php _e('First Name',$mcdomain) ?><br />
                <input type="text" name="first_name" id="first_name" class="input" value="<?php echo esc_attr(stripslashes($first_name)); ?>" size="25" /></label>
        </p>
        <?php
		
		$last_name = ( isset($_POST['last_name'])) ? $_POST['last_name'] : '';
		?>
        <p>
            <label for="last_name"><?php _e('Last Name',$mcdomain) ?><br />
                <input type="text" name="last_name" id="last_name" class="input" value="<?php echo esc_attr(stripslashes($last_name)); ?>" size="25" /></label>
        </p>
		<?php	
		
		$phone = (isset($_POST['phone'])) ? $_POST['phone'] : '';
		?>
        <p>
            <label for="phone"><?php _e('Phone',$mcdomain) ?><br />
                <input type="text" name="phone" id="phone" class="input" value="<?php echo esc_attr(stripslashes($phone)); ?>" size="25" /></label>
        </p>
		<?php	
		
		$phone_extension = (isset($_POST['phone_extension'])) ? $_POST['phone_extension'] : '';
		?>
        <p>
            <label for="phone_extension"><?php _e('Phone Extension',$mcdomain) ?><i><small>(Optional)</small></i><br />
			<input type="text" name="phone_extension" id="phone_extension" class="input" value="<?php echo esc_attr(stripslashes($phone_extension)); ?>" size="25"></label>
		</p>
		<p>
			<label for="terms_acceptance">
				<?php _e('You must click on ') ?><a href="#" id="view_terms"><?php _e('Terms',$mcdomain) ?></a> <?php _e('to view and accept the terms before registering for access to this site.') ?><br/>
			</label>
		</p>
		<div id="terms" title="<?php echo $termsdialogtitle; ?>" style="display:none;">
		<?php
			if (strlen($terms) > 0) {
				echo $terms;
			} else {
				echo '<p>&nbsp;</p>';
			}
		?>
		</div>
		<?php
}

 //2. Add validation. In this case, we make sure first_name is required.
function mailchimp_registration_errors ($errors, $sanitized_user_login, $user_email) {

    if ( empty( $_POST['first_name'] ) )
        $errors->add( 'first_name_error', __('<strong>ERROR</strong>: You must include a first name.',$mcdomain) );

    if ( empty( $_POST['last_name'] ) )
        $errors->add( 'last_name_error', __('<strong>ERROR</strong>: You must include a last name.',$mcdomain) );

    if ( empty( $_POST['phone'] ) )
        $errors->add( 'phone_error', __('<strong>ERROR</strong>: You must include a phone number.',$mcdomain) );
	
	//if (validate_phone_number($_POST['phone']) === false) {
	//	$errors->add('phone_error', __('<strong>ERROR</strong>: You must specify the phone in the proper format.',$mcdomain));
	//}

    return $errors;
}

//3. Finally, save our extra registration user meta.
add_action('user_register', 'mailchimp_user_register');
function mailchimp_user_register ($user_id) {
    if ( isset( $_POST['first_name'] ) )
        update_user_meta($user_id, 'first_name', $_POST['first_name']);
    if ( isset( $_POST['last_name'] ) )
        update_user_meta($user_id, 'last_name', $_POST['last_name']);
    if ( isset( $_POST['phone'] ) )
        update_user_meta($user_id, 'phone', $_POST['phone']);
    if ( isset( $_POST['phone_extension'] ) )
        update_user_meta($user_id, 'phone_extension', $_POST['phone_extension']);

	$password = wp_generate_password();
	wp_set_password( $password, $user_id );
	
	require_once dirname(__FILE__).'/inc/Mailchimp.class.php';
	require_once dirname(__FILE__).'/inc/config.inc.php'; //contains apikey
	
	$apikey = get_option('mailchimp_apikey');
	$listId = get_option('mailchimp_listid');
	
	$MailChimp = new MailChimp($apikey);
	$result = $MailChimp->call('lists/subscribe', array(
            'id'                => $listId,
            'email'             => array('email'=>$_POST['user_email']),
            'merge_vars'        => array('FNAME'=>$_POST['first_name'], 
										 'LNAME'=>$_POST['last_name'],
										 'PASSWORD'=>$password,
										 'PHONE'=>$_POST['phone'],
										 'PHONEEXT'=>$_POST['phone_extension'],
										 'USERNAME'=>$_POST['user_login']),
            'double_optin'      => false,
            'update_existing'   => true,
            'replace_interests' => false,
            'send_welcome'      => true,
        ));

	if (is_array($result) && isset($result['status'])) {
		wp_die($result['error']);
		exit;
	}
}

function validate_phone_number($phoneNumber)
{    
    //Check to make sure the phone number format is valid 
    if (preg_match('/^\(\d{3}\) \d{3}-\d{4}\$/', $phoneNumber)) {
		return true;
    } else {
        return false;
    }
}

add_action('login_head', 'mailchimp_custom_login');

function mailchimp_custom_login() {
	if (file_exists(get_bloginfo('template_directory').'/logo.png')) {
		echo '<style type="text/css">
		h1 a { background-image: url('.get_bloginfo('template_directory').'/logo.png) !important;}
		</style>';
	}
}

add_action('admin_menu', 'mailchimp_admin_actions'); 

function mailchimp_admin_actions() {
	add_options_page('Mailchimp As A Registration', 'Mailchimp As A Registration', 'manage_options', 'mailchimp_admin', 'mailchimp_admin');
}

function mailchimp_admin() {
	require_once dirname(__FILE__).'/inc/MCAPI.class.php';
	require_once dirname(__FILE__).'/inc/config.inc.php'; //contains apikey
	
	if (isset($_POST['savemcsettings'])) {
		if ($_POST['mcapi'] != '') {
			update_option('mailchimp_apikey',$_POST['mcapi']);
		} else {
			delete_option('mailchimp_apikey');
		}
		if (isset($_POST['list']) && count($_POST['list']) > 0) {
			$list = $_POST['list'];
			update_option('mailchimp_listid',$list[0]);
		} else {
			delete_option('mailchimp_listid');
		}
		if (isset($_POST['terms']) && strlen($_POST['terms']) > 10) {
			$terms = $_POST['terms'];
			update_option('registration_terms',$terms);
		} else {
			delete_option('registration_terms');
		}
		if ($_POST['termsdialogtitle'] != '') {
			update_option('registration_terms_dialog_title',$_POST['termsdialogtitle']);
		} else {
			delete_option('registration_terms_dialog_title');
		}
	}
	
	if (get_option('mailchimp_apikey') != '') {
		$apikey = get_option('mailchimp_apikey');
	} else {
		$apikey = '';
	}
	
	if (get_option('registration_terms') != '') {
		$terms = get_option('registration_terms');
	} else {
		$terms = '';
	}
	
	if (get_option('registration_terms_dialog_title') != '') {
		$termsdialogtitle = get_option('registration_terms_dialog_title');
	} else {
		$termsdialogtitle = '';
	}
	
?>
	<h1>Configuration</h1>
	<form method="post">
		<h3>Mailchimp API:</h3>
		<input type="text" name="mcapi" id="mcapi" value="<?php echo $apikey?>" size="<?php echo strlen($apikey) + 80 ?>"><small><i>Get your API <a href="http://mailchimp.com" target="_blank">here</a>, [Account|Extras|API keys] opens a new window</i></small><br/>
		<h3>Terms (<small><i>Create Terms <a href="http://termsfeed.com/" target="_blank">here</a>, opens a new window</i></small>):</h3>
		Terms Dialog Title:<input type="text" name="termsdialogtitle" id="termsdialogtitle" value="<?php echo $termsdialogtitle ?>" size="50"><br/>
<?php
	wp_editor( $terms, 
            'content-id', 
            array( 'textarea_name' => 'terms', 
                           'media_buttons' => false, 
                           'tinymce_adv' => array( 'width' => '100', 
                           'theme_advanced_buttons1' => 'formatselect,forecolor,|,bold,italic,underline,|,bullist,numlist,blockquote,|,justifyleft,justifycenter,justifyright,justifyfull,|,link,unlink,|,spellchecker,wp_fullscreen,wp_adv' ) 
                 ) 
	);

	if ($apikey != '') {
		$api = new MCAPI($apikey);
		$lists = $api->lists();
		$campaigns = $api->campaigns();	
		if (is_array($lists) && 
			is_array($campaigns) && 
			count($lists) > 0 && 
			count($campaigns) > 0) {
			update_option('mailchimp_registration','on');
		} else {
			update_option('mailchimp_registration','off');
		}
	}

	$listId = get_option('mailchimp_listid');
	
	echo '<h3>Lists</h3>';
	echo '<table><th>ID</th><th>Name</th>';
	if (is_array($lists)) {
		foreach($lists['data'] as $list) {
			echo '<tr><td><input type="radio" name="list[]" id="list[]" value="'.$list['id'].'" '.($list['id']==$listId ? 'checked': '').'>'.$list['id'].'</td><td>'.$list['name'].'</td></tr>';
		}
	} else {
		echo '<tr><td colspan="2">No lists found, or API key is not defined</td></tr>';
	}
	echo '</table>';
	
	echo '<h3>Campaigns</h3>';
	echo '<table><th>ID</th><th>List ID</th><th>Title/Name</th>';
	if (is_array($campaigns)) {
		foreach($campaigns['data'] as $campaign) {
			echo '<tr><td>'.$campaign['id'].'</td><td>'.$campaign['list_id'].'</td><td>'.$campaign['title'].'</td></tr>';
		}
	} else {
		echo '<tr><td colspan="3">No campaigns found, or API key is not defined</td></tr>';
	}
	echo '</table>';
?>
	<br/>
	<input type="submit" name="savemcsettings" id="savemcsettings" value="Save">
	</form>
<?php
}


?>
