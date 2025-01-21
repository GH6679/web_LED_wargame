<?php
include './../db/maindb.php';
session_start();

date_default_timezone_set('Asia/Seoul'); // 한국 시간대로 설정

// JSON 데이터 읽기
$json = file_get_contents('php://input');
$data = json_decode($json, true); // true를 주면 연관 배열로 변환

$c_id = $data['c_id'];
$key = $data['key'];
$username = $data['username'];
$user_id = $_SESSION['userid'];

$stmt = $conn->prepare("SELECT c_key FROM challenges_data WHERE c_id = ?");
$stmt->bind_param("i", $c_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row['c_key'] == $key) {

    // db 처리
    $now = date('Y-m-d H:i:s');

    $stmt = $conn->prepare("INSERT INTO user_data values(null,?,?,?)");
    $stmt->bind_param("iis", $user_id, $c_id, $now);
    $stmt->execute();

    $stmt = $conn->prepare("UPDATE challenges_data SET c_solves = c_solves + 1 WHERE c_id = ?");
    $stmt->bind_param("i", $c_id);
    $stmt->execute();

    echo json_encode([
        'success' => true,
    ]);
} else {
    echo json_encode([
        'success' => false,
    ]);
}
