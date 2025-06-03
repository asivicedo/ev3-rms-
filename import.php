<?php
require 'includes/auth.php';
require 'config.php';
require 'includes/functions.php';
require 'includes/header.php';
include 'includes/navbar.php';

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        !isset($_FILES['csv_file']) ||
        $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK
    ) {
        $error = "Please select a valid CSV file to upload.";
    } else {
        $tmpPath = $_FILES['csv_file']['tmp_name'];
        $handle  = fopen($tmpPath, 'r');

        if ($handle === false) {
            $error = "Unable to open the uploaded file.";
        } else {
            fgetcsv($handle);

            $rowCount = 0;
            while (($data = fgetcsv($handle)) !== false) {
                $name = trim($data[0] ?? '');
                $type = trim($data[1] ?? '');
                $qty  = intval($data[2] ?? -1);

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
            $success = "Successfully imported {$rowCount} rows.";
        }
    }
}
?>

<div class="container mt-4">
  <h1 class="mb-4">Import Parts from CSV</h1>

  <?php if ($error): ?>
    <div class="alert alert-danger"><?= h($error) ?></div>
  <?php elseif ($success): ?>
    <div class="alert alert-success"><?= h($success) ?></div>
  <?php endif; ?>

  <div class="form-container">
    <form method="post" enctype="multipart/form-data">
      <div class="form-group mb-3">
        <label for="csv_file" class="form-label">Choose CSV File</label>
        <input
          type="file"
          name="csv_file"
          id="csv_file"
          class="form-control"
          accept=".csv"
          required
        >
        <div class="form-text">
          CSV columns must be, in order: Part Name, Part Type, Quantity.
        </div>
      </div>

      <div class="btn-group">
        <button type="submit" class="btn btn-danger me-2">Upload & Import</button>
        <a href="index.php" class="btn btn-secondary">Back to Parts</a>
      </div>
    </form>
  </div>
</div>

<style>
body {
  background-color: #f8f9fa;
  color: #212529;
}

h1 {
  color: #212529;
  font-weight: 700;
  border-bottom: 3px solid #dc3545;
  padding-bottom: 10px;
}

.form-container {
  background: white;
  padding: 30px;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  max-width: 600px;
}

.form-label {
  color: #212529;
  font-weight: 600;
  margin-bottom: 8px;
}

.form-control {
  border: 2px solid #dee2e6;
  padding: 12px;
  transition: all 0.3s ease;
}

.form-control:focus {
  border-color: #dc3545;
  box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}

.form-text {
  color: #6c757d;
  font-size: 0.875rem;
}

.alert-danger {
  background-color: #f8d7da;
  border-color: #f5c6cb;
  color: #721c24;
  border: 1px solid;
  border-radius: 6px;
  padding: 12px;
}

.alert-success {
  background-color: #d4edda;
  border-color: #c3e6cb;
  color: #155724;
  border: 1px solid;
  border-radius: 6px;
  padding: 12px;
}

.btn-danger {
  background-color: #dc3545;
  border-color: #dc3545;
  color: white;
  padding: 10px 20px;
  font-weight: 600;
}

.btn-danger:hover {
  background-color: #c82333;
  border-color: #bd2130;
}

.btn-secondary {
  background-color: #6c757d;
  border-color: #6c757d;
  color: white;
  padding: 10px 20px;
  font-weight: 600;
}

.btn-secondary:hover {
  background-color: #545b62;
  border-color: #4e555b;
}
</style>

</body>
</html>