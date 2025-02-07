<?php
session_start();

$flag = "FLAG{tQ5pMCwheie5s8imxyJRmMJmIWMsxf}";
$upload_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['uploadedFile'])) {
        $file = $_FILES['uploadedFile'];
        $fileName = $file['name'];
        $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        // 의도적으로 취약한 파일 체크
        $allowed = array('jpg', 'jpeg', 'png', 'gif');
        
        if (in_array($fileType, $allowed)) {
            // 업로드 디렉토리 설정
            $uploadDir = '/var/www/html/uploads/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            // 파일 저장
            $uploadPath = $uploadDir . basename($fileName);
            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                // 파일 내용 검증
                $fileContent = file_get_contents($uploadPath);
                if (strpos($fileContent, '<?php') !== false && 
                    (strpos($fileContent, 'system') !== false || 
                     strpos($fileContent, 'shell_exec') !== false || 
                     strpos($fileContent, 'exec') !== false)) {
                    // PHP 코드가 포함되어 있고 시스템 명령어를 실행하려고 시도하는 경우
                    $output = shell_exec("cat /var/www/html/hoon/level19.php");
                    if ($output && strpos($output, $flag) !== false) {
                        $upload_message = "🎉 축하합니다! 플래그를 찾았습니다: " . $flag;
                    } else {
                        $upload_message = "❌ 플래그를 찾지 못했습니다. 다시 시도해보세요.";
                    }
                } else {
                    $upload_message = "✅ 파일이 업로드되었습니다: " . htmlspecialchars($fileName);
                    $upload_message .= "<br>파일 위치: <a href='/uploads/" . htmlspecialchars(basename($fileName)) . "' target='_blank'>/uploads/" . htmlspecialchars(basename($fileName)) . "</a>";
                    $upload_message .= "<br>❌ 하지만 올바른 페이로드가 아닙니다.";
                }
            } else {
                $upload_message = "❌ 파일 업로드 중 오류가 발생했습니다.";
            }
        } else {
            $upload_message = "❌ 이미지 파일만 업로드 가능합니다.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>File Upload Challenge</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #1a1b26;
            --bg-secondary: #24283b;
            --text-primary: #a9b1d6;
            --text-bright: #c0caf5;
            --accent-color: #7aa2f7;
            --error-color: #f7768e;
            --success-color: #9ece6a;
            --border-color: #414868;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            margin: 0;
            padding: 20px;
            line-height: 1.6;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 30px;
            background: var(--bg-secondary);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .challenge-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .challenge-title {
            color: var(--text-bright);
            font-size: 2.2rem;
            margin-bottom: 10px;
        }

        .upload-container {
            background: var(--bg-primary);
            border: 2px dashed var(--border-color);
            border-radius: 12px;
            padding: 60px 20px;
            text-align: center;
            margin: 30px 0;
            transition: all 0.3s ease;
            min-height: 250px;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            box-sizing: border-box;
        }

        .upload-container:hover {
            border-color: var(--accent-color);
        }

        .file-input {
            display: none;
        }

        .upload-btn {
            position: relative;
            background: var(--accent-color);
            color: var(--bg-primary);
            padding: 12px 24px;
            border-radius: 8px;
            border: none;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 2;
            margin-top: 20px;
        }

        .upload-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(122, 162, 247, 0.2);
        }

        .hint-box {
            background: rgba(122, 162, 247, 0.1);
            border-left: 4px solid var(--accent-color);
            padding: 20px;
            margin: 30px 0;
            border-radius: 0 8px 8px 0;
        }

        .file-info {
            margin-top: 20px;
            color: var(--text-bright);
            position: relative;
            z-index: 2;
            width: 100%;
            word-break: break-all;
        }

        .message {
            margin-top: 20px;
            padding: 15px;
            border-radius: 8px;
            animation: fadeIn 0.3s ease;
        }

        .message.error {
            background: rgba(247, 118, 142, 0.1);
            color: var(--error-color);
        }

        .message.success {
            background: rgba(158, 206, 106, 0.1);
            color: var(--success-color);
        }

        .file-types {
            position: relative;
            display: flex;
            justify-content: center;
            gap: 10px;
            color: var(--text-primary);
            font-size: 0.9rem;
            margin-top: 80px;
            width: 100%;
            flex-wrap: wrap;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        #dropZone {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 50px;
        }

        #dropZone::after {
            content: '📁 파일을 드래그하거나 클릭하여 업로드하세요';
            position: absolute;
            top: 60%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: var(--text-primary);
            pointer-events: none;
            width: 90%;
            text-align: center;
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="challenge-header">
            <h1 class="challenge-title">File Upload Challenge</h1>
            <p>이미지 파일만 업로드할 수 있습니다... 정말로?</p>
        </div>

        <form action="" method="POST" enctype="multipart/form-data">
            <div class="upload-container" id="dropZone">
                <input type="file" name="uploadedFile" id="fileInput" class="file-input">
                <label for="fileInput" class="upload-btn">파일 선택</label>
                <div class="file-types">
                    <span>Allowed: JPG, JPEG, PNG, GIF</span>
                </div>
                <div class="file-info" id="fileInfo"></div>
                <button type="submit" class="upload-btn" style="margin-top: 20px;">업로드</button>
            </div>

            <?php if($upload_message): ?>
                <div class="message <?php echo strpos($upload_message, '✅') !== false ? 'success' : 'error'; ?>">
                    <?php echo $upload_message; ?>
                </div>
            <?php endif; ?>
        </form>
    </div>

    <script>
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('fileInput');
        const fileInfo = document.getElementById('fileInfo');

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.style.borderColor = 'var(--accent-color)';
        });

        dropZone.addEventListener('dragleave', (e) => {
            e.preventDefault();
            dropZone.style.borderColor = 'var(--border-color)';
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            fileInput.files = e.dataTransfer.files;
            updateFileInfo();
        });

        fileInput.addEventListener('change', updateFileInfo);

        function updateFileInfo() {
            if (fileInput.files.length > 0) {
                const file = fileInput.files[0];
                fileInfo.textContent = `선택된 파일: ${file.name} (${formatSize(file.size)})`;
            }
        }

        function formatSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
    </script>
</body>
</html>
