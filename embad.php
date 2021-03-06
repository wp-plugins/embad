<?php
/*
Plugin Name: EmbAd
Plugin URI: http://www.embad.com
Description: This plugin will automatically add your EmbAd script to your website pages.
Author: EmbAd
Version: 1.0
Author URI: http://www.embad.com
*/

//install/uninstall function calls
register_activation_hook( __FILE__, 'embad_install' );
register_uninstall_hook( __FILE__, 'embad_uninstall' );

add_action( 'admin_menu', 'embad_pages' );
add_action( 'wp_footer', 'embadScript' );

function embad_install() {
	add_option( 'embad_status', '1' );
}

function embad_uninstall() {
	delete_option( 'embad_status' );
	delete_option( 'embad_pid' );
	delete_option( 'embad_wsid' );
}

$pluginpath = plugins_url( '/', __FILE__ );

// action function for above hook
function embad_pages() {
    add_options_page( 'EmbAd Ads', 'EmbAd Settings', 'manage_options', 'embad-admin', 'embadsetting_page' );
}

//plugin settings page
function embadsetting_page() {

	//save plugin settings
	if( isset( $_POST['btnSave'] ) ) {

		//check nonce for security
		check_admin_referer( 'embad_plugin_save' );

		$embad_status = ( isset( $_POST['embad_status'] ) ) ? $_POST['embad_status'] : 0;
		
		update_option( 'embad_status', absint( $embad_status ) );
		update_option( 'embad_pid', absint( $_POST['embad_pid'] ) );
		update_option( 'embad_wsid', absint( $_POST['embad_wsid'] ) );
	
		echo '<div id="message" class="updated">Settings saved successfully</div>';

	}

	//load setting values
	$embad_status = get_option( 'embad_status' );
	$embad_pid = get_option( 'embad_pid' );
	$embad_wsid = ( get_option( 'embad_wsid' ) ) ? get_option( 'embad_wsid' ) : 0;
	
?>
<style>
.explanation {
	width:100%;
}
.input-wrapper {
	padding:10px 20px;
}
.input-title, .input-field {
	display:inline-block;
}
.input-title {
	min-width:120px;
	vertical-align:top;
	margin-right:25px;
}
.input-group {
	padding:20px;
}
</style>
<form method="post" name="frm_embad" id="frm_embad">
	<?php wp_nonce_field( 'embad_plugin_save' ); ?>
  <input type="hidden" id="embadid" name="embadid" value="" />
	<div style="float:left; width:40%;"><table border="1" width="100%">
	<div class="title"><div id="icon-options-general" class="icon32"> </div><h2>EmbAd Official Plugin</h2></div>
	<div class="explanation">
	        <div>This plugin will automatically add <a href="http://www.embad.com">embAd</a> script to your website pages</div>
	        <p class='explation'>
		        To use it simply register your website with <a href="http://www.embad.com/user/register">embAd</a>, Which will provide you with two identifying codes WSID and PID,
		        copy and paste them to the input fields below.
	        </p>
	</div>
	<div class="input-data input-wrapper">
		<div class="registeration-data  input-group" >
			<div id='publishr-data  input-wrapper' >
	            <div class="input-title"><label for="pid">Publisher ID :</label></div>
	            <div class="input-field">
					<input type="text" name="embad_pid" id="embad_pid"  value="<?php echo esc_attr( $embad_pid ); ?>"/><br />
					<span class="small_txt">Please enter your EmbAd pid</span>
				</div>
			</div>
		
          
			<div id="website-data  input-wrapper">
				<div class="input-title"><label for="wsid">Website ID :</label></div>
				<div class="input-field">
					<input type="text" name="embad_wsid" id="embad_wsid" value="<?php echo esc_attr( $embad_wsid ); ?>"/><br />
					<span class="small_txt">Please enter your EmbAd wsid</span>
				</div>
			</div>
		</div>
		
		<div class='active-data input-wrapper '>
            <div class="input-title">EmbAd Ads:</div>
			<div class="input-field">
				<input type="radio" name="embad_status" value="1" <?php checked( $embad_status, 1 ); ?> /> On <br />
				<input type="radio" name="embad_status" value="0" <?php checked( $embad_status, 0 ); ?> /> Off
			</div>
        </div>
    </div>
     
	<div class="form-footer">
		<input type="submit" class="button-primary" name="btnSave" value="Save Settings" />
	</div>
  </div>

</form>
<?php	
}

//add the script in footer
function embadScript() {
	//to check the ads enable or disable
	$status = get_option( 'embad_status' );
	
	if( $status == 1 ) {	
		$pid = absint( get_option( 'embad_pid' ) );
		$wsid = absint( get_option('embad_wsid') );
		echo '<script type="text/javascript" src="http://js.embad.com/build/Initializer.js?pid='.$pid.'&wsid='.$wsid.'"></script>';
	}	
}
?>
