<?php
/* Create -> New Art Record
INPUT: username, Longitude & Latitude
OUTPUT:     success: 'art added'
            art_filename
*/

require_once("../db_connect.php");
require_once('../db_config.php');

// Declare JSON Output Array
$output = array();
$output['success'] = 0;
$output['status'] = 'Script started.';

// Authenticate user
$output = authUser( $output );
$username = $output['username'];

// Check location post/get data
if( $_SERVER["REQUEST_METHOD"] === "POST" )
{
    if( isset($_POST['loc_lat']) && isset($_POST['loc_lng']) )
    {
        $loc_lat = $POST['loc_lat'];
        $loc_lng = $POST['loc_lng'];
        $output['status'] = "Parameters successfully POSTED";
    }
    else
        death( $output, "Longitude and Latitude Parameters missing. (loc_lng, loc_lat)" );
}
else if( $_SERVER["REQUEST_METHOD"] === "GET" )
{
    if( isset($_GET['loc_lat']) && isset($_GET['loc_lng']) )
    {
        $loc_lat = $_GET['loc_lat'];
        $loc_lng = $_GET['loc_lng'];
        $output['status'] = "Parameters successfully GET";
    }
    else
        death( $output, "Inccorect parameters");

}
else {
    death( $output, "POST|GET Request Method Expected");
}

// Establish Database connection
$db = new DB_CONNECT();

// Prepare user/password check MySQL query
$query = "INSERT INTO art(user_id, loc_lat, loc_lng) VALUES('$username', '$loc_lat', '$loc_lng')";
$result = $db->query( $query );

$last_id = -1;
if ($result) {
    $output['status'] = "New art entry successfully created";
    $last_id = $db->last_id();
} else {
    death($output, "Failed Inserting New Record");
}

//Create Directory
$image_dir = $username.$last_id;
$query = "UPDATE art SET art_filepath='$image_dir' WHERE art_id='$last_id'";
$result = $db->query( $query );
if( $result )
{
    mkdir( IMG_ROOT . $image_dir, 700 );
    $output['success'] = 1;
    $output['status'] = 'Image directory successfully created';
}
else
    death( $output, "Failed to add Image directory." );

$query = "SELECT * FROM art WHERE art_id='$last_id'";
$result = $db->query( $query );

if( !empty($result) )
{
    if( mysqli_num_rows( $result ) > 0 )
    {
        $row = mysqli_fetch_array( $result );
        // Load JSON array with Query results
        // Check password
        if( $output["username"] === $row['user_id'] )
        {
            $output["success"] = 1;
            $output['status'] = "Art Work Details Retrieved";

            $output["art_id"] = $row["art_id"];
            $output["art_filepath"] = $row["art_filepath"];
        }
        else
        {
            $output["success"] = 0;
            $output['status'] = "Unauthorized Access";
        }
    }
    else
    {
        $output['success'] = 0;
        $output['status'] = "Incorrect art id";
    }
}
else
{
    $output['success'] = 0;
    $output['status'] = "Query Error: Empty";
}

echo json_encode($output);
