<?php

include './../db/maindb.php';
session_start();
$user_id = $_SESSION['userid'];;

// 유저의 첼린지 데이터를 불러오는 php
$stmt = $conn->prepare("SELECT c_id,c_title,c_difficulty,c_point,c_solves,d_time FROM challenges_data INNER JOIN user_data ON challenges_data.c_id = user_data.d_cid WHERE d_uid=? ORDER BY d_time DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        
        $data[] = $row;

    }
}

echo json_encode($data);

?>