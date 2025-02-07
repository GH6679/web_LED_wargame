<?php
session_start();

// 초기 설정
if (!isset($_SESSION['user_chocobi'])) {
    $_SESSION['user_chocobi'] = 0;  // 처음 시작은 0개
    $_SESSION['total_requested'] = 0;  // 총 요청 횟수
    $_SESSION['daily_requests'] = 0;  // 일일 요청 횟수
    $_SESSION['last_request_time'] = 0;  // 마지막 요청 시간
}

if (!isset($_SESSION['admin_chocobi'])) {
    $_SESSION['admin_chocobi'] = 1000;  // 관리자 초기 초코비 수량
}

// 초코비 요청 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'request_chocobi' && isset($_POST['amount'])) {
        $amount = (int)$_POST['amount'];
        $current_time = time();
        
        // 각종 제한 조건 체크
        if ($amount > 20) {
            $_SESSION['error_message'] = "한 번에 20개까지만 요청할 수 있습니다!";
        }
        else if ($_SESSION['total_requested'] >= 60) {
            $_SESSION['error_message'] = "더 이상 초코비를 요청할 수 없습니다. (최대 60개)";
        }
        else if ($_SESSION['daily_requests'] >= 3) {
            $_SESSION['error_message'] = "오늘은 더 이상 요청할 수 없습니다. (하루 3회 제한)";
        }
        else if ($current_time - $_SESSION['last_request_time'] < 300) { // 5분 대기
            $wait_time = 300 - ($current_time - $_SESSION['last_request_time']);
            $_SESSION['error_message'] = "다음 요청까지 {$wait_time}초 기다려야 합니다.";
        }
        else if ($_SESSION['admin_chocobi'] < $amount) {
            $_SESSION['error_message'] = "관리자의 초코비가 부족합니다!";
        }
        else {
            // 요청 처리
            $_SESSION['user_chocobi'] += $amount;
            $_SESSION['admin_chocobi'] -= $amount;
            $_SESSION['total_requested'] += $amount;
            $_SESSION['daily_requests']++;
            $_SESSION['last_request_time'] = $current_time;
            $_SESSION['success_message'] = "초코비 {$amount}개를 받았습니다!";
        }
    }
    // CSRF 취약점이 있는 관리자 초코비 전송 기능
    else if ($_POST['action'] === 'admin_send' && isset($_POST['amount'])) {
        $amount = (int)$_POST['amount'];
        if ($amount >= 100 && $_SESSION['admin_chocobi'] >= $amount) {
            $_SESSION['user_chocobi'] += $amount;
            $_SESSION['admin_chocobi'] -= $amount;
            $flag = "FLAG{CSRF_4DM1N_CH0C0B1_ST0L3N!}";
            $_SESSION['success_message'] = "축하합니다! 관리자의 초코비를 훔쳤습니다! Flag: " . $flag;
        }
    }
}

// 메시지 처리
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
unset($_SESSION['error_message'], $_SESSION['success_message']);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>짱구의 초코비 은행</title>
    <style>
        :root {
            --primary-color: #6D28D9;
            --primary-light: #8B5CF6;
            --secondary: #4C1D95;
            --background: #111827;
            --surface: #1F2937;
            --text: #F9FAFB;
            --text-secondary: #9CA3AF;
            --accent: #EC4899;
            --border: #374151;
        }

        body {
            font-family: 'Noto Sans KR', sans-serif;
            background: var(--bg-color);
            margin: 0;
            padding: 20px;
            color: var(--text-color);
        }

        .miniroom-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border: 3px solid var(--border-color);
            border-radius: 10px;
            box-shadow: var(--box-shadow);
        }

        .header {
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(to bottom, #6495ed, #4a7bcc);
            padding: 15px;
            border-radius: 7px 7px 0 0;
            border-bottom: 2px solid var(--border-color);
            color: white;
        }

        .profile-section {
            display: flex;
            padding: 20px;
            background: var(--secondary-color);
            border-bottom: 1px dashed var(--border-color);
            gap: 20px;
        }

        .profile-image {
            width: 150px;
            height: 150px;
            background: #fff url('/hoon/JJANG.png') center/cover;
            border: 5px solid #fff;
            box-shadow: 0 0 10px rgba(100, 149, 237, 0.2);
            border-radius: 10px;
            flex-shrink: 0;
        }

        .profile-info {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding-left: 20px;
        }

        .profile-info h2 {
            margin: 0 0 10px 0;
            color: var(--text-color);
            font-size: 1.5em;
        }

        .profile-info p {
            margin: 0 0 15px 0;
            color: var(--text-color);
            font-size: 1.1em;
        }

        .today-box {
            background: white;
            padding: 10px 15px;
            border: 2px solid var(--primary-color);
            border-radius: 8px;
            margin-top: auto;
            color: var(--text-color);
            box-shadow: var(--box-shadow);
            font-weight: bold;
        }

        .dotori-section {
            padding: 20px;
            background: var(--secondary-color);
            border-top: 1px dashed var(--border-color);
        }

        .menu-tab {
            background: white;
            padding: 15px;
            border-top: 1px solid var(--border-color);
        }

        .menu-tab button {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 14px;
            margin-right: 10px;
            transition: all 0.3s ease;
        }

        .menu-tab button:hover {
            background: var(--hover-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(100, 149, 237, 0.2);
        }

        .dotori-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .dotori-form {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 15px;
            background: white;
            border: 2px solid var(--primary-color);
            border-radius: 8px;
            box-shadow: var(--box-shadow);
        }

        .dotori-form input[type="number"] {
            width: 200px;
            padding: 10px;
            margin-right: 10px;
        }

        .dotori-form button {
            margin-right: 0;
        }

        .choco-images {
            display: flex;
            gap: 10px;
            margin-left: 10px;
        }

        .choco-image {
            width: 150px;
            height: 150px;
            border-radius: 10px;
            flex-shrink: 0;
        }

        .choco-image-1 {
            background: url('/hoon/choco.jpg') center/cover no-repeat;
        }

        .choco-image-2 {
            background: url('/hoon/choco2.jpg') center/cover no-repeat;
        }

        .hint-box {
            margin: 20px;
            padding: 20px;
            background: white;
            border: 2px solid var(--primary-color);
            border-radius: 8px;
            font-size: 14px;
            color: var(--text-color);
            box-shadow: var(--box-shadow);
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .hint-content {
            flex: 1;
        }

        .hint-image {
            width: 150px;
            height: 150px;
            background: url('/hoon/action.jpeg') center/cover;
            border-radius: 10px;
            flex-shrink: 0;
        }

        .bgm-player {
            position: absolute;
            top: 15px;
            right: 20px;
            background: rgba(255, 255, 255, 0.9);
            color: var(--text-color);
            padding: 8px 15px;
            border-radius: 20px;
            box-shadow: var(--box-shadow);
            border: 1px solid var(--border-color);
            font-size: 12px;
            z-index: 1000;
        }

        .success-message {
            background: #4CAF50;
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .error-message {
            background: #ff5555;
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .limits-info {
            background: #2a1f3d;
            color: #e0e0e0;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        
        .info-container {
            display: flex;
            gap: 20px;
            margin: 20px 0;
        }
        
        .limits-info {
            flex: 1;
            background: #2a1f3d;
            color: #e0e0e0;
            padding: 15px;
            border-radius: 8px;
        }
        
        .chocobi-images {
            flex: 1;
            display: flex;
            gap: 10px;
            justify-content: center;
            align-items: center;
        }
        
        .chocobi-image {
            width: 150px;
            height: 150px;
            border-radius: 10px;
            object-fit: cover;
        }

        .chocobi-container {
            margin: 20px 0;
        }

        .chocobi-form {
            background: var(--surface);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .input-group {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .styled-input {
            flex: 1;
            padding: 12px 15px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: var(--background);
            color: var(--text);
            font-size: 16px;
        }

        .styled-button {
            padding: 12px 25px;
            background: var(--primary-color);
            color: var(--text);
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .styled-button:hover {
            background: var(--primary-light);
            transform: translateY(-2px);
        }

        .styled-button:disabled {
            background: var(--border);
            cursor: not-allowed;
            transform: none;
        }

        .info-container {
            display: flex;
            gap: 20px;
            margin: 20px 0;
        }

        .limits-info {
            flex: 1;
            background: var(--surface);
            color: var(--text);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .chocobi-images {
            flex: 1;
            display: flex;
            gap: 15px;
            justify-content: center;
            align-items: center;
        }

        .chocobi-image {
            width: 150px;
            height: 150px;
            border-radius: 10px;
            object-fit: cover;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
        }

        .chocobi-image:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="miniroom-container">
        <div class="header">
            <h1>🏠 짱구의 초코비 은행</h1>
            <div class="bgm-player">
                🎵 BGM: 짱구는 못말려 - 액션가면 [재생중...]
            </div>
        </div>

        <div class="profile-section">
            <div class="profile-image"></div>
            <div class="profile-info">
                <h2>신짱구의 초코비 은행</h2>
                <p>⭐ 초코비 나눔 이벤트 진행중! ⭐</p>
                <div class="today-box">
                    관리자(짱구) 보유 초코비: <?php echo $_SESSION['admin_chocobi']; ?>개<br>
                    내 보유 초코비: <?php echo $_SESSION['user_chocobi']; ?>개
                </div>
            </div>
        </div>

        <div class="hint-box">
            <div class="hint-content">
                <h3>🎯 미션</h3>
                <p>짱구의 초코비 은행에 CSRF 취약점이 발견되었습니다!</p>
                <p>관리자 페이지의 초코비 전송 기능을 악용해보세요.</p>
                <p>100개 이상의 초코비를 탈취하면 플래그를 획득할 수 있습니다!</p>
                <p>힌트: 관리자 페이지의 초코비 전송 기능이 CSRF에 취약합니다...</p>
            </div>
            <div class="hint-image"></div>
        </div>

        <div class="info-container">
            <div class="limits-info">
                <h3>🕒 초코비 요청 제한</h3>
                <p>- 한 번에 최대 20개까지 요청 가능</p>
                <p>- 총 누적 60개까지만 요청 가능</p>
                <p>- 하루 3회까지만 요청 가능</p>
                <p>- 요청 간 5분 대기 시간 필요</p>
                <p>현재까지 요청한 초코비: <?php echo $_SESSION['total_requested']; ?>개</p>
                <p>오늘 남은 요청 횟수: <?php echo (3 - $_SESSION['daily_requests']); ?>회</p>
            </div>
            
            <div class="chocobi-images">
                <img src="/hoon/choco.jpg" alt="초코비" class="chocobi-image">
                <img src="/hoon/choco2.jpg" alt="초코비2" class="chocobi-image">
            </div>
        </div>

        <?php if ($error_message): ?>
        <div class="error-message">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
        <?php endif; ?>

        <?php if ($success_message): ?>
        <div class="success-message">
            <?php echo htmlspecialchars($success_message); ?>
        </div>
        <?php endif; ?>

        <div class="chocobi-container">
            <form class="chocobi-form" method="POST">
                <input type="hidden" name="action" value="request_chocobi">
                <div class="input-group">
                    <input type="number" 
                           name="amount" 
                           min="1" 
                           max="20" 
                           placeholder="요청할 초코비 수 (최대 20개)"
                           class="styled-input">
                    <button type="submit" 
                            class="styled-button"
                            <?php echo ($_SESSION['total_requested'] >= 60 || $_SESSION['daily_requests'] >= 3) ? 'disabled' : ''; ?>>
                        초코비 요청하기
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
