<?php
session_start();

// 세션 정보를 반환
echo json_encode($_SESSION);