<?php

include './../db/maindb.php';
$count_data = include './../php/getCount.php';

$Ccount = $count_data['Ccount'];
$Tpoint = $count_data['Tpoint'];
$Ecount = $count_data['Ecount'];
$Ncount = $count_data['Ncount'];
$Hcount = $count_data['Hcount'];

// 유저의 첼린지 카운트 수치를 불러오는 php
session_start();
$user_id = $_SESSION['userid'];;

// 
$stmt = $conn->prepare("SELECT c_point,c_difficulty FROM challenges_data INNER JOIN user_data ON challenges_data.c_id = user_data.d_cid WHERE d_uid=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$point = 0;
$data = [];
$difficulty = [0, 0, 0];



    while ($row = $result->fetch_assoc()) {

        $point += $row['c_point'];
        if ($row['c_difficulty'] == 'Easy') {
            $difficulty[0]++;
        } else if ($row['c_difficulty'] == 'Normal') {
            $difficulty[1]++;
        } else if ($row['c_difficulty'] == 'Hard') {
            $difficulty[2]++;
        } else {
        }
    }

    // 직접 결과 데이터 구성
    $data = [
        'point' => $point,
        'difficulty' => $difficulty,
        'Ccount' => $Ccount,
        'Tpoint' => $Tpoint,
        'Ecount' => $Ecount,
        'Ncount' => $Ncount,
        'Hcount' => $Hcount
    ];

    // JSON으로 인코딩하여 출력
    header('Content-Type: application/json');
    echo json_encode($data);

