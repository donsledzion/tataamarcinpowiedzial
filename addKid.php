<?php
session_start();
require_once "database.php";

if (!isset($_SESSION['tata_logged_ID']))
{
	header ('Location: index.php') ;
	exit();
}

if($_SESSION['alWrite']==false)
	{
		$_SESSION['e_edit'] = 'Ups! Nie masz uprawnień do edycji!' ;
		header ('Location: showKids.php');
		exit();
	}

if (!isset($_SESSION['redirect']))
{
	// jeśli nie zostałem odesłany to należy wyczyścić 
	// pola autouzupełniania formularza
	unset($_SESSION['fill_quote']);
	unset($_SESSION['fill_quoted']);
	unset($_SESSION['fill_picture']);
	unset($_SESSION['fill_date']);	
	unset($_SESSION['redirect']);
}

$kidsQuery = $db->query('SELECT * FROM kids_themselves ORDER BY birthdate ASC');
$kids = $kidsQuery->fetchAll();

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <title>Dodaj bombelka</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link rel="stylesheet" href="css/main.css" type="text/css">
    <link rel="stylesheet" href="css/fontello.css" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lobster|Open+Sans:400,700&amp;subset=latin-ext" rel="stylesheet">
    <!--[if lt IE 9]>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
    <![endif]-->
</head>

<body>
	<header>
		<div class="headContainer">
			<p>
			Witaj <?echo(ucfirst($_SESSION['tata_logged_ID']))?>
			</br>
			
			</p>
		</div>
	</header>
<!---------------------------------------------------------------------------------------------------->
<!---------------------------NAWIGACJA---------------------------------------------------------------->
<!---------------------------------------------------------------------------------------------------->
	<nav>
		<div class="navContainer" style="margin-top:20px;">
			<a href="addQuote.php"><div class="navButton">DODAJ CYTAT</div></a>
			<a href="showQuotes.php"><div class="navButton">PRZEGLĄDAJ CYTATY</div></a>
			<a href="logout.php"><div class="navButton">WYLOGUJ</div></a>
			<div style="clear:both;"></div> 				
		</div>
	</nav>
<!---------------------------------------------------------------------------------------------------->
<!----------------------------  TREŚĆ ---------------------------------------------------------------->
<!---------------------------------------------------------------------------------------------------->
	<main>
		<article>
			<div class="container">
			
			<div class="navButton" style="text-align:center;">BOMBELKI</div>
			
			<div class="kidsNav">
				<div class="kidNavButton" style="background-color:#37b93d; margin-bottom:30px;">DODAJ BOMBELKA <i class="icon-user-plus"></i></div>
			</div>
<!--------------------------------------OTWARCIE FORMULARZA------------------------------------------->

				<form action="saveNewKid.php" method="post" id="addKid" name="addKid" enctype="multipart/form-data">
				
<!---------------------------------------------------------------------------------------------------->
<!----------------------------------------- PŁEĆ DZIECKA --------------------------------------------->
<!---------------------------------------------------------------------------------------------------->
				<fieldset>
					<input type="radio" id="male" name="newKidGender" value="male">
					<label for="male"><i class="icon-male"></i></label>
					<input type="radio" id="female" name="newKidGender" value="female">
					<label for="female"><i class="icon-female"></i></label><br>
				</fieldset>
<!---------------------------------------------------------------------------------------------------->
<!----------------------------------------- IMIĘ DZIECKA --------------------------------------------->
<!---------------------------------------------------------------------------------------------------->

					<input type="text" name="kidName" id="kidName" placeholder="imię" onfocus="this.placeholder=''" onblur="this.placeholder='imię'">
					
<!---------------------------------------------------------------------------------------------------->
<!------------------------------------------ DATA NARODZIN ------------------------------------------->
<!---------------------------------------------------------------------------------------------------->
					
					<?
					echo "<input type=\"date\" name=\"kidBirth\" id=\"kidBirth\" max=\"".date("Y-m-d")."\" value=\"" ;
								if (isset($_SESSION['fill_birthday']))
								{
									echo $_SESSION['fill_birthday'] ;
									unset($_SESSION['fill_birthday']);
								}
								else
								{
									echo date("Y-m-d");
								}
					echo "\">" ;
					if(isset($_SESSION['e_date']))
					{
						echo 	"<div class=\"error\">
									<p>".$_SESSION['e_date']."</p>
								</div>" ;
						unset($_SESSION['e_date']);
					}
					?>
												
<!---------------------------------------------------------------------------------------------------->
<!------------------------------------------ KRÓTKIE INFO -------------------------------------------->
<!---------------------------------------------------------------------------------------------------->
				
					<textarea name="kidInfo" id="kidInfo" wrap="soft" rows="3" form="addKid"><?
							if (isset($_SESSION['fill_info']))
							{
								echo $_SESSION['fill_info'] ;
								unset($_SESSION['fill_info']);
							}
							else
							{
								echo "...podaj krótkie info o dziecku..." ;
							}?></textarea>
					<?if(isset($_SESSION['e_fillAny']))
							{
								echo 	"<div class=\"error\">
											<p>".$_SESSION['e_fillAny']."</p>
										</div>" ;
								unset($_SESSION['e_fillAny']);
							}
					?>
<!---------------------------------------------------------------------------------------------------->
<!------------------------------------------ ZDJĘCIE ------------------------------------------------->
<!---------------------------------------------------------------------------------------------------->
					<input type="file" name="default_picture" id="default_picture" placeholder="fotka" onfocus="this.placeholder=''" onblur="this.placeholder='fotka'">
					<div>
					<?
					if(isset($_SESSION['e_upload']))
					{
						echo 	"<div class=\"error\">
									<p>".$_SESSION['e_upload']."</p>
								</div>" ;
						unset($_SESSION['e_upload']);
					}
					?>
					</div>
					
					<input type="submit" value="Dodaj dziecko!" name="submit">
															
				</form>
<!------------------------------------------ ZAMKNIĘCIE FORMULARZA ------------------------------------------->

			</div>
		</article>
		
		<article>
			<div class="kidsContainer">
							
				<div class="kidsNav">
				
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
		</article>
	</main>
	
	<footer>
		<div class="footContainer">
			Copyrights All Rights Reserved - Adam Chojaczyk 2020 r.
		</div>
	</footer>
</body>
</html>
