<?php
    include "functions.php";
    include "functions-run.php";
    $_SESSION['title'] = $displayLang["site_title"];
    $_SESSION['description'] = $displayLang["site_desc"];
    $_SESSION['index'] = "index";

    include "header.php";
    include "nav.php";
    include "main.php";
    include "footer.php";
?>