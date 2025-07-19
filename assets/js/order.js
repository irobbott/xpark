document.addEventListener('DOMContentLoaded', () => {
    loadCart();
    updateCartCount();
    document.getElementById('homeService').addEventListener('change', renderCart);
});

function addToCart(button) {
    const card = button.closest('.card');
    const itemName = card.getAttribute('data-name');
    const itemPrice = parseFloat(card.getAttribute('data-price')); // Ensure price is treated as a number

    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    const existingItem = cart.find(item => item.name === itemName);

    if (!existingItem) {
        cart.push({ name: itemName, price: itemPrice, quantity: 1 });
        localStorage.setItem('cart', JSON.stringify(cart));
        alert('Item added to cart');
    } else {
        alert('Item already in cart');
    }

    updateCartCount();
    renderCart();
}

function renderCart() {
    const cartItems = document.getElementById('cart-items');
    cartItems.innerHTML = '';
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const homeServiceCheckbox = document.getElementById('homeService');

    // Show or hide the checkout form based on cart length
    const checkoutForm = document.querySelector('.checkout-form');
    if (cart.length > 0) {
        checkoutForm.style.display = 'block';
    } else {
        checkoutForm.style.display = 'none';
    }

    let totalAmount = 0;

    if (cart.length === 0) {
        // Display "Your cart is empty" message
        cartItems.innerHTML = '<p>Your cart is empty</p>';
    } else {
        cart.forEach((item, index) => {
            const cartItem = document.createElement('div');
            cartItem.classList.add('ci');
            cartItem.innerHTML = `
                <div class="row cc" style="border-bottom: 1px solid #4d4d4d; margin-bottom: 20px;">
                    <div class="col-8">
                        <p class="fw-bold">${item.name}</p>
                        <p>₦${item.price}</p>
                    </div>
                    <div class="col-4">
                        <button onclick="removeFromCart(${index})" style="background: none; border: none;">
                            <i class="fa-sharp fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            cartItems.appendChild(cartItem);

            totalAmount += item.price * item.quantity;
        });

        // Add home service cost if checkbox is checked
        if (homeServiceCheckbox.checked) {
            totalAmount += 15000; // Add the home service cost
        }

        document.getElementById('total-amount').textContent = `Total: ₦${totalAmount}`;
        updateCartCount();
    }

    // Ensure the "Clear Cart" and "Checkout" buttons are always visible
    const clearCartButton = document.getElementById('clear-cart');
    const totAmt = document.getElementById('total-amount');
    const hs1 = document.getElementById('hs1');
    clearCartButton.style.display = cart.length > 0 ? 'block' : 'none';
    totAmt.style.display = cart.length > 0 ? 'block' : 'none';
    hs1.style.display = cart.length > 0 ? 'block' : 'none';
}

function removeFromCart(index) {
    let cart = JSON.parse(localStorage.getItem('cart'));
    cart.splice(index, 1);
    localStorage.setItem('cart', JSON.stringify(cart));
    renderCart();
}

function clearCart() {
    localStorage.removeItem('cart');
    renderCart();
}

function loadCart() {
    renderCart();
}

// document.getElementById('checkout').addEventListener('click', () => {
//     document.getElementById('checkout-form').style.display = 'block';
//     window.scrollTo(0, document.body.scrollHeight);
// });

function submitOrder(event) {
    event.preventDefault();

    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const number = document.getElementById('number').value;
    const date = document.getElementById('date').value;
    const time = document.getElementById('time').value;
    const homeService = "No";
    const trx_ref = document.getElementById('trx_ref').value;
    // const homeService = document.getElementById('homeService').checked ? "Yes" : "No";

    const order = {
        cart,
        customer: {
            name,
            email,
            number,
            date,
            time,
            trx_ref
        },
        home_service: homeService
    };

    // Submit the order to the server
    fetch('../../app/submit-order.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(order)
    })
    .then(response => response.json())
    .then(data => {
        if (data.message) {
            alert("Processing... We'll redirect you in a moment");
            // alert('Order submitted successfully');
            // Clear the cart and form
            clearCart();
            document.getElementById('checkoutForm').reset();
            const checkoutFormElement = document.getElementById('checkout-form');
            if (checkoutFormElement) {
                checkoutFormElement.style.display = 'none';
            }
            // Redirect after a delay to allow the alert to be closed
            setTimeout(() => {
                window.location.href = '../booking-confirmation/?trx_ref=' + trx_ref; // Change this to your desired page
            }, 100); // Adjust the delay as needed
        } else {
            alert('Failed to submit order: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to submit order');
    });
}

function updateCartCount() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    document.getElementById('cart-count').textContent = cart.length;
}