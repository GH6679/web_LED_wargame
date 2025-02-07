// 스코어 카드 생성기
const createScoreCard = (data, rank) => {
    try {

        // 랭크 번호 컨테이너
        const card = document.createElement('tr');
        card.className = 'rank-' + rank;

        // 랭크 번호 표시
        const rank_td = document.createElement('td');
        rank_td.textContent = rank;

        // 유저 이름 표시
        const user_td = document.createElement('td');
        user_td.textContent = data.nickname;


        // 포인트 표시
        const point_td = document.createElement('td');
        point_td.textContent = data.total_point + "/" + data.Tpoint;

        // 해결 문제 표시
        const challenge_td = document.createElement('td');
        challenge_td.textContent = data.total_solved + "/" + data.Ccount;

        // 마지막 활동 시간 표시
        const last_active_td = document.createElement('td');
        last_active_td.textContent = data.latest_date;

        card.appendChild(rank_td);
        card.appendChild(user_td);
        card.appendChild(point_td);
        card.appendChild(challenge_td);
        card.appendChild(last_active_td);

        return card;

    } catch (error) {
        console.error('스코어 카드 생성 중 오류 발생:', error);
    }

}

// 최근 활동 카드 생성기
const createActivityCard = (data) => {
    // 메인 카드 컨테이너
    const card = document.createElement('div');
    card.className = 'activity-card';

    // 아이콘
    const icon = document.createElement('span');
    icon.className = 'material-symbols-outlined text-success';
    icon.textContent = 'task_alt';

    // 내용을 담을 div
    const contentDiv = document.createElement('div');

    // 텍스트 내용 생성
    const content = document.createElement('div');

    // 유저 이름
    const userStrong = document.createElement('strong');
    userStrong.textContent = data.nickname;

    // 챌린지 이름
    const challengeSpan = document.createElement('span');
    challengeSpan.className = 'text-info';
    challengeSpan.textContent = data.c_title;

    // 시간 표시
    const timeDiv = document.createElement('div');
    timeDiv.className = 'text-muted small';
    timeDiv.textContent = data.d_time;

    // 요소들 조립
    content.appendChild(userStrong);
    content.appendChild(document.createTextNode(' solved '));
    content.appendChild(challengeSpan);

    contentDiv.appendChild(content);
    contentDiv.appendChild(timeDiv);

    card.appendChild(icon);
    card.appendChild(contentDiv);

    return card;
}

// 메인 첼린지 카드 생성기
function createChallengeCard(data) {
    // 메인 카드 컨테이너
    const card = document.createElement('div');
    card.className = 'challenge-card';

    // 상단 타입과 난이도 컨테이너
    const headerContainer = document.createElement('div');
    headerContainer.className = 'd-flex justify-content-between align-items-center mb-3';

    // 챌린지 타입 (WEB)
    const typeSpan = document.createElement('span');
    typeSpan.className = 'challenge-type web';
    typeSpan.textContent = 'WEB';

    // 난이도
    const difficultySpan = document.createElement('span');
    difficultySpan.className = `difficulty ${data.c_difficulty.toLowerCase()}`;
    difficultySpan.textContent = data.c_difficulty;

    // 제목
    const title = document.createElement('h3');
    title.textContent = data.c_title;

    // 설명
    const description = document.createElement('p');
    description.className = 'text-muted small mb-3';
    // description.textContent = data.c_text;
    description.textContent = "웹은 웹 페이지 고유 설명을 따릅니다.";


    // 하단 포인트와 솔브 수 컨테이너
    const footerContainer = document.createElement('div');
    footerContainer.className = 'd-flex justify-content-between';

    // 포인트
    const pointsSpan = document.createElement('span');
    pointsSpan.className = 'points';
    pointsSpan.textContent = `${data.c_point} pts`;

    // 솔브 수
    const solveCountSpan = document.createElement('span');
    solveCountSpan.className = 'solve-count';
    solveCountSpan.textContent = `Solves: ${data.c_solves}`;

    // 요소들 조립
    headerContainer.appendChild(typeSpan);
    headerContainer.appendChild(difficultySpan);

    footerContainer.appendChild(pointsSpan);
    footerContainer.appendChild(solveCountSpan);

    card.appendChild(headerContainer);
    card.appendChild(title);
    card.appendChild(description);
    card.appendChild(footerContainer);

    return card;
}

// 진행도 바 애니메이션 생성기
function animateProgress(element, targetPercentage, duration = 1500) {
    // 항상 0에서 시작
    element.style.width = '0%';
    const startTime = performance.now();

    // targetPercentage가 100을 넘지 않도록 제한
    targetPercentage = Math.min(targetPercentage, 100);

    function update(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);

        // 0에서 목표값까지 부드럽게 증가
        const currentWidth = progress * targetPercentage;
        element.style.width = `${currentWidth}%`;

        if (progress < 1) {
            requestAnimationFrame(update);
        }
    }

    requestAnimationFrame(update);
}

// 진행도 바 생성기
function createProgressBar(label, current, total) {
    // 0으로 나누기 방지
    if (!total) total = 1;

    const percentage = (current / total) * 100;

    // 진행도 바 라벨 생성
    const labelEl = document.createElement('label');
    labelEl.className = 'form-label mt-3';
    labelEl.setAttribute('for', 'progress-bar');
    labelEl.textContent = label;

    // 진행도 바 컨테이너 생성
    const progressContainer = document.createElement('div');
    progressContainer.className = 'progress position-relative'; // position-relative 추가

    // 진행도 바 엘리먼트 생성
    const progressEl = document.createElement('div');
    progressEl.className = 'progress-bar custom-progress-bar';
    progressEl.style.width = percentage + '%';

    // 수치 표시용 텍스트 엘리먼트 생성
    const textEl = document.createElement('span');
    textEl.className = 'position-absolute w-100 text-center'; // Bootstrap 클래스로 중앙 정렬
    textEl.style.left = '0';
    textEl.style.right = '0';
    textEl.style.top = '50%';
    textEl.style.transform = 'translateY(-50%)'; // 수직 중앙 정렬
    textEl.textContent = `${current} / ${total}`;

    // 합치기
    progressContainer.appendChild(progressEl);
    progressContainer.appendChild(textEl);

    // JavaScript 애니메이션 적용
    animateProgress(progressEl, percentage);

    return {
        labelEl,
        progressEl: progressContainer // 컨테이너를 반환
    };
}


// 라인 생성기
function createLineBar(label) {

    // 라인 라벨 생성
    const labelEl = document.createElement('label');
    labelEl.className = 'form-label mt-3';
    labelEl.setAttribute('for', 'line-bar');
    labelEl.textContent = label;

    // 진행도 바 컨테이너 생성
    const lineBarEl = document.createElement('div');
    lineBarEl.className = 'line-bar'
    lineBarEl.style.borderTop = "1px solid #fff";
    lineBarEl.style.width = "100%";
    lineBarEl.style.height = "1px";

    return {
        labelEl,
        lineBarEl
    };
}

