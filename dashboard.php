<?php
// -----------------------------------------------------------------
// dashboard.php
// -----------------------------------------------------------------
// Protected page: shows quick summary (total parts, low stock,
// and part‐type breakdown). Requires login (includes auth) 
// and shows the navbar. Uses Bootstrap for styling.
// -----------------------------------------------------------------

require 'includes/auth.php';
require 'config.php';
require 'includes/functions.php';
include  'includes/navbar.php';

// 1) Total number of parts
$totalParts = $conn
    ->query("SELECT COUNT(*) FROM robot_parts")
    ->fetch_row()[0];

// 2) Number of low‐stock parts (quantity < 3)
$lowStockParts = $conn
    ->query("SELECT COUNT(*) FROM robot_parts WHERE quantity < 3")
    ->fetch_row()[0];

// 3) Breakdown by part_type
$stmt = $conn->prepare("
    SELECT part_type, COUNT(*) AS count
    FROM robot_parts
    GROUP BY part_type
");
$stmt->execute();
$breakdownResult = $stmt->get_result();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard — LEGO RMS</title>
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    rel="stylesheet"
  >
</head>
<body class="container mt-4">
  <h1 class="mb-4">Dashboard</h1>

  <div class="row mb-4">
    <div class="col-md-4">
      <div class="card text-white bg-primary mb-3">
        <div class="card-body">
          <h5 class="card-title">Total Parts</h5>
          <p class="card-text display-6"><?= h($totalParts) ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card text-white bg-warning mb-3">
        <div class="card-body">
          <h5 class="card-title">Low Stock (&lt; 3)</h5>
          <p class="card-text display-6"><?= h($lowStockParts) ?></p>
        </div>
      </div>
    </div>
  </div>

  <h3>Breakdown by Category</h3>
  <table class="table table-bordered">
    <thead class="table-dark">
      <tr>
        <th>Part Type</th>
        <th>Count</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $breakdownResult->fetch_assoc()): ?>
        <tr>
          <td><?= h($row['part_type']) ?></td>
          <td><?= h($row['count']) ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</body>
</html>
