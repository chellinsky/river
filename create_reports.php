
<?php
//Reports will include printouts for specific volunteers/projects along with overall site and project statistics.  RASL will be ever so easy

if (isset($_POST['vol_sign_sheet']))
{
	//disply the volunteer sign-in sheets
	$proj_name = $_POST['project'];
	sign_in_sheet($proj_name);
}
/*if (isset($_POST['vol_excel_export']))
{
	//export a list of volunteers for a project to excel
	$proj_name = $_POST['project'];
	excel_export($proj_name);
}*/
else
{
	reports_menu();
}

?>
