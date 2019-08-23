<?php
    include "../functions.php";
    include "../header.php";

    $getProfile = $_GET['profile'];
    $favouritesQuestions = $userData->getFavouritesQuestions($getProfile);
    //print_r($favouritesQuestions);
    $userData = $userData->getUserData($getProfile);
    $username = $userData['username'];
    $userSite = $userData['site'];
    $userQuestions = $questionsData->getAddedQuestionsToProfileSite($username);
    //print_r($userQuestions);
    $userAnswears = $answearsData->getAddedQuestionsToProfileSite($username);
    //print_r($userAnswears);

?>

    <!-- MAIN  -->
        <main class="container-fluid">
            <div class="row justify-content-center my-4 mx-0">
                <!-- LEFT COL -->
                <main class="col-md-4 col-l-4">
                    <?php echo "<h2 class='mb-5'> $username </h2>" ;
                    echo (!empty($userSite)) ? "<p>Strona użytkownika: <a href=$userSite>$userSite</a></p>" : null ?>
                    
                    
                </main>
                
                <!-- END LEFT COL -->

                <!-- RIGHT COL -->
                <div class="col-md-7 col-l-6 col-xl-5 border-left border-warning" >
                    <div>
                        <p>"Added questions:"</p>
                        <?php for ($i=0; $i < count($userQuestions); $i++) : ?>
                            <div>
                                <?php 
                                echo ($i+1)." ".$userQuestions[$i]['category']." -> ";
                                echo $userQuestions[$i]['title']."<br>";
                                ?>
                            </div>
                        <?php endfor; ?>
                    </div>

                    <div>
                        <p>"Added answears:"</p>
                        <?php for ($i=0; $i < count($userAnswears); $i++) : ?>
                            <div>
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
                        echo "<p> Your favourites questions:</p>";
                        for ($i=0; $i < count($favouritesQuestions); $i++) : ?>
                        <div class="d-flex flex-row">
                            <div class="d-flex flex-column align-items-start py-2 px-3">
                                <span data-toggle="tooltip" title="<?php echo $displayLang["add_to_favourites"] ?>" data-placement="bottom">
                                    <form action="" method="post">
                                            <input type="image" src="../img/heart-f.svg" alt="heart-icon" class="" name=<?php echo $favouritesQuestions[$i]['id']; ?> >
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


