<?php
session_start();
require_once "database.php";

if (!isset($_SESSION['tata_logged_ID']))
{
	header ('Location: index.php') ;
	exit();
}
else
{
	if($_SESSION['alWrite']==false)
	{
		$_SESSION['e_edit'] = 'Ups! Nie masz uprawnień do edycji wpisów!' ;
		header ('Location: showQuotes.php');
		exit();
	}
	if (isset($_SESSION['editID']))
		$postID = $_SESSION['editID'] ;
	else
		$postID = $_POST['postID'] ;
	
	$editQuery = $db->prepare('SELECT * FROM kids_post WHERE id=?');
	$editQuery -> execute([$postID]);
	$eQ = $editQuery->fetch();
	unset($_SESSION['postID']) ;
	
	if(isset($_SESSION['fill_quote']))
		$edit_sentence = $_SESSION['fill_quote'];
	else
		$edit_sentence = $eQ['sentence'];
	
	if(isset($_SESSION['datestamp']))
		$edit_Datestamp = $_SESSION['datestamp'];
	else
		$editDatestamp = $eQ['datestamp'];
	
	if(isset($_SESSION['fill_date']))
		$edit_qd = $_SESSION['fill_date'];
	else
	$edit_qd = $eQ['quote_date'];

	if(isset($_SESSION['fill_quoted']))
		$edit_kid = $_SESSION['fill_quoted'];
	else
		$edit_kid = $eQ['bombelek'];
	
	if(isset($_SESSION['fill_picture']))
		$edit_picture = $_SESSION['fill_picture'];
	else
		$edit_picture = $eQ['picture'];
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
			Witaj <?echo(ucfirst($_SESSION['tata_logged_ID']));?>
			</br>
			
			</p>
		</div>
	</header>

	<nav>
		<div class="navContainer" style="margin-top:20px;">
		    <a href="index.php"><div class="navButton">STRONA GŁÓWNA</div></a>
			<div class="navButton"><a href="addQuote.php">DODAJ CYTAT</a></div>
			<div class="navButton"><a href="showQuotes.php">PRZEGLĄDAJ CYTATY</a></div>			
			<div class="navButton"><a href="logout.php">WYLOGUJ</a></div>
			<div style="clear:both;"></div> 				
		</div>
	</nav>

	<main>
		<article>
			<div class="container">
				<form action="updateQuote.php" method="post" id="editQuote" name="editQuote" enctype="multipart/form-data">
					<input type="hidden" name="editID" id="editID" value=<?php echo "\"".$postID."\"";?>>
					
<!---------------------------------------------------------------------------------------------------->
<!----------------------------------------- CYTOWANE DZIECKO ----------------------------------------->
<!---------------------------------------------------------------------------------------------------->

					<select name="editQuoted" id="editQuoted">
						<?
							foreach ($kids as $kid) 
							{
								//$kidsName = $kid['kiduno'];
								echo "<option value=\"".$kid['kiduno']."\"";
								if($kid['kiduno']==$edit_kid) 
								{
									echo " selected ";
								}								
								echo ">".$kid['kiduno']."</option>";
							}
						?>
					</select>					

<!---------------------------------------------------------------------------------------------------->
<!------------------------------------------ CYTAT --------------------------------------------------->
<!---------------------------------------------------------------------------------------------------->
					<textarea name="editQuote" id="editQuote" wrap="soft" rows="3" form="editQuote"><?echo $edit_sentence;?></textarea>
					<? echo "
					<div class=\"picture\">
						<img src=\"pics\\".$edit_picture."\" width=\"200\">
					</div>";
					?>
					<input type="hidden" name="oldPicture" id="oldPicture" value=<?echo "\"".$edit_picture."\"";?>>

<!---------------------------------------------------------------------------------------------------->
<!------------------------------------------ ZDJĘCIE ------------------------------------------------->
<!---------------------------------------------------------------------------------------------------->					
					<input type="file" name="editPicture" id="editPicture">
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
					echo "<input type=\"date\" name=\"editQuote_date\" min=\"".$firstBirth."\" max=\"".date("Y-m-d")."\" value=\"" ;
							
								if (isset($edit_qd))
								{
									echo $edit_qd ;
									unset($edit_qd);
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
					<input type="submit" value="Zapisz edycję" name="submit">
															
				</form>
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
