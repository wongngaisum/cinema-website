<?php
	if (isset($_POST['username']) && isset($_POST['password'])) {
		$ac = $_POST['username'];
		$pw = $_POST['password'];

		#Connect to sophia 
		$db_conn = mysqli_connect(host,username,password,dbname) 
			or die("Connection Error!".mysqli_connect_error());
		$query = "SELECT * FROM Login WHERE UserId = '$ac'";
		$result = mysqli_query($db_conn, $query) 
			or die("<p>Query Error!<br>".mysqli_error($db_conn)."</p>");

		if (mysqli_num_rows($result) > 0) { 
			echo "<h1>Account already existed</h1>"; 
			header("refresh:3;url=createaccount.html");
		} else {
			$query = "INSERT INTO Login (UserId, PW) VALUES ('$ac', '$pw')";
			if (!mysqli_query($db_conn, $query))
				echo "<p>Error insert!!<br>".mysqli_error($db_conn)."</p>"; 
			else
				echo "<h1>Account created! Welcome</h1>"; 
			header("refresh:3;url=index.html");
		}
		mysqli_free_result($result); 
		mysqli_close($db_conn);
	}
?>