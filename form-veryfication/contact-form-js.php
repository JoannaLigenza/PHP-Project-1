<?php
    include "../functions.php";
    include "../functions-run.php";
    //$userData = new UserData();

    function sendContactMessage($name, $email, $message) {
        $messageInfo = "";
        include "../contact/send-message.php";
        $messageInfo = sendMessage($email, $name, $message);
        echo json_encode($messageInfo);
    }

    if (!empty($_POST['sendMessageButton'])) {
        $name = htmlspecialchars($_POST['userName'], ENT_QUOTES);
        $email = htmlspecialchars($_POST['userEmail'], ENT_QUOTES);
        $message = htmlspecialchars($_POST['textareaMessage'], ENT_QUOTES);
        sendContactMessage($name, $email, $message);
    }
?>