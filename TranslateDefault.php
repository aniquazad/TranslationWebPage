<?php
/*
 * This file is for if the user doesn't login/signup. It uses the default file stored in the database.
 */
    require_once 'dbInfo.php';
    $conn = new mysqli($hn, $un, $pw, $db);
    
    $content_assoc = array();
    
    if($conn->connect_error) 
    {
        die("Connection failed: ".$conn->connect_error);
    }
    
    require_once 'openDefFile.php';
    
    echo getInfoFromTable($conn);
    
    function getInfoFromTable($conn)
    {
        
        echo "Default: English to Italian";
        $query = "SELECT * FROM defaultFile";
        $res = $conn->query($query);
        if(!$res) 
        {
            die("Couldn't get info:" .$conn->error);
        }
        getTableContents($res, 'content',$conn);
    }
    
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
    
