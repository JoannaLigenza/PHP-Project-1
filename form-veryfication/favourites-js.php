<?php
    include "../functions.php";
    $language = new Language();
    $currentLang = $_POST['currentLang'];
    $chooseLang = $language->setSessionLanguage($currentLang);

    $questionsData = new QuestionsData();
    $answersData = new answersData();


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

    function setRating($answersData) {
        $res = "no";
        $user = $_SESSION['username'];
        $answerId = $_POST['answerId'];
        if ($_POST['arrDirection'] === 'up') {
            $result = $answersData->addVote($user, $answerId, "+");
            $up = $result[0];
            $down = $result[1];
            $difference = $result[2];
            if ($up) {
                $answersData->changeanswerVotesNumber($answerId, "+", $difference);
                $res = ["orange", $difference];
            } else {
                $answersData->changeanswerVotesNumber($answerId, "-", $difference);
                $res = ["grey", $difference];
            }
        } else if ($_POST['arrDirection'] === 'down') {
            $result = $answersData->addVote($user, $answerId, "-");
            $up = $result[0];
            $down = $result[1];
            $difference = $result[2];
            if ($down) {
                $answersData->changeanswerVotesNumber($answerId, "-", $difference);
                $res = ["orange", $difference];
            } else {
                $answersData->changeanswerVotesNumber($answerId, "+", $difference);
                $res = ["grey", $difference];
            }
        }
        
        echo json_encode($res);
    }

    if(isset($_POST['isLoggedInName'])) {
        setFavourites($questionsData);
    }
    
    if(isset($_POST['clickedArr'])) {
        setRating($answersData);
    }

?>    