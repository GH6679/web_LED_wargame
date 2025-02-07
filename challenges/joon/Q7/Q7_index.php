<?php
error_reporting(0);
$_="{\"r\":\"http:\\/\\/admin.ledteam.kr\"}";$r=json_decode($_,1)['r'];
if(@$_SERVER['HTTP_REFERER']===$r){echo"<div class='success-message'><h1>접근 성공!</h1><p>Flag: IPNDjvaTwSDDax0RFe7BvK5vDa12p6</p></div>";}else{echo"<div class='error-message'><h1>접근 거부됨</h1><p>이 페이지는 admin.ledteam.kr 에서만 접근할 수 있습니다.</p><p>현재 Referer: ".htmlspecialchars(@$_SERVER['HTTP_REFERER']?:'없음')."</p></div>";}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LEDTEAM 워게임</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: #0a0a0a;
            color: #fff;
            font-family: 'Segoe UI', Arial, sans-serif;
            line-height: 1.6;
        }

        .navbar {
            background: rgba(0, 0, 0, 0.9);
            backdrop-filter: blur(10px);
            color: #fff;
            padding: 1rem;
            text-align: center;
            font-size: 1.2rem;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
        }

        .content {
            max-width: 800px;
            margin: 100px auto 0;
            padding: 2rem;
        }

        .error-message, .success-message {
            padding: 2rem;
            border-radius: 8px;
            margin: 1rem 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(5px);
        }

        .error-message {
            background: rgba(255, 0, 0, 0.1);
            border: 1px solid rgba(255, 0, 0, 0.2);
        }

        .success-message {
            background: rgba(0, 255, 0, 0.1);
            border: 1px solid rgba(0, 255, 0, 0.2);
        }

        h1 {
            font-size: 1.8rem;
            margin-bottom: 1rem;
            font-weight: 500;
        }

        p {
            margin: 0.5rem 0;
            font-size: 1rem;
        }

        .success-message h1 {
            color: #00ff00;
        }

        .error-message h1 {
            color: #ff0000;
        }

        @media (max-width: 768px) {
            .content {
                padding: 1rem;
                margin-top: 80px;
            }
            
            h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">LEDTEAM 워게임 - Mission #7</div>
    <div class="content">
</div>
</body>
</html>