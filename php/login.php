<?php

include_once './../db/maindb.php';
session_start();

try {

    // JSON 데이터 읽기
    $json = file_get_contents('php://input');
    $data = json_decode($json, true); // true를 주면 연관 배열로 변환

    $username = htmlspecialchars($data['username']);
    $password = htmlspecialchars($data['password']);

    // 데이터 유효성 검사
    if (!isset($username) || $username == '') {
        echo json_encode([
            'success' => false,
            'type' => 'msg',
            'color' => 'red',
            'index' => 0,
            'message' => '아이디를 입력해주세요.',
        ]);
        exit;
    }
    if (!isset($password) || $password == '') {
        echo json_encode([
            'success' => false,
            'type' => 'msg',
            'color' => 'red',
            'index' => 1,
            'message' => '비밀번호를 입력해주세요.'
        ]);
        exit;
    }



    // Prepared Statement를 사용하여 SQL 인젝션 방지
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password); // "s" 는 추가할 문자열의 갯수
    $stmt->execute();

    // 결과 가져오기(Select 일때만)
    $result_login = $stmt->get_result();

    if ($result_login->num_rows > 0) {

        $sql_userdata = "SELECT u_point,email,nickname,user_role,u_id FROM users WHERE username='$username'";
        $result_userdata = $conn->query($sql_userdata);

        $u_point = "";
        $email = "";
        $nickname = "";

        //변수에 값 저장
        if ($result_userdata->num_rows > 0) {

            while ($row = $result_userdata->fetch_assoc()) {
                $u_point = $row['u_point'];
                $email = $row['email'];
                $nickname = $row['nickname'];
                $user_role = $row['user_role'];
                $id = $row['u_id'];
            }
        }

        // 세션에 정보 저장
        $_SESSION['user_access'] = true;
        $_SESSION['userid'] = $id;
        $_SESSION['username'] = $username;
        // $_SESSION['u_point'] = $u_point;
        // $_SESSION['email'] = $email; 
        $_SESSION['nickname'] = $nickname;
        $_SESSION['user_role'] = $user_role;

        try {
            echo json_encode([
                'success' => true,
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'title' => '로그인 실패',
                'message' => '로그인 완료 응답처리중 오류 발생',
                'end' => false
            ]);
        }

    } else {
        echo json_encode([
            'success' => false,
            'title' => '로그인 실패',
            'message' => 'ID 또는 비밀번호가 틀렸습니다.',
            'end' => false
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'title' => '오류',
        'message' => $e->getMessage(),
        'end' => false
    ]);
}
