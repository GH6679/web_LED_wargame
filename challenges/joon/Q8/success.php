<?php
if (!isset($_COOKIE['ledcookie']) || $_COOKIE['ledcookie'] !== 'cookiecheck') {
    header("Location: check.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>미션 성공!</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="navbar">LEDTEAM 워게임 - Q8</div>
    
    <div class="content">
        <div class="challenge-box">
            <h1>🎉 축하합니다!</h1>
            <div class="description">
                <p>성공적으로 쿠키를 조작하여 접근에 성공했습니다.</p>
                <p>패스워드: <strong>ZgxkcozdtbINQ9dn9czugjt2ALovtC</strong></p>
            </div>
        </div>
    </div>
</body>
</html> 