<?php
// 메인 화면 출력용 기본 정보를 받아오는 php
include './../db/maindb.php';
$count_data = include './../php/getCount.php';

$Ccount = $count_data['Ccount'];
$Tpoint = $count_data['Tpoint'];
$Ecount = $count_data['Ecount'];
$Ncount = $count_data['Ncount'];
$Hcount = $count_data['Hcount'];
$Ucount = $count_data['Ucount'];
$Tsolves = $count_data['Tsolves'];
$data = [];

$data['Ccount'] = $Ccount;
$data['Tpoint'] = $Tpoint;
$data['Ecount'] = $Ecount;
$data['Ncount'] = $Ncount;
$data['Hcount'] = $Hcount;
$data['Ucount'] = $Ucount;
$data['Tsolves'] = $Tsolves;
echo json_encode($data);


?>
