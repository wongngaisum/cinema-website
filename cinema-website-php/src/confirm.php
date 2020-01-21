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
		<title>Order Confirmation</title>
		<link rel="shortcut icon" href="favicon.ico" type="image/icon"> 
		<link rel="icon" href="favicon.ico" type="image/icon"> 
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="style/confirm.css">
		<div class="nav">
			<a class="left" href="main.php">HK Cinema</a>
			<a class="right" href="logout.php">Logout</a>
			<a class="right" href="history.php">Purchase History</a>
			<a class="right" href="comment.php">Movie Review</a>
			<a class="active right" href="buywelcome.php">Buy A Ticket</a>
		</div>
		</header>
		<main>
			<div class="container">
				<h1>Order information</h1>
				<br>
				<br>
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
						$totalFee = 0;
						foreach ($_POST as $param_name => $param_val) {
							if ($param_name != "BroadCastId") {
								echo "<div class='info'>";
								echo "Cinema&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp; HK<br>";
								echo "House&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp; House ".$row["HouseId"]."<br>";
								echo "SeatNo&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp; ".$param_name."<br>";
								echo "Film&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp; ".$row2["FilmName"]."<br>";
								echo "Category&nbsp;&nbsp;&nbsp;:&nbsp; ".$row2["Category"]."<br>";
								echo "Show Time&nbsp;&nbsp;:&nbsp; ".$row["Dates"]." (".$row["Day"].") ".$row["Time"]."<br>";
								if ($param_val == "adult") {
									echo "Ticket Fee&nbsp;:&nbsp; $75(Adult)<br>";
									$totalFee += 75;
									$query = "UPDATE Ticket SET UserId = '".$_SESSION['username']."', TicketFee = '75', TicketType = 'Adult', Valid = '0' WHERE BroadCastId = '".$id."' AND SeatNo = '".$param_name."';";
								}
								else {
									echo "Ticket Fee&nbsp;:&nbsp; $50(Student/Senior)<br>";
									$totalFee += 50;
									$query = "UPDATE Ticket SET UserId = '".$_SESSION['username']."', TicketFee = '50', TicketType = 'Student/Senior', Valid = '0' WHERE BroadCastId = '".$id."' AND SeatNo = '".$param_name."';";
								}
							    echo "</div>";
							    echo "<br>";

							    $result3 = mysqli_query($db_conn, $query) 
							    	or die("<p>Query Error!<br>".mysqli_error($db_conn)."</p>");
							}
						}
						echo "<p id='totalFee'>Total fee: $ ".$totalFee."</p>";
					}
				?>
				<br>
				<hr>
				<br>
				<p>Please present valid proof of age/status when purchasing Student or Senior tickets before entering the cinema house.</p>
				<br>
				<button class="button" onclick="location.href='buywelcome.php'" type="button">OK</button>
			</div>
		</main>
		<?php
			mysqli_free_result($result); 
			mysqli_free_result($result2); 
			mysqli_free_result($result3); 
			mysqli_close($db_conn);
	}
?>
