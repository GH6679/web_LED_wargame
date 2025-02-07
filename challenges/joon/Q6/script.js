document.addEventListener('DOMContentLoaded', () => {
    // 우클릭 방지
    document.addEventListener('contextmenu', function(event) {
        event.preventDefault();
        const warningBox = document.querySelector('.warning-box');
        warningBox.style.animation = 'glitch 0.3s linear';
    });

    // 힌트 토글
    window.toggleHint = function() {
        const hint = document.querySelector('.hint');
        if (hint.style.display === 'block') {
            hint.style.display = 'none';
        } else {
            hint.style.display = 'block';
            hint.innerHTML = `
                <p>🔑 크롤링 방지와 관련된 중요한 단서를 얻기 위해 robots.txt 파일을 확인하세요.</p>
                <p>💡 발견한 메시지는 Base64로 인코딩되어 있을 수 있습니다.</p>
            `;
        }
    };

    // 스캔 라인 애니메이션
    const scanLine = document.querySelector('.scan-line');
    if (scanLine) {
        setInterval(() => {
            scanLine.style.top = '0';
            setTimeout(() => {
                scanLine.style.top = '100%';
            }, 100);
        }, 3000);
    }

    // 콘솔에 힌트 메시지 출력
    console.log('%c🔍 쿠키에 숨겨진 비밀을 찾아보세요...', 'color: #00f3ff; font-size: 14px; font-weight: bold;');
}); 