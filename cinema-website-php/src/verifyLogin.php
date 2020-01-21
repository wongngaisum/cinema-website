<?php
	session_start();

	if (isset($_SESSION['username'])) { //if already authenticated
		header('location: main.php');
	}

	if (isset($_POST['username']) && isset($_POST['password'])) {
		$ac = $_POST['username'];
		$pw = $_POST['password'];

		#Connect to sophia 
		$db_conn = mysqli_connect(host,username,password,dbname) 
			or die("Connection Error!".mysqli_connect_error());
		$query = "SELECT PW FROM Login WHERE UserId = '$ac'";
		$result = mysqli_query($db_conn, $query) 
			or die("<p>Query Error!<br>".mysqli_error($db_conn)."</p>");

		if (mysqli_num_rows($result) == 0) {	// cant find ac
			echo "<h1>Invalid login, please login again.</h1>"; 
			header("refresh:3;url=index.html");
		} else {
			$row = mysqli_fetch_array($result);
			if ($row['PW'] != $pw) {	// wrong pw
				echo "<h1>Invalid login, please login again.</h1>"; 
				header("refresh:3;url=index.html");
			} else {
				$_SESSION['username'] = $ac;	// store authenticated variable
				session_write_close();	// free session lock
				header('location: main.php');
			}
		}

		mysqli_free_result($result); 
		mysqli_close($db_conn);
	}
?>