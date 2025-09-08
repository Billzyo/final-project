<?php require views_path('partials/header');?>

<div class="container-fluid d-flex justify-content-center align-items-center min-vh-100">
  <div class="card shadow-lg col-lg-4 col-md-5 p-4">
    <form method="post">
      <div class="text-center mb-4">
        <h1 class="display-4 text-primary">
          <i class="fa fa-user"></i>
        </h1>
        <h3>Login</h3>
        <p class="text-muted"><?= esc(APP_NAME) ?></p>
      </div>

      <div class="mb-4">
        <input value="<?= set_value('email') ?>" name="email" type="email" class="form-control <?= !empty($errors['email']) ? 'border-danger' : '' ?>" id="email" placeholder="Email" autocomplete="off" autofocus>
        <?php if (!empty($errors['email'])): ?>
          <small class="text-danger"><?= $errors['email'] ?></small>
        <?php endif; ?>
      </div>

      <div class="mb-4">
        <input value="<?= set_value('password') ?>" name="password" type="password" class="form-control <?= !empty($errors['password']) ? 'border-danger' : '' ?>" id="password" placeholder="Password">
        <?php if (!empty($errors['password'])): ?>
          <small class="text-danger"><?= $errors['password'] ?></small>
        <?php endif; ?>
      </div>

      <div class="row mb-3">
        <button class="btn btn-primary w-100 py-2" style="font-size: 18px;">Login</button>
      </div>
    </form>
  </div>
</div>

<?php require views_path('partials/footer');?>
