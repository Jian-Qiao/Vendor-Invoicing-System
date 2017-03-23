<?php
session_start();
?>
<!DOCTYPE html>

<html>
	<head>
		<title>Manage Memo</title>
		<link rel="stylesheet" type="text/css" href="style.css">
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	</head>
	<body>
		<div id="Main">
			
			<ul id="Menu">
				<?php 
				if ($_SESSION["Priv"]==1){
					echo "<li><a href='Admin_Home.php'>Home</a></li>";
					echo "<li><a href='Memos.php'>Manage Memos</a></li>";                 
				}else{
					echo "<li><a href='Home.php'>Home</a></li>
					<li><a href='Fill_Invoices.php'>Fill Invoices</a></li>";
				}
				?>
				<li><a href="Completed_Invoices.php">Completed Invoices</a></li>
				<li><a href="Price_Sheet.php">View Price Sheet</a></li>

				<li><a href="Index.php">Log out</a></li>
			</ul>
			
			<h1>Memo Management</h1>
			<hr>
			
			<div class='Container'>
				<a href='Create_Memos.php'><div class='Memo_buttons' float='left'><h3 align='center'>Create Memos</h3><image src='Icons/Create.svg' width='100%' height='90%'></div></a>
				<a href='Edit_Memos.php'><div class='Memo_buttons' float='center'><h3 align='center'>Edit Memos</h3><image src='Icons/Edit.svg' width='100%' height='90%'></div></a>
				<a href='Ship_Memos.php'><div class='Memo_buttons' float='right'><h3 align='center'>Ship Memos</h3><image src='Icons/Ship.svg' width='100%' height='90%'></div></a>
				<span class='strech'></span>
			</div>
		</div>
	</body>
</html>