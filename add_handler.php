<?php
require 'includes/auth.php';
require 'config.php';
require 'includes/functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode([
            'success' => false,
            'message' => 'Please select a valid CSV file to upload.'
        ]);
        exit;
    }

    $tmpPath = $_FILES['csv_file']['tmp_name'];
    $handle = fopen($tmpPath, 'r');

    if ($handle === false) {
        echo json_encode([
            'success' => false,
            'message' => 'Unable to open the uploaded file.'
        ]);
        exit;
    }

    try {
        fgetcsv($handle);
        $rowCount = 0;

        while (($data = fgetcsv($handle)) !== false) {
            $name = trim($data[0] ?? '');
            $type = trim($data[1] ?? '');
            $qty = intval($data[2] ?? -1);

            if ($name === '' || $qty < 0) {
                continue;
            }

            $stmt = $conn->prepare("
                INSERT INTO robot_parts (part_name, part_type, quantity)
                VALUES (?, ?, ?)
            ");
            $stmt->bind_param("ssi", $name, $type, $qty);
            $stmt->execute();
            $insertedId = $stmt->insert_id;
            $stmt->close();

            log_action($conn, 'Imported part via CSV', $insertedId);
            $rowCount++;
        }
        fclose($handle);

        echo json_encode([
            'success' => true,
            'message' => "Successfully imported {$rowCount} rows."
        ]);
    } catch (Exception $e) {
        fclose($handle);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to import CSV. Please check the file format.'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method.'
    ]);
}
?>