<?php

session_start();

if(isset($_SESSION['tata_logged_ID']))
{

	require_once 'database.php' ;
        include 'imgMods.php';

	if (isset($_POST['deleteID']))
	{
                if(($_POST['deletePicName']!='julek.jpg')&&($_POST['deletePicName']!='hania.jpg')) {
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
