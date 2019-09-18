/*
 * This file validates the email, username, and password given by the user when they sign up. This file is used by userSignUp.php
 */
const MIN_LENGTH = 6;
function validateUsername(field)
{
    if(field == "")
    {
        return "No username was entered.\n"
    }
    else if(field.length < MIN_LENGTH)
    {
        return "Username must be at least "MIN_LENGTH" characters.\n"
    }
    else if(/[^a-zA-Z0-9_-]/.test(field))
    {
        return "Only a-z, A-z, 0-9, - and _ are allowed in usernames.\n"
    }
    return ""
}

function validatePassword(field)
{
    if(field == "")
    {
        return "No password was entered.\n"
    }
    else if(field.length < MIN_LENGTH)
    {
        return "Password must be at least "MIN_LENGTH" characters.\n"
    }
    else if((!/[a-z]/.test(field) || ! /[A-Z]/.test(field) ||!/[0-9]/.test(field))
    {
        return "Passwords require one each of a-z, A-Z and 0-9.\n"
    }
    return ""
}

function validateEmail(field)
{
    if(field == "")
    {
        return "No email was entered.\n"
    }
    else if(/^ [a-z\d\.-]+ @ [a-z\d]+ \. [a-z]{2,8} \.[a-z]{2,8}? $/)
    {
        return "Email is not in the correct format: name@domain.extension.\n"
    }
    return ""
}

