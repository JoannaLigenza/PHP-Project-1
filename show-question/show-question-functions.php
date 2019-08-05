<?php
    include "../functions.php";

    class DisplayAnswearsData extends AnswearsData {
        public $answearsNumOnPage = 10;
        private $pageNumber;
        private $from;
        private $to;

        public function answearsNumber() {   
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

        public function getAnswears() {
            $getId = $_GET['id']-1;
            $getAnswears = $this->getAnswearData('answer_text', $getId, $this->from, $this->to);
            $answearsNumber = count($getAnswears);
            
            if ( $answearsNumber < $this->answearsNumOnPage) {
                $this->answearsNumOnPage = $answearsNumber;
            }
            return $getAnswears;
        }

        public function pageNavigationNumber() {
            $this->answearsNumber();
            $getAllAnswearNumber = $this->answearRowsNum($_GET['id']-1);
            $pageNavigationNumber = ceil($getAllAnswearNumber/$this->answearsNumOnPage);
            return $pageNavigationNumber;
        }
    }

    $displayAnswearsData = new DisplayAnswearsData();
    $getId = $_GET['id']-1;
    $pageNavigationNumberForAnswears = $displayAnswearsData->pageNavigationNumber();
    

    if (isset($_POST["add-answear-button"])) {
        $answear = $_POST['answear-textarea'];
        if(!empty($answear)) {
            $answearsData->putAnswearData($getId, $answear);
            $url = $_SERVER['REQUEST_URI'];
            //$scrollTo = '#'.($answearsNumber-1); 
            $scroll = $answearsNumber;
            header("Refresh:2; url=$url");
            // header("Refresh:2; url=$url?scroll=$scroll");
            // header("Refresh:2; url=$url$scrollTo");
        }
        
    }

?>