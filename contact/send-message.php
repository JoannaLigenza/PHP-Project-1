<?php

    use PHPMailer\PHPMailer\PHPMailer;

    require_once ("../../PhpMailer/Exception.php");
    require_once ("../../PhpMailer/PHPMailer.php");
    require_once ("../../PhpMailer/SMTP.php");

    function sendMessage($email, $name, $message, $subject) {
        include  dirname(dirname(dirname(__DIR__)))."/mailconnect.php";
        $lang = $_SESSION['lang'];

        $mail = new PHPMailer(); 
        $mail->CharSet = 'UTF-8';

        //SMTP Settings
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';                                              // or: "ssl" (with: $mail->Port = 465;)
        $mail->Port = 587;                                                      // or: 465  (with $mail->SMTPSecure = 'ssl';)
        $mail->Username = $from ;
        $mail->Password = $mailPass;

        //Email settings
        $mail->isHTML(true);
        $mail->setFrom($email, $name);
        $mail->addAddress($from , "love-coding.pl");                            // user address / user name
        $mail->addReplyTo($email, $name);                                       // email address / decription
        $mail->addBCC($mailBCC);
        $mail->Subject = $subject;
        
        $mail->Body    = $message;
        $mail->AltBody = $message;

        if ($mail->send()) {
            //echo 'Email is send';
            if ($lang === "pl") {
                return "Twój email został wysłany, dziękuję za kontakt :)";
            } else {
                return "Your email has been sent, thank you for contact :)";
            }
        } else {
            //echo "not send because: ".$mail->ErrorInfo;
            if ($lang === "pl") {
                return "Coś poszło nie tak, spróbuj jeszcze raz";
            } else {    
                return "Something went wrong, try again";
            }
        }
    }

    function resetPassMessage($email, $token) {
        include  dirname(dirname(dirname(__DIR__)))."/mailconnect.php";
        $lang = $_SESSION['lang'];

        $mail = new PHPMailer(); 
        $mail->CharSet = 'UTF-8';

        //SMTP Settings
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';                                              // or: "ssl" (with: $mail->Port = 465;)
        $mail->Port = 587;                                                      // or: 465  (with $mail->SMTPSecure = 'ssl';)
        $mail->Username = $from ;
        $mail->Password = $mailPass;

        //Email settings
        $mail->isHTML(true);
        $mail->setFrom($from, "love-coding.pl");
        $mail->addAddress($email, $email);                  // user address / user name
        $mail->addReplyTo($from, "love-coding.pl");        // email address / decription
        $mail->addBCC($mailBCC);
        if ($lang === "pl") {
            $mail->Subject = "Instrukcja zmiany hasła - love-coding.pl pytania rekrutacyjne dla Front-end developerów";
            $mail->Body    = "Aby zmienić hasło skopiuj ten link i wklej go do przeglądarki: <br> <a href='https://love-coding.pl/pytania-rekrutacyjne/".$lang."/reset-password/?token=".$token."'> https://love-coding.pl/pytania-rekrutacyjne/".$lang."/reset-password/?token=".$token." </a> <br>Link jest aktywny przez 24 godziny";
            $mail->AltBody = "Aby zmienić hasło skopiuj ten link i wklej go do przeglądarki: \nhttps://love-coding.pl/pytania-rekrutacyjne/".$lang."/reset-password/?token=".$token." \nLink jest aktywny przez 24 godziny";
        } else {
            $mail->Subject = "Password change instructions - love-coding.pl recruiment questions for front-end developers";
            $mail->Body    = "To change password copy this link and paste it to browser: <br> <a href='https://love-coding.pl/pytania-rekrutacyjne/".$lang."/reset-password/?token=".$token."'> https://love-coding.pl/pytania-rekrutacyjne/".$lang."/reset-password/?token=".$token." </a> <br> Link is active 24 hours";
            $mail->AltBody = "To change password copy this link and paste it to browser: \nhttps://love-coding.pl/pytania-rekrutacyjne/".$lang."/reset-password/?token=".$token." \nLink is active 24 hours";
        }

        if ($mail->send()) {
            //echo 'Email is send';
            if ($lang === "pl") {
                return "Sprawdź skrzynkę pocztową :)";
            } else {
                return "Check your email account :)";
            }
        } else {
            //echo "not send because: ".$mail->ErrorInfo;
            if ($lang === "pl") {
                return "Coś poszło nie tak, spróbuj jeszcze raz";
            } else {
                return "Something went wrong, try again";
            }
        }
    }
    

?>