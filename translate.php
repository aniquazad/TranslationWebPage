<?php
    /*
     * This file obtains the most recent file in the database for the user. If they haven't uploaded a file,
     * it uses the default file. It gets the english word from the user(sanitizes it), and then shows the translated word.
     * If it can't be found in the dictionary, a message is printed for the user to know.
     */
    session_start();
    require_once 'dbInfo.php';
    require_once 'sessionSecurity.php';
    $conn = new mysqli($hn, $un, $pw, $db);
    setcookie($un,'Aniqua', time() + 60*60*24*7,'/');
    
    $content_assoc = array();
    
    if($conn->connect_error) 
    {
        die("Connection failed: ".$conn->connect_error);
    }
    
    require_once 'openDefFile.php';
    
    //if the user is logged in
    if(isset($_SESSION['email']))
    {
        $email = $_SESSION['email'];
        getInfoFromTable($email, $conn);
    }
    
    /*
     * This function gets the information from the table based on the user's email. If no file exists, then the default text file
     * is used. If the file exists, then we call getTableContents() to get the most recent file.
     */
    function getInfoFromTable($email, $conn)
    {
        $fileName = getFileName($conn, $email);
        $query = "SELECT fileContent FROM proj.fileInfo WHERE email = '$email' AND fileName = '$fileName'";
        $res = $conn->query($query);
        if($res->num_rows == 0)
        {
            $query = "SELECT * FROM defaultFile";
            $res = $conn->query($query);
            getTableContents($res, 'content',$conn);
            $res->close();
        }
        else
        {
            getTableContents($res, 'fileContent',$conn);
        }
            
        if(!$res) 
        {
            die("Couldn't get info:" .$conn->error);
        }
    }
    
    /*
     * This function gets the name of the most recent file 
     */
    function getFileName($conn, $email)
    {
        $query = "SELECT fileName FROM fileInfo WHERE email = '$email'";
        $res = $conn->query($query);
        $rows = $res->num_rows;
        for ($j = 0 ; $j < $rows ; ++$j)
        {
            $res->data_seek($j);
            $row = $res->fetch_array(MYSQLI_NUM);

            for ($k = 0 ; $k < 1 ; ++$k) 
            {
                $fileName = $row[$k];
            }
        }
        return $fileName;
        $res->close();
    }

    /*
     * This function gets the content from the most recent file. It gets each line,
     * splits it at the "," and adds the english word and the foreign word to the
     * assoc_array. The english word is the key and foreign word is the value
     */
    function getTableContents($result, $colName, $conn)
    {
        /*This portion takes the info of the file from the db and puts
         *it into an associative array(keys= English wordd, values = Latin words). 
         */
        while($rows = mysqli_fetch_assoc($result))
            {
                $contentsArr = explode("\n", $rows[$colName]);
                foreach($contentsArr as $value)
                {
                    $temp = explode(",", $value);
                    $content_assoc[$temp[0]] = $temp[1];
                }
            }
            echo getWordToBeTranslated($conn, $content_assoc);
            $result->close();
    }
    
    /*
     * This function gets the user input on the word they would like to be translated.
     */
    function getWordToBeTranslated($conn, $content_assoc)
    {
         echo <<<_END
        <html><head></head>
        <body>
            <form method="post" action="" enctype="multipart/form-data">
            <label>Word to Translate</label>
            <input type ="text" name="wordToTrans"><br/>
            <input type ="submit" value="Translate">
        </form>
        </body>
        </html>
_END;
         if(isset($_POST['wordToTrans']))
         {
             $wordToTrans = sanitizeMySQL($conn, $_POST['wordToTrans']);
             $wordToTrans = ucfirst($wordToTrans);
             $translatedWord = findTransWord($wordToTrans, $content_assoc);
             echo "Word to translate:   ". $wordToTrans. "<br>";
             echo "Translated:  ".$translatedWord;
         }
    }
    
    /*
     * This function finds the associated foreign word for the user's English word
     */
    function findTransWord($wordToTrans, $content_assoc)
    {
        foreach($content_assoc as $key => $value)
        {
            if($wordToTrans == $key)
            {
                return $value;
            }
        }
        return "Sorry, the word you entered is not in the dictionary!";
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
    

