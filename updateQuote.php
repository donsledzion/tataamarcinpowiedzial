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
		$_SESSION['fill_picture'] = $_POST['picture'];
		$_SESSION['fill_date'] = $_POST['editQuote_date'];
                $_SESSION['oldPicture'] = basename($_POST['oldPicture']);
		$_SESSION['target_file'] = date("Ymd").date("His").".".strtolower(pathinfo(basename($_POST['oldPicture']),PATHINFO_EXTENSION));
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
		if(empty($_FILES["picture"]["name"]))
			//$picture = $_POST['oldPicture'] ; // domyślny obrazek
                        true;
		else
		{
			include 'pictureUpload.php' ;
			if(isset($_SESSION['e_upload']))
			{
				header('Location: editQuote.php');
				exit();
			}	
			
		}
                $picture = $_SESSION['target_file'] ;
		
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
		$currentDate = date("Ymd His") ;
                $_SESSION['edit_date'] = $currentDate;
		if($all_OK == true)
		{
                $dateToInsert = "";
                $dateToInsert .= $_POST['editQuote_date']." ".date("H:i:s",strtotime($currentDate));

                $currentDate = date("Y-m-d H:i:s") ;   
                    
		$query = $db -> prepare(	'UPDATE kids_post SET datestamp=:datestamp, quote_date=:quote_date, bombelek=:bombelek, sentence=:sentence, picture=:picture WHERE id=:editID');
		$query->bindValue(':editID', $_POST[editID], PDO::PARAM_STR);
                $query->bindValue(':datestamp', $currentDate);
		$query->bindValue(':quote_date', $dateToInsert, PDO::PARAM_STR); //data zdarzenia
		$query->bindValue(':bombelek', $_POST[editQuoted], PDO::PARAM_STR);
		$query->bindValue(':sentence', $_POST[editQuote], PDO::PARAM_STR);
		$query->bindValue(':picture', $picture, PDO::PARAM_STR);
		$query->execute();
		$oldName = basename($_POST['oldPicture']);
                if(ctype_digit($oldName[0])){
                    rename("pics/768/".$oldName, "pics/768/".$picture);
                    rename("pics/480/".$oldName, "pics/480/".$picture);
                    rename("pics/320/".$oldName, "pics/320/".$picture);
                    rename("pics/160/".$oldName, "pics/160/".$picture);
                }
                
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
