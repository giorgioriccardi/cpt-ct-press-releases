<?php
/**
 * @package GRC_CPT_CT
 * @version 2.1.0
 *
 * Plugin Name: Press Releases Post Types and Taxonomies
 * Plugin URI: https://griccardi.com
 * Description: A custom plugin that adds custom post types and taxonomies.
 *    Using this plugin there is no need to write code into the theme/functions.php;
 *    doing so the CPT will remain available indipendently by the theme we select.
 * Version: 2.1.0
 * Author: Giorgio Riccardi @SSWS
 * Author URI: https://griccardi.com
 * Requires at least: 3.0.0
 * Tested up to:      4.9.5
 * Requires PHP:	  5.6 or >
 * License: GPL v3
 * SSWS Press Releases CPT
 * Copyright © 2015-2018, SSWS - www.griccardi.com

 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

if ( !class_exists( 'GRC_CPT_CT' ) ) {

    class GRC_CPT_CT {

        /**
         * Class constructor
         */
        function __construct() {

            $this->define_constants();
            $this->includes();

        }

        /**
         * Setup plugin constants.
         *
         * @since 1.0.0
         * @return void
         */
        public function define_constants() {

            if ( !defined( 'GRC_VERSION_NUM' ) )
                define( 'GRC_VERSION_NUM', '2.1.0' );

            if ( !defined( 'GRC_URL' ) )
                define( 'GRC_URL', plugin_dir_url( __FILE__ ) );

            if ( !defined( 'GRC_BASENAME' ) )
                define( 'GRC_BASENAME', plugin_basename( __FILE__ ) );

            if ( !defined( 'GRC_PLUGIN_DIR' ) )
                define( 'GRC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
        }

        /**
         * Include the required files.
         *
         * @since 1.0.0
         * @return void
         */
        public function includes() {

            require_once( GRC_PLUGIN_DIR . 'includes/cpt-ct-press-releases_functions.php' );

        }

    }

    $GLOBALS['ssws'] = new GRC_CPT_CT();
}

?>