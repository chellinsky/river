<?php
if (isset($_POST['fin_proj']))
{
    $db = new db(EZSQL_DB_USER, EZSQL_DB_PASSWORD, EZSQL_DB_NAME, EZSQL_DB_HOST);
    echo '<h2 align="center">Finalization Results</h2>';
    foreach ($_POST as $key => $value)
    {
        if (!(strstr($key, 'hours')==false))
        {
            $vol_id = explode( '_', $key );
            $sql = "UPDATE ".TAB_PRE."_hours SET act_hours = ".$value." WHERE vol_id = ".$vol_id[1]." AND proj_id = ".$_POST['proj_key']." LIMIT 1";
            if (valid_input($value)) $results = $db->query($sql);
            else die("Invalid input.  Only use these characters: abcdefghijklmnopqrstuvwxyz0123456789_-,? '.()@:!&*=+;<>`~|");
        }
    }
    echo '<p>All volunteer records updated to reflect service</p>';
    foreach ($_POST as $key => $value)
    {
        if (!(strstr($key, 'notes')==false))
        {
            $vol_id = explode( '_', $key );
            $sql = "UPDATE ".TAB_PRE."_hours SET notes = '".$value."' WHERE vol_id = ".$vol_id[1]." AND proj_id = ".$_POST['proj_key']." LIMIT 1";
            if (valid_input($value)) $results = $db->query($sql);
            else die("Invalid input.  Only use these characters: abcdefghijklmnopqrstuvwxyz0123456789_-,? '.()@:!&*=+;<>`~|");
        }
    }
    echo '<p>All volunteer records updated with notes</p>';
    $sql = "UPDATE ".TAB_PRE."_projects SET num_child_serv = ".$_POST['num_child_serv'].", num_serv = ".$_POST['num_serv'].", num_sp = ".$_POST['num_sp'].", finalized = 1 WHERE id_key = ".$_POST['proj_key']." LIMIT 1";
    if (is_numeric($_POST['num_child_serv']) && is_numeric($_POST['num_serv']) && is_numeric($_POST['num_sp'])) $results = $db->query($sql);
    else die("Invalid input.  Number entries must contain numbers.");
    echo '<p>Project was successfully updated to reflect it has been finalized and is now archived.</p>';
}
elseif (isset($_POST['pick_proj']))
{

    echo '<h2 align="center">Finalization Form</h2>';
    echo '<p>Please complete the form to finalize the project.  If a volunteer did not show up, simply mark them down for zero hours.  If a volunteer showed up, but was not previously assigned to the project, you must press the back button and assign them before finalizing, or their hours will not be recorded toward the project.  Additionally, this is the last opportunity to update any of project information before it is archived.</p>';

    echo '<form name="fin_proj" method="post" action="index.php?choice=finalize_project">';
    
    $db = new db(EZSQL_DB_USER, EZSQL_DB_PASSWORD, EZSQL_DB_NAME, EZSQL_DB_HOST);
    $sql = 'SELECT * FROM '.TAB_PRE.'_projects WHERE name = "'.$_POST['project'].'" AND finalized=0';
    $proj_info = $db->get_row($sql);
    echo '<input type="hidden" name="proj_key" value="'.$proj_info->id_key.'" />';
    echo '<h3>'.$proj_info->name.'</h3>';
    echo '<p>'.$proj_info->company.'<br />'.$proj_info->address.'<br />'.$proj_info->city.', '.$proj_info->state.' '.$proj_info->zip.'</p>';
    echo '<p>'.$proj_info->sp_name.'<br />'.$proj_info->proj_date.'<br />'.$proj_info->proj_time.'</p>';
    echo '<p>'.$proj_info->description.'</p>';
    echo '<p>Goal Number of Volunteers: '.$proj_info->num_volunteers.'</p>';
    
    $sql = "SELECT * FROM ".TAB_PRE."_hours WHERE proj_id = ".$proj_info->id_key." ORDER BY vol_id, shift_id";
    $volunteers = $db->get_results($sql);
    echo '<h3> Volunteers for '.$_POST['project'].'</h3><br />';

    echo '<table border=1><tr bgcolor="white"><td width="1%">Volunteer Name</td><td width="1%">Company/Organization</td><td>Phone</td><td>Assigned Start</td><td>Assigned Length</td><td>Actual Start</td><td>Actual Length</td></tr>';
    sort_vol_alpha($volunteers, $proj_info);//sorts the volunteers
    echo '</table>';
    
    echo '<p><table><tr width="100%"><td align="center" width="33.33%">Number of Youth Served:<br /><input type="text" size=4 name="num_child_serv" /><td><td align="center" width="33.33%">Total Number Served:<br /><input type="text" size=4 name="num_serv" /></td><td align="center" width="33.34%">Number of Service Partners (ensure they are listed above):<br /><input type="text" size=3 name="num_sp" /></td></tr></table></p>';
    echo '<p align="center"><input type="submit" name="fin_proj" value="Finalize and Archive Project" /></p>';
    
    echo '</form>';
    
}
else
{

    echo '<h2 align="center">Select a Project to Finalize</h2><br />';
    
    echo '<form name="pick_proj" method="post" action="index.php?choice=finalize_project"><p align="center"><select name="project">';
    
    $db = new db(EZSQL_DB_USER, EZSQL_DB_PASSWORD, EZSQL_DB_NAME, EZSQL_DB_HOST);
    $sql = "SELECT name FROM ".TAB_PRE."_projects WHERE finalized=0";
    $results = $db->get_results($sql);
    foreach ($results as $result)
    {
    	echo ('<option>'.$result->name.'</option>');
    }
    echo ('</select></p>');
    echo ('<br /><p align="center"><input type="submit" name="pick_proj" value="Finalize This Project" /></p>');
    
    echo '</form>';
}

?>