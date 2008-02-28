<?php
/*
Plugin Name: Extended User Profile
Plugin URI: http://extended-user-profile.horttcore.de
Description: Extend your the userprofil - * PHP4+ or Function Library Plugin from my blog.
Author: Ralf Hortt
Version: 1.1
Author URI: http://www.horttcore.de/
*/ 

/*
 * Put this file in wp-content/plugins/ folder
 * Edit the formular in the 'extented_user_profil' function
 * Activate it in WP Backend
 * To get meta data simply do '<?php $meta = call_usermeta(); ?>'
*/

#add_action('register_form', extend_user_profil); // Remove "#" if user can fill out the form on registration.
#add_action('register_post', extended_user_profil_registration_save); // Remove "#" if user can fill out the form on registration.

add_action('show_user_profile',extend_user_profil); // Do Not Remove This Line
add_action('edit_user_profile',extend_user_profil); // Do Not Remove This Line
add_action('profile_update',extended_user_profil_save); // Do Not Remove This Line



//======================================
// Description: The form to extend the profile
Function extend_user_profil() {
global $wpdb;
	
	// Loads the Usermetadata into $meta array - !DO NOT CHANGE THE NEXT LINES!
	$meta = call_usermeta('','br2nl');
		
	// ---------- You can edit the next lines ?>
	
	<fieldset>
		<legend>Title</legend>

		<p><label for="info">Label:<br />
		<input type="text" name="info" value="<?php echo $meta->info;?>"  /></label></p>

		<p><label for="info2">Label2<br />
		<textarea name="info2"><?php echo $meta->info2?></textarea></label></p>
	</fieldset>
	
	<? // ---------- No more editing
}



//======================================
// Description: Hack to save the metadata in $wpdb->usermeta ( Called in wp-admin/users.php and wp-admin/profile.php )
Function extended_user_profil_save(){
global $wpdb;
	
	// Filtering queries which were already sent by WP
	$ignore = extended_user_profil_ignore();

	// Switch between users.php and profil.php
	if ( $_POST['user_id'] ) {
		$id = $_POST['user_id'];
	}
	else{ $id = $_POST['checkuser_id']; }
	
	// Update Meta data
	foreach($_POST as $key => $value) {
			if	(!in_array($key,$ignore)) {
					$value = strip_tags($value); // Remove HTML and PHP tags
					#$value = htmlentities($value); // converting characters - might fix undetected code of strip_tags();
					update_usermeta($id ,$key ,$value);
			}				
	}
}



//======================================
// Description: Hack to save the metadata in $wpdb->usermeta ( Called in wp-login.php )
Function extended_user_profil_registration_save() {
global $wpdb;
	$ignore = extended_user_profil_ignore();
	
	// Getting next Users ID
	$sql = "SHOW TABLE STATUS FROM ".DB_NAME." LIKE '$wpdb->users'";
	$row = $wpdb->get_row($sql);
	$id = $row->Auto_increment;
		
	// Update Meta data
	foreach($_POST as $key => $value) {
			if	(!in_array($key,$ignore)) {
					$value = strip_tags($value); // Remove HTML and PHP tags
					#$value = htmlentities($value); // converting characters - might fix undetected code of strip_tags();										
					update_usermeta($id,$key,$value);
			}				
	}
}



//======================================
// Description: Getting all relevant usermeta data
// Param: str user; - UserID
// Param: Experimental
Function call_usermeta($id="", $run="") {
global $wpdb,$user_ID, $author_name;

$ignore = extended_user_profil_ignore();
	
	// Get Userdata by ID
	if (!empty($id)) {
		$curauth = get_userdata(intval($id));
	}
	// Get Userdata by Author
	elseif ($author_name) {
		$curauth = get_userdatabylogin($author_name);
	}
	// Get Userdata by 'MyProfil'
	elseif (is_admin() && !$_GET['user_id']) {
		$curauth = get_userdata(intval($user_ID));
	}
	// Get Userdata by UserID
	elseif ($_GET['user_id']) {
		$curauth = get_userdata(intval($_GET['user_id']));
	}
	// Building Metadata Array
	if ($curauth) {
		foreach ($curauth as $key => $value) {
			if(!in_array($key,$ignore)) {
				$meta->$key = $curauth->$key;
				if (!$run){
					$meta->$key = nl2br($meta->$key);
				}
			}
		}
		return $meta;
	}
	else {
		return false;
	}
}



//======================================
// Description: Script should ignore these data
// Param: Which part should be ignored; -default $trash
// Note: Important if other Plugins where run on Userprofil or Registration
// Develop: Experimental, might throw it out as it's just needed for saving into DB
Function extended_user_profil_ignore($mode = "trash"){
	
	$trash = array( 'wpsubmit', 'wp_http_referer', 'action', 'user_login', 'user_pass', 'user_nicename', 'user_registered', 'user_activation_key', 'user_status', 
					'rich_editing', 'wp_capabilities', 'ID', '_wpnonce', 'wp_user_level', '_wp_http_referer', 'from', 'checkuser_id', 'submit', 'email','user_level',
					'user_description', 'user_url', 'user_lastname', 'user_firstname', 'user_description');
	
	$meta = array( 'user_email','user_url','display_name','nickname', 'jabber' ,'role' ,'user_id' ,'yim' ,'wp_user_level' ,'user_level', 'description', 'first_name', 'last_name',
					'aim', 'url',);
	
	if ($mode == "all") {
		$ignore = array_merge($trash,$meta);
		return $ignore;
	}
	else{return $trash;}
		
}




//====================================== Fallback: * < PHP5
//====================================== Function: array_diff_key
if	(!function_exists('array_diff_key')) {
	function array_diff_key() {
		$arrs = func_get_args();
		$result = array_shift($arrs);
		foreach ($arrs as $array) {
			foreach ($result as $key => $v) {
				if (array_key_exists($key, $array)) {
					unset($result[$key]);
				}
			}
		}
		return $result;
	}	
}
?>