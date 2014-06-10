<?php
/**
 * Plugin Name: Simple Zippyshare Embed
 * Plugin URI: http://it-maniak.pl
 * Description: Replace all zippyshare links to embed media.
 * Version: 1.2
 * Author: Adam Stachowicz
 * Author URI: http://it-maniak.pl
 * License: GPLv2
 */

/*  Copyright 2014  Adam Stachowicz  (email : saibamenppl@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// activation
register_activation_hook( __FILE__, 'zippy_set_up_options' );

// uninstall
register_uninstall_hook( __FILE__, 'zippy_delete_options' );

// set default zippy values
function zippy_set_up_options() {
    add_option( 'zippydownbutt', 'above' );
    add_option( 'zippyvol', '80' );
    add_option( 'zippywidth', '850' );
    // colors
    add_option( 'zippytext', '#000000' );
    add_option( 'zippyback', '#e8e8e8' );
    add_option( 'zippyplay', '#ff6600' );
    add_option( 'zippywave', '#000000' );
    add_option( 'zippyborder', '#cccccc' );
}

// Cleaning after uninstall
function zippy_delete_options() {
    delete_option( 'zippydownbutt' );
    delete_option( 'zippyvol' );
    delete_option( 'zippywidth' );
    delete_option( 'zippytext' );
    delete_option( 'zippyback' );
    delete_option( 'zippyplay' );
    delete_option( 'zippywave' );
    delete_option( 'zippyborder' );
}

function zippy_replace_links_to_embed( $the_content ) {
    // Searching for zippyshare links and attach it into $matches
    preg_match_all( "#http://www(.*?).zippyshare.com/v/([0-9]*)/file.html#", $the_content, $matches, PREG_SET_ORDER );
    
    if ( $matches ) {
	
	$zippyauto = 'false'; // autoplay - always false
	
	foreach ( $matches as $data ) {
	    // Understanding the $data
	    $zippylink = esc_attr( $data[0] );
	    $zippywww = esc_attr( $data[1] );
	    $zippyfile = esc_attr( $data[2] );
	    
	    // Modify content
	    // Button above
	    if ( esc_attr( get_option( 'zippydownbutt' ) ) == 'above' ) {
		$the_content = str_replace( $zippylink, '<div style="text-align:center;"><a href="'. $zippylink .'"><img align="middle" src="'. plugins_url( '/images/download_button.png', __FILE__ ) .'" /></a></div><br />'
			. '<script type="text/javascript">var zippywww="'. $zippywww .'";var zippyfile="'. $zippyfile .'";var zippytext="'. get_option( 'zippytext' ) .'";var zippyback="'. esc_attr( get_option( 'zippyback' ) ).'";var zippyplay="'. esc_attr( get_option( 'zippyplay' ) ) .'";var zippywidth='. esc_attr( get_option( 'zippywidth' ) ) .';var zippyauto='. $zippyauto .';var zippyvol='. esc_attr( get_option( 'zippyvol' ) ) .';var zippywave = "'. esc_attr( get_option( 'zippywave' ) ) .'";var zippyborder = "'. esc_attr( get_option( 'zippyborder' ) ) .'";</script><script type="text/javascript" src="http://api.zippyshare.com/api/embed_new.js"></script>', $the_content );
	    }
	    // Button under
	    elseif ( esc_attr( get_option( 'zippydownbutt' ) ) == 'under' ) {
		$the_content = str_replace( $zippylink, '<script type="text/javascript">var zippywww="'. $zippywww .'";var zippyfile="'. $zippyfile .'";var zippytext="'. get_option( 'zippytext' ) .'";var zippyback="'. esc_attr( get_option( 'zippyback' ) ).'";var zippyplay="'. esc_attr( get_option( 'zippyplay' ) ) .'";var zippywidth='. esc_attr( get_option( 'zippywidth' ) ) .';var zippyauto='. $zippyauto .';var zippyvol='. esc_attr( get_option( 'zippyvol' ) ) .';var zippywave = "'. esc_attr( get_option( 'zippywave' ) ) .'";var zippyborder = "'. esc_attr( get_option( 'zippyborder' ) ) .'";</script><script type="text/javascript" src="http://api.zippyshare.com/api/embed_new.js"></script>'
			.'<div style="text-align:center;"><a href="'. $zippylink .'"><img align="middle" src="'. plugins_url( '/images/download_button.png', __FILE__ ) .'" /></a></div>', $the_content );
	    }
	    // No download button
	    else {
		$the_content = str_replace( $zippylink, '<script type="text/javascript">var zippywww="'. $zippywww .'";var zippyfile="'. $zippyfile .'";var zippytext="'. get_option( 'zippytext' ) .'";var zippyback="'. esc_attr( get_option( 'zippyback' ) ).'";var zippyplay="'. esc_attr( get_option( 'zippyplay' ) ) .'";var zippywidth='. esc_attr( get_option( 'zippywidth' ) ) .';var zippyauto='. $zippyauto .';var zippyvol='. esc_attr( get_option( 'zippyvol' ) ) .';var zippywave = "'. esc_attr( get_option( 'zippywave' ) ) .'";var zippyborder = "'. esc_attr( get_option( 'zippyborder' ) ) .'";</script><script type="text/javascript" src="http://api.zippyshare.com/api/embed_new.js"></script>', $the_content );
	    }
	}
    }
    // return changed or unchanged content
    return $the_content;
}

// activate plugin when we see the_content
add_action( 'the_content', 'zippy_replace_links_to_embed' );

function zippy_create_menu() {
    // create new options page
    add_options_page( 'Simple Zippyshare Embed options', 'Simple Zippyshare Embed', 'administrator', __FILE__, 'zippy_settings_page' );

    // call register settings function
    add_action( 'admin_init', 'zippy_register_settings' );
}

add_action( 'admin_menu', 'zippy_create_menu' );


function zippy_register_settings() {
    // register settings
    register_setting( 'zippy-settings-group', 'zippydownbutt' );
    register_setting( 'zippy-settings-group', 'zippyvol' );
    register_setting( 'zippy-settings-group', 'zippywidth' );
    register_setting( 'zippy-settings-group', 'zippytext' );
    register_setting( 'zippy-settings-group', 'zippyback' );
    register_setting( 'zippy-settings-group', 'zippyplay' );
    register_setting( 'zippy-settings-group', 'zippywave' );
    register_setting( 'zippy-settings-group', 'zippyborder' );
}

// Localization
function zippy_translations_init() {
    load_plugin_textdomain( 'simple-zippyshare-embed', false, basename( dirname( __FILE__ ) ) . '/languages' );
}

add_action( 'init', 'zippy_translations_init' );

function zippy_settings_page() {
?>
    <div class="wrap">
    <h2>Simple Zippyshare Embed</h2>
    <?php echo __( 'Colors must be in HTML (HEX) format', 'simple-zippyshare-embed' ); ?>

    <form method="post" action="options.php">
	<?php settings_fields( 'zippy-settings-group' ); ?>
	<?php do_settings_sections( 'zippy-settings-group' ); ?>
	<table class="form-table">
	    <tr valign="top">
	    <th scope="row"><?php echo __( 'Download Button', 'simple-zippyshare-embed' ); ?></th>
	    <td><select name="zippydownbutt">
		    <option value="none" <?php selected( esc_attr( get_option( 'zippydownbutt' ) ), 'none' ); ?> ><?php echo __( 'None', 'simple-zippyshare-embed' ); ?></option>
		    <option value="above" <?php selected( esc_attr( get_option( 'zippydownbutt' ) ), 'above' ); ?> ><?php echo __( 'Above', 'simple-zippyshare-embed' ); ?></option>
		    <option value="under" <?php selected( esc_attr( get_option( 'zippydownbutt' ) ), 'under' ); ?> ><?php echo __( 'Under', 'simple-zippyshare-embed' ); ?></option>
		</select></td>
	    </tr>
	    
	    <tr valign="top">
	    <th scope="row"><?php echo __( 'Default volume', 'simple-zippyshare-embed' ); ?></th>
	    <td><input type="number" name="zippyvol" value="<?php echo esc_attr( get_option( 'zippyvol' ) ); ?>" required />%</td>
	    </tr>

	    <tr valign="top">
	    <th scope="row"><?php echo __( 'Width', 'simple-zippyshare-embed' ); ?></th>
	    <td><input type="number" min="60" name="zippywidth" value="<?php echo esc_attr( get_option( 'zippywidth' ) ); ?>" required />px</td>
	    </tr>

	    <tr valign="top">
	    <th scope="row"><?php echo __( 'Text and Waveform Progress Color', 'simple-zippyshare-embed' ); ?></th>
	    <td><input type="color" name="zippytext" value="<?php echo esc_attr( get_option( 'zippytext' ) ); ?>" required pattern=".{4,}" required title="<?php echo __( '4 characters minimum', 'simple-zippyshare-embed' ); ?>" /></td>
	    </tr>

	    <tr valign="top">
	    <th scope="row"><?php echo __( 'Background Color', 'simple-zippyshare-embed' ); ?></th>
	    <td><input type="color" name="zippyback" value="<?php echo esc_attr( get_option( 'zippyback' ) ); ?>" required pattern=".{4,}" required title="<?php echo __( '4 characters minimum', 'simple-zippyshare-embed' ); ?>" /></td>
	    </tr>

	    <tr valign="top">
	    <th scope="row"><?php echo __( 'Play and Full Waveform Color', 'simple-zippyshare-embed' ); ?></th>
	    <td><input type="color" name="zippyplay" value="<?php echo esc_attr( get_option( 'zippyplay' ) ); ?>" required pattern=".{4,}" required title="<?php echo __( '4 characters minimum', 'simple-zippyshare-embed' ); ?>" /></td>
	    </tr>

	    <tr valign="top">
	    <th scope="row"><?php echo __( 'Waveform Color', 'simple-zippyshare-embed' ); ?></th>
	    <td><input type="color" name="zippywave" value="<?php echo esc_attr( get_option( 'zippywave' ) ); ?>" required pattern=".{4,}" required title="<?php echo __( '4 characters minimum', 'simple-zippyshare-embed' ); ?>" /></td>
	    </tr>

	    <tr valign="top">
	    <th scope="row"><?php echo __( 'Border Color', 'simple-zippyshare-embed' ); ?></th>
	    <td><input type="color" minlength="4" name="zippyborder" value="<?php echo esc_attr( get_option( 'zippyborder' ) ); ?>" required pattern=".{4,}" required title="<?php echo __( '4 characters minimum', 'simple-zippyshare-embed' ); ?>" /></td>
	    </tr>
	</table>
	
	<?php
	
	// security
	wp_nonce_field( 'zippy_form_check', 'zippy_check' );
	
	// compatibility check
	if ( get_bloginfo( 'version' ) >= 3.1 ) {
	    submit_button();
	}
	else {?>
	    <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo __( 'Save Changes', 'simple-zippyshare-embed' ); ?>" /></p>
	<?php } ?>

    </form>
    
    <?php echo __( 'Plugin by', 'simple-zippyshare-embed' ); ?> <a href="http://it-maniak.pl">Saibamen</a>
    <br /><br /><br /><br />
    
    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
	<input type="hidden" name="cmd" value="_s-xclick">
	<input type="hidden" name="hosted_button_id" value="62WX9NREDGCBG">
	<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
	<img alt="" border="0" src="https://www.paypalobjects.com/pl_PL/i/scr/pixel.gif" width="1" height="1">
    </form>

    
    </div>
<?php
}