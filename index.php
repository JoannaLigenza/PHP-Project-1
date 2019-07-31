<?php
    include "functions.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $lang["site_title"]  ?></title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="container-fluid p-0">
        <!-- HEADER -->
        <header>
            <div class="d-flex flex-column flex-sm-row justify-content-between border-bottom border-warning">
                <a class="navbar-brand p-3" href="/">LOGO HERE</a>
                <div class="p-2 float-right">
                    <button type="button" class="btn btn-outline-warning"><?php echo $lang["log_in"]  ?></button>
                    <button type="button" class="btn btn-warning mx-2 myBtnHover"><?php echo $lang["sign_up"]  ?></button>
                </div>
            </div>

            <nav class="navbar navbar-expand-sm navbar-light bg-light">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav justify-content-center text-center m-auto">
                    <li class="nav-item nav-fill">
                        <a class="nav-link active" href="#"><?php echo $lang["all"]  ?> <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Html</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Css</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Javascript</a>
                    </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- HEADER END -->

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


    </div>

    <a href="/en/">Angielski</a> | <a href="/pl/">Polski</a>
    
    <!-- 
    <h1><?php echo $lang["title"]  ?></h1>
    <p><?php echo $lang['text']  ?></p> 
    <a href="/en/">Angielski</a> | <a href="/pl/">Polski</a>

    <!-- jQuery first, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <script> 
        // Switch tabs
        $('#myTab a').on('click', function (e) {
            e.preventDefault();
            $(this).tab('show');
        });

        // Show tooltip
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
</script>
</body>
</html>