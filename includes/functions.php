<?php
function log_action($conn, $action, $part_id) {
    if (!isset($_SESSION['user']['id'])) {
        return;
    }
    $user_id = (int) $_SESSION['user']['id'];

    $stmt = $conn->prepare("
        INSERT INTO logs (action, part_id, user_id)
        VALUES (?, ?, ?)
    ");
    $stmt->bind_param("sii", $action, $part_id, $user_id);
    $stmt->execute();
    $stmt->close();
}

function h($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
?>