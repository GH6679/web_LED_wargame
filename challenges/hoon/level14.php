<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>쿠키 값 변경 문제</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #000;
            color: #fff;
            text-align: center;
            padding: 20px;
        }
        h1 {
            color: #ffcc00;
        }
        .grid-container {
            display: grid;
            grid-template-columns: repeat(6, 40px);
            gap: 3px;
            justify-content: center;
            margin-top: 20px;
        }
        .hint, .cell {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            background-color: #333;
            color: #ffcc00;
            border: 1px solid #555;
            cursor: pointer;
        }
        .cell.filled {
            background-color: #ffcc00;
            color: #000;
        }
        button {
            background-color: #ffcc00;
            color: #000;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 20px;
            border-radius: 5px;
        }
        button:hover {
            background-color: #ffd633;
        }
    </style>
</head>
<body>
    <h1>쿠키 값 변경 문제</h1>
    <p>기본 쿠키 값을 변경하여 다음 문제로 넘어가세요.</p>
    <p>변경할 쿠키 값의 힌트를 얻으려면 아래 버튼을 클릭하세요.</p>
    <button onclick="showHint()">힌트 보기</button>

    <div id="hint-container" style="display: none;">
        <h2>노노그램 게임</h2>
        <div class="grid-container">
            <div class="hint"></div>
            <div class="hint">2</div>
            <div class="hint">2</div>
            <div class="hint">1</div>
            <div class="hint">2<br>1</div>
            <div class="hint">2</div>

            <div class="hint">2</div>
            <div class="cell"></div>
            <div class="cell"></div>
            <div class="cell"></div>
            <div class="cell"></div>
            <div class="cell"></div>

            <div class="hint">2 2</div>
            <div class="cell"></div>
            <div class="cell"></div>
            <div class="cell"></div>
            <div class="cell"></div>
            <div class="cell"></div>

            <div class="hint">2 1</div>
            <div class="cell"></div>
            <div class="cell"></div>
            <div class="cell"></div>
            <div class="cell"></div>
            <div class="cell"></div>

            <div class="hint">0</div>
            <div class="cell"></div>
            <div class="cell"></div>
            <div class="cell"></div>
            <div class="cell"></div>
            <div class="cell"></div>

            <div class="hint">1</div>
            <div class="cell"></div>
            <div class="cell"></div>
            <div class="cell"></div>
            <div class="cell"></div>
            <div class="cell"></div>
        </div>
        <button onclick="checkSolution()">정답 확인</button>
        <div id="result" style="margin-top: 20px; color: #00ccff; font-weight: bold;"></div>
    </div>
    
    <div id="password-container" style="margin-top: 20px; color: #00ccff; font-weight: bold; display: none;">
        <p>쿠키 값을 올바르게 변경했습니다! 다음 문제로 넘어가는 패스워드: <span id="password">qMd80U23JCSdMw2QQqUnFklK1jCjw5</span></p>
    </div>

    <script>
        // 기본 쿠키 값 설정
        document.cookie = "default_cookie=value";

        // 힌트 표시 함수
        function showHint() {
            document.getElementById('hint-container').style.display = 'block';
        }

        // 정답 패턴을 인코딩하여 저장 (5x5 배열)
        const _0x4f8d = [btoa(JSON.stringify([
            [0, 0, 1, 1, 0],
            [1, 1, 0, 1, 1],
            [1, 1, 0, 0, 1],
            [0, 0, 0, 0, 0],
            [0, 0, 0, 1, 0]
        ]))];
        const solution = JSON.parse(atob(_0x4f8d[0]));

        // 셀 클릭 이벤트
        document.querySelectorAll('.cell').forEach((cell, index) => {
            cell.addEventListener('click', () => {
                cell.classList.toggle('filled');
            });
        });

        // 정답 확인 함수
        function checkSolution() {
            const cells = document.querySelectorAll('.cell');
            let correct = true;
            const _pattern = solution;

            cells.forEach((cell, index) => {
                const row = Math.floor(index / 5);
                const col = index % 5;
                const isFilled = cell.classList.contains('filled');
                if ((isFilled && _pattern[row][col] === 0) || (!isFilled && _pattern[row][col] === 1)) {
                    correct = false;
                }
            });

            const result = document.getElementById('result');
            if (correct) {
                result.textContent = "축하합니다! 정답입니다! ";
                alert("정답입니다!! 👍👍");
                document.cookie = "default_cookie=new_cookie_value";
                checkCookieValue();
            } else {
                result.textContent = "이게 어려운가 🤣";
            }
        }

        // 쿠키 값 확인 함수 - 난독화 및 인코딩
        function checkCookieValue() {
            const _k = atob('bmV3amVhbnM='); // 'newjeans'를 base64로 인코딩
            alert("Cookie Value Hint : 새로운 청바지들");

            setTimeout(() => {
                const checkValue = prompt("F12를 눌러 개발자 도구를 열고, 쿠키 값을 확인해 주세요. 변경된 쿠키 값을 입력하세요:");
                if (checkValue === _k) {
                    document.getElementById('password-container').style.display = 'block';
                } else {
                    alert("쿠키 값이 올바르지 않습니다. 다시 시도해보세요.");
                }
            }, 1000);
        }

        // 패스워드 보호
        const _p = document.getElementById('password');
        _p.textContent = atob('cU1kODBVMjNKQ1NkTXcyUVFxVW5Ga2xLMWpDanc1'); // 패스워드를 base64로 인코딩
    </script>
</body>
</html>
