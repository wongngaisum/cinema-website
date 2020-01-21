<?php
	header("Content-Type: application/json; charset=UTF-8");

	if (isset($_GET["name"])) {
		#Connect to sophia 
		$db_conn = mysqli_connect(host,username,password,dbname) 
			or die("Connection Error!".mysqli_connect_error());
		
		$query = "SELECT * FROM Film WHERE FilmName = '".$_GET["name"]."'";
		$result = mysqli_query($db_conn, $query) 
					or die("<p>Query Error!<br>".mysqli_error($db_conn)."</p>");
		$film = mysqli_fetch_array($result);

		$query = "SELECT * FROM Comment WHERE FilmId = '".$film["FilmId"]."'";
		$result = mysqli_query($db_conn, $query) 
					or die("<p>Query Error!<br>".mysqli_error($db_conn)."</p>");

		if (mysqli_num_rows($result) > 0) {
			$json_arr = array();
			while ($row = mysqli_fetch_array($result)) {
				$json_arr[] = array("viewer" => $row["UserId"], "comment" => $row["Comment"]);
			}
		}

		echo json_encode($json_arr);

		mysqli_free_result($result); 
		mysqli_close($db_conn);
	}
?>