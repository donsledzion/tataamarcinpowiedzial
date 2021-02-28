<?php

session_start();

if(isset($_SESSION['tata_logged_ID']))
{

	require_once 'database.php' ;
        include 'imgMods.php';

	if (isset($_POST['deleteID']))
	{                        
                $string = "".$_POST['deletePicName'];
            
                if(ctype_digit( $string[0] )) { // script checks if picture's name starts with digit. If it's true it means that it's not default kid's picture (because it's named after kid's name)
                    delete_picture($_POST['deletePicName']);
                }
                
		$query = $db -> prepare(	'DELETE FROM kids_post WHERE id=:deleteID');
		$query->bindValue(':deleteID', $_POST[deleteID], PDO::PARAM_STR);
		$query->execute();
	
} else {
	header('Location: showQuotes.php') ;
	exit();
}
	
	header('Location: showQuotes.php');
	exit();
	
}
else
	header('Location: index.php');
?>
