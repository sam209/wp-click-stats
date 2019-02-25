<?php

/*
Plugin Name: WP Click Stats
Plugin URI: https://webdesign949.com
Description: Click statistics on links and buttons in website, ability to export and analyze results in csv.
Author: Sam H
Version: 1.7.1
Author URI: https://webdesign949.com
*/


 add_action('wp_enqueue_scripts', 'wp_click_stats');
function wp_click_stats() {   
    wp_enqueue_script( 'wp_click_stats', plugin_dir_url( __FILE__ ) . 'wp_click_stats.js', array('jquery'), '1.0.0', true );
	wp_localize_script( 'wp_click_stats', 'my_ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ),
				   'nonce' => wp_create_nonce('jsfh_e6y77')			) );
}
///////////
add_action( 'wp_ajax_add_data', 'add_data' );
add_action( 'wp_ajax_nopriv_add_data', 'add_data' ); 

function add_data(){
check_ajax_referer( 'jsfh_e6y77', 'security' );
	 global $wpdb;
	 $cs_is_link = $_POST['cs_is_link'];
	 $cs_is_button = $_POST['cs_is_button'];
	 $cs_url = $_POST['cs_url'];
	 $cs_text = $_POST['cs_text'];
	  $wcs = $wpdb->prefix . 'wp_click_stats';
	 $wpdb->query("INSERT INTO ".$wcs."(cs_url, cs_Is_link, cs_Is_button, cs_text) VALUES ('$cs_url', '$cs_is_link', '$cs_is_button', '$cs_text')");
 wp_die(); 
}

////////////////on install

 global $wp_click_stats_db_version;
$wp_click_stats_db_version = '1.0';
// function to create the DB / Options / Defaults					
function wp_click_stats_install() {
   	global $wpdb;
	global $wp_click_stats_db_version;
  $wcs = $wpdb->prefix . 'wp_click_stats';
 $charset_collate = $wpdb->get_charset_collate();
	// create the database table
	
		$sql="CREATE TABLE IF NOT EXISTS ".$wcs." (
 cs_id int(11) NOT NULL AUTO_INCREMENT,
 cs_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 cs_url varchar(100) DEFAULT NULL,
 cs_Is_link int(11) DEFAULT NULL,
 cs_Is_button int(11) DEFAULT NULL,
 cs_text varchar(100) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
 PRIMARY KEY  (cs_id)
)";
 
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		
		dbDelta($sql);
	add_option( 'wp_click_stats_db_version', $wp_click_stats_db_version );
 
}
// run the install scripts upon plugin activation
register_activation_hook(__FILE__,'wp_click_stats_install');
/////////////////deactivation///////////////////////
register_deactivation_hook( __FILE__, 'wp_click_stats_uninstall' );
function wp_click_stats_uninstall() {
     global $wpdb;
     $table_name = $wpdb->prefix . 'wp_click_stats';
     $sql = "DROP TABLE IF EXISTS ".$table_name;
     $wpdb->query($sql);
     delete_option("wp_click_stats_db_version");
}  
/////////////download csv//////////
function wcs_downloadcsv(){

}
///////////
//////////////////////admin/////////////////////
add_action( 'admin_menu', 'wcs_admin_menu' );

function wcs_admin_menu()
{
    add_menu_page(
        'WP Click Stats Admin',     // page title
        'WP Click Stats',     // menu title
        'manage_options',   // capability
        'wp-click-stats',     // menu slug
        'wcs_admin_page' // callback function
    );
}
function wcs_admin_page()
{
	echo '<h2>WP Click Stats Admin Page</h2>';
	echo '<p>Description:</p>';
	//downloadcsv
	echo '<br><div><button style="cursor:pointer;" id="wcs_admin_page_csv_btn">Download WP Click Stats Data CSV</button></div><br>';
	 wp_enqueue_script( 'wcs_admin_page', plugin_dir_url( __FILE__ ) . 'wcs_admin_page.js', array('jquery'), '1.0.0', true );
	wp_localize_script( 'wcs_admin_page', 'my_ajax_object3',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ),
				   'nonce' => wp_create_nonce('jsfh_e6y77')			) );
	//reset database
	echo '<br><div><button style="cursor:pointer;" id="wcs_admin_page_btn">Reset Data</button></div><br>';
	 wp_enqueue_script( 'wcs_admin_page', plugin_dir_url( __FILE__ ) . 'wcs_admin_page.js', array('jquery'), '1.0.0', true );
	wp_localize_script( 'wcs_admin_page', 'my_ajax_object2',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ),
				   'nonce' => wp_create_nonce('jsfh_e6y77')			) );
	//donate

}
add_action( 'wp_ajax_wcs_reset_data', 'wcs_reset_data' );
function wcs_reset_data(){
	check_ajax_referer( 'jsfh_e6y77', 'security' );
	global $wpdb;
     $table_name = $wpdb->prefix . 'wp_click_stats';
     $sql = "DELETE FROM ".$table_name;
     $wpdb->query($sql);
	 wp_die(); 
}
add_action( 'wp_ajax_wcs_download_csv', 'wcs_download_csv' );
function wcs_download_csv(){
	
	check_ajax_referer( 'jsfh_e6y77', 'security' );
	global $wpdb;
	$table_name = $wpdb->prefix . 'wp_click_stats';
	$sql ="SELECT * FROM ".$table_name;
	$filename = 'wp_click_stats.csv';
	//$headers = array('cs_id', 'cs_date', 'cs_url','cs_Is_link','cs_Is_button','cs_text');
////////////
   $header_row = array(
		0 => 'Id',
		1 => 'Date',
		2 => 'URL',
		3 => 'Is Link',
		4 => 'Is Button',
		5 => 'Text'
	);
	$data_rows = array();
	$cs_rows = $wpdb->get_results($sql);

	foreach ( $cs_rows as $u ) {
		$row = array();
		$row[0] = $u->cs_id;
		$row[1] = $u->cs_date;
		$row[2] = $u->cs_url;
		$row[3] = $u->cs_Is_link;
		$row[4] = $u->cs_Is_button;
		$row[5] = $u->cs_text;
		$data_rows[] = $row;
	}
	$fh = @fopen( 'php://output', 'w' );
	fprintf( $fh, chr(0xEF) . chr(0xBB) . chr(0xBF) );
	header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
	header( 'Content-Description: File Transfer' );
	header( 'Content-type: text/csv' );
	header( "Content-Disposition: attachment; filename={$filename}" );
	header( 'Expires: 0' );
	header( 'Pragma: public' );
	fputcsv( $fh, $header_row );
	foreach ( $data_rows as $data_row ) {
		fputcsv( $fh, $data_row );
	}
	fclose( $fh );
	die();
	 
}

?>