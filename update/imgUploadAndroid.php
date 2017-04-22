<?php

$output = array();
$output['success'] = 0;
$output['message'] = "Script started.";

if(1)// isset($_REQUEST["upload"]) )
    {
    if (isset($_FILES['file']))
    {
        $file   = $_FILES['file'];
        // print_r($file);  just checking File properties

        // File Properties
        $target_dir = '/usr/local/skyffiti/img/';
        $file_name  = $file['name'];
        $file_tmp   = $file['tmp_name'];
        $file_size  = $file['size'];
        $file_error = $file['error'];

        $uploadOk = 1;
        if (file_exists($target_file))
        {
            $output['success'] = 1;
            $output['message']  = 'File Already Exists';
            $uploadOk = 0;
        }

        // Working With File Extension
        $file_ext   = explode('.', $file_name);
        $file_fname = explode('.', $file_name);

        $file_fname = strtolower(current($file_fname));
        $file_ext   = strtolower(end($file_ext));
        $allowed    = array('png', 'jpg', 'gif', 'jpeg', 'gif');

        //Add microtime to time file name and reconstruct new Target Filepath
        $file_name_new     =  $file_fname . uniqid('',true) . '.' . $file_ext;
        $file_name_new    =  uniqid('',true) . '.' . $file_ext;
        $target_filepath = $target_dir . $file_name_new;

        if( $uploadOk && in_array($file_ext,$allowed) )
        {
            if ($file_error === 0)
            {
                if ($file_size <= 5000000)
                {
                    if (move_uploaded_file($file_tmp, $target_filepath))
                    {
                        $output['success'] = 1;
                        $output['message']  = 'Image Uploaded';
                    }
                    else
                    {
                        $output['success'] = 0;
                        $output['message']  = "Some error in uploading file";
                    }
                }
                else
                {
                    $output['success'] = 0;
                    $output['message']  = "Size must one less then 5MB";
                }
            }
        }
        else
        {
            $output['success'] = 0;
            $output['message']  = "Invalid File";
        }
    }
}

echo json_encode( $output );

?>
