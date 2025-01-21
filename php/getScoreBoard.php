<?php
// 순위 정보를 가져오는 파일 (총 10위 까지)
include_once './../db/maindb.php';
$count_data = include './../php/getCount.php';

$Ccount = $count_data['Ccount'];
$Tpoint = $count_data['Tpoint'];

$sql = "SELECT nickname,SUM(challenges_data.c_point) as total_point,COUNT(user_data.d_cid) AS total_solved,MAX(user_data.d_time) AS latest_date FROM users INNER JOIN user_data ON user_data.d_uid = users.u_id INNER JOIN challenges_data ON challenges_data.c_id = user_data.d_cid GROUP BY users.u_id ORDER BY total_point DESC LIMIT 10";
$result = mysqli_query($conn, $sql);

$data = [];

while($row = mysqli_fetch_assoc($result)){

    foreach($row as $value){

        $row['Ccount'] = $Ccount;
        $row['Tpoint'] = $Tpoint;
        
    }

    $data[] = $row;

}

echo json_encode($data);

?>

