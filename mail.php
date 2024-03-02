<?php 
require __DIR__.'/amo.php';

$email = $_POST['email'];
$phone = $_POST['phone'];


if (empty($email)) {
    $name = 'Заявка Хуснутдинов';
}else {
    $name = $email;
}

if (!empty($phone)) {
    
    $oauth2Data = file_get_contents(__DIR__.'/oauth2.json');
    $oauth2Data = json_decode($oauth2Data, true);
    $subdomain = 'irochkapetrova2003';
    $accessToken = $oauth2Data['access_token'];
    
    $amo = new AmoCRM($subdomain, $accessToken);
    $contactId = $amo->createContact($name, $email, $phone);
    
    if ($contactId !== false) {
        $result = $amo->createLead($contactId);
    }
    
    $to = 'sk@salesgenerator.pro,karnaushkina@salesgenerator.pro';
    $subject = 'Получите набор файлов для руководителя';
    $message = "
        Email: $email
        Phone: $phone
    ";
    $headers = 'From: info@likeandlike.ru';
 
    if (mail($to, $subject, $message, $headers)) {
       http_response_code(200);
    } else {
        http_response_code(400);
    }
}
?>