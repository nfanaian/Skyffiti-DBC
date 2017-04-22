<?php

/* require_once('../db_config.php');

if (isset($_FILES['file'])) {
    // Declare json array
    $output = array();
    $output['success'] = 0;
    $output['message'] = "Script started.";

    // Get file info
    $file = $_FILES['file'];

    // File Properties
    $file_name  = $file['name'];
    $file_tmp   = $file['tmp_name'];
    $file_size  = $file['size'];s
    $file_error = $file['error'];

    $uploadOk = 1;
    if (file_exists($target_file)) {
        $output['success'] = 1;
        $output['message']  = 'File Already Exists';
        $uploadOk = 0;
    }

    // Working With File Extension
    $filename_exploded = explode('.', $file_name);
    $file_folder = strtolower(current($filename_exploded)) . "/";
    $file_name = strtolower(current($filename_exploded) . next($filename_exploded));
    $file_ext = strtolower(end($filename_exploded));

    // Accepted File Formats
    $allowed = array('png', 'jpg', 'gif', 'jpeg', 'gif', 'bmp');

    $target_filepath = dirname(__FILE__).'/uploads/';//IMG_ROOT . $file_folder . $file_name . $file_ext;

    if ($uploadOk && in_array($file_ext, $allowed)) {
        if ($file_error === 0) {
            try {
                if (move_uploaded_file($file_tmp, $target_filepath)) {
                    $output['success'] = 1;
                    $output['message']  = 'Image Uploaded';
                } else {
                    $output['success'] = 0;
                    $output['message']  = "Some error in uploading file";
                }
            } catch (Exception $e) {
                $$output['success'] = 0;
                $output['message']  = $e->getMessage();
            }
        } else {
            $output['success'] = 0;
            $output['message']  = "File Error: [" . $file['error'] . "]";
        }
    } else {
        $output['success'] = 0;
        $output['message']  = "Invalid File Format";
    }
}

echo json_encode($output);
*/

// Path to move uploaded files
$target_path = dirname(__FILE__).'/uploads/';

if (isset($_FILES['file']['name'])) {
    $target_path = $target_path . basename($_FILES['file']['name']);

    try {
        // Throws exception incase file is not being moved
        if (!move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
            // make error flag true
            echo json_encode(array('status'=>'fail', 'message'=> $_FILES['file']['error']));
        }

        // File successfully uploaded
        echo json_encode(array('status'=>'success', 'message'=>'File Uploaded'));
    } catch (Exception $e) {
        // Exception occurred. Make error flag true
        echo json_encode(array('status'=>'fail', 'message'=>$e->getMessage()));
    }
} else {
    // File parameter is missing
    echo json_encode(array('status'=>'fail', 'message'=>'Not received any file'));
}

?>
