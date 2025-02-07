<?php
session_start();
$flag = "FLAG{7OkR4RXwxzWh5YeU4dyAFeAe5Ej5zv}";

// POST 요청 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['check_flag'])) {
    if (isset($_COOKIE['js_deobf_success']) && $_COOKIE['js_deobf_success'] === '1') {
        echo $flag;
        exit;
    }
}

// 난독화된 코드 수정 - 쿠키 설정도 난독화
$obfuscated_code = "
function _0x4e4999(_0x227ed3){
    var _0x4b3f33=['value','charCodeAt','FLAG','Wrong!','Correct!','cookie','js_deobf_success=1'];
    var _0x5a6de4=document.getElementById('flag')[_0x4b3f33[0]];
    var _0x2a=[67,84,70,123,106,115,95,100,101,111,98,102,125];
    var _0x4d1d=String.fromCharCode(..._0x2a);
    if(_0x5a6de4===_0x4d1d){
        var _0x3f=['d','o','c','u','m','e','n','t'];
        var _0x4f=_0x3f.join('');
        var _0x5f=['c','o','o','k','i','e'];
        var _0x6f=_0x5f.join('');
        window[_0x4f][_0x6f]=_0x4b3f33[6];
        return _0x4b3f33[4];
    }
    return _0x4b3f33[3];
}
";
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>JavaScript Deobfuscation Challenge</title>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #1a1a1a;
            --text-color: #ffffff;
            --primary-color: #3b82f6;
            --code-bg: #2d2d2d;
            --success-color: #22c55e;
            --error-color: #ef4444;
            --input-bg: #2a2a2a;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-color);
            color: var(--text-color);
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: #2d2d2d;
            box-shadow: 0 0 20px rgba(0,0,0,0.3);
            border-radius: 10px;
        }

        .challenge-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .challenge-title {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .code-container {
            background: var(--code-bg);
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            overflow-x: auto;
        }

        .code-text {
            font-family: 'JetBrains Mono', monospace;
            color: #e4e4e7;
            font-size: 0.9rem;
            line-height: 1.5;
            white-space: pre-wrap;
            word-break: break-all;
        }

        .input-section {
            margin: 2rem 0;
            text-align: center;
        }

        .flag-input {
            width: 100%;
            max-width: 400px;
            padding: 12px;
            border: 2px solid #3d3d3d;
            border-radius: 6px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 1rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            background: var(--input-bg);
            color: var(--text-color);
        }

        .flag-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }

        .submit-btn {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .submit-btn:hover {
            background: #2563eb;
            transform: translateY(-1px);
        }

        .hint-box {
            background: #2a2a2a;
            border-left: 4px solid var(--primary-color);
            padding: 15px;
            margin: 20px 0;
            border-radius: 0 6px 6px 0;
        }

        #result {
            margin-top: 1rem;
            padding: 10px;
            border-radius: 6px;
            text-align: center;
            display: none;
        }

        .success {
            background: rgba(34, 197, 94, 0.1);
            color: var(--success-color);
        }

        .error {
            background: rgba(239, 68, 68, 0.1);
            color: var(--error-color);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="challenge-header">
            <h1 class="challenge-title">JavaScript Deobfuscation Challenge</h1>
            <p>난독화된 JavaScript 코드를 분석하여 플래그를 찾으세요!</p>
        </div>

        <div class="hint-box">
            <h3>💡 힌트</h3>
            <p>1. ASCII 코드값을 문자로 변환해보세요.</p>
            <p>2. String.fromCharCode() 함수의 역할을 확인해보세요.</p>
            <p>3. 배열의 값들을 순서대로 변환하면 플래그가 나옵니다.</p>
        </div>

        <div class="code-container">
            <div class="code-text"><?php echo htmlspecialchars($obfuscated_code); ?></div>
        </div>

        <div class="input-section">
            <input type="text" id="flag" class="flag-input" placeholder="FLAG{...}">
            <button onclick="checkAnswer()" class="submit-btn">제출하기</button>
        </div>

        <div id="result"></div>
    </div>

    <script>
        <?php echo $obfuscated_code; ?>

        function checkAnswer() {
            const result = _0x4e4999();
            const resultDiv = document.getElementById('result');
            resultDiv.style.display = 'block';
            
            if(result.includes('Correct')) {
                // AJAX로 서버에 정답 확인 요청
                fetch('level18.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'check_flag=1'
                })
                .then(response => response.text())
                .then(data => {
                    resultDiv.textContent = result + ' ' + data;
                    resultDiv.className = 'success';
                    if(typeof fireworks !== 'undefined') {
                        fireworks.startShow();
                    }
                });
            } else {
                resultDiv.textContent = result;
                resultDiv.className = 'error';
            }
        }
    </script>
</body>
</html>