<div class="row justify-content-center">
    <!-- Total Users Card -->
    <div class="col-md-3 col-12 mb-4">
        <div class="card shadow-sm border-0 rounded p-4 text-center">
            <i class="fa fa-user" style="font-size: 40px; color: #5c6bc0;"></i>
            <h4 class="mt-3 mb-2 text-muted">Total Users</h4>
            <h1 class="display-4 font-weight-bold"><?= $total_users ?></h1>
        </div>
    </div>

    <!-- Total Products Card -->
    <div class="col-md-3 col-12 mb-4">
        <div class="card shadow-sm border-0 rounded p-4 text-center">
            <i class="fa fa-hamburger" style="font-size: 40px; color: #ff7043;"></i>
            <h4 class="mt-3 mb-2 text-muted">Total Products</h4>
            <h1 class="display-4 font-weight-bold"><?= $total_products ?></h1>
        </div>
    </div>

    <!-- Total Sales Card -->
    <div class="col-md-3 col-12 mb-4">
        <div class="card shadow-sm border-0 rounded p-6 text-center">
            <i class="fa fa-money-bill-wave" style="font-size: 40px; color: #43a047;"></i>
            <h4 class="mt-3 mb-2 text-muted">Total Sales</h4>
            <h1 class="display-4 font-weight-bold">K<?= $total_sales ?></h1>
        </div>
    </div>
</div>

<div class="row justify-content-center mt-4">
    <div class="col-12">
        <div class="card shadow-sm border-0 rounded p-4">
            <h4 class="mb-3 text-muted">Total Sales Per Product</h4>
            <table class="table table-bordered table-hover table-striped">
                <thead class="thead-light">
                    <tr>
                        <th>Description</th>
                        <th>Total Sales (K)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($total_sales_per_product)): ?>
                        <?php foreach ($total_sales_per_product as $item): ?>
                            <tr>
                                <td><?= esc($item['description']) ?></td>
                                <td><?= number_format($item['total_sales'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="2" class="text-center">No sales data available.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
