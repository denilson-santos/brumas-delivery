$(function () {
    $('#plateNotAdded button#cleanCartAndAdd').on('click', function() {
        cleanCart();

        cart = loadCart();

        $('#showPlate button#addCart').click();

        $('.cart-quantity').text(cart.quantity);
        $('.cart-price').text(formatter.format(cart.total/100)); 
    });
});