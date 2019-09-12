<?php
    $questionsData = new QuestionsData();
    $answearsData = new AnswearsData();
    $userData = new UserData();
    $loadSite = new LoadSites();
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
    <!-- last added questions -->
    <div class="mb-5">
        <div><h3 class="border-left border-warning px-2 font-weight-bold h6-size"><?php echo $displayLang['last_added_questions']; ?></h3></div>
        <?php for ($i=0; $i < $limit; $i++) : ?>
            <div class="px-2">
                <?php 
                $goToQuestion = '/'.$latestQuestions[$i]['lang']."/".$loadSite->loadSite("show-question").'/?id='.$latestQuestions[$i]['id'];
                $pathToProfile = "/".$_SESSION['lang']."/profile/?profile=".$latestQuestions[$i]['author'];
                echo "<div><img src='/img/arr-right.svg' alt='arrow-right-icon' class='mr-2'>";
                echo "<a href=".$pathToProfile.">".$latestQuestions[$i]['author']."</a> - ";
                echo "<a href='$goToQuestion' style='color:black'>".$latestQuestions[$i]['title']."</a></div>";
                ?>
            </div>
        <?php endfor; ?>
    </div>
    <!-- last added answears -->
    <div class="mb-5">
        <div><h3 class="border-left border-warning px-2 font-weight-bold h6-size"><?php echo $displayLang['last_added_answears']; ?></h3></div>
        <?php for ($i=0; $i < $limit; $i++) : ?>
            <div class="px-2">
                <?php 
                $answearText = $latestAnswears[$i]['answear_text'];
                $goToAnswear = '/'.$latestAnswears[$i]['lang']."/".$loadSite->loadSite("show-question").'?id='.$latestAnswears[$i]['to_question'];
                $pathToProfile = "/".$_SESSION['lang']."/profile/?profile=".$latestAnswears[$i]['author'];
                echo "<div><img src='/img/arr-right.svg' alt='arrow-right-icon' class='mr-2'>";
                echo "<a href=".$pathToProfile.">".$latestAnswears[$i]['author']."</a> - ";
                if(strlen($answearText)>20) {
                    $answearText="<a href='$goToAnswear' style='color:black'>".substr($answearText,0,80)."</a>";
                    echo $answearText." ...</div>";
                } else {
                    echo "<a href='$goToAnswear' style='color:black'>".$answearText."</a></div>";
                }
                ?>
            </div>
        <?php endfor; ?>
    </div>
    <!-- most questions added by -->
    <div class="mb-5">
        <div><h3 class="border-left border-warning px-2 font-weight-bold h6-size"><?php echo $displayLang['most_questions_added_by']; ?></h3></div>
        <?php for ($i=0; $i < $limit; $i++) : ?>
            <div class="px-2">
                <?php 
                $pathToProfile = "/".$_SESSION['lang']."/profile/?profile=".$mostAddedQuestions[$i]['username'];
                echo "<div><img src='/img/arr-right.svg' alt='arrow-right-icon' class='mr-2'>";
                echo "<a href=".$pathToProfile.">".$mostAddedQuestions[$i]['username']."</a> - ".$mostAddedQuestions[$i]['added_questions']."</div>";
                ?>
            </div>
        <?php endfor; ?>
    </div>
    <!-- most answears added by -->
    <div class="mb-5">
        <div><h3 class="border-left border-warning px-2 font-weight-bold h6-size"><?php echo $displayLang['most_answears_added_by']; ?></h3></div>
        <?php for ($i=0; $i < $limit; $i++) : ?>
            <div class="px-2">
                <?php 
                $pathToProfile = "/".$_SESSION['lang']."/profile/?profile=".$mostAddedAnswears[$i]['username'];
                echo "<div><img src='/img/arr-right.svg' alt='arrow-right-icon' class='mr-2'>";
                echo "<a href=".$pathToProfile.">".$mostAddedAnswears[$i]['username']."</a> - ".$mostAddedAnswears[$i]['added_answears']."</div>";
                ?>
            </div>
        <?php endfor; ?>
    </div>
    <!-- download pdf git commands -->
    <div class="bg-warning p-2 rounded mb-3"><?php echo $displayLang['developer-tools'].":"; ?></div>
    <div><h3 class="border-left border-warning px-2 font-weight-bold h6-size"><?php echo $displayLang['download_basic_git_command']; ?></h3></div>
    <a href=<?php echo "/basic_git_commands_".$_SESSION['lang'].".pdf" ?>  target="_blank"> <img src="../img/pdf.svg" alt="download-pdf-icon"> </a>
</div>