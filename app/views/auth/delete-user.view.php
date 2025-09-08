<?php require views_path('partials/header'); ?>

<div class="container-fluid d-flex justify-content-center mt-5">
    <div class="col-lg-6 col-md-8 col-sm-10 p-4 border rounded shadow-sm">
        <?php if(is_array($row) && $row['deletable']): ?>
        <form method="post">
            <div class="text-center">
                <h3 class="mb-3"><i class="fa fa-user"></i> Delete User</h3>
                <div class="alert alert-danger text-center mb-4">Are you sure you want to delete this user?</div>
            </div>
            
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <div class="form-control-plaintext"><?= esc($row['username']) ?></div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <div class="form-control-plaintext"><?= esc($row['email']) ?></div>
            </div>

            <div class="mb-3">
                <label for="gender" class="form-label">Gender</label>
                <div class="form-control-plaintext"><?= esc($row['gender']) ?></div>
            </div>

            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <div class="form-control-plaintext"><?= esc($row['role']) ?></div>
            </div>

            <div class="d-flex justify-content-end">
                <button class="btn btn-danger mr-2" type="submit">Delete</button>
                <a href="index.php?pg=admin&tab=users" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
        <?php else: ?>
            <?php if(is_array($row) && !$row['deletable']): ?>
                <div class="alert alert-warning text-center">This user cannot be deleted!</div>
            <?php else: ?>
                <div class="alert alert-danger text-center">User not found!</div>
            <?php endif; ?>

            <a href="index.php?pg=admin&tab=users" class="btn btn-secondary">Back to Users</a>
        <?php endif; ?>
    </div>
</div>

<?php require views_path('partials/footer'); ?>
