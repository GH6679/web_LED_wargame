class Terminal {
    constructor() {
        this.input = document.getElementById('userInput');
        this.output = document.getElementById('output');
        this.currentPath = '/home/ledteam4';
        
        this.files = {
            'firdel_encrypted_f': 'ENCRYPTED_CONTENT',
            'README.txt': '환영합니다!!!!\n이 문제의 열쇠(패스워드)는 이름을 뒤집는 것에 달려 있습니다!!!!!'
        };

        this.input.addEventListener('keydown', this.handleInput.bind(this));
        this.writeOutput('LedTeam 시스템 v24.11.19에 오신 것을 환영합니다');
        this.writeOutput('사용 가능한 파일을 보려면 "ls"를 입력하세요');
        this.writeOutput('현재 사용자: ledteam4');
    }

    handleInput(e) {
        if (e.key === 'Enter') {
            const command = this.input.value.trim();
            
            if (command) {
                this.writeOutput(`ledteam4@machine:${this.currentPath}$ ${command}`);
                
                this.processCommand(command);
            }
            
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
            case 'cat':
                this.handleCat(args);
                break;
            case 'openssl':
                this.handleOpenSSL(args);
                break;
            case 'clear':
                this.output.innerHTML = '';
                break;
            case 'help':
                this.showHelp();
                break;
            default:
                this.writeOutput(`Command not found: ${cmd}`);
        }
    }

    handleOpenSSL(args) {
        if (args.length !== 14) {
            this.writeOutput('Usage: openssl enc -d -aes-256-cbc -salt -in firdel_encrypted_f -out [output_file] -k ledrif -pbkdf2 -iter 10000');
            return;
        }

        if (args[1] === 'enc' && 
            args[2] === '-d' && 
            args[3] === '-aes-256-cbc' && 
            args[4] === '-salt' && 
            args[5] === '-in' && 
            args[6] === 'firdel_encrypted_f' && 
            args[7] === '-out' && 
            args[9] === '-k' && 
            args[10] === 'ledrif' && 
            args[11] === '-pbkdf2' && 
            args[12] === '-iter' && 
            args[13] === '10000') {
            
            const outputFileName = args[8];
            
            this.writeOutput('Decrypting file...');
            
            this.files[outputFileName] = 'Decrypted content: TPXUZPaIiyse7026puGE4gCAT2gnOm';
            
            setTimeout(() => {
                this.writeOutput('Decryption completed successfully.');
                this.handleLs();
            }, 1000);
        } else {
            this.writeOutput('Error: Invalid openssl command format');
        }
    }

    handleLs() {
        const fileList = Object.keys(this.files).sort();
        this.writeOutput(fileList.join('\n'));
    }

    handleCat(args) {
        if (args.length < 2) {
            this.writeOutput('Usage: cat <filename>');
            return;
        }

        const filename = args[1];
        if (this.files[filename]) {
            if (filename === 'firdel_encrypted_f') {
                this.writeOutput('cat: firdel_encrypted_f: Cannot display binary file');
            } else {
                this.writeOutput(this.files[filename]);
            }
        } else {
            this.writeOutput(`cat: ${filename}: No such file or directory`);
        }
    }

    writeOutput(text) {
        const line = document.createElement('div');
        line.textContent = text;
        line.className = 'output-line';
        this.output.appendChild(line);
        this.output.scrollTop = this.output.scrollHeight;
    }
}

new Terminal();