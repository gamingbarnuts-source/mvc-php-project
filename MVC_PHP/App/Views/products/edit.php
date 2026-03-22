<?php $pageTitle = 'Edit Product - StoreHub'; include "../App/Views/layouts/header.php"; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Edit Product</h5>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="/MVC_PHP/public/Product/edit/<?= $product['id'] ?>" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Product Name</label>
                        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3" required><?= htmlspecialchars($product['description']) ?></textarea>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Price (₱)</label>
                            <input type="number" name="price" class="form-control" step="0.01" value="<?= $product['price'] ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Stock Quantity</label>
                            <input type="number" name="stock" class="form-control" value="<?= $product['stock'] ?>" required>
                        </div>
                    </div>
                    <?php if ($product['image']): ?>
                        <div class="mb-3">
                            <img src="/MVC_PHP/public/<?= $product['image'] ?>" class="rounded" style="max-height: 120px;">
                        </div>
                    <?php endif; ?>
                    <div class="mb-4">
                        <label class="form-label">Change Image (optional)</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Update Product</button>
                        <a href="/MVC_PHP/public/Product" class="btn btn-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include "../App/Views/layouts/footer.php"; ?>
