const n_name_el = document.getElementById('n-name');
const n_point_el = document.getElementById('n-point');
const n_login_el = document.getElementById('n-login');
const n_ch_btn_el = document.getElementById('n-ch-btn');
const n_logout_btn_el = document.getElementById('n-logout-btn');
const challenge_grid_el = document.querySelector('.challenge-grid');
const main_stats_el = document.querySelectorAll('#main-stats-container .stat-box .stat-number');
const activity_grid_el = document.querySelector('.activity-grid');
const score_table_body_el = document.querySelector('#score-table-body');

const login_msg_el = document.querySelectorAll('.login-msg');
const signin_msg_el = document.querySelectorAll('.signin-msg');

let is_id_check = false;
let is_pass_check = false;

// 경고 모달 호출
const callBsModal = (title, body, end) => {


    a_modal_el = new bootstrap.Modal(document.getElementById('alert-modal'));
    document.getElementById('alert-modal-title').textContent = title;
    document.getElementById('alert-modal-body').textContent = body;
    document.getElementById('alert-modal-end').addEventListener('click', () => {
        var mm = end;
        if (mm) location.reload();
    });
    a_modal_el.show();
}

// 유저 챌린지 데이터 업데이트
const setUserChallengesData = async () => {

    let u_point = 0;
    try {
        const data = await fetchData('/php/getUChallengesData.php');

            // 초기화
            challenge_grid_el.innerHTML = '';

            // 최근것 3개 까지만 출력
            let limit = 3;
            if (data.length > 0) {


                for (let i = 0; i < data.length; i++) {

                    if (i < limit) {
                        const card = createChallengeCard(data[i]);


                        challenge_grid_el.appendChild(card);
                        u_point += data[i].c_point;

                    } else {
                        u_point += data[i].c_point;

                    }

                    // 포인트 총합 출력
                    n_name_el.textContent = "<?php echo $_SESSION['nickname']; ?>"
                    // n_point_el.textContent = "포인트 : " + "<?php echo $_SESSION['u_point']; ?>"
                    n_point_el.textContent = "포인트 : " + u_point;



                }


            } else {
                // 만약 새로 생성한 계정 이기때문에 getUChallengesData 이 값을 불러오지 못한다면.
                // 포인트 총합 출력
                n_name_el.textContent = "<?php echo $_SESSION['nickname']; ?>"
                // n_point_el.textContent = "포인트 : " + "<?php echo $_SESSION['u_point']; ?>"
                n_point_el.textContent = "포인트 : " + u_point;


            }



    } catch (error) {
        console.error('메인 데이터 업데이트 중 오류 발생:', error);
    }

}

// 메인 통계 정보 업데이트
const setMainData = async () => {

    try {
        // 메인 통계 정보 출력
        const data = await fetchData('/php/getMainData.php');

            main_stats_el.forEach((item, i) => {
                if (i == 0) {
                    item.textContent = data.Ccount;
                } else if (i == 1) {
                    item.textContent = data.Ucount;
                } else if (i == 2) {
                    item.textContent = data.Tsolves;
            }
        });


    } catch (error) {
        console.error('메인 통계 정보 업데이트 중 오류 발생:', error);
    }

}

// 문제 카드 생성기
const c_list_el = document.getElementById('c-list');
setChallenges = async (sessionData) => {

    try {
        const data = await fetchData('/php/getChallenges.php', 'POST', null);
        data.forEach(item => {

            // 문제 컨테이너
            var c_box = document.createElement('div');
            c_box.className = "col-2 mb-2 mx-1 pb-2 pt-1 plist c_box";

            // 문제 제목 컨테이너
            var c_title_box = document.createElement('div');
            c_title_box.className = "d-flex justify-content-center p-2 c_title_box";

            // 문제 제목 클릭 이벤트
            c_title_box.addEventListener('click', () => {
                if (sessionData.user_access) {
                    window.open(item.c_link, '_blank');
                } else {
                    callBsModal('경고', '로그인 되지 않았습니다.', false);
                }
            })

            // 문제 제목
            var c_title = document.createElement('span');
            c_title.textContent = item.c_title;
            c_title.className = "fw-bold fs-5 text-white";

            // 문제 내용 컨테이너
            var c_content_box = document.createElement('div');
            c_content_box.className = "p-3";

            // 문제 난이도 및 포인트 출력 라벨
            var c_label = document.createElement('label');
            c_label.className = "form-label d-flex justify-content-between mb-2";
            c_label.setAttribute("for", "sol" + item.c_id);
            c_label.innerHTML = `
                <span class="challenge-badge badge difficulty-${item.c_difficulty.toLowerCase()}">${item.c_difficulty}</span>
                <span class="challenge-badge badge points">${item.c_point} pts</span>
            `;

            // 키값 입력 FORM
            var c_input = document.createElement('input');
            c_input.className = "form-control ptext mt-4";
            c_input.id = "sol" + item.c_id;
            c_input.style.transition = "all 0.3s ease";

            // 이미 정답 처리된 문제 처리
            if (item.c_clear == 'true') {

                c_title_box.style.background = "rgba(0, 255, 0, 0.6)";
                c_title_box.style.border = "1px solid rgba(0, 255, 0, 0.8)";

                c_box.style.border = "1px solid rgba(0, 255, 0, 0.5)";
                c_box.style.boxShadow = "0 0 10px rgba(0, 255, 0, 0.5)";
                c_box.style.animation = "statGlowgreen 3s infinite alternate";

                c_input.value = 'CLEAR!';
                c_input.type = 'text';
                c_input.readOnly = true;

            } else {
                // 키값 입력 FORM 설정
                c_input.setAttribute('placeholder', "key input");
                c_input.setAttribute('type', "password");
                // 비로그인 검증
                c_input.addEventListener('click', () => {
                    if (!sessionData.user_access) {
                        callBsModal('경고', '키값을 입력하려면 로그인 해야합니다.', false);
                    }
                });

                // 문제 정답 키값 입력 로직
                c_input.addEventListener('input', async function () {

                    // 키값 코드는 글자수가 30글자 이므로 30 글자 일때만 동작
                    if (c_input.value.length == 30) {

                        // 로그인 확인
                        if (sessionData.user_access) {

                            const data = await fetchData('/php/setkey.php', 'POST', {
                                username: sessionData.username,
                                c_id: item.c_id,
                                key: c_input.value
                            })
                            if (data.success) {

                                // 정답 확인 후 처리
                                c_title_box.style.background = "rgba(0, 255, 0, 0.6)";
                                c_title_box.style.border = "1px solid rgba(0, 255, 0, 0.8)";

                                c_box.style.border = "1px solid rgba(0, 255, 0, 0.5)";
                                c_box.style.boxShadow = "0 0 10px rgba(0, 255, 0, 0.5)";
                                c_box.style.animation = "statGlowgreen 3s infinite alternate";

                                c_input.value = 'CLEAR!';
                                c_input.type = 'text';
                                c_input.readOnly = true;

                                // 메인 데이터 출력
                                setUserChallengesData();
                                // 메인 통계 출력
                                setMainData();
                            }
                        }
                    }
                });
            }

            c_box.appendChild(c_title_box);
            c_box.appendChild(c_content_box);
            c_title_box.appendChild(c_title);
            c_content_box.appendChild(c_label);
            c_content_box.appendChild(c_input);
            c_list_el.appendChild(c_box);

        });
    } catch (error) {
        console.error('문제 카드 생성 중 오류 발생:', error);
    }

}


// 스코어 카드 생성기
const list_score_list_el = document.getElementById('list-score-list');
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

// 스코어 보드 업데이트
const setScoreBoard = async () => {
    try {

        const data = await fetchData('/php/getScoreBoard.php');

        score_table_body_el.innerHTML = '';
        data.forEach((item, index) => {
            const card = createScoreCard(item, index + 1);
            score_table_body_el.appendChild(card);
        });

    } catch (error) {
        console.error('스코어 보드 업데이트 중 오류 발생:', error);
    }


}

// 왼쪽 메뉴 스코어 리스트 클릭 이벤트
list_score_list_el.addEventListener('click', () => {
    setScoreBoard();
});


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

// 최근 활동 리스트 업데이트
const setUserActivities = async () => {

    try {
        const data = await fetchData('/php/getUActivities.php');

        activity_grid_el.innerHTML = '';
        data.forEach(item => {
            const card = createActivityCard(item);
            activity_grid_el.appendChild(card);
        });

    } catch (error) {
        console.error('활동 기록 업데이트 중 오류 발생:', error);
    }

}



// 최근 활동 리스트 업데이트
async function updateUserActivities() {

    setUserActivities();

}

// 30초 간격으로 업데이트 반복
setInterval(updateUserActivities, 30000);


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



const init = async () => {
    // 세션 정보 받아오기
    const sessionData = await fetchData('/php/sessionInfo.php');

    // 챌린지 카드 생성
    setChallenges(sessionData);
    // 문제 추가 SSH/WEB 전환 버튼 업데이트
    setScoreBoard();
    // 포인트 카드 생성
    getpointview();

    // 메인 통계 정보 출력
    setMainData();
    // 최근 활동 업데이트
    setUserActivities();


    const admin_m_el = document.querySelectorAll('.admin_m');
    let userRole = sessionData.user_role;

    // console.log('userAccess=' + userAccess);
    if (sessionData.user_access) {
        // console.log('로그인됨');

        // 메인 데이터 출력
        setUserChallengesData();
        // 채팅 서버 연결 시도
        connectWebSocket();

        n_name_el.style.display = 'block';
        n_point_el.style.display = 'block';
        n_ch_btn_el.style.display = 'block';
        n_login_el.style.display = 'none';
        n_logout_btn_el.style.display = 'block';


        if (userRole == 'admin') {
            admin_m_el.forEach(item => {
                item.classList.add('d-flex');
                item.style.display = 'block';
            });
        } else {
            admin_m_el.forEach(item => {
                item.classList.remove('d-flex');
                item.style.display = 'none';
            });
        }




    } else {

        n_name_el.style.display = 'none';
        n_point_el.style.display = 'none';
        n_ch_btn_el.style.display = 'none';
        n_login_el.style.display = 'block';
        n_logout_btn_el.style.display = 'none';

        admin_m_el.forEach(item => {
            item.classList.remove('d-flex');
            item.style.display = 'none';
        });

    }



}



// 채팅창 on / off
let chaton = false;
// const mid_body_el = document.querySelector('.mid-body');

const chat_box_el = document.getElementById('chat-box');
// const n_ch_btn_el = document.getElementById('n-ch-btn');
n_ch_btn_el.addEventListener('click', function () {
    if (chaton === false) {
        chat_box_el.style.display = 'flex';
        chaton = true;
    } else {
        chat_box_el.style.display = 'none';
        chaton = false;
    }

    document.querySelector('.chat-header .btn-close').addEventListener('click', function () {
        chat_box_el.style.display = 'none';
        chaton = false;
    });



});
// 회원가입 메시지
const password_check_msg_el = document.querySelectorAll('.signin-msg');

// 비밀번호 체크
const password_el = document.getElementById('password-signin');
const password_check_el = document.getElementById('password-check-signin');

const is_pass_check_ok = () => {

    if (password_check_el.value == password_el.value) {
        password_check_msg_el[3].textContent = '암호가 일치합니다.';
        password_check_msg_el[3].style.color = 'green';
        password_check_msg_el[3].style.display = 'block';
    } else {
        password_check_msg_el[3].textContent = '암호가 일치하지 않습니다.';
        password_check_msg_el[3].style.color = 'red';
        password_check_msg_el[3].style.display = 'block';
    }

}

password_check_el.addEventListener('input', function () {

    is_pass_check_ok();

});

password_el.addEventListener('input', function () {

    is_pass_check_ok();

});

// id 중복 체크
document.getElementById('id-ch-btn').addEventListener('click', async function () {

    const data = await fetchData('/php/idcheck.php', 'POST', {
        username: signin_form_el.username.value,
    });

    if (data.success) {
        signin_msg_el[0].style.color = data.color;
        signin_msg_el[0].textContent = data.message;
    } else {
        signin_msg_el[0].style.color = data.color;
        signin_msg_el[0].textContent = data.message;
    }

})

// 회원 가입
const signin_form_el = document.getElementById('signin-form');
document.getElementById('signin-btn').addEventListener('click', async function () {


    const data = await fetchData('/php/signin.php', 'POST', {
        username: signin_form_el.username.value,
        password: signin_form_el.password.value,
        password_check: signin_form_el['password-check'].value,
        nickname: signin_form_el.nickname.value

    });

    if (data.success) {
        // 회원가입 성공시 메인페이지로 이동
        window.location.href = '/';
    } else {
        if (data.type == 'msg') {
            signin_msg_el.forEach((item, i) => {
                if (data.index == i) {
                    item.textContent = data.message;
                    item.style.color = data.color;
                } else {
                    item.textContent = '';
                }
            });
        }

    }


})


// 로그인
const login_form_el = document.getElementById('login-form');
document.getElementById('login-btn').addEventListener('click', async function () {

    const data = await fetchData('/php/login.php', 'POST', {
        username: login_form_el.username.value,
        password: login_form_el.password.value
    });

    if (data.success) {
        // 로그인 성공시 메인페이지로 이동
        window.location.href = '/';
    } else {
        // 로그인 실패 처리
        if (data.type == 'msg') {
            login_msg_el.forEach((item, i) => {
                if (data.index == i) {
                    item.textContent = data.message;
                    item.style.color = data.color;
                } else {
                    item.textContent = '';
                }
            });
        } else {
            callBsModal(data.title, data.message, data.end);
        }
    }
});

// 로그아웃 버튼 클릭 이벤트
n_logout_btn_el.addEventListener('click', function () {

    window.location.href = '/php/logout.php';

});



const body_main_el = document.getElementById('body main');
const main_menu_list_el = document.querySelector('.main-menu-list');
const c_menu_lists_el = document.querySelectorAll('.c-menu-list a');
main_menu_list_el.addEventListener('mouseenter', () => {
    c_menu_lists_el.forEach((item, index) => {
        switch (index) {
            // checklist
            case 0:
                // item.classList.remove('d-flex');
                // item.classList.remove('justify-content-center');
                item.innerHTML = '';
                item.innerHTML = '<div class="d-flex"><span class="material-symbols-outlined">home</span> &nbsp;&nbsp;<span>메인 페이지</span></div>';
                break;
            case 1:
                item.innerHTML = '';
                item.innerHTML = '<div class="d-flex"><span class="material-symbols-outlined">edit_note</span> &nbsp;&nbsp;<span>문제 모음집</span></div>';
                break;
            case 2:
                item.innerHTML = '';
                item.innerHTML = '<div class="d-flex"><span class="material-symbols-outlined">sports_score</span> &nbsp;&nbsp;<span>스코어 보드</span></div>';
                break;
            case 3:
                item.innerHTML = '';
                item.innerHTML = '<div class="d-flex"><span class="material-symbols-outlined">finance</span> &nbsp;&nbsp;<span>사용자 통계</span></div>';
                break;
        }


    });


    // body_main_el.classList.replace('col-10','col-9');
    main_menu_list_el.classList.replace('col-1', 'col-2'); // col-1을 col-2로 변경
});

// 왼쪽 메뉴바 마우스 호버 이벤트
main_menu_list_el.addEventListener('mouseleave', () => {
    c_menu_lists_el.forEach((item, index) => {
        switch (index) {

            case 0:
                item.innerHTML = '';
                item.innerHTML = '<span class="material-symbols-outlined">home</span>';
                break;
            case 1:
                item.innerHTML = '';
                item.innerHTML = '<span class="material-symbols-outlined">edit_note</span>';
                break;
            case 2:
                item.innerHTML = '';
                item.innerHTML = '<span class="material-symbols-outlined">sports_score</span>';
                break;
            case 3:
                item.innerHTML = '';
                item.innerHTML = '<span class="material-symbols-outlined">finance</span>';
                break;
        }


    });
    main_menu_list_el.classList.replace('col-2', 'col-1'); // col-2를 col-1로 복원
});


// 문제 추가 컨트롤
const sc_form_el = document.querySelector('#sc-form');
const sc_form_labels_el = sc_form_el.querySelectorAll('label');
const form_els = [];
const form_labels = [];
const sc_p_v_el = document.getElementById('sc-p-v');
const sc_d_v_el = document.getElementById('sc-d-v');

[...sc_form_el.elements].forEach(item => {
    form_els.push(item);
})

sc_form_labels_el.forEach(item => {
    form_labels.push(item);
})

// 0, 웹확인
// 1, 제목
// 2, ssh 링크
// 3, web 링크
// 4, 키 값
// 5, 점수
// 6, 난이도
// 7, 설명
// 8, 힌트
// 9, 버튼

// 문제 추가 SSH/WEB 전환 컨트롤
const setChallengeForm = () => {

    if (form_els[0].checked == true) {
        form_els[3].style.display = 'block';
        form_labels[3].style.display = 'block';

        form_els[2].style.display = 'none';
        form_labels[2].style.display = 'none';

        form_els[7].style.display = 'none';
        form_labels[7].style.display = 'none';

        form_els[8].style.display = 'none';
        form_labels[8].style.display = 'none';


    } else {
        form_els[3].style.display = 'none';
        form_labels[3].style.display = 'none';

        form_els[2].style.display = 'block';
        form_labels[2].style.display = 'block';

        form_els[7].style.display = 'block';
        form_labels[7].style.display = 'block';

        form_els[8].style.display = 'block';
        form_labels[8].style.display = 'block';

    }

}

// 문제 추가 난이도바 업데이트
const getpointview = () => {

    sc_p_v_el.textContent = form_els[5].value;

    if (form_els[6].value == 1) {

        sc_d_v_el.textContent = "Normal";
        sc_d_v_el.style.color = "black";

    } else if (form_els[6].value == 2) {

        sc_d_v_el.textContent = "Hard";
        sc_d_v_el.style.color = "red";

    } else {

        sc_d_v_el.textContent = "Easy";
        sc_d_v_el.style.color = "green";

    }

}



// 문제 추가 SSH/WEB 전환 컨트롤
form_els[0].addEventListener('click', () => {

    set_sc_form();

});

// 문제 추가 포인트 업데이트
form_els[5].addEventListener('input', () => {

    getpointview();

})

// 문제 추가 난이도 업데이트
form_els[6].addEventListener('input', () => {

    getpointview();

})



// 페이지 무빙 제어 함수
function initFullPageScroll(containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;

    const sections = container.querySelectorAll('.section');
    let currentSectionIndex = 0;
    let isScrolling = false; // 빠른 스크롤 방지

    container.addEventListener('wheel', (event) => {
        if (isScrolling) return; // 스크롤이 이미 진행 중이면 무시

        isScrolling = true; // 스크롤 시작

        // 휠 내리기
        if (event.deltaY > 0) {
            if (currentSectionIndex < sections.length - 1) {
                currentSectionIndex++;
            }
        } else {
            // 휠 올리기
            if (currentSectionIndex > 0) {
                currentSectionIndex--;
            }
        }

        // 섹션으로 부드럽게 이동
        sections[currentSectionIndex].scrollIntoView({
            behavior: 'smooth',
            block: 'start',
        });

        // 스크롤 완료 후, 스크롤 허용
        setTimeout(() => {
            isScrolling = false;
        }, 1000); // 1초 간격으로 스크롤을 제어 (부드럽게 이동하기 위함)

        event.preventDefault(); // 기본 스크롤 방지
    });
}

// 유저 데이터 생성
document.getElementById('list-userdata-list').addEventListener('click', () => {

    fetch('/php/getUCCountData.php') // PHP 파일 경로로 변경
        .then(response => response.json())
        .then(data => {
            // console.log(data);
            // 추가적인 데이터 처리...

            // 초기화
            const lu_fullPage_1_el = document.getElementById('lu-fullPage-1');
            lu_fullPage_1_el.innerHTML = '';

            const lu_fullPage_2_el = document.getElementById('lu-fullPage-2');
            lu_fullPage_2_el.innerHTML = '';


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

            // 진행도 바를 생성
            const totalProgress = createProgressBar('진행도', data.difficulty[0] + data.difficulty[1] + data.difficulty[2], data.Ccount);


            // 메인 차트 컨테이너
            const c_div_el = document.createElement('div');
            c_div_el.className = "d-flex justify-content-center gap-4"; // gap-4 추가
            c_div_el.style.height = "520px";



            // 각 차트를 위한 개별 컨테이너
            const d_chart_container = document.createElement('div');
            d_chart_container.className = "d_chart-container";
            d_chart_container.style.width = "420px"; // 컨테이너 크기 지정
            d_chart_container.style.height = "420px"; // 컨테이너 크기 지정
            d_chart_container.style.margin = "0 auto";

            const p_chart_container = document.createElement('div');
            p_chart_container.className = "p_chart-container";
            p_chart_container.style.width = "420px"; // 컨테이너 크기 지정
            p_chart_container.style.height = "420px"; // 컨테이너 크기 지정
            p_chart_container.style.margin = "0 auto";
            // 난이도 차트 생성
            const d_chart_el = document.createElement('canvas');
            d_chart_el.id = 'd_chart'; // ID 부여
            // 포인트 차트 생성
            const p_chart_el = document.createElement('canvas');
            p_chart_el.id = 'p_chart'; // ID 부여

            const d_chart_label_el = document.createElement('label');
            d_chart_label_el.className = "form-label";
            d_chart_label_el.setAttribute('for', 'd_chart');
            d_chart_label_el.style.fontSize = "1.2rem";
            d_chart_label_el.textContent = "난이도 분포";

            const p_chart_label_el = document.createElement('label');
            p_chart_label_el.className = "form-label";
            p_chart_label_el.setAttribute('for', 'p_chart');
            p_chart_label_el.style.fontSize = "1.2rem";
            p_chart_label_el.textContent = "포인트 분포";

            const none_count = data.Ccount - (data.difficulty[0] + data.difficulty[1] + data.difficulty[2]);
            const d_chart_data = {
                labels: ['none', 'Easy', 'Normal', 'Hard'],
                datasets: [{
                    label: '클리어 갯수 ',
                    data: [none_count, data.difficulty[0], data.difficulty[1], data.difficulty[2]],
                    backgroundColor: [
                        'rgba(128, 128, 128, 0.5)', // none - 회색 계열
                        'rgba(0, 255, 157, 0.5)', // Easy - 네온 그린
                        'rgba(0, 195, 255, 0.5)', // Normal - 네온 블루 (#00c3ff)
                        'rgba(255, 62, 62, 0.5)' // Hard - 네온 레드
                    ],
                    borderColor: [ // 테두리 색상 추가
                        'rgba(128, 128, 128, 0.8)', // none
                        'rgba(0, 255, 157, 0.8)', // Easy
                        'rgba(0, 195, 255, 0.8)', // Normal
                        'rgba(255, 62, 62, 0.8)' // Hard
                    ],
                    borderWidth: 2, // 테두리 두께,
                    hoverOffset: 5
                }]
            };

            const d_config = {
                type: 'doughnut',
                data: d_chart_data,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        shadow: {
                            blur: 10,
                            color: function (context) {
                                return context.dataset.backgroundColor[context.dataIndex];
                            }
                        },
                        legend: {
                            position: 'bottom', // 라벨 위치를 아래로 설정
                            align: 'center', // 라벨 정렬
                            labels: {
                                padding: 20, // 라벨 간격
                                color: '#fff', // 라벨 색상 (사이트 테마에 맞게 조정)
                                font: {
                                    size: 22
                                }
                            }

                        }
                    }

                }
            };

            const p_chart_data = {
                labels: ['남은 포인트 : ' + (data.Tpoint - data.point), '획득 포인트 : ' + data.point],
                datasets: [{
                    label: 'Point',
                    data: [data.Tpoint - data.point, data.point],
                    backgroundColor: [
                        'rgba(128, 128, 128, 0.5)', // 남은 포인트 - 회색 계열
                        'rgba(0, 195, 255, 0.5)', // 획득 포인트 - 네온 블루 (#00c3ff)
                    ],
                    borderColor: [ // 테두리 색상 추가
                        'rgba(128, 128, 128, 0.8)', // 남은 포인트
                        'rgba(0, 195, 255, 0.8)', // 획득 포인트
                    ],
                    borderWidth: 2, // 테두리 두께,
                    hoverOffset: 2
                }]
            };

            const p_config = {
                type: 'doughnut',
                data: p_chart_data,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        shadow: {
                            blur: 10,
                            color: function (context) {
                                return context.dataset.backgroundColor[context.dataIndex];
                            }
                        },
                        legend: {
                            position: 'bottom', // 라벨 위치를 아래로 설정
                            align: 'center', // 라벨 정렬
                            labels: {
                                padding: 20, // 라벨 간격
                                color: '#fff', // 라벨 색상 (사이트 테마에 맞게 조정)
                                font: {
                                    size: 22
                                }
                            }
                        }
                    }
                }
            };

            // DOM에 canvas 추가 후 차트 생성
            d_chart_container.appendChild(d_chart_label_el);
            d_chart_container.appendChild(d_chart_el);
            p_chart_container.appendChild(p_chart_label_el);
            p_chart_container.appendChild(p_chart_el);

            c_div_el.appendChild(d_chart_container);
            c_div_el.appendChild(p_chart_container);
            lu_fullPage_1_el.appendChild(c_div_el);

            // 총 진행도 구분선
            const h_div_el = createLineBar('총 진행도');

            // DOM에 추가
            lu_fullPage_1_el.appendChild(h_div_el.labelEl);
            lu_fullPage_1_el.appendChild(h_div_el.lineBarEl);
            lu_fullPage_1_el.appendChild(totalProgress.labelEl);
            lu_fullPage_1_el.appendChild(totalProgress.progressEl);




            // 차트 생성
            const d_chart = new Chart(d_chart_el, d_config);
            const p_chart = new Chart(p_chart_el, p_config);

            // 스크롤 효과 적용
            initFullPageScroll('lu-fullPage');

            document.addEventListener('wheel', (event) => {

                if (event.deltaY > 0) {
                    lu_fullPage_2_el.innerHTML = '';
                    const easyProgress = createProgressBar('Easy', data.difficulty[0], data.Ecount);
                    const normalProgress = createProgressBar('Normal', data.difficulty[1], data.Ncount);
                    const hardProgress = createProgressBar('Hard', data.difficulty[2], data.Hcount);
                    const pointProgress = createProgressBar('총 획득 포인트', data.point, data.Tpoint);

                    // 난이도 별 세부 정보 구분선
                    const d_line_el = createLineBar('난이도 별 세부 정보');
                    // 획득 포인트 정보 구분선
                    const p_line_el = createLineBar('획득 포인트 정보');

                    lu_fullPage_2_el.appendChild(d_line_el.labelEl);
                    lu_fullPage_2_el.appendChild(d_line_el.lineBarEl);
                    lu_fullPage_2_el.appendChild(easyProgress.labelEl);
                    lu_fullPage_2_el.appendChild(easyProgress.progressEl);
                    lu_fullPage_2_el.appendChild(normalProgress.labelEl);
                    lu_fullPage_2_el.appendChild(normalProgress.progressEl);
                    lu_fullPage_2_el.appendChild(hardProgress.labelEl);
                    lu_fullPage_2_el.appendChild(hardProgress.progressEl);

                    lu_fullPage_2_el.appendChild(p_line_el.labelEl);
                    lu_fullPage_2_el.appendChild(p_line_el.lineBarEl);
                    lu_fullPage_2_el.appendChild(pointProgress.labelEl);
                    lu_fullPage_2_el.appendChild(pointProgress.progressEl);

                    // 세부 베치 조절
                    hardProgress.progressEl.classList.add('mb-5');
                    pointProgress.progressEl.classList.add('mb-5');
                }

            });

            // 세부 배치 조절
            totalProgress.progressEl.classList.add('mb-5');




        })
        .catch(error => console.error('Error:', error));


})

// 채팅 서버 연결 및 구현 로직-----------------------------------------
const chat_send_btn_el = document.getElementById('chat-send-btn');
const chat_input_el = document.getElementById('chat-input');
const chat_messages_el = document.getElementById('chat-messages');

// WebSocket 연결 상태 관리 용 변수
let socket = null;
let isConnected = false;

// WebSocket 연결 로직
function connectWebSocket() {
    try {
        // WebSocket 연결 대상 서버
        socket = new WebSocket('ws://192.168.1.133:30000');

        // WebSocket 연결 성공 로직
        socket.onopen = function () {
            // console.log('WebSocket 연결 성공!');
            isConnected = true;

            // 연결 된후 입장 알림을 위해 유저 닉네임 정보를 전송
            const messageData = JSON.stringify({
                type: 'user_connect',
                point: n_point_el.textContent.split(' ')[2], // 현재 포인트
                nickname: nickname// PHP 세션의 닉네임
            });
            socket.send(messageData);

            // 연결 성공 메시지 를 채팅창에 표시
            const message = document.createElement('div');
            message.textContent = '채팅 서버에 연결되었습니다.';
            message.className = 'system-message';
            chat_messages_el.appendChild(message);
        };

        // WebSocket 연결 종료 로직
        socket.onclose = function () {
            // console.log('WebSocket 연결이 종료되었습니다.');
            isConnected = false;

            // 3초 후 재연결 시도
            setTimeout(connectWebSocket, 3000);
        };

        // WebSocket 에러 로직
        socket.onerror = function (error) {
            console.error('WebSocket 에러:', error);
            isConnected = false;
        };

        // WebSocket 메시지 수신 로직
        socket.onmessage = function (event) {
            // 디버깅 용 콘솔 메시지
            // console.log('메시지 수신:', event.data);
            const data = JSON.parse(event.data);

            function createChatMessage(data) {
                const chat_output_box = document.createElement('div');
                chat_output_box.classList.add('chat-output-box');

                if (data.type === 'systemmsg') {
                    // 시스템 메시지 (입장/퇴장)
                    const systemMsg_nickname = document.createElement('div');
                    systemMsg_nickname.classList.add('system-message-nickname');
                    systemMsg_nickname.style.color = data.color;
                    systemMsg_nickname.textContent = data.nickname;

                    const systemMsg = document.createElement('div');
                    systemMsg.textContent = data.message;
                    systemMsg.className = 'system-message';
                    chat_output_box.appendChild(systemMsg_nickname);
                    chat_output_box.appendChild(systemMsg);
                } else {
                    // 일반 채팅 메시지
                    const chat_user_box = document.createElement('div');
                    chat_user_box.classList.add('chat-user-box');

                    const chat_nickname = document.createElement('div');
                    chat_nickname.classList.add('chat-nickname');
                    chat_nickname.style.color = data.color;
                    chat_nickname.textContent = data.nickname;

                    const chat_point = document.createElement('div');
                    chat_point.classList.add('chat-point');
                    chat_point.textContent = `(pts${data.point})`;

                    const chat_msg = document.createElement('div');
                    chat_msg.textContent = ' : ' + data.message;
                    chat_msg.className = 'chat-message';

                    chat_user_box.appendChild(chat_nickname);
                    chat_user_box.appendChild(chat_point);
                    chat_output_box.appendChild(chat_user_box);
                    chat_output_box.appendChild(chat_msg);
                }

                return chat_output_box;
            }

            // 메시지 표시 함수
            function displayMessage(data) {
                const messageElement = createChatMessage(data);
                chat_messages_el.appendChild(messageElement);
                chat_messages_el.scrollTop = chat_messages_el.scrollHeight;
            }


            if (data.type === 'color') {
                // 서버에서 할당받은 색상 저장
                myColor = data.color;
                // console.log('할당받은 색상:', myColor);
                return;
            }

            displayMessage(data);

            if (data.type === 'user_connect') {
                // 유저 입장 알림 표시

                return;
            }

        };

    } catch (error) {
        console.error('WebSocket 연결 중 에러:', error);
        // 3초 후 재연결 시도
        setTimeout(connectWebSocket, 3000);
    }
}



// 메시지 전송 함수
function sendMessage() {
    if (!isConnected) {
        // console.log('WebSocket이 연결되어 있지 않습니다.');
        return;
    }

    const message = chat_input_el.value.trim();
    if (message) {
        try {
            // 메시지 및 유저 데이터를 채팅 서버로 전송
            const messageData = JSON.stringify({
                message: message,
                nickname: nickname, // PHP 세션의 닉네임
                point: n_point_el.textContent.split(' ')[2] // 현재 포인트
            });
            socket.send(messageData);
            // console.log('메시지 전송:', messageData);
            chat_input_el.value = '';
        } catch (error) {
            console.error('메시지 전송 중 에러:', error);
        }
    }
}

// 이벤트 리스너
chat_send_btn_el.addEventListener('click', sendMessage);
chat_input_el.addEventListener('keydown', (event) => {
    if (event.key === 'Enter') {
        event.preventDefault();
        sendMessage();
    }
});

// ----------------------------------------------------------------------



init();