<?php
    // include "show-question-functions.php";
    // include "../header.php";

    include "../functions.php";
    include "../header.php";

    $getId = $_GET['id'];
    $pageNumber = 1;
    $pageNavigationNumberForAnswears = $displayAnswearsData->pageNavigationNumber($getId);        // this must be set before getAnswears, because it read page number from url and sets answears to display on page
    $getAnswears = $displayAnswearsData->getAnswears($getId);
    $questionData = $displayQuestionsData->questionDataOnAnswearPage(($getId));
    $deleteQuestion = false;
    $loginMessage = -1;

    if (isset($_POST['add-answear-button'])) {
        $answear = $_POST['answear-textarea'];
        $author = $_SESSION['username'];
        //echo $answear;
        if(!empty($answear)) {
            if ($displayAnswearsData->addAnswear($getId, $answear, $author)) {
                $displayQuestionsData->setAnswearsNumber(($getId), '+');
            }
        }
    }

    if (isset($_GET['page'])) {
        $pageNumber = $_GET['page'];
    }

    if (isset($_POST['delete-question'])) {
        $deleteQuestion = true;
    }

    if (isset($_POST['delete-question-no'])) {
        $deleteQuestion = false;
    }

    if (isset($_POST['delete-question-yes'])) {
        if ($displayQuestionsData->deleteQuestion($getId)) {
            $questionsData->deleteFromFavourites($_SESSION['username'], $getId);
            $lang = $_SESSION['lang'];
            header("Location: /".$lang);
        } 
    }

    for($i=0; $i < count($getAnswears); $i++) {
        $answearId = $getAnswears[$i]['id'];
        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
            $user = $_SESSION['username'];
        }
        if (isset($_POST['delete-answear-'.$answearId])) {
            if ($displayAnswearsData->deleteAnswear($getAnswears[$i]['id'])) {
                $displayQuestionsData->setAnswearsNumber(($getId), '-');
                $answearsData->deleteVote($user, $answearId);
            }
        }
        if (isset($_POST['arr-up-'.$getAnswears[$i]['id']])) {
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
                $res = $answearsData->addVote($user, $answearId, "+");
                $up = $res[0];
                $down = $res[1];
                $difference = $res[2];
                if ($up) {
                    $answearsData->changeAnswearVotesNumber($answearId, "+", $difference);
                } else {
                    $answearsData->changeAnswearVotesNumber($answearId, "-", $difference);
                }
            } else {
                $loginMessage = $i;
            }
        }
        if (isset($_POST['arr-down-'.$getAnswears[$i]['id']])) {
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
                $res = $answearsData->addVote($user, $answearId, "-");
                $up = $res[0];
                $down = $res[1];
                $difference = $res[2];
                if ($down) {
                    $answearsData->changeAnswearVotesNumber($answearId, "-", $difference);
                } else {
                    $answearsData->changeAnswearVotesNumber($answearId, "+", $difference);
                }
            } else {
                $loginMessage = $i;
            }
        }
    }

    $getAnswears = $displayAnswearsData->getAnswears($getId);

?>

        <!-- MAIN  -->
        <div class="container-fluid p-0">
            <div class="row justify-content-center my-4 mx-0">
                <!-- LEFT COL -->
                <main class="col-md-7 col-xl-6">
                    
                    <!-- QUESTION DATA -->
                    <div class="container-flex">
                        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && $_SESSION['username'] === $questionData['author']) :  ?>
                        <p class="text-right text-muted pb-2">
                            <small>Autor: <?php echo $questionData['author']; ?>, data dodania: <?php echo $questionData['date']; ?> </small>
                            <div class="d-flex justify-content-end">
                                <form action=<?php echo basename($_SERVER['REQUEST_URI']) ?> method="post">
                                    <button type="submit" name="delete-question" class="btn btn-warning my-2 shadow-none myBtnHover d-flex" > <img src="../img/delete.svg" alt="trash-icon">Usuń pytanie</button>
                                </form>
                            </div> 
                            <?php if ($deleteQuestion) :  ?>
                                <div class="container text-center py-4">
                                    <form action="" method="post">
                                        <p>Czy na pewno chcesz usunąć to pytanie? </p>
                                        <button type="submit" name="delete-question-yes" class="btn btn-warning my-2 shadow-none myBtnHover"> Tak </button>
                                        <button type="submit" name="delete-question-no" class="btn btn-warning my-2 shadow-none myBtnHover"> Nie </button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </p>
                        <?php else: ?>
                        <p class="text-right text-muted pb-2"><small>Autor: <?php echo $questionData['author']; ?>, data dodania: <?php echo $questionData['date']; ?> </small></p>
                        <?php endif; ?>
                    </div>

                    <!-- QUESTION TITLE -->
                    <div class="pb-5">
                        <!-- nl2br() - displays enters -->
                        <h3 class="word-break h2-size"><?php echo nl2br($questionData['title']); ?></h3>
                    </div>
                    
                    <!-- ADD ANSWEAR BUTTON -->
                    <div class="d-flex flex-row justify-content-end">
                        <div class="container-flex justify-content-center">
                            <a href="#add-answear-div">
                                <button type="button" class="btn btn-warning my-2 shadow-none myBtnHover"> <?php echo "+ ". $displayLang["add_answear"]  ?> </button>
                            </a>
                        </div>
                    </div>

                    <!-- ANSWEARS -->
                    <div class="py-5">
                        <?php if (count($getAnswears) === 0) : ?>
                            <p>Nie dodano jeszcze odpowiedzi / No answers yet </p>
                        <?php else : ?>   
                            <!-- LOOP FOR ANSWEARS -->
                            <?php for($i=0; $i < count($getAnswears); $i++) : ?>
                                <?php 
                                    $author = $getAnswears[$i]['author'];
                                    $date = $getAnswears[$i]['date'];
                                    $id = $getAnswears[$i]['id'];
                                    $votes = $getAnswears[$i]['votes'];
                                    $answearText = $getAnswears[$i]['answear_text'];
                                ?>
                                <section class="container-fluid text-center p-0 pb-3 my-2" id=<?php echo $i ?> >
                                    <!--  AUTHOR, DATE, DELETE ICON FOR ANSWEAR  -->
                                    <p class=""><?php echo $loginMessage === $i ? 'Log in first!' : ''; ?></p>
                                    <div class="d-flex justify-content-between">
                                        <div class="pl-2 text-muted"><small>Autor: <?php echo $author; ?>, data dodania: <?php echo $date; ?> </small></div>
                                        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && $_SESSION['username'] === $author) :  ?>
                                        <div>
                                            <form action="" method="post"> 
                                                <button type="submit" id=<?php echo 'delete-answear-'.$id ?> name="<?php echo 'delete-answear-'.$id ?>" class="d-flex flex-column justify-content-center btn m-2 p-0 shadow-none" value="delete-answear">
                                                    <img src="../img/delete.svg" alt="trash-icon">
                                                </button>
                                            </form>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="d-flex flex-row">
                                        <!--  RATES SYSTEM  -->
                                        <div class="d-flex flex-column justify-content-start align-items-center bg-light rounded-left pl-1">
                                            <form action="" method="post">
                                                <button type="submit" id=<?php echo 'arr-up-'.$id ?> name=<?php echo 'arr-up-'.$id ?> class="btn border-0 m-0 p-0 shadow-none d-flex align-items-center flex-column" >
                                                    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) :  ?>
                                                        <img src=<?php echo $answearsData->isVoted($_SESSION['username'], $id)["up"] ? "../img/arr-up.svg" : "../img/arr-up-grey.svg" ?> alt="arrow-up-icon" class="arr">
                                                    <?php else: ?>
                                                        <img src="../img/arr-up-grey.svg" alt="arrow-up-icon" class="arr">
                                                    <?php endif; ?>
                                                </button>
                                                
                                                <div><p class="m-0 py-0"><?php echo $votes ?></p></div>
                                                
                                                <button type="submit" id=<?php echo 'arr-down-'.$id ?> name=<?php echo 'arr-down-'.$id ?> class="btn border-0 m-0 p-0 shadow-none d-flex align-items-center flex-column" >
                                                    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) :  ?>
                                                        <img src=<?php echo $answearsData->isVoted($_SESSION['username'], $id)["down"] ? "../img/arr-down.svg" : "../img/arr-down-grey.svg" ?> alt="arrow-down-icon" class="arr">
                                                    <?php else: ?>
                                                        <img src="../img/arr-down-grey.svg" alt="arrow-down-icon" class="arr">
                                                    <?php endif; ?>
                                                </button>
                                            </form>
                                        </div>
                                        
                                        <!--  ANSWEAR TEXT  -->
                                        <div class="bg-light rounded-right p-2 text-left flex-fill">
                                            <p class="word-break"> 
                                                <!-- nl2br() - displays enters -->
                                                <?php echo nl2br($getAnswears[$i]['answear_text'], false) ; ?>
                                            </p>
                                        </div>
                                    </div>                                   
                                </section>
                            <?php endfor; ?>
                        <?php endif; ?>
                    </div>


                    <!-- NUMERIC PAGE NAVIGATION -->
                    <div class="d-flex flex-row justify-content-center justify-content-lg-end flex-wrap py-4">
                        <?php if ($pageNavigationNumberForAnswears > 1) : ?>
                            <a href=<?php echo $getPathToNavigation ?> >
                                <div class="pagination first"> <?php echo 1; ?> </div>
                            </a>
                            <?php echo $pageNumber > 4 ? "<div class='pagination'> ... </div>" : null ?>
                            <?php for($i=$pageNumber-3; $i < $pageNumber+2; $i++) : ?>
                                <?php if ($i < 0 || $i >= $pageNavigationNumberForAnswears || $i===0 || $i===intval($pageNavigationNumberForAnswears-1)) {continue;} ?>
                                    <a href=<?php echo ($i === 0) ? $getPathToNavigation : $getPathToNavigation.'&page='.($i+1) ?> >
                                        <div class="pagination"> <?php echo ($i+1); ?> </div>
                                    </a>
                            <?php endfor; ?>
                            <?php echo $pageNumber < $pageNavigationNumberForAnswears-3 ? "<div class='pagination'> ... </div>" : null ?>
                            <a href=<?php echo $getPathToNavigation.'&page='.$pageNavigationNumberForAnswears ?> >
                                <div class="pagination last"> <?php echo $pageNavigationNumberForAnswears; ?> </div>
                            </a>
                        <?php endif; ?>
                    </div>


                    <!-- ADD ANSWEAR FORM -->
                    <div class="py-5" id="add-answear-div">
                        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) :  ?>
                        <form action="" method="post">
                            <textarea type="text" name="answear-textarea" class="form-control form-control-lg" placeholder="Add answear"></textarea>
                            <div class="d-flex flex-row justify-content-end">
                                    <div class="container-flex justify-content-center pt-3">
                                        <button type="submit" name="add-answear-button" id="add-answear-button" class="btn btn-warning my-2 shadow-none myBtnHover"> <?php echo "+ ".$displayLang["add_answear"]  ?> </button>
                                    </div>
                            </div>
                        </form>
                        <?php else: ?>   
                            <div class="container-fluid text-center my-2" id="log-in-first">
                                <p class="py-3">Please log in to add answear</p>
                                <a href=<?php echo '/'.$_SESSION['lang'].'/login/' ?>><button type="button" class="btn btn-outline-warning"><?php echo $displayLang["log_in"]  ?></button></a>
                            </div>
                        <?php endif; ?>
                    </div>
                    <!-- END ANSWEARS -->
                </main>
                <!-- END LEFT COL -->

                <!-- RIGHT COL -->
                <div class="col-md-4 col-xl-3 border-left border-warning">
                    asd
                </div>
                <!-- END RIGHT COL -->

            </div>
        </div>
        <!-- END MAIN -->
        
<?php include "../footer.php"; ?>


