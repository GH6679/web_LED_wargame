<?php

// alert 모달 호출기 call_alert_modal
// 인덱스 = 0, 호출 여부 1, 제목 2, 내용
if ($call_alert_modal[0]) {

    echo "<script>a_modal_el = new bootstrap.Modal(document.getElementById('alert-modal'));
        document.getElementById('alert-modal-title').textContent = " . json_encode($call_alert_modal[1]) . ";
        document.getElementById('alert-modal-body').textContent = " . json_encode($call_alert_modal[2]) . ";
        document.getElementById('alert-modal-end').addEventListener('click',()=>{var mm = " . json_encode($call_alert_modal[3]) . ";if(mm)location.reload();});
        a_modal_el.show();</script>";
}

// if($_SESSION['signin_success'] = true){

//     echo "<script>signin_success = new bootstrap.Modal(document.getElementById('signinsuccessMidal'));signin_success.show();</script>";
//     $_SESSION['signin_success'] = false;

// }





?>