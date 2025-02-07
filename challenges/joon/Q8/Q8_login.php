<?php
error_reporting(0);
$_="\x62\x47\x56\x6b\x59\x32\x39\x76\x61\x32\x6c\x6c";$__="\x59\x32\x39\x76\x61\x32\x6c\x6c\x59\x32\x68\x6c\x59\x32\x73\x3d";
$___=base64_decode($_);$____=base64_decode($__);@setcookie($___,'wrong_value',time()+3600,'/');
if(@$_COOKIE[$___]===$____){header("Location: ".mt_rand().".html");exit;}header("Location: Q8_cookie.html");
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LED TEAM 워게임 - Mission #8</title>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;600&family=Source+Code+Pro&display=swap" rel="stylesheet">
    <style>
        :root {
            --neon-blue: #00f3ff;
            --neon-pink: #ff00ff;
            --dark-bg: #0a0a0f;
            --terminal-green: #33FF00;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: var(--dark-bg);
            color: #fff;
            font-family: 'Rajdhani', sans-serif;
            min-height: 100vh;
            background-image: 
                linear-gradient(0deg, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.4) 100%),
                url('../images/cyber-bg.jpg');
            background-size: cover;
            background-attachment: fixed;
        }

        .cyber-header {
            background: linear-gradient(45deg, #ff00ff22, #00f3ff22);
            padding: 20px;
            text-align: center;
            border-bottom: 2px solid var(--neon-blue);
            box-shadow: 0 0 20px rgba(0,243,255,0.3);
        }

        .cyber-title {
            font-size: 2.5em;
            text-transform: uppercase;
            text-shadow: 
                0 0 5px var(--neon-blue),
                0 0 10px var(--neon-blue),
                0 0 15px var(--neon-pink);
            letter-spacing: 3px;
        }

        .cyber-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
        }

        .mission-box {
            background: rgba(0,0,0,0.8);
            border: 1px solid var(--neon-blue);
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0,243,255,0.1);
        }

        .error-box {
            background: rgba(255,0,0,0.1);
            border: 1px solid rgba(255,0,0,0.3);
            padding: 2rem;
            margin: 20px;
            border-radius: 8px;
            backdrop-filter: blur(5px);
            text-align: center;
        }

        h2 {
            color: var(--neon-pink);
            margin-bottom: 1rem;
            text-shadow: 0 0 5px var(--neon-pink);
        }

        p {
            margin: 0.5rem 0;
            color: #fff;
            text-shadow: 0 0 2px #fff;
        }
    </style>
</head>
<body>
    <header class="cyber-header">
        <h1 class="cyber-title">ACCESS DENIED</h1>
    </header>

    <div class="cyber-container">
        <div class="mission-box">
            <div class="error-box">
                <h2>⚠️ 접근 거부됨</h2>
                <p>잘못된 접근입니다. 쿠키(cookie)를 체크(check)해보세요.</p>
                <p>현재 쿠키 값이 올바르지 않습니다.</p>
            </div>
        </div>
    </div>
</body>
</html>
