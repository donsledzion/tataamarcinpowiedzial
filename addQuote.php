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
		$_SESSION['e_edit'] = 'Ups! Nie masz uprawnień do edycji wpisów!' ;
		header ('Location: showQuotes.php');
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
	$oldestKid = $kids[0][2];
	$firstBirth = $kids[0][3];
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <title>Dodaj cytat</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link rel="stylesheet" href="css/main.css">
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
		    <div class="navButton"><a href="index.php">STRONA GŁÓWNA</a></div>
			<div class="navButton"><a href="showKids.php">BOMBELKI</a></div>
			<div class="navButton"><a href="showQuotes.php">PRZEGLĄDAJ CYTATY</a></div>
			<div class="navButton"><a href="logout.php">WYLOGUJ</a></div>
			<div style="clear:both;"></div> 				
		</div>
	</nav>
<!---------------------------------------------------------------------------------------------------->
<!----------------------------  TREŚĆ ---------------------------------------------------------------->
<!---------------------------------------------------------------------------------------------------->
	<main>
		<article>
			<div class="container">
<!--------------------------------------OTWARCIE FORMULARZA------------------------------------------->

				<form action="submitQuote.php" method="post" id="addQuote" name="addQuote" enctype="multipart/form-data">
				
<!---------------------------------------------------------------------------------------------------->
<!----------------------------------------- CYTOWANE DZIECKO ----------------------------------------->
<!---------------------------------------------------------------------------------------------------->

					<select name="quoted" id="quoted">
						<?
							foreach ($kids as $kid) 
							{
								//$kidsName = $kid['kiduno'];
								echo "<option value=\"".$kid['kiduno']."\"";
								if($kid['kiduno']==$_SESSION['fill_quoted']) 
								{
									echo " selected ";
									unset($_SESSION['fill_quoted']);
								}								
								echo ">".$kid['kiduno']."</option>";
							}
						?>
					</select>					
						
<!---------------------------------------------------------------------------------------------------->
<!------------------------------------------ CYTAT --------------------------------------------------->
<!---------------------------------------------------------------------------------------------------->
				
					<textarea name="quote" id="quote" wrap="soft" rows="3" form="addQuote"><?
							if (isset($_SESSION['fill_quote']))
							{
								echo $_SESSION['fill_quote'] ;
								unset($_SESSION['fill_quote']);
							}
							else
							{
								echo "...cytat..." ;
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
					<input type="file" name="picture" id="picture" placeholder="fotka" onfocus="this.placeholder=''" onblur="this.placeholder='fotka'">
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
					
<!---------------------------------------------------------------------------------------------------->
<!------------------------------------------ DATA ---------------------------------------------------->
<!---------------------------------------------------------------------------------------------------->
					
					<?
					echo "<input type=\"date\" name=\"quote_date\" min=\"".$firstBirth."\" max=\"".date("Y-m-d")."\" value=\"" ;
								if (isset($_SESSION['fill_date']))
								{
									echo $_SESSION['fill_date'] ;
									unset($_SESSION['fill_date']);
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
					
					<input type="submit" value="Dodaj cytat" name="submit">
															
				</form>
<!------------------------------------------ ZAMKNIĘCIE FORMULARZA ------------------------------------------->

			</div>
		</article>
	</main>
	
	<footer>
		<div class="footContainer">
			Copyrights All Rights Reserved - Adam Chojaczyk <?php echo date("Y")?> r.
		</div>
	</footer>
</body>
</html>
