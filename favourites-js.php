<?php
    include "functions.php";
    $language = new Language();
    $currentLang = $_POST['currentLang'];
    $chooseLang = $language->setSessionLanguage($currentLang);

    $questionsData = new QuestionsData();
    function setFavourites($questionsData) {
        $res = "no";
        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
            $user = $_SESSION['username'];
            $toQuestion = $_POST['isLoggedInName'];
            $questionsData->addToFavourites($user, $toQuestion);
            $res = "yes";
        } 
        echo json_encode($res);
        // return $res;
    }

    if(isset($_POST['isLoggedInName'])) {
        setFavourites($questionsData);
    }
    

?>    