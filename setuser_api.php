<?php

/**
 * Author : Polawattana Chaiphugdee
 * FB : https://fb.com/kla.chai
 * Author URL : https://tampage.com , https://pnxinfo.com
 * Version : 1.0
 * License: GNU General Public License v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html 
 */

/**
 * copy file ไปไว้ที่ {site}/wp-admin
 */
define('WP_USE_THEMES', false);
require_once( '../wp-config.php' );
wp();

$data = $_POST;

$admin_username = isset($data['aduser']) ? $data['aduser'] : false;
$admin_pass = isset($data['adpass']) ? $data['adpass'] : false;


if (!$admin_username || !$admin_pass) {
    error_log("[sync_user] : request authen failed");
    return_json();
}

//must be pass admin authentication to use this feature
if (!wp_login($admin_username, $admin_pass)) {
    error_log("[sync_user] : request authen failed");
    return_json($result);
}

/**
 * 
 * now insert user data into DB
 * 
 * Structure :
 * $user_data['users'] => ('user_login','user_pass','user_nicename','user_email','user_status','display_name');
 * $user_data['usermeta'] => ('nickname','first_name','last_name');
 * 
 * remark : User ID in this site will be not === User ID in original site
 * 
 */
#test data
$testdata = (isset($data['usetest']) && $data['usetest'] == true) ? 'test1' : false;
if ($testdata != false) {
    
    $user_data = array(
        'users' => array(
            'user_login' => $testdata,
            'user_pass' => $testdata,
            'user_nicename' => $testdata,
            'user_email' => $testdata,
            'display_name' => $testdata,
        ),
        'usermeta' => array(
            'nickname' => $testdata,
            'first_name' => $testdata,
            'last_name' => $testdata,
        ),
    );
    return_json($user_data);
} else {

    error_log("not test checkvalue");
    
    $is_updated = isset($data['updated']) ? $data['updated'] : false;
    $userdata = isset($data['users']) ? $data['users'] : false;
    $usermeta = isset($data['usermeta']) ? $data['usermeta'] : false;
    $main_user_id = isset($data['main_user_id']) ? $data['main_user_id'] : false;

    if (!$userdata || !$usermeta) {
        error_log("error user data");
        $result = array(
            'result' => false,
            'msg' => 'Missing some argument.',
        );
        return_json($result);
    }

    $user_data = array(
        'users' => $userdata,
        'usermeta' => $usermeta,
    );

    global $wpdb;

    if (!$is_updated) {

        error_log("[sync_user] : start insert user data");
        $res_user_id = $wpdb->insert($wpdb->prefix.'users', $userdata);

        if ($res_user_id != false) {

            foreach ($usermeta as $key => $val) {

                $resrow[$key] = add_user_meta($res_user_id, $key, $val);
            }
            if (!in_array(false, $resrow)) {

                $result = array(
                    'result' => true,
                    'msg' => 'insert user success',
                );
                return_json($result);
                return;
            }
        }
        return_json();
    } else {

        error_log("[sync_user] : start update user data");
        global $wpdb;
        
        $sql = "select ID from " . $wpdb->prefix . "users where user_login = '".$userdata['user_login']."'";
        $res_usID = $wpdb->get_row($sql);
        $usID = intval($res_usID->ID);
        if($usID < 1 || $res_usID == false){
            return_json();
        }

        $res_update = $wpdb->update($wpdb->prefix.'users', $userdata, array('ID' => $usID));
        foreach ($usermeta as $key => $val) {

            $resrow_update[$key] = update_user_meta($usID, $key, $val);
        }
        if (!in_array(false, $resrow_update) && $res_update != false) {

            $result = array(
                'result' => true,
                'msg' => 'update user success',
            );
            return_json($result);
            return;
        }
    }
}

/**
 * Utility Function
 */
function return_json($result = array('result' => false, 'msg' => 'error has occur.')) {
    header('Content-Type: application/json');
    echo json_encode($result);
    die();
}
