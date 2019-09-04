<?php
    include "functions.php";
    $language = new Language();
    $currentLang = $_POST['currentLang'];
    $chooseLang = $language->setSessionLanguage($currentLang);

    $questionsData = new QuestionsData();
    $answearsData = new AnswearsData();


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

    function setRating($answearsData) {
        $res = "no";
        $user = $_SESSION['username'];
        $answearId = $_POST['answearId'];
        if ($_POST['arrDirection'] === 'up') {
            $result = $answearsData->addVote($user, $answearId, "+");
            $up = $result[0];
            $down = $result[1];
            $difference = $result[2];
            if ($up) {
                $answearsData->changeAnswearVotesNumber($answearId, "+", $difference);
                $res = ["orange", $difference];
            } else {
                $answearsData->changeAnswearVotesNumber($answearId, "-", $difference);
                $res = ["grey", $difference];
            }
        } else if ($_POST['arrDirection'] === 'down') {
            $result = $answearsData->addVote($user, $answearId, "-");
            $up = $result[0];
            $down = $result[1];
            $difference = $result[2];
            if ($down) {
                $answearsData->changeAnswearVotesNumber($answearId, "-", $difference);
                $res = ["orange", $difference];
            } else {
                $answearsData->changeAnswearVotesNumber($answearId, "+", $difference);
                $res = ["grey", $difference];
            }
        }
        
        echo json_encode($res);
    }

    if(isset($_POST['isLoggedInName'])) {
        setFavourites($questionsData);
    }
    
    if(isset($_POST['clickedArr'])) {
        setRating($answearsData);
    }

?>    