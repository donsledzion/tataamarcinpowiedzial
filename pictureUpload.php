<?php
session_start();

if (!isset($_SESSION['tata_logged_ID'])){
	header('Location: user.php') ;
	exit();
}

$target_dir = "pics/";
$target_file = $target_dir . basename($_FILES["picture"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
  $check = getimagesize($_FILES["picture"]["tmp_name"]);
  if($check !== false) {
    //echo "File is an image - " . $check["mime"] . ".";
    $uploadOk = 1;
  } else {
    $_SESSION['e_upload'] = "Błąd - plik nie jest obrazem!";
    $uploadOk = 0;
  }
}



// Check if file already exists
if (file_exists($target_file)) {
	$_SESSION['picture'] = basename($_FILES["picture"]["name"]) ;
	//$_SESSION['e_upload'] =  "Błąd - ten obraz już istnieje!";
	//$uploadOk = 0;
}
else{
// Check file size
if ($_FILES["picture"]["size"] > 5000000) {
  $_SESSION['e_upload'] =  "Błąd - za duży plik!";
  $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
  $_SESSION['e_upload'] =  "Błąd - tylko obrazy w formacie JPG, JPEG, PNG oraz GIF!";
  $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
  //$_SESSION['e_upload'] =  "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
	//$_SESSION['e_upload'] = "Co sie jebie, ale tutaj dotarlo!" ;
  if (move_uploaded_file($_FILES["picture"]["tmp_name"], $target_file)) {
	  $_SESSION['picture'] = basename($_FILES["picture"]["name"]) ;
    //echo "The file ". htmlspecialchars( basename( $_FILES["picture"]["name"])). " has been uploaded.";
  } else {
    $_SESSION['e_upload'] =  "Wystąpił błąd podczas przesyłania pliku.";
  }
}
}
?>