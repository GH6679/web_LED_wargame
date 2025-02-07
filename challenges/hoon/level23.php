<?php
class UserSystem {
    private $users = [
        1 => ['id' => 1, 'username' => 'user1', 'profile' => '일반 사용자입니다.'],
        2 => ['id' => 2, 'username' => 'user2', 'profile' => '일반 사용자입니다.'],
        3 => ['id' => 3, 'username' => 'admin', 'profile' => 'flag{ioFkmtqImumg878f320a4E3n6ySeiL}']
    ];

    public function getUserProfile($user_id) {
        // 취약한 코드: 권한 검증 없이 직접 사용자 ID로 접근
        if (isset($this->users[$user_id])) {
            return $this->users[$user_id];
        }
        return null;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $userSystem = new UserSystem();
    $action = $_POST['action'] ?? '';
    
    switch($action) {
        case 'get_profile':
            $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 1;
            $profile = $userSystem->getUserProfile($user_id);
            if ($profile) {
                echo json_encode(['status' => 'success', 'data' => $profile]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'User not found']);
            }
            break;
    }
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>IDOR Challenge</title>
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
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: var(--card-bg);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.3);
        }

        h1 {
            text-align: center;
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        .profile-section {
            background: rgba(255,255,255,0.1);
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }

        .profile-card {
            background: var(--card-bg);
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
            border: 1px solid rgba(255,255,255,0.1);
        }

        .profile-card h3 {
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        button {
            background-color: var(--secondary-color);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin: 5px;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(74, 158, 255, 0.3);
        }

        .hint-section {
            margin-top: 30px;
            padding: 20px;
            background: rgba(255,255,255,0.05);
            border-radius: 10px;
            border-left: 4px solid var(--secondary-color);
        }

        #profile-display {
            margin-top: 20px;
            padding: 15px;
            background: rgba(255,255,255,0.05);
            border-radius: 8px;
            word-break: break-all;
        }

        .success-animation {
            animation: successPulse 2s infinite;
        }

        @keyframes successPulse {
            0% { box-shadow: 0 0 0 0 rgba(46, 204, 113, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(46, 204, 113, 0); }
            100% { box-shadow: 0 0 0 0 rgba(46, 204, 113, 0); }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>User Profile Viewer</h1>
        
        <div class="profile-section">
            <h2>Available Profiles</h2>
            <div class="profile-card">
                <h3>User 1</h3>
                <button onclick="viewProfile(1)">View Profile</button>
            </div>
            <div class="profile-card">
                <h3>User 2</h3>
                <button onclick="viewProfile(2)">View Profile</button>
            </div>
        </div>

        <div id="profile-display"></div>

        <div class="hint-section">
            <h3>💡 Challenge Info</h3>
            <p>이 시스템은 사용자 프로필을 조회하는 기능을 제공합니다.</p>
            <p>일반 사용자는 user1과 user2의 프로필만 볼 수 있어야 합니다.</p>
            <p>하지만 IDOR 취약점으로 인해 다른 사용자의 프로필도 볼 수 있을지도...?</p>
            <p>목표: admin 사용자의 프로필을 찾아 플래그를 획득하세요!</p>
        </div>
    </div>

    <script>
    async function viewProfile(userId) {
        try {
            const response = await fetch('/hoon/level23.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=get_profile&user_id=${userId}`
            });
            
            const data = await response.json();
            const displayDiv = document.getElementById('profile-display');
            
            if (data.status === 'success') {
                let content = `
                    <div class="profile-card ${data.data.username === 'admin' ? 'success-animation' : ''}">
                        <h3>Profile Information</h3>
                        <p><strong>Username:</strong> ${data.data.username}</p>
                        <p><strong>Profile:</strong> ${data.data.profile}</p>
                    </div>
                `;
                displayDiv.innerHTML = content;

                // admin 프로필을 찾았을 때 축하 효과
                if (data.data.username === 'admin') {
                    displayConfetti();
                }
            } else {
                displayDiv.innerHTML = `<div class="profile-card">
                    <p style="color: var(--danger-color);">${data.message}</p>
                </div>`;
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    function displayConfetti() {
        const duration = 3000;
        const end = Date.now() + duration;

        (function frame() {
            confetti({
                particleCount: 7,
                angle: 60,
                spread: 55,
                origin: { x: 0 },
                colors: ['#ff0000', '#00ff00', '#0000ff']
            });
            confetti({
                particleCount: 7,
                angle: 120,
                spread: 55,
                origin: { x: 1 },
                colors: ['#ff0000', '#00ff00', '#0000ff']
            });

            if (Date.now() < end) {
                requestAnimationFrame(frame);
            }
        }());
    }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
</body>
</html>
