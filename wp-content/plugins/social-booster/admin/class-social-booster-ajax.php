<?php

/**
 * The admin-specific Ajax files.
 *
 * @link       http://rextheme.com/
 * @since      1.0.0
 *
 * @package    Social_Booster
 * @subpackage Social_Booster/admin
 */

class Social_Booster_Ajax {

    function socialbooster_process_tw_info() {

      check_ajax_referer( 'social_booster', 'security' );

		if ($_POST["usernametw"] != "" && $_POST["conkeytw"] != "" && $_POST["consecrettw"] != ""  && $_POST["accesstokentw"] != ""&&  $_POST["accesstokensecrettw"] != "")
		        {
		            global $wpdb;
		            $table = $wpdb->prefix . 'socialbooster_twtable';
		            $wpdb->query("DELETE FROM $table where 1=1 ");
                $usernametwsanitize = strip_tags($_POST["usernametw"], "");
                $usernametwsanitizes = sanitize_text_field( $usernametwsanitize );
                $conkeytwsanitize = strip_tags($_POST["conkeytw"], "");
                $conkeytwsanitizes = sanitize_text_field( $conkeytwsanitize );
                $consecrettwsanitize = strip_tags($_POST["consecrettw"], "");
                $consecrettwsanitizes = sanitize_text_field( $consecrettwsanitize );
                $accesstokentwsanitize = strip_tags($_POST["accesstokentw"], "");
                $accesstokentwsanitizes = sanitize_text_field( $accesstokentwsanitize );
                $accesstokensecrettwsanitize = strip_tags($_POST["accesstokensecrettw"], "");
                $accesstokensecrettwsanitizes = sanitize_text_field( $accesstokensecrettwsanitize );
		            $usernametw = $usernametwsanitizes;
		            $conkeytw = $conkeytwsanitizes;
		            $consecrettw = $consecrettwsanitizes;
		            $accesstokentw = $accesstokentwsanitizes;
		            $accesstokensecrettw = $accesstokensecrettwsanitizes;

		            require_once plugin_dir_path(__FILE__) . '/lib/codebird.php';

            		\Codebird\Codebird::setConsumerKey($conkeytw,$consecrettw); 

            		$cb = \Codebird\Codebird::getInstance();
            		$reply = $cb->oauth_requestToken([
              		'oauth_callback' => 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']
            		]);

            		$_SESSION['oauth_verify'] = true;
            		$auth_url = $cb->oauth_requestToken();
            		$url = $auth_url->oauth_token;
            		$authorize_url = 'https://api.twitter.com/oauth/authorize?oauth_token=' . $url;
            		$msg = array();

            		if(!empty($url)){
                  		$wpdb->insert(
                  		$table,
                  		array(

                      'username' => $usernametw,
                       'consumerkey' => $conkeytw,
                      'consumersecret' => $consecrettw,
                      'accesstoken' => $accesstokentw,
                      'accsesstokensecret' => $accesstokensecrettw

                    	)
              		);
                  		$msg['success'] = '<h1 class="label_design">Successfully Saved</h1>';

            		}
            		else {

            			$msg['error'] = '<h1 class="label_design">Error Occured. Invalid Consumer Key and Cunsumer Secret</h1>';
            		}

		        } 

		        elseif ($_POST["usernametw"] == "" || $_POST["conkeytw"] == "" || $_POST["consecrettw"] == "" ||  $_POST["accesstokentw"] == "" || $_POST["accesstokensecrettw"] == "" )
		        {
		           $msg['error'] = '<h1 class="label_design">Fill All Required Fields</h1>'; 
		        }
		        $msg['url'] = $authorize_url;

		        echo json_encode($msg);
		        exit();
    }

    function socialbooster_process_delete_info() {
      check_ajax_referer( 'social_booster', 'security' );
        global $wpdb;
        $tablestatus = $wpdb->prefix . 'socialbooster_statustable';
        parse_str( $_POST[ 'newFormRecherche' ], $newFormRecherche );
        $id = $newFormRecherche['deleteform'];
        $wpdb->query("DELETE FROM $tablestatus where schno=$id ");
        echo '<META HTTP-EQUIV="REFRESH" CONTENT="1">' ;
        wp_die();
 
    }

}