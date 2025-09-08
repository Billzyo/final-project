<?php require views_path('partials/header');?>

<div class="container d-flex justify-content-center align-items-center min-vh-100">
  <div class="card shadow-sm rounded-lg p-4 col-lg-5 col-md-6">
    <?php if(is_array($row)):?>
    
      <div class="text-center mb-4">
        <h3><i class="fa fa-user"></i> User Profile</h3>
        <div class="text-muted"><?=esc(APP_NAME)?></div>
      </div>
      
      <div class="text-center mb-4">
        <img src="<?=crop($row['image'], 400, $row['gender'])?>" class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">
      </div>

      <table class="table table-bordered table-striped">
        <tr>
          <th>Username</th>
          <td><?=$row['username']?></td>
        </tr>
        <tr>
          <th>Email</th>
          <td><?=$row['email']?></td>
        </tr>
        <tr>
          <th>Gender</th>
          <td><?=$row['gender']?></td>
        </tr>
        <tr>
          <th>Role</th>
          <td><?=$row['role']?></td>
        </tr>
        <tr>
          <th>Date Created</th>
          <td><?=get_date($row['date'])?></td>
        </tr>
      </table>

      <div class="d-flex justify-content-between mt-4">
        <a href="index.php?pg=edit-user&id=<?=$row['id']?>" class="btn btn-primary btn-lg px-4">
          Edit
        </a>
        <a href="index.php?pg=delete-user&id=<?=$row['id']?>" class="btn btn-danger btn-lg px-4">
          Delete
        </a>
      </div>

    <?php else:?>
      <div class="alert alert-danger text-center mt-4">That user was not found!</div>
    <?php endif;?>
  </div>
</div>

<?php require views_path('partials/footer');?>
