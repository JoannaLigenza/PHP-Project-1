<?php
    include "../functions.php";
    $getId = $_GET['id']-1;
    $pageNavigationNumberForAnswears = $displayAnswearsData->pageNavigationNumber();
    $getAnswears = $displayAnswearsData->getAnswears();
    $questionData = $displayQuestionsData->questionDataOnAnswearPage(($getId+1));
    //$answearsNumber = $answearsData->answearRowsNum($getId);
    

    if (isset($_POST['add-answear-button'])) {
        $answear = $_POST['answear-textarea'];
        if(!empty($answear)) {
            $displayAnswearsData->addAnswear($getId, $answear);
            $displayQuestionsData->setAnswearsNumber(($getId+1), '+');
            $url = $_SERVER['REQUEST_URI'];
            //$scrollTo = '#'.($setAnswearsNumber-1); 
            //$scroll = $setAnswearsNumber;
            header("Refresh:2; url=$url");
            // header("Refresh:2; url=$url?scroll=$scroll");
            // header("Refresh:2; url=$url$scrollTo");
        }
        
    }

?>