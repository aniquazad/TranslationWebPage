<?php
/*
 * This file gets sanitizes the login info and salts the password to prevent it from being stolen. It also checks that 
 * Once everything is correct, it greets the user and prompts them to go to the file upload page.
 */
    session_start();
    require_once 'dbInfo.php';
    /*
     * info from login.php
        $hn = 'localhost';
        $un = 'azad';
        $pw = 'apple';
        $db = 'proj';
     */
    $conn = new mysqli($hn, $un, $pw, $db);
    setcookie($un,'Aniqua', time() + 60*60*24*7,'/');
    
    if($conn->connect_error) 
    {
        die("Connection failed: ".$conn->connect_error);
    }
    //Checks to see that the username and password combo exists and sanitizes the inputs
     if(isset($_POST['userName']) && isset($_POST['pword']))
    {
        $uname = sanitizeMySQL($conn,$_POST['userName']);
        $pword = sanitizeMySQL($conn,$_POST['pword']);
        $salt1 = "qm&h*";
        $salt2 = "pg!@";
        $token = hash('ripemd128', "$salt1$pword$salt2"); //salts the password
        
        $query="SELECT * FROM proj.credentials WHERE "
              . "username='$uname' AND password='$token'";
        $result=$conn->query($query);
        if(!$result)
        {
            die($connection->error);
        }
        /*if the connection works, then the query runs and checks to see if there exists
         * a username and password combo given by the user. If the user exists, he/she is greeted
         * and prompted to continue.
         */
        elseif($result->num_rows)
        {
            $row = $result->fetch_array(MYSQLI_NUM);
            $result->close();
            $salt1 = "qm&h*";
            $salt2 = "pg!@";
            $token = hash('ripemd128', "$salt1$pword$salt2");
            if($token == $row[3])
            {
                $query2 = "SELECT email FROM proj.credentials WHERE "
              . "username='$uname' AND password='$token'";
                $result2 = $conn->query($query2);
                foreach($result2 as $rows) 
                {
                    $_SESSION['email'] = $rows['email'];
                }
                $_SESSION['username'] = $uname;
                $_SESSION['password'] = $token;
                $_SESSION['check'] = hash('ripemd128',$_SERVER['REMOTE_ADDR']
                        .$_SERVER['HTTP_USER_AGENT']);
                echo "Hello, $row[2]!".'<br>';
                die("<p><a href=uploadFile.php>Click here to continue</a></p>");
                $result2()->close();
            }
            else 
            {
                die("No such username/password combination found");
            }
        }
        else//if($result === FALSE ||$result->num_rows <= 0 )
        {
            die("No such username/password combination found");
        }
    }
    
    /*
     * The following method sanitizes the user input from HTML
     */
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

