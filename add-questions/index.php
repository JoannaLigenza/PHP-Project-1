<?php
    include "../functions.php";
    include "../functions-run.php";

    $_SESSION['title'] = $displayLang["add_site_title"];
    $_SESSION['description'] = $displayLang["add_site_desc"];

    include "../header.php";

    $isQuestionAdded = false;
    $displayQuestionsData = new DisplayQuestionsData();
    //$pageNavigationNumberForQuestions = $displayQuestionsData->pageNavigationNumber();
    //$questionData = $displayQuestionsData->getQuestions();

    if (isset($_POST["add-question-button"])) {
        // Convert special characters like < " > to HTML entities ( &lt; &quot; &gt;), so user cannot make script injections
        $category = htmlspecialchars($_POST['select-category'], ENT_QUOTES);
        $title = htmlspecialchars($_POST['question-title-textarea'], ENT_QUOTES);
        $author = $_SESSION['username'];
        if(!empty($title)) {
            if ($displayQuestionsData->addQuestion($category, $title, $author)){
                $isQuestionAdded = true;
                $lang = $_SESSION['lang'];
                $lastIndex = $displayQuestionsData->lastQuestionIndex();
                $site = "question";
                if ($_SESSION['lang'] === 'pl') {
                    $site = "pytanie";
                }
                header("Location: /".$lang."/".$site."?id=".$lastIndex);
            }
        }
    }
    
?>
        <!-- MAIN  -->
        <div class="container-fluid">
            <div class="row justify-content-center my-4 mx-0">
                <!-- LEFT COL -->
                <main class="col-md-7 col-xl-6 mb-5">
                    <?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']===true): ?>
                    <div>
                        <form action="" method="post">
                            <!-- <label for="question-title">Add question:</label> -->
                            <textarea type="text" name="question-title-textarea" class="form-control form-control-lg" <?php $placeholderText = $displayLang['add_question']; echo "placeholder='$placeholderText'" ?>></textarea>
                            <div>
                                <select name="select-category" id="select-category" class="form-control my-4 shadow-none" required>
                                    <option value=""> --<?php echo $displayLang["choose-category"] ?>-- </option>
                                    <option value="HTML">HTML</option>
                                    <option value="CSS">CSS</option>
                                    <option value="Javascript">Javascript</option>
                                    <option value="Narzędzia programisty"><?php echo $displayLang['developer-tools'] ?></option>
                                </select>
                            </div>

                            <div class="d-flex flex-row justify-content-end">
                                <div class="container-flex justify-content-center">
                                    <button type="submit" name="add-question-button" class="btn btn-warning my-2 shadow-none myBtnHover"> <?php echo "+ ".$displayLang['add_question']  ?> </button>
                                </div>
                            </div>

                            <div class="container-fluid text-center py-4">
                                <?php echo $isQuestionAdded ? 'Pytanie zostało dodane' : "" ?>
                            </div>

                        </form>
                    </div>

                    <div class="container-fluid text-center">
                        <div class="container-flex justify-content-center">
                            <a href="../" ><button type="submit" name="bak-to-main" class="btn btn-warning my-2 shadow-none myBtnHover"> <?php echo $displayLang['go-back'] ?> </button></a>
                        </div>
                    </div>
                    
                    <?php else: ?>
                        <div class="container-fluid py-5"><p class="text-center"> <?php echo $displayLang['log-in-to-add-question']?> </p></div>
                        <div class="container-fluid text-center my-5">
                            <a href=<?php echo '/'.$_SESSION['lang'].'/login/' ?>><button type="button" class="btn btn-outline-warning"><?php echo $displayLang['log_in']  ?></button></a>
                        </div>
                    <?php endif ?>
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


