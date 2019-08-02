<?php
    include "../functions.php";

    include "../header.php";

    $getId = $_GET['id']-1;

?>
        <!-- MAIN  -->
        <div class="container-fluid">
            <div class="row justify-content-center my-4 mx-0">
                <!-- LEFT COL -->
                <main class="col-md-7 col-xl-6">
                    
                    
                    <div class="container-flex">
                        <p class="text-right text-muted pb-2">Autor: <?php echo $getQuestionsData->getQuestionData('author')[$getId]; ?>, data dodania: <?php echo $getQuestionsData->getQuestionData('date')[$getId]; ?>  </p>
                    </div>

                    <div class="pb-5">
                        <h3><?php echo $getQuestionsData->getQuestionData('title')[$getId]; ?></h3>
                    </div>

                    <div class="d-flex flex-row justify-content-end">
                        <div class="container-flex justify-content-center">
                            <button type="button" class="btn btn-warning my-2 shadow-none myBtnHover"> <?php echo $lang["add_answear"]  ?> </button>
                        </div>
                    </div>

                    <div>
                        odpowiedzi w petli d <br><br>
                        <section class="container-fluid text-center p-0 my-2">
                            <div class="d-flex flex-row bg-light rounded">
                                sada<br>
                                sada<br>
                                sada<br>
                                sada<br>
                                sada<br>
                            </div>
                        </section>

                        <?php for($i=0; $i < $getAnswearsData->answearRowsNum($getId); $i++) : ?>
                            <section class="container-fluid text-center p-0 my-2">
                                <div class="d-flex flex-row bg-light rounded p-2">
                                    <p> <?php echo $getAnswearsData->getAnswearData('answer_text', $getId)[$i]; ?> </p>
                                </div>
                            </section>
                        <?php endfor; ?>
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


