<?php

/**
 * Author : Polawattana Chaiphugdee
 * FB : https://fb.com/kla.chai
 * Author URL : https://tampage.com , https://pnxinfo.com
 * Version : 1.0
 * License: GNU General Public License v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 *
 */


/**
 * ส่วนนี้ copy ไปวางใน wp-config.php หลังจากแถวของ $table_prefix
 * เปลี่ยนเป็นค่าตามจริงที่เรียกใช้งาน
 */
define('PUSH_URL', 'http://example-site.local');
define('FILE_PUSH', '/wp-admin/setuser_api.php');
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSNAME', '1234');
//------------------------------------------------------------------------------





/**
 * ส่วนนี้สำหรับทดสอบกับไฟล์นี้
 */
define('WP_USE_THEMES', true);
require_once( '../wp-config.php' );
wp();

$res = set_cross_site_user(1);
echo $res;
//------------------------------------------------------------------------------




/**
 * ส่วนนี้ copy ไปวางใน functions.php ของธีมที่ใช้เป็น origin site
 * ถ้าจะให้ทั้งสอง site ทำการ sync กันได้ ก็ใส่ทั้งสิง site
 */
add_action('user_register', 'set_cross_site_user', 10, 1);
add_action('profile_update', 'set_cross_site_user', 10, 2);

function set_cross_site_user($user_id, $olddata = false) {

    global $wpdb;
    
    $select = 'user_login,user_pass,user_nicename,user_email,display_name';
    $sql = "select $select from ".$wpdb->prefix."users where ID = $user_id";
    $res_users = $wpdb->get_row($sql);

    
    $users = $res_users;
    
    $usermeta = array(
        'nickname' => get_user_meta($user_id, 'nickname',true),
        'first_name' => get_user_meta($user_id, 'first_name',true),
        'last_name' => get_user_meta($user_id, 'last_name',true),
    );

    $body_sent = array(
        'aduser' => ADMIN_USERNAME,
        'adpass' => ADMIN_PASSNAME,
        'usetest' => false,
        'updated' => ($olddata === false)? false : true,
        'users' => $users,
        'usermeta' => $usermeta,
    );

    $args = array(
        'body' => $body_sent,
        'timeout' => '5',
        'redirection' => '5',
        'httpversion' => '1.0',
        'blocking' => true,
        'headers' => array(),
        'cookies' => array()
    );

    $response = wp_remote_post(PUSH_URL . FILE_PUSH, $args);
    $bodyg = wp_remote_retrieve_body($response);
    return $bodyg;
}
//------------------------------------------------------------------------------
