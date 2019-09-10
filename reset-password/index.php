<?php 
    include "../functions.php";
    include "../functions-run.php";
    $_SESSION['title'] = $displayLang["site_title"];
    $_SESSION['description'] = $displayLang["site_desc"];
    $_SESSION['index'] = "noindex";
    include "../header.php";

    function automaticallyCheckInvalidTokens() {
        // swap date (in english string) to number (seconds from first date - zero date);
        $time = strtotime("now -1 day");
        $remindPassword = new RemindPassword();
        $remindPassword->automaticallyDeleteToken($time);
    }
    automaticallyCheckInvalidTokens();

    function clickChangePassButton() {
        $messageInfo = "";
        $token = $_GET['token'];
        $remindPassword = new RemindPassword();
        $tokenData = $remindPassword->checkToken($token);
        if ($tokenData === NULL) {
            $messageInfo = "Invalid Reset Link";  
        } 
        if (isset($_POST['change-password-button'])) {
            $newPass = htmlspecialchars($_POST['change-pass-input'], ENT_QUOTES);
            $newPassConfirm = htmlspecialchars($_POST['confirm-change-pass-input'], ENT_QUOTES);
            if (empty($newPass) || empty($newPassConfirm)) {
                $messageInfo = "Please fill all fields to change password";
            } else {
                if ($newPass !== $newPassConfirm) {
                    $messageInfo = "Password is not the same";
                } else {
                    $userData = new UserData();
                    $email = $tokenData['email'];
                    $newPass = password_hash("$newPass", PASSWORD_ARGON2I);
                    if ($userData->changeUserPasword($newPass, $email)) {
                        $messageInfo = "Your password was successfully changed!";
                        $remindPassword->deleteToken($token);
                    }
                }
            }
        }
        return $messageInfo;
    }
    $messageInfo = clickChangePassButton();
?>

<div class="gray-background">
    <div class="card text-center py-5 gray-background vh-100">
        <?php 
        $loadSites = new LoadSites();
        if ($messageInfo === "Invalid Reset Link") : ?>
        <p class="pb-3">This link has expired or is invalid</p>
        <a href=<?php echo "/".$_SESSION['lang']."/".$loadSites->loadSite("forgot-password") ?> class="container btn btn-outline-warning my-2 py-2"> Send another link </a>
        <?php else : ?>
            <div class="card-body py-5">
                <!-- <p class="pb-4">Enter your email below and check your email account to reset your password.</p> -->
                <form action="" method="post">
                    <input type="password" placeholder=<?php echo $displayLang["password"] ?> name="change-pass-input" id="change-pass-input" class="container form-control form-control-lg shadow-none mb-3" autofocus>
                    <input type="password" placeholder=<?php echo "confirm pasword" ?> name="confirm-change-pass-input" id="confirm-change-pass-input" class="container form-control form-control-lg shadow-none mb-3">
                    <button type="submit" name="change-password-button" class="container btn btn-outline-warning my-2 py-2" id="change-password-button">Change password</button>
                </form>
                <p class="mt-5"> <?php echo $messageInfo ?> </p>
                <div class="py-5">
                    <?php echo $displayLang["back_to"] ?> <a href="../login"><?php echo $displayLang["login"] ?></a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>




<?php include "../footer.php"; ?>