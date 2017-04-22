<?php

/* Create -> New Art Record
INPUT:      art_id, distance
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
$output = authUser($output);
$username = $output['username'];


// Local variables
$art_id = 0;
$limit = 25;
$distance = 50;


// Check art_id
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['art_id'])) {
        $art_id = $_POST['art_id'];
        $output['status'] = "Parameters successfully POSTED";
    } else {
        $output['success'] = 0;
        $output['status'] = "Art_ID POST variable missing.";
        die(json_encode($output));
    }

    if (isset($_POST['distance'])) {
        $distance = $_POST['distance'];
    } else {
        $distance = 10;
    }

    if (isset($_POST['limit'])) {
        $limit = $_POST['limit'];
    } else {
        $limit = 25;
    }
} elseif ($_SERVER["REQUEST_METHOD"] === "GET") {
    if (isset($_GET['art_id'])) {
        $art_id = $_GET['art_id'];
        $output['status'] = "Parameters successfully GET";
    } else {
        $output['success'] = 0;
        $output['status'] = "Art_ID GET variable missing.";
        die(json_encode($output));
    }

    if (isset($_GET['distance'])) {
        $distance = $_GET['distance'];
    } else {
        $distance = 10;
    }

    if (isset($_GET['limit'])) {
        $limit = $_GET['limit'];
    } else {
        $limit = 25;
    }
} else {
    $output['success'] = 0;
    $output['status'] = "POST/GET Request Method Expected";
    die(json_encode($output));
}


// Establish Database connection
$db = new DB_CONNECT();

// Get longitude/latitude of Art_id
$query = "SELECT * FROM art WHERE art_id='$art_id'";
$result = $db->query($query);

if (!empty($result)) {
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        // Load JSON array with Query results
        // Check user authentication
        if ($output["username"] === $row['user_id']) {
            $output['status'] = "longitude/latitude retrieved from input art_id";
            $loc_lat = $row["loc_lat"];
            $loc_lng = $row["loc_lng"];
        } else {
            $output["success"] = 0;
            $output['status'] = "Unauthorized Access";
            die(json_encode($output));
        }
    } else {
        $output['success'] = 0;
        $output['status'] = "Incorrect art id";
        die(json_encode($output));
    }
} else {
    $output['success'] = 0;
    $output['status'] = "Query Error: Empty";
    die(json_encode($output));
}

// Prepare MySQL query to select art within distance of input coordinates
$query =
    "   SELECT
            `art_id`,
            (
                6378100 *
                acos(
                    cos(radians('$loc_lat')) *
                    cos(radians(`loc_lat`)) *
                    cos(
                        radians(`loc_lng`) - radians('$loc_lng')
                   ) +
                    sin(radians('$loc_lat')) *
                    sin(radians(`loc_lat`))
               )
           ) `distance`
        FROM
            `art`
        HAVING
            `distance` < '$distance'
        ORDER BY
            `distance`
        LIMIT ".
            $limit;

$result = $db->query($query);

if (!empty($result)) {
    if (mysqli_num_rows($result) > 0) {
        $output['locations'] = array();
        $db_local = new DB_CONNECT();
        while ($row = mysqli_fetch_array($result)) {
            $locations = array();
            $locations['art_id'] = $row['art_id'];
            $subQ = "SELECT * FROM art WHERE ".$locations["art_id"];
            $subQ = $db_local->query($subQ);
            $art_record = mysqli_fetch_array($subQ);
            $locations['loc_lng'] = $art_record['loc_lng'];
            $locations['loc_lat'] = $art_record['loc_lat'];
            array_push($output['locations'], $locations);
        }
        // Success has been achieved
        $output['success'] = 1;
        $output['status'] = "All nearby locations retrieved.";
    } else {
        $output['success'] = 0;
        $output['status'] = "No locations found.";
    }
} else {
    $output['success'] = 0;
    $output['status'] = "Failed retrieving list of locations.";
}
echo json_encode($output);
