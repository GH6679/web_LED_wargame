// 비동기 처리 메서드
const fetchData = async (url, method = 'GET', body = null) => {
    const options = {
        method: method,
        ...(method === 'POST' && {
            headers: {
                'Content-Type': 'application/json'
            },
            body: body ? JSON.stringify(body) : null
        })
    };

    const response = await fetch(url, options);
    
    // 응답 상태 코드 확인
    if (!response.ok) {
        throw new Error('Network response was not ok: ' + response.statusText);
        // console.error(JSON.stringify(response.error()));
    }

    // JSON 응답 처리
    const data = await response.json();
    return data;
}

// bs alert 모달 생성기 (제목, 내용, 화면 새로고침 여부)
// const call_alert_modal = (title, body, end) => {


//     a_modal_el = new bootstrap.Modal(document.getElementById('alert-modal'));
//     document.getElementById('alert-modal-title').textContent = title;
//     document.getElementById('alert-modal-body').textContent = body;
//     document.getElementById('alert-modal-end').addEventListener('click', () => {
//         var mm = end;
//         if (mm) location.reload();
//     });
//     a_modal_el.show();
// }