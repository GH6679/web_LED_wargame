<?php
session_start();

// 초기 세션 설정
if (!isset($_SESSION['cards_cleared'])) {
    $_SESSION['cards_cleared'] = false;
}

// AJAX 요청 처리
if (isset($_POST['action']) && $_POST['action'] === 'complete_cards') {
    $_SESSION['cards_cleared'] = true;
    exit('success');
}

$flag = "FLAG{YY8ndXXk08w4YMecPWQoS4ujGQdi7m}";
$error = null;
$success = null;

// DB 연결 설정
$db = new mysqli('localhost', 'username', 'password', 'wargame_db');

// POST 요청 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // SQL Injection 정답 체크 (여러 가능한 답안 허용)
    $valid_answers = [
        "' OR '1'='1",
        "' OR 1=1 -- ",
        "' OR '1'='1' -- ",
        "admin' -- ",
        "admin'#",
        "' OR 1=1#",
        "' OR '1'='1'#",
        "admin') -- ",
        "admin')#",
        "') OR ('1'='1",
        "' UNION SELECT 'admin",
        "' OR 1=1 LIMIT 1 -- ",
        "admin' /*",
        "' OR '1'='1' /*"
    ];

    if (in_array($username, $valid_answers) || in_array($password, $valid_answers)) {
        $success = true;
        setcookie("card_master_flag", $flag, time() + 3600, "/");
    } else {
        $error = "접근이 거부되었습니다! 🚫";
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>Card Master's Challenge</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
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

        body {
            background-color: var(--background-color);
            color: var(--text-color);
            font-family: 'Nunito', sans-serif;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            background: var(--card-bg);
            border-radius: 20px;
            border: 1px solid var(--primary-color);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        .header h1 {
            color: var(--primary-color);
            font-size: 2.5em;
            margin-bottom: 10px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .header p {
            color: var(--text-color);
            font-size: 1.1em;
            opacity: 0.9;
        }

        .game-board {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 12px;
            margin: 20px auto;
            max-width: 800px;
            padding: 15px;
        }

        .card {
            aspect-ratio: 1;
            width: 100%;
            max-width: 90px;
            height: auto;
            margin: auto;
            background: transparent;
            border-radius: 15px;
            cursor: pointer;
            transition: transform 0.6s;
            transform-style: preserve-3d;
            position: relative;
        }

        .card.flipped {
            transform: rotateY(180deg);
        }

        .card-front, .card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .card-front {
            background: var(--card-bg);
            transform: rotateY(180deg);
        }

        .card-back {
            background: var(--card-bg);
            border: 3px solid #3d3d3d;
            transform: rotateY(0deg);
        }

        .hint {
            background: var(--card-bg);
            padding: 20px;
            border-radius: 15px;
            border-left: 5px solid var(--primary-color);
            margin: 20px 0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            color: var(--text-color);
        }

        button {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 25px;
            font-size: 1.1em;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        button:hover {
            background: #FF1493;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 105, 180, 0.3);
        }

        .sql-challenge {
            background: var(--card-bg);
            padding: 25px;
            border-radius: 15px;
            margin: 20px auto;
            max-width: 600px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            border: 1px solid var(--primary-color);
        }

        .challenge-form {
            background: #2a2a2a;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
            border: 1px solid #3d3d3d;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            background: #1a1a1a;
            border: 2px solid #3d3d3d;
            border-radius: 5px;
            font-size: 16px;
            color: var(--text-color);
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 2px rgba(74, 158, 255, 0.2);
        }

        .hint-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 15px 0;
            font-size: 0.9em;
        }

        .error-message {
            background: #ffe6e6;
            color: #dc3545;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
        }

        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🃏 Card Master's SQL Challenge 🃏</h1>
            <p>카드를 모두 맞추고 진정한 카드 마스터가 되어보세요!</p>
        </div>

        <div class="hint">
            <h3>🎯 미션</h3>
            <p>1. 모든 카드 쌍을 맞추세요</p>
            <p>2. SQL Injection 취약점을 찾아 관리자 권한을 획득하세요</p>
            <p>3. 카드 마스터의 비밀을 밝혀내세요!</p>
        </div>

        <div class="game-board" id="gameBoard"></div>

        <div class="sql-challenge" id="sqlChallenge" style="display: <?php echo $_SESSION['cards_cleared'] ? 'block' : 'none'; ?>">
            <h2>🎮 카드 마스터의 비밀 데이터베이스</h2>
            
            <div class="hint-box">
                <p>💡 힌트: 카드 마스터의 데이터베이스는 MYSQL 데이터베이스입니다.</p>
            </div>

            <div class="challenge-form">
                <form method="POST">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" placeholder="카드 마스터 계정명">
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" placeholder="비밀번호">
                    </div>
                    <button type="submit" class="challenge-btn">데이터베이스 접근 시도 🔐</button>
                </form>

                <?php if($error): ?>
                    <div class="error-message"><?php echo $error; ?></div>
                <?php endif; ?>

                <?php if($success): ?>
                    <div class="success-message">
                        축하합니다! FLAG를 획득하셨습니다: <?php echo $flag; ?> 🏆
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // 카드 게임 로직
        const cards = [];
        let matchedPairs = 0;
        let flippedCards = [];
        
        function createCards() {
            const gameBoard = document.getElementById('gameBoard');
            gameBoard.innerHTML = ''; // 기존 카드들을 제거
            
            // 18쌍의 귀여운 동물 이모지와 배경색
            const animals = [
                { emoji: '🐱', color: '#FFB7C5' }, // 고양이
                { emoji: '🐶', color: '#AEC6CF' }, // 강아지
                { emoji: '🐰', color: '#FFE4E1' }, // 토끼
                { emoji: '🦊', color: '#FFDAB9' }, // 여우
                { emoji: '🐼', color: '#E6E6FA' }, // 팬더
                { emoji: '🐨', color: '#B0C4DE' }, // 코알라
                { emoji: '🦁', color: '#FFE4B5' }, // 사자
                { emoji: '🐯', color: '#FFA07A' }, // 호랑이
                { emoji: '🦒', color: '#DEB887' }, // 기린
                { emoji: '🐘', color: '#B8C4C4' }, // 코끼리
                { emoji: '🐧', color: '#87CEEB' }, // 펭귄
                { emoji: '🦄', color: '#E6E6FA' }, // 유니콘
                { emoji: '🐸', color: '#98FB98' }, // 개구리
                { emoji: '🦋', color: '#87CEFA' }, // 나비
                { emoji: '🐢', color: '#90EE90' }, // 거북이
                { emoji: '🦔', color: '#D8BFD8' }, // 고슴도치
                { emoji: '🦦', color: '#ADD8E6' }, // 수달
                { emoji: '🦩', color: '#FFB6C1' }  // 플라밍고
            ];
            
            // 각 동물을 두 번씩 사용하여 36장의 카드 생성
            const cardValues = [...animals, ...animals];
            shuffleArray(cardValues);
            
            cardValues.forEach((value, index) => {
                const card = document.createElement('div');
                card.className = 'card';
                card.innerHTML = `
                    <div class="card-front" style="background-color: ${value.color}">
                        <span class="animal-emoji">${value.emoji}</span>
                    </div>
                    <div class="card-back"></div>
                `;
                card.dataset.value = value.emoji;
                card.addEventListener('click', flipCard);
                gameBoard.appendChild(card);
            });
        }

        function flipCard() {
            if (flippedCards.length === 2) return;
            if (this.classList.contains('flipped')) return;

            this.classList.add('flipped');
            flippedCards.push(this);

            if (flippedCards.length === 2) {
                checkMatch();
            }
        }

        function checkMatch() {
            const [card1, card2] = flippedCards;
            const match = card1.dataset.value === card2.dataset.value;

            if (match) {
                matchedPairs++;
                if (matchedPairs === 18) {
                    setTimeout(() => {
                        // AJAX로 서버에 완료 상태 저장
                        fetch('level17.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: 'action=complete_cards'
                        }).then(() => {
                            const sqlChallenge = document.getElementById('sqlChallenge');
                            sqlChallenge.style.display = 'block';
                            sqlChallenge.scrollIntoView({ behavior: 'smooth' });
                        });
                    }, 500);
                }
            } else {
                setTimeout(() => {
                    card1.classList.remove('flipped');
                    card2.classList.remove('flipped');
                }, 1000);
            }
            flippedCards = [];
        }

        function shuffleArray(array) {
            for (let i = array.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [array[i], array[j]] = [array[j], array[i]];
            }
        }

        // 페이지 로드 시에만 한 번 실행
        window.onload = function() {
            createCards();
            if (<?php echo $_SESSION['cards_cleared'] ? 'true' : 'false'; ?>) {
                const sqlChallenge = document.getElementById('sqlChallenge');
                sqlChallenge.style.display = 'block';
            }
        };
    </script>

    <!-- 폭죽 효과를 위한 캔버스 추가 -->
    <canvas id="fireworks" style="position: fixed; top: 0; left: 0; pointer-events: none; z-index: 999; display: none;"></canvas>

    <!-- 폭죽 효과 스크립트 -->
    <script>
    const fireworks = {
        init() {
            this.canvas = document.getElementById('fireworks');
            this.ctx = this.canvas.getContext('2d');
            this.resizeCanvas();
            window.addEventListener('resize', () => this.resizeCanvas());
        },

        resizeCanvas() {
            this.canvas.width = window.innerWidth;
            this.canvas.height = window.innerHeight;
        },

        startShow() {
            this.canvas.style.display = 'block';
            let count = 0;
            const maxFireworks = 5;
            const interval = setInterval(() => {
                this.launch();
                count++;
                if (count >= maxFireworks) {
                    clearInterval(interval);
                    setTimeout(() => {
                        this.canvas.style.display = 'none';
                        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
                    }, 2000);
                }
            }, 300);
        },

        launch() {
            const x = Math.random() * this.canvas.width;
            const y = this.canvas.height;
            const endX = x + (Math.random() - 0.5) * 200;
            const endY = y * 0.3;
            this.animateFirework(x, y, endX, endY);
        },

        animateFirework(startX, startY, endX, endY) {
            const colors = ['#FF1493', '#00FF00', '#FF4500', '#4169E1', '#FFD700'];
            const color = colors[Math.floor(Math.random() * colors.length)];
            let progress = 0;
            
            const animate = () => {
                this.ctx.beginPath();
                const x = startX + (endX - startX) * progress;
                const y = startY + (endY - startY) * progress;
                
                if (progress < 1) {
                    this.ctx.arc(x, y, 2, 0, Math.PI * 2);
                    this.ctx.fillStyle = color;
                    this.ctx.fill();
                    progress += 0.02;
                    requestAnimationFrame(animate);
                } else {
                    this.explode(endX, endY, color);
                }
            };
            
            animate();
        },

        explode(x, y, color) {
            const particles = 50;
            for (let i = 0; i < particles; i++) {
                const angle = (Math.PI * 2 * i) / particles;
                const velocity = 2 + Math.random() * 2;
                this.animateParticle(x, y, Math.cos(angle) * velocity, Math.sin(angle) * velocity, color);
            }
        },

        animateParticle(x, y, vx, vy, color) {
            let life = 1;
            const gravity = 0.05;
            
            const animate = () => {
                this.ctx.beginPath();
                this.ctx.arc(x, y, 1, 0, Math.PI * 2);
                this.ctx.fillStyle = color;
                this.ctx.fill();
                
                x += vx;
                y += vy;
                vy += gravity;
                life -= 0.02;
                
                if (life > 0) {
                    requestAnimationFrame(animate);
                }
            };
            
            animate();
        }
    };

    // 폭죽 초기화
    fireworks.init();

    // 성공 메시지가 표시될 때 폭죽 시작
    <?php if($success): ?>
        fireworks.startShow();
    <?php endif; ?>
    </script>
</body>
</html>
