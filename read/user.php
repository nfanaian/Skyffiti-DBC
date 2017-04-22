<?php
/* READ -> User
INPUT: username & password
OUTPUT:     success: 'user exists'
            user_id
            email
            level
*/

require_once("../db_connect.php");
require_once('../db_config.php');

// Declare JSON Output Array
$output = array();
$output['success'] = 0;
$output['status'] = 'Script started.';

// Authenticate user
$output = authUser($output);

// Establish Database connection
$db = new DB_CONNECT();

// Prepare user/password check MySQL query
$username = $output['username'];

//Check password
if ($_SERVER['REQUEST_METHOD'] === "GET") {
    if (isset($_GET["password"])) {
        $password = $_GET["password"];
    }
} elseif ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (isset($_POST["password"])) {
        $password = $_POST["password"];
    }
} else {
    $password = "";
}

$query = "SELECT * FROM user WHERE user_id='$username'";

$result = $db->query($query);

if (!empty($result)) {
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_array($result);

        // Check password
        if ($row["password"] === md5($password)) {
            $output["success"] = 1;
            $output['status'] = "User Authenticated";

            // Load JSON array with Query results
            $output["user_id"] = $row["user_id"];
            $output["email"] = $row["email"];
            $output["level"] = $row["level"];
            unset($output['password']);
        } else {
            $output["success"] = 0;
            $output['status'] = "Incorrect Password";
        }
    } else {
        $output['success'] = 0;
        $output['status'] = "Incorrect Username";
    }
} else {
    $output['success'] = 0;
    $output['status'] = "Query Error: Empty";
}

echo json_encode($output);
