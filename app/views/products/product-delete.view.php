<?php require views_path('partials/header');?>

<div class="container-fluid d-flex justify-content-center align-items-center p-4">
    <div class="card shadow-sm rounded-lg p-4 w-100" style="max-width: 500px;">

        <?php if(!empty($row)): ?>

        <form method="post" enctype="multipart/form-data">
            <h5 class="text-danger mb-4"><i class="fa fa-trash"></i> Delete Product</h5>

            <div class="alert alert-danger text-center mb-4">
                <strong>Warning:</strong> Are you sure you want to delete this product?
            </div>

            <div class="mb-3">
                <label for="productControlInput1" class="form-label">Product Description</label>
                <input disabled value="<?= set_value('description', $row['description']) ?>" name="description" type="text" class="form-control <?= !empty($errors['description']) ? 'border-danger' : '' ?>" id="productControlInput1" placeholder="Product description">
                <?php if(!empty($errors['description'])): ?>
                    <small class="text-danger"><?= $errors['description'] ?></small>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="barcodeControlInput1" class="form-label">Barcode <small class="text-muted">(optional)</small></label>
                <input disabled value="<?= set_value('barcode', $row['barcode']) ?>" name="barcode" type="text" class="form-control <?= !empty($errors['barcode']) ? 'border-danger' : '' ?>" id="barcodeControlInput1" placeholder="Product barcode">
                <?php if(!empty($errors['barcode'])): ?>
                    <small class="text-danger"><?= $errors['barcode'] ?></small>
                <?php endif; ?>
            </div>

            <div class="text-center mb-4">
                <img class="mx-auto d-block" src="<?= $row['image'] ?>" style="max-width: 80%; height: auto;">
            </div>

            <div class="d-flex justify-content-between">
                <button class="btn btn-danger" type="submit">Delete</button>
                <a href="index.php?pg=admin&tab=products" class="btn btn-secondary">Cancel</a>
            </div>
        </form>

        <?php else: ?>
            <div class="alert alert-warning" role="alert">
                The product was not found.
            </div>
            <div class="d-flex justify-content-center">
                <a href="index.php?pg=admin&tab=products" class="btn btn-primary">Back to Products</a>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php require views_path('partials/footer'); ?>
