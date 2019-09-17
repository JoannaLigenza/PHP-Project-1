<?php
    // include "show-question-functions.php";
    // include "../header.php";

    include "../functions.php";
    include "../functions-run.php";
    $_SESSION['title'] = $displayLang["answers_site_title"];
    $_SESSION['description'] = $displayLang["answers_site_desc"];
    $_SESSION['index'] = "index";
    include "../header.php";

    $getId = $_GET['id'];
    $pageNumber = 1;
    $deleteQuestion = false;
    $loginMessage = -1;

    // New path to reload page
    $path = new Path();
    $getPathToNavigation = $path->getPath();

    // Sorting answers
    $setSession = new SetSession();
    $setSession->setSessionParams($displayLang, $getPathToNavigation);

    // Get question data
    $questionsData = new QuestionsData();
    $displayQuestionsData = new DisplayQuestionsData();
    $oneQuestionData = $displayQuestionsData->questionDataOnanswerPage(($getId));
    
    // Get answers data
    $answersData = new answersData();
    $displayanswersData = new DisplayanswersData();
    $pageNavigationNumberForanswers = $displayanswersData->pageNavigationNumber($getId);        // this must be set before getanswers, because it read page number from url and sets answers to display on page
    $getanswers = $displayanswersData->getanswers($getId);

    $mainDir = $_SESSION['main-dir'];
    
    // if there is no question with id given from url, ther redirest to main page
    if ($oneQuestionData === NULL) {
        header('Location: '.$mainDir.'/'.$_SESSION['lang']);
    }

    if (isset($_POST['add-answer-button'])) {
        $answer = htmlspecialchars($_POST['answer-textarea'], ENT_QUOTES);
        $author = $_SESSION['username'];
        //echo $answer;
        if(!empty($answer)) {
            if ($displayanswersData->addanswer($getId, $answer, $author)) {
                $displayQuestionsData->setanswersNumber(($getId), '+');
                // if added answer appear on new page then go to this page
                $page;
                if (empty($_GET['page'])) {
                    $page = 1;
                } else {
                    $page = $_GET['page'];
                }
                $numOfPages = ceil($displayanswersData->getAllanswersNum($getId)/$displayanswersData->answersNumOnPage);
                if ($numOfPages > $page ) {
                    header("Location: $getPathToNavigation&page=".($numOfPages+1));
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

    for($i=0; $i < count($getanswers); $i++) {
        $answerId = $getanswers[$i]['id'];
        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
            $user = $_SESSION['username'];
        }
        if (isset($_POST['delete-answer-'.$answerId])) {
            if ($displayanswersData->deleteanswer($getanswers[$i]['id'], $user)) {
                $displayQuestionsData->setanswersNumber(($getId), '-');
                $answersData->deleteVote($user, $answerId);
            }
        }
        if (isset($_POST['arr-up-'.$getanswers[$i]['id']])) {
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
                $res = $answersData->addVote($user, $answerId, "+");
                $up = $res[0];
                $down = $res[1];
                $difference = $res[2];
                if ($up) {
                    $answersData->changeanswerVotesNumber($answerId, "+", $difference);
                } else {
                    $answersData->changeanswerVotesNumber($answerId, "-", $difference);
                }
            } else {
                $loginMessage = $i;
            }
        }
        if (isset($_POST['arr-down-'.$getanswers[$i]['id']])) {
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
                $res = $answersData->addVote($user, $answerId, "-");
                $up = $res[0];
                $down = $res[1];
                $difference = $res[2];
                if ($down) {
                    $answersData->changeanswerVotesNumber($answerId, "-", $difference);
                } else {
                    $answersData->changeanswerVotesNumber($answerId, "+", $difference);
                }
            } else {
                $loginMessage = $i;
            }
        }
    }

    $getanswers = $displayanswersData->getanswers($getId);

    // if deleted answer was last one on page then go to previous page
    if (count($getanswers) <= 0 && (!empty($_GET['page']) ) ) {
        if ($_GET['page'] > 1) {
            $redirectToPage = ceil($displayanswersData->getAllanswersNum($getId)/$displayanswersData->answersNumOnPage);
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
                        <?php $pathToProfile = '../profile/?profile='.$oneQuestionData['author'];
                        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && $_SESSION['username'] === $oneQuestionData['author']) :  ?>
                        <p class="text-right text-muted pb-2">
                            <small><?php echo $displayLang['author'].": <a href='$pathToProfile'>".$oneQuestionData['author']."</a>, "; echo $displayLang['adding-date'].': '.$oneQuestionData['date']; ?> </small>
                            <div class="d-flex justify-content-end">
                                <form action=<?php echo basename($_SERVER['REQUEST_URI']) ?> method="post">
                                    <button type="submit" name="delete-question" class="btn btn-warning my-2 shadow-none myBtnHover d-flex" > <img src=<?php echo $mainDir."/img/delete.svg" ?> alt="trash-icon"><?php echo $displayLang['delte-question'] ?></button>
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
                        <p class="text-right text-muted pb-2"><small><?php echo $displayLang['author'].": <a href='$pathToProfile'>".$oneQuestionData['author']."</a>, "; echo $displayLang['adding-date'].': '.$oneQuestionData['date']; ?> </small></p>
                        <?php endif; ?>
                    </div>

                    <!-- QUESTION TITLE -->
                    <div class="pb-5">
                        <!-- nl2br() - displays enters -->
                        <h3 class="word-break h2-size"><?php echo nl2br($oneQuestionData['title']); ?></h3>
                    </div>
                    
                    <!-- ADD answer BUTTON -->
                    <div class="d-flex flex-row justify-content-end">
                        <div class="container-flex justify-content-center">
                            <a href="#add-answer-div">
                                <button type="button" class="btn btn-warning my-2 shadow-none myBtnHover" id="scroll-to-add-answer-button"> <?php echo "+ ". $displayLang["add_answer"]  ?> </button>
                            </a>
                        </div>
                    </div>

                    <!--  SORTING answerS  -->
                    <div class="my-3">
                        <div class="d-flex flex-row justify-content-end">
                            <ul class="nav nav-tabs">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $displayLang["sort"].":"  ?></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <form action=<?php echo $getPathToNavigation ?> method="post">
                                            <button type="submit" name="answer-adding-date" class=<?php echo (isset($_SESSION['answer-sort']) && $_SESSION['answer-sort'] === "date") ? "'dropdown-item shadow-none bg-light'" : "'dropdown-item shadow-none'" ?>><?php echo $displayLang["adding_date"]; ?></button>
                                            <button type="submit" name="answer-top-rated" class=<?php echo (isset($_SESSION['answer-sort']) && $_SESSION['answer-sort'] === "votes") ? "'dropdown-item shadow-none bg-light'" : "'dropdown-item shadow-none'" ?>><?php echo $displayLang["top_rated"]; ?></button>
                                        </form>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- <div "> 
                        <form action="" method="post" class="d-flex flex-row justify-content-end pt-3">
                            <p class="btn shadow-none h6-size">Sort:</p>
                            <button type="submit" name="answer-adding-date" class="btn shadow-none h6-size"><?php echo $displayLang["adding_date"]; ?></button>
                            <button type="submit" name="answer-top-rated" class="btn shadow-none h6-size"><?php echo $displayLang["top_rated"]; ?></button>
                        </form>
                    </div> -->
                    

                    <!-- answerS -->
                    <div class="py-5">
                        <?php if (count($getanswers) === 0) : ?>
                            <p><?php echo $displayLang["no-answer"] ?> </p>
                        <?php else : ?>   
                            <!-- LOOP FOR answerS -->
                            <?php for($i=0; $i < count($getanswers); $i++) : ?>
                                <?php 
                                    $author = $getanswers[$i]['author'];
                                    $date = $getanswers[$i]['date'];
                                    $id = $getanswers[$i]['id'];
                                    $votes = $getanswers[$i]['votes'];
                                    $answerText = $getanswers[$i]['answer_text'];
                                    $pathToProfile = '../profile/?profile='.$author;
                                ?>
                                <section class="container-fluid text-center p-0 pb-3 my-2" id=<?php echo $i ?> >
                                    <!--  AUTHOR, DATE, DELETE ICON FOR answer  -->
                                    <p class="login-first-message" id=<?php echo 'login-first-message-'.$id ?>  > <?php echo $loginMessage === $i ? $displayLang['log_in_first'] : ''; ?></p>
                                    <div class="d-flex justify-content-between">
                                        <div class="pl-2 text-muted"><small><?php echo $displayLang['author'].": <a href='$pathToProfile'>".$author."</a>, "; echo $displayLang['adding-date'].': '.$date; ?> </small></div>
                                        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && $_SESSION['username'] === $author) :  ?>
                                        <div>
                                            <form action="" method="post"> 
                                                <button type="submit" id=<?php echo 'delete-answer-'.$id ?> name="<?php echo 'delete-answer-'.$id ?>" class="d-flex flex-column justify-content-center btn m-2 p-0 shadow-none" value="delete-answer">
                                                    <img src=<?php echo $mainDir."/img/delete.svg" ?> alt="trash-icon">
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
                                                        <img src=<?php echo $answersData->isVoted($_SESSION['username'], $id)["up"] ? $mainDir."/img/arr-up.svg" : $mainDir."/img/arr-up-grey.svg" ?> alt="arrow-up-icon" class="arr-up">
                                                    <?php else: ?>
                                                        <img src=<?php echo $mainDir."/img/arr-up-grey.svg" ?> alt="arrow-up-icon" class="arr-up">
                                                    <?php endif; ?>
                                                </button>
                                                
                                                <div><p id=<?php echo 'votes-'.$id ?> class="m-0 py-0"><?php echo $votes ?></p></div>
                                                
                                                <button type="submit" id=<?php echo 'arr-down-'.$id ?> name=<?php echo 'arr-down-'.$id ?> class="btn border-0 m-0 p-0 shadow-none d-flex align-items-center flex-column rate-down" >
                                                    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) :  ?>
                                                        <img src=<?php echo $answersData->isVoted($_SESSION['username'], $id)["down"] ? "../img/arr-down.svg" : "../img/arr-down-grey.svg" ?> alt="arrow-down-icon" class="arr-down">
                                                    <?php else: ?>
                                                        <img src=<?php echo $mainDir."/img/arr-down-grey.svg" ?> alt="arrow-down-icon" class="arr-down">
                                                    <?php endif; ?>
                                                </button>
                                            </form>
                                        </div>
                                        
                                        <!--  answer TEXT  -->
                                        <div class="bg-light rounded-right p-2 text-left flex-fill">
                                            <!-- white-space: pre-wrap - displays indentation (wciecia tekstu). Below code must be written in one line // or may use nl2br() - to displays enters -->
                                            <p class="word-break" style="white-space: pre-wrap;"><?php echo $getanswers[$i]['answer_text'] ; ?></p>
                                        </div>
                                    </div>                                   
                                </section>
                            <?php endfor; ?>
                        <?php endif; ?>
                    </div>


                    <!-- NUMERIC PAGE NAVIGATION -->
                    <div class="d-flex flex-row justify-content-center justify-content-lg-end flex-wrap py-4">
                        <?php if ($pageNavigationNumberForanswers > 1) : ?>
                            <a href=<?php echo $getPathToNavigation ?> >
                                <div class="pagination first"> <?php echo 1; ?> </div>
                            </a>
                            <?php echo $pageNumber > 4 ? "<div class='pagination'> ... </div>" : null ?>
                            <?php for($i=$pageNumber-3; $i < $pageNumber+2; $i++) : ?>
                                <?php if ($i < 0 || $i >= $pageNavigationNumberForanswers || $i===0 || $i===intval($pageNavigationNumberForanswers-1)) {continue;} ?>
                                    <a href=<?php echo ($i === 0) ? $getPathToNavigation : $getPathToNavigation.'&page='.($i+1) ?> >
                                        <div class="pagination"> <?php echo ($i+1); ?> </div>
                                    </a>
                            <?php endfor; ?>
                            <?php echo $pageNumber < $pageNavigationNumberForanswers-3 ? "<div class='pagination'> ... </div>" : null ?>
                            <a href=<?php echo $getPathToNavigation.'&page='.$pageNavigationNumberForanswers ?> >
                                <div class="pagination last"> <?php echo $pageNavigationNumberForanswers; ?> </div>
                            </a>
                        <?php endif; ?>
                    </div>


                    <!-- ADD answer FORM -->
                    <div class="py-5" id="add-answer-div">
                        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) :  ?>
                        <form action="#add-answer-div" method="post" id="add-answer-form">
                            <textarea type="text" name="answer-textarea" id="answer-textarea" class="form-control form-control-lg" <?php $placeholderText = $displayLang['add_answer']; echo "placeholder='$placeholderText'" ?>></textarea>
                            <div class="d-flex flex-row justify-content-end">
                                    <div class="container-flex justify-content-center pt-3">
                                        <button type="submit" name="add-answer-button" id="add-answer-button" class="btn btn-warning my-2 shadow-none myBtnHover"> <?php echo "+ ".$displayLang["add_answer"]  ?> </button>
                                    </div>
                            </div>
                        </form>
                        <?php else: ?>   
                            <div class="container-fluid text-center my-2" id="log-in-first">
                                <p class="py-3"><?php echo $displayLang["log_in_to_add_answer"] ?></p>
                                <a href=<?php echo $mainDir.'/'.$_SESSION['lang'].'/login/' ?>><button type="button" class="btn btn-outline-warning"><?php echo $displayLang["log_in"]  ?></button></a>
                            </div>
                        <?php endif; ?>
                    </div>
                    <!-- END answerS -->
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


