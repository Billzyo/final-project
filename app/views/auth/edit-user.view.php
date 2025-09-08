<?php

if(!empty($_SESSION['referer'])) {
    $back_link = $_SESSION['referer'];
} else {
    $back_link = "index.php?pg=admin&tab=users";
}

?>

<?php require views_path('partials/header');?>

<div class="container-fluid d-flex justify-content-center mt-5">
    <div class="card col-lg-6 col-md-8 p-4 shadow-sm rounded">

        <?php if(is_array($row)):?>
        <form method="post" enctype="multipart/form-data">
            <div class="text-center mb-4">
                <h3 class="text-primary"><i class="fa fa-user"></i> Edit User</h3>
                <p class="text-muted"><?=esc(APP_NAME)?></p>
            </div>

            <div class="mb-3">
                <label for="formFile" class="form-label">User Image</label>
                <input name="image" class="form-control <?=!empty($errors['image']) ? 'border-danger':''?>" type="file" id="formFile">
                <?php if(!empty($errors['image'])):?>
                    <small class="text-danger"><?=$errors['image']?></small>
                <?php endif;?>
            </div>
            <div class="text-center mb-3">
                <img class="img-fluid rounded-circle" src="<?=$row['image']?>" style="width:150px; height:150px; object-fit: cover;">
            </div>

            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input value="<?=set_value('username', $row['username'])?>" name="username" type="text" class="form-control <?=!empty($errors['username']) ? 'border-danger':''?>" id="username" placeholder="Username">
                <?php if(!empty($errors['username'])):?>
                    <small class="text-danger"><?=$errors['username']?></small>
                <?php endif;?>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input value="<?=set_value('email', $row['email'])?>" name="email" type="email" class="form-control <?=!empty($errors['email']) ? 'border-danger':''?>" id="email" placeholder="name@example.com">
                <?php if(!empty($errors['email'])):?>
                    <small class="text-danger"><?=$errors['email']?></small>
                <?php endif;?>
            </div>

            <div class="mb-3">
                <label for="gender" class="form-label">Gender</label>
                <select name="gender" class="form-control <?=!empty($errors['gender']) ? 'border-danger':''?>" id="gender">
                    <option><?=$row['gender']?></option>
                    <option>male</option>
                    <option>female</option>
                </select>
                <?php if(!empty($errors['gender'])):?>
                    <small class="text-danger"><?=$errors['gender']?></small>
                <?php endif;?>
            </div>

            <?php if(Auth::get('role') == "admin"):?>
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select name="role" class="form-control <?=!empty($errors['role']) ? 'border-danger':''?>" id="role">
                    <option><?=$row['role']?></option>
                    <option>admin</option>
                    <option>supervisor</option>
                    <option>cashier</option>
                    <option>user</option>
                </select>
                <?php if(!empty($errors['role'])):?>
                    <small class="text-danger"><?=$errors['role']?></small>
                <?php endif;?>
            </div>
            <?php endif;?>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input value="<?=set_value('password','')?>" name="password" type="password" class="form-control <?=!empty($errors['password']) ? 'border-danger':''?>" id="password" placeholder="Password (leave empty to not change)">
                <?php if(!empty($errors['password'])):?>
                    <small class="text-danger"><?=$errors['password']?></small>
                <?php endif;?>
            </div>

            <div class="mb-3">
                <label for="password_retype" class="form-label">Retype Password</label>
                <input value="<?=set_value('password_retype','')?>" name="password_retype" type="password" class="form-control <?=!empty($errors['password_retype']) ? 'border-danger':''?>" id="password_retype" placeholder="Retype Password (leave empty to not change)">
                <?php if(!empty($errors['password_retype'])):?>
                    <small class="text-danger"><?=$errors['password_retype']?></small>
                <?php endif;?>
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-success">Save</button>
                <a href="<?=$back_link?>" class="btn btn-danger">Cancel</a>
            </div>

        </form>
        <?php else:?>
        <div class="alert alert-danger text-center">That user was not found!</div>
        <a href="<?=$back_link?>" class="btn btn-danger">Go Back</a>
        <?php endif;?>
    </div>
</div>

<?php require views_path('partials/footer');?>
