<?php
session_start();
require_once "database.php";

if (!isset($_SESSION['tata_logged_ID'])){
	header('Location: user.php') ;
	exit();
}

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <title>Sentencje i aforyzmy - Juliusz Chojaczyk</title>
    <meta name="description" content="cytaty, bombelki, Julek, aforyzmy, bombelek">
    <meta name="keywords" content="cytaty, Julek, bombelek, bombelki">
    <meta http-equiv="X-Ua-Compatible" content="IE=edge">

    <link rel="stylesheet" href="css/main.css">
    <link href="https://fonts.googleapis.com/css?family=Lobster|Open+Sans:400,700&amp;subset=latin-ext" rel="stylesheet">
    <!--[if lt IE 9]>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
    <![endif]-->
</head>

<body>
    
	<!---------------------------------------------------------------------------------------------------->
	<header>
		<div class="headContainer">
			<p>
			Witaj <?echo(ucfirst($_SESSION['tata_logged_ID']))?>
			</br>
			
			</p>
		</div>
	</header>
<!---------------------------------------------------------------------------------------------------->	

	<nav>
		<div class="navContainer" style="margin-top:20px;">
			<div class="navButton"><a href="showKids.php">BOMBELKI</a></div>
			<?
			if ($_SESSION['alWrite']==true)
				echo "<a href=\"addQuote.php\"><div class=\"navButton\">DODAJ CYTAT</div></a>";
			?>
			<div class="navButton"><a href="showQuotes.php">PRZEGLĄDAJ CYTATY</a></div>
			<div class="navButton"><a href="logout.php">WYLOGUJ</a></div>
			<div style="clear:both;"></div> 				
		</div>
	</nav>
	
	<!---------------------------------------------------------------------------------------------------->
	<footer>
		<div class="footContainer">
			Copyrights All Rights Reserved - Adam Chojaczyk 2020 r.
		</div>
	</footer>
<!---------------------------------------------------------------------------------------------------->
</body>
</html>