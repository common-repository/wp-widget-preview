<?php
/**
 * @package WP Widget Preview
 * @version 1.1
 */
/*
Plugin Name: WP Widget Preview
Plugin URI: http://daan.kortenba.ch/wordpress-widget-preview/
Description: Enables a preview mode for widgets. In preview mode, widgets are only visible for admins.
Author: Daan Kortenbach
Author URI: http://daan.kortenba.ch/
Version: 1.1
License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/
/*  Copyright 2013  Daan Kortenbach  (email : info@forsitemedia.net)

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
/*
	Credit: Original code from WooThemes found in the WooDojo plugin.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * ForSite Widget Preview Mode Class
 *
 * All functionality pertaining to the widget preview mode downloadable component.
 *
 * @package WordPress
 * @author Matty, Daan Kortenbach
 * @since 1.0.0
 *
 * TABLE OF CONTENTS
 *
 * - __construct()
 * - control_widget_access()
 * - save_widget_form()
 * - widget_form_html()
 */
class ForSite_Widget_Preview {

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __construct() {
		if ( is_admin() ) {
			add_filter( 'widget_update_callback', array( &$this, 'save_widget_form' ), 10, 2 );
			add_action( 'in_widget_form', array( &$this, 'widget_form_html' ), 10, 3 );
		}
		else {
			add_filter( 'widget_display_callback', array( &$this, 'control_widget_access' ), 10, 3 );
		}
	} // End __construct()

	/**
	 * Control the display of a widget.
	 *
	 * @param {array} $instance the settings for the widget
	 * @param {object} $obj      the widget instance object
	 * @param {array} $args     arguments
	 * @since 1.0.0
	 * @return {array}           the instance
	 */
	public function control_widget_access( $instance, $obj, $args ) {
		if ( isset( $instance['widget_preview_mode'] ) && ( $instance['widget_preview_mode'] == true ) && ( ! current_user_can( 'manage_options' ) ) )
			return false;

		return $instance;
	} // End control_widget_access()

	/**
	 * Save the data from our custom form fields.
	 *
	 * @param array   $instance     array of settings for this widget
	 * @param array   $new_instance array of settings for this widget
	 * @param array   $old_instance array of settings for this widget
	 * @param object  $obj          the instance of the widget
	 * @since 1.0.0
	 * @return array           array of settings for this widget
	 */
	public function save_widget_form( $instance, $new_instance, $old_instance, $obj ) {
		if ( isset( $new_instance['widget_preview_mode'] ) ) {
			$instance['widget_preview_mode'] = $new_instance['widget_preview_mode'];
		}
		else {
			$instance['widget_preview_mode'] = false;
		}
		return $instance;
	} // End save_widget_form()

	/**
	 * Output a checkbox on the widget control form.
	 *
	 * @param object  $obj      the instance of the widget
	 * @param boolean $return   the return for the widget
	 * @param array   $instance an array of settings for this widget
	 * @since 1.0.0
	 * @return void
	 */
	public function widget_form_html( $obj, $return, $instance ) {
		global $return;

		if ( ! isset( $instance['widget_preview_mode'] ) )
			$instance['widget_preview_mode'] = false;

		echo '<!-- Widget Preview: Checkbox Input -->';
		echo '<p>';
		echo '<input id="' . $obj->get_field_id( 'widget_preview_mode' ) . '" name="' . $obj->get_field_name( 'widget_preview_mode' ) . '" type="checkbox" ' . checked( $instance['widget_preview_mode'], 1, false ) . ' value="1" /> ';
		echo '<label for="' . $obj->get_field_id( 'widget_preview_mode' ) . '">' . _e( 'Preview Mode', 'forsite' ) . '</label>';
		echo '</p>';

		$return = null;
	} // End widget_form_html()
} // End Class

/* Instantiate ForSite Widget Preview */
new ForSite_Widget_Preview();
