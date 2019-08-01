<?php

    //define('connection', 'mysqli_connect("localhost", "Aska", "myPass33", "recruiment_questions")');

    class Language {
        // get language from browser and set language depent on if statement
        private function setLanguage($getLang) {
            $lang = 'en';
            if (strpos($getLang, 'pl') !== false) {
                $lang = 'pl';
            }
            return $lang;
        }

        // get first part of url and check if it's equal to substing
        public function startsWith($string, $substring) { 
            // expolode() - split a string and make array from it
            return (explode('/', $string)[1] === $substring);
        } 

        // redirect page to location with setted language
        public function redirect($getURI) {
            $getLang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];   // en-US,en;q=0.9
            if (!$this->startsWith($getURI, 'pl') && !$this->startsWith($getURI, 'en')) {         // if static word before function is used, then can't use $this-> , must use Language::
                header('Location: '.$this->setLanguage($getLang).'/');
            } 
        }

        public function setSessionLanguage($getURI) {
            session_start();
            if ( $this->startsWith($getURI, 'pl' ) ) {
                $_SESSION['lang'] = 'pl';
            } else {
                $_SESSION['lang'] = 'en';
            }
            return $_SESSION['lang'];
        }
    }

    $language = new Language();
    $language->redirect($_SERVER['REQUEST_URI']);
    $lang = $language->setSessionLanguage($_SERVER['REQUEST_URI']);

    require_once 'languages/'. $lang . ".php";




    class Data {
        private $numRows;
        private function connectToDatabase($query) {
            $mysqli = new mysqli("localhost", "Aska", "myPass33", "recruiment_questions");
            if ($mysqli->connect_error) {
                die('Connect Error (' . $mysqli->connect_errno . ') '. $mysqli->connect_error);
            } else {
                // $mysqli->query() - for OOP , mysqli_connect() - for procedural programing
                $result = $mysqli->query($query);
                mysqli_close($mysqli);
                if(!$result) {
                    die('Query failed');
                }
                return $result;
            }
        }

        public function getQuestionData($columnName, $from=0, $to=9) {
            $lang = $_SESSION['lang'];
            $query = "SELECT $columnName from questions_$lang LIMIT $from, $to;";
            //$questionsData = new Data();
            // $colName = $this->getQuestion($query, $columnName) ;
            // return $colName;
            $questionsArr = [];
            $result = $this->connectToDatabase($query);
            if(mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_array($result)){
                    array_push($questionsArr, $row[$columnName]);
                }
            }
            return $questionsArr;
        }

        public function putQuestionData($category, $title) {
            $lang = $_SESSION['lang'];
            $date=date("Y-m-d");
            //$query = "INSERT INTO questions_$lang SET category = $category, title = $title, answears = 0, author = 'anonim', date = $date, favourites = false, votes = 0";
            $query = "INSERT INTO questions_$lang SET category = '$category', title = '$title', answears = 0, author = 'anonim', date = $date, favourites = false, votes = 0";
            $this->connectToDatabase($query);
        }

        public function numRows($from=0, $to=9) {
            $lang = $_SESSION['lang'];
            $rowsNumberQuery = "SELECT id from questions_$lang LIMIT $from, $to;";
            //$questionsNumbers = new Data();
            $numRows = mysqli_num_rows($this->connectToDatabase($rowsNumberQuery));
            return $numRows;
        }
    }

    $getData = new Data();




    class LoadSites {
        private $loadSite;

        private function setSite($site) {
            $lang = $_SESSION['lang'];
            if ($site = "add-question") {
                if ($lang = 'pl') {
                    $this->loadSite = "dodaj-pytanie";
                } else {
                    $this->loadSite = "add-question";
                }
            } else if ($site = "show-question") {
                if ($lang = 'pl') {
                    $this->loadSite = "dodaj-pytanie";
                } else {
                    $this->loadSite = "add-question";
                }
            }
        }

        public function loadSite($site) {
            $this->setSite($site);
            return $this->loadSite;
        }

    }

    $loadSite = new LoadSites();

?>