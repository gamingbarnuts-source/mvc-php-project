<?php 
$pageTitle = 'Order Details - StoreHub'; 
include "../App/Views/layouts/header.php"; 
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0 fw-bold">Order #<?= $order['id'] ?></h4>
    <a href="/MVC_PHP/public/Order" class="btn btn-light">
        <i class='bx bx-arrow-back'></i> Back to Orders
    </a>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="table-container shadow-sm border rounded bg-white">
            <div class="p-3 border-bottom bg-light">
                <h6 class="mb-0 fw-bold text-primary">Order Items</h6>
            </div>
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <?php if (!empty($item['image'])): ?>
                                        <img src="/MVC_PHP/public/<?= $item['image'] ?>" 
                                             class="rounded border" 
                                             style="width: 45px; height: 45px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="bg-light rounded border d-flex align-items-center justify-content-center" 
                                             style="width: 45px; height: 45px;">
                                            <i class='bx bx-image text-muted'></i>
                                        </div>
                                    <?php endif; ?>
                                    <span class="fw-medium"><?= htmlspecialchars($item['product_name']) ?></span>
                                </div>
                            </td>
                            <td>₱<?= number_format($item['price'], 2) ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td class="fw-bold text-end">₱<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="fw-bold mb-3 border-bottom pb-2">Order Summary</h6>
                
                <div class="mb-2">
                    <small class="text-muted d-block">Customer</small>
                    <span class="fw-medium"><?= htmlspecialchars($order['customer_name']) ?></span>
                </div>
                
                <div class="mb-2">
                    <small class="text-muted d-block">Order Date</small>
                    <span><?= date('M d, Y h:i A', strtotime($order['created_at'])) ?></span>
                </div>
                
                <div class="mb-3">
                    <small class="text-muted d-block">Current Status</small>
                    <span class="badge rounded-pill status-<?= strtolower($order['status']) ?>">
                        <?= $order['status'] ?>
                    </span>
                </div>

                <hr class="my-3">
                
                <div class="d-flex justify-content-between align-items-center mb-0">
                    <span class="fw-bold text-muted">Grand Total</span>
                    <span class="fw-bold fs-4 text-primary">₱<?= number_format($order['total_amount'], 2) ?></span>
                </div>

                <?php if ($_SESSION['role_name'] === 'customer' && $order['status'] === 'Pending'): ?>
                    <hr class="my-3">
                    <form method="POST" action="/MVC_PHP/public/Order/cancel/<?= $order['id'] ?>" 
                          onsubmit="return confirm('Are you sure you want to cancel this order? This action cannot be undone.');">
                        <button type="submit" class="btn btn-outline-danger btn-sm w-100 py-2">
                            <i class='bx bx-x-circle'></i> Cancel My Order
                        </button>
                    </form>
                <?php endif; ?>

                <?php if ($_SESSION['role_name'] === 'admin'): ?>
                    <hr class="my-3">
                    <form method="POST" action="/MVC_PHP/public/Order/updateStatus/<?= $order['id'] ?>">
                        <label class="form-label small fw-bold text-muted">Management Actions</label>
                        <div class="input-group">
                            <select name="status" class="form-select form-select-sm">
                                <option value="Pending" <?= $order['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="Completed" <?= $order['status'] === 'Completed' ? 'selected' : '' ?>>Completed</option>
                                <option value="Cancelled" <?= $order['status'] === 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            </select>
                            <button type="submit" class="btn btn-primary btn-sm px-3">Update</button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if ($order['status'] === 'Cancelled'): ?>
            <div class="alert alert-secondary mt-3 mb-0 py-2 small">
                <i class='bx bx-info-circle'></i> This order was cancelled and is no longer active.
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include "../App/Views/layouts/footer.php"; ?>