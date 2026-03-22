<?php $pageTitle = 'Products - StoreHub'; include "../App/Views/layouts/header.php"; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0 fw-bold">Products</h4>
    <?php if (in_array($_SESSION['role_name'], ['admin', 'seller'])): ?>
        <a href="/MVC_PHP/public/Product/create" class="btn btn-primary"><i class='bx bx-plus'></i> Add Product</a>
    <?php endif; ?>
</div>

<div class="row g-4">
    <?php if (empty($products)): ?>
        <div class="col-12 text-center text-muted py-5">
            <i class='bx bx-package' style="font-size: 3rem;"></i>
            <p class="mt-2">No products found</p>
        </div>
    <?php else: ?>
        <?php foreach ($products as $product): ?>
        <div class="col-md-6 col-lg-4">
            <div class="card product-card h-100">
                <?php if ($product['image']): ?>
                    <img src="/MVC_PHP/public/<?= $product['image'] ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                <?php else: ?>
                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                        <i class='bx bx-image' style="font-size: 3rem; color: #cbd5e1;"></i>
                    </div>
                <?php endif; ?>
                <div class="card-body">
                    <h6 class="fw-bold mb-1"><?= htmlspecialchars($product['name']) ?></h6>
                    <p class="text-muted small mb-2"><?= htmlspecialchars(substr($product['description'], 0, 80)) ?>...</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-primary">₱<?= number_format($product['price'], 2) ?></span>
                        <span class="badge bg-light text-dark">Stock: <?= $product['stock'] ?></span>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top-0 pb-3">
                    <div class="d-flex gap-2">
                        <?php if ($_SESSION['role_name'] === 'customer'): ?>
                            <a href="/MVC_PHP/public/Cart/add/<?= $product['id'] ?>" class="btn btn-primary btn-sm flex-fill">
                                <i class='bx bx-cart-add'></i> Add to Cart
                            </a>
                        <?php endif; ?>
                        <?php if ($_SESSION['role_name'] === 'admin' || ($_SESSION['role_name'] === 'seller' && $product['seller_id'] == $_SESSION['user_id'])): ?>
                            <a href="/MVC_PHP/public/Product/edit/<?= $product['id'] ?>" class="btn btn-outline-primary btn-sm">
                                <i class='bx bx-edit'></i>
                            </a>
                            <button onclick="confirmDelete('/MVC_PHP/public/Product/delete/<?= $product['id'] ?>')" class="btn btn-outline-danger btn-sm">
                                <i class='bx bx-trash'></i>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php include "../App/Views/layouts/footer.php"; ?>
