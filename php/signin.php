<?php

include_once './../db/maindb.php';
session_start();

// JSON 데이터 읽기
$json = file_get_contents('php://input');
$data = json_decode($json, true); // true를 주면 연관 배열로 변환

$username = htmlspecialchars($data['username']);
$password = htmlspecialchars($data['password']);
$password_check = htmlspecialchars($data['password_check']);
$nickname = htmlspecialchars($data['nickname']);

// 유효성 검사
if (!isset($username) || $username == '') {
    echo json_encode([
        'success' => false,
        'type' => 'msg',
        'color' => 'red',
        'index' => 0,
        'message' => '아이디를 입력해주세요.'
    ]);
    exit;
}

if (!isset($nickname) || $nickname == '') {
    echo json_encode([
        'success' => false,
        'type' => 'msg',
        'color' => 'red',
        'index' => 1,
        'message' => '닉네임을 입력해주세요.'
    ]);
    exit;
}

if (!isset($password) || $password == '') {
    echo json_encode([
        'success' => false,
        'type' => 'msg',
        'color' => 'red',
        'index' => 2,
        'message' => '비밀번호를 입력해주세요.'
    ]);
    exit;
}

if (!isset($password_check) || $password_check == '') {
    echo json_encode([
        'success' => false,
        'type' => 'msg',
        'color' => 'red',
        'index' => 3,
        'message' => '비밀번호 확인을 입력해주세요.'
    ]);
    exit;
}

if ($password !== $password_check) {
    echo json_encode([
        'success' => false,
        'type' => 'msg',
        'color' => 'red',
        'index' => 3,
        'message' => '비밀번호가 일치하지 않습니다.'
    ]);
    exit;
}

// 아이디 중복 확인(인젝션 방지)
$stmt_u_check = $conn->prepare("SELECT * FROM users WHERE username=?"); 
$stmt_u_check->bind_param("s", $username);
$stmt_u_check->execute();
$result_u_check = $stmt_u_check->get_result();

if ($result_u_check->num_rows > 0) {
    echo json_encode([
        'success' => false,
        'type' => 'msg',
        'color' => 'red',
        'index' => 0,
        'message' => '이미 존재하는 아이디입니다.'
    ]);
    exit;
}


// Prepared Statement를 사용하여 SQL 인젝션 방지
$stmt_insert = $conn->prepare("INSERT INTO users values(null,?,?,?,'test@test.com','user')");
$stmt_insert->bind_param("sss", $nickname, $username, $password); // "s" 는 추가할 문자열의 갯수
$stmt_insert->execute();

if ($conn->affected_rows > 0) {

    $stmt_userdata = $conn->prepare("SELECT u_id FROM users WHERE username=?");
    $stmt_userdata->bind_param("s", $username);
    $stmt_userdata->execute();
    
    $result_userdata = $stmt_userdata->get_result();
    $id = "";

    //변수에 값 저장
    if ($result_userdata->num_rows > 0) {

        while ($row = $result_userdata->fetch_assoc()) {
            $id = $row['u_id'];
        }
    }


    // 세션에 정보 저장
    $_SESSION['user_access'] = true;
    $_SESSION['userid'] = $id;
    $_SESSION['username'] = $username;
    $_SESSION['nickname'] = $nickname;
    $_SESSION['user_role'] = 'user';
    echo json_encode([
        'success' => true,
        'message' => '회원가입 성공']);
} else {

    echo json_encode([
        'success' => false, 
        'message' => '회원가입 실패']);
}
