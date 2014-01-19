<?php
require_once('../ez_sql.php');
require_once('../river_auth_funcs.php');
require_once('../river_data_forms.php');

if (check_for_cookie())//test for log-in here and direct to new page, or display something different, if not logged in
{
    $month = $_POST['month'];
    $day = $_POST['day'];
    $year = $_POST['year'];
    
    $db = new db(EZSQL_DB_USER, EZSQL_DB_PASSWORD, EZSQL_DB_NAME, EZSQL_DB_HOST);
    
    $time_string = $day.' '.$month.' '.$year;
//    echo $time_string.'<br />';
//    $requested_time = strtotime($time_string);
    $small_m = strftime("%m", strtotime($time_string));
    $requested_time = $year.'-'.$small_m.'-'.$day;
    
    $sql = "SELECT * FROM ".$TAB_PRE."_volunteers WHERE create_date >= '".$requested_time."'";
    $volunteers = $db->get_results($sql);

   	 //header info for browser
  	 header("Content-Type: application/vnd.ms-excel");
  	 header("Content-Disposition: attachment; filename=new_civics.xls");
  	 header("Pragma: no-cache");
  	 header("Expires: 0");
     //define separator
     $sep = ",";
     //start of printing column names as names of MySQL fields
     foreach ($db->get_col_info("name") as $name)
     {
         echo $name . $sep;
     }
     print("\n");
     //end of printing column names
     //start while loop to get data
     foreach($volunteers as $volunteer)
     {
         $schema_insert = "";
         foreach ($db->get_col_info("name") as $name)
         {
         		$schema_insert .= $volunteer->{$name}.$sep;
         }
         $schema_insert = str_replace($sep."$", "", $schema_insert);
         $schema_insert .= $sep;
         print(trim($schema_insert));
         print "\n";
     }
}
else print "You must be logged in to use this service, punk.";
?>