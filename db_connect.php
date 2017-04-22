<?php
/**
 * A class file to connect to database
 */
class DB_CONNECT
{
    public $conn = null;

    // constructor
    function __construct()
    {
        // connecting to database
        $this->connect();
    }

    // destructor
    function __destruct()
    {
        // closing db connection
        $this->conn->close();
    }

    function query( $str )
    {
        return mysqli_query( $this->conn, $str );
    }

    function last_id()
    {
        return mysqli_insert_id( $this->conn );
    }

     // Function to connect with database
    private function connect()
    {
        require_once( 'db_config.php' );
        // Connecting to mysql database
        $this->conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);

        if( !$this->conn)
        {
            die( "Connection failure: " . mysqli_connect_error() );
        }
    }

    // Function to close db connection
    public function close()
    {
        // closing db connection & destroy db object
        __destruct();
    }
}
