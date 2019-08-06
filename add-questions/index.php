<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


    include "../functions.php";

    include "../header.php";

?>
        <!-- MAIN  -->
        <div class="container-fluid">
            <div class="row justify-content-center my-4 mx-0">
                <!-- LEFT COL -->
                <main class="col-md-7 col-xl-6">
                    <div>
                        <form action="" method="post">
                            <!-- <label for="question-title">Add question:</label> -->
                            <textarea type="text" name="question-title-textarea" class="form-control form-control-lg" placeholder="Add question"></textarea>
                            <div>
                                <select name="select-category" id="select-category" class="form-control my-4 shadow-none" required>
                                    <option value=""> --<?php echo $lang["choose-category"] ?>-- </option>
                                    <option value="HTML">HTML</option>
                                    <option value="CSS">CSS</option>
                                    <option value="Javascript">Javascript</option>
                                    <option value="Narzędzia programisty"><?php echo $lang["developer-tools"] ?></option>
                                </select>
                            </div>

                            <div class="d-flex flex-row justify-content-end">
                                <div class="container-flex justify-content-center">
                                    <button type="submit" name="add-question-button" class="btn btn-warning my-2 shadow-none myBtnHover"> <?php echo $lang["add_question"]  ?> </button>
                                </div>
                            </div>

                            <div class="container-fluid text-center py-4">
                                <?php 
                                    if (isset($_POST["add-question-button"])) {
                                        $category = $_POST['select-category'];
                                        $title = $_POST['question-title-textarea'];
                                        $displayQuestionsData->addQuestion($category, $title);
                                        echo "Pytanie zostało dodane";
                                    }
                                ?>
                            </div>

                        </form>
                    </div>

                    <div class="container-fluid text-center">
                        <div class="container-flex justify-content-center">
                            <a href="../" ><button type="submit" name="bak-to-main" class="btn btn-warning my-2 shadow-none myBtnHover"> <?php echo $lang["go-back"] ?> </button></a>
                        </div>
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


