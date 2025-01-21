<?php

include_once './../db/maindb.php';

// JSON 데이터 읽기
$json = file_get_contents('php://input');
$data = json_decode($json, true); // true를 주면 연관 배열로 변환

$username = htmlspecialchars($data['username']);

$stmt = $conn->prepare("SELECT username FROM users WHERE username=?");
$stmt->bind_param("s", $username);
$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows > 0){
    echo json_encode([
        'success' => false,
        'message' => '이미 있는 Id 입니다.',
        'color' => 'red',
    ]);
}else{
    echo json_encode([
        'success' => true,
        'message' => '사용 가능한 Id 입니다.',
        'color' => 'green',
    ]);
}
