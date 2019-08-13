<?php 
    include "signin-functions.php";
    include "../header.php";
?>

<div class="login-container" style="background-color: #F7F7F7">
    <div class="card text-center py-5" style="background-color: #F7F7F7">
        <!-- <h3>Dołącz, by dodać nowe pytania i odpowiedzi do bazy wiedzy dla Junior Front-end Developerów</h3> -->
        <div class="card-body py-5" style="background-color: #F7F7F7">

            <form action="" method="post" id="signin-form">
                <input type="text" placeholder="username" name="signin-username" class="container form-control form-control-lg" id="signin-username" autofocus>
                <label for="pass" class="container-fluid text-left text-muted "><small>Username can contain letters, numbers and dash character</small></label>

                <input type="email" placeholder="e-mail:" name="signin-email" class="container form-control form-control-lg" id="signin-email" >
                <label for="email" class="container-fluid text-left text-muted"><small>Needed to confirm registration</small></label>

                <input type="password" placeholder="password:" name="signin-pass" class="container form-control form-control-lg" id="signin-pass" >
                <label for="pass" class="container-fluid text-left text-muted "><small>Password can have 6-30 characters. You can use these characters: [a-zA-Z0-9?!#]</small></label>

                <p class='mt-3 text-danger'><?php echo $message ?> </p>
                <button type="submit" name="signin-button" class="container btn btn-outline-warning my-2 py-2" id="signin-button">Sign In</button>
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