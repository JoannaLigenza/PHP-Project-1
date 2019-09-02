<?php
    include "functions.php";

    $path = new Path();
    $getPathToNavigation = $path->getPath();
    
    $setSession = new SetSession();
    $setSession->setSessionParams($displayLang, $getPathToNavigation);

    include "header.php";
    include "nav.php";
    include "main.php";
    include "footer.php";
?>