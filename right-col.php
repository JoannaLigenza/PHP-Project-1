<?php
    $questionsData = new QuestionsData();
    $answersData = new answersData();
    $userData = new UserData();
    $loadSite = new LoadSites();
    // limit displaying latest questions and answers number
    $limit = 3;
    $latestQuestions = $questionsData->getNewestQuestions($limit);
    $latestanswers = $answersData->getNewestanswers($limit);
    $mostAddedQuestions =  $userData->getMostAdded("questions", $limit);
    $mostAddedanswers =  $userData->getMostAdded("answers", $limit);

    //print_r($latestanswers);
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
    <!-- last added answers -->
    <div class="mb-5">
        <div><h3 class="border-left border-warning px-2 font-weight-bold h6-size"><?php echo $displayLang['last_added_answers']; ?></h3></div>
        <?php for ($i=0; $i < $limit; $i++) : ?>
            <div class="px-2">
                <?php 
                $answerText = $latestanswers[$i]['answer_text'];
                $goToanswer = '/'.$latestanswers[$i]['lang']."/".$loadSite->loadSite("show-question").'?id='.$latestanswers[$i]['to_question'];
                $pathToProfile = "/".$_SESSION['lang']."/profile/?profile=".$latestanswers[$i]['author'];
                echo "<div><img src='/img/arr-right.svg' alt='arrow-right-icon' class='mr-2'>";
                echo "<a href=".$pathToProfile.">".$latestanswers[$i]['author']."</a> - ";
                if(strlen($answerText)>20) {
                    $answerText="<a href='$goToanswer' style='color:black'>".substr($answerText,0,80)."</a>";
                    echo $answerText." ...</div>";
                } else {
                    echo "<a href='$goToanswer' style='color:black'>".$answerText."</a></div>";
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
    <!-- most answers added by -->
    <div class="mb-5">
        <div><h3 class="border-left border-warning px-2 font-weight-bold h6-size"><?php echo $displayLang['most_answers_added_by']; ?></h3></div>
        <?php for ($i=0; $i < $limit; $i++) : ?>
            <div class="px-2">
                <?php 
                $pathToProfile = "/".$_SESSION['lang']."/profile/?profile=".$mostAddedanswers[$i]['username'];
                echo "<div><img src='/img/arr-right.svg' alt='arrow-right-icon' class='mr-2'>";
                echo "<a href=".$pathToProfile.">".$mostAddedanswers[$i]['username']."</a> - ".$mostAddedanswers[$i]['added_answers']."</div>";
                ?>
            </div>
        <?php endfor; ?>
    </div>
    <!-- download pdf git commands -->
    <div class="bg-warning p-2 rounded mb-3"><?php echo $displayLang['developer-tools'].":"; ?></div>
    <div><h3 class="border-left border-warning px-2 font-weight-bold h6-size"><?php echo $displayLang['download_basic_git_command']; ?></h3></div>
    <a href=<?php echo "/basic_git_commands_".$_SESSION['lang'].".pdf" ?>  target="_blank"> <img src="../img/pdf.svg" alt="download-pdf-icon"> </a>
</div>