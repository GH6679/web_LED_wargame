<?php


session_start();
include './db/maindb.php';





// form 컨트롤 php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // form에 form-name 값이 온지 확인
    if (isset($_POST['form-name'])) {

        // login form 일경우
        if ($_POST['form-name'] == 'login-form') {
            $username = htmlspecialchars($_POST['username']);
            $password = htmlspecialchars($_POST['password']);

            // Prepared Statement를 사용하여 SQL 인젝션 방지
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
            $stmt->bind_param("ss", $username, $password); // "s" 는 추가할 문자열의 갯수
            $stmt->execute();

            // 결과 가져오기(Select 일때만)
            $result_login = $stmt->get_result();


            // $sql_login = "SELECT * FROM users WHERE username='$username' AND password='$password'";
            // $result_login = $conn->query($sql_login);

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

                echo json_encode(['success' => true, 'message' => '로그인 성공']);

                exit();
            } else {

                echo json_encode(['success' => false, 'message' => '로그인 실패']);
            }


            // 화원가입 form 일 경우
        } elseif ($_POST['form-name'] == 'signin-form') {

            $username = htmlspecialchars($_POST['username']);
            $password = htmlspecialchars($_POST['password']);
            $nickname = htmlspecialchars($_POST['nickname']);

            // Prepared Statement를 사용하여 SQL 인젝션 방지
            $stmt = $conn->prepare("INSERT INTO users values(null,?,?,?,0,'test@test.com','user')");
            $stmt->bind_param("sss", $nickname, $username, $password); // "s" 는 추가할 문자열의 갯수
            $stmt->execute();

            if ($conn->affected_rows > 0) {

                $sql_userdata = "SELECT u_id FROM users WHERE username='$username'";
                $result_userdata = $conn->query($sql_userdata);
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
                $_SESSION['u_point'] = 0;
                // $_SESSION['email'] = $email; 
                $_SESSION['nickname'] = $nickname;
                $_SESSION['user_role'] = 'user';
                // $_SESSION['signin_success'] = true;
                echo json_encode(['success' => true, 'message' => '회원가입 성공']);
                
            } else {

                echo json_encode(['success' => false, 'message' => '회원가입 실패']);
            }
        } elseif ($_POST['form-name'] == 'sc-form') {
            $test_set = $_POST['sc-isweb'];

            if (isset($_POST['sc-isweb'])) {

                $p_title = $_POST['sc-title'];
                $p_web = true;
                $p_link = $_POST['sc-link'];
                $p_point = $_POST['sc-point'];
                $p_difficulty = "";
                $p_key = $_POST['sc-key'];

                if ($_POST['sc-difficulty'] == 1) {

                    $p_difficulty = "Normal";
                } elseif ($_POST['sc-difficulty'] == 2) {

                    $p_difficulty = "Hard";
                } else {

                    $p_difficulty = "Easy";
                }


                $stmt = $conn->prepare("INSERT INTO challenges_data VALUES(null,?,null,?,?,null,?,?,?,null,0)");
                $stmt->bind_param("sisiss", $p_title, $p_web, $p_link, $p_point, $p_difficulty, $p_key);
                $stmt->execute();


                echo json_encode(['success' => true, 'message' => '챌린지 생성 성공']);
                exit;
            } else {

                echo json_encode(['success' => false, 'message' => '챌린지 생성 실패']);
            }
        } else {
        }
    }
}





?>