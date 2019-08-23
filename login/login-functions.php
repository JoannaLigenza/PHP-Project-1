<?php 
    include "../functions.php";
    $message = "";

    if (isset($_POST['login-button'])) {
        //$username = htmlspecialchars($_POST['login-username'], ENT_QUOTES);
        $email = htmlspecialchars($_POST['login-username'], ENT_QUOTES);
        $pass = htmlspecialchars($_POST['login-pass'], ENT_QUOTES);

        if (empty($email) || empty($pass)) {
            $message = "Please fill all fields!";
        } else {
            $userdata = $userData->getUserVeryficationData($email);
            $checkPass = password_verify($pass, $userdata['pass']);
            //  echo "cookie  ". $_COOKIE['AuthCode']."<br>";
            //  echo "code ".$authCode;
            // echo "authCode  ".$authCode."<br>";
            // print_r($_COOKIE);
            if ($checkPass && $userdata['email'] === $email) {
                //echo "logged in!";
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $userdata['username'];
                $_SESSION['email'] = $userdata['email'];
                $url = $_SERVER['REQUEST_URI'];
                header('Location: /'.$_SESSION['lang'].'/');
            } else {
                $message = "Please enter valid username and password";
            }
        }
    }

?>