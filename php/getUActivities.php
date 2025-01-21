<?php
include './../db/maindb.php';

$sql = "SELECT * FROM user_data INNER JOIN challenges_data ON user_data.d_cid = challenges_data.c_id INNER JOIN users ON user_data.d_uid = users.u_id ORDER BY d_time DESC LIMIT 8";
$result = mysqli_query($conn, $sql);

$data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data);

?>      