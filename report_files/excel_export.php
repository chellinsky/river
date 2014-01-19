<?php
		require_once('../ez_sql.php');
		require_once('../river_auth_funcs.php');
		require_once('../river_data_forms.php');

		if (check_for_cookie())//test for log-in here and direct to new page, or display something different, if not logged in
		{
		$proj_name = $_POST['project'];
		$db = new db(EZSQL_DB_USER, EZSQL_DB_PASSWORD, EZSQL_DB_NAME, EZSQL_DB_HOST);
    $sql = 'SELECT * FROM '.$TAB_PRE.'_projects WHERE name = "'.$proj_name.'" AND finalized=0';
    $proj_info = $db->get_row($sql);
    $sql = "SELECT * FROM ".$TAB_PRE."_hours WHERE proj_id = ".$proj_info->id_key." ORDER BY vol_id, shift_id";
    $volunteers = $db->get_results($sql);

		$volunteers = sort_vol_alpha($volunteers, $proj_info);
		
   	 //header info for browser: determines file type ('.doc' or '.xls')
  	 header("Content-Type: application/vnd.ms-excel");
  	 header("Content-Disposition: attachment; filename=vol_list.xls");
  	 header("Pragma: no-cache");
  	 header("Expires: 0");
     /*    FORMATTING FOR EXCEL DOCUMENTS ('.xls')   */
     //define separator (defines columns in excel & tabs in word)
     $sep = ","; //tabbed character
     //start of printing column names as names of MySQL fields
     $db->query("SELECT * FROM ".$TAB_PRE."_volunteers");
     foreach ($db->get_col_info("name") as $name)
     {
         echo $name . $sep;
     }
     print("\n");
     //end of printing column names
     //start while loop to get data
     foreach($volunteers as $volunteer)
     {
         //set_time_limit(60); // HaRa
         $schema_insert = "";
         foreach ($db->get_col_info("name") as $name)
         {
         		$schema_insert .= $volunteer->{$name}.$sep;
         }
         /*
         for($j=0; $j<mysql_num_fields($result);$j++)
         {
             if(!isset($row[$j]))
                 $schema_insert .= "NULL".$sep;
             elseif ($row[$j] != "")
                 $schema_insert .= "$row[$j]".$sep;
             else
                 $schema_insert .= "".$sep;
         }
         */
         $schema_insert = str_replace($sep."$", "", $schema_insert);
         //following fix suggested by Josue (thanks, Josue!)
         //this corrects output in excel when table fields contain n or r
         //these two characters are now replaced with a space
//         $schema_insert = preg_replace("/rn|nr|n|r/", " ", $schema_insert);
         $schema_insert .= $sep;
         print(trim($schema_insert));
         print "\n";
     }
		}
		else print "no go";
?>