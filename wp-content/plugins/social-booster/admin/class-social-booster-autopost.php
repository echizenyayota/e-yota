<?php

/**
 * The admin-specific Posting process to different social media.
 *
 * @link       http://rextheme.com/
 * @since      1.0.0
 *
 * @package    Social_Booster
 * @subpackage Social_Booster/admin
 */

require_once plugin_dir_path(__FILE__) . 'class-social-booster-cron.php';
class Social_Booster_Autopost {

		public $socialbooster_schedule;

		function __construct() {

            $this->socialbooster_schedule = new Social_Booster_Cron();

        }

        function socialbooster_add_cron( $next_run, $schedule, $hookname, $args ) {
            wp_schedule_event( $next_run, $schedule, $hookname, $args );
        }

        function socialbooster_action_php_cron_event($args){

            global $wpdb;
            $tablestatus = $wpdb->prefix . 'socialbooster_statustable';
            $dbstatus = $wpdb->get_var("SELECT status FROM $tablestatus WHERE schno={$args} ");
            $dblink = $wpdb->get_var("SELECT link FROM $tablestatus WHERE schno={$args} ");
            $dbstatusmeta = $dbstatus . " " . $dblink;
            $fb = $wpdb->get_var("SELECT fb FROM $tablestatus WHERE schno={$args} ");
            $tw = $wpdb->get_var("SELECT tw FROM $tablestatus WHERE schno={$args} ");

            if($dbstatus!="" && $dblink!=""){

                if (!empty($fb) && !empty($tw)) {

                    $this->socialbooster_tweetstatus($dbstatusmeta);
                    $this->socialbooster_facebook($dbstatus,$dblink);
                }
                elseif (!empty($fb) && empty($tw)) {

                    $this->socialbooster_facebook($dbstatus,$dblink);
                }
                elseif (empty($fb) && !empty($tw)) {

                    $this->socialbooster_tweetstatus($dbstatusmeta);
                }
            }

            elseif ($dbstatus!="" && $dblink=="") {

                if (!empty($fb) && !empty($tw)) {

                    $this->socialbooster_tweetstatus($dbstatus);
                    $this->socialbooster_facebookstatus($dbstatus);
                }
                elseif (!empty($fb) && empty($tw)) {

                    $this->socialbooster_facebooklink($dbstatus);
                }
                elseif (empty($fb) && !empty($tw)) {

                    $this->socialbooster_tweetstatus($dbstatus);
                }
            }

            elseif ($dbstatus=="" && $dblink!="") {

                if (!empty($fb) && !empty($tw)) {

                    $this->socialbooster_tweetstatus($dblink);
                    $this->socialbooster_facebooklink($dblink);
                }
                elseif (!empty($fb) && empty($tw)) {

                    $this->socialbooster_facebooklink($dblink);
                }
                elseif (empty($fb) && !empty($tw)) {

                    $this->socialbooster_tweetstatus($dblink);
                }
            }
        }

        function socialbooster_post_info( $id, $post ) {

            check_ajax_referer( 'social_booster', 'security' );

            $captionsanitize = $_POST['post'];
            $captionsanitizes = sanitize_text_field( $captionsanitize );
            $schnosanitize = $_POST['schno'];
            $schnosanitizes = sanitize_text_field( $schnosanitize );
            $socialsanitize = $_POST['social'];
            $socialsanitizes = sanitize_text_field( $socialsanitize );
            $timingsanitize = $_POST['times'];
            $timingsanitizes = sanitize_text_field( $timingsanitize );
            $urlsanitize = $_POST['postid'];
            $urlsanitizes = sanitize_text_field( $urlsanitize );
            $url_test = get_permalink($urlsanitizes);
            $postnowsanitize = $_POST['postnow'];
            $postnowsanitizes = sanitize_text_field( $postnowsanitize );
            $caption = $captionsanitizes;
            $schno = $schnosanitizes;
            $social = $socialsanitizes;
            $timing = $timingsanitizes;
            $url = $url_test;
            $dbstatusmeta = $caption . " " . $url;
            $postnow = $postnowsanitizes;
            
            if ($social== 'socmulticheckbox=check1&socmulticheckbox=check2') {

                $twmeta = '1';
                $fbmeta = '1';
            }
            elseif ($social== 'socmulticheckbox=check1') {

                $twmeta = '1';
                $fbmeta = "0";
            }
            elseif ($social== 'socmulticheckbox=check2') {
                $twmeta = "0";
                $fbmeta = '1';
            }

            if ($postnow=='on') {
                        global $wpdb;
                        $tablepost = $wpdb->prefix . 'socialbooster_postnowtable';

                    if ( $twmeta==1 && $fbmeta==1) {

                        $wpdb->insert(
                                $tablepost,
                                    array(

                                        'status' => $caption,
                                        'link' => $url,
                                        'schno' => $schno,
                                        'tw' => 1,
                                        'fb' => 1
                                    )
                                );
                    }
                    elseif ($twmeta==1 && $fbmeta==0) {

                        $wpdb->insert(
                                $tablepost,
                                    array(

                                        'status' => $caption,
                                        'link' => $url,
                                        'schno' => $schno,
                                        'tw' => 1,
                                        'fb' => 0
                                    )
                                );
                    }
                    elseif ($twmeta==0 && $fbmeta==1) {

                        $wpdb->insert(
                                $tablepost,
                                    array(

                                        'status' => $caption,
                                        'link' => $url,
                                        'schno' => $schno,
                                        'tw' => 0,
                                        'fb' => 1
                                    )
                                );
                    }

            }

            if ($timing=='yearly') {

                $dbinterval = 31557600;
            }
            elseif ($timing=='monthly') {
                $dbinterval = 2628000;
            }
            elseif ($timing=='weekly') {
                $dbinterval = 604900;
            }
            else {
                $dbinterval = 0;
            }

            $name = 'socialbooster_'.$dbinterval;
            $interval = $dbinterval;
            $display = 'Every '.$dbinterval.' Seconds';

            if ($dbinterval!=0) {


                global $wpdb;
                $tablestatus = $wpdb->prefix . 'socialbooster_statustable';

                if ($twmeta==1 && $fbmeta==1) {

                    $prepared_schno = $wpdb->get_var( "SELECT schno FROM $tablestatus WHERE  schno = $schno ");
                    if (!empty($prepared_schno)) {
                        $wpdb->update($tablestatus, array('status'=>$caption, 'link'=>$url, 'interv'=>$dbinterval, 'tw' =>1, 'fb'=> 1), array('schno'=>$prepared_schno));
                    }
                    else
                        {
                            $wpdb->insert(
                            $tablestatus,
                            array(

                                'status' => $caption,
                                'link' => $url,
                                'schno' => $schno,
                                'interv' => $dbinterval,
                                'tw' => 1,
                                'fb' => 1
                            )
                        );
                    if ($wpdb->last_error !== '') {
                      
                        _e( '<h1 class="label_design">Data Already Included. Change Event ID to Save Updated Post or Delete it from Schedule Page.</h1>', 'social-booster' );
                    }
                    else {
                        $this->socialbooster_schedule->socialbooster_add_schedule($name, $interval, $display);
                        $this->socialbooster_add_cron(time(), 'socialbooster_'.$interval, 'crontrol_cron_job',  array('schno' => $schno));

                        }
                    }
                }

                elseif ($twmeta==1 && $fbmeta==0) {

                    $prepared_schno = $wpdb->get_var( "SELECT schno FROM $tablestatus WHERE  schno = $schno ");
                    if (!empty($prepared_schno)) {
                        $wpdb->update($tablestatus, array('status'=>$caption, 'link'=>$url, 'interv'=>$dbinterval, 'tw' =>1), array('schno'=>$prepared_schno));
                    }
                    else
                        {
                            $wpdb->insert(
                            $tablestatus,
                            array(

                                'status' => $caption,
                                'link' => $url,
                                'schno' => $schno,
                                'interv' => $dbinterval,
                                'tw' => 1
                            )
                        );
                    if ($wpdb->last_error !== '') {
                        _e( '<h1 class="label_design">Data Already Included. Change Event ID to Save Updated Post or Delete it from Schedule Page.</h1>', 'social-booster' );
                    }
                    else {
                        $this->socialbooster_schedule->socialbooster_add_schedule($name, $interval, $display);
                        $this->socialbooster_add_cron(time(), 'socialbooster_'.$interval, 'crontrol_cron_job',  array('schno' => $schno));
                        }
                    }
                }

                elseif ($twmeta==0 && $fbmeta==1) {


                    $prepared_schno = $wpdb->get_var( "SELECT schno FROM $tablestatus WHERE  schno = $schno ");
                    if (!empty($prepared_schno)) {
                        $wpdb->update($tablestatus, array('status'=>$caption, 'link'=>$url, 'interv'=>$dbinterval, 'fb'=> 1), array('schno'=>$prepared_schno));
                    }
                    else
                        {
                            $wpdb->insert(
                            $tablestatus,
                            array(

                                'status' => $caption,
                                'link' => $url,
                                'schno' => $schno,
                                'interv' => $dbinterval,
                                'fb' => 1
                            )
                        );
                    if ($wpdb->last_error !== '') {
                        _e( '<h1 class="label_design">Data Already Included. Change Event ID to Save Updated Post or Delete it from Schedule Page.</h1>', 'social-booster' );
                    }

                    else {
                        $this->socialbooster_schedule->socialbooster_add_schedule($name, $interval, $display);
                        $this->socialbooster_add_cron(time(), 'socialbooster_'.$interval, 'crontrol_cron_job',  array('schno' => $schno));
                        
                        }  
                    }
                }
            }
            exit();
        }

        function socialbooster_tweet($message,$image) {
            require_once plugin_dir_path(__FILE__) . '/lib/codebird.php';  
            global $wpdb;
            $tabletw = $wpdb->prefix . 'socialbooster_twtable';
            $consumer_key = $wpdb->get_var("SELECT consumerkey FROM $tabletw ");
            $consumer_secret = $wpdb->get_var("SELECT consumersecret FROM $tabletw ");
            $access_token = $wpdb->get_var("SELECT accesstoken FROM $tabletw ");
            $access_token_secret = $wpdb->get_var("SELECT accsesstokensecret FROM $tabletw "); 
            \Codebird\Codebird::setConsumerKey( $consumer_key, $consumer_secret );
            $cb = \Codebird\Codebird::getInstance();
            $cb->setToken($access_token, $access_token_secret);
            $reply = $cb->media_upload(array(
                'media' => $image
            ));
            $mediaID = $reply->media_id_string;
            $params = array(
                'status' => $message,
                'media_ids' => $mediaID
            );

            $reply = $cb->statuses_update($params);
          
        } 

        function socialbooster_tweetstatus($message) {
            
            require_once plugin_dir_path(__FILE__) . '/lib/codebird.php';  

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

    	function socialbooster_tweetlink($image) {
            require_once plugin_dir_path(__FILE__) . '/lib/codebird.php';  
            global $wpdb;
            $tabletw = $wpdb->prefix . 'socialbooster_twtable';
            $consumer_key = $wpdb->get_var("SELECT consumerkey FROM $tabletw ");
            $consumer_secret = $wpdb->get_var("SELECT consumersecret FROM $tabletw ");
            $access_token = $wpdb->get_var("SELECT accesstoken FROM $tabletw ");
            $access_token_secret = $wpdb->get_var("SELECT accsesstokensecret FROM $tabletw "); 
            \Codebird\Codebird::setConsumerKey( $consumer_key, $consumer_secret );
            $cb = \Codebird\Codebird::getInstance();
            $cb->setToken($access_token, $access_token_secret);
            $reply = $cb->media_upload(array(
                'media' => $image
            ));

            $mediaID = $reply->media_id_string;
            $params = array(

                'media_ids' => $mediaID
            );

            $reply = $cb->statuses_update($params);
          
        }

        function socialbooster_facebook($message,$image) {

    		require_once plugin_dir_path(__FILE__) . '/lib/facebook.php';
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

    	function socialbooster_facebookstatus($message) {

    		require_once plugin_dir_path(__FILE__) . '/lib/facebook.php';
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
    		  "message" => $message

    		);
    		try {
    		  $ret = $fb->api('/me/feed', 'POST', $params);
    		} catch(Exception $e) {
    		  echo $e->getMessage();
    		}
    	}

    	function socialbooster_facebooklink($image) {

    		require_once plugin_dir_path(__FILE__) . '/lib/facebook.php';
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
}





