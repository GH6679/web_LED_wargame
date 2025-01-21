<?php

include './../db/maindb.php';

session_start();
$user_id = $_SESSION['userid'];;

// user_data 에서 유저의 클리어 정보를 가져온다.
$stmt = $conn->prepare("SELECT d_cid FROM user_data WHERE d_uid = ?");
$stmt->bind_param("i", $user_id); // "s" 는 추가할 문자열의 갯수
$stmt->execute();
$ud_result = $stmt->get_result();

// solution_data 에서 문제 모음집을 불러온다.
$sql = "SELECT c_id,c_title,c_difficulty,c_point,c_solves,c_link FROM challenges_data";
$cd_result = $conn->query($sql);

// 결과를 저장할 배열 초기화
$data = array();

// 유저 데이터 대조용 저장 배열 초기화
$d_cid = [];


// user_data 데이터 검증
if ($ud_result->num_rows > 0) {


    while ($ud_row = $ud_result->fetch_assoc()) {

        $d_cid[] = $ud_row['d_cid'];
    }

    
}


    // solution_data 데이터 검증
    if ($cd_result->num_rows > 0) {

        while ($cd_row = $cd_result->fetch_assoc()) {

            foreach ($d_cid as $value) {

                if ($cd_row['c_id'] == $value) {
                    // s_id가 d_sid와 일치하는 데이터만 배열에 추가
                    $cd_row['c_clear'] = 'true';
                    break;
                } else {
                    $cd_row['c_clear'] = 'false';
                }
            }
            $data[] = $cd_row; // 연관 배열을 배열에 추가


        }
    }





// JSON 형식으로 인코딩
header('Content-Type: application/json');
echo json_encode($data);
