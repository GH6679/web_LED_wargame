<?php

// logout.php
session_start();

// 모든 세션 변수 삭제
$_SESSION = array();

// 세션 쿠키가 있으면 삭제
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 세션 파기
session_destroy();

// 로그아웃 후 리다이렉션 (예: 홈 페이지)
header("Location: /index.html");
exit();
?>
