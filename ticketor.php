<?php
/*
Plugin Name: ticketor
Plugin URI: https://www.ticketor.dev/WordPress-Plugin-for-Ticketing
Description: Use Ticketor plug-in to add full-featured event ticketing and box-office functionality. You need to sign up for a Ticketor account on https://www.Ticketor.com and get the proper embed shortcode. Example to embed http://www.Ticketor.com/Demo upcoming events page: [ticketor id="TicketorFrame" allowtransparency="true" style="background:transparent;" src="https://www.ticketor.com/Demo/upcomingevents?frame=1&moduleOnly=1&noheader=1&notopnav=1&nomodulewrap=1&transparent=1&linktarget=frame" frameborder="0" width="100%" height="1000"] 
Version: 4.3
Author: Ticketor
Author URI: http://Ticketor.com
License: GPLv3
*/

define('TICKETOR_PLUGIN_VERSION', '2.3');

function ticketor_plugin_add_shortcode_cb( $atts ) {
	$defaults = array(
		'id' => 'TicketorFrame',
		'frameborder' => '0',
		'src' => 'https://www.ticketor.com/demo/upcomingevents?pageid=343&frame=1&moduleOnly=1&noheader=1&notopnav=1&nomodulewrap=1&transparent=1&linktarget=frame',
		'width' => '100%',
		'height' => '1000'
	);

	foreach  ( $defaults as $default => $value ) { // add defaults
		if ( ! @array_key_exists( $default, $atts ) ) { // mute warning with "@" when no params at all
			$atts[$default] = $value;
		}
	}

	$html = "\n".'<!-- Ticketor plugin v.'.TICKETOR_PLUGIN_VERSION.' Ticketor.com -->'."\n";
	$html .= '<iframe';
	foreach( $atts as $attr => $value ) {
		if ( strtolower($attr) != 'same_height_as' AND strtolower($attr) != 'onload'
			AND strtolower($attr) != 'onpageshow' AND strtolower($attr) != 'onclick') { // remove some attributes
			if ( $value != '' ) { // adding all attributes
				$html .= ' ' . esc_attr( $attr ) . '="' . esc_attr( $value ) . '"';
			} else { // adding empty attributes
				$html .= ' ' . esc_attr( $attr );
			}
		}
	}
	$html .= '></iframe>'."\n";


	$html .= '
		<script type="text/javascript">
			window.addEventListener("message", function(e){if (e.data.indexOf("TicketorFrameHeight") == 0){ document.getElementById("TicketorFrame").style.height = e.data.substr(19)+"px";document.getElementById("TicketorFrame").style.overflowY = "hidden";document.getElementById("TicketorFrame").setAttribute("scrolling","no");}}, false); 
		</script>
	';

	return $html;
}
add_shortcode( 'ticketor', 'ticketor_plugin_add_shortcode_cb' );


function ticketor_plugin_row_meta_cb( $links, $file ) {
	if ( $file == plugin_basename( __FILE__ ) ) {
		$row_meta = array(
			'support' => '<a href="http://Ticketor.com" target="_blank"><span class="dashicons dashicons-editor-help"></span> ' . __( 'Ticketor', 'ticketor' ) . '</a>'
		);
		$links = array_merge( $links, $row_meta );
	}
	return (array) $links;
}
add_filter( 'plugin_row_meta', 'ticketor_plugin_row_meta_cb', 10, 2 );
