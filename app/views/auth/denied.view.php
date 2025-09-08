<?php require views_path('partials/header');?>

<div class="container d-flex justify-content-center align-items-center min-vh-100">
  <div class="text-center">
    <div class="mb-4">
      <i class="fa fa-exclamation-triangle fa-5x text-danger"></i>
    </div>
    <h1 class="display-3 text-danger">Access Denied!</h1>
    <p class="lead text-muted"><?= Auth::getMessage() ?></p>
  </div>
</div>

<?php require views_path('partials/footer');?>
