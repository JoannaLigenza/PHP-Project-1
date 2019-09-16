<?php 
    include "../functions.php";
    include "../functions-run.php";
    //include "login-functions.php";

    $message = "";
    $userData = new UserData();
    $loadSite = new LoadSites();
    $mainDir = $_SESSION['main-dir'];

    if (isset($_POST['login-button'])) {
        $email = htmlspecialchars($_POST['login-email'], ENT_QUOTES);
        $pass = htmlspecialchars($_POST['login-pass'], ENT_QUOTES);

        if (empty($email) || empty($pass)) {
            $message = $displayLang["fill_all_fields"];
        } else {
            $userdata = $userData->getUserVeryficationData($email);
            $checkPass = password_verify($pass, $userdata['pass']);
            if ($checkPass && $userdata['email'] === $email) {
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $userdata['username'];
                $_SESSION['email'] = $userdata['email'];
                header('Location: '.$mainDir.'/'.$_SESSION['lang'].'/');
            } else {
                $message = $displayLang["enter_valid_email_and_password"];
            }
        }
    }

    $_SESSION['title'] = $displayLang["login_site_title"];
    $_SESSION['description'] = $displayLang["login_site_desc"];
    $_SESSION['index'] = "index";
    include "../header.php";
?>

<div class="gray-background">
    <div class="card text-center py-5 gray-background vh-100">
        <!-- <h3>Dołącz, by dodać nowe pytania i odpowiedzi do bazy wiedzy dla Junior Front-end Developerów</h3> -->
        <div class="card-body py-5">

            <form action="" method="post" id="login-form">
                <input type="email" placeholder=<?php echo $displayLang["email"] ?> name="login-email" class="container form-control form-control-lg shadow-none" id="login-email" autofocus>
                <input type="password" placeholder=<?php echo $displayLang["password"] ?> name="login-pass" class="container form-control form-control-lg my-3 shadow-none" id="login-pass" >

                <p class='mt-3 text-danger'><?php echo $message ?> </p>
                <button type="submit" name="login-button" class="container btn btn-outline-warning my-2 py-2" id="login-button"><?php echo $displayLang["log_in"] ?></button>
            </form>
            <div class="py-5">
                <a href=<?php echo "../".$loadSite->loadSite('forgot-password'); ?> class="focus-a"><?php echo $displayLang["forgot_password"]; ?></a>
            </div>
        </div>
    </div>
</div>




<?php include "../footer.php"; ?>