<?php
    echo "start";
    //include db  & connect
    require_once(__DIR__."db_connect.php");
    $db = new DB_CONNECT();

    $username = $_GET['username'];
    //FUTURE Password Encryption
    //Possibly MD5
    $password = $_GET['password'];

    echo $username . "<br /> ". $password . "<br /";

    $result = mysqli_query($db,"SELECT * FROM user where user_id='$username' and password='$password'") or die( mysql_error() );

    //To be encoded by JSON then echoed
    $response = array();

    echo "We made it this far!";
    if( !empty( $result ) )
    {
        // There can only be one highlander!
        if( mysql_num_rows( $result ) == 1 )
        {
            echo "Lord have mercy we have data!"
            $row = mysql_fetch_array($result);
            $response["success"] = 1;
            $response["message"] - "User has been verified.";
            $response["username"] = $row["user_id"];
            $resposne["email"] = $row["email"];
            $resposne["level"] = $row["level"];
            echo json_encode($resposne);
        }
        else
        {
            $response["success"] = 0;
            $response["message"] - "No user found.";
            echo json_encode($resposne);
        }
    }
    else
    {
        // missing required items
        $response["success"] = 0;
        $response["message"] - "No user found.";

        // echo dat bih
        echo json_encode($response);
    }

    echo "end";
?>
