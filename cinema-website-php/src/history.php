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
		<title>Purchase History</title>
		<link rel="shortcut icon" href="favicon.ico" type="image/icon"> 
		<link rel="icon" href="favicon.ico" type="image/icon"> 
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="style/history.css">
		<div class="nav">
			<a class="left" href="main.php">HK Cinema</a>
			<a class="right" href="logout.php">Logout</a>
			<a class="active right" href="history.php">Purchase History</a>
			<a class="right" href="comment.php">Movie Review</a>
			<a class="right" href="buywelcome.php">Buy A Ticket</a>
		</div>
		<main>
			<div class="container">
				<h1>Purchase History</h1>
				<br>
				<br>
				<?php
					echo "<h3>Username: ".$_SESSION['username']."</h3><br><br>";

					$db_conn = mysqli_connect(host,username,password,dbname) 
						or die("Connection Error!".mysqli_connect_error());

					$query = "SELECT * FROM Ticket WHERE UserId = '".$_SESSION['username']."'";
					$result = mysqli_query($db_conn, $query) 
						or die("<p>Query Error!<br>".mysqli_error($db_conn)."</p>");

					if (mysqli_num_rows($result) > 0) {
						while ($row = mysqli_fetch_array($result)) {
							$query = "SELECT * FROM BroadCast WHERE BroadCastId = '".$row['BroadCastId']."'";
							$result2 = mysqli_query($db_conn, $query) 
								or die("<p>Query Error!<br>".mysqli_error($db_conn)."</p>");
							$broadcast = mysqli_fetch_array($result2);

							$query = "SELECT * FROM Film WHERE FilmId = '".$broadcast['FilmId']."'";
							$result3 = mysqli_query($db_conn, $query) 
								or die("<p>Query Error!<br>".mysqli_error($db_conn)."</p>");
							$film = mysqli_fetch_array($result3);
							echo "<div class='record'";
							echo "TicketId: ".$row['TicketId']." $".$row['TicketFee']."(".$row['TicketType'].")"."<br>";
							echo "House&nbsp;&nbsp;&nbsp;&nbsp;: ".$broadcast['HouseId']."<br>";
							echo "Seat&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ".$row['SeatNo']."<br>";
							echo "FilmName&nbsp;: ".$film['FilmName']."(".$film['Category'].") ".$film['Duration']."<br>";
							echo "Language&nbsp;: ".$film['Language']."<br>";
							echo "Date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ".$broadcast['Dates']."(".$broadcast['Day'].") ".$broadcast['Time']."</div>";
							echo "<br><hr><br>";
						}
					} else {
						echo "You haven't purchased any ticket.";
					}
				?>
			</div>
		</main>
		<?php
			mysqli_free_result($result); 
			mysqli_free_result($result2); 
			mysqli_free_result($result3); 
			mysqli_close($db_conn);
	}
?>
