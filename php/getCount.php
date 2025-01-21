<?php 
// 총 문제수를 불러오는 php

include './../db/maindb.php';
$data = [];

$sql = "SELECT COUNT(*), SUM(c_point),SUM(c_difficulty='Easy') as Ecount,SUM(c_difficulty='Normal') as Ncount,SUM(c_difficulty='Hard') as Hcount,SUM(c_solves) as Tsolves FROM challenges_data";
$result = $conn->query($sql);
$row = $result->fetch_assoc();


$sql_u = "SELECT COUNT(*) as u_count FROM users";
$result_u = $conn->query($sql_u);
$row_u = $result_u->fetch_assoc();
// 전체 문제 횟수
$data['Ccount'] = $row['COUNT(*)'];
// 전체 점수
$data['Tpoint'] = $row['SUM(c_point)'];
// 쉬움 문제 횟수
$data['Ecount'] = $row['Ecount'];
// 보통 문제 횟수
$data['Ncount'] = $row['Ncount'];
// 어려움 문제 횟수
$data['Hcount'] = $row['Hcount'];
// 전체 유저 수
$data['Ucount'] = $row_u['u_count'];
// 전체 해결 횟수
$data['Tsolves'] = $row['Tsolves'];
return $data;

?>