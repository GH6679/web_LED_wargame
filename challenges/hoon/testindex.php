<?php
session_start();

if (!isset($_SESSION['puzzle_solved'])) {
    $_SESSION['puzzle_solved'] = false;
}

$image_path = '/hoon/image/1aa19f76b3422e5da34da9eb980e8ee2.jpg';

// 이미지 크기 확인
$image_size = getimagesize($_SERVER['DOCUMENT_ROOT'] . $image_path);
if ($image_size) {
    $width = $image_size[0];
    $height = $image_size[1];
    echo "<!-- 이미지 크기: {$width}x{$height} 픽셀 -->";
}
?>