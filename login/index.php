<?php 
    include "login-functions.php";
    include "../header.php";
?>

<div class="gray-background">
    <div class="card text-center py-5 gray-background vh-100">
        <!-- <h3>Dołącz, by dodać nowe pytania i odpowiedzi do bazy wiedzy dla Junior Front-end Developerów</h3> -->
        <div class="card-body py-5">

            <form action="" method="post" id="signin-form">
                <input type="text" placeholder=<?php echo $displayLang["email"] ?> name="login-username" class="container form-control form-control-lg shadow-none" id="login-username" autofocus>
                <input type="password" placeholder=<?php echo $displayLang["password"] ?> name="login-pass" class="container form-control form-control-lg my-3 shadow-none" id="login-pass" >

                <p class='mt-3 text-danger'><?php echo $message ?> </p>
                <button type="submit" name="login-button" class="container btn btn-outline-warning my-2 py-2" id="login-button"><?php echo $displayLang["log_in"] ?></button>
            </form>
        </div>
    </div>
</div>




<?php include "../footer.php"; ?>