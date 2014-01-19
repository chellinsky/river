<?php

/*I am using this now because it works.  At some point
these forms should be edited to be dynamically created adding
increased scalability to the program and an ease of updating
into the future.  For now, everything is hard-coded. */

if(!isset($_POST['Submit'])) //see if the form was attempted
{ //if not, display the form

    //display_project_form();
    add_data_form(TAB_PRE.'_projects');

}// end the if
elseif (strlen($_POST['name']) > 1 && strlen($_POST['company']) > 1 && strlen($_POST['sp_name']) > 1 && strlen($_POST['proj_date']) > 1)
{
    $inputarray = $_POST;
    $table = TAB_PRE."_projects";
    
    add_data($inputarray, $table);

//call function to put data into database
}// end the elseif
else
{
    //reprint form with user input intact
    $data = $_POST;
    echo '<h3>Not all information entered correctly!  Please try again.</h3>';
    display_project_form($data);

}// end the else
