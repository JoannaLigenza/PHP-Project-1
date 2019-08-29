<?php
    include "../functions.php";
    $message = "";
    // if (isset($_GET['signup'])) {
    //     $signup = $_GET['signup'];
    //     if ($signup === 'empty') {
    //         $message = "Please fill all fields!";
    //     }
    // }
function signInVeryfication($userData) {
    if (isset($_POST['signin-button'])) {
        $userName = htmlspecialchars($_POST['signin-username'], ENT_QUOTES);
        $email = htmlspecialchars($_POST['signin-email'], ENT_QUOTES);
        $pass = htmlspecialchars($_POST['signin-pass'], ENT_QUOTES);
        if (empty($userName) || empty($email) || empty($pass)) {
            // $url = $_SERVER['REQUEST_URI'];
            // header("Location: $url?signup=empty");
            $message = "Please fill all fields!";
        } else {
            if (!(preg_match('/^[a-zA-Z0-9-]{3,30}$/', $userName))) {
                $message = "Please enter valid username. You can use lowercase and uppercase letters, digits and dash";
                return;
            } 
            else if (preg_match('/^(admin|administrator)$/i', $userName)) {
                $message = "This user name is already taken";
                return;
            }
            else if (!(preg_match('/^[a-zA-Z0-9-._]+@[a-zA-Z0-9-_.]+\.[a-zA-Z]{2,25}$/', $email))) {
                $message = "Please enter valid email. You can use lowercase and uppercase letters, digits, dots and dash";
                return;
            }
            // password must have 6-30 characters and only letters, numbers or characters ?!#
            else if (!(preg_match('/^[a-zA-Z0-9?!#]{6,30}$/', $pass))) {
                $message = "Please enter valid password. You can use lowercase and uppercase letters, digits and characters ?!#. Password must have at least 6 characters";
                return;
            }
            // check if username is already taken
            else {
                $userRowsnum = $userData->checkUserName($userName, "username");
                $emailRowsnum = $userData->checkUserName($email, "email");
                if ($userRowsnum > 0) {
                    $message = "This username is already taken";
                    return;
                } 
                if ($emailRowsnum > 0) {
                    $message = "This email address is already taken";
                    return;
                } 
                // if not taken then create account
                else {
                    $password = password_hash("$pass", PASSWORD_ARGON2I);
                    if ($userData->addUser($userName, $email, $password)){
                        $url = $_SERVER['REQUEST_URI'];
                        header("Location: $url?signup=success");
                    };
                }
            }
        }


        // if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        //     $message = "Please write validate e-mail address";
        // }

        
    }
    // echo "le";
    return "le";
}
signInVeryfication($userData);
?>