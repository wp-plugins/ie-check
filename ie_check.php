<?php
/**
 * @package IE Check
 * @version 0.9.1
 */
/*
Plugin Name: IE Check
Plugin URI: http://wordpress.org/plugins/ie-check/
Description: Checks if the browser is an older version of Internet Explorer, releases rage if it's IE<11
Author: José Marques
Version: 0.9
Author URI: http://feedingtherobots.com
License: GPL2 
*/



// Set-up Action and Filter Hooks
register_activation_hook(__FILE__, 'iecheck_add_defaults');
register_deactivation_hook(__FILE__, 'iecheck_delete_plugin_options');
register_uninstall_hook(__FILE__, 'iecheck_delete_plugin_options');
add_action('admin_init', 'iecheck_init' );
add_action('admin_menu', 'iecheck_add_options_page');
//add_filter( 'plugin_action_links', 'iecheck_plugin_action_links', 10, 2 );


// Init plugin options to white list our options
function iecheck_init(){
	register_setting( 'iecheck_plugin_options', 'iecheck_options', 'iecheck_validate_options' );
}

// Define default option settings
function iecheck_add_defaults() {
	$tmp = get_option('iecheck_options');
    if(($tmp['chk_default_options_db']=='1')||(!is_array($tmp))) {
		delete_option('iecheck_options'); // so we don't have to reset all the 'off' checkboxes too! (don't think this is needed but leave for now)
		$arr = array(	'title' => 'Wow',	
						'show_browser_age' => 'true',					
						'browser_page_URI' => 'http://browsehappy.com/',
						'message' => 'Using an outdated browser makes your computer unsafe.	 For the best experience on the web, please upgrade your browser.',
						'display_mode' => 'header',
						'last_supported_version' => 11
		);
		update_option('iecheck_options', $arr);
	}
}

// Delete options table entries ONLY when plugin deactivated AND deleted
function iecheck_delete_plugin_options() {
	delete_option('iecheck_options');
}


// Add menu page
function iecheck_add_options_page() {
	add_options_page('Configure', 'IE Check', 'manage_options', __FILE__, 'iecheck_render_form');
}


// Render the Plugin options form
// TODO: folder name cannot be hardcoded
function iecheck_render_form() {

	?>
	
	<link rel="stylesheet" type="text/css" href="<?php echo plugins_url(); ?>/IE-Check/plugin.css" />
	<div class="wrap ie_check">
		
		<!-- Display Plugin Icon, Header, and Description -->
		<div class="icon32" id="icon-options-general"><br/></div>
		<h2>IE Check configuration</h2>
		<p>In case you want to change the default text, here are some options for you!</p>
		

		<!-- Beginning of the Plugin Options Form -->
		<form method="post" action="options.php" >
			<?php settings_fields('iecheck_plugin_options'); ?>
			<?php $options = get_option('iecheck_options'); ?>


			<p>
				<label>IE version supported</label>
				<select name='iecheck_options[last_supported_version]'>
					<option value='7' <?php selected(7, $options['last_supported_version']); ?>>Internet Explorer 7</option>
					<option value='8' <?php selected(8, $options['last_supported_version']); ?>>Internet Explorer 8</option>
					<option value='9' <?php selected(9, $options['last_supported_version']); ?>>Internet Explorer 9</option>
					<option value='10' <?php selected(10, $options['last_supported_version']); ?>>Internet Explorer 10</option>
					<option value='11' <?php selected(11, $options['last_supported_version']); ?>>Internet Explorer 11</option>							
				</select>
			</p>

			
			<p>
				<label>Display mode</label>
				<select name='iecheck_options[display_mode]'>
					<!-- full screen is too disruptive -->
					<!--<option value='fullScreen' <?php selected('fullScreen', $options['display_mode']); ?>>full screen</option>-->
					<option value='header' <?php selected('header', $options['display_mode']); ?>>header</option>
					<option value='footer' <?php selected('footer', $options['display_mode']); ?>>footer</option>							
				</select>
			</p>

			<p>
				<label>Browser page URI</label>
				<input type="url" size="80" name="iecheck_options[browser_page_URI]" value="<?php echo $options['browser_page_URI']; ?>" />
			</p>


			<p>
				<label>Title</label>
				<input type="text" size="50" name="iecheck_options[title]" value="<?php echo $options[ 'title' ]; ?>" />
			</p>

			<p>
				<label>Display browser age?</label>				
				
				<input name="iecheck_options[show_browser_age]" type="checkbox" value="true"  <?php if ($options[ 'show_browser_age' ] == 'true') echo 'checked="checked"'; ?> />
			</p>
						
			
			<p class="wysiwyg">
				<label>Message</label>
			</p>
				<?php
					$args = array("textarea_name" => "iecheck_options[message]","media_buttons" => false, "teeny" => true,"textarea_rows" =>3);
					wp_editor( $options['message'], "iecheck_options[message]", $args );
				?>
				<span></span>
				
			

				

			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
			
		</form>

	</div>
	<?php	
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function iecheck_validate_options($input) {
	 // strip html from textboxes
	$input['title'] =  wp_filter_nohtml_kses($input['title']); 
	//$input['browserPageURI'] =  wp_filter_nohtml_kses($input['browserPageURI']); 

	return $input;
}


/**
 * @param string $browser_version the browser version
 * @param int $years number of years since browser was released
 * @param string $years_label singular or plural years label
 * @param string $browser_version the browser version
*/

function ie_check(){

	$options = get_option('iecheck_options');

	$browser_version = 1;
	$years = 0;
	$years_label = " year";

	preg_match('/MSIE (.*?);/', $_SERVER['HTTP_USER_AGENT'], $matches);

	
	if (count($matches)>1||true){
	  //Then we're using IE
	  $browser_version = (int) $matches[1];

	  switch(true){
	    case ($browser_version<=5):
			$years = date("Y") - 2000;
			break;
		case ($browser_version<=6):
			$years = date("Y") - 2001;
			break;
		case ($browser_version<=7):
			$years = date("Y") - 2006;
			break;
		case ($browser_version<=8):
			$years = date("Y") - 2009;
			break;	
		case ($browser_version<=9):
			$years = date("Y") - 2011;
			break;	
		case ($browser_version<=10):
			$years = date("Y") - 2012;
			break;	
		default:
			$years = date("Y") - 2013;
			break;	
	  }

	if($options['last_supported_version']>$browser_version){

    	$years_label = " years";

    	//this should be the link to the plugin folder, if the path is not the stand it will not work

    	echo '<link href="http://fonts.googleapis.com/css?family=Noto+Sans:400,700" rel="stylesheet" type="text/css">';

    	echo '<link rel="stylesheet" type="text/css" href="'.plugins_url().'/ie-check/ie_check.css" />';

		echo '<div id="browser-warning" class="browser-feedback '.$options['display_mode'].'">';
		echo '<h3>'.$options['title'].'</h3>';
		
		if($options[ 'show_browser_age' ]=='true'){
			echo '<p>You are using Microsoft Internet Explorer '.$browser_version.', which is over '.$years.$years_label.' old! </p>';
		}
		

		echo '<div class="message">'.$options['message'].'</div>';

		echo '<p class="buttons"><a href="'.$options['browser_page_URI'].'" class="upgrade" target="_blank">Upgrade</a>';

		
		echo '<script type="text/javascript" >
    			function hide_warning(){			    				    				
    				document.getElementById("browser-warning").className  = document.getElementById("browser-warning").className + " hidden";	
    				document.getElementById("browser-warning").style.display="none";				
    			}

    			
    		</script>';
		echo 'or  <a href="javascript:hide_warning();" >continue to website</a>';

	    	
		
			
		echo '</p></div>';

	}



	}

	


}

?>