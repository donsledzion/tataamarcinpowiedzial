<?php


function resize_picture ($sourceFile,$targetWidth,$target_file)
{
    $source_file_dimensions = getimagesize($sourceFile);
    $name = basename($sourceFile);
    $target_name = basename($target_file);
    
    $width = $source_file_dimensions[0];
    $height = $source_file_dimensions[1];
    
    $img_type = $source_file_dimensions[2];
        
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
    
    switch($img_type){
        
        case IMAGETYPE_PNG:
            $source = imagecreatefrompng($sourceFile);    
            imagecopyresampled($small, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
            imagepng($small,$target_file);
            break;
        
        case IMAGETYPE_GIF:
            $source = imagecreatefromgif($sourceFile);    
            imagecopyresampled($small, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
            imagegif($small,$target_file);
            break;
        
        case IMAGETYPE_JPEG:
            $source = imagecreatefromjpeg($sourceFile);    
            imagecopyresampled($small, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
            imagejpeg($small,$target_file);
            break;
        
    }
    
    
    
    
    return true;
    
}


function delete_picture($picture_name)
{
    if(!unlink('pics/768/'.$picture_name)) return false;
    if(!unlink('pics/480/'.$picture_name)) return false;
    if(!unlink('pics/320/'.$picture_name)) return false;
    if(!unlink('pics/160/'.$picture_name)) return false;
    
    return true;
}


?>