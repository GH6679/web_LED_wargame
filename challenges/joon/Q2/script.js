class ViEditor {
    constructor() {
        this.editorContent = document.getElementById('editorContent');
        this.editorStatus = document.getElementById('editorStatus');
        this.editorTitle = document.getElementById('editorTitle');
        this.currentFile = null;
        this.searchMode = false;
        this.searchQuery = '';
        this.currentPosition = -1;
        this.lastSearchQuery = '';
        this.password = 'Ewlx0Dh8KWf4uus8PVBeiOR5AvrtCw';

        // 파일 시스템 설정
        this.files = {
            'ledteam1.txt': this.generateRandomContent(false),
            'ledteam2.txt': this.generateRandomContent(true)  // true인 파일에만 company와 비밀번호 포함
        };

        document.addEventListener('keydown', this.handleKeyPress.bind(this));
    }

    generateRandomContent(includeTarget) {
        const randomChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()';
        let lines = [];
        
        // 랜덤 문자열 생성 (50줄)
        for (let i = 0; i < 50; i++) {
            let line = '';
            for (let j = 0; j < 80; j++) {  // 한 줄당 80자
                line += randomChars[Math.floor(Math.random() * randomChars.length)];
            }
            lines.push(line);
        }

        if (includeTarget) {
            // company와 비밀번호를 포함한 줄 생성
            const targetLine = `${randomChars.slice(0, 20)}company ${this.password}${randomChars.slice(0, 20)}`;
            // 랜덤한 위치(10~40번째 줄 사이)에 삽입
            const insertPosition = Math.floor(Math.random() * 30) + 10;
            lines[insertPosition] = targetLine;
        }
        
        return lines.join('\n');
    }

    handleKeyPress(e) {
        if (!this.currentFile) return;

        if (this.searchMode) {
            if (e.key === 'Enter') {
                this.lastSearchQuery = this.searchQuery;
                this.executeSearch();
                this.exitSearchMode();
                e.preventDefault();
            } else if (e.key === 'Escape') {
                this.exitSearchMode();
                e.preventDefault();
            } else if (e.key.length === 1) {
                e.preventDefault();
                this.searchQuery += e.key;
                this.updateStatus('/' + this.searchQuery);
            } else if (e.key === 'Backspace') {
                e.preventDefault();
                this.searchQuery = this.searchQuery.slice(0, -1);
                this.updateStatus('/' + this.searchQuery);
            }
        } else {
            if (e.key === '/') {
                this.enterSearchMode();
                e.preventDefault();
            } else if (e.key === 'n') {
                if (this.lastSearchQuery) {
                    this.findNext();
                }
                e.preventDefault();
            } else if (e.key === ':') {
                this.handleCommand();
                e.preventDefault();
            }
        }
    }

    enterSearchMode() {
        this.searchMode = true;
        this.searchQuery = '';
        this.updateStatus('/');
    }

    exitSearchMode() {
        this.searchMode = false;
        this.updateStatus();
    }

    executeSearch() {
        if (!this.searchQuery.trim()) return;
        
        const content = this.files[this.currentFile];
        const searchTerm = 'company';
        const index = content.indexOf(searchTerm, this.currentPosition + 1);
        
        if (index !== -1) {
            this.currentPosition = index;
            this.highlightSearch(index, searchTerm);
            this.scrollToSearch(index);
        } else {
            // 현재 위치 이후에 찾지 못했다면 처음부터 다시 검색
            const newIndex = content.indexOf(searchTerm, 0);
            if (newIndex !== -1 && newIndex !== this.currentPosition) {
                this.currentPosition = newIndex;
                this.highlightSearch(newIndex, searchTerm);
                this.scrollToSearch(newIndex);
            }
        }
    }

    highlightSearch(index, searchTerm) {
        const content = this.files[this.currentFile];
        const before = content.substring(0, index);
        const match = content.substring(index, index + searchTerm.length);
        const passwordIndex = content.indexOf(this.password, index);
        let after;
        
        if (passwordIndex !== -1 && passwordIndex < index + 100) { // company 이후 100자 이내에 비밀번호가 있는 경우
            const betweenText = content.substring(index + searchTerm.length, passwordIndex);
            const password = content.substring(passwordIndex, passwordIndex + this.password.length);
            after = content.substring(passwordIndex + this.password.length);
            
            this.editorContent.innerHTML = `${this.escapeHtml(before)}<span class="highlight-search">${this.escapeHtml(match)}</span>${this.escapeHtml(betweenText)}<span class="highlight-password">${this.escapeHtml(password)}</span>${this.escapeHtml(after)}`;
        } else {
            after = content.substring(index + searchTerm.length);
            this.editorContent.innerHTML = `${this.escapeHtml(before)}<span class="highlight-search">${this.escapeHtml(match)}</span>${this.escapeHtml(after)}`;
        }
    }

    scrollToSearch(index) {
        // 검색된 위치로 스크롤
        const lineHeight = 20; // 예상 라인 높이
        const lines = this.files[this.currentFile].substring(0, index).split('\n').length;
        this.editorContent.scrollTop = (lines - 1) * lineHeight;
    }

    escapeHtml(text) {
        return text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    findNext() {
        if (this.lastSearchQuery) {
            this.searchQuery = this.lastSearchQuery;
            this.executeSearch();
        }
    }

    updateStatus(text = '') {
        const modeIndicator = this.editorStatus.querySelector('.mode-indicator');
        const searchIndicator = this.editorStatus.querySelector('.search-indicator');
        
        if (this.searchMode) {
            modeIndicator.textContent = 'SEARCH';
            searchIndicator.textContent = text;
        } else {
            modeIndicator.textContent = 'NORMAL';
            searchIndicator.textContent = '';
        }
    }

    openFile(filename) {
        if (this.files[filename]) {
            this.currentFile = filename;
            this.editorContent.textContent = this.files[filename];
            this.editorTitle.textContent = `VI EDITOR - ${filename}`;
            this.currentPosition = 0;
            return true;
        }
        return false;
    }

    handleCommand() {
        const command = prompt(':');
        if (command === 'q') {
            this.currentFile = null;
            this.editorContent.textContent = '';
            this.editorTitle.textContent = 'VI EDITOR';
        }
    }
}

class Terminal {
    constructor() {
        this.input = document.getElementById('userInput');
        this.output = document.getElementById('output');
        this.viEditor = new ViEditor();
        this.currentPath = '/home/ledteam2';
        
        this.input.addEventListener('keydown', this.handleInput.bind(this));
        this.writeOutput('LedTeam 시스템 v24.11.19에 오신 것을 환영합니다');
        this.writeOutput('파일 목록을 보려면 "ls"를 입력하세요');
    }

    updatePrompt() {
        const promptElement = document.querySelector('.prompt');
        promptElement.textContent = `ledteam@machine:${this.currentPath}$ `;
    }

    handleInput(e) {
        if (e.key === 'Enter') {
            const command = this.input.value.trim();
            this.processCommand(command);
            this.input.value = '';
            e.preventDefault();
        }
    }

    processCommand(command) {
        const args = command.split(' ');
        const cmd = args[0];

        switch(cmd) {
            case 'pwd':
                this.writeOutput(this.currentPath);
                break;
            case 'vi':
                if (args.length < 2) {
                    this.writeOutput('사용법: vi <파일명>');
                    this.writeOutput('힌트: vi 에디터에서 "/" 를 눌러 검색할 수 있습니다');
                    return;
                }
                if (this.viEditor.openFile(args[1])) {
                    this.writeOutput(`Opening ${args[1]}...`);
                } else {
                    this.writeOutput(`Error: ${args[1]} not found`);
                }
                break;
            case 'cat':
                if (args.length < 2) {
                    this.writeOutput('사용법: cat <파일명>');
                    return;
                }
                const filename = args[1];
                if (this.viEditor.files[filename]) {
                    this.writeOutput(this.viEditor.files[filename]);
                } else {
                    this.writeOutput(`Error: ${filename} not found`);
                }
                break;
            case 'ls':
                this.writeOutput('ledteam1.txt  ledteam2.txt');
                break;
            default:
                this.writeOutput(`Command not found: ${cmd}`);
        }
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