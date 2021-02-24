<?php
session_start();
require_once "database.php";

if (!isset($_SESSION['tata_logged_ID'])){
	header('Location: user.php') ;
	exit();
}

	$kidsQuery = $db->query('SELECT * FROM kids_themselves ORDER BY birthdate ASC');
	$kids = $kidsQuery->fetchAll();
	$oldestKid = $kids[0][2];
	$firstBirth = $kids[0][3];

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <title>Sentencje i aforyzmy - Juliusz Chojaczyk</title>
    <meta name="description" content="cytaty, bombelki, Julek, aforyzmy, bombelek">
    <meta name="keywords" content="cytaty, Julek, bombelek, bombelki">
    <meta http-equiv="X-Ua-Compatible" content="IE=edge">

    <link rel="stylesheet" href="css/main.css" type="text/css">
	<link href="css/lightbox.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/fontello.css"  type="text/css">
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
			<?
			if ($_SESSION['alWrite']==true)
				echo "<a href=\"addQuote.php\"><div class=\"navButton\">DODAJ CYTAT</div></a>";
			?>
			<a href="showQuotes.php"><div class="navButton">PRZEGLĄDAJ CYTATY</div></a>
			<a href="logout.php"><div class="navButton">WYLOGUJ</div></a>
			<div style="clear:both;"></div> 				
		</div>
	</nav>
	<main>
		<div class="kidsContainer">
		
			<div class="navButton" style="text-align:center;">BOMBELKI</div>
			
			<div class="kidsNav">
			
				<a href="addKid.php"><div class="kidNavButton" style="background-color:#37b93d; margin-bottom:30px;">DODAJ BOMBELKA <i class="icon-user-plus"></i></div></a>
			
				<? foreach ($kids as $kid)
				{
					echo "<div class=\"kidNavButton\">".strtoupper($kid['kiduno'])."	<i class=\"icon-" ;
					if ($kid['sex']=='female') echo "fe" ;
					echo "male\"></i></div>" ;
					echo "<div class=\"kidContainer\">" ;
					echo "<div class=\"kidPicture\">" ;
					echo "<a class=\"example-image-link\" href=\"pics\\".$kid['default_pic']."\" data-lightbox=\"example-1\"><img class=\"example-image\" src=\"pics\\".$kid['default_pic']."\"  alt=\"image-1\" width=\"200\" /></a>";
					echo "</div>" ;
					echo "<div class=\"kidInfo\">";
						echo "<u>NARODZINY:</u></br>".$kid['birthdate']."</br></br>";
						echo "INFO:</br>".$kid['about_kid']."</br>";
					echo "</div>" ;
					echo "<div style=\"clear:both;\"></div>";
					
					
					
					echo "</div>" ;
					
				}
				
				?>		
			</div>
				
		</div>
	</main>
	<!---------------------------------------------------------------------------------------------------->
	<footer>
		<div class="footContainer">
			Copyrights All Rights Reserved - Adam Chojaczyk 2020 r.
		</div>
	</footer>
	
<script src="js/lightbox-plus-jquery.js">
	lightbox.option({
      'alwaysShowNavOnTouchDevices': true
    })
</script>
<!---------------------------------------------------------------------------------------------------->
</body>
</html>