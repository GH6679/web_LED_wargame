<?php
session_start();

$flag = "FLAG{m7RwAPeIS9ZKy0QvQcBJc5dXLcEguH}";
$message = "";
$showHints = false;

if (isset($_SESSION['puzzle_solved']) && $_SESSION['puzzle_solved']) {
    $showHints = true;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $x_forwarded_for = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? '';
    $custom_header = $_SERVER['HTTP_CUSTOM_AUTH'] ?? '';
    
    if ($user_agent === 'Admin-Browser' && 
        $x_forwarded_for === '127.0.0.1' && 
        $custom_header === 'SECRET_KEY_1337') {
        $message = "성공! FLAG: " . $flag;
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>Header Manipulation Challenge</title>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;600&family=Outfit:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #000000;
            --bg-secondary: #111111;
            --text-primary: #e4e4e4;
            --text-bright: #ffffff;
            --accent-color: #7aa2f7;
            --error-color: #f7768e;
            --success-color: #9ece6a;
            --border-color: #2a2a2a;
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
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
        }

        .challenge-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .challenge-title {
            color: var(--accent-color);
            font-size: 2.2rem;
            margin-bottom: 10px;
            position: relative;
            display: inline-block;
            background: linear-gradient(45deg, var(--accent-color), #5d5dff);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            @supports not (-webkit-background-clip: text) {
                background: none;
                color: var(--accent-color);
            }
        }

        .game-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 30px;
        }

        .puzzle-board {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 2px;
            width: 350px;
            height: 350px;
            background: var(--bg-secondary);
            padding: 10px;
            border-radius: 8px;
            margin: 20px auto;
        }

        .pipe-piece {
            width: 45px;
            height: 45px;
            background: #2a2a2a;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 24px;
            color: #4a4a4a;
        }

        .pipe-piece.rotating {
            transform-origin: center;
            animation: rotate-pulse 0.3s ease;
        }

        .pipe-piece.connected {
            color: #7aa2f7;
            text-shadow: 0 0 10px rgba(122, 162, 247, 0.5);
            background: #1a1a1a;
            transition: all 0.3s ease;
        }

        .pipe-piece.loop {
            color: #9ece6a;
            text-shadow: 0 0 10px rgba(158, 206, 106, 0.5);
            transition: all 0.3s ease;
        }

        .pipe-piece.start {
            color: #9ece6a;
            text-shadow: 0 0 10px rgba(158, 206, 106, 0.5);
        }

        .pipe-piece.end {
            color: #f7768e;
            text-shadow: 0 0 10px rgba(247, 118, 142, 0.5);
        }

        .controls {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            margin-top: 20px;
        }

        .control-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }

        .move-counter {
            color: var(--text-primary);
            font-size: 1.2rem;
            font-weight: 500;
        }

        @keyframes rotate-pulse {
            0% { transform: scale(1) rotate(0deg); }
            50% { transform: scale(0.95) rotate(45deg); }
            100% { transform: scale(1) rotate(90deg); }
        }

        .hint-box, .admin-note, .current-status, .code-example {
            display: <?php echo $showHints ? 'block' : 'none'; ?>;
            background: var(--bg-primary);
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .message {
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            background: rgba(122, 162, 247, 0.1);
            border: 1px solid var(--accent-color);
        }

        .control-btn {
            background: var(--accent-color);
            color: var(--bg-primary);
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            margin: 0 5px;
        }

        .control-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(122, 162, 247, 0.3);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="challenge-header">
            <h1 class="challenge-title">Header Manipulation Challenge</h1>
            <p>파이프 퍼즐을 해결하고 관리자 페이지 접근 힌트를 얻으세요!</p>
        </div>

        <div class="game-container">
            <div class="puzzle-board" id="puzzleBoard"></div>
            <div class="controls">
                <!-- 버튼들은 JavaScript에서 동적으로 추가됩니다 -->
            </div>
        </div>

        <?php if($showHints): ?>
        <div class="hint-box">
            <h3>💡 시스템 로그 발견!</h3>
            <div class="log-content">
                [System Log]
                - Admin access detected from 127.0.0.1
                - Browser: Admin-Browser
                - Auth: SECRET_KEY_1337
            </div>
        </div>

        <div class="admin-note">
            <h3>📝 관리자 메모</h3>
            <div class="note-content">
                <p>새로운 관리자 접근 제한 정책:</p>
                <ul>
                    <li>승인된 브라우저만 접근 가능</li>
                    <li>내부 네트워크에서만 접근 가능</li>
                    <li>인증 키 필요</li>
                </ul>
            </div>
        </div>

        <div class="current-status">
            <h3>🔍 현재 접속 정보</h3>
            <div class="header-info">
                <div class="header-item">
                    <span class="header-name">User-Agent:</span>
                    <span class="header-value"><?php echo htmlspecialchars($_SERVER['HTTP_USER_AGENT'] ?? ''); ?></span>
                </div>
                <div class="header-item">
                    <span class="header-name">X-Forwarded-For:</span>
                    <span class="header-value"><?php echo htmlspecialchars($_SERVER['HTTP_X_FORWARDED_FOR'] ?? ''); ?></span>
                </div>
                <div class="header-item">
                    <span class="header-name">Custom-Auth:</span>
                    <span class="header-value"><?php echo htmlspecialchars($_SERVER['HTTP_CUSTOM_AUTH'] ?? ''); ?></span>
                </div>
            </div>
        </div>

        <div class="code-example">
            <h3>🔧 헤더 설정 예시</h3>
            <div class="code-block">
                <pre>
curl -X GET \
-H "User-Agent: [BROWSER]" \
-H "X-Forwarded-For: [IP]" \
-H "Custom-Auth: [KEY]" \
http://example.com/admin
                </pre>
            </div>
        </div>
        <?php endif; ?>

        <?php if($message): ?>
        <div class="message">
            <?php echo htmlspecialchars($message); ?>
        </div>
        <?php endif; ?>
    </div>

    <script>
        class PipePuzzle {
            constructor() {
                this.size = 7;
                this.moves = 0;
                this.maxMoves = 100;
                this.board = [];
                this.rotations = [];
                this.rules = {
                    allowBackflow: true,
                    allowLoops: true,
                    maxTurns: 100
                };
                this.init();
                this.addMoveCounter();
                this.addControlButtons();
            }

            addControlButtons() {
                const controls = document.querySelector('.controls');
                const buttonContainer = document.createElement('div');
                buttonContainer.className = 'control-buttons';

                const checkButton = document.createElement('button');
                checkButton.className = 'control-btn';
                checkButton.textContent = '확인';
                checkButton.onclick = () => this.checkSolution();

                const resetButton = document.createElement('button');
                resetButton.className = 'control-btn';
                resetButton.textContent = '리셋';
                resetButton.onclick = () => this.resetPuzzle();

                buttonContainer.appendChild(checkButton);
                buttonContainer.appendChild(resetButton);
                controls.insertBefore(buttonContainer, controls.firstChild);
            }

            init() {
                this.moves = 0;
                this.generatePuzzle();
                this.renderBoard();
            }

            addMoveCounter() {
                let controls = document.querySelector('.controls');
                if (!controls) {
                    controls = document.createElement('div');
                    controls.className = 'controls';
                    document.getElementById('puzzleBoard').after(controls);
                }

                const existingCounter = document.querySelector('.move-counter');
                if (existingCounter) {
                    existingCounter.remove();
                }

                const counter = document.createElement('div');
                counter.className = 'move-counter';
                counter.innerHTML = `회전 횟수: <span id="moveCount">0</span> / ${this.maxMoves}`;
                controls.appendChild(counter);
            }

            generatePuzzle() {
                const PIPE_TYPES = {
                    vertical: '║',
                    horizontal: '═',
                    corner: '╗'
                };

                let maxAttempts = 100; // 최대 시도 횟수
                let validPuzzleFound = false;

                while (!validPuzzleFound && maxAttempts > 0) {
                    // 보드 초기화
                    this.board = new Array(this.size * this.size).fill('');
                    this.rotations = new Array(this.size * this.size).fill(0);

                    // 시작점과 끝점 설정
                    this.board[0] = '▣';
                    this.board[this.size * this.size - 1] = '▣';
                    this.rotations[0] = 0;
                    this.rotations[this.size * this.size - 1] = 0;

                    // 나머지 파이프 배치
                    for (let i = 1; i < this.size * this.size - 1; i++) {
                        const randomType = Math.random();
                        if (randomType < 0.33) {
                            this.board[i] = PIPE_TYPES.vertical;
                        } else if (randomType < 0.66) {
                            this.board[i] = PIPE_TYPES.horizontal;
                        } else {
                            this.board[i] = PIPE_TYPES.corner;
                        }
                        this.rotations[i] = Math.floor(Math.random() * 4) * 90;
                    }

                    // 유효한 경로가 있는지 확인
                    if (this.hasValidPath()) {
                        validPuzzleFound = true;
                    }

                    maxAttempts--;
                }

                if (!validPuzzleFound) {
                    // 기본 해결 가능한 퍼즐 생성
                    this.createDefaultPuzzle();
                }
            }

            createDefaultPuzzle() {
                // 기본 해결 가능한 퍼즐 패턴
                this.board = new Array(this.size * this.size).fill('═'); // 기본적으로 수평 파이프로 채움
                this.rotations = new Array(this.size * this.size).fill(0);
                
                // 시작점과 끝점
                this.board[0] = '▣';
                this.board[this.size * this.size - 1] = '▣';
                
                // 중간에 몇 개의 코너 파이프 추가
                for (let i = 2; i < this.size * this.size - 2; i += 3) {
                    this.board[i] = '╗';
                    this.rotations[i] = Math.floor(Math.random() * 4) * 90;
                }
            }

            resetPuzzle() {
                this.moves = 0;
                const moveCountElement = document.getElementById('moveCount');
                if (moveCountElement) {
                    moveCountElement.textContent = this.moves;
                }
                
                this.generatePuzzle();
                this.renderBoard();
            }

            renderBoard() {
                const board = document.getElementById('puzzleBoard');
                board.innerHTML = '';

                for (let i = 0; i < this.size * this.size; i++) {
                    const piece = document.createElement('div');
                    piece.className = 'pipe-piece';
                    if (i === 0) piece.classList.add('start');
                    if (i === this.size * this.size - 1) piece.classList.add('end');
                    
                    piece.textContent = this.board[i];
                    piece.style.transform = `rotate(${this.rotations[i]}deg)`;
                    piece.addEventListener('click', () => this.rotatePiece(i));
                    board.appendChild(piece);
                }

                // 초기 연결 상태 확인
                this.checkRealTimeConnections();
            }

            hasValidPath() {
                const visited = new Set();
                const connected = new Set();
                return this.findPath(0, visited, connected);
            }

            findPath(current, visited, connected) {
                visited.add(current);
                connected.add(current);

                if (current === this.size * this.size - 1) {
                    return true;
                }

                const connections = this.getConnections(current);
                for (const next of connections) {
                    if (visited.has(next)) continue;
                    if (this.findPath(next, visited, connected)) {
                        return true;
                    }
                }

                connected.delete(current);
                return false;
            }

            rotatePiece(index) {
                if (index === 0 || index === this.size * this.size - 1) return;
                if (this.moves >= this.maxMoves) {
                    alert('최대 회전 횟수에 도달했습니다!');
                    return;
                }

                const piece = document.querySelectorAll('.pipe-piece')[index];
                
                piece.classList.add('rotating');
                this.rotations[index] = (this.rotations[index] + 90) % 360;
                piece.style.transform = `rotate(${this.rotations[index]}deg)`;

                this.moves++;
                const moveCountElement = document.getElementById('moveCount');
                if (moveCountElement) {
                    moveCountElement.textContent = this.moves;
                }
                
                setTimeout(() => {
                    piece.classList.remove('rotating');
                    this.checkRealTimeConnections();
                }, 300);
            }

            checkRealTimeConnections() {
                const visited = new Set();
                const connected = new Set();
                this.findConnectedPipes(0, visited, connected);
                this.updatePipeVisuals(connected);
            }

            findConnectedPipes(currentIndex, visited, connected) {
                visited.add(currentIndex);
                connected.add(currentIndex);

                const connections = this.getConnections(currentIndex);
                
                for (const nextIndex of connections) {
                    if (visited.has(nextIndex)) {
                        if (this.rules.allowLoops && this.isValidLoop(currentIndex, nextIndex)) {
                            connected.add(nextIndex);
                        }
                        continue;
                    }

                    const nextConnections = this.getConnections(nextIndex);
                    if (nextConnections.includes(currentIndex)) {
                        this.findConnectedPipes(nextIndex, visited, connected);
                    }
                }
            }

            isValidLoop(current, next) {
                const currentConnections = this.getConnections(current);
                const nextConnections = this.getConnections(next);
                return currentConnections.includes(next) && nextConnections.includes(current);
            }

            getConnections(index) {
                const connections = [];
                const row = Math.floor(index / this.size);
                const col = index % this.size;
                const piece = this.board[index];
                const rotation = this.rotations[index];

                const openings = this.getPipeOpenings(piece, rotation);
                
                for (const [dx, dy] of openings) {
                    const newRow = row + dy;
                    const newCol = col + dx;
                    
                    if (newRow >= 0 && newRow < this.size && newCol >= 0 && newCol < this.size) {
                        const nextIndex = newRow * this.size + newCol;
                        const nextPiece = this.board[nextIndex];
                        const nextRotation = this.rotations[nextIndex];
                        const nextOpenings = this.getPipeOpenings(nextPiece, nextRotation);
                        
                        if (this.canConnect(dx, dy, nextOpenings)) {
                            connections.push(nextIndex);
                        }
                    }
                }

                return connections;
            }

            canConnect(dx, dy, nextOpenings) {
                return nextOpenings.some(([ndx, ndy]) => dx === -ndx && dy === -ndy);
            }

            getPipeOpenings(piece, rotation) {
                let openings = [];
                switch (piece) {
                    case '║':
                        openings = rotation % 180 === 0 ? 
                            [[0, -1], [0, 1]] : 
                            [[-1, 0], [1, 0]];
                        break;
                    case '═':
                        openings = rotation % 180 === 0 ? 
                            [[-1, 0], [1, 0]] : 
                            [[0, -1], [0, 1]];
                        break;
                    case '╗':
                        switch (rotation) {
                            case 0:   openings = [[-1, 0], [0, 1]]; break;
                            case 90:  openings = [[0, 1], [1, 0]]; break;
                            case 180: openings = [[1, 0], [0, -1]]; break;
                            case 270: openings = [[0, -1], [-1, 0]]; break;
                        }
                        break;
                    case '▣':
                        openings = [[0, -1], [0, 1], [-1, 0], [1, 0]];
                        break;
                }
                return openings;
            }

            updatePipeVisuals(connected) {
                const pieces = document.querySelectorAll('.pipe-piece');
                pieces.forEach((piece, index) => {
                    if (connected.has(index)) {
                        piece.classList.add('connected');
                        if (this.isPartOfLoop(index, connected)) {
                            piece.classList.add('loop');
                        } else {
                            piece.classList.remove('loop');
                        }
                    } else {
                        piece.classList.remove('connected', 'loop');
                    }
                });
            }

            isPartOfLoop(index, connected) {
                const visited = new Set();
                return this.detectLoop(index, index, visited, connected, 0);
            }

            detectLoop(start, current, visited, connected, depth) {
                if (depth > 0 && current === start) return true;
                if (visited.has(current)) return false;
                
                visited.add(current);
                const connections = this.getConnections(current);
                
                for (const next of connections) {
                    if (!connected.has(next)) continue;
                    if (this.detectLoop(start, next, visited, connected, depth + 1)) {
                        return true;
                    }
                }
                
                return false;
            }

            checkSolution() {
                const visited = new Set();
                const connected = new Set();
                const startIndex = 0;
                const endIndex = this.size * this.size - 1;
                
                this.clearConnections();
                const isConnected = this.findPath(startIndex, visited, connected);
                
                this.highlightConnectedPipes(connected);
                
                if (isConnected && connected.has(endIndex)) {
                    setTimeout(() => {
                        alert('축하합니다! 파이프가 올바르게 연결되었습니다!');
                        fetch('update_puzzle_status.php', {
                            method: 'POST',
                            headers: {'Content-Type': 'application/json'},
                            body: JSON.stringify({completed: true})
                        }).then(() => {
                            location.reload();
                        });
                    }, 500);
                } else {
                    alert('아직 파이프가 올바르게 연결되지 않았습니다.');
                }
            }

            clearConnections() {
                const pieces = document.querySelectorAll('.pipe-piece');
                pieces.forEach(piece => {
                    piece.classList.remove('connected', 'loop');
                });
            }

            highlightConnectedPipes(connected) {
                const pieces = document.querySelectorAll('.pipe-piece');
                pieces.forEach((piece, index) => {
                    if (connected.has(index)) {
                        piece.classList.add('connected');
                    } else {
                        piece.classList.remove('connected');
                    }
                });
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const game = new PipePuzzle();
        });
    </script>
</body>
</html>
