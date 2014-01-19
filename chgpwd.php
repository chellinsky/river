<?php

function print_change_pass_form()
{
?>
<h2 align="center"><b>Change Your Password</b></h2>
<p><b>A brief note on security:</b> When you enter a password, it is immediately encrypted by the script on the server side.  Thus, I have no access to your password, nor does anyone else.  Therefore, if you lose or forget it, all I can do is reset it, not recover it.</p>
<div align="center">
  <center>
  <form method="POST" action="index.php?choice=chgpwd">
  <table border="0" cellpadding="0" cellspacing="0" width="40%">
    <tr>
      <td width="100%" colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td width="34%"><p>Username:</p></td>
      <td width="66%">
      <input type="text" name="username" size="25"></td>
    </tr>

    <tr>
      <td width="34%"><p>Old Password:</p></td>
      <td width="66%">
      <input type="password" name="oldpasswd" size="25"></td>
    </tr>
    <tr>
      <td width="34%"><p>New Password:</p></td>
      <td width="66%">
      <input type="password" name="newpasswd" size="25"></td>
    </tr>
    <tr>
      <td width="34%"><p>Confirm New Password:</p></td>
      <td width="66%">
      <input type="password" name="confirmpasswd" size="25"></td>
    </tr>
    <tr>
      <td width="100%" colspan="2">&nbsp; </td>
    </tr>
    <tr>
      <td width="100%" colspan="2">
      <p align="center"><input type="submit" value="Save Changes" name="submit">
      <input type="reset" value="Reset Fields" name="reset"></td>
    </tr>
    <tr>
      <td width="100%" colspan="2">&nbsp;
       </td>
    </tr>
  </table>      
  </form>
  </center>
</div>

<?php
}

if (!isset($_POST['submit']))
{
	print_change_pass_form();
}//end if
else
{// Get global variable values

	$username = $_POST['username'];
	$oldpasswd = $_POST['oldpasswd'];
	$newpasswd = $_POST['newpasswd'];
	$confirmpasswd = $_POST['confirmpasswd'];

	$db = new db(EZSQL_DB_USER, EZSQL_DB_PASSWORD, EZSQL_DB_NAME, EZSQL_DB_HOST);

	$userdata = $db->get_var("SELECT pw FROM ".TAB_PRE."_users WHERE username='".$username."'");
	
	if ($userdata == md5($oldpasswd))  // Check if Old password is the correct
	{
		// Check if New password if blank
		if (trim($newpasswd) == "")
		{
			print "<p align=\"center\">";
			print "	<font face=\"Arial\" color=\"#FF0000\">";
			print "		<b>New password cannot be blank!</b>";
			print "	</font>";
			print "</p>";
			print_change_pass_form();
			exit;
		}
		// Check if New password is confirmed
		if ($newpasswd != $confirmpasswd)
		{
			print "<p align=\"center\">";
			print "	<font face=\"Arial\" color=\"#FF0000\">";
			print "		<b>New password was not confirmed!  Please enter it the same twice</b>";
			print "	</font>";
			print "</p>";
			print_change_pass_form();
			exit;
		}
		
		// If everything is ok, use auth class to modify the record
		if (valid_input($newpasswd)) {
			$db->query("UPDATE ".TAB_PRE."_users SET pw = '".MD5($newpasswd)."' WHERE username = '".$username."'");
			print "<p align=\"center\">";
			print "		<b>Password Changed!</b><br>";
			print "</p>";
		}
		else
		{
			print "There are invalid characters in your password.  Please try again using only alpha numerics and these characters: _- '.()@:";
			print_change_pass_form();
		}
	}//end check if correct if
	else
	{
	print "<p>Incorrect old password.  Please try again</p>";
	print_change_pass_form();
	}
}//end original else statement
?>
