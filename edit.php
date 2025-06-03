<?php
require 'includes/auth.php';
require 'config.php';
require 'includes/functions.php';

// Comment out or remove the navbar include to hide it
// include 'includes/navbar.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    header("Location: index.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM robot_parts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    $stmt->close();
    header("Location: index.php");
    exit();
}
$part = $result->fetch_assoc();
$stmt->close();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['part_name'] ?? '');
    $type = trim($_POST['part_type'] ?? '');
    $qty  = intval($_POST['quantity'] ?? -1);

    if ($name === '' || $qty < 0) {
        $error = 'Please enter a valid part name and a non-negative quantity.';
    } else {
        $stmt = $conn->prepare("
            UPDATE robot_parts
            SET part_name = ?, part_type = ?, quantity = ?
            WHERE id = ?
        ");
        $stmt->bind_param("ssii", $name, $type, $qty, $id);
        $stmt->execute();
        $stmt->close();

        log_action($conn, 'Edited part', $id);

        header("Location: index.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Part — LEGO RMS</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    body {
      font-family: 'Segoe UI', Arial, sans-serif;
      background: linear-gradient(135deg, #2c2c2c 0%, #1a1a1a 100%);
      color: #ffffff;
      min-height: 100vh;
      padding: 20px;
    }
    
    .container {
      max-width: 800px;
      margin: 0 auto;
      background: #333333;
      border-radius: 12px;
      padding: 30px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    }
    
    h1 {
      color: #dc3545;
      font-size: 2.5rem;
      margin-bottom: 30px;
      text-align: center;
      font-weight: 700;
    }
    
    .alert {
      background: #dc3545;
      color: #ffffff;
      padding: 15px;
      border-radius: 8px;
      margin-bottom: 25px;
      border: 2px solid #b02a37;
    }
    
    .form-group {
      margin-bottom: 25px;
    }
    
    label {
      display: block;
      color: #ffffff;
      font-weight: 600;
      margin-bottom: 8px;
      font-size: 1.1rem;
    }
    
    input[type="text"],
    input[type="number"] {
      width: 100%;
      padding: 15px;
      background: #4a4a4a;
      border: 2px solid #666666;
      border-radius: 8px;
      color: #ffffff;
      font-size: 1rem;
      transition: all 0.3s ease;
    }
    
    input[type="text"]:focus,
    input[type="number"]:focus {
      outline: none;
      border-color: #dc3545;
      background: #555555;
      box-shadow: 0 0 10px rgba(220, 53, 69, 0.3);
    }
    
    .form-text {
      color: #cccccc;
      font-size: 0.9rem;
      margin-top: 5px;
    }
    
    .btn-group {
      display: flex;
      gap: 15px;
      margin-top: 30px;
    }
    
    .btn {
      padding: 15px 30px;
      border: none;
      border-radius: 8px;
      font-size: 1.1rem;
      font-weight: 600;
      cursor: pointer;
      text-decoration: none;
      display: inline-block;
      text-align: center;
      transition: all 0.3s ease;
    }
    
    .btn-warning {
      background: #dc3545;
      color: #ffffff;
    }
    
    .btn-warning:hover {
      background: #c82333;
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
    }
    
    .btn-secondary {
      background: #6c757d;
      color: #ffffff;
    }
    
    .btn-secondary:hover {
      background: #545b62;
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(108, 117, 125, 0.4);
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Edit Part</h1>

    <?php if ($error): ?>
      <div class="alert"><?= h($error) ?></div>
    <?php endif; ?>

    <form method="post">
      <div class="form-group">
        <label for="part_name">Part Name</label>
        <input
          type="text"
          name="part_name"
          id="part_name"
          value="<?= h($part['part_name']) ?>"
          required
          autofocus
        >
      </div>

      <div class="form-group">
        <label for="part_type">Part Type</label>
        <input
          type="text"
          name="part_type"
          id="part_type"
          value="<?= h($part['part_type']) ?>"
        >
        <div class="form-text">E.g., Motor, Sensor, Beam, Wheel, etc.</div>
      </div>

      <div class="form-group">
        <label for="quantity">Quantity</label>
        <input
          type="number"
          name="quantity"
          id="quantity"
          min="0"
          value="<?= h($part['quantity']) ?>"
          required
        >
      </div>

      <div class="btn-group">
        <button type="submit" class="btn btn-warning">Update Part</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</body>
</html>