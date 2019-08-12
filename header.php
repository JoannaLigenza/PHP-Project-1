<!DOCTYPE html>
<html lang=<?php echo $_SESSION['lang'] ?>>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $lang["site_title"]  ?></title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/style.css">
    <script src="/js/script.js"></script>
</head>
<body class="min-vh-100">
    <div class="container-fluid m-0 p-0">
        <!-- HEADER -->
        <header>
            <div class="d-flex flex-column flex-sm-row justify-content-between border-bottom border-warning">
                <a class="navbar-brand p-3" href="/">LOGO HERE</a>
                <div class="p-2 float-right">
                    <button type="button" class="btn btn-outline-warning"><?php echo $lang["log_in"]  ?></button>
                    <button type="button" class="btn btn-warning mx-2 myBtnHover"><?php echo $lang["sign_up"]  ?></button>
                </div>
            </div>

            
        </header>
        <!-- HEADER END -->