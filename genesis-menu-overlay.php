<?php
/*
 Plugin Name: Genesis Navigation Menu Overlay
 Description: This plugin creates a full screen menu overlay
 Version: 1.0
 Author: Angie Vale
 Author URI: http://www.purplebabyhippo.co.uk
 Text Domain: : genesis-menu-overlay
License: GPLv2
*/



defined( 'WPINC' ) or die;

register_activation_hook( __FILE__, 'pbh_activation_check' );
/**
 * This function runs on plugin activation. It checks to make sure the required
 * minimum Genesis version is installed. If not, it deactivates itself.
 */
function pbh_activation_check() {
	$latest = '2.0';
	$theme_info = wp_get_theme( 'genesis' );

	if ( ! function_exists('genesis_pre') ) {
		deactivate_plugins( plugin_basename( __FILE__ ) ); // Deactivate plugin
		wp_die( sprintf( __( 'Sorry, you can\'t activate %1$sGenesis mobile menu overlay unless you have installed the %3$sGenesis Framework%4$s. Go back to the %5$sPlugins Page%4$s.', 'genesis-menu-overlay' ), '<em>', '</em>', '<a href="http://www.studiopress.com/themes/genesis" target="_blank">', '</a>', '<a href="javascript:history.back()">' ) );
	}

	if ( version_compare( $theme_info['Version'], $latest, '<' ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) ); // Deactivate plugin
		wp_die( sprintf( __( 'Sorry, you can\'t activate %1$sGenesis mobile menu overlay unless you have installed the %3$sGenesis %4$s%5$s. Go back to the %6$sPlugins Page%5$s.', 'genesis-menu-overlay' ), '<em>', '</em>', '<a href="http://www.studiopress.com/themes/genesis" target="_blank">', $latest, '</a>', '<a href="javascript:history.back()">' ) );
	}
}

add_action('admin_init', 'pbh_deactivate_check');
/**
 * This function runs on admin_init and checks to make sure Genesis is active, if not, it 
 * deactivates the plugin. This is useful for when users switch to a non-Genesis themes.
 */
function pbh_deactivate_check() {
    if ( ! function_exists('genesis_pre') ) {
		deactivate_plugins( plugin_basename( __FILE__ ) ); // Deactivate plugin
    }
} 


//* Load scripts
add_action( 'wp_enqueue_scripts', 'pbh_load_overlay_scripts' );
function pbh_load_overlay_scripts() { 
	wp_enqueue_script( 'modernizr', plugin_dir_url( __FILE__ ) . '/includes/js/modernizr.custom.js' );
	wp_enqueue_script( 'classie', plugin_dir_url( __FILE__ ) . '/includes/js/classie.min.js', '', '', true );
	wp_enqueue_script( 'global', plugin_dir_url( __FILE__ ) . '/includes/js/global.min.js' , array( 'jquery' ), '1.0.0', true ); 
	
}


//* Add mobile menu icon
add_action( 'genesis_header', 'dl_nav_toggle', 8 );
function dl_nav_toggle() {
	?>
	
 	<span class="nav-label" data-target="#navPop">Menu</span>
	<button type="button" id="navbar-toggle" data-target="#navPop" aria-label="Menu" aria-controls="menu-primary-navigation" aria-expanded="false">
	<span class='sr-text'><?php echo __( 'Toggle Menu', 'genesis-menu-overlay' );?></span>
	<span class="icon-bar"></span>
	<span class="icon-bar"></span>
	<span class="icon-bar"></span>
	</button>
	<?php 
	}



//* Wrap .nav-primary in overlay div
add_filter( 'genesis_do_nav', 'genesis_child_nav', 10, 3 );
function genesis_child_nav($nav_output, $nav, $args) {

	return '<div class="nav-overlay overlay-simplegenie" aria-hidden="true"><div class="nav-header"><button type="button" id="nav-button" class="overlay-close" aria-label="Close Navigation">
	<span class="sr-text">Close Menu</span>X</button></div>' . $nav_output . '</div>';

}


