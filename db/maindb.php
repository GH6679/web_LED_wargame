<?php

$host = 'localhost';
// $servername = "wargame-db-service"; // 쿠버네티스 Service 이름
$db = 'LED_WG';
$user = 'wargame';
$pass = '1234';
// MySQL 연결 생성
// $conn = new mysqli($servername, $user, $pass, $db);
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


?>
