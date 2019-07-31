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