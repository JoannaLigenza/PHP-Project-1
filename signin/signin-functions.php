<?php
    include "../functions.php";
    include "../functions-run.php";
    
    $userData = new UserData();
    $message = "";
    
    if (isset($_POST['signin-button'])) {
        $userName = htmlspecialchars($_POST['signin-username'], ENT_QUOTES);
        $email = htmlspecialchars($_POST['signin-email'], ENT_QUOTES);
        $pass = htmlspecialchars($_POST['signin-pass'], ENT_QUOTES);
        if (empty($userName) || empty($email) || empty($pass)) {
            $message = $displayLang["fill_all_fields"];
        } else {
            $validateData = new ValidateData();
            if (!$validateData->validateName($userName)) {
                $message = $displayLang["enter_valid_username"];
                return;
            } 
            else if (preg_match('/^(admin|administrator)$/i', $userName)) {
                $message = $displayLang["username_taken"];
                return;
            }
            else if (!$validateData->validateEmail($email)) {
                $message = $displayLang["enter_valid_email"];
                return;
            }
            // password must have 6-30 characters and only letters, numbers or characters ?!#
            else if (!$validateData->validatePassword($pass)) {
                $message = $displayLang["enter_valid_password"];
                return;
            }
            // check if checkbox input is checked
            else if ($_POST['checkbox-privacy-policy'] !== "on") {
                $message = $displayLang["confirm_privacy_policy"];
                return;
            }
            // check if username is already taken
            else {
                $userRowsnum = $userData->checkUserName($userName, "username");
                $emailRowsnum = $userData->checkUserName($email, "email");
                if ($userRowsnum > 0) {
                    $message = $displayLang["username_taken"];
                    return;
                } 
                if ($emailRowsnum > 0) {
                    $message = $displayLang["email_taken"];
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
    }

?>