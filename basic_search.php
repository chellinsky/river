<?php
/*<p>This element may not be used except to be called by the edit function or even
the create report.  This may end up being in vms_functions in the end rather than
a seperate file.</p>*/

//create a search field from the database
$db = new db(EZSQL_DB_USER, EZSQL_DB_PASSWORD, EZSQL_DB_NAME, EZSQL_DB_HOST);

$users = $db->get_results("SELECT * FROM ".$table);

echo '<form action="index.php?choice='.$_GET['choice'].'" method="post">';
echo '<p>';
echo'<select name="criteria">';
$i = 0;
// Output the name for each column type as a choice
foreach ( $db->get_col_info("name")  as $name )
{
      echo '<option value="'.$name.'">'.$name.'</option>';
      $i++;
}
echo '</select>';
echo '<input type="text" name="search_term"></p>';
echo '<p>Sort results by: <select name="sort_by">';
$i = 0;
// Output the name for each column type as a choice
foreach ( $db->get_col_info("name")  as $name )
{
      echo '<option value="'.$name.'">'.$name.'</option>';
      $i++;
}
echo '</select>';
echo '<input type="hidden" name="num_col" value='.$i.' />';
echo '</p><p><input type="submit" value="Send">';
echo '</p>';
echo '</form>';


?>