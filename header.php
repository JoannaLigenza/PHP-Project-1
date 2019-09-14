<?php 
    if (isset($_POST['logout-button'])) {
        $actualLang = $_SESSION['lang'];
        session_unset();
        session_destroy();
        session_start();
        session_regenerate_id();
        $_SESSION['lang'] = $actualLang;
        $_SESSION['title'] = $displayLang["profile_site_title"];
        $_SESSION['description'] = $displayLang["profile_site_desc"];
        //echo "session new id ". session_id();
        header('Location: /'.$_SESSION['lang']);
    }

    if (!isset($_SESSION['index'])) {
        $_SESSION['index'] = "index";
    }
    //print_r($_COOKIE);
    $title = $_SESSION['title'];
    $description = $_SESSION['description'];
?>
<!DOCTYPE html>
<html lang=<?php echo $_SESSION['lang'] ?>>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php if ($_SESSION['index'] === "noindex") : ?>
    <meta name="robots" content="noindex" />
    <?php endif; ?>
    <title><?php echo "$title"  ?></title>
    <meta name="description" content=<?php echo "'$description'" ?> >
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body class="min-vh-100">
    <div class="m-0 p-0 h-100">
        <!-- HEADER -->
        <header>
            <div class="d-flex flex-sm-row justify-content-between border-bottom border-warning">
                <div class="d-flex flex-column justify-content-center pl-2">
                    <a class="navbar p-0" href=<?php echo '/'.$_SESSION['lang'] ?>>
                        <picture>
                                <source media="(max-width: 400px)" srcset="/img/logo-80x40.jpg">
                                <img src="/img/logo-100x50.jpg" style="width:auto" alt="logo">
                        </picture>
                    </a>
                </div>
                
                <div class="p-2 right">
                    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true ) :  
                        $pathToProfile = '/'.$_SESSION['lang'].'/profile/?profile='.$_SESSION['username'];
                    ?>
                    <form action="" method="post">
                        <a href=<?php echo $pathToProfile; ?> class="btn btn-outline-warning"><?php echo $displayLang["profil"] ?></a>
                        <button type="submit" class="btn btn-warning myBtnHover" name="logout-button" id="logout-button"><?php echo $displayLang["log_out"] ?></button>
                    </form>
                    <?php else: ?>
                    <form action="" method="post">
                        <a href=<?php echo '/'.$_SESSION['lang'].'/login/' ?>><button type="button" class="btn btn-outline-warning"><?php echo $displayLang["log_in"]  ?></button></a> 
                        <a href=<?php echo '/'.$_SESSION['lang'].'/signin/' ?>><button type="button" class="btn btn-warning mx-2 myBtnHover"><?php echo $displayLang["sign_up"]  ?></button></a> 
                    </form>
                    <?php endif; ?>
                </div>
            </div>

            
        </header>
        <!-- HEADER END -->