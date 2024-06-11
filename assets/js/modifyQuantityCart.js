
document.addEventListener('DOMContentLoaded', function () {
    /*
      Function to update the quantity of a product in the shopping cart.
      It sends a POST request to the server with the new quantity and updates the UI accordingly.
      Rejects if there is a network error or if the server returns an error message.
    */
    const updateQuantity = async (bagId, productId, newQuantity) => {
        try {
            // Send a POST request to the server with the new quantity.
            const response = await fetch(`/shop/update-quantity/${bagId}/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                // Convert the new quantity to JSON and send it as the request body.
                body: JSON.stringify({ quantity: newQuantity }),
            });

            // If the response is not ok, throw an error.
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            // Parse the response body as JSON.
            const data = await response.json();

            // If the server returns a success message, update the UI.
            if (data.success) {
                // Find the row in the table corresponding to the product.
                const row = document.querySelector(`tr[data-product-id="${productId}"]`);
                // Update the quantity displayed in the row.
                row.querySelector('.quantity').textContent = newQuantity;
                // Update the subtotal displayed in the row.
                row.querySelector('.subtotal').textContent = `${data.subtotal.toFixed(2)}€`;

                // Update the total price in the UI.
                document.getElementById('totalPrice').textContent = `${data.totalPrice.toFixed(2)}€`;
                // Update the subtotal price in the UI.
                document.getElementById('subtotalPrice').textContent = `${(data.totalPrice * 0.79).toFixed(2)}€`;
                // Update the IVA price in the UI.
                document.getElementById('ivaPrice').textContent = `${(data.totalPrice * 0.21).toFixed(2)}€`;
            } else {
                // If the server returns an error message, log it to the console.
                console.error('Error updating quantity:', data.message);
            }
        } catch (error) {
            // If there is a network error or if the server returns an error message,
            // log the error to the console.
            console.error('Fetch error:', error);
        }
    };

    /*
      Function to remove a product from the shopping cart.
      Sends a POST request to the server with the product's bag ID and product ID.
      Rejects if there is a network error or if the server returns an error message.
      On success, removes the product's row from the table and updates the UI.
    */
    const removeFromCart = async (bagId, productId) => {
        try {
            // Send a POST request to the server with the product's bag ID and product ID.
            const response = await fetch(`/shop/remove-from-cart/${bagId}/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            // If the response is not ok, throw an error.
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            // Parse the response body as JSON.
            const data = await response.json();

            // If the server returns a success message, remove the product's row from the table
            // and update the UI.
            if (data.success) {
                // Find the row in the table corresponding to the product.
                const row = document.querySelector(`tr[data-product-id="${productId}"]`);
                // Remove the row from the table.
                row.remove();
                // Update the total price in the UI.
                document.getElementById('totalPrice').textContent = `${data.totalPrice.toFixed(2)}€`;
                // Update the subtotal price in the UI.
                document.getElementById('subtotalPrice').textContent = `${(data.totalPrice * 0.79).toFixed(2)}€`;
                // Update the IVA price in the UI.
                document.getElementById('ivaPrice').textContent = `${(data.totalPrice * 0.21).toFixed(2)}€`;

            } else {
                // If the server returns an error message, log it to the console.
                console.error('Error removing product:', data.message);
            }
        } catch (error) {
            // If there is a network error or if the server returns an error message,
            // log the error to the console.
            console.error('Fetch error:', error);
        }
    };


    // This block of code selects all elements with the class 'increase-btn' and adds an event listener to each one.
    document.querySelectorAll('.increase-btn').forEach(button => {
        // When the 'increase' button is clicked, this function executes.
        button.addEventListener('click', function () {
            // Find the nearest ancestor 'tr' element (table row) of the clicked button.
            const row = this.closest('tr');
            // Extract the custom 'bagId' and 'productId' data attributes from the row.
            const bagId = row.dataset.bagId;
            const productId = row.dataset.productId;
            // Get the current quantity of the product by parsing the text content of the element with class 'quantity'.
            let quantity = parseInt(row.querySelector('.quantity').textContent);
            // Increment the quantity by 1.
            updateQuantity(bagId, productId, ++quantity); // Call the 'updateQuantity' function with the new quantity.
        });
    });

    // This block of code selects all elements with the class 'decrease-btn' and adds an event listener to each one.
    document.querySelectorAll('.decrease-btn').forEach(button => {
        // When the 'decrease' button is clicked, this function executes.
        button.addEventListener('click', function () {
            // Find the nearest ancestor 'tr' element (table row) of the clicked button.
            const row = this.closest('tr');
            // Extract the custom 'bagId' and 'productId' data attributes from the row.
            const bagId = row.dataset.bagId;
            const productId = row.dataset.productId;
            // Get the current quantity of the product by parsing the text content of the element with class 'quantity'.
            let quantity = parseInt(row.querySelector('.quantity').textContent);
            // Check if the quantity is greater than 1.
            if (quantity > 1) {
                // If yes, decrement the quantity by 1 and call the 'updateQuantity' function.
                updateQuantity(bagId, productId, --quantity);
            } else {
                // If the quantity is 1, call the 'removeFromCart' function.
                removeFromCart(bagId, productId);
            }
        });
    });

});
