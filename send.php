<?php
session_start();

$url = 'https://fcm.googleapis.com/fcm/send';
$YOUR_API_KEY = 'AIzaSyCpwY3CCP-snMnfaktCecEp_x5zLFDLmDk';

$fields = [
    'to' => $_SESSION['token'],
    'notification' => [
        'title' => 'Ералаш',
        'body' => sprintf('Начало в %s.', date('H:i')),
        'icon' => 'https://eralash.ru.rsz.io/sites/all/themes/eralash_v5/logo.png?width=40&height=40',
        'click_action' => 'http://eralash.ru/',
    ],
];

$headers = [
    'Content-Type: application/json',
    'Authorization: key=' . $YOUR_API_KEY,
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

$response = curl_exec($ch);
curl_close($ch);

$response = ($array = @json_decode($response)) ? json_encode($array, JSON_PRETTY_PRINT) : $response;
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Test push notification</title>
</head>
<body>
<h3>Request</h3>
headers:
<pre><code><?=implode(PHP_EOL, $headers)?></pre>
body:
<pre><code><?=json_encode($fields, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)?></pre>
<br>
<h3>Response</h3>
<pre><code><?=$response?></pre>
<br>
<a href="/index.html">< Go back</a>
</body>
</html>
<script src="https://www.gstatic.com/firebasejs/3.6.8/firebase.js"></script>
<script src="/app.js"></script>
