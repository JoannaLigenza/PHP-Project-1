<?php
    include "../functions.php";

    include "../header.php";
?>
        <!-- MAIN  -->
        <div class="container-fluid">
            <div class="row justify-content-center my-4 mx-0">
                <!-- LEFT COL -->
                <main class="col-md-7 col-xl-6">
                    <div>
                        <h3><?php echo $getData->getQuestionData('title')[0]; ?></h3>
                    </div>
                    <div class="d-flex flex-row justify-content-end">
                        <div class="container-flex justify-content-center">
                            <button type="button" class="btn btn-warning my-2 shadow-none myBtnHover"> <?php echo $lang["add_answear"]  ?> </button>
                        </div>
                    </div>
                    <div>
                        odpowiedzi w petli ddd <br><br>
                        <section class="container-fluid border border-warning rounded text-center p-0 my-2">
                            <div class="d-flex flex-row">
                                sada
                            </div>
                        </section>
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

<!-- <div><?php echo "blablbalba" ?></div>
<div><?php echo $_SESSION['lang'] ?></div>
<div><?php echo $lang["add_to_favourites"] ?></div> -->
        
<?php include "../footer.php"; ?>


