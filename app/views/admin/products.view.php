<div class="table-responsive">
    <table class="table table-hover table-striped">
        <!-- Table Header -->
        <thead class="thead-light">
            <tr>
                <th>Barcode</th>
                <th>Product</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Image</th>
                <th>Date</th>
                <th>
                    <a href="index.php?pg=product-new">
                        <button class="btn btn-primary btn-sm rounded-pill"><i class="fa fa-plus"></i> Add New</button>
                    </a>
                </th>
            </tr>
        </thead>

        <!-- Table Body -->
        <tbody>
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <tr class="align-middle">
                        <td><?= esc($product['barcode']) ?></td>
                        <td>
                            <a href="index.php?pg=product-single&id=<?= $product['id'] ?>" class="text-decoration-none text-dark">
                                <?= esc($product['description']) ?>
                            </a>
                        </td>
                        <td><?= esc($product['qty']) ?></td>
                        <td><?= esc($product['amount']) ?></td>
                        <td>
                            <?php 
                                $imagePath = $product['image'];
                                if(strpos($imagePath, 'uploads/') !== 0){
                                    $imagePath = 'uploads/' . $imagePath;
                                }
                            ?>
                            <img src="<?= esc($imagePath) ?>" alt="Product Image" class="img-fluid rounded" style="max-width: 100px;">
                        </td>
                        <td><?= date("jS M, Y", strtotime($product['date'])) ?></td>
                        <td>
                            <a href="index.php?pg=product-edit&id=<?= $product['id'] ?>" class="text-decoration-none">
                                <button class="btn btn-success btn-sm rounded-pill">Edit</button>
                            </a>
                            <a href="index.php?pg=product-delete&id=<?= $product['id'] ?>" class="text-decoration-none">
                                <button class="btn btn-danger btn-sm rounded-pill">Delete</button>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
