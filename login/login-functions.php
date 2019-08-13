<?php 
    include "../functions.php";
    $message = "";

    if (isset($_POST['login-button'])) {
        $username = htmlspecialchars($_POST['login-username'], ENT_QUOTES);
        $pass = htmlspecialchars($_POST['login-pass'], ENT_QUOTES);

        if (empty($username) || empty($pass)) {
            $message = "Please fill all fields!";
        } else {
            $userdata = $userData->getUserData($username);
            $checkPass = password_verify($pass, $userdata['pass']);
            if ($checkPass && $userdata['username'] === $username) {
                echo "logged in!";
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