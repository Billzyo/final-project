<style>
    .product-single {
        max-width: 600px;
        margin: 2rem auto;
        padding: 1.5rem;
        background-color: #fff;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        font-family: system-ui, -apple-system, sans-serif;
        color: #334155;
    }
    .product-single h2 {
        font-size: 1.75rem;
        font-weight: 600;
        margin-bottom: 1rem;
        text-align: center;
    }
    .product-single p {
        font-size: 1rem;
        margin: 0.5rem 0;
    }
    .product-single img {
        display: block;
        margin: 1rem auto;
        max-width: 100%;
        height: auto;
        border-radius: 0.5rem;
    }
    .product-single a.btn {
        display: inline-block;
        margin-top: 1rem;
        padding: 0.5rem 1rem;
        background-color: #3b82f6;
        color: white;
        text-decoration: none;
        border-radius: 0.375rem;
        text-align: center;
        transition: background-color 0.3s ease;
    }
    .product-single a.btn:hover {
        background-color: #2563eb;
    }
</style>

<div class="product-single">
    <h2>Product Details</h2>
    <p><strong>Barcode:</strong> <?= esc($product['barcode']) ?></p>
    <p><strong>Description:</strong> <?= esc($product['description']) ?></p>
    <p><strong>Quantity:</strong> <?= esc($product['qty']) ?></p>
    <p><strong>Price:</strong> <?= esc($product['amount']) ?></p>
    <p><strong>Date Added:</strong> <?= date("jS M, Y", strtotime($product['date'])) ?></p>
    <p><strong>Image:</strong></p>
    <img src="<?= esc($product['image']) ?>" alt="Product Image" style="max-width: 300px;">
    <p>
        <a href="index.php?pg=admin&tab=products" class="btn btn-secondary">Back to Products</a>
    </p>
</div>
