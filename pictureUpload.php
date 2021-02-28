<?php
session_start();

include 'imgMods.php';

if (!isset($_SESSION['tata_logged_ID'])){
	header('Location: user.php') ;
	exit();
}

$target_dir = "pics/";
$target_file = $target_dir.basename($_FILES["picture"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
$oldName = basename($_POST['oldPicture']);
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
  $check = getimagesize($_FILES["picture"]["tmp_name"]);
  if($check != false) {
    //echo "File is an image - " . $check["mime"] . ".";
    $uploadOk = 1;
    $_SESSION['target_file'] = date("Ymd").date("His").".".$imageFileType;
    $target_file = $target_dir.$_SESSION['target_file'];    
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

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
  $_SESSION['e_upload'] =  "Błąd - tylko obrazy w formacie JPG, JPEG, PNG oraz GIF!";
  $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    $_SESSION['e_upload'] =  "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
} else {
	//$_SESSION['e_upload'] = "Co sie jebie, ale tutaj dotarlo!" ;
  //if (move_uploaded_file($_FILES["picture"]["tmp_name"], $target_file)) {
  if (resize_picture($_FILES["picture"]["tmp_name"],768,$target_dir.'768/'.$_SESSION['target_file'])) {
      
        resize_picture('pics/768/'.$_SESSION['target_file'],480,$target_dir.'480/'.$_SESSION['target_file']);
        resize_picture('pics/768/'.$_SESSION['target_file'],320,$target_dir.'320/'.$_SESSION['target_file']);
        resize_picture('pics/768/'.$_SESSION['target_file'],160,$target_dir.'160/'.$_SESSION['target_file']);
        
        //if(empty($_FILES["editPicture"]["name"])){
	  $_SESSION['picture'] = basename($_FILES["picture"]["name"]) ;
        //} else {
        //  $_SESSION['picture'] = basename($_FILES["editPicture"]["name"]) ;
        //  unset($_FILES["editPicture"]["name"]);
        //}
        if(isset($oldName)&&ctype_digit($oldName[0])){
            delete_picture($_SESSION['oldPicture']);
            unset($oldName);
        }    
          
          
  } else {
    $_SESSION['e_upload'] =  "Wystąpił błąd podczas przesyłania pliku.";
  }
}
}
?>