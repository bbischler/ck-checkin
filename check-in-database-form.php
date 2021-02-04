<?php
require_once('check-in-constants.php');
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

global $wpdb;
global $jal_db_version;
global $ck_table_form;
global $table_name_form;
$jal_db_version = '1.0';
$ck_table_form = FORMTABLE;
$table_name_form = $wpdb->prefix . $ck_table_form;

function create_database_form() {
	global $wpdb;
    global $jal_db_version;
    global $table_name_form;
	
	$charset_collate = $wpdb->get_charset_collate(); 

 
	$sql = "CREATE TABLE $table_name_form (
			id INT UNSIGNED NOT NULL AUTO_INCREMENT,
			ck_reg_time datetime DEFAULT '0000-00-00 00:00' NOT NULL,
			ck_date date DEFAULT '0000-00-00' NOT NULL,
			ck_time1 time DEFAULT '00:00' NOT NULL,
			ck_time2 time,
			ck_max mediumint(8) unsigned,
			PRIMARY KEY  (id)
	) $charset_collate;";

	maybe_create_table( $table_name_form, $sql );

	add_option( 'jal_db_version', $jal_db_version );

	global $wpdb;
	$installed_ver = get_option( "jal_db_version" );

	if ( $installed_ver != $jal_db_version ) {

		$sql = "CREATE IF NOT EXISTTABLE $table_name_form (
			id INT UNSIGNED NOT NULL AUTO_INCREMENT,
			ck_reg_time datetime DEFAULT '0000-00-00 00:00' NOT NULL,
			ck_date date DEFAULT '0000-00-00' NOT NULL,
			ck_time1 time DEFAULT '00:00' NOT NULL,
			ck_time2 time,
			ck_max mediumint(8) unsigned,
			PRIMARY KEY  (id)
		);";

		maybe_create_table( $table_name_form, $sql );
		update_option( "jal_db_version", $jal_db_version );
	}
}

function insert_data_database_form($date, $time1, $time2, $max){
	global $wpdb;
	global $table_name_form;
	$wpdb->insert( 
		$table_name_form, 
		array( 
			'ck_reg_time' => current_time( 'mysql' ), 
			'ck_date' => $date, 
			'ck_time1' => $time1,
			'ck_time2' => $time2,
			'ck_max' => $max, 
		) 
	);
}

function get_all_forms(){
    global $wpdb;
    global $table_name_form;
    return (array)$wpdb->get_results("SELECT * FROM $table_name_form");  	
}

function delete_form_by_id($id){
    global $wpdb;
    global $table_name_form;
    $wpdb->delete( $table_name_form, array( 'id' => $id ) );
}


function get_form_by_id($id){
    global $wpdb;
    global $table_name_form;
    return $wpdb->get_results("SELECT * FROM $table_name_form WHERE id=$id");
}

function myplugin_update_db_check() {
    global $jal_db_version;
    if ( get_site_option( 'jal_db_version' ) != $jal_db_version ) {
		create_database_form();
    }
}

if(isset($_POST['ck_delete'])){
	$delete = 0;

	echo '<script type="text/javascript">';
	echo ' confirm("Sure you want to delete? </br> All registered persons will be deleted!")';
	echo '</script>';	

	
	if($delete){
		delete_form_by_id($id=$_POST['deleteId']);
	}
}

?>