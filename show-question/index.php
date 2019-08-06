<?php
    include "show-question-functions.php";

    include "../header.php";

    

?>
        <!-- MAIN  -->
        <div class="container-fluid">
            <div class="row justify-content-center my-4 mx-0">
                <!-- LEFT COL -->
                <main class="col-md-7 col-xl-6">
                    
                    
                    <div class="container-flex">
                        <p class="text-right text-muted pb-2">Autor: <?php echo $questionData['author']; ?>, data dodania: <?php echo $questionData['date']; ?>  </p>
                    </div>

                    <div class="pb-5">
                        <h3><?php echo $questionData['title']; ?></h3>
                    </div>

                    <div class="d-flex flex-row justify-content-end">
                        <div class="container-flex justify-content-center">
                            <button type="button" class="btn btn-warning my-2 shadow-none myBtnHover"> <?php echo $lang["add_answear"]  ?> </button>
                        </div>
                    </div>

                    <!-- ANSWEARS -->
                    <div class="py-5">
                        <?php if (count($getAnswears) === 0) : ?>
                            <p>Nie dodano jeszcze odpowiedzi / No answers yet </p>
                        <?php else : ?>   
                            <?php for($i=0; $i < count($getAnswears); $i++) : ?>
                                <section class="container-fluid text-center p-0 my-2" id=<?php echo $i ?> >
                                    <div class="d-flex flex-row bg-light rounded p-2">
                                        <p> 
                                            <?php echo $getAnswears[$i]['answer_text']; ?>
                                        </p>
                                    </div>
                                </section>
                            <?php endfor; ?>
                        <?php endif; ?>
                    </div>

                    <!-- NUMERIC PAGE NAVIGATION -->
                    <div class="d-flex flex-row justify-content-center">
                        <?php if ($pageNavigationNumberForAnswears > 1) : ?>
                            <?php for($i=0; $i < $pageNavigationNumberForAnswears; $i++) : ?>
                                    <a href=<?php echo ($i === 0) ? $getPathToNavigation : $getPathToNavigation.'&page='.($i+1) ?> >
                                        <div class="p-4"> <?php echo ($i+1); ?> </div>
                                    </a>
                            <?php endfor; ?>
                        <?php endif; ?>
                    </div>

                    <!-- ADD QUESTION FORM -->
                    <div class="py-5" id="add-answear-div">
                        <form action="" method="post">
                            <textarea type="text" name="answear-textarea" class="form-control form-control-lg" placeholder="Add answear"></textarea>
                            <div class="d-flex flex-row justify-content-end">
                                <div class="container-flex justify-content-center pt-3">
                                    <button type="submit" name="add-answear-button" id="add-answear-button" class="btn btn-warning my-2 shadow-none myBtnHover"> <?php echo $lang["add_answear"]  ?> </button>
                                </div>
                            </div>
                            <div class="text-center py-4">
                                <p id="add-answear-info">
                                </p>
                            </div>
                        </form>
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


