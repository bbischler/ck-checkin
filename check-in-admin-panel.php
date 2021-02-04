<?php
function ck_admin_menu_page(){
    add_menu_page( 'CK Check In', 'CK Check In', 'manage_options', 'ck-check-in-plugin', 'create_admin_panel' );
}
 
function create_admin_panel(){
?>
    <h1>Create a new Check In</h1>
    <form method='post' style='display:flex;justify-content:space-between;background:lightblue;margin:1em;padding: 2em;'>
    <label> Date: <input type='date' id='ck_date' name='ck_date'></label>
    <label> Time #1: <input type='time' id='ck_time1' name='ck_time1'></label>
    <label> Time #2: <input type='time' id='ck_time2' name='ck_time2'></label>
    <label> Max People: <input type='number' id='ck_max' name='ck_max' min='0'></label>
    <button class='button-primary' type='submit' id='ck_submit' name='ck_submit'>create</button>
   </form>

   <?php

    if (isset($_POST["ck_submit"])) {
        insert_data_database_form($_POST["ck_date"],$_POST["ck_time1"],$_POST["ck_time2"],$_POST["ck_max"]);
    }

    ?></br><h2>List of existing forms</h2>
    <div style='padding:1em; margin:1em; font-weight:bold; display:flex; flex-wrap:wrap'>
        <div style='width:10%'>ID</div>
        <div style='width:20%'>ShortCode</div>
        <div style='width:10%'>Created at</div>
        <div style='width:10%'>Event Time</div>
        <div style='width:20%'>Time #1</div>
        <div style='width:10%'>Time #2</div>
        <div style='width:10%'>Max People</div>
    </div>
    
    <?php

    $results = get_all_forms();
    for ($i = count($results)-1; $i >= 0 ; $i--) {
        $_id = $results[$i]->id; ?>
        <form method='post' style='padding:1em; margin:1em; background:LightGray; display:flex; flex-wrap:wrap'>
            <div style='width:10%'> <?php echo($_id); ?></div>
            <div style='width:20%'> [ck_check_in ck_id='<?php echo($_id); ?>']</div>
            <div style='width:10%'> <?php echo($results[$i]->ck_reg_time); ?> </div>
            <div style='width:10%'> <?php echo($results[$i]->ck_date); ?> </div>
            <div style='width:20%'> <?php echo($results[$i]->ck_time1); ?> </div>
            <div style='width:10%'> <?php echo($results[$i]->ck_time2 ); ?></div>
            <div style='width:10%'> <?php echo($results[$i]->ck_max); ?>  </div>
            <input type='hidden' name='deleteId' value= <?php echo($_id); ?>/>
            <button type='submit' class='submitdelete button delete' name='ck_delete'>Delete</button>
        </form>
        
    <?php
        }

}
?>