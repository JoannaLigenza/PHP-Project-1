<?php
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
        private $host = "localhost";
        private $dbUserName = "Aska";
        private $dbPass = "myPass33";
        private $dbName = "recruiment_questions";

        protected function connectionToDb() {
            $mysqli = new mysqli($this->host, $this->dbUserName, $this->dbPass , $this->dbName);
            return $mysqli;
        }
        // protected function connectToDatabase($query) {
        //     $mysqli = $this->connectionToDb();
        //     if ($mysqli->connect_error) {
        //         //die('Connect Error (' . $mysqli->connect_errno . ') '. $mysqli->connect_error);
        //         exit('Error connecting to database');
        //     } else {
        //         // $mysqli->query() - for OOP , mysqli_connect() - for procedural programing
        //         $result = $mysqli->query($query);
        //         mysqli_close($mysqli);
        //         if(!$result) {
        //             die('Query failed');
        //         }
        //         return $result;
        //     }
        // }
    }

    class QuestionsData extends Data {
        protected function getQuestionsData($from, $to) {
            settype($from, "integer");
            settype($to, "integer");
            $connection = $this->connectionToDb();
            $lang = $_SESSION['lang'];
            $stmt = $connection->prepare("SELECT * from questions_$lang LIMIT ?, ?;");
            if ($stmt) {
                $stmt->bind_param("ii", $from, $to);
                $questionsArr = [];
                if($stmt->execute() === TRUE) {
                   // $stmt->bind_result($result);
                    $result = $stmt->get_result();
                    while($row = $result->fetch_array(MYSQLI_ASSOC)) {
                        array_push($questionsArr, $row);
                    }
                } 
            }
            $stmt->close();
            mysqli_close($connection);
            return $questionsArr;
        }

        protected function putQuestionData($category, $title) {
            $connection = $this->connectionToDb();
            $lang = $_SESSION['lang'];
            $date = date("Y-m-d");
            // Convert special characters like < " > to HTML entities ( &lt; &quot; &gt;), so user cannot make script injections
            $category = htmlspecialchars($category, ENT_QUOTES);
            $title = htmlspecialchars($title, ENT_QUOTES);
            // Prepared Statement send query and the data to the database separatly, not as one query.
            $stmt = $connection->prepare("INSERT INTO questions_$lang SET category = ?, title = ?, answears = 0, author = 'anonim', date = ?, favourites = false, votes = 0;");
            if ($stmt) {
                $stmt->bind_param("sss", $category, $title, $date);
                if($stmt->execute()) {
                    $result = true;
                    echo 'Pytanie zostało dodane';
                } else {
                    $result = false;
                }
            }
            $stmt->close();
            mysqli_close($connection);
            return $result;
        }

        protected function questionRowsNum() {
            $connection = $this->connectionToDb();
            $lang = $_SESSION['lang'];
            $query = "SELECT id from questions_$lang";
            $result = $connection->query($query);
            $numRows = mysqli_num_rows($result);
            return $numRows;
        }

        protected function changeAnswearsNumber($id, $sign) {
            $connection = $this->connectionToDb();
            settype($id, "integer");
            $lang = $_SESSION['lang'];
            if ($sign === "+") {
                $query = "UPDATE questions_$lang SET answears = answears+1 WHERE id = ?";
            } else if ($sign === "-") {
                $query = "UPDATE questions_$lang SET answears = answears-1 WHERE id = ?";
            }
            $stmt = $connection->prepare($query);
            if ($stmt) {
                $stmt->bind_param("i", $id );
                if($stmt->execute()) {
                    $result = true;
                    echo 'Usunięto odpowiedź';
                } else {
                    $result = false;
                }
            }
            $stmt->close();
            mysqli_close($connection);
            return $result;
        }
    }

    $questionsData = new QuestionsData();

    class AnswearsData extends Data {
        protected function getAnswearsData($toQuestion, $from, $to) {
            $connection = $this->connectionToDb();
            settype($toQuestion, "integer");
            settype($from, "integer");
            settype($to, "integer");
            $lang = $_SESSION['lang'];
            $stmt = $connection->prepare("SELECT * from answears_$lang WHERE to_question LIKE $toQuestion LIMIT ?, ?;");
            if ($stmt) {
                $stmt->bind_param("ii", $from, $to);
                $answearsArr = [];
                if($stmt->execute()) {
                   // $stmt->bind_result($result);
                    $result = $stmt->get_result();
                    while($row = $result->fetch_array(MYSQLI_ASSOC)) {
                        array_push($answearsArr, $row);
                    }
                } 
            }
            $stmt->close();
            mysqli_close($connection);
            return $answearsArr;
        }

        protected function putAnswearData($toQuestion, $answear, $author="anonim", $link="" ) {
            $connection = $this->connectionToDb();
            settype($toQuestion, "integer");
            $answear = htmlspecialchars($answear, ENT_QUOTES);
            $link = htmlspecialchars($link, ENT_QUOTES);
            $lang = $_SESSION['lang'];
            $date=date("Y-m-d");
            $stmt = $connection->prepare("INSERT INTO answears_$lang SET to_question = ?, answear_text = ?, link = ?, author = ?, date = ?, votes_down = 0, votes_up = 0");
            if ($stmt) {
                $stmt->bind_param("issss", $toQuestion, $answear, $link, $author, $date);
                if($stmt->execute()) {
                    $result = true;
                    $url = $_SERVER['REQUEST_URI'];
                    header("Refresh:1.5; url=$url");
                } else {
                    $result = false;
                }
            }
            $stmt->close();
            mysqli_close($connection);
            return $result;
        }

        protected function removeAnswear($id) {
            $connection = $this->connectionToDb();
            settype($id, "integer");
            $lang = $_SESSION['lang'];
            $stmt = $connection->prepare("DELETE from answears_$lang where id = ?");
            if ($stmt) {
                $stmt->bind_param("i", $id);
                if($stmt->execute()) {
                    $result = true;
                    // $url = $_SERVER['REQUEST_URI'];
                    // header("Refresh:1.5; url=$url");
                } else {
                    $result = false;
                }
            }
            $stmt->close();
            mysqli_close($connection);
            return $result;
        }

        protected function answearRowsNum($toQuestion) {
            $connection = $this->connectionToDb();
            settype($toQuestion, "integer");
            $lang = $_SESSION['lang'];
            $stmt = $connection->prepare("SELECT id from answears_$lang WHERE to_question LIKE ?");
            if ($stmt) {
                $stmt->bind_param("i", $toQuestion);
                if($stmt->execute()) {
                    $stmt->store_result();
                    $numRows = $stmt->num_rows;
                } else {
                    $numRows = 0;
                }
            }
            $stmt->close();
            mysqli_close($connection);
            return $numRows;
        }
    }

    $answearsData = new AnswearsData();
    //$answearsData->deleteAnswear(71);


    class DisplayQuestionsData extends QuestionsData {
        public $questionsNumOnPage = 3;
        private $pageNumber;
        private $from;
        private $to;

        private function questionsNumber() {    
            if(isset($_GET['page'])) {
                $this->pageNumber = $_GET['page'];
            }
            if (!empty($this->pageNumber)) {
                $this->from = $this->questionsNumOnPage*(($this->pageNumber)-1);
                $this->to = $this->questionsNumOnPage;
            } else {
                $this->from = 0;
                $this->to = $this->questionsNumOnPage;
            }  
        }

        public function getQuestions() {
            $getQuestions = $this->getQuestionsData($this->from, $this->to);
            $questionsNumber = count($getQuestions);
            
            if ( $questionsNumber < $this->questionsNumOnPage) {
                $this->questionsNumOnPage = $questionsNumber;
            }
            return $getQuestions;
        }

        public function pageNavigationNumber() {
            $this->questionsNumber();
            $getAllQuestionsNumber = $this->questionRowsNum();
            $pageNavigationNumber = ceil($getAllQuestionsNumber/$this->questionsNumOnPage);
            return $pageNavigationNumber;
        }

        public function addQuestion($category, $title) {
            $this->putQuestionData($category, $title);
        }

        public function setAnswearsNumber($id, $sign) {
            $this->changeAnswearsNumber($id, $sign);
        }

        public function questionDataOnAnswearPage($id) {
            $connection = $this->connectionToDb();
            settype($id, "integer");
            $lang = $_SESSION['lang'];
            $stmt = $connection->prepare("SELECT * from questions_$lang WHERE id LIKE ?");
            if ($stmt) {
                $stmt->bind_param("i", $id);
                if($stmt->execute()) {
                    $result = $stmt->get_result();
                    $result = $result->fetch_array(MYSQLI_ASSOC);
                } 
            }
            $stmt->close();
            mysqli_close($connection);
            return $result;
        }
    }

    $displayQuestionsData = new DisplayQuestionsData();
    $pageNavigationNumberForQuestions = $displayQuestionsData->pageNavigationNumber();
    $questionData = $displayQuestionsData->getQuestions();

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
            $getAnswears = $this->getAnswearsData($getId, $this->from, $this->to);
            $setAnswearsNumber = count($getAnswears);
            
            if ( $setAnswearsNumber < $this->answearsNumOnPage) {
                $this->answearsNumOnPage = $setAnswearsNumber;
            }
            return $getAnswears;
        }

        public function addAnswear($toQuestion, $answear, $link="") {
            $res = $this->putAnswearData($toQuestion, $answear);
            return $res;
        }

        public function deleteAnswear($id) {
            $res = $this->removeAnswear($id);
            return $res;
        }
    }

    $displayAnswearsData = new DisplayAnswearsData();


    class LoadSites {
        private $loadSite;

        private function setSite($site) {
            $lang = $_SESSION['lang'];
            if ($site === "add-question") {
                if ($lang = 'pl') {
                    $this->loadSite = "dodaj-pytanie";
                } else {
                    $this->loadSite = "add-question";
                }
            } else if ($site === "show-question") {
                if ($lang = 'pl') {
                    $this->loadSite = "pytanie";
                } else {
                    $this->loadSite = "question";
                }
            }
        }

        public function loadSite($site) {
            $this->setSite($site);
            return $this->loadSite;
        }

    }

    $loadSite = new LoadSites();


    class Path {
        public function getPath() {
            $url = $_SERVER['REQUEST_URI'];
            $mainPath = $_SERVER['REQUEST_URI'];
            if (strpos($url, "id")) {
                $mainPath = explode("?", $mainPath);
                $mainPath = $mainPath[0].'?id='.($_GET['id']);
            } else {
                if (strpos($url, "?")) {
                    $mainPath = explode("?", $_SERVER['REQUEST_URI'])[0];
                }
            }
            
            return $mainPath;
        }
    }

    $path = new Path();
    $getPathToNavigation = $path->getPath();

?>