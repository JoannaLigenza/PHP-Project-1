<?php

    function setLanguage() {
        $getLang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];   // en-US,en;q=0.9
        $lang = 'en';
        if (strpos($getLang, 'pl') !== false) {
            $lang = 'pl';
        }
        return $lang;
    }

    // check first part of url 
    function startsWith($string, $substring) { 
        // expolode() - split a string and make array from it
        return (explode('/', $string)[1] === $substring);
    } 

    // if url path don't start from /pl or /en then make redirect
    if (!startsWith($_SERVER['REQUEST_URI'], 'pl') && !startsWith($_SERVER['REQUEST_URI'], 'en')) {
        header('Location: '.setLanguage().'/');
        //header('Location: /en/make-redirect.php');
    } 

    
    // sets site language
    session_start();
    // if (!isset($_SESSION['lang'])) 
    if ( startsWith($_SERVER['REQUEST_URI'], 'pl' ) ) {
        echo 'starts from pl';
        $_SESSION['lang'] = 'pl';
    } else {
        $_SESSION['lang'] = 'en';
        echo 'starts from en';
    }
    require_once 'languages/'. $_SESSION['lang'] . ".php";

?>