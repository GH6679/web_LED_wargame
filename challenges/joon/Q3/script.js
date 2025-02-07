class Terminal {
    constructor() {
        this.input = document.getElementById('userInput');
        this.output = document.getElementById('output');
        this.currentPath = '/home/ledteam3';
        
        // 단순화된 파일 시스템
        this.files = {
            '--ledteam1.txt': 'Flag: yJCkGhAwcqWAHtDAzckST7LS5yXIan'
        };

        this.input.addEventListener('keydown', this.handleInput.bind(this));
        this.writeOutput('LedTeam 시스템 v24.11.19에 오신 것을 환영합니다');
        this.writeOutput('사용 가능한 파일을 보려면 "ls"를 입력하세요');
    }

    handleInput(e) {
        if (e.key === 'Enter') {
            const command = this.input.value.trim();
            this.writeOutput(`ledteam@machine:${this.currentPath}$ ${command}`);
            this.processCommand(command);
            this.input.value = '';
            e.preventDefault();
        }
    }

    processCommand(command) {
        const args = command.split(' ');
        const cmd = args[0];

        switch(cmd) {
            case 'ls':
                this.handleLs();
                break;
            case 'pwd':
                this.writeOutput(this.currentPath);
                break;
            case 'cat':
                this.handleCat(args);
                break;
            case 'cd':
                this.handleCd(args[1]);
                break;
            default:
                this.writeOutput(`Command not found: ${cmd}`);
        }
    }

    handleLs() {
        this.writeOutput('--ledteam1.txt');
    }

    handleCat(args) {
        if (args.length < 2) {
            this.writeOutput('사용법: cat <파일명>');
            return;
        }

        const filename = args[1];
        
        // 직접적인 --파일 접근 시도
        if (filename === '--ledteam1.txt') {
            this.writeOutput('cat: invalid option -- \'-\'');
            this.writeOutput('Try \'cat --help\' for more information.');
            return;
        }

        // 상대 경로를 사용한 올바른 접근 (예: cat ./--ledteam1.txt)
        if (filename === './--ledteam1.txt') {
            this.writeOutput(this.files['--ledteam1.txt']);
            return;
        }

        this.writeOutput(`cat: ${filename}: No such file or directory`);
    }

    handleCd(path) {
        if (!path || path === '.') return;
        if (path === '..') {
            if (this.currentPath === '/') return;
            this.currentPath = this.currentPath.split('/').slice(0, -1).join('/') || '/';
        } else {
            this.currentPath = path.startsWith('/') ? path : `${this.currentPath}/${path}`;
        }
        this.updatePrompt();
    }

    updatePrompt() {
        const promptElement = document.querySelector('.prompt');
        promptElement.textContent = `ledteam@machine:${this.currentPath}$ `;
    }

    writeOutput(text) {
        const line = document.createElement('div');
        line.textContent = text;
        this.output.appendChild(line);
        this.output.scrollTop = this.output.scrollHeight;
    }
}

// 초기화
document.addEventListener('DOMContentLoaded', () => {
    window.terminal = new Terminal();
}); 