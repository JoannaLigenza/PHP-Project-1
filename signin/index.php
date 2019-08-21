<?php 
    include "signin-functions.php";
    include "../header.php";
?>

<div class="gray-background">
    <div class="card text-center py-5 gray-background vh-100">
        <!-- <h3>Dołącz, by dodać nowe pytania i odpowiedzi do bazy wiedzy dla Junior Front-end Developerów</h3> -->
        <div class="card-body py-5">

            <form action="" method="post" id="signin-form">
                <input type="text" placeholder=<?php echo $displayLang["username"] ?> name="signin-username" class="container form-control form-control-lg shadow-none" id="signin-username" autofocus>
                <label for="pass" class="container-fluid text-left text-muted "><small><?php echo $displayLang["label_username"] ?></small></label>

                <input type="email" placeholder="e-mail:" name="signin-email" class="container form-control form-control-lg shadow-none" id="signin-email" >
                <label for="email" class="container-fluid text-left text-muted"><small><?php echo $displayLang["label_email"] ?></small></label>

                <input type="password" placeholder=<?php echo $displayLang["password"] ?> name="signin-pass" class="container form-control form-control-lg shadow-none" id="signin-pass" >
                <label for="pass" class="container-fluid text-left text-muted "><small><?php echo $displayLang["label_password"] ?></small></label>

                <p class='mt-3 text-danger'><?php echo $message ?> </p>
                <button type="submit" name="signin-button" class="container btn btn-outline-warning my-2 py-2" id="signin-button"><?php echo $displayLang["sign_up"] ?></button>
            </form>
        </div>
    </div>
</div>




<?php include "../footer.php"; ?>

<!-- <script>
    $(document).ready(function () {
        $("#signin-form").submit(function(e) {
            e.preventDefault();
            name = 
            console.log("submit");
        })
    })
</script> -->