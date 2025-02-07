<?php
if (isset($_COOKIE['ledcookie']) && $_COOKIE['ledcookie'] === 'cookiecheck') {
    // 성공 페이지로 리다이렉트
    header("Location: success.php");
    exit();
} else {
    // 실패 메시지 표시
    $error = true;
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>쿠키 체크</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="navbar">LEDTEAM 워게임 - Q8</div>
    
    <div class="content">
        <div class="challenge-box">
            <?php if (isset($error)): ?>
            <h1>⚠️ 접근 거부</h1>
            <div class="description">
                <p>올바른 쿠키 값이 설정되지 않았습니다.</p>
                <p>Burp Suite를 사용하여 쿠키를 조작한 후 다시 시도해주세요.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html> 