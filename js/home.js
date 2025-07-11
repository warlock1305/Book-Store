$(document).ready(function () {

    $.ajaxSetup({
        beforeSend: function (xhr) {
            const token = localStorage.getItem('token');
            if (token) {
                console.log('Setting Authentication header with token:', token);
                xhr.setRequestHeader('Authentication', token);
            }
        }
    });

    const navbar = $('#navbar-container');
    const token = localStorage.getItem('token');

    let role = null;

    if (token) {
        try {
            const decoded = jwt_decode(token);
            role = decoded.user.role || null;
            console.log('Decoded token:', decoded);
            console.log('User role:', role);
        } catch (err) {
            console.error('Error decoding token:', err);
        }
    }

    
    if (token) {
      navbar.html(`
        <li class="nav-item mx-2"><a class="nav-link" href="account.html">Account</a></li>
        <li class="nav-item mx-2"><a class="nav-link" href="my_orders.html">My Orders</a></li>
        <li class="nav-item mx-2"><a class="nav-link" href="cart.html">Cart</a></li>
        <li class="nav-item mx-2"><a class="nav-link" href="#" id="logoutBtn">Logout</a></li>
      `);
        
      // Add logout click handler
      $('#logoutBtn').on('click', function(e) {
        e.preventDefault();
        localStorage.removeItem('token');
        // Optional: redirect to login or homepage
        window.location.href = 'login.html';
      });
    
    } else {
      navbar.html(`
        <li class="nav-item mx-2"><a class="nav-link" href="register.html">Sign Up</a></li>
        <li class="nav-item mx-2"><a class="nav-link" href="login.html">Log In</a></li>
        <li class="nav-item mx-2"><a class="nav-link" href="cart.html">Cart</a></li>
      `);
    }

    // if(role == 'admin'){
    //     navbar.html(`
    //     <li class="nav-item mx-2"><a class="nav-link" href="admin.html">Admin</a></li>
    //     <li class="nav-item mx-2"><a class="nav-link" href="account.html">Account</a></li>
    //     <li class="nav-item mx-2"><a class="nav-link" href="my_orders.html">My Orders</a></li>
    //     <li class="nav-item mx-2"><a class="nav-link" href="cart.html">Cart</a></li>
    //     <li class="nav-item mx-2"><a class="nav-link" href="#" id="logoutBtn">Logout</a></li>
    //   `);
    // }

    let currentGenre = 'all';
    let currentSort = 'popularity';
    let isLoading = false;

    const $bookList = $('#book-list');
    const $genreFilter = $('#genre-filter');
    const $sortBy = $('#sort-by');
    const $applyFilters = $('#apply-filters');

    fetchBooks();

    $applyFilters.on('click', applyFiltersAndSorting);
    $sortBy.on('change', applyFiltersAndSorting);

    function applyFiltersAndSorting() {
        currentGenre = $genreFilter.val();
        currentSort = $sortBy.val();
        fetchBooks();
    }

    function fetchBooks() {
        if (isLoading) return;
        
        isLoading = true;
        showLoading();
        
        let apiUrl = 'http://localhost/Book%20store/api/books';
        
        if (currentGenre !== 'all') {
            // apiUrl = `http://localhost/Book%20store/api/books/genre/${encodeURIComponent(currentGenre)}`;
            if (currentGenre == 'fiction') {
                apiUrl = 'http://localhost/Book%20store/api/books/genre/G002';

            }
        }

        $.ajax({
            url: apiUrl,
            method: 'GET',
            dataType: 'json',
            success: function(books) {
                // Apply sorting to the received books
                const sortedBooks = sortBooks(books);
                renderBooks(sortedBooks);
            },
            error: function(xhr, status, error) {
                console.error("Error fetching books:", error);
                showError("Failed to load books. Please try again later.");
            },
            complete: function() {
                isLoading = false;
                hideLoading();
            }
        });
    }

    function sortBooks(books) {
        switch(currentSort) {
            case 'rating':
                return [...books].sort((a, b) => (b.rating || 0) - (a.rating || 0));
            case 'newest':
                return [...books].sort((a, b) => new Date(b.published_date || 0) - new Date(a.published_date || 0));
            case 'price-low':
                return [...books].sort((a, b) => (a.price || 0) - (b.price || 0));
            case 'price-high':
                return [...books].sort((a, b) => (b.price || 0) - (a.price || 0));
            case 'popularity':
            default:
                return [...books].sort((a, b) => (b.popularity || 0) - (a.popularity || 0));
        }
    }

    function renderBooks(books) {
        $bookList.empty();

        if (books.length === 0) {
            $bookList.html(`
                <div class="col-12 text-center py-5">
                    <h4>No books found matching your criteria</h4>
                    <button class="btn btn-primary mt-3" onclick="resetFilters()">Reset Filters</button>
                </div>
            `);
            return;
        }

        books.forEach(book => {
            const ratingDisplay = book.rating ? `${book.rating.toFixed(1)} ★` : 'Not rated yet';
            console.log('Rendering book:', book);

            const bookItem = $(`
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4 book-item" data-book-id="${book.bookID}">
                    <div class="card h-100 clickable-card">
                        <div class="image-wrapper" style="height: 200px; overflow: hidden;">
                            <img src="${book.image || '/assets/images/book-placeholder.jpg'}" 
                                 class="card-img-top h-100 object-fit-cover" 
                                 alt="${book.title}"
                                 onerror="this.src='/assets/images/book-placeholder.jpg'">
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">${book.title}</h5>
                            <p class="card-text text-muted">by ${book.author || 'Unknown Author'}</p>
                            <p class="rating-text text-warning mb-2">${ratingDisplay}</p>
                            <div class="mt-auto">
                                <p class="price h5">$${(book.price || 0).toFixed(2)}</p>
                                <button class="btn btn-primary btn-block btn-add-to-cart" 
                                        data-book-id="${book.bookID}">
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `);

            $bookList.append(bookItem);
        });
    }


    function showLoading() {
        $bookList.html(`
            <div class="col-12 text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <p>Loading books...</p>
            </div>
        `);
    }

    function hideLoading() {
    }

    function showError(message) {
        $bookList.html(`
            <div class="col-12 text-center py-5">
                <div class="alert alert-danger">
                    ${message}
                </div>
                <button class="btn btn-primary" onclick="fetchBooks()">
                    Try Again
                </button>
            </div>
        `);
    }

    function slugify(text) {
        return text.toString().toLowerCase()
            .replace(/\s+/g, '-')
            .replace(/[^\w\-]+/g, '')
            .replace(/\-\-+/g, '-')
            .replace(/^-+/, '')
            .replace(/-+$/, '');
    }

    window.resetFilters = function() {
        $genreFilter.val('all');
        $sortBy.val('popularity');
        currentGenre = 'all';
        currentSort = 'popularity';
        fetchBooks();
    };

    function addToCart(book) {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        
        const existingIndex = cart.findIndex(item => item.id === book.id);
        
        if (existingIndex >= 0) {
            cart[existingIndex].quantity = (cart[existingIndex].quantity || 1) + 1;
        } else {
            book.quantity = 1;
            cart.push(book);
        }
        
        localStorage.setItem('cart', JSON.stringify(cart));
    }

    function showCartNotification(bookTitle) {
        const notification = $(`
            <div class="cart-notification">
                <span>${bookTitle} added to cart!</span>
            </div>
        `);
        
        $('body').append(notification);
        notification.fadeIn(300).delay(2000).fadeOut(300, function() {
            $(this).remove();
        });
    }

    // Navigate to book page when card is clicked
    $(document).on('click', '.book-item', function(e) {
        if ($(e.target).closest('.btn-add-to-cart').length) return;

        const bookId = $(this).data('book-id');
        console.log('Navigating to book page for ID:', bookId);
        window.location.href = `templates/book_page.html?id=${bookId}`;
    });


    // Add to cart
    $(document).on('click', '.btn-add-to-cart', function(e) {
        e.stopPropagation(); // prevent triggering the card click

        const book = $(this).closest('.book-item').data('book');
        if (!book) {
            console.warn('Book data not found.');
            return;
        }

        addToCart(book);
        showCartNotification(book.title);
    });

});