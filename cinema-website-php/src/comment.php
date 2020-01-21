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
		<title>Movie Comments</title>
		<link rel="shortcut icon" href="favicon.ico" type="image/icon"> 
		<link rel="icon" href="favicon.ico" type="image/icon"> 
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="style/comment.css">
		<div class="nav">
			<a class="left" href="main.php">HK Cinema</a>
			<a class="right" href="logout.php">Logout</a>
			<a class="right" href="history.php">Purchase History</a>
			<a class="active right" href="comment.php">Movie Review</a>
			<a class="right" href="buywelcome.php">Buy A Ticket</a>
		</div>
		<main>
			<div class="container">
				<h1>Movie Review</h1>
				<br>
				<br>
				<form action="comment_submit.php" onsubmit="return checkEmpty();" method="post">
					<?php
						$db_conn = mysqli_connect(host,username,password,dbname) 
							or die("Connection Error!".mysqli_connect_error());
						$query = "SELECT * FROM Film";
						$result = mysqli_query($db_conn, $query) 
							or die("<p>Query Error!<br>".mysqli_error($db_conn)."</p>");

						if (mysqli_num_rows($result) > 0) {
							echo "Film Name: ";
							echo "<select name='filmName' id='filmName'>";
								while ($row = mysqli_fetch_array($result)) {
									echo "<option value='".$row['FilmName']."'>".$row['FilmName']."</option>";
								}
							echo "</select>";
						}

						mysqli_free_result($result); 
						mysqli_close($db_conn);
					?>
					<br>
					<br>
					<textarea id="input_comment" name="comments" cols="80" rows="20" placeholder="Please input comment here"></textarea>
					<br>
					<br>
					<button class="button" type="button" id="view_comment">View comment</button>
					<input class="button" type="submit" value="Submit comment">
				</form>
				<br>
				<br>
				<section id="all_comments">
				</section>
			</div>
		</main>

		<script>
			function checkEmpty() {
				var comment = document.getElementById("input_comment");
				if (!comment.value) {
					alert("Please enter the comment before the submission.");	
					return false;
				}
				return true;
			}

			var ajaxObj = new XMLHttpRequest(); 
			if (!ajaxObj) { 
				alert("Cannot create XMLHttpRequest object!"); 
			}

			function ajaxRequest() {
				var filmName = document.getElementById("filmName").value;
				ajaxObj.onreadystatechange = ajaxResponse;
				ajaxObj.open('GET', "comment_retrieve.php?name=" + filmName, true);
				ajaxObj.send();
			}

			var view = document.getElementById("view_comment");
			view.addEventListener('click', ajaxRequest);

			function ajaxResponse() {
				if (ajaxObj.readyState == 4 && ajaxObj.status == 200) {
					let jsObj = JSON.parse(ajaxObj.responseText);
					let txt = "";
					for (let i in jsObj) {
						txt += "<br><h2>Viewer: " + jsObj[i].viewer + "</h2>";
						txt += "<h3>Comment: " + jsObj[i].comment + "</h3><br><hr>"
					}
					if (txt == "") {
						txt = "No comment given."
					}
					document.getElementById("all_comments").innerHTML = txt;
				}
			}
		</script>
		<?php
	}
?>