<?php
    for($i=0; $i < $displayQuestionsData->questionsNumOnPage; $i++) {
        if(isset($_POST[$questionData[$i]['id'].'_x'])){
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
                $user = $_SESSION['username'];
                $toQuestion = $questionData[$i]['id'];
                $questionsData->addToFavourites($user, $toQuestion);
            } else {    
                echo 'Log in first!';
            }
        }
    }
?>       
       
        <!-- MAIN  -->
        <div class="container-fluid">
            <div class="row justify-content-center my-4 mx-0">
                <!-- LEFT COL -->
                <main class="col-md-7 col-xl-6">

                    <div class="d-flex flex-row justify-content-end">
                        <div class="container-flex justify-content-center">
                            <a href=<?php echo $loadSite->loadSite('add-question') ?> >
                                <button type="button" class="btn btn-warning my-2 shadow-none myBtnHover"> <?php echo $lang["add_question"]  ?> </button>
                            </a>
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

                    <?php if ($displayQuestionsData->questionsNumOnPage === 0) : ?>
                        <p>Nie dodano jeszcze pytań/ No questions yet </p>
                    <?php else : ?>   
                        <?php for($i=0; $i < $displayQuestionsData->questionsNumOnPage; $i++) : ?>
                        <section class="container-fluid border border-warning rounded text-center p-0 my-2">
                            <div class="d-flex flex-row">
                                <div class="d-flex flex-column justify-content-center px-2">
                                    <div class="h-100 pb-2 d-flex flex-column justify-content-end">
                                        <div><?php echo $questionData[$i]['votes']; ?></div>
                                        <div><img src="img/arr-up.svg" class="p-2" alt="up-icon red "></div>
                                    </div>
                                    <div class="h-100 pt-2">
                                        <!-- <div><img src="img/arr-down.svg" class="p-2" alt="down-icon blue"></div>
                                        <div>0</div> -->
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="container-fluid bg-gradient-warning border-bottom border-warning py-1 h4"> <?php echo $questionData[$i]['category']; ?> </div>
                                    <h2 class="p-2 h5 text-left"><?php echo $questionData[$i]['title']; ?></h2>
                                    <div class="d-flex flex-row justify-content-center align-items-center">
                                        <img src="img/arr-down-b.svg" class="h-100" alt="arr-down-icon"> 
                                        <a href=<?php echo $loadSite->loadSite('show-question').'?id='.$questionData[$i]['id'] ?> >
                                            <div class="p-2 h6 lead text-body">Odpowiedzi: <?php echo $questionData[$i]['answears']; ?></div>
                                        </a>
                                    </div>
                                </div>
                                <div class="d-flex flex-column align-items-start py-2 px-3">
                                    <span data-toggle="tooltip" title="<?php echo $lang["add_to_favourites"] ?>" data-placement="bottom">
                                        <form action="" method="post">
                                            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) : ?>
                                                <input type="image" src=<?php echo $displayQuestionsData->isAddedToFavourites($_SESSION['username'], $questionData[$i]['id']) ? "img/heart-f.svg" : "img/heart-e.svg" ?> alt="heart-icon" id="heart-icon" class="add-to-favourites-img" name=<?php echo $questionData[$i]['id']; ?> >
                                            <?php else: ?>
                                                <input type="image" src="img/heart-e.svg" alt="heart-icon" id="heart-icon" class="add-to-favourites-img" name=<?php echo $questionData[$i]['id']; ?> >
                                            <?php endif; ?>
                                        </form>
                                    </span>
                                </div>
                            </div>
                        </section>
                        <?php endfor; ?>
                    <?php endif; ?>
                    <!-- END QUESTIONS FROM DATABASE -->

                    <!-- NUMERIC PAGE NAVIGATION -->
                    <div class="d-flex flex-row justify-content-center">
                        <?php if ($pageNavigationNumberForQuestions > 1) : ?>
                            <?php for($i=0; $i < $pageNavigationNumberForQuestions; $i++) : ?>
                                    <a href=<?php echo ($i === 0) ? $getPathToNavigation : $getPathToNavigation.'?page='.($i+1) ?> >
                                        <div class="p-4"> <?php echo ($i+1); ?> </div>
                                    </a>
                            <?php endfor; ?>
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