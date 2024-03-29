<?php
    include "../functions.php";
    include "../functions-run.php";
    $_SESSION['title'] = $displayLang["profile_site_title"];
    $_SESSION['description'] = $displayLang["profile_site_desc"];
    $_SESSION['index'] = "index";

    $message= "";
    $getProfile = $_GET['profile'];

    $loadSite = new LoadSites();
    $userData = new UserData();
    $questionsData = new QuestionsData();
    $answersData = new answersData();
    $path = new Path();
    $getPathToNavigation = $path->getPath();
    $favouritesQuestions = $userData->getFavouritesQuestions($getProfile);
    $userIdentity = $userData->getUserData($getProfile);
    $username = $userIdentity['username'];
    $email = $userIdentity['email'];
    $userSite = $userIdentity['site'];
    $userQuestions = $questionsData->getAddedQuestionsToProfileSite($username);
    $useranswers = $answersData->getAddedanswersToProfileSite($username);
    $mainDir = $_SESSION['main-dir'];
    $showAddLink = false;


    if (isset($_POST['show-add-link-div'])) {
        $showAddLink = true;
    }

    if (isset($_POST['add-link-button'])) {
        $site = $_POST['url'];
        if (empty($site)) {
            $showAddLink = true;
        } else {
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && $_SESSION['username'] === $username) {
                $username = $_SESSION['username'];
                if ($userData->addUserSite($username, $site)) {
                    $userIdentity = $userData->getUserData($getProfile);
                    $userSite = $userIdentity['site'];
                    $showAddLink = false;
                }
            }
        }
    }

    if (isset($_POST['change-password-button'])) {
        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && $_SESSION['username'] === $username) {
            $oldPass = $_POST['old-pass'];
            $newPass = htmlspecialchars($_POST['new-pass'], ENT_QUOTES);
            if (!(preg_match('/^[a-zA-Z0-9?!#]{6,30}$/', $newPass))) {
                $message = $displayLang['valid_pass'];
            } else {
                $userVerify = $userData->getUserVeryficationData($_SESSION['email']);
                $checkPass = password_verify($oldPass, $userVerify['pass']);
                if ($checkPass) {
                    $newPass = password_hash("$newPass", PASSWORD_BCRYPT);
                    if($userData->changeUserPasword($newPass, $email)) {
                        $message = $displayLang['pass_changed'];
                    } else {
                        $message = $displayLang['pass_not_changed'];
                    }  
                } else {
                    $message = $displayLang['correct_pass'];
                }
            }            
        }
    }

    for($i=0; $i < count($favouritesQuestions); $i++) {
        if(isset($_POST[$favouritesQuestions[$i]['id'].'_x'])){
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && $_SESSION['username'] === $username) {
                $user = $_SESSION['username'];
                $toQuestion = $favouritesQuestions[$i]['id'];
                $questionsData->addToFavourites($user, $toQuestion);
                $favouritesQuestions = $userData->getFavouritesQuestions($getProfile);
            } else {    
                //echo 'Try again!';
            }
        }
    }

    include "../header.php";
?>

    <!-- MAIN  -->
        <main class="container-fluid p-0">
            <div class="row justify-content-center my-4 mx-0">
                <!-- LEFT COL -->
                <div class="col-md-4 col-l-4 mb-5">
                    <?php echo "<h2 class='mb-5'> $username </h2>" ;
                    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && $_SESSION['username'] === $username) : 
                        echo (!empty($userSite)) ? "<p>".$displayLang['your_website'].": <a href=$userSite target='_blank' rel='nofollow noopener noreferrer'>$userSite</a></p>" : null
                    ?>  
                        <form action=<?php echo basename($_SERVER['REQUEST_URI']) ?> method="post">
                            <button type="submit" name="show-add-link-div" id="show-add-link-div" class="btn p-0 pb-3 box-shadow " data-visible=<?php echo $showAddLink ?> ><u> <?php echo $displayLang['change_link'] ?> </u></button>
                        </form>
                        <?php if ($showAddLink) : ?>
                        <div class="border border-warning rounded d-flex justify-content-center d-md-inline-flex p-3" id="hidden-div">
                            <form action="" method="post">
                                <input type="url" name="url" id="url" placeholder="https://example.com" class="my-0"><br>
                                <button type="submit" name="add-link-button" id="add-link-button" class="btn btn-warning mt-4 shadow-none myBtnHover"> <?php echo $displayLang['change'] ?> </button>
                            </form>
                        </div>
                        <?php endif; ?>

                        <p class="mt-5"><?php echo $displayLang['change_password'].':' ?></p>
                        <div class="border border-warning rounded d-flex justify-content-center d-md-inline-flex p-3" id="change-password-div">
                            <form action="#change-password-div" method="post">
                                <label for="old-pass" class="text-muted"><small><?php echo $displayLang['old_password'] ?></small></label><br>
                                <input type="password" name="old-pass" id="old-pass" ><br>
                                <label for="new-pass" class="text-muted"><small><?php echo $displayLang['new_password'] ?></small></label><br>
                                <input type="password" name="new-pass" id="new-pass" ><br>
                                <button type="submit" name="change-password-button" class="btn btn-warning mt-4 shadow-none myBtnHover"><?php echo $displayLang['change_password'] ?></button>
                            </form>
                        </div>
                    <?php 
                        echo "<div class='py-3'> $message </div>";
                    else :
                    echo (!empty($userSite)) ? "<p>".$displayLang['user_website'].": <a href=$userSite target='_blank' rel='nofollow noopener noreferrer'>$userSite</a></p>" : null;
                    endif; ?>
                </div>
                
                <!-- END LEFT COL -->

                <!-- RIGHT COL -->
                <div class="col-md-7 col-l-6 col-xl-5" >
                    <!-- DISPLAYING ADDED QUESTIONS -->
                    <div class="mb-4">
                        <p class="border-left border-warning px-2 font-weight-bold"><?php echo $displayLang['added_questions'] ?></p>
                        <?php for ($i=0; $i < count($userQuestions); $i++) : ?>
                            <div class="px-2">
                                <?php 
                                echo '<div class="bg-light my-1 p-1 rounded">'. ($i+1)." ".$userQuestions[$i]['category']." -> ";
                                echo $userQuestions[$i]['title']."</div>";
                                ?>
                            </div>
                        <?php endfor; ?>
                    </div>

                    <!-- DISPLAYING ADDED answerS -->
                    <div class="mb-4">
                        <p class="border-left border-warning px-2 font-weight-bold"><?php echo $displayLang['added_answers']; ?></p>
                        <?php for ($i=0; $i < count($useranswers); $i++) : ?>
                            <div class="px-2">
                                <?php 
                                $answerText = $useranswers[$i]['answer_text'];
                                $goToanswer = dirname($getPathToNavigation).'/'.$loadSite->loadSite("show-question").'?id='.$useranswers[$i]['to_question'];
                                if(strlen($answerText)>20){
                                    $answerText=substr($answerText,0,80)."<a href='$goToanswer'> ...Read more</a>";
                                    echo '<div class="bg-light my-1 p-1 rounded">'.$answerText.'</div>';
                                } else
                                    echo '<div class="bg-light my-1 p-1 rounded">'.$answerText.'</div>';
                                ?>
                            </div>
                        <?php endfor; ?>
                    </div>

                    <!-- DISPLAYING USER FAVOURITES -->
                    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && $_SESSION['username'] === $username) :  
                        echo "<p class='border-left border-warning px-2 font-weight-bold'> ".$displayLang['favourites_questions']."</p>";
                        for ($i=0; $i < count($favouritesQuestions); $i++) : ?>
                        <div class="d-flex flex-row align-items-center" id="show-favourites">
                            <div class="d-flex flex-column align-items-start py-2 px-3">
                                <span data-toggle="tooltip" title="<?php echo $displayLang["delete_from_favourites"] ?>" data-placement="bottom">
                                    <form action="#show-favourites" method="post" class="d-flex flex-row align-items-center">
                                            <input type="image" src=<?php echo $mainDir."/img/heart-f.svg" ?> alt="heart-icon" name=<?php echo $favouritesQuestions[$i]['id']; ?> >
                                    </form>
                                </span>
                            </div>
                            <div>
                                <?php 
                                echo ($i+1)." ".$favouritesQuestions[$i]['category']." -> ";
                                echo $favouritesQuestions[$i]['title']."<br>";
                                ?>
                            </div>
                        </div>
                    <?php endfor; ?>
                        <!-- GET FAVOURITES TO PDF -->
                        <div class="py-5">
                            <p><?php echo $displayLang['download_favourites']; ?></p>
                            <a href="download-pdf.php" target="_blank"> <img src=<?php echo $mainDir."/img/pdf.svg" ?> alt="download-pdf-icon"> </a>
                        </div>
                    <?php endif; ?>
                </div>
                <!-- END RIGHT COL -->

            </div>
        </main>
        <!-- END MAIN -->

        
<?php include "../footer.php"; ?>


