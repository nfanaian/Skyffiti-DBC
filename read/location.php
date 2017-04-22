<?php
    /* READ -> User
    INPUT: art_id
    OUTPUT:     success: 'painting exists exists'
                art_id
                loc_lat
                loc_lng
    */

    require_once( "../db_connect.php" );

    $response = array();
    $art_id = null;
    $loc_lat = null;
    $loc_lng = null;

    // POST/GET HANDLING
    if( $_SERVER['REQUEST_METHOD'] === 'POST' )
    {
        if( isset($_POST['art_id']) )
            $art_id = $_POST['art_id'];
        else if( isset($_POST['art']) )
            $art_id = $_POST['art'];
        $response['request_method'] = "POST";
    }
    else if( $_SERVER['REQUEST_METHOD'] === 'GET' )
    {
        if( isset($_GET['art_id']) )
            $art_id = $_GET['art_id'];
        else if( isset($_GET['art']) )
            $art_id = $_GET['art'];
        $response['request_method'] = "GET";
    }
    else
    {
        $response['success'] = 0;
        $response['status'] = "Neither POST or GET HTTP methods were requested.";
        die();
    }

    // Establish Database connection
    $db = new DB_CONNECT();

    $query = "SELECT * FROM user WHERE user_id = '$username' AND password = '$password'";

    $result = mysqli_query($db->conn, $query );

    if( !empty($result) )
    {
        if( mysqli_num_rows($result) > 0 )
        {
            $response["success"] = 1;
            $response["users"] = array();
            while( $row = mysqli_fetch_array( $result ) )
            {
                $user = array();
                $user["user_id"] = $row["user_id"];
                $user["password"] = $row["password"];
                $user["email"] = $row["email"];
                $user["level"] = $row["level"];
                array_push($response["users"], $user);
            }
        }
        else
        {
            $response['success'] = 0;
            $response['status'] = "No user was found.";
        }
    }
    else
    {
        $response['success'] = 0;
        $response['status'] = "Query came back empty.";
    }

echo json_encode($response);
