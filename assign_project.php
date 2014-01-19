<?php

$db = new db(EZSQL_DB_USER, EZSQL_DB_PASSWORD, EZSQL_DB_NAME, EZSQL_DB_HOST);
//need an if for assign to shifts and an if to begin again and say all is well.
if (isset($_POST['finalize_vol_assign']))
{
//    print_r($_POST);
	$db = new db(EZSQL_DB_USER, EZSQL_DB_PASSWORD, EZSQL_DB_NAME, EZSQL_DB_HOST);
	$inputarray = $_POST;
	foreach ($inputarray as $key => $value)
	{
	   if(substr($key, 0, 4) == "vol_")
	   {
	   list($vol, $vol_id, $proj, $proj_id, $shift, $shift_id) = explode("_", $value);
	   $project = $db->get_row("SELECT proj_time, proj_shifts, proj_length FROM ".TAB_PRE."_projects WHERE id_key = ".$proj_id);
	   $shift_length = $project->proj_length / $project->proj_shifts;
	   $db->query("INSERT INTO ".TAB_PRE."_hours (id_key, vol_id, proj_id, shift_length, shift_id, create_date) VALUES (NULL,".$vol_id.",".$proj_id.", ".$shift_length.", ".$shift_id.", now())");
//	   $db->debug();
        echo "<p>".$db->rows_affected." row(s) affected in database call.</p><br />";
   	   }
    }
}
elseif (isset($_POST['assign_vol']))
{
	$db = new db(EZSQL_DB_USER, EZSQL_DB_PASSWORD, EZSQL_DB_NAME, EZSQL_DB_HOST);
    $ezr = new ez_results();
    $ezr->nav_top = false;
    $ezr->nav_bottom = false;
	$inputarray = $_POST;
	$inputarray2 = $inputarray;
//	print_r ($inputarray);
    echo "<form action=index.php?choice=".$_GET['choice']." method=post>";
    $vol_count = 0;
	foreach ($inputarray as $key => $value)
	{
		if($key != 'assign_vol' && substr($key, 0, 4) == "vol_")
		{
		  //display a litte volunteer information alongside the shift options for the volunteer
		  $value = trim($value, "vol_");
		  $volunteer = $db->get_row("SELECT id_key, first_name, last_name, company, phone1 FROM ".TAB_PRE."_volunteers WHERE id_key = ".$value);
          echo '<p><b>'.$volunteer->last_name.', '.$volunteer->first_name.'</b> of '.$volunteer->company.'<br />';
		  echo "Please choose the shifts the volunteer agreed to attend.</p>";
		  $vol_count++;
		  foreach ($inputarray2 as $key2 => $value2)
		  {
		      if($key != 'assign_vol' && substr($key2, 0, 5) == "proj_")
		      {
		          //display the shift options here!
		          $value2 = trim($value2, "proj_");
		          $project = $db->get_row("SELECT name, proj_date, proj_time, proj_shifts, proj_length FROM ".TAB_PRE."_projects WHERE id_key = ".$value2);
		          //this output will need some gussying up sometime
		          echo "<p>".$project->name." has ".$project->proj_shifts." shift(s).  The choices are below:</p>";
		          $i = 1;
		          $shift_length = ($project->proj_length)/($project->proj_shifts);
		          $time_of_day = $project->proj_time;
		          while ($i <= $project->proj_shifts)
		          {
		              echo '<p><input type="checkbox" name="vol_'.$value.'_proj_'.$value2.'_shift_'.$i.'" value="vol_'.$value.'_proj_'.$value2.'_shift_'.$i.'" />Shift '.$i.': '.$time_of_day.' - '.($time_of_day+$shift_length).'</p>';
		              $time_of_day += $shift_length;
		              $i++;
		          }
		      }
		  }
		}
	}
	echo '<input type="submit" name="finalize_vol_assign" value="Finalize Assignment" /></form>';	
}
elseif (isset($_POST['lookup']))
{
    if (valid_input($_POST['vol_crit']) && valid_input($_POST['vol_search_term']) && valid_input($_POST['proj_crit']) && valid_input($_POST['proj_search_term']))
        {//send the query and display the results

//sometime we need to check here for volunteers already assigned and prevent them
//from being displayed.  There should also be a disclaimer that if more than 3000
//results are returned then there will be problems.  Hopefully people will realize
//it is easier to just search for whom they wish to add than to pick from the 
//entire database

        echo '<form action="index.php?choice='.$_GET['choice'].'" method="post">';
        echo '<p>Please select the volunteers you would like to add to a project.</p>';
        $db = new db(EZSQL_DB_USER, EZSQL_DB_PASSWORD, EZSQL_DB_NAME, EZSQL_DB_HOST);
        $ezr = new ez_results();

				$ezr->num_results_per_page = 50;
        $ezr->query_mysql("SELECT id_key, first_name, last_name, company, phone1 FROM ".TAB_PRE."_volunteers WHERE ".$_POST['vol_crit']." LIKE '%".$_POST['vol_search_term']."%'");
        $ezr->results_row = create_edit_col_template(5, "vol_", true);
        $ezr->display();
        echo '<p>Please select the projects.</p>';
        $db = new db(EZSQL_DB_USER, EZSQL_DB_PASSWORD, EZSQL_DB_NAME, EZSQL_DB_HOST);

        $ezr = new ez_results();

        $ezr->query_mysql("SELECT id_key, name, company, proj_date, description FROM ".TAB_PRE."_projects WHERE ".$_POST['proj_crit']." LIKE '%".$_POST['proj_search_term']."%' AND finalized=0");
        $ezr->results_row = create_edit_col_template(5, "proj_", true);
				$ezr->display();
        echo '<br /><input type="submit" name="assign_vol" value="Assign to Project"></form>';
        echo '<br /><form action="index.php?choice='.$_GET['choice'].'" method="post"><input type="submit" name="restart_search" value="Restart Process" /></form>';
	   }
    else
        {//display the form again and denote invalid input was made
        echo "<b>Invalid input in your search!  Please only include alpha-numerics and these characters: _- '.()@.  Thanks for helping us keep this secure.";
        echo "Press back and try again";
        }

}
else
{
$users = $db->get_results("SELECT * FROM ".TAB_PRE."_volunteers");

//to aid in functionality, I would like this to eventually be a part of the 
//generic basic/advanced search duo.  due to time constraints, we will get
//by with this for now.

echo '<form action="index.php?choice='.$_GET['choice'].'" method="post">';
echo '<p>Volunteer Criteria: ';
echo'<select name="vol_crit">';
$i = 0;
// Output the name for each column type as a choice
foreach ( $db->get_col_info("name")  as $name )
{
      echo '<option value="'.$name.'">'.$name.'</option>';
      $i++;
}
echo '</select> = ';
echo '<input type="text" name="vol_search_term" />';
echo '<input type="hidden" name="num_vol_col" value='.$i.' />';
echo '</p>';

echo '<p>Project Criteria: ';
echo '<select name="proj_crit">';
$projects = $db->get_results("SELECT * FROM ".TAB_PRE."_projects");

$i=0;
// Output the name for each column type as a choice
foreach ( $db->get_col_info("name")  as $name )
{
      echo '<option value="'.$name.'">'.$name.'</option>';
      $i++;
}
echo '</select> = ';
echo '<input type="text" name="proj_search_term" />';
echo '<input type="hidden" name="num_proj_col" value='.$i.' />';
echo '</p>';

echo '<p><input type="submit" name="lookup" value="Lookup Volunteers and Projects" /></p>';
echo '</form>';
}

?>