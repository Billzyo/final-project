<?php

/**
 * database class
 */
class Database
{
    // Function to establish a connection to the database
    private function db_connect()
    {
        $DBHOST = "localhost";
        $DBNAME = "ink_pos";  
        $DBUSER = "root";     
        $DBPASS = "";         
        $DBDRIVER = "mysql";  

        try {
            // Create a new PDO instance and set the error mode to exceptions
            $con = new PDO("$DBDRIVER:host=$DBHOST;dbname=$DBNAME", $DBUSER, $DBPASS);
            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Handle connection error
            echo "Connection failed: " . $e->getMessage();
            return null;  // Return null if the connection fails
        }

        return $con;
    }

    // Function to run a query on the database
    public function query($query, $data = array())
    {
        // Get the database connection
        $con = $this->db_connect();
        if (!$con) {
            return false;  // Return false if the connection fails
        }

        // Prepare the query for execution
        $smt = $con->prepare($query);

        // Execute the query with the provided data
        $check = $smt->execute($data);

        // Check if the query executed successfully
        if ($check) {
            // Fetch the result as an associative array
            $result = $smt->fetchAll(PDO::FETCH_ASSOC);
            if (is_array($result) && count($result) > 0) {
                return $result;  // Return the result if available
            }
        }

        return false;  // Return false if no results are found
    }
}
