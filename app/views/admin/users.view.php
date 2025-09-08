<div class="table-responsive">
    <table class="table table-hover table-striped">
        <!-- Table Header -->
        <thead class="thead-light">
            <tr>
                <th>Image</th>
                <th>Username</th>
                <th>Gender</th>
                <th>Email</th>
                <th>Role</th>
                <th>Date</th>
                <th>
                    <a href="index.php?pg=signup">
                        <button class="btn btn-primary btn-sm rounded-pill"><i class="fa fa-plus"></i> Add New</button>
                    </a>
                </th>
            </tr>
        </thead>

        <!-- Table Body -->
        <tbody>
            <?php if (!empty($users)): ?>
                <?php foreach ($users as $user): ?>
                    <tr class="align-middle">
                        <td>
                            <?php 
                                $userImagePath = $user['image'];
                                if(strpos($userImagePath, 'uploads/') !== 0){
                                    $userImagePath = 'uploads/' . $userImagePath;
                                }
                            ?>
                            <a href="index.php?pg=profile&id=<?= $user['id'] ?>">
                                <img src="<?= esc($userImagePath) ?>" style="width: 80px; height: 80px; object-fit: cover;" class="rounded-circle">
                            </a>
                        </td>
                        <td>
                            <a href="index.php?pg=profile&id=<?= $user['id'] ?>" class="text-decoration-none text-dark"><?= esc($user['username']) ?></a>
                        </td>
                        <td><?= esc($user['gender']) ?></td>
                        <td><?= esc($user['email']) ?></td>
                        <td><?= esc($user['role']) ?></td>
                        <td><?= date("jS M, Y", strtotime($user['date'])) ?></td>
                        <td>
                            <a href="index.php?pg=edit-user&id=<?= $user['id'] ?>" class="text-decoration-none">
                                <button class="btn btn-success btn-sm rounded-pill">Edit</button>
                            </a>
                            <a href="index.php?pg=delete-user&id=<?= $user['id'] ?>" class="text-decoration-none">
                                <button class="btn btn-danger btn-sm rounded-pill">Delete</button>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <?php $pager->display(count($users)) ?>
</div>
