<?php
/*
Plugin Name: Extended User Profile
Plugin URI: http://horttcore.de/wordpress/extended-user-profile
Description: Extend the user profile
Author: Ralf Hortt
Version: 1.3.1
Author URI: http://www.horttcore.de/
*/ 

//======================================
// @Description: Plugin Option Menu
// @Require: 
// @Optional: 
// @Return: 
function eup_management(){?>
		<h2>Extended User Profile</h2>
		<p>Hello World</p>
	<?php
}

//======================================
// @Description: Custom user profile
function eup_custom(){
	$meta = eup_get_extended_profile();
	?>
	<h3><?php _e('Extended User Profile'); ?></h3>
	
	<table class="form-table" id="clonehere"><?php
	if ($meta) {
	 	foreach($meta as $key => $value) {?>
		<tr id="tr_<?php echo $key; ?>">
			<th><label for="<?php echo $key ?>"><?php echo str_replace('eup_','',$key) ?></label></th>
			<td>
				<input type="text" value="<?php echo $value ?>" id="<?php echo $key ?>" name="<?php echo $key ?>" /> 
				<a class="remove_meta" href="#" onClick="jQuery('#tr_<?php echo $key; ?>').remove(); return false;" rel="<?php echo $key ?>"title="<?php _e('Delete'); ?>"></a>
			</td>
		</tr>
		<?php
		}
	}
	?>
	</table>
	<p class="form-table">
		<input type="text" name="eup_meta" id="eup_meta" /> <input type="text" name="eup_value" id="eup_value" /> <span id="eup_addmeta" class="button"><?php _e('Add Meta'); ?></span>
	</p>
	<?php
}

//======================================
// Description: The form to extend the profile
function eup_static() {
global $wpdb;
	// Loads the Usermetadata into $meta array - !DO NOT CHANGE THE NEXT LINES!
	$meta = eup_get_extended_profile();
	$static_fields = array('eup_input');
	// ---------- You can edit the next lines ?>
	<h3><?php _e('Extended User Profile'); ?></h3>
	
	<table class="form-table">
		<tr>
			<th><label for="eup_input">Label</label></th>
			<td><input type="text" name="eup_input" id="eup_input" value="<?php echo $meta->eup_input; ?>" /></td>
		</tr>
		<?php
		if ($meta) {
		 	foreach($meta as $key => $value) {
				if (!in_array($key, $static_fields)) {
				?>
				<tr id="tr_<?php echo $key; ?>">
					<th><label for="<?php echo $key ?>"><?php echo str_replace('eup_','',$key) ?></label></th>
					<td>
						<input type="text" value="<?php echo $value ?>" id="<?php echo $key ?>" name="<?php echo $key ?>" /> 
						<a class="remove_meta" href="#" onClick="jQuery('#tr_<?php echo $key; ?>').remove(); return false;" rel="<?php echo $key ?>"title="<?php _e('Delete'); ?>"></a>
					</td>
				</tr>
				<?php
				}
			}
		}
		?>
	</table>	
	<? // ---------- No more editing
}

//======================================
// @Description: 
// @Require: 
// @Optional: 
// @Return: 
function eup_dynamic(){
	// Loads the Usermetadata into $meta array - !DO NOT CHANGE THE NEXT LINES!
	$meta = eup_get_extended_profile();
	?>
	<h3><?php _e('Extended User Profile'); ?></h3>
	
	<table class="form-table" id="clonehere">
		<tr>
			<th><label for="input">Label</label></th>
			<td><input type="text" name="input" id="input" value="<?php echo $meta->input; ?>" /></td>
		</tr>
	</table>
	<p class="form-table">
		<input type="text" name="eup_meta" id="eup_meta" /> <input type="text" name="eup_value" id="eup_value" /> <span id="eup_addmeta" class="button"><?php _e('Add Meta'); ?></span>
	</p>
	
	<?php
}


//======================================
// @Description: Get a user meta object
// @Param: int $id user_ID 
// @Param: bool $default_profile - include all user meta 
// @Return: obj $meta all meta_values
function eup_get_extended_profile($id = ""){
global $wpdb, $user_ID;
	if (preg_match('&profile.php&', $_SERVER['REQUEST_URI'])) {
		$id = $user_ID;
	}
	elseif($_GET['user_id']) {
		$id = $_GET['user_id'];
	}
	
	$meta = get_usermeta($id,'eup_profile',TRUE);
	return $meta;
}

//======================================
// @Description: Extended User Profile Options
// @Return: array $options - options array
function eup_options(){
	$options = array(
		'eup_profile' => 'custom', // custom, static, build
		'eup_profile_max' => '0', //
	);
	return $options;
}

//======================================
// Description: Hack to save the metadata in $wpdb->usermeta ( Called in wp-admin/users.php and wp-admin/profile.php )
function eup_save(){
global $wpdb, $user_ID;
	if (preg_match('&profile.php&', $_SERVER['REQUEST_URI'])) {
		$id = $user_ID;
	}
	elseif($_GET['user_id']) {
		$id = $_GET['user_id'];
	}
	foreach($_POST as $key => $value) {
		if (preg_match('&eup_&', $key) && $key != 'eup_value' && $key != 'eup_meta' && $value) {
			$profile[$key] = $value;
		}
	}
	update_usermeta($id, 'eup_profile', $profile);
}

//======================================
// Description: Hack to save the metadata in $wpdb->usermeta ( Called in wp-login.php )
function eup_registration_save() {
global $wpdb;

}

//======================================
// @Description: Runs on plugin activation
function eup_install(){
	$options = eup_options();
	foreach($options as $key => $value) {
		add_option($key,$value);
	}
}

//======================================
// @Description: Runs on plugin deactivation
function eup_deinstall(){
	$options = eup_options();
	foreach($options as $key => $value) {
		delete_option($key,$value);
	}
}

//======================================
// Description: Getting all relevant usermeta data
// Param: str user; - UserID
// Param: Experimental
function call_usermeta($id="", $run="") {
global $wpdb,$user_ID, $author_name;
	/** DEPRACTED **/
}

//======================================
// Description: Script should ignore these data
function eup_ignore(){
	
}

//======================================
// @Description: jQuery Script for Extended User Profile
function eup_adminhead(){?>
	<style type="text/css">
	.remove_meta {
		background: url(./images/xit.gif) left center no-repeat;
		padding: 4px 10px 0 0;
	}
	.remove_meta:hover {
		background: url(./images/xit.gif) right center no-repeat;
		padding: 4px 10px 0 0;
	}
	</style>
	<script type="text/javascript">
		jQuery(document).ready(function(){		
			function add_meta(){
				if (jQuery('#eup_meta').val()){
					jQuery('#clonehere').append('<tr id="tr_' + jQuery('#eup_meta').val() + '"><th><label for="eup_' + jQuery('#eup_meta').val() + '">' + jQuery('#eup_meta').val() + '</label></th><td><input type="text" id="eup_' + jQuery('#eup_meta').val() + '" name="eup_' + jQuery('#eup_meta').val() + '" value="' + jQuery('#eup_value').val() +'" /> <a class="remove_meta" href="#" onClick="jQuery(\'#tr_'+jQuery('#eup_meta').val()+'\').remove(); return false;" title="<?php _e('Delete'); ?>"></a></td></tr>');
					jQuery('#eup_meta').attr('value','').focus();
					jQuery('#eup_value').attr('value','');
				}
			}	
			jQuery('#eup_addmeta').click(function(){
					add_meta();
			});
			jQuery('#eup_value').keypress(function(e){
				if (e.which == 13) {
					add_meta();
				}
			});
			// jQuery("#clonehere").sortable();
		});
	</script><?php
}

//======================================
// WP HOOKS

// Install / Deinstall Hooks
register_activation_hook(__FILE__, 'eup_install');
register_deactivation_hook(__FILE__, 'eup_deinstall');

// Which Profile should be included
$eup_profile = get_option('eup_profile');
if ($eup_profile == 'custom') {
	add_action('show_user_profile', 'eup_custom');
	add_action('edit_user_profile', 'eup_custom');
}
elseif ($eup_profile == 'static') {
	add_action('show_user_profile', 'eup_static');
	add_action('edit_user_profile', 'eup_static');
}
elseif ($eup_profile == 'dynamic') {
	add_action('show_user_profile', 'eup_dynamic');
	add_action('edit_user_profile', 'eup_dynamic');
}

add_action('profile_update', 'eup_save');
add_action('admin_head', 'eup_adminhead')

?>