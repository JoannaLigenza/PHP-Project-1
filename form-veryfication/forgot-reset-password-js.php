<?php
    include "../functions.php";
    include "../functions-run.php";
    $userData = new UserData();
    $remindPassword = new RemindPassword();

    function forgotPass($displayLang, $remindPassword, $userData, $email) {
        $res = 0;
        $messageInfo = "";
        $rowsnum = $userData->checkUserName($email , "email");
        if ($rowsnum === 0) {
            $messageInfo = $displayLang["user_dont_exist"];
            $res = 1;
            echo json_encode([$res, $messageInfo]);
            return;
        }
        include "../contact/send-message.php";
        $token = randomString();
        $tokenData = $remindPassword->checkIfUserHasToken($email);
        // if user has active token in database then change it, if not - add it to database
        if ($tokenData !== NULL) {
            if ($remindPassword->changeToken($email, $token)) {
                $messageInfo = resetPassMessage($email, $token);
                $res = 1;
            }
        } else {
            if ($remindPassword->addToken($email, $token)) {
                $messageInfo = resetPassMessage($email, $token);
                $res = 1;
            }
        }
        echo json_encode([$res, $messageInfo]);
    }

    function randomString() {
        $n = 20;
        $characters = '01234_56789-abcdef_ghijklmnopq_rstuvwxyz-ABCDEFG_HIJKLMNOPQ_RSTUVWXYZ_-';
        $randomString = ''; 
        for ($i = 0; $i < $n; $i++) { 
            $index = rand(0, strlen($characters) - 1); 
            $randomString .= $characters[$index]; 
        } 
        return $randomString; 
    }

    function resetPass($displayLang, $token, $remindPassword, $newPass, $newPassConfirm) {
        $res = 0;
        $messageInfo = "";
        $tokenData = $remindPassword->checkToken($token);
        if ($tokenData === NULL) {
            $messageInfo = "Invalid Reset Link";  
            $res = 1;
            echo json_encode([$res, $messageInfo]);
            return;
        } else {
            if ($newPass !== $newPassConfirm) {
                $messageInfo = $displayLang["pass_is_not_the_same"];
                $res = 1;
            } else {
                $userData = new UserData();
                $email = $tokenData['email'];
                $newPass = password_hash("$newPass", PASSWORD_BCRYPT);
                if ($userData->changeUserPasword($newPass, $email)) {
                    $messageInfo = $displayLang["pass_changed"];
                    $remindPassword->deleteToken($token);
                    $res = 1;
                }
            }
        }
        echo json_encode([$res, $messageInfo]);
    }

    if (!empty($_POST['sendLinkButton'])) {
        $email = htmlspecialchars($_POST['forgot-pass-email'], ENT_QUOTES);
        forgotPass($displayLang, $remindPassword, $userData, $email);
    }

    if (!empty($_POST['resetPassButton'])) {
        $newPass = htmlspecialchars($_POST['reset-pass-email'], ENT_QUOTES);
        $newPassConfirm = htmlspecialchars($_POST['reset-pass-email-confirm'], ENT_QUOTES);
        $token = $_POST['token'];
        resetPass($displayLang, $token, $remindPassword, $newPass, $newPassConfirm);
    }
?>