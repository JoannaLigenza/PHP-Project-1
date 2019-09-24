<?php
    include "../functions.php";
    include "../functions-run.php";
    //$userData = new UserData();

    function sendContactMessage($name, $email, $message) {
        $messageInfo = "";
        include "../contact/send-message.php";
        $lang = $_SESSION['lang'];
        $subject = "";
        if ($lang === "pl") {
            $subject = "Wiadomość od ".$name." wysłana przez formularz kontaktowy ze strony love-coding.pl/pytania-rekrutacyjne-dla-front-end-developerow";
        } else {
            $subject = "Message from ".$name." sended on contact form from site love-coding.pl/front-end-developers-recruiment-questions";
        }
        $messageInfo = sendMessage($email, $name, $message, $subject);
        echo json_encode($messageInfo);
    }

    if (!empty($_POST['sendMessageButton'])) {
        $name = htmlspecialchars($_POST['userName'], ENT_QUOTES);
        $email = htmlspecialchars($_POST['userEmail'], ENT_QUOTES);
        $message = htmlspecialchars($_POST['textareaMessage'], ENT_QUOTES);
        sendContactMessage($name, $email, $message);
    }
?>