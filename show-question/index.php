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
            $lang = $_SESSION['lang'];
            header("Location: /".$lang);
        } 
    }

    for($i=0; $i < count($getAnswears); $i++) {
        if (isset($_POST['delete-answear-'.$getAnswears[$i]['id']])) {
            if ($displayAnswearsData->deleteAnswear($getAnswears[$i]['id'])) {
                $displayQuestionsData->setAnswearsNumber(($getId), '-');
            }
        }
    }

    $getAnswears = $displayAnswearsData->getAnswears($getId);
?>

        <!-- MAIN  -->
        <div class="container-fluid">
            <div class="row justify-content-center my-4 mx-0">
                <!-- LEFT COL -->
                <main class="col-md-7 col-xl-6">
                    
                    <!-- QUESTION DATA -->
                    <div class="container-flex">
                        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && $_SESSION['username'] === $questionData['author']) :  ?>
                        <p class="text-right text-muted pb-2">
                            Autor: <?php echo $questionData['author']; ?>, data dodania: <?php echo $questionData['date']; ?> 
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
                        <p class="text-right text-muted pb-2">Autor: <?php echo $questionData['author']; ?>, data dodania: <?php echo $questionData['date']; ?>  </p>
                        <?php endif; ?>
                    </div>

                    <!-- QUESTION TITLE -->
                    <div class="pb-5">
                        <!-- nl2br() - displays enters -->
                        <h3 class="word-break"><?php echo nl2br($questionData['title']); ?></h3>
                    </div>
                    
                    <!-- ADD ANSWEAR BUTTON -->
                    <div class="d-flex flex-row justify-content-end">
                        <div class="container-flex justify-content-center">
                            <a href="#add-answear-div">
                                <button type="button" class="btn btn-warning my-2 shadow-none myBtnHover"> <?php echo "+ ". $lang["add_answear"]  ?> </button>
                            </a>
                        </div>
                    </div>

                    <!-- ANSWEARS -->
                    <div class="py-5">
                        <?php if (count($getAnswears) === 0) : ?>
                            <p>Nie dodano jeszcze odpowiedzi / No answers yet </p>
                        <?php else : ?>   
                            <?php for($i=0; $i < count($getAnswears); $i++) : ?>
                                <section class="container-fluid text-center p-0 pb-3 my-2" id=<?php echo $i ?> >
                                    <div class="d-flex justify-content-between">
                                        <div class="pl-2 text-muted"><small>Autor: <?php echo $getAnswears[$i]['author']; ?>, data dodania: <?php echo $getAnswears[$i]['date']; ?> </small></div>
                                        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && $_SESSION['username'] === $getAnswears[$i]['author']) :  ?>
                                        <div>
                                            <form action="" method="post"> <button type="submit" id="delete-answear" name="<?php echo 'delete-answear-'.$getAnswears[$i]['id'] ?>" class="d-flex flex-column justify-content-center btn btn-warning my-2 shadow-none myBtnHover" value="delete-answear"><img src="../img/delete.svg" alt="trash-icon"></button></form>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="d-flex flex-row bg-light rounded p-2 text-left">
                                        <p class="word-break"> 
                                            <!-- nl2br() - displays enters -->
                                            <?php echo nl2br($getAnswears[$i]['answear_text'], false) ; ?>
                                        </p>
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
                                        <button type="submit" name="add-answear-button" id="add-answear-button" class="btn btn-warning my-2 shadow-none myBtnHover"> <?php echo "+ ".$lang["add_answear"]  ?> </button>
                                    </div>
                            </div>
                        </form>
                        <?php else: ?>   
                            <div class="container-fluid text-center my-2">
                                <p class="py-3">Please log in to add answear</p>
                                <a href=<?php echo '/'.$_SESSION['lang'].'/login/' ?>><button type="button" class="btn btn-outline-warning"><?php echo $lang["log_in"]  ?></button></a>
                            </div>
                        <?php endif; ?>
                    </div>

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


