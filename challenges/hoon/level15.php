<?php
session_start();

$flag = "FLAG{NfQsjnZzdDeNHvpx6zXZWncaCDuBBf}";
$error_msg = "";
$success_msg = "";

// 관리자 봇 시뮬레이션
if(isset($_POST['post'])) {
    $content = $_POST['post'];
    
    // 기본적인 필터링 (일부러 느슨하게 설정)
    $filtered = preg_replace('/script|alert|cookie/i', '🤔', $content);
    
    $_SESSION['latest_post'] = $filtered;
    
    // 관리자 봇 시뮬레이션
    if(strpos($filtered, 'fetch') !== false || strpos($filtered, 'img') !== false) {
        // 관리자의 쿠키를 설정
        setcookie("admin_secret", $flag, time() + 3600, "/");
        $_SESSION['admin_view'] = true;
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>InstaHack - Social Media Challenge</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Noto Sans KR', sans-serif;
            background: #121212;
            margin: 0;
            padding: 0;
            color: #e0e0e0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            box-sizing: border-box;
        }
        .header {
            background: linear-gradient(45deg, #405DE6, #5851DB, #833AB4, #C13584, #E1306C, #FD1D1D);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .post-form {
            background: #1e1e1e;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            margin-bottom: 20px;
        }
        textarea {
            width: 100%;
            padding: 15px;
            border: 1px solid #333;
            border-radius: 5px;
            margin-bottom: 10px;
            font-size: 16px;
            background: #2d2d2d;
            color: #e0e0e0;
            box-sizing: border-box;
            resize: vertical;
        }
        button {
            background: #E1306C;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s;
            width: auto;
            display: block;
        }
        button:hover {
            background: #C13584;
            transform: translateY(-2px);
        }
        .post {
            background: #1e1e1e;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        .hint {
            background: #2a1f3d;
            color: #e0e0e0;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #833AB4;
        }
        .error {
            background: #3d1f1f;
            color: #ff6b6b;
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .success {
            background: #1f3d25;
            color: #69db7c;
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .post-header {
            border-bottom: 1px solid #333;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .post-content {
            word-wrap: break-word;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔥 InstaHack Challenge 🔥</h1>
            <p>관리자의 쿠키를 탈취하고 인플루언서가 되어보세요!</p>
        </div>

        <div class="hint">
            <h3>🎯 미션</h3>
            <p>우리의 인스타 클론 사이트에 관리자가 정기적으로 접속해서 게시물을 검토합니다.</p>
            <p>XSS 취약점을 이용해 관리자의 쿠키를 탈취하세요!</p>
            <p>필터링을 우회하면 인플루언서 인증마크(🔵)를 획득할 수 있습니다!</p>
        </div>

        <div class="post-form">
            <h2>✨ 새 게시물 작성</h2>
            <form method="POST">
                <textarea name="post" placeholder="무슨 생각을 하고 계신가요? #해시태그 #YOLO" rows="4"></textarea>
                <button type="submit">게시하기 📸</button>
            </form>
        </div>

        <?php if(isset($_SESSION['latest_post'])): ?>
        <div class="post">
            <div class="post-header">
                <span>👤 Anonymous</span>
                <span style="float: right">⚡️ 방금 전</span>
            </div>
            <div class="post-content">
                <?php echo $_SESSION['latest_post']; ?>
            </div>
            <!-- 관리자만 볼 수 있는 쿠키: admin_secret=<?php echo $flag; ?> -->
        </div>
        <?php endif; ?>
    </div>

    <script>
        // 관리자 봇 시뮬레이션
        if(document.cookie.includes('admin_view=true')) {
            // 관리자 권한으로 페이지 확인 중...
            console.log("관리자가 페이지를 확인하고 있습니다! 🕵️‍♂️");
        }
    </script>
</body>
</html> 