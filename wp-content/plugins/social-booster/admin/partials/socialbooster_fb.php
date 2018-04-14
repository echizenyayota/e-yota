<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://rextheme.com/
 * @since      1.0.0
 *
 * @package    Social_Booster
 * @subpackage Social_Booster/admin/partials
 */
?>
<html>
  <body> 
    <h1 class="headposter">Social Booster</h1>
    <div class="navigation_bar">
      
            <ul>

              <li><a href="#">Facebook</a></li>
              <li><a href="admin.php?page=socialbooster_twitter">Twitter</a></li>
              <li><a href="admin.php?page=socialbooster_gplus">Google Plus</a></li>
              <li><a href="admin.php?page=socialbooster_instragram">Instragram</a></li>
              <li><a href="admin.php?page=socialbooster_tumblr">Tumblr</a></li>

            </ul>
      
    </div>
      <div class="soc_form_primary">
          
            <div class="fbinfo_form">
                  <h3>Information Form for Facebook</h3>
                  <div id="fbsavedinfo" class= "fbsaved">
        
                  </div>

                  <form id="fbform" action="" method="post">

                    <label class="label_design">App ID</label><br>
                    <input type="text" name="appidfb" id="fullname" placeholder=<?php             
                    global $wpdb;
                    $tablefb = $wpdb->prefix . 'socialbooster_fbtable';
                    $appid = $wpdb->get_var("SELECT appid FROM $tablefb ");
                    _e( $appid, 'social-booster' );
                    ?>><br>
                    <label class="label_design">App Secret</label><br>
                    <input type="text" name="appsecretfb" id="fullname" placeholder=<?php             
                    global $wpdb;
                    $tablefb = $wpdb->prefix . 'socialbooster_fbtable';
                    $appsecret = $wpdb->get_var("SELECT appsecret FROM $tablefb ");
                    _e( $appsecret, 'social-booster' );
                    ?>><br>
                    <input type="submit" name="submit_form" id="submit_form" value="Authorize">&nbsp;

                  </form>
            </div>
      </div>
     
      <?php 
      require_once "Facebook/autoload.php";

      if (isset($_POST["submit_form"]) && $_POST["appidfb"] != "" && $_POST["appsecretfb"] != "" )
        {

          $appidfbsanitize = strip_tags($_POST["appidfb"], "");
          $appidfbsanitizes = sanitize_text_field( $appidfbsanitize );
          $appsecretfbsanitize = strip_tags($_POST["appsecretfb"], "");
          $appsecretfbsanitizes = sanitize_text_field( $appsecretfbsanitize );
          $appidfb = $appidfbsanitizes;
          $appsecretfb = $appsecretfbsanitizes;
          global $wpdb;
          $table = $wpdb->prefix . 'socialbooster_fbtable';
          $wpdb->query("DELETE FROM $table where 1=1 ");
          $wpdb->insert(
                    $table,
                    array(

                        'username' => 0,
                         'appid' => $appidfb,
                        'appsecret' => $appsecretfb,
                        'accsesstokensecret' => 0,
                        'redirecturl' => 0,
                        'authorization' => 0

                      )
                ); 
          $FB = new \Facebook\Facebook([
                  'app_id' => $appidfb,
                  'app_secret' => $appsecretfb,
                  'default_graph_version' => 'v2.10'
          ]);

          $helper = $FB->getRedirectLoginHelper();

          if (!isset($_SESSION['access_token'])) {
              $theuri = $_SERVER['SERVER_NAME'];             
              $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
              $redirectURL = $protocol.$theuri."/wp-admin/admin.php?page=socialbooster_autopost";
              $permissions = ['email', 'publish_actions', 'user_about_me', 'user_birthday', 'user_education_history', 'user_friends', 'user_games_activity', 'user_hometown', 'user_likes', 'user_location', 'user_photos', 'user_posts', 'user_relationship_details', 'user_relationships', 'user_religion_politics', 'user_status', 'user_tagged_places', 'user_videos', 'user_website', 'user_work_history', 'ads_management', 'ads_read', 'business_management', 'manage_pages', 'pages_manage_cta', 'pages_manage_instant_articles', 'pages_messaging', 'pages_messaging_phone_number', 'pages_messaging_subscriptions', 'pages_show_list', 'publish_pages', 'read_page_mailboxes', 'rsvp_event', 'user_events', 'user_managed_groups'];
              $loginURL = $helper->getLoginUrl($redirectURL, $permissions);
              echo "<script>window.top.location.href='".$loginURL."'</script>";
            }
    }

      if (isset($_GET['code'])) {
        global $wpdb;
        $table = $wpdb->prefix . 'socialbooster_fbtable';
        $idfound = $wpdb->get_var("SELECT appid FROM $table ");
        $secretfound = $wpdb->get_var("SELECT appsecret FROM $table ");
        $FB = new \Facebook\Facebook([
                  'app_id' => $idfound,
                  'app_secret' => $secretfound,
                  'default_graph_version' => 'v2.10'
          ]);

          $helper = $FB->getRedirectLoginHelper();
        try {
          $accessToken = $helper->getAccessToken();
        } catch (\Facebook\Exceptions\FacebookResponseException $e) {
          echo "Response Exception: " . $e->getMessage();
          exit();
        } catch (\Facebook\Exceptions\FacebookSDKException $e) {
          echo "SDK Exception: " . $e->getMessage();
          exit();
        }

        if (!$accessToken) {
          _e( '<h1 class="label_design2">Invalid access token</h1>', 'social-booster' );
          exit();
        }

        $oAuth2Client = $FB->getOAuth2Client();
        if (!$accessToken->isLongLived())
          $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);

        $response = $FB->get("/me?fields=id, first_name, last_name, email, picture.type(large)", $accessToken);
        $userData = $response->getGraphNode()->asArray();
        $_SESSION['userData'] = $userData;
        $_SESSION['access_token'] = (string) $accessToken;
        $accesstokenfound = $_SESSION['access_token'];
        $matchid = $wpdb->get_var("SELECT appid FROM $table ");
        $wpdb->update($table, array('accsesstokensecret'=>$accesstokenfound), array('appid'=>$matchid)); 
        $wpdb->update($table, array('authorization'=>1), array('appid'=>$matchid)); 
        _e( '<h1 class="label_design2">Successfully Authorized</h1>', 'social-booster' );
        }
        ?>
        <div id="fbsavedinfo">
        <?php
        global $wpdb;
        $table = $wpdb->prefix . 'socialbooster_fbtable';
        $authorized = $wpdb->get_var("SELECT authorization FROM $table ");
        if($authorized!=1)
        {
          _e( '<h1 class="label_design">You are currently not authorized</h1>', 'social-booster' );
        }
        ?>
        </div>
        <?php
      if (isset($_POST["submit_form"]) && ($_POST["appidfb"] == "" || $_POST["appsecretfb"] == ""))
        {         
             _e( '<h1 class="label_design">Fill All Required Fields</h1>', 'social-booster' );     
        }
      ?>
  </body>
</html>







