<?php

function print_header($title = "CYCO R.I.V.E.R. System") {
?>

<html>
<head>
	<title><?php print $title; ?></title>
	<link rel="stylesheet" type="text/css" href="river_style.css" />
	<script type="text/javascript">
	function disp_confirm()
	{
		var response = confirm("Do you really want to delete this information from the database?  (This cannot be undone)");
		return response;
	}
	</script>
</head>

<body>

<!---<h1 align="center"><?php //print $title; ?></h1>---><p align="center"><img src="images.php?title=<?php print $title; ?>" alt="River Logo" /></p>
		<hr>
		
<?php
	$logged_in = check_new_login();

	if (check_for_cookie() || $logged_in)//test for log-in here and direct to new page, or display something different, if not logged in

	{
	
        echo '<table width="100%">';
        echo '<tr><td width="16.6%"><p><a href="index.php">R.I.V.E.R.</a></p></td>';
        echo '<td width="16.6%"><p><a href="index.php?choice=volunteers">Volunteers</a></p></td>';
        echo '<td width="16.6%"><p><a href="index.php?choice=projects">Projects</a></p></td>';
        echo '<td width="16.6%"><p><a href="index.php?choice=participants">Participants</a></p></td>';
        echo '<td width="16.6%"><p><a href="index.php?choice=sp">Service Parnters</a></p></td>';
        echo '<td width="16.7%"><p><a href="index.php?choice=create_reports">Reports</a></p></td></tr>';
        
        if ($_GET['choice']=='about' || !isset($_GET['choice']))
        {
            echo '<tr bgcolor="white"><td><p><a href="index.php?choice=about">About R.I.V.E.R.</a></p></td></tr>';
        }
        
        elseif (!(strpos($_GET['choice'], 'volunteer')===false) || !(strpos($_GET['choice'], 'emergency')===false))//extremely explicit here due to nature of strpos function
        {
            echo '<tr bgcolor="white"><td><p><a href="index.php?choice=add_volunteers">Add Volunteer</a></p></td>';
            echo '<td><p><a href="index.php?choice=edit_volunteers">View/Edit Volunteer</a></p></td></tr>';
        }
        
        elseif (!(strpos($_GET['choice'], 'project')===false))
        {
    		echo '<tr bgcolor="white"><td><p><a href="index.php?choice=add_project">Add a Project</a></p></td>';
            echo '<td><p><a href="index.php?choice=edit_project">View/Edit a Project</a></p></td>';
            echo '<td><p><a href="index.php?choice=assign_project">Assign Volunteers</a></p></td>';
            echo '<td><p><a href="index.php?choice=finalize_project">Finalize Project</a></p></td>';
            echo '<td><p><a href="index.php?choice=project_archive">Project Archives</a></p></td></tr>';
        }

		echo '</table><hr />';
	
	}
	
	else {
	
		print_login();
		die;
		
		}

}

function print_footer() {

?>
	<hr />
	<p align="center">User Administration: <a href="index.php?choice=chgpwd">Change Your Password</a> | <a href="logout.php">Logout</a></p>

	<p align="center">&copy;2005 Andrew Chellinsky</p>
	<p align="center">Email <a href="mailto:chellinsky@chellinsky.f2o.org">Andrew Chellinsky</a> with any questions</p>

</body>
</html>

<?php

}

function print_login() {

?>
<center><p>Please login to use the services provided.</p><br />
<form action="index.php" method="post">
<table><tr><td>Username: </td><td><input type="text" name="username" /><br /></td></tr>
<tr><td>Password: </td><td><input type="password" name="pass" /><br /></td></tr></table>
<input type="submit" value="Submit" name="submit" />
</form>
<p align="center">Information about the status of R.I.V.E.R. can be found at its <a href="http://sourceforge.net/projects/r-i-v-e-r/">Sourceforge Listing</a>.  Current information about the development roadmap, bugs, and support issues can be found at the Sourceforge site.  Please use this method to contact the developers of this software.</p>
</body>
<?php

}

function create_edit_col_template($num_col, $box_prefix="", $checkboxes=false)
{
$typical_row_var = '<tr bgcolor=ALTCOLOR1><td bgcolor=ALTCOLOR2>';

if (!$checkboxes) {$typical_row_var .= '<input type="radio" name="id_key" value="'.$box_prefix.'COL1" /></td>';}
else {$typical_row_var .= '<input type="checkbox" name="'.$box_prefix.'COL1" value="'.$box_prefix.'COL1" /></td>';}

for ($i = 1; $i <= $num_col; $i++)
{
    $typical_row_var .= "<td bgcolor=ALTCOLOR2>COL".$i."</td>";
}

$typical_row_var .= "</tr>";

return $typical_row_var;

}

?>