<?php $pageTitle = 'Orders - StoreHub'; include "../App/Views/layouts/header.php"; ?>

<h4 class="mb-4 fw-bold">Orders</h4>

<div class="table-container">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Order #</th>
                    <?php if ($_SESSION['role_name'] !== 'customer'): ?>
                        <th>Customer</th>
                    <?php endif; ?>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($orders)): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">No orders found</td></tr>
                <?php else: ?>
                    <?php foreach ($orders as $order): ?>
                    <tr>
                        <td>#<?= $order['id'] ?></td>
                        <?php if ($_SESSION['role_name'] !== 'customer'): ?>
                            <td><?= htmlspecialchars($order['customer_name']) ?></td>
                        <?php endif; ?>
                        <td>₱<?= number_format($order['total_amount'], 2) ?></td>
                        <td><span class="badge status-<?= strtolower($order['status']) ?>"><?= $order['status'] ?></span></td>
                        <td><?= date('M d, Y', strtotime($order['created_at'])) ?></td>
                        <td>
                            <a href="/MVC_PHP/public/Order/details/<?= $order['id'] ?>" class="btn btn-outline-primary btn-sm">
                                <i class='bx bx-show'></i> View
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include "../App/Views/layouts/footer.php"; ?>