// Create our number formatter.
formatter = new Intl.NumberFormat('pt-BR', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2 
});

$('.phone-mask').mask('(00) 0000-0000');
$('.cell-phone-mask').mask('(00) 0 0000-0000');

$('.cart-quantity').text(cart.quantity);
$('.cart-price').text(formatter.format(cart.total/100));

function parseBrlToFloat(brl) {
    return parseFloat(brl.replace('.', '').replace(',','.'));
}
