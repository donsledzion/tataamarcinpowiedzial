<?php

session_start();

if(isset($_SESSION['tata_logged_ID']))
{

	require_once 'database.php' ;

	if (isset($_POST['quote']))
	{
		$_SESSION['quote'] = $_POST['quote'];
		//udana walidacja? Załóżmy, że tak!
		$all_OK = true ; //ustawienie flagi! dowolna niepoprawność zmieni flagę na false
		
		
		// przypisanie do zmiennych sesyjnych wartości przesłanych przez POST w celu automatycznego uzupełnienia formularza
		// w przypadku napotkania błędu i odesłania użytkownika do poprawienia niewłaściwych danych
		$_SESSION['fill_quote'] = $_POST['quote'];
		$_SESSION['fill_quoted'] = $_POST['quoted'];
		$_SESSION['fill_picture'] = $_POST['picture'];
		$_SESSION['fill_date'] = $_POST['quote_date'];
		
		//Sprawdź czy dokonano jakiejkolwiek formy wpisu (cytat lub zdjęcie)
		
		if(!isset($_POST['quoted']))
		{
			$all_OK = false ;
			$_SESSION['e_fillKid']="Kogoś musisz zacytować!";
		}
		else
		{
			$quotedQuery = $db->prepare('SELECT * FROM kids_themselves WHERE kiduno=? ');
			$quotedQuery -> execute([$_POST['quoted']]);
			$qQ = $quotedQuery->fetch();
			$quotedBirth = $qQ['birthdate'];
		}
		
		
		if(!isset($_POST['quote']))
		{
			$all_OK = false ;
			$_SESSION['e_fillAny']="wpisz cokolwiek!";
		}
		if(empty($_FILES["picture"]["name"]))
			$picture = 'julek.jpg' ; // domyślny obrazek
		else
		{
			include 'pictureUpload.php' ;
			if(isset($_SESSION['e_upload']))
			{
				header('Location: addQuote.php');
				exit();
			}	
			$picture = $_SESSION['picture'] ;
		}
		
		//Sprawdź czy data podana w formularzu nie jest z przyszłości!
		$currentDate = date("Y-m-d") ;
		if(date(strtotime($currentDate)-strtotime($_POST['quote_date']))<0)
		{
			$all_OK = false;
			$_SESSION['e_date'] = 'Elo, elo! Data cytatu nie może być z przyszłości!' ;
		}
		else if($_POST['quote_date']<$quotedBirth)
		{
			$all_OK = false;
			$_SESSION['e_date'] = 'Elo, elo! Cytat sprzed narodzin dziecka?!' ;
		}
		
		if($all_OK == true)
		{				
		$query = $db -> prepare('INSERT INTO kids_post VALUES(:id, :datestamp, :quote_date, :author, :bombelek, :sentence, :picture)');
		$query->bindValue(':id', NULL);
		$query->bindValue(':datestamp', $currentDate); //data dodania wpisu
		$query->bindValue(':quote_date', $_POST[quote_date]); //data wypowiedzi :)
		$query->bindValue(':author', $_SESSION[tata_logged_ID], PDO::PARAM_STR);
		$query->bindValue(':bombelek', $_POST[quoted], PDO::PARAM_STR);
		$query->bindValue(':sentence', $_POST[quote], PDO::PARAM_STR);
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
			header('Location: addQuote.php') ;
			exit();
		}
	
} else {
	$_SESSION['redirect'] = true ;
	header('Location: addQuote.php') ;
	exit();
}
	
	header('Location: showQuotes.php');
	exit();
	
}
else
	header('Location: index.php');
?>
