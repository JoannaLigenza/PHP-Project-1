<?php 
    include "login-functions.php";
    include "../header.php";
?>

<div class="login-container" style="background-color: #F7F7F7">
    <div class="card text-center py-5" style="background-color: #F7F7F7">
        <!-- <h3>Dołącz, by dodać nowe pytania i odpowiedzi do bazy wiedzy dla Junior Front-end Developerów</h3> -->
        <div class="card-body py-5" style="background-color: #F7F7F7">

            <form action="" method="post" id="signin-form">
                <input type="text" placeholder="username" name="login-username" class="container form-control form-control-lg" id="login-username" autofocus>
                <input type="password" placeholder="password" name="login-pass" class="container form-control form-control-lg my-3" id="login-pass" >

                <p class='mt-3 text-danger'><?php echo $message ?> </p>
                <button type="submit" name="login-button" class="container btn btn-outline-warning my-2 py-2" id="login-button">Sign In</button>
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