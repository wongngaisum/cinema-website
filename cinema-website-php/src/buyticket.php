<?php
	session_start();

	if (isset($_SESSION['username'])) { //if already authenticated
		start();
	} else {
		echo "You have not logged in"; 
		header("refresh:3;url=index.html");
	}

	function start() {
		?>
		<meta charset="utf-8">
		<title>Buy A Ticket</title>
		<link rel="shortcut icon" href="favicon.ico" type="image/icon"> 
		<link rel="icon" href="favicon.ico" type="image/icon"> 
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="style/buyticket.css">
		<div class="container">
			<h1 class="topic">Cart</h1>
			<div id="broadcastInfo">
				<?php
					$id = $_POST["BroadCastId"];

					$db_conn = mysqli_connect(host,username,password,dbname) 
						or die("Connection Error!".mysqli_connect_error());

					$query = "SELECT * FROM BroadCast WHERE BroadCastId = '".$id."'";
					$result = mysqli_query($db_conn, $query) 
						or die("<p>Query Error!<br>".mysqli_error($db_conn)."</p>");
					$row = mysqli_fetch_array($result);

					$query = "SELECT * FROM Film WHERE FilmId = '".$row["FilmId"]."'";
					$result2 = mysqli_query($db_conn, $query) 
						or die("<p>Query Error!<br>".mysqli_error($db_conn)."</p>");
					$row2 = mysqli_fetch_array($result2);

					if ($row && $row2) {
						echo "Cinema&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp; HK<br>";
						echo "House&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp; House ".$row["HouseId"]."<br>";
						echo "Film&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp; ".$row2["FilmName"]."<br>";
						echo "Category&nbsp;&nbsp;:&nbsp; ".$row2["Category"]."<br>";
						echo "Show Time&nbsp;:&nbsp; ".$row["Dates"]." (".$row["Day"].") ".$row["Time"];
					}
				?>
			</div>
			<br>
			<br>
			<form action="confirm.php" method="post">
				<?php
					echo "<input type='hidden' name='BroadCastId' value=".$id.">";
					foreach($_POST['chkBox'] as $selected) {
						echo "<span>".$selected."&nbsp;</span>";
						echo "<select class='options' name='".$selected."'>";
						echo "<option value='adult'>Adult($75)</option>";
						echo "<option value='studentOrSenior'>Student/Senior($50)</option>";
						echo "</select><br>";
					}
				?>
				<br>
				<br>
				<input class="button" type="submit" value="Confirm">
				<button class="button" onclick="location.href='buywelcome.php'" type="button">Cancel</button>
			</form>
		</div>
		<?php
			mysqli_free_result($result); 
			mysqli_free_result($result2); 
			mysqli_close($db_conn);
	}
?>