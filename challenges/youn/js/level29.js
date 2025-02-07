const sendBtn = document.getElementById('send-btn');

sendBtn.addEventListener('click', () => {

    if (document.getElementById('hiddenvalue').value !== 'YES') {
        let senddata = document.getElementById('hiddenvalue').value;
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/youn/php/level29.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function () {

            if (xhr.status === 200) {

                let response = xhr.responseText;

                let json = JSON.parse(response);
                showAlert(json.title, json.message);
            }

        }; xhr.send('senddata=' + encodeURIComponent(senddata));

    } else {
        showAlert("실패", "NO");
    }



});

function showAlert(title, message) {
    const modal = new bootstrap.Modal(document.getElementById('alert-modal'));
    document.getElementById('alert-modal-title').textContent = title;
    document.getElementById('alert-modal-body').textContent = message;
    modal.show();
}

