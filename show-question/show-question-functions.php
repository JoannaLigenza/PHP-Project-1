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
            $getAllAnswearNumber = $this->answearRowsNum($_GET['id']-1);
            $pageNavigationNumber = ceil($getAllAnswearNumber/10);
            return $pageNavigationNumber;
        }
        

        public function getPath() {
            $mainPath = explode("?", $_SERVER['REQUEST_URI']);
            return $mainPath[0].'?id='.($_GET['id']);
        }

    }

    $displayAnswearsData = new DisplayAnswearsData();
    $getId = $_GET['id']-1;
    $displayAnswearsData->answearsNumber();

    // $getId = $_GET['id']-1;
    // $answearsNumOnPage = 10;
    // $pageNumber;
    // $from;
    // $to;
    // if(isset($_GET['page'])) {
    //     $pageNumber = $_GET['page'];
    // }

    // if (!empty($pageNumber)) {
    //     $from = $answearsNumOnPage*($pageNumber-1);
    //     $to = $answearsNumOnPage;
    // } else {
    //     $from = 0;
    //     $to = $answearsNumOnPage;
    // }

    // $getAnswears = $answearsData->getAnswearData('answer_text', $getId, $from, $to);
    // $answearsNumber = count($getAnswears);
    
    // if ( $answearsNumber < $answearsNumOnPage) {
    //     $answearsNumOnPage = $answearsNumber;
    // }

    // $getAllAnswearNumber = $answearsData->answearRowsNum($getId);
    // $pageNavigationNumber = ceil($getAllAnswearNumber/10);

    // function getPath() {
    //     $mainPath = explode("?", $_SERVER['REQUEST_URI']);
    //     return $mainPath[0].'?id='.($_GET['id']);
    // }
    

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
            unset($_POST['answear-textarea']);
        }
        
    }

?>