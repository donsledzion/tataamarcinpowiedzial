<?php
session_start();
require_once "database.php";

if (!isset($_SESSION['tata_logged_ID']))
{
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
	<link href="css/lightbox.css" rel="stylesheet" />
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
			<?
			if (isset($_SESSION['e_edit']))
			{
				echo 	"
							<div class=\"error\">
								".$_SESSION['e_edit']."
							</div>
						";
				unset($_SESSION['e_edit']);
			}
			?>
			</p>
		</div>
	</header>
<!---------------------------------------------------------------------------------------------------->	


	<nav>
		<div class="navContainer" style="margin-top:20px;">
		    <a href="index.php"><div class="navButton">STRONA GŁÓWNA</div></a>
			<div class="navButton"><a href="showKids.php">BOMBELKI</a></div>
			<?
			if ($_SESSION['alWrite']==true)
				echo "<a href=\"addQuote.php\"><div class=\"navButton\">DODAJ CYTAT</div></a>";
			?>
			<div class="navButton"><a href="logout.php">WYLOGUJ</a></div>
			<div style="clear:both;"></div> 				
		</div>
	</nav>
	
	<main>
	
	<?php
		   
	   $quotesQuery = $db->query('SELECT * FROM kids_post ORDER BY quote_date DESC');
	   $quotes = $quotesQuery->fetchAll();
		   
		   //print_r($records);	
		foreach($quotes as $quote)
		{
	
			echo "<div class=\"post\">
				
				<div class=\"quote\">".htmlentities($quote['sentence'])."<br>"
                                . "<p class=\"quote_date\">{$quote['bombelek']}  {$quote['quote_date']}</p></div>
				
				<div class=\"picture\">
					<a class=\"example-image-link\" href=\"pics\\".$quote['picture']."\" data-lightbox=\"example-1\"><img class=\"example-image\" src=\"pics\\".$quote['picture']."\"  alt=\"image-1\" width=\"200\" /></a>
				</div>
				
				<div style=\"clear:both\">
				</div>";
				if($_SESSION['alWrite']==true)
				{
					$_SESSION['edit_id'] = $quote['id'];
					$_SESSION['edit_ds'] = $quote['datestamp'];
					$_SESSION['edit_qd'] = $quote['quote_date'];
					$_SESSION['edit_kid'] = $quote['bombelek'];
					$_SESSION['edit_sentence'] = $quote['sentence'];
					$_SESSION['edit_picture'] = $quote['picture'];
					//echo "<br>".$_SESSION['picture']."</br>";
					echo 	"
							<div style=\"float:left;\">
								<form action=\"editQuote.php\" method=\"post\" name=\"editQuote\" id=\"editQuote\">
									<input type = \"hidden\" name=\"postID\" id=\"postID\" value=\"".$quote['id']."\">
									<input type = \"submit\" style=\"width:300px; background-color:#9de1a1\" name=\"editSubmit\" value=\"edytuj\">
								</form>
							</div>
							
							<div style=\"float:left; margin-left:35px;\">
								<form action=\"deleteQuote.php\" method=\"post\" name=\"deleteQuote\" id=\"deleteQuote\">
									<input type = \"hidden\" name=\"deleteID\" id=\"deleteID\" value=\"".$quote['id']."\">
									<input type = \"submit\" style=\"width:300px;background-color:#ff8080;\" name=\"deleteSubmit\" value=\"usuń\" >
								</form>
							</div>
							<div style=\"clear:both;\"></div>
							";
				}
				
			echo "</div>
			";
		}
	?>

	</main>
	
	<!--------------------------------<img src=\"pics\\".$quote['picture']."\" width=\"200\">-------------------------------------------------------------------->
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