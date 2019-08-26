<?php
    include "../functions.php";
    include "../header.php";

    $message= "";
    $getProfile = $_GET['profile'];
    $favouritesQuestions = $userData->getFavouritesQuestions($getProfile);
    //print_r($favouritesQuestions);
    $userIdentity = $userData->getUserData($getProfile);
    $username = $userIdentity['username'];
    $userSite = $userIdentity['site'];
    $userQuestions = $questionsData->getAddedQuestionsToProfileSite($username);
    //print_r($userQuestions);
    $userAnswears = $answearsData->getAddedQuestionsToProfileSite($username);
    //print_r($userAnswears);

    if (isset($_POST['change-password-button'])) {
        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && $_SESSION['username'] === $username) {
            $oldPass = $_POST['old-pass'];
            $newPass = htmlspecialchars($_POST['new-pass'], ENT_QUOTES);
            if (!(preg_match('/^[a-zA-Z0-9?!#]{6,30}$/', $newPass))) {
                $message = "Please enter valid password. You can use lowercase and uppercase letters, digits and characters ?!#. Password must have at least 6 characters";
            } else {
                $userVerify = $userData->getUserVeryficationData($_SESSION['email']);
                $checkPass = password_verify($oldPass, $userVerify['pass']);
                if ($checkPass) {
                    $newPass = password_hash("$newPass", PASSWORD_ARGON2I);
                    if($userData->changeUserPasword($newPass, $username)) {
                        $message = "Password successfully changed!";
                    } else {
                        $message = "Password not changed, try again!";
                    }  
                } else {
                    $message = "Please write correct password";
                }
            }            
        }
    }

?>

    <!-- MAIN  -->
        <main class="container-fluid p-0">
            <div class="row justify-content-center my-4 mx-0">
                <!-- LEFT COL -->
                <div class="col-md-4 col-l-4 mb-5">
                    <?php echo "<h2 class='mb-5'> $username </h2>" ;
                    echo (!empty($userSite)) ? "<p>Strona użytkownika: <a href=$userSite>$userSite</a></p>" : null ?>
                    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && $_SESSION['username'] === $username) : ?>
                        <p>Change password:</p>
                        <!-- <div class="border border-warning rounded d-md-inline-block text-center p-3"> -->
                        <div class="border border-warning rounded d-flex justify-content-center d-md-inline-flex p-3">
                            <form action="" method="post">
                                <label for="old-pass" class="text-muted"><small>Old password</small></label><br>
                                <input type="password" name="old-pass" id="old-pass" ><br>
                                <label for="new-pass" class="text-muted"><small>New password</small></label><br>
                                <input type="password" name="new-pass" id="new-pass" ><br>
                                <button type="submit" name="change-password-button" class="btn btn-warning mt-4 shadow-none myBtnHover">Change Password</button>
                            </form>
                        </div>
                    <?php 
                        echo "<div class='py-3'> $message </div>";
                    endif; ?>
                </div>
                
                <!-- END LEFT COL -->

                <!-- RIGHT COL -->
                <div class="col-md-7 col-l-6 col-xl-5" >
                    <div class="mb-4">
                        <p class="border-left border-warning px-2 font-weight-bold">Added questions:</p>
                        <?php for ($i=0; $i < count($userQuestions); $i++) : ?>
                            <div class="px-2">
                                <?php 
                                echo ($i+1)." ".$userQuestions[$i]['category']." -> ";
                                echo $userQuestions[$i]['title']."<br>";
                                ?>
                            </div>
                        <?php endfor; ?>
                    </div>

                    <div class="mb-4">
                        <p class="border-left border-warning px-2 font-weight-bold">Added answears:</p>
                        <?php for ($i=0; $i < count($userAnswears); $i++) : ?>
                            <div class="px-2">
                                <?php 
                                $answearText = $userAnswears[$i]['answear_text'];
                                $goToAnswear = dirname($getPathToNavigation).'/'.$loadSite->loadSite("show-question").'?id='.$userAnswears[$i]['to_question'];
                                if(strlen($answearText)>20){
                                    $answearText=substr($answearText,0,50)."<a href='$goToAnswear'> ...Read more</a>";
                                } else
                                echo $answearText;
                                ?>
                            </div>
                        <?php endfor; ?>
                    </div>

                    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && $_SESSION['username'] === $username) :  
                        echo "<p class='border-left border-warning px-2 font-weight-bold'> Your favourites questions:</p>";
                        for ($i=0; $i < count($favouritesQuestions); $i++) : ?>
                        <div class="d-flex flex-row align-items-center">
                            <div class="d-flex flex-column align-items-start py-2 px-3">
                                <span data-toggle="tooltip" title="<?php echo $displayLang["add_to_favourites"] ?>" data-placement="bottom">
                                    <form action="" method="post" class="d-flex flex-row align-items-center">
                                            <input type="image" src="../img/heart-f.svg" alt="heart-icon" name=<?php echo $favouritesQuestions[$i]['id']; ?> >
                                    </form>
                                </span>
                            </div>
                            <div>
                                <?php 
                                echo ($i+1)." ".$favouritesQuestions[$i]['category']." -> ";
                                echo $favouritesQuestions[$i]['title']."<br>";
                                //echo $favouritesQuestions[$i]['id']."<br>";
                                //print_r($favouritesQuestions[$i]."<br>");
                                ?>
                            </div>
                        </div>
                    <?php endfor; ?>
                    <?php endif; ?>
                </div>
                <!-- END RIGHT COL -->

            </div>
        </main>
        <!-- END MAIN -->

        
<?php include "../footer.php"; ?>


