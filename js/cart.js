$(document).ready(function() {
    // Load cart items from localStorage
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const $cartContainer = $('#cart-items-container'); // Fixed: Use the specific container ID
    let totalPrice = 0;

    // Render cart items
    if (cart.length === 0) {
        showEmptyCart();
    } else {
        renderCartItems(cart);
        updateTotalPrice();
    }

    // Event delegation for dynamic elements
    $(document).on('change', '.quantity-input', function() {
        const bookId = $(this).closest('.cart-item').data('book-id');
        const newQuantity = parseInt($(this).val()) || 1;
        updateCartItemQuantity(bookId, newQuantity);
    });

    $(document).on('click', '.btn-remove', function() {
        const bookId = $(this).closest('.cart-item').data('book-id');
        removeFromCart(bookId);
    });

    $('.btn-checkout').on('click', function() {
        if (cart.length > 0) {
            alert('Proceeding to checkout!');
            // window.location.href = 'checkout.html'; // Uncomment for actual checkout
        }
    });

    function showEmptyCart() {
        $cartContainer.html(`
            <div class="col-12 text-center py-5">
                <h4>Your cart is empty</h4>
                <p>Start shopping to add items to your cart</p>
                <a href="home.html" class="btn btn-primary">Browse Books</a>
            </div>
        `);
        $('.total-section, .checkout-section').hide();
    }

    function renderCartItems(cartItems) {
        $cartContainer.empty();
        totalPrice = 0; // Reset total before recalculating
        
        cartItems.forEach(item => {
            const ratingStars = generateRatingStars(item.rating);
            const itemTotal = item.price * (item.quantity || 1);
            totalPrice += itemTotal;
            
            const cartItem = $(`
                <div class="col-sm-6 col-md-4 col-lg-3 mb-4 cart-item" data-book-id="${item.id}">
                    <div class="card h-100">
                        <div class="image-wrapper" style="height: 200px; overflow: hidden;">
                            <img src="${item.image || 'assets/images/book-placeholder.jpg'}" 
                                 class="card-img-top h-100 object-fit-cover" 
                                 alt="${item.title}"
                                 onerror="this.src='assets/images/book-placeholder.jpg'">
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">${item.title}</h5>
                            <p class="card-text">by <strong>${item.author || 'Unknown Author'}</strong></p>
                            <p class="price">Price: <strong>$${item.price.toFixed(2)}</strong></p>
                            <p class="mb-2">Rating: ${ratingStars} (${item.rating?.toFixed(1) || 'N/A'}/5)</p>
                            <div class="mt-auto d-flex align-items-center">
                                <button class="btn btn-warning btn-sm btn-remove mr-2">Remove</button>
                                <input type="number" class="form-control form-control-sm quantity-input" 
                                       value="${item.quantity || 1}" min="1" style="width: 70px;">
                            </div>
                            <div class="mt-2 text-right">
                                <small>Subtotal: $${itemTotal.toFixed(2)}</small>
                            </div>
                        </div>
                    </div>
                </div>
            `);
            
            $cartContainer.append(cartItem);
        });
        
        updateTotalPrice();
    }

    function updateCartItemQuantity(bookId, newQuantity) {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        const itemIndex = cart.findIndex(item => item.id === bookId);
        
        if (itemIndex >= 0) {
            cart[itemIndex].quantity = newQuantity > 0 ? newQuantity : 1;
            localStorage.setItem('cart', JSON.stringify(cart));
            renderCartItems(cart); // Re-render to update all prices
        }
    }

    function removeFromCart(bookId) {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        cart = cart.filter(item => item.id !== bookId);
        localStorage.setItem('cart', JSON.stringify(cart));
        
        if (cart.length === 0) {
            showEmptyCart();
        } else {
            renderCartItems(cart);
        }
    }

    function updateTotalPrice() {
        $('.total-section h4').last().text(`$${totalPrice.toFixed(2)}`);
    }

    function generateRatingStars(rating) {
        if (!rating) return '<span class="text-muted">Not rated</span>';
        
        const fullStars = Math.floor(rating);
        const hasHalfStar = rating % 1 >= 0.5;
        let stars = '';
        
        for (let i = 1; i <= 5; i++) {
            if (i <= fullStars) {
                stars += '<span class="star-filled">★</span>';
            } else if (i === fullStars + 1 && hasHalfStar) {
                stars += '<span class="star-half">½</span>';
            } else {
                stars += '<span class="star-empty">☆</span>';
            }
        }
        
        return stars;
    }
});