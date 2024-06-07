
document.addEventListener('DOMContentLoaded', function () {
    const updateQuantity = async (bagId, productId, newQuantity) => {
        try {
            const response = await fetch(`/shop/update-quantity/${bagId}/${productId}`, {
        method: 'POST',
        headers: {
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({ quantity: newQuantity }),
        });

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const data = await response.json();

        if (data.success) {
            const row = document.querySelector(`tr[data-product-id="${productId}"]`);
            row.querySelector('.quantity').textContent = newQuantity;
            row.querySelector('.subtotal').textContent = `${data.subtotal.toFixed(2)}€`;

            document.getElementById('totalPrice').textContent = `${data.totalPrice.toFixed(2)}€`;
            document.getElementById('subtotalPrice').textContent = `${(data.totalPrice * 0.79).toFixed(2)}€`;
            document.getElementById('ivaPrice').textContent = `${(data.totalPrice * 0.21).toFixed(2)}€`;
        } else {
            console.error('Error updating quantity:', data.message);
        }
        } catch (error) {
            console.error('Fetch error:', error);
        }
    };

    const removeFromCart = async (bagId, productId) => {
        try {
            const response = await fetch(`/shop/remove-from-cart/${bagId}/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            const data = await response.json();

            if (data.success) {
                const row = document.querySelector(`tr[data-product-id="${productId}"]`);
                row.remove();
                document.getElementById('totalPrice').textContent = `${data.totalPrice.toFixed(2)}€`;
                document.getElementById('subtotalPrice').textContent = `${(data.totalPrice * 0.79).toFixed(2)}€`;
                document.getElementById('ivaPrice').textContent = `${(data.totalPrice * 0.21).toFixed(2)}€`;
                

            } else {
                console.error('Error removing product:', data.message);
            }
        } catch (error) {
            console.error('Fetch error:', error);
        }
    };

    document.querySelectorAll('.increase-btn').forEach(button => {
        button.addEventListener('click', function () {
            const row = this.closest('tr');
            const bagId = row.dataset.bagId;
            const productId = row.dataset.productId;
            let quantity = parseInt(row.querySelector('.quantity').textContent);
            updateQuantity(bagId, productId, ++quantity);
        });
    });

    document.querySelectorAll('.decrease-btn').forEach(button => {
        button.addEventListener('click', function () {
            const row = this.closest('tr');
            const bagId = row.dataset.bagId;
            const productId = row.dataset.productId;
            let quantity = parseInt(row.querySelector('.quantity').textContent);
            if (quantity > 1) {
                updateQuantity(bagId, productId, --quantity);
            } else {
                removeFromCart(bagId, productId);
            }
        });
    });
});
