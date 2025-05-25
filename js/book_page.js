$(document).ready(function () {
    const bookId = getQueryParam('id');
    if (!bookId) {
        $('#book-details-container').html('<div class="alert alert-danger">Book ID not specified.</div>');
        return;
    }

    $.ajax({
        url: `http://localhost/Book%20store/api/books/${bookId}`,
        method: 'GET',
        dataType: 'json',
        success: function (book) {
            renderBookDetails(book);
        },
        error: function () {
            $('#book-details-container').html('<div class="alert alert-danger">Failed to load book details.</div>');
        }
    });

    function getQueryParam(param) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    }

    function renderBookDetails(book) {
        const ratingStars = getRatingStars(book.rating || 0);

        const html = `
            <div class="book-detail d-flex flex-wrap">
                <div class="book-image col-md-4">
                    <img src="${book.image || '../assets/images/book-placeholder.jpg'}" class="img-fluid" alt="${book.title}">
                </div>
                <div class="book-info col-md-8">
                    <h1 class="book-title">${book.title}</h1>
                    <p class="book-author">by <strong>${book.author}</strong></p>
                    <p class="book-genre">Genre: ${book.genre || 'N/A'}</p>
                    <p class="book-page-count"><strong>Pages:</strong> ${book.page_count || 'Unknown'}</p>
                    <p class="book-language"><strong>Language:</strong> ${book.language || 'English'}</p>
                    <p class="book-format"><strong>Format:</strong> ${book.format || 'Paperback'}</p>
                    <p class="book-rating">Rating: ${ratingStars} (${book.rating?.toFixed(1) || 'N/A'}/5)</p>
                    <p class="book-price">Price: <strong>$${(book.price || 0).toFixed(2)}</strong></p>
                    <button class="add-to-cart btn btn-primary" data-book-id="${book.id}">Add to Cart</button>
                </div>
            </div>

            <div class="description mt-5">
                <h4>Description</h4>
                <p>${book.description || 'No description available.'}</p>
            </div>
        `;
        $('#book-details-container').html(html);
    }

    function getRatingStars(rating) {
        let stars = '';
        const fullStars = Math.floor(rating);
        const unfilled = 5 - fullStars;
        for (let i = 0; i < fullStars; i++) stars += '<span class="filled">★</span>';
        for (let i = 0; i < unfilled; i++) stars += '<span class="unfilled">☆</span>';
        return `<span class="rating">${stars}</span>`;
    }
});
