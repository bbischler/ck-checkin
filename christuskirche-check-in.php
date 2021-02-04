<?php
/*
Plugin Name: CK Check In
Description: A plugin that allows visitors to register for a service 
Author: Bastian Bischler
Version: 1.0
*/
include 'check-in-admin-panel.php';
include 'check-in-database-form.php';
include 'check-in-database-user.php';

register_activation_hook( __FILE__, 'create_databases' );

add_action('plugins_loaded', 'myplugin_update_db_check');
add_action('admin_menu', 'ck_admin_menu_page');
add_action('activated_plugin','my_save_error');
add_shortcode('ck_check_in', 'register_shortcode');

// Debug information
function my_save_error()
{
    file_put_contents(dirname(__file__).'/error_activation.txt', ob_get_contents());
}

function create_databases(){
	createDatabaseForm();
	createDatabaseUser();
}

function register_shortcode( $atts ) {
	$atts = shortcode_atts( array(
        'ck_id' => '0',
    ), $atts, 'ck_check_in' );
    return render_form($atts['ck_id']);
}


function render_form($id){
	$form = get_form_by_id($id);

	foreach ($form as $value) {
		$attendees1 = count_attendees($id, TRUE);
		$attendees2 = count_attendees($id, False);
		$disable1 = ($attendees1 < $value->ck_max) ? '' : 'disabled';
		$disable2 = ($attendees2 < $value->ck_max) ? '' : 'disabled';

		?>
		<div style='margin: 1em auto; padding: 2em'>
			<form method='post'>
			<table style='padding: 2em; margin-bottom:1em'>
				<tr>
					<td> <div style=''><?php echo $value->ck_date; ?> </div></td>
					<td> <div style='display:flex;justify-content:center'><?php echo $value->ck_time1; ?> </div></td>
					<td> <div style='display:flex;justify-content:center'><?php echo $value->ck_time2; ?> </div></td>
				</tr>
				<tr>
					<td><?php echo count_all_attendees($id); ?> Teilnehmer</td>
					<td> <div style='display:flex;justify-content:center'><?php echo $attendees1 ?>/<?php echo $value->ck_max; ?> </div></td>
					<td> <div style='display:flex;justify-content:center'><?php echo $attendees2 ?>/<?php echo $value->ck_max; ?> </div></td>
				</tr>
				<tr style='background:lightblue'>
					<td> <input type='text' id='ck_name' name='ck_name' placeholder='Name'></td>
					<td style='text-align:center'> <input type="checkbox" id="ck_attend1" name="ck_attend1"<?php echo $disable1; ?>></td>
					<td style='text-align:center'> <input type="checkbox" id="ck_attend2" name="ck_attend2"<?php echo $disable2; ?>></td>
				</tr>
			</table>
				<input type='hidden' name='formid' id="formid" value=<?php echo($id); ?>>	
				<button class='button' type='submit' name='ck_login'>Anmelden</button>
			</form>
		</div>
		<?php
	}
}

if (isset($_POST["ck_login"])) {
	$name = '';
	$at1 = 0;
	$at2 = 0;

	if(isset($_POST["ck_name"])){
		$name = $_POST["ck_name"];
	}
	if(isset($_POST["ck_attend1"])){
		$at1 = 1;
	}
	if(isset($_POST["ck_attend2"])){
		$at2 = 1;
	}

	if(!empty($name) && ($at1 || $at2)){
    	insert_data_database_user($name, $at1, $at2, $_POST["formid"]);
    }
}

?>