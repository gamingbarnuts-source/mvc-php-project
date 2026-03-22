<?php $pageTitle = 'Cart - StoreHub'; include "../App/Views/layouts/header.php"; ?>

<h4 class="mb-4 fw-bold">Shopping Cart</h4>

<?php if (empty($cartItems)): ?>
    <div class="text-center py-5 shadow-sm border rounded bg-white mb-5">
        <i class='bx bx-cart' style="font-size: 4rem; color: #cbd5e1;"></i>
        <p class="text-muted mt-2">Your cart is empty</p>
        <a href="#browse-products" class="btn btn-primary">Browse Products</a>
    </div>
<?php else: ?>
    <div class="table-container mb-4">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th style="width: 140px;">Quantity</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems as $item): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <?php if ($item['image']): ?>
                                    <img src="/MVC_PHP/public/<?= $item['image'] ?>" class="rounded" style="width: 48px; height: 48px; object-fit: cover;">
                                <?php endif; ?>
                                <span class="fw-medium"><?= htmlspecialchars($item['name']) ?></span>
                            </div>
                        </td>
                        <td>₱<?= number_format($item['price'], 2) ?></td>
                        <td>
                            <div class="input-group input-group-sm">
                                <button class="btn btn-outline-secondary" onclick="updateCartQty(<?= $item['id'] ?>, <?= $item['quantity'] - 1 ?>)">-</button>
                                <input type="text" class="form-control text-center" value="<?= $item['quantity'] ?>" readonly>
                                <button class="btn btn-outline-secondary" onclick="updateCartQty(<?= $item['id'] ?>, <?= $item['quantity'] + 1 ?>)">+</button>
                            </div>
                        </td>
                        <td class="fw-bold">₱<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                        <td>
                            <a href="/MVC_PHP/public/Cart/remove/<?= $item['id'] ?>" class="btn btn-outline-danger btn-sm">
                                <i class='bx bx-trash'></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row justify-content-end mb-5">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Total</span>
                        <span class="fw-bold fs-5 text-primary">₱<?= number_format($total, 2) ?></span>
                    </div>
                    <a href="/MVC_PHP/public/Order/checkout" class="btn btn-primary w-100">
                        <i class='bx bx-credit-card'></i> Proceed to Checkout
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<hr class="my-5">

<div id="browse-products">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold">More Products</h5>
        
        <div class="d-flex gap-2">
            <div class="input-group input-group-sm" style="width: 250px;">
                <span class="input-group-text bg-white border-end-0"><i class='bx bx-search'></i></span>
                <input type="text" id="productSearch" class="form-control border-start-0" placeholder="Search products..." onkeyup="filterProducts()">
            </div>
            
            <select id="priceFilter" class="form-select form-select-sm" style="width: 150px;" onchange="filterProducts()">
                <option value="all">All Prices</option>
                <option value="0-500">Under ₱500</option>
                <option value="500-2000">₱500 - ₱2,000</option>
                <option value="2000+">Over ₱2,000</option>
            </select>
        </div>
    </div>

    <div class="row g-3" id="productGrid">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <div class="col-md-3 product-item" 
                     data-name="<?= strtolower(htmlspecialchars($product['name'])) ?>" 
                     data-price="<?= $product['price'] ?>">
                    <div class="card h-100 border-0 shadow-sm overflow-hidden">
                        <?php if ($product['image']): ?>
                            <img src="/MVC_PHP/public/<?= $product['image'] ?>" class="card-img-top" style="height: 160px; object-fit: cover;">
                        <?php endif; ?>
                        <div class="card-body p-3">
                            <h6 class="card-title fw-bold mb-1 text-truncate"><?= htmlspecialchars($product['name']) ?></h6>
                            <p class="text-primary fw-bold mb-2">₱<?= number_format($product['price'], 2) ?></p>
                            <a href="/MVC_PHP/public/Cart/add/<?= $product['id'] ?>" class="btn btn-sm btn-outline-primary w-100">
                                <i class='bx bx-plus'></i> Add to Cart
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center text-muted">No additional products available.</p>
        <?php endif; ?>
    </div>
</div>

<script>
function filterProducts() {
    const searchInput = document.getElementById('productSearch').value.toLowerCase();
    const priceFilter = document.getElementById('priceFilter').value;
    const products = document.getElementsByClassName('product-item');

    Array.from(products).forEach(product => {
        const name = product.getAttribute('data-name');
        const price = parseFloat(product.getAttribute('data-price'));
        
        // Search Match
        const matchesSearch = name.includes(searchInput);
        
        // Price Match
        let matchesPrice = true;
        if (priceFilter === '0-500') matchesPrice = price < 500;
        else if (priceFilter === '500-2000') matchesPrice = price >= 500 && price <= 2000;
        else if (priceFilter === '2000+') matchesPrice = price > 2000;

        // Show/Hide
        if (matchesSearch && matchesPrice) {
            product.style.display = 'block';
        } else {
            product.style.display = 'none';
        }
    });
}
</script>

<?php include "../App/Views/layouts/footer.php"; ?>