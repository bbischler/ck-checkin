<?php
require_once('check-in-constants.php');
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
global $wpdb;
global $jal_db_version;
global $ck_table_user;
global $table_name_user;
$jal_db_version = '1.0';
$ck_table_user = USERTABLE;
$table_name_user = $wpdb->prefix . $ck_table_user;

global $ck_table_form;
global $table_name_form;
$ck_table_form = FORMTABLE;
$table_name_form = $wpdb->prefix . $ck_table_form;


function create_database_user() {
	global $wpdb;
    global $jal_db_version;
    global $table_name_user;
	global $table_name_form;
		
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name_user (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			id_form INT UNSIGNED NOT NULL,
			ck_username tinytext NOT NULL,
			ck_attend1 boolean DEFAULT false,
			ck_attend2 boolean DEFAULT false,
			ck_reg_time datetime DEFAULT '0000-00-00 00:00' NOT NULL,
			PRIMARY KEY  (id),
			FOREIGN KEY (id_form) REFERENCES $table_name_form(id) ON DELETE CASCADE	
				
	) $charset_collate;";

	maybe_create_table( $table_name_user, $sql );
	add_option( 'jal_db_version', $jal_db_version );

	global $wpdb;
	$installed_ver = get_option( "jal_db_version" );

	if ( $installed_ver != $jal_db_version ) {

		$sql = "CREATE TABLE $table_name_user (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			id_form INT UNSIGNED NOT NULL,
			ck_username tinytext NOT NULL,
			ck_attend1 boolean DEFAULT false,
			ck_attend2 boolean DEFAULT false,
			ck_reg_time datetime DEFAULT '0000-00-00 00:00' NOT NULL,
			PRIMARY KEY  (id),
			FOREIGN KEY (id_form) REFERENCES $table_name_form(id) ON DELETE CASCADE
		);";

		maybe_create_table( $table_name_user, $sql );
		update_option( "jal_db_version", $jal_db_version );
	}
}

function insert_data_database_user($name, $attend1, $attend2, $formid){
	global $wpdb;
	global $table_name_user;
	global $table_name_form;

	$wpdb->insert( 
		$table_name_user, 
		array( 
			'id_form' => $formid, 
			'ck_username' => $name,
			'ck_attend1' => $attend1,
			'ck_attend2' => $attend2,
			'ck_reg_time' => current_time( 'mysql' )
		)
	);

	$id = $wpdb->insert_id;
 
	if($id == 0){
		return "Beim Eintragen ist ein Fehler aufgetreten";
	}
}

function count_attendees($id, $first){
	global $wpdb;
	global $table_name_user;

	if ($first){
		return $wpdb->get_var("SELECT COUNT(*) FROM $table_name_user WHERE id_form = $id AND ck_attend1 = 1");
	}else{
		return $wpdb->get_var("SELECT COUNT(*) FROM $table_name_user WHERE id_form = $id AND ck_attend2 = 1");
	}
}

function count_all_attendees($id){
	global $wpdb;
	global $table_name_user;
	return $wpdb->get_var("SELECT COUNT(*) FROM $table_name_user WHERE id_form = $id");
}
?>