<?php
/*
 * This file checks to see that the file the user inputs is a text file. Then it inserts the file into the database under the 
 * user's email. If everything is correct, it prompts the user to go to the translations.
 */
    session_start();
    require_once 'dbInfo.php';
    require_once 'sessionSecurity.php';
    $conn = new mysqli($hn, $un, $pw, $db);
    setcookie($un,'Aniqua', time() + 60*60*24*7,'/');
    
    if($conn->connect_error) 
    {
        die("Connection failed: ".$conn->connect_error);
    }
    
     if ($_FILES)
    {
        $txtName = sanitizeMySQL($conn, 'txtName');
        if(isset($_SESSION['email']))
        {
            $email = $_SESSION['email'];
        }
        //destroy_session_and_data();
        $name = $_FILES['filename']['name'];
        switch($_FILES['filename']['type'])
        {
            case 'text/plain'   :$ext = 'txt';break;
            case 'text/html'    :$ext = 'html';break;
            case 'text/css'     :$ext = 'css';break;
            case 'text/xml'     :$ext = 'xml';break;
            default             :$ext = '';break;
        }
        if($ext)
        {
            //if file type is correct, inserts user inputs into table
            $n = "text.$ext";
            move_uploaded_file($_FILES['filename']['tmp_name'], $n);
            $contents = file_get_contents($n);
            $sql = "INSERT INTO proj.fileInfo (email, fileName ,fileContent) VALUES ('$email', '$txtName','$contents')";

            if($conn->query($sql) === FALSE) 
            {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }  
            else
            {
                echo <<<_END
                    <html><head></head>
                    <body>
                        <form method="post" action="" enctype="multipart/form-data">
                        <p><a href="translate.php"><input type="button" value="Go to Translations"></a></p>
                        <br/>
                    </body>
                    </html>
_END;
            }
        }
        else
        {
            echo "$name is not an accepted text file";
            include 'uploadFile.php';
        }
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

