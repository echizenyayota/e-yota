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

      
            <ul id="nav">

              <li><a href="admin.php?page=socialbooster_autopost">Facebook</a></li>
              <li><a href="#">Twitter</a></li>
              <li><a href="admin.php?page=socialbooster_gplus">Google Plus</a></li>
              <li><a href="admin.php?page=socialbooster_instragram">Instragram</a></li>
              <li><a href="admin.php?page=socialbooster_tumblr">Tumblr</a></li>

            </ul>
      
    </div>

      <div class="soc_form_primary">

          
            <div class="fbinfo_form">
                  <h3>Information Form for Twitter</h3>
                  <div id="twsavedinfo">

                  </div>
                 <form id="twform" action="" method="post">

                    <label class="label_design">Username</label><br>
                    <input type="text" name="usernametw" id="fullname" placeholder=<?php             
                    global $wpdb;
                    $tabletw = $wpdb->prefix . 'socialbooster_twtable';
                    $username = $wpdb->get_var("SELECT username FROM $tabletw ");
                    _e( $username, 'social-booster' );
                    ?>><br>
                    <label class="label_design">Consumer key</label><br>
                    <input type="text" name="conkeytw" id="fullname" placeholder=<?php             
                    global $wpdb;
                    $tabletw = $wpdb->prefix . 'socialbooster_twtable';
                    $consumerkey = $wpdb->get_var("SELECT consumerkey FROM $tabletw ");
                    _e( $consumerkey, 'social-booster' );
                    ?>><br>
                    <label class="label_design">Consumer Secret</label><br>
                    <input type="text" name="consecrettw" id="fullname" placeholder=<?php             
                    global $wpdb;
                    $tabletw = $wpdb->prefix . 'socialbooster_twtable';
                    $consumersecret = $wpdb->get_var("SELECT consumersecret FROM $tabletw ");
                    _e( $consumersecret, 'social-booster' );
                    ?>><br>
                    <label class="label_design">Access token</label><br>
                    <input type="text" name="accesstokentw" id="fullname" placeholder=<?php             
                    global $wpdb;
                    $tabletw = $wpdb->prefix . 'socialbooster_twtable';
                    $accesstoken = $wpdb->get_var("SELECT accesstoken FROM $tabletw ");
                    _e( $accesstoken, 'social-booster' );
                    ?>><br>
                    <label class="label_design">Access token secret</label><br>
                    <input type="text" name="accesstokensecrettw" id="fullname" placeholder=<?php             
                    global $wpdb;
                    $tabletw = $wpdb->prefix . 'socialbooster_twtable';
                    $accesstokensecret = $wpdb->get_var("SELECT accsesstokensecret FROM $tabletw ");
                    _e( $accesstokensecret, 'social-booster' );
                    ?>><br><br>
                    <input type="submit" name="submit_form" id="submit_form" value="Authorize">&nbsp;
                    <img src="<?php echo admin_url('/images/spinner.gif');?>" class="waiting" id="loadimg" style="display:none;" /> 
                  </form> 
            </div>
      </div>

        <?php 

            global $wpdb;
            $table = $wpdb->prefix . 'socialbooster_twtable';
            $username = $wpdb->get_var("SELECT username FROM $tabletw ");
            if(isset($_GET['oauth_token'])){
               $otoken = $_GET['oauth_token'];
               _e( '<h1 class="label_design2">Successfully Authorized</h1>', 'social-booster' );
            }

            if(isset($_GET['oauth_verifier'])){
               $overify = $_GET['oauth_verifier'];
            }

            if(isset($otoken) && isset($overify) ){
              $wpdb->update($table, array('authorization'=>1), array('username'=>$username));
            } ?>
            <div id="twsavedinfo">
            <?php
            global $wpdb;
            $table = $wpdb->prefix . 'socialbooster_twtable';
            $auhorization = $wpdb->get_var("SELECT authorization FROM $table ");
            if ($auhorization == 0) {
              _e( '<h1 class="label_design">You are currently not authorized</h1>', 'social-booster' );
            ?>
            </div>
            <?php
            }
            ?>
    
  </body>
</html>







