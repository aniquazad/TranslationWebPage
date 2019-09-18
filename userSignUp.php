<?php
//This file is a form for the user if he/she has not signed up before and cannot login.
//The user input is validated to make sre there aren't any wrong info given by the user
echo <<<_END
    <html><head><title>Sign Up</title>
    <script>
        function validate(form)
        {
            fail = validateEmail(form.email.value)
            fail += validateUsername(form.userName.value)
            fail += validatePassword(form.pword.value)
            
            if(fail == "") return true
            else{alert(fail); return false}
        }
    </script>
    <script src = "validate_functions.js"></script>
   </head>
    <body>
    <form method="post" action="signup.php" onsubmit="return validate(this)">
               <label>Email</label>
               <input type ="email" name="email"><br/>        
               <label>Username</label>
               <input type ="text" name="userName"><br/>
               <label>Password</label>
               <input type ="password" name="pword"><br/>
               <input type ="submit" value="Sign up">
           </form>
           <br/>
    </body>
    </html>
_END;
