<?php
	// Destroy the cookie
    setcookie ( "sid", "", time()-3000, "/" );
?>

<html>
<head>
	<title>R.I.V.E.R. Log-out</title>
	<link rel="stylesheet" type="text/css" href="river_style.css" />
</head>

<body>
	<h1>R.I.V.E.R. Log-out</h1>
	<p>You successfully logged out.</p>
	<p><a href="index.php">Return</a> to R.I.V.E.R. to re-login.</p>
</body>
</html>
