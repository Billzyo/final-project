<?php require views_path('partials/header');?>

<div class="container d-flex justify-content-center align-items-center min-vh-100">
  <div class="card shadow-sm rounded-lg p-4 col-lg-5 col-md-6">
    <form method="post">
      
      <div class="text-center mb-4">
        <h3><i class="fa fa-user"></i> Create User</h3>
        <div class="text-muted"><?=esc(APP_NAME)?></div>
      </div>

      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input value="<?=set_value('username')?>" name="username" type="text" class="form-control <?=!empty($errors['username']) ? 'border-danger':''?>" id="username" placeholder="Username">
        <?php if(!empty($errors['username'])):?>
          <small class="text-danger"><?=$errors['username']?></small>
        <?php endif;?>
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>
        <input value="<?=set_value('email')?>" name="email" type="email" class="form-control <?=!empty($errors['email']) ? 'border-danger':''?>" id="email" placeholder="name@example.com">
        <?php if(!empty($errors['email'])):?>
          <small class="text-danger"><?=$errors['email']?></small>
        <?php endif;?>
      </div>

      <div class="mb-3">
        <label for="gender" class="form-label">Gender</label>
        <select name="gender" class="form-select <?=!empty($errors['gender']) ? 'border-danger':''?>" id="gender">
          <option value="male" <?=set_value('gender') === 'male' ? 'selected' : ''?>>Male</option>
          <option value="female" <?=set_value('gender') === 'female' ? 'selected' : ''?>>Female</option>
        </select>
        <?php if(!empty($errors['gender'])):?>
          <small class="text-danger"><?=$errors['gender']?></small>
        <?php endif;?>
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input value="<?=set_value('password')?>" name="password" type="password" class="form-control <?=!empty($errors['password']) ? 'border-danger':''?>" id="password" placeholder="Password">
        <?php if(!empty($errors['password'])):?>
          <small class="text-danger"><?=$errors['password']?></small>
        <?php endif;?>
      </div>

      <div class="mb-3">
        <label for="password_retype" class="form-label">Retype Password</label>
        <input value="<?=set_value('password_retype')?>" name="password_retype" type="password" class="form-control <?=!empty($errors['password_retype']) ? 'border-danger':''?>" id="password_retype" placeholder="Retype Password">
        <?php if(!empty($errors['password_retype'])):?>
          <small class="text-danger"><?=$errors['password_retype']?></small>
        <?php endif;?>
      </div>

      <div class="d-flex justify-content-between mt-4">
        <button class="btn btn-primary btn-lg px-4">Create</button>
        <a href="index.php?pg=admin&tab=users" class="btn btn-danger btn-lg px-4">Cancel</a>
      </div>

    </form>
  </div>
</div>

<?php require views_path('partials/footer');?>
