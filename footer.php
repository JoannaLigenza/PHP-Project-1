<?php
    $loadSites = new LoadSites();
    $mainDir = $_SESSION['main-dir'];
?>

    </div>

    <!-- FOOTER -->
    <footer class="container-fluid">
        <div class="container mt-3">
            <div class="row">
                <div class="col-6">
                    <a href='<?php echo $mainDir."/".$_SESSION['lang']."/".$loadSites->loadSite("contact") ?>' class="text-white">Kontakt</a>
                </div>
            </div>
            
        </div>

        <div class="right py-3">
            <form action="" method="post">
                <button name="set-en-lang" class="btn footer-btn"><?php echo $displayLang['english'] ?></button> | <button name="set-pl-lang" class="btn footer-btn"><?php echo $displayLang['polish'] ?></button>
            </form>
        </div>
    </footer>
    <!-- END FOOTER -->

    <!-- jQuery first, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>     
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src=<?php echo $mainDir."/js/script.js" ?>></script>

</body>
</html>