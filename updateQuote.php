<?php

session_start();

if(isset($_SESSION['tata_logged_ID']))
{

	require_once 'database.php' ;

	if (isset($_POST['editQuote']))
	{
		$_SESSION['editQuote'] = $_POST['editQuote'];
		//udana walidacja? Załóżmy, że tak!
		$all_OK = true ; //ustawienie flagi! dowolna niepoprawność zmieni flagę na false
		
		$_SESSION['fill_quote'] = $_POST['editQuote'];
		$_SESSION['fill_quoted'] = $_POST['editQuoted'];
		$_SESSION['fill_picture'] = $_POST['editPicture'];
		$_SESSION['fill_date'] = $_POST['editQuote_date'];
		
		//Sprawdź czy dokonano jakiejkolwiek formy wpisu (cytat lub zdjęcie)
		
		if(!isset($_POST['editQuoted']))
		{
			$all_OK = false ;
			$_SESSION['e_fillKid']="Kogoś musisz zacytować!";
		}
		else
		{
			$quotedQuery = $db->prepare('SELECT * FROM kids_themselves WHERE kiduno=? ');
			$quotedQuery -> execute([$_POST['editQuoted']]);
			$qQ = $quotedQuery->fetch();
			$quotedBirth = $qQ['birthdate'];
		}
		
		
		if(!isset($_POST['editQuote']))
		{
			$all_OK = false ;
			$_SESSION['e_fillAny']="dodaj cokolwiek!";
		}
		if(empty($_FILES["editPicture"]["name"]))
			$picture = $_POST['oldPicture'] ; // domyślny obrazek
		else
		{
			include 'pictureUpdate.php' ;
			if(isset($_SESSION['e_upload']))
			{
				header('Location: editQuote.php');
				exit();
			}	
			$picture = $_SESSION['editPicture'] ;
		}
		
		//Sprawdź czy data podana w formularzu nie jest z przyszłości!
		$currentDate = date("Y-m-d") ;
		if(date(strtotime($currentDate)-strtotime($_POST['editQuote_date']))<0)
		{
			$all_OK = false;
			$_SESSION['e_date'] = 'Elo, elo! Data nie może być z przyszłości!' ;
		}
		else if($_POST['editQuote_date']<$quotedBirth)
		{
			$all_OK = false;
			$_SESSION['e_date'] = 'Elo, elo! Cytat sprzed narodzin dziecka?!' ;
		}
				
		if($all_OK == true)
		{			
		$query = $db -> prepare(	'UPDATE kids_post SET quote_date=:quote_date, bombelek=:bombelek, sentence=:sentence, picture=:picture WHERE id=:editID');
		$query->bindValue(':editID', $_POST[editID], PDO::PARAM_STR);
		$query->bindValue(':quote_date', $_POST[editQuote_date], PDO::PARAM_STR); //data zdarzenia
		$query->bindValue(':bombelek', $_POST[editQuoted], PDO::PARAM_STR);
		$query->bindValue(':sentence', $_POST[editQuote], PDO::PARAM_STR);
		$query->bindValue(':picture', $picture, PDO::PARAM_STR);
		$query->execute();
		
		unset($_SESSION['fill_quote']);
		unset($_SESSION['fill_quoted']);
		unset($_SESSION['fill_picture']);
		unset($_SESSION['fill_date']);
		}
		
		else 
		{
			$_SESSION['redirect'] = true ;
			header('Location: editQuote.php') ;
			exit();
		}
	
} else {
	$_SESSION['redirect'] = true ;
	header('Location: editQuote.php') ;
	exit();
}
	
	header('Location: showQuotes.php');
	exit();
	
}
else
	header('Location: index.php');
?>
