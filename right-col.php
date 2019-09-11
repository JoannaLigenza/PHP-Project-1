<?php
    $questionsData = new QuestionsData();
    $answearsData = new AnswearsData();
    $userData = new UserData();
    // limit displaying latest questions and answears number
    $limit = 3;
    $latestQuestions = $questionsData->getNewestQuestions($limit);
    $latestAnswears = $answearsData->getNewestAnswears($limit);
    $mostAddedQuestions =  $userData->getMostAdded("questions", $limit);
    $mostAddedAnswears =  $userData->getMostAdded("answears", $limit);

    //print_r($latestAnswears);
?>
<div class="pb-5">
    <div class="bg-warning p-2 rounded mb-3"><?php echo $displayLang['statistics']; ?></div>
    <div class="mb-5">
        <div><h3 class="border-left border-warning px-2 font-weight-bold h6-size"><?php echo $displayLang['last_added_questions']; ?></h3></div>
        <?php for ($i=0; $i < $limit; $i++) : ?>
            <div class="px-2">
                <?php 
                echo "<div><img src='/img/arr-right.svg' alt='arrow-right-icon' class='mr-2'>";
                echo "<a href='profile/?profile=".$latestAnswears[$i]['author']."'>".$latestAnswears[$i]['author']."</a> - ";
                echo $latestQuestions[$i]['title']."</div>";
                ?>
            </div>
        <?php endfor; ?>
    </div>

    <div class="mb-5">
        <div><h3 class="border-left border-warning px-2 font-weight-bold h6-size"><?php echo $displayLang['last_added_answears']; ?></h3></div>
        <?php for ($i=0; $i < $limit; $i++) : ?>
            <div class="px-2">
                <?php 
                echo "<div><img src='/img/arr-right.svg' alt='arrow-right-icon' class='mr-2'>";
                echo "<a href='profile/?profile=".$latestAnswears[$i]['author']."'>".$latestAnswears[$i]['author']."</a> - ";
                echo $latestAnswears[$i]['answear_text']."</div>";
                ?>
            </div>
        <?php endfor; ?>
    </div>

    <div class="mb-5">
        <div><h3 class="border-left border-warning px-2 font-weight-bold h6-size"><?php echo $displayLang['most_questions_added_by']; ?></h3></div>
        <?php for ($i=0; $i < $limit; $i++) : ?>
            <div class="px-2">
                <?php 
                echo "<div><img src='/img/arr-right.svg' alt='arrow-right-icon' class='mr-2'>";
                echo "<a href='profile/?profile=".$mostAddedQuestions[$i]['username']."'>".$mostAddedQuestions[$i]['username']."</a> - ".$mostAddedQuestions[$i]['added_questions']."</div>";
                ?>
            </div>
        <?php endfor; ?>
    </div>

    <div class="mb-5">
        <div><h3 class="border-left border-warning px-2 font-weight-bold h6-size"><?php echo $displayLang['most_answears_added_by']; ?></h3></div>
        <?php for ($i=0; $i < $limit; $i++) : ?>
            <div class="px-2">
                <?php 
                echo "<div><img src='/img/arr-right.svg' alt='arrow-right-icon' class='mr-2'>";
                echo "<a href='profile/?profile=".$mostAddedAnswears[$i]['username']."'>".$mostAddedAnswears[$i]['username']."</a> - ".$mostAddedAnswears[$i]['added_answears']."</div>";
                ?>
            </div>
        <?php endfor; ?>
    </div>

    <div class="bg-warning p-2 rounded mb-3"><?php echo $displayLang['developer-tools'].":"; ?></div>
    <div><h3 class="border-left border-warning px-2 font-weight-bold h6-size"><?php echo $displayLang['download_basic_git_command']; ?></h3></div>
    <a href="/basic_git_command_pl.pdf" target="_blank"> <img src="../img/pdf.svg" alt="download-pdf-icon"> </a>
</div>