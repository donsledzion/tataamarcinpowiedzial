<?php

session_start();

if(isset($_SESSION['tata_logged_ID']))
{
    require_once 'database.php' ;

    $_SESSION['cameFrom'] = $_POST['cameFrom'];

    if (isset($_POST['quote']))
    {
        $_SESSION['quote'] = $_POST['quote'];
        //udana walidacja? Załóżmy, że tak!
        $all_OK = true ; //ustawienie flagi! dowolna niepoprawność zmieni flagę na false

        $currentDate = date("Ymd His") ;

        // przypisanie do zmiennych sesyjnych wartości przesłanych przez POST w celu automatycznego uzupełnienia formularza
        // w przypadku napotkania błędu i odesłania użytkownika do poprawienia niewłaściwych danych
        $_SESSION['fill_quote'] = $_SESSION['quote'];
        $_SESSION['fill_quoted'] = $_POST['quoted'];
        $_SESSION['fill_picture'] = $_POST['picture'];
        $_SESSION['fill_date'] = $_POST['quote_date'];

        if($_SESSION['cameFrom']=='editQuote.php'){            
            $_SESSION['oldPicture'] = basename($_POST['oldPicture']);
            $_SESSION['target_file'] = date("Ymd").date("His").".".strtolower(pathinfo(basename($_POST['oldPicture']),PATHINFO_EXTENSION));
            $_SESSION['edit_date'] = $currentDate;
        } else {
            $_SESSION['submit_data'] = $currentDate;            
        }       

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

        if(empty($_FILES["picture"]["name"])) {
                $picture = strtolower($_POST['quoted']).'.jpg' ; // default picture depending on quoted child
        }
        else
        {
                include 'pictureUpload.php' ;

                if(isset($_SESSION['e_upload']))
                {
                        header('Location: '.$_SESSION['cameFrom']) ;
                        exit();
                }	
                $picture = $_SESSION['target_file'] ;
        }

        //Sprawdź czy data podana w formularzu nie jest z przyszłości!

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
        $dateToInsert = "";
        $dateToInsert .= $_POST['quote_date']." ".date("H:i:s",strtotime($currentDate));

        $currentDate = date("Y-m-d H:i:s") ;   
//=========================================================================================        
       if($_SESSION['cameFrom']=='editQuote.php'){
            $query = $db -> prepare('UPDATE kids_post '
                    . ' SET datestamp=:datestamp,'
                    . ' quote_date=:quote_date,'
                    . ' bombelek=:bombelek,'
                    . ' sentence=:sentence,'
                    . ' picture=:picture'
                    . ' WHERE id=:editID');
            $query->bindValue(':editID', $_POST[editID], PDO::PARAM_STR);
            $query->bindValue(':datestamp', $currentDate);
            $query->bindValue(':quote_date', $dateToInsert, PDO::PARAM_STR); //data zdarzenia
            $query->bindValue(':bombelek', $_POST[quoted], PDO::PARAM_STR);
            $query->bindValue(':sentence', $_SESSION[quote], PDO::PARAM_STR);
            $query->bindValue(':picture', $picture, PDO::PARAM_STR);
            $query->execute();
            $oldName = basename($_POST['oldPicture']);
            
            if(ctype_digit($oldName[0])){
                rename("pics/768/".$oldName, "pics/768/".$picture);
                rename("pics/480/".$oldName, "pics/480/".$picture);
                rename("pics/320/".$oldName, "pics/320/".$picture);
                rename("pics/160/".$oldName, "pics/160/".$picture);
            }
        } else {
//=========================================================================================

            $query = $db -> prepare('INSERT INTO kids_post VALUES('
                    . ':id,'
                    . ' :datestamp,'
                    . ' :quote_date,'
                    . ' :author,'
                    . ' :bombelek,'
                    . ' :sentence,'
                    . ' :picture)');
            $query->bindValue(':id', NULL);
            $query->bindValue(':datestamp', $currentDate); //data dodania wpisu
            $query->bindValue(':quote_date', $dateToInsert); //data wypowiedzi :)
            $query->bindValue(':author', $_SESSION[tata_logged_ID], PDO::PARAM_STR);
            $query->bindValue(':bombelek', $_POST[quoted], PDO::PARAM_STR);
            $query->bindValue(':sentence', $_SESSION[quote], PDO::PARAM_STR);
            $query->bindValue(':picture', $picture, PDO::PARAM_STR);
            $query->execute();
        }    
        unset($_SESSION['fill_quote']);
        unset($_SESSION['fill_quoted']);
        unset($_SESSION['fill_picture']);
        unset($_SESSION['fill_date']);		
        
        } else {
            $_SESSION['redirect'] = true ;
            header('Location: '.$_SESSION['cameFrom']) ;
            exit();
        }
	
} else {
    $_SESSION['e_fillAny']="wpisz cokolwiek!";
    $_SESSION['redirect'] = true ;
    
    header('Location: '.$_SESSION['cameFrom']) ;
    exit();
}	
    header('Location: showQuotes.php');
    exit();	
}
else
    header('Location: index.php');
?>