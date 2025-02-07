<?php
session_start();
$correct_password = "9091";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = $_POST['password'];
    
    if ($password === $correct_password) {
        echo "<script>alert('next Lv Password :B8YfZTyfRrfLEmq31yUlOEvkiwk0G1');</script>";
        $_SESSION['loggedin'] = true;
    } else {
        echo "<script>alert('Correct Password Plz!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6D28D9;          /* 보라색 계열 */
            --primary-light: #8B5CF6;    /* 밝은 보라 */
            --secondary: #4C1D95;        /* 진한 보라 */
            --background: #111827;       /* 진한 남색 배경 */
            --surface: #1F2937;          /* 컴포넌트 배경 */
            --text: #F9FAFB;            /* 밝은 텍스트 */
            --text-secondary: #9CA3AF;   /* 보조 텍스트 */
            --accent: #EC4899;          /* 강조색 (분홍) */
            --border: #374151;          /* 테두리 색상 */
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--background);
            color: var(--text);
            margin: 0;
            padding: 20px;
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            width: 100%;
            max-width: 400px;
            background: var(--surface);
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2),
                       0 0 0 1px rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: var(--text);
            font-weight: 600;
            letter-spacing: -0.5px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-secondary);
            font-weight: 500;
            font-size: 0.9rem;
        }

        .form-group input {
            width: 100%;
            padding: 12px 16px;
            background: var(--background);
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 16px;
            color: var(--text);
            transition: all 0.3s ease;
            box-sizing: border-box;
        }

        .form-group input:focus {
            border-color: var(--primary-light);
            outline: none;
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.2);
        }

        .btn {
            background: var(--primary);
            color: var(--text);
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn:hover {
            background: var(--primary-light);
            transform: translateY(-1px);
        }

        .btn:active {
            transform: translateY(0);
        }

        #hintButton {
            margin-top: 20px;
            background: var(--accent);
        }

        #hintButton:hover {
            background: #D946EF;
        }

        #hint {
            margin-top: 15px;
            color: var(--accent);
            font-size: 0.9rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>LOGIN</h1>
        <form method="post">
            <div class="form-group">
                <label for="password">PASSWORD</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn">LOGIN</button>
        </form>
        <button id="hintButton" class="btn" onclick="showHint()">HINT</button>
        <p id="hint" style="display:none;">pw : 4-Random int</p>
    </div>

    <script>
        function showHint() {
            document.getElementById('hint').style.display = 'block';
        }
    </script>
</body>
</html>
