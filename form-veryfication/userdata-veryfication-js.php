<?php
    include "../functions.php";
    $userData = new UserData();

    function checkIfIsTaken($userData, $option) {
        $getOption = $_POST[$option];
        $rowsnum = $userData->checkUserName($getOption , $option);
        $res = 0;
        if ($rowsnum > 0) {
            $res = 1;
        } 
        echo json_encode($res);
        //return $res;
    }

    function signIn($userData, $pass, $userName, $email) {
        $password = password_hash("$pass", PASSWORD_ARGON2I);
        $res = 0;
        if ($userData->addUser($userName, $email, $password)){
            $res = 1;
        };
        echo json_encode($res);
    }

    if (!empty($_POST['username'])) {
        checkIfIsTaken($userData, "username");
    }

    if (!empty($_POST['email'])) {
        checkIfIsTaken($userData, "email");
    }

    if (!empty($_POST['signinButton'])) {
        $pass = htmlspecialchars($_POST['signin-pass'], ENT_QUOTES);
        $userName = htmlspecialchars($_POST['signin-username'], ENT_QUOTES);
        $email = htmlspecialchars($_POST['signin-email'], ENT_QUOTES);
        signIn($userData, $pass, $userName, $email);
    }

?>