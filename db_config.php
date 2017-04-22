<?php
    // Database definitions
    define('DB_SERVER', "localhost"); // db server (localhost, MySQL DB is on the same computer bruh)
    define('DB_USER', "root"); // db user
    define('DB_PASSWORD', "COP4331!"); // db password (mention your db password here)
    define('DB_DATABASE', "skyffiti"); // database name
    define('IMG_ROOT', '/usr/local/skyffiti/img/'); //Root image directory
    define('ROOT_URL', "http://162.243.60.44/");

    // Error Definitions
    define( "USER_ERR", "Username missing." );

    // Functions
    function authUser( $output )
    {
        if( $_SERVER['REQUEST_METHOD'] === 'POST' )
        {
            // Just add username to output
            // Die if Null
            if( isset($_POST['username']) )
                $output['username'] = $_POST['username'];
            else if( isset($_POST['user']) )
                $output['username'] = $_POST['user'];
            else
                death( $output , USER_ERR);

            //MySQL Authentication within this function
            //if( isset($_POST["password"]) )

        }
        else if( $_SERVER['REQUEST_METHOD'] === 'GET' )
        {
            if( isset($_GET['username']) )
                $output['username'] = $_GET['username'];
            else if( isset($_GET['user']) )
                $output['username'] = $_GET['user'];
            else
                death( $output, USER_ERR );
        }
        else
            death( $output, "Neither POST or GET HTTP methods were requested.");
        return $output;
    }

    function death( $output, $string)
    {
        $output['success'] = 0;
        $output['status'] = $string;
        die( json_encode($output) );
    }

    //process input data
    function trim_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

?>
