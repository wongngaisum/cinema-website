<?php
	session_start();
	if (isset($_COOKIE[session_name()]))
		setcookie(session_name(),'',time() - 3600, '/'); 
	session_unset(); 
	session_destroy();
	echo "<h2>Logging out</h2>"; 
	header("refresh:3;url=index.html");
?>