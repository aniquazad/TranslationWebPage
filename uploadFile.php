<?php
//This file prompts the user to upload a file
    echo <<<_END
        <html><head><title>File Upload</title></head>
        <body>
      <form method="post" action="content.php" enctype="multipart/form-data">
           Dictionary should be English word,Foreign word<br/>
           Select a dictionary TEXT file: <input type ="file" name="filename"><br/> 
           Give your file a name: <input type ="text" name="txtName"><br/>
           <input type ="submit" value="Submit" >

            <p><a href="translate.php"><input type="button" value="Get Translations"></a></p>
       </form>
       <br/>
_END;

