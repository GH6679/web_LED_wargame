<?php
session_start();

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['completed']) && $data['completed'] === true) {
    $_SESSION['puzzle_solved'] = true;
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>
