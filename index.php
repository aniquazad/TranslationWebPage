<?php
    //prompts user to either login or sign up or go directly to translations
    echo <<<_END
        <html><head><title>User Login</title></head>
        <body>
        <form method="post" action="login.php" enctype="multipart/form-data">       
                   <label>Username</label>
                   <input type ="text" name="userName"><br/>
                   <label>Password</label>
                   <input type ="password" name="pword"><br/>
                   <input type ="submit" value="Click to log in" >

                    <p><a href="userSignUp.php"><input type="button" 
                       value="Click here to sign up"></a></p>

                       <p><a href="TranslateDefault.php"><input type="button" 
                       value="Click here to translate"></a></p>
               </form>
               <br/>
        </body>
        </html>
_END;
    