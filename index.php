<?php
    
    //$availableLanguages = ['en', 'pl'];

    function setLanguage() {
        $getLang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];   // en-US,en;q=0.9
        $lang = 'en';
        if (strpos($getLang, 'pl') !== false) {
            $lang = 'pl';
        }
        return $lang;
        //return 'pl';
        //return 'de';
    }
    //echo setLanguage();


    function startsWith ($string, $substring) 
    { 
        $len = strlen($substring); 
        return (substr($string, 0, $len) === $substring); 
    } 

    //if browser language is in array with available languages && REQUEST_URI do NOT start with browser language, then make redirect
    // if (in_array(setLanguage(), $availableLanguages) && !startsWith($_SERVER['REQUEST_URI'], '/'.setLanguage())) {
    //     header('Location: '.setLanguage().'/');
    // } 
    // else if (!in_array(setLanguage(), $availableLanguages) && !startsWith($_SERVER['REQUEST_URI'], '/en')) {
    //     header('Location: /en/');
    // }
    if (!startsWith($_SERVER['REQUEST_URI'], '/pl') && !startsWith($_SERVER['REQUEST_URI'], '/en') && !startsWith($_SERVER['REQUEST_URI'], '/dd')) {
        header('Location: '.setLanguage().'/');
        //header('Location: /en/make-redirect.php');
    } 

    

    session_start();
    if (!isset($_SESSION['lang']) || empty($_GET['lang']) ) {
        if ( startsWith($_SERVER['REQUEST_URI'], '/pl' ) ) {
            $_SESSION['lang'] = 'pl';
        } else {
            $_SESSION['lang'] = 'en';
        }
    } else {
        if (in_array($_GET['lang'], $availableLanguages)) {
            $_SESSION['lang'] = substr($_SERVER['REQUEST_URI'], 1, 2);
        } else {
            $_SESSION['lang'] = 'en';
        }
    }
    //echo 'bzbz ' . $_SESSION['lang'] . "<br><br>";
    
    

    require_once 'languages/'. $_SESSION['lang'] . ".php";

    // if (setLanguage() === '/pl' && !startsWith($_SERVER['REQUEST_URI'], '/pl')) {
    //     header('Location: /pl/');
    // }
    // if (setLanguage() === '/en' && !startsWith($_SERVER['REQUEST_URI'], '/en')) {
    //     header('Location: /en/');
    // }

    // if (!startsWith($_SERVER['REQUEST_URI'], '/en') ) {
    //     header('Location: /en/');
    // }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $lang["site_title"]  ?></title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="d-flex flex-column flex-sm-row justify-content-between border-bottom border-warning">
        <a class="navbar-brand p-3" href="/">LOGO HERE</a>
        <div class="p-2 ">
            <button type="button" class="btn btn-outline-warning float-right"><?php echo $lang["log_in"]  ?></button>
            <button type="button" class="btn btn-warning float-right mx-2"><?php echo $lang["sign_up"]  ?></button>
        </div>
    </div>

    <!-- <div class="nav bg-light">
        <nav class="navbar navbar-expand-sm navbar-light red">
            <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#myNav" >
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse nav-fill" id="myNav">
                <a class="nav-item nav-link" href="#">Active</a>
                <a class="nav-item nav-link" href="#">Link</a>
                <a class="nav-item nav-link" href="#">Link</a>
                <a class="nav-item nav-link" href="#">Disabled</a>
            </div>
            </div>
        </nav>
    </div> -->

    <nav class="navbar navbar-expand-sm navbar-light bg-light">
        <a class="navbar-brand" href="#"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav text-center m-auto">
            <li class="nav-item active ">
                <a class="nav-link" href="#"><?php echo $lang["all"]  ?> <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Html</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Css</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Javascript</a>
            </li>
            </ul>
        </div>
    </nav>

    
    <!-- 
    <h1><?php echo $lang["title"]  ?></h1>
    <p><?php echo $lang['text']  ?></p> 
    <a href="/en/">Angielski</a> | <a href="/pl/">Polski</a>

    <!-- jQuery first, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</body>
</html>