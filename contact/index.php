<?php 
    include "../functions.php";
    include "../functions-run.php";
    $_SESSION['title'] = $displayLang["contact"];
    $_SESSION['description'] = $displayLang["contact"];
    $_SESSION['index'] = "index";
    include "../header.php";

    function clickContactButton() {
        $messageInfo = "";
        if (isset($_POST['send-contact-message-button'])) {
            $name = htmlspecialchars($_POST['user-name-contact-input'], ENT_QUOTES);
            $email = htmlspecialchars($_POST['email-contact-input'], ENT_QUOTES);
            $message = htmlspecialchars($_POST['contact-textarea'], ENT_QUOTES);
            if (empty($name) || empty($email) || empty($message)) {
                $messageInfo = "Please fill all fields to send message";
            } else {
                $validateData = new ValidateData();
                if (!$validateData->validateName($name)) {
                    $messageInfo = "Please enter valid name";
                } 
                else if (!$validateData->validateEmail($email)) {
                    $messageInfo = "Please enter valid email";
                } else {
                    include "send-message.php";
                    $lang = $_SESSION['lang'];
                    $subject = "";
                    if ($lang === "pl") {
                        $subject = "Wiadomość od ".$name." wysłana przez formularz kontaktowy ze strony love-coding.pl/pytania-rekrutacyjne-dla-front-end-developerow";
                    } else {
                        $subject = "Message from ".$name." sended on contact form from site love-coding.pl/front-end-developers-recruiment-questions";
                    }
                    $messageInfo = sendMessage($email, $name, $message, $subject);
                }
            }
        }
        return $messageInfo;
    }
    $messageInfo = clickContactButton();
    
?>

<div class="gray-background">
    <div class="card text-center py-5 gray-background vh-100">
        <!-- <h3>Dołącz, by dodać nowe pytania i odpowiedzi do bazy wiedzy dla Junior Front-end Developerów</h3> -->
        <div class="card-body py-5">
            <p class="pb-4"> <?php echo $displayLang["contact_message"] ?></p>
            <form action="" method="post" id="contact-form">
                <input type="text" name="user-name-contact-input" placeholder="name" class="container form-control form-control-lg shadow-none mb-2" id="user-name-contact-input">
                <input type="email" name="email-contact-input" placeholder=<?php echo $displayLang["email"] ?> class="container form-control form-control-lg shadow-none mb-2" id="email-contact-input">
                <textarea name="contact-textarea" id="contact-textarea" cols="30" rows="7" class="container form-control form-control-lg shadow-none" ></textarea>
                <button type="submit" name="send-contact-message-button" class="container btn btn-outline-warning my-2 py-2" id="send-contact-message-button">Send</button>
                <p class="mt-4"> <?php echo $messageInfo ?> </p>
            </form>
        </div>
    </div>
</div>




<?php include "../footer.php"; ?>