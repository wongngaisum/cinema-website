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
		<title>Ticketing</title>
		<link rel="shortcut icon" href="favicon.ico" type="image/icon"> 
		<link rel="icon" href="favicon.ico" type="image/icon"> 
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="style/seatplantry.css">
		<div class="container">
			<h1 class="topic">Ticketing</h1>
			<div id="broadcastInfo">
				<?php
					$id = $_POST["option"];

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
			<form action="buyticket.php" onsubmit="return checkValid();" method="post">
				<div id="seats">
					<?php
						echo "<input type='hidden' name='BroadCastId' value=".$id.">";
						$query = "SELECT HouseId FROM BroadCast WHERE BroadCastId = '".$id."'";
						$result = mysqli_query($db_conn, $query) 
							or die("<p>Query Error!<br>".mysqli_error($db_conn)."</p>");
						$house = mysqli_fetch_array($result)["HouseId"];
						
						$query = "SELECT * FROM House WHERE HouseId = '".$house."'";
						$result = mysqli_query($db_conn, $query) 
							or die("<p>Query Error!<br>".mysqli_error($db_conn)."</p>");
						$data = mysqli_fetch_array($result);
						$row = $data["HouseRow"];
						$col = $data["HouseCol"];

						for ($i = $row; $i >= 1; $i--) {
							for ($j = 1; $j <= $col; $j++) {

								$seat = chr($i + ord('A') - 1).$j;
								$query = "SELECT * FROM Ticket WHERE SeatNo = '".$seat."' AND BroadCastId = '".$id."'";
								$result = mysqli_query($db_conn, $query) 
									or die("<p>Query Error!<br>".mysqli_error($db_conn)."</p>");
								$availability = mysqli_fetch_array($result)["Valid"];

								if ($availability == 1) {
									echo "<div class='seat valid'>";
									echo "<input type='checkbox' class='chkBox' name='chkBox[]' value='".$seat."'><br>";
									echo $seat;
									echo "</div>";

								} else {
									echo "<div class='seat invalid'>";
									echo "Sold<br>";
									echo $seat;
									echo "</div>";
								}
							}
							echo "<br>";
						}
						echo "<div id='screen'>SCREEN</div>";
					?>
				</div>
				<br>
				<br>
				<input class="button" type="submit" value="Submit">
				<button class="button" onclick="location.href='buywelcome.php'" type="button">Cancel</button>
			</form>
		</div>

		<script>
			function checkValid() {
				var chkBox = document.getElementsByClassName("chkBox");
				var select = false;
				for (var i = chkBox.length - 1; i >= 0; --i)
					if (chkBox[i].checked == true)
						select = true;

				if (select == false) {
					alert("You haven't selected any seat!");	
					return false;
				}
				return true;
			}
		</script>

		<?php
			mysqli_free_result($result); 
			mysqli_free_result($result2); 
			mysqli_close($db_conn);
	}
?>