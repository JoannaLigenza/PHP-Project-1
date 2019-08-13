<?php 
    if (isset($_POST['logout-button'])) {
        $actualLang = $_SESSION['lang'];
        session_unset();
        session_destroy();
        session_start();
        $_SESSION['lang'] = $actualLang;
        header('Location: /'.$_SESSION['lang']);
    }
    // echo "session lang: ".$_SESSION['lang']."<br>";
    // echo "logged in: ".$_SESSION['loggedin']
?>
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
                <a class="navbar-brand p-3" href=<?php echo '/'.$_SESSION['lang'] ?>>LOGO HERE</a>
                <div class="p-2 float-right">
                    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true ) :  ?>
                    <form action="" method="post">
                        <button type="submit" class="btn btn-outline-warning" name="logout-button"><?php echo "Log Out" ?></button>
                    </form>
                    <?php else: ?>
                    <form action="" method="post">
                        <a href=<?php echo '/'.$_SESSION['lang'].'/login/' ?>><button type="button" class="btn btn-outline-warning"><?php echo $lang["log_in"]  ?></button></a> 
                        <a href=<?php echo '/'.$_SESSION['lang'].'/signin/' ?>><button type="button" class="btn btn-warning mx-2 myBtnHover"><?php echo $lang["sign_up"]  ?></button></a> 
                    </form>
                    <?php endif; ?>
                </div>
            </div>

            
        </header>
        <!-- HEADER END -->