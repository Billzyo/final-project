<?php require views_path('partials/header');?>

<div class="container-fluid d-flex justify-content-center align-items-center p-4">
    <div class="card shadow-sm rounded-lg p-4 w-100" style="max-width: 500px;">

        <form method="post" enctype="multipart/form-data">
            <h5 class="text-primary mb-4"><i class="fa fa-hamburger"></i> Add Product</h5>

            <div class="mb-3">
                <label for="productControlInput1" class="form-label">Product Description</label>
                <input name="description" type="text" class="form-control <?=!empty($errors['description']) ? 'border-danger':''?>" id="productControlInput1" placeholder="Enter product description">
                <?php if(!empty($errors['description'])): ?>
                    <small class="text-danger"><?= $errors['description'] ?></small>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="barcodeControlInput1" class="form-label">Barcode <small class="text-muted">(optional)</small></label>
                <input name="barcode" type="text" class="form-control <?=!empty($errors['barcode']) ? 'border-danger':''?>" id="barcodeControlInput1" placeholder="Enter product barcode">
                <?php if(!empty($errors['barcode'])): ?>
                    <small class="text-danger"><?= $errors['barcode'] ?></small>
                <?php endif; ?>
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text">Qty:</span>
                <input name="qty" value="1" type="number" class="form-control <?=!empty($errors['qty']) ? 'border-danger':''?>" placeholder="Quantity" aria-label="Quantity">
                <span class="input-group-text">Amount:</span>
                <input name="amount" value="0.00" step="0.05" type="number" class="form-control <?=!empty($errors['amount']) ? 'border-danger':''?>" placeholder="Amount" aria-label="Amount">
            </div>
            <?php if(!empty($errors['qty'])): ?>
                <small class="text-danger"><?= $errors['qty'] ?></small>
            <?php endif; ?>
            <?php if(!empty($errors['amount'])): ?>
                <small class="text-danger"><?= $errors['amount'] ?></small>
            <?php endif; ?>
            
            <div class="mb-3">
                <label for="formFile" class="form-label">Product Image</label>
                <input name="image" class="form-control <?=!empty($errors['image']) ? 'border-danger':''?>" type="file" id="formFile">
                <?php if(!empty($errors['image'])): ?>
                    <small class="text-danger"><?= $errors['image'] ?></small>
                <?php endif; ?>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button class="btn btn-danger ms-2" type="submit">Save</button>
                <a href="index.php?pg=admin&tab=products" class="btn btn-secondary ms-2">Cancel</a>
            </div>
        </form>

    </div>
</div>

<?php require views_path('partials/footer'); ?>
