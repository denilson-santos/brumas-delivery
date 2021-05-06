// Create our number formatter.
formatter = new Intl.NumberFormat('pt-BR', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2 
});

var cart = loadCart();

$('.phone-mask').mask('(00) 0000-0000');
$('.cell-phone-mask').mask('(00) 0 0000-0000');

$('.cart-quantity').text(cart.quantity);
$('.cart-price').text(formatter.format(cart.total/100));

$('.show-tooltip').tooltip();

function addCart(plateId, restaurantId, comments, complementsChecked, itemsChecked, plateTotalPrice) {
  const { items, quantity } = cart;

  items.push({
    id: items.length ? items[items.length - 1].id + 1 : 1,
    plate_id: plateId,
    restaurant_id: restaurantId,
    comments: comments,
    plate_total_price: plateTotalPrice,
    ...(complementsChecked.length && { plate_complements: complementsChecked }),
    ...(itemsChecked.length && { plate_items: itemsChecked })
  });

  const total = items.reduce((totalItems, item) => {
    return totalItems + item.plate_total_price;
  }, 0);

  cart.quantity = items.length;
  cart.total = total;

  localStorage.setItem('cart', JSON.stringify(cart));
}

function removeCart(id) {
  cart.items = cart.items.filter((item) => item.id !== id);
  updateCart(cart.items);
}

function cleanCart() {
  localStorage.removeItem('cart');
}

function updateCart(cartItems) {  
  cart.items = cartItems;

  const total = cart.items.reduce((totalItems, item) => {
    return totalItems + item.plate_total_price;
  }, 0);

  cart.quantity = cart.items.length;
  cart.total = total;

  localStorage.setItem('cart', JSON.stringify(cart));
}

function loadCart() {
  let storeCart = JSON.parse(localStorage.getItem('cart'));

  if (!storeCart || !Object.keys(storeCart).length)
    storeCart = {
      items: [],
      total: 0,
      quantity: 0,
    };

  return storeCart;
}

function getCart() {
  return cart;
}