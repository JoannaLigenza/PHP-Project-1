<?php
    ini_set('session.name', 'SESSION_ID');
    ini_set('session.cookie_httponly', 1 );
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
            return (explode('/', $string)[2] === $substring);
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

    // $language = new Language();
    // $language->redirect($_SERVER['REQUEST_URI']);
    // $chooseLang = $language->setSessionLanguage($_SERVER['REQUEST_URI']);

    // require_once 'languages/'. $chooseLang . ".php";


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


    class SetSession {
        public function setSessionParams($displayLang, $getPathToNavigation) {
            if (isset($_POST['display-all-categories'])) {
                $_SESSION['category'] = "all";
                header("Location: $getPathToNavigation");
            }
            if (isset($_POST['display-html'])) {
                $_SESSION['category'] = "HTML";
                header("Location: $getPathToNavigation");
            }
            if (isset($_POST['display-css'])) {
                $_SESSION['category'] = "CSS";
                header("Location: $getPathToNavigation");
            }
            if (isset($_POST['display-javascript'])) {
                $_SESSION['category'] = "Javascript";
                header("Location: $getPathToNavigation");
            }
            if (isset($_POST['display-dev-tools'])) {
               $_SESSION['category'] = $displayLang['developer-tools'];
               header("Location: $getPathToNavigation");
            }
            if (isset($_POST['display-mindset'])) {
               $_SESSION['category'] = $displayLang['programmer-mindset'];
               header("Location: $getPathToNavigation");
            }
            if (isset($_POST['display-soft-skills'])) {
               $_SESSION['category'] = $displayLang['soft-skills'];
               header("Location: $getPathToNavigation");
            }
            if (isset($_POST['adding-date'])) {
               $_SESSION['queston-sort'] = "date";
               header("Location: $getPathToNavigation");
            }
            if (isset($_POST['adding-date-newest'])) {
               $_SESSION['queston-sort'] = "date-newest";
               header("Location: $getPathToNavigation");
            }
            if (isset($_POST['most-answers'])) {
               $_SESSION['queston-sort'] = "answers";
               header("Location: $getPathToNavigation");
            }
            if (isset($_POST['top-rated'])) {
               $_SESSION['queston-sort'] = "votes";
               header("Location: $getPathToNavigation");
            }
            if (isset($_POST['answer-adding-date'])) {
               $_SESSION['answer-sort'] = "date";
               header("Location: $getPathToNavigation");
            }
            if (isset($_POST['answer-top-rated'])) {
               $_SESSION['answer-sort'] = "votes";
               header("Location: $getPathToNavigation");
            }
        }

        public function setSessionParamsInFoter($mainDir) {
            if (isset($_POST['set-en-lang'])) {
                $_SESSION['category'] = "all";
                header("Location: ".$mainDir."/en/");
            }

            if (isset($_POST['set-pl-lang'])) {
                $_SESSION['category'] = "all";
                header("Location: ".$mainDir."/pl/");
            }
        }
    }


    class Data {
        protected function connectionToDb() {
            include dirname(dirname(__DIR__))."/dbconnect.php";
            //include 'dbconnect.php';
            $mysqli = new mysqli($host, $dbUserName, $dbPass , $dbName);
            if ($mysqli->connect_error) {
                die();
                exit('Error connecting to database');
            }
            return $mysqli;
        }
    }

    class QuestionsData extends Data {
        protected function getQuestionsData($from, $to) {
            settype($from, "integer");
            settype($to, "integer");
            $questionsArr = [];
            $connection = $this->connectionToDb();
            $lang = $_SESSION['lang'];
            if (!isset($_SESSION['queston-sort']) || $_SESSION['queston-sort'] === "date") {
                $order = "date";
            } else if ($_SESSION['queston-sort'] === "date-newest") {
                $order = "date DESC";
            } else {
                $order = $_SESSION['queston-sort']." DESC";
            }
            if (!isset($_SESSION['category']) || $_SESSION['category'] === "all") {
                $query = $connection->prepare("SELECT * from questions WHERE lang = ? ORDER BY $order LIMIT ?, ?;");
                if ($query) {
                    $query->bind_param("sii", $lang, $from, $to);
                    if($query->execute()) {
                        $result = $query->get_result();
                        while($row = $result->fetch_array(MYSQLI_ASSOC)) {
                            array_push($questionsArr, $row);
                        }
                    } 
                    $query->close();
                    mysqli_close($connection);
                }
                return $questionsArr;
            } else {
                $category = $_SESSION['category'];
                $query = $connection->prepare("SELECT * from questions WHERE lang = ? AND category = ? ORDER BY $order LIMIT ?, ?;");
                if ($query) {
                    $query->bind_param("ssii", $lang, $category, $from, $to);
                    if($query->execute()) {
                        $result = $query->get_result();
                        while($row = $result->fetch_array(MYSQLI_ASSOC)) {
                            array_push($questionsArr, $row);
                        }
                    } 
                    $query->close();
                    mysqli_close($connection);
                }
                return $questionsArr;
            }
        }

        protected function putQuestionData($category, $title, $author) {
            $res = false;
            $connection = $this->connectionToDb();
            $lang = $_SESSION['lang'];
            $date = date("Y-m-d");
            // Prepared Statement send query and the data to the database separatly, not as one query.
            $query = $connection->prepare("INSERT INTO questions SET lang = ?, category = ?, title = ?, answers = 0, author = ?, date = ?, votes = 0;");
            if ($query) {
                $query->bind_param("sssss", $lang, $category, $title, $author, $date);
                if($query->execute()) {
                    $res = true;
                }
                $query->close();
                mysqli_close($connection);
            }
            return $res;
        }

        protected function removeQuestion($id) {
            $res = false;
            $connection = $this->connectionToDb();
            settype($id, "integer");
            $query = $connection->prepare("DELETE from answers where to_question = ?");
            if ($query) {
                $query->bind_param("i", $id);
                if($query->execute()) {
                    $query = $connection->prepare("DELETE from questions where id = ?");
                    if ($query) {
                        $query->bind_param("i", $id);
                        if($query->execute()) {
                            $res = true;
                        }
                    }
                }
                $query->close();
                mysqli_close($connection);
            }
            return $res;
        }

        protected function questionRowsNum() {
            $connection = $this->connectionToDb();
            $lang = $_SESSION['lang'];
            if (!isset($_SESSION['category']) || $_SESSION['category'] === "all") {
                $query = "SELECT id from questions WHERE lang = '$lang'";
            } else {
                $category = $_SESSION['category'];
                $query = "SELECT id from questions WHERE category = '$category' AND lang = '$lang'";
            }
            $result = $connection->query($query);
            $numRows = mysqli_num_rows($result);
            return $numRows;
        }

        protected function changeanswersNumber($id, $sign) {
            $res = false;
            $connection = $this->connectionToDb();
            settype($id, "integer");
            if ($sign === "+") {
                $query = "UPDATE questions SET answers = answers+1 WHERE id = ?";
            } else if ($sign === "-") {
                $query = "UPDATE questions SET answers = answers-1 WHERE id = ?";
            }
            $query = $connection->prepare($query);
            if ($query) {
                $query->bind_param("i", $id );
                if($query->execute()) {
                    $res = true;
                }
                $query->close();
                mysqli_close($connection);
            }
            return $res;
        }

        public function addToFavourites($user, $toQuestion) {
            $isAdded = false;
            $connection = $this->connectionToDb();
            $query = $connection->prepare("SELECT * FROM favourites WHERE username = ? AND question_number = ?");
            if($query) {
                $query->bind_param("si", $user, $toQuestion);
                if($query->execute()) {
                    $result = $query->get_result();
                    $numRows = mysqli_num_rows($result);
                    //$result = $result->fetch_array(MYSQLI_ASSOC);
                    if ($numRows > 0) {
                        $query = $connection->prepare("DELETE from favourites WHERE username = ? AND question_number = ?");
                        if($query) {
                            $query->bind_param("si", $user, $toQuestion);
                            if($query->execute()) {
                                $isAdded = false;
                                $query->close();
                                mysqli_close($connection);
                                return $isAdded;
                            } 
                        }
                    } else {
                        $query = $connection->prepare("INSERT INTO favourites SET username = ?, question_number = ?");
                        if($query) {
                            $query->bind_param("si", $user, $toQuestion);
                            if($query->execute()) {
                                $isAdded = true;
                                $query->close();
                                mysqli_close($connection);
                                return $isAdded;
                            } 
                        }
                    }
                } 
                $query->close();
                mysqli_close($connection);
            }
            return $isAdded;
        }

        public function deleteFromFavourites($user, $toQuestion) {
            $res = false;
            $connection = $this->connectionToDb();
            $query = $connection->prepare("DELETE from favourites where username = ? AND question_number = ?");
            if($query) {
                $query->bind_param("si", $user, $toQuestion);
                if($query->execute()) {
                    $res = true;
                }
            }
            return $res;
        }

        public function getAddedQuestionsToProfileSite($username) {
            $userQuestionsArr = [];
            //$lang = $_SESSION['lang'];
            $connection = $this->connectionToDb();
            $query = $connection->prepare("SELECT category, title FROM questions WHERE author = ?");
            if($query) {
                $query->bind_param("s", $username);
                if($query->execute()) {
                    $result = $query->get_result();
                    while($row = $result->fetch_array(MYSQLI_ASSOC)) {
                        array_push($userQuestionsArr, $row);
                    }
                }
                $query->close();
                mysqli_close($connection);
            }
            return $userQuestionsArr;
        }

        public function getNewestQuestions($limit) {
            $latestQuestionsArr = [];
            settype($limit, "integer");
            $connection = $this->connectionToDb();
            $query = $connection->prepare("SELECT id, author, title, lang FROM questions ORDER BY id DESC LIMIT ?");
            if($query) {
                $query->bind_param("i", $limit);
                if($query->execute()) {
                    $result = $query->get_result();
                    while($row = $result->fetch_array(MYSQLI_ASSOC)) {
                        array_push($latestQuestionsArr, $row);
                    }
                }
                $query->close();
                mysqli_close($connection);
            }
            return $latestQuestionsArr;
        }
    }


    class answersData extends Data {
        protected function getanswersData($toQuestion, $from, $to) {
            $answersArr = [];
            $connection = $this->connectionToDb();
            settype($toQuestion, "integer");
            settype($from, "integer");
            settype($to, "integer");
            $lang = $_SESSION['lang'];
            if (!isset($_SESSION['answer-sort']) || $_SESSION['answer-sort'] === "date") {
                $order = "date";
            } else {
                $order = $_SESSION['answer-sort']." DESC";
            }
            $query = $connection->prepare("SELECT * from answers WHERE to_question = $toQuestion AND lang = ? ORDER BY $order LIMIT ?, ?;");
            if ($query) {
                $query->bind_param("sii", $lang, $from, $to);
                if($query->execute()) {
                    $result = $query->get_result();
                    while($row = $result->fetch_array(MYSQLI_ASSOC)) {
                        array_push($answersArr, $row);
                    }
                }
                $query->close();
                mysqli_close($connection);
            }
            return $answersArr;
        }

        protected function putanswerData($toQuestion, $answer, $author, $link="" ) {
            $res = false;
            $connection = $this->connectionToDb();
            settype($toQuestion, "integer");
            $link = htmlspecialchars($link, ENT_QUOTES);
            $lang = $_SESSION['lang'];
            $date=date("Y-m-d");
            $query = $connection->prepare("INSERT INTO answers SET lang = ?, to_question = ?, answer_text = ?, link = ?, author = ?, date = ?, votes = 0");
            if ($query) {
                $query->bind_param("sissss", $lang, $toQuestion, $answer, $link, $author, $date);
                if($query->execute()) {
                    $res = true;
                }
                $query->close();
                mysqli_close($connection);
            }
            return $res;
        }

        protected function removeanswer($id) {
            $res = false;
            $connection = $this->connectionToDb();
            settype($id, "integer");
            $query = $connection->prepare("DELETE from answers where id = ?");
            if ($query) {
                $query->bind_param("i", $id);
                if($query->execute()) {
                    $res = true;
                } 
                $query->close();
                mysqli_close($connection);
            }
            return $res;
        }

        protected function answerRowsNum($toQuestion) {
            $numRows = 0;
            $connection = $this->connectionToDb();
            settype($toQuestion, "integer");
            $lang = $_SESSION['lang'];
            $query = $connection->prepare("SELECT id from answers WHERE to_question LIKE ? AND lang = ?");
            if ($query) {
                $query->bind_param("is", $toQuestion, $lang);
                if($query->execute()) {
                    $query->store_result();
                    $numRows = $query->num_rows;
                } 
                $query->close();
                mysqli_close($connection);
            }
            return $numRows;
        }

        public function changeanswerVotesNumber($answerId, $sign, $difference) {
            $res = false;
            $connection = $this->connectionToDb();
            settype($answerId, "integer");
            if ($sign === "+") {
                $query = "UPDATE answers SET votes = votes+$difference WHERE id = ?";
            } else if ($sign === "-") {
                $query = "UPDATE answers SET votes = votes-$difference WHERE id = ?";
            }
            $query = $connection->prepare($query);
            if ($query) {
                $query->bind_param("i", $answerId);
                if($query->execute()) {
                    $res = true;
                }
                $query->close();
                mysqli_close($connection);
            }
            return $res;
        }

        public function addVote($user, $answerId, $sign) {
            $isVotesAdded = [];
            $connection = $this->connectionToDb();
            $query = $connection->prepare("SELECT * FROM votes WHERE username = ? AND answer_number = ?");
            if($query) {
                $query->bind_param("si", $user, $answerId);
                if($query->execute()) {
                    $result = $query->get_result();
                    $numRows = mysqli_num_rows($result);
                    if ($numRows > 0) {
                        $result = $result->fetch_array(MYSQLI_ASSOC);
                        $up =  $result['up'];
                        $down =  $result['down'];
                        $id = $result['id'];
                        $difference = 1;
                        if ($sign === "+") {
                            if ($down) {
                                $difference = 2;
                            }
                            $up = !$up;
                            $down = false;
                        }
                        if ($sign === "-") {
                            if ($up) {
                                $difference = 2;
                            }
                            $up = false;
                            $down = !$down;
                        } 
                        $query = $connection->prepare("UPDATE votes SET up = '$up', down = '$down' WHERE id = ?");
                        if($query) {
                            $query->bind_param("i", $id);
                            if($query->execute()) {                             
                                array_push($isVotesAdded, $up, $down, $difference);
                                // if up and down are false then remove row
                                if ($up === false && $down === false) {
                                    $query = $connection->prepare("DELETE from votes WHERE username = ? AND answer_number = ?");
                                    if($query) {
                                        $query->bind_param("si", $user, $answerId);
                                        $query->execute();
                                    }
                                }
                            }
                            $query->close();
                            mysqli_close($connection);
                        }
                        return $isVotesAdded;
                    } else {
                        $up = false;
                        $down = false;
                        if ($sign === "+") {
                            $up = true;
                        }
                        if ($sign === "-") {
                            $down = true;
                        }
                        $difference = 1;
                        $query = $connection->prepare("INSERT INTO votes SET username = ?, answer_number = ?, up = '$up', down = '$down'");
                        if($query) {
                            $query->bind_param("si", $user, $answerId);
                            if($query->execute()) {
                                array_push($isVotesAdded, $up, $down, $difference);
                            }
                            $query->close();
                            mysqli_close($connection);
                        } 
                        return $isVotesAdded;
                    }
                } 
                $query->close();
                mysqli_close($connection);
            }
            return $isVotesAdded;
        }

        public function deleteVote($user, $answerId) {
            $res = false;
            $connection = $this->connectionToDb();
            $query = $connection->prepare("DELETE from votes where username = ? AND answer_number = ?");
            if($query) {
                $query->bind_param("si", $user, $answerId);
                if($query->execute()) {
                    $res = true;
                }
            }
            return $res;
        }

        public function isVoted($user, $answerId) {
            $connection = $this->connectionToDb();
            $query = $connection->prepare("SELECT * FROM votes where username = ? AND answer_number = ?");
            if($query) {
                $query->bind_param("si", $user, $answerId);
                if($query->execute()) {
                    $result = $query->get_result();
                    $result = $result->fetch_array(MYSQLI_ASSOC);
                }
            }
            return $result;
        }

        public function getAddedanswersToProfileSite($username) {
            $useranswersArr = [];
            $connection = $this->connectionToDb();
            $query = $connection->prepare("SELECT id, to_question, answer_text FROM answers WHERE author = ?");
            if($query) {
                $query->bind_param("s", $username);
                if($query->execute()) {
                    $result = $query->get_result();
                    while($row = $result->fetch_array(MYSQLI_ASSOC)) {
                        array_push($useranswersArr, $row);
                    }
                }
                $query->close();
                mysqli_close($connection);
            }
            return $useranswersArr;
        }

        public function getNewestanswers($limit) {
            $latestanswersArr = [];
            settype($limit, "integer");
            $connection = $this->connectionToDb();
            $query = $connection->prepare("SELECT answer_text, to_question, lang, author FROM answers ORDER BY id DESC LIMIT ?");
            if($query) {
                $query->bind_param("i", $limit);
                if($query->execute()) {
                    $result = $query->get_result();
                    while($row = $result->fetch_array(MYSQLI_ASSOC)) {
                        array_push($latestanswersArr, $row);
                    }
                }
                $query->close();
                mysqli_close($connection);
            }
            return $latestanswersArr;
        }
    }


    class DisplayQuestionsData extends QuestionsData {
        private $questionsNumOnPage = 3;
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
            return $getQuestions;
        }

        public function pageNavigationNumber() {
            $this->questionsNumber();
            $getAllQuestionsNumber = $this->questionRowsNum();
            $pageNavigationNumber = ceil($getAllQuestionsNumber/$this->questionsNumOnPage);
            return $pageNavigationNumber;
        }

        public function addQuestion($category, $title, $author='anonim') {
            //$res = $this->putQuestionData($category, $title, $author);
            $res = false;
            if ($this->putQuestionData($category, $title, $author)) {
                $userData = new UserData();
                $res = $userData->changeAddedQuestionsNumber($author, "+");
            }
            return $res;
        }

        public function deleteQuestion($id, $user) {
            $res = false;
            if ($this->removeQuestion($id)) {
                $userData = new UserData();
                $res = $userData->changeAddedQuestionsNumber($user, "-");
            }
            return $res;
        }

        public function setanswersNumber($id, $sign) {
            $res = $this->changeanswersNumber($id, $sign);
            return $res;
        }

        public function lastQuestionIndex() {
            $connection = $this->connectionToDb();
            $lang = $_SESSION['lang'];
            $res = $connection->query("SELECT id from questions WHERE lang = '$lang' ORDER BY id DESC LIMIT 1");
            $row = $res->fetch_row();
            return intval($row[0]);
        }

        public function isAddedToFavourites($user, $toQuestion) {
            $connection = $this->connectionToDb();
            $query = $connection->prepare("SELECT * FROM favourites WHERE username = ? AND question_number = ?");
            if($query) {
                $query->bind_param("si", $user, $toQuestion);
                if($query->execute()) {
                    $result = $query->get_result();
                    $numRows = mysqli_num_rows($result);
                } 
                $query->close();
                mysqli_close($connection);
            }
            if ($numRows > 0) {
                return true;
            } else {
                return false;
            }
        }

        public function questionDataOnanswerPage($id) {
            $connection = $this->connectionToDb();
            settype($id, "integer");
            $lang = $_SESSION['lang'];
            $query = $connection->prepare("SELECT * from questions WHERE id LIKE ? AND lang = ?");
            if ($query) {
                $query->bind_param("is", $id, $lang);
                if($query->execute()) {
                    $result = $query->get_result();
                    $result = $result->fetch_array(MYSQLI_ASSOC);
                } 
                $query->close();
                mysqli_close($connection);
            }
            return $result;
        }
    }


    class DisplayanswersData extends answersData {
        public $answersNumOnPage = 10;
        private $pageNumber;
        private $from;
        private $to;

        protected function setanswersNumber() {   
            if(isset($_GET['page'])) {
                $this->pageNumber = $_GET['page'];
            }

            if (!empty($this->pageNumber)) {
                $this->from = $this->answersNumOnPage*(($this->pageNumber)-1);
                $this->to = $this->answersNumOnPage;
            } else {
                $this->from = 0;
                $this->to = $this->answersNumOnPage;
            }  
        }

        public function pageNavigationNumber($getId) {
            $this->setanswersNumber();
            $getAllanswerNumber = $this->answerRowsNum($getId);
            $pageNavigationNumber = ceil($getAllanswerNumber/$this->answersNumOnPage);
            return $pageNavigationNumber;
        }

        public function getanswers($getId) {
            $getanswers = $this->getanswersData($getId, $this->from, $this->to);
            return $getanswers;
        }

        public function getAllanswersNum($getId) {     
            $getanswers = $this->answerRowsNum($getId);
            return $getanswers;
        }

        public function getAllanswersToPDF($getId) {
            $getanswers = $this->getanswersData($getId, 0, 10000);       
            //$getanswers = $this->answerRowsNum($getId);
            return $getanswers;
        }

        public function addanswer($toQuestion, $answer, $author='anonim', $link="") {
            $res = false;
            if ($this->putanswerData($toQuestion, $answer, $author)) {
                $userData = new UserData();
                $res = $userData->changeAddedanswersNumber($author, "+");
            }
            return $res;
        }

        public function deleteanswer($id, $user) {
            $res = false;
            if ($this->removeanswer($id)) {
                $userData = new UserData();
                $res = $userData->changeAddedanswersNumber($user, "-");
            }
            return $res;
        }
    }

    class UserData extends Data {
        public function checkUserName($userName, $option) {
            $connection = $this->connectionToDb();
            settype($userName, "string");
            settype($option, "string");
            if ($option === "username") {
                $query = $connection->prepare("SELECT * from users WHERE username LIKE ?");
            } 
            if ($option === "email") {
                $query = $connection->prepare("SELECT * from users WHERE email LIKE ?");
            } 
            if ($query) {
                $query->bind_param("s", $userName);
                if($query->execute()) {
                    $query->store_result();
                    $numRows = $query->num_rows;
                } else {
                    $numRows = 0;
                }
                $query->close();
                mysqli_close($connection);
            }
            return $numRows;
        }

        public function addUser($userName, $email, $pass) {
            $res = false;
            $connection = $this->connectionToDb();
            $date=date("Y-m-d");
            $query = $connection->prepare("INSERT INTO users SET username = ?, email = ?, pass = ?, date = ?");
            if ($query) {
                $query->bind_param("ssss", $userName, $email, $pass, $date);
                if($query->execute()) {
                    $res = true;
                }
                $query->close();
                mysqli_close($connection);
            }
            return $res;
        }

        public function changeUserPasword($newPass, $email) {
            $res = false;
            $connection = $this->connectionToDb();
            $date=date("Y-m-d");
            $query = $connection->prepare("UPDATE users SET pass = ? WHERE email = ?");
            if ($query) {
                $query->bind_param("ss", $newPass, $email);
                if($query->execute()) {
                    $res = true;
                }
                $query->close();
                mysqli_close($connection);
            }
            return $res;
        }

        public function getUserData($username) {
            $connection = $this->connectionToDb();
            $query = $connection->prepare("SELECT id, username, email, site, date FROM users WHERE username = ?");
            if($query) {
                $query->bind_param("s", $username);
                if($query->execute()) {
                    $result = $query->get_result();
                    $result = $result->fetch_array(MYSQLI_ASSOC);
                }
                $query->close();
                mysqli_close($connection);
            }
            return $result;
        }

        public function getUserVeryficationData($email) {
            $connection = $this->connectionToDb();
            $query = $connection->prepare("SELECT username, email, pass FROM users WHERE email = ?");
            if($query) {
                $query->bind_param("s", $email);
                if($query->execute()) {
                    $result = $query->get_result();
                    $result = $result->fetch_array(MYSQLI_ASSOC);
                }
                $query->close();
                mysqli_close($connection);
            }
            return $result;
        }

        public function getFavouritesQuestions($username) {
            $favouritesArr = [];
            //$lang = $_SESSION['lang'];
            $connection = $this->connectionToDb();
            // $query = $connection->prepare("SELECT category, title FROM questions_$lang INNER JOIN favourites ON questions_$lang.id = favourites.question_number WHERE username = ?");
            $query = $connection->prepare("SELECT questions.id, category, title FROM questions INNER JOIN favourites ON questions.id = favourites.question_number WHERE username = ?");
            if($query) {
                $query->bind_param("s", $username);
                if($query->execute()) {
                    $result = $query->get_result();
                    while($row = $result->fetch_array(MYSQLI_ASSOC)) {
                        array_push($favouritesArr, $row);
                    }
                }
                $query->close();
                mysqli_close($connection);
            }
            return $favouritesArr;
        }

        public function addUserSite($username, $site) {
            $res = false;
            $connection = $this->connectionToDb();
            $query = $connection->prepare("UPDATE users SET site = ? WHERE username = ?");
            if($query) {
                $query->bind_param("ss", $site, $username);
                if($query->execute()) {
                    $res = true;
                }
                $query->close();
                mysqli_close($connection);
            }
            return $res;
        }

        public function changeAddedQuestionsNumber($username, $sign) {
            $res = false;
            $connection = $this->connectionToDb();
            if ($sign === "+") {
                $query = $connection->prepare("UPDATE users SET added_questions=added_questions+1 WHERE username = ?");
            } else {
                $query = $connection->prepare("UPDATE users SET added_questions=added_questions-1 WHERE username = ?");
            }
            if($query) {
                $query->bind_param("s", $username);
                if($query->execute()) {
                    $res = true;
                }
                $query->close();
                mysqli_close($connection);
            }
            return $res;
        }

        public function changeAddedanswersNumber($username, $sign) {
            $res = false;
            $connection = $this->connectionToDb();
            if ($sign === "+") {
                $query = $connection->prepare("UPDATE users SET added_answers=added_answers+1 WHERE username = ?");
            } else {
                $query = $connection->prepare("UPDATE users SET added_answers=added_answers-1 WHERE username = ?");
            }
            if($query) {
                $query->bind_param("s", $username);
                if($query->execute()) {
                    $res = true;
                }
                $query->close();
                mysqli_close($connection);
            }
            return $res;
        }

        public function getMostAdded($added, $limit) {
            $mostAdded = [];
            $connection = $this->connectionToDb();
            if ($added === "questions") {
                $query = $connection->prepare("SELECT username, added_questions FROM users ORDER BY added_questions DESC LIMIT ?");
            } else if ($added === "answers") {
                $query = $connection->prepare("SELECT username, added_answers FROM users ORDER BY added_answers DESC LIMIT ?");
            }
            if($query) {
                $query->bind_param("i", $limit);
                if($query->execute()) {
                    $result = $query->get_result();
                    while($row = $result->fetch_array(MYSQLI_ASSOC)) {
                        array_push($mostAdded, $row);
                    }
                }
                $query->close();
                mysqli_close($connection);
            }
            return $mostAdded;
        }
    }

    class RemindPassword extends Data {
        public function addToken($email, $token) {
            $res = false;
            $connection = $this->connectionToDb();
            $date = date("Y-m-d h:i:s");
            $time = strtotime($date);
            $query = $connection->prepare("INSERT INTO remind_password SET email = ?, token = ?, date = ?, time = ?");
            if ($query) {
                $query->bind_param("sssi", $email, $token, $date, $time );
                if($query->execute()) {
                    $res = true;
                }
                $query->close();
                mysqli_close($connection);
            }
            return $res;
        }

        public function changeToken($email, $token) {
            $res = false;
            $connection = $this->connectionToDb();
            $date = date("Y-m-d h:i:s");
            $time = strtotime($date);
            $query = $connection->prepare("UPDATE remind_password SET token = ?, date = ?, time = ? WHERE email = ?");
            if ($query) {
                $query->bind_param("ssis", $token, $date, $time, $email);
                if($query->execute()) {
                    $res = true;
                }
                $query->close();
                mysqli_close($connection);
            }
            return $res;
        }

        public function checkToken($token) {
            $result = [];
            $connection = $this->connectionToDb();
            $query = $connection->prepare("SELECT * FROM remind_password WHERE token = ?");
            if ($query) {
                $query->bind_param("s", $token);
                if($query->execute()) {
                    $result = $query->get_result();
                    $result = $result->fetch_array(MYSQLI_ASSOC);
                }
                $query->close();
                mysqli_close($connection);
            }
            return $result;
        }

        public function checkIfUserHasToken($email) {
            $result = [];
            $connection = $this->connectionToDb();
            $query = $connection->prepare("SELECT * FROM remind_password WHERE email = ?");
            if ($query) {
                $query->bind_param("s", $email);
                if($query->execute()) {
                    $result = $query->get_result();
                    $result = $result->fetch_array(MYSQLI_ASSOC);
                }
                $query->close();
                mysqli_close($connection);
            }
            return $result;
        }

        public function deleteToken($token) {
            $res = false;
            $connection = $this->connectionToDb();
            $query = $connection->prepare("DELETE FROM remind_password WHERE token = ?");
            if ($query) {
                $query->bind_param("s", $token);
                if($query->execute()) {
                    $res = true;
                }
                $query->close();
                mysqli_close($connection);
            }
            return $res;
        }

        public function automaticallyDeleteToken($time) {
            $res = false;
            $connection = $this->connectionToDb();
            $query = $connection->prepare("DELETE FROM remind_password WHERE time < ?");
            if ($query) {
                $query->bind_param("i", $time);
                if($query->execute()) {
                    $res = true;
                }
                $query->close();
                mysqli_close($connection);
            }
            return $res;
        }

    }


    class LoadSites {
        private $loadSite;

        private function setSite($site) {
            $lang = $_SESSION['lang'];
            if ($site === "add-question") {
                if ($lang === 'pl') {
                    $this->loadSite = "dodaj-pytanie";
                } else {
                    $this->loadSite = "add-question";
                }
            } else if ($site === "show-question") {
                if ($lang === 'pl') {
                    $this->loadSite = "pytanie";
                } else {
                    $this->loadSite = "question";
                }
            } else if ($site === "forgot-password") {
                if ($lang === 'pl') {
                    $this->loadSite = "przypomnij-haslo";
                } else {
                    $this->loadSite = "remind-password";
                }
            } else if ($site === "contact") {
                if ($lang === 'pl') {
                    $this->loadSite = "kontakt";
                } else {
                    $this->loadSite = "contact";
                }
            }
        }

        public function loadSite($site) {
            $this->setSite($site);
            return $this->loadSite;
        }
    }

    class ValidateData {
        public function validateName($name) {
            $res = false;
            if (preg_match('/^[a-z0-9-śćąężźńłó]{3,30}$/i', $name)) {
               $res = true;
            }
            return $res; 
        }

        public function validateEmail($email) {
            $res = false;
            if (preg_match('/^[a-zA-Z0-9-._]+@[a-zA-Z0-9-_.]+\.[a-zA-Z]{2,25}$/', $email)) {
               $res = true;
            }
            return $res; 
        }

        public function validatePassword($pass) {
            $res = false;
            // password must have at least one small and one large letter and one number
            if (preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])[a-zA-Z0-9?!#]{6,30}$/', $pass)) {
               $res = true;
            }
            return $res; 
        }

    }

?>