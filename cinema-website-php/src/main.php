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
		<title>Main Page</title>
		<link rel="shortcut icon" href="favicon.ico" type="image/icon"> 
		<link rel="icon" href="favicon.ico" type="image/icon"> 
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="style/main.css">
		<div class="nav">
			<a class="left" href="main.php">HK Cinema</a>
			<a class="right" href="logout.php">Logout</a>
			<a class="right" href="history.php">Purchase History</a>
			<a class="right" href="comment.php">Movie Review</a>
			<a class="right" href="buywelcome.php">Buy A Ticket</a>
		</div>
		<main>
			<div class="centered">
				<h1 id="name">HK Cinema</h1>
				<h1 id="slogan">Enjoy the film in our best cinema</h1>
			</div>
		</main>
		<?php
	}
?>
