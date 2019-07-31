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
        //private $result = "";

        public function query($query) {
            $mysqli = new mysqli("localhost", "Aska", "myPass33", "recruiment_questions");
            if ($mysqli->connect_error) {
                //die("Database connection failed");
                die('Connect Error (' . $mysqli->connect_errno . ') '. $mysqli->connect_error);
            } else {
                // $mysqli->query() - for OOP , mysqli_connect() - for procedural programing
                $result = $mysqli->query($query);
                //echo "dziala";
                $this->result = $result;
                mysqli_close($mysqli);
                return $result;
            }
        }

        function getQuestion($query, $column) {
            $questionsArr = [];
            if(mysqli_num_rows($this->query($query)) > 0){
                while($row = mysqli_fetch_array($this->result)){
                        //echo $row[$column];
                    array_push($questionsArr, $row[$column]);
                }
            }
            return $questionsArr;
        }
    }

    

    class Variables {
        private $numRows = 0;
        private $title = "title";
        private $category = "category";

        function setVariable($columnName) {
            $selectQuery = 'SELECT * from questions_pl';
            $questions = new Data();
            if ($this->numRows === 0) {
                $numRows = mysqli_num_rows($questions->query($selectQuery));
                $this->numRows = $numRows;
            }
            $colName = $questions->getQuestion($selectQuery, $columnName)[0] ;
            $this->$columnName = $colName;
            return $this->$columnName;
        }

        // function returnVariable($var) {
        //     return $this->$var;
        // }
    }

    $variables = new Variables();
    //echo $variables->setVariable('title');
    

?>