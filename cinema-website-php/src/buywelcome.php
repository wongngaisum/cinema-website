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
		<link rel="stylesheet" href="style/buywelcome.css">
		<div class="nav">
			<a class="left" href="main.php">HK Cinema</a>
			<a class="right" href="logout.php">Logout</a>
			<a class="right" href="history.php">Purchase History</a>
			<a class="right" href="comment.php">Movie Review</a>
			<a class="active right" href="buywelcome.php">Buy A Ticket</a>
		</div>
		<main>
			<div class="container">
				<h1>Buy A Ticket</h1>
				<br>
				<br>
				<?php
					$db_conn = mysqli_connect(host,username,password,dbname) 
						or die("Connection Error!".mysqli_connect_error());

					$query = "SELECT * FROM Film";
					$result = mysqli_query($db_conn, $query) 
						or die("<p>Query Error!<br>".mysqli_error($db_conn)."</p>");

					if (mysqli_num_rows($result) == 0) { 
						echo "<h1>No film is available</h1>"; 
					} else {
						while ($row = mysqli_fetch_array($result)) {
							echo "<div class='film'><h1>".$row['FilmName']."</h1><br>";
							echo "<img class='images' src='films_images/".$row['PosterName']."'><br>";
							echo "<h3>Synopsis: ".$row['Description']."</h3><br>";
							echo "<h4>Director: ".$row['Director']."</h4><br>";
							echo "<h4>Duration: ".$row['Duration']."</h4><br>";
							echo "<h4>Category: ".$row['Category']."</h4><br>";
							echo "<h4> Language: ".$row['Language']."</h4><br>";
							?>

							<form action="seatplantry.php" method="post">
								<select id="broadcast" name="option">
									<?php
										
										$query = "SELECT * FROM BroadCast WHERE FilmId = '".$row['FilmId']."'";
										$result2 = mysqli_query($db_conn, $query) 
											or die("<p>Query Error!<br>".mysqli_error($db_conn)."</p>");

										if (mysqli_num_rows($result) > 0) {
											while ($row = mysqli_fetch_array($result2)) {
												echo "<option value=\"".$row['BroadCastId']."\">".
													$row['Dates']." ".$row['Time']." (".$row['Day'].") House ".$row['HouseId']."</option>";
											}
										} 
									?>
								</select>
								<input class="button" type="submit" value="Submit">
							</form>
							<br>

							<?php
							echo "</div><br><hr><br>";		
						}
					}
				?>
			</div>
		</main>
		<?php
		mysqli_free_result($result); 
		mysqli_free_result($result2); 
		mysqli_close($db_conn);
	}
?>
