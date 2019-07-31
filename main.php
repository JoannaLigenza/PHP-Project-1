        <!-- MAIN  -->
        <div class="container-fluid">
            <div class="row justify-content-center my-4 mx-0">
                <!-- LEFT COL -->
                <main class="col-md-7 col-xl-6">

                    <div class="d-flex flex-row justify-content-end">
                        <div class="container-flex justify-content-center">
                            <button type="button" class="btn btn-warning my-2 shadow-none myBtnHover"> <?php echo $lang["add_question"]  ?> </button>
                        </div>
                    </div>

                    <div class="my-3">
                        <div class="d-flex flex-row justify-content-end">
                            <ul class="nav nav-tabs">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $lang["sort"].":"  ?></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="#">Najlepiej oceniane</a>
                                    <a class="dropdown-item" href="#">Z odpowiedziami</a>
                                    <a class="dropdown-item" href="#">Data dodania</a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- QUESTIONS FROM DATABASE -->
                    <section class="container-fluid border border-warning rounded text-center p-0 my-2">
                        <div class="d-flex flex-row">
                            <div class="d-flex flex-column justify-content-center px-2">
                                <div class="h-100 pb-2 d-flex flex-column justify-content-end">
                                    <div>0</div>
                                    <div><img src="img/arr-up.svg" class="p-2" alt="up-icon red "></div>
                                </div>
                                <div class="h-100 pt-2">
                                    <!-- <div><img src="img/arr-down.svg" class="p-2" alt="down-icon blue"></div>
                                    <div>0</div> -->
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="container-fluid bg-gradient-warning border-bottom border-warning py-1 h4"> HTML </div>
                                <h2 class="p-2 h5 text-left">Co to jest model pudełkowy oraz jakie znasz techniki clearfix i float fix</h2>
                                <div class="d-flex flex-row justify-content-center align-items-center">
                                    <img src="img/arr-down-b.svg" class="h-100" alt="heart-icon"> 
                                    <a href="#"><div class="p-2 h6 lead text-body">Odpowiedzi: 0</div></a>
                                    
                                </div>
                            </div>
                            <div class="d-flex flex-column align-items-start py-2 px-3">
                                <span data-toggle="tooltip" title="<?php echo $lang["add_to_favourites"] ?>" data-placement="bottom"><img src="img/heart-e.svg" alt="heart-icon"></span>
                            </div>
                        </div>
                    </section>


                    <?php for($i=0; $i < $variables->numRows(); $i++) : ?>
                    <section class="container-fluid border border-warning rounded text-center p-0 my-2">
                        <div class="d-flex flex-row">
                            <div class="d-flex flex-column justify-content-center px-2">
                                <div class="h-100 pb-2 d-flex flex-column justify-content-end">
                                    <div><?php echo $variables->getVariable('votes')[$i]; ?></div>
                                    <div><img src="img/arr-up.svg" class="p-2" alt="up-icon red "></div>
                                </div>
                                <div class="h-100 pt-2">
                                    <!-- <div><img src="img/arr-down.svg" class="p-2" alt="down-icon blue"></div>
                                    <div>0</div> -->
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="container-fluid bg-gradient-warning border-bottom border-warning py-1 h4"> <?php echo $variables->getVariable('category')[$i]; ?> </div>
                                <h2 class="p-2 h5 text-left"><?php echo $variables->getVariable('title')[$i]; ?></h2>
                                <div class="d-flex flex-row justify-content-center align-items-center">
                                    <img src="img/arr-down-b.svg" class="h-100" alt="heart-icon"> 
                                    <a href="#"><div class="p-2 h6 lead text-body">Odpowiedzi: <?php echo $variables->getVariable('answears')[$i]; ?></div></a>
                                    
                                </div>
                            </div>
                            <div class="d-flex flex-column align-items-start py-2 px-3">
                                <span data-toggle="tooltip" title="<?php echo $lang["add_to_favourites"] ?>" data-placement="bottom"><img src="img/heart-e.svg" alt="heart-icon"></span>
                            </div>
                        </div>
                    </section>
                    <?php endfor; ?>
                    <!-- END QUESTIONS FROM DATABASE -->

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