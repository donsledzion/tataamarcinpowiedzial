<?php

session_start();

if(isset($_SESSION['tata_logged_ID']))
{

	require_once 'database.php' ;

	if (isset($_POST['deleteID']))
	{
					
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
