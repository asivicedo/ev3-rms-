<?php
require 'includes/auth.php';
require 'config.php';
require 'includes/functions.php';
include 'includes/navbar.php';

if ($_SESSION['user']['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}

$sql = "
    SELECT
      logs.id         AS log_id,
      logs.action,
      logs.created_at,
      users.username  AS user_name,
      robot_parts.part_name
    FROM logs
    LEFT JOIN users
      ON logs.user_id = users.id
    LEFT JOIN robot_parts
      ON logs.part_id = robot_parts.id
    ORDER BY logs.created_at DESC
";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Action Logs — LEGO RMS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
    .table {
      background-color: white;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      border-radius: 8px;
      overflow: hidden;
    }
    .table thead th {
      background-color: #212529;
      color: white;
      border: none;
      font-weight: 600;
      padding: 15px;
    }
    .table tbody tr {
      transition: all 0.2s;
    }
    .table tbody tr:hover {
      background-color: #f8f9fa;
      transform: scale(1.01);
    }
    .table tbody td {
      padding: 12px 15px;
      vertical-align: middle;
    }
    .log-action {
      font-weight: 600;
    }
    .log-action.add {
      color: #28a745;
    }
    .log-action.edit {
      color: #6c757d;
    }
    .log-action.delete {
      color: #dc3545;
    }
    .log-action.import {
      color: #495057;
    }
    .log-number {
      background-color: #6c757d;
      color: white;
      padding: 4px 8px;
      border-radius: 4px;
      font-weight: 600;
      font-size: 0.85rem;
    }
    .log-timestamp {
      font-family: monospace;
      font-size: 0.9rem;
      color: #495057;
    }
    .log-user {
      background-color: #e9ecef;
      padding: 4px 8px;
      border-radius: 4px;
      font-weight: 500;
    }
  </style>
</head>
<body class="container mt-4">
  <h1 class="mb-4">Action Logs</h1>

  <table class="table table-hover">
    <thead>
      <tr>
        <th>#</th>
        <th>Timestamp</th>
        <th>User</th>
        <th>Action</th>
        <th>Part Name</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result->num_rows === 0): ?>
        <tr>
          <td colspan="5" class="text-center text-muted">No log entries found.</td>
        </tr>
      <?php else: ?>
        <?php $i = 1; while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><span class="log-number"><?= h($i++) ?></span></td>
            <td class="log-timestamp"><?= h($row['created_at']) ?></td>
            <td><span class="log-user"><?= h($row['user_name'] ?? 'Unknown') ?></span></td>
            <td><span class="log-action <?= strtolower(h($row['action'])) ?>"><?= h($row['action']) ?></span></td>
            <td><?= h($row['part_name'] ?? '—') ?></td>
          </tr>
        <?php endwhile; ?>
      <?php endif; ?>
    </tbody>
  </table>
</body>
</html>