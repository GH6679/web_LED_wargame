<?php
$stack = isset($_COOKIE['stack']) ? $_COOKIE['stack'] : 0;
if (isset($_POST['senddata'])) {

    $senddata = htmlspecialchars($_POST['senddata']);

    if ($senddata == 'YES') {
        $stack++;
        setcookie('stack', $stack, time() + 3600); // 1시간 유효
        
        require_once '/var/www/private/level_key/level29_key.php';
        $data = getKey();

        if ($stack == 500) {
            echo json_encode(array("title" => "success", "message" => $data['key']));
        } else {
            echo json_encode(array("title" => "fail try twice", "message" => "NO"));
        }
    } else {
        echo json_encode(array("title" => "fail", "message" => "NO"));
    }
}
