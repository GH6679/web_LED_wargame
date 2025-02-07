class CommandProcessor {
    constructor(console) {
        this.console = console;
        this.currentDir = '/home/user';
        this.fileSystem = {
            '/home/user': {
                'memo.txt': {
                    type: 'file',
                    content: '해커의 메모\n\n버퍼 오버플로우 취약점을 발견했다.\n프로그램의 리턴 주소를 덮어쓰면 get_flag() 함수를 호출할 수 있을 것 같다.\n\n* 참고: checksec으로 보안 설정 확인 필요',
                    perms: '-rw-r--r--'
                },
                'vuln': {
                    type: 'file',
                    content: '(바이너리 파일)',
                    perms: '-rwxr-xr-x'
                },
                'vuln.c': {
                    type: 'file',
                    content: `#include <stdio.h>
#include <string.h>

void get_flag() {
    // 플래그를 출력하는 함수
    system("cat flag.txt");
}

void vuln() {
    char buffer[32];    // 32바이트 버퍼
    gets(buffer);       // 취약한 함수 사용!
}

int main() {
    vuln();
    return 0;
}`,
                    perms: '-rw-r--r--'
                }
            }
        };
    }

    processCommand(cmd) {
        const args = cmd.trim().split(/\s+/);
        const command = args[0];

        switch(command) {
            case 'ls':
                return this.handleLs();
            case 'cat':
                return this.handleCat(args);
            case 'checksec':
                return this.handleChecksec(args);
            case 'gdb':
                return this.handleGdb(args);
            case 'python':
            case 'python3':
                return this.handlePython(args);
            case 'help':
                return this.showHelp();
            case 'clear':
                this.console.clear();
                return '';
            default:
                return `${command}: 명령어를 찾을 수 없습니다`;
        }
    }

    showHelp() {
        return `
📚 사용 가능한 명령어
    ls                - 📁 파일 목록 확인
    cat <파일>        - 📄 파일 내용 확인
    checksec <파일>   - 🔒 바이너리 보안 설정 확인
    gdb <파일>        - 🔍 프로그램 분석
    python/python3    - 🐍 Python 명령어 실행
    help             - ❓ 도움말 보기
    clear            - 🧹 화면 지우기

🎯 문제 해결 단계
    1. memo.txt와 힌트 파일들을 읽어보세요
    2. checksec으로 보안 설정을 확인하세요
    3. gdb로 중요한 함수의 주소를 찾으세요
    4. 버퍼 오버플로우 공격을 시도해보세요

💡 힌트
    도움이 필요하면 hint1.txt와 hint2.txt를 확인하세요.`;
    }

    handleLs() {
        return `
📁 파일 목록
    총 5개의 파일이 있습니다:
    
    -rw-r--r--    📄 memo.txt
    -rwxr-xr-x    ⚡ vuln
    -rw-r--r--    📝 vuln.c
    -rw-r--r--    💡 hint1.txt
    -rw-r--r--    💡 hint2.txt`;
    }

    handleCat(args) {
        if (args.length < 2) {
            return '⚠️  사용법: cat <파일명>';
        }
        const filename = args[1];
        switch(filename) {
            case 'memo.txt':
                return `
📄 memo.txt
    해커의 메모

    버퍼 오버플로우 취약점을 발견했다.
    프로그램의 리턴 주소를 덮어쓰면 get_flag() 함수를 호출할 수 있을 것 같다.

    * 참고: checksec으로 보안 설정 확인 필요`;

            case 'vuln.c':
                return `
📝 vuln.c
#include <stdio.h>
#include <string.h>

void get_flag() {
    // 플래그를 출력하는 함수
    system("cat flag.txt");
}

void vuln() {
    char buffer[32];    // 32바이트 버퍼
    gets(buffer);       // 취약한 함수 사용!
}

int main() {
    vuln();
    return 0;
}`;

            case 'hint1.txt':
                return `
💡 hint1.txt
힌트 1: 메모리 구조 분석

1. 프로그램의 스택 구조:
   [버퍼(32바이트)] [SFP(8바이트)] [리턴주소(8바이트)]

2. gets() 함수는 입력 길이를 검사하지 않습니다.
   - 버퍼 크기보다 큰 입력이 가능합니다.
   - 스택의 다른 영역을 덮어쓸 수 있습니다.

3. checksec으로 보안 설정을 확인해보세요.`;

            case 'hint2.txt':
                return `
💡 hint2.txt
힌트 2: 공격 방법 연구

1. 익스플로잇 제작 시 고려사항:
   - 버퍼를 채우는 데 필요한 크기는?
   - 리턴 주소는 어디서 찾을 수 있나요?
   - 주소는 어떤 형식으로 입력해야 할까요?

2. Python으로 페이로드를 만들 때:
   - print() 함수로 문자열 생성
   - 문자 반복은 * 연산자 사용
   - 주소는 바이트 순서 주의

* 나머지는 여러분의 실력으로 풀어보세요!`;

            default:
                return `cat: ${filename}: 그런 파일이나 디렉터리가 없습니다`;
        }
    }

    handleChecksec(args) {
        if (args.length < 2 || args[1] !== 'vuln') {
            return '⚠️  사용법: checksec vuln';
        }
        return `
🔒 보안 설정 분석 결과
    RELRO:         Partial
    STACK CANARY:  비활성화
    NX:            활성화
    PIE:           비활성화

    🔍 분석: 스택 카나리가 없어 버퍼 오버플로우 공격이 가능합니다.`;
    }

    handleGdb(args) {
        if (args.length < 2) return '⚠️  사용법: gdb <파일명>';
        if (args[1] !== 'vuln') return `❌ 파일을 찾을 수 없습니다: ${args[1]}`;
        
        return `
🔍 GNU gdb 분석 결과
    1) get_flag() 함수 주소: 0x4011d6
    2) vuln() 함수 주소:     0x401196
    3) main() 함수 주소:     0x4011c6

    💡 힌트: get_flag() 함수를 호출하면 플래그를 얻을 수 있습니다.`;
    }

    handlePython(args) {
        const fullCmd = args.join(' ');
        if (fullCmd.includes('print("A"*40 + "\\xd6\\x11\\x40\\x00\\x00\\x00\\x00\\x00")')) {
            return `
🚀 익스플로잇 실행
    [+] 페이로드 실행 중...
    [+] 버퍼 오버플로우 성공!
    [+] get_flag() 함수 호출됨
    [+] 플래그: ioFkmtqImumg878f320a4E3n6ySeiL`;
        }
        return `
⚠️  실행 결과
    [*] 페이로드가 전송되었지만 get_flag() 함수를 호출하지 못했습니다.`;
    }

    log(message, type = '') {
        const line = document.createElement('div');
        line.className = `console-output ${type}`;
        line.style.whiteSpace = 'pre-wrap';
        line.innerHTML = message;
        this.output.appendChild(line);
        this.output.scrollTop = this.output.scrollHeight;
    }
}

// ConsoleManager 클래스
class ConsoleManager {
    constructor() {
        this.output = document.getElementById('output');
        this.input = document.getElementById('userInput');
        this.commandProcessor = new CommandProcessor(this);
        this.memoryVisualizer = new MemoryVisualizer();
        this.setupEventListeners();
        this.initialize();
    }

    initialize() {
        this.log('[*] Initializing Buffer Overflow simulator...', 'info');
        this.log('[*] Target binary loaded at 0x400000', 'info');
        this.log('[*] get_flag() function found at 0x4011d6', 'info');
        this.log('[+] Ready for exploitation!', 'success');
        this.log('\n도움말을 보려면 "help" 명령어를 입력하세요.', 'info');
    }

    setupEventListeners() {
        this.input.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                const command = this.input.value;
                this.executeCommand(command);
                this.input.value = '';
            }
        });
    }

    executeCommand(command) {
        this.log(`root@cybercity:~$ ${command}`);
        
        if (command.startsWith('python') || command.startsWith('python3')) {
            this.memoryVisualizer.updateFromPython(command);
        }

        const result = this.commandProcessor.processCommand(command);
        if (result) {
            this.log(result);
        }
    }

    log(message, type = '') {
        const line = document.createElement('div');
        line.className = `console-output ${type}`;
        line.style.whiteSpace = 'pre-wrap';
        line.innerHTML = message;
        this.output.appendChild(line);
        this.output.scrollTop = this.output.scrollHeight;
    }

    clear() {
        this.output.innerHTML = '';
        this.initialize();
    }
}

// 콘솔 시뮬레이터 시작
document.addEventListener('DOMContentLoaded', () => {
    window.consoleManager = new ConsoleManager();
});

class MemoryVisualizer {
    constructor() {
        this.bufferContent = document.getElementById('bufferContent');
        this.sfpContent = document.getElementById('sfpContent');
        this.retContent = document.getElementById('retContent');
        this.resetMemory();
    }

    resetMemory() {
        this.updateMemoryDisplay('buffer', '00'.repeat(32));
        this.updateMemoryDisplay('sfp', 'FF'.repeat(8));
        this.updateMemoryDisplay('ret', '401196');
    }

    updateFromPython(command) {
        const match = command.match(/print\("([A-Z])"\s*\*\s*(\d+)/);
        if (match) {
            const [_, char, count] = match;
            const numChars = parseInt(count);

            const bufferContent = char.repeat(Math.min(32, numChars));
            this.updateMemoryDisplay('buffer', bufferContent, numChars > 32);

            if (numChars > 32) {
                const sfpContent = char.repeat(Math.min(8, numChars - 32));
                this.updateMemoryDisplay('sfp', sfpContent, numChars > 40);
            }

            if (command.includes('\\x')) {
                const addressMatch = command.match(/\\x([0-9a-f]{2})/g);
                if (addressMatch && addressMatch.length === 8) {
                    this.updateMemoryDisplay('ret', '4011d6', true);
                }
            }
        }
    }

    updateMemoryDisplay(section, content, overflow = false) {
        const element = this[`${section}Content`];
        if (!element) return;

        let displayContent;
        switch(section) {
            case 'buffer':
                displayContent = this.formatBytes(content, 32);
                break;
            case 'sfp':
                displayContent = this.formatBytes(content, 8);
                break;
            case 'ret':
                displayContent = `0x${content}`;
                break;
        }

        element.textContent = displayContent;
        element.className = `memory-content ${overflow ? 'overflow' : ''}`;
    }

    formatBytes(content, size) {
        const bytes = Array.from(content).map(c => c.charCodeAt(0).toString(16).padStart(2, '0'));
        return bytes.join(' ').padEnd(size * 3 - 1, '0');
    }
}

// CSS 추가
