<?php

function check_for_cookie()
{
//		echo time();
	$db = new db(EZSQL_DB_USER, EZSQL_DB_PASSWORD, EZSQL_DB_NAME, EZSQL_DB_HOST);

	if (isset($_COOKIE['sid']))
		{
		//check if valid cookie with database
		$query = "SELECT expire FROM ".TAB_PRE."_sessions WHERE sid = '".$_COOKIE['sid']."'";

		$expire_time = $db->get_var($query);
//		$db->debug();
//		echo time();
		if ($expire_time >= time())//check if it returned anything and if the cookie is still good
		{//runs updates of everything
		  $query = "UPDATE ".TAB_PRE."_sessions SET expire = ".(time()+3600)." WHERE sid = '".$_COOKIE['sid']."'";
		  $results = $db->query($query);
//		  $db->debug();
		
		  setcookie ( "sid", $_COOKIE['sid'], (time() + ( 60 * 60 )), "/" );
    
		  return true;
		}
        else {return false;}
		}
	else {return false;}	
}

function clean_table() //cleans out old sessions from the database
{
	$db = new db(EZSQL_DB_USER, EZSQL_DB_PASSWORD, EZSQL_DB_NAME, EZSQL_DB_HOST);
    $query = "DELETE FROM ".TAB_PRE."_sessions WHERE expire < ".time();
//    echo $query;
    $db->query($query);
//    $db->debug();
    return true;
}

function check_new_login()
{
	$db = new db(EZSQL_DB_USER, EZSQL_DB_PASSWORD, EZSQL_DB_NAME, EZSQL_DB_HOST);


	if (isset($_POST['username']) && isset($_POST['pass']))
	{

	$username = $_POST['username'];
	$password = $_POST['pass'];

	$password = md5($password);

	$query = "SELECT * FROM ".TAB_PRE."_users WHERE username='".$username."' and pw='".$password."'";

	$db->get_results($query);
//	$db->debug();

	if($db->num_rows == 1) {//begin creating session

		 srand ((float) microtime() * 10000000);
		 $sid = substr(md5(rand(0,9999999)),0,26);

		 setcookie ( "sid", $sid, time() + ( 60 * 60 ), "/" );

		 //add to db
 		 $db->query("INSERT INTO ".TAB_PRE."_sessions (sid, opened, expire, addr) VALUES ('".$sid."', ".time().", ".(time() + 3600).", '".$_SERVER["REMOTE_ADDR"]."')");
//	   $db->debug();

		 echo "login ok!"; //session id: ".$s->get_sid();
		 $logged_in = true;

	}//end check on # rows and create session
	else {
	   echo "login failed!";
	   $logged_in = false;
	}
    if(clean_table()) echo "Table is clean <br />";

	}//end check for new login (i.e. post variables set
	else {$logged_in = false;}

	return $logged_in;

}

?>
