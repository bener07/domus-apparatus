<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart Modal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Modal styling */
        .cart-modal {
            position: fixed;
            bottom: -70%; /* Initially hide the modal off the screen */
            left: 0;
            width: 100%;
            height: 70%;
            background-color: #fff;
            box-shadow: 0px -4px 15px rgba(0, 0, 0, 0.2);
            transition: bottom 0.5s ease-out; /* Smooth slide-up animation */
            z-index: 9999;
            display: none; /* Hidden by default */
        }

        .cart-modal.show {
            display: block;
            bottom: 0; /* Slide up to the bottom of the screen */
        }

        .cart-content {
            padding: 20px;
            overflow-y: auto;
        }

        #closeCartButton {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 1.5rem;
            cursor: pointer;
        }

        /* Cart Button */
        #cartButton {
            font-size: 2rem;
            padding: 10px 20px;
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 10000;
        }

        /* Optional: Styling for cart content */
        #cart-items {
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <!-- Cart Button -->
    <button id="cartButton" class="btn btn-primary">
        🛒 Cart <span id="cart-number" class="badge bg-danger">0</span>
    </button>

    <!-- Cart Modal -->
    <div id="cartModal" class="cart-modal">
        <div class="cart-content">
            <button id="closeCartButton" class="btn-close" aria-label="Close"></button>
            <h5 class="fs-5">Your Cart</h5>
            <div id="cart-items">
                <p>No items in the cart.</p>
            </div>
            <div id="cart-total-div">
                <p>Total items: <span id="total-items">0</span></p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const cartButton = document.getElementById('cartButton');
            const cartModal = document.getElementById('cartModal');
            const closeCartButton = document.getElementById('closeCartButton');
            const cartNumber = document.getElementById('cart-number');
            const cartItems = document.getElementById('cart-items');
            const totalItems = document.getElementById('total-items');
            const cartData = [
                { id: 1, product: 'Product 1', quantity: 3 },
                { id: 2, product: 'Product 2', quantity: 1 },
                { id: 3, product: 'Product 3', quantity: 2 }
            ];

            // Function to update cart content
            function updateCart() {
                let total = 0;
                cartItems.innerHTML = '';
                cartData.forEach(item => {
                    total += item.quantity;
                    cartItems.innerHTML += `<p>${item.product} - Quantity: ${item.quantity}</p>`;
                });
                totalItems.textContent = total;
                cartNumber.textContent = total;
            }

            // Show the cart modal when the cart button is clicked
            cartButton.addEventListener('click', function () {
                cartModal.classList.add('show');
            });

            // Close the cart modal when the close button is clicked
            closeCartButton.addEventListener('click', function () {
                cartModal.classList.remove('show');
            });

            // Close the cart modal when clicking outside the modal
            window.addEventListener('click', function (event) {
                if (event.target === cartModal) {
                    cartModal.classList.remove('show');
                }
            });

            // Initial cart update
            updateCart();
        });
    </script>

    <!-- Bootstrap JS (for dropdown functionality if needed) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
