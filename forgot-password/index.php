<?php 
    include "../functions.php";
    include "../functions-run.php";
    include "../header.php";

    if (isset($_POST['resent-password-button'])) {
        echo 'klikniety';
        
    }
?>

<div class="gray-background">
    <div class="card text-center py-5 gray-background vh-100">
        <!-- <h3>Dołącz, by dodać nowe pytania i odpowiedzi do bazy wiedzy dla Junior Front-end Developerów</h3> -->
        <div class="card-body py-5">

            <form action="" method="post">
                <input type="email" placeholder=<?php echo $displayLang["email"] ?> name="login-username" class="container form-control form-control-lg shadow-none" id="login-username" autofocus>
                <button type="submit" name="resent-password-button" class="container btn btn-outline-warning my-2 py-2" id="resent-password-button">Send reset link</button>
            </form>
            <div class="py-5">
                Back to <a href="../login">Login</a>
            </div>
        </div>
    </div>
</div>




<?php include "../footer.php"; ?>