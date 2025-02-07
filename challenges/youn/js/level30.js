document.getElementById('send-btn').addEventListener('click', () => {
    const coupon = document.getElementById('coupon').value;
    const currentDate = new Date();
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth() + 1;
    const day = currentDate.getDate();
    const formattedDate = `${year}-${month}-${day}`;

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/youn/php/level30.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {

        if (xhr.status === 200) {

            let response = xhr.responseText;
            let json = JSON.parse(response);
            showAlert(json.title, json.message);


        }

    }; xhr.send('coupon=' + encodeURIComponent(coupon) + '&date=' + encodeURIComponent(formattedDate));




});

document.getElementById('coupon').addEventListener('keydown', (event) => {
    if (event.key === 'Enter') {
        event.preventDefault();
        document.getElementById('send-btn').click();
    }
});



function showAlert(title, message) {
    const modal = new bootstrap.Modal(document.getElementById('alert-modal'));
    document.getElementById('alert-modal-title').textContent = title;
    document.getElementById('alert-modal-body').textContent = message;
    modal.show();
}