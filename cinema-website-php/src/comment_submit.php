<?php
	session_start();

	if (isset($_POST['filmName']) && isset($_POST['comments'])) {
		$filmName = $_POST['filmName'];
		$comments = $_POST['comments'];

		#Connect to sophia 
		$db_conn = mysqli_connect(host,username,password,dbname) 
			or die("Connection Error!".mysqli_connect_error());

		$query = "SELECT * FROM Film WHERE FilmName = '".$filmName."'";
		$result = mysqli_query($db_conn, $query) 
					or die("<p>Query Error!<br>".mysqli_error($db_conn)."</p>");
		$film = mysqli_fetch_array($result);

		$query = "INSERT INTO Comment(`UserId`, `FilmId`, `Comment`) VALUES ('".$_SESSION['username']."', '".$film["FilmId"]."', '".$comments."');";

		$result = mysqli_query($db_conn, $query) 
			or die("<p>Query Error!<br>".mysqli_error($db_conn)."</p>");
		
		echo "<h1>Your comment has been submitted</h1>"; 
		header("refresh:3;url=comment.php");

		mysqli_free_result($result); 
		mysqli_close($db_conn);
	}
?>