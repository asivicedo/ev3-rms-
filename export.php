<?php
// -----------------------------------------------------------------
// export.php
// -----------------------------------------------------------------
// Protected page: streams a CSV containing all robot_parts.
// Sets appropriate headers, then outputs rows from the DB.
// -----------------------------------------------------------------

require 'includes/auth.php'; // ensure user is logged in
require 'config.php';       // DB connection

// 1) Send CSV download headers
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="parts_export_' . date('Ymd_His') . '.csv"');

// 2) Open output stream
$output = fopen('php://output', 'w');

// 3) Write CSV column headers
fputcsv($output, ['ID', 'Part Name', 'Part Type', 'Quantity']);

// 4) Fetch all parts and write each row
$result = $conn->query("SELECT id, part_name, part_type, quantity FROM robot_parts ORDER BY id ASC");
while ($row = $result->fetch_assoc()) {
    fputcsv($output, [
        $row['id'],
        $row['part_name'],
        $row['part_type'],
        $row['quantity']
    ]);
}

fclose($output);
exit();
?>
