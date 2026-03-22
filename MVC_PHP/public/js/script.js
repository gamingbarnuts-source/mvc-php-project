// Sidebar toggle
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');

    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('show');
        });

        // Close sidebar on outside click (mobile)
        document.addEventListener('click', function(e) {
            if (window.innerWidth <= 768 && sidebar.classList.contains('show')) {
                if (!sidebar.contains(e.target) && e.target !== toggleBtn) {
                    sidebar.classList.remove('show');
                }
            }
        });
    }
});

// Confirm delete
function confirmDelete(url) {
    if (confirm('Are you sure you want to delete this? This action cannot be undone.')) {
        window.location.href = url;
    }
}

// Update cart quantity via form submit
function updateCartQty(cartId, qty) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = baseUrl + 'Cart/update';

    const idInput = document.createElement('input');
    idInput.type = 'hidden';
    idInput.name = 'cart_id';
    idInput.value = cartId;

    const qtyInput = document.createElement('input');
    qtyInput.type = 'hidden';
    qtyInput.name = 'quantity';
    qtyInput.value = qty;

    form.appendChild(idInput);
    form.appendChild(qtyInput);
    document.body.appendChild(form);
    form.submit();
}

// Auto-dismiss alerts
setTimeout(function() {
    const alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(function(alert) {
        alert.style.transition = 'opacity 0.3s';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 300);
    });
}, 4000);

