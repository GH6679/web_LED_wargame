<?php
// level21.php
session_start();

$flag = "FLAG{ZMrnaKcPUIY1BeX4zeB8W5e4IafOkT}";
$message = "";
$currentPage = isset($_GET['page']) ? $_GET['page'] : 'main';

function getContent($page) {
    // 기본 페이지들의 경로 설정
    $defaultPages = [
        'main' => '/hoon/pages/main.html',
        'about' => '/hoon/pages/about.html',
        'contact' => '/hoon/pages/contact.html'
    ];

    // 절대 경로 설정
    $basePath = $_SERVER['DOCUMENT_ROOT'];
    
    // 기본 페이지인 경우
    if(isset($defaultPages[$page])) {
        $filePath = $basePath . $defaultPages[$page];
        if(file_exists($filePath)) {
            return file_get_contents($filePath);
        }
    }
    
    // LFI 취약점 - PHP wrapper와 path traversal 허용
    if(strpos($page, 'php://') === 0) {
        // PHP wrapper 허용
        return file_get_contents($page);
    } else {
        // 일반 파일 접근 - 상대 경로 허용
        $filePath = realpath(dirname(__FILE__) . '/' . str_replace('../', '', $page));
        if($filePath === false) {
            $filePath = $basePath . '/' . $page;
        }
        if(file_exists($filePath)) {
            return file_get_contents($filePath);
        }
    }
    
    return "페이지를 찾을 수 없습니다. (Requested Path: " . htmlspecialchars($page) . ")";
}

// 디버깅 정보 추가
$debugInfo = "";
if(isset($_GET['debug'])) {
    $debugInfo = "Current Directory: " . getcwd() . "<br>";
    $debugInfo .= "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
    $debugInfo .= "Requested Page: " . htmlspecialchars($currentPage) . "<br>";
    $debugInfo .= "Script Path: " . __FILE__ . "<br>";
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>Secret Archive</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #7C3AED;
            --secondary: #4C1D95;
            --background: #F3F4F6;
            --text: #1F2937;
            --accent: #EC4899;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--background);
            color: var(--text);
            margin: 0;
            padding: 20px;
            line-height: 1.6;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            background: white;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .nav {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }

        .nav-link {
            color: var(--primary);
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            background: var(--primary);
            color: white;
        }

        .content {
            background: var(--background);
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }

        .hint {
            background: rgba(124, 58, 237, 0.1);
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            border-left: 4px solid var(--primary);
        }

        .secret-note {
            background: rgba(236, 72, 153, 0.1);
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            border-left: 4px solid var(--accent);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Secret Archive</h1>
        <div class="nav">
            <a href="?page=main" class="nav-link">Home</a>
            <a href="?page=about" class="nav-link">About</a>
            <a href="?page=contact" class="nav-link">Contact</a>
            
        </div>
        
        <div class="content">
            <?php 
            if($debugInfo) {
                echo "<div class='debug-info'>" . $debugInfo . "</div>";
            }
            echo getContent($currentPage); 
            ?>
        </div>

        <div class="hint">  
            <h3>🔍 개발자 노트</h3>
            <p>페이지 include 시스템을 구현했습니다. 보안을 위해 허용된 페이지만 접근 가능하도록 했어요!</p>
            <p>모든 페이지는 /hoon/pages 디렉토리에 있습니다.</p>
        </div>

        <div class="secret-note">
            <h3>🔒 관리자 메모</h3>
            <p>FLAG는 특정 디렉토리에 저장되어 있습니다.</p>
            <p>절대 외부에서 접근할 수 없게 해두었으니 안심하세요!</p>
        </div>
    </div>
</body>
</html>
