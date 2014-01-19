<?php

function sign_in_sheet($proj_name)
{
		$db = new db(EZSQL_DB_USER, EZSQL_DB_PASSWORD, EZSQL_DB_NAME, EZSQL_DB_HOST);
    $sql = 'SELECT * FROM '.TAB_PRE.'_projects WHERE name = "'.$proj_name.'" AND finalized=0';
    $proj_info = $db->get_row($sql);
    $sql = "SELECT * FROM ".TAB_PRE."_hours WHERE proj_id = ".$proj_info->id_key." ORDER BY vol_id, shift_id";
    $volunteers = $db->get_results($sql);
		echo ('<h2> Volunteers for '.$proj_name.'</h2><br />');
		
		sort_vol_alpha($volunteers, $proj_info);//sorts the volunteers alphabetically and displays them
}

function reports_menu()
{
    echo ("<h2>Create Reports Related to Current and Past Projects</h2>");

    echo ('<fieldset>');
    echo ('<legend>Volunteer Information and Sign-in Sheets for Project(s)</legend>');
    echo ('<form name="vol_sign_in" method="post" action="index.php?choice=create_reports">');
    echo ('<br>Please select the project from the list: <select name="project">');
		$db = new db(EZSQL_DB_USER, EZSQL_DB_PASSWORD, EZSQL_DB_NAME, EZSQL_DB_HOST);
    $sql = "SELECT name FROM ".TAB_PRE."_projects WHERE finalized=0";
    $results = $db->get_results($sql);
    foreach ($results as $result)
    {
    	echo ('<option>'.$result->name.'</option>');
		}
    echo ('</select>');
    echo ('<br /><input type="submit" name="vol_sign_sheet" value="Create Sign-in Sheet" />');
    echo ('</form>');
    echo ('</fieldset>');

    echo ('<fieldset>');
    echo ('<legend>Export Volunteer Data to Excel</legend>');
    echo ('<form name="vol_to_excel" method="post" action="report_files/excel_export.php">');
    echo ('<br>Please select the project to export from the list: <select name="project">');
//		$db = new db(EZSQL_DB_USER, EZSQL_DB_PASSWORD, EZSQL_DB_NAME, EZSQL_DB_HOST);
//    $sql = "SELECT name FROM ".TAB_PRE."_projects WHERE finalized=0";
//    $results = $db->get_results($sql);
    foreach ($results as $result)
    {
    	echo ('<option>'.$result->name.'</option>');
		}
    echo ('</select>');
    echo ('<br /><input type="submit" name="vol_excel_export" value="Export to Excel" />');
    echo ('</form>');
    echo ('</fieldset>');
    
    echo '<fieldset>';
    echo '<legend>New Civic Engagements</legend>';
    echo '<form name="new_civics" method="post" action="report_files/new_civic.php">';
    echo '<br />This form exports all new contacts from a selected date into an excel sheet for easy tracking.  Just be sure a list is maintained of the dates from which this is run.';
    echo '<br />Please select the date from which the query should be run: ';
    echo '<select name="month"><option>January</option><option>February</option><option>March</option><option>April</option><option>May</option><option>June</option><option>July</option><option>August</option><option>September</option><option>October</option><option>November</option><option>December</option></select>';
    echo '<select name="day"><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option><option>6</option><option>7</option><option>8</option><option>9</option><option>10</option><option>11</option><option>12</option><option>13</option><option>14</option><option>15</option><option>16</option><option>17</option><option>18</option><option>19</option><option>20</option><option>21</option><option>22</option><option>23</option><option>24</option><option>25</option><option>26</option><option>27</option><option>28</option><option>29</option><option>30</option><option>31</option></select>';
    echo '<select name="year"><option>2004</option><option>2005</option><option>2006</option></select>';
    echo '<br /><input type="submit" name="new_civics" value="Export New Contacts" />';
    echo '</form></fieldset>';


/*    echo ("<FIELDSET>\n");
    echo ("<LEGEND>Most active volunteers</LEGEND>\n");
    echo ("<FORM method=\"get\" action=\"reports.php\">\n");
    echo ("Beginning <INPUT type=\"text\" name=\"beginning_date\" value=\"2000-01-01\" size=\"10\">\n");
    echo ("Ending <INPUT type=\"text\" name=\"ending_date\" value=\"".date('Y-m-d')."\" size=\"10\">\n");
    echo ("<BR><INPUT type=\"submit\" name=\"report_active_volunteers\" value=\""._("Make report")."\">\n");
    echo ("</FORM>\n");
    echo ("</FIELDSET>\n");

    if (!array_key_exists('download', $_REQUEST))
    {
	make_html_end();
    }*/
}

?>
