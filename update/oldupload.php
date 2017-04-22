<?php

$response = array();
$response['success'] = 0;
$response['log'] = "Hello World!";

$target_dir = "images/";// $_SERVER['DOCUMENT_ROOT']."/images/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

// Check if image file is a actual image or fake image
if(isset($_POST["submit"]))
{
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false)
    {
        $response['log'] = "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    }
    else
    {
        $response['log'] = "File is not an image.";
        $uploadOk = 0;
    }
}
// Check if file already exists
if (file_exists($target_file))
{
    $response['log'] = "Sorry, file already exists.";
    $uploadOk = 0;
}
// Check file size
if( $_FILES["fileToUpload"]["size"] > (2*500000) )
{
    $response['log'] = "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    $response['success'] = 0;
    $response['log'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ( !$uploadOk ){
    $response['success'] = 0;
// if everything is ok, try to upload file
}
else
{
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        $response['success'] = 1;
        $response['log'] = "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
        $response['filepath'] = $target_file;
    } else {;
        $response['success'] = 0;
        $response['log'] = "Sorry, there was an error uploading your file.";
    }
}

echo json_encode( $response );
