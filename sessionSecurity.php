<?php
    /*
     * This file maintains the session security for all the other files
     */
    echo sessionRegeneration();
    if($_SESSION['check'] != hash('ripemd128',$_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']))
    {
        echo different_user();
    }
    function sessionRegeneration()
    {
        if (!isset($_SESSION['initiated'])) 
        {
            session_regenerate_id();
            $_SESSION['initiated'] = 1;
        }
        if (!isset($_SESSION['count']))
        {
            $_SESSION['count'] = 0;
        }
        else
        {
            ++$_SESSION['count'];
        }
    }
    function destroy_session_and_data() 
    {
	$_SESSION = array();
	setcookie('Aniqua', '', time() - 2592000, '/');
	session_destroy();
    }
    function different_user()
    {
        destroy_session_and_data();
        echo "Please login again due to a technical error!";
        require_once 'index.php';
    }

