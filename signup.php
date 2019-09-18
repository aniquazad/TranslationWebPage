<?php
/*
 * This file gets sanitizes the sign-in info and salts the password to prevent it from being stolen. It also checks that 
 * once everything is correct, it prompts the user to login with the new info.
 */
    session_start();
    
    require_once 'dbInfo.php';
    $conn = new mysqli($hn, $un, $pw, $db);
    setcookie($un,'Aniqua', time() + 60*60*24*7,'/');
    
    if($conn->connect_error) 
    {
        die("Connection failed: ".$conn->connect_error);
    }
    
    if(isset($_POST['email']) && isset($_POST['userName']) 
            && isset($_POST['pword']))
    {
        $e = sanitizeMySQL($conn, $_POST['email']);
        $uname = sanitizeMySQL($conn, $_POST['userName']);
        $pword = sanitizeMySQL($conn, $_POST['pword']);
        $salt1 = "qm&h*";
        $salt2 = "pg!@";
        $token = hash('ripemd128', "$salt1$pword$salt2");
        $query = "INSERT INTO proj.credentials(email, username, password) VALUES('$e','$uname','$token')";
        if($conn->query($query) === FALSE) 
        {
            echo "Error: " . $query . "<br>" . $conn->error;
        }
        echo <<<_END
        <html><head></head>
        <body>
            <form method="post" action="" enctype="multipart/form-data">
            <p><a href="index.php">Click here to login</a></p>
            <br/>
        </body>
        </html>
_END;
}     

    function sanitizeString($var) 
    {
	$var = stripslashes($var);
	$var = strip_tags($var);
	$var = htmlentities($var);
	return $var;              
    }
    function sanitizeMySQL($connection, $var)
    {
	$var = $connection->real_escape_string($var);
	$var = sanitizeString($var);
	return $var;
    }
    
    $conn->close();
