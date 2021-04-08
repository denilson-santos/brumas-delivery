$(function () {
    $('#removeCart button.remove').on('click', function() {
        $(this).attr('disabled', true);

        removeCart(parseInt($('span.deleting').closest('.cart-item').attr('cart-item')));
        
        location.reload();
    });

    $('#removeCart').on('hidden.bs.modal', function() {
        $('.cart-item span.delete').removeClass('deleting');
    });
});