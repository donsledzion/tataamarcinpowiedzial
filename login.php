<?php
session_start();

if (!isset($_SESSION['tata_logged_ID']))
{
	if(isset($_POST['login']))
	{
		$login = filter_input(INPUT_POST, 'login');
		$password = filter_input(INPUT_POST, 'pass');
		
		require_once "database.php";
		
		$matchPass = $db->prepare('SELECT login, password FROM kids_users WHERE login = :login');
		$matchPass->bindValue(':login', $login, PDO::PARAM_STR);
		$matchPass->execute();
		
		echo $matchPass->rowCount()."<br/>";
		
		$user = $matchPass->fetch();
		
		echo $password." ".$user['password']."<br/>";
		
		if(password_verify($password, $user['password']))
		{
			$_SESSION['tata_logged_ID'] = $user['login'] ;
			unset($_SESSION['bad_attempt']);
			
			$catQuery = $db->prepare('SELECT login, category FROM kids_users WHERE login=?');
			$catQuery -> execute([$_SESSION[tata_logged_ID]]);
			$cQ = $catQuery->fetch();
			
			$permQuery = $db->prepare('SELECT category_name, allow_write FROM kids_category WHERE category_name=?');
			$permQuery -> execute([$cQ[1]]);
			$pQ = $permQuery->fetch();
			if($pQ['allow_write']=='1')
				$_SESSION['alWrite'] = true; 
			else
				$_SESSION['alWrite'] = false; 
			
		}
		else
		{
			$_SESSION['bad_attempt'] = true ;
			header('Location: index.php');
			exit();
		}
	}
	else
	{
		header('Location: index.php');
		exit();
	}
}

header('Location: index.php');
echo $_SESSION['tata_logged_ID']."<br/>" ;
echo 'dane logowania poprawne, trwa logowanie';



?>