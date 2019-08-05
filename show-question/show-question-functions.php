<?php
    include "../functions.php";

    class DisplayAnswearsData extends AnswearsData {
        public $answearsNumOnPage = 10;
        private $pageNumber;
        private $from;
        private $to;

        private function setAnswearsNumber() {   
            if(isset($_GET['page'])) {
                $this->pageNumber = $_GET['page'];
            }

            if (!empty($this->pageNumber)) {
                $this->from = $this->answearsNumOnPage*(($this->pageNumber)-1);
                $this->to = $this->answearsNumOnPage;
            } else {
                $this->from = 0;
                $this->to = $this->answearsNumOnPage;
            }  
        }

        public function pageNavigationNumber() {
            $this->setAnswearsNumber();
            $getAllAnswearNumber = $this->answearRowsNum($_GET['id']-1);
            $pageNavigationNumber = ceil($getAllAnswearNumber/$this->answearsNumOnPage);
            return $pageNavigationNumber;
        }

        public function getAnswears() {
            $getId = $_GET['id']-1;
            $getAnswears = $this->getAnswearData('answer_text', $getId, $this->from, $this->to);
            $setAnswearsNumber = count($getAnswears);
            
            if ( $setAnswearsNumber < $this->answearsNumOnPage) {
                $this->answearsNumOnPage = $setAnswearsNumber;
            }
            return $getAnswears;
        }

    }

    $displayAnswearsData = new DisplayAnswearsData();
    $getId = $_GET['id']-1;
    $pageNavigationNumberForAnswears = $displayAnswearsData->pageNavigationNumber();
    $getAnswears = $displayAnswearsData->getAnswears();
    $questionData = $displayQuestionsData->questionDataOnAnswearPage(($getId+1));
    $answearsNumber = $answearsData->answearRowsNum($getId);
    

    if (isset($_POST["add-answear-button"])) {
        $answear = $_POST['answear-textarea'];
        if(!empty($answear)) {
            $answearsData->putAnswearData($getId, $answear);
            $url = $_SERVER['REQUEST_URI'];
            //$scrollTo = '#'.($setAnswearsNumber-1); 
            //$scroll = $setAnswearsNumber;
            header("Refresh:2; url=$url");
            // header("Refresh:2; url=$url?scroll=$scroll");
            // header("Refresh:2; url=$url$scrollTo");
        }
        
    }

?>