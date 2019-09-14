<?php
    // include "show-question-functions.php";
    // include "../header.php";

    include "../functions.php";
    include "../functions-run.php";
    $_SESSION['title'] = $displayLang["answears_site_title"];
    $_SESSION['description'] = $displayLang["answears_site_desc"];
    $_SESSION['index'] = "index";
    include "../header.php";

    $getId = $_GET['id'];
    $pageNumber = 1;
    $deleteQuestion = false;
    $loginMessage = -1;

    // New path to reload page
    $path = new Path();
    $getPathToNavigation = $path->getPath();

    // Sorting answears
    $setSession = new SetSession();
    $setSession->setSessionParams($displayLang, $getPathToNavigation);

    // Get question data
    $questionsData = new QuestionsData();
    $displayQuestionsData = new DisplayQuestionsData();
    $questionData = $displayQuestionsData->questionDataOnAnswearPage(($getId));
    
    // Get answears data
    $answearsData = new AnswearsData();
    $displayAnswearsData = new DisplayAnswearsData();
    $pageNavigationNumberForAnswears = $displayAnswearsData->pageNavigationNumber($getId);        // this must be set before getAnswears, because it read page number from url and sets answears to display on page
    //echo $pageNavigationNumberForAnswears;
    $getAnswears = $displayAnswearsData->getAnswears($getId);
    
    //print_r($getAnswears);

    
    

    if (isset($_POST['add-answear-button'])) {
        $answear = htmlspecialchars($_POST['answear-textarea'], ENT_QUOTES);
        $author = $_SESSION['username'];
        //echo $answear;
        if(!empty($answear)) {
            if ($displayAnswearsData->addAnswear($getId, $answear, $author)) {
                $displayQuestionsData->setAnswearsNumber(($getId), '+');
                // if added answear appear on new page then go to this page
                $page;
                if (empty($_GET['page'])) {
                    $page = 1;
                } else {
                    $page = $_GET['page'];
                }
                if (ceil($displayAnswearsData->getAllAnswearsNum($getId)/$displayAnswearsData->answearsNumOnPage) > $page ) {
                    header("Location: $getPathToNavigation&page=".($page+1));
                }
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
        $user = $_SESSION['username'];
        echo "deleted 1";
        if ($displayQuestionsData->deleteQuestion($getId, $user)) {
            echo "deleted 2";
            $questionsData->deleteFromFavourites($user, $getId);
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
            if ($displayAnswearsData->deleteAnswear($getAnswears[$i]['id'], $user)) {
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

    // if deleted answear was last one on page then go to previous page
    if (count($getAnswears) <= 0 && (!empty($_GET['page']) ) ) {
        if ($_GET['page'] > 1) {
            $redirectToPage = ceil($displayAnswearsData->getAllAnswearsNum($getId)/$displayAnswearsData->answearsNumOnPage);
            header("Location: $getPathToNavigation&page=$redirectToPage");
        }
        
    }
?>

        <!-- MAIN  -->
        <div class="container-fluid p-0">
            <div class="row justify-content-center my-4 mx-0">
                <!-- LEFT COL -->
                <main class="col-md-7 col-xl-6">
                    
                    <!-- QUESTION DATA -->
                    <div class="container-flex">
                        <?php $pathToProfile = '../profile/?profile='.$questionData['author'];
                        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && $_SESSION['username'] === $questionData['author']) :  ?>
                        <p class="text-right text-muted pb-2">
                            <small><?php echo $displayLang['author'].": <a href='$pathToProfile'>".$questionData['author']."</a>, "; echo $displayLang['adding-date'].': '.$questionData['date']; ?> </small>
                            <div class="d-flex justify-content-end">
                                <form action=<?php echo basename($_SERVER['REQUEST_URI']) ?> method="post">
                                    <button type="submit" name="delete-question" class="btn btn-warning my-2 shadow-none myBtnHover d-flex" > <img src="../img/delete.svg" alt="trash-icon"><?php echo $displayLang['delte-question'] ?></button>
                                </form>
                            </div> 
                            <?php if ($deleteQuestion) :  ?>
                                <div class="container text-center py-4">
                                    <form action="" method="post">
                                        <p><?php echo $displayLang['delte-question-confirm'] ?> </p>
                                        <button type="submit" name="delete-question-yes" class="btn btn-warning my-2 shadow-none myBtnHover"> <?php echo $displayLang['yes'] ?> </button>
                                        <button type="submit" name="delete-question-no" class="btn btn-warning my-2 shadow-none myBtnHover"> <?php echo $displayLang['no'] ?> </button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </p>
                        <?php else: ?>
                        <p class="text-right text-muted pb-2"><small><?php echo $displayLang['author'].": <a href='$pathToProfile'>".$questionData['author']."</a>, "; echo $displayLang['adding-date'].': '.$questionData['date']; ?> </small></p>
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
                                <button type="button" class="btn btn-warning my-2 shadow-none myBtnHover" id="scroll-to-add-answear-button"> <?php echo "+ ". $displayLang["add_answear"]  ?> </button>
                            </a>
                        </div>
                    </div>

                    <!--  SORTING ANSWEARS  -->
                    <div class="my-3">
                        <div class="d-flex flex-row justify-content-end">
                            <ul class="nav nav-tabs">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $displayLang["sort"].":"  ?></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <form action=<?php echo $getPathToNavigation ?> method="post">
                                            <button type="submit" name="answear-adding-date" class=<?php echo (isset($_SESSION['answear-sort']) && $_SESSION['answear-sort'] === "date") ? "'dropdown-item shadow-none bg-light'" : "'dropdown-item shadow-none'" ?>><?php echo $displayLang["adding_date"]; ?></button>
                                            <button type="submit" name="answear-top-rated" class=<?php echo (isset($_SESSION['answear-sort']) && $_SESSION['answear-sort'] === "votes") ? "'dropdown-item shadow-none bg-light'" : "'dropdown-item shadow-none'" ?>><?php echo $displayLang["top_rated"]; ?></button>
                                        </form>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- <div "> 
                        <form action="" method="post" class="d-flex flex-row justify-content-end pt-3">
                            <p class="btn shadow-none h6-size">Sort:</p>
                            <button type="submit" name="answear-adding-date" class="btn shadow-none h6-size"><?php echo $displayLang["adding_date"]; ?></button>
                            <button type="submit" name="answear-top-rated" class="btn shadow-none h6-size"><?php echo $displayLang["top_rated"]; ?></button>
                        </form>
                    </div> -->
                    

                    <!-- ANSWEARS -->
                    <div class="py-5">
                        <?php if (count($getAnswears) === 0) : ?>
                            <p><?php echo $displayLang["no-answear"] ?> </p>
                        <?php else : ?>   
                            <!-- LOOP FOR ANSWEARS -->
                            <?php for($i=0; $i < count($getAnswears); $i++) : ?>
                                <?php 
                                    $author = $getAnswears[$i]['author'];
                                    $date = $getAnswears[$i]['date'];
                                    $id = $getAnswears[$i]['id'];
                                    $votes = $getAnswears[$i]['votes'];
                                    $answearText = $getAnswears[$i]['answear_text'];
                                    $pathToProfile = '../profile/?profile='.$author;
                                ?>
                                <section class="container-fluid text-center p-0 pb-3 my-2" id=<?php echo $i ?> >
                                    <!--  AUTHOR, DATE, DELETE ICON FOR ANSWEAR  -->
                                    <p class="login-first-message" id=<?php echo 'login-first-message-'.$id ?>  > <?php echo $loginMessage === $i ? $displayLang['log_in_first'] : ''; ?></p>
                                    <div class="d-flex justify-content-between">
                                        <div class="pl-2 text-muted"><small><?php echo $displayLang['author'].": <a href='$pathToProfile'>".$author."</a>, "; echo $displayLang['adding-date'].': '.$date; ?> </small></div>
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
                                        <!--  RATING SYSTEM  -->
                                        <div class="d-flex flex-column justify-content-start align-items-center bg-light rounded-left pl-1">
                                            <form action="" method="post">
                                                <button type="submit" id=<?php echo 'arr-up-'.$id ?> name=<?php echo 'arr-up-'.$id ?> class="btn border-0 m-0 p-0 shadow-none d-flex align-items-center flex-column rate-up" >
                                                    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) :  ?>
                                                        <img src=<?php echo $answearsData->isVoted($_SESSION['username'], $id)["up"] ? "../img/arr-up.svg" : "../img/arr-up-grey.svg" ?> alt="arrow-up-icon" class="arr-up">
                                                    <?php else: ?>
                                                        <img src="../img/arr-up-grey.svg" alt="arrow-up-icon" class="arr-up">
                                                    <?php endif; ?>
                                                </button>
                                                
                                                <div><p id=<?php echo 'votes-'.$id ?> class="m-0 py-0"><?php echo $votes ?></p></div>
                                                
                                                <button type="submit" id=<?php echo 'arr-down-'.$id ?> name=<?php echo 'arr-down-'.$id ?> class="btn border-0 m-0 p-0 shadow-none d-flex align-items-center flex-column rate-down" >
                                                    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) :  ?>
                                                        <img src=<?php echo $answearsData->isVoted($_SESSION['username'], $id)["down"] ? "../img/arr-down.svg" : "../img/arr-down-grey.svg" ?> alt="arrow-down-icon" class="arr-down">
                                                    <?php else: ?>
                                                        <img src="../img/arr-down-grey.svg" alt="arrow-down-icon" class="arr-down">
                                                    <?php endif; ?>
                                                </button>
                                            </form>
                                        </div>
                                        
                                        <!--  ANSWEAR TEXT  -->
                                        <div class="bg-light rounded-right p-2 text-left flex-fill">
                                            <!-- white-space: pre-wrap - displays indentation (wciecia tekstu). Below code must be written in one line // or may use nl2br() - to displays enters -->
                                            <p class="word-break" style="white-space: pre-wrap;"><?php echo $getAnswears[$i]['answear_text'] ; ?></p>
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
                        <form action="#add-answear-div" method="post" id="add-answear-form">
                            <textarea type="text" name="answear-textarea" id="answear-textarea" class="form-control form-control-lg" <?php $placeholderText = $displayLang['add_answear']; echo "placeholder='$placeholderText'" ?>></textarea>
                            <div class="d-flex flex-row justify-content-end">
                                    <div class="container-flex justify-content-center pt-3">
                                        <button type="submit" name="add-answear-button" id="add-answear-button" class="btn btn-warning my-2 shadow-none myBtnHover"> <?php echo "+ ".$displayLang["add_answear"]  ?> </button>
                                    </div>
                            </div>
                        </form>
                        <?php else: ?>   
                            <div class="container-fluid text-center my-2" id="log-in-first">
                                <p class="py-3"><?php echo $displayLang["log_in_to_add_answear"] ?></p>
                                <a href=<?php echo '/'.$_SESSION['lang'].'/login/' ?>><button type="button" class="btn btn-outline-warning"><?php echo $displayLang["log_in"]  ?></button></a>
                            </div>
                        <?php endif; ?>
                    </div>
                    <!-- END ANSWEARS -->
                </main>
                <!-- END LEFT COL -->

                <!-- RIGHT COL -->
                <div class="d-none d-md-block col-md-4 col-xl-3">
                    <?php include "../right-col.php" ?>
                </div>
                <!-- END RIGHT COL -->

            </div>
        </div>
        <!-- END MAIN -->
        
<?php include "../footer.php"; ?>


