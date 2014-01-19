<?php

/*
Functions used to deal with the database and data
*/

function add_data($inputarray, $table)
{  
	$db = new db(EZSQL_DB_USER, EZSQL_DB_PASSWORD, EZSQL_DB_NAME, EZSQL_DB_HOST);
  
    foreach($inputarray as $el_key => $el_val) //tests for valid input
    {
        if($el_key != "emerg_form") {echo '<p>'.$el_key." => ".$el_val."</p>";}
        if(!valid_input($el_val)) {die ("<p>Invalid entry!  Feel free to press back and try again</p>");}
    }
    //use a foreaches to create the sql call to the database
    if(!isset($inputarray['id_key']))
    {
        $query = "INSERT INTO ".$table." (id_key, ";
        foreach($inputarray as $el_key => $el_val)//put in the column headers
        {
            if($el_key != "emerg_form") {$query .= $el_key.", ";}
        }
        if($table == TAB_PRE.'_volunteers')
        {
         	if(!isset($inputarray['consent_form']))
         	{
         		$query .= "consent_form, ";
         	}
         	if(!isset($inputarray['photo_form']))
         	{
         		$query .= "photo_form, ";
         	}
        }
        $query .= "create_date) VALUES (NULL, ";//add in a date stamp for creation date
        foreach($inputarray as $el_key => $el_val)//put in the values now
        {
            if($el_key != "emerg_form") {$query .= "'".$el_val."', ";}
        }
        if($table == TAB_PRE.'_volunteers')
        {
        	if(!isset($inputarray['consent_form']))
        	{
        		$query .= "0, ";
        	}
        	if(!isset($inputarray['photo_form']))
        	{
        		$query .= "0, ";
        	}
        }
        $query .= "now())";
    }
    else
    {
        $query = "UPDATE ".$table." SET ";
       foreach($inputarray as $el_key => $el_val)
        {
            if($el_key != "id_key")
            {
                if($el_key != "emerg_form") {$query .= $el_key." = '".$el_val."', ";}
           }
        }
        if($table == TAB_PRE.'_volunteers')
        {
        	if(!isset($inputarray['consent_form']))
        	{
        		$query .= "consent_form = 0, ";
        	}
        	if(!isset($inputarray['photo_form']))
        	{
        		$query .= "photo_form = 0, ";
        	}
        }
        $query{strlen($query)-2} = " ";
        $query .= "WHERE id_key = ".$inputarray['id_key'];
    }
//    echo $query."<br />";
    $result = $db->query($query);
//    $db->debug();
    echo '<p>'.$db->rows_affected.' row(s) entered into database.</p>';
    if($table == TAB_PRE.'_volunteers')
    {
        if($inputarray['emerg_form'] == 'yes_emerg')
        {    
            if(!isset($inputarray['id_key'])) {$id_key = $db->insert_id;}
            else {$id_key = $inputarray['id_key'];}
            echo '<p>id_key = '.$id_key.'</p>';
            emergency_add($id_key);
        }
    }
}


function edit_funcs($table, $type)
{
if (isset($_POST['remove_vol']))
{
	$i = 0;
	foreach($_POST as $key => $value)
	{
		if (is_numeric($key))
		{
			//remove from _shifts table where vol_id and proj_id are appropriate
      $db = new db(EZSQL_DB_USER, EZSQL_DB_PASSWORD, EZSQL_DB_NAME, EZSQL_DB_HOST);
			$results = $db->query("DELETE FROM ".TAB_PRE."_hours WHERE vol_id = ".$value." AND proj_id = ".$_POST['proj_id']);
			$i++;
		}
	}
	echo $i.' volunteer(s) removed from project.';
}
elseif (isset($_POST['restart_search']))
{
/*

At some point all of this cookie nonsense can be placed in a table of current user data,
perhaps the sessions table to reduce reliance on cookies to make this all work.  This
is the quick, easy, and dirty solution for now.

*/
  setcookie ( "search[$table.'criteria']", $_COOKIE['search']["$table.\'criteria\'"], (time() - ( 60 * 60 )), "/" );
	setcookie ( "search[$table.'search_term']", $_COOKIE['search']["$table.\'search_term\'"], (time() - ( 60 * 60 )), "/" ); 
	setcookie ( "search[$table.'num_col']", $_COOKIE['search']["$table.\'num_col\'"], (time() - ( 60 * 60 )), "/" ); 
	setcookie ( "search[$table.'sort_by']", $_COOKIE['search']["$table.\'sort_by\'"], (time() - ( 60 * 60 )), "/" );
	echo '<p>Here one can edit and search for specific volunteers</p>';
  include("basic_search.php");	
}
elseif (isset($_POST['submit_to_edit']))
{
    if(valid_input($_POST['submit_to_edit']))
    {
        foreach ($_POST as $key => $value)
        {//call to add_volunteer function
          if ($key == 'id_key')
          {
            $db = new db(EZSQL_DB_USER, EZSQL_DB_PASSWORD, EZSQL_DB_NAME, EZSQL_DB_HOST);
            $sql = "SELECT * FROM ".$table." WHERE id_key = ".$value;
//            echo $sql;
            $old_stuff = $db->get_row($sql);
            add_data_form($table, $old_stuff);
            
            if($table == TAB_PRE.'_projects')
            {
				$sql = 'SELECT * FROM '.TAB_PRE.'_projects WHERE id_key = '.$value;
				$proj_info = $db->get_row($sql);
				$assigned_volunteers = $db->get_results("SELECT * FROM ".TAB_PRE."_hours WHERE proj_id = ".$value." ORDER BY vol_id, shift_id");
       			echo '<h3>The following volunteers are assigned to the project:</h3>';
				echo '<p>Use the button at the bottom of the page to remove selected volunteers from the project</p>';
				echo '<form action="index.php?choice=edit_project" method="post"><table border="1">';
		        sort_vol_alpha($assigned_volunteers, $proj_info);
				echo '</table><input type="hidden" name="proj_id" value="'.$proj_info->id_key.'" /><p align="center">';
				echo '<input type="submit" name="remove_vol" value="Remove Selected Volunteers" /></p></form>';
            }
            elseif($table == TAB_PRE.'_volunteers')//use this area for current projects as well
            {
                //set-up variables to run statistics about the volunteer's history
                $total_hours = 0;
                $current_FY_hours = 0;
                $prev_FY_hours = 0;
                $sql = "SELECT * FROM ".TAB_PRE."_hours WHERE vol_id = ".$value;
                $assigned_projects = $db->get_results($sql);
                echo '<h3>Archived Project Participation:</h3>';
                echo '<table border=1><tr width="100%" bgcolor="white"><td>Project Name</td><td>Project Date</td><td>Assigned Hours</td><td>Actual Hours</td><td>Notes</td>';
                foreach($assigned_projects as $assigned_project)
                {
                    $sql = "SELECT * FROM ".TAB_PRE."_projects WHERE id_key = ".$assigned_project->proj_id." AND finalized = 1";
                    $proj_info = $db->get_row($sql);
                    if ($db->num_rows == 1)
                    {
                    echo '<tr><td>'.$proj_info->name.'</td>';
                    echo '<td>'.$proj_info->proj_date.'</td>';
                    echo '<td>'.$assigned_project->shift_length.'</td>';
                    echo '<td>'.$assigned_project->act_hours.'</td>';
                    echo '<td>'.$assigned_project->notes.'</td></tr>';
                    $total_hours += $assigned_project->act_hours;
                    
                    }
                }
                echo '</table>';//end display of old projects
                //begin running a reporting statistics on old projects
                echo '<h4>Lifetime Completed Hours = '.$total_hours.'</h3>';
            }
          }
        }
    }
    else
        {//display the form again and denote invalid input was made
        echo "<b>Invalid input in your search!  Please only include alpha-numerics and these characters: _- '.()@.  Thanks for helping us keep this secure.";
        include("basic_search.php");
        }
}
elseif (isset($_POST['submit_to_delete']))
{
/*At some point, I want to change this to preserve the volunteers
for project archive reasons, but not allow them to be editted or viewed
or added to projects.  Similar to the "finalized projects" idea, but
with volunteers.*/
  foreach ($_POST as $key => $value)
  {//call to delete queries function
     if ($key == 'id_key')
     {
         $db = new db(EZSQL_DB_USER, EZSQL_DB_PASSWORD, EZSQL_DB_NAME, EZSQL_DB_HOST);
         $deleted_query = $db->query("DELETE FROM ".$table." WHERE id_key = ".$value." LIMIT 1");
         if($table == TAB_PRE.'_volunteers')
         {
            $deleted_query = $db->query("DELETE FROM ".TAB_PRE."_emergency WHERE vol_id = ".$value." LIMIT 1");
            $deleted_query = $db->query("DELETE FROM ".TAB_PRE."_hours WHERE vol_id = ".$value);
         }
         elseif ($table == TAB_PRE.'_projects')
         {
            $deleted_query = $db->query("DELETE FROM ".TAB_PRE."_hours WHERE proj_id = ".$value);
         }
         echo '<p>Project / Volunteer removed from database.</p>';
		 }
  }
}
elseif (isset($_POST['criteria']))
{
/*

This elseif and the next can be combine into one with the change of $_POST and $_COOKIE
to some other array and then used universally in a "display edit results" function or
something of the like.

*/
    if (valid_input($_POST['criteria']) && valid_input($_POST['search_term']))
        {//send the query and display the results
        setcookie ( "search[$table.'criteria']", $_POST['criteria'], (time() + ( 60 * 30 )), "/" );
				setcookie ( "search[$table.'search_term']", $_POST['search_term'], (time() + ( 60 * 30 )), "/" ); 
				setcookie ( "search[$table.'num_col']", $_POST['num_col'], (time() + ( 60 * 30 )), "/" ); 
				setcookie ( "search[$table.'sort_by']", $_POST['sort_by'], (time() + ( 60 * 60 )), "/");

        echo '<form name="edit_delete" action="index.php?choice='.$type.'" method="post">';
        $db = new db(EZSQL_DB_USER, EZSQL_DB_PASSWORD, EZSQL_DB_NAME, EZSQL_DB_HOST);

        $ezr = new ez_results();
        
        $sql = "SELECT * FROM ".$table." WHERE ".$_POST['criteria']." LIKE '%".$_POST['search_term']."%'";
        if ($table == TAB_PRE.'_projects')
        {
            $sql .= " AND finalized=0";
        }
        $sql .= " ORDER BY ".$_POST['sort_by'];
//        echo $sql;

        $ezr->query_mysql($sql);
        $ezr->results_row = create_edit_col_template(/*$_POST['num_col']*/ 10 );
        $results = $ezr->get();
        $ezr->display();
        if ($results != "No Results")
        {
        echo '<br /><input type="submit" name="submit_to_edit" value="Submit to Edit" />';
				echo ' &nbsp;| &nbsp;<input type="submit" name="submit_to_delete" value="Submit to Delete" onClick="return disp_confirm();" />';
        }//end test for real results
        echo '</form><br /><form action="index.php?choice='.$type.'" method="post"><input type="submit" name="restart_search" value="Restart Search" /></form>';//you always want to be able to restart your search, eh?
	   }
    else
        {//display the form again and denote invalid input was made
        echo "<b>Invalid input in your search!  Please only include alpha-numerics and these characters: _- '.()@.  Thanks for helping us keep this secure.";
        include("basic_search.php");
        }

}//ends check for post data
elseif (isset($_COOKIE['search']["$table.\'criteria\'"]))
{
    if (valid_input($_COOKIE['search']["$table.'criteria'"]) && valid_input($_COOKIE['search']["$table.'search_term'"]))
        {//send the query and display the results
        setcookie ( "search[$table.'criteria']", $_COOKIE['search']["$table.\'criteria\'"], (time() + ( 60 * 60 )), "/" );
				setcookie ( "search[$table.'search_term']", $_COOKIE['search']["$table.\'search_term\'"], (time() + ( 60 * 60 )), "/" ); 
				setcookie ( "search[$table.'num_col']", $_COOKIE['search']["$table.\'num_col\'"], (time() + ( 60 * 60 )), "/" ); 
				setcookie ( "search[$table.'sort_by']", $_COOKIE['search']["$table.\'sort_by\'"], (time() + ( 60 * 60 )), "/" );

        echo '<form name="edit_delete" action="index.php?choice='.$type.'" method="post">';
        $db = new db(EZSQL_DB_USER, EZSQL_DB_PASSWORD, EZSQL_DB_NAME, EZSQL_DB_HOST);

        $ezr = new ez_results();
        
        $sql = "SELECT * FROM ".$table." WHERE ".$_COOKIE['search']["$table.\'criteria\'"]." LIKE '%".$_COOKIE['search']["$table.\'search_term\'"]."%'";
        if ($table==TAB_PRE.'_projects')
        {
            $sql .= " AND finalized=0";
        }
        $sql .= " ORDER BY ".$_COOKIE['search']["$table.\'sort_by\'"];
        
 //       echo $sql;

        $ezr->query_mysql($sql);
        $ezr->results_row = create_edit_col_template(/*$_COOKIE['search']["$table.\'num_col\'"]*/ 10 );
        $ezr->display();
        echo '<br /><input type="submit" name="submit_to_edit" value="Submit to Edit" />';
				echo ' &nbsp;| &nbsp;<input type="submit" name="submit_to_delete" value="Submit to Delete" onClick="return disp_confirm();" /></form>';
        echo '<br /><form action="index.php?choice='.$type.'" method="post"><input type="submit" name="restart_search" value="Restart Search" /></form>';
	   }
    else
        {//display the form again and denote invalid input was made
        echo "<b>Invalid input in your search!  Please only include alpha-numerics and these characters: _- '.()@.  Thanks for helping us keep this secure.";
        include("basic_search.php");
        }

}
else
{
//	print_r ($_COOKIE['search']);
//	echo $table;
 echo '<p>Here one can edit and search for specific items</p>';
 include("basic_search.php");	
}
}

function add_data_form($table, $current_data = NULL)
{
if ($table == TAB_PRE."_volunteers")
{
		echo '<h2>Volunteer Entry Form</h2>';
    echo '<p>Please complete this form and hit submit to add/update a volunteer in the database.  You can always update the information later by clicking on the "Edit Volunteer" link to the left.  Items marked with an * are required.</p>';
    echo '<form name="add_volunteer" method="post" action="index.php?choice=add_volunteers">';
    echo '<table>';
    if (isset($current_data->id_key)){echo '<tr><td>ID Key = '.$current_data->id_key.'</td><td><input type="hidden" name="id_key" value='.$current_data->id_key.' /></td></tr>';}
    echo '<tr><td align="right">First Name*:</td><td><input type="text" name="first_name" value="'.$current_data->first_name.'" /></td></tr><tr><td align="right">Middle Name:</td><td><input type="text" name="middle_name" value="'.$current_data->middle_name.'" /></td></tr><tr><td align="right">Last Name*:</td><td><input type="text" name="last_name" value="'.$current_data->last_name.'" /></p>';
		echo '<tr><td align="right">Company:</td><td><input type="text" name="company" value="'.$current_data->company.'" /></td></tr>';
    echo '<tr><td align="right">Address:</td><td><input type="text" name="address" value="'.$current_data->address.'" /></td></tr>';
    echo '<tr><td align="right">City:</td><td><input type="text" name="city" value="'.$current_data->city.'" /></td></tr><tr><td align="right">State:</td><td><input type="text" name="state" value="'.$current_data->state.'" size=2 /></td></tr><tr><td align="right">Zip Code:</td><td><input type="text" name="zip" value="'.$current_data->zip.'" size=5 maxlength=5 /></td></tr>';
    echo '<tr><td align="right">Phone One (with area code and only numerics)*:</td><td><input type="text" name="phone1" size="10" maxlength="10" value="'.$current_data->phone1.'" /></td></tr>';
    echo '<tr><td align="right">Phone Two (with area code and only numerics):</td><td><input type="text" name="phone2" size="10" maxlength="10" value="'.$current_data->phone2.'" /></td></tr>';
    echo '<tr><td align="right">Email:</td><td><input type="text" name="email" value="'.$current_data->email.'" /></td></tr>';
    echo '<tr><td align="right">Birthdate:</td><td><input type="text" name="birthdate" size="10" maxlength="10" value="'.$current_data->birthdate.'" /> (mm-dd-yyyy)</td></tr>';
    echo '<tr><td align="right">Consent Form?</td><td><input type="checkbox" name="consent_form" value="1" ';
    if ($current_data->consent_form) echo ' checked';
    echo ' /></td></tr><tr><td align="right">Photo Form?</td><td><input type="checkbox" name="photo_form" value="1" ';
    if ($current_data->photo_form) echo ' checked';
    echo ' /></td></tr>';
    echo '<tr><td align="right">Enter Emergency Information:</td><td><input type="radio" name="emerg_form" value="yes_emerg" /> Now <input type="radio" name="emerg_form" value="no_emerg" /> Later </td></tr></table>';

    echo '<p align="center"><input type="submit" name="Submit" value="Make It Happen" /></p>';
    echo '</form>';
    
}
elseif ($table == TAB_PRE."_projects")
{
    echo '<h2>Project Entry Form</h2>';
		echo '<p>Please complete this form in order to set-up a project in the database and subscribe volunteers to it. Items marked with an * are required.</p>';
    
    echo '<form name="add_project" method="post" action="index.php?choice=add_project">';
    echo '<table>';
    if (isset($current_data->id_key)){echo '<input type="hidden" name="id_key" value='.$current_data->id_key.' />';}
   
    echo '<tr><td align="right">Project Name*:</td><td><input type="text" name="name" value="'.$current_data->name.'" /></td></tr>';
    echo '<tr><td align="right">Company or Organization*:</td><td><input type="text" name="company" value="'.$current_data->company.'" /></td></tr>';
    echo '<tr><td align="right">Address:</td><td><input type="text" name="address" value="'.$current_data->address.'" /></td></tr>';
    echo '<tr><td align="right">City:</td><td><input type="text" name="city" value="'.$current_data->city.'" /></td></tr><tr><td align="right">State:</td><td><input type="text" size=2 name="state" value="'.$current_data->state.'" /></td></tr><tr><td align="right">Zip Code:</td><td><input type="text" maxlength=5 size=5 name="zip_code" value="'.$current_data->zip_code.'" /></td></tr>';
    echo '<tr><td align="right">Service Partner Name*:</td><td><input type="text" name="sp_name" value="'.$current_data->sp_name.'" /></td></tr>';
    echo '<tr><td align="right">Date of Project* (mm-dd-yyyy):</td><td><input type="text" size=10 maxlength=10 name="proj_date" value="'.$current_data->proj_date.'" /></td></tr><tr><td align="right">Time of Project Start (for best results, use a 24 hour clock):</td><td><input type="text" name="proj_time" value="'.$current_data->proj_time.'" /></td></tr><tr><td align="right">Length of Project:</td><td><input type="text" name="proj_length" value="'.$current_data->proj_length.'" /></td></tr><tr><td align="right">Number of Shifts:</td><td><input type="text" name="proj_shifts" value="'.$current_data->proj_shifts.'" /></td></tr>';

    echo '<tr><td align="right">Please Describe the Project:</td><td><textarea name="description">'.$current_data->description.'</textarea></td></tr>';
    echo '<tr><td align="right">Directions to the Project from Broad @ High:</td><td><textarea name="direction">'.$current_data->direction.'</textarea></td></tr>';
    echo '<tr><td align="right">Tools Required:</td><td><textarea name="tools">'.$current_data->tools.'</textarea></td></tr><tr><td align="right">Number of Volunteers Required (including City Year):</td><td><input type="text" size=3 name="num_volunteers" value="'.$current_data->num_volunteers.'" /></td></tr></table>';
    echo '<p align="center"><input type="submit" name="Submit" value="Update Project Record"></p>';

    echo '</form>';
}
}

function valid_input($str, $validmask="abcdefghijklmnopqrstuvwxyz0123456789_-,? '.()@:!&*=+;<>`~|")
{
	$str=strtolower($str);
	$str=stripslashes($str);
	if (strspn($str, $validmask) == strlen($str)) {return true;}
	else {return false;}
}

function display_vol_info($vol_id, $shift_info, $proj_info, $result, $sign_in = 0)
{
	if ($sign_in==0)
	{
		$db = new db(EZSQL_DB_USER, EZSQL_DB_PASSWORD, EZSQL_DB_NAME, EZSQL_DB_HOST);
    $sql = "SELECT * FROM ".TAB_PRE."_emergency WHERE vol_id = ".$vol_id;
    $emerg = $db->get_row($sql);
		echo '<fieldset>';
		echo '<legend>Volunteer Information For...</legend>';
		echo '<h2>'.$result->first_name.' '.$result->last_name.'</h2><br />';
		echo '<table width="100%"><tr><td width="50%"><table><tr><td><p>Address: </p></td><td><p>'.$result->address.'</p></td></tr>';
		echo '<tr><td><p>City, State Zip: </p></td><td><p>'.$result->city.', '.$result->state.' '.$result->zip.'</p></td></tr>';
		echo '<tr><td><p>Home Phone: </p></td><td><p>'.$result->phone1.'</p></td></tr>';
		echo '<tr><td><p>Email: </p></td><td><p>'.$result->email.'</p></td></tr>';
		echo '<tr><td><p>Emergency<br />Contact 1: </p></td><td><p>'.$emerg->emerg_name_1.'<br />Phone: '.$emerg->emerg_phone_1.'<br />Relationship: '.$emerg->emerg_rel_1.'</p></td></tr>';
		echo '<tr><td><p>Emergency<br />Contact 2: </p></td><td><p>'.$emerg->emerg_name_2.'<br />Phone: '.$emerg->emerg_phone_2.'<br />Relationship: '.$emerg->emerg_rel_2.'</p></td></tr>';
		echo '<tr><td><p>Consent Form: </p></td><td><p>'.$result->consent_form.'</p></td></tr>';
		echo '<tr><td><p>Photo Form: </p></td><td><p>'.$result->photo_form.'</p></td></tr>';
		echo '</table></td><td width="50%"><p>Signed up for the shift beginning at: ';
		echo date("h:i a", (strtotime($proj_info->proj_time) + (($shift_info->shift_id - 1) * ($proj_info->proj_length / $proj_info->proj_shifts) * 3600))).'</p>';
		echo '<p>Lasting for: '.$shift_info->shift_length.' hour(s)<br /><br /></p>';
		echo '<p>Sign-In Time: __________ Initials: __________<br /><br /></p><p>Sign-Out Time: __________ Initials: __________</p></td></tr></table>';
		echo '</fieldset>';
	}
	elseif ($sign_in==1)
	{
		echo '<tr><td><input type="checkbox" name="'.$vol_id.'" value="'.$vol_id.'" /></td>';
		echo '<td>'.$result->first_name.' '.$result->last_name.'</td>';
		echo '<td>'.$result->company.'</td>';
		echo '<td>'.$result->phone1.'</td>';
		echo '<td>Shift begins at: '.date("h:i a", (strtotime($proj_info->proj_time) + (($shift_info->shift_id - 1) * ($proj_info->proj_length / $proj_info->proj_shifts) * 3600))).'</td>';
		echo '<td>Lasting for '.$shift_info->shift_length.' hour(s)</td></tr>';
	}
	elseif ($sign_in==2)
	{
	   echo '<tr><td>'.$result->first_name.' '.$result->last_name.'</td>';
	   echo '<td>'.$result->company.'</td>';
	   echo '<td>'.$result->phone1.'</td>';
	   echo '<td>Assigned shift at '.date("h:i a", (strtotime($proj_info->proj_time) + (($shift_info->shift_id - 1) * ($proj_info->proj_length / $proj_info->proj_shifts) * 3600))).'</td>';
	   echo '<td>Assigned for '.$shift_info->shift_length.' hour(s)</td>';
	   echo '<td>Actual Shift Length (hours): <input type="text" size=2 name="hours_'.$result->id_key.'" value=0 /></td>';
	   echo '<td>Notes: <textarea name="notes_'.$result->id_key.'"></textarea></td></tr>';
    }
    return true;
}

function sort_vol_alpha($volunteers, $proj_info)
{
    $db = new db(EZSQL_DB_USER, EZSQL_DB_PASSWORD, EZSQL_DB_NAME, EZSQL_DB_HOST);
		$i = 0;
		while ($i<count($volunteers))//this should check for duplicate volunteers
		//at some point this needs to be more robust for non-consecutive duplicates (or auto-align the duplicates)
		{
			$volunteer = array_shift($volunteers);
			if ($volunteer->vol_id == $volunteers[0]->vol_id)
			{
				$volunteer->shift_length += $volunteers[0]->shift_length;
				array_shift($volunteers);
			}
			$volunteers[] = $volunteer;
			$i++;
		}
		foreach ($volunteers as $volunteer)//this gets an array of first/last names for sorting
		{
			$sql = "SELECT last_name, first_name FROM ".TAB_PRE."_volunteers WHERE id_key = ".$volunteer->vol_id;
    	$result = $db->get_row($sql);
			$sorting_array[] = array('id_key' => $volunteer->vol_id, 'last_name' => $result->last_name, 'first_name' => $result->first_name);
		}
		foreach ($sorting_array as $key => $row)//this prepares the sort
		{
			$last_name[$key] = $row['last_name'];
			$first_name[$key] = $row['first_name'];
		}
		array_multisort($last_name, SORT_ASC, $first_name, SORT_ASC, $sorting_array);//this executest the sort
//		print_r ($sorting_array);
    foreach ($sorting_array as $single_folk)//calls the display function
    {
			$sql = "SELECT * FROM ".TAB_PRE."_volunteers WHERE id_key = ".$single_folk['id_key'];//gets the rest of the vol info
//			echo $sql.'<br />';
    	$result = $db->get_row($sql);
    	foreach ($volunteers as $volun)//regains the proper shift info from the id_key
    	{
    		if ($volun->vol_id == $single_folk['id_key'])
    		{
    			$volunteer = $volun;
    		}
    	}
    	if ($_GET['choice'] == 'create_reports')
    	{
            display_vol_info($single_folk['id_key'], $volunteer, $proj_info, $result, 0);//finally calls the display stuff
        }
        elseif ($_GET['choice'] == 'edit_project')
        {
        	display_vol_info($single_folk['id_key'], $volunteer, $proj_info, $result, 1);//displays within edit project screen
        }
        elseif ($_GET['choice'] == 'finalize_project')
        {
            display_vol_info($single_folk['id_key'], $volunteer, $proj_info, $result, 2);
        }
    }
		if (!isset($_GET['choice']))
		{
			$sql = "SELECT * FROM ".TAB_PRE."_volunteers WHERE ";
			foreach ($sorting_array as $single_folk)
			{
					$sql .= "id_key = ".$single_folk['id_key']." OR ";
			}
			$sql .= "id_key = 0";
			return $db->get_results($sql);
		}
		else return true;
}

?>