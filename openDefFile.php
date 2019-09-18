<?php
/*
 * This file opens the database which holds the information for the deafault file. 
 */
    require_once 'dbInfo.php';
    $conn = new mysqli($hn, $un, $pw, $db);
    
    if($conn->connect_error) 
    {
        die("Connection failed: ".$conn->connect_error);
    }
    
    $sql = "SELECT content FROM proj.defaultFile";
    
    if($conn->query($sql) === FALSE) 
    {
        echo "Error: " . $sql . "<br>" . $conn->error;
    } 
    //$conn->close();

