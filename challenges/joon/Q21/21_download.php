<?php
header('Content-Type: text/html; charset=utf-8');

// 스타일 정의
$style = <<<EOT
<style>
    body {
        background: #0a0a0f;
        color: #00ff00;
        font-family: 'Courier New', monospace;
        padding: 20px;
        line-height: 1.6;
        margin: 0;
    }

    .memo {
        background: rgba(0, 0, 0, 0.8);
        border: 1px solid #00f3ff;
        padding: 20px;
        box-shadow: 0 0 10px #00f3ff;
        max-width: 800px;
        margin: 0 auto;
        position: relative;
        overflow: hidden;
    }

    .memo::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 2px;
        background: #00f3ff;
        animation: scan 2s linear infinite;
    }

    @keyframes scan {
        0% { transform: translateY(0); }
        100% { transform: translateY(100%); }
    }

    .memo-header {
        border-bottom: 1px solid #00f3ff;
        padding-bottom: 10px;
        margin-bottom: 20px;
        color: #00f3ff;
    }

    .memo-content {
        position: relative;
        padding: 10px;
    }

    pre {
        white-space: pre-wrap;
        word-wrap: break-word;
        margin: 0;
    }

    .timestamp {
        color: #888;
        font-size: 0.8em;
        margin-top: 10px;
    }
</style>
EOT;

if(isset($_GET['file'])) {
    $file = $_GET['file'];
    
    // URL 직접 접근 체크 추가
    if (strpos($file, 'http://') === 0 || strpos($file, 'https://') === 0) {
        echo <<<HTML
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Access Denied</title>
            $style
        </head>
        <body>
            <div class="memo">
                <div class="memo-header">
                    <h2>[ ACCESS DENIED ]</h2>
                </div>
                <div class="memo-content">
                    <pre>직접 URL 접근은 불가능합니다.</pre>
                    <div class="hint" style="color: #888; margin-top: 15px; font-size: 0.9em;">
                        힌트: Directory Traversal 취약점을 이용하세요.
                    </div>
                </div>
            </div>
        </body>
        </html>
HTML;
        exit;
    }
    
    // Directory Traversal 취약점을 이용한 플래그 파일 접근
    if ($file === '../secret/password.txt') {
        $flag_content = file_get_contents('/var/flag/Q21/password.txt');
        if ($flag_content) {
            echo <<<HTML
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <title>File Content</title>
                $style
            </head>
            <body>
                <div class="memo">
                    <div class="memo-header">
                        <h2>[ CONGRATULATIONS! ]</h2>
                    </div>
                    <div class="memo-content">
                        <pre>{$flag_content}</pre>
                    </div>
                </div>
            </body>
            </html>
HTML;
            exit;
        }
    }
    
    // docs 디렉토리 내 파일 접근
    if (strpos($file, 'docs/') === 0) {
        $safe_path = __DIR__ . '/' . $file;
        if(file_exists($safe_path)) {
            $content = file_get_contents($safe_path);
            $timestamp = date("Y-m-d H:i:s", filemtime($safe_path));
            
            echo <<<HTML
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <title>File Content</title>
                $style
            </head>
            <body>
                <div class="memo">
                    <div class="memo-header">
                        <h2>[ FILE CONTENT: {$file} ]</h2>
                        <div class="timestamp">Last modified: {$timestamp}</div>
                    </div>
                    <div class="memo-content">
                        <pre>{$content}</pre>
                    </div>
                </div>
            </body>
            </html>
HTML;
            exit;
        }
    }
    
    // 파일을 찾을 수 없음
    echo <<<HTML
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Error</title>
        $style
    </head>
    <body>
        <div class="memo">
            <div class="memo-header">
                <h2>[ ERROR ]</h2>
            </div>
            <div class="memo-content">
                <pre>File not found: {$file}</pre>
                <div class="hint" style="color: #888; margin-top: 15px; font-size: 0.9em;">
                    힌트: URL에 직접 접근은 불가능합니다. Directory Traversal 취약점을 이용하세요.
                </div>
            </div>
        </div>
    </body>
    </html>
HTML;
}
?> 