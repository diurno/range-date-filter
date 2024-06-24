<?php
/**
 * Range Date Filter
 *
 * @package       RANGEDATEF
 * @author        Federico Cadierno
 * @license       gplv2
 * @version       1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:   Range Date Filter
 * Plugin URI:    federico-cadierno.com
 * Description:   federico-cadierno.com
 * Version:       1.0.0
 * Author:        Federico Cadierno
 * Author URI:    federico-cadierno.com
 * Text Domain:   range-date-filter
 * Domain Path:   /languages
 * License:       GPLv2
 * License URI:   https://www.gnu.org/licenses/gpl-2.0.html
 *
 * You should have received a copy of the GNU General Public License
 * along with Range Date Filter. If not, see <https://www.gnu.org/licenses/gpl-2.0.html/>.
 */

namespace rangedatef;


// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

spl_autoload_register(
	function ( $item ) {
		//echo "namespace ".strncmp( __NAMESPACE__, $item, 10 )."<br>";
		if ( 0 === strncmp( __NAMESPACE__, $item, 10 ) ) {
			$item = str_replace( array( '\\' ), array( '/' ), substr( $item, 10 ) );
			//$item = preg_replace( '@([^/]+)$@', 'class-$1.php', $item );
			$item =$item.".php";

			//echo "item down ".__DIR__.$item."<br>";

			if ( file_exists( __DIR__  . $item ) ) {
				require_once __DIR__ . $item;
			}

		}
	}
);


use rangedatef\Core\Range_Date_Filter;


// Plugin name
define( 'RANGEDATEF_NAME',			'Range Date Filter' );

// Plugin version
define( 'RANGEDATEF_VERSION',		'1.0.0' );

// Plugin Root File
define( 'RANGEDATEF_PLUGIN_FILE',	__FILE__ );

// Plugin base
define( 'RANGEDATEF_PLUGIN_BASE',	plugin_basename( RANGEDATEF_PLUGIN_FILE ) );

// Plugin Folder Path
define( 'RANGEDATEF_PLUGIN_DIR',	plugin_dir_path( RANGEDATEF_PLUGIN_FILE ) );

// Plugin Folder URL
define( 'RANGEDATEF_PLUGIN_URL',	plugin_dir_url( RANGEDATEF_PLUGIN_FILE ) );

/**
 * Load the main class for the core functionality
 */


/**
 * The main function to load the only instance
 * of our master class.
 *
 * @author  Federico Cadierno
 * @since   1.0.0
 * @return  object|Range_Date_Filter
 */
function RANGEDATEF() {
	return  Range_Date_Filter::instance();
}

RANGEDATEF();
