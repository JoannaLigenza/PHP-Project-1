<?php 
    include "../functions.php";
    include "../functions-run.php";
    $_SESSION['title'] = $displayLang["site_title"];
    $_SESSION['description'] = $displayLang["site_desc"];
    $_SESSION['index'] = "noindex";
    include "../header.php";

    function clickResetPassButton() {
        $messageInfo = "";
        if (isset($_POST['reset-password-button'])) {
            $email = htmlspecialchars($_POST['forgot-pass-email-input'], ENT_QUOTES);
            if (empty($email)) {
                $messageInfo = "Please fill all fields to send message";
            } else {
                include "../contact/send-message.php";
                $remindPassword = new RemindPassword();
                $token = randomString();
                $tokenData = $remindPassword->checkIfUserHasToken($email);
                // if user has active token in database then change it, if not - add it to database
                if ($tokenData !== NULL) {
                    if ($remindPassword->changeToken($email, $token)) {
                        $messageInfo = resetPassMessage($email, $token);
                    }
                } else {
                    if ($remindPassword->addToken($email, $token)) {
                        $messageInfo = resetPassMessage($email, $token);
                    }
                }
            }
        }
        return $messageInfo;
    }
    $messageInfo = clickResetPassButton();

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
?>

<div class="gray-background">
    <div class="card text-center py-5 gray-background vh-100">
        <div class="card-body py-5">
            <p class="pb-4">Enter your email below and check your email account to reset your password.</p>
            <form action="" method="post">
                <input type="email" placeholder=<?php echo $displayLang["email"] ?> name="forgot-pass-email-input" id="forgot-pass-email-input" class="container form-control form-control-lg shadow-none" autofocus>
                <button type="submit" name="reset-password-button" class="container btn btn-outline-warning my-2 py-2" id="reset-password-button">Send reset link</button>
            </form>
            <p class="mt-5"> <?php echo $messageInfo ?> </p>
            <div class="py-5">
                <?php echo $displayLang["back_to"] ?> <a href="../login"><?php echo $displayLang["login"] ?></a>
            </div>
        </div>
    </div>
</div>




<?php include "../footer.php"; ?>