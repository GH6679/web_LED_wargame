let clickCount = 0;
const clickCountElement = document.getElementById('click-count');
document.getElementById('key-btn').addEventListener('click', function (e) {
    // 클릭 이벤트가 부모 요소로 전파되지 않도록.
    e.stopPropagation();
    clickCount++;
    clickCountElement.textContent = clickCount;
    if (clickCount === 3) {
        
        let key = "";

        var xhr = new XMLHttpRequest();
        xhr.open('POST','/youn/php/level28.php',true);
        xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        
        xhr.onload = function(){

            if(xhr.status === 200){

                let response = xhr.responseText;
                let data = JSON.parse(response);
                key = data.key;
                showAlert("성공", "key : " + key);


            }

        }; xhr.send('clickCount=' + clickCount);



    }else{
        
    }
});

document.getElementById('body').addEventListener('click', function () {
    clickCount = 0;
    clickCountElement.textContent = clickCount;
});

document.getElementById('key-btn').addEventListener('mouseenter', function () {
    const button = this;
    const windowWidth = window.innerWidth;
    const windowHeight = window.innerHeight;

    button.style.position = 'absolute';

    // 랜덤한 위치로 이동시키기 (버튼이 화면 내에서 벗어나지 않도록 제한)
    const randomX = Math.random() * (windowWidth - button.offsetWidth);
    const randomY = Math.random() * (windowHeight - button.offsetHeight);

    button.style.left = `${randomX}px`;
    button.style.top = `${randomY}px`;
});

function showAlert(title, message) {
    const modal = new bootstrap.Modal(document.getElementById('alert-modal'));
    document.getElementById('alert-modal-title').textContent = title;
    document.getElementById('alert-modal-body').textContent = message;
    modal.show();
}