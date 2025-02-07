document.getElementById('login-form').addEventListener('submit', function (e) {
    e.preventDefault();

    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    if (username === "admin" && password === "password123") {
        

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/youn/php/level27.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function () {

            if (xhr.status === 200) {
                let key = "";
                let response = xhr.responseText;
                let data = JSON.parse(response);
                key = data.key;
                showAlert("성공", "key : " + key);

            }

        }; xhr.send('username=' + encodeURIComponent(username) + '&password=' + encodeURIComponent(password));
    } else {
        showAlert("실패", "아이디나 비밀번호가 틀렸습니다.");
    }
});

function showAlert(title, message) {
    const modal = new bootstrap.Modal(document.getElementById('alert-modal'));
    document.getElementById('alert-modal-title').textContent = title;
    document.getElementById('alert-modal-body').textContent = message;
    modal.show();
}