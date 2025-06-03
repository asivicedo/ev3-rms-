<?php
// -----------------------------------------------------------------
// delete.php
// -----------------------------------------------------------------
// Protected action: deletes the specified part by id, logs the
// action, then redirects back to index.php. Uses no form.
// -----------------------------------------------------------------

require 'includes/auth.php';       // ensure user is logged in
require 'config.php';             // DB connection
require 'includes/functions.php'; // log_action()

$id = intval($_GET['id'] ?? 0);
if ($id > 0) {
    // Optionally you can verify the part exists before deleting
    // but for simplicity, just issue DELETE:
    $stmt = $conn->prepare("DELETE FROM robot_parts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    // Log this action
    log_action($conn, 'Deleted part', $id);
}

header("Location: index.php");
exit();
?>
