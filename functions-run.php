<?php
    $language = new Language();
    $language->redirect($_SERVER['REQUEST_URI']);
    $chooseLang = $language->setSessionLanguage($_SERVER['REQUEST_URI']);

    require_once 'languages/'. $chooseLang . ".php";

    $path = new Path();
    $getPathToNavigation = $path->getPath();

    $setSession = new SetSession();
    $setSession->setSessionParams($displayLang, $getPathToNavigation);

    // set name of directory with your project, e.g. "/recriument-questions" or "" <- in case if your project is in main direcroty
    $_SESSION['main-dir'] = "";
?>