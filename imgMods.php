<?php


function resize_picture ($sourceFile,$targetWidth)
{
    $source_file_dimensions = getimagesize($sourceFile);
    $name = basename($sourceFile);
    
    $width = $source_file_dimensions[0];
    $height = $source_file_dimensions[1];
        
    if($width<$height)
    {
        $aspect = $width / $targetWidth;
        $new_width = $targetWidth;
        $new_height= $height / $aspect;
    }
    else
    {
        $aspect = $height / $targetWidth;
        $new_height = $targetWidth;
        $new_width= $width / $aspect;
    }
        
        
    $small = imagecreatetruecolor($new_width, $new_height);
    $source = imagecreatefromjpeg($sourceFile);
    
    imagecopyresampled($small, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
    imagejpeg($small,'processed/'.pathinfo($name,PATHINFO_FILENAME)."_".$targetWidth.".".pathinfo($name,PATHINFO_EXTENSION));
    
}


?>