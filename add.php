<?php
require 'includes/auth.php';
require 'config.php';
require 'includes/functions.php';
require 'includes/header.php';
include 'includes/navbar.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['part_name'] ?? '');
    $type = trim($_POST['part_type'] ?? '');
    $qty  = intval($_POST['quantity'] ?? -1);

    if ($name === '' || $qty < 0) {
        $error = 'Please enter a valid part name and a non-negative quantity.';
    } else {
        $stmt = $conn->prepare("
            INSERT INTO robot_parts (part_name, part_type, quantity)
            VALUES (?, ?, ?)
        ");
        $stmt->bind_param("ssi", $name, $type, $qty);
        $stmt->execute();
        $newPartId = $stmt->insert_id;
        $stmt->close();

        log_action($conn, 'Added part', $newPartId);

        header("Location: index.php");
        exit();
    }
}
?>

<div class="container mt-4">
  <h1 class="mb-4">Add New Part</h1>

  <?php if ($error): ?>
    <div class="alert alert-danger"><?= h($error) ?></div>
  <?php endif; ?>

  <div class="form-container">
    <form method="post">
      <div class="form-group mb-3">
        <label for="part_name" class="form-label">Part Name</label>
        <input
          type="text"
          name="part_name"
          id="part_name"
          class="form-control"
          required
          autofocus
        >
      </div>

      <div class="form-group mb-3">
        <label for="part_type" class="form-label">Part Type</label>
        <input
          type="text"
          name="part_type"
          id="part_type"
          class="form-control"
        >
        <div class="form-text">E.g., Motor, Sensor, Beam, Wheel, etc.</div>
      </div>

      <div class="form-group mb-3">
        <label for="quantity" class="form-label">Quantity</label>
        <input
          type="number"
          name="quantity"
          id="quantity"
          class="form-control"
          min="0"
          required
        >
      </div>

      <div class="btn-group">
        <button type="submit" class="btn btn-danger me-2">Add Part</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
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