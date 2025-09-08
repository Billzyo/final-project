<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm rounded-lg px-4 py-2">
  <div class="container-fluid">
    <!-- Navbar Brand with Logo -->
    <a class="navbar-brand d-flex align-items-center" href="index.php?pg=home">
      <img src="assets/images/poslogo.png" alt="Company Logo" width="110" height="70" class="d-inline-block align-text-top me-3">
      <span class="fw-bold text-dark"><?= esc(str: APP_NAME) ?></span>
    </a>

    <!-- Navbar Toggler -->
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Navbar Content -->
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto mb-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="index.php?pg=home">Point of Sale</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="receipt_debug.php">Receipts</a>
        </li>

        <?php if (Auth::access('supervisor')) : ?>
          <li class="nav-item">
            <a class="nav-link" href="index.php?pg=admin">Admin</a>
          </li>
        <?php endif; ?>

        <?php if (Auth::access('admin')) : ?>
          <li class="nav-item">
            <a class="nav-link" href="index.php?pg=signup">Create User</a>
          </li>
        <?php endif; ?>

        <?php if (!Auth::logged_in()) : ?>
          <li class="nav-item">
            <a class="nav-link" href="index.php?pg=login">Login</a>
          </li>
        <?php else : ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Hi, <?= auth('username') ?> (<?= Auth::get('role') ?>)
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
              <li><a class="dropdown-item" href="index.php?pg=profile">Profile</a></li>
              <li><a class="dropdown-item" href="index.php?pg=edit-user&id=<?= Auth::get('id') ?>">Profile Settings</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="index.php?pg=logout">Logout</a></li>
            </ul>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
