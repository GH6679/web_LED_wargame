<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

try {

    require_once '/var/www/private/level_key/level27_key.php';
    $data = getKey($_POST['username'], $_POST['password']);
    
    // JSON 인코딩
    $json = json_encode($data, JSON_THROW_ON_ERROR);
    
    echo $json;
} catch (Exception $e) {
    // 오류 발생시 에러 메시지를 JSON 형식으로 반환
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);

    
}
?>
