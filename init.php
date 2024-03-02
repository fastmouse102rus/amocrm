<?php 
$subdomain = 'irochkapetrova2003'; //Поддомен нужного аккаунта
$link = 'https://' . $subdomain . '.amocrm.ru/oauth2/access_token'; //Формируем URL для запроса

/** Соберем данные для запроса */
$data = [
    'client_id' => '9e15d5d7-f8b3-48e4-9e69-9ef26a18d501',
    'client_secret' => 'kXYt0Q7AnDVPYrIw6UBwb0Mv4SFiPN1JvtoiREr1fqq1vJN07rUDPxvzpOFj83Dh',
    'grant_type' => 'authorization_code',
    'code' => 'def502000bdf129cb4d6839231a1c642d5f4cce8e21e16b6f0078b7e5df80cf6d5782ce323c850d9bb0a7ae57b93766f0a0fd328f8da940ead57dcdbd10c27195ccf1c643293982d6d117569398dd3cccbedc48f6246c77d35eeda5cfc323d0cb12c856388282a1219b7ceaa5d9ec4fc79ca6c877cf11927d3399101e1aeff80749dffe1e706db7ec57140d9e69e6b294891f184ca90b81e27cafcf4a889c6591d69bf113ef6e7feb91ddf3a322dba149a91e445b8122a4b3741c30be9a24239437fde0c32f1d25a07f7536b4ed2c791c8030a366bcc97f9687d526c12add7d76f060188b1a4af60c60fce11e55fec22c4b02f24fa2b6435f171f6d27e56f7e8d0cf711692effbe1113ca9181c010a5ec762065a6b9ef931d30c11f190aaca9f3a9f881ea26f93439795280ce2f7ad285df7b89280373c31ff5c656ae537d589cb0cf83f002d5a7829feb4dee8ff106a3ae22ac255867391c70243e6b3bab2a32258af2c82561ef2287e98ebeb6f73ee705ed3b1eb6b2f34612a53e7072c20e910e10b04a5729573c3fca5ca48351fbe1ef81e86c58df2a6809b66149d96fb9e445e0bbddb20bac499aecb00c76d6db09703c6117bc53f48ae4233f1a9dbf5a0aaa1f9ae3818d6f2689714eac7ec10e0e434214e862710357c3a8db3a33ca46e9f3a',
    'redirect_uri' => 'https://localhost',
];

/**
 * Нам необходимо инициировать запрос к серверу.
 * Воспользуемся библиотекой cURL (поставляется в составе PHP).
 * Вы также можете использовать и кроссплатформенную программу cURL, если вы не программируете на PHP.
 */
$curl = curl_init(); //Сохраняем дескриптор сеанса cURL
/** Устанавливаем необходимые опции для сеанса cURL  */
curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-oAuth-client/1.0');
curl_setopt($curl,CURLOPT_URL, $link);
curl_setopt($curl,CURLOPT_HTTPHEADER,['Content-Type:application/json']);
curl_setopt($curl,CURLOPT_HEADER, false);
curl_setopt($curl,CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($curl,CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, 2);
$out = curl_exec($curl); //Инициируем запрос к API и сохраняем ответ в переменную
$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);
/** Теперь мы можем обработать ответ, полученный от сервера. Это пример. Вы можете обработать данные своим способом. */
$code = (int)$code;
$errors = [
    400 => 'Bad request',
    401 => 'Unauthorized',
    403 => 'Forbidden',
    404 => 'Not found',
    500 => 'Internal server error',
    502 => 'Bad gateway',
    503 => 'Service unavailable',
];

try
{
    /** Если код ответа не успешный - возвращаем сообщение об ошибке  */
    if ($code < 200 || $code > 204) {
        throw new Exception(isset($errors[$code]) ? $errors[$code] : 'Undefined error', $code);
    }
}
catch(\Exception $e)
{
    die('Ошибка: ' . $e->getMessage() . PHP_EOL . 'Код ошибки: ' . $e->getCode());
}

$response = json_decode($out, true);

$access_token = $response['access_token']; //Access токен
$refresh_token = $response['refresh_token']; //Refresh токен
$token_type = $response['token_type']; //Тип токена
$expires_in = $response['expires_in']; //Через сколько действие токена истекает

$oauth2Data = [
    'access_token' => $access_token,
    'refresh_token' => $refresh_token,
    'token_type' => $token_type,
    'expires_in' => $expires_in,
];

file_put_contents(__DIR__.'/oauth2.json', json_encode($oauth2Data));
?>