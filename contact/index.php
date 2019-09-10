<?php 
    include "../functions.php";
    include "../functions-run.php";
    $_SESSION['title'] = $displayLang["contact"];
    $_SESSION['description'] = $displayLang["contact"];
    include "../header.php";

    //$messageInfo = "";

    function clickContactButton() {
        $messageInfo = "";
        if (isset($_POST['send-message-button'])) {
            $name = htmlspecialchars($_POST['user-name'], ENT_QUOTES);
            $email = htmlspecialchars($_POST['send-email'], ENT_QUOTES);
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
                    $messageInfo = sendMessage($email, $name, $message);
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
            <p class="pb-4">Jeśli masz jakieś pytania lub uwagi, napisz do mnie :)</p>
            <form action="" method="post">
                <input type="text" name="user-name" placeholder="name" class="container form-control form-control-lg shadow-none mb-2" >
                <input type="email" name="send-email" placeholder=<?php echo $displayLang["email"] ?> class="container form-control form-control-lg shadow-none mb-2" >
                <textarea name="contact-textarea" id="contact-textarea" cols="30" rows="7" class="container form-control form-control-lg shadow-none" ></textarea>
                <button type="submit" name="send-message-button" class="container btn btn-outline-warning my-2 py-2" id="send-message-button">Send</button>
            </form>
        </div>
        <p> <?php echo $messageInfo ?> </p>
    </div>
</div>




<?php include "../footer.php"; ?>