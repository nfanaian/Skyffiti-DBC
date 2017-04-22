<!DOCTYPE html>
<html>
<body>

<!-- HTML Upload Form -->
<form method="POST" enctype="multipart/form-data">
    <label for="">Upload</label>
    <input type="file" name="file" id="file">
    <input type="submit" name="upload" value="upload">
</form>

<?php
    if(isset($_REQUEST["upload"]))
    {
    if (isset($_FILES['file'])) {
            $file   = $_FILES['file'];
            // print_r($file);  just checking File properties

            // File Properties
            $file_name  = $file['name'];
            $file_tmp   = $file['tmp_name'];
            $file_size  = $file['size'];
            $file_error = $file['error'];

            // Working With File Extension
            $file_ext   = explode('.', $file_name);
            $file_fname = explode('.', $file_name);

            $file_fname = strtolower(current($file_fname));
            $file_ext   = strtolower(end($file_ext));
            $allowed    = array('png', 'jpg', 'gif', 'jpeg', 'gif');


            if (in_array($file_ext,$allowed)) {
                //print_r($_FILES);


                if ($file_error === 0) {
                    if ($file_size <= 5000000)
                    {
                            $file_name_new     =  $file_fname . uniqid('',true) . '.' . $file_ext;
                            $file_name_new    =  uniqid('',true) . '.' . $file_ext;

                            $file_destination =  '/usr/local/skyffiti/img/' . $file_name_new;

                            // echo $file_destination;
                            if (move_uploaded_file($file_tmp, $file_destination)) {
                                    echo "Image Uploaded";
                            }
                            else
                            {
                                echo "Some error in uploading file".mysql_error();
                            }
                    }
                    else
                    {
                        echo "Size must one less then 5MB";
                    }
                }

            }
            else
            {
                echo "invalid file";
            }
    }
    }
?>
</body>
</html>
