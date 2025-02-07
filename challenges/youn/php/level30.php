<?php

if (isset($_POST['coupon'])) {

    $coupon = trim(htmlspecialchars($_POST['coupon']));
    $date = trim(htmlspecialchars($_POST['date']));

    if ($coupon == 'CHRISTMAS2020') {


        if ($date == '2020-12-25') {
            require_once '/var/www/private/level_key/level30_key.php';
            $data = getKey();
            echo json_encode(array("title" => "축하합니다! 뭐.. 기대한건 없죠?", "message" => $data['key']));
        } else {
            echo json_encode(array("title" => "아쉽네요", "message" => "이 쿠폰은 유효기간이 지났습니다."));
        }
    } else {
        echo json_encode(array("title" => "실패", "message" => "쿠폰 코드가 올바르지 않습니다."));
    }
}
