<?php
    include "../functions.php";
    include "../functions-run.php";
    $userData = new UserData();

    function login($userData, $pass, $email) {
        $res = 0;
        $getUserData = $userData->getUserVeryficationData($email);
        $checkPass = password_verify($pass, $getUserData['pass']);
        if ($checkPass && $getUserData['email'] === $email) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $getUserData['username'];
            $_SESSION['email'] = $getUserData['email'];
            $res = 1;
        } 
        echo json_encode($res);
    }

    if (!empty($_POST['loginButton'])) {
        $pass = htmlspecialchars($_POST['login-pass'], ENT_QUOTES);
        $email = htmlspecialchars($_POST['login-email'], ENT_QUOTES);
        login($userData, $pass, $email);
    }
?>