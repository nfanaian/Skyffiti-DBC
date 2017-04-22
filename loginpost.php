<?php

    $response = array();

    if (1)//isset($_POST['username']) && isset($_POST['password']))
    {
        $username = $_POST['username'];
        $password = $_POST['password'];


        //include db  & connect
        require_once __DIR__ .  ("db_connect.php");
        $db = new DB_CONNECT();

        $result = mysqli_query($db,"SELECT user_id FROM user where user_id='$username' and password='$password'");
        $row = mysqli_fetch_array($result);
        $data = $row[0];

        if($data)
        {
            $response["username"] = 'navid';
            echo json_encode($data);

        }
        else
        {
            //failed
            echo json_encode($data);
        }
    }
    else
    {
        // missing required items
        $response["success"] = 0;
        $response["message"] - "Required field(s) is missing";

        // echo dat bih
        echo json_encode($response);
    }
?>
