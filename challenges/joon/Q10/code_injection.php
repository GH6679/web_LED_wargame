    <?php
    // 파일 위치: /var/www/html/Q10/code_injection.php

    // 사용자 입력 받기
    $user_input = isset($_POST['input']) ? $_POST['input'] : '';

    // 페이지 헤더 및 설명
    echo "<h1 class='cyber-title'>PHP Code Injection 실습</h1>";
    echo "<div class='cyber-description'>아래 입력창에 PHP 코드를 입력해 결과를 확인해보세요.</div>";

    // 힌트 섹션 - 항상 표시
    echo "<div class='cyber-hint-box'>
        <div class='cyber-hint-title'>🔍 문제 힌트</div>
        <ul>
            <li>secret_key 변수에 특정 값을 할당해야 합니다</li>
            <li>필요한 값: ledteamcode.key</li>
            <li>flag 문자열을 출력해야 합니다</li>
            <li>예시 형식: \$variable = 'value'; echo 'string';</li>
        </ul>
    </div>";

    // 입력한 코드가 있을 경우 실행
    if ($user_input) {
        echo "<h2 class='cyber-subtitle'>결과</h2>";
        
        // 금지된 문자열 검사
        $blacklist = array('system');
        foreach ($blacklist as $banned) {
            if (stripos($user_input, $banned) !== false) {
                die('<div class="cyber-error">보안 위험: 금지된 함수가 감지되었습니다!</div>');
            }
        }

        // 문법 오류 체크를 위해 PHP 파서 오류 핸들링
        try {
            // PHP 파서로 코드 검사
            if (PHP_VERSION_ID >= 70300) {
                $result = @token_get_all("<?php " . $user_input . " ?>", TOKEN_PARSE);
            }
            
            // 고정된 키 값 사용
            $random_key = "ledteamcode.key";
            
            // eval() 실행
            eval($user_input);

            // 조건 검사
            if (isset($secret_key) && $secret_key === $random_key) {
                if (strpos($user_input, 'flag') !== false) {
                    echo "<script>alert('성공! 패스워드는 F1qzhfOa1IzokTvsGSq8OBpObGJdBR 입니다.');</script>";
                }
            }
        } catch (ParseError $e) {
            echo "<div class='cyber-error'>";
            echo "PHP 문법 오류: " . $e->getMessage();
            echo "</div>";
        } catch (Error $e) {
            echo "<div class='cyber-error'>";
            echo "실행 오류: " . $e->getMessage();
            echo "</div>";
        }
    } else {
        echo "<p class='cyber-text'>코드를 입력하고 제출해 결과를 확인하세요.</p>";
    }

    // CSS 스타일 수정
    echo "<style>
    :root {
        --neon-blue: #00f3ff;
        --neon-pink: #ff00ff;
        --neon-purple: #9d4edd;
        --dark-bg: #0a0a0f;
    }

    body {
        font-family: 'Rajdhani', 'Segoe UI', sans-serif;
        max-width: 900px;
        margin: 0 auto;
        padding: 20px;
        background-color: var(--dark-bg);
        color: #fff;
        background-image: 
            linear-gradient(45deg, rgba(0,243,255,0.1) 0%, rgba(157,78,221,0.1) 100%),
            repeating-linear-gradient(45deg, rgba(0,0,0,0.1) 0px, rgba(0,0,0,0.1) 2px, transparent 2px, transparent 4px);
    }

    .cyber-title {
        color: var(--neon-blue);
        text-align: center;
        text-transform: uppercase;
        font-size: 2.5em;
        text-shadow: 
            0 0 5px var(--neon-blue),
            0 0 10px var(--neon-blue),
            0 0 20px var(--neon-pink);
        letter-spacing: 3px;
        margin-bottom: 30px;
    }

    .cyber-description {
        text-align: center;
        color: var(--neon-purple);
        margin: 20px 0;
        font-size: 1.2em;
        text-shadow: 0 0 5px var(--neon-purple);
    }

    .cyber-hint-box {
        background: linear-gradient(45deg, rgba(0,243,255,0.1), rgba(157,78,221,0.1));
        border: 2px solid var(--neon-blue);
        padding: 20px;
        border-radius: 5px;
        margin: 20px 0;
        box-shadow: 
            0 0 10px var(--neon-blue),
            inset 0 0 10px var(--neon-blue);
    }

    .cyber-hint-title {
        font-size: 1.3em;
        font-weight: bold;
        color: var(--neon-pink);
        margin-bottom: 15px;
        text-shadow: 0 0 5px var(--neon-pink);
    }

    .cyber-hint-box ul {
        margin: 0;
        padding-left: 20px;
        list-style-type: none;
    }

    .cyber-hint-box li {
        margin: 10px 0;
        color: #fff;
        text-shadow: 0 0 2px #fff;
        position: relative;
        padding-left: 20px;
    }

    .cyber-hint-box li:before {
        content: '>';
        position: absolute;
        left: 0;
        color: var(--neon-blue);
    }

    textarea {
        width: 100%;
        padding: 15px;
        background: rgba(0,0,0,0.7);
        border: 2px solid var(--neon-blue);
        border-radius: 5px;
        color: var(--neon-blue);
        font-family: 'Source Code Pro', monospace;
        margin: 10px 0;
        resize: vertical;
        box-shadow: 0 0 10px var(--neon-blue);
    }

    input[type='submit'] {
        background: linear-gradient(45deg, var(--neon-blue), var(--neon-purple));
        color: #fff;
        border: none;
        padding: 15px 30px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1.1em;
        text-transform: uppercase;
        letter-spacing: 2px;
        transition: all 0.3s;
        box-shadow: 0 0 10px var(--neon-blue);
    }

    input[type='submit']:hover {
        transform: translateY(-2px);
        box-shadow: 
            0 0 20px var(--neon-blue),
            0 0 40px var(--neon-purple);
    }

    .cyber-error {
        background: rgba(255,0,0,0.2);
        border: 2px solid #ff0000;
        color: #ff0000;
        padding: 15px;
        border-radius: 5px;
        margin: 15px 0;
        text-shadow: 0 0 5px #ff0000;
    }

    .cyber-text {
        color: #fff;
        text-shadow: 0 0 2px #fff;
        text-align: center;
        font-size: 1.1em;
    }

    /* 텍스트 선택 방지 */
    body {
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    /* textarea만 선택 가능하도록 설정 */
    textarea {
        -webkit-user-select: text;
        -moz-user-select: text;
        -ms-user-select: text;
        user-select: text;
    }
    </style>";

    // HTML Form 수정
    echo "<form method='POST' action='' class='cyber-form'>
        <label for='input' style='color: var(--neon-blue); font-weight: bold; text-transform: uppercase; letter-spacing: 1px;'>
            PHP 코드 입력:
        </label><br>
        <textarea name='input' id='input' rows='5' cols='50' placeholder='여기에 PHP 코드를 입력하세요...'></textarea><br>
        <div style='text-align: center;'>
            <input type='submit' value='코드 실행'>
        </div>
    </form>";

    // 페이지 헤더에 보안 스크립트 추가
    echo "<script>
    // 개발자 도구 방지
    document.onkeydown = function(e) {
        if(e.keyCode == 123) { // F12
            return false;
        }
        if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)) { // Ctrl+Shift+I
            return false;
        }
        if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)) { // Ctrl+Shift+J
            return false;
        }
        if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)) { // Ctrl+U
            return false;
        }
    };

    // 오른쪽 마우스 클릭 방지
    document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
    });

    // 텍스트 선택 방지
    document.addEventListener('selectstart', function(e) {
        if (e.target.tagName !== 'TEXTAREA') {
            e.preventDefault();
        }
    });

    // 드래그 방지
    document.addEventListener('dragstart', function(e) {
        e.preventDefault();
    });

    // 개발자 도구 감지 및 경고
    let devtools = function() {};
    devtools.toString = function() {
        window.location.href = 'about:blank';
        return 'Warning!';
    }

    // 개발자 도구 콘솔 출력 방지
    console.log = devtools;
    console.info = devtools;
    console.warn = devtools;
    console.error = devtools;
    </script>";

    // 추가 보안 헤더 설정
    header('X-Frame-Options: DENY');
    header('X-Content-Type-Options: nosniff');
    header('X-XSS-Protection: 1; mode=block');
    ?>
