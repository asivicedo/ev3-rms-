<?php
require 'config.php';
require 'includes/functions.php';

if (isset($_SESSION['user'])) {
    header("Location: dashboard.php");
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Please enter both username and password.';
    } else {
        $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user'] = [
                    'id'       => $user['id'],
                    'username' => $user['username'],
                    'role'     => $user['role']
                ];
                $stmt->close();
                header("Location: dashboard.php");
                exit();
            }
        }

        $error = 'Invalid username or password.';
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login — LEGO RMS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #495057, #212529);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .login-container {
      background: white;
      border-radius: 12px;
      box-shadow: 0 8px 32px rgba(0,0,0,0.3);
      padding: 40px;
      width: 100%;
      max-width: 420px;
    }
    .login-header {
      text-align: center;
      margin-bottom: 30px;
    }
    .login-header h2 {
      color: #212529;
      font-weight: 700;
      margin-bottom: 10px;
    }
    .login-header::after {
      content: '';
      display: block;
      width: 60px;
      height: 3px;
      background-color: #dc3545;
      margin: 15px auto;
    }
    .form-label {
      color: #495057;
      font-weight: 600;
    }
    .form-control {
      border: 2px solid #e9ecef;
      border-radius: 8px;
      padding: 12px 15px;
      transition: all 0.3s;
    }
    .form-control:focus {
      border-color: #6c757d;
      box-shadow: 0 0 0 0.2rem rgba(108, 117, 125, 0.25);
    }
    .btn-primary {
      background: linear-gradient(135deg, #6c757d, #495057);
      border: none;
      border-radius: 8px;
      padding: 12px;
      font-weight: 600;
      transition: all 0.3s;
    }
    .btn-primary:hover {
      background: linear-gradient(135deg, #495057, #212529);
      transform: translateY(-1px);
    }
    .alert-danger {
      background-color: #f8d7da;
      border-color: #dc3545;
      color: #721c24;
      border-radius: 8px;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="login-header">
      <h2>LEGO RMS Login</h2>
    </div>

    <?php if ($error): ?>
      <div class="alert alert-danger"><?= h($error) ?></div>
    <?php endif; ?>

    <form method="post" novalidate>
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input
          type="text"
          name="username"
          id="username"
          class="form-control"
          required
          autofocus
        >
      </div>

      <div class="mb-4">
        <label for="password" class="form-label">Password</label>
        <input
          type="password"
          name="password"
          id="password"
          class="form-control"
          required
        >
      </div>

      <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
  </div>
</body>
</html>