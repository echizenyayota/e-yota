<?php
session_start();
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://rextheme.com/
 * @since             1.0.0
 * @package           Social_Booster
 *
 * @wordpress-plugin
 * Plugin Name:       Social Booster
 * Plugin URI:        http://rextheme.com/social-booster/
 * Description:       Post automatically from wordpress blog to some specific social media like facebook or twitter. It is easy and comfortable to use.
 * Version:           1.0.0
 * Author:            Rextheme
 * Author URI:        http://rextheme.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       social-booster
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Currently pligin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'SOCIAL_BOOSTER_VERSION', '1.0.0' );




/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-social-booster-activator.php
 */


require_once plugin_dir_path( __FILE__ ) . 'admin/class-social-booster-db-manager.php';
function activate_social_booster() {

	$dbcreate = new Social_Booster_Db_Manager();
	flush_rewrite_rules();
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-social-booster-activator.php';
	Social_Booster_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-social-booster-deactivator.php
 */
function deactivate_social_booster() {
 	flush_rewrite_rules();
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-social-booster-deactivator.php';
	Social_Booster_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_social_booster' );
register_deactivation_hook( __FILE__, 'deactivate_social_booster' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-social-booster.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_social_booster() {

	$plugin = new Social_Booster();
	$plugin->run();

}
run_social_booster();

require_once plugin_dir_path(__FILE__) . 'admin/metabox/metabox.php';
require_once plugin_dir_path(__FILE__) . 'admin/metabox/cmb2-conditionals-master/cmb2-conditionals.php';
require_once plugin_dir_path(__FILE__) . 'admin/metabox/init.php';	




function socialbooster_tweetstatusnow($message) {
            
    require_once plugin_dir_path(__FILE__) . 'admin/lib/codebird.php';  

    global $wpdb;
    $tabletw = $wpdb->prefix . 'socialbooster_twtable';
    $consumer_key = $wpdb->get_var("SELECT consumerkey FROM $tabletw ");
    $consumer_secret = $wpdb->get_var("SELECT consumersecret FROM $tabletw ");
    $access_token = $wpdb->get_var("SELECT accesstoken FROM $tabletw ");
    $access_token_secret = $wpdb->get_var("SELECT accsesstokensecret FROM $tabletw "); 
    \Codebird\Codebird::setConsumerKey( $consumer_key, $consumer_secret );
    $cb = \Codebird\Codebird::getInstance();

    $cb->setToken($access_token, $access_token_secret);
	$params = array(
    	'status' => $message
   
	);
	$reply = $cb->statuses_update($params);

}

function socialbooster_facebooknow($message,$image) {

    		require_once plugin_dir_path(__FILE__) . 'admin/lib/facebook.php';
    		global $wpdb;
            $tablefb = $wpdb->prefix . 'socialbooster_fbtable';
            $appid = $wpdb->get_var("SELECT appid FROM $tablefb ");
            $app_secret = $wpdb->get_var("SELECT appsecret FROM $tablefb ");
            $access_token_secretfb = $wpdb->get_var("SELECT accsesstokensecret FROM $tablefb "); 
    		$config = array();
    		$config['appId'] = $appid;
    		$config['secret'] = $app_secret;
    		$config['fileUpload'] = true;
    		$fb = new Facebook($config);
    		 
    		$params = array(

    		  "access_token" => $access_token_secretfb,
    		  "message" => $message,
    		  "link" => $image

    		);
    		try {
    		  $ret = $fb->api('/me/feed', 'POST', $params);
    		} catch(Exception $e) {
    		  echo $e->getMessage();
    		}
    	}

function socialbooster_facebooklinknow($image) {

    		require_once plugin_dir_path(__FILE__) . 'admin/lib/facebook.php';
    		global $wpdb;
            $tablefb = $wpdb->prefix . 'socialbooster_fbtable';
            $appid = $wpdb->get_var("SELECT appid FROM $tablefb ");
            $app_secret = $wpdb->get_var("SELECT appsecret FROM $tablefb ");
            $access_token_secretfb = $wpdb->get_var("SELECT accsesstokensecret FROM $tablefb "); 
    		$config = array();
    		$config['appId'] = $appid;
    		$config['secret'] = $app_secret;
    		$config['fileUpload'] = true; 
    		$fb = new Facebook($config);
    		 
    		$params = array(

    		  "access_token" => $access_token_secretfb,
    		  "link" => $image

    		);
    		try {
    		  $ret = $fb->api('/me/feed', 'POST', $params);
    		} catch(Exception $e) {
    		  echo $e->getMessage();
    		}
    	}

function socialbooster_post_now() {
            global $wpdb;
            $tablepost = $wpdb->prefix . 'socialbooster_postnowtable';
            $dbstatusnow = $wpdb->get_var("SELECT status FROM $tablepost ");
            $dblinknow = $wpdb->get_var("SELECT link FROM $tablepost ");
            $dbstatusmetanow = $dbstatusnow . " " . $dblinknow;
            $fbnow = $wpdb->get_var("SELECT fb FROM $tablepost ");
            $twnow = $wpdb->get_var("SELECT tw FROM $tablepost ");

            if($dbstatusnow!="" && $dblinknow!=""){

                if (!empty($fbnow) && !empty($twnow)) {

                    socialbooster_tweetstatusnow($dbstatusmetanow);
                    socialbooster_facebooknow($dbstatusnow,$dblinknow);
                $wpdb->query("DELETE FROM $tablepost where 1=1 ");
                }
                elseif (!empty($fbnow) && empty($twnow)) {

                    socialbooster_facebooknow($dbstatusnow,$dblinknow);
                $wpdb->query("DELETE FROM $tablepost where 1=1 ");
                }
                elseif (empty($fbnow) && !empty($twnow)) {

                    socialbooster_tweetstatusnow($dbstatusmetanow);
                $wpdb->query("DELETE FROM $tablepost where 1=1 ");
                }
            }

            elseif ($dbstatusnow!="" && $dblinknow=="") {

                if (!empty($fbnow) && !empty($twnow)) {

                    socialbooster_tweetstatusnow($dbstatusnow);
                    socialbooster_facebookstatusnow($dbstatusnow);
                $wpdb->query("DELETE FROM $tablepost where 1=1 ");
                }
                elseif (!empty($fbnow) && empty($twnow)) {

                    socialbooster_facebooklinknow($dbstatusnow);
                $wpdb->query("DELETE FROM $tablepost where 1=1 ");
                }
                elseif (empty($fbnow) && !empty($twnow)) {

                    socialbooster_tweetstatusnow($dbstatusnow);
                $wpdb->query("DELETE FROM $tablepost where 1=1 ");
                }
            }

            elseif ($dbstatusnow=="" && $dblinknow!="") {

                if (!empty($fbnow) && !empty($twnow)) {

                    socialbooster_tweetstatusnow($dblinknow);
                    socialbooster_facebooklinknow($dblinknow);
                $wpdb->query("DELETE FROM $tablepost where 1=1 ");
                }
                elseif (!empty($fbnow) && empty($twnow)) {

                    socialbooster_facebooklinknow($dblinknow);
                $wpdb->query("DELETE FROM $tablepost where 1=1 ");
                }
                elseif (empty($fbnow) && !empty($twnow)) {

                    socialbooster_tweetstatusnow($dblinknow);
                $wpdb->query("DELETE FROM $tablepost where 1=1 ");
                }
            }
}
add_action( 'save_post', 'socialbooster_post_now', 10, 3 );

$timeis = time() + 31557600;
add_option( 'social-boosterw', $timeis );
$checkbox = get_option('social-boosterw');
if ($checkbox <= time()) {
      add_action('wp_dashboard_setup', 'social_booster_custom_dashboard_widgets');

    function social_booster_custom_dashboard_widgets() {
    global $wp_meta_boxes;
    
    wp_add_dashboard_widget('custom_help_widget', 'Social Booster', 'social_booster_dashboard_help');

    }
     
    function social_booster_dashboard_help() {
    echo '<h2>Welcome to the Social Booster</h2><a href="https://wordpress.org/plugins/social-booster/#reviews">Rate this plugin</a><br><a href="mailto:takhi44@gmail.com">Report! If you do not like this plugin</a>';
    } 
}



