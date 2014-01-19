<?php
function emergency_add($id_key)
{
$db = new db(EZSQL_DB_USER, EZSQL_DB_PASSWORD, EZSQL_DB_NAME, EZSQL_DB_HOST);

$results = $db->get_row("SELECT * FROM ".TAB_PRE."_emergency WHERE vol_id = ".$id_key);

?>
<form method="post" action="index.php?choice=emergency_info">
<table>
<tr><td>Emergency Contact 1:</td><td><input type="text" name="emerg_name_1" value="<?php if(isset($results->emerg_name_1)){echo $results->emerg_name_1;} ?>" /></td><tr>
<tr><td>Emergency Phone 1:</td><td><input type="text" name="emerg_phone_1" value="<?php if(isset($results->emerg_phone_1)){echo $results->emerg_phone_1;} ?>" /></td></tr>
<tr><td>Emergency Relationship 1:</td><td><input type="text" name="emerg_rel_1" value="<?php if(isset($results->emerg_rel_1)){echo $results->emerg_rel_1;} ?>" /></td><tr>
<tr><td>Emergency Contact 2:</td><td><input type="text" name="emerg_name_2" value="<?php if(isset($results->emerg_name_2)){echo $results->emerg_name_2;} ?>" /></td><tr>
<tr><td>Emergency Phone 2:</td><td><input type="text" name="emerg_phone_2" value="<?php if(isset($results->emerg_phone_2)){echo $results->emerg_phone_2;} ?>" /></td></tr>
<tr><td>Emergency Relationship 2:</td><td><input type="text" name="emerg_rel_2" value="<?php if(isset($results->emerg_rel_2)){echo $results->emerg_rel_2;} ?>" /></td></tr>
<tr><td>Allergies, Limitations, Medications, etc.:</td><td><textarea name="emerg_medic" value="<?php if(isset($results->emerg_medic)){echo $results->emerg_medic;} ?>" ></textarea></td></tr>
</table>
<?php if(isset($results->id_key)) {echo '<input type="hidden" name="id_key" value='.$results->id_key.' />';} ?>
<input type="hidden" name="vol_id" value="<?php echo $id_key; ?>" />
<input type="submit" name="emerg_submit" value="Submit Emergency Information" />
<?php

}

?>