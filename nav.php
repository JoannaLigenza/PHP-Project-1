            <nav class="navbar navbar-expand-md navbar-light bg-light">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <form action="" method="post" class="navbar-nav justify-content-center text-center m-auto">
                        <button type="submit" name="display-all-categories" class=<?php echo (isset($_SESSION['category']) && $_SESSION['category'] === "all") ? "'btn my-2 shadow-none btn-warning myBtnHover'" : "'btn my-2 shadow-none'" ?>> <?php echo $displayLang["all"] ?> </button>
                        <button type="submit" name="display-html" class=<?php echo (isset($_SESSION['category']) && $_SESSION['category'] === "HTML") ? "'btn my-2 shadow-none btn-warning myBtnHover'" : "'btn my-2 shadow-none'" ?>>Html</button>
                        <button type="submit" name="display-css" class=<?php echo (isset($_SESSION['category']) && $_SESSION['category'] === "CSS") ? "'btn my-2 shadow-none btn-warning myBtnHover'" : "'btn my-2 shadow-none'" ?>>Css</button>
                        <button type="submit" name="display-javascript" class=<?php echo (isset($_SESSION['category']) && $_SESSION['category'] === "Javascript") ? "'btn my-2 shadow-none btn-warning myBtnHover'" : "'btn my-2 shadow-none'" ?>>Javascript</button>
                        <button type="submit" name="display-dev-tools" class=<?php echo (isset($_SESSION['category']) && ($_SESSION['category'] === "Developer tools") || $_SESSION['category'] === "Narzędzia programisty") ? "'btn my-2 shadow-none btn-warning myBtnHover'" : "'btn my-2 shadow-none'" ?>><?php echo $displayLang["developer-tools"] ?></button>
                        <button type="submit" name="display-mindset" class=<?php echo (isset($_SESSION['category']) && ($_SESSION['category'] === "Programmer mindset") || $_SESSION['category'] === "Mindset programisty") ? "'btn my-2 shadow-none btn-warning myBtnHover'" : "'btn my-2 shadow-none'" ?>><?php echo $displayLang["programmer-mindset"] ?></button>
                        <button type="submit" name="display-soft-skills" class=<?php echo (isset($_SESSION['category']) && ($_SESSION['category'] === "Soft skills" || $_SESSION['category'] === "Kompetencje miękkie")) ? "'btn my-2 shadow-none btn-warning myBtnHover'" : "'btn my-2 shadow-none'" ?>><?php echo $displayLang["soft-skills"] ?></button>
                    </form>
                </div>
            </nav>