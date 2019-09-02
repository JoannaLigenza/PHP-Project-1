<?php 
    include "../functions.php";
    $message = "";
    $userData = new UserData();

    if (isset($_POST['login-button'])) {
        $email = htmlspecialchars($_POST['login-username'], ENT_QUOTES);
        $pass = htmlspecialchars($_POST['login-pass'], ENT_QUOTES);

        if (empty($email) || empty($pass)) {
            $message = "Please fill all fields!";
        } else {
            $userdata = $userData->getUserVeryficationData($email);
            $checkPass = password_verify($pass, $userdata['pass']);
            if ($checkPass && $userdata['email'] === $email) {
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $userdata['username'];
                $_SESSION['email'] = $userdata['email'];
                $url = $_SERVER['REQUEST_URI'];
                header('Location: /'.$_SESSION['lang'].'/');
            } else {
                $message = "Please enter valid email and password";
            }
        }
    }

?>