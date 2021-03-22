// Create our number formatter.
formatter = new Intl.NumberFormat('pt-BR', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2 
});

$('.cart-quantity').text(cart.quantity);
$('.cart-price').text(formatter.format(cart.total/100));

function parseBrlToFloat(brl) {
    return parseFloat(brl.replace('.', '').replace(',','.'));
}
