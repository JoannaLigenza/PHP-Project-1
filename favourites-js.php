<?php
    include "functions.php";

    function setFavourites($questionsData) {
        $res = 0;
        if(isset($_POST['isLoggedInName'])) {
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
                $user = $_SESSION['username'];
                $toQuestion = $_POST['isLoggedInName'];
                $questionsData->addToFavourites($user, $toQuestion);
                $res = 1;
            } 
        }
        echo $res;
        return $res;
    }
    setFavourites($questionsData);

?>    