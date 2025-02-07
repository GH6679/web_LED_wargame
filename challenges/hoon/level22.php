<?php
require_once __DIR__ . '/vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class JWTChallenge {
    private $secret_key = "your_weak_secret_2024";
    public $none_algo_flag = "flag{298A5boVuPe5cklcHNy7DcLj4cFIgA}";
    public $weak_secret_flag = "flag{weak_secret_key_cracked}";
    public $sign_confusion_flag = "flag{signature_algorithm_confusion}";
    
    public function generateToken($user_id, $role = 'user') {
        $payload = array(
            "user_id" => $user_id,
            "role" => $role,
            "exp" => time() + 3600
        );
        
        return JWT::encode($payload, $this->secret_key, 'HS256');
    }
    
    public function verifyToken($token) {
        try {
            // JWT 헤더를 파싱하여 알고리즘 확인
            $tks = explode('.', $token);
            if (count($tks) < 2) return false;
            
            $header = json_decode(base64_decode($tks[0]));
            if ($header->alg === 'none') {
                // none 알고리즘일 경우 페이로드만 디코드
                $payload = json_decode(base64_decode($tks[1]));
                return $payload;
            } else {
                // 다른 알고리즘의 경우 기존 방식대로 검증
                return JWT::decode($token, new Key($this->secret_key, 'HS256'));
            }
        } catch(Exception $e) {
            return false;
        }
    }
    
    public function checkAdmin($token) {
        $decoded = $this->verifyToken($token);
        if($decoded && $decoded->role === 'admin') {
            return true;
        }
        return false;
    }
}

// API 요청 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $challenge = new JWTChallenge();
    $action = $_POST['action'] ?? '';
    
    switch($action) {
        case 'login':
            $user_id = $_POST['user_id'] ?? '';
            $token = $challenge->generateToken($user_id);
            echo json_encode(['status' => 'success', 'token' => $token]);
            exit;
            
        case 'verify':
            $token = $_POST['token'] ?? '';
            $result = $challenge->verifyToken($token);
            if($result) {
                if($challenge->checkAdmin($token)) {
                    echo json_encode(['status' => 'success', 'message' => 'Admin access granted', 'flag' => $challenge->none_algo_flag]);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Admin access required']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Invalid token']);
            }
            exit;
    }
}

// HTML은 API 요청이 아닐 때만 출력
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
?>
<!DOCTYPE html>
<html>
<head>
    <title>JWT Security Challenge</title>
    <style>
        :root {
            --primary-color: #8ebbff;
            --secondary-color: #4a9eff;
            --background-color: #1a1a1a;
            --card-bg: #2d2d2d;
            --text-color: #ffffff;
            --success-color: #2ecc71;
            --danger-color: #e74c3c;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--background-color);
            color: var(--text-color);
            line-height: 1.6;
        }

        .container {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 0 20px;
        }

        h1 {
            text-align: center;
            color: var(--primary-color);
            margin-bottom: 2rem;
            font-size: 2.5rem;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .challenge-section {
            background: var(--card-bg);
            margin-bottom: 2rem;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease;
        }

        .challenge-section:hover {
            transform: translateY(-5px);
        }

        h2 {
            color: var(--primary-color);
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }

        p {
            color: #cccccc;
            margin-bottom: 1.5rem;
        }

        button {
            background-color: var(--secondary-color);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        button:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }

        #token-display {
            margin-top: 1rem;
            padding: 1rem;
            background-color: #f8f9fa;
            border-radius: 5px;
            word-break: break-all;
            font-family: monospace;
            font-size: 0.9rem;
            border: 1px solid #dee2e6;
        }

        .result {
            margin-top: 1rem;
            padding: 1rem;
            border-radius: 5px;
        }

        .success {
            background-color: var(--success-color);
            color: white;
        }

        .error {
            background-color: var(--danger-color);
            color: white;
        }

        /* 스타일 추가 */
        .verify-section {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
        }

        .verify-section h3 {
            margin-bottom: 15px;
            color: var(--secondary-color);
        }

        #token-input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            font-family: monospace;
            resize: vertical;
        }

        #verify-result {
            margin-top: 15px;
            padding: 10px;
            border-radius: 5px;
            display: none;
        }

        #verify-result.success {
            display: block;
            background-color: var(--success-color);
            color: white;
        }

        #verify-result.error {
            display: block;
            background-color: var(--danger-color);
            color: white;
        }

        .hint-section {
            background: #2d2d2d;
            margin-top: 2rem;
            padding: 2rem;
            border-radius: 10px;
            border-left: 4px solid var(--secondary-color);
        }

        .hint-section h3 {
            color: var(--secondary-color);
            margin-bottom: 1rem;
        }

        .hint-content {
            font-family: monospace;
            line-height: 1.5;
        }

        .hint-content pre {
            background: #1a1a1a;
            color: #ecf0f1;
            padding: 1rem;
            border-radius: 5px;
            overflow-x: auto;
            margin: 1rem 0;
        }

        .hint-tip {
            color: var(--secondary-color);
            font-weight: bold;
            margin-top: 1rem;
        }

        #token-display {
            background: #1a1a1a;
            color: #ecf0f1;
            padding: 1rem;
            border-radius: 5px;
            margin-top: 1rem;
            word-break: break-all;
            font-family: monospace;
        }

        #token-input {
            background: #1a1a1a;
            color: #ecf0f1;
            border: 1px solid #3d3d3d;
        }

        button {
            background-color: var(--secondary-color);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        button:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(74, 158, 255, 0.2);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>JWT Security Challenge</h1>
        
        <div class="challenge-section">
            <h2>None Algorithm Challenge</h2>
            <p>Try to bypass the signature verification by manipulating the JWT algorithm.</p>
            <button onclick="getNormalToken()">Get Normal Token</button>
            <div id="token-display"></div>
            
            <div class="verify-section">
                <h3>Verify Token</h3>
                <textarea id="token-input" placeholder="Enter your modified token here..." rows="4"></textarea>
                <button onclick="verifyUserToken()">Verify Token</button>
                <div id="verify-result"></div>
            </div>
        </div>

        <div class="hint-section">
            <h3>💡 Hints</h3>
            <div class="hint-content">
                <p>JWT 토큰의 구조:</p>
                <pre>
header.payload.signature

1. Header (Base64 encoded):
   {"typ":"JWT","alg":"HS256"}

2. Payload (Base64 encoded):
   {"user_id":"test_user","role":"user","exp":1234567890}

3. Signature:
   HMACSHA256(base64UrlEncode(header) + "." + base64UrlEncode(payload), secret)
                </pre>
                <p class="hint-tip">🔍 Try modifying the algorithm and role...</p>
            </div>
        </div>
    </div>

    <script>
    async function getNormalToken() {
        try {
            const response = await fetch('/hoon/level22.php', {  // URL 경로 수정
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=login&user_id=test_user'
            });
            const data = await response.json();
            const tokenDisplay = document.getElementById('token-display');
            tokenDisplay.textContent = data.token;
            tokenDisplay.style.display = 'block';
        } catch (error) {
            console.error('Error:', error);
            const tokenDisplay = document.getElementById('token-display');
            tokenDisplay.textContent = 'Error getting token: ' + error.message;
            tokenDisplay.classList.add('error');
        }
    }
    
    async function verifyToken(token) {
        try {
            const response = await fetch('/hoon/level22.php', {  // URL 경로 수정
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=verify&token=${token}`
            });
            const data = await response.json();
            return data;
        } catch (error) {
            console.error('Error:', error);
            return { status: 'error', message: error.message };
        }
    }
    
    async function verifyUserToken() {
        const tokenInput = document.getElementById('token-input').value;
        const resultDiv = document.getElementById('verify-result');
        
        try {
            const response = await verifyToken(tokenInput);
            resultDiv.textContent = response.status === 'success' 
                ? `Success! Flag: ${response.flag}` 
                : `Error: ${response.message}`;
            resultDiv.className = response.status === 'success' ? 'success' : 'error';
            resultDiv.style.display = 'block';
        } catch (error) {
            resultDiv.textContent = 'Error: ' + error.message;
            resultDiv.className = 'error';
            resultDiv.style.display = 'block';
        }
    }
    </script>
</body>
</html>
<?php
}
?>
