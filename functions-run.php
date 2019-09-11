<?php
    $language = new Language();
    $language->redirect($_SERVER['REQUEST_URI']);
    $chooseLang = $language->setSessionLanguage($_SERVER['REQUEST_URI']);

    require_once 'languages/'. $chooseLang . ".php";

    $path = new Path();
    $getPathToNavigation = $path->getPath();

    $setSession = new SetSession();
    $setSession->setSessionParams($displayLang, $getPathToNavigation);
?>