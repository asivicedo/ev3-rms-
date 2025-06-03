<?php
// -----------------------------------------------------------------
// includes/navbar.php
// -----------------------------------------------------------------
// Bootstrap‐styled navigation bar for all protected pages.
// Shows links according to user role and displays the logged‐in username.
//
// Usage (at top of any protected page, after including auth.php & config.php):
//   include 'includes/navbar.php';
// -----------------------------------------------------------------
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
  <div class="container-fluid">
    <a class="navbar-brand" href="dashboard.php">LEGO RMS</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarContent"
            aria-controls="navbarContent" aria-expanded="false"
            aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <!-- Dashboard (all roles) -->
        <li class="nav-item">
          <a class="nav-link" href="dashboard.php">Dashboard</a>
        </li>

        <!-- Parts List (all roles) -->
        <li class="nav-item">
          <a class="nav-link" href="index.php">Parts</a>
        </li>

        <!-- Add Part (all roles) -->
        <li class="nav-item">
          <a class="nav-link" href="#">+ Add Part</a>
        </li>

        <!-- Export CSV (all roles) -->
        <li class="nav-item">
          <a class="nav-link" href="export.php">Export CSV</a>
        </li>

        <!-- Import CSV (all roles) -->
        <li class="nav-item">
          <a class="nav-link" href="#">Import CSV</a>
        </li>

        <!-- Logs (admin only) -->
        <?php if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin'): ?>
          <li class="nav-item">
            <a class="nav-link" href="logs.php">Action Logs</a>
          </li>
        <?php endif; ?>
      </ul>

      <!-- Right side: show logged‐in username and Logout -->
      <span class="navbar-text me-3">
        Logged in as: <strong><?= h($_SESSION['user']['username']) ?></strong>
      </span>
      <a class="btn btn-outline-secondary" href="logout.php">Logout</a>
    </div>
  </div>
</nav>