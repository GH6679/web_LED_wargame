class Terminal {
    constructor() {
        this.input = document.getElementById('userInput');
        this.output = document.getElementById('output');
        this.currentPath = '/home/ledteam5';
        this.successCount = 0;
        this.isConnected = false;
        
        this.input.addEventListener('keydown', this.handleInput.bind(this));
        this.writeOutput('LedTeam 시스템 v5.0에 오신 것을 환영합니다');
        this.writeOutput('사용 가능한 명령어를 보려면 "help"를 입력하세요');
        this.modal = document.getElementById('passwordModal');
        this.passwordInput = document.getElementById('passwordInput');
        this.setupPasswordModal();
    }

    setupPasswordModal() {
        this.passwordInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                const password = this.passwordInput.value;
                this.handlePasswordAttempt(password);
                this.passwordInput.value = '';
            }
        });
    }

    handlePasswordAttempt(password) {
        const decodedPassword = atob('VFBYVVpQYUlpeXNlNzAyNnB1R0U0Z0NBVDJnbk9t');
        if (password === decodedPassword) {
            this.showModalMessage('접근 권한 승인', 'success');
            setTimeout(() => {
                this.closeModal();
                this.writeOutput('인증 성공');
                this.writeOutput('ledteam6 시스템과 연결되었습니다');
                this.writeOutput('이제 nc 명령어로 서버와 통신할 수 있습니다');
                this.isConnected = true;
            }, 2000);
        } else {
            this.showModalMessage('접근 거부', 'error');
            setTimeout(() => {
                this.closeModal();
                this.writeOutput('인증 실패');
            }, 2000);
        }
    }

    showModalMessage(message, type) {
        const statusDiv = this.modal.querySelector('.connection-status');
        statusDiv.innerHTML = `<div class="${type}-message">${message}</div>`;
    }

    handleInput(e) {
        if (e.key === 'Enter') {
            const command = this.input.value.trim();
            this.writeOutput(`ledteam5@machine:${this.currentPath}$ ${command}`);
            this.processCommand(command);
            this.input.value = '';
            e.preventDefault();
        }
    }

    processCommand(command) {
        if (command === 'nc localhost 30000') {
            this.modal.style.display = 'block';
            this.passwordInput.focus();
            return;
        }

        // 필수 요소들이 모두 포함된 명령어인지 확인
        const requiredElements = [
            'echo',
            atob('VFBYVVpQYUlpeXNlNzAyNnB1R0U0Z0NBVDJnbk9t'),
            '|',
            'nc',
            'localhost',
            '30000'
        ];

        // 모든 필수 요소가 포함되어 있는지 확인
        const hasAllElements = requiredElements.every(element => 
            command.includes(element)
        );

        if (hasAllElements && this.isConnected) {
            // 40% 확률로 연결 실패
            if (Math.random() < 0.4) {
                this.writeOutput('연결 실패: 서버 시간 초과');
                this.writeOutput('서버가 불안정합니다. 다시 시도하세요...');
                return;
            }

            this.successCount++;
            if (this.successCount >= 3) {
                this.writeOutput('=== 연결 성공 ===');
                this.writeOutput('보안 저장소 접근 중...');
                setTimeout(() => {
                    this.writeOutput('비밀번호 복호화 중...');
                    setTimeout(() => {
                        this.writeOutput('');
                        this.writeOutput('🔓 LEDTEAM6 비밀번호 발견 🔓');
                        this.writeOutput('비밀번호: zo1zJAvwxaFOX3J0A3rKoINfz1YCHW');
                        this.writeOutput('');
                        this.successCount = 0;
                        this.isConnected = false;
                    }, 1000);
                }, 1000);
            } else {
                this.writeOutput('연결 성공!');
                this.writeOutput(`진행도: ${this.successCount}/3 성공`);
                this.writeOutput('계속해서 비밀번호를 전송하세요...');
            }
        } else if (!hasAllElements && command.includes('nc')) {
            this.writeOutput('연결 실패: 잘못된 명령어 형식');
            this.writeOutput('힌트: echo 명령어와 올바른 비밀번호를 사용하세요');
        } else {
            switch(command) {
                case 'help':
                    this.showHelp();
                    break;
                case 'clear':
                    this.output.innerHTML = '';
                    break;
                default:
                    if (!this.isConnected) {
                        this.writeOutput(`Command not found: ${command}`);
                    }
            }
        }
    }

    showHelp() {
        this.writeOutput('사용 가능한 명령어:');
        this.writeOutput('  nc localhost 30000   - 서버에 연결');
        this.writeOutput('  echo "비밀번호" | nc localhost 30000  - 서버에 비밀번호 전송');
        this.writeOutput('  clear                - 화면 지우기');
        this.writeOutput('  help                 - 도움말 보기');
    }

    writeOutput(text) {
        const line = document.createElement('div');
        line.textContent = text;
        this.output.appendChild(line);
        this.output.scrollTop = this.output.scrollHeight;
    }

    closeModal() {
        this.modal.style.display = 'none';
        this.modal.querySelector('.connection-status').innerHTML = `
            <div class="status-line">연결 상태: <span class="blink">대기중</span></div>
            <div class="status-line">대상: LEDTEAM6 시스템</div>
            <div class="status-line">포트: 30000</div>
        `;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    window.terminal = new Terminal();
}); 